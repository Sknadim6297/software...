<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: linear-gradient(135deg, #7c3aed 0%, #a855f7 100%); color: white; padding: 20px; text-align: center; }
        .content { background: #f9f9f9; padding: 30px; }
        .target-box { background: #fef3c7; border-left: 4px solid #f59e0b; padding: 15px; margin: 20px 0; }
        .footer { text-align: center; padding: 20px; color: #666; font-size: 12px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>📊 Target Not Achieved</h2>
        </div>
        <div class="content">
            <p>Dear <strong>{{ $bdm->name }}</strong>,</p>
            
            <div class="target-box">
                <h3>Monthly Target Failure</h3>
                <p>Your target for <strong>{{ $target->period }}</strong> was not met.</p>
                <p><strong>Achievement:</strong> {{ number_format($target->achievement_percentage, 2) }}% (Required: 80%)</p>
            </div>
            
            <p><strong>Target Details:</strong></p>
            <ul>
                <li>Project Target: {{ $target->projects_achieved }} / {{ $target->total_project_target }}</li>
                <li>Revenue Target: ₹{{ number_format($target->revenue_achieved, 2) }} / ₹{{ number_format($target->total_revenue_target, 2) }}</li>
                <li>Overall Achievement: {{ number_format($target->achievement_percentage, 2) }}%</li>
            </ul>
            
            <p>Please note that consistent failure to meet targets may result in warnings and potential termination.</p>
            
            <p>For support and guidance on improving your performance, please reach out to your supervisor.</p>
            
            <p>Best regards,<br>Konnectix Management</p>
        </div>
        <div class="footer">
            © {{ date('Y') }} Konnectix Software. All rights reserved.
        </div>
    </div>
</body>
</html>
