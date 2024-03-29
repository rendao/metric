<?php

/**
 * Laravel-admin - admin builder based on Laravel.
 * @author z-song <https://github.com/z-song>
 *
 * Bootstraper for Admin.
 *
 * Here you can remove builtin form field:
 * Encore\Admin\Form::forget(['map', 'editor']);
 *
 * Or extend custom form field:
 * Encore\Admin\Form::extend('php', PHPEditor::class);
 *
 * Or require js and css assets:
 * Admin::css('/packages/prettydocs/css/styles.css');
 * Admin::js('/packages/prettydocs/js/main.js');
 *
 */

Encore\Admin\Form::forget(['map', 'editor']);

// `admin custom views`
app('view')->prependNamespace('admin', resource_path('views/admin'));

use Encore\Admin\Facades\Admin;
use Encore\Admin\Grid\Column;

Column::extend('jumpto', function ($value, $link) {
    return "<a href='$link$value'>$value</a>";
});


Admin::js('/vendor/chartjs/dist/Chart.min.js');