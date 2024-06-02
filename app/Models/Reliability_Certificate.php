<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reliability_Certificate extends Model
{
    use HasFactory;

    protected $fillable = [
        'image',
        'personal_image',
        'another_image',
        'send_date',
        'artist_id',
    ];

    public $hidden = [
        'status',
    ];

    public function artist()
    {
        return $this->belongsTo(Artist::class);
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }
}
