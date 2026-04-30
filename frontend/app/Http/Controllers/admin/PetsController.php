<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DeceasedReport;
use App\Models\Pet;
use App\Models\PetHealthRecord;
use App\Services\AuditLogger;
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

        $pendingDeceased = DeceasedReport::with('pet.owner')
            ->where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->get();

        // Counts for summary filters
        $counts = [
            'all' => $pets->count(),
            'cats' => $pets->where('pet_type', 'cat')->count(),
            'dogs' => $pets->where('pet_type', 'dog')->count(),
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

        AuditLogger::log('Confirmed Deceased Report', $report->pet->pet_name, "Confirmed deceased status for pet ID {$report->pet->pet_id}.");

        return back()->with('success', "Deceased report confirmed for {$report->pet->pet_name}.");
    }

    /**
     * Reject a deceased report.
     */
    public function rejectDeceased($reportId): RedirectResponse
    {
        $report = DeceasedReport::findOrFail($reportId);
        $report->update(['status' => 'rejected']);

        AuditLogger::log('Rejected Deceased Report', $report->pet->pet_name, "Rejected deceased status for pet ID {$report->pet->pet_id}.");

        return back()->with('success', "Deceased report rejected for {$report->pet->pet_name}.");
    }

    /**
     * Delete a pet.
     */
    public function destroy($id): RedirectResponse
    {
        $pet = Pet::findOrFail($id);
        $name = $pet->pet_name;
        $pet->delete();

        AuditLogger::log('Deleted Pet', $name, "Deleted pet ID {$id} and their data.");

        return back()->with('success', "Pet {$name} deleted.");
    }

    /**
     * Update health records for a pet.
     */
    public function updateHealth(Request $request, $id): RedirectResponse
    {
        $pet = Pet::findOrFail($id);

        $validated = $request->validate([
            'vaccinated_date' => 'nullable|date',
            'dewormed_date' => 'nullable|date',
            'spayed_date' => 'nullable|date',
            'description' => 'nullable|string',
        ]);

        PetHealthRecord::updateOrCreate(
            ['pet_id' => $id],
            [
                'vaccinated' => $request->has('vaccinated'),
                'vaccinated_date' => $request->vaccinated_date,
                'dewormed' => $request->has('dewormed'),
                'dewormed_date' => $request->dewormed_date,
                'spayed_neutered' => $request->has('spayed_neutered'),
                'spayed_date' => $request->spayed_date,
                'description' => $request->description,
                'updated_at' => now(),
            ]
        );

        AuditLogger::log('Updated Health Record', $pet->pet_name, "Updated health details for pet ID {$id}.");

        return back()->with('success', "Health records updated for {$pet->pet_name}.");
    }
}