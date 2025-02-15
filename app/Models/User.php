<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Enums\UserRoleEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

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
        'photo',
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
        'password' => 'hashed',
        
    ];

    public function favourites(): MorphToMany
    {
        return $this->morphToMany(Post::class, 'postable');
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }


    // scopes
    public function scopeOfBloggers($query)
    {
        return $query->where('role', UserRoleEnum::BLOGGER->value);
    }

    public function scopeOfAdmins($query)
    {
        return $query->where('role', UserRoleEnum::ADMIN->value);
    }

    // methods [ isAdmin & isBlogger ]
    public function isAdmin(): bool
    {
        return $this->role === UserRoleEnum::ADMIN->value;
    }
    public function isBlogger(): bool
    {
        return $this->role === UserRoleEnum::BLOGGER->value;
    }

}
