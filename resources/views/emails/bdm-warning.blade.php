<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: linear-gradient(135deg, #7c3aed 0%, #a855f7 100%); color: white; padding: 20px; text-align: center; }
        .content { background: #f9f9f9; padding: 30px; }
        .warning-box { background: #fef3c7; border-left: 4px solid #f59e0b; padding: 15px; margin: 20px 0; }
        .footer { text-align: center; padding: 20px; color: #666; font-size: 12px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>⚠️ Performance Warning</h2>
        </div>
        <div class="content">
            <p>Dear <strong>{{ $bdm->name }}</strong>,</p>
            
            <div class="warning-box">
                <h3>Warning {{ $warningCount }}/3</h3>
                <p>This is an official warning regarding your performance for <strong>{{ $targetPeriod }}</strong>.</p>
                <p>Your achievement did not meet the minimum required threshold of 80%.</p>
            </div>
            
            <p><strong>Important Information:</strong></p>
            <ul>
                <li>Current Warning Count: <strong>{{ $warningCount }} out of 3</strong></li>
                <li>Target Period: {{ $targetPeriod }}</li>
                <li>Warning Date: {{ now()->format('F d, Y') }}</li>
            </ul>
            
            @if($warningCount >= 3)
                <p style="color: red; font-weight: bold;">
                    ⚠️ This is your final warning. Failure to meet the next target will result in termination.
                </p>
            @else
                <p>Please improve your performance in the upcoming period. Consecutive failures to meet targets may result in termination.</p>
            @endif
            
            <p>For any clarifications, please contact your supervisor or HR department.</p>
            
            <p>Best regards,<br>Konnectix Management</p>
        </div>
        <div class="footer">
            © {{ date('Y') }} Konnectix Software. All rights reserved.
        </div>
    </div>
</body>
</html>
