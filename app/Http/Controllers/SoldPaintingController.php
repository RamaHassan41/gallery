<?php

namespace App\Http\Controllers;
use App\Traits\GeneralTrait;
use App\Models\Sold_Painting;
use Illuminate\Http\Request;
use Carbon\Carbon;

class SoldPaintingController extends Controller
{
    use GeneralTrait;

    public function index()
    {
        // $sold_paintings=Sold_Painting::orderByDesc('sell_date')
        // ->with(['painting',function($query){
        //     $query->select('id','title','url');
        // },'user',function($query){
        //     $query->select('id','name','image');
        // }])->get();

        $sold_paintings=Sold_Painting::orderByDesc('sell_date')
        ->with(['painting:id,title,url,artist_id','user:id,name,image'])->get();
        if(!$sold_paintings){
            return $this->sendResponse('No sold paintings are exist to display',200);
        }
        if(!$sold_paintings){
            return $this->sendResponse('No sold paintings are exist to display',200);
        }
        foreach($sold_paintings as $sold_painting){
            $soldAt=Carbon::parse($sold_painting->sell_date);
            $timeAgo=$soldAt->diffForHumans();
            $sold_painting->formatted_selling_date=$timeAgo;
        }
        return $this->sendResponse([$sold_paintings,'Sold paintings are displayed successfully'],200);
    }
}
