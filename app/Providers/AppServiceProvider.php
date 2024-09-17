<?php

namespace App\Providers;

use App\Policies\NotificationPolicy;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        Gate::policy(DatabaseNotification::class, NotificationPolicy::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
