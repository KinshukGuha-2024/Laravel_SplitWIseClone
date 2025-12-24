<?php
namespace App\Repositories\V1_0_0\User;

use App\Helpers\FileHelper;
use App\Models\User;
use App\Repositories\V1_0_0\User\UserRepo;
use Carbon\Carbon;

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

    public function validateOtp($request)
    {
        $user = User::find($request->user_id);
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
}