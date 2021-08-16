<?php

use Illuminate\Routing\Router;

Admin::routes();

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
    'as'            => config('admin.route.prefix') . '.',
], function (Router $router) {

    $router->get('/', 'HomeController@index')->name('home');
    $router->resource('categories', CategoryCrudController::class);
    $router->any('category/api', 'CategoryCrudController@api');
    $router->resource('test_types', TestTypeCrudController::class);
    $router->any('test_type/api', 'TestTypeCrudController@api');
    $router->resource('tests', TestCrudController::class);
});
