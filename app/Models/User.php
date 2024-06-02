<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use LaravelLegends\EloquentFilter\Concerns\HasFilter;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Support\Facades\Hash;

class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens,HasFactory,Notifiable,HasFilter;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'image',
        'user_name',
        'gender',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'code',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function setPasswordAttribute($value)
    {
        $this->attributes['password']=Hash::make($value);
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    public function favourite()
    {
        return $this->hasOne(Favourite::class,'user_id','id');
    }

    // public function favourite()
    // {
    //     return $this->hasOne(Favourite::class,'user');
    // }

    public function chats()
    {
        return $this->hasMany(Chat::class);
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    public function complaint_reporter()
    {
        return $this->morphMany(Complaint::class,'reporter');
    }

    // public function complaint_reported()
    // {
    //     return $this->morphMany(Complaint::class,'reported');
    // }

    public function followings()
    {
        return $this->morphMany(Follow::class,'follower');
    }

    public function purchase_orders()
    {
        return $this->hasMany(Purchase_Order::class,'user_id','id');
    }

    public function article_interactions()
    {
        return $this->morphMany(Article_Interaction::class,'reactant');
    }

    public function painting_interactions()
    {
        return $this->morphMany(Painting_Interaction::class,'reactant');
    }

    public function artist_evaluations()
    {
        return $this->morphMany(Artist_Evaluation::class,'rater');
    }

    public function painting_evaluations()
    {
        return $this->morphMany(Painting_Evaluation::class,'rater');
    }

    public function article_comments()
    {
        return $this->morphMany(Article_Comment::class,'user');
    }

    public function painting_comments()
    {
        return $this->morphMany(Painting_Comment::class,'user');
    }

    public function purchased_paintings()
    {
        return $this->hasMany(Sold_Painting::class,'user_id','id');
    }
}
