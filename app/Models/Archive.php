<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Archive extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'details',
        'creation_date',
    ];

    public function paintings()
    {
        return $this->hasMany(Painting::class,'archive_id','id')
        ->with(['artist'=>function($query){
            $query->select('id','name','image');
        },'type'=>function($query){
            $query->select('id','type_name');
        }]);
    }

    public function artist()
    {
        return $this->belongsTo(Artist::class);
    }
}
