<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use LaravelLegends\EloquentFilter\Concerns\HasFilter;

class Sold_Painting extends Model
{
    use HasFactory,HasFilter;

    protected $table='sold_paintings';
    
    protected $fillable = [
        'painting_id',
        'sell_date',
        'user_id',
    ];

    public function painting()
    {
        return $this->belongsTo(Painting::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
