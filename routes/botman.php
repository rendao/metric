<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BotManController;

Route::match(['get', 'post'], '/botman', 'BotManController@handle');

// $botman = resolve('botman');
// $botman->hears('.*(Hi|Hello).*', BotManController::class.'@handle');
// $botman->hears('.*(help).*', BotManController::class.'@help');