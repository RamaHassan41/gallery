<?php

namespace App\Traits;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Exceptions\HttpResponseException;

trait GeneralTrait
{
    public function sendResponse($result,$code)
    {
        $response=[
            'success'=>true,
            'result'=>$result,
            'code'=>$code
        ];
        return response()->json($response);
    }

    public function sendError($error,$code)
    {
        $response=[
            'success'=>false,
            'data'=>$error,
            'code'=>$code,
        ];
        return response()->json($response);
    }

    public function setProfileImage($image)
    {
        //if ($request->image->isValid()){
            $imageName=time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('profile_images'),$imageName);
            $photo='profile_images/'.$imageName;
            return $photo;
        //}
        //return null;
        
    }

    public function setPaintingImage($url){
            $paintingName=time() . '.' . $url->getClientOriginalExtension();
            $url->move(public_path('painting_images'),$paintingName);
            $paintingPath='painting_images/'.$paintingName;
            return $paintingPath;
            //}
            // if(isset($paintingPath)){
            //     $painting->url=$paintingPath;
            // }
    }

    public function setArticleImage($url){
            $imageName=time() . '.' . $url->getClientOriginalExtension();
            $url->move(public_path('article_images'),$imageName);
            $imagePath='article_images/'.$imageName;
            return $imagePath;
        }

    // public function deleteProfileImage($image){
    //    // if ($request->has('delete_image') && $request->delete_image && $request->user()->image) {
    //     if($image){
    //         $profileImagePath=public_path($image);
    //         if(File::exists($profileImagePath)){
    //             File::delete($profileImagePath);
    //         }
    //         return null;
    //     }
    // }

    // public function deletePaintingImage($url){
    //     // if ($request->has('delete_image') && $request->delete_image && $request->user()->image) {
    //      if($url){
    //          $paintingImagePath=public_path($url);
    //          if(File::exists($paintingImagePath)){
    //              File::delete($paintingImagePath);
    //          }
    //          return null;
    //      }
    //  }


    //  public function deleteArticleImage($url){
    //     // if ($request->has('delete_image') && $request->delete_image && $request->user()->image) {
    //      if($url){
    //          $articleImagePath=public_path($url);
    //          if(File::exists($articleImagePath)){
    //              File::delete($articleImagePath);
    //          }
    //          return null;
    //      }
    //  }

    public function setCertificateImage($image)
    {
        //if ($request->image->isValid()){
            $imageName=time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('certificate_images'),$imageName);
            $photo='certificate_images/'.$imageName;
            return $photo;
        //}
        //return null;
        
    }

    public function setPersonalImage($image)
    {
        //if ($request->image->isValid()){
            $imageName=time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('personal_images'),$imageName);
            $photo='personal_images/'.$imageName;
            return $photo;
        //}
        //return null;
        
    }

    public function setAnotherImage($image)
    {
        //if ($request->image->isValid()){
            $imageName=time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('another_images'),$imageName);
            $photo='another_images/'.$imageName;
            return $photo;
        //}
        //return null;
    }

    public function deleteImage($url){
        // if ($request->has('delete_image') && $request->delete_image && $request->user()->image) {
        if($url){
            $certificateImagePath=public_path($url);
            if(File::exists($certificateImagePath)){
                File::delete($certificateImagePath);
            }
            return null;
        }
    }

    //  public function deletePersonalImage($url){
    //     // if ($request->has('delete_image') && $request->delete_image && $request->user()->image) {
    //      if($url){
    //          $personalImagePath=public_path($url);
    //          if(File::exists($personalImagePath)){
    //              File::delete($personalImagePath);
    //          }
    //          return null;
    //      }
    //  }

    //  public function deleteAnotherImage($url){
    //     // if ($request->has('delete_image') && $request->delete_image && $request->user()->image) {
    //      if($url){
    //          $anotherImagePath=public_path($url);
    //          if(File::exists($anotherImagePath)){
    //              File::delete($anotherImagePath);
    //          }
    //          return null;
    //      }
    //  }
}