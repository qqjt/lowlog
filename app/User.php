<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * Alter model binding key.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'hashid';
    }

    //relationships
    public function Posts()
    {
        return $this->hasMany(Post::class, 'author_id');
    }
    public function comments()
    {
        return $this->hasMany(Comment::class, 'author_id');
    }
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'user_role')->withTimestamps();
    }
}
