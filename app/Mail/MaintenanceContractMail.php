<?php

namespace App\Mail;

use App\Models\MaintenanceContract;
use App\Models\Invoice;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class MaintenanceContractMail extends Mailable
{
    use Queueable, SerializesModels;

    public $maintenanceContract;
    public $invoice;

    /**
     * Create a new message instance.
     */
    public function __construct(MaintenanceContract $maintenanceContract, Invoice $invoice = null)
    {
        $this->maintenanceContract = $maintenanceContract;
        $this->invoice = $invoice;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Maintenance Contract - ' . $this->maintenanceContract->project->project_name)
                    ->view('emails.maintenance-contract');
    }
}
