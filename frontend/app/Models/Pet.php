<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pet extends Model
{
    protected $table = 'pets';
    protected $primaryKey = 'pet_id';
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'pet_name',
        'pet_type',
        'breed',
        'gender',
        'age',
        'color',
        'status',
        'registered_at',
        'approved_at',
    ];

    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function healthRecord()
    {
        return $this->hasOne(PetHealthRecord::class, 'pet_id', 'pet_id');
    }

    public function deceasedReport()
    {
        return $this->hasOne(DeceasedReport::class, 'pet_id', 'pet_id');
    }
}