<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Money Receipt {{ $invoice->invoice_number }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            color: #000;
            margin: 0;
            padding: 15px;
        }
        .receipt-title {
            text-align: center;
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 15px;
            text-decoration: underline;
        }
        .company-header {
            text-align: center;
            margin-bottom: 10px;
        }
        .company-name {
            font-size: 16px;
            font-weight: bold;
        }
        .company-details {
            font-size: 10px;
            line-height: 1.4;
        }
        .section-row {
            display: table;
            width: 100%;
            margin-bottom: 15px;
        }
        .section-col {
            display: table-cell;
            width: 50%;
            vertical-align: top;
            padding: 10px;
            border: 1px solid #000;
        }
        .section-title {
            font-weight: bold;
            margin-bottom: 8px;
            font-size: 11px;
        }
        .detail-line {
            margin: 3px 0;
            font-size: 10px;
        }
        .invoice-info {
            margin-bottom: 15px;
            font-size: 10px;
        }
        .invoice-info-row {
            display: table;
            width: 100%;
        }
        .invoice-info-col {
            display: table-cell;
            width: 50%;
            padding: 3px 10px;
        }
        table.items-table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
            font-size: 9px;
        }
        .items-table th,
        .items-table td {
            border: 1px solid #000;
            padding: 5px;
            text-align: center;
        }
        .items-table th {
            background-color: #f0f0f0;
            font-weight: bold;
            font-size: 9px;
        }
        .items-table td {
            font-size: 9px;
        }
        .text-left {
            text-align: left !important;
        }
        .text-right {
            text-align: right !important;
        }
        .text-center {
            text-align: center !important;
        }
        .total-words {
            margin: 10px 0;
            padding: 8px;
            border: 1px solid #000;
            font-weight: bold;
            font-size: 10px;
        }
        .footer-section {
            margin-top: 20px;
            display: table;
            width: 100%;
        }
        .footer-col {
            display: table-cell;
            width: 50%;
            vertical-align: top;
            font-size: 10px;
        }
        .bank-details {
            margin-top: 10px;
            padding: 8px;
            border: 1px solid #000;
        }
        .signature-section {
            margin-top: 40px;
            text-align: right;
        }
        .note {
            margin-top: 10px;
            font-size: 9px;
            font-style: italic;
        }
        .bold {
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="receipt-title">MONEY RECEIPT</div>
    
    <div class="company-header">
        <div class="company-name">Konnectix Technologies Pvt.Ltd.</div>
        <div class="company-details">
            Regd. Office : 449, SB SARANI<br>
            Rajpur Sonarpur<br>
            Kolkata 7000149, West Bengal<br>
            India<br>
            State Code-19 Name: West Bengal
        </div>
    </div>
    
    <div class="section-row">
        <div class="section-col">
            <div class="section-title">Bill To (Details Of Receiver)</div>
            <div class="detail-line"><strong>{{ $invoice->customer->customer_name ?? 'N/A' }}</strong></div>
            @if($invoice->customer && $invoice->customer->company_name)
                <div class="detail-line">{{ $invoice->customer->company_name }}</div>
            @endif
            @if($invoice->customer && $invoice->customer->address)
                <div class="detail-line">{{ $invoice->customer->address }}</div>
            @endif
            @if($invoice->customer)
                <div class="detail-line">
                    {{ $invoice->customer->city }}
                    @if($invoice->customer->state), {{ $invoice->customer->state }}@endif
                    @if($invoice->customer->postal_code)-{{ $invoice->customer->postal_code }}@endif
                </div>
            @endif
            @if($invoice->customer_gstin)
                <div class="detail-line"><strong>GSTIN No: {{ $invoice->customer_gstin }}</strong></div>
            @else
                <div class="detail-line"><strong>GSTIN No: NA</strong></div>
            @endif
            @if($invoice->customer_state_code || $invoice->customer_state_name)
                <div class="detail-line">
                    <strong>STATE CODE/Name : {{ $invoice->customer_state_code ?? '' }}/{{ $invoice->customer_state_name ?? '' }}</strong>
                </div>
            @else
                <div class="detail-line"><strong>STATE CODE/Name :</strong></div>
            @endif
        </div>
        <div class="section-col">
            <div class="detail-line"><strong>Money Receipt Number :</strong> {{ $invoice->invoice_number }}</div>
            <div class="detail-line"><strong>Date :</strong> {{ $invoice->invoice_date->format('Y-m-d') }}</div>
            @if($invoice->invoice_ref_no)
                <div class="detail-line"><strong>Money Receipt Ref No. :</strong> {{ $invoice->invoice_ref_no }}</div>
            @endif
            @if($invoice->invoice_ref_date)
                <div class="detail-line"><strong>Money Receipt Ref Date :</strong> {{ $invoice->invoice_ref_date->format('Y-m-d') }}</div>
            @endif
            @if($invoice->remarks)
                <div class="detail-line"><strong>Remarks :</strong> {{ $invoice->remarks }}</div>
            @endif
            <div class="detail-line">
                <strong>Status:</strong> 
                @if($invoice->payment_status == 'paid')
                    <span style="color: green;">Paid</span>
                @elseif($invoice->payment_status == 'partially_paid')
                    <span style="color: orange;">Partially Paid</span>
                @else
                    <span style="color: red;">Unpaid</span>
                @endif
            </div>
        </div>
    </div>
    
    <table class="items-table">
        <thead>
            <tr>
                <th style="width: 8%;">Sl No.</th>
                <th style="width: 47%;">Description Of Goods & Services</th>
                <th style="width: 10%;">Quantity</th>
                <th style="width: 15%;">Rate Per<br>Service</th>
                <th style="width: 10%;">Discount</th>
                <th style="width: 10%;">Total</th>
            </tr>
        </thead>
        <tbody>
            @php
                $totalQuantity = 0;
                $totalRate = 0;
                $totalDiscount = 0;
                $grandTotal = 0;
            @endphp
            @foreach($invoice->items as $index => $item)
                @php
                    $itemTotal = $item->quantity * $item->rate;
                    $discountAmount = ($itemTotal * ($item->discount_percentage ?? 0)) / 100;
                    $finalAmount = $itemTotal - $discountAmount;
                    
                    $totalQuantity += $item->quantity;
                    $totalRate += $item->rate;
                    $totalDiscount += $discountAmount;
                    $grandTotal += $finalAmount;
                @endphp
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td class="text-left">{{ $item->product_description }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td class="text-right">{{ number_format($item->rate, 0) }}</td>
                    <td class="text-right">{{ number_format($discountAmount, 0) }}</td>
                    <td class="text-right">{{ number_format($finalAmount, 0) }}</td>
                </tr>
            @endforeach
            <tr style="background-color: #f0f0f0; font-weight: bold;">
                <td colspan="2" class="text-left">Sub Total:</td>
                <td>{{ $totalQuantity }}</td>
                <td class="text-right">{{ number_format($totalRate, 2) }}</td>
                <td class="text-right">{{ number_format($totalDiscount, 2) }}</td>
                <td class="text-right">{{ number_format($grandTotal, 2) }}</td>
            </tr>
        </tbody>
    </table>
    
    <div class="total-words">
        <strong>Total Money Receipt Value (In Words):</strong> 
        {{ \App\Models\Invoice::numberToWords($grandTotal) }}
    </div>
    
    <div class="invoice-info">
        <div class="invoice-info-row">
            <div class="invoice-info-col"><strong>TCS :</strong> {{ number_format($invoice->tcs_amount ?? 0, 2) }}</div>
            <div class="invoice-info-col"></div>
        </div>
        <div class="invoice-info-row">
            <div class="invoice-info-col"><strong>Round Off :</strong> {{ number_format($invoice->round_off ?? 0, 0) }}</div>
            <div class="invoice-info-col"></div>
        </div>
        <div class="invoice-info-row">
            <div class="invoice-info-col"><strong>Total Paid Amount :</strong> 0</div>
            <div class="invoice-info-col"></div>
        </div>
        <div class="invoice-info-row">
            <div class="invoice-info-col"><strong>Money Receipt Value :</strong> {{ number_format($grandTotal + ($invoice->round_off ?? 0), 0) }}</div>
            <div class="invoice-info-col"></div>
        </div>
    </div>
    
    <div class="footer-section">
        <div class="footer-col">
            <div class="bank-details">
                <div class="bold">Our Bank Details</div>
                <div class="detail-line"><strong>NAME :</strong> Ishita Banerjee</div>
                <div class="detail-line"><strong>ACCOUNT NUMBER :</strong> 250512010003529</div>
                <div class="detail-line"><strong>IFSC CODE :</strong> UBIN0825051</div>
                <div class="detail-line"><strong>BANK NAME :</strong> Union Bank Of India</div>
            </div>
        </div>
        <div class="footer-col" style="text-align: right;">
            <div class="detail-line"><strong>CIN NO:</strong> U72900WB2021PTC243081</div>
            <div class="signature-section">
                <div class="bold">For KONNECTIX TECHNOLOGIES PVT.LTD.</div>
                <div style="margin-top: 40px; border-top: 1px solid #000; display: inline-block; padding-top: 5px;">
                    Authorized Signatory
                </div>
            </div>
        </div>
    </div>
    
    <div style="margin-top: 10px; text-align: left;">
        <div class="detail-line"><strong>PAN NO:</strong> AAICK6076B</div>
    </div>
    
    <div class="note">
        This is a system generated invoice stamp and seal not required.
    </div>
</body>
</html>
