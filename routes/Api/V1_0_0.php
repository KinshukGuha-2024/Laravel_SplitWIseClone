<?php

use App\Http\Controllers\Api\V1_0_0\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->controller(AuthController::class)->group(function() {
    Route::post('register', 'register');
    Route::post('verify-otp', 'verifyOtp');
    Route::post('resend-otp', 'resendOtp');
    Route::post('login', 'login');
    Route::post('forget-password', 'forgetPassword');
});