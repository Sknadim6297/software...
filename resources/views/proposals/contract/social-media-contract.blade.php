@php
    // Get contract data for title
    $contractData = $contract_data ?? [];
    $metadata = json_decode($proposal->metadata, true) ?? [];
    $clientName = $metadata['company_name'] ?? $proposal->lead->customer_name ?? $proposal->customer_name ?? 'Client';
    
    // Get project type for dynamic title
    $projectType = $proposal->project_type ?? 'Social Media Marketing';
    $projectTypeTitles = [
        'Social Media Marketing' => 'SOCIAL MEDIA MARKETING',
        'YouTube Marketing' => 'YOUTUBE MARKETING',
        'Graphic / Poster Designing' => 'GRAPHIC DESIGNING',
        'Reels Design' => 'REELS DESIGN',
        'Digital Marketing' => 'DIGITAL MARKETING',
        'Website Development' => 'WEBSITE DEVELOPMENT',
        'Mobile App Development' => 'MOBILE APP DEVELOPMENT',
        'Software Development' => 'SOFTWARE DEVELOPMENT',
        'UI/UX Design' => 'UI/UX DESIGN',
        'ERP Software Development' => 'ERP SOFTWARE DEVELOPMENT'
    ];
    $projectTitle = $projectTypeTitles[$projectType] ?? 'SERVICE';
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $projectTitle }} CONTRACT FOR {{ strtoupper($clientName) }}</title>
    <link rel="stylesheet" href="{{ asset('assets/style.css') }}">
