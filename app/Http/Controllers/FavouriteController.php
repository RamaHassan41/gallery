<?php

namespace App\Http\Controllers;
use App\Traits\GeneralTrait;
use App\Models\User;
use App\Models\Painting;
use App\Models\Favourite;
use App\Models\Favourite_Painting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class FavouriteController extends Controller
{
    use GeneralTrait;

    public function showFavouriteList()
    {
        $user=Auth::guard('api')->user();
        $favourite=$user->favourite()->where('user_type','App\\Models\\User')->first(); 
        $favouritePaintings=$favourite->favourite_paintings()->get();
        $favouriteArticles=$favourite->favourite_articles()->get();
        if($favouritePaintings->isEmpty()&&$favouriteArticles->isEmpty()){
            return $this->sendResponse('Favourite list is empty',200);
        }
        foreach($favouritePaintings as $painting){
            $addedAt=Carbon::parse($painting->adding_date);
            $timeAgo=$addedAt->diffForHumans();
            $painting->formatted_adding_date=$timeAgo;
        }
        foreach($favouriteArticles as $article){
            $addedAt=Carbon::parse($article->adding_date);
            $timeAgo=$addedAt->diffForHumans();
            $article->formatted_adding_date=$timeAgo;
        }
        $elements=$favouritePaintings->concat($favouriteArticles)->sortByDesc('adding_date');
        return $this->sendResponse([$favourite,$elements,'Favourite list is displayed successfully'],200);
    }

    public function changeForPainting($painting_id)
    {
        $user=Auth::guard('api')->user();
        $favourite=$user->favourite()->where('user_type','App\\Models\\User')->first();
        $painting=Painting::find($painting_id);
        if(!$painting){
            return $this->sendError('Painting is not found',404);
        }
        $favouritePainting=$favourite->favourite_paintings()->where('painting_id',$painting_id)->first();
        if($favouritePainting){
            $favouritePainting->delete();
            $favourite->elements_number--;
            $favourite->save();
            return $this->sendResponse([$favourite,$favouritePainting,'Painting is deleted from favourite successfully'],200);
        }
        $favouritePainting=$favourite->favourite_paintings()->create([
            'painting_id'=>$painting->id,
            'adding_date'=>now()->toDateTimeString(),
        ]);
        $favourite->elements_number++;
        $favourite->save();
        return $this->sendResponse([$favourite,$favouritePainting,'Painting is added to favourite successfully'],200);
    }

    public function showFavouriteListByArtist()
    {
        $artist=Auth::guard('artist_api')->user();
        $favourite=$artist->favourite()->where('user_type','App\\Models\\Artist')->first();        
        $favouritePaintings=$favourite->favourite_paintings()->get();
        $favouriteArticles=$favourite->favourite_articles()->get();
        if($favouritePaintings->isEmpty()&&$favouriteArticles->isEmpty()){
            return $this->sendResponse('Favourite list is empty',200);
        }
        foreach($favouritePaintings as $painting){
            $addedAt=Carbon::parse($painting->adding_date);
            $timeAgo=$addedAt->diffForHumans();
            $painting->formatted_adding_date=$timeAgo;
        }
        foreach($favouriteArticles as $article){
            $addedAt=Carbon::parse($article->adding_date);
            $timeAgo=$addedAt->diffForHumans();
            $article->formatted_adding_date=$timeAgo;
        }
        $elements=$favouritePaintings->concat($favouriteArticles)->sortByDesc('adding_date');
        return $this->sendResponse([$favourite,$elements,'Favourite list is displayed successfully'],200);
    }

    public function changeForPaintingByArtist($painting_id)
    {
        $artist=Auth::guard('artist_api')->user();
        $favourite=$artist->favourite()->where('user_type','App\\Models\\Artist')->first();
        $painting=Painting::find($painting_id);
        if(!$painting){
            return $this->sendError('Painting is not found',404);
        }
        $favouritePainting=$favourite->favourite_paintings()->where('painting_id',$painting_id)->first();
        if($favouritePainting){
            $favouritePainting->delete();
            $favourite->elements_number--;
            $favourite->save();
            return $this->sendResponse([$favourite,$favouritePainting,'Painting is deleted from favourite successfully'],200);
        }
        $favouritePainting=$favourite->favourite_paintings()->create([
            'painting_id'=>$painting->id,
            'adding_date'=>now()->toDateTimeString(),
        ]);
        $favourite->elements_number++;
        $favourite->save();
        return $this->sendResponse([$favourite,$favouritePainting,'Painting is added to favourite successfully'],200);
    }
}
