<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Complaint extends Model
{
    use HasFactory;

    protected $fillable = [
        'content',
        'date',
        'reporter_id',
        'reporter_type',
        'reported_id',
        'reported_type',
        'status',
    ];

    // public function painting()
    // {
    //     return $this->belongsTo(Painting::class);
    // }

    // public function article()
    // {
    //     return $this->belongsTo(Article::class);
    // }

    public function reporter()
    {
        return $this->morphTo();
    }

    public function reported()
    {
        return $this->morphTo();
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }

}
