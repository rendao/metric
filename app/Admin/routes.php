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

    $router->resource('users', UserCrudController::class);

    $router->resource('categories', CategoryCrudController::class);
    $router->any('category/api', 'CategoryCrudController@api');

    $router->get('test_types', 'TestTypeCrudController@index');
    $router->any('test_type/api', 'TestTypeCrudController@api');

    $router->resource('tests', TestCrudController::class);
    $router->any('test/api', 'TestCrudController@api');

    $router->resource('test_scores', TestScoreCrudController::class);
    $router->resource('test_sessions', TestSessionController::class);

    $router->resource('test_templates', TestTemplateCrudController::class);
    $router->any('test_template/api', 'TestTemplateCrudController@api');

    $router->get('question_types', 'QuestionTypeCrudController@index');
    $router->any('question_type/api', 'QuestionTypeCrudController@api');
    $router->resource('questions', QuestionCrudController::class);

    $router->resource('question_sessions', QuestionSessionController::class);

});
