<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
//use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use LaravelLegends\EloquentFilter\Concerns\HasFilter;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Artist extends Authenticatable implements JWTSubject,MustVerifyEmail

{
    use HasApiTokens, HasFactory, Notifiable, HasFilter;
    //protected $guard_name='api_artist';
    //protected $table = 'artists';

    protected $fillable = [
        'name',
        'user_name',
        'email',
        'password',
        'image',
        'expertise',
        'specialization',
        'biography',
        'gender',
        'rates_number',
        'followers_number',
        'code',
        'device_token',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'status',
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

    public function archives()
    {
        return $this->hasMany(Archive::class,'artist_id','id')
        ->with(['artist'=>function($query){
            $query->select('id','name','image');
        }]);
    }

    public function paintings()
    {
        return $this->hasMany(Painting::class,'artist_id','id')
        ->with(['artist'=>function($query){
            $query->select('id','name','image');
        },'type'=>function($query){
            $query->select('id','type_name');
        }]);
    }
        
    public function files()
    {
        return $this->hasMany(PDFFile::class,'artist_id','id');
    }

    // public function sold_paintings()
    // {
    //     return $this->hasMany(Sold_Painting::class,'artist_id','id');
    // }

    public function certificates()
    {
        return $this->hasOne(Reliability_Certificate::class,'artist_id','id');
    }

    public function articles()
    {
        return $this->hasMany(Article::class,'artist_id','id')
        ->with(['artist'=>function($query){
            $query->select('id','name','image');
        }]);
    }

    public function followers()
    {
        return $this->hasMany(Follow::class,'followed_id','id')
        ->with(['follower'=>function($query){
            $query->select('id','name','image');
        }]);
    }

    public function followings()
    {
        return $this->morphMany(Follow::class,'follower');
    }

    public function chats()
    {
        return $this->hasMany(Chat::class);
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    public function evaluations()
    {
        return $this->hasMany(Artist_Evaluation::class,'artist_id','id')
        ->with(['rater'=>function($query){
            $query->select('id','name','image');
        }]);
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

    public function complaint_reporter()
    {
        return $this->morphMany(Complaint::class,'reporter');
    }

    // public function complaint_reported()
    // {
    //     return $this->morphMany(Complaint::class,'reported');
    // }

    public function favourite()
    {
        return $this->hasOne(Favourite::class,'user_id','id');
    }

    // public function favourite()
    // {
    //     return $this->hasOne(Favourite::class,'user');
    // }
}

