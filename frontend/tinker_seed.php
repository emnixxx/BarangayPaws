<?php
// Run with:  php artisan tinker --execute="require base_path('tinker_seed.php');"
// Or paste into:  php artisan tinker

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

DB::statement('SET FOREIGN_KEY_CHECKS=0;');

// ── USERS (residents) ────────────────────────────────────────────────
$users = [
    ['user_name'=>'Juan Dela Cruz',     'email'=>'juan@example.com',     'gender'=>'Male',   'contact_num'=>'09171234501', 'role'=>'resident', 'status'=>'approved'],
    ['user_name'=>'Maria Santos',       'email'=>'maria@example.com',    'gender'=>'Female', 'contact_num'=>'09171234502', 'role'=>'resident', 'status'=>'approved'],
    ['user_name'=>'Pedro Reyes',        'email'=>'pedro@example.com',    'gender'=>'Male',   'contact_num'=>'09171234503', 'role'=>'resident', 'status'=>'approved'],
    ['user_name'=>'Ana Lopez',          'email'=>'ana@example.com',      'gender'=>'Female', 'contact_num'=>'09171234504', 'role'=>'resident', 'status'=>'approved'],
    ['user_name'=>'Jose Mercado',       'email'=>'jose@example.com',     'gender'=>'Male',   'contact_num'=>'09171234505', 'role'=>'resident', 'status'=>'pending'],
    ['user_name'=>'Liza Cruz',          'email'=>'liza@example.com',     'gender'=>'Female', 'contact_num'=>'09171234506', 'role'=>'resident', 'status'=>'pending'],
    ['user_name'=>'Mark Tan',           'email'=>'mark@example.com',     'gender'=>'Male',   'contact_num'=>'09171234507', 'role'=>'resident', 'status'=>'approved'],
    ['user_name'=>'Carla Dizon',        'email'=>'carla@example.com',    'gender'=>'Female', 'contact_num'=>'09171234508', 'role'=>'resident', 'status'=>'approved'],
];
foreach ($users as $u) {
    DB::table('users')->insert(array_merge($u, [
        'password'        => Hash::make('password'),
        'is_active'       => 1,
        'date_registered' => now()->toDateString(),
        'approved_at'     => $u['status']==='approved' ? now() : null,
        'created_at'      => now(),
        'updated_at'      => now(),
    ]));
}

// ── PETS ─────────────────────────────────────────────────────────────
$pets = [
    ['user_id'=>2, 'pet_name'=>'Bantay',  'pet_type'=>'Dog', 'breed'=>'Aspin',          'gender'=>'Male',   'age'=>3, 'color_and_description'=>'Brown with white chest', 'status'=>'approved'],
    ['user_id'=>2, 'pet_name'=>'Muning',  'pet_type'=>'Cat', 'breed'=>'Puspin',         'gender'=>'Female', 'age'=>2, 'color_and_description'=>'Orange tabby',           'status'=>'approved'],
    ['user_id'=>3, 'pet_name'=>'Bruno',   'pet_type'=>'Dog', 'breed'=>'Labrador',       'gender'=>'Male',   'age'=>4, 'color_and_description'=>'Golden coat',            'status'=>'approved'],
    ['user_id'=>4, 'pet_name'=>'Whiskey', 'pet_type'=>'Cat', 'breed'=>'Persian',        'gender'=>'Male',   'age'=>1, 'color_and_description'=>'White long-haired',      'status'=>'approved'],
    ['user_id'=>5, 'pet_name'=>'Luna',    'pet_type'=>'Dog', 'breed'=>'Shih Tzu',       'gender'=>'Female', 'age'=>5, 'color_and_description'=>'Black and white',        'status'=>'approved'],
    ['user_id'=>5, 'pet_name'=>'Max',     'pet_type'=>'Dog', 'breed'=>'Aspin',          'gender'=>'Male',   'age'=>2, 'color_and_description'=>'Brown',                   'status'=>'pending'],
    ['user_id'=>8, 'pet_name'=>'Coco',    'pet_type'=>'Dog', 'breed'=>'Poodle',         'gender'=>'Female', 'age'=>3, 'color_and_description'=>'White curly fur',        'status'=>'approved'],
    ['user_id'=>9, 'pet_name'=>'Tom',     'pet_type'=>'Cat', 'breed'=>'Puspin',         'gender'=>'Male',   'age'=>4, 'color_and_description'=>'Gray striped',           'status'=>'approved'],
    ['user_id'=>3, 'pet_name'=>'Rex',     'pet_type'=>'Dog', 'breed'=>'German Shepherd','gender'=>'Male',   'age'=>6, 'color_and_description'=>'Black and tan',          'status'=>'deceased'],
    ['user_id'=>4, 'pet_name'=>'Mittens', 'pet_type'=>'Cat', 'breed'=>'Siamese',        'gender'=>'Female', 'age'=>3, 'color_and_description'=>'Cream with brown points','status'=>'pending'],
];
foreach ($pets as $p) {
    DB::table('pets')->insert(array_merge($p, [
        'registered_at' => now()->toDateString(),
        'approved_at'   => in_array($p['status'], ['approved','deceased']) ? now() : null,
    ]));
}

