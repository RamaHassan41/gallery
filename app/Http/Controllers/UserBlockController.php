<?php

namespace App\Http\Controllers;

use App\Traits\GeneralTrait;
use App\Models\User;
use App\Models\Artist;
use Illuminate\Http\Request;

class UserBlockController extends Controller
{
    use GeneralTrait;

    public function changeBlockArtist($id)
    {
        $artist=Artist::find($id);
        if(!$artist){
            return $this->sendError('Account is not found',404);
        }
        if($artist->status=='blocked'){
            $artist->status='activeAsArtist';
            /*
            or 
            $artist->status=='activeAsUser';
            $artist->cerificates()->first()->delete();
            $artist->save();
            */
            $artist->save();
            return $this->sendResponse('Artist is unblocked successfully',200);
        }
        $artist->status='blocked';
        $artist->save();
        return $this->sendResponse('Artist is blocked successfully',200);
    }

    // public function unblockArtist(Request $request, $id)
    // {
    //     $artist=Artist::find($id);
    //     if(!$artist){
    //         return $this->sendError('Account is not found',404);
    //     }
    //     $artist->status='activeAsArtist';
    //     $artist->save();
    //     return $this->sendResponse('Artist is unblocked successfully',200);
    // }

    public function changeBlockUser($id)
    {
        $user=User::find($id);
        if(!$user){
            return $this->sendError('Account is not found',404);
        }
        if($user->status=='blocked'){
            $user->status='active';
            $user->save();
            return $this->sendResponse('User is unblocked successfully',200);
        }
        $user->status='blocked';
        $user->save();
        return $this->sendResponse('User is blocked successfully',200);
    }

    // public function unblockUser(Request $request, $id)
    // {
    //     $user=User::find($id);
    //     if(!$user){
    //         return $this->sendError('Account is not found',404);
    //     }
    //     $user->status='active';
    //     $user->save();
    //     return $this->sendResponse('User is unblocked successfully',200);
    // }
}