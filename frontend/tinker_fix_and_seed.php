<?php
// Run with: php artisan tinker --execute="require base_path('tinker_fix_and_seed.php');"

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

DB::statement('SET FOREIGN_KEY_CHECKS=0;');

// ── 1. Re-link orphaned pets to valid users ───────────────────────
$validUserIds = DB::table('users')->where('role','resident')->pluck('user_id')->toArray();
$orphans = DB::table('pets')->whereNotIn('user_id', $validUserIds)->whereNotIn('user_id', [1])->get();
foreach ($orphans as $p) {
    $newOwnerId = $validUserIds[array_rand($validUserIds)] ?? 1;
    DB::table('pets')->where('pet_id', $p->pet_id)->update(['user_id' => $newOwnerId]);
}
echo "Re-linked " . count($orphans) . " orphan pets.\n";

// ── 2. Add more pending residents (for approval testing) ───────────
$pendingUsers = [
    ['user_name'=>'Roberto Silva',  'email'=>'roberto@example.com',  'gender'=>'Male'],
    ['user_name'=>'Diana Cruz',     'email'=>'diana@example.com',    'gender'=>'Female'],
    ['user_name'=>'Felix Ramos',    'email'=>'felix@example.com',    'gender'=>'Male'],
];
foreach ($pendingUsers as $u) {
    if (!DB::table('users')->where('email', $u['email'])->exists()) {
        DB::table('users')->insert(array_merge($u, [
            'password'        => Hash::make('password'),
            'contact_num'     => '0917' . random_int(1000000, 9999999),
            'role'            => 'resident',
            'status'          => 'pending',
            'is_active'       => 1,
            'date_registered' => now()->toDateString(),
            'created_at'      => now(),
            'updated_at'      => now(),
        ]));
    }
}

// ── 3. Add more pending pets (for approval testing) ────────────────
$validUserIds = DB::table('users')->where('role','resident')->where('status','approved')->pluck('user_id')->toArray();
$pendingPets = [
    ['pet_name'=>'Buddy',   'pet_type'=>'Dog', 'breed'=>'Beagle',         'gender'=>'Male',   'age'=>2, 'color_and_description'=>'Tri-color beagle'],
    ['pet_name'=>'Bella',   'pet_type'=>'Cat', 'breed'=>'Maine Coon',     'gender'=>'Female', 'age'=>3, 'color_and_description'=>'Brown tabby long-hair'],
    ['pet_name'=>'Charlie', 'pet_type'=>'Dog', 'breed'=>'Pomeranian',     'gender'=>'Male',   'age'=>1, 'color_and_description'=>'Cream fluffy'],
];
foreach ($pendingPets as $p) {
    if (!DB::table('pets')->where('pet_name', $p['pet_name'])->exists()) {
        DB::table('pets')->insert(array_merge($p, [
            'user_id'       => $validUserIds[array_rand($validUserIds)],
            'status'        => 'pending',
            'registered_at' => now()->toDateString(),
        ]));
    }
}

// ── 4. Add more announcements ──────────────────────────────────────
$existingTitles = DB::table('announcements')->pluck('title')->toArray();
$newAnnouncements = [
    ['title'=>'Monthly Pet Wellness Check',       'category'=>'general',     'target_pet_type'=>'other', 'event_date'=>now()->addDays(10)->toDateString(), 'location'=>'Health Center',     'details'=>'Free monthly wellness check for all registered pets in the barangay.'],
    ['title'=>'Rabies Awareness Seminar',         'category'=>'vaccination', 'target_pet_type'=>'Dogs',  'event_date'=>now()->addDays(5)->toDateString(),  'location'=>'Barangay Hall',     'details'=>'Educational seminar on rabies prevention and pet care basics.'],
    ['title'=>'Free Pet Grooming Day',            'category'=>'general',     'target_pet_type'=>'other', 'event_date'=>now()->addDays(20)->toDateString(), 'location'=>'Plaza Mayor',       'details'=>'Volunteer groomers will provide free basic grooming services.'],
];
foreach ($newAnnouncements as $a) {
    if (!in_array($a['title'], $existingTitles)) {
        DB::table('announcements')->insert(array_merge($a, [
            'user_id'    => 1,
            'date_posted'=> now()->toDateString(),
            'created_at' => now(),
            'updated_at' => now(),
        ]));
    }
}

DB::statement('SET FOREIGN_KEY_CHECKS=1;');

echo "\n=== FINAL COUNTS ===\n";
echo "Users:         " . DB::table('users')->count() . "\n";
echo "Residents:     " . DB::table('users')->where('role','resident')->count() . "\n";
echo "Pending Res:   " . DB::table('users')->where('role','resident')->where('status','pending')->count() . "\n";
echo "Pets:          " . DB::table('pets')->count() . "\n";
echo "Pending Pets:  " . DB::table('pets')->where('status','pending')->count() . "\n";
echo "Announcements: " . DB::table('announcements')->count() . "\n";
echo "Audit Logs:    " . DB::table('audit_logs')->count() . "\n";
