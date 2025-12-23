<?php

namespace App\Mail;

use App\Models\ServiceRenewal;
use App\Models\Invoice;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RenewalReminderMail extends Mailable
{
    use Queueable, SerializesModels;

    public $serviceRenewal;
    public $invoice;

    /**
     * Create a new message instance.
     */
    public function __construct(ServiceRenewal $serviceRenewal, Invoice $invoice)
    {
        $this->serviceRenewal = $serviceRenewal;
        $this->invoice = $invoice;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Service Renewal Reminder - ' . $this->serviceRenewal->service_type)
                    ->view('emails.renewal-reminder');
    }
}
