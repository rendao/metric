<?php

namespace App\Admin\Controllers;

use App\Models\Test;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;

class TestCrudController extends Controller
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
        $grid = new Grid(new Test);

        $grid->id('id', __('ID'));
        $grid->code('code', __('Code'));
        $grid->slug('slug', __('Slug'));
        $grid->name('name', __('Name'));
        // $grid->column('image', __('Image'))->image('', 48, 48);
        // $grid->short_description('short_description');
        // $grid->description('description');
        $grid->column('category.name',  __('Category'))->label('info');
        $grid->column('test_type.name',  __('Type'));
        // $grid->created_at(trans('admin.created_at'));
        // $grid->updated_at(trans('admin.updated_at'));

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
        $show = new Show(Test::findOrFail($id));

        $show->id('ID');
        $show->name('name');
        $show->code('code');
        $show->slug('slug');
        $show->image('image');
        $show->short_description('short_description');
        $show->description('description');
        $show->category_id('category_id');
        $show->test_type_id('test_type_id');
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
        $form = new Form(new Test);

        // $form->display('ID');
        $form->select('category_id', __('Category'))->options('/admin/category/api');
        $form->select('test_type_id', __('Type'))->options('/admin/test_type/api');
        $form->text('name', __('Name'));
        // $form->text('code', 'code');
        $form->text('slug', __('Slug'));
        $form->image('image', __('Image'));
        $form->textarea('short_description', 'Short Description');
        $form->textarea('description', 'Description');
        $form->textarea('script', 'Script');
        $form->textarea('template', 'Template');
        // $form->text('category_id', 'category_id');
        // $form->text('test_type_id', 'test_type_id');
        // $form->display(trans('admin.created_at'));
        // $form->display(trans('admin.updated_at'));

        // $type = $id ? Factor::where('id', $id)->value('type') : 1;
        // $form->select('type','Type')->options(['1'=>'For Question','2'=>'For Answer'])->default($type);

        return $form;
    }
}