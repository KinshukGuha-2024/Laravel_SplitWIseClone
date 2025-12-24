<?php

namespace App\Http\Controllers\Api\V1_0_0;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ForgetPasswordRequest;
use App\Http\Requests\Api\LoginRequest;
use App\Http\Requests\Api\RegisterRequest;
use App\Http\Requests\Api\ResendOtpRequest;
use App\Http\Requests\Api\VerifyOtpRequest;
use App\Repositories\V1_0_0\User\UserRepo;

class AuthController extends Controller
{
    public $userRepo;
    public function __construct(UserRepo $userRepo) {
        $this->userRepo = $userRepo; 
    }

    public function login(LoginRequest $request) {
        $response = $this->userRepo->loginUser($request);
        return $this->responseSuccess($response["message"], $response["data"], $response["status"]);        
    }

    public function register(RegisterRequest $request) {
        $response = $this->userRepo->registerUser($request);
        return $this->responseSuccess($response["message"], $response["data"], $response["status"]);
    }

    public function verifyOtp(VerifyOtpRequest $request) {
        $response = $this->userRepo->verifyOtp($request);
        return $this->responseSuccess($response["message"], $response["data"], $response["status"]);
    }

    public function resendOtp(ResendOtpRequest $request) {
        $response = $this->userRepo->resendOtp($request);
        return $this->responseSuccess($response["message"], $response["data"], $response["status"]);
    }

    public function forgetPassword(ForgetPasswordRequest $request) {
        $response = $this->userRepo->forgetPassword($request);
        return $this->responseSuccess($response["message"], $response["data"], $response["status"]);
    }
}
