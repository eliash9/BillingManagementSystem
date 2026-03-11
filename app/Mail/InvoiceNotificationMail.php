<?php

namespace App\Mail;

use App\Models\Invoice;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class InvoiceNotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $invoice;
    public $customer;
    public $portalUrl;

    public function __construct(Invoice $invoice)
    {
        $this->invoice = $invoice;
        $this->customer = $invoice->customer;
        $this->portalUrl = route('widget.invoice', [
            'token' => $this->customer->widget_token,
            'invoice' => $this->invoice->id
        ]);
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Tagihan Baru #' . $this->invoice->invoice_number,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.invoice_notification',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
