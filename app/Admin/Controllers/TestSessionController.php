<?php

namespace App\Admin\Controllers;

use App\Models\TestSession;
use App\Models\QuestionSession;

use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;

class TestSessionController extends Controller
{
    use HasResourceActions;

    /**
     * Index interface.
     *
     * @param Content $content
     * @return Content
     */
    public function index(Content $content)
    {
        return $content
            ->header(__('Test Sessions'))
            ->body($this->grid());
    }

    /**
     * Show interface.
     *
     * @param mixed $id
     * @param Content $content
     * @return Content
     */
    public function show($id, Content $content)
    {
        return $content
            ->header(trans('admin.detail'))
            ->description(trans('admin.description'))
            ->body($this->detail($id));
    }

    /**
     * Edit interface.
     *
     * @param mixed $id
     * @param Content $content
     * @return Content
     */
    public function edit($id, Content $content)
    {
        return $content
            ->header(trans('admin.edit'))
            ->description(trans('admin.description'))
            ->body($this->form()->edit($id));
    }

    /**
     * Create interface.
     *
     * @param Content $content
     * @return Content
     */
    public function create(Content $content)
    {
        return $content
            ->header(trans('admin.create'))
            ->description(trans('admin.description'))
            ->body($this->form());
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new TestSession);

        $grid->filter(function($filter){
            $filter->disableIdFilter();
            $filter->like('user_id', 'User ID');
            $filter->like('test_id', 'Test ID');
        });

        $grid->disableCreation();
        $grid->actions(function ($actions) {
            $actions->disableEdit();
        });

        // $test_id = request('test_id');
        // if ($test_id) {
        //     $grid->model()->where('test_id', '=', $test_id);
        // }
        
        $grid->column('id', 'ID')->display(function ($id, $column) {
            $link = '/admin/question_sessions?test_session_id='.$this->id;
            return "<a href=$link>$this->id</a>";
        });

        $grid->code('code');
        $grid->column('user.name', 'User');
        $grid->column('test.name', 'Test');
        $grid->column('question.id', 'QID');

        $grid->start_at('start_at');
        $grid->end_at('end_at');
        $grid->completed_at('completed_at');
        $grid->duration('Duration');
        $grid->result('result');
        
        $grid->status('status');


        return $grid;
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(TestSession::findOrFail($id));

        $show->id('ID');
        $show->user_id('user_id');
        $show->test_id('test_id');
        $show->code('code');
        $show->result('result');
        $show->current_question('current_question');
        $show->start_at('start_at');
        $show->end_at('end_at');
        $show->total_time_taken('total_time_taken');
        $show->completed_at('completed_at');
        $show->status('status');
        $show->created_at(trans('admin.created_at'));
        $show->updated_at(trans('admin.updated_at'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new TestSession);

        $form->display('ID');
        $form->text('user_id', 'user_id');
        $form->text('test_id', 'test_id');
        $form->text('code', 'code');
        $form->text('result', 'result');
        $form->text('current_question', 'current_question');
        $form->text('start_at', 'start_at');
        $form->text('end_at', 'end_at');
        $form->text('total_time_taken', 'total_time_taken');
        $form->text('completed_at', 'completed_at');
        $form->text('status', 'status');
        $form->display(trans('admin.created_at'));
        $form->display(trans('admin.updated_at'));

        return $form;
    }

}
