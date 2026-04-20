<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $primaryKey = 'user_id';

    public $timestamps = true;

    protected $fillable = [
        'user_name',
        'email',
        'password',
        'gender',
        'contact_num',
        'address',
        'role',
        'status',
        'is_active',
        'date_registered',
        'approved_at',
        'profile_icon',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    // Relationship: user has many pets
    public function pets()
    {
        return $this->hasMany(Pet::class, 'user_id', 'user_id');
    }
}