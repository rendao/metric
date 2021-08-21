<?php

namespace App\Admin\Controllers;

use App\Models\User;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;

class UserCrudController extends Controller
{
    use HasResourceActions;
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
    
    public function show($id, Content $content)
    {
        return $content
            ->header(__('admin.detail'))
            ->description(__('admin.description'))
            ->body($this->detail($id));
    }

    public function edit($id, Content $content)
    {
        return $content
            ->header(__('admin.edit'))
            ->description(__('admin.description'))
            ->body($this->form()->edit($id));
    }

    public function create(Content $content)
    {
        return $content
            ->header(__('admin.create'))
            ->description(__('admin.description'))
            ->body($this->form());
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new User);

        $grid->column('id', 'ID')->display(function ($id, $column) {
            $link = '/admin/test_sessions?user_id='.$this->id;
            return "<a href=$link>$this->id</a>";
        });
        $grid->column('name', __('Name'));
        $grid->column('email', __('Email'))->editable();
        $grid->column('email_verified_at',  __('Email Verified At'));
        $grid->column('last_login_at',  __('Last Login'));
        $grid->column('last_login_ip',  __('IP'));
        $grid->column('created_at',  __('Created'));

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
        $show = new Show(User::findOrFail($id));

        $show->id('ID');
        $show->name('Name');
        $show->email('Email');
        $show->email_verified_at('Verified');
        $show->column('last_login_at',  __('Last Login'));
        $show->column('last_login_ip',  __('IP'));
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
        $form = new Form(new User);
        
        $form->display('id');

        $form->text('name', __('Name'))->required();
        $form->text('email', __('Email'));
        $form->datetime('email_verified_at', __('Verified'));

        return $form;
    }

}
