<?php

namespace App\Http\Controllers;
use App\Traits\GeneralTrait;
use App\Models\User;
use App\Models\Article;
use App\Models\Favourite;
use App\Models\Favourite_Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavouriteArticleController extends Controller
{
    use GeneralTrait;

    public function changeForArticle($article_id)
    {
        $user=Auth::guard('api')->user();
        $favourite=$user->favourite()->where('user_type','App\\Models\\User')->first();
        $article=Article::find($article_id);
        if(!$article){
            return $this->sendError('Article is not found',404);
        }
        $favouriteArticle=$favourite->favourite_articles()->where('article_id',$article_id)->first();
        if($favouriteArticle){
            $favouriteArticle->delete();
            $favourite->elements_number--;
            $favourite->save();
            return $this->sendResponse([$favourite,$favouriteArticle,'Article is deleted from favourite successfully'],200);

        }
        $favouriteArticle=$favourite->favourite_articles()->create([
            'article_id'=>$article->id,
            'adding_date'=>now()->toDateTimeString(),
        ]);
        $favourite->elements_number++;
        $favourite->save();
        return $this->sendResponse([$favourite,$favouriteArticle,'Article is added to favourite successfully'],200);
    }

    public function changeForArticleByArtist($article_id)
    {
        $artist=Auth::guard('artist_api')->user();
        $favourite=$artist->favourite()->where('user_type','App\\Models\\Artist')->first();
        $article=Article::find($article_id);
        if(!$article){
            return $this->sendError('Article is not found',404);
        }
        $favouriteArticle=$favourite->favourite_articles()->where('article_id',$article_id)->first();
        if($favouriteArticle){
            $favouriteArticle->delete();
            $favourite->elements_number--;
            $favourite->save();
            return $this->sendResponse([$favourite,$favouriteArticle,'Article is deleted from favourite successfully'],200);
        }
        $favouriteArticle=$favourite->favourite_articles()->create([
            'article_id'=>$article->id,
            'adding_date'=>now()->toDateTimeString(),
        ]);
        $favourite->elements_number++;
        $favourite->save();
        return $this->sendResponse([$favourite,$favouriteArticle,'Article is added to favourite successfully'],200);
    }
}
