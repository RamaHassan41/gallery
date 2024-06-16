<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase_Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_date',
        'painting_id',
        'user_id',
        'status',
    ];

    public function painting()
    {
        return $this->belongsTo(Painting::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
