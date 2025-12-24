<?php

namespace App\Http\Controllers;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class WebController extends Controller
{
    public function regeneratePassword($token) {
        return DB::transaction(function() use($token) {
            $id = Crypt::decryptString($token);
            $user = User::find($id);
            if(!$user) {
                return $this->responseError(message:'Broken link found, or user does not exist!!', status: 400);
            }
            $linkExpired = Carbon::parse($user->password_url_sent_at)
                           ->addMinutes(10)
                           ->isPast();
            if(empty($user->password_url_sent_at) || $linkExpired){
                return $this->responseError(message:'Link Expired!!', status: 400);
            }
            return view('regenerate_password');
        });
    }

    public function resetPassword(Request $request) {
        $request->validate([
            "token" => "required",
            "password" => "required|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{7,16}$/|confirmed"
        ]);

        return DB::transaction(function() use($request) {
            $id = Crypt::decryptString($request->token);
            $user = User::find($id);
            if(!$user) {
                return $this->responseError(message:'Broken link found, or user does not exist!!', status: 400);
            }
            $linkExpired = Carbon::parse($user->password_url_sent_at)
                           ->addMinutes(10)
                           ->isPast();
            if(empty($user->password_url_sent_at) || $linkExpired){
                return $this->responseError(message:'Link Expired!!', status: 400);
            }
            $user->password = Hash::make($request->password);
            $user->password_url_sent_at = null;
            $user->save();
            return $this->responseSuccess('Password reset was successfull!');
        });
    }
}
