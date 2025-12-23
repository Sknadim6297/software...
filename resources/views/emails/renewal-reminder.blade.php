<!DOCTYPE html>
<html>
<head>
    <title>Service Renewal Reminder</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background-color: #4CAF50; color: white; padding: 20px; text-align: center; }
        .content { padding: 20px; background-color: #f9f9f9; }
        .details { background-color: white; padding: 15px; margin: 15px 0; border-left: 4px solid #4CAF50; }
        .detail-row { margin: 10px 0; }
        .label { font-weight: bold; color: #555; }
        .footer { text-align: center; padding: 20px; font-size: 12px; color: #777; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Service Renewal Reminder</h2>
        </div>
        
        <div class="content">
            <p>Dear {{ $serviceRenewal->customer->name }},</p>
            
            <p>This is a reminder that your <strong>{{ $serviceRenewal->service_type }}</strong> service is due for renewal.</p>
            
            <div class="details">
                <div class="detail-row">
                    <span class="label">Service Type:</span> {{ $serviceRenewal->service_type }}
                </div>
                <div class="detail-row">
                    <span class="label">Renewal Date:</span> {{ $serviceRenewal->renewal_date->format('d M, Y') }}
                </div>
                <div class="detail-row">
                    <span class="label">Renewal Type:</span> {{ $serviceRenewal->renewal_type }}
                </div>
                <div class="detail-row">
                    <span class="label">Amount:</span> ₹{{ number_format($serviceRenewal->amount, 2) }}
                </div>
                <div class="detail-row">
                    <span class="label">Invoice Number:</span> #INV-{{ str_pad($invoice->id, 5, '0', STR_PAD_LEFT) }}
                </div>
            </div>
            
            <p>Please find the attached invoice for your reference. Kindly make the payment before the renewal date to continue enjoying uninterrupted service.</p>
            
            <p>If you wish to discontinue the service, please contact your Business Development Manager.</p>
            
            <p>Thank you for your continued business!</p>
            
            <p>Best regards,<br>Konnectix Software Team</p>
        </div>
        
        <div class="footer">
            <p>This is an automated email. Please do not reply to this email.</p>
        </div>
    </div>
</body>
</html>
