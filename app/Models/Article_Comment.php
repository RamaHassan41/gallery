<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article_Comment extends Model
{
    use HasFactory;

    protected $table='article_comments';
    protected $fillable = [
        'comment_text',
        'date',
        'user_id',
        'user_type',
        'article_id',
    ];

    public function complaints()
    {
        return $this->hasMany(Complaint::class);
    }

    public function user()
    {
        return $this->morphTo();
    }

    public function article()
    {
        return $this->belongsTo(Article::class);
    }
}
