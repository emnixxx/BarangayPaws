<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    use HasFactory;

    protected $table = 'audit_logs';
    protected $primaryKey = 'audit_id';
    public $timestamps = false;

    protected $attributes = [
        'report_id'       => null,
        'pet_id'          => null,
        'record_id'       => null,
        'announcement_id' => null,
        'old_status'      => null,
        'new_status'      => null,
    ];

    protected $fillable = [
        'user_id',
        'report_id',
        'pet_id',
        'record_id',
        'announcement_id',
        'audit_date',
        'status',
        'old_status',
        'new_status',
        'action_notes',
        'created_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function pet()
    {
        return $this->belongsTo(Pet::class, 'pet_id', 'pet_id');
    }

    public function report()
    {
        return $this->belongsTo(\App\Models\DeceasedReport::class, 'report_id', 'report_id');
    }

    public function record()
    {
        return $this->belongsTo(\App\Models\PetHealthRecord::class, 'record_id', 'record_id');
    }
}
