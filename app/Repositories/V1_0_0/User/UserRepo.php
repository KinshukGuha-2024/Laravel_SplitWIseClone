<?php

namespace App\Repositories\V1_0_0\User;

interface UserRepo
{
    public function createUser($request);
    public function validateOtp($request);
    public function registerUser($request);
    public function loginUser($request);
    public function verifyOtp($request);
    public function resendOtp($request);
    public function forgetPassword($request);
}
