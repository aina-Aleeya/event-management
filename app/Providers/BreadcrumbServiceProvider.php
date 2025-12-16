<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class BreadcrumbServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Store event ID in session when viewing participants
        // This helps breadcrumbs when viewing individual participant details
        if (request()->route() && request()->route()->getName() === 'admin.participants') {
            $eventId = request()->route()->parameter('event');
            if ($eventId) {
                session(['last_event_id' => $eventId]);
            }
        }
    }
}