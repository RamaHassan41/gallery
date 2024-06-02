<?php

namespace App\Http\Controllers;
use Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

class ForgotPasswordController extends Controller
{
    public function forgot(Request $request){
        $email=Validator::make($request->all(),[
            'email'=>'required|email',
        ]);
        Password::sendResetLink([$email]);
        return $this->respondWithMessage('Reset password link sent on your email');
    }

    public function reset(Request $request){
        $input=Validator::make($request->all(),[
            'email'=>'required|email',
            'token'=>'required|string',
            'password'=>'required|min:8',
            'c_password'=>'required|same:password',
        ]);
        $email_password_status=Password::reset($input,function($user,$password){
            $user->password=$password;
            $user->save;
        });
        if($email_password_status==Password::INVALID_TOKEN){
            return $this->respondBadRequest(INVALID_RESET_PASSWORD_TOKEN);
        }
        return $this->respondWithMessage('Password changed successfully');
    }
}
