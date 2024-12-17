<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
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

    /**
     * Get the todos for the user.
     */
    public function todos()
    {
        return $this->hasMany(Todo::class, 'owner_id');
    }

    /**
     * Get the completed todos count.
     */
    public function getCompletedTodosCountAttribute()
    {
        return $this->todos()->where('completed', true)->count();
    }

    /**
     * Get the completion rate.
     */
    public function getCompletionRateAttribute()
    {
        $total = $this->todos()->count();
        if ($total === 0) return 0;

        return ($this->completed_todos_count / $total) * 100;
    }
}
