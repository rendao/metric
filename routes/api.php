<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\HomeController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\TestController;
use App\Http\Controllers\Api\ResultController;
use App\Http\Controllers\Api\HistoryController;

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

// Route::any('/botman', [BotManController::class, 'handle']);

Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/login', [AuthController::class, 'login']);

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::get('/test/{test:code}/start', [TestController::class, 'start'])->name('test_start');
    Route::any('/test/{test:code}/answer', [TestController::class, 'answer'])->name('test_answer');
    Route::any('/test/{test:code}/finish', [TestController::class, 'finish'])->name('test_finish');
    Route::get('/test/result/{test_session:code}', [ResultController::class, 'show'])->name('test_result');
});

Route::middleware(['auth:sanctum'])->prefix('user')->group(function () {
    Route::get('/profile', function(Request $request) {
        return auth()->user();
    });
    Route::get('/histories', [HistoryController::class, 'index']);
});

Route::group([], function(){
    Route::any('home', [HomeController::class, 'index']);
    Route::any('categories', [CategoryController::class, 'index']);
    Route::any('category/{category:id}', [CategoryController::class, 'tests']);
    Route::any('tests', [TestController::class, 'index']);
    Route::any('test/{test:code}', [TestController::class, 'show'])->name('test_show');
});

Route::fallback(function(){
    return response()->json(['message' => 'Resource Not Found.'], 404);
})->name('api.fallback.404');