// ── PET_HEALTH_RECORD ────────────────────────────────────────────────
for ($petId = 1; $petId <= 10; $petId++) {
    DB::table('pet_health_record')->insert([
        'pet_id'         => $petId,
        'vaccinated'     => now()->subMonths(rand(1,6))->toDateString(),
        'dewormed'       => 1,
        'vaccinated_date'=> now()->subMonths(rand(1,6))->toDateString(),
        'dewormed_date'  => now()->subMonths(rand(1,4))->toDateString(),
        'spayed_neutered'=> rand(0,1),
        'spayed_date'    => now()->subMonths(rand(1,12))->toDateString(),
        'description'    => 'Routine health check completed. Pet is in good condition.',
        'updated_at'     => now(),
    ]);
}

// ── PET_MEDIA ────────────────────────────────────────────────────────
for ($petId = 1; $petId <= 10; $petId++) {
    DB::table('pet_media')->insert([
        'pet_id'       => $petId,
        'profile_icon' => 'pet_'.$petId.'.png',
        'is_primary'   => 1,
        'uploaded_at'  => now(),
        'approved_at'  => now(),
    ]);
}

// ── REPORTS ──────────────────────────────────────────────────────────
$reports = [
    ['pet_id'=>1, 'vaccinated'=>1, 'description'=>'Anti-rabies vaccination completed.'],
    ['pet_id'=>2, 'dewormed'=>1,   'description'=>'Deworming dose administered.'],
    ['pet_id'=>3, 'spayed_neutered'=>1, 'description'=>'Neutering procedure successful.'],
    ['pet_id'=>4, 'vaccinated'=>1, 'dewormed'=>1, 'description'=>'Combined vaccine and deworming.'],
    ['pet_id'=>5, 'vaccinated'=>1, 'description'=>'5-in-1 vaccine given.'],
    ['pet_id'=>7, 'spayed_neutered'=>1, 'description'=>'Spay surgery completed.'],
    ['pet_id'=>8, 'dewormed'=>1, 'description'=>'Deworming for adult cat.'],
];
foreach ($reports as $r) {
    DB::table('reports')->insert(array_merge([
        'vaccinated'=>0,'vaccinated_date'=>now()->subDays(rand(5,60))->toDateString(),
        'dewormed'=>0,'dewormed_date'=>now()->subDays(rand(5,60))->toDateString(),
        'spayed_neutered'=>0,'spayed_date'=>now()->subDays(rand(5,60))->toDateString(),
        'updated_at'=>now(),
    ], $r));
}

