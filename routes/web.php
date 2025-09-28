<?php

use App\Http\Controllers\DashboardController;
use App\Livewire\Author;
use App\Livewire\StudyProgram;
use App\Livewire\RepositoryList;
use App\Livewire\RepositoryForm;
use App\Livewire\RepositoryDetail;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/study-programs', StudyProgram::class)->name('study-program.index');
    Route::get('/authors', Author::class)->name('author.index');
    Route::get('/repositories', RepositoryList::class)->name('repository.index');
    Route::get('/repositories/create', RepositoryForm::class)->name('repository.create');
    Route::get('/repositories/{repository:slug}/show', RepositoryDetail::class)->name('repository.show');
    Route::get('/repositories/{repository_slug}/edit', RepositoryForm::class)->name('repository.edit');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
