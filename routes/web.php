<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DiscoverController;
use App\Http\Controllers\PitchController;
use App\Http\Controllers\PitchInterestController;
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

Route::get('discover', [DiscoverController::class, '__invoke'])
    ->name('discover');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('pitches', [PitchController::class, 'index'])->name('pitches.index');
    Route::get('pitches/create', [PitchController::class, 'create'])->name('pitches.create');
    Route::post('pitches', [PitchController::class, 'store'])->name('pitches.store');
    Route::get('pitches/interests', [PitchInterestController::class, 'index'])->name('pitches.interests.index');
    Route::post('pitches/{pitch}/interest', [PitchInterestController::class, 'store'])->name('pitches.interest.store');
    Route::patch('pitches/interests/{interest}', [PitchInterestController::class, 'update'])->name('pitches.interests.update');
    Route::get('pitches/{pitch}/edit', [PitchController::class, 'edit'])->name('pitches.edit');
    Route::patch('pitches/{pitch}', [PitchController::class, 'update'])->name('pitches.update');
});

Route::get('pitches/{pitch:slug}', [PitchController::class, 'show'])
    ->name('pitches.show');

require __DIR__.'/settings.php';
