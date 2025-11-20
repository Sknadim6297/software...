<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #007bff; color: white; padding: 20px; text-align: center; }
        .content { background: #f8f9fa; padding: 30px; margin-top: 20px; }
        .footer { text-align: center; padding: 20px; color: #666; font-size: 12px; }
        .button { display: inline-block; padding: 12px 30px; background: #28a745; color: white; text-decoration: none; border-radius: 5px; margin: 20px 0; }
        .details { background: white; padding: 15px; margin: 15px 0; border-left: 4px solid #007bff; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>New Proposal from Konnectix Technologies</h1>
        </div>
        
        <div class="content">
            <h2>Dear {{ $proposal->customer_name }},</h2>
            
            <p>We are pleased to send you a proposal for your <strong>{{ $proposal->project_type }}</strong> project.</p>
            
            <div class="details">
                <h3>Proposal Details:</h3>
                <p><strong>Project Type:</strong> {{ $proposal->project_type }}</p>
                <p><strong>Proposed Amount:</strong> {{ $proposal->currency }} {{ number_format($proposal->proposed_amount, 2) }}</p>
                @if($proposal->estimated_days)
                    <p><strong>Estimated Duration:</strong> {{ $proposal->estimated_days }} days</p>
                @endif
                @if($proposal->payment_terms)
                    <p><strong>Payment Terms:</strong> {{ $proposal->payment_terms }}</p>
                @endif
            </div>
            
            @if($proposal->project_description)
                <h3>Project Description:</h3>
                <p>{{ $proposal->project_description }}</p>
            @endif
            
            <h3>Full Proposal:</h3>
            <div style="background: white; padding: 20px; white-space: pre-wrap; font-family: inherit;">{{ $proposal->proposal_content }}</div>
            
            <p style="margin-top: 30px;">Please review the proposal and let us know if you have any questions or require any clarifications.</p>
            
            <p>We look forward to working with you!</p>
            
            <p>
                <strong>Best regards,</strong><br>
                Konnectix Technologies Team<br>
                Email: bdm.konnectixtech@gmail.com
            </p>
        </div>
        
        <div class="footer">
            <p>&copy; {{ date('Y') }} Konnectix Technologies. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
