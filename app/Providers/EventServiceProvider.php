<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    protected $listen = [
            'Illuminate\Auth\Events\Login' => [
                \App\Listeners\UpdateUserRoleAfterLogin::class,
            ],
        ];
        
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
