<?php

namespace App\Http\Controllers;
use Validator;
use App\Traits\GeneralTrait;
use App\Models\Painting;
use Illuminate\Http\Request;
use App\Models\Painting_Interaction;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class Painting_InteractionController extends Controller
{
    use GeneralTrait;

    public function showPaintingInteractions($id)
    {
        $painting=Painting::find($id);
        if(!$painting){
            return $this->sendError('Painting is not found',404);
        }
        $interactions=$painting->interactions()->orderByDesc('date')->get();
        if($interactions->isEmpty()){
            return $this->sendResponse('This painting has not have any interaction',200);
        }
        foreach($interactions as $interaction){
            $createdAt=Carbon::parse($interaction->date);
            $timeAgo=$createdAt->diffForHumans();
            $interaction->formatted_creation_date=$timeAgo;
        }
        return $this->sendResponse([$interactions,'Interactions are displayed successfully'],200);
    }

    public function changeLike($painting_id){
        $painting=Painting::find($painting_id);
        if(!$painting){
            return $this->sendError('Painting is not found',404); 
        }
        $user=Auth::guard('api')->user();
        $like=$user->painting_interactions()->where('painting_id',$painting_id)
        ->where('reactant_type','App\\Models\\User')->first();
        if($like&&$like->type=='dislike'){
            $like->type='like';
            $like->save();
            return $this->sendResponse([$like,'Changing from dislike to like is done successfully'],200);
        }
        elseif($like&&$like->type=='like'){
            $like->delete();
            $painting->interactions_number--;
            $painting->save();
            return $this->sendResponse([$like,'Like is deleted successfully'],200);
        }
        else{
            $like=$user->painting_interactions()->create([
                'type'=>'like',
                'painting_id'=>$painting->id,
                'date'=>now()->toDateTimeString(),
            ]);
            $painting->interactions_number++;
            $painting->save();
            return $this->sendResponse([$like,'Like is added successfully'],200);
        } 
    }
  
    public function changeDisLike($painting_id){
        $painting=Painting::find($painting_id);
        if(!$painting){
            return $this->sendError('Painting is not found',404); 
        }
        $user=Auth::guard('api')->user();
        $dislike=$user->painting_interactions()->where('painting_id',$painting_id)
        ->where('reactant_type','App\\Models\\User')->first();
        if($dislike&&$dislike->type=='like'){
            $dislike->type='dislike';
            $dislike->save();
            return $this->sendResponse([$dislike,'Changing from like to dislike is done successfully'],200);
        }
        elseif($dislike&&$dislike->type=='dislike'){
            $dislike->delete();
            $painting->interactions_number--;
            $painting->save();
            return $this->sendResponse([$dislike,'Dislike is deleted successfully'],200);
        }
        else{
            $dislike=$user->painting_interactions()->create([
                'type'=>'dislike',
                'painting_id'=>$painting->id,
                'date'=>now()->toDateTimeString(),
            ]);
            $painting->interactions_number++;
            $painting->save();
            return $this->sendResponse([$dislike,'Dislike is added successfully'],200);
        } 
    }

    public function changeLikeFromArtist($painting_id){
        $painting=Painting::find($painting_id);
        if(!$painting){
            return $this->sendError('Painting is not found',404); 
        }
        $artist=Auth::guard('artist_api')->user();
        $like=$artist->painting_interactions()->where('painting_id',$painting_id)
        ->where('reactant_type','App\\Models\\Artist')->first();
        if($like&&$like->type=='dislike'){
            $like->type='like';
            $like->save();
            return $this->sendResponse([$like,'Changing from dislike to like is done successfully'],200);
        }
        elseif($like&&$like->type=='like'){
            $like->delete();
            $painting->interactions_number--;
            $painting->save();
            return $this->sendResponse([$like,'Like is deleted successfully'],200);
        }
        else{
            $like=$artist->painting_interactions()->create([
                'type'=>'like',
                'painting_id'=>$painting->id,
                'date'=>now()->toDateTimeString(),
            ]);
            $painting->interactions_number++;
            $painting->save();
            return $this->sendResponse([$like,'Like is added successfully'],200);
        } 
    }

    public function changeDisLikeFromArtist($painting_id){
        $painting=Painting::find($painting_id);
        if(!$painting){
            return $this->sendError('Painting is not found',404); 
        }
        $artist=Auth::guard('artist_api')->user();
        $dislike=$artist->painting_interactions()->where('painting_id',$painting_id)
        ->where('reactant_type','App\\Models\\Artist')->first();
        if($dislike&&$dislike->type=='like'){
            $dislike->type='dislike';
            $dislike->save();
            return $this->sendResponse([$dislike,'Changing from like to dislike is done successfully'],200);
        }
        elseif($dislike&&$dislike->type=='dislike'){
            $dislike->delete();
            $painting->interactions_number--;
            $painting->save();
            return $this->sendResponse([$dislike,'Dislike is deleted successfully'],200);
        }
        else{
            $dislike=$artist->painting_interactions()->create([
                'type'=>'dislike',
                'painting_id'=>$painting->id,
                'date'=>now()->toDateTimeString(),
            ]);
            $painting->interactions_number++;
            $painting->save();
            return $this->sendResponse([$dislike,'Dislike is added successfully'],200);
        } 
    }
}

