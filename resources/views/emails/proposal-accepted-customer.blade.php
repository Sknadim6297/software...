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
                <p>We are excited to start working on your <strong>{{ $proposal->project_type }}</strong> project!</p>
            </div>
            
            <h3>What Happens Next:</h3>
            <p>We have automatically generated the following documents for you:</p>
            
            <div class="details">
                <h4>📄 Contract Details:</h4>
                <p><strong>Contract Number:</strong> {{ $contract->contract_number }}</p>
                <p><strong>Final Amount:</strong> {{ $contract->currency }} {{ number_format($contract->final_amount, 2) }}</p>
                <p><strong>Start Date:</strong> {{ \Carbon\Carbon::parse($contract->start_date)->format('d M Y') }}</p>
                <p><strong>Expected Completion:</strong> {{ \Carbon\Carbon::parse($contract->expected_completion_date)->format('d M Y') }}</p>
            </div>
            
            <div class="details">
                <h4>🧾 Invoice Details:</h4>
                <p><strong>Invoice Number:</strong> {{ $invoice->invoice_number }}</p>
                <p><strong>Amount:</strong> {{ $invoice->currency }} {{ number_format($invoice->grand_total, 2) }}</p>
                <p><strong>Due Date:</strong> {{ $invoice->due_date->format('d M Y') }}</p>
                <p><strong>Payment Status:</strong> {{ ucfirst($invoice->payment_status) }}</p>
            </div>
            
            <h3>Project Timeline:</h3>
            @if($contract->deliverables)
                <p><strong>Deliverables:</strong></p>
                <p style="white-space: pre-line;">{{ $contract->deliverables }}</p>
            @endif
            
            @if($contract->milestones)
                <p><strong>Milestones:</strong></p>
                <p style="white-space: pre-line;">{{ $contract->milestones }}</p>
            @endif
            
            @if($contract->payment_schedule)
                <p><strong>Payment Schedule:</strong></p>
                <p style="white-space: pre-line;">{{ $contract->payment_schedule }}</p>
            @endif
            
            <p style="margin-top: 30px;">
                Our team will contact you shortly to discuss the next steps and project kickoff.
            </p>
            
            <p>
                <strong>Thank you for choosing Konnectix Technologies!</strong><br><br>
                Best regards,<br>
                Konnectix Technologies Team<br>
                Email: bdm.konnectixtech@gmail.com
            </p>
        </div>
    </div>
</body>
</html>
