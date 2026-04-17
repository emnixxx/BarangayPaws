<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome');

Route::view('dashboard', 'pages.dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');
Route::view('residents', 'pages.residents')->name('residents.index');
Route::view('pets', 'pages.pets')->name('pets.index');
Route::view('approvals', 'pages.approvals')->name('approvals.index');
Route::view('announcements', 'pages.announcements')->name('announcements.index');
Route::view('audit-logs', 'pages.coming-soon')->name('audit-logs.index');
Route::view('settings', 'pages.coming-soon')->name('settings.index');
require __DIR__.'/auth.php';
