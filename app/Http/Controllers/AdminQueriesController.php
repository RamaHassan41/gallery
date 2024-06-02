<?php

namespace App\Http\Controllers;

use App\Traits\GeneralTrait;
use App\Models\Painting;
use App\Models\Artist;
use App\Models\Type;
use App\Models\Article;
use App\Models\Sold_Painting;
use Illuminate\Http\Request;

class AdminQueriesController extends Controller
{
    use GeneralTrait;

    public function queryAboutArtist(Request $request){
        $artists=Artist::filter($request)->select('id','name','image')
        ->orderByDesc('created_at')
        ->get();
        if($artists->isEmpty()){
            return $this->sendResponse('Artists list is empty',200);
        }
        return $this->sendResponse([$artists,'Artist list is displayed successfully'],200);
    }

    public function queryAboutType(Request $request){
        $type=Type::filter($request)->with(['paintings'=>function($query){
            $query->orderByDesc('creation_date');     
        }])->get();
        return $this->sendResponse([$type,'Type details are displayed successfully'],200);
    }

    public function queryAboutPainting(Request $request){
        $painting=Painting::filter($request)->with(['artist'=>function($query){
            $query->select('id','name','image');
        },
        'type'=>function($query){
            $query->select('id','type_name');
        }])->orderByDesc('creation_date')->get();
        return $this->sendResponse([$painting,'Paintings list is displayed successfully'],200);
    }

    public function queryAboutArticle(Request $request){
        $article=Article::filter($request)->with(['artist'=>function($query){
            $query->select('id','name','image');     
        }])->orderByDesc('creation_date')->get();
        return $this->sendResponse([$article,'Article list is displayed successfully'],200);
    }

    public function queryAboutSoldPainting(Request $request){
        $soldPainting=Sold_Painting::filter($request)->with(['artist'=>function($query){
            $query->select('id','name','image');     
        },
        'user'=>function($query){
            $query->select('id','name','image');
        }])->orderByDesc('sell_date')->get();
        return $this->sendResponse([$soldPainting,'Sold paintings list is displayed successfully'],200);
    }
}
