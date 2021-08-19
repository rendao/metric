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
        $categories = Category::where('is_active', '=', 1)->orderBy('id', 'desc')->paginate(10);
        $data = [
            'data' => $categories
        ];
        return response()->json($data, 200);
    }

}
