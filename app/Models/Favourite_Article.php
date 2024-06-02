<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Favourite_Article extends Model
{
    use HasFactory;

    protected $fillable = [
        'adding_date',
        'article_id',
        'favourite_id',
    ]; 

    public function article()
    {
        return $this->belongsTo(Article::class)
        ->with(['artist'=>function($query){
            $query->select('id','name','image');
        }]);
    }

    public function favourite()
    {
        return $this->belongsTo(Favourite::class);
    }
}
