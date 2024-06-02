<?php

namespace App\Http\Controllers;
use Validator;
use App\Traits\GeneralTrait;
use App\Models\Painting;
use Illuminate\Http\Request;
use App\Http\Requests\EvaluationRequest;
use App\Models\Painting_Evaluation;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class Painting_EvaluationController extends Controller
{
    use GeneralTrait;

    public function showPaintingRates($painting_id){
        $painting=Painting::find($painting_id);
        if(!$painting){
            return $this->sendError('Painting is not found',404);
        }
        $rates=$painting->evaluations()->orderByDesc('date')->get();
        if($rates->isEmpty()){
            return $this->sendResponse('This painting has not have any rate',200);
        }
        foreach($rates as $rate){
            $createdAt=Carbon::parse($rate->date);
            $timeAgo=$createdAt->diffForHumans();
            $rate->formatted_creation_date=$timeAgo;
        }
        return $this->sendResponse([$rates,'Painting rates are displayed successfully'],200);
    }

    public function changeRate(EvaluationRequest $request,$painting_id){
        $painting=Painting::find($painting_id);
        if(!$painting){
            return $this->sendError('Painting is not found',404);     
        }
        $user=Auth::guard('api')->user();
        $rate=$user->painting_evaluations()->where('painting_id',$painting_id)
        ->where('rater_type','App\\Models\\User')->first();
        if($rate){
            $rate->degree=$request->degree;
            if($rate->degree==0){
                $rate->delete();
                $painting->rates_number--;
                $painting->save();
                return $this->sendResponse([$rate,'Rate is deleted successfully'],200);
            }
            $rate->save();
            return $this->sendResponse([$rate,'Rate is updated successfully'],200);
        }
        if($request->degree==0){
            return $this->senderror('No rate to adding',400);
        }
        $rate=$user->painting_evaluations()->create([
            'degree'=>$request->degree,
            'painting_id'=>$painting_id,
            'date'=>now()->toDateTimeString(),
        ]);
        $painting->rates_number++;
        $painting->save();
        return $this->sendResponse([$rate,'Rate is added successfully'],200); 
    }

    public function changeRateFromArtist(EvaluationRequest $request,$painting_id){
        $painting=Painting::find($painting_id);
        if(!$painting){
            return $this->sendError('Painting is not found',404);     
        }
        $artist=Auth::guard('artist_api')->user();
        $rate=$artist->painting_evaluations()->where('painting_id',$painting_id)
        ->where('rater_type','App\\Models\\Artist')->first();
        if($painting->artist_id==$artist->id){
            return $this->sendError('You can not rate your own painting',401);
        }
        if($rate){
            $rate->degree=$request->degree;
            if($rate->degree==0){
                $rate->delete();
                $painting->rates_number--;
                $painting->save();
                return $this->sendResponse([$rate,'Rate is deleted successfully'],200);
            }
            $rate->save();
            return $this->sendResponse([$rate,'Rate is updated successfully'],200);
        }
        if($request->degree==0){
            return $this->senderror('No rate to adding',400);
        }
        $rate=$artist->painting_evaluations()->create([
            'degree'=>$request->degree,
            'painting_id'=>$painting_id,
            'date'=>now()->toDateTimeString(),
        ]);
        $painting->rates_number++;
        $painting->save();
        return $this->sendResponse([$rate,'Rate is added successfully'],200); 
    }
}
