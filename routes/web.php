<?php

use App\Http\Controllers\WebController;
use Illuminate\Support\Facades\Route;
use Symfony\Component\Process\Process;

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('auth')->controller(WebController::class)->group(function() {
    Route::get('regenerate_password/{token}', 'regeneratePassword');
    Route::post('reset-password', 'resetPassword');
});

Route::view('/web-toast', 'toast')->name('web.toast');
