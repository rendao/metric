<?php

namespace App\Admin\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use App\Models\TestSession;
use App\Models\QuestionSession;
use App\Http\Controllers\Controller;

use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;

class QuestionSessionController extends Controller
{
    use HasResourceActions;

    /**
     * Index interface.
     *
     * @param Content $content
     * @return Content
     */
    public function index(TestSession $test_session, Content $content)
    {
        $test_session_id = $test_session->id;
        return $content
            ->header(trans('admin.index'))
            ->description(trans('admin.description'))
            ->body($this->grid($test_session_id));
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
    protected function grid($test_session_id)
    {

        $grid = new Grid(new QuestionSession);

        $grid->filter(function($filter){
            // 去掉默认的id过滤器
            $filter->disableIdFilter();
            $filter->like('user.name', 'User');
            $filter->equal('test_session_id', 'Test Session ID');
        });

        $grid->disableCreation();
        $grid->actions(function ($actions) {
            $actions->disableEdit();
        });

        $grid->column('user.name', 'User');
        $grid->column('test_session_id', 'Test Session');
        $grid->column('test.name', 'Test');
        $grid->column('question.question', 'Question');

        $grid->trait('trait');
        $grid->column('option', 'Option');
        $grid->duration('Duration');
        $grid->status('status');
        $grid->skipped('skipped');

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
        $show = new Show(QuestionSession::findOrFail($id));

        $show->id('ID');
        $show->test_id('test_id');
        $show->user_id('user_id');
        $show->trait('trait');
        $show->option('option');
        $show->test_session_id('test_session_id');
        $show->time_taken('time_taken');
        $show->status('status');
        $show->skipped('skipped');
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
        $form = new Form(new QuestionSession);

        $form->display('ID');
        $form->text('test_id', 'test_id');
        $form->text('user_id', 'user_id');
        $form->text('trait', 'trait');
        $form->text('option', 'option');
        $form->text('test_session_id', 'test_session_id');
        $form->text('time_taken', 'time_taken');
        $form->text('status', 'status');
        $form->text('skipped', 'skipped');
        $form->display(trans('admin.created_at'));
        $form->display(trans('admin.updated_at'));

        return $form;
    }
}
