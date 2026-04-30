<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\PetsController;
use App\Http\Controllers\Admin\ApprovalsController;
use App\Http\Controllers\Admin\ResidentsController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\AnnouncementsController;
use App\Http\Controllers\Admin\AuditlogController;
use App\Http\Controllers\Admin\NotificationController;

Route::view('/', 'welcome');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

Route::middleware(['auth', 'verified', 'admin'])->group(function () {
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('residents', [ResidentsController::class, 'index'])->name('residents');
    Route::delete('residents/{id}', [ResidentsController::class, 'destroy'])->name('residents.destroy');

    Route::get('pets', [PetsController::class, 'index'])->name('pets');
    Route::delete('pets/{id}', [PetsController::class, 'destroy'])->name('pets.destroy');
    Route::post('pets/{id}/health', [PetsController::class, 'updateHealth'])->name('pets.health.update');
    Route::post('pets/deceased/{reportId}/confirm', [PetsController::class, 'confirmDeceased'])->name('pets.deceased.confirm');
    Route::post('pets/deceased/{reportId}/reject', [PetsController::class, 'rejectDeceased'])->name('pets.deceased.reject');

    Route::get('approvals', [ApprovalsController::class, 'index'])->name('approvals');
    Route::post('approvals/resident/{id}/approve', [ApprovalsController::class, 'approveResident'])->name('approvals.resident.approve');
    Route::post('approvals/resident/{id}/reject', [ApprovalsController::class, 'rejectResident'])->name('approvals.resident.reject');
    Route::post('approvals/pet/{id}/approve', [ApprovalsController::class, 'approvePet'])->name('approvals.pet.approve');
    Route::post('approvals/pet/{id}/reject', [ApprovalsController::class, 'rejectPet'])->name('approvals.pet.reject');

    Route::get('announcements', [AnnouncementsController::class, 'index'])->name('announcements');
    Route::post('announcements', [AnnouncementsController::class, 'store'])->name('announcements.store');
    Route::put('announcements/{id}', [AnnouncementsController::class, 'update'])->name('announcements.update');
    Route::delete('announcements/{id}', [AnnouncementsController::class, 'destroy'])->name('announcements.destroy');

    Route::get('auditlog', [AuditlogController::class, 'index'])->name('auditlog');

    Route::get('notifications/pending-count', [NotificationController::class, 'pendingCount'])->name('notifications.pending');

    Route::view('settings', 'pages.coming-soon')->name('settings');
});

require __DIR__.'/auth.php';