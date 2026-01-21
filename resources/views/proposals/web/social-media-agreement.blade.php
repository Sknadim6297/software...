<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ strtoupper($proposal->project_type ?? 'SOCIAL MEDIA MARKETING') }} AGREEMENT</title>
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

        .agreement-content h1,
        .agreement-content h2,
        .agreement-content h3,
        .agreement-content h4 {
            font-family: 'Poppins', sans-serif;
            font-weight: 700;
            color: #000;
            margin-top: 18px;
            margin-bottom: 12px;
        }

        .agreement-content h1 {
            font-size: 22px;
            text-align: center;
        }

        .agreement-content h2 {
            font-size: 19px;
        }

        .agreement-content p {
            margin-bottom: 10px;
            font-size: 17px;
            line-height: 1.4;
        }

        .agreement-content ul,
        .agreement-content ol {
            margin-left: 25px;
            margin-bottom: 12px;
            font-size: 17px;
            line-height: 1.4;
        }

        .agreement-content li {
            margin-bottom: 6px;
        }

        .agreement-content table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
            font-size: 17px;
        }

        .agreement-content table th,
        .agreement-content table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }

        .agreement-content table th {
            background-color: #f2f2f2;
            font-weight: 700;
        }

        .agreement-content strong {
            font-weight: 700;
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
        }
    </style>
