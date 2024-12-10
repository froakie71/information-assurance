<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Domain\Todo\TodoRepository;
use App\Infrastructure\Persistence\Eloquent\Todo\EloquentTodoRepository;
use App\Domain\User\UserRepository;
use App\Infrastructure\Persistence\Eloquent\User\EloquentUserRepository;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(TodoRepository::class, EloquentTodoRepository::class);
        $this->app->bind(UserRepository::class, EloquentUserRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
