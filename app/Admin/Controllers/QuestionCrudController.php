<?php

namespace App\Admin\Controllers;

use App\Models\Question;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;

class QuestionCrudController extends Controller
{
    use HasResourceActions;

    protected $casts = [
        'options' => 'json',
    ];
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
        $grid = new Grid(new Question);

        $grid->id( __('admin.id'));
        $grid->code(__('admin.code'));
        $grid->position(__('admin.position'));
        $grid->trait(__('admin.trait'));
        $grid->question(__('admin.question'));
        $grid->column('test.id',  __('admin.test'))->label('info');
        $grid->column('question_type.name',  __('admin.type'));

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
        $show = new Show(Question::findOrFail($id));

        $show->id('ID');
        $show->question('Q');
        $show->code('code');
        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form($id='')
    {
        $form = new Form(new Question);
        $form->column(1/4, function ($form) {
            $form->text('question', __('admin.question'));
            $form->select('test_id', __('admin.category'))->options('/admin/test/api');
            $form->select('question_type_id', __('admin.type'))->options('/admin/question_type/api');
            $form->switch('skippable', __('skippable'));
            $form->number('position', __('position'));
            $form->text('trait');
            $form->image('image', __('Image'));
        });
        $form->column(3/4, function ($form) {
            $form->summernote('hint', __('admin.description'));
            $form->table('options', __('options'), function ($form) {
                $form->text('label');
                $form->text('value');
                $form->text('score');
                $form->text('trait');
                $form->image('image');
            });

        });


        return $form;
    }
}
