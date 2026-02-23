<?php

namespace App\Listeners;

use App\Events\InvoiceCreated;
use App\Events\InvoicePaid;
use App\Events\ServiceSuspended;
use App\Events\InvoiceReminderNeeded;
use App\Events\InvoiceOverdueReminderNeeded;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendEmailNotification implements ShouldQueue
{
    /**
     * Handle the event.
     */
    public function handle(object $event): void
    {
        // $event->invoice or $event->service will be available depending on the event class.
        // E.g., sending an actual notification via Laravel's Notification Facade
        // \Illuminate\Support\Facades\Log::info('Email Sent for event: ' . get_class($event));

        if ($event instanceof InvoiceCreated) {
            // Mail::to($event->invoice->service->customer->user)->send(new InvoiceCreatedMail($event->invoice));
        } elseif ($event instanceof InvoicePaid) {
            // Mail::to($event->invoice->service->customer->user)->send(new InvoicePaidMail($event->invoice));
        } elseif ($event instanceof ServiceSuspended) {
            // Mail::to($event->service->customer->user)->send(new ServiceSuspendedMail($event->service));
        } elseif ($event instanceof InvoiceReminderNeeded) {
            // Mail::to($event->invoice->service->customer->user)->send(new InvoiceReminderMail($event->invoice));
        } elseif ($event instanceof InvoiceOverdueReminderNeeded) {
            // Mail::to($event->invoice->service->customer->user)->send(new InvoiceOverdueMail($event->invoice));
        }
    }
}
