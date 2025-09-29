<?php

use App\Http\Controllers\DashboardController;
use App\Livewire\Author;
use App\Livewire\Profile;
use App\Livewire\StudyProgram;
use App\Livewire\RepositoryList;
use App\Livewire\RepositoryForm;
use App\Livewire\RepositoryDetail;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('index');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/study-programs', StudyProgram::class)->name('study-program.index');
    Route::get('/authors', Author::class)->name('author.index');
    Route::get('/repositories', RepositoryList::class)->name('repository.index');
    Route::get('/repositories/create', RepositoryForm::class)->name('repository.create');
    Route::get('/repositories/{repository:slug}/show', RepositoryDetail::class)->name('repository.show');
    Route::get('/repositories/{repository_slug}/edit', RepositoryForm::class)->name('repository.edit');

    Route::get('/profile', Profile::class)->name('profile.index');
});


require __DIR__ . '/auth.php';
