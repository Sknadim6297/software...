<?php

namespace App\Mail;

use App\Models\BDMLeaveApplication;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class BDMLeaveStatusNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $leaveApplication;

    public function __construct(BDMLeaveApplication $leaveApplication)
    {
        $this->leaveApplication = $leaveApplication;
    }

    public function build()
    {
        $status = ucfirst($this->leaveApplication->status);
        return $this->subject("Leave Application {$status}")
                    ->view('emails.bdm-leave-status');
    }
}
