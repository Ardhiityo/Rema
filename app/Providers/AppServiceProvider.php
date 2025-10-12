<?php

namespace App\Providers;

use App\Repositories\Contratcs\AuthorRepositoryInterface;
use App\Repositories\Contratcs\CategoryRepositoryInterface;
use App\Repositories\Contratcs\StudyProgramRepositoryInterface;
use App\Repositories\Contratcs\UserRepositoryInterface;
use App\Repositories\Eloquent\AuthorRepository;
use App\Repositories\Eloquent\CategoryRepository;
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
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Model::unguard();
    }
}
