<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\HomeController;
use App\Http\Controllers\Api\CategoryController;

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

Route::group([], function(){
    Route::any('home', [HomeController::class, 'index']);
    Route::any('category', [CategoryController::class, 'index']);
    // Route::any('tests/{category:slug}', [TestController::class, 'category']);
    Route::any('login', [AuthController::class, 'login']);
    Route::any('register', [AuthController::class, 'register']);
});

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::middleware(['auth:api'])->prefix('user')->group(function () {
    Route::get('profile', function (Request $request) {
            return $request->user();
    });
    Route::any('dashboard', 'UserController@dashboard');
});