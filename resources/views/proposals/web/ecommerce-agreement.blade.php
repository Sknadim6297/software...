<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-COMMERCE WEBSITE DEVELOPMENT AGREEMENT</title>
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
            margin: 0 auto 40px auto;
            background-color: white;
            padding: 0;
            padding-bottom: 100px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            position: relative;
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

        .header-row {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            width: 100%;
            margin-bottom: 15px;
            padding: 20mm 20mm 0 20mm;
            position: sticky;
            top: 0;
            background-color: white;
            z-index: 10;
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

            .header-row {
                position: static;
                padding-top: 20mm;
            }
        }
    </style>
</head>
<body>
    @php
        $agreementTitle = 'E-COMMERCE WEBSITE DEVELOPMENT AGREEMENT';
        $clientName = $proposal->lead->customer_name ?? $proposal->customer_name ?? 'Client';
        $agreementDate = $proposal->created_at ? \Carbon\Carbon::parse($proposal->created_at)->format('d.m.Y') : '13.12.2025';

        $meta = $proposal->metadata ? json_decode($proposal->metadata, true) : [];
        $meta = is_array($meta) ? $meta : [];

        $totalCost = $proposal->total_cost
            ?? $proposal->proposed_amount
            ?? ($meta['total_cost'] ?? $meta['proposed_amount'] ?? 0);

        // Fallback: try to extract amounts/percentages from proposal content if DB fields are empty
        if (empty($totalCost) && !empty($proposal->proposal_content)) {
            if (preg_match('/Total\s+Project\s+Cost:\s*₹?\s*([0-9,]+)/i', $proposal->proposal_content, $m)) {
                $totalCost = (int) str_replace(',', '', $m[1]);
            }
        }

        $upfrontPercentage = $proposal->upfront_percentage
            ?? ($meta['upfront_percentage'] ?? $meta['advance_percentage'] ?? 30);

        $finalPercentage = $proposal->final_percentage
            ?? ($meta['final_percentage'] ?? (100 - $upfrontPercentage));

        if (!empty($proposal->proposal_content)) {
            if (empty($upfrontPercentage) || $upfrontPercentage === 0) {
                if (preg_match('/Upfront\s+Payment:\s*([0-9]{1,3})%/i', $proposal->proposal_content, $m)) {
                    $upfrontPercentage = (int) $m[1];
                }
            }

            if (empty($finalPercentage) || $finalPercentage === 0) {
                if (preg_match('/Final\s+Payment:\s*([0-9]{1,3})%/i', $proposal->proposal_content, $m)) {
                    $finalPercentage = (int) $m[1];
                } else {
                    $finalPercentage = 100 - $upfrontPercentage;
                }
            }
        }

        $upfrontAmount = $proposal->upfront_amount
            ?? ($meta['upfront_amount'] ?? ($totalCost * $upfrontPercentage / 100));

        $finalAmount = $proposal->final_amount
            ?? ($meta['final_amount'] ?? ($totalCost * $finalPercentage / 100));

        if (!empty($proposal->proposal_content)) {
            if (empty($upfrontAmount) || $upfrontAmount == 0) {
                if (preg_match('/Upfront\s+Payment:\s*[^₹]*₹\s*([0-9,]+)/i', $proposal->proposal_content, $m)) {
                    $upfrontAmount = (int) str_replace(',', '', $m[1]);
                }
            }

            if (empty($finalAmount) || $finalAmount == 0) {
                if (preg_match('/Final\s+Payment:\s*[^₹]*₹\s*([0-9,]+)/i', $proposal->proposal_content, $m)) {
                    $finalAmount = (int) str_replace(',', '', $m[1]);
                }
            }
        }

        $timeline = $proposal->timeline
            ?? $proposal->estimated_days
            ?? ($meta['timeline'] ?? '3 working days');

        $upfrontPercentage = $upfrontPercentage ?: 30;
        $finalPercentage = $finalPercentage ?: (100 - $upfrontPercentage);
        $totalCost = $totalCost ?: 0;

        if ((empty($upfrontAmount) || $upfrontAmount == 0) && $totalCost > 0) {
            $upfrontAmount = $totalCost * $upfrontPercentage / 100;
        }

        if ((empty($finalAmount) || $finalAmount == 0) && $totalCost > 0) {
            $finalAmount = $totalCost * $finalPercentage / 100;
        }

        $totalCostWords = is_numeric($totalCost)
            ? ucwords(\App\Helpers\NumberToWords::convert($totalCost))
            : 'Zero';
    @endphp

    <div class="action-buttons">
        <button onclick="window.print()" class="btn btn-primary">Print / Save as PDF</button>
        <a href="{{ route('proposals.show', $proposal->id) }}" class="btn btn-secondary">Back to Proposal</a>
    </div>

    <!-- Page 1 -->
    <div class="container">
        <div class="watermark">
            <img src="{{ asset('template/images/logo/logo_konnectix.webp') }}" alt="Watermark">
        </div>

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
                <h1 class="agreement-title">{{ $agreementTitle }}</h1>

                <p class="agreement-date">This Agreement is made on {{ $agreementDate }}</p>

                <div class="parties-section">
                    <p class="between-label"><strong>BETWEEN</strong></p>

                    <p class="party-info">
                        <strong>{{ $clientName }},</strong><br>
                        hereinafter referred to as the "Client",
                    </p>

                    <p class="between-label"><strong>AND</strong></p>

                    <p class="party-info">
                        <strong>Konnectix Technologies Pvt. Ltd.,</strong><br>
                        hereinafter referred to as the "Service Provider."
                    </p>

                    <p class="parties-collective">
                        The Client and the Service Provider shall collectively be referred to as the "Parties."
                    </p>
                </div>

                <div class="agreement-section">
                    <h2 class="section-title">1. PURPOSE OF THE AGREEMENT</h2>
                    <p class="section-content">
                        The purpose of this Agreement is to define the terms and conditions under which the
                        Service Provider shall design and develop an E-commerce website for the Client.
                    </p>
                </div>

                <div class="agreement-section">
                    <h2 class="section-title">2. SCOPE OF WORK</h2>
                    <p class="section-content">
                        The Service Provider agrees to provide the following services:
                    </p>
                    <ul class="scope-list">
                        <li>Design and development of an E-commerce website</li>
                        <li>Mobile-responsive design (compatible with desktop, tablet, and mobile devices)</li>
                        <li>Product listing and product detail pages</li>
                        <li>Shopping cart and checkout functionality</li>
                        <li>Payment gateway integration (subject to third-party approval)</li>
                        <li>Admin panel for managing products and orders</li>
                        <li>Website testing and deployment</li>
                    </ul>
                    <p class="section-content">
                        Any features or changes beyond the above scope shall be considered additional work and
                        charged separately upon mutual agreement.
                    </p>
                </div>
            </div>
        </div>

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

    <!-- Page 2 -->
    <div class="container">
        <div class="watermark">
            <img src="{{ asset('template/images/logo/logo_konnectix.webp') }}" alt="Watermark">
        </div>

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
                <div class="agreement-section">
                    <h2 class="section-title">3. PROJECT TIMELINE</h2>
                    <ul class="section-list">
                        <li>The project shall commence after receipt of the upfront payment and required materials from the Client</li>
                        <li>Estimated project completion timeline: {{ $timeline }}</li>
                        <li>Any delay due to late content, approvals, or feedback from the Client shall extend the timeline accordingly</li>
                    </ul>
                </div>

                <div class="agreement-section">
                    <h2 class="section-title">4. FEES &amp; PAYMENT TERMS</h2>
                    <ul class="section-list">
                        <li><strong>Total Project Cost:</strong> ₹{{ number_format($totalCost) }} (Rupees {{ $totalCostWords }} Only)</li>
                        <li><strong>Upfront Payment:</strong> {{ $upfrontPercentage }}% of the total amount (₹{{ number_format($upfrontAmount) }}) payable before commencement of work</li>
                        <li><strong>Final Payment:</strong> {{ $finalPercentage }}% of the total amount (₹{{ number_format($finalAmount) }}) payable after completion of the website and before the website goes live</li>
                    </ul>
                    <p class="section-content">
                        The website shall not be made live until the full and final payment is received.
                    </p>
                </div>

                <div class="agreement-section">
                    <h2 class="section-title">5. DOMAIN &amp; HOSTING</h2>
                    <ul class="section-list">
                        <li>The domain name shall be provided by the Client</li>
                        <li>Hosting for one (1) year shall be provided by the Service Provider</li>
                        <li>The Service Provider shall not be responsible for delays caused due to domain-related issues from the Client's end</li>
                    </ul>
                </div>

                <div class="agreement-section">
                    <h2 class="section-title">6. CLIENT RESPONSIBILITIES</h2>
                    <p class="section-content">
                        The Client agrees to:
                    </p>
                    <ul class="section-list">
                        <li>Provide all necessary content, product details, images, and branding materials</li>
                        <li>Ensure timely approvals and feedback</li>
                        <li>Bear any third-party charges such as payment gateway fees</li>
                    </ul>
                </div>

                <div class="agreement-section">
                    <h2 class="section-title">7. INTELLECTUAL PROPERTY RIGHTS</h2>
                    <ul class="section-list">
                        <li>Ownership of the website and related files shall be transferred to the Client only after full payment</li>
                        <li>The Service Provider retains the right to display the completed project in its portfolio unless restricted in writing</li>
                    </ul>
                </div>
            </div>
        </div>

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

    <!-- Page 3 -->
    <div class="container">
        <div class="watermark">
            <img src="{{ asset('template/images/logo/logo_konnectix.webp') }}" alt="Watermark">
        </div>

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
                <div class="agreement-section">
                    <h2 class="section-title">8. WARRANTY &amp; SUPPORT</h2>
                    <ul class="section-list">
                        <li>The Service Provider will provide 7 days of free bug support after website delivery</li>
                        <li>Any future updates, changes, or maintenance requests shall be chargeable separately</li>
                    </ul>
                </div>

                <div class="agreement-section">
                    <h2 class="section-title">9. TERMINATION</h2>
                    <ul class="section-list">
                        <li>Either Party may terminate this Agreement with written notice</li>
                        <li>Payments made shall be non-refundable</li>
                        <li>In case of termination after project commencement, the upfront payment shall be forfeited</li>
                    </ul>
                </div>

                <div class="agreement-section">
                    <h2 class="section-title">10. LIMITATION OF LIABILITY</h2>
                    <p class="section-content">
                        The Service Provider shall not be liable for:
                    </p>
                    <ul class="section-list">
                        <li>Downtime or failure caused by third-party services including domain registrars and payment gateways</li>
                        <li>Any indirect loss of business, revenue, or data</li>
                    </ul>
                </div>

                <div class="agreement-section">
                    <h2 class="section-title">11. GOVERNING LAW &amp; JURISDICTION</h2>
                    <p class="section-content">
                        This Agreement shall be governed by and construed in accordance with the laws of India, and
                        courts of Kolkata shall have exclusive jurisdiction.
                    </p>
                </div>

                <div class="agreement-section">
                    <h2 class="section-title">12. ENTIRE AGREEMENT</h2>
                    <p class="section-content">
                        This Agreement constitutes the entire understanding between the Parties and supersedes all
                        prior discussions or communications.
                    </p>
                </div>

                <div class="agreement-section signature-section-wrapper">
                    <h2 class="section-title">13. ACCEPTANCE &amp; SIGNATURES</h2>
                    <p class="section-content">
                        By signing below, both Parties agree to the terms and conditions stated herein.
                    </p>

                    <div class="signature-section">
                        <div class="signature-block">
                            <p class="signature-label">For {{ $clientName }} (Client)</p>
                            <p class="signature-detail">Name: {{ $clientName }}</p>
                            <p class="signature-detail">Signature:</p>
                            <p class="signature-detail">Date:</p>
                        </div>

                        <div class="signature-block">
                            <p class="signature-label">For Konnectix Technologies Pvt. Ltd. (Service Provider)</p>
                            <p class="signature-detail">Name: Ishita Banerjee</p>
                            <p class="signature-detail">Designation: Director</p>
                            <p class="signature-detail">Signature:</p>
                            <p class="signature-detail">Date: {{ $agreementDate }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

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
