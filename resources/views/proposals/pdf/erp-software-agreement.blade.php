<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ strtoupper($proposal->project_type ?? 'SOFTWARE DEVELOPMENT') }} AGREEMENT</title>
    <style>

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
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
            padding-bottom: 70px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            position: relative;
        }

        /* Watermark */
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

        .content {
            position: relative;
            z-index: 1;
            padding: 20mm;
            padding-top: 20mm;
        }

        /* Header with green bars and logo */
        .header {
            margin-bottom: 30px;
            position: relative;
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

        .header-content {
            display: flex;
            justify-content: flex-end;
            align-items: flex-start;
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

        .company-info {
            text-align: left;
        }

        .company-name {
            font-size: 18px;
            font-weight: bold;
            color: #2d7a32;
            margin-bottom: 5px;
            letter-spacing: 1px;
        }

        .cin-number {
            font-size: 11px;
            color: #666;
            text-align: right;
        }

        /* Company details section */
        .company-details {
            margin-top: 25px;
            margin-bottom: 30px;
            font-size: 12px;
            line-height: 1.8;
            color: #333;
        }

        .company-details p {
            margin-bottom: 3px;
        }

        .date {
            margin-top: 15px;
            font-weight: bold;
        }

        /* Recipient section */
        .recipient {
            margin-top: 30px;
            margin-bottom: 20px;
        }

        .recipient-label {
            font-size: 14px;
            margin-bottom: 5px;
        }

        .recipient-name {
            font-size: 14px;
            margin-top: 5px;
        }

        .recipient-name strong {
            font-size: 15px;
            color: #2d7a32;
        }

        /* Subject section */
        .subject {
            margin-top: 20px;
            margin-bottom: 25px;
        }

        .subject-label {
            font-size: 14px;
            margin-bottom: 5px;
        }

        .subject-text {
            font-size: 14px;
            font-weight: bold;
            text-decoration: underline;
            color: #2d7a32;
        }

        /* Salutation */
        .salutation {
            margin-top: 20px;
            margin-bottom: 15px;
            font-size: 14px;
        }

        /* Body content */
        .body-content {
            margin-top: 20px;
            font-size: 14px;
            text-align: justify;
            line-height: 1.8;
            color: #333;
        }

        .body-content p {
            margin-bottom: 15px;
            text-indent: 0;
        }

        .body-content strong {
            color: #2d7a32;
            font-weight: bold;
        }

        /* Footer */
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
        }

        .footer-icon {
            color: black;
            font-size: 16px;
            display: inline-block;
            margin-right: 5px;
            filter: grayscale(100%) brightness(0);
        }

        /* Agreement Content Styles */
        .agreement-content {
            font-size: 17px;
            line-height: 1.4;
            color: #000;
            text-align: justify;
        }

        .agreement-title {
            font-size: 22px;
            font-weight: bold;
            text-align: center;
            text-decoration: underline;
            margin-bottom: 20px;
            margin-top: -50px;
            color: #000;
            letter-spacing: 0.5px;
            line-height: 1.3;
        }

        .agreement-date {
            text-align: left;
            margin-bottom: 25px;
            font-size: 17px;
            font-weight: normal;
            color: #000;
            line-height: 1.4;
        }

        .parties-section {
            margin-bottom: 25px;
            text-align: left;
        }

        .between-label {
            text-align: left;
            font-size: 17px;
            margin: 18px 0;
            font-weight: bold;
            color: #000;
            line-height: 1.4;
        }

        .party-info {
            margin: 12px 0;
            font-size: 17px;
            line-height: 1.4;
            color: #000;
        }

        .party-info strong {
            color: #000;
            font-weight: bold;
        }

        .parties-collective {
            margin-top: 18px;
            font-size: 17px;
            font-style: normal;
            color: #000;
            line-height: 1.4;
        }

        .agreement-section {
            margin-bottom: 20px;
        }

        .section-title {
            font-size: 19px;
            font-weight: bold;
            color: #000;
            margin-bottom: 10px;
            margin-top: 18px;
            line-height: 1.3;
        }

        .section-content {
            margin-bottom: 10px;
            text-align: justify;
            font-size: 17px;
            line-height: 1.4;
            color: #000;
        }

        .section-list {
            margin-left: 25px;
            margin-bottom: 10px;
            font-size: 17px;
            line-height: 1.4;
            color: #000;
        }

        .section-list li {
            margin-bottom: 6px;
            text-align: justify;
            color: #000;
        }

        .scope-list {
            margin-left: 25px;
            margin-bottom: 12px;
            font-size: 17px;
            line-height: 1.4;
            color: #000;
        }

        .scope-list li {
            margin-bottom: 6px;
            text-align: justify;
            color: #000;
        }

        .section-content strong {
            color: #000;
            font-weight: bold;
        }

        /* Page break section for second page */
        .page-break-section {
            page-break-before: always;
            break-before: page;
            position: relative;
            min-height: 297mm;
            max-width: 210mm;
            width: 100%;
            margin: 0 auto;
            margin-top: 40px;
            background-color: white;
            padding: 0;
            padding-bottom: 70px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            clear: both;
        }

        .page-watermark {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 0;
            pointer-events: none;
            opacity: 0.08;
        }

        .page-watermark img {
            width: 500px;
            height: auto;
            max-width: 90vw;
        }

        .page-content {
            position: relative;
            z-index: 1;
            padding: 20mm;
            padding-top: 0mm;
        }

        /* Signature Section Styles */
        .signature-section-wrapper {
            margin-top: 25px;
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
            font-weight: bold;
            color: #000;
            margin-bottom: 15px;
            line-height: 1.2;
        }

        .signature-detail {
            font-size: 18px;
            color: #000;
            margin-bottom: 8px;
            line-height: 1.2;
        }

        .signature-detail:last-child {
            margin-bottom: 0;
        }

        /* Print styles */
        @media print {
            body {
                background-color: white;
                padding: 0;
            }

            .container {
                box-shadow: none;
                max-width: 100%;
                page-break-after: always;
            }

            .page-break-section {
                box-shadow: none;
                page-break-before: always;
                break-before: page;
            }

            .page-content {
                padding-top: 20mm;
            }
        }

        /* Screen view - page simulation */
        @media screen {
            .page-break-section {
                margin-top: 40px;
            }
        }
 
    </style>
</head>
<body>
    <!-- PAGE 1 -->
    <div class="container">
        <div class="watermark">
            <img src="{{ public_path('template/images/logo/logo_konnectix.webp') }}" alt="Watermark">
        </div>

        <div class="content">
            <!-- Header -->
            <div class="header-row">
                <div class="green-bars">
                    <div class="green-bar"></div>
                    <div class="green-bar"></div>
                </div>
                <div class="logo-section">
                    <img src="{{ public_path('template/images/logo/logo_konnectix.webp') }}" alt="Konnectix Technologies Logo" class="logo">
                    <div class="cin-number">CIN NO:-U72900WB2021PTC243081</div>
                </div>
            </div>

            <div class="agreement-content">
                <h1 class="agreement-title">{{ strtoupper($proposal->project_type) }}</h1>

                <p class="agreement-date">This Agreement is made on {{ \Carbon\Carbon::parse($proposal->created_at)->format('d.m.Y') }}</p>

                <div class="parties-section">
                    <p class="between-label"><strong>BETWEEN</strong></p>
                    <p class="party-info">
                        <strong>{{ $proposal->lead->customer_name }},</strong><br>
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
                        The purpose of this Agreement is to define the terms and conditions under which the Service Provider shall design and develop {{ strtolower($proposal->project_type) }} for the Client.
                    </p>
                </div>

                <div class="agreement-section">
                    <h2 class="section-title">2. SCOPE OF WORK</h2>
                    <p class="section-content">
                        The Service Provider agrees to provide professional development services including:
                    </p>
                    <ul class="section-list">
                        <li>Requirement analysis and technical architecture</li>
                        <li>System design and database design</li>
                        <li>Development and coding</li>
                        <li>Quality assurance and testing</li>
                        <li>Deployment and go-live support</li>
                    </ul>
                    <p class="section-content">
                        Any additional features or changes beyond the agreed scope shall be considered additional work and charged separately upon mutual agreement.
                    </p>
                </div>
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

    <!-- PAGE 2 -->
    <div class="page-break-section">
        <div class="page-watermark">
            <img src="{{ public_path('template/images/logo/logo_konnectix.webp') }}" alt="Watermark">
        </div>

        <!-- Header -->
        <div class="header-row">
            <div class="green-bars">
                <div class="green-bar"></div>
                <div class="green-bar"></div>
            </div>
            <div class="logo-section">
                <img src="{{ public_path('template/images/logo/logo_konnectix.webp') }}" alt="Konnectix Technologies Logo" class="logo">
                <div class="cin-number">CIN NO:-U72900WB2021PTC243081</div>
            </div>
        </div>

        <div class="page-content">
            <div class="agreement-content">
                <div class="agreement-section">
                    <h2 class="section-title">3. PROJECT TIMELINE</h2>
                    <ul class="section-list">
                        <li>The project shall commence after receipt of the advance payment and detailed requirements from the Client</li>
                        <li>Project phases: Analysis → Design → Development → Testing → Deployment</li>
                        <li>Any delay due to late content, approvals, or feedback from the Client shall extend the timeline accordingly</li>
                    </ul>
                </div>

                <div class="agreement-section">
                    <h2 class="section-title">4. FEES & PAYMENT TERMS</h2>
                    <ul class="section-list">
                        <li><strong>Total Project Cost:</strong> ₹{{ number_format($proposal->proposed_amount) }}</li>
                        <li><strong>Advance Payment:</strong> Upon project commencement</li>
                        <li><strong>Development Payment:</strong> Upon completion of development phase</li>
                        <li><strong>Final Payment:</strong> Upon successful deployment and go-live</li>
                    </ul>
                    <p class="section-content">
                        GST will be applicable on all amounts as per government regulations.
                    </p>
                </div>

                <div class="agreement-section">
                    <h2 class="section-title">5. CLIENT RESPONSIBILITIES</h2>
                    <p class="section-content">The Client agrees to:</p>
                    <ul class="section-list">
                        <li>Provide complete and detailed requirements at project initiation</li>
                        <li>Designate a project point of contact for timely decisions</li>
                        <li>Provide timely approvals and feedback</li>
                        <li>Bear any third-party licensing costs</li>
                        <li>Ensure the Client's environment is ready for deployment</li>
                    </ul>
                </div>

                <div class="agreement-section">
                    <h2 class="section-title">6. INTELLECTUAL PROPERTY RIGHTS</h2>
                    <ul class="section-list">
                        <li>Ownership of the software and related files shall be transferred to the Client only after full payment</li>
                        <li>The Service Provider retains the right to use the developed technology in future projects</li>
                        <li>The Service Provider retains the right to display the completed project in its portfolio unless restricted in writing</li>
                    </ul>
                </div>
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

    <!-- PAGE 3 -->
    <div class="page-break-section">
        <div class="page-watermark">
            <img src="{{ public_path('template/images/logo/logo_konnectix.webp') }}" alt="Watermark">
        </div>

        <!-- Header -->
        <div class="header-row">
            <div class="green-bars">
                <div class="green-bar"></div>
                <div class="green-bar"></div>
            </div>
            <div class="logo-section">
                <img src="{{ public_path('template/images/logo/logo_konnectix.webp') }}" alt="Konnectix Technologies Logo" class="logo">
                <div class="cin-number">CIN NO:-U72900WB2021PTC243081</div>
            </div>
        </div>

        <div class="page-content">
            <div class="agreement-content">
                <div class="agreement-section">
                    <h2 class="section-title">7. WARRANTY & SUPPORT</h2>
                    <ul class="section-list">
                        <li>The Service Provider will provide warranty cover for defects discovered within 30 days of go-live</li>
                        <li>Bug fixes and corrections during warranty period are provided at no additional cost</li>
                        <li>Post-warranty support and maintenance shall be chargeable separately</li>
                    </ul>
                </div>

                <div class="agreement-section">
                    <h2 class="section-title">8. TERMINATION</h2>
                    <ul class="section-list">
                        <li>Either Party may terminate this Agreement with written notice</li>
                        <li>Payments made shall be non-refundable</li>
                        <li>In case of termination after project commencement, the advance payment shall be forfeited</li>
                        <li>The Client shall receive all work completed up to the termination date</li>
                    </ul>
                </div>

                <div class="agreement-section">
                    <h2 class="section-title">9. LIMITATION OF LIABILITY</h2>
                    <p class="section-content">The Service Provider shall not be liable for:</p>
                    <ul class="section-list">
                        <li>System downtime due to Client's infrastructure or network issues</li>
                        <li>Loss of data due to Client's failure to maintain backups</li>
                        <li>Third-party library or framework changes</li>
                        <li>Indirect loss of business, revenue, or data</li>
                    </ul>
                </div>

                <div class="agreement-section">
                    <h2 class="section-title">10. CONFIDENTIALITY</h2>
                    <p class="section-content">
                        Both parties agree to maintain confidentiality of all sensitive information, including source code, architecture, business logic, and client data shared during the project.
                    </p>
                </div>

                <div class="agreement-section">
                    <h2 class="section-title">11. GOVERNING LAW & JURISDICTION</h2>
                    <p class="section-content">
                        This Agreement shall be governed by and construed in accordance with the laws of India, and courts of Kolkata shall have exclusive jurisdiction.
                    </p>
                </div>

                <div class="agreement-section signature-section-wrapper">
                    <h2 class="section-title">12. ACCEPTANCE & SIGNATURES</h2>
                    <p class="section-content">
                        By signing below, both Parties agree to the terms and conditions stated herein.
                    </p>

                    <div class="signature-section">
                        <div class="signature-block">
                            <p class="signature-label">For {{ $proposal->lead->customer_name }} (Client)</p>
                            <p class="signature-detail">Name: {{ $proposal->lead->customer_name }}</p>
                            <p class="signature-detail">Signature:</p>
                            <p class="signature-detail">Date:</p>
                        </div>

                        <div class="signature-block">
                            <p class="signature-label">For Konnectix Technologies Pvt. Ltd. (Service Provider)</p>
                            <p class="signature-detail">Name: Ishita Banerjee</p>
                            <p class="signature-detail">Designation: Director</p>
                            <p class="signature-detail">Signature:</p>
                            <p class="signature-detail">Date: {{ \Carbon\Carbon::parse($proposal->created_at)->format('d.m.Y') }}</p>
                        </div>
                    </div>
                </div>
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
