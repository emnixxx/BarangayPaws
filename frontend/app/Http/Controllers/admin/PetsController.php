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

        return back()->with('success', "Deceased report confirmed for {$report->pet->pet_name}.");
    }

    /**
     * Reject a deceased report.
     */
    public function rejectDeceased($reportId): RedirectResponse
    {
        $report = DeceasedReport::findOrFail($reportId);
        $report->update(['status' => 'rejected']);

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

        return back()->with('success', "Pet {$name} deleted.");
    }
}