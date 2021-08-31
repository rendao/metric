<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;

use App\Models\Category;

use App\Models\Test;
use App\Models\TestScore;
use App\Models\TestSession;
use App\Models\TestTemplate;

use App\Models\Question;
use App\Models\QuestionSession;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class TestController extends Controller
{
    use ApiResponser;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tests = Test::where('is_active', '=', 1)->orderBy('id', 'desc')->paginate(10);
        $data = [
            'data' => $tests
        ];
        return response()->json($data, 200);
    }

    /**
     * Display single resource.
     */

    public function show($code)
    {
        $where = array(
            'code' => $code,
            'is_active' => 1
        );
        $test= Test::where($where)->with(['category:id,code,name'])->firstOrFail();
        $data = [
            'data' => $test
        ];
        return response()->json($data, 200);
    }

    /**
     * init a test session.
     */
    public function start(Test $test)
    {
        $where = array(
            'test_id' => $test->id,
            'user_id' => auth()->user()->id,
            'status' => 'started'
        );
        $session = TestSession::where($where)->latest()->first();

        if ($session) {
            return $this->goto($test, $session->code);
        }  else {
            return $this->create($test);
        }
    }

    /**
     * create a new test session.
     */
    public function create(Test $test)
    {
        
        $now = Carbon::now();
        $questions = $test->questions()->with(['question_type:id,name,code'])->get();

        $session = TestSession::create([
            'test_id' => $test->id,
            'user_id' => auth()->user()->id,
            'start_at' => $now->toDateTimeString(),
            'end_at' => $now->addSeconds($test->duration)->toDateTimeString(),
            'status' => 'started'
        ]);

        if ($session) {
            return $this->goto($test, $session->code);
        }
        return $this->error('Target not created.', 200);
    }

    /**
     * jump to a exist test session.
     */
    public function goto(Test $test, $session_code)
    {
        $session = TestSession::where(['code' => $session_code, 'user_id' => auth()->user()->id])->firstOrFail();

        // TODO: if completed, update and redirect to finish.
        $questions = $test->questions()->with(['question_type:id,name,code', 'question_session' => function($query) {
            $query->where('user_id', auth()->user()->id)->select(['question_id', 'status', 'duration'])->latest()->first();
        }])->get()->makeHidden(['id']);

        $data = [
            'test' => $test->only('code', 'slug', 'name', 'total', 'duration'),
            'questions' => $questions,
            'session_code' => $session->code,
            'answered_count' => $session->questions()->wherePivot('status', '=', 'answered')->count()
        ];
        return response()->json($data, 200);
    }

    public function answer(Request $request, Test $test)
    {
        // query question
        $question = Question::where('code', '=', $request->question_code)->firstOrFail();

        // query test session.
        $session = TestSession::where('code', '=', $request->session_code)->firstOrFail();

        // create new question session.
        $option = $request->option;
        $question_save = QuestionSession::upsert(
           [
                'user_id' => auth()->user()->id,
                'test_id' => $test->id,
                'test_session_id' => $session->id,
                'question_id' => $question->id,
                'trait' => $request->trait,
                'option' => json_encode($option),
                'score' => $option['score'],
                'duration' => $request->duration,
                'status' => 'answered',
            ], 
            ['user_id', 'test_id', 'test_session_id', 'question_id'],
            ['option', 'score', 'duration']);

        // if new question session
        if($question_save == 1) {
            $session->update([
                'current_question_id' => $question->id,
                'count' => $session->count + 1,
            ]);
        }

        $question_session = QuestionSession::where('test_session_id', '=', $session->id)->get();

        // if test completed, when the last one of questions group submit.
        $is_finished = false;

        return $this->compute($session);

        if ($question->finish == true || $request->finish == true) {
            $session->update([
                'status' => 'completed',
                'completed_at' => $now->toDateTimeString(),
                'duration' => $question_session->sum('duration')
            ]);
            $is_finished = true;
        }

        $data = [
            'test' => $test->only('code', 'slug', 'name', 'total', 'duration'),
            'session' => $session,
            'answered_count' => $question_sessions_count,
            'is_finished' => $is_finished
        ];
        return $data;
    }
    

    public function compute($test_session)
    {
        
        // get test:
        $test = Test::findOrFail($test_session->test_id);

        $compute_type = $test->compute_type;
        $compute_api_enabled = $test->compute_api_enabled;
        $template_enabled = $test->template_enabled;

        /**
         * finsh and computed result. from api or template script.
         */
        // API
        if($compute_api_enabled) {

        }
        // template
        if ($template_enabled) {
            $test_template = TestTemplate::findOrFail($test->template_id);
        }

        // question sesssions stats
        $question_sessions = QuestionSession::where('test_session_id', $test_session->id);
        $duration = $question_sessions->sum('duration');
        $scores = $question_sessions->groupBy('trait')->selectRaw('sum(score) as score, trait')->pluck('score', 'trait');

        // match test scores
        $test_scores = TestScore::where('test_id', $test->id);

        // loop for result
        $matchs = [];
        foreach ($scores as $trait => $score) {
            /**
             * trait or start-end or trait+start-end
             */
            if ($compute_type == 'trait') {
                // trait only
                $test_score = $test_scores->where('code', $trait)->firstOrFail();
            } else {
                // trait + start-end
                $test_score = $test_scores->whereRaw('? between start and end',  $score)->get();
            }
            $match = [
                'trait' => $trait,
                'score' => $score,
                'match' => $test_score
            ];
            array_push($matchs, $match);
        }

        $result = [
            'duration' => $duration,
            'scores' => $scores,
            'matchs' => $matchs,
        ];

        /**
         * update this test session.
         */
        $test_session->update([
            'duration' => $duration,
            'result' => $result,
            'status' => 'completed',
            'completed_at' => Carbon::now()->toDateTimeString()
        ]);

        /**
         * response
         */
        $data = [
            'test' => $test->only('name', 'code', 'short_description'),
            'result' => $result
        ];

        return response()->json($data, 200);
    }

}
