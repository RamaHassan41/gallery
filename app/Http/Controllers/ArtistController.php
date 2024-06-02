<?php

namespace App\Http\Controllers;
use Validator;
use App\Traits\GeneralTrait;
use App\Models\Artist;
use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\EditProfileRequest;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Auth;

class ArtistController extends Controller
{
    use GeneralTrait;
    public function artistRegister(RegisterRequest $request){
        $artist=Artist::create([
            'name'=>$request->name,
            'email'=>$request->email,
            'password'=>$request->password,
        ]);
        $credentials=['email'=>$request->email,'password'=>$request->password];
        $token=JWTAuth::fromUser($artist);
        $artist->token=$token;
        if(!$token)
            return $this->sendError('Unauthorized',401);
        $artist->favourite()->create([
            'user_type'=>'App\\Models\\Artist',
        ]);
        return $this->sendResponse([$artist,'Register is done successfully'],200);
    }

    public function artistEditProfile(EditProfileRequest $request){
        $artist=Auth::guard('artist_api')->user();
        if($request->image&&$artist->image){
            $this->deleteImage($artist->image);
        }
        $artist['name']=isset($request->name)?$request->name:$artist->name;
        $artist['email']=isset($request->email)?$request->email:$artist->email;
        $artist['password']=isset($request->password)?$request->password:$artist->password;
        $artist['user_name']=isset($request->user_name)?$request->user_name:$artist->user_name;
        $artist['gender']=isset($request->gender)?$request->gender:$artist->gender;
        $artist['image']=isset($request->image)?$this->setProfileImage($request->image):$artist->image;
        $artist['image']=isset($request->delete_image)?$this->deleteImage($artist->image):$artist->image;
        $artist['expertise']=isset($request->expertise)?$request->expertise:$artist->expertise;
        $artist['specialization']=isset($request->specialization)?$request->specialization:$artist->specialization;
        $artist['biography']=isset($request->biography)?$request->biography:$artist->biography;
        $artist->save();
        return $this->sendResponse([$artist,'Profile is updated successfully'],200);
    }

    public function artistLogin(LoginRequest $request){
        $credentials=$request->only(['email','password']);
        $token=Auth::guard('artist_api')->attempt($credentials);
        $artist_exist=Artist::where('email',$request->email)->first();
        if($artist_exist&&!$token)
            return $this->sendError('Unauthorized',401);
        if(!$token)
            return $this->sendError('Account is Not found',404);
        $artist=Auth::guard('artist_api')->user();
        $artist->token=$token;
        return $this->sendResponse([$artist,'Login id done successfully'],200);
    }

    public function artistLogout(Request $request){
        $token=$request->bearerToken();
        if($token){
            JWTAuth::setToken($token)->invalidate();
            return $this->sendResponse('Logout is done successfully, whould you like to login again?',200);
        }
        return $this->sendError('Some errors are occured',400);
    }

    public function artistDeleteAccount(){
        $artist=Auth::guard('artist_api')->user();

        if($artist->image){
            $this->deleteImage($artist->image);
        }
        $artist->delete();
        return $this->sendResponse('Account is deleted successfully, whould you like to create a new account?',200);

    }
 
    public function artistGetProfile(){
        $artist=Auth::guard('artist_api')->user();
        return $this->sendResponse([$artist,'Profile is displayed successfully'],200);
    }

    public function showArtistsList(){
        $artists=Artist::all();
        return $this->sendResponse([$artists,'Artists list is displayed successfully'],200);
    }

    public function showArtist($id){
        $artist=Artist::find($id);
        if(!$artist){
            return $this->sendError('Account is not found',404);
        }
        return $this->sendResponse([$artist,'Artist profile is displayed successfully'],200);
    }
}
