<?php

namespace App\Admin\Controllers;

use App\Models\QuestionType;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;

class QuestionTypeCrudController extends Controller
{
    // use HasResourceActions;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Content $content)
    {
        return $content
            ->header(trans('admin.index'))
            ->description(trans('admin.description'))
            ->body($this->grid());
    }


    protected function grid()
    {
        $grid = new Grid(new QuestionType);

        $grid->id(trans('admin.id'));
        $grid->name(trans('admin.name'));
        $grid->code(trans('admin.code'));
        $grid->type(trans('admin.type'));
        $grid->description(__('admin.description'));

        return $grid;
    }
    

    public function api(){
        $data = QuestionType::select('id', 'name as text')->get();
        return response()->json($data);
    }
}
