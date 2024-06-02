<?php

namespace App\Http\Controllers;
use Validator;
use App\Traits\GeneralTrait;
use App\Models\Artist;
use Illuminate\Http\Request;
use App\Http\Requests\CertificateRequest;
use App\Models\Reliability_Certificate;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ReliabilityCertificateController extends Controller
{
    use GeneralTrait;

    // public function showCertificates()
    // {
    //     $artist=Auth::guard('artist_api')->user();
    //     // if(!$artist){
    //     //     return $this->sendError('Account is not found',404);
    //     // }
    //     // if($artist->id!=Auth::guard('artist_api')->id()){
    //     //     return $this->sendError('Unauthorized',401);
    //     // }
    //     $certificates=$artist->certificates()->get();
    //     return $this->sendResponse([$certificates,'Reliability certificates are displayed successfully'],200);
    // }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CertificateRequest $request)
    {
        $artist=Auth::guard('artist_api')->user();
        $certificate=$artist->certificates()->first();
        if($certificate){
            return $this->sendError('You are already added a reliability certificate images',401);
        }
        $certificate=$artist->certificates()->create([
            'image'=>$this->setCertificateImage($request->image),
            'personal_image'=>$this->setPersonalImage($request->personal_image),
            'another_image'=>isset($request->another_image)?$this->setAnotherImage($request->another_image):null,
            'send_date'=>now()->toDateTimeString(),
        ]);
        return $this->sendResponse([$certificate,'Certificate is added successfully'],200);
    }

    public function showCertificates(){
        $certificates=Reliability_Certificate::orderByDesc('send_date')
        ->with('artist',function($query){
            $query->select('id','name','image');
        })->get();
        if(!$certificates){
            return $this->sendResponse('No certificates are exist to display',200);
        }
        foreach($certificates as $certificate){
            $createdAt=Carbon::parse($certificate->send_date);
            $timeAgo=$createdAt->diffForHumans();
            $certificate->formatted_creation_date=$timeAgo;
        }
        return $this->sendResponse([$certificates,'Reliability certificates are displayed successfully'],200);
    }

        public function showCertificateDetails($id)
    {
        $certificate=Reliability_Certificate::find($id);
        if(!$certificate){
            return $this->sendError('Certificate is not found',404);
        }
        $createdAt=Carbon::parse($certificate->send_date);
        $timeAgo=$createdAt->diffForHumans();
        $certificate->formatted_creation_date=$timeAgo;
        $certificateDetails=$certificate->with('artist',function($query){
            $query->select('id','name','image');
        })->get();
        return $this->sendResponse([$certificateDetails,'Reliability certificate is displayed successfully'],200);
    }

    public function acceptCertificate($id){
        $certificate=Reliability_Certificate::find($id);
        if(!$certificate){
            return $this->sendError('Certificate is not found',404);
        }
        $certificate->status='accepted';
        $certificate->save();
        //$artist=$certificate->artist;
        $artist=$certificate->artist()->first();
        $artist->status='active';
        $artist->save();
        return $this->sendResponse([$certificate,'Certificate is accepted successfully'],200);
    }

    public function rejectCertificate($id){
        $certificate=Reliability_Certificate::find($id);
        if(!$certificate){
            return $this->sendError('Certificate is not found',404);
        }
        $this->deleteImage($certificate->image);
        $this->deleteImage($certificate->personal_image);
        if($certificate->another_image){
            $this->deleteImage($certificate->another_image);
        }
        $certificate->delete();
        return $this->sendResponse([$certificate,'Certificate is rejected and deleted successfully'],200);
    }

    /**
     * Display the specified resource.
     */
    // public function showCertificateDetails($id)
    // {
    //     $certificate=Reliability_Certificate::find($id);
    //     if(!$certificate){
    //         return $this->sendError('Certificate is not found',404);
    //     }
    //     if($certificate->artist_id!=Auth::guard('artist_api')->id()){
    //         return $this->sendError('Unauthorized',401);
    //     }
    //     return $this->sendResponse([$certificate,'Certificate is displayed successfully'],200);
    // }

    /**
     * Update the specified resource in storage.
     */
    // public function update(Request $request,$id)
    // {
    //     $certificate=Reliability_Certificate::find($id);
    //     if(!$certificate){
    //         return $this->sendError('Certificate is not found',404);
    //     }
    //     if($certificate->artist_id!=Auth::guard('artist_api')->id()){
    //         return $this->sendError('Unauthorized',401);
    //     }
    //     $valiadtor=Validator::make($request->all(),[
    //         'image'=>'image|mimes:jpeg,png,jpg,gif|max:2048',
    //         'personal_image'=>'image|mimes:jpeg,png,jpg,gif|max:2048',
    //         'another_image'=>'image|mimes:jpeg,png,jpg,gif|max:2048',
    //     ]);
    //     if($valiadtor->fails()){
    //         return $this->sendError([$validator->errors(),'Please validate error'],400);
    //     }
    //     if($request->image){$this->deleteImage($certificate->image);}
    //     if($request->personal_image){$this->deleteImage($certificate->personal_image);}
    //     if($request->another_image&&$certificate->another_image){$this->deleteImage($certificate->another_image);}
    //     $certificate->image=isset($request->image)
    //     ?$this->setCertificateImage($request->image):$certificate->image;
    //     $certificate->personal_image=isset($request->personal_image)
    //     ?$this->setPersonalImage($request->personal_image):$certificate->personal_image;
    //     $certificate->another_image=isset($request->another_image)
    //     ?$this->setAnotherImage($request->another_image):$certificate->another_image;
    //     $certificate->save();
    //     return $this->sendResponse([$certificate,'Certificate is updated successfully'],200);
    // }

    /**
     * Remove the specified resource from storage.
     */
    // public function destroy($id)
    // {
    //     $certificate=Reliability_Certificate::find($id);
    //     if(!$certificate){
    //         return $this->sendError('Certificate is not found',404);
    //     }
    //     if($certificate->artist_id!=Auth::guard('artist_api')->id()){
    //         return $this->sendError('Unauthorized',401);
    //     }
    //     $this->deleteImage($certificate->image);
    //     $this->deleteImage($certificate->personal_image);
    //     if($certificate->another_image){
    //         $this->deleteImage($certificate->another_image);
    //     }
    //     $certificate->delete();
    //     return $this->sendResponse([$certificate,'Certificate is deleted successfully'],200);
    // }
}
