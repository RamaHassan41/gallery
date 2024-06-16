<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use LaravelLegends\EloquentFilter\Concerns\HasFilter;

class Painting extends Model
{
    use HasFactory,HasFilter;

    protected $fillable = [
        'title',
        'description',
        'type_id',
        'price',
        'size',
        'creation_date',
        'url',
        'artist_id',
        'archive_id',
        'comments_number',
        'interactions_number',
        'rates_number',
    ];

    protected $hidden = [
        'reports_number',
    ];

    public function complaints()
    {
        return $this->morphMany(Complaint::class,'reported');
    }

    public function favourite_paintings()
    {
        return $this->hasMany(Favourite_Painting::class,'painting_id','id');
    }

    public function interactions()
    {
        return $this->hasMany(Painting_Interaction::class,'painting_id','id')
        ->with(['reactant'=>function($query){
            $query->select('id','name','image');
        }]);
    }

    public function evaluations()
    {
        return $this->hasMany(Painting_Evaluation::class,'painting_id','id')
        ->with(['rater'=>function($query){
            $query->select('id','name','image');
        }]);
    }

    public function comments()
    {
        return $this->hasMany(Painting_Comment::class,'painting_id','id')
        ->with(['user'=>function($query){
            $query->select('id','name','image');
        }]);
    }

    public function purchase_orders()
    {
        return $this->hasMany(Purchase_Order::class,'painting_id','id');
    }

    public function artist()
    {
        return $this->belongsTo(Artist::class);
    } 

    public function archive()
    {
        return $this->belongsTo(Archive::class);
    }

    public function type()
    {
        return $this->belongsTo(Type::class);
    }

    public function sold_paintings()
    {
        return $this->hasOne(Sold_Painting::class,'painting_id','id');
    }
}