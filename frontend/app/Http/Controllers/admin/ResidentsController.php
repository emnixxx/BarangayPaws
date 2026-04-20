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
    public function destroy($id): RedirectResponse
    {
        $resident = User::where('role', 'resident')->findOrFail($id);
        $name = $resident->user_name;
        $resident->delete();

        return back()->with('success', "Resident {$name} deleted.");
    }
}