<?php

namespace App\Http\Controllers;
use Validator;
use App\Traits\GeneralTrait;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\EditProfileRequest;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Mail;
use App\Mail\EmailVerificationMail;

class AuthController extends Controller
{
    use GeneralTrait;

    public function userRegister(RegisterRequest $request)
    {
        $user=User::create([
            'name'=>$request->name,
            'email'=>$request->email,
            'password'=>$request->password,
            'code'=>rand(100000,999999),
        ]);
        $credentials=['email'=>$request->email,'password'=>$request->password];
        $token=JWTAuth::fromUser($user);
        $user->token=$token;
        if(!$token)
            return $this->sendError('Unauthorized',401);
        $user->favourite()->create([
            'user_type'=>'App\\Models\\User',
        ]);
        Mail::to($user->email)->send(new EmailVerificationMail($user->name,$user->code));
        return $this->sendResponse([$user,'Register is done successfully. Email verification code is sent to your email'],200);
    }

    public function userEditProfile(EditProfileRequest $request){
        $user=Auth::guard('api')->user();
        if($request->image&&$user->image){
            $this->deleteImage($user->image);
        }
        $user['name']=isset($request->name)?$request->name:$user->name;
        $user['email']=isset($request->email)?$request->email:$user->email;
        $user['password']=isset($request->password)?$request->password:$user->password;
        $user['user_name']=isset($request->user_name)?$request->user_name:$user->user_name;
        $user['gender']=isset($request->gender)?$request->gender:$user->gender;
        $user['image']=isset($request->image)?$this->setProfileImage($request->image):$user->image;
        $user['image']=isset($request->delete_image)?$this->deleteImage($user->image):$user->image;
        $user->save();
        return $this->sendResponse([$user,'Profile is updated successfully'],200);
    }

    public function userLogin(LoginRequest $request){
        $credentials=$request->only(['email','password']);
        $token=Auth::guard('api')->attempt($credentials);
        $user_exist=User::where('email',$request->email)->first();
        if($user_exist&&!$token)
            return $this->sendError('Unauthorized',401);
        if(!$token)
            return $this->sendError('Account is Not found',404);
        $user=Auth::guard('api')->user();
        //$user->token=$token; 
        $user->email_verified_at=null;
        $user->code=rand(100000, 999999);
        $user->save();
        $user->token=$token;
        Mail::to($user->email)->send(new EmailVerificationMail($user->name,$user->code));
        return $this->sendResponse([$user,'Login id done successfully. Email verification code is sent to your email'],200);
    }

    public function userLogout(Request $request){
        $token=$request->bearerToken();
        if($token){
            //$user=Auth::guard('api')->user();
            // $user->email_verified_at=null;
            // $user->save();
            JWTAuth::setToken($token)->invalidate();
            return $this->sendResponse('Logout is done successfully, whould you like to login again?',200);
        }
        return $this->sendError('Some errors are occured',400);
    }

    public function userDeleteAccount(){
        $user=Auth::guard('api')->user();
        if($user->image){
            $this->deleteImage($user->image);
        }
        $user->delete();
        return $this->sendResponse('Account is deleted successfully, whould you like to create a new account?',200);
    }

    public function userGetProfile(){
        $user=Auth::guard('api')->user();
        return $this->sendResponse([$user,'Profile is displayed successfully'],200);
    }























}
