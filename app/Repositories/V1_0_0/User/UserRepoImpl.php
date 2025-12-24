<?php
namespace App\Repositories\V1_0_0\User;

use App\Helpers\FileHelper;
use App\Mail\ForgetPassEmailTemplate;
use App\Mail\OtpEmailTemplate;
use App\Models\User;
use App\Repositories\V1_0_0\User\UserRepo;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;
use stdClass;

class UserRepoImpl implements UserRepo {
    public function createUser($request): User
    {
        if($request["photo"]) {
            $split_data = explode('.', $request["photo"]->getClientOriginalName());
            $file_name = uniqid('user_image_') . '.' . end($split_data);
            $request["photo"] = FileHelper::upload_file($request["photo"], 'users', $file_name);
        }
        return User::create($request);
    }

    public function registerUser($request) {
        try{
            $return_data = DB::transaction(function() use($request) {
                $user = $this->createUser($request->only(['name', 'email', 'password', 'photo']));
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
            return repoToControllerResponse(1, 'Otp sent successfully on your registered email, please verify otp to continue!', $return_data);
        } catch(Exception $e) {
            Log::error('Error occured when registering user !! ', [$e->getMessage()]);
            return repoToControllerResponse(0, 'Unable to process registration right now!', status:500);
        }
    }

    public function loginUser($request)
    {
        try{
            $user = $request->user;
            if($user->status == 'inactive') {
                return repoToControllerResponse(0, 'Please complete your OTP verification process to continue!', status: 500);
            } elseif($user->status == 'suspended') {
                return repoToControllerResponse(0, 'Your account was suspended!', status: 500);
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
            return repoToControllerResponse(1, 'You have logged in sucessfully!', data: $response_data);
        } catch(Exception $e) {
            Log::error('Error occured when registering user !! ', [$e->getMessage()]);
            return repoToControllerResponse(0, 'Unable to process registration right now', status: 500);
        }
    }

    public function validateOtp($request)
    {
        $user = User::where('email',$request->email)->first();
        $otpExpired = Carbon::parse($user->otp_send_at)
                           ->addMinutes(5)
                           ->isPast();

        if(!$user->otp_send_at) {
            return ["success" => 0, "message" => "Your OTP was already verified before!"];
        }
        if($otpExpired){
            return ["success" => 0, "message" => "This OTP is no longer valid. Tap resend to get a new one!"];
        } 
        if($user->otp != $request->otp) {
            return ["success" => 0, "message" => "This OTP is invalid!"];
        }
        $user->otp = null;
        $user->otp_send_at = null;
        $user->status = "active";
        $user->email_verified_at = now();
        $user->save();
        
        return ["success" => 1, "message" => "Your OTP is verified successfully!", "user" => $user];
    }

    public function verifyOtp($request)
    {
        try{
            return DB::transaction(function() use($request) {
                $response = $this->validateOtp($request);
                if(!$response["success"]) {
                    return repoToControllerResponse(0, $response["message"], status: 422);
                }
                $token = $response['user']->createToken('APP')->plainTextToken;
                $response_data = [
                    "token" => $token,
                    "user" => [
                        "id" => $response["user"]->id,
                        "name" => $response["user"]->name,
                        "photo" => $response["user"]->photo,
                        "status" => $response["user"]->status,
                    ]
                ];
                return repoToControllerResponse(1, $response["message"], data: $response_data);
            });
        } catch(Exception $e) {
            Log::error('Error occured when validation user otp !! ', [$e->getMessage()]);
            return repoToControllerResponse(0, 'Unable to process otp verification right now.', status: 500);
        }
    }

    public function resendOtp($request)
    {
        try{
            return DB::transaction(function() use($request) {
                $user = User::where('email',$request->email)->first();
                if(!$user){
                    return repoToControllerResponse(0, 'Unable to find your email address at this moment!', status: 500);
                }
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

                return repoToControllerResponse(1, 'Otp sent successfully on your registered email!');
            });
        } catch(Exception $e) {
            Log::error('Error occured when trying to resend user otp !! ', [$e->getMessage()]);
            return repoToControllerResponse(0, 'Unable to generate otp at this moment!.', status: 500);
        }
    }

    public function forgetPassword($request)
    {
        try{
            return DB::transaction(function() use($request) {
                $user = User::where('email',$request->email)->first();
                if(!$user){
                    return repoToControllerResponse(0, 'Unable to find your email address at this moment!', status: 500);
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
                return repoToControllerResponse(1, 'Password reset link has been sucessfully sent to your registered email address!');
            });
        } catch(Exception $e) {
            Log::error('Error occured when generating forget password url!! ', [$e->getMessage()]);
            return repoToControllerResponse(0, 'Unable to generate forget password url right now.', status: 500);
        }
    }
}
