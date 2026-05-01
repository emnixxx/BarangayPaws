<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Pet;
use App\Models\PetHealthRecord;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display the dashboard with analytics.
     */
    public function index(): View
    {
        // ── Stat Cards ───────────────────────────────────────────────
        $totalUsers       = User::where('role', 'resident')->count();
        $totalPets        = Pet::count();
        $pendingApprovals = User::where('role', 'resident')->where('status', 'pending')->count()
                          + Pet::where('status', 'pending')->count();
        $deceasedPets     = Pet::where('status', 'deceased')->count();
        $vaccinatedPets   = PetHealthRecord::whereNotNull('vaccinated')->count();

        // ── Registrations Over Time (last 6 months) ─────────────────
        $regLabels = [];
        $regData   = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $regLabels[] = $month->format('M Y');
            $regData[]   = Pet::whereYear('registered_at', $month->year)
                              ->whereMonth('registered_at', $month->month)
                              ->count();
        }

        // ── Pet Type Distribution ───────────────────────────────────
        $petTypeCounts = Pet::select('pet_type', DB::raw('COUNT(*) as total'))
                            ->groupBy('pet_type')
                            ->pluck('total', 'pet_type');
        $dogCount = (int) ($petTypeCounts['Dog'] ?? $petTypeCounts['dog'] ?? 0);
        $catCount = (int) ($petTypeCounts['Cat'] ?? $petTypeCounts['cat'] ?? 0);

        // ── Health Rates ────────────────────────────────────────────
        $totalForRates  = max($totalPets, 1);
        $dewormedCount  = PetHealthRecord::where('dewormed', 1)->count();
        $spayedCount    = PetHealthRecord::where('spayed_neutered', 1)->count();

        $vaccinationRate = round(($vaccinatedPets / $totalForRates) * 100);
        $dewormingRate   = round(($dewormedCount / $totalForRates) * 100);
        $spayedRate      = round(($spayedCount / $totalForRates) * 100);

        // ── Recent Pets (latest 5) ──────────────────────────────────
        $recentPets = Pet::with('owner')
            ->orderByDesc('registered_at')
            ->limit(5)
            ->get()
            ->map(function ($pet) {
                return [
                    'name'   => $pet->pet_name,
                    'owner'  => optional($pet->owner)->user_name ?? 'Unknown',
                    'type'   => $pet->pet_type,
                    'status' => $pet->status,
                    'date'   => $pet->registered_at
                                    ? Carbon::parse($pet->registered_at)->format('M j, Y')
                                    : '—',
                ];
            });

        // ── Recent Audit Logs (latest 5) ────────────────────────────
        $recentAuditLogs = AuditLog::with(['user', 'pet'])
            ->orderByDesc('created_at')
            ->limit(5)
            ->get()
            ->map(function ($log) {
                $status    = strtolower($log->status ?? 'pending');
                $newStatus = strtolower($log->new_status ?? $status);

                $type = 'created';
                if (in_array($status, ['rejected']) || in_array($newStatus, ['rejected','deleted'])) $type = 'deleted';
                elseif ($status === 'approved') $type = 'approved';

                $entity = 'System';
                if ($log->pet_id)        $entity = 'Pet';
                elseif ($log->report_id) $entity = 'Report';
                elseif ($log->record_id) $entity = 'Health Record';

                return [
                    'type'  => $type,
                    'label' => $entity . ' ' . ucfirst($newStatus),
                    'desc'  => $log->action_notes ?? '',
                    'time'  => $log->created_at ? \Carbon\Carbon::parse($log->created_at)->diffForHumans() : '',
                ];
            });

        return view('pages.dashboard', compact(
            'totalUsers',
            'totalPets',
            'pendingApprovals',
            'deceasedPets',
            'vaccinatedPets',
            'regLabels',
            'regData',
            'dogCount',
            'catCount',
            'vaccinationRate',
            'dewormingRate',
            'spayedRate',
            'dewormedCount',
            'spayedCount',
            'recentPets',
            'recentAuditLogs'
        ));
    }
}