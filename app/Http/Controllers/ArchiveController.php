<?php

namespace App\Http\Controllers;
use Validator;
use App\Traits\GeneralTrait;
use App\Models\Painting;
use App\Models\Artist;
use App\Models\Archive;
use Illuminate\Http\Request;
use App\Http\Requests\ArchiveRequest;
use App\Http\Requests\EditArchiveRequest;
use App\Http\Requests\AddPaintingRequest;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\PaintingController;
use Carbon\Carbon;

class ArchiveController extends Controller
{
    use GeneralTrait;

    /**
     * Display a listing of the resource.
     */
    public function index(){
        $archives=Archive::orderByDesc('created_at')->with('artist',function($query){
            $query->select('id','name','image');
        })->get();
        if(!$archives){
            return $this->sendResponse('No archives are exist to display',200);
        }
        return $this->sendResponse([$archives,'Archives are displayed successfully'],200);
    }

    public function showArchiveWorks($archive_id){
        $archive=Archive::find($archive_id);
        if(!$archive){
            return $this->sendError('Archive is not found',404);
        }
        $createdAt=Carbon::parse($archive->creation_date);
        $timeAgo=$createdAt->diffForHumans();
        $archive->formatted_creation_date=$timeAgo;
        $archiveWorks=$archive->loadMissing(['paintings'=>function($query){
            $query->orderByDesc('creation_date');
        }]);
        if($archive->paintings->isEmpty()){
            return $this->sendResponse([$archive,'Archive paintings list is empty'],200);
        }
        return $this->sendResponse([$archiveWorks,'Archive paintings are displayed successfully'],200);
    }

    public function showArtistArchives($artist_id){
        $artist=Artist::find($artist_id);
        if(!$artist){
            return $this->sendError('Artist is not found',404);
        }
        $artistArchives=$artist->loadMissing(['archives'=>function($query){
            $query->orderByDesc('created_at');
        }]);
        if($artistArchives->archives->isEmpty()){
            return $this->sendResponse('This artist has not have any archive',200);
        }
        return $this->sendResponse([$artistArchives,'Artist archives are displayed successfully'],200);
    }

    /**
     * Store a newly created resource in storage.
     */

    public function store(ArchiveRequest $request)
    {
        $artist=Auth::guard('artist_api')->user();
        $archive=$artist->archives()->create([
            'name'=>$request->name,
            'details'=>$request->details,
            'creation_date'=>now()->toDateTimeString(),
        ]);
        foreach ($request->paintings as $paintingData){
            $painting[]=$archive->paintings()->create([
                'title'=>$paintingData['title'],
                'description'=>isset($paintingData['description'])?$paintingData['description']:null,
                'url'=>$this->setPaintingImage($paintingData['url']),
                'price'=>isset($paintingData['price'])?$paintingData['price']:null,
                'size'=>isset($paintingData['size'])?$paintingData['size']:null,
                'type_id'=>$paintingData['type_id'],
                'artist_id'=>$artist->id,
                'creation_date'=>now()->toDateTimeString(),
            ]);
        }
        $archive=$archive->loadMissing('paintings');
        return $this->sendResponse([$archive,'Archive is added successfully'],200);
    }

    // public function store(Request $request)
    // {
    //     $artist=Auth::guard('artist_api')->user();
    //     $archive=$artist->archives()->create([
    //         'name'=>$request->name,
    //         'details'=>$request->details,
    //         'creation_date'=>now()->toDateTimeString(),
    //     ]);
    //     $painting_ids=$request->input('painting_ids',[]);
    //     foreach($painting_ids as $painting_id){
    //         $painting=$artist->paintings()->where('id',$painting_id)->first();
    //         if(!$painting){
    //             return $this->sendError('Painting with id '.$painting_id.' is not found',404);
    //         }
    //         if($painting->archive_id==$archive->id){
    //             return $this->sendError('Painting with id ' .$painting_id.' is already exists in this archive',401);
    //         }
    //         if($painting->archive_id&&$painting->archive_id!=$archive->id){
    //             return $this->sendError('Painting with id ' .$painting_id.' is already exists in another archive',401);
    //         }
    //         $archive->paintings()->save($painting);
    //     }
    //     $archive=$archive->loadMissing('paintings');
    //     return $this->sendResponse([$archive,'Archive is added successfully'],200);
    // }

    public function editArchiveDetails(EditArchiveRequest $request,$archive_id){
        $archive=Archive::find($archive_id);
        if(!$archive){
            return $this->sendError('Archive is not found',404);
        }
        if($archive->artist_id!=Auth::guard('artist_api')->id()){
            return $this->sendError('Unauthorized',401);
        }
        $archive['name']=isset($request->name)?$request->name:$archive->name;
        $archive['details']=isset($request->details)?$request->details:$archive->details;
        $archive->save();
        return $this->sendResponse([$archive,'Archive details are updated successfully'],200);
    }

    public function addPaintingToArchive(Request $request,$archive_id){
        $artist=Auth::guard('artist_api')->user();
        $archive=$artist->archives()->where('id',$archive_id)->first();
        if (!$archive) {
            return $this->sendError('Archive is not found',404);
        }
        $painting_ids=$request->input('painting_ids',[]);
        foreach($painting_ids as $painting_id){
            $painting=$artist->paintings()->where('id',$painting_id)->first();
            if(!$painting){
                return $this->sendError('Painting with id '.$painting_id.' is not found',404);
            }
            if($painting->archive_id==$archive_id){
                return $this->sendError('Painting with id ' .$painting_id.' is already exists in this archive',401);
            }
            if($painting->archive_id&&$painting->archive_id!=$archive_id){
                return $this->sendError('Painting with id ' .$painting_id.' is already exists in another archive',401);
            }
            $archive->paintings()->save($painting);
        }
        return $this->sendResponse([$archive,'Paintings are added to archive successfully'],200);
    }

    public function removePaintingFromArchive(Request $request,$archive_id){
        $artist=Auth::guard('artist_api')->user();
        $archive=$artist->archives()->where('id',$archive_id)->first();
        if(!$archive){
            return $this->sendError('Archive is not found',404);
        }
        $painting_ids=$request->input('painting_ids',[]);
        foreach($painting_ids as $painting_id){
            $painting=$artist->paintings()->where('id',$painting_id)->first();
            if(!$painting){
                return $this->sendError('Painting with id '.$painting_id.' is not found',404);
            }
            if($painting->archive_id!=$archive_id){
                return $this->sendError('Painting with id '.$painting_id.' is not found in this archive',404);
            }
            if($painting->archive_id==$archive_id){
                $painting->archive_id=null;
                $painting->save();
            }
            $paintings=$archive->paintings()->get();
            if($paintings->isEmpty()){
                $archive->delete();
                return $this->sendResponse('Archive paintings list is empty. Archive is deleted successfully',200);
            }
        }
        return $this->sendResponse([$archive,'Painting is removed to this archive successfully'],200);
    }

    /**
     * Remove the specified resource from storage.
     */

    public function destroy($id){
        $archive=Archive::find($id);
        if(!$archive){
            return $this->sendError('Archive is not found',404);
        }
        if($archive->artist_id!=Auth::guard('artist_api')->id()){
            return $this->sendError('Unauthorized',401);
        }
        $paintings=$archive->paintings()->get();
        foreach($paintings as $painting){
            $painting->archive_id=null;
            $painting->save();
        }
        $archive->delete();
        return $this->sendResponse('Archive is deleted successfully',200);
    }
}