// ── ANNOUNCEMENTS ────────────────────────────────────────────────────
$announcements = [
    ['title'=>'Free Anti-Rabies Vaccination',     'category'=>'vaccination',  'target_pet_type'=>'Dogs', 'event_date'=>now()->addDays(7)->toDateString(),  'location'=>'Barangay Hall',          'details'=>'Bring your dogs for a free anti-rabies shot. 8AM-12PM.'],
    ['title'=>'Libreng Kapon Drive',              'category'=>'libre_kapon',  'target_pet_type'=>'Cats', 'event_date'=>now()->addDays(14)->toDateString(), 'location'=>'Health Center',          'details'=>'Free spay/neuter for cats. Pre-registration required.'],
    ['title'=>'Deworming Day for Pets',           'category'=>'deworming',    'target_pet_type'=>'Dogs', 'event_date'=>now()->addDays(3)->toDateString(),  'location'=>'Plaza',                  'details'=>'Free deworming for all registered dogs.'],
    ['title'=>'Spay & Neuter Awareness',          'category'=>'spay_neuter',  'target_pet_type'=>'other','event_date'=>now()->addDays(21)->toDateString(), 'location'=>'Multipurpose Hall',      'details'=>'Seminar on responsible pet ownership.'],
    ['title'=>'Reminder: Pet Registration',       'category'=>'general',      'target_pet_type'=>'other','event_date'=>now()->addDays(30)->toDateString(), 'location'=>'Barangay Office',        'details'=>'All residents must register their pets by month-end.'],
];
foreach ($announcements as $a) {
    DB::table('announcements')->insert(array_merge($a, [
        'user_id'    => 1,
        'date_posted'=> now()->toDateString(),
        'created_at' => now(),
        'updated_at' => now(),
    ]));
}

// ── AUDIT_LOGS ───────────────────────────────────────────────────────
$logs = [
    ['user_id'=>1, 'pet_id'=>1, 'status'=>'approved','old_status'=>'pending','new_status'=>'approved','action_notes'=>'Pet approved after document verification.'],
    ['user_id'=>1, 'pet_id'=>2, 'status'=>'approved','old_status'=>'pending','new_status'=>'approved','action_notes'=>'Pet approved.'],
    ['user_id'=>1, 'pet_id'=>9, 'status'=>'approved','old_status'=>'approved','new_status'=>'deceased','action_notes'=>'Pet marked as deceased.'],
    ['user_id'=>1, 'report_id'=>1,'status'=>'approved','old_status'=>'pending','new_status'=>'approved','action_notes'=>'Vaccination report approved.'],
    ['user_id'=>1, 'report_id'=>2,'status'=>'approved','old_status'=>'pending','new_status'=>'approved','action_notes'=>'Deworming report approved.'],
    ['user_id'=>1, 'record_id'=>1,'status'=>'approved','old_status'=>'pending','new_status'=>'approved','action_notes'=>'Health record verified.'],
    ['user_id'=>1, 'pet_id'=>10,'status'=>'pending','old_status'=>null,'new_status'=>'pending','action_notes'=>'New pet registration awaiting review.'],
    ['user_id'=>1, 'pet_id'=>6, 'status'=>'rejected','old_status'=>'pending','new_status'=>'rejected','action_notes'=>'Incomplete documents.'],
];
foreach ($logs as $l) {
    DB::table('audit_logs')->insert(array_merge([
        'report_id'=>null,'pet_id'=>null,'record_id'=>null,
        'audit_date'=>now(),'created_at'=>now(),
    ], $l));
}

// ── PASSWORD_RESET_TOKENS ────────────────────────────────────────────
DB::table('password_reset_tokens')->insert([
    'email'      => 'juan@example.com',
    'token'      => Hash::make('reset-token-sample'),
    'created_at' => now(),
]);

DB::statement('SET FOREIGN_KEY_CHECKS=1;');

echo "✅ Seeded: ".DB::table('users')->count()." users, "
    .DB::table('pets')->count()." pets, "
    .DB::table('pet_health_record')->count()." health records, "
    .DB::table('pet_media')->count()." media, "
    .DB::table('reports')->count()." reports, "
    .DB::table('announcements')->count()." announcements, "
    .DB::table('audit_logs')->count()." audit logs.\n";
