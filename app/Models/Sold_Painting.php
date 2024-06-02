<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use LaravelLegends\EloquentFilter\Concerns\HasFilter;

class Sold_Painting extends Model
{
    use HasFactory,HasFilter;

    protected $fillable = [
        'painting_name',
        'price',
        'sell_date',
        'artist_id',
        'user_id',
    ];

    public function artist()
    {
        return $this->belongsTo(Artist::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
