<?php
// Quick diagnostic
use Illuminate\Support\Facades\DB;

echo "=== USERS ===\n";
foreach (DB::table('users')->get(['user_id','user_name','role']) as $u) {
    echo "  [{$u->user_id}] {$u->user_name} ({$u->role})\n";
}

echo "\n=== PETS ===\n";
foreach (DB::table('pets')->get(['pet_id','user_id','pet_name','pet_type','status']) as $p) {
    echo "  [{$p->pet_id}] {$p->pet_name} ({$p->pet_type}, {$p->status}) -> user_id={$p->user_id}\n";
}

echo "\n=== COUNTS ===\n";
echo "  Pet::count() = " . App\Models\Pet::count() . "\n";
echo "  approved+deceased = " . App\Models\Pet::whereIn('status', ['approved','deceased'])->count() . "\n";
echo "  pending pets = " . App\Models\Pet::where('status','pending')->count() . "\n";
echo "  pending residents = " . App\Models\User::where('role','resident')->where('status','pending')->count() . "\n";
echo "  vaccinated records = " . App\Models\PetHealthRecord::where('vaccinated', 1)->count() . "\n";

echo "\n=== OWNER TEST ===\n";
$pet = App\Models\Pet::with('owner')->first();
if ($pet) {
    echo "  Pet: {$pet->pet_name}, user_id={$pet->user_id}, owner=" . ($pet->owner ? $pet->owner->user_name : 'NULL') . "\n";
}
