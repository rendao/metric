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
            ->header(__('admin.index'))
            ->description(__('admin.description'))
            ->body($this->grid());
    }


    protected function grid()
    {
        $grid = new Grid(new QuestionType);

        $grid->disableActions();
        $grid->disableCreation();

        // $grid->actions(function ($actions) {
        //     $actions->disableDelete();
        //     $actions->disableEdit();
        // });

        $grid->id(__('admin.id'));
        $grid->name(__('admin.name'));
        $grid->code(__('admin.code'))->label('info');
        $grid->type(__('admin.type'));
        $grid->description(__('admin.description'));

        return $grid;
    }
    

    public function api(){
        $data = QuestionType::select('id', 'name as text')->get();
        return response()->json($data);
    }
}
