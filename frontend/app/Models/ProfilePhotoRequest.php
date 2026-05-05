<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProfilePhotoRequest extends Model
{
    protected $table = 'profile_photo_requests';

    protected $fillable = [
        'resident_id',
        'current_photo_path',
        'new_photo_path',
        'status',
        'requested_at',
        'reviewed_at',
        'reviewed_by',
        'rejection_reason',
    ];

    protected $casts = [
        'requested_at' => 'datetime',
        'reviewed_at'  => 'datetime',
    ];

    public function resident()
    {
        return $this->belongsTo(User::class, 'resident_id', 'user_id');
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by', 'user_id');
    }
}
