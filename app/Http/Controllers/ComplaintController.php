<?php

namespace App\Http\Controllers;
use Validator;
use App\Traits\GeneralTrait;
use App\Models\Artist;
use App\Models\User;
use App\Models\Painting;
use App\Models\Article;
use App\Models\Complaint;
use Illuminate\Http\Request;
use App\Http\Requests\ComplaintRequest;
use Illuminate\Support\Facades\Auth;
use\Carbon\Carbon;

class ComplaintController extends Controller
{
    use GeneralTrait;

    public function storeFromUserAgainstPainting(ComplaintRequest $request,$painting_id)
    {
        $reporter=Auth::guard('api')->user();
        $reported=Painting::where('id',$painting_id)->first();
        if(!$reported){
            return $this->sendError('Painting is not found',404);
        }
        $complaint=$reporter->complaint_reporter()->where('reported_id',$painting_id)
        ->where('reported_type','App\\Models\\Painting')
        ->where('reporter_type','App\\Models\\User')
        ->first();
        if($complaint){
            return $this->sendError('You are already added a complaint',401);
        }

        $complaint=$reporter->complaint_reporter()->create([
            'content'=>$request->content,
            'reported_id'=>$reported->id,
            'reported_type'=>'App\\Models\\Painting',
            'date'=>now()->toDateTimeString(),
        ]);
        $reported->reports_number++;
        $reported->save();
        return $this->sendResponse([$complaint,'Complaint is added successfully'],200);
    }

    public function storeFromArtistAgainstPainting(ComplaintRequest $request,$painting_id)
    {
        $reporter=Auth::guard('artist_api')->user();
        $reported=Painting::where('id',$painting_id)->first();
        if(!$reported){
            return $this->sendError('Painting is not found',404);
        }
        if($reported->artist_id==$reporter->id){
            return $this->sendError('You can not add a complaint against your own painting',401);
        }
        $complaint=$reporter->complaint_reporter()->where('reported_id',$painting_id)
        ->where('reported_type','App\\Models\\Painting')
        ->where('reporter_type','App\\Models\\Artist')
        ->first();
        if($complaint){
            return $this->sendError('You are already added a complaint',401);
        }

        $complaint=$reporter->complaint_reporter()->create([
            'content'=>$request->content,
            'reported_id'=>$reported->id,
            'reported_type'=>'App\\Models\\Painting',
            'date'=>now()->toDateTimeString(),
        ]);
        $reported->reports_number++;
        $reported->save();
        return $this->sendResponse([$complaint,'Complaint is added successfully'],200);
    }

    public function storeFromUserAgainstArticle(ComplaintRequest $request,$article_id)
    {
        $reporter=Auth::guard('api')->user();
        $reported=Article::where('id',$article_id)->first();
        if(!$reported){
            return $this->sendError('Article is not found',404);
        }
        $complaint=$reporter->complaint_reporter()->where('reported_id',$article_id)
        ->where('reported_type','App\\Models\\Article')
        ->where('reporter_type','App\\Models\\User')
        ->first();
        if($complaint){
            return $this->sendError('You are already added a complaint',401);
        }
        $complaint=$reporter->complaint_reporter()->create([
            'content'=>$request->content,
            'reported_id'=>$reported->id,
            'reported_type'=>'App\\Models\\Article',
            'date'=>now()->toDateTimeString(),
        ]);
        $reported->reports_number++;
        $reported->save();
        return $this->sendResponse([$complaint,'Complaint is added successfully'],200);
    }

    public function storeFromArtistAgainstArticle(ComplaintRequest $request,$article_id)
    {
        $reporter=Auth::guard('artist_api')->user();
        $reported=Article::where('id',$article_id)->first();
        if(!$reported){
            return $this->sendError('Article is not found',404);
        }
        if($reported->artist_id==$reporter->id){
            return $this->sendError('You can not add a complaint against your own article',401);
        }
        $complaint=$reporter->complaint_reporter()->where('reported_id',$article_id)
        ->where('reported_type','App\\Models\\Article')
        ->where('reporter_type','App\\Models\\Artist')
        ->first();
        if($complaint){
            return $this->sendError('You are already added a complaint',401);
        }
        $complaint=$reporter->complaint_reporter()->create([
            'content'=>$request->content,
            'reported_id'=>$reported->id,
            'reported_type'=>'App\\Models\\Article',
            'date'=>now()->toDateTimeString(),
        ]);
        $reported->reports_number++;
        $reported->save();
        return $this->sendResponse([$complaint,'Complaint is added successfully'],200);
    }

        public function showComplaints()
    {
        $complaints=Complaint::orderByDesc('date')
        ->with('reporter',function($query){
            $query->select('id','name','image');
        })->get();
        if(!$complaints){
            return $this->sendResponse('No complaints are exist to display',200);
        }
        foreach($complaints as $complaint){
            $createdAt=Carbon::parse($complaint->date);
            $timeAgo=$createdAt->diffForHumans();
            $complaint->formatted_creation_date=$timeAgo;
        }
        return $this->sendResponse([$complaints,'Complaints are displayed successfully'],200);
    }

    public function showComplaintDetails($id){
        $complaint=Complaint::find($id);
        if(!$complaint){
            return $this->sendError('Complaint is not found',404);
        }
        $createdAt=Carbon::parse($complaint->date);
        $timeAgo=$createdAt->diffForHumans();
        $complaint->formatted_creation_date=$timeAgo;
        $complaintDetails=$complaint->with(['reporter',function($query){
            $query->select('id','name','image');
        },'reported',function($query){
            $query->select('id','title','url','artist_id');
        }])->get();
        return $this->sendResponse([$complaintDetails,'Complaint is displayed successfully'],200);
    } 

    public function acceptComplaint($id){
        $complaint=Complaint::find($id);
        if(!$complaint){
            return $this->sendError('Complaint is not found',404);
        }
        $complaint->status='accepted';
        $complaint->save();
        // $reported=$complaint->reported;
        $reported=$complaint->reported()->first();
        $acceptedComplaints=$reported->complaints()->where('status','accepted')->get();
        if($acceptedComplaints->count()>=20){
            $this->deleteImage($reported->url);
            $reported->delete();
            return $this->sendResponse('Complaint is accepted. Element is deleted successfully',200);
        }
        return $this->sendResponse('Complaint is accepted successfully',200);
    }

    public function rejectComplaint($id){
        $complaint=Complaint::find($id);
        if(!$complaint){
            return $this->sendError('Complaint is not found',404);
        }
        // $reported=$complaint->reported;
        $reported=$complaint->reported()->first();
        $complaint->delete();
        $reported->reports_number--;
        $reported->save();
        return $this->sendResponse('Complaint is rejected and deleted successfully',200);
    }
}
