<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeceasedReport extends Model
{
    protected $table = 'reports';
    protected $primaryKey = 'report_id';
    public $timestamps = false;

    protected $fillable = [
        'pet_id',
        'vaccinated',
        'vaccinated_date',
        'dewormed',
        'dewormed_date',
        'spayed_neutered',
        'spayed_date',
        'description',
        'updated_at',
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