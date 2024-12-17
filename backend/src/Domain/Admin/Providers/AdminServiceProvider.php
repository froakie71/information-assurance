<?php

namespace Domain\Admin\Providers;

use Domain\Admin\Repositories\AdminTodoRepository;
use Domain\Admin\Repositories\AdminTodoRepositoryInterface;
use Domain\Admin\Repositories\AdminUserRepository;
use Domain\Admin\Repositories\AdminUserRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class AdminServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Bind AdminUserRepository
        $this->app->bind(AdminUserRepositoryInterface::class, function ($app) {
            return new AdminUserRepository();
        });

        // Bind AdminTodoRepository
        $this->app->bind(AdminTodoRepositoryInterface::class, function ($app) {
            return new AdminTodoRepository();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
} 