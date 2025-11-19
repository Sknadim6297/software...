<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meeting Scheduled - Konnectix Technologies</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background: white;
            padding: 0;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
            border-radius: 10px 10px 0 0;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .content {
            padding: 30px;
        }
        .meeting-details {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
            border-left: 4px solid #667eea;
        }
        .detail-row {
            margin: 10px 0;
            display: flex;
            align-items: flex-start;
        }
        .detail-label {
            font-weight: bold;
            min-width: 130px;
            color: #555;
        }
        .detail-value {
            flex: 1;
        }
        .footer {
            background: #f8f9fa;
            padding: 20px 30px;
            text-align: center;
            border-radius: 0 0 10px 10px;
            border-top: 1px solid #e9ecef;
        }
        .company-info {
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #e9ecef;
            text-align: center;
            color: #666;
        }
        .btn {
            display: inline-block;
            background: #667eea;
            color: white;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 6px;
            margin: 10px 0;
        }
        @media (max-width: 600px) {
            .container {
                margin: 10px;
                padding: 0;
            }
            .header, .content, .footer {
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>{{ $isCustomerEmail ? 'Meeting Scheduled' : 'New Meeting Alert' }}</h1>
            <p>{{ $isCustomerEmail ? 'Your meeting has been confirmed' : 'A new meeting has been scheduled' }}</p>
        </div>

        <div class="content">
            @if($isCustomerEmail)
                <p>Dear {{ $lead->customer_name }},</p>
                <p>Thank you for your interest in our services. We're pleased to confirm that your meeting has been scheduled with our Business Development Manager.</p>
            @else
                <p>Hello BDM Team,</p>
                <p>A new meeting has been scheduled by the BDM team. Please find the details below:</p>
            @endif

            <div class="meeting-details">
                <h3 style="margin-top: 0; color: #667eea;">Meeting Details</h3>
                
                <div class="detail-row">
                    <span class="detail-label">Customer Name:</span>
                    <span class="detail-value">{{ $lead->customer_name }}</span>
                </div>
                
                <div class="detail-row">
                    <span class="detail-label">Phone Number:</span>
                    <span class="detail-value">{{ $lead->meeting_phone_number ?: $lead->phone_number }}</span>
                </div>
                
                @if($lead->email)
                <div class="detail-row">
                    <span class="detail-label">Email:</span>
                    <span class="detail-value">{{ $lead->email }}</span>
                </div>
                @endif
                
                <div class="detail-row">
                    <span class="detail-label">Date & Time:</span>
                    <span class="detail-value">{{ \Carbon\Carbon::parse($lead->meeting_time)->format('l, F j, Y \a\t g:i A') }}</span>
                </div>
                
                <div class="detail-row">
                    <span class="detail-label">Meeting Address:</span>
                    <span class="detail-value">{{ $lead->meeting_address }}</span>
                </div>
                
                <div class="detail-row">
                    <span class="detail-label">Contact Person:</span>
                    <span class="detail-value">{{ $lead->meeting_person_name }}</span>
                </div>
                
                <div class="detail-row">
                    <span class="detail-label">Project Type:</span>
                    <span class="detail-value">{{ ucwords(str_replace('_', ' ', $lead->project_type)) }}</span>
                </div>
                
                @if($lead->project_valuation)
                <div class="detail-row">
                    <span class="detail-label">Est. Project Value:</span>
                    <span class="detail-value">₹{{ number_format($lead->project_valuation, 2) }}</span>
                </div>
                @endif
                
                @if($lead->meeting_summary)
                <div class="detail-row">
                    <span class="detail-label">Discussion Summary:</span>
                    <span class="detail-value">{{ $lead->meeting_summary }}</span>
                </div>
                @endif
            </div>

            @if($isCustomerEmail)
                <p>Please make sure to be available at the scheduled time and location. If you need to reschedule or have any questions, please contact us immediately.</p>
                
                <p style="text-align: center;">
                    <a href="tel:+917007054441" class="btn">Call Us: +91 7007054441</a>
                </p>
            @else
                <p><strong>Note:</strong> This is an automated notification. Please ensure the BDM is prepared for this meeting.</p>
            @endif

            <div class="company-info">
                <h4>Konnectix Technologies</h4>
                <p>
                    📧 Email: info@konnectixtech.com<br>
                    📧 BDM Email: bdm.konnectixtech@gmail.com<br>
                    📞 Phone: +91 7007054441<br>
                    🌐 Website: www.konnectixtech.com
                </p>
            </div>
        </div>

        <div class="footer">
            <p><strong>This is an automated email. Please do not reply directly to this email.</strong></p>
            @if($isCustomerEmail)
                <p>For any queries, please contact us at <a href="mailto:info@konnectixtech.com">info@konnectixtech.com</a></p>
            @else
                <p>BDM Dashboard: <a href="{{ route('dashboard') }}">{{ url('/') }}</a></p>
            @endif
        </div>
    </div>
</body>
</html>
