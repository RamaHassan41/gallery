<?php

namespace App\Http\Controllers;
use Validator;
use App\Traits\GeneralTrait;
use App\Models\Article;
use Illuminate\Http\Request;
use App\Models\Article_Interaction;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class Article_InteractionController extends Controller
{
    use GeneralTrait;

    public function showArticleInteractions($id)
    {
        $article=Article::find($id);
        if(!$article){
            return $this->sendError('Article is not found',404);
        }
        $interactions=$article->interactions()->orderByDesc('date')->get();
        if($interactions->isEmpty()){
            return $this->sendResponse('This article has not have any interaction',200);
        }
        foreach($interactions as $interaction){
            $createdAt=Carbon::parse($interaction->date);
            $timeAgo=$createdAt->diffForHumans();
            $interaction->formatted_creation_date=$timeAgo;
        }
        return $this->sendResponse([$interactions,'Interactions are displayed successfully'],200);
    }

    public function changeLike($article_id){
        $article=Article::find($article_id);
        if(!$article){
            return $this->sendError('Article is not found',404); 
        }
        $user=Auth::guard('api')->user();
        $like=$user->article_interactions()->where('article_id',$article_id)
        ->where('reactant_type','App\\Models\\User')->first();
        if($like&&$like->type=='dislike'){
            $like->type='like';
            $like->save();
            return $this->sendResponse([$like,'Changing from dislike to like is done successfully'],200);
        }
        elseif($like&&$like->type=='like'){
            $like->delete();
            $article->interactions_number--;
            $article->save();
            return $this->sendResponse([$like,'Like is deleted successfully'],200);
        }
        else{
            $like=$user->article_interactions()->create([
                'type'=>'like',
                'article_id'=>$article->id,
                'date'=>now()->toDateTimeString(),
            ]);
            $article->interactions_number++;
            $article->save();
            return $this->sendResponse([$like,'Like is added successfully'],200);
        } 
    }
  
    public function changeDisLike($article_id){
        $article=Article::find($article_id);
        if(!$article){
            return $this->sendError('Article is not found',404); 
        }
        $user=Auth::guard('api')->user();
        $dislike=$user->article_interactions()->where('article_id',$article_id)
        ->where('reactant_type','App\\Models\\User')->first();
        if($dislike&&$dislike->type=='like'){
            $dislike->type='dislike';
            $dislike->save();
            return $this->sendResponse([$dislike,'Changing from like to dislike is done successfully'],200);
        }
        elseif($dislike&&$dislike->type=='dislike'){
            $dislike->delete();
            $article->interactions_number--;
            $article->save();
            return $this->sendResponse([$dislike,'Dislike is deleted successfully'],200);
        }
        else{
            $dislike=$user->article_interactions()->create([
                'type'=>'dislike',
                'article_id'=>$article->id,
                'date'=>now()->toDateTimeString(),
            ]);
            $article->interactions_number++;
            $article->save();
            return $this->sendResponse([$dislike,'Dislike is added successfully'],200);
        } 
    }

    public function changeLikeFromArtist($article_id){
        $article=Article::find($article_id);
        if(!$article){
            return $this->sendError('Article is not found',404); 
        }
        $artist=Auth::guard('artist_api')->user();
        $like=$artist->article_interactions()->where('article_id',$article_id)
        ->where('reactant_type','App\\Models\\Artist')->first();
        if($like&&$like->type=='dislike'){
            $like->type='like';
            $like->save();
            return $this->sendResponse([$like,'Changing from dislike to like is done successfully'],200);
        }
        elseif($like&&$like->type=='like'){
            $like->delete();
            $article->interactions_number--;
            $article->save();
            return $this->sendResponse([$like,'Like is deleted successfully'],200);
        }
        else{
            $like=$artist->article_interactions()->create([
                'type'=>'like',
                'article_id'=>$article->id,
                'date'=>now()->toDateTimeString(),
            ]);
            $article->interactions_number++;
            $article->save();
            return $this->sendResponse([$like,'Like is added successfully'],200);
        } 
    }

    public function changeDisLikeFromArtist($article_id){
        $article=Article::find($article_id);
        if(!$article){
            return $this->sendError('Article is not found',404); 
        }
        $artist=Auth::guard('artist_api')->user();
        $dislike=$artist->article_interactions()->where('article_id',$article_id)
        ->where('reactant_type','App\\Models\\Artist')->first();
        if($dislike&&$dislike->type=='like'){
            $dislike->type='dislike';
            $dislike->save();
            return $this->sendResponse([$dislike,'Changing from like to dislike is done successfully'],200);
        }
        elseif($dislike&&$dislike->type=='dislike'){
            $dislike->delete();
            $article->interactions_number--;
            $article->save();
            return $this->sendResponse([$dislike,'Dislike is deleted successfully'],200);
        }
        else{
            $dislike=$artist->article_interactions()->create([
                'type'=>'dislike',
                'article_id'=>$article->id,
                'date'=>now()->toDateTimeString(),
            ]);
            $article->interactions_number++;
            $article->save();
            return $this->sendResponse([$dislike,'Dislike is added successfully'],200);
        } 
    }
}

