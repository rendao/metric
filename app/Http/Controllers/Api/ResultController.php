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
    public function result(Request $request, TestSession $test_session)
    {
        $result = $test_session->where('user_id', auth()->user()->id)->result;
        return response()->json($result, 200);
    }
       
}
