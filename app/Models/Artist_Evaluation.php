<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Artist_Evaluation extends Model
{
    use HasFactory;

    protected $table='artist_evaluations';
    protected $fillable = [
        'degree',
        'date',
        'rater_id',
        'rater_type',
        'artist_id',
    ]; 

    public function artist()
    {
        return $this->belongsTo(Artist::class,'artist_id');
    }

    public function rater()
    {
        return $this->morphTo();
    }
}
