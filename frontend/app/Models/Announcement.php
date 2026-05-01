<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    use HasFactory;

    protected $table = 'announcements';
    protected $primaryKey = 'announcement_id';
    public $timestamps = true;

    protected $fillable = [
        'user_id',
        'title',
        'category',
        'target_pet_type',
        'event_date',
        'location',
        'details',
        'date_posted',
    ];

    public function postedBy()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }
}
