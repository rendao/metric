<?php

use App\Models\User;

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\HomeController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\TestController;

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
Route::any('/auth/login', [AuthController::class, 'login'])->name('login');

Route::middleware(['auth:sanctum'])->group(function () {
    // Route::any('dashboard', 'UserController@dashboard');
    Route::get('/profile', function(Request $request) {
        return auth()->user();
    })->name('user_profile');
});

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::post('/auth/logout', [AuthController::class, 'logout']);
});

Route::group([], function(){
    Route::any('home', [HomeController::class, 'index']);
    Route::any('categories', [CategoryController::class, 'index']);
    Route::any('categories/{category:id}', [CategoryController::class, 'tests']);
    Route::any('tests', [TestController::class, 'index']);

});
