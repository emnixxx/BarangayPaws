<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\PetsController;
use App\Http\Controllers\Admin\ApprovalsController;
use App\Http\Controllers\Admin\ResidentsController;

Route::view('/', 'welcome');

// Profile (any authenticated user, admin or resident)
Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

// Admin-only pages
Route::middleware(['auth', 'verified', 'admin'])->group(function () {
    Route::view('dashboard', 'pages.dashboard')->name('dashboard');

    // Residents
    Route::get('residents', [ResidentsController::class, 'index'])->name('residents');
    Route::delete('residents/{id}', [ResidentsController::class, 'destroy'])->name('residents.destroy');

    Route::get('pets', [PetsController::class, 'index'])->name('pets');
    Route::delete('pets/{id}', [PetsController::class, 'destroy'])->name('pets.destroy');
    Route::post('pets/deceased/{reportId}/confirm', [PetsController::class, 'confirmDeceased'])->name('pets.deceased.confirm');
    Route::post('pets/deceased/{reportId}/reject', [PetsController::class, 'rejectDeceased'])->name('pets.deceased.reject');

    // Approvals
    Route::get('approvals', [ApprovalsController::class, 'index'])->name('approvals');
    Route::post('approvals/resident/{id}/approve', [ApprovalsController::class, 'approveResident'])->name('approvals.resident.approve');
    Route::post('approvals/resident/{id}/reject', [ApprovalsController::class, 'rejectResident'])->name('approvals.resident.reject');
    Route::post('approvals/pet/{id}/approve', [ApprovalsController::class, 'approvePet'])->name('approvals.pet.approve');
    Route::post('approvals/pet/{id}/reject', [ApprovalsController::class, 'rejectPet'])->name('approvals.pet.reject');

    Route::view('announcements', 'pages.announcements')->name('announcements');
    Route::get('/auditlog', function () {
        return view('pages.auditlog');
    })->name('auditlog');
    Route::view('settings', 'pages.coming-soon')->name('settings');
});

require __DIR__.'/auth.php';