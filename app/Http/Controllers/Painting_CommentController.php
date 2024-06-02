<?php

namespace App\Http\Controllers;

use Validator;
use App\Traits\GeneralTrait;
use App\Models\Painting;
use Illuminate\Http\Request;
use App\Http\Requests\CommentRequest;
use App\Models\Painting_Comment;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class Painting_CommentController extends Controller
{
    use GeneralTrait;

    public function showPaintingComments($id)
    {
        $painting=Painting::find($id);
        if(!$painting){
            return $this->sendError('Painting is not found',404);
        }
        $comments=$painting->comments()->orderByDesc('date')->get();
        if($comments->isEmpty()){
            return $this->sendResponse('This Painting has not have any comment',200);
        }
        foreach($comments as $comment){
            $createdAt=Carbon::parse($comment->date);
            $timeAgo=$createdAt->diffForHumans();
            $comment->formatted_creation_date=$timeAgo;
        }
        return $this->sendResponse([$comments,'Comments are displayed successfully'],200);
    }

    public function store(CommentRequest $request,$id)
    {
        $painting=Painting::find($id);
        if(!$painting){
            return $this->sendError('Painting is not found',404);
        }
        $user=Auth::guard('api')->user();
        $comment=$user->painting_comments()->create([
            'comment_text'=>$request->comment_text,
            'painting_id'=>$painting->id,
            'date'=>now()->toDateTimeString(),
        ]);
        $painting->comments_number++;
        $painting->save();
        return $this->sendResponse([$comment,'Comment is added successfully'],200);
    }

    public function update(CommentRequest $request,$comment_id)
    {
        $comment=Painting_Comment::find($comment_id);
        if(!$comment){
            return $this->sendError('Comment is not found',404); 
        }
        $user=Auth::guard('api')->user();
        if($comment->user_id==$user->id&&$comment->user_type=='App\\Models\\User'){
            $comment->comment_text=$request->comment_text;
            $comment->save();
            return $this->sendResponse([$comment,'Comment is updated successfully'],200);
        }
        return $this->sendError('Unauthorized',401);
        
    }

    public function destroy($id)
    {
        $comment=Painting_Comment::find($id);
        if(!$comment){
            return $this->sendError('Comment is not found',404);
        }
        $user=Auth::guard('api')->user();
        if($comment->user_id==$user->id&&$comment->user_type=='App\\Models\\User'){
            $painting=$comment->painting;
            $comment->delete();
            $painting->comments_number--;
            $painting->save();
            return $this->sendResponse([$comment,'Comment is deleted successfully'],200);    
        }
        return $this->sendError('Unauthorized',401);

    }

    public function storeFromArtist(CommentRequest $request,$id)
    {
        $painting=Painting::find($id);
        if(!$painting){
            return $this->sendError('Painting is not found',404);
        }
        $artist=Auth::guard('artist_api')->user();
        $comment=$artist->painting_comments()->create([
            'comment_text'=>$request->comment_text,
            'painting_id'=>$painting->id,
            'date'=>now()->toDateTimeString(),
        ]);
        $painting->comments_number++;
        $painting->save();
        return $this->sendResponse([$comment,'Comment is added successfully'],200);
    }

    public function updateFromArtist(CommentRequest $request,$comment_id)
    {
        $comment=Painting_Comment::find($comment_id);
        if(!$comment){
            return $this->sendError('Comment is not found',404); 
        }
        $artist=Auth::guard('artist_api')->user();
        if($comment->user_id==$artist->id&&$comment->user_type=='App\\Models\\Artist'){
            $comment->comment_text=$request->comment_text;
            $comment->save();
            return $this->sendResponse([$comment,'Comment is updated successfully'],200);
        }
        return $this->sendError('Unauthorized',401);
    }

    public function destroyFromArtist($id)
    {
        $comment=Painting_Comment::find($id);
        if(!$comment){
            return $this->sendError('Comment is not found',404);
        }
        $artist=Auth::guard('artist_api')->user();
        if($comment->user_id==$artist->id&&$comment->user_type=='App\\Models\\Artist'){
            $painting=$comment->painting;
            $comment->delete();
            $painting->comments_number--;
            $painting->save();
            return $this->sendResponse([$comment,'Comment is deleted successfully'],200);
        }
        return $this->sendError('Unauthorized',401);
    }
}
