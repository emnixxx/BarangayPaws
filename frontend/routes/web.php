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

// Admin profile actions (panel-driven)
Route::middleware(['auth'])->group(function () {
    Route::patch('account/profile', [\App\Http\Controllers\Admin\AccountController::class, 'updateProfile'])->name('account.profile');
    Route::patch('account/password', [\App\Http\Controllers\Admin\AccountController::class, 'updatePassword'])->name('account.password');
    Route::delete('account', [\App\Http\Controllers\Admin\AccountController::class, 'destroy'])->name('account.destroy');
});

// Admin-only pages
Route::middleware(['auth', 'verified', 'admin'])->group(function () {
    Route::get('dashboard', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');

    // Residents
    Route::get('residents', [ResidentsController::class, 'index'])->name('residents');
    Route::delete('residents/{id}', [ResidentsController::class, 'destroy'])->name('residents.destroy');

    Route::get('pets', [PetsController::class, 'index'])->name('pets');
    Route::delete('pets/{id}', [PetsController::class, 'destroy'])->name('pets.destroy');
    Route::patch('pets/{id}/health', [PetsController::class, 'updateHealth'])->name('pets.health.update');
    Route::post('pets/deceased/{reportId}/confirm', [PetsController::class, 'confirmDeceased'])->name('pets.deceased.confirm');
    Route::post('pets/deceased/{reportId}/reject', [PetsController::class, 'rejectDeceased'])->name('pets.deceased.reject');

    // Approvals
    Route::get('approvals', [ApprovalsController::class, 'index'])->name('approvals');
    Route::get('api/notifications/count', [ApprovalsController::class, 'getPendingCount'])->name('notifications.count');
    Route::get('api/notifications/items', [ApprovalsController::class, 'getPendingItems'])->name('notifications.items');
    Route::post('approvals/resident/{id}/approve', [ApprovalsController::class, 'approveResident'])->name('approvals.resident.approve');
    Route::post('approvals/resident/{id}/reject', [ApprovalsController::class, 'rejectResident'])->name('approvals.resident.reject');
    Route::post('approvals/pet/{id}/approve', [ApprovalsController::class, 'approvePet'])->name('approvals.pet.approve');
    Route::post('approvals/pet/{id}/reject', [ApprovalsController::class, 'rejectPet'])->name('approvals.pet.reject');

    Route::get('announcements', [\App\Http\Controllers\Admin\AnnouncementsController::class, 'index'])->name('announcements');
    Route::post('announcements', [\App\Http\Controllers\Admin\AnnouncementsController::class, 'store'])->name('announcements.store');
    Route::get('/auditlog', [\App\Http\Controllers\Admin\AuditlogController::class, 'index'])->name('auditlog');
    Route::view('settings', 'pages.coming-soon')->name('settings');
});

require __DIR__.'/auth.php';