<?php

namespace App\Http\Controllers;

use App\Models\Proposal;
use App\Models\Lead;
use App\Models\Contract;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;

class ProposalController extends Controller
{
    /**
     * Display a listing of proposals
     */
    public function index()
    {
        $proposals = Proposal::with('lead')
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        
        return view('proposals.index', compact('proposals'));
    }

    /**
     * Show the form for creating a new proposal - Step 1: Select lead type
     */
    public function create()
    {
        return view('proposals.select-lead-type');
    }

    /**
     * Show eligible customers based on lead type
     */
    public function selectCustomer(Request $request)
    {
        $leadType = $request->input('lead_type');
        
        // Validate lead type
        if (!in_array($leadType, ['incoming', 'outgoing'])) {
            return back()->with('error', 'Please select a valid lead type (Incoming or Outgoing).');
        }
        
        // Get eligible leads (only those with interested status from the selected lead type)
        $eligibleLeads = Lead::where('type', $leadType)
            ->where('status', 'interested')
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('proposals.select-customer', compact('eligibleLeads', 'leadType'));
    }

    /**
     * Show the form for creating a proposal with selected customer
     */
    public function createWithCustomer(Request $request)
    {
        $leadId = $request->input('lead_id');
        $leadType = $request->input('lead_type');
        
        $lead = Lead::findOrFail($leadId);
        
        // Check if this is a Social Media Marketing project
        if ($lead->project_type === 'social_media_marketing') {
            return view('proposals.social-media-form', compact('lead', 'leadType'));
        }
        
        // Project types for other projects
        $projectTypes = [
            'Website Development',
            'Software Development',
            'Mobile App Development',
            'SEO Services',
            'Digital Marketing',
            'UI/UX Design',
            'E-commerce Solution',
            'Custom Software',
            'Other'
        ];
        
        return view('proposals.create-form', compact('lead', 'leadType', 'projectTypes'));
    }

    /**
     * Store a newly created proposal
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'lead_id' => 'required|exists:leads,id',
            'lead_type' => 'required|in:incoming,outgoing',
            'project_type' => 'required|string|max:255',
            'project_description' => 'nullable|string',
            'proposal_content' => 'required|string',
            'proposed_amount' => 'required|numeric|min:0',
            'currency' => 'required|string|max:10',
            'estimated_days' => 'nullable|integer|min:1',
            'deliverables' => 'nullable|string',
            'payment_terms' => 'nullable|string',
        ]);

        $lead = Lead::findOrFail($request->lead_id);
        
        $validated['customer_name'] = $lead->customer_name;
        $validated['customer_email'] = $lead->email;
        $validated['customer_phone'] = $lead->phone_number;
        $validated['status'] = 'draft';
        
        $proposal = Proposal::create($validated);
        
        return redirect()->route('proposals.show', $proposal->id)
            ->with('success', 'Proposal created successfully! You can now review and send it.');
    }

    /**
     * Store a social media marketing proposal
     */
    public function storeSocialMedia(Request $request)
    {
        $validated = $request->validate([
            'lead_id' => 'required|exists:leads,id',
            'lead_type' => 'required|in:incoming,outgoing',
            'project_type' => 'required|string',
            'company_name' => 'required|string|max:255',
            'monthly_charges' => 'required|numeric|min:1000',
            'platforms' => 'required|array|min:1',
            'platforms.*' => 'string',
            'target_audience' => 'required|string',
            'posters_per_month' => 'required|integer|min:1',
            'reels_per_week' => 'required|integer|min:0',
            'includes_video_editing' => 'nullable|boolean',
            'services' => 'nullable|array',
            'services.*' => 'string',
            'payment_mode' => 'required|string',
            'gst_applicable' => 'required|string',
            'additional_notes' => 'nullable|string|max:1000'
        ]);

        $lead = Lead::findOrFail($request->lead_id);
        
        // Generate professional proposal content
        $proposalContent = $this->generateSocialMediaProposalContent($validated, $lead);
        
        // Create proposal record
        $proposal = Proposal::create([
            'lead_id' => $request->lead_id,
            'lead_type' => $request->lead_type,
            'customer_name' => $lead->customer_name,
            'customer_email' => $lead->email,
            'customer_phone' => $lead->phone_number,
            'project_type' => 'Social Media Marketing',
            'project_description' => "Social Media Marketing services for {$validated['company_name']}",
            'proposal_content' => $proposalContent,
            'proposed_amount' => $validated['monthly_charges'],
            'currency' => 'INR',
            'estimated_days' => 30, // Monthly service
            'deliverables' => $this->generateDeliverablesText($validated),
            'payment_terms' => $this->generatePaymentTerms($validated),
            'status' => 'draft',
            'metadata' => json_encode($validated) // Store all form data for future reference
        ]);
        
        return redirect()->route('proposals.show', $proposal->id)
            ->with('success', 'Social Media Marketing proposal created successfully! You can now review and send it.');
    }

