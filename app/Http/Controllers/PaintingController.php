<?php

namespace App\Http\Controllers;

use Validator;
use App\Traits\GeneralTrait;
use App\Models\Painting;
use App\Models\Artist;
use App\Models\Type;
use Illuminate\Http\Request;
use App\Http\Requests\AddPaintingRequest;
use App\Http\Requests\EditPaintingRequest;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class PaintingController extends Controller
{
    use GeneralTrait;

    public function artistPaintings($id){
        $artist=Artist::find($id);
        if(!$artist){
            return $this->sendError('Account is not found',404);
        }
        $paintings=$artist->paintings()->orderByDesc('creation_date')->get();
        if($paintings->isEmpty()){
            return $this->sendResponse('This artist has not have any painting',200);
        }
        foreach($paintings as $painting){
            $createdAt=Carbon::parse($painting->creation_date);
            $timeAgo=$createdAt->diffForHumans();
            $painting->formatted_creation_date=$timeAgo;
        }
        return $this->sendResponse([$paintings,'Paintings are displayed sucessfully'],200);
    }

    /**
     * Store a newly created resource in storage.
     */

    public function store(AddPaintingRequest $request)
    {
        $artist=Auth::guard('artist_api')->user();
        $painting=$artist->paintings()->create([
            'url'=>$this->setPaintingImage($request->url),
            'title'=>$request->title,
            'description'=>$request->description,
            'price'=>$request->price,
            'size'=>$request->size,
            'type_id'=>$request->type_id,
            'creation_date'=>now()->toDateTimeString(),
        ]);
        return $this->sendResponse([$painting,'Painting is added sucessfully'],200);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $painting=Painting::find($id); 
        if(!$painting){
            return $this->sendError('Painting is not found',404);
        }
        $createdAt=Carbon::parse($painting->creation_date);
        $timeAgo=$createdAt->diffForHumans();
        $painting->formatted_creation_date=$timeAgo;
        $paintingDetails=$painting->loadMissing('artist','type');
        return $this->sendResponse([$paintingDetails,'Painting is displayed sucessfully'],200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(EditPaintingRequest $request,$id)
    {
        $painting=Painting::find($id);
        if(!$painting){
            return $this->sendError('Painting is not found',404);
        }
        if($painting->artist_id!=Auth::guard('artist_api')->id()){
            return $this->sendError('Unauthorized',401);
        }
        if($request->url)
        {
            $this->deleteImage($painting->url);
        }
        $painting['title']=isset($request->title)?$request->title:$painting->title;
        $painting['description']=isset($request->description)?$request->description:$painting->description;
        $painting['price']=isset($request->price)?$request->price:$painting->price;
        $painting['url']=isset($request->url)?$this->setPaintingImage($request->url):$painting->url;
        $painting['size']=isset($request->size)?$this->$request->size:$painting->size;
        $painting->save();
        return $this->sendResponse([$painting,'Painting is updated successfully'],200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $painting=Painting::find($id);
        if(!$painting){
            return $this->sendError('Painting is not found',404);
        }
        if($painting->artist_id!=Auth::guard('artist_api')->id()){
            return $this->sendError('Unauthorized',401);
        }
        $this->deleteImage($painting->url);
        $painting->delete();
        return $this->sendResponse($painting,'Painting is deleted successfully');

    }
}
