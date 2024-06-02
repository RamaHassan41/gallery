<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Painting_Interaction extends Model
{
    use HasFactory;

    protected $table='painting_interactions';
    protected $fillable = [
        'type',
        'date',
        'reactant_id',
        'reactant_type',
        'painting_id',
    ]; 

    public function painting()
    {
        return $this->belongsTo(Painting::class);
    }

    public function reactant()
    {
        return $this->morphTo();
    }

}