    /**
     * Generate professional social media proposal content
     */
    private function generateSocialMediaProposalContent($data, $lead)
    {
        $platforms = implode(' & ', $data['platforms']);
        $services = isset($data['services']) ? $data['services'] : [];
        
        $content = "
# Social Media Marketing Proposal

## Scope of Work & Marketing Strategy
**For: {$data['company_name']}**  
**Submitted by: Konnectix Technologies Pvt. Ltd.**

---

## Platform Management
- Page/Profile optimization on all platforms
- Daily posting and engaging caption writing  
- Hashtag research and implementation
- Profile highlights and story management

## Lead Generation
- Use of Meta Lead Forms and Landing Pages
- Capturing inquiries from {$data['target_audience']}
- Weekly lead reports with follow-up tracking support

## Paid Ad Management
Strategic ad campaigns for:
- Lead Generation (targeting {$data['target_audience']})
- Page Likes & Followers Growth
- A/B Testing of creatives & audience targeting  
- Real-time ad monitoring and optimization for ROI

**Platforms Covered: {$platforms}**

## Content Creation & Posting
- {$data['posters_per_month']} Posters per Month (Static/Carousel/Infographic based on marketing objective)
- {$data['reels_per_week']} Reels per Week (Product-focused, testimonial, behind-the-scenes, etc.)
" . (isset($data['includes_video_editing']) && $data['includes_video_editing'] ? "- Video Editing Support: Any video content shared by your team will be professionally edited and optimized for social media.\n" : "") . "

We propose a complete social media marketing solution to enhance {$data['company_name']}'s digital presence, drive quality leads, and increase brand awareness across {$platforms}.

## Deliverables Summary
| Deliverables | Details |
|--------------|---------|
| Posters per month | {$data['posters_per_month']} per month |
| Reels per Week | " . ($data['reels_per_week'] * 4) . "+ per month |
| Ad Creative Designs | Included |
| Video Editing | " . (isset($data['includes_video_editing']) && $data['includes_video_editing'] ? 'Included (Client video)' : 'Not included') . " |
| Lead Generation Setup & Monitoring | Included |
| Page Management & Strategy | Included |

## Monthly Charges
**Total Monthly Fee: ₹" . number_format($data['monthly_charges']) . "/-**

## Payment Terms
- Payment Mode: " . str_replace('_', ' / ', strtoupper($data['payment_mode'])) . "
- GST: " . str_replace('_', ' ', $data['gst_applicable']) . "
- Advance Payment: One month in advance to initiate work

## Growth Monitoring
- Monthly performance report (Reach, Engagement, Leads, Followers)
- Strategy refinement based on insights

## Important Notes
- Meta Ad budget (Facebook/Instagram Ads) is to be provided separately by the client
- Ads will be run through client's business manager/ad account for transparency  
- All designs and edited content will be shared for approval before posting

" . (isset($data['additional_notes']) && $data['additional_notes'] ? "## Additional Notes\n{$data['additional_notes']}\n\n" : "") . "

---

**Let's Elevate Your Digital Presence!**

For queries or approval, feel free to contact us:
- Phone: +91 9123354003
- Email: sales.konnectixtech@gmail.com  
- Website: www.konnectixtech.com

We look forward to helping {$data['company_name']} achieve digital marketing success!
        ";
        
        return trim($content);
    }

    /**
     * Generate deliverables text
     */
    private function generateDeliverablesText($data)
    {
        $deliverables = [
            "{$data['posters_per_month']} Posters per month",
            ($data['reels_per_week'] * 4) . "+ Reels per month",
            "Ad Creative Designs",
            "Lead Generation Setup & Monitoring",
            "Page Management & Strategy"
        ];
        
        if (isset($data['includes_video_editing']) && $data['includes_video_editing']) {
            $deliverables[] = "Video Editing Support";
        }
        
        return implode("\n", $deliverables);
    }

    /**
     * Generate payment terms text  
     */
    private function generatePaymentTerms($data)
    {
        return "Monthly Fee: ₹" . number_format($data['monthly_charges']) . "/-\n" .
               "Payment Mode: " . str_replace('_', ' / ', strtoupper($data['payment_mode'])) . "\n" .
               "GST: " . str_replace('_', ' ', $data['gst_applicable']) . "\n" .
               "Advance Payment: One month in advance to initiate work";
    }

