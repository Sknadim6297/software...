<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class LateWarningMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(public User $user, public int $lateCount)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Attendance Warning - Late Marks Notice',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.late-warning',
            with: [
                'user' => $this->user,
                'lateCount' => $this->lateCount,
                'warningMessage' => $this->getWarningMessage(),
            ],
        );
    }

    private function getWarningMessage(): string
    {
        return match ($this->lateCount) {
            3 => 'You have received 3 late marks this month. One more late will automatically mark you as Half-Day.',
            default => sprintf('You have received %d late marks this month.', $this->lateCount),
        };
    }
}
