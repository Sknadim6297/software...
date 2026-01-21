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
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Dompdf\Dompdf;
use Dompdf\Options;

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
        
        // Check if this is a Social Media Marketing or related project
        $socialMediaTypes = ['social_media_marketing', 'youtube_marketing', 'graphic_designing', 'reels_design'];
        if (in_array($lead->project_type, $socialMediaTypes)) {
            return view('proposals.social-media-form', compact('lead', 'leadType'));
        }
        
        // Check if this is an App/Website Development project
        $appWebsiteTypes = [
            'website_development', 
            'web_development',  // Added this
            'app_development', 
            'mobile_app',  // Added this
            'ecommerce_development', 
            'web_application'
        ];
        if (in_array($lead->project_type, $appWebsiteTypes)) {
            return view('proposals.app-website-form', compact('lead', 'leadType'));
        }
        
        // Check if this is an ERP/Software Development project
        $erpSoftwareTypes = ['software_development', 'ui_ux_design'];
        if (in_array($lead->project_type, $erpSoftwareTypes)) {
            return view('proposals.erp-software-form', compact('lead', 'leadType'));
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
            'target_audience' => 'nullable|string',
            'posters_per_month' => 'required|integer|min:1',
            'reels_per_week' => 'required|integer|min:0',
            'includes_video_editing' => 'nullable',
            'scope_of_work' => 'nullable|string',
            'marketing_strategy' => 'nullable|string',
            'payment_mode' => 'required|string',
            'gst_applicable' => 'required|string',
            'additional_notes' => 'nullable|string|max:1000'
        ]);

        $lead = Lead::findOrFail($request->lead_id);
        
        // Generate professional proposal content
        $proposalContent = $this->generateSocialMediaProposalContent($validated, $lead);
        
        // Map project type to display name
        $projectTypeMap = [
            'social_media_marketing' => 'Social Media Marketing',
            'youtube_marketing' => 'YouTube Marketing',
            'graphic_designing' => 'Graphic / Poster Designing',
            'reels_design' => 'Reels Design'
        ];
        
        $projectTypeName = $projectTypeMap[$lead->project_type] ?? 'Social Media Marketing';
        
        // Create proposal record
        $proposal = Proposal::create([
            'lead_id' => $request->lead_id,
            'lead_type' => $request->lead_type,
            'customer_name' => $lead->customer_name,
            'customer_email' => $lead->email,
            'customer_phone' => $lead->phone_number,
            'project_type' => $projectTypeName,
            'project_description' => "{$projectTypeName} services for {$validated['company_name']}",
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
     * Store an ERP/Software Development proposal
     */
    public function storeErpSoftware(Request $request)
    {
        $validated = $request->validate([
            'lead_id' => 'required|exists:leads,id',
            'lead_type' => 'required|in:incoming,outgoing',
            'project_type' => 'required|string',
            'project_title' => 'required|string|max:500',
            'project_description' => 'nullable|string',
            'objectives' => 'nullable|string',
            'scope_of_work' => 'nullable|string',
            'total_cost' => 'required|numeric|min:1000',
            'gst_percentage' => 'required|numeric|min:0|max:100',
            'payment_descriptions' => 'required|array|min:1',
            'payment_descriptions.*' => 'required|string|max:255',
            'payment_percentages' => 'required|array|min:1',
            'payment_percentages.*' => 'required|numeric|min:0|max:100',
            'timeline_weeks' => 'required|integer|min:1',
            'features' => 'nullable|array',
            'features.*' => 'string',
            'deliverables' => 'nullable|array',
            'deliverables.*' => 'string',
            'support_months' => 'nullable|integer|min:0',
            'architecture' => 'nullable|string',
            'technology_stack' => 'nullable|string',
            'additional_notes' => 'nullable|string|max:2000'
        ]);

        $lead = Lead::findOrFail($request->lead_id);
        
        // Generate professional ERP/Software proposal content
        $proposalContent = $this->generateErpSoftwareProposalContent($validated, $lead);
        
        // Map project type to display name
        $projectTypeMap = [
            'software_development' => 'Software Development',
            'web_development' => 'Web Development',
            'mobile_app' => 'Mobile App Development',
            'ui_ux_design' => 'UI/UX Design'
        ];
        
        $projectTypeName = $projectTypeMap[$lead->project_type] ?? 'Software Development';
        
        // Calculate final amount with GST
        $gstAmount = ($validated['total_cost'] * $validated['gst_percentage']) / 100;
        $finalAmount = $validated['total_cost'] + $gstAmount;
        
        // Create proposal record
        $proposal = Proposal::create([
            'lead_id' => $request->lead_id,
            'lead_type' => $request->lead_type,
            'customer_name' => $lead->customer_name,
            'customer_email' => $lead->email,
            'customer_phone' => $lead->phone_number,
            'project_type' => $projectTypeName,
            'project_description' => $validated['project_title'],
            'proposal_content' => $proposalContent,
            'proposed_amount' => $finalAmount,
            'currency' => 'INR',
            'estimated_days' => $validated['timeline_weeks'] * 7,
            'deliverables' => $this->generateErpDeliverablesText($validated),
            'payment_terms' => $this->generateErpPaymentTerms($validated),
            'status' => 'draft',
            'metadata' => json_encode($validated) // Store all form data for future reference
        ]);
        
        return redirect()->route('proposals.show', $proposal->id)
            ->with('success', 'ERP/Software proposal created successfully! You can now review and send it.');
    }

    /**
     * Store an app/website development proposal
     */
    public function storeAppWebsite(Request $request)
    {
        $validated = $request->validate([
            'lead_id' => 'required|exists:leads,id',
            'lead_type' => 'required|in:incoming,outgoing',
            'project_type' => 'required|string',
            'project_title' => 'required|string|max:255',
            'project_description' => 'required|string',
            'objectives' => 'required|string',
            'scope_of_work' => 'required|string',
            'total_cost' => 'required|numeric|min:1000',
            'gst_percentage' => 'required|numeric|min:0|max:100',
            'timeline_weeks' => 'required|integer|min:1',
            'support_months' => 'nullable|integer|min:0',
            'payment_descriptions' => 'required|array|min:1',
            'payment_descriptions.*' => 'required|string',
            'payment_percentages' => 'required|array|min:1',
            'payment_percentages.*' => 'required|numeric|min:0|max:100',
            'domain_provided_by' => 'nullable|string',
            'hosting_duration' => 'nullable|string',
            'client_responsibilities' => 'nullable|array',
            'client_responsibilities.*' => 'string',
            'additional_terms' => 'nullable|string'
        ]);

        $paymentTotal = array_sum($validated['payment_percentages']);
        if (abs($paymentTotal - 100) > 0.01) {
            return back()->withInput()->withErrors(['payment_percentages' => 'Payment percentages must total 100%.']);
        }

        $lead = Lead::findOrFail($request->lead_id);
        
        // Generate professional app/website agreement content
        $proposalContent = $this->generateAppWebsiteProposalContent($validated, $lead);
        
        // Map project type to display name
        $projectTypeMap = [
            'website_development' => 'Website Development',
            'app_development' => 'Mobile App Development',
            'ecommerce_development' => 'E-Commerce Development',
            'web_application' => 'Web Application Development'
        ];
        
        $projectTypeName = $projectTypeMap[$lead->project_type] ?? $validated['project_title'];
        
        // Calculate final amount with GST
        $gstAmount = ($validated['total_cost'] * $validated['gst_percentage']) / 100;
        $finalAmount = $validated['total_cost'] + $gstAmount;
        
        // Create proposal record
        $proposal = Proposal::create([
            'lead_id' => $request->lead_id,
            'lead_type' => $request->lead_type,
            'customer_name' => $lead->customer_name,
            'customer_email' => $lead->email,
            'customer_phone' => $lead->phone_number,
            'project_type' => $projectTypeName,
            'project_description' => $validated['project_title'],
            'proposal_content' => $proposalContent,
            'proposed_amount' => $finalAmount,
            'currency' => 'INR',
            'estimated_days' => $validated['timeline_weeks'] * 7,
            'deliverables' => $this->generateAppWebsiteDeliverables($validated),
            'payment_terms' => $this->generateAppWebsitePaymentTerms($validated),
            'status' => 'draft',
            'metadata' => json_encode($validated) // Store all form data for future reference
        ]);
        
        return redirect()->route('proposals.show', $proposal->id)
            ->with('success', 'App/Website agreement created successfully! You can now review and send it.');
    }

    /**
     * Generate professional ERP/Software proposal content
     */
    private function generateErpSoftwareProposalContent($data, $lead)
    {
        $gstAmount = ($data['total_cost'] * $data['gst_percentage']) / 100;
        $finalAmount = $data['total_cost'] + $gstAmount;
        
        $features = isset($data['features']) && is_array($data['features']) ? $data['features'] : [];
        $deliverables = isset($data['deliverables']) && is_array($data['deliverables']) ? $data['deliverables'] : [];
        
        $content = "
# {$data['project_title']}

**Proposal for: {$lead->customer_name}**  
**Submitted by: Konnectix Technologies Pvt. Ltd.**

---

## 1. Executive Summary

We are pleased to submit this comprehensive proposal for the development and implementation of a custom software solution for **{$lead->customer_name}**. This proposal outlines our approach, technical specifications, deliverables, timeline, and investment required to bring your vision to reality.

" . ($data['project_description'] ? "## 2. Project Overview\n\n" . $data['project_description'] . "\n\n" : "") . "

## 3. Key Features & Modules
";

        // Add features dynamically
        if (!empty($features)) {
            foreach ($features as $index => $feature) {
                $featureNum = $index + 1;
                $content .= "\n### 3.{$featureNum} " . trim(explode("\n", $feature)[0]) . "\n\n";
                
                // Add the rest of the feature content
                $featureLines = explode("\n", trim($feature));
                if (count($featureLines) > 1) {
                    array_shift($featureLines); // Remove first line (already used as heading)
                    $content .= implode("\n", $featureLines) . "\n\n";
                }
            }
        } else {
            $content .= "
* Comprehensive system architecture tailored to your business needs
* User-friendly interface with role-based access control
* Real-time data processing and reporting
* Automated workflow management
* Integration capabilities with existing systems

";
        }

        $content .= "

## 4. Technical Specifications

**Architecture:** " . ($data['architecture'] ?? 'Cloud-based / On-premise (as per requirement)') . "  
**Technology Stack:** " . ($data['technology_stack'] ?? 'PHP/Laravel, MySQL, React/HTML, CSS, Bootstrap 4.0') . "  
**Security:** SSL encryption, secure authentication, regular backups  
**Compatibility:** Desktop, Tablet, Mobile responsive

## 5. Timeline

**Total Duration:** Approximately {$data['timeline_weeks']} weeks

The project will be executed in phases to ensure quality and timely delivery.

## 6. Pricing & Payment Terms

**Total Project Cost:** ₹" . number_format($data['total_cost']) . "/- + {$data['gst_percentage']}% GST  
**Final Amount:** ₹" . number_format($finalAmount) . "/-

**Payment Schedule:**
";
        if (isset($data['payment_descriptions']) && isset($data['payment_percentages'])) {
            foreach ($data['payment_descriptions'] as $index => $description) {
                $percentage = $data['payment_percentages'][$index] ?? 0;
                $amountForStage = ($finalAmount * $percentage) / 100;
                $content .= "* **{$percentage}%** {$description} - ₹" . number_format($amountForStage) . "/-\n";
            }
        }

        $content .= "\n## 7. Deliverables
    ";

        if (!empty($deliverables)) {
            foreach ($deliverables as $deliverable) {
                $content .= "* " . trim($deliverable) . "\n";
            }
        } else {
            $content .= "* Fully functional system as per the agreed scope
* Admin and user manuals
* User training materials
* Source code documentation
";
        }

        if (isset($data['support_months']) && $data['support_months'] > 0) {
            $content .= "* {$data['support_months']} months of free technical support post-deployment\n";
        }

        $content .= "

## 8. Why Konnectix Technologies Pvt. Ltd.?

* **Proven Expertise:** Years of experience in ERP design and implementation
* **User-Centric Approach:** Strong focus on user experience and intuitive design
* **Data Security:** Industry-standard security protocols and data protection
* **Dedicated Support:** Post-deployment support team available for assistance
* **Custom Solutions:** Tailored solutions aligned with your exact business processes
* **Quality Assurance:** Rigorous testing and quality control procedures

" . (isset($data['additional_notes']) && $data['additional_notes'] ? "## 9. Additional Information\n\n{$data['additional_notes']}\n\n" : "") . "

## " . (isset($data['additional_notes']) && $data['additional_notes'] ? "10" : "9") . ". Acceptance

We believe our proposed solution will empower **{$lead->customer_name}** with improved operational efficiency, better control, and higher productivity. We are ready to initiate the project upon your approval.

---

**For Konnectix Technologies Pvt. Ltd.**

Ishita Banerjee  
Director & Owner  
Email: info@konnectixtech.com  
Phone: 9123354003
        ";
        
        return trim($content);
    }

    /**
     * Generate ERP deliverables text
     */
    private function generateErpDeliverablesText($data)
    {
        $deliverables = [];
        
        if (isset($data['deliverables']) && is_array($data['deliverables'])) {
            $deliverables = $data['deliverables'];
        } else {
            $deliverables = [
                "Fully functional system as per the agreed scope",
                "Admin and user manuals",
                "User training materials"
            ];
        }
        
        if (isset($data['support_months']) && $data['support_months'] > 0) {
            $deliverables[] = "{$data['support_months']} months of free technical support";
        }
        
        return implode("\n", $deliverables);
    }

    /**
     * Generate ERP payment terms text  
     */
    private function generateErpPaymentTerms($data)
    {
        $gstAmount = ($data['total_cost'] * $data['gst_percentage']) / 100;
        $finalAmount = $data['total_cost'] + $gstAmount;
        
        $paymentSchedule = "Total Cost: ₹" . number_format($data['total_cost']) . "/-\n" .
               "GST ({$data['gst_percentage']}%): ₹" . number_format($gstAmount) . "/-\n" .
               "Final Amount: ₹" . number_format($finalAmount) . "/-\n\n" .
               "Payment Schedule:\n";
        
        // Add dynamic payment terms
        if (isset($data['payment_descriptions']) && isset($data['payment_percentages'])) {
            foreach ($data['payment_descriptions'] as $index => $description) {
                $percentage = $data['payment_percentages'][$index] ?? 0;
                $paymentSchedule .= "- {$percentage}% {$description}\n";
            }
        }
        
        return $paymentSchedule;
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

## Proposed Price
**Total Proposed Price: ₹" . number_format($data['monthly_charges']) . "/-**

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
        return "Proposed Price: ₹" . number_format($data['monthly_charges']) . "/-\n" .
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
        // Check if this is a Social Media Marketing or related proposal
        $socialMediaTypes = ['Social Media Marketing', 'YouTube Marketing', 'Graphic / Poster Designing', 'Reels Design'];
        if (in_array($proposal->project_type, $socialMediaTypes)) {
            // Load lead relationship
            $proposal->load('lead');
            
            // Get lead information
            $lead = $proposal->lead ?? Lead::find($proposal->lead_id);
            
            // If lead not found, use proposal customer data as fallback
            if (!$lead) {
                $lead = (object)[
                    'id' => $proposal->lead_id,
                    'customer_name' => $proposal->customer_name,
                    'email' => $proposal->customer_email,
                    'phone_number' => $proposal->customer_phone,
                    'type' => $proposal->lead_type
                ];
            }
            
            $leadType = $proposal->lead_type;
            
            // Decode metadata to populate form
            $metadata = json_decode($proposal->metadata, true) ?? [];
            
            return view('proposals.social-media-edit', compact('proposal', 'lead', 'leadType', 'metadata'));
        }
        
        // Project types for other proposals
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
        // Check if this is a social media marketing or related proposal
        $socialMediaRequestTypes = ['social_media_marketing', 'youtube_marketing', 'graphic_designing', 'reels_design'];
        $socialMediaProposalTypes = ['Social Media Marketing', 'YouTube Marketing', 'Graphic / Poster Designing', 'Reels Design'];
        
        if (in_array($request->project_type, $socialMediaRequestTypes) || in_array($proposal->project_type, $socialMediaProposalTypes)) {
            $validated = $request->validate([
                'lead_id' => 'required|exists:leads,id',
                'lead_type' => 'required|in:incoming,outgoing',
                'project_type' => 'required|string',
                'company_name' => 'required|string|max:255',
                'monthly_charges' => 'required|numeric|min:1000',
                'platforms' => 'required|array|min:1',
                'platforms.*' => 'string',
                'target_audience' => 'nullable|string',
                'posters_per_month' => 'required|integer|min:1',
                'reels_per_week' => 'required|integer|min:0',
                'includes_video_editing' => 'nullable|boolean',
                'services' => 'nullable|array',
                'services.*' => 'string',
                'payment_mode' => 'required|string',
                'gst_applicable' => 'required|string',
                'additional_notes' => 'nullable|string|max:1000'
            ]);

            // Convert checkbox value to boolean
            $validated['includes_video_editing'] = $request->has('includes_video_editing') ? true : false;

            $lead = Lead::findOrFail($request->lead_id);
            
            // Map project type to display name
            $projectTypeMap = [
                'social_media_marketing' => 'Social Media Marketing',
                'youtube_marketing' => 'YouTube Marketing',
                'graphic_designing' => 'Graphic / Poster Designing',
                'reels_design' => 'Reels Design'
            ];
            
            $projectTypeName = $projectTypeMap[$request->project_type] ?? $proposal->project_type;
            
            // Regenerate professional proposal content
            $proposalContent = $this->generateSocialMediaProposalContent($validated, $lead);
            
            // Update proposal record
            $proposal->update([
                'customer_name' => $lead->customer_name,
                'customer_email' => $lead->email,
                'customer_phone' => $lead->phone_number,
                'project_type' => $projectTypeName,
                'project_description' => "{$projectTypeName} services for {$validated['company_name']}",
                'proposal_content' => $proposalContent,
                'proposed_amount' => $validated['monthly_charges'],
                'currency' => 'INR',
                'estimated_days' => 30,
                'deliverables' => $this->generateDeliverablesText($validated),
                'payment_terms' => $this->generatePaymentTerms($validated),
                'metadata' => json_encode($validated)
            ]);
            
            return redirect()->route('proposals.show', $proposal->id)
                ->with('success', 'Social Media Marketing proposal updated successfully!');
        }
        
        // Standard proposal update
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

            // Determine which PDF template to use based on project type
            $projectType = strtolower($proposal->project_type ?? '');
            $pdfView = 'emails.proposal-pdf'; // Default view
            
            // Check for app/website development projects
            $appWebsiteKeywords = ['website', 'app', 'ecommerce', 'web application', 'mobile app'];
            foreach ($appWebsiteKeywords as $keyword) {
                if (strpos($projectType, $keyword) !== false) {
                    $pdfView = 'proposals.pdf.app-website-agreement';
                    break;
                }
            }
            
            // Check for social media projects
            $socialMediaKeywords = ['social media', 'youtube', 'graphic', 'poster', 'reels'];
            foreach ($socialMediaKeywords as $keyword) {
                if (strpos($projectType, $keyword) !== false) {
                    $pdfView = 'proposals.pdf.social-media-agreement';
                    break;
                }
            }
            
            // Check for ERP/Software projects
            $erpKeywords = ['software', 'erp', 'ui/ux', 'design'];
            foreach ($erpKeywords as $keyword) {
                if (strpos($projectType, $keyword) !== false) {
                    $pdfView = 'proposals.pdf.erp-software-agreement';
                    break;
                }
            }

            // Render proposal HTML for PDF
            $pdfHtml = view($pdfView, [
                'proposal' => $proposal,
                'contentHtml' => Str::markdown($proposal->proposal_content ?? '')
            ])->render();

            // Generate PDF attachment with optimized settings
            $options = new Options();
            $options->set('isRemoteEnabled', false); // Disable remote loading for security and performance
            $options->set('isHtml5ParserEnabled', true);
            $options->set('isFontSubsettingEnabled', true);
            $options->set('defaultFont', 'Arial');
            $options->setChroot(public_path()); // Allow local file access from public directory
            
            $dompdf = new Dompdf($options);
            $dompdf->loadHtml($pdfHtml);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();
            $pdfOutput = $dompdf->output();

            $proposal->update([
                'status' => 'sent',
                'sent_at' => now()
            ]);
            
            // Send email to customer with PDF
            Mail::send('emails.proposal-sent-customer', ['proposal' => $proposal], function($message) use ($proposal, $pdfOutput) {
                $message->to($proposal->customer_email)
                    ->subject('Proposal for ' . $proposal->project_type . ' - Konnectix')
                    ->attachData($pdfOutput, 'proposal-' . $proposal->id . '.pdf', ['mime' => 'application/pdf']);
            });
            
            // Send email to admin with PDF
            Mail::send('emails.proposal-sent-admin', ['proposal' => $proposal], function($message) use ($proposal, $pdfOutput) {
                $message->to('snfreelancingteam@gmail.com')
                    ->subject('New Proposal Sent to ' . $proposal->customer_name)
                    ->attachData($pdfOutput, 'proposal-' . $proposal->id . '.pdf', ['mime' => 'application/pdf']);
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
        $proposal->update(['viewed_at' => now()]);
        return redirect()->route('proposals.show', $proposal->id)
            ->with('success', 'Proposal marked as viewed.');
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
     * Accept proposal and generate contract
     */
    public function accept(Request $request, Proposal $proposal)
    {
        $validated = $request->validate([
            'final_amount' => 'required|numeric|min:0',
            'start_date' => 'required|date',
            'agreement_duration' => 'required|string|max:255',
            'milestones' => 'nullable|string|max:5000',
            'terms_and_conditions' => 'nullable|string|max:5000'
        ]);

        DB::beginTransaction();
        
        try {
            // Update proposal status
            $proposal->update([
                'status' => 'accepted',
                'responded_at' => now()
            ]);

            // Get metadata to extract payment schedule and other details
            $metadata = json_decode($proposal->metadata, true) ?? [];
            
            // Calculate final amount with GST if available
            $gstPercentage = $metadata['gst_percentage'] ?? 18;
            $baseAmount = $metadata['total_cost'] ?? $validated['final_amount'];
            $gstAmount = ($baseAmount * $gstPercentage) / 100;
            $finalAmountWithGst = $baseAmount + $gstAmount;
            
            // Build payment schedule from metadata
            $paymentScheduleText = $this->buildPaymentScheduleFromMetadata($metadata, $finalAmountWithGst);
            
            // Use generated payment schedule
            $finalPaymentSchedule = $paymentScheduleText;

            // Ensure we have a customer_id: try proposal->customer_id, else find by email, else create a Customer
            $customerId = $proposal->customer_id ?? null;
            if (empty($customerId)) {
                $possibleEmail = $proposal->customer_email ?? ($metadata['customer_email'] ?? null);
                $possibleName = $proposal->customer_name ?? ($proposal->lead->customer_name ?? ($metadata['customer_name'] ?? null));
                $possiblePhone = $proposal->customer_phone ?? ($metadata['customer_phone'] ?? null);

                if ($possibleEmail) {
                    $existing = Customer::where('email', $possibleEmail)->first();
                    if ($existing) {
                        $customerId = $existing->id;
                    }
                }

                if (empty($customerId)) {
                    // create a lightweight customer record
                    $newCustomer = Customer::create([
                        'customer_name' => $possibleName ?? 'Unknown',
                        'email' => $possibleEmail ?? null,
                        'number' => $possiblePhone ?? null,
                        'added_date' => now()->toDateString(),
                        'active' => true,
                    ]);

                    $customerId = $newCustomer->id;
                }

                // persist back to proposal for future reference
                $proposal->customer_id = $customerId;
                $proposal->save();
            }

            // Determine contract view based on project type
            $contractView = $this->getContractViewByProjectType($proposal->project_type);
            
            // Generate contract content using the appropriate template
            $contractContent = $this->renderContractFromTemplate($proposal, $contractView, [
                'final_amount' => $validated['final_amount'],
                'start_date' => $validated['start_date'],
                'agreement_duration' => $validated['agreement_duration'],
                'payment_schedule' => $finalPaymentSchedule,
                'terms_and_conditions' => $validated['terms_and_conditions'] ?? ''
            ]);

            // Create Contract record (include all DB-required fields)
            $contract = Contract::create([
                'proposal_id' => $proposal->id,
                'customer_id' => $customerId,
                'customer_name' => $proposal->customer_name ?? ($proposal->lead->customer_name ?? ($metadata['customer_name'] ?? null)),
                'customer_email' => $proposal->customer_email ?? ($proposal->lead->customer_email ?? ($metadata['customer_email'] ?? null)),
                'customer_phone' => $proposal->customer_phone ?? ($proposal->lead->customer_phone ?? ($metadata['customer_phone'] ?? null)),
                'contract_number' => $this->generateContractNumber(),
                'contract_content' => $contractContent,
                'project_type' => $proposal->project_type ?? ($metadata['project_title'] ?? 'Website Development'),
                'final_amount' => $validated['final_amount'],
                'total_amount' => $finalAmountWithGst ?? $validated['final_amount'],
                'currency' => $proposal->currency ?? ($metadata['currency'] ?? 'INR'),
                'start_date' => $validated['start_date'],
                'expected_completion_date' => $validated['agreement_duration'],
                'deliverables' => $metadata['deliverables'] ?? $proposal->deliverables ?? '',
                'payment_schedule' => $finalPaymentSchedule,
                'milestones' => $validated['milestones'] ?? '',
                'status' => 'active',
                'signed_at' => now()
            ]);

            // Create Invoice record
            $invoice = Invoice::create([
                'contract_id' => $contract->id,
                'customer_id' => $customerId,
                'invoice_number' => $this->generateInvoiceNumber(),
                'invoice_date' => now(),
                'due_date' => now()->addDays(30),
                'subtotal' => $baseAmount,
                'tax_percentage' => $gstPercentage,
                'tax_amount' => $gstAmount,
                'total_amount' => $finalAmountWithGst,
                'status' => 'pending',
                'notes' => $finalPaymentSchedule
            ]);

            // Add invoice items from metadata if available
            if (isset($metadata['payment_descriptions']) && isset($metadata['payment_percentages'])) {
                foreach ($metadata['payment_descriptions'] as $index => $description) {
                    $percentage = $metadata['payment_percentages'][$index] ?? 0;
                    $itemAmount = ($finalAmountWithGst * $percentage) / 100;
                    
                    InvoiceItem::create([
                        'invoice_id' => $invoice->id,
                        'description' => $description,
                        'quantity' => 1,
                        'unit_price' => $itemAmount,
                        'total_price' => $itemAmount
                    ]);
                }
            }

            // Send email notifications
            $this->sendAcceptanceEmails($proposal, $contract);

            DB::commit();
            
            return redirect()->route('proposals.show', $proposal->id)
                ->with('success', 'Proposal accepted successfully! Contract and invoice have been generated.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to accept proposal: ' . $e->getMessage());
        }
    }

    /**
     * Build payment schedule text from metadata
     */
    private function buildPaymentScheduleFromMetadata($metadata, $finalAmount)
    {
        $paymentSchedule = "";
        
        if (isset($metadata['payment_descriptions']) && isset($metadata['payment_percentages'])) {
            foreach ($metadata['payment_descriptions'] as $index => $description) {
                $percentage = $metadata['payment_percentages'][$index] ?? 0;
                $amount = ($finalAmount * $percentage) / 100;
                $paymentSchedule .= "{$percentage}% {$description} - ₹" . number_format($amount, 2) . "\n";
            }
        }
        
        return $paymentSchedule;
    }

    /**
     * Get contract view template based on project type
     */
    private function getContractViewByProjectType($projectType)
    {
        $projectType = strtolower($projectType ?? '');
        
        // Check for social media
        if (strpos($projectType, 'social media') !== false || strpos($projectType, 'social_media') !== false) {
            return 'proposals.contract.social-media-contract';
        }
        
        // Check for e-commerce
        if (strpos($projectType, 'e-commerce') !== false || strpos($projectType, 'ecommerce') !== false) {
            return 'proposals.contract.app-website-contract'; // You can create app-website-ecommerce-contract.blade.php later
        }
        
        // Check for mobile app
        if (strpos($projectType, 'mobile') !== false || strpos($projectType, 'app') !== false) {
            return 'proposals.contract.app-website-contract'; // Same template, just title changes
        }
        
        // Check for website
        if (strpos($projectType, 'website') !== false || strpos($projectType, 'web') !== false) {
            return 'proposals.contract.app-website-contract';
        }
        
        // Default to app-website contract
        return 'proposals.contract.app-website-contract';
    }

    /**
     * Render contract from Blade template
     */
    private function renderContractFromTemplate($proposal, $contractView, $additionalData)
    {
        return view($contractView, [
            'proposal' => $proposal,
            'contract_data' => $additionalData
        ])->render();
    }

    /**
     * Generate unique contract number
     */
    private function generateContractNumber()
    {
        $year = date('Y');
        $month = date('m');
        $lastContract = Contract::whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->orderBy('id', 'desc')
            ->first();
        
        $sequence = $lastContract ? (intval(substr($lastContract->contract_number, -4)) + 1) : 1;
        
        return 'CONT-' . $year . $month . '-' . str_pad($sequence, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Generate unique invoice number
     */
    private function generateInvoiceNumber()
    {
        $year = date('Y');
        $month = date('m');
        $lastInvoice = Invoice::whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->orderBy('id', 'desc')
            ->first();
        
        $sequence = $lastInvoice ? (intval(substr($lastInvoice->invoice_number, -4)) + 1) : 1;
        
        return 'INV-' . $year . $month . '-' . str_pad($sequence, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Send acceptance notification emails
     */
    private function sendAcceptanceEmails($proposal, $contract)
    {
        try {
            // Send to customer
            if ($proposal->customer_email) {
                Mail::to($proposal->customer_email)->send(
                    new \App\Mail\ProposalAcceptedCustomer($proposal, $contract)
                );
            }
            
            // Send to admin
            Mail::to('info@konnectixtech.com')->send(
                new \App\Mail\ProposalAcceptedAdmin($proposal, $contract)
            );
        } catch (\Exception $e) {
            Log::error('Failed to send acceptance emails: ' . $e->getMessage());
        }
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
        Agreement Duration: {$validated['agreement_duration']}
        
        DELIVERABLES:
        " . ($validated['deliverables'] ?? $proposal->deliverables) . "
        
        PAYMENT SCHEDULE:
        " . ($validated['payment_schedule'] ?? $proposal->payment_terms) . "
        
        TERMS AND CONDITIONS:
        " . ($validated['terms_and_conditions'] ?? 'Standard terms apply') . "
        
        This contract is binding upon acceptance.
        ";
    }

    /**
     * Generate app/website development agreement content
     */
    private function generateAppWebsiteProposalContent($data, $lead)
    {
        $gstAmount = ($data['total_cost'] * $data['gst_percentage']) / 100;
        $finalAmount = $data['total_cost'] + $gstAmount;
        
        $services = isset($data['services']) && is_array($data['services']) ? $data['services'] : [];
        $clientResponsibilities = isset($data['client_responsibilities']) && is_array($data['client_responsibilities']) ? $data['client_responsibilities'] : [];
        
        $servicesText = !empty($services) ? "\n- " . implode("\n- ", $services) : '';
        $responsibilitiesText = !empty($clientResponsibilities) ? "\n- " . implode("\n- ", $clientResponsibilities) : '';
        $additionalTerms = isset($data['additional_terms']) && $data['additional_terms'] ? "\n\n## 13. ADDITIONAL TERMS\n{$data['additional_terms']}" : '';
        
        // Build payment schedule from dynamic arrays
        $paymentSchedule = "";
        if (isset($data['payment_descriptions']) && isset($data['payment_percentages'])) {
            foreach ($data['payment_descriptions'] as $index => $description) {
                $percentage = $data['payment_percentages'][$index] ?? 0;
                $amount = ($finalAmount * $percentage) / 100;
                $paymentSchedule .= "- {$percentage}% {$description} (₹" . number_format($amount) . ")\n";
            }
        }
        
        // Format timeline
        $timelineText = "{$data['timeline_weeks']} weeks";
        
        $content = "
# {$data['project_title']} AGREEMENT

This Agreement is made on " . date('d.m.Y') . "

**BETWEEN**

**{$lead->customer_name}**,  
hereinafter referred to as the **\"Client\"**,

**AND**

**Konnectix Technologies Pvt. Ltd.**,  
hereinafter referred to as the **\"Service Provider.\"**

The Client and the Service Provider shall collectively be referred to as the **\"Parties.\"**

---

## 1. PURPOSE OF THE AGREEMENT

The purpose of this Agreement is to define the terms and conditions under which the Service Provider shall design and develop a {$data['project_title']} for the Client.

" . (isset($data['project_description']) && $data['project_description'] ? "## 1.1 PROJECT OVERVIEW\n\n{$data['project_description']}\n\n" : "") . "

" . (isset($data['objectives']) && $data['objectives'] ? "## 1.2 OBJECTIVES\n\n{$data['objectives']}\n\n" : "") . "

## 2. SCOPE OF WORK

The Service Provider agrees to provide the following services:
{$servicesText}

" . (isset($data['scope_of_work']) && $data['scope_of_work'] ? "{$data['scope_of_work']}\n\n" : "") . "

**Note:** Any features or changes beyond the above scope shall be considered additional work and charged separately upon mutual agreement.

## 3. PROJECT TIMELINE

- The project shall commence after receipt of the initial payment and required materials from the Client
- Estimated project completion timeline: **{$timelineText}**
- Any delay due to late content, approvals, or feedback from the Client shall extend the timeline accordingly

## 4. FEES & PAYMENT TERMS

- **Base Project Cost:** ₹" . number_format($data['total_cost']) . " (Rupees " . $this->convertToWords($data['total_cost']) . " Only)
- **GST ({$data['gst_percentage']}%):** ₹" . number_format($gstAmount) . "/-
- **Total Project Cost:** ₹" . number_format($finalAmount) . " (Rupees " . $this->convertToWords($finalAmount) . " Only)

**Payment Schedule:**
{$paymentSchedule}

The website/app shall not be made live until the full and final payment is received.

## 5. DOMAIN & HOSTING

- The domain name shall be provided by the **" . (isset($data['domain_provided_by']) ? $data['domain_provided_by'] : 'Client') . "**
- " . (isset($data['hosting_duration']) ? $data['hosting_duration'] : 'Hosting details to be confirmed') . "
- The Service Provider shall not be responsible for delays caused due to domain-related issues from the Client's end

## 6. CLIENT RESPONSIBILITIES

The Client agrees to:
{$responsibilitiesText}
- Provide timely feedback and approval on deliverables
- Provide necessary content, images, and other materials

## 7. INTELLECTUAL PROPERTY RIGHTS

- Ownership of the website/app and related files shall be transferred to the Client only after full payment
- The Service Provider retains the right to display the completed project in its portfolio unless restricted in writing

## 8. WARRANTY & SUPPORT

" . (isset($data['support_months']) && $data['support_months'] > 0 ? "- The Service Provider shall provide {$data['support_months']} months of technical support and maintenance from the date of project completion\n" : "- Technical support and maintenance shall be provided as mutually agreed upon\n") . "
- Support shall include bug fixes, minor updates, and server-related issues
- Additional support beyond the stipulated period shall be charged separately

## 9. TERMINATION

- Either Party may terminate this Agreement with written notice
- Payments made shall be non-refundable
- In case of termination after project commencement, the initial payment shall be forfeited

## 10. LIMITATION OF LIABILITY

The Service Provider shall not be liable for:
- Downtime or failure caused by third-party services including domain registrars and hosting providers
- Any indirect loss of business, revenue, or data

## 11. GOVERNING LAW & JURISDICTION

This Agreement shall be governed by and construed in accordance with the laws of India, and courts of Kolkata shall have exclusive jurisdiction.

## 12. ENTIRE AGREEMENT

This Agreement constitutes the entire understanding between the Parties and supersedes all prior discussions or communications.
{$additionalTerms}

---

## 13. ACCEPTANCE & SIGNATURES

By signing below, both Parties agree to the terms and conditions stated herein.

**For {$lead->customer_name} (Client)**  
Name: {$lead->customer_name}  
Signature: _________________  
Date: _________________

**For Konnectix Technologies Pvt. Ltd. (Service Provider)**  
Name: Ishita Banerjee  
Designation: Director  
Signature: _________________  
Date: " . date('d.m.Y') . "

---

**Contact Information:**  
Konnectix Technologies Pvt. Ltd.  
📞 7003228913 / 9123354003  
✉ info@konnectixtech.com  
🌐 www.konnectixtech.com  
📍 Dum Dum, Kolkata - 700 074
        ";
        
        return trim($content);
    }

    /**
     * Generate deliverables text for app/website proposal
     */
    private function generateAppWebsiteDeliverables($data)
    {
        $deliverables = [];
        
        if (isset($data['services']) && is_array($data['services'])) {
            $deliverables = $data['services'];
        } else {
            $deliverables = [
                "Fully functional website/app as per the agreed scope",
                "Responsive design for mobile and desktop",
                "Source code delivery"
            ];
        }
        
        if (isset($data['support_months']) && $data['support_months'] > 0) {
            $deliverables[] = "{$data['support_months']} months of free technical support";
        }
        
        return implode("\n", $deliverables);
    }

    /**
     * Generate payment terms text for app/website proposal
     */
    private function generateAppWebsitePaymentTerms($data)
    {
        $gstAmount = ($data['total_cost'] * $data['gst_percentage']) / 100;
        $finalAmount = $data['total_cost'] + $gstAmount;
        
        $paymentSchedule = "Total Cost: ₹" . number_format($data['total_cost']) . "/-\n" .
               "GST ({$data['gst_percentage']}%): ₹" . number_format($gstAmount) . "/-\n" .
               "Final Amount: ₹" . number_format($finalAmount) . "/-\n\n" .
               "Payment Schedule:\n";
        
        // Add dynamic payment terms
        if (isset($data['payment_descriptions']) && isset($data['payment_percentages'])) {
            foreach ($data['payment_descriptions'] as $index => $description) {
                $percentage = $data['payment_percentages'][$index] ?? 0;
                $paymentSchedule .= "- {$percentage}% {$description}\n";
            }
        }
        
        return $paymentSchedule;
    }

    /**
     * Estimate days from timeline string
     */
    private function estimateDaysFromTimeline($timeline)
    {
        // Try to extract number from timeline string
        if (preg_match('/(\d+)\s*(day|week|month)/i', $timeline, $matches)) {
            $number = intval($matches[1]);
            $unit = strtolower($matches[2]);
            
            switch ($unit) {
                case 'day':
                    return $number;
                case 'week':
                    return $number * 7;
                case 'month':
                    return $number * 30;
            }
        }
        
        return 7; // Default to 7 days
    }

    /**
     * Convert number to words (Indian numbering system)
     */
    private function convertToWords($number)
    {
        $number = intval($number);
        
        if ($number === 0) return 'Zero';
        
        $words = [
            '', 'One', 'Two', 'Three', 'Four', 'Five', 'Six', 'Seven', 'Eight', 'Nine',
            'Ten', 'Eleven', 'Twelve', 'Thirteen', 'Fourteen', 'Fifteen', 'Sixteen',
            'Seventeen', 'Eighteen', 'Nineteen'
        ];
        
        $tens = ['', '', 'Twenty', 'Thirty', 'Forty', 'Fifty', 'Sixty', 'Seventy', 'Eighty', 'Ninety'];
        
        if ($number < 20) {
            return $words[$number];
        }
        
        if ($number < 100) {
            return $tens[intval($number / 10)] . ($number % 10 ? ' ' . $words[$number % 10] : '');
        }
        
        if ($number < 1000) {
            return $words[intval($number / 100)] . ' Hundred' . ($number % 100 ? ' ' . $this->convertToWords($number % 100) : '');
        }
        
        if ($number < 100000) {
            return $this->convertToWords(intval($number / 1000)) . ' Thousand' . ($number % 1000 ? ' ' . $this->convertToWords($number % 1000) : '');
        }
        
        if ($number < 10000000) {
            return $this->convertToWords(intval($number / 100000)) . ' Lakh' . ($number % 100000 ? ' ' . $this->convertToWords($number % 100000) : '');
        }
        
        return $this->convertToWords(intval($number / 10000000)) . ' Crore' . ($number % 10000000 ? ' ' . $this->convertToWords($number % 10000000) : '');
    }

    /**
     * View proposal agreement as webpage
     */
    public function viewAgreement(Proposal $proposal)
    {
        // Determine which view template to use based on project type
        $projectType = strtolower($proposal->project_type ?? '');
        $view = 'proposals.web.app-website-agreement'; // Default view

        // Check for dedicated e-commerce agreements
        $ecommerceKeywords = ['e-commerce', 'ecommerce'];
        foreach ($ecommerceKeywords as $keyword) {
            if (strpos($projectType, $keyword) !== false) {
                $view = 'proposals.web.app-website-agreement';
                break;
            }
        }
        
        // Check for app/website development projects
        if ($view === 'proposals.web.app-website-agreement') {
            $appWebsiteKeywords = ['website', 'app', 'ecommerce', 'web application', 'mobile app'];
            foreach ($appWebsiteKeywords as $keyword) {
                if (strpos($projectType, $keyword) !== false) {
                    $view = 'proposals.web.app-website-agreement';
                    break;
                }
            }
        }
        
        // Check for social media projects
        $socialMediaKeywords = ['social media', 'youtube', 'graphic', 'poster', 'reels'];
        foreach ($socialMediaKeywords as $keyword) {
            if (strpos($projectType, $keyword) !== false) {
                $view = 'proposals.web.social-media-agreement';
                break;
            }
        }
        
        // Check for ERP/Software projects
        $erpKeywords = ['software', 'erp', 'ui/ux', 'design'];
        foreach ($erpKeywords as $keyword) {
            if (strpos($projectType, $keyword) !== false) {
                $view = 'proposals.web.erp-software-agreement';
                break;
            }
        }

        return view($view, [
            'proposal' => $proposal,
            'contentHtml' => Str::markdown($proposal->proposal_content ?? '')
        ]);
    }

    /**
     * View generated contract as webpage (for accepted proposals)
     */
    public function viewContract(Proposal $proposal)
    {
        // Only show contract for accepted proposals
        if ($proposal->status !== 'accepted') {
            return redirect()->route('proposals.show', $proposal->id)
                ->with('error', 'Contract is only available for accepted proposals.');
        }

        // Determine which contract template to use based on project type
        $contractView = $this->getContractViewByProjectType($proposal->project_type);
        
        // Get the contract record
        $contract = Contract::where('proposal_id', $proposal->id)->first();
        
        if (!$contract) {
            return redirect()->route('proposals.show', $proposal->id)
                ->with('error', 'Contract not found. Please contact support.');
        }

        return view($contractView, [
            'proposal' => $proposal,
            'contract' => $contract,
            'contract_data' => [
                'final_amount' => $contract->total_amount,
                'start_date' => $contract->start_date,
                'agreement_duration' => $contract->expected_completion_date,
                'payment_schedule' => $contract->payment_terms,
                'terms_and_conditions' => ''
            ]
        ]);
    }
}