    /**
     * Display the specified proposal
     */
    public function show(Proposal $proposal)
    {
        $proposal->load('lead', 'contract');
        return view('proposals.show', compact('proposal'));
    }

    /**
     * Show the form for editing the specified proposal
     */
    public function edit(Proposal $proposal)
    {
        // Project types
        $projectTypes = [
            'Website Development',
            'Software Development',
            'Mobile App Development',
            'SEO Services',
            'Digital Marketing',
            'UI/UX Design',
            'E-commerce Solution',
            'Custom Software',
            'Other'
        ];
        
        return view('proposals.edit', compact('proposal', 'projectTypes'));
    }

    /**
     * Update the specified proposal
     */
    public function update(Request $request, Proposal $proposal)
    {
        $validated = $request->validate([
            'project_type' => 'required|string|max:255',
            'project_description' => 'nullable|string',
            'proposal_content' => 'required|string',
            'proposed_amount' => 'required|numeric|min:0',
            'currency' => 'required|string|max:10',
            'estimated_days' => 'nullable|integer|min:1',
            'deliverables' => 'nullable|string',
            'payment_terms' => 'nullable|string',
        ]);
        
        $proposal->update($validated);
        
        return redirect()->route('proposals.show', $proposal->id)
            ->with('success', 'Proposal updated successfully!');
    }

    /**
     * Send proposal to customer and admin
     */
    public function send(Proposal $proposal)
    {
        if ($proposal->status !== 'draft' && $proposal->status !== 'sent') {
            return back()->with('error', 'This proposal has already been responded to.');
        }

        try {
            DB::beginTransaction();
            
            $proposal->update([
                'status' => 'sent',
                'sent_at' => now()
            ]);
            
            // Send email to customer
            Mail::send('emails.proposal-sent-customer', ['proposal' => $proposal], function($message) use ($proposal) {
                $message->to($proposal->customer_email)
                    ->subject('Proposal for ' . $proposal->project_type . ' - Konnectix');
            });
            
            // Send email to admin
            Mail::send('emails.proposal-sent-admin', ['proposal' => $proposal], function($message) use ($proposal) {
                $message->to('bdm.konnectixtech@gmail.com')
                    ->subject('New Proposal Sent to ' . $proposal->customer_name);
            });
            
            DB::commit();
            
            return redirect()->route('proposals.show', $proposal->id)
                ->with('success', 'Proposal sent successfully to customer and admin!');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to send proposal: ' . $e->getMessage());
        }
    }

    /**
     * Mark proposal as viewed (tracking)
     */
    public function markViewed(Proposal $proposal)
    {
        if ($proposal->status === 'sent' && !$proposal->viewed_at) {
            $proposal->update([
                'status' => 'viewed',
                'viewed_at' => now()
            ]);
        }
        
        return response()->json(['success' => true]);
    }

