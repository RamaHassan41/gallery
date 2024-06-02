<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PasswordController extends Controller
{
    use GeneralTrait;
    
    public function resetPassword(ResetPasswordRequest $request){
        $user=Auth::guard('api')->user();
        if(Hash::check($request->old_password,$user->password)){
            $user->update([
                'password'=>$request->password,
            ]);
            return $this->sendResponse('Password is updated successfully',200);
        }
        return $this->sendError('Operation is failed',400);
    }

    public function forgetPassword(EmailRequest $request){
        $user=User::where('email',$request->email)->first();
        if(!$user){
            return $this->sendError('Account is not found',404);
        }
        $code=mt_rand(0,999999);
        $user->update([
            'code'=>$code,
        ]);
        $mailData=[
            'title'=>'Forgot Password Email',
            'code'=>$code,
        ];
        return $this->sendResponse('Operation is completed successfully',200);
    }

    public function checkCode(CodeRequest $request){
        $code=$request->code;
        $user=User::where('email',$request->email)->first();
        if(!$user){
            return $this->sendError('Account is not found',404);
        }
        if($user->code!=$code){
            return $this->sendError('The entered verification code is not correct',403);
        }
        return $this->sendResponse('Operation is completed successfully',200);
    }

    public function passwordNew(PasswordNewRequest $request){
        $user=User::where('email',$request->email)->first();
        if(!$user){
            return $this->sendError('Account is not found',404);
        }
        if(isset($user)){
            $user->update([
                'password'=>$request->password,
            ]);
            $token=JWTAuth::fromUser($user);
            if(!$token){
                return $this->sendError('Unauthorized',401);
            }
            $user->token=$token;
            return $this->sendResponse($user,'login successfully',200);
        }
        return $this->sendError('try again',405);
    }
}
