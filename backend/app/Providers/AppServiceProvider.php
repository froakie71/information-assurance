<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Domain\Todo\TodoRepository;
use App\Infrastructure\Persistence\Eloquent\Todo\EloquentTodoRepository;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(TodoRepository::class, EloquentTodoRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
