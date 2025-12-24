<?php

namespace App\Repositories\V1_0_0\User;

interface UserRepo
{
    public function createUser($request);
    public function validateOtp($request);
}
