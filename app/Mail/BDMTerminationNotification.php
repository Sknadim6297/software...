<?php

namespace App\Mail;

use App\Models\BDM;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class BDMTerminationNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $bdm;
    public $reason;

    public function __construct(BDM $bdm, string $reason)
    {
        $this->bdm = $bdm;
        $this->reason = $reason;
    }

    public function build()
    {
        return $this->subject('Account Termination Notice')
                    ->view('emails.bdm-termination');
    }
}
