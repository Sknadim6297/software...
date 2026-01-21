<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #28a745; color: white; padding: 20px; text-align: center; }
        .content { background: #f8f9fa; padding: 30px; margin-top: 20px; }
        .details { background: white; padding: 15px; margin: 15px 0; border-left: 4px solid #28a745; }
        .highlight { background: #d4edda; padding: 20px; border: 2px solid #28a745; border-radius: 5px; margin: 20px 0; }
        .button { display: inline-block; padding: 12px 24px; background: #28a745; color: white; text-decoration: none; border-radius: 5px; margin: 20px 0; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🎉 Proposal Accepted!</h1>
        </div>
        
        <div class="content">
            <h2>Great News!</h2>
            
            <div class="highlight">
                <h3>✅ Proposal Accepted by {{ $customerName }}</h3>
                <p><strong>Project:</strong> {{ $proposalTitle }}</p>
            </div>
            
            <div class="details">
                <h3>Customer Information:</h3>
                <p><strong>Name:</strong> {{ $customerName }}</p>
                <p><strong>Email:</strong> {{ $customerEmail }}</p>
            </div>
            
            <div class="details">
                <h3>Contract Details:</h3>
                <p><strong>Contract Number:</strong> {{ $contractNumber }}</p>
                <p><strong>Total Amount:</strong> ₹{{ number_format($totalAmount, 2) }}</p>
                <p><strong>Start Date:</strong> {{ \Carbon\Carbon::parse($startDate)->format('d M Y') }}</p>
                @if($isSocialMedia)
                    <p><strong>Agreement Duration:</strong> {{ $endDate }}</p>
                @else
                    <p><strong>Expected Completion:</strong> {{ \Carbon\Carbon::parse($endDate)->format('d M Y') }}</p>
                    <p><strong>Duration:</strong> {{ \Carbon\Carbon::parse($startDate)->diffInDays(\Carbon\Carbon::parse($endDate)) }} days</p>
                @endif
            </div>
            
            <div style="text-align: center;">
                <a href="{{ $proposalUrl }}" class="button">View Proposal Details</a>
            </div>
            
            <h3>✅ Auto-Generated:</h3>
            <ul>
                <li>✅ Contract has been generated</li>
                <li>✅ Invoice has been created</li>
                <li>✅ Customer email notification sent</li>
                <li>✅ Ready for project kickoff</li>
            </ul>
            
            <p style="margin-top: 30px; font-weight: bold;">
                All systems updated successfully. Time to start the project! 🚀
            </p>
            
            <p style="margin-top: 20px;">
                <strong>Konnectix Technologies Admin Panel</strong><br>
                Automated Notification System
            </p>
        </div>
    </div>
</body>
</html>
