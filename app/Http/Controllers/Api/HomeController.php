<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Test;

class HomeController extends Controller
{

    public function index()
    {
        $tests = Test::where('is_active', '=', 1)->with(['category:id,code,name'])->orderBy('id', 'desc')->paginate(10);

        $data = [
            'data' => $tests->makeHidden(['id', 'category.id'])
        ];

        return response()->json($data, 200);
    }
}
