<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 640px; margin: 0 auto; padding: 20px; }
        .header { background: #17a2b8; color: white; padding: 20px; text-align: center; }
        .content { background: #f8f9fa; padding: 24px; margin-top: 20px; }
        .details { background: white; padding: 16px; margin: 15px 0; border-left: 4px solid #17a2b8; }
        .grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 10px; }
        .pill { display: inline-block; padding: 6px 10px; background: #e0f2fe; color: #0ea5e9; border-radius: 999px; margin: 2px 0; font-size: 12px; }
        .small { font-size: 13px; color: #555; }
    </style>
</head>
<body>
    @php
        $meta = json_decode($proposal->metadata ?? '{}', true);
    @endphp
    <div class="container">
        <div class="header">
            <h1>New Proposal Sent</h1>
        </div>
        
        <div class="content">
            <h2>Proposal Notification</h2>
            
            <p>A new proposal has been sent to a customer.</p>
            
            <div class="details">
                <h3>Proposal Details:</h3>
                <p><strong>Proposal ID:</strong> #{{ $proposal->id }}</p>
                <p><strong>Customer Name:</strong> {{ $proposal->customer_name }}</p>
                <p><strong>Customer Email:</strong> {{ $proposal->customer_email }}</p>
                <p><strong>Customer Phone:</strong> {{ $proposal->customer_phone }}</p>
                <p><strong>Project Type:</strong> {{ $proposal->project_type }}</p>
                <p><strong>Proposed Amount:</strong> {{ $proposal->currency }} {{ number_format($proposal->proposed_amount, 2) }}</p>
                <p><strong>Lead Type:</strong> {{ ucfirst($proposal->lead_type) }}</p>
                <p><strong>Sent At:</strong> {{ $proposal->sent_at->format('d M Y, h:i A') }}</p>
            </div>

            @if($proposal->project_type === 'Social Media Marketing' && is_array($meta) && !empty($meta))
                <h3>Social Media Plan Summary</h3>
                <div class="grid">
                    <div><strong>Company:</strong><br>{{ $meta['company_name'] ?? '-' }}</div>
                    <div><strong>Platforms:</strong><br>
                        @if(isset($meta['platforms']))
                            @foreach($meta['platforms'] as $p)
                                <span class="pill">{{ $p }}</span>
                            @endforeach
                        @endif
                    </div>
                    <div><strong>Target Audience:</strong><br>{{ $meta['target_audience'] ?? '-' }}</div>
                    <div><strong>Posters / Month:</strong><br>{{ $meta['posters_per_month'] ?? '-' }}</div>
                    <div><strong>Reels / Week:</strong><br>{{ $meta['reels_per_week'] ?? '-' }}</div>
                    <div><strong>Video Editing:</strong><br>{{ !empty($meta['includes_video_editing']) ? 'Included' : 'Not included' }}</div>
                    <div><strong>Payment Mode:</strong><br>{{ isset($meta['payment_mode']) ? str_replace('_',' / ', strtoupper($meta['payment_mode'])) : '-' }}</div>
                    <div><strong>GST:</strong><br>{{ $meta['gst_applicable'] ?? '-' }}</div>
                </div>
                @if(!empty($meta['services']))
                    <p class="small" style="margin-top:8px;"><strong>Services:</strong>
                        {{ implode(', ', array_map('ucwords', str_replace('_',' ', $meta['services']))) }}
                    </p>
                @endif
                @if(!empty($meta['additional_notes']))
                    <p class="small"><strong>Additional Notes:</strong> {{ $meta['additional_notes'] }}</p>
                @endif
            @endif
            
            <p>The proposal has been successfully delivered to the customer's email.</p>
        </div>
    </div>
</body>
</html>
