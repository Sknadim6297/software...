<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: linear-gradient(135deg, #dc2626 0%, #991b1b 100%); color: white; padding: 20px; text-align: center; }
        .content { background: #f9f9f9; padding: 30px; }
        .termination-box { background: #fee2e2; border-left: 4px solid #dc2626; padding: 15px; margin: 20px 0; }
        .footer { text-align: center; padding: 20px; color: #666; font-size: 12px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>🚫 Account Termination Notice</h2>
        </div>
        <div class="content">
            <p>Dear <strong>{{ $bdm->name }}</strong>,</p>
            
            <div class="termination-box">
                <h3>Account Terminated</h3>
                <p><strong>Reason:</strong> {{ $reason }}</p>
                <p><strong>Effective Date:</strong> {{ now()->format('F d, Y') }}</p>
            </div>
            
            <p>Your employment with Konnectix Software has been terminated due to the reason mentioned above.</p>
            
            <p><strong>Important Information:</strong></p>
            <ul>
                <li>Your access to the BDM panel has been revoked</li>
                <li>Final settlement will be processed as per company policy</li>
                <li>Please return all company property</li>
                <li>Termination date: {{ $bdm->termination_date->format('F d, Y') }}</li>
            </ul>
            
            <p>For any questions regarding final settlement or exit formalities, please contact the HR department.</p>
            
            <p>Best regards,<br>Konnectix Management</p>
        </div>
        <div class="footer">
            © {{ date('Y') }} Konnectix Software. All rights reserved.
        </div>
    </div>
</body>
</html>
