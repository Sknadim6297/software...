<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ strtoupper($proposal->project_title ?? 'WEBSITE DEVELOPMENT') }} AGREEMENT</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap');

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f5f5f5;
            padding: 20px;
            line-height: 1.6;
            position: relative;
        }

        .container {
            max-width: 210mm;
            min-height: 297mm;
            margin: 0 auto;
            background-color: white;
            padding: 0;
            padding-bottom: 100px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            position: relative;
            margin-bottom: 40px;
            page-break-after: always;
            break-after: page;
        }

        .watermark {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 0;
            pointer-events: none;
            opacity: 0.08;
        }

        .watermark img {
            width: 500px;
            height: auto;
            max-width: 90vw;
        }

        .page-header {
            position: sticky;
            top: 0;
            background-color: white;
            z-index: 10;
            padding: 20mm 20mm 0 20mm;
        }

        .header-row {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            width: 100%;
            margin-bottom: 15px;
        }

        .green-bars {
            display: flex;
            flex-direction: column;
            gap: 15px;
            flex: 1;
        }

        .green-bar {
            height: 20px;
            background-color: #33973a;
            clip-path: polygon(0 0, 100% 0, calc(100% - 30px) 100%, 0 100%);
        }

        .green-bar:first-child {
            height: 55px;
            width: 110%;
            clip-path: polygon(0 0, 100% 0, calc(100% - 50px) 100%, 0 100%);
        }

        .green-bar:last-child {
            width: 70%;
        }

        .logo-section {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            gap: 10px;
            padding-top: 60px;
            padding-right: 30px;
        }

        .logo {
            height: 70px;
            width: auto;
        }

        .cin-number {
            font-size: 11px;
            color: #666;
            text-align: right;
            font-family: 'Poppins', sans-serif;
        }

        .content {
            position: relative;
            z-index: 1;
            padding: 0 20mm 20mm 20mm;
            padding-top: 20mm;
        }

        .agreement-content {
            font-size: 17px;
            line-height: 1.4;
            color: #000;
            text-align: justify;
            font-family: 'Poppins', sans-serif;
        }

        .agreement-title {
            font-size: 22px;
            font-weight: 700;
            text-align: center;
            text-decoration: underline;
            margin-bottom: 20px;
            margin-top: 0;
            color: #000;
            letter-spacing: 0.5px;
            line-height: 1.3;
            font-family: 'Poppins', sans-serif;
        }

        .agreement-date {
            text-align: left;
            margin-bottom: 25px;
            font-size: 17px;
            font-weight: 500;
            color: #000;
            line-height: 1.4;
            font-family: 'Poppins', sans-serif;
        }

        .parties-section {
            margin-bottom: 25px;
            text-align: left;
        }

        .between-label {
            text-align: left;
            font-size: 17px;
            margin: 18px 0;
            font-weight: 700;
            color: #000;
            line-height: 1.4;
            font-family: 'Poppins', sans-serif;
        }

        .party-info {
            margin: 12px 0;
            font-size: 17px;
            line-height: 1.4;
            color: #000;
            font-family: 'Poppins', sans-serif;
        }

        .party-info strong {
            color: #000;
            font-weight: 700;
            font-family: 'Poppins', sans-serif;
        }

        .parties-collective {
            margin-top: 18px;
            font-size: 17px;
            font-style: normal;
            color: #000;
            line-height: 1.4;
            font-family: 'Poppins', sans-serif;
        }

        .agreement-section {
            margin-bottom: 20px;
            page-break-inside: avoid;
        }

        .section-title {
            font-size: 19px;
            font-weight: 700;
            color: #000;
            margin-bottom: 10px;
            margin-top: 18px;
            line-height: 1.3;
            font-family: 'Poppins', sans-serif;
        }

        .section-content {
            margin-bottom: 10px;
            text-align: justify;
            font-size: 17px;
            line-height: 1.4;
            color: #000;
            font-family: 'Poppins', sans-serif;
        }

        .section-list {
            margin-left: 25px;
            margin-bottom: 10px;
            font-size: 17px;
            line-height: 1.4;
            color: #000;
            font-family: 'Poppins', sans-serif;
        }

        .section-list li {
            margin-bottom: 6px;
            text-align: justify;
            color: #000;
            font-family: 'Poppins', sans-serif;
        }

        .scope-list {
            margin-left: 25px;
            margin-bottom: 12px;
            font-size: 17px;
            line-height: 1.4;
            color: #000;
            font-family: 'Poppins', sans-serif;
        }

        .scope-list li {
            margin-bottom: 6px;
            text-align: justify;
            color: #000;
            font-family: 'Poppins', sans-serif;
        }

        .section-content strong {
            color: #000;
            font-weight: 700;
            font-family: 'Poppins', sans-serif;
        }

        /* Markdown content styling */
        .agreement-content h1,
        .agreement-content h2,
        .agreement-content h3 {
            margin-top: 18px;
            margin-bottom: 10px;
            font-weight: 700;
            color: #000;
            font-family: 'Poppins', sans-serif;
        }

        .agreement-content h1 {
            font-size: 24px;
            color: #000;
            text-align: center;
            margin-top: 0;
            margin-bottom: 12px;
        }

        .agreement-content h2 {
            font-size: 19px;
            color: #33973a;
            margin-top: 18px;
            margin-bottom: 10px;
        }

        .agreement-content h3 {
            font-size: 17px;
            color: #000;
            margin-top: 12px;
            margin-bottom: 8px;
        }

        .agreement-content p {
            font-size: 17px;
            line-height: 1.4;
            color: #000;
            text-align: justify;
            margin-bottom: 10px;
            font-family: 'Poppins', sans-serif;
        }

        .agreement-content ul,
        .agreement-content ol {
            margin-left: 25px;
            margin-bottom: 10px;
            font-size: 17px;
            line-height: 1.4;
            color: #000;
            font-family: 'Poppins', sans-serif;
        }

        .agreement-content li {
            margin-bottom: 6px;
            text-align: justify;
            color: #000;
            font-family: 'Poppins', sans-serif;
        }

        .agreement-content strong {
            font-weight: 700;
            color: #000;
        }

        .agreement-content em {
            font-style: italic;
        }

        .agreement-content hr {
            border: none;
            border-top: 2px solid #33973a;
            margin: 20px 0;
        }

        .agreement-content blockquote {
            border-left: 4px solid #33973a;
            padding-left: 15px;
            margin-left: 0;
            margin-bottom: 10px;
            font-style: italic;
            color: #333;
        }

        /* Contact Information Styling */
        .agreement-content > p:last-child,
        .contact-info {
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 15px;
            margin-top: 20px;
            font-size: 16px;
            line-height: 1.8;
        }

        .contact-info strong,
        .agreement-content > p:last-child strong {
            color: #000;
            font-weight: 700;
            display: block;
            margin-bottom: 8px;
            font-size: 17px;
        }

        .contact-info-item {
            display: block;
            margin-bottom: 6px;
            word-break: break-word;
        }

        .contact-info-item span {
            margin-right: 8px;
            font-size: 18px;
        }

        .footer-wrapper {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            width: 100%;
        }

        .footer-top-bar {
            position: absolute;
            bottom: 85%;
            right: 0;
            width: 120px;
            height: 45px;
            background-color: #33973a;
            clip-path: polygon(50px 0, 100% 0, 100% 100%, 0 100%);
            z-index: 2;
        }

        .footer {
            position: relative;
            background-color: #a9cdac;
            padding: 15px 20mm;
            color: black;
            font-size: 14px;
            width: 100%;
            z-index: 1;
            font-family: 'Poppins', sans-serif;
        }

        .footer-content {
            display: grid;
            grid-template-columns: 1fr 1fr;
            grid-template-rows: 1fr 1fr;
            gap: 10px 20px;
            align-items: center;
        }

        .footer-info {
            display: contents;
        }

        .footer-info span {
            white-space: nowrap;
            display: flex;
            align-items: center;
            gap: 8px;
            font-family: 'Poppins', sans-serif;
        }

        .footer-icon {
            color: black;
            font-size: 16px;
            display: inline-block;
            margin-right: 5px;
            filter: grayscale(100%) brightness(0);
        }

        .signature-section-wrapper {
            margin-top: 25px;
            page-break-inside: avoid;
        }

        .signature-section {
            margin-top: 25px;
            display: flex;
            justify-content: space-between;
            gap: 60px;
            align-items: flex-start;
        }

        .signature-block {
            flex: 1;
            margin-top: 0;
        }

        .signature-label {
            font-size: 18px;
            font-weight: 700;
            color: #000;
            margin-bottom: 15px;
            line-height: 1.2;
            font-family: 'Poppins', sans-serif;
        }

        .signature-detail {
            font-size: 18px;
            color: #000;
            margin-bottom: 8px;
            line-height: 1.2;
            font-family: 'Poppins', sans-serif;
        }

        .signature-detail:last-child {
            margin-bottom: 0;
        }

        .action-buttons {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1000;
            display: flex;
            gap: 10px;
        }

        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
            display: inline-block;
            font-family: 'Poppins', sans-serif;
        }

        .btn-primary {
            background-color: #33973a;
            color: white;
        }

        .btn-secondary {
            background-color: #6c757d;
            color: white;
        }

        .btn:hover {
            opacity: 0.9;
        }

        @media print {
            .action-buttons {
                display: none;
            }

            body {
                background-color: white;
                padding: 0;
            }

            .container {
                box-shadow: none;
                max-width: 100%;
                page-break-after: always;
                break-after: page;
                margin-bottom: 0;
            }

            .page-header {
                position: static;
            }

            .contact-info {
                page-break-inside: avoid;
            }

            .agreement-content > p:last-child {
                page-break-inside: avoid;
            }
        }
    </style>
