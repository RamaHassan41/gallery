<?php

namespace App\Http\Controllers;
use App\Traits\GeneralTrait;
use App\Models\Type;
use Illuminate\Http\Request;

class TypeController extends Controller
{
    use GeneralTrait;
    
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $types=Type::with('paintings')->get();
        return $this->sendResponse([$types,'Types are displayed successfully'],200);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $type=Type::find($id);
        if(!$type){
            return $this->sendError('Type is not found',404);
        }
        $type->loadMissing('paintings');
        return $this->sendResponse([$type,'Type details are displayed successfully'],200);
    }
}
