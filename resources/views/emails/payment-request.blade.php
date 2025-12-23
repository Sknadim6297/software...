<!DOCTYPE html>
<html>
<head>
    <title>Payment Request</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background-color: #2196F3; color: white; padding: 20px; text-align: center; }
        .content { padding: 20px; background-color: #f9f9f9; }
        .details { background-color: white; padding: 15px; margin: 15px 0; border-left: 4px solid #2196F3; }
        .detail-row { margin: 10px 0; }
        .label { font-weight: bold; color: #555; }
        .footer { text-align: center; padding: 20px; font-size: 12px; color: #777; }
        .amount-highlight { font-size: 24px; color: #2196F3; font-weight: bold; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Payment Request - {{ $installment['type'] }} Installment</h2>
        </div>
        
        <div class="content">
            <p>Dear {{ $project->customer->name }},</p>
            
            <p>We hope your project <strong>{{ $project->project_name }}</strong> is progressing well.</p>
            
            <p>This is to inform you that the <strong>{{ $installment['type'] }} Installment</strong> payment is now due.</p>
            
            <div class="details">
                <div class="detail-row">
                    <span class="label">Project Name:</span> {{ $project->project_name }}
                </div>
                <div class="detail-row">
                    <span class="label">Project Type:</span> {{ $project->project_type }}
                </div>
                <div class="detail-row">
                    <span class="label">Installment Type:</span> {{ $installment['type'] }}
                </div>
                <div class="detail-row">
                    <span class="label">Amount Due:</span> <span class="amount-highlight">₹{{ number_format($installment['amount'], 2) }}</span>
                </div>
                <div class="detail-row">
                    <span class="label">Invoice Number:</span> #INV-{{ str_pad($invoice->id, 5, '0', STR_PAD_LEFT) }}
                </div>
                <div class="detail-row">
                    <span class="label">Due Date:</span> {{ $invoice->due_date->format('d M, Y') }}
                </div>
            </div>
            
            <p>Please find the invoice attached with this email. Kindly process the payment at your earliest convenience.</p>
            
            <p>For any queries, please feel free to contact your project coordinator.</p>
            
            <p>Thank you for your business!</p>
            
            <p>Best regards,<br>Konnectix Software Team</p>
        </div>
        
        <div class="footer">
            <p>This is an automated email. Please do not reply to this email.</p>
        </div>
    </div>
</body>
</html>
