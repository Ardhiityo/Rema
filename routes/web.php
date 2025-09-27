<?php

use App\Http\Controllers\AuthorController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RepositoryController;
use App\Http\Controllers\StudyProgramController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::controller(StudyProgramController::class)->group(function () {
        Route::get('/study-programs', 'index')->name('study-program.index');
    });

    Route::controller(AuthorController::class)->group(function () {
        Route::get('/authors', 'index')->name('author.index');
    });

    Route::controller(RepositoryController::class)->group(function () {
        Route::get('/repositories', 'index')->name('repository.index');
        Route::get('/repositories/create', 'create')->name('repository.create');
    });
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
