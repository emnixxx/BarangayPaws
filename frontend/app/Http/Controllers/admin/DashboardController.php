<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display the dashboard with analytics.
     */
    public function index(): View
    {
        // TODO: Fetch analytics data
        // $totalPets = Pet::count();
        // $pendingApprovals = User::where('status', 'pending')->count();
        // $vaccinated = PetHealthRecord::where('vaccinated', true)->count();
        // $deceased = Pet::where('status', 'deceased')->count();

        return view('pages.dashboard');
    }
}