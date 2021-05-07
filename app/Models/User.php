<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];


    protected $hidden = [
        'password',
        'remember_token',
    ];


    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function timeline(){
        $freinds = $this->follows()->pluck('users.id');
        return Tweet::whereIn('user_id', $freinds)
                      ->orWhere("user_id", auth()->user()->id)
                      ->latest()->get();
    }

    public function tweets(){
        return $this->hasMany(Tweet::class);
    }

    public function getAvatarAttribute(){
        return "https://i.pravatar.cc/200?u=".$this->email;
    }

    public function follow(User $user){
        return $this->follows()->save($user);
    }

    public function follows(){
        return $this->belongsToMany(User::class, 'follows', 'user_id', 'following_user_id');
    }

    public function getRouteKeyName(){
        return 'name';
    }
}
