<?php

namespace App\Http\Controllers;
use App\Traits\GeneralTrait;
use App\Http\Requests\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
use App\Models\Artist;
use App\Models\Admin;
use App\Mail\EmailVerificationMail;
use Illuminate\Support\Facades\Auth;

class EmailVerificationController extends Controller
{
    use GeneralTrait;

    public function verifyEmail(EmailVerificationRequest $request)
    {
        $user=User::where('email',$request->email)->first();
        if(!$user){
            return $this->sendError('Account is not found',404);
        }
        if($user->code!=$request->code){
            return $this->sendError('You entered a wrong code',400);
        }
        $user->email_verified_at=now();
        $user->code=null;
        $user->save();
        //$user = $request->user();
        // $code = $request->input('code');
        // if ($user->code == $code) {
        //     $user->markEmailAsVerified();
        //     return response()->json(['message' => 'Email verified']);
        // }
        return $this->sendresponse('Your email is verified successfully',200);
    }

    public function artistVerifyEmail(EmailVerificationRequest $request)
    {
        $artist=Artist::where('email',$request->email)->first();
        if(!$artist){
            return $this->sendError('Account is not found',404);
        }
        if($artist->code!=$request->code){
            return $this->sendError('You entered a wrong code',400);
        }
        $artist->email_verified_at=now();
        $artist->code=null;
        $artist->save();
        return $this->sendresponse('Your email is verified successfully',200);
    }

    public function adminVerifyEmail(EmailVerificationRequest $request)
    {
        $admin=Admin::where('email',$request->email)->first();
        if(!$admin){
            return $this->sendError('Account is not found',404);
        }
        if($admin->code!=$request->code){
            return $this->sendError('You entered a wrong code',400);
        }
        $admin->email_verified_at=now();
        $admin->code=null;
        $admin->save();
        return $this->sendresponse('Your email is verified successfully',200);
    }
}