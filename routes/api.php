<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\HomeController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\TestController;

use App\Models\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/login', [AuthController::class, 'login']);

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::post('/auth/logout', [AuthController::class, 'logout']);
});

Route::middleware(['auth:sanctum'])->prefix('user')->group(function () {
    Route::get('/profile', function(Request $request) {
        return auth()->user();
    });

    Route::get('/test/{test:code}/init', [TestController::class, 'init'])->name('test_init');
    Route::get('/test/{test:code}/{session}', [TestController::class, 'session'])->name('test_session');
    Route::get('/test/{test:code}/{session}/update', [TestController::class, 'update'])->name('test_update');

});

Route::group([], function(){
    Route::any('home', [HomeController::class, 'index']);
    Route::any('categories', [CategoryController::class, 'index']);
    Route::any('category/{category:id}', [CategoryController::class, 'tests']);
    Route::any('tests', [TestController::class, 'index']);
    Route::any('test/{test:code}', [TestController::class, 'show']);
    Route::any('test/{test:code}/questions', [TestController::class, 'questions']);
});
