<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;

use App\Models\Test;
use App\Models\Category;
use App\Models\Question;
use App\Models\TestSession;
use App\Models\QuestionSession;

use Carbon\Carbon;
use Illuminate\Support\Str;

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
        $sessions = $test->sessions()->where('status', '=', 'started');

        if ($sessions->count() > 0) {
            $session = $test->sessions()->where('user_id', auth()->user()->id)->latest()->first();
            return $this->goto($test, $session->code);
        } else {
            return $this->create($test);
        }

        $data = [
            'data' => $sessions
        ];
        return response()->json($data, 200);

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
        $session = TestSession::where('code', $session_code)->firstOrFail();

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
       $question_save = QuestionSession::upsert([
            'user_id' => auth()->user()->id,
            'test_id' => $test->id,
            'test_session_id' => $session->id,
            'question_id' => $question->id,
            'trait' => $request->trait,
            'option' => json_encode($request->option),
            'duration' => $request->duration,
            'status' => 'answered',
            ], 
            ['user_id', 'test_id', 'test_session_id', 'question_id'],
            ['option', 'duration']);

        // if new question session
        if($question_save == 1) {
            $session->update([
                'current_question_id' => $question->id,
                'count' => $session->count + 1,
            ]);
        }

        $question_session = QuestionSession::where('test_session_id', '=', $session->id)->get();

        // if test completed, when the last one of questions group submit.
        if ($request->question_position == $test->total) {
            $session->update([
                'status' => 'completed',
                'completed_at' => $now->toDateTimeString(),
                'duration' => $question_session->sum('duration')
            ]);
            // TODO finsh and computed result. from api or template.
        }

        $data = [
            'test' => $test->only('code', 'slug', 'name', 'total', 'duration'),
            'session' => $session,
            'question_sessions' => $question_session
        ];
        return $data;
    }
    // TODO: goto(), start(), answer(), finish(), result(), thanks().
   
}