</head>
<body>
    <!-- Action Buttons -->
    <div class="action-buttons">
        <button onclick="window.print()" class="btn btn-primary">Print / Save as PDF</button>
        <a href="{{ route('proposals.show', $proposal->id) }}" class="btn btn-secondary">Back to Proposal</a>
    </div>

    @php
        $meta = $proposal->metadata ? json_decode($proposal->metadata, true) : [];
        $meta = is_array($meta) ? $meta : [];
        
        $companyName = $meta['company_name'] ?? $proposal->lead->customer_name ?? 'Client Company';
        $platforms = $meta['platforms'] ?? ['Instagram', 'Facebook'];
        $platformsText = is_array($platforms) ? implode(' & ', $platforms) : 'Instagram & Facebook';
        
        $monthlyCharges = $meta['monthly_charges'] ?? $proposal->proposed_amount ?? 19000;
        $postersPerMonth = $meta['posters_per_month'] ?? 30;
        $reelsPerWeek = $meta['reels_per_week'] ?? 2;
        $reelsPerMonth = $reelsPerWeek * 4;
        
        $services = $meta['services'] ?? [];
        $paymentMode = $meta['payment_mode'] ?? 'Bank Transfer / UPI';
        $gstApplicable = $meta['gst_applicable'] ?? 'Additional as applicable';

        $scopeOfWork = $meta['scope_of_work'] ?? '';
        $marketingStrategy = $meta['marketing_strategy'] ?? '';
    @endphp

    <!-- PAGE 1 -->
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
                <h1>Social Media Marketing Proposal</h1>
                
                <p style="text-align: center; font-weight: 600; margin-bottom: 5px;">For {{ $companyName }}</p>
                <p style="text-align: center; margin-bottom: 5px;">Submitted by: Konnectix Technologies Pvt. Ltd.</p>
                <p style="text-align: center; margin-bottom: 20px;"><strong>Platforms Covered:</strong> {{ $platformsText }}</p>

                <h2>Scope of Work</h2>
                @if($scopeOfWork)
                    {!! $scopeOfWork !!}
                @else
                    <p>
                        We propose a complete social media marketing solution to enhance {{ $companyName }}'s digital presence, drive
                        quality leads, and increase brand awareness across {{ $platformsText }}.
                    </p>

                    <h3 style="font-size: 18px; margin-top: 15px;"><strong>Content Creation & Posting</strong></h3>
                    <ul>
                        <li>{{ $postersPerMonth }} Posters per Month (Static/Carousel/Infographic based on marketing objective)</li>
                        <li>{{ $reelsPerWeek }} Reels per Week (Product-focused, testimonial, behind-the-scenes, etc.)</li>
                        <li>Video Editing Support: Any video content shared by your team will be professionally edited and optimized for social media</li>
                    </ul>
                @endif

                @if($marketingStrategy)
                <h2 style="margin-top: 25px;">Marketing Strategy</h2>
                {!! $marketingStrategy !!}
                @else
                <h2 style="margin-top: 25px;">Marketing Strategy</h2>

                <h3 style="font-size: 18px; margin-top: 15px;"><strong>Platform Management</strong></h3>
                <ul>
                    <li>Page/Profile optimization on both platforms</li>
                    <li>Daily posting and engaging caption writing</li>
                    <li>Hashtag research and implementation</li>
                    <li>Profile highlights and story management</li>
                </ul>

                <h3 style="font-size: 18px; margin-top: 15px;"><strong>Lead Generation</strong></h3>
                <ul>
                    <li>Use of Meta Lead Forms and Landing Pages</li>
                </ul>

                <h3 style="font-size: 18px; margin-top: 15px;"><strong>Paid Ad Management</strong></h3>
                <ul>
                    <li>Strategic ad campaigns for Lead Generation (targeting businesses, builders, and architects)</li>
                    <li>Page Likes & Followers Growth</li>
                    <li>A/B Testing of creatives & audience targeting</li>
                    <li>Real-time ad monitoring and optimization for ROI</li>
                </ul>
                @endif
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

    <!-- PAGE 2 -->
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
                <h2 style="margin-top: 30px;">Financials</h2>

                <table>
                    <thead>
                        <tr>
                            <th style="width: 60%;">Deliverables</th>
                            <th style="width: 40%;">Details</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>30 Posters / month</td>
                            <td>{{ $postersPerMonth }} per month</td>
                        </tr>
                        <tr>
                            <td>{{ $reelsPerWeek }} Reels / Week</td>
                            <td>{{ $reelsPerMonth }}+ per month</td>
                        </tr>
                        <tr>
                            <td>Ad Creative Designs</td>
                            <td>Included</td>
                        </tr>
                        <tr>
                            <td>Video Editing</td>
                            <td>Included (Client video)</td>
                        </tr>
                        <tr>
                            <td>Lead Generation Setup & Monitoring</td>
                            <td>Included</td>
                        </tr>
                        <tr>
                            <td>Page Management & Strategy</td>
                            <td>Included</td>
                        </tr>
                        <tr>
                            <td><strong>Monthly Charges</strong></td>
                            <td><strong>Rs. {{ number_format($monthlyCharges) }}</strong></td>
                        </tr>
                        <tr>
                            <td><strong>Payment Terms</strong></td>
                            <td><strong>Advance</strong></td>
                        </tr>
                    </tbody>
                </table>

                <h2 style="margin-top: 25px;">Payment Details</h2>
                <ul>
                    <li><strong>Total Monthly Fee:</strong> Rs. {{ number_format($monthlyCharges) }}/-</li>
                    <li><strong>Payment Mode:</strong> {{ $paymentMode }}</li>
                    <li><strong>GST:</strong> {{ $gstApplicable }}</li>
                    <li><strong>Advance Payment:</strong> One month in advance to initiate work</li>
                </ul>

                <h2 style="margin-top: 25px;">Note</h2>
                <ul>
                    <li>Meta Ad budget (Facebook/Instagram Ads) is to be provided separately by the client</li>
                    <li>Ads will be run through client's business manager/ad account for transparency</li>
                    <li>All designs and edited content will be shared for approval before posting</li>
                </ul>
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

    <!-- PAGE 3 -->
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
                <h1 style="margin-top: 50px; margin-bottom: 30px;">Let's Elevate Your Digital Presence!</h1>
                
                <p style="text-align: center; font-size: 18px; line-height: 1.6; margin-bottom: 40px;">
                    We look forward to helping {{ $companyName }} achieve strong visibility and qualified leads through powerful and
                    strategic social media marketing.
                </p>

                <div style="text-align: center; margin-top: 50px;">
                    <p style="font-size: 18px; font-weight: 600; margin-bottom: 20px;">For queries or approval, feel free to contact us.</p>
                    
                    <p style="margin-bottom: 10px;"><strong>Phone:</strong> +91 9123354003</p>
                    <p style="margin-bottom: 10px;"><strong>Email:</strong> sales.konnectixtech@gmail.com</p>
                    <p style="margin-bottom: 10px;"><strong>Website:</strong> www.konnectixtech.com</p>
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
