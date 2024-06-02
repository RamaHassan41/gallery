<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Painting_Comment extends Model
{
    use HasFactory;

    protected $table='painting_comments';
    protected $fillable = [
        'comment_text',
        'date',
        'user_id',
        'user_type',
        'painting_id',
    ];

    public function complaints()
    {
        return $this->hasMany(Complaint::class);
    }

    public function painting()
    {
        return $this->belongsTo(Painting::class);
    }

    public function user()
    {
        return $this->morphTo();
    }

}
