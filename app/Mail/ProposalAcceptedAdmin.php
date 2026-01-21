<?php

namespace App\Mail;

use App\Models\Proposal;
use App\Models\Contract;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ProposalAcceptedAdmin extends Mailable
{
    use Queueable, SerializesModels;

    public $proposal;
    public $contract;

    /**
     * Create a new message instance.
     */
    public function __construct(Proposal $proposal, Contract $contract)
    {
        $this->proposal = $proposal;
        $this->contract = $contract;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        $socialMediaTypes = ['social_media_marketing', 'youtube_marketing', 'graphic_designing', 'reels_design'];
        $isSocialMedia = in_array($this->proposal->project_type, $socialMediaTypes);

        return $this->subject('Proposal Accepted - Action Required')
                    ->view('emails.proposal-accepted-admin')
                    ->with([
                        'proposalTitle' => $this->proposal->project_type,
                        'customerName' => $this->proposal->customer_name,
                        'customerEmail' => $this->proposal->customer_email,
                        'contractNumber' => $this->contract->contract_number,
                        'totalAmount' => $this->contract->total_amount,
                        'startDate' => $this->contract->start_date,
                        'endDate' => $this->contract->expected_completion_date,
                        'isSocialMedia' => $isSocialMedia,
                        'proposalUrl' => route('proposals.show', $this->proposal->id)
                    ]);
    }
}