</head>
<body>
    <!-- Action Buttons -->
    <div class="action-buttons">
        <button onclick="window.print()" class="btn btn-primary">Print / Save as PDF</button>
        <a href="{{ route('proposals.show', $proposal->id) }}" class="btn btn-secondary">Back to Proposal</a>
    </div>

    <!-- PAGE 1 -->
    <div class="container">
        <div class="watermark">
            <img src="{{ asset('template/images/logo/logo_konnectix.webp') }}" alt="Watermark">
        </div>

        <!-- Header -->
        <div class="header-row">
            <div class="green-bars">
                <div class="green-bar"></div>
                <div class="green-bar"></div>
            </div>
            <div class="logo-section">
                <img src="{{ asset('template/images/logo/logo_konnectix.webp') }}" alt="Konnectix Technologies Logo" class="logo">
                <div class="cin-number">CIN NO:-U72900WB2021PTC243081</div>
            </div>
        </div>

        <div class="content">
            <div class="agreement-content">
                @php
                    // Try to use proposal_content if available
                    $content = $proposal->proposal_content;
                    
                    // If proposal_content is empty or missing, try to reconstruct from metadata
                    if (empty($content)) {
                        $metadata = json_decode($proposal->metadata, true) ?? [];
                        
                        // Get GST info from proposed_amount if needed
                        $totalCost = $metadata['total_cost'] ?? $proposal->proposed_amount ?? 0;
                        $gstPercentage = $metadata['gst_percentage'] ?? 18;
                        $gstAmount = ($totalCost * $gstPercentage) / 100;
                        $finalAmount = $totalCost + $gstAmount;
                        
                        // Get other fields
                        $projectTitle = $metadata['project_title'] ?? $proposal->project_description ?? 'APP & WEBSITE DEVELOPMENT';
                        $projectDescription = $metadata['project_description'] ?? '';
                        $objectives = $metadata['objectives'] ?? '';
                        $scopeOfWork = $metadata['scope_of_work'] ?? '';
                        $timelineWeeks = $metadata['timeline_weeks'] ?? 4;
                        $supportMonths = $metadata['support_months'] ?? 0;
                        $domainProvidedBy = $metadata['domain_provided_by'] ?? 'Client';
                        $hostingDuration = $metadata['hosting_duration'] ?? '';
                        $clientResponsibilities = $metadata['client_responsibilities'] ?? [];
                        $paymentDescriptions = $metadata['payment_descriptions'] ?? [];
                        $paymentPercentages = $metadata['payment_percentages'] ?? [];
                        $additionalTerms = $metadata['additional_terms'] ?? '';
                        
                        // Build payment schedule
                        $paymentSchedule = "";
                        if (!empty($paymentDescriptions)) {
                            foreach ($paymentDescriptions as $index => $description) {
                                $percentage = $paymentPercentages[$index] ?? 0;
                                $amount = ($finalAmount * $percentage) / 100;
                                $paymentSchedule .= "- {$percentage}% {$description} (₹" . number_format($amount) . ")\n";
                            }
                        }
                        
                        // Reconstruct the full agreement content
                        $content = "# " . strtoupper($projectTitle) . " AGREEMENT\n\n";
                        $content .= "This Agreement is made on " . date('d.m.Y') . "\n\n";
                        
                        $content .= "**BETWEEN**\n\n";
                        $content .= "**" . $proposal->lead->customer_name . "**,  \n";
                        $content .= "hereinafter referred to as the **\"Client\"**,\n\n";
                        
                        $content .= "**AND**\n\n";
                        $content .= "**Konnectix Technologies Pvt. Ltd.**,  \n";
                        $content .= "hereinafter referred to as the **\"Service Provider.\"**\n\n";
                        
                        $content .= "The Client and the Service Provider shall collectively be referred to as the **\"Parties.\"**\n\n";
                        $content .= "---\n\n";
                        
                        $content .= "## 1. PURPOSE OF THE AGREEMENT\n\n";
                        $content .= "The purpose of this Agreement is to define the terms and conditions under which the Service Provider shall design and develop a " . $projectTitle . " for the Client.\n\n";
                        
                        if (!empty($projectDescription)) {
                            $content .= "## 1.1 PROJECT OVERVIEW\n\n" . $projectDescription . "\n\n";
                        }
                        
                        if (!empty($objectives)) {
                            $content .= "## 1.2 OBJECTIVES\n\n" . $objectives . "\n\n";
                        }
                        
                        $content .= "## 2. SCOPE OF WORK\n\n";
                        $content .= "The Service Provider agrees to provide the following services:\n\n";
                        
                        if (!empty($scopeOfWork)) {
                            $content .= $scopeOfWork . "\n\n";
                        } else {
                            $content .= "- Professional website/app design and development\n";
                            $content .= "- Responsive design for all devices\n";
                            $content .= "- User-friendly interface\n\n";
                        }
                        
                        $content .= "**Note:** Any features or changes beyond the above scope shall be considered additional work and charged separately upon mutual agreement.\n\n";
                        
                        $content .= "## 3. PROJECT TIMELINE\n\n";
                        $content .= "- The project shall commence after receipt of the initial payment and required materials from the Client\n";
                        $content .= "- Estimated project completion timeline: **" . $timelineWeeks . " weeks**\n";
                        $content .= "- Any delay due to late content, approvals, or feedback from the Client shall extend the timeline accordingly\n\n";
                        
                        $content .= "## 4. FEES & PAYMENT TERMS\n\n";
                        $content .= "- **Base Project Cost:** ₹" . number_format($totalCost) . "\n";
                        $content .= "- **GST (" . $gstPercentage . "%):** ₹" . number_format($gstAmount) . "/-\n";
                        $content .= "- **Total Project Cost:** ₹" . number_format($finalAmount) . "\n\n";
                        
                        if (!empty($paymentSchedule)) {
                            $content .= "**Payment Schedule:**\n" . $paymentSchedule . "\n";
                        }
                        
                        $content .= "The website/app shall not be made live until the full and final payment is received.\n\n";
                        
                        $content .= "## 5. DOMAIN & HOSTING\n\n";
                        $content .= "- The domain name shall be provided by the **" . $domainProvidedBy . "**\n";
                        if (!empty($hostingDuration)) {
                            $content .= "- " . $hostingDuration . "\n";
                        }
                        $content .= "- The Service Provider shall not be responsible for delays caused due to domain-related issues from the Client's end\n\n";
                        
                        $content .= "## 6. CLIENT RESPONSIBILITIES\n\n";
                        $content .= "The Client agrees to:\n";
                        if (!empty($clientResponsibilities)) {
                            foreach ($clientResponsibilities as $responsibility) {
                                $content .= "- " . $responsibility . "\n";
                            }
                        } else {
                            $content .= "- Provide all necessary content and materials\n";
                            $content .= "- Ensure timely approvals and feedback\n";
                        }
                        $content .= "\n";
                        
                        $content .= "## 7. INTELLECTUAL PROPERTY RIGHTS\n\n";
                        $content .= "- Ownership of the website/app and related files shall be transferred to the Client only after full payment\n";
                        $content .= "- The Service Provider retains the right to display the completed project in its portfolio unless restricted in writing\n\n";
                        
                        $content .= "## 8. WARRANTY & SUPPORT\n\n";
                        if ($supportMonths > 0) {
                            $content .= "- The Service Provider shall provide " . $supportMonths . " months of technical support and maintenance from the date of project completion\n";
                        } else {
                            $content .= "- Technical support and maintenance shall be provided as mutually agreed upon\n";
                        }
                        $content .= "- Support shall include bug fixes, minor updates, and server-related issues\n";
                        $content .= "- Additional support beyond the stipulated period shall be charged separately\n\n";
                        
                        $content .= "## 9. TERMINATION\n\n";
                        $content .= "- Either Party may terminate this Agreement with written notice\n";
                        $content .= "- Payments made shall be non-refundable\n";
                        $content .= "- In case of termination after project commencement, the initial payment shall be forfeited\n\n";
                        
                        $content .= "## 10. LIMITATION OF LIABILITY\n\n";
                        $content .= "The Service Provider shall not be liable for:\n";
                        $content .= "- Downtime or failure caused by third-party services including domain registrars and hosting providers\n";
                        $content .= "- Any indirect loss of business, revenue, or data\n\n";
                        
                        $content .= "## 11. GOVERNING LAW & JURISDICTION\n\n";
                        $content .= "This Agreement shall be governed by and construed in accordance with the laws of India, and courts of Kolkata shall have exclusive jurisdiction.\n\n";
                        
                        $content .= "## 12. ENTIRE AGREEMENT\n\n";
                        $content .= "This Agreement constitutes the entire understanding between the Parties and supersedes all prior discussions or communications.\n";
                        
                        if (!empty($additionalTerms)) {
                            $content .= "\n## 13. ADDITIONAL TERMS\n\n" . $additionalTerms . "\n";
                        }
                        
                        $content .= "\n---\n\n";
                        $content .= "## " . (!empty($additionalTerms) ? "14" : "13") . ". ACCEPTANCE & SIGNATURES\n\n";
                        $content .= "By signing below, both Parties agree to the terms and conditions stated herein.\n\n";
                        $content .= "**For " . $proposal->lead->customer_name . " (Client)**  \n";
                        $content .= "Name: " . $proposal->lead->customer_name . "  \n";
                        $content .= "Signature: _________________  \n";
                        $content .= "Date: _________________\n\n";
                        
                        $content .= "**For Konnectix Technologies Pvt. Ltd. (Service Provider)**  \n";
                        $content .= "Name: Ishita Banerjee  \n";
                        $content .= "Designation: Director  \n";
                        $content .= "Signature: _________________  \n";
                        $content .= "Date: " . date('d.m.Y') . "\n\n";
                        
                        $content .= "---\n\n";
                        $content .= "**Contact Information:**  \n";
                        $content .= "Konnectix Technologies Pvt. Ltd.  \n";
                        $content .= "📞 7003228913 / 9123354003  \n";
                        $content .= "✉ info@konnectixtech.com  \n";
                        $content .= "🌐 www.konnectixtech.com  \n";
                        $content .= "📍 Dum Dum, Kolkata - 700 074";
                    }
                @endphp
                
                {!! \Illuminate\Support\Str::markdown($content) !!}
            </div>
        </div>

        <!-- Footer -->
        <div class="footer-wrapper">
            <div class="footer-top-bar"></div>
            <div class="footer">
                <div class="footer-content">
                    <div class="footer-info">
                        <span><span class="footer-icon">☎</span> 7003228913 / 9123354003</span>
                        <span><span class="footer-icon">✉</span> info@konnectixtech.com</span>
                        <span><span class="footer-icon">📍</span> Dum Dum, Kolkata - 700 074</span>
                        <span><span class="footer-icon">🌐</span> www.konnectixtech.com</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>
</html>
