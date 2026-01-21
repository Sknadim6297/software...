<?php

namespace App\Mail;

use App\Models\Proposal;
use App\Models\Contract;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ProposalAcceptedCustomer extends Mailable
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

        return $this->subject('Proposal Accepted - Contract Generated')
                    ->view('emails.proposal-accepted-customer')
                    ->with([
                        'proposalTitle' => $this->proposal->project_type,
                        'contractNumber' => $this->contract->contract_number,
                        'totalAmount' => $this->contract->total_amount,
                        'startDate' => $this->contract->start_date,
                        'endDate' => $this->contract->expected_completion_date,
                        'isSocialMedia' => $isSocialMedia,
                        'contractUrl' => route('proposals.contract', $this->proposal->id)
                    ]);
    }
}
