<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
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
        $test= Test::where($where)->with(['category:id,code,name'])->withCount(['questions'])->firstOrFail();
        $data = [
            'data' => $test
        ];
        return response()->json($data, 200);
    }

    /**
     * Display question list belong to this test.
     */
    public function questions($code)
    {
        // $session = QuizSession::with('questions')->where('code', $session)->firstOrFail();

        $where = array(
            'code' => $code
        );
        $questions= Test::where($where)->with(['questions'])->withCount(['questions'])->firstOrFail();
        $data = [
            'data' => $questions
        ];
        return response()->json($data, 200);
    }

    /**
     * init a test session.
     */
    public function init(Test $test)
    {

        $sessions = $test->sessions()->where('status', '=', 'started');

        if ($sessions->count() > 0) {
            $session = $test->sessions()->where('user_id', auth()->user()->id)->latest()->first();
            $this->goto($test, $session->code);
        } else {
            return $this->start($test);
        }

        $data = [
            'data' => $sessions
        ];
        return response()->json($data, 200);

    }

    /**
     * start a new test session.
     */
    public function start(Test $test)
    {
        
        $now = Carbon::now();
        $questions = $test->questions()->with(['question_type:id,name,code'])->get();

        $session = TestSession::create([
            'test_id' => $test->id,
            'user_id' => auth()->user()->id,
            'start_at' => $now->toDateTimeString(),
            'end_at' => $now->addSeconds($test->total_time_taken)->toDateTimeString(),
            'status' => 'started'
        ]);

        $data = [
            'data' => $session
        ];
        return response()->json($data, 200);
    }

    /**
     * jump to a exist test session.
     */
    public function goto(Test $test, $session_code)
    {
        $session = TestSession::with('questions')->where('code', $session_code)->firstOrFail();

        $data = [
            'data' => $session
        ];
        return response()->json($data, 200);
    }
    // TODO: goto(), start(), answer(), finish().
   
}
