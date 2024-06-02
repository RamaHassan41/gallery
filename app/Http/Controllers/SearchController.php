<?php

namespace App\Http\Controllers;

use App\Traits\GeneralTrait;
use App\Models\Painting;
use App\Models\Artist;
use App\Models\Type;
use App\Models\Painting_Evaluation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SearchController extends Controller
{
    use GeneralTrait;

    public function searchForArtist(Request $request){
        $artists=Artist::filter($request)->select('id','name','image')
        ->orderByDesc('created_at')->get();
        if($artists->isEmpty()){
            return $this->sendResponse('Artists list is empty',200);
        }
        return $this->sendResponse([$artists,'Search result is displayed successfully'],200);
    }

    public function searchForType(Request $request){
        $type=Type::filter($request)->with(['paintings'=>function($query){
            $query->orderByDesc('creation_date');     
        }])->get();
        return $this->sendResponse([$type,'Search result is displayed successfully'],200);
    }

    public function searchForPainting(Request $request){
        $paintings=Painting::filter($request);
        if($request->has('sort_by')&&$request->sort_by=='rate'){
            $paintings->leftJoin('painting_evaluations','paintings.id','=','painting_evaluations.painting_id')
            ->select('paintings.title',DB::raw('AVG(painting_evaluations.degree) as average_rating'))
            ->groupBy('paintings.id','paintings.title','paintings.description','paintings.url',
            'paintings.artist_id','paintings.archive_id','paintings.type_id','paintings.creation_date',
            'paintings.size','paintings.price','paintings.interactions_number','paintings.comments_number',
            'paintings.rates_number','paintings.reports_number',
            'paintings.created_at','paintings.updated_at',
            )->orderByDesc('average_rating');
        }
        elseif($request->has('sort_by')&&$request->sort_by=='price'){
            $paintings->orderByDesc('price');
        }
        else{
            $paintings->orderByDesc('creation_date');
        }
        $paintings=$paintings->with(['artist'=>function($query){
            $query->select('id','name','image');
        },
        'type'=>function($query){
            $query->select('id','type_name');
        }])->get();
        if($paintings->isEmpty()){
            return $this->sendResponse('Serach result list is empty',200);
        }
        return $this->sendResponse([$paintings,'Search result is displayed successfully'],200);
    }
















    public function search(Request $request){
        $query=Painting::query();
        if($request->has('artist_name')){
            $query->where('artist_name','like','%'.$request->artist_name.'%');
        }
        elseif($request->has('price')){
            $query->where('price',$request->price);
        }
        elseif($request->has('creation_date')){
            $query->where('creation_date',$request->creation_date);
        }
        elseif($request->has('type')){
            $query->where('type','like','%'.$request->type.'%');
        }
        else{
            return $this->sendError('You are entering a wrong key for searching');
        }
        $paintings=$query->get();
        if($paintings->isEmpty()){
            return $this->sendResponse('No paintings','Paintings list is empty');

        }
        else{
            $response = $this->sendResponse($paintings, 'Paintings retrieved successfully');
            $applyFilters = false;
            if ($request->has('filter_artist_name') || $request->has('filter_price') || $request->has('filter_creation_date') || $request->has('filter_type')) {
                $applyFilters = true;
            }
            if ($applyFilters) {
                $filteredPaintings = $paintings;
                if ($request->has('filter_artist_name')) {
                    $filteredPaintings = $filteredPaintings->where('artist_name',$request->filter_artist_name);
                }
                elseif ($request->has('filter_price')) {
                    $filteredPaintings = $filteredPaintings->where('price', $request->filter_price);
                }
                elseif ($request->has('filter_creation_date')) {
                    $filteredPaintings = $filteredPaintings->where('creation_date', $request->filter_creation_date);
                }
                elseif ($request->has('filter_type')) {
                    $filteredPaintings = $filteredPaintings->where('type',$request->filter_type);
                }
                else{
                    return $this->sendError('You are entering a wrong key for filtering');
                }
                if ($filteredPaintings->isEmpty()) {
                    return $this->sendResponse('No paintings', 'Filtered paintings list is empty');
                } else {
                    $response = $this->sendResponse($filteredPaintings, 'Paintings retrieved and filtered successfully');
                }
            }
            return $response;
        }  
    }
}