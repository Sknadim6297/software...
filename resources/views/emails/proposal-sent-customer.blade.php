<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 640px; margin: 0 auto; padding: 20px; }
        .header { background: #007bff; color: white; padding: 20px; text-align: center; }
        .content { background: #f8f9fa; padding: 24px; margin-top: 20px; }
        .footer { text-align: center; padding: 16px; color: #666; font-size: 12px; }
        .details { background: white; padding: 16px; margin: 15px 0; border-left: 4px solid #007bff; }
        .grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 10px; }
        .pill { display: inline-block; padding: 6px 10px; background: #eef2ff; color: #1d4ed8; border-radius: 999px; margin: 2px 0; font-size: 12px; }
        .small { font-size: 13px; color: #555; }
    </style>
</head>
<body>
    @php
        $meta = json_decode($proposal->metadata ?? '{}', true);
    @endphp
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
            @if($proposal->project_type === 'Social Media Marketing' && is_array($meta) && !empty($meta))
                <h3>Social Media Plan (Dynamic)</h3>
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

            @if($proposal->project_description)
                <h3>Project Description:</h3>
                <p>{{ $proposal->project_description }}</p>
            @endif
            
            <h3>Full Proposal:</h3>
            <div style="background: white; padding: 18px; font-family: inherit;">
                {!! \Illuminate\Support\Str::markdown($proposal->proposal_content) !!}
            </div>
            <p class="small" style="margin-top:12px;">A PDF copy is attached for your convenience.</p>
            
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
