<?php

namespace App\Http\Controllers;
use App\Models\Artist;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class HomeController extends Controller
{
    use GeneralTrait;

    public function home()
    {
        $user=Auth::guard('api')->user();
        $followedArtistIds=$user->followings()->pluck('followed_id')->toArray();
        $followedArtistsPaintings=collect();
        foreach($followedArtistIds as $artistId){
            $artist=Artist::find($artistId);
            $artistPaintings=$artist->paintings()->get();
            foreach($artistPaintings as $painting){
                $createdAt=Carbon::parse($painting->creation_date);
                $timeAgo=$createdAt->diffForHumans();
                $painting->formatted_creation_date=$timeAgo;
            }
            $followedArtistsPaintings=$followedArtistsPaintings->concat($artistPaintings)
            ->sortByDesc('creation_date'); 
        }
        $unfollowedArtistsIds=Artist::whereNotIn('id',$followedArtistIds)->pluck('id')->toArray();
        $unfollowedArtistsPaintings=collect();
        foreach($unfollowedArtistsIds as $othersId){
            $other=Artist::find($othersId);
            $otherPaintings=$other->paintings()->get();
            foreach($otherPaintings as $painting){
                $createdAt=Carbon::parse($painting->creation_date);
                $timeAgo=$createdAt->diffForHumans();
                $painting->formatted_creation_date=$timeAgo;
            }
            $unfollowedArtistsPaintings=$unfollowedArtistsPaintings->concat($otherPaintings)
            ->sortByDesc('creation_date'); 
        }
        $content=$followedArtistsPaintings->concat($unfollowedArtistsPaintings);
        return $this->sendResponse([$content,'Welcome to home page'],200);
    }

    public function homeForArtist()
    {
        $user=Auth::guard('artist_api')->user();
        $followedArtistIds=$user->followings()->pluck('followed_id')->toArray();
        $followedArtistsPaintings=collect();
        foreach($followedArtistIds as $artistId){
            $artist=Artist::find($artistId);
            $artistPaintings=$artist->paintings()->get();
            foreach($artistPaintings as $painting){
                $createdAt=Carbon::parse($painting->creation_date);
                $timeAgo=$createdAt->diffForHumans();
                $painting->formatted_creation_date=$timeAgo;
            }
            $followedArtistsPaintings=$followedArtistsPaintings->concat($artistPaintings)
            ->sortByDesc('creation_date');
        }
        $unfollowedArtistsIds=Artist::whereNotIn('id',$followedArtistIds)->pluck('id')->toArray();
        $unfollowedArtistsPaintings=collect();
        foreach($unfollowedArtistsIds as $othersId){
            $other=Artist::find($othersId);
            $otherPaintings=$other->paintings()->get();
            foreach($otherPaintings as $painting){
                $createdAt=Carbon::parse($painting->creation_date);
                $timeAgo=$createdAt->diffForHumans();
                $painting->formatted_creation_date=$timeAgo;
            }
            $unfollowedArtistsPaintings=$unfollowedArtistsPaintings->concat($otherPaintings)
            ->sortByDesc('creation_date');
        }
        $content=$followedArtistsPaintings->concat($unfollowedArtistsPaintings);
        return $this->sendResponse([$content,'Welcome to home page'],200);
    }
}
