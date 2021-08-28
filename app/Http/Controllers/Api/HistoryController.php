<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Test;
use App\Models\TestSession;

class HistoryController extends Controller
{
    public function index()
    {
        $test_sessions = TestSession::where('user_id', '=', auth()->user()->id)->with('test:id,code,name,image')->orderBy('id', 'desc')->paginate(10);

        $data = [
            'data' => $test_sessions->makeHidden(['id', 'category.id'])
        ];

        return response()->json($data, 200);
    }
}
