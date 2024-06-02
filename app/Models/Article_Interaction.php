<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article_Interaction extends Model
{
    use HasFactory;

    protected $table='article_interactions';
    protected $fillable = [
        'type',
        'date',
        'reactant_id',
        'reactant_type',
        'article_id',
    ]; 

    public function reactant()
    {
        return $this->morphTo();
    }

    public function article()
    {
        return $this->belongsTo(Article::class);
    }
}
