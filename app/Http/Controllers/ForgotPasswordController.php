<?php

namespace App\Http\Controllers;

use App\Traits\GeneralTrait;
use App\Models\User;
use App\Models\ِArtist;
use App\Models\Admin;
use Illuminate\Http\Request;
use App\Http\Requests\ForgotPassword;
use App\Http\Requests\ResetPassword;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\ResetPasswordMail;

class ForgotPasswordController extends Controller 
{
    use GeneralTrait;

    public function forgotPassword(ForgotPassword $request){ 
        $user=User::where('email',$request->email)->first();
        if(!$user){
            return $this->sendError('Account is not found',404);
        }
        $code=rand(100000,999999);
        $user->code=$code;
        $user->save();
        Mail::to($user->email)->send(new ResetPasswordMail($user->name,$code));
        return $this->sendResponse('Code is sent to your email successfully',200);
    }

    public function resetPassword(ResetPassword $request){
        $user=User::where('email',$request->email)->first();
        if(!$user){
            return $this->sendError('Account is not found',404);
        }
        if($user->code!=$request->code){
            return $this->sendError('You entered a wrong code',400);
        }
        $user->password=$request->password;
        $user->code=null;
        $user->save();
        return $this->sendResponse('Password is reset successfully',200);
    }

    public function artistForgotPassword(ForgotPassword $request){ 
        $artist=Artist::where('email',$request->email)->first();
        if(!$artist){
            return $this->sendError('Account is not found',404);
        }
        $code=rand(100000,999999);
        $artist->code=$code;
        $artist->save();
        Mail::to($artist->email)->send(new ResetPasswordMail($artist->name,$code));
        return $this->sendResponse('Code is sent to your email successfully',200);
    }

    public function artistResetPassword(ResetPassword $request){
        $artist=Artist::where('email',$request->email)->first();
        if(!$artist){
            return $this->sendError('Account is not found',404);
        }
        if($artist->code!=$request->code){
            return $this->sendError('You entered a wrong code',400);
        }
        $artist->password=$request->password;
        $artist->code=null;
        $artist->save();
        return $this->sendResponse('Password is reset successfully',200);
    }

    public function adminForgotPassword(ForgotPassword $request){ 
        $admin=Admin::where('email',$request->email)->first();
        if(!$admin){
            return $this->sendError('Account is not found',404);
        }
        $code=rand(100000,999999);
        $admin->code=$code;
        $admin->save();
        Mail::to($admin->email)->send(new ResetPasswordMail($admin->name,$code));
        return $this->sendResponse('Code is sent to your email successfully',200);
    }

    public function adminResetPassword(ResetPassword $request){
        $admin=Admin::where('email',$request->email)->first();
        if(!$admin){
            return $this->sendError('Account is not found',404);
        }
        if($admin->code!=$request->code){
            return $this->sendError('You entered a wrong code',400);
        }
        $admin->password=$request->password;
        $admin->code=null;
        $admin->save();
        return $this->sendResponse('Password is reset successfully',200);
    }
}
