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
        $grid->column('category.name',  __('Category'))->label('info');
        $grid->column('test_type.name',  __('Type'));

        $grid->column('id', 'Manage')->display(function($id, $column) {
            return "
            <a href='test_scores?test_id={$id}'>Scores</a> / 
            <a href='questions?test_id={$id}'>Questions</a> /
            <a href='test_sessions?test_id={$id}'>Sessions</a>
            ";
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
        $form->column(1/3, function ($form) {
            $form->display('id');
            $form->select('category_id', __('Category'))->options('/admin/category/api')->required();
            $form->select('test_type_id', __('Type'))->options('/admin/test_type/api')->required();
            $form->text('name', __('Name'))->required();
            // $form->text('code', 'code');
            $form->text('slug', __('Slug'));
            $form->textarea('short_description', __('Short Description'));  
        });
        $form->column(2/3, function ($form) {
            $form->image('image', __('Image'));
            $form->summernote('description', __('Description'));
            $form->fieldset('Addons', function (Form $form) {
                $form->url('compute_api', __('API'));
                $form->switch('compute_api_enabled', __('API Enabled'));
                $form->php('compute_script', __('Script'))->height(100);
                $form->switch('compute_script_enabled', __('Script Enabled'));
                $form->textarea('template', __('Template'));
                $form->switch('template_enabled', __('Template Enabled'));
            });
        });

        // $type = $id ? Factor::where('id', $id)->value('type') : 1;
        // $form->select('type','Type')->options(['1'=>'For Question','2'=>'For Answer'])->default($type);

        return $form;
    }

    
    public function api(){
        $data = Test::select('id', 'name as text')->get();
        return response()->json($data);
    }
}
