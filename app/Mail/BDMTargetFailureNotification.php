<?php

namespace App\Mail;

use App\Models\BDM;
use App\Models\BDMTarget;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class BDMTargetFailureNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $bdm;
    public $target;

    public function __construct(BDM $bdm, BDMTarget $target)
    {
        $this->bdm = $bdm;
        $this->target = $target;
    }

    public function build()
    {
        return $this->subject('Monthly Target Not Achieved')
                    ->view('emails.bdm-target-failure');
    }
}
