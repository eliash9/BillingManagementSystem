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
