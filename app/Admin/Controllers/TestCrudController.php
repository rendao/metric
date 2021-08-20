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

        $category_id = request('category_id');
        if ($category_id) {
            $grid->model()->where('category_id', '=', $category_id);
        }

        $grid->column('id', __('ID'));
        $grid->code('code', __('Code'));
        $grid->slug('slug', __('Slug'));
        $grid->name('name', __('Name'))->editable();
        $grid->column('total_questions', __('Questions'));
        $grid->column('duration', __('Duration'));
        // $grid->column('image', __('Image'))->image('', 48, 48);
        $grid->column('category.name',  __('Category'))->label('info');
        $grid->column('test_type.name',  __('Type'));
        $grid->column('test_template.name',  __('Template'));

        $grid->manage('Manage');
        $grid->rows(function($row, $manage) {
            $id = $row->column('id');
            $content = "<a href='test_scores?test_id={$id}'>Scores</a> / 
            <a href='questions?test_id={$id}'>Questions</a> /
            <a href='test_sessions?test_id={$id}'>Sessions</a>";
            $row->column('manage', $content);
            
        });

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
        $show->description('description')->setEscape(false);
        $show->field('category.name',  __('Category'))->label('info');
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
        $form->column(1/2, function ($form) {
            $form->display('id');
            $form->select('category_id', __('Category'))->options('/admin/category/api')->required();
            $form->select('test_type_id', __('Type'))->options('/admin/test_type/api')->required();
            $form->text('name', __('Name'))->required();
            $form->number('total_questions', 'Total Questions');
            $form->number('duration', 'Duration');
            $form->text('slug', __('Slug'));
            $form->textarea('short_description', __('Short Description'));  
            $form->summernote('description', __('Description'));
        });
        $form->column(1/2, function ($form) {
            $form->image('image', __('Image'));
            $form->fieldset('Addons', function (Form $form) {
                $form->url('compute_api', __('API'));
                $form->switch('compute_api_enabled', __('API Enabled'));
                $form->select('template_id', __('Template'))->options('/admin/test_template/api');
                $form->switch('template_enabled', __('Template Enabled'));
            });
        });

        return $form;
    }

    
    public function api(){
        $data = Test::select('id', 'name as text')->get();
        return response()->json($data);
    }
}
