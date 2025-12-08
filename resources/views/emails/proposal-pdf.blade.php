<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Proposal for {{ $proposal->customer_name }}</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        @page {
            margin: 0;
            margin-top: 130px;
            margin-bottom: 20px;
        }

        body {
            font-family: 'Poppins', Arial, sans-serif;
            background-color: white;
            padding: 0;
            line-height: 1.6;
            position: relative;
            margin: 0;
        }

        /* Watermark - fixed position for all pages */
        .watermark {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 0;
            pointer-events: none;
            opacity: 0.05;
            width: 100%;
            text-align: center;
        }

        .watermark img {
            width: 400px;
            height: auto;
            max-width: 60%;
        }

        /* Header - fixed at top of every page */
        .page-header {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            height: 110px;
            background: white;
            z-index: 10;
        }

        .header-content-wrapper {
            padding: 10px 20mm 0 20mm;
        }

        .green-bars {
            margin-bottom: 8px;
        }

        .green-bar {
            height: 15px;
            background-color: #33973a;
            margin-bottom: 10px;
            position: relative;
        }

        .green-bar:first-child {
            height: 45px;
            width: 100%;
        }

        .green-bar:last-child {
            width: 60%;
        }

        .logo-section {
            position: absolute;
            top: 10px;
            right: 20mm;
            text-align: right;
        }

        .logo {
            height: 55px;
            width: auto;
            margin-bottom: 3px;
        }

        .cin-number {
            font-size: 9px;
            color: #666;
        }

        /* Footer - inline at end of content only */
        .footer-wrapper {
            margin-top: 40px;
            position: relative;
            page-break-inside: avoid;
        }

        .footer-top-bar {
            width: 100px;
            height: 20px;
            background-color: #33973a;
            float: right;
            margin-bottom: -20px;
        }

        .footer {
            background-color: #a9cdac;
            padding: 12px 20mm;
            color: black;
            font-size: 11px;
            clear: both;
        }

        .footer-grid {
            display: table;
            width: 100%;
        }

        .footer-row {
            display: table-row;
        }

        .footer-cell {
            display: table-cell;
            padding: 3px 10px 3px 0;
            width: 50%;
        }

        .footer-icon {
            font-size: 12px;
            margin-right: 5px;
        }

        /* Main content area */
        .content {
            position: relative;
            z-index: 1;
            padding: 10px 20mm 0 20mm;
        }

        /* First page specific content */
        .first-page-content {
            margin-bottom: 15px;
            margin-top: 5px;
        }

        .company-details {
            margin-bottom: 18px;
            font-size: 11px;
            line-height: 1.6;
            color: #333;
        }

        .company-details p {
            margin-bottom: 2px;
        }

        .date {
            margin-top: 8px;
            font-weight: 600;
            font-size: 11px;
        }

        .recipient {
            margin-top: 18px;
            margin-bottom: 12px;
            font-size: 12px;
        }

        .recipient-name {
            margin-top: 5px;
        }

        .recipient-name strong {
            font-size: 13px;
            color: #2d7a32;
            font-weight: 600;
        }

        .subject {
            margin-top: 12px;
            margin-bottom: 15px;
            font-size: 12px;
        }

        .subject-text {
            font-weight: 600;
            color: #2d7a32;
            font-size: 12px;
        }

        .salutation {
            margin-top: 12px;
            margin-bottom: 8px;
            font-size: 12px;
        }

        /* Body content */
        .body-content {
            margin-top: 10px;
            font-size: 12px;
            line-height: 1.7;
            color: #333;
        }

        .body-content p {
            margin-bottom: 12px;
            text-align: justify;
        }

        .body-content strong {
            color: #2d7a32;
            font-weight: 600;
        }

        .body-content h1, .body-content h2, .body-content h3, .body-content h4 {
            color: #2d7a32;
            margin-top: 20px;
            margin-bottom: 10px;
            page-break-after: avoid;
            font-weight: 600;
            clear: both;
        }

        .body-content h1 { 
            font-size: 20px; 
            text-align: center;
            border-bottom: 3px solid #33973a; 
            padding-bottom: 8px;
            margin-bottom: 15px;
        }
        
        .body-content h2 { 
            font-size: 15px; 
            border-bottom: 2px solid #33973a; 
            padding-bottom: 5px;
            margin-top: 25px;
        }
        
        .body-content h3 { font-size: 13px; }
        .body-content h4 { font-size: 12px; }

        .body-content ul, .body-content ol {
            margin-left: 25px;
            margin-bottom: 12px;
        }

        .body-content li {
            margin-bottom: 6px;
            line-height: 1.7;
        }

        .body-content table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            page-break-inside: avoid;
            font-size: 11px;
        }

        .body-content table th,
        .body-content table td {
            border: 1px solid #33973a;
            padding: 12px;
            text-align: left;
            vertical-align: top;
        }

        .body-content table th {
            background-color: #33973a;
            color: white;
            font-weight: 600;
        }

        .body-content table tr:nth-child(even) {
            background-color: #f0f8f1;
        }
        
        .body-content table tr:hover {
            background-color: #e8f5e9;
        }
        
        .body-content hr {
            border: none;
            border-top: 2px solid #33973a;
            margin: 25px 0;
        }

        .section-title {
            font-size: 16px;
            font-weight: 600;
            color: #2d7a32;
            margin-top: 25px;
            margin-bottom: 12px;
            border-bottom: 2px solid #33973a;
            padding-bottom: 5px;
        }

        .info-box {
            background: #f0f8f1;
            border-left: 4px solid #33973a;
            padding: 15px;
            margin: 15px 0;
            border-radius: 4px;
        }

        .info-box p {
            margin: 5px 0;
        }

        /* Page breaks */
        .page-break {
            page-break-after: always;
        }

        /* Avoid breaking inside important elements */
        .recipient, .subject, .company-details {
            page-break-inside: avoid;
        }
    </style>
