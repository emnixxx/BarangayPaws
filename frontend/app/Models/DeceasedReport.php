<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeceasedReport extends Model
{
    protected $table = 'deceased_reports';
    protected $primaryKey = 'report_id';
    public $timestamps = false;

    protected $fillable = [
        'pet_id',
        'user_id',
        'date_of_death',
        'cause',
        'status',
        'approved_at',
        'created_at',
    ];

    public function pet()
    {
        return $this->belongsTo(Pet::class, 'pet_id', 'pet_id');
    }

    public function reporter()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }
}