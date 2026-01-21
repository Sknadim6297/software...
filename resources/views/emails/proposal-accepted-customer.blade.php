<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #28a745; color: white; padding: 20px; text-align: center; }
        .content { background: #f8f9fa; padding: 30px; margin-top: 20px; }
        .details { background: white; padding: 15px; margin: 15px 0; border-left: 4px solid #28a745; }
        .success { background: #d4edda; padding: 15px; border: 1px solid #c3e6cb; border-radius: 5px; margin: 20px 0; }
        .button { display: inline-block; padding: 12px 24px; background: #28a745; color: white; text-decoration: none; border-radius: 5px; margin: 20px 0; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🎉 Proposal Accepted!</h1>
        </div>
        
        <div class="content">
            <h2>Dear {{ $proposal->customer_name }},</h2>
            
            <div class="success">
                <h3>✅ Congratulations! Your proposal has been accepted.</h3>
                <p>We are excited to start working on your <strong>{{ $proposalTitle }}</strong> project!</p>
            </div>
            
            <h3>What Happens Next:</h3>
            <p>We have automatically generated your contract and invoice:</p>
            
            <div class="details">
                <h4>📄 Contract Details:</h4>
                <p><strong>Contract Number:</strong> {{ $contractNumber }}</p>
                <p><strong>Total Amount:</strong> ₹{{ number_format($totalAmount, 2) }}</p>
                <p><strong>Start Date:</strong> {{ \Carbon\Carbon::parse($startDate)->format('d M Y') }}</p>
                @if($isSocialMedia)
                    <p><strong>Agreement Duration:</strong> {{ $endDate }}</p>
                @else
                    <p><strong>Expected Completion:</strong> {{ \Carbon\Carbon::parse($endDate)->format('d M Y') }}</p>
                @endif
            </div>
            
            <div style="text-align: center;">
                <a href="{{ $contractUrl }}" class="button">View Your Contract</a>
            </div>
            
            <h3>Next Steps:</h3>
            <ul>
                <li>Review the contract document</li>
                <li>Our team will contact you shortly for project kickoff</li>
                <li>You will receive the invoice for the first payment milestone</li>
            </ul>
            
            <p style="margin-top: 30px;">
                <strong>Thank you for choosing Konnectix Technologies!</strong><br><br>
                Best regards,<br>
                Konnectix Technologies Team<br>
                📞 7003228913 / 9123354003<br>
                ✉ info@konnectixtech.com<br>
                🌐 www.konnectixtech.com
            </p>
        </div>
    </div>
</body>
</html>
