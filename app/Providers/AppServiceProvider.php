<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Share settings globally
        try {
            if (\Illuminate\Support\Facades\Schema::hasTable('settings')) {
                $globalSettings = \App\Models\Setting::pluck('value', 'key')->toArray();
                \Illuminate\Support\Facades\View::share('globalSettings', $globalSettings);
            }
        } catch (\Exception $e) {
            // Table might not exist yet during migration
        }

        \Illuminate\Support\Facades\Event::listen(
            \App\Events\InvoiceCreated::class,
            [\App\Listeners\SendEmailNotification::class, 'handle']
        );
        \Illuminate\Support\Facades\Event::listen(
            \App\Events\InvoiceCreated::class,
            [\App\Listeners\SendWhatsAppNotification::class, 'handle']
        );

        \Illuminate\Support\Facades\Event::listen(
            \App\Events\InvoicePaid::class,
            [\App\Listeners\SendEmailNotification::class, 'handle']
        );
        \Illuminate\Support\Facades\Event::listen(
            \App\Events\InvoicePaid::class,
            [\App\Listeners\SendWhatsAppNotification::class, 'handle']
        );

        \Illuminate\Support\Facades\Event::listen(
            \App\Events\ServiceSuspended::class,
            [\App\Listeners\SendEmailNotification::class, 'handle']
        );

        \Illuminate\Support\Facades\Event::listen(
            \App\Events\InvoiceReminderNeeded::class,
            [\App\Listeners\SendEmailNotification::class, 'handle']
        );
        \Illuminate\Support\Facades\Event::listen(
            \App\Events\InvoiceOverdueReminderNeeded::class,
            [\App\Listeners\SendEmailNotification::class, 'handle']
        );
    }
}
