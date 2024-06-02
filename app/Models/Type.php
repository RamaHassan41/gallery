<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use LaravelLegends\EloquentFilter\Concerns\HasFilter;

class Type extends Model
{
    use HasFactory,HasFilter;

    protected $fillable = [
        'type_name',
        'details',
    ];

    public function paintings()
    {
        return $this->hasMany(Painting::class,'type_id','id')
        ->with(['artist'=>function($query){
            $query->select('id','name','image');
        },'type'=>function($query){
            $query->select('id','type_name');
        }]);
    }
}
