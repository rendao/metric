<?php

namespace App\Admin\Controllers;

use App\Models\TestTemplate;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;

class TestTemplateCrudController extends Controller
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
        $grid = new Grid(new TestTemplate);

        $grid->id('ID');
        $grid->code('code');
        $grid->name('name');
        $grid->chart_type('chart_type');
        $grid->description('description');
        $grid->script('script');
        $grid->template('template');

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
        $show = new Show(TestTemplate::findOrFail($id));

        $show->id('ID');
        $show->code('code');
        $show->name('name');
        $show->image('image');
        $show->description('description');
        $show->chart_type('chart_type');
        $show->script('script');
        $show->template('template');
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
        $form = new Form(new TestTemplate);
        
        $id='';
        
        if($form->isEditing()){
            $id = request()->route()->parameters()['test_template'];
            $form->display('ID');
        }
        $form->text('name', 'name');
        $form->image('image', __('Image'));
        $form->text('description', 'description');
 
        $chart_type = $id ? TestTemplate::where('id', $id)->value('chart_type') : '';

        $chartTypes = [
            'chartjs-line'=>'ChartJs Line Chart',
            'chartjs-bar'=>'ChartJs Bar Chart',
            'chartjs-radar'=>'ChartJs Radar Chart',
        ];
        $form->select('chart_type','Chart Type')->options($chartTypes)->default($chart_type);

        $form->textarea('script', 'script');
        $form->textarea('template', 'template');

        return $form;
    }

    public function api(){
        $data = TestTemplate::select('id', 'name as text')->get();
        return response()->json($data);
    }
}
