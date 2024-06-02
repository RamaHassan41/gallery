<?php

namespace App\Http\Controllers;
use App\Models\Artist;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ArticlePageController extends Controller
{
    use GeneralTrait;

    public function userArticlePage()
    {
        $user=Auth::guard('api')->user();
        $followedArtistIds=$user->followings()->pluck('followed_id')->toArray();
        $followedArtistsArticles=collect();
        foreach($followedArtistIds as $artistId){
            $artist=Artist::find($artistId);
            $artistArticles=$artist->articles()->get();
            foreach($artistArticles as $article){
                $createdAt=Carbon::parse($article->creation_date);
                $timeAgo=$createdAt->diffForHumans();
                $article->formatted_creation_date=$timeAgo;
            }
            $followedArtistsArticles=$followedArtistsArticles->concat($artistArticles)
            ->sortByDesc('creation_date');
        }
        $unfollowedArtistsIds=Artist::whereNotIn('id',$followedArtistIds)->pluck('id')->toArray();
        $unfollowedArtistsArticles=collect();
        foreach($unfollowedArtistsIds as $othersId){
            $other=Artist::find($othersId);
            $otherArticles=$other->articles()->get();
            foreach($otherArticles as $article){
                $createdAt=Carbon::parse($article->creation_date);
                $timeAgo=$createdAt->diffForHumans();
                $article->formatted_creation_date=$timeAgo;
            }
            $unfollowedArtistsArticles=$unfollowedArtistsArticles->concat($otherArticles)
            ->sortByDesc('creation_date');
        }
        $content=$followedArtistsArticles->concat($unfollowedArtistsArticles);
        return $this->sendResponse([$content,'Articles page is displayed successfully'],200);
    }

    public function artistArticlePage()
    {
        $user=Auth::guard('artist_api')->user();
        $followedArtistIds=$user->followings()->pluck('followed_id')->toArray();
        $followedArtistsArticles=collect();
        foreach($followedArtistIds as $artistId){
            $artist=Artist::find($artistId);
            $artistArticles=$artist->articles()->get();
            foreach($artistArticles as $article){
                $createdAt=Carbon::parse($article->creation_date);
                $timeAgo=$createdAt->diffForHumans();
                $article->formatted_creation_date=$timeAgo;
            }
            $followedArtistsArticles=$followedArtistsArticles->concat($artistArticles)
            ->sortByDesc('creation_date');
        }
        $unfollowedArtistsIds=Artist::whereNotIn('id',$followedArtistIds)->pluck('id')->toArray();
        $unfollowedArtistsArticles=collect();
        foreach($unfollowedArtistsIds as $othersId){
            $other=Artist::find($othersId);
            $otherArticles=$other->articles()->get();
            foreach($otherArticles as $article){
                $createdAt=Carbon::parse($article->creation_date);
                $timeAgo=$createdAt->diffForHumans();
                $article->formatted_creation_date=$timeAgo;
            }
            $unfollowedArtistsArticles=$unfollowedArtistsArticles->concat($otherArticles)
            ->sortByDesc('creation_date'); 
        }
        $content=$followedArtistsArticles->concat($unfollowedArtistsArticles);
        return $this->sendResponse([$content,'Articles page is displayed successfully'],200);
    }
}
