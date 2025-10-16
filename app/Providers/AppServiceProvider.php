<?php

namespace App\Providers;

use App\Repositories\Contratcs\AuthorRepositoryInterface;
use App\Repositories\Contratcs\CategoryRepositoryInterface;
use App\Repositories\Contratcs\DashboardRepositoryInterface;
use App\Repositories\Contratcs\LandingPageRepositoryInterface;
use App\Repositories\Contratcs\MetaDataCategoryRepositoryInterface;
use App\Repositories\Contratcs\MetaDataRepositoryInterface;
use App\Repositories\Contratcs\NoteRepositoryInterface;
use App\Repositories\Contratcs\StudyProgramRepositoryInterface;
use App\Repositories\Contratcs\UserRepositoryInterface;
use App\Repositories\Eloquent\AuthorRepository;
use App\Repositories\Eloquent\CategoryRepository;
use App\Repositories\Eloquent\DashboardRepository;
use App\Repositories\Eloquent\LandingPageRepository;
use App\Repositories\Eloquent\MetaDataCategoryRepository;
use App\Repositories\Eloquent\MetaDataRepository;
use App\Repositories\Eloquent\NoteRepository;
use App\Repositories\Eloquent\StudyProgramRepository;
use App\Repositories\Eloquent\UserRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(CategoryRepositoryInterface::class, CategoryRepository::class);
        $this->app->bind(StudyProgramRepositoryInterface::class, StudyProgramRepository::class);
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(AuthorRepositoryInterface::class, AuthorRepository::class);
        $this->app->bind(MetaDataRepositoryInterface::class, MetaDataRepository::class);
        $this->app->bind(MetaDataCategoryRepositoryInterface::class, MetaDataCategoryRepository::class);
        $this->app->bind(DashboardRepositoryInterface::class, DashboardRepository::class);
        $this->app->bind(NoteRepositoryInterface::class, NoteRepository::class);
        $this->app->bind(LandingPageRepositoryInterface::class, LandingPageRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Model::unguard();
    }
}
