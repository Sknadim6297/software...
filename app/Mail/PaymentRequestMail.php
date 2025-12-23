<?php

namespace App\Mail;

use App\Models\Project;
use App\Models\Invoice;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PaymentRequestMail extends Mailable
{
    use Queueable, SerializesModels;

    public $project;
    public $invoice;
    public $installment;

    /**
     * Create a new message instance.
     */
    public function __construct(Project $project, Invoice $invoice, array $installment)
    {
        $this->project = $project;
        $this->invoice = $invoice;
        $this->installment = $installment;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Payment Request - ' . $this->project->project_name . ' (' . $this->installment['type'] . ' Installment)')
                    ->view('emails.payment-request');
    }
}
