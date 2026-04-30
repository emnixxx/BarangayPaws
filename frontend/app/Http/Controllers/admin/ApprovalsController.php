<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pet;
use App\Models\User;
use App\Services\AuditLogger;
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

    public function approveResident($id): RedirectResponse
    {
        $resident = User::where('role', 'resident')->findOrFail($id);
        $resident->update([
            'status' => 'approved',
            'approved_at' => now(),
            'rejection_reason' => null,
        ]);

        AuditLogger::log('Approved Resident', $resident->user_name, "Approved resident ID {$id}.");

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

        AuditLogger::log('Rejected Resident', $resident->user_name, "Rejected resident ID {$id}. Reason: {$request->rejection_reason}");

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

        AuditLogger::log('Approved Pet', $pet->pet_name, "Approved pet ID {$id} belonging to owner ID {$pet->owner_id}.");

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

        AuditLogger::log('Rejected Pet', $pet->pet_name, "Rejected pet ID {$id}. Reason: {$request->rejection_reason}");

        return back()->with('success', "Pet {$pet->pet_name} rejected.");
    }
}