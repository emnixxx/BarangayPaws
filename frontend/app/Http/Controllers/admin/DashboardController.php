<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Pet;
use App\Models\PetHealthRecord;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $totalPets = Pet::whereIn('status', ['approved'])->count();
        $pendingApprovals = User::where('role', 'resident')->where('status', 'pending')->count() + Pet::where('status', 'pending')->count();
        $deceased = Pet::where('status', 'deceased')->count();

        $vaccinated = PetHealthRecord::where('vaccinated', 1)->count();
        $dewormed = PetHealthRecord::where('dewormed', 1)->count();
        $spayed = PetHealthRecord::where('spayed_neutered', 1)->count();

        $registrations = [
            'labels' => [],
            'data' => []
        ];
        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $count = Pet::whereYear('registered_at', $month->year)
                ->whereMonth('registered_at', $month->month)
                ->count();
            $registrations['labels'][] = $month->format('M');
            $registrations['data'][] = $count;
        }

        $dogs = Pet::where('pet_type', 'dog')->count();
        $cats = Pet::where('pet_type', 'cat')->count();
        $petTypes = [
            'dogs' => $dogs,
            'cats' => $cats
        ];

        $recentPets = Pet::with('owner')->orderBy('registered_at', 'desc')->take(4)->get()->map(function($pet) {
            return [
                'name' => $pet->pet_name,
                'owner' => $pet->owner->user_name ?? 'Unknown',
                'type' => ucfirst($pet->pet_type),
                'status' => $pet->status,
                'date' => Carbon::parse($pet->registered_at)->format('M j, Y')
            ];
        });

        $auditLogs = AuditLog::with('user')->orderBy('created_at', 'desc')->take(4)->get()->map(function($log) {
            return [
                'type' => 'general',
                'label' => 'Log',
                'desc' => $log->action . ': ' . $log->target,
                'time' => $log->created_at->diffForHumans()
            ];
        });
        
        $stats = [
            'totalPets' => $totalPets,
            'pendingApprovals' => $pendingApprovals,
            'vaccinated' => $vaccinated,
            'deceased' => $deceased,
        ];

        $rates = [
            'vaccination' => $totalPets > 0 ? round(($vaccinated / $totalPets) * 100) : 0,
            'deworming' => $totalPets > 0 ? round(($dewormed / $totalPets) * 100) : 0,
            'spayed' => $totalPets > 0 ? round(($spayed / $totalPets) * 100) : 0,
        ];

        $rawCounts = [
            'vaccinated' => $vaccinated,
            'dewormed' => $dewormed,
            'spayed' => $spayed,
        ];

        return view('pages.dashboard', compact(
            'stats', 'registrations', 'petTypes', 'rates', 'recentPets', 'auditLogs', 'totalPets', 'rawCounts'
        ));
    }
}