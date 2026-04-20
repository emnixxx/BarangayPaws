<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PetHealthRecord extends Model
{
    protected $table = 'pet_health_record';
    protected $primaryKey = 'record_id';
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

    protected $casts = [
        'vaccinated' => 'boolean',
        'dewormed' => 'boolean',
        'spayed_neutered' => 'boolean',
    ];

    public function pet()
    {
        return $this->belongsTo(Pet::class, 'pet_id', 'pet_id');
    }
}