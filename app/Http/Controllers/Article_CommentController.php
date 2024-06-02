<?php

namespace App\Http\Controllers;
use Validator;
use App\Traits\GeneralTrait;
use App\Models\Article;
use Illuminate\Http\Request;
use App\Http\Requests\CommentRequest;
use App\Models\Article_Comment;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class Article_CommentController extends Controller
{
    use GeneralTrait;
    
    public function showArticleComments($id)
    {
        $article=Article::find($id);
        if(!$article){
            return $this->sendError('Article is not found',404);
        }
        $comments=$article->comments()->orderByDesc('date')->get();
        if($comments->isEmpty()){
            return $this->sendResponse('This Article has not have any comment',200);
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
        $article=Article::find($id);
        if(!$article){
            return $this->sendError('Article is not found',404);
        }
        $user=Auth::guard('api')->user();
        $user->article_comments()->create([
            'comment_text'=>$request->comment_text,
            'article_id'=>$article->id,
            'date'=>now()->toDateTimeString(),
        ]);
        $article->comments_number++;
        $article->save();
        $comments=$article->comments()->get();
        return $this->sendResponse([$comments,'Comment is added successfully'],200);
    }

    public function update(CommentRequest $request,$comment_id)
    {
        $comment=Article_Comment::find($comment_id);
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
        $comment=Article_Comment::find($id);
        if(!$comment){
            return $this->sendError('Comment is not found',404);
        }
        $user=Auth::guard('api')->user();
        if($comment->user_id==$user->id&&$comment->user_type=='App\\Models\\User'){
            $article=$comment->article;
            $comment->delete();
            $article->comments_number--;
            $article->save();
            return $this->sendResponse([$comment,'Comment is deleted successfully'],200);
        }
        return $this->sendError('Unauthorized',401);
    }

    public function storeFromArtist(CommentRequest $request,$id)
    {
        $article=Article::find($id);
        if(!$article){
            return $this->sendError('Article is not found',404);
        }
        $artist=Auth::guard('artist_api')->user();
        $comment=$artist->article_comments()->create([
            'comment_text'=>$request->comment_text,
            'article_id'=>$article->id,
            'date'=>now()->toDateTimeString(),
        ]);
        $article->comments_number++;
        $article->save();
        $comments=$article->comments()->get();
        return $this->sendResponse([$comments,'Comment is added successfully'],200);
    }

    public function updateFromArtist(CommentRequest $request,$comment_id)
    {
        $comment=Article_Comment::find($comment_id);
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
        $comment=Article_Comment::find($id);
        if(!$comment){
            return $this->sendError('Comment is not found',404);
        }
        $artist=Auth::guard('artist_api')->user();
        if($comment->user_id==$artist->id&&$comment->user_type=='App\\Models\\Artist'){
            $article=$comment->article;
            $comment->delete();
            $article->comments_number--;
            $article->save();
            return $this->sendResponse([$comment,'Comment is deleted successfully'],200);
        }
        return $this->sendError('Unauthorized',401);
    }
}
