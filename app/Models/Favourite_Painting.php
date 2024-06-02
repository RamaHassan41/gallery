<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Favourite_Painting extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'adding_date',
        'painting_id',
        'favourite_id',
    ]; 

    public function painting()
    {
        return $this->belongsTo(Painting::class)
        ->with(['artist'=>function($query){
            $query->select('id','name','image');
        },'type'=>function($query){
            $query->select('id','type_name');
        }]);
    }

    public function favourite()
    {
        return $this->belongsTo(Favourite::class);
    }
}
