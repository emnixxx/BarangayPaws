<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    use HasFactory;

    protected $primaryKey = 'announcement_id';

    protected $fillable = [
        'title',
        'category',
        'target_pet_type',
        'event_date',
        'location',
        'details',
        'posted_by',
    ];

    public function postedBy()
    {
        return $this->belongsTo(User::class, 'posted_by', 'user_id');
    }
}
