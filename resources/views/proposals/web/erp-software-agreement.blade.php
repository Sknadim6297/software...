<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ERP SOFTWARE PROPOSAL</title>
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

        .content-inner {
            font-size: 15px;
            line-height: 1.6;
            color: #000;
            font-family: 'Poppins', sans-serif;
        }

        .content-inner h1 {
            font-size: 22px;
            font-weight: 700;
            text-align: center;
            margin-bottom: 25px;
            color: #000;
            text-decoration: underline;
        }

        .content-inner h2 {
            font-size: 19px;
            font-weight: 700;
            color: #000;
            margin-top: 25px;
            margin-bottom: 12px;
        }

        .content-inner h3 {
            font-size: 17px;
            font-weight: 700;
            color: #000;
            margin-top: 18px;
            margin-bottom: 10px;
        }

        .content-inner p {
            margin-bottom: 12px;
            text-align: justify;
        }

        .content-inner ul {
            margin-left: 25px;
            margin-bottom: 12px;
        }

        .content-inner li {
            margin-bottom: 6px;
        }

        .content-inner strong {
            font-weight: 700;
        }

        .letterhead {
            text-align: center;
            margin-bottom: 20px;
            font-size: 13px;
            line-height: 1.4;
        }

        .letterhead h3 {
            font-size: 18px;
            font-weight: 700;
            margin-bottom: 5px;
        }

        .letter-meta {
            text-align: left;
            margin-bottom: 20px;
            font-size: 15px;
        }

        .letter-body {
            text-align: justify;
            line-height: 1.6;
            margin-bottom: 15px;
        }

        .signature-block {
            margin-top: 30px;
        }

        .signature-block p {
            margin-bottom: 5px;
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
        $meta = $proposal->metadata ? json_decode($proposal->metadata, true) : [];
        $meta = is_array($meta) ? $meta : [];
        
        $projectTitle = $meta['project_title'] ?? $proposal->project_description ?? 'ERP Software Implementation';
        $clientName = $proposal->lead->customer_name ?? $proposal->customer_name ?? 'Client Company';
        $proposalDate = $proposal->created_at ? \Carbon\Carbon::parse($proposal->created_at)->format('d/m/Y') : date('d/m/Y');
        
        $totalCost = $proposal->total_cost ?? $meta['total_cost'] ?? 900000;
        $gstPercentage = $meta['gst_percentage'] ?? 18;
        
        // Dynamic payment terms
        $paymentDescriptions = $meta['payment_descriptions'] ?? ['Advance (Project Kickoff)', 'After Completion of Development', 'After Final Deployment'];
        $paymentPercentages = $meta['payment_percentages'] ?? [30, 40, 30];
        
        $timelineWeeks = $meta['timeline_weeks'] ?? '8-10';
        $supportMonths = $meta['support_months'] ?? 6;
        $architecture = $meta['architecture'] ?? 'Hostinger/Hostgator';
        $techStack = $meta['technology_stack'] ?? 'PHP/Laravel, MySQL, React/HTML, CSS, Bootstrap 4.0';
        
        $deliverables = $meta['deliverables'] ?? [
            'Fully functional ERP system as per the agreed scope',
            'Admin and Super Admin manuals',
            'User training materials',
            $supportMonths . ' months of free technical support post-deployment'
        ];
        
        $objectives = $meta['objectives'] ?? '';
        $scopeOfWork = $meta['scope_of_work'] ?? '';
        $introduction = $meta['project_description'] ?? '';
    @endphp

    <div class="action-buttons">
        <button onclick="window.print()" class="btn btn-primary">Print / Save as PDF</button>
        <a href="{{ route('proposals.show', $proposal->id) }}" class="btn btn-secondary">Back to Proposal</a>
    </div>

    <!-- PAGE 1: Cover Letter -->
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
            <div class="content-inner">
                <div class="letterhead">
                    <p>Konnectix Technologies Pvt. Ltd.<p>
                    <p>Regd. Office: 449, S. B Sarani, Sonarpur, Kolkata- 700149</p>
                    <p>Corporate Address: Dum Dum & Dum Dum Cantonment</p>
                    <p>Email: info@konnectixtech.com | Website: www.konnectixtech.com</p>
                </div>

                <div class="letter-meta">
                    <p><strong>Date:</strong> {{ $proposalDate }}</p>
                    <p style="margin-top: 15px;"><strong>To</strong></p>
                    <p>The Management,</p>
                    <p><strong>{{ $clientName }}</strong></p>
                </div>

                <p style="margin-top: 20px; margin-bottom: 15px;"><strong>Subject: Proposal for {{ $projectTitle }}</strong></p>

                <p style="margin-bottom: 15px;">Dear Sir,</p>

                <div class="letter-body">
                    @if($introduction)
                        {!! $introduction !!}
                    @else
                        <p>
                            We, at Konnectix Technologies Pvt Ltd, are pleased to present our proposal for developing
                            and implementing a comprehensive ERP software solution tailored to meet the operational
                            and management needs of {{ $clientName }}.
                        </p>

                        <p>
                            Our ERP solution is designed to optimize workflows, enhance productivity, and ensure
                            seamless integration across all departments. With key features including role-based access,
                            real-time data tracking, reporting tools, secure backup and restore functionalities (including
                            a super-admin recovery option for deleted items within 25 days), and advanced analytics,
                            our platform ensures efficiency and transparency at every level.
                        </p>

                        <p>
                            We pride ourselves on delivering technology solutions that are reliable, scalable, and user-
                            friendly, backed by our dedicated technical support team. Our implementation approach
                            includes requirement gathering, customization, training, deployment, and post-launch
                            support to ensure smooth adoption.
                        </p>

                        <p>
                            We are confident that our expertise, combined with a deep understanding of business
                            processes, will help {{ $clientName }} achieve greater operational efficiency
                            and strategic growth.
                        </p>

                        <p>
                            We look forward to the opportunity to collaborate with your team and deliver a solution that
                            exceeds expectations.
                        </p>
                    @endif
                </div>

                <div class="signature-block">
                    <p>Sincerely,</p>
                    <p style="margin-top: 20px;"><strong>Ishita Banerjee</strong></p>
                    <p>Director & Owner</p>
                    <p><em>Konnectix Technologies Pvt Ltd</em></p>
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

    <!-- PAGE 2+: Main Proposal -->
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
            <div class="content-inner">
                <h1>{{ strtoupper($projectTitle) }}</h1>

                <h2>1. Introduction</h2>
                <p>
                    Konnectix Technologies Pvt. Ltd. proposes the development and deployment of a fully
                    customized ERP software for <strong>{{ strtoupper($clientName) }}</strong>, designed to
                    integrate all essential business functions into a single unified platform.
                </p>
                <p>
                    The ERP will provide centralized data management, automated processes, and
                    advanced reporting capabilities, ensuring that your organization can operate more
                    efficiently, maintain compliance, and enhance decision-making.
                </p>

                <h2>2. Objectives</h2>
                @if($objectives)
                    {!! $objectives !!}
                @else
                    <ul>
                        <li>Automate and streamline day-to-day operations</li>
                        <li>Enable real-time access to data for better decision-making</li>
                        <li>Maintain a detailed log book of all activities for auditing and accountability</li>
                        <li>Ensure data security with role-based permissions for Super Admin, Admin, and User</li>
                        <li>Provide scalability for future process expansions</li>
                    </ul>
                @endif

                <h2>3. Scope of Work</h2>
                @if($scopeOfWork)
                    {!! $scopeOfWork !!}
                @else
                    <p><strong>Modules to be Developed:</strong></p>
                    <h3>1. User Management & Access Control</h3>
                    <ul>
                        <li><strong>Super Admin Panel:</strong> Full control of all modules, logs, settings, and user creation</li>
                        <li><strong>Admin Panel:</strong> Department-level control and approvals</li>
                        <li><strong>User Panel:</strong> Limited access as per assigned role</li>
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

    <!-- PAGE 3+: Technical & Financial Details -->
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
            <div class="content-inner">
                <h2>4. Integration & Customization</h2>
                <ul>
                    <li>API integration with third-party services if required</li>
                    <li>Custom workflow automation for {{ $clientName }}-specific processes</li>
                </ul>

                <h2>5. Training & Support</h2>
                <ul>
                    <li>User training sessions for different access levels</li>
                    <li>Technical support for {{ $supportMonths }} months post-deployment</li>
                </ul>

                <h2>6. Technical Specifications</h2>
                <p><strong>Architecture:</strong> {{ $architecture }}</p>
                <p><strong>Technology Stack:</strong> {{ $techStack }}</p>
                <p><strong>Security:</strong> SSL encryption, secure authentication, regular backups</p>
                <p><strong>Compatibility:</strong> Desktop, Tablet, Mobile responsive</p>

                <h2>7. Timeline</h2>
                <p><strong>Total Duration:</strong> Approximately {{ $timelineWeeks }} weeks</p>

                <h2>8. Pricing & Payment Terms</h2>
                <p><strong>Total Project Cost:</strong> ₹{{ number_format($totalCost) }} + {{ $gstPercentage }}% GST</p>

                <p style="margin-top: 15px;"><strong>Payment Schedule:</strong></p>
                <ul>
                    @foreach($paymentDescriptions as $index => $description)
                        @php
                            $percentage = $paymentPercentages[$index] ?? 0;
                        @endphp
                        <li>{{ $percentage }}% {{ $description }}</li>
                    @endforeach
                </ul>

                <h2>9. Deliverables</h2>
                <ul>
                    @foreach($deliverables as $deliverable)
                        <li>{{ $deliverable }}</li>
                    @endforeach
                </ul>

                <h2>10. Why Konnectix Technologies Pvt. Ltd.?</h2>
                <ul>
                    <li>Proven expertise in ERP design and implementation</li>
                    <li>Strong focus on user experience and data security</li>
                    <li>Dedicated post-deployment support team</li>
                    <li>Custom solutions aligned with your exact business processes</li>
                </ul>

                <h2>11. Acceptance</h2>
                <p>
                    We believe our proposed ERP solution will empower <strong>{{ strtoupper($clientName) }}</strong>
                    with improved operational efficiency, better control, and higher productivity. We are ready
                    to initiate the project upon your approval.
                </p>

                <div style="border-top: 2px solid #000; margin-top: 40px; padding-top: 20px;">
                    <p><strong>For Konnectix Technologies Pvt. Ltd.</strong></p>
                    <p style="margin-top: 40px;"><strong>Ishita Banerjee</strong></p>
                    <p>Director & Owner</p>
                    <p>Email: info@konnectixtech.com</p>
                    <p>Phone: 9123354003</p>
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