</head>
<body>
    @php
        $logoPath = public_path('logo.jpg');
        $logoData = '';
        if (file_exists($logoPath)) {
            $logoData = 'data:image/jpeg;base64,' . base64_encode(file_get_contents($logoPath));
        }
    @endphp
    
    <!-- Watermark - appears on all pages -->
    <div class="watermark">
        @if($logoData)
            <img src="{{ $logoData }}" alt="Watermark">
        @endif
    </div>
    
    <!-- Header - appears on all pages -->
    <div class="page-header">
        <div class="header-content-wrapper">
            <div class="green-bars">
                <div class="green-bar"></div>
                <div class="green-bar"></div>
            </div>
            <div class="logo-section">
                @if($logoData)
                    <img src="{{ $logoData }}" alt="Konnectix Technologies Logo" class="logo">
                @endif
                <div class="cin-number">CIN NO:-U72900WB2021PTC243081</div>
            </div>
        </div>
    </div>
    
    <!-- Main Content - flows across pages -->
    <div class="content">
        <div class="first-page-content">
            <div class="company-details">
                <p><strong>KONNECTIX TECHNOLOGIES PVT. LTD.</strong></p>
                <p>Dum Dum, Kolkata - 700 074, West Bengal, India</p>
                <p>Phone: 7003228913 / 9123354003 | Email: info@konnectixtech.com</p>
                <p class="date"><strong>Date:</strong> {{ $proposal->created_at?->format('d F, Y') }}</p>
            </div>

            <div class="recipient">
                <div><strong>To,</strong></div>
                <div class="recipient-name">
                    <strong>{{ $proposal->customer_name }}</strong><br>
                    @if($proposal->customer_email)Email: {{ $proposal->customer_email }}<br>@endif
                    @if($proposal->customer_phone)Phone: {{ $proposal->customer_phone }}@endif
                </div>
            </div>

            <div class="subject">
                <div><strong>Subject:</strong></div>
                <div class="subject-text">{{ $proposal->project_type }} Proposal - Submitted by Konnectix Technologies Pvt. Ltd.</div>
            </div>
        </div>

        <div class="body-content">
            {!! $contentHtml !!}
        </div>
        
        <!-- Footer - only at the end -->
        <div class="footer-wrapper">
            <div class="footer-top-bar"></div>
            <div class="footer">
                <div class="footer-grid">
                    <div class="footer-row">
                        <div class="footer-cell"><span class="footer-icon">☎</span> 7003228913 / 9123354003</div>
                        <div class="footer-cell"><span class="footer-icon">✉</span> info@konnectixtech.com</div>
                    </div>
                    <div class="footer-row">
                        <div class="footer-cell"><span class="footer-icon">📍</span> Dum Dum, Kolkata - 700 074</div>
                        <div class="footer-cell"><span class="footer-icon">🌐</span> www.konnectixtech.com</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
