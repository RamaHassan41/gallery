<?php

namespace App\Http\Controllers;

use Validator;
use App\Traits\GeneralTrait;
use App\Models\Article;
use App\Models\Artist;
use Illuminate\Http\Request;
use App\Http\Requests\AddArticleRequest;
use App\Http\Requests\EditArticleRequest;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ArticleController extends Controller
{
    use GeneralTrait;
    /**
     * Display a listing of the resource.
     */
    
    public function artistArticles($id){
        $artist=Artist::find($id);
        if(!$artist){
            return $this->sendError('Account is not found',404);
        }
        $articles=$artist->articles()->orderByDesc('creation_date')->get();
        if($articles->isEmpty()){
            return $this->sendResponse('This artist has not have any article',200);
        }
        foreach($articles as $article){
            $createdAt=Carbon::parse($article->creation_date);
            $timeAgo=$createdAt->diffForHumans();
            $article->formatted_creation_date=$timeAgo;
        }
        return $this->sendResponse([$articles,'Articles are displayed successfully'],200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AddArticleRequest $request)
    {
        $artist=Auth::guard('artist_api')->user();
        $article=$artist->articles()->create([
            'title'=>$request->title,
            'description'=>$request->description,
            'url'=>$this->setArticleImage($request->url),
            'creation_date'=>now()->toDateTimeString(),
        ]);
        return $this->sendResponse([$article,'Article is added successfully'],200);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $article=Article::find($id); 
        if(!$article){
            return $this->sendError('Article is not found',404);
        }        
        $createdAt=Carbon::parse($article->creation_date);
        $timeAgo=$createdAt->diffForHumans();
        $article->formatted_creation_date=$timeAgo;
        $articleDetails=$article->loadMissing('artist');
        return $this->sendResponse([$articleDetails,'Article is displayed successfully'],200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(EditArticleRequest $request,$id)
    {
        $article=Article::find($id);
        if(!$article){
            return $this->sendError('Article is not found',404);
        }
        if($article->artist_id!=Auth::guard('artist_api')->id()){
            return $this->sendError('Unauthorized',401);
        }
        if($request->url)
        {
            $this->deleteImage($article->url);
        }
        $article['title']=isset($request->title)?$request->title:$article->title;
        $article['description']=isset($request->description)?$request->description:$article->description;
        $article['url']=isset($request->url)?$this->setArticleImage($request->url):$article->url;
        $article->save();
        return $this->sendResponse([$article,'Article is updated successfully'],200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $article=Article::find($id);
        if(!$article){
            return $this->sendError('Article is not found',404);
        }
        if($article->artist_id!=Auth::guard('artist_api')->id()){
            return $this->sendError('Unauthorized',401);
        }
        $this->deleteImage($article->url);
        $article->delete();
        return $this->sendResponse([$article,'Article is deleted successfully'],200);
    }
}
