<?php

namespace App\Providers;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Events\QueryExecuted;
use App\Models\Category;
use App\Models\MetaData;
use App\Models\Coordinator;
use App\Models\StudyProgram;
use App\Observers\CategoryObserver;
use App\Observers\MetaDataObserver;
use App\Observers\CoordinatorObserver;
use App\Observers\StudyProgramObserver;
use Illuminate\Support\ServiceProvider;
use App\Repositories\Eloquent\NoteRepository;
use App\Repositories\Eloquent\UserRepository;
use App\Repositories\Eloquent\AuthorRepository;
use App\Repositories\Eloquent\ActivityRepository;
use App\Repositories\Eloquent\CategoryRepository;
use App\Repositories\Eloquent\MetaDataRepository;
use App\Repositories\Eloquent\DashboardRepository;
use App\Repositories\Eloquent\CoordinatorRepository;
use App\Repositories\Eloquent\LandingPageRepository;
use App\Repositories\Eloquent\StudyProgramRepository;
use App\Repositories\Contratcs\NoteRepositoryInterface;
use App\Repositories\Contratcs\UserRepositoryInterface;
use App\Repositories\Contratcs\AuthorRepositoryInterface;
use App\Repositories\Eloquent\MetaDataCategoryRepository;
use App\Repositories\Contratcs\ActivityRepositoryInterface;
use App\Repositories\Contratcs\CategoryRepositoryInterface;
use App\Repositories\Contratcs\MetaDataRepositoryInterface;
use App\Repositories\Contratcs\DashboardRepositoryInterface;
use App\Repositories\Contratcs\CoordinatorRepositoryInterface;
use App\Repositories\Contratcs\LandingPageRepositoryInterface;
use App\Repositories\Contratcs\StudyProgramRepositoryInterface;
use App\Repositories\Contratcs\MetaDataCategoryRepositoryInterface;

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
        $this->app->bind(ActivityRepositoryInterface::class, ActivityRepository::class);
        $this->app->bind(CoordinatorRepositoryInterface::class, CoordinatorRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // DB::listen(function (QueryExecuted $query) {
        //     logger(json_encode($query->sql, JSON_PRETTY_PRINT));
        // });

        Category::observe(CategoryObserver::class);
        StudyProgram::observe(StudyProgramObserver::class);
        Coordinator::observe(CoordinatorObserver::class);
        MetaData::observe(MetaDataObserver::class);
    }
}
