<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\ApiResponser;


use App\Models\TestSession;
use App\Models\QuestionSession;

class ResultController extends Controller
{
    // get test result
    public function show($test_session_code)
    {
        $where = [
            'code' => $test_session_code,
            'user_id' => auth()->user()->id
        ];
        $result = TestSession::where($where)->firstOrFail();
        return response()->json($result, 200);
    }
       
}
