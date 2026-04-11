<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LandingPageController;
use App\Http\Controllers\ReportController;
use App\Livewire\Activity\Activity;
use App\Livewire\Activity\ActivityDetail;
use App\Livewire\Author\Author;
use App\Livewire\Category\Category;
use App\Livewire\Coordinator\Coordinator;
use App\Livewire\Profile;
use App\Livewire\Report\Report;
use App\Livewire\Repository\Form\RepositoryForm;
use App\Livewire\Repository\RepositoryDetail;
use App\Livewire\Repository\RepositoryList;
use App\Livewire\StudyProgram\StudyProgram;
use Illuminate\Support\Facades\Route;

Route::middleware(['throttle:50,1'])->group(function () {
    Route::get('/reports/activities/{year}', [ReportController::class, 'activity'])
        ->name('reports.activities.download');
    Route::get('/reports/repositories/{nidn}/{year}/{includes}', [ReportController::class, 'repository'])
        ->name('reports.repositories.download');

    Route::controller(LandingPageController::class)->group(function () {
        Route::get('/', 'index')->name('landing_page.index');
        Route::get('/repositories/{category_slug}/{meta_data_slug}/read', 'read')->name('repository.read');
    });

    Route::get('/privacy-policy', fn () => view('privacy-policy'))->name('privacy-policy');
    Route::get('/terms-of-service', fn () => view('terms-of-service'))->name('terms-of-service');
});

Route::middleware(['throttle:40,1'])->group(function () {
    Route::middleware(['auth', 'verified'])->group(function () {
        Route::middleware(['role:admin'])->group(function () {
            Route::get('/categories', Category::class)->name('category.index');
            Route::get('/study-programs', StudyProgram::class)->name('study-program.index');
            Route::get('/authors', Author::class)->name('author.index');
            Route::get('/activities', Activity::class)->name('activity.index');
            Route::get('/activities/{category_slug}/{meta_data_slug}', ActivityDetail::class)->name('activity.show');
            Route::get('/coordinators', Coordinator::class)->name('coordinator.index');
        });

        Route::middleware(['role:admin|leader'])->group(function () {
            Route::get('/reports', Report::class)->name('report.index');
        });

        Route::middleware(['role:author'])->group(function () {
            Route::get('/repositories/authors', RepositoryList::class)
                ->name('repository.author.index');
        });

        Route::get('/dashboard', [DashboardController::class, 'index'])
            ->name('dashboard');
        Route::get('/repositories', RepositoryList::class)
            ->name('repository.index');
        Route::get('/repositories/create', RepositoryForm::class)
            ->name('repository.create');
        Route::get('/repositories/{meta_data:slug}/show', RepositoryDetail::class)
            ->name('repository.show');

        Route::get('/repositories/{meta_data_slug}/edit', RepositoryForm::class)->name('repository.edit');
        Route::get('/profile', Profile::class)->name('profile.index');
    });
});

require __DIR__.'/auth.php';
