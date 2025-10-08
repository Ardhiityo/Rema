<?php

use App\Livewire\Author;
use App\Livewire\Profile;
use App\Livewire\Category;
use App\Livewire\StudyProgram;
use App\Livewire\RepositoryForm;
use App\Livewire\RepositoryList;
use App\Livewire\RepositoryDetail;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LandingPageController;
use App\Http\Middleware\ValidateAuthorMiddleware;

Route::controller(LandingPageController::class)->group(function () {
    Route::get('/', 'index')->name('landing_page.index');
    Route::get('/repositories/{meta_data_id}/{category_id}/read', 'read')->name('repository.read');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/categories', Category::class)->name('category.index');
    Route::get('/study-programs', StudyProgram::class)->name('study-program.index');
    Route::get('/authors', Author::class)->name('author.index');

    Route::middleware(ValidateAuthorMiddleware::class)->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])
            ->name('dashboard');
        Route::get('/repositories', RepositoryList::class)
            ->name('repository.index');
        Route::get('/repositories/authors', RepositoryList::class)
            ->name('repository.author.index');
        Route::get('/repositories/create', RepositoryForm::class)
            ->name('repository.create');
        Route::get('/repositories/{meta_data:slug}/show', RepositoryDetail::class)
            ->name('repository.show');
    });

    Route::get('/repositories/{meta_data_slug}/edit', RepositoryForm::class)->name('repository.edit');
    Route::get('/profile', Profile::class)->name('profile.index');
});


require __DIR__ . '/auth.php';
