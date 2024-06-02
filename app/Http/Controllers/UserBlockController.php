<?php

namespace App\Http\Controllers;

use App\Traits\GeneralTrait;
use App\Models\User;
use App\Models\Artist;
use Illuminate\Http\Request;

class UserBlockController extends Controller
{
    use GeneralTrait;

    public function blockUser($id)
    {
        $artist=Artist::find($id);
        if(!$artist){
            return $this->sendError('Account is not found',404);
        }
        $artist->status='active';
        $artist->save();
        return $this->sendResponse('Artist is blocked successfully',200);
    }

    public function unblockUser(Request $request, $id)
    {
        $artist=Artist::find($id);
        if(!$artist){
            return $this->sendError('Account is not found',404);
        }
        $artist->status='inactive';
        $artist->save();
        return $this->sendResponse('User is unblocked successfully',200);
    }
}
