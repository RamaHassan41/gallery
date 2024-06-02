<?php

namespace App\Http\Controllers;
use Validator;
use Log;
use App\Traits\GeneralTrait;
use App\Http\Requests\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
use App\Notifications\EmailVerificationNotification;
use Ichtrojan\Otp\Otp;

class EmailVerificationController extends Controller
{
    use GeneralTrait;
    private $otp;
    
    public function __construct(){
        $this->otp=new Otp;
    }

    public function sendEmailVerification(Request $request){
        $request->user()->notify(new EmailVerificationNotification());
        return $this->sendResponse('done','happy');
    }

    public function email_verification(Request $request){
        $input=$request->all();
        $otp2=$this->otp->validate($input,[
            'email'=>'required|email|exists:users',
            'otp'=>'required|max:6'
        ]);
        if(!$otp2->status){
            return $this->sendError(['error'=>$otp2],'Try later');
        }
        $user=User::where('email',$request->email)->first();
        $user->update(['email_verified_at'=>now()]);
        return $this->sendResponsse('Ok','Email is verified successfully');
    }
}
