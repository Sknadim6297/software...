<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; color: #333; line-height: 1.6; margin: 0; padding: 20px; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h1 { margin: 0; color: #0d6efd; }
        .meta { margin-bottom: 15px; font-size: 14px; }
        .section { margin-bottom: 20px; }
        .section h3 { margin-bottom: 8px; color: #0d6efd; }
        .details { background: #f7f9fc; padding: 12px; border-radius: 6px; font-size: 14px; }
        .content { margin-top: 10px; }
        .content h1, .content h2, .content h3, .content h4, .content h5 { color: #0d6efd; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #e5e7eb; padding: 8px; font-size: 14px; }
        th { background: #f1f5f9; text-align: left; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Proposal - Konnectix Technologies</h1>
        <p>Proposal ID: {{ $proposal->id }} | Date: {{ $proposal->created_at?->format('d M Y') }}</p>
    </div>

    <div class="section">
        <h3>Customer Details</h3>
        <div class="details">
            <div><strong>Name:</strong> {{ $proposal->customer_name }}</div>
            <div><strong>Email:</strong> {{ $proposal->customer_email }}</div>
            <div><strong>Phone:</strong> {{ $proposal->customer_phone }}</div>
        </div>
    @php
        $meta = json_decode($proposal->metadata ?? '{}', true);
    @endphp

    <div class="section">
        <h3>Proposal Details</h3>
        <div class="details">
            <div><strong>Project Type:</strong> {{ $proposal->project_type }}</div>
            @if($proposal->project_description)
                <div><strong>Description:</strong> {{ $proposal->project_description }}</div>
            @endif
            <div><strong>Amount:</strong> {{ $proposal->currency }} {{ number_format($proposal->proposed_amount, 2) }}</div>
            @if($proposal->estimated_days)
                <div><strong>Estimated Duration:</strong> {{ $proposal->estimated_days }} days</div>
            @endif
            @if($proposal->payment_terms)
                <div><strong>Payment Terms:</strong> {{ $proposal->payment_terms }}</div>
            @endif
        </div>
    </div>

    @if($proposal->project_type === 'Social Media Marketing' && is_array($meta) && !empty($meta))
    <div class="section">
        <h3>Social Media Plan Summary</h3>
        <div class="details">
            <div><strong>Company:</strong> {{ $meta['company_name'] ?? '-' }}</div>
            <div><strong>Platforms:</strong>
                @if(isset($meta['platforms']))
                    {{ implode(' / ', $meta['platforms']) }}
                @endif
            </div>
            <div><strong>Target Audience:</strong> {{ $meta['target_audience'] ?? '-' }}</div>
            <div><strong>Posters / Month:</strong> {{ $meta['posters_per_month'] ?? '-' }}</div>
            <div><strong>Reels / Week:</strong> {{ $meta['reels_per_week'] ?? '-' }}</div>
            <div><strong>Video Editing:</strong> {{ !empty($meta['includes_video_editing']) ? 'Included' : 'Not included' }}</div>
            <div><strong>Payment Mode:</strong> {{ isset($meta['payment_mode']) ? str_replace('_',' / ', strtoupper($meta['payment_mode'])) : '-' }}</div>
            <div><strong>GST:</strong> {{ $meta['gst_applicable'] ?? '-' }}</div>
            @if(!empty($meta['services']))
                <div><strong>Services:</strong> {{ implode(', ', array_map('ucwords', str_replace('_',' ', $meta['services']))) }}</div>
            @endif
            @if(!empty($meta['additional_notes']))
                <div><strong>Additional Notes:</strong> {{ $meta['additional_notes'] }}</div>
            @endif
        </div>
    </div>
    @endif

    <div class="section content">
        {!! $contentHtml !!}
    </div>
</body>
</html>
