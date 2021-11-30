<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BotManController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::post('/botman', [BotManController::class, 'handle']);
// Route::match(['get', 'post'], '/botman', 'BotManController@handle');

Route::get('/', function () {
    return view('welcome');
})->name('welcome');


Route::group(['middleware' => 'user',], function ($router) {
    $router->resource('users', UserController::class);
});

Route::namespace('Auth')->group(function () {
    Route::get('/login','LoginController@show_login_form')->name('login');
    Route::post('/login','LoginController@process_login')->name('login');
    Route::get('/register','LoginController@show_signup_form')->name('register');
    Route::post('/register','LoginController@process_signup');
    Route::post('/logout','LoginController@logout')->name('logout');
});
