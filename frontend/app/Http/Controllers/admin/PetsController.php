<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DeceasedReport;
use App\Models\Pet;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PetsController extends Controller
{
    /**
     * Display all pets and pending deceased reports.
     */
    public function index(): View
    {
        $pets = Pet::with('owner', 'healthRecord')
            ->whereIn('status', ['approved', 'deceased'])
            ->orderBy('registered_at', 'desc')
            ->get();

        $pendingDeceased = collect();

        // Counts for summary filters (case-insensitive)
        $counts = [
            'all' => $pets->count(),
            'cats' => $pets->filter(fn($p) => strtolower($p->pet_type) === 'cat')->count(),
            'dogs' => $pets->filter(fn($p) => strtolower($p->pet_type) === 'dog')->count(),
            'deceased' => $pets->where('status', 'deceased')->count(),
        ];

        return view('pages.pets', compact('pets', 'pendingDeceased', 'counts'));
    }

    /**
     * Confirm a deceased report.
     */
    public function confirmDeceased($reportId): RedirectResponse
    {
        $report = DeceasedReport::findOrFail($reportId);
        $report->update([
            'status' => 'approved',
            'approved_at' => now(),
        ]);

        $report->pet->update(['status' => 'deceased']);

        \App\Models\AuditLog::create([
            'user_id'      => auth()->id() ?? 1,
            'pet_id'       => $report->pet->pet_id,
            'report_id'    => $report->report_id,
            'status'       => 'approved',
            'old_status'   => 'pending',
            'new_status'   => 'approved',
            'action_notes' => "Confirmed deceased report for pet: {$report->pet->pet_name}.",
            'audit_date'   => now(),
            'created_at'   => now(),
        ]);

        return back()->with('success', "Deceased report confirmed for {$report->pet->pet_name}.");
    }

    /**
     * Reject a deceased report.
     */
    public function rejectDeceased($reportId): RedirectResponse
    {
        $report = DeceasedReport::findOrFail($reportId);
        $report->update(['status' => 'rejected']);

        \App\Models\AuditLog::create([
            'user_id'      => auth()->id() ?? 1,
            'pet_id'       => $report->pet->pet_id,
            'report_id'    => $report->report_id,
            'status'       => 'rejected',
            'old_status'   => 'pending',
            'new_status'   => 'rejected',
            'action_notes' => "Rejected deceased report for pet: {$report->pet->pet_name}.",
            'audit_date'   => now(),
            'created_at'   => now(),
        ]);

        return back()->with('success', "Deceased report rejected for {$report->pet->pet_name}.");
    }

    /**
     * Delete a pet.
     */
    public function destroy(Request $request, $id): RedirectResponse
    {
        $request->validate([
            'rejection_reason' => ['required', 'string', 'max:1000'],
        ]);

        $pet = Pet::findOrFail($id);
        $name = $pet->pet_name;
        $reason = $request->rejection_reason;
        $pet->delete();

        \App\Models\AuditLog::create([
            'user_id'      => auth()->id() ?? 1,
            'status'       => 'rejected',
            'old_status'   => $pet->status ?? null,
            'new_status'   => 'deleted',
            'action_notes' => "Deleted pet: {$name}. Reason: {$reason}",
            'audit_date'   => now(),
            'created_at'   => now(),
        ]);

        return redirect()->route('pets')->with('success', "Pet {$name} deleted.");
    }

    /**
     * Update pet health record.
     */
    public function updateHealth(Request $request, $id): RedirectResponse
    {
        $pet = Pet::findOrFail($id);
        
        $request->validate([
            'vaccinated' => ['nullable', 'boolean'],
            'vaccinated_date' => ['nullable', 'date'],
            'dewormed' => ['nullable', 'boolean'],
            'dewormed_date' => ['nullable', 'date'],
            'spayed_neutered' => ['nullable', 'boolean'],
            'spayed_date' => ['nullable', 'date'],
            'description' => ['nullable', 'string', 'max:1000'],
        ]);

        if ($pet->healthRecord) {
            $pet->healthRecord->update([
                'vaccinated' => $request->boolean('vaccinated'),
                'vaccinated_date' => $request->boolean('vaccinated') ? $request->vaccinated_date : null,
                'dewormed' => $request->boolean('dewormed'),
                'dewormed_date' => $request->boolean('dewormed') ? $request->dewormed_date : null,
                'spayed_neutered' => $request->boolean('spayed_neutered'),
                'spayed_date' => $request->boolean('spayed_neutered') ? $request->spayed_date : null,
                'description' => $request->description,
                'updated_at' => now(),
            ]);
        } else {
            $pet->healthRecord()->create([
                'vaccinated' => $request->boolean('vaccinated'),
                'vaccinated_date' => $request->boolean('vaccinated') ? $request->vaccinated_date : null,
                'dewormed' => $request->boolean('dewormed'),
                'dewormed_date' => $request->boolean('dewormed') ? $request->dewormed_date : null,
                'spayed_neutered' => $request->boolean('spayed_neutered'),
                'spayed_date' => $request->boolean('spayed_neutered') ? $request->spayed_date : null,
                'description' => $request->description,
                'updated_at' => now(),
            ]);
        }

        \App\Models\AuditLog::create([
            'user_id'      => auth()->id() ?? 1,
            'pet_id'       => $pet->pet_id,
            'status'       => 'approved',
            'new_status'   => 'updated',
            'action_notes' => "Updated health record for pet: {$pet->pet_name}.",
            'audit_date'   => now(),
            'created_at'   => now(),
        ]);

        return redirect()->route('pets')->with('success', "Health record for {$pet->pet_name} updated successfully.");
    }
}
