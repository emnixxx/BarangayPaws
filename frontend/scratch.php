<?php

use App\Models\Pet;
use App\Models\PetHealthRecord;

$pets = Pet::all();
foreach ($pets as $index => $pet) {
    PetHealthRecord::updateOrCreate(
        ['pet_id' => $pet->pet_id],
        [
            'vaccinated' => $index % 2 == 0 ? 1 : 0,
            'vaccinated_date' => $index % 2 == 0 ? now()->subDays(rand(1, 100)) : null,
            'dewormed' => $index % 3 == 0 ? 1 : 0,
            'dewormed_date' => $index % 3 == 0 ? now()->subDays(rand(1, 100)) : null,
            'spayed_neutered' => $index % 4 == 0 ? 1 : 0,
            'spayed_date' => $index % 4 == 0 ? now()->subDays(rand(1, 100)) : null,
            'description' => 'Automatically generated health record for testing.',
            'updated_at' => now(),
        ]
    );
}
echo "Health records created for " . count($pets) . " pets.\n";
