<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PitchController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Fortify\Features;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canRegister' => Features::enabled(Features::registration()),
    ]);
})->name('home');

Route::get('dashboard', DashboardController::class)
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('pitches', [PitchController::class, 'index'])->name('pitches.index');
    Route::get('pitches/create', [PitchController::class, 'create'])->name('pitches.create');
    Route::post('pitches', [PitchController::class, 'store'])->name('pitches.store');
    Route::get('pitches/{pitch}/edit', [PitchController::class, 'edit'])->name('pitches.edit');
    Route::patch('pitches/{pitch}', [PitchController::class, 'update'])->name('pitches.update');
});

require __DIR__.'/settings.php';
