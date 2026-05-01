<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pet;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ApprovalsController extends Controller
{
    public function index(): View
    {
        $pendingResidents = User::where('role', 'resident')
            ->where('status', 'pending')
            ->orderBy('date_registered', 'desc')
            ->get();

        $pendingPets = Pet::with('owner')
            ->where('status', 'pending')
            ->orderBy('registered_at', 'desc')
            ->get();

        return view('pages.approvals', compact('pendingResidents', 'pendingPets'));
    }

    public function getPendingCount()
    {
        $pendingResidents = User::where('role', 'resident')->where('status', 'pending')->count();
        $pendingPets = Pet::where('status', 'pending')->count();

        return response()->json([
            'count' => $pendingResidents + $pendingPets,
            'residents' => $pendingResidents,
            'pets' => $pendingPets
        ]);
    }

    public function getPendingItems()
    {
        $residents = User::where('role', 'resident')
            ->where('status', 'pending')
            ->orderByDesc('date_registered')
            ->limit(10)
            ->get(['user_id', 'user_name', 'email', 'date_registered'])
            ->map(function ($r) {
                return [
                    'type'  => 'resident',
                    'id'    => $r->user_id,
                    'title' => $r->user_name,
                    'sub'   => $r->email,
                    'time'  => $r->date_registered ? \Carbon\Carbon::parse($r->date_registered)->diffForHumans() : '',
                ];
            });

        $pets = Pet::with('owner:user_id,user_name')
            ->where('status', 'pending')
            ->orderByDesc('registered_at')
            ->limit(10)
            ->get()
            ->map(function ($p) {
                return [
                    'type'  => 'pet',
                    'id'    => $p->pet_id,
                    'title' => $p->pet_name,
                    'sub'   => ($p->pet_type ? ucfirst($p->pet_type) : 'Pet') . ' • ' . (optional($p->owner)->user_name ?? 'Unknown owner'),
                    'time'  => $p->registered_at ? \Carbon\Carbon::parse($p->registered_at)->diffForHumans() : '',
                ];
            });

        $items = $residents->concat($pets)->values();

        return response()->json([
            'count'           => $items->count(),
            'residents_count' => $residents->count(),
            'pets_count'      => $pets->count(),
            'items'           => $items,
        ]);
    }

    public function approveResident($id): RedirectResponse
    {
        $resident = User::where('role', 'resident')->findOrFail($id);
        $resident->update([
            'status' => 'approved',
            'approved_at' => now(),
            'rejection_reason' => null,
        ]);

        \App\Models\AuditLog::create([
            'user_id'      => auth()->id() ?? 1,
            'status'       => 'approved',
            'old_status'   => 'pending',
            'new_status'   => 'approved',
            'action_notes' => "Approved resident registration: {$resident->user_name}.",
            'audit_date'   => now(),
            'created_at'   => now(),
        ]);

        return back()->with('success', "Resident {$resident->user_name} approved.");
    }

    public function rejectResident(Request $request, $id): RedirectResponse
    {
        $request->validate([
            'rejection_reason' => ['required', 'string', 'max:1000'],
        ]);

        $resident = User::where('role', 'resident')->findOrFail($id);
        $resident->update([
            'status' => 'rejected',
            'rejection_reason' => $request->rejection_reason,
        ]);

        \App\Models\AuditLog::create([
            'user_id'      => auth()->id() ?? 1,
            'status'       => 'rejected',
            'old_status'   => 'pending',
            'new_status'   => 'rejected',
            'action_notes' => "Rejected resident registration: {$resident->user_name}. Reason: {$request->rejection_reason}",
            'audit_date'   => now(),
            'created_at'   => now(),
        ]);

        return back()->with('success', "Resident {$resident->user_name} rejected.");
    }

    public function approvePet($id): RedirectResponse
    {
        $pet = Pet::findOrFail($id);
        $pet->update([
            'status' => 'approved',
            'approved_at' => now(),
            'rejection_reason' => null,
        ]);

        \App\Models\AuditLog::create([
            'user_id'      => auth()->id() ?? 1,
            'pet_id'       => $pet->pet_id,
            'status'       => 'approved',
            'old_status'   => 'pending',
            'new_status'   => 'approved',
            'action_notes' => "Approved pet registration: {$pet->pet_name}.",
            'audit_date'   => now(),
            'created_at'   => now(),
        ]);

        return back()->with('success', "Pet {$pet->pet_name} approved.");
    }

    public function rejectPet(Request $request, $id): RedirectResponse
    {
        $request->validate([
            'rejection_reason' => ['required', 'string', 'max:1000'],
        ]);

        $pet = Pet::findOrFail($id);
        $pet->update([
            'status' => 'rejected',
            'rejection_reason' => $request->rejection_reason,
        ]);

        \App\Models\AuditLog::create([
            'user_id'      => auth()->id() ?? 1,
            'pet_id'       => $pet->pet_id,
            'status'       => 'rejected',
            'old_status'   => 'pending',
            'new_status'   => 'rejected',
            'action_notes' => "Rejected pet registration: {$pet->pet_name}. Reason: {$request->rejection_reason}",
            'audit_date'   => now(),
            'created_at'   => now(),
        ]);

        return back()->with('success', "Pet {$pet->pet_name} rejected.");
    }
}
