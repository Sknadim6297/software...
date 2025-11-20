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
                <h3>✅ Proposal Accepted by {{ $proposal->customer_name }}</h3>
                <p><strong>Project:</strong> {{ $proposal->project_type }}</p>
            </div>
            
            <div class="details">
                <h3>Customer Information:</h3>
                <p><strong>Name:</strong> {{ $proposal->customer_name }}</p>
                <p><strong>Email:</strong> {{ $proposal->customer_email }}</p>
                <p><strong>Phone:</strong> {{ $proposal->customer_phone }}</p>
                <p><strong>Lead Type:</strong> {{ ucfirst($proposal->lead_type) }}</p>
            </div>
            
            <div class="details">
                <h3>Proposal Details:</h3>
                <p><strong>Proposal ID:</strong> #{{ $proposal->id }}</p>
                <p><strong>Project Type:</strong> {{ $proposal->project_type }}</p>
                <p><strong>Original Amount:</strong> {{ $proposal->currency }} {{ number_format($proposal->proposed_amount, 2) }}</p>
                <p><strong>Accepted At:</strong> {{ $proposal->responded_at->format('d M Y, h:i A') }}</p>
            </div>
            
            <div class="details">
                <h3>Auto-Generated Documents:</h3>
                <p><strong>✅ Contract:</strong> {{ $contract->contract_number }}</p>
                <p><strong>✅ Invoice:</strong> {{ $invoice->invoice_number }}</p>
                <p><strong>✅ Customer Added to Portal:</strong> Yes</p>
            </div>
            
            <div class="details">
                <h3>Contract Details:</h3>
                <p><strong>Final Amount:</strong> {{ $contract->currency }} {{ number_format($contract->final_amount, 2) }}</p>
                <p><strong>Start Date:</strong> {{ \Carbon\Carbon::parse($contract->start_date)->format('d M Y') }}</p>
                <p><strong>Expected Completion:</strong> {{ \Carbon\Carbon::parse($contract->expected_completion_date)->format('d M Y') }}</p>
                <p><strong>Duration:</strong> {{ \Carbon\Carbon::parse($contract->start_date)->diffInDays(\Carbon\Carbon::parse($contract->expected_completion_date)) }} days</p>
            </div>
            
            <div class="details">
                <h3>Invoice Details:</h3>
                <p><strong>Invoice Number:</strong> {{ $invoice->invoice_number }}</p>
                <p><strong>Amount:</strong> {{ $invoice->currency }} {{ number_format($invoice->subtotal, 2) }}</p>
                <p><strong>GST (18%):</strong> {{ $invoice->currency }} {{ number_format($invoice->tax_total, 2) }}</p>
                <p><strong>Grand Total:</strong> {{ $invoice->currency }} {{ number_format($invoice->grand_total, 2) }}</p>
                <p><strong>Due Date:</strong> {{ $invoice->due_date->format('d M Y') }}</p>
            </div>
            
            <h3>✅ Next Steps:</h3>
            <ul>
                <li>Contract and invoice have been emailed to the customer</li>
                <li>Customer has been added to the Customer Management Portal</li>
                <li>Project can now be tracked and managed</li>
                <li>Team can begin project kickoff</li>
            </ul>
            
            <p style="margin-top: 30px; font-weight: bold;">
                All systems updated successfully. Time to start the project! 🚀
            </p>
        </div>
    </div>
</body>
</html>
