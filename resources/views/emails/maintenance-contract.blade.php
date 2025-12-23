<!DOCTYPE html>
<html>
<head>
    <title>Maintenance Contract</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background-color: #FF9800; color: white; padding: 20px; text-align: center; }
        .content { padding: 20px; background-color: #f9f9f9; }
        .details { background-color: white; padding: 15px; margin: 15px 0; border-left: 4px solid #FF9800; }
        .detail-row { margin: 10px 0; }
        .label { font-weight: bold; color: #555; }
        .footer { text-align: center; padding: 20px; font-size: 12px; color: #777; }
        .badge { display: inline-block; padding: 5px 10px; border-radius: 3px; font-weight: bold; }
        .badge-free { background-color: #4CAF50; color: white; }
        .badge-chargeable { background-color: #2196F3; color: white; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Maintenance Contract Created</h2>
        </div>
        
        <div class="content">
            <p>Dear {{ $maintenanceContract->customer->name }},</p>
            
            <p>Congratulations! Your project <strong>{{ $maintenanceContract->project->project_name }}</strong> has been completed successfully.</p>
            
            <p>We have created a maintenance contract for your project with the following details:</p>
            
            <div class="details">
                <div class="detail-row">
                    <span class="label">Project Name:</span> {{ $maintenanceContract->project->project_name }}
                </div>
                <div class="detail-row">
                    <span class="label">Contract Type:</span> 
                    <span class="badge badge-{{ strtolower($maintenanceContract->contract_type) }}">
                        {{ $maintenanceContract->contract_type }}
                    </span>
                </div>
                
                @if($maintenanceContract->contract_type === 'Free')
                <div class="detail-row">
                    <span class="label">Free Maintenance Period:</span> {{ $maintenanceContract->free_months }} months
                </div>
                @else
                <div class="detail-row">
                    <span class="label">Maintenance Charges:</span> ₹{{ number_format($maintenanceContract->charges, 2) }}
                </div>
                <div class="detail-row">
                    <span class="label">Charge Frequency:</span> {{ $maintenanceContract->charge_frequency }}
                </div>
                @if($invoice)
                <div class="detail-row">
                    <span class="label">Invoice Number:</span> #INV-{{ str_pad($invoice->id, 5, '0', STR_PAD_LEFT) }}
                </div>
                @endif
                @endif
                
                <div class="detail-row">
                    <span class="label">Contract Start Date:</span> {{ $maintenanceContract->contract_start_date->format('d M, Y') }}
                </div>
                @if($maintenanceContract->contract_end_date)
                <div class="detail-row">
                    <span class="label">Contract End Date:</span> {{ $maintenanceContract->contract_end_date->format('d M, Y') }}
                </div>
                @endif
                
                @if($maintenanceContract->domain_purchase_date)
                <div class="detail-row">
                    <span class="label">Domain Renewal Date:</span> {{ $maintenanceContract->domain_renewal_date->format('d M, Y') }}
                </div>
                @endif
                
                @if($maintenanceContract->hosting_purchase_date)
                <div class="detail-row">
                    <span class="label">Hosting Renewal Date:</span> {{ $maintenanceContract->hosting_renewal_date->format('d M, Y') }}
                </div>
                @endif
            </div>
            
            @if($maintenanceContract->contract_type === 'Chargeable' && $invoice)
            <p>Please find the attached invoice for the maintenance charges. Kindly process the payment at your earliest convenience.</p>
            @endif
            
            <p>We look forward to continuing our partnership with you!</p>
            
            <p>Best regards,<br>Konnectix Software Team</p>
        </div>
        
        <div class="footer">
            <p>This is an automated email. Please do not reply to this email.</p>
        </div>
    </div>
</body>
</html>
