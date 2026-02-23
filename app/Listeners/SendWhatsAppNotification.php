<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendWhatsAppNotification implements ShouldQueue
{
    /**
     * Handle the event.
     */
    public function handle(object $event): void
    {
        // WhatsApp API Integration Stub
        // \Illuminate\Support\Facades\Log::info('WhatsApp Sent for event: ' . get_class($event));
    }
}
