<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Favourite extends Model
{
    use HasFactory;

    protected $fillable = [
        'elements_number',
        'user_id',
        'user_type',
    ]; 

    // public function user()
    // {
    //     return $this->morphTo();
    // }
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function artist()
    {
        return $this->belongsTo(Artist::class);
    }

    public function favourite_paintings()
    {
        return $this->hasMany(Favourite_Painting::class,'favourite_id','id')
        ->with(['painting'=>function($query){
            $query->select('id','title','url','artist_id');
        }])
        ->with(['painting.artist'=>function($query){
            $query->select('id','name','image');
        }]);
    }

    public function favourite_articles()
    {
        return $this->hasMany(Favourite_Article::class,'favourite_id','id')
        ->with(['article'=>function($query){
            $query->select('id','title','url','artist_id');
        }])
        ->with(['article.artist'=>function($query){
            $query->select('id','name','image');
        }]);
    }
}
