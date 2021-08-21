<?php

namespace App\Admin\Controllers;

use App\Models\TestScore;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;

class TestScoreCrudController extends Controller
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
            ->header(trans('admin.index'))
            ->description(trans('admin.description'))
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
        
        $grid = new Grid(new TestScore);

        $grid->filter(function($filter){
            $filter->disableIdFilter();
            $filter->column(1/2, function ($filter) {
                $filter->equal('test_id', 'Test ID');
            });
        });

        $grid->actions(function ($actions) {
            $actions->disableView();
        });

        $grid->id('ID');
        // $grid->code('Code');
        $grid->column('test.id', __('Test ID'));
        $grid->trait('trait');
        $grid->start('start');
        $grid->end('end');
        $grid->name('name');

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
        $show = new Show(TestScore::findOrFail($id));

        $show->id('ID');
        $show->code('code');
        $show->test_id('test_id');
        $show->trait('trait');
        $show->start('start');
        $show->end('end');
        $show->response('response');
        $show->created_at(trans('admin.created_at'));
        $show->updated_at(trans('admin.updated_at'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form($id='')
    {
        $form = new Form(new TestScore);

        if ($id) {
            $form->display('id');
        }
        $form->select('test_id', __('admin.test'))->options('/admin/test/api')->required();
        $form->text('trait', 'Trait');
        $form->number('start', 'Start');
        $form->number('end', 'End');
        $form->text('name', 'Name');
        $form->textarea('response', 'response');
        $form->image('image', 'Image');

        return $form;
    }
}
