<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PDFFile extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'artist_id',
        'creation_date',
        'pdf_file',
        'size',
    ];

    public function artist()
    {
        return $this->belongsTo(Artist::class);
    } 
}

