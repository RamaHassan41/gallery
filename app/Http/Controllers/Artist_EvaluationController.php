<?php

namespace App\Http\Controllers;
use Validator;
use App\Traits\GeneralTrait;
use App\Models\Artist;
use Illuminate\Http\Request;
use App\Http\Requests\EvaluationRequest;
use App\Models\Artist_Evaluation;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class Artist_EvaluationController extends Controller
{
    use GeneralTrait;

    public function showArtistRates($artist_id){
        $artist=Artist::find($artist_id);
        if(!$artist){
            return $this->sendError('Account is not found',404);
        }
        $rates=$artist->evaluations()->orderByDesc('date')->get();
        if($rates->isEmpty()){
            return $this->sendResponse('This artist has not have any rate',200);
        }
        foreach($rates as $rate){
            $createdAt=Carbon::parse($rate->date);
            $timeAgo=$createdAt->diffForHumans();
            $rate->formatted_creation_date=$timeAgo;
        }
        return $this->sendResponse([$rates,'Artist rates are displayed successfully'],200);
    }

    public function changeRate(EvaluationRequest $request,$artist_id){
        $artist=Artist::find($artist_id);
        if(!$artist){
            return $this->sendError('Account is not found',404);     
        }
        $user=Auth::guard('api')->user();
        $rate=$user->artist_evaluations()->where('artist_id',$artist_id)
        ->where('rater_type','App\\Models\\User')->first();
        if($rate){
            $rate->degree=$request->degree;
            if($rate->degree==0){
                $rate->delete();
                $artist->rates_number--;
                $artist->save();
                return $this->sendResponse([$rate,'Rate is deleted successfully'],200);
            }
            $rate->save();
            return $this->sendResponse([$rate,'Rate is updated successfully'],200);
        }
        if($request->degree==0){
            return $this->sendError('No rate to adding',400);
        }
        $rate=$user->artist_evaluations()->create([
            'degree'=>$request->degree,
            'artist_id'=>$artist->id,
            'date'=>now()->toDateTimeString(),
        ]);
        $artist->rates_number++;
        $artist->save();
        return $this->sendResponse([$rate,'Rate is added successfully'],200); 
    }

    public function changeRateFromArtist(EvaluationRequest $request,$artist_id){
        $artist=Artist::find($artist_id);
        if(!$artist){
            return $this->sendError('Account is not found',404);     
        }
        $user=Auth::guard('artist_api')->user();
        $rate=$user->artist_evaluations()->where('artist_id',$artist_id)
        ->where('rater_type','App\\Models\\Artist')->first();
        if($user->id==$artist_id){
            return $this->sendError('You can not rate yourself',401);
        }
        if($rate){
            $rate->degree=$request->degree;
            if($rate->degree==0){
                $rate->delete();
                $artist->rates_number--;
                $artist->save();
                return $this->sendResponse([$rate,'Rate is deleted successfully'],200);
            }
            $rate->save();
            return $this->sendResponse([$rate,'Rate is updated successfully'],200);
        }
        if($request->degree==0){
            return $this->sendError('No rate to adding',400);
        }
        $rate=$user->artist_evaluations()->create([
            'degree'=>$request->degree,
            'artist_id'=>$artist->id,
            'date'=>now()->toDateTimeString(),
        ]);
        $artist->rates_number++;
        $artist->save();
        return $this->sendResponse([$rate,'Rate is added successfully'],200); 
    } 
}
