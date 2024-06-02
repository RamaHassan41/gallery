<?php

namespace App\Http\Controllers;
use Validatior;
use App\Models\PDFFile;
use App\Models\Artist;
use Illuminate\Http\Request;
use App\Http\Controllers\BaseController;

class PDFFileController extends BaseController
{
    public function artistFiles($artist_id){
        $artist=Artist::find($artist_id);
            if($artist){
                $files=PDFFile::where('artist_id',$artist_id)->get();
                return $this->sendResponse($files,'Files retrieved successfully');        
        }
        return $this->sendError('Wrong artist account');
    }

    public function store(Request $request){
        $validator=Validator::make($request->all(),[
            'title'=>'required|string',
            'description'=>'string',
            'pdf_file'=>'required|mimes:pdf',
        ]);
        if($validator->fails()){
            return $this->sendError('Validate error',$validator->errors());
        }
        if($request->hasFile('pdf_file')){
            $file=$request->file('pdf_file');
            $path=$file->store('public');
            $newFile=new PDFFile();
            $newFile->title=$request->title;
            $newFile->description=$request->description;
            $newFile->name=$file->getClientOriginalName();
            $newFile->path=$path;
            $newFile->size=$file->getSize();
            $newFile->creation_date=now();
            $newFile->artist_id=Auth::id();
            $newFile->artist_name=Auth::user()->name;
            $newFile->save();
            return $this->sendResponse($newFile,'File created successfully');
        }
        return $this->sendError('Some errors occured');
    }

    public function show($file_id){
        $file=PDFFile::find($file_id);
        if($file){
            return $this->sendResponse($file,'success');
        }
        return $this->sendError('File is not found');
    }

    public function update(Request $request,$file_id){
        $file=PDFFile::find($file_id);
        if($file){
            $validator=Validator::make($request->all(),[
                'title'=>'string',
                'description'=>'string',
            ]);
        }
        return $this->sendError('File is not found');
    }

    public function destroy($file_id){
        $file=PDFFile::find($file_id);
        if($file){
            $file->delete();
            $filePath=public_path($file->path);
            if(file_exists($filePath)){
                unlink($filePath);
                return $this->sendResponse($file,'File deleted successfully');
            }
            else{
                return $this->sendError('Some errors occured');
            }
        }
        return $this->sendError('File is not found');
    }
}