</head>
<body>
    <!-- Action Buttons -->
    <div class="action-buttons">
        <button onclick="window.print()" class="btn btn-primary">Print / Save as PDF</button>
        <a href="{{ route('proposals.show', $proposal->id) }}" class="btn btn-secondary">Back to Proposal</a>
    </div>

    <!-- PAGE 1 -->
    <div class="container">
        <div class="watermark">
            <img src="{{ asset('template/images/logo/logo_konnectix.webp') }}" alt="Watermark">
        </div>

        <!-- Header -->
        <div class="header-row">
            <div class="green-bars">
                <div class="green-bar"></div>
                <div class="green-bar"></div>
            </div>
            <div class="logo-section">
                <img src="{{ asset('template/images/logo/logo_konnectix.webp') }}" alt="Konnectix Technologies Logo" class="logo">
                <div class="cin-number">CIN NO:-U72900WB2021PTC243081</div>
            </div>
        </div>

        <div class="content">
            <div class="agreement-content">
                @php
                    // Get contract data
                    $contractData = $contract_data ?? [];
                    $finalAmount = $contractData['final_amount'] ?? $proposal->proposed_amount ?? 10000;
                    $startDate = $contractData['start_date'] ?? date('Y-m-d');
                    $agreementDuration = $contractData['agreement_duration'] ?? '6 months';

                    // Get scope of work and payment terms from proposal if available
                    $scopeOfWork = $metadata['scope_of_work'] ?? '';
                    $paymentTerms = $metadata['payment_terms'] ?? '';
                @endphp

                <h1 class="agreement-title">{{ $projectTitle }} AGREEMENT FOR {{ strtoupper($clientName) }}</h1>

                <p class="agreement-date">This {{ $projectTitle }} Agreement ("Agreement") is made and entered into on this {{ date('jS') }} day of {{ date('F') }}, {{ date('Y') }}, by and between:</p>

                <div class="parties-section">
                    <p class="between-label"><strong>Client:</strong> {{ $clientName }}</p>
                    <p class="party-info">(Hereinafter referred to as the 'Client')</p>

                    <p class="between-label"><strong>AND</strong></p>

                    <p class="party-info">
                        <strong>Konnectix Technologies Pvt. Ltd.,</strong><br>
                        Registered Office: [Insert Address]<br>
                        Contact: +91 91233 54003<br>
                        Email: info@konnectixtech.com<br>
                        Website: www.konnectixtech.com
                    </p>
                    <p class="party-info">(Hereinafter referred to as the 'Service Provider')</p>

                    <p class="parties-collective">
                        The Client and the Service Provider shall collectively be referred to as the "Parties."
                    </p>
                </div>

                <div class="agreement-section">
                    <h2 class="section-title">1. SCOPE OF WORK</h2>
                    @if(!empty($scopeOfWork))
                        <div class="section-content">
                            {!! nl2br(e(strip_tags(html_entity_decode($scopeOfWork)))) !!}
                        </div>
                    @else
                        <p class="section-content">
                            The Service Provider agrees to provide the following professional services for the Client's YouTube Podcast Channel and Social Media Platforms:
                        </p>
                        <ul class="scope-list">
                            <li>YouTube Video Editing (Weekly): 1 long podcast video per week with intro/outro, transitions, subtitles, and background music optimized for YouTube.</li>
                            <li>Reel Creation (Weekly): 5 short reels per week optimized for Instagram and Facebook.</li>
                            <li>Social Media Handling (Monthly): Managing Instagram and Facebook pages — post scheduling, caption writing, engagement tracking, and insights review.</li>
                            <li>Scripting & Creative Direction (Monthly): Scriptwriting assistance and ideation sessions for trending and brand-aligned topics.</li>
                            <li>Paid Ad Marketing (Monthly): Ad campaign setup, creative support, and performance monitoring (ad spend to be provided separately by the Client).</li>
                        </ul>
                        <p class="section-content">
                            Any features or changes beyond the above scope shall be considered additional work and charged separately upon mutual agreement.
                        </p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer-wrapper">
            <div class="footer-top-bar"></div>
            <div class="footer">
                <div class="footer-content">
                    <div class="footer-info">
                        <span><span class="footer-icon">☎</span> 7003228913 / 9123354003</span>
                        <span><span class="footer-icon">✉</span> info@konnectixtech.com</span>
                        <span><span class="footer-icon">📍</span> Dum Dum, Kolkata - 700 074</span>
                        <span><span class="footer-icon">🌐</span> www.konnectixtech.com</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Page 2 starts here -->
    <div class="page-break-section">
        <div class="page-watermark">
            <img src="{{ asset('template/images/logo/logo_konnectix.webp') }}" alt="Watermark">
        </div>

        <!-- Inline Header -->
        <div class="header-row">
            <div class="green-bars">
                <div class="green-bar"></div>
                <div class="green-bar"></div>
            </div>
            <div class="logo-section">
                <img src="{{ asset('template/images/logo/logo_konnectix.webp') }}" alt="Konnectix Technologies Logo" class="logo">
                <div class="cin-number">CIN NO:-U72900WB2021PTC243081</div>
            </div>
        </div>

        <div class="page-content">
            <div class="agreement-content">
                <div class="agreement-section">
                    <h2 class="section-title">2. PAYMENT TERMS</h2>
                    @if(!empty($paymentTerms))
                        <div class="section-content">
                            {!! nl2br(e(strip_tags(html_entity_decode($paymentTerms)))) !!}
                        </div>
                    @else
                        <ul class="section-list">
                            <li><strong>Total Monthly Cost:</strong> ₹{{ number_format($finalAmount) }} + GST per month</li>
                            <li><strong>Total Contract Value (6 Months):</strong> ₹{{ number_format($finalAmount * 6) }} + GST</li>
                            <li><strong>Payment Schedule:</strong> 100% advance payment before commencement of each month's work</li>
                            <li><strong>Ad Budget:</strong> To be provided separately by the Client</li>
                        </ul>
                        <p class="section-content">
                            Work will begin only after receipt of the first month's advance payment.
                        </p>
                    @endif
                </div>

                <div class="agreement-section">
                    <h2 class="section-title">3. DURATION & RENEWAL</h2>
                    <p class="section-content">
                        <strong>Agreement Duration:</strong> {{ $agreementDuration }}
                    </p>
                    <p class="section-content">
                        This Agreement shall remain valid for a period of {{ $agreementDuration }} from the commencement date. Upon mutual consent, the contract may be renewed or extended in writing.
                    </p>
                </div>

                <div class="agreement-section">
                    <h2 class="section-title">4. OWNERSHIP & INTELLECTUAL PROPERTY RIGHTS</h2>
                    <ul class="section-list">
                        <li>Upon full payment, all final edited videos, creatives, and scripts produced under this Agreement shall become the property of the Client</li>
                        <li>Konnectix Technologies Pvt. Ltd. retains the right to showcase completed work in its portfolio or case studies for marketing purposes</li>
                        <li>Raw project files and editable source materials remain the property of the Service Provider unless otherwise agreed in writing</li>
                    </ul>
                </div>

                <div class="agreement-section">
                    <h2 class="section-title">5. REVISIONS & MODIFICATIONS</h2>
                    <ul class="section-list">
                        <li>The Client is entitled to two (2) rounds of changes or revisions per deliverable at no additional cost</li>
                        <li>Any further modifications or additional editing requests beyond the two complimentary changes shall be chargeable</li>
                        <li>Revision requests must be communicated within 48 hours of delivery of the draft to maintain workflow</li>
                    </ul>
                </div>

                <div class="agreement-section">
                    <h2 class="section-title">6. CONFIDENTIALITY</h2>
                    <p class="section-content">
                        Both parties agree to maintain strict confidentiality regarding all creative materials, project data, credentials, scripts, business strategies, and other sensitive information shared during the course of this collaboration. No information or material shall be disclosed to any third party without prior written consent. This confidentiality obligation remains effective during the contract and for 12 months after termination or completion.
                    </p>
                </div>
            </div>
        </div>

        <!-- Inline Footer -->
        <div class="footer-wrapper">
            <div class="footer-top-bar"></div>
            <div class="footer">
                <div class="footer-content">
                    <div class="footer-info">
                        <span><span class="footer-icon">☎</span> 7003228913 / 9123354003</span>
                        <span><span class="footer-icon">✉</span> info@konnectixtech.com</span>
                        <span><span class="footer-icon">📍</span> Dum Dum, Kolkata - 700 074</span>
                        <span><span class="footer-icon">🌐</span> www.konnectixtech.com</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Page 3 starts here -->
    <div class="page-break-section">
        <div class="page-watermark">
            <img src="{{ asset('template/images/logo/logo_konnectix.webp') }}" alt="Watermark">
        </div>

        <!-- Inline Header -->
        <div class="header-row">
            <div class="green-bars">
                <div class="green-bar"></div>
                <div class="green-bar"></div>
            </div>
            <div class="logo-section">
                <img src="{{ asset('template/images/logo/logo_konnectix.webp') }}" alt="Konnectix Technologies Logo" class="logo">
                <div class="cin-number">CIN NO:-U72900WB2021PTC243081</div>
            </div>
        </div>

        <div class="page-content">
            <div class="agreement-content">
                <div class="agreement-section">
                    <h2 class="section-title">7. TERMINATION</h2>
                    <ul class="section-list">
                        <li>Either party may terminate this Agreement by providing a 30-day written notice</li>
                        <li>The Client shall compensate for all completed and ongoing work up to the termination date</li>
                        <li>The Service Provider reserves the right to suspend or terminate services immediately in cases of breach of confidentiality, misuse of brand assets, or non-payment</li>
                    </ul>
                </div>

                <div class="agreement-section">
                    <h2 class="section-title">8. LIMITATION OF LIABILITY</h2>
                    <p class="section-content">
                        The Service Provider shall not be liable for any indirect, incidental, or consequential damages resulting from the use of the content or social media-related issues such as account restrictions, algorithm changes, or ad disapprovals.
                    </p>
                </div>

                <div class="agreement-section">
                    <h2 class="section-title">9. COMMUNICATION</h2>
                    <p class="section-content">
                        All project communications, feedback, deliverables, and approvals shall be exchanged via email or WhatsApp for clarity and record maintenance.
                    </p>
                </div>

                <div class="agreement-section">
                    <h2 class="section-title">10. GOVERNING LAW</h2>
                    <p class="section-content">
                        This Agreement shall be governed by and construed in accordance with the laws of India, and all disputes shall fall under the jurisdiction of the courts of Kolkata, West Bengal.
                    </p>
                </div>

                <div class="agreement-section">
                    <h2 class="section-title">11. ENTIRE AGREEMENT</h2>
                    <p class="section-content">
                        This Agreement constitutes the entire understanding between the Parties and supersedes all prior discussions or communications.
                    </p>
                </div>

                <div class="agreement-section signature-section-wrapper">
                    <h2 class="section-title">12. ACCEPTANCE & SIGNATURES</h2>
                    <p class="section-content">
                        By signing below, both Parties agree to the terms and conditions stated herein.
                    </p>

                    <div class="signature-section">
                        <div class="signature-block">
                            <p class="signature-label">For {{ $clientName }} (Client)</p>
                            <p class="signature-detail">Name: {{ $clientName }}</p>
                            <p class="signature-detail">Signature:</p>
                            <p class="signature-detail">Date:</p>
                        </div>

                        <div class="signature-block">
                            <p class="signature-label">For Konnectix Technologies Pvt. Ltd. (Service Provider)</p>
                            <p class="signature-detail">Name: Ishita Banerjee</p>
                            <p class="signature-detail">Designation: Business Development Manager</p>
                            <p class="signature-detail">Signature:</p>
                            <p class="signature-detail">Date: {{ date('d.m.Y') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Inline Footer -->
        <div class="footer-wrapper">
            <div class="footer-top-bar"></div>
            <div class="footer">
                <div class="footer-content">
                    <div class="footer-info">
                        <span><span class="footer-icon">☎</span> 7003228913 / 9123354003</span>
                        <span><span class="footer-icon">✉</span> info@konnectixtech.com</span>
                        <span><span class="footer-icon">📍</span> Dum Dum, Kolkata - 700 074</span>
                        <span><span class="footer-icon">🌐</span> www.konnectixtech.com</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>
</html>