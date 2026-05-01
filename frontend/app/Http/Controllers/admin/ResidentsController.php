<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ResidentsController extends Controller
{
    /**
     * Display all approved residents with their pets.
     */
    public function index(): View
    {
        $residents = User::where('role', 'resident')
            ->where('status', 'approved')
            ->with('pets')
            ->orderBy('user_name')
            ->get();

        return view('pages.residents', compact('residents'));
    }

    /**
     * Delete a resident.
     */
    public function destroy(Request $request, $id): RedirectResponse
    {
        $request->validate([
            'rejection_reason' => ['required', 'string', 'max:1000'],
        ]);

        $resident = User::where('role', 'resident')->findOrFail($id);
        $name = $resident->user_name;
        $reason = $request->rejection_reason;
        $resident->delete();

        \App\Models\AuditLog::create([
            'user_id'      => auth()->id() ?? 1,
            'status'       => 'rejected',
            'new_status'   => 'deleted',
            'action_notes' => "Deleted resident: {$name}. Reason: {$reason}",
            'audit_date'   => now(),
            'created_at'   => now(),
        ]);

        return redirect()->route('residents')->with('success', "Resident {$name} deleted.");
    }
}
