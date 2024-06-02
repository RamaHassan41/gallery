<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Follow extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'follower_id',
        'follower_type',
        'followed_id',
    ]; 

    public function follower()
    {
        return $this->morphTo();
    }

    public function followed_artist()
    {
        return $this->belongsTo(Artist::class);
    }
}
