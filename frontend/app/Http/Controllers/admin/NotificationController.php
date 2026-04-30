<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pet;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class NotificationController extends Controller
{
    public function pendingCount(): JsonResponse
    {
        $pendingResidents = User::where('role', 'resident')
            ->where('status', 'pending')
            ->count();

        $pendingPets = Pet::where('status', 'pending')->count();

        return response()->json([
            'residents' => $pendingResidents,
            'pets' => $pendingPets,
            'total' => $pendingResidents + $pendingPets,
        ]);
    }
}