    /**
     * Accept proposal and generate contract & invoice
     */
    public function accept(Request $request, Proposal $proposal)
    {
        $validated = $request->validate([
            'final_amount' => 'required|numeric|min:0',
            'start_date' => 'required|date',
            'expected_completion_date' => 'required|date|after:start_date',
            'deliverables' => 'nullable|string',
            'milestones' => 'nullable|string',
            'payment_schedule' => 'nullable|string',
            'terms_and_conditions' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();
            
            // Update proposal status
            $proposal->update([
                'status' => 'accepted',
                'responded_at' => now()
            ]);
            
            // 1. Generate Contract automatically
            $contract = Contract::create([
                'proposal_id' => $proposal->id,
                'contract_number' => Contract::generateContractNumber(),
                'customer_name' => $proposal->customer_name,
                'customer_email' => $proposal->customer_email,
                'customer_phone' => $proposal->customer_phone,
                'contract_content' => $this->generateContractContent($proposal, $validated),
                'project_type' => $proposal->project_type,
                'final_amount' => $validated['final_amount'],
                'currency' => $proposal->currency,
                'start_date' => $validated['start_date'],
                'expected_completion_date' => $validated['expected_completion_date'],
                'deliverables' => $validated['deliverables'] ?? $proposal->deliverables,
                'milestones' => $validated['milestones'] ?? null,
                'payment_schedule' => $validated['payment_schedule'] ?? $proposal->payment_terms,
                'terms_and_conditions' => $validated['terms_and_conditions'] ?? null,
                'status' => 'active',
                'sent_to_customer_at' => now(),
                'sent_to_admin_at' => now(),
            ]);
            
            // 2. Add customer to Customer Management Portal (if not exists)
            // Customer identification is based on mobile number (primary unique identifier)
            $customer = Customer::where('number', $proposal->customer_phone)->first();
            
            if (!$customer) {
                $customer = Customer::create([
                    'name' => $proposal->customer_name,
                    'email' => $proposal->customer_email,
                    'number' => $proposal->customer_phone,
                    'project_type' => $proposal->project_type,
                    'payment_terms' => $proposal->payment_terms ?? 'Net 30',
                    'added_date' => now(),
                ]);
            }
            
            // 3. Generate Invoice automatically
            $invoice = Invoice::create([
                'customer_id' => $customer->id,
                'proposal_id' => $proposal->id,
                'contract_id' => $contract->id,
                'invoice_number' => Invoice::generateInvoiceNumber('regular'),
                'invoice_type' => 'regular',
                'invoice_date' => now(),
                'due_date' => now()->addDays(30),
                'subtotal' => $validated['final_amount'],
                'discount_amount' => 0,
                'tax_total' => $validated['final_amount'] * 0.18, // 18% GST
                'grand_total' => $validated['final_amount'] * 1.18,
                'payment_status' => 'pending',
                'notes' => 'Invoice generated from accepted proposal #' . $proposal->id,
            ]);
            
            // Add invoice item
            $taxAmount = $validated['final_amount'] * 0.18;
            InvoiceItem::create([
                'invoice_id' => $invoice->id,
                'product_description' => $proposal->project_type . ' - ' . $proposal->project_description,
                'quantity' => 1,
                'rate' => $validated['final_amount'],
                'cgst_percentage' => 9,
                'sgst_percentage' => 9,
                'igst_percentage' => 0,
                'tax_amount' => $taxAmount,
                'total_amount' => $validated['final_amount'] + $taxAmount,
            ]);
            
            // 4. Send emails to customer and admin
            Mail::send('emails.proposal-accepted-customer', [
                'proposal' => $proposal,
                'contract' => $contract,
                'invoice' => $invoice
            ], function($message) use ($proposal) {
                $message->to($proposal->customer_email)
                    ->subject('Proposal Accepted - Contract & Invoice - Konnectix');
            });
            
            Mail::send('emails.proposal-accepted-admin', [
                'proposal' => $proposal,
                'contract' => $contract,
                'invoice' => $invoice
            ], function($message) use ($proposal) {
                $message->to('bdm.konnectixtech@gmail.com')
                    ->subject('Proposal Accepted by ' . $proposal->customer_name);
            });
            
            DB::commit();
            
            return redirect()->route('contracts.show', $contract->id)
                ->with('success', 'Proposal accepted! Contract and Invoice have been generated and sent automatically.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to accept proposal: ' . $e->getMessage());
        }
    }

    /**
     * Reject proposal with reason
     */
    public function reject(Request $request, Proposal $proposal)
    {
        $validated = $request->validate([
            'rejection_reason' => 'required|string|max:1000'
        ]);
        
        $proposal->update([
            'status' => 'rejected',
            'rejection_reason' => $validated['rejection_reason'],
            'responded_at' => now()
        ]);
        
        return redirect()->route('proposals.show', $proposal->id)
            ->with('success', 'Proposal marked as rejected. The data has been saved for future reference.');
    }

    /**
     * Remove the specified proposal
     */
    public function destroy(Proposal $proposal)
    {
        if ($proposal->status === 'accepted') {
            return back()->with('error', 'Cannot delete an accepted proposal that has generated contracts.');
        }
        
        $proposal->delete();
        
        return redirect()->route('proposals.index')
            ->with('success', 'Proposal deleted successfully.');
    }

    /**
     * Generate contract content from proposal
     */
    private function generateContractContent($proposal, $validated)
    {
        return "
        CONTRACT FOR {$proposal->project_type}
        
        This Contract is entered into on " . date('Y-m-d') . " between:
        
        Party A: Konnectix Technologies
        Party B: {$proposal->customer_name}
        Email: {$proposal->customer_email}
        Phone: {$proposal->customer_phone}
        
        PROJECT DETAILS:
        Type: {$proposal->project_type}
        Description: {$proposal->project_description}
        
        FINANCIAL TERMS:
        Total Amount: {$proposal->currency} " . number_format($validated['final_amount'], 2) . "
        
        TIMELINE:
        Start Date: {$validated['start_date']}
        Expected Completion: {$validated['expected_completion_date']}
        
        DELIVERABLES:
        " . ($validated['deliverables'] ?? $proposal->deliverables) . "
        
        PAYMENT SCHEDULE:
        " . ($validated['payment_schedule'] ?? $proposal->payment_terms) . "
        
        TERMS AND CONDITIONS:
        " . ($validated['terms_and_conditions'] ?? 'Standard terms apply') . "
        
        This contract is binding upon acceptance.
        ";
    }
}
