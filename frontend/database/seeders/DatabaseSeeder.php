<?php

namespace Database\Seeders;

use App\Models\DeceasedReport;
use App\Models\Pet;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // ─── ADMIN ACCOUNT ──────────────────────────────
        User::create([
            'user_name' => 'Juan Dela Cruz',
            'email' => 'admin@barangaypaws.com',
            'password' => Hash::make('admin123'),
            'gender' => 'Male',
            'contact_num' => '0917-111-2222',
            'address' => 'Barangay Hall, San Jose',
            'role' => 'admin',
            'status' => 'approved',
            'is_active' => true,
            'date_registered' => now()->subMonths(6),
            'approved_at' => now()->subMonths(6),
        ]);

        // ─── APPROVED RESIDENTS ─────────────────────────
        $approvedResidents = [
            ['Maria Santos', 'maria.santos@email.com', '0917-123-4567', '123 Main St, San Jose', 'Female'],
            ['Pedro Cruz', 'pedro.cruz@email.com', '0918-234-5678', '456 Oak Ave, San Jose', 'Male'],
            ['Rosa Lopez', 'rosa.lopez@email.com', '0919-345-6789', '789 Pine Rd, San Jose', 'Female'],
            ['Jose Garcia', 'jose.garcia@email.com', '0920-456-7890', '321 Elm St, San Jose', 'Male'],
        ];

        foreach ($approvedResidents as [$name, $email, $phone, $address, $gender]) {
            User::create([
                'user_name' => $name,
                'email' => $email,
                'password' => Hash::make('password123'),
                'gender' => $gender,
                'contact_num' => $phone,
                'address' => $address,
                'role' => 'resident',
                'status' => 'approved',
                'is_active' => true,
                'date_registered' => now()->subDays(rand(30, 90)),
                'approved_at' => now()->subDays(rand(1, 29)),
            ]);
        }

        // ─── PENDING RESIDENTS ──────────────────────────
        $pendingResidents = [
            ['Ana Reyes', 'ana.reyes@email.com', '0921-555-1111', '654 Birch Ln, San Jose', 'Female'],
            ['Carlos Martinez', 'carlos.martinez@email.com', '0922-555-2222', '987 Maple Dr, San Jose', 'Male'],
            ['Isabel Cruz', 'isabel.cruz@email.com', '0923-555-3333', '147 Cedar Ct, San Jose', 'Female'],
        ];

        foreach ($pendingResidents as [$name, $email, $phone, $address, $gender]) {
            User::create([
                'user_name' => $name,
                'email' => $email,
                'password' => Hash::make('password123'),
                'gender' => $gender,
                'contact_num' => $phone,
                'address' => $address,
                'role' => 'resident',
                'status' => 'pending',
                'is_active' => true,
                'date_registered' => now()->subDays(rand(1, 7)),
                'approved_at' => null,
            ]);
        }

        // ─── APPROVED PETS ──────────────────────────────
        $residents = User::where('role', 'resident')->where('status', 'approved')->get();

        $approvedPets = [
            ['Max', 'dog', 'Labrador', 'Male', 3, 'Golden'],
            ['Whiskers', 'cat', 'Persian', 'Female', 2, 'White'],
            ['Luna', 'cat', 'Siamese', 'Female', 4, 'Gray'],
            ['Rocky', 'dog', 'German Shepherd', 'Male', 5, 'Black'],
        ];

        foreach ($approvedPets as $i => [$name, $type, $breed, $gender, $age, $color]) {
            Pet::create([
                'user_id' => $residents[$i % $residents->count()]->user_id,
                'pet_name' => $name,
                'pet_type' => $type,
                'breed' => $breed,
                'gender' => $gender,
                'age' => $age,
                'color' => $color,
                'status' => 'approved',
                'registered_at' => now()->subDays(rand(30, 60)),
                'approved_at' => now()->subDays(rand(1, 29)),
            ]);
        }

        // ─── PENDING PETS ───────────────────────────────
        $pendingPets = [
            ['Buddy', 'dog', 'Beagle', 'Male', 2, 'Brown'],
            ['Mittens', 'cat', 'Domestic Shorthair', 'Female', 1, 'Black and White'],
        ];

        foreach ($pendingPets as $i => [$name, $type, $breed, $gender, $age, $color]) {
            Pet::create([
                'user_id' => $residents[$i]->user_id,
                'pet_name' => $name,
                'pet_type' => $type,
                'breed' => $breed,
                'gender' => $gender,
                'age' => $age,
                'color' => $color,
                'status' => 'pending',
                'registered_at' => now()->subDays(rand(1, 5)),
                'approved_at' => null,
            ]);
        }

        // ─── PENDING DECEASED REPORTS ───────────────────
        // Create 2 extra approved pets that will have deceased reports pending
        $petsForDeceased = [
            ['Bruno', 'dog', 'Aspin', 'Male', 12, 'Brown'],
            ['Tom', 'cat', 'Domestic Shorthair', 'Male', 10, 'Orange'],
        ];

        $deceasedCauses = ['Old age', 'Illness'];

        foreach ($petsForDeceased as $i => [$name, $type, $breed, $gender, $age, $color]) {
            $pet = Pet::create([
                'user_id' => $residents[$i]->user_id,
                'pet_name' => $name,
                'pet_type' => $type,
                'breed' => $breed,
                'gender' => $gender,
                'age' => $age,
                'color' => $color,
                'status' => 'approved', // still approved; not deceased until report confirmed
                'registered_at' => now()->subDays(rand(90, 180)),
                'approved_at' => now()->subDays(rand(60, 89)),
            ]);

            DeceasedReport::create([
                'pet_id' => $pet->pet_id,
                'user_id' => $pet->user_id,
                'date_of_death' => now()->subDays(rand(1, 10)),
                'cause' => $deceasedCauses[$i],
                'status' => 'pending',
                'approved_at' => null,
                'created_at' => now()->subDays(rand(1, 5)),
            ]);
        }

        $this->command->info('');
        $this->command->info('✓ Seeding complete!');
        $this->command->info('─────────────────────────────────');
        $this->command->info('Admin login:');
        $this->command->info('  Email:    admin@barangaypaws.com');
        $this->command->info('  Password: admin123');
        $this->command->info('─────────────────────────────────');
        $this->command->info('Resident password: password123');
    }
}