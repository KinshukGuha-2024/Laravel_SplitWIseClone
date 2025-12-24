<?php

namespace App\Http\Controllers\Api\V1_0_0;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ForgetPasswordRequest;
use App\Http\Requests\Api\LoginRequest;
use App\Http\Requests\Api\RegisterRequest;
use App\Http\Requests\Api\ResendOtpRequest;
use App\Http\Requests\Api\VerifyOtpRequest;
use App\Mail\ForgetPassEmailTemplate;
use App\Mail\OtpEmailTemplate;
use App\Models\User;
use App\Models\UserDevices;
use App\Repositories\V1_0_0\User\UserRepo;
use Exception;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;
use stdClass;

use function PHPUnit\Framework\throwException;

class AuthController extends Controller
{
    public $userRepo;
    public function __construct(UserRepo $userRepo) {
        $this->userRepo = $userRepo; 
    }

    public function login(LoginRequest $request) {
        try{
            $user = $request->user;
            if($user->status == 'inactive') {
                return $this->responseError('Please complete your OTP verification process to continue!', status: 500);
            } elseif($user->status == 'suspended') {
                return $this->responseError('Your account was suspended!', status: 500);
            }
            $token = $user->createToken('APP');
            $token = $token->plainTextToken;
            $response_data = [
                "token" => $token,
                "user" => [
                    "id" => $user->id,
                    "name" => $user->name,
                    "email" => $user->email,
                    "photo" => $user->photo,
                    "status" => $user->status,
                ]
            ];
            return $this->responseSuccess('You have logged in sucessfully!', data: $response_data);
        } catch(Exception $e) {
            Log::error('Error occured when registering user !! ', [$e->getMessage()]);
            return $this->responseError('Unable to process registration right now.', status: 500);
        }
        
    }

    public function register(RegisterRequest $request) {
        try{
            $return_data = DB::transaction(function() use($request) {
                $user = $this->userRepo->createUser($request->only(['name', 'email', 'password', 'photo']));
                if(!$user) {
                    throw ValidationException::withMessages(["error" => "This request cannot be processed at this moment, please try again later!"]);
                }
                $otp = rand(1000, 9999);
                $user->otp = $otp;
                $user->otp_send_at = now();
                $user->save();

                $mail_data = new stdClass();
                $mail_data->user = $user;
                $mail_data->page = 'register';
                $mail_data->otp = $otp;
                $mail_data->minutes = 5;
                Mail::to($user->email)->send(new OtpEmailTemplate($mail_data));

                return[
                    "user" => [
                        "id" => $user->id, 
                        "name" => $user->name, 
                        "email" => $user->email,
                        "photo" => asset($user->photo)
                    ]
                ];
            });
            return $this->responseSuccess('Otp sent successfully on your registered email, please verify otp to continue!', data: $return_data);

        } catch(Exception $e) {
            Log::error('Error occured when registering user !! ', [$e->getMessage()]);
            return $this->responseError('Unable to process registration right now.', status: 500);
        }
    }

    public function verifyOtp(VerifyOtpRequest $request) {
        try{
            return DB::transaction(function() use($request) {
                $response = $this->userRepo->validateOtp($request);
                if($response["success"]) {
                    $token = $response['user']->createToken('APP');
                    $token = $token->plainTextToken;
                    $response_data = [
                        "token" => $token,
                        "user" => [
                            "id" => $response["user"]->id,
                            "name" => $response["user"]->name,
                            "photo" => $response["user"]->photo,
                            "status" => $response["user"]->status,
                        ]
                    ];
                    return $this->responseSuccess($response["message"], data: $response_data);
                }
                return $this->responseError($response["message"], status: 422);
            });
        } catch(Exception $e) {
            Log::error('Error occured when validation user otp !! ', [$e->getMessage()]);
            return $this->responseError('Unable to process otp verification right now.', status: 500);
        }

    }

    public function resendOtp(ResendOtpRequest $request) {
        try{
            return DB::transaction(function() use($request) {
                $user = User::where('email',$request->email)->first();
                $otp = rand(1000, 9999);
                $user->otp = $otp;
                $user->otp_send_at = now();
                $user->save();
        
                $mail_data = new stdClass();
                $mail_data->user = $user;
                $mail_data->page = $request->page;
                $mail_data->otp = $otp;
                $mail_data->minutes = 5;
                $mail_data->is_resend = 1;
                Mail::to($user->email)->send(new OtpEmailTemplate($mail_data));

                return $this->responseSuccess('Otp sent successfully on your registered email!');
            });
        } catch(Exception $e) {
            Log::error('Error occured when trying to resend user otp !! ', [$e->getMessage()]);
            return $this->responseError('Unable to generate otp at this moment!.', status: 500);
        }
        
    }

    public function forgetPassword(ForgetPasswordRequest $request) {
        try{
            return DB::transaction(function() use($request) {
                $user = User::where('email',$request->email)->first();
                if(!$user){
                    return $this->responseError('Unable to find your email address at this moment!', status: 500);
                }
                $user->password_url_sent_at = now();
                $user->save();

                $encryptedUserId = Crypt::encryptString($user->id);
                $reset_link = url("auth/regenerate_password/{$encryptedUserId}");

                $mail_data = new stdClass();
                $mail_data->user = $user;
                $mail_data->link = $reset_link;
                $mail_data->minutes = 10;

                Mail::to($user->email)->send(new ForgetPassEmailTemplate($mail_data));
                return $this->responseSuccess('Password reset link has been sucessfully sent to your registered email address!');
            });
        } catch(Exception $e) {
            Log::error('Error occured when generating forget password url!! ', [$e->getMessage()]);
            return $this->responseError('Unable to generate forget password url right now.', status: 500);
        }
    }
}
