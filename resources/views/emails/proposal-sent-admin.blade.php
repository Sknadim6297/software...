<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; background-color: #f5f5f5; margin: 0; padding: 20px; }
        .container { max-width: 640px; margin: 0 auto; background: white; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
        .header { background: linear-gradient(135deg, #17a2b8 0%, #20c0d7 100%); color: white; padding: 30px 20px; text-align: center; }
        .header h1 { margin: 0; font-size: 24px; font-weight: 600; }
        .content { padding: 30px 24px; background: white; }
        .alert { background: #e7f6fd; border-left: 4px solid #17a2b8; padding: 15px; margin-bottom: 20px; border-radius: 4px; }
        .details { background: #f8f9fa; padding: 20px; margin: 20px 0; border-radius: 6px; border: 1px solid #e9ecef; }
        .details p { margin: 8px 0; font-size: 14px; }
        .details strong { color: #17a2b8; display: inline-block; min-width: 140px; }
        .footer { background: #a9cdac; padding: 20px; text-align: center; color: #333; font-size: 13px; }
    </style>
</head>
<body>
    @php
        $meta = json_decode($proposal->metadata ?? '{}', true);
        $logoPath = public_path('logo.jpg');
        $logoData = '';
        if (file_exists($logoPath)) {
            $logoData = 'data:image/jpeg;base64,' . base64_encode(file_get_contents($logoPath));
        }
    @endphp
    <div class="container">
        <div class="header">
            @if($logoData)
                <img src="{{ $logoData }}" alt="Konnectix Technologies" style="height: 70px; max-width: 250px;">
            @else
                <h1 style="margin: 0; color: white;">Konnectix Technologies</h1>
            @endif
        </div>
        
        <div class="content">
            <div class="alert">
                <strong>✅ Proposal Sent Successfully</strong><br>
                <span style="font-size: 14px; font-weight: normal;">A proposal has been successfully sent to the customer with PDF attachment.</span>
            </div>
            
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
