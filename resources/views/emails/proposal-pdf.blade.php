<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Proposal for {{ $proposal->customer_name }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        @page {
            margin: 0;
            size: A4;
        }

        body {
            font-family: 'Times New Roman', serif;
            background-color: white;
            padding: 0;
            margin: 0;
            line-height: 1.6;
        }

        .container {
            width: 210mm;
            min-height: 297mm;
            margin: 0;
            background-color: white;
            padding: 0;
            padding-bottom: 80px;
            position: relative;
        }

        .page {
            position: relative;
            width: 210mm;
            min-height: 297mm;
            page-break-after: auto;
        }

        /* Watermark */
        .watermark {
            position: fixed;
            top: 50%;
            left: 50%;
            width: 500px;
            height: 500px;
            margin-left: -250px;
            margin-top: -250px;
            z-index: 0;
            pointer-events: none;
            opacity: 0.08;
        }

        .watermark img {
            width: 100%;
            height: auto;
        }

        .content {
            position: relative;
            z-index: 1;
            padding: 20mm;
            padding-top: 10mm;
        }

        /* Header with green bars and logo */
        .header {
            margin-bottom: 30px;
            position: relative;
        }

        .header-row {
            position: relative;
            width: 100%;
            margin-bottom: 15px;
            min-height: 120px;
            page-break-inside: avoid;
        }

        .green-bars {
            position: absolute;
            left: 0;
            top: 0;
            width: 75%;
            z-index: 1;
        }

        .green-bar {
            background-color: #33973a;
            position: relative;
        }

        .green-bar:first-child {
            height: 55px;
            width: 110%;
            margin-bottom: 15px;
        }

        .green-bar:last-child {
            height: 20px;
            width: 70%;
        }

        .header-content {
            display: flex;
            justify-content: flex-end;
            align-items: flex-start;
        }

        .logo-section {
            position: absolute;
            right: 20mm;
            top: 60px;
            text-align: right;
            z-index: 10;
        }

        .logo {
            height: 70px;
            width: auto;
            display: block;
            margin-bottom: 5px;
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
            white-space: nowrap;
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
            page-break-inside: avoid;
            orphans: 3;
            widows: 3;
        }

        .body-content strong {
            color: #2d7a32;
            font-weight: bold;
        }

        .body-content h1 {
            font-size: 18px;
            color: #2d7a32;
            font-weight: bold;
            margin-top: 25px;
            margin-bottom: 15px;
            text-align: center;
            border-bottom: 2px solid #33973a;
            padding-bottom: 8px;
            page-break-after: avoid;
        }

        .body-content h2 {
            font-size: 15px;
            color: #2d7a32;
            font-weight: bold;
            margin-top: 20px;
            margin-bottom: 10px;
            border-bottom: 2px solid #33973a;
            padding-bottom: 5px;
            page-break-after: avoid;
        }

        .body-content h3 {
            font-size: 13px;
            color: #2d7a32;
            font-weight: bold;
            margin-top: 15px;
            margin-bottom: 8px;
            page-break-after: avoid;
        }

        .body-content ul,
        .body-content ol {
            margin-left: 25px;
            margin-bottom: 15px;
            page-break-inside: avoid;
        }

        .body-content li {
            margin-bottom: 8px;
            line-height: 1.8;
            page-break-inside: avoid;
        }

        .body-content table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            font-size: 12px;
            page-break-inside: avoid;
        }

        .body-content table th {
            border: 1px solid #33973a;
            padding: 12px;
            text-align: left;
            background-color: #33973a;
            color: #ffffff;
            font-weight: bold;
        }

        .body-content table td {
            border: 1px solid #33973a;
            padding: 12px;
            text-align: left;
            background-color: #ffffff;
        }

        .body-content hr {
            border: none;
            border-top: 2px solid #33973a;
            margin: 25px 0;
        }

        /* Footer */
        .footer-wrapper {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            width: 100%;
            height: auto;
        }

        .footer-top-bar {
            position: absolute;
            top: -20px;
            right: 0;
            width: 120px;
            height: 45px;
            background-color: #33973a;
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
            width: 100%;
        }

        .footer-info {
            width: 100%;
        }

        .footer-row {
            margin-bottom: 5px;
            line-height: 1.8;
        }

        .footer-row:last-child {
            margin-bottom: 0;
        }

        .footer-info span {
            white-space: nowrap;
            display: inline-block;
            width: 49%;
            vertical-align: top;
            font-size: 14px;
        }

        .footer-icon {
            color: black;
            font-size: 16px;
            display: inline;
            margin-right: 5px;
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
    
    <div class="container">
        <div class="watermark">
            @if($logoData)
                <img src="{{ $logoData }}" alt="Watermark">
            @endif
        </div>
        
        <!-- Header row with green bars and logo -->
        <div class="header-row">
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
        
        <div class="content">
            <div class="company-details">
                <p><strong>KONNECTIX TECHNOLOGIES PVT. LTD.</strong></p>
                <p>Dum Dum, Kolkata - 700 074, West Bengal, India</p>
                <p>Phone: 7003228913 / 9123354003</p>
                <p>Email: info@konnectixtech.com | Website: www.konnectixtech.com</p>
                <p class="date"><strong>Date:</strong> {{ $proposal->created_at?->format('d F, Y') }}</p>
            </div>

            <div class="recipient">
                <div class="recipient-label"><strong>To,</strong></div>
                <div class="recipient-name">
                    <strong>{{ $proposal->customer_name }}</strong><br>
                    @if($proposal->customer_email)Email: {{ $proposal->customer_email }}<br>@endif
                    @if($proposal->customer_phone)Phone: {{ $proposal->customer_phone }}@endif
                </div>
            </div>

            <div class="subject">
                <div class="subject-label"><strong>Subject:</strong></div>
                <div class="subject-text">{{ $proposal->project_type }} Proposal - Submitted by Konnectix Technologies Pvt. Ltd.</div>
            </div>

            <div class="salutation">
                <strong>Dear {{ $proposal->customer_name }},</strong>
            </div>

            <div class="body-content">
                {!! $contentHtml !!}
            </div>
        </div>
        </div>

        <!-- Footer -->
        <div class="footer-wrapper">
            <div class="footer-top-bar"></div>
            <div class="footer">
                <div class="footer-content">
                    <div class="footer-info">
                        <div class="footer-row">
                            <span><span class="footer-icon">☎</span> 7003228913 / 9123354003</span>
                            <span><span class="footer-icon">✉</span> info@konnectixtech.com</span>
                        </div>
                        <div class="footer-row">
                            <span><span class="footer-icon">📍</span> Dum Dum, Kolkata - 700 074</span>
                            <span><span class="footer-icon">🌐</span> www.konnectixtech.com</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
