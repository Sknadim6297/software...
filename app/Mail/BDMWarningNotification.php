<?php

namespace App\Mail;

use App\Models\BDM;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class BDMWarningNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $bdm;
    public $warningCount;
    public $targetPeriod;

    public function __construct(BDM $bdm, int $warningCount, string $targetPeriod)
    {
        $this->bdm = $bdm;
        $this->warningCount = $warningCount;
        $this->targetPeriod = $targetPeriod;
    }

    public function build()
    {
        return $this->subject('Performance Warning - Target Not Met')
                    ->view('emails.bdm-warning');
    }
}
