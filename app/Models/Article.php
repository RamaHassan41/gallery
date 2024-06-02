<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use LaravelLegends\EloquentFilter\Concerns\HasFilter;

class Article extends Model
{
    use HasFactory,HasFilter;

    protected $fillable = [
        'title',
        'description',
        'creation_date',
        'url',
        'comments_number',
        'interactions_number',

    ];

    protected $hidden = [
        'reports_number',
    ];

    // public function complaints()
    // {
    //     return $this->hasMany(Complaint::class);
    // }

    public function artist()
    {
        return $this->belongsTo(Artist::class);
    }

    public function interactions()
    {
        return $this->hasMany(Article_Interaction::class,'article_id','id')
        ->with(['reactant'=>function($query){
            $query->select('id','name','image');
        }]);
    }

    public function comments()
    {
        return $this->hasMany(Article_Comment::class,'article_id','id')
        ->with(['user'=>function($query){
            $query->select('id','name','image');
        }]);
    }

    public function complaints()
    {
        return $this->morphMany(Complaint::class,'reported');
    }
}
