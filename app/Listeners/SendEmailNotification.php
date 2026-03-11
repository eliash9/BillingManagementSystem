<?php

namespace App\Listeners;

use App\Events\InvoiceCreated;
use App\Events\InvoicePaid;
use App\Events\ServiceSuspended;
use App\Events\InvoiceReminderNeeded;
use App\Events\InvoiceOverdueReminderNeeded;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

use Illuminate\Support\Facades\Mail;
use App\Mail\InvoiceNotificationMail;
use App\Mail\InvoiceReminderMail;
use App\Mail\InvoiceOverdueMail;

class SendEmailNotification implements ShouldQueue
{
    /**
     * Handle the event.
     */
    public function handle(object $event): void
    {
        if ($event instanceof InvoiceCreated) {
            $customer = $event->invoice->customer;
            if ($customer && $customer->email) {
                Mail::to($customer->email)->send(new InvoiceNotificationMail($event->invoice));
            }
        } elseif ($event instanceof InvoicePaid) {
            // Can add InvoicePaidMail later if needed
        } elseif ($event instanceof ServiceSuspended) {
            // Can add ServiceSuspendedMail later if needed
        } elseif ($event instanceof InvoiceReminderNeeded) {
            $customer = $event->invoice->customer;
            if ($customer && $customer->email) {
                Mail::to($customer->email)->send(new InvoiceReminderMail($event->invoice));
            }
        } elseif ($event instanceof InvoiceOverdueReminderNeeded) {
            $customer = $event->invoice->customer;
            if ($customer && $customer->email) {
                Mail::to($customer->email)->send(new InvoiceOverdueMail($event->invoice));
            }
        }
    }
}
