<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: 'Poppins', Arial, sans-serif; line-height: 1.6; color: #333; background-color: #f5f5f5; margin: 0; padding: 20px; }
        .container { max-width: 640px; margin: 0 auto; background: white; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
        .header { background: linear-gradient(135deg, #2d7a32 0%, #33973a 100%); color: white; padding: 30px 20px; text-align: center; }
        .header img { height: 70px; max-width: 250px; }
        .content { padding: 30px 24px; background: white; }
        .greeting { font-size: 16px; color: #2d7a32; font-weight: 600; margin-bottom: 20px; }
        .message { font-size: 15px; line-height: 1.8; color: #333; margin-bottom: 20px; }
        .highlight-box { background: #f0f8f1; border-left: 4px solid #33973a; padding: 20px; margin: 20px 0; border-radius: 4px; }
        .project-info { margin: 20px 0; }
        .project-info p { margin: 8px 0; font-size: 14px; }
        .project-info strong { color: #2d7a32; display: inline-block; min-width: 140px; }
        .cta-button { display: inline-block; background: #33973a; color: white; padding: 12px 30px; text-decoration: none; border-radius: 5px; font-weight: 600; margin: 20px 0; }
        .footer { background: #a9cdac; padding: 20px; text-align: center; color: #333; font-size: 13px; }
        .footer-contact { margin: 10px 0; }
        .footer-contact span { display: inline-block; margin: 0 10px; }
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
                <img src="{{ $logoData }}" alt="Konnectix Technologies">
            @else
                <h1 style="margin: 0; color: white;">Konnectix Technologies</h1>
            @endif
        </div>
        
        <div class="content">
            <div class="greeting">Dear {{ $proposal->customer_name }},</div>
            
            <div class="message">
                <p>Thank you for considering <strong>Konnectix Technologies</strong> for your <strong>{{ $proposal->project_type }}</strong> project.</p>
                
                <p>We are excited to present our comprehensive proposal tailored specifically to meet your business requirements. Our team has carefully analyzed your needs and crafted a solution that will deliver exceptional value.</p>
            </div>
            
            <div class="highlight-box">
                <h3 style="margin-top: 0; color: #2d7a32;">Proposal Highlights</h3>
                <div class="project-info">
                    <p><strong>Project Type:</strong> {{ $proposal->project_type }}</p>
                    <p><strong>Proposed Investment:</strong> {{ $proposal->currency }} {{ number_format($proposal->proposed_amount, 2) }}</p>
                    @if($proposal->estimated_days)
                        <p><strong>Timeline:</strong> {{ $proposal->estimated_days }} days</p>
                    @endif
                    @if($proposal->payment_terms)
                        <p><strong>Payment Terms:</strong> {{ $proposal->payment_terms }}</p>
                    @endif
                </div>
            </div>
            
            <div class="message">
                <p><strong>📎 Attached:</strong> Please find the complete detailed proposal attached as a PDF document. The document includes:</p>
                <ul style="margin: 10px 0; padding-left: 25px;">
                    <li>Comprehensive project scope and deliverables</li>
                    <li>Detailed pricing breakdown</li>
                    <li>Timeline and milestones</li>
                    <li>Terms and conditions</li>
                </ul>
            </div>
            
            <div class="message">
                <p>We would be delighted to discuss this proposal with you in detail and address any questions you may have.</p>
                
                <p><strong>Next Steps:</strong></p>
                <ol style="margin: 10px 0; padding-left: 25px;">
                    <li>Review the attached proposal document</li>
                    <li>Feel free to reach out with any questions or clarifications</li>
                    <li>We're ready to schedule a call to discuss further</li>
                </ol>
            </div>
            
            <div style="margin: 30px 0; text-align: center;">
                <p style="font-size: 14px; color: #666; margin-bottom: 15px;">We look forward to partnering with you!</p>
            </div>
            
            <div style="border-top: 2px solid #f0f0f0; padding-top: 20px; margin-top: 30px;">
                <p style="margin: 5px 0;"><strong style="color: #2d7a32;">Best Regards,</strong></p>
                <p style="margin: 5px 0;">Konnectix Technologies Team</p>
                <p style="margin: 5px 0; font-size: 14px; color: #666;">📧 bdm.konnectixtech@gmail.com</p>
                <p style="margin: 5px 0; font-size: 14px; color: #666;">📞 7003228913 / 9123354003</p>
            </div>
        </div>
        
        <div class="footer">
            <div class="footer-contact">
                <span>📞 7003228913 / 9123354003</span>
                <span>✉ info@konnectixtech.com</span>
            </div>
            <div class="footer-contact">
                <span>📍 Dum Dum, Kolkata - 700 074</span>
                <span>🌐 www.konnectixtech.com</span>
            </div>
            <p style="margin-top: 15px; font-size: 12px;">&copy; {{ date('Y') }} Konnectix Technologies Pvt. Ltd. All rights reserved.<br>CIN NO: U72900WB2021PTC243081</p>
        </div>
    </div>
</body>
</html>
