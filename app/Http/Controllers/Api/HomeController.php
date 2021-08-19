<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Test;

class HomeController extends Controller
{

    public function index()
    {
        $tests = Test::where('is_active', '=', 1)->orderBy('id', 'desc')->paginate(10);
        $data = [
            'data' => $tests
        ];
        return response()->json($data, 200);
    }
}
