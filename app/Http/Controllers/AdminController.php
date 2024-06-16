<?php

namespace App\Http\Controllers;
use Validator;
use App\Traits\GeneralTrait;
use App\Models\Admin;
use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\EditProfileRequest;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\EmailVerificationMail;

class AdminController extends Controller
{
    use GeneralTrait;

    public function adminRegister(RegisterRequest $request){
        $admin=Admin::create([
            'name'=>$request->name,
            'email'=>$request->email,
            'password'=>$request->password,
            'code'=>rand(100000,999999),
        ]);
        $credentials=['email'=>$request->email,'password'=>$request->password];
        $token=JWTAuth::fromUser($admin);
        $admin->token=$token;
        if(!$token)
            return $this->sendError('Unauthorized',401);
        Mail::to($admin->email)->send(new EmailVerificationMail($admin->name,$admin->code));
        return $this->sendResponse([$admin,'Register is done successfully. Email verification code is sent to your email'],200);
    }

    public function adminEditProfile(EditProfileRequest $request){
        $admin=Auth::guard('admin_api')->user();
        if($request->image&&$admin->image){
            $this->deleteImage($admin->image);
        }
        $admin['name']=isset($request->name)?$request->name:$admin->name;
        $admin['email']=isset($request->email)?$request->email:$admin->email;
        $admin['password']=isset($request->password)?$request->password:$admin->password;
        $admin['user_name']=isset($request->user_name)?$request->user_name:$admin->user_name;
        $admin['gender']=isset($request->gender)?$request->gender:$admin->gender;
        $admin['image']=isset($request->image)?$this->setProfileImage($request->image):$admin->image;
        $admin['image']=isset($request->delete_image)?$this->deleteImage($admin->image):$admin->image;
        $admin->save();
        return $this->sendResponse([$admin,'Profile is updated successfully'],200);
    }

    public function adminLogin(LoginRequest $request){
        $credentials=$request->only(['email','password']);
        $token=Auth::guard('admin_api')->attempt($credentials);
        $admin_exist=Admin::where('email',$request->email)->first();
        if($admin_exist&&!$token)
            return $this->sendError('Unauthorized',401);
        if(!$token)
            return $this->sendError('Account is Not found',404);
        $admin=Auth::guard('admin_api')->user();
        $admin->email_verified_at=null;
        $admin->code=rand(100000, 999999);
        $admin->save();
        $admin->token=$token;
        // $admin->token=$token;
        // $admin->code=rand(100000,999999);
        Mail::to($admin->email)->send(new EmailVerificationMail($admin->name,$admin->code));
        return $this->sendResponse([$admin,'Login id done successfully. Email verification code is sent to your email'],200);
    }

    public function adminLogout(Request $request){
        $token=$request->bearerToken();
        if($token){
            // $admin=Auth::guard('admin_api')->user();
            // $admin->email_verified_at=null;
            // $admin->save();
            JWTAuth::setToken($token)->invalidate();
            return $this->sendResponse('Logout is done successfully, whould you like to login again?',200);
        }
        return $this->sendError('Some errors are occured',400);
    }

    public function adminDeleteAccount(){
        $admin=Auth::guard('admin_api')->user();
        if($admin->image){
            $this->deleteImage($admin->image);
        }
        $admin->delete();
        return $this->sendResponse('Account is deleted successfully, whould you like to create a new account?',200);
    }
 
    public function adminGetProfile(){
        $admin=Auth::guard('admin_api')->user();
        return $this->sendResponse([$admin,'Profile is displayed successfully'],200);
    }
}
