<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Painting_Evaluation extends Model
{
    use HasFactory;
    
    protected $table='painting_evaluations';
    protected $fillable = [
        'degree',
        'date',
        'rater_id',
        'rater_type',
        'painting_id',
    ]; 

    public function painting()
    {
        return $this->belongsTo(Painting::class);
    }

    public function rater()
    {
        return $this->morphTo();
    }
}
