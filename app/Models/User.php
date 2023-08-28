<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
// use App\Models\Type;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    

    protected $fillable = [
        'name',
        'email',
        'password',
        'username',
        'picture',
        'biography',
        'type',
        'blocked',
        'direct_publish'

    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function authorType(){
        return $this->belongsTo(Type::class,'type','id');
    }
    public function getpictureAttribute($value){
        if($value){
            return asset('back/dist/img/authors/'.$value);
        }else
        {
            return asset('back/dist/img/authors/male.png');
        }
    }
}
