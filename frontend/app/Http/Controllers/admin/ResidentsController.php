<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\AuditLogger;
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
    $pendingResidents = User::where('role', 'resident')
        ->where('status', 'pending')
        ->with('pets')
        ->orderBy('date_registered', 'desc')
        ->get();

    $residents = User::where('role', 'resident')
        ->where('status', '!=', 'pending')
        ->with('pets')
        ->orderBy('date_registered', 'desc')
        ->get();

    return view('pages.residents', compact('residents', 'pendingResidents'));
}

    /**
     * Delete a resident.
     */
    public function destroy($id): RedirectResponse
    {
        $resident = User::where('role', 'resident')->findOrFail($id);
        $name = $resident->user_name;
        $resident->delete();

        AuditLogger::log('Deleted Resident', $name, "Deleted resident ID {$id} and their data.");

        return back()->with('success', "Resident {$name} deleted.");
    }
}