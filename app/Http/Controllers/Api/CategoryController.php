<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Category;
use App\Models\Test;

class CategoryController extends Controller
{
    
    public function index()
    {
        $categories = Category::where('is_active', '=', 1)->orderBy('id', 'desc')->get();
        $data = [
            'data' => $categories
        ];
        return response()->json($data, 200);
    }

    public function tests($id)
    {
        $where = array(
            'is_active' =>  1
        );

        if ($id) {
            $where['category_id'] = $id;
        }

        $tests = Test::where($where)->orderBy('id', 'desc')->paginate(10);

        $data = [
            'where' => $where,
            'code' => $id,
            'data' => $tests
        ];
        return response()->json($data, 200);
    }

}
