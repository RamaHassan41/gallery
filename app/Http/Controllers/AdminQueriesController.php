<?php

namespace App\Http\Controllers;

use App\Traits\GeneralTrait;
use App\Models\Painting;
use App\Models\Artist;
use App\Models\User;
use App\Models\Type;
use App\Models\Article;
use App\Models\Sold_Painting;
use Illuminate\Http\Request;

class AdminQueriesController extends Controller
{
    use GeneralTrait;

    public function queryAboutUser(Request $request){
        $users=User::filter($request)->select('id','name','image')
        ->orderByDesc('created_at')->get();
        if($users->isEmpty()){
            return $this->sendResponse('Users list is empty',200);
        }
        return $this->sendResponse([$users,'Users list is displayed successfully'],200);
    }

    public function queryAboutArtist(Request $request){
        $artists=Artist::filter($request)->select('id','name','image')
        ->orderByDesc('created_at')->get();
        if($artists->isEmpty()){
            return $this->sendResponse('Artists list is empty',200);
        }
        return $this->sendResponse([$artists,'Artists list is displayed successfully'],200);
    }

    public function queryAboutType(Request $request){
        $type=Type::filter($request)->with(['paintings'=>function($query){
            $query->orderByDesc('creation_date');     
        }])->get();
        return $this->sendResponse([$type,'Type details are displayed successfully'],200);
    }

    public function queryAboutPainting(Request $request){
        $paintings=Painting::filter($request)->with(['artist'=>function($query){
            $query->select('id','name','image');
        },
        'type'=>function($query){
            $query->select('id','type_name');
        }])->orderByDesc('creation_date')->get();
        if($paintings->isEmpty()){
            return $this->sendResponse('Paintings list is empty',200);
        }
        return $this->sendResponse([$paintings,'Paintings list is displayed successfully'],200);
    }

    public function queryAboutArticle(Request $request){
        $articles=Article::filter($request)->with(['artist'=>function($query){
            $query->select('id','name','image');     
        }])->orderByDesc('creation_date')->get();
        if($articles->isEmpty()){
            return $this->sendResponse('Articles list is empty',200);
        }
        return $this->sendResponse([$articles,'Articles list is displayed successfully'],200);
    }

    public function queryAboutSoldPainting(Request $request){
        $soldPaintings=Sold_Painting::filter($request)->with(['painting'=>function($query){
            $query->select('id','title','url','artist_id')->with('artist:id,name,image');     
        },
        'user'=>function($query){
            $query->select('id','name','image');
        }])->orderByDesc('sell_date')->get();
        if($soldPaintings->isEmpty()){
            return $this->sendResponse('Sold paintings list is empty',200);
        }
        return $this->sendResponse([$soldPaintings,'Sold paintings list is displayed successfully'],200);
    }
}
