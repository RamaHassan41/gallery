<?php

namespace App\Http\Controllers;
use Validator;
use App\Traits\GeneralTrait;
use App\Models\Follow;
use App\Models\Artist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class FollowController extends Controller
{
    use GeneralTrait;

    public function showArtistFollowers($id){
        $artist=Artist::find($id);
        if(!$artist){
            return $this->sendError('Account is not found',404);
        }
        $followers=$artist->followers()->orderByDesc('date')->get();
        if($followers->isEmpty()){
            return $this->sendResponse('This artist has not have any follower',200);
        }
        foreach($followers as $follower){
            $followAt=Carbon::parse($follower->date);
            $timeAgo=$followAt->diffForHumans();
            $follower->formatted_following_date=$timeAgo;
        }
        return $this->sendResponse([$followers,'Followers are displayed successfully'],200);
    }

    public function changeFollowing($followed_id){
        $artist=Artist::find($followed_id);
        if(!$artist){
            return $this->sendError('Account is not found',404);
        }
        $user=Auth::guard('api')->user();
        $follow=$user->followings()->where('followed_id',$followed_id)
        ->where('follower_type','App\\Models\\User')->first();
        if($follow){
            $follow->delete();
            $artist->followers_number--;
            $artist->save();
            return $this->sendResponse('Unfollow is done successfully',200);
        }
        $follow=$user->followings()->create([
            'followed_id'=>$artist->id,
            'date'=>now()->toDateTimeString(),
        ]);
        $artist->followers_number++;
        $artist->save();
        return $this->sendResponse([$follow,'Follow is done successfully'],200);
    }

    public function changeFollowingFromArtist($followed_id){
        $artist=Artist::find($followed_id);
        if(!$artist){
            return $this->sendError('Account is not found',404);
        }
        $follower_artist=Auth::guard('artist_api')->user();
        $follow=$follower_artist->followings()->where('followed_id',$followed_id)
        ->where('follower_type','App\\Models\\Artist')->first();
        if($follower_artist->id==$followed_id){
            return $this->sendError('You can not follow yourself',401);
        }
        if($follow){
            $follow->delete();
            $artist->followers_number--;
            $artist->save();
            return $this->sendResponse('Unfollow is done successfully',200);
        }
        $follow=$follower_artist->followings()->create([
            'followed_id'=>$artist->id,
            'date'=>now()->toDateTimeString(),
        ]);
        $artist->followers_number++;
        $artist->save();
        return $this->sendResponse([$follow,'Follow is done successfully'],200);
    }



    // public function showArtistSingleFollower($artist_id,$user_id){
    //     $artist=Artist::find($artist_id);
    //     if($artist){
    //        $user->id=Auth::find($user_id);
    //        if($user){
    //             $status=Follow::where('followed_id',$artist_id)
    //                             ->where('follower_id',$user_id)->get();
    //         if($status){
    //             return $this->sendResponse($user,'User retrieved successfully');
    //         }
    //         else{
    //             return $this->sendError('This user does not follow this artist');
    //         }
    //        }
    //        return $this->sendError('You are searching for wrong user account');         
    //     }
    //     return $this->sendError('You are searching for wrong artist account');
    // }
}
