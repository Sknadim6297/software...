<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice {{ $invoice->invoice_number }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            color: #333;
            margin: 0;
            padding: 20px;
        }
        .header {
            display: table;
            width: 100%;
            margin-bottom: 30px;
        }
        .company-info {
            display: table-cell;
            width: 50%;
            vertical-align: top;
        }
        .invoice-info {
            display: table-cell;
            width: 50%;
            vertical-align: top;
            text-align: right;
        }
        .company-name {
            font-size: 24px;
            font-weight: bold;
            color: #007bff;
            margin-bottom: 10px;
        }
        .invoice-title {
            font-size: 28px;
            font-weight: bold;
            color: #333;
            margin-bottom: 10px;
        }
        .invoice-number {
            font-size: 16px;
            color: #666;
        }
        .bill-to {
            margin: 30px 0;
            padding: 15px;
            background-color: #f8f9fa;
            border-left: 4px solid #007bff;
        }
        .bill-to h3 {
            margin: 0 0 10px 0;
            color: #007bff;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        .items-table th,
        .items-table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }
        .items-table th {
            background-color: #007bff;
            color: white;
            font-weight: bold;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .total-section {
            width: 40%;
            margin-left: auto;
            margin-top: 20px;
        }
        .total-section table {
            margin: 0;
        }
        .total-section td {
            border: 1px solid #ddd;
            padding: 8px;
        }
        .total-section .total-row {
            background-color: #007bff;
            color: white;
            font-weight: bold;
            font-size: 14px;
        }
        .notes {
            margin-top: 30px;
            padding: 15px;
            background-color: #f8f9fa;
            border-radius: 5px;
        }
        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 10px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 15px;
        }
        .status {
            padding: 5px 10px;
            border-radius: 3px;
            font-size: 11px;
            font-weight: bold;
        }
        .status-paid {
            background-color: #d4edda;
            color: #155724;
        }
        .status-pending {
            background-color: #fff3cd;
            color: #856404;
        }
        .status-overdue {
            background-color: #f8d7da;
            color: #721c24;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="company-info">
            <div class="company-name">Konnectix Technologies</div>
            <div>Software Development & IT Solutions</div>
            <div>Mumbai, Maharashtra, India</div>
            <div>Email: info@konnectix.com</div>
            <div>Phone: +91 99999 99999</div>
        </div>
        <div class="invoice-info">
            <div class="invoice-title">INVOICE</div>
            <div class="invoice-number">#{{ $invoice->invoice_number }}</div>
            <div style="margin-top: 10px;">
                <div><strong>Date:</strong> {{ $invoice->invoice_date->format('d M Y') }}</div>
                @if($invoice->due_date)
                    <div><strong>Due Date:</strong> {{ $invoice->due_date->format('d M Y') }}</div>
                @endif
                <div style="margin-top: 5px;">
                    <span class="status status-{{ $invoice->status }}">
                        {{ ucfirst($invoice->status) }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div class="bill-to">
        <h3>Bill To:</h3>
        <div><strong>{{ $invoice->customer->customer_name }}</strong></div>
        @if($invoice->customer->company_name)
            <div>{{ $invoice->customer->company_name }}</div>
        @endif
        @if($invoice->customer->address)
            <div>{{ $invoice->customer->address }}</div>
        @endif
        @if($invoice->customer->city || $invoice->customer->state || $invoice->customer->postal_code)
            <div>
                {{ $invoice->customer->city }}
                @if($invoice->customer->state), {{ $invoice->customer->state }}@endif
                @if($invoice->customer->postal_code) - {{ $invoice->customer->postal_code }}@endif
            </div>
        @endif
        @if($invoice->customer->email)
            <div>Email: {{ $invoice->customer->email }}</div>
        @endif
        @if($invoice->customer->phone)
            <div>Phone: {{ $invoice->customer->phone }}</div>
        @endif
    </div>

    <table class="items-table">
        <thead>
            <tr>
                <th style="width: 5%;">#</th>
                <th style="width: 40%;">Description</th>
                <th style="width: 10%;">HSN/SAC</th>
                <th style="width: 8%;">Qty</th>
                <th style="width: 12%;">Rate</th>
                <th style="width: 10%;">Discount</th>
                <th style="width: 10%;">Tax</th>
                <th style="width: 15%;">Amount</th>
            </tr>
        </thead>
        <tbody>
            @foreach($invoice->items as $index => $item)
                @php
                    $itemTotal = $item->quantity * $item->rate;
                    $discountAmount = ($itemTotal * $item->discount_percentage) / 100;
                    $taxableAmount = $itemTotal - $discountAmount;
                    $cgstAmount = ($taxableAmount * $item->cgst_percentage) / 100;
                    $sgstAmount = ($taxableAmount * $item->sgst_percentage) / 100;
                    $igstAmount = ($taxableAmount * $item->igst_percentage) / 100;
                    $totalTax = $cgstAmount + $sgstAmount + $igstAmount;
                    $finalAmount = $taxableAmount + $totalTax;
                @endphp
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $item->product_description }}</td>
                    <td class="text-center">{{ $item->sac_hsn_code }}</td>
                    <td class="text-center">{{ $item->quantity }}</td>
                    <td class="text-right">₹{{ number_format($item->rate, 2) }}</td>
                    <td class="text-right">
                        {{ $item->discount_percentage }}%
                        @if($discountAmount > 0)
                            <br><small>(₹{{ number_format($discountAmount, 2) }})</small>
                        @endif
                    </td>
                    <td class="text-center">
                        @if($item->cgst_percentage > 0)
                            CGST: {{ $item->cgst_percentage }}%<br>
                            SGST: {{ $item->sgst_percentage }}%
                        @endif
                        @if($item->igst_percentage > 0)
                            IGST: {{ $item->igst_percentage }}%
                        @endif
                    </td>
                    <td class="text-right">₹{{ number_format($finalAmount, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="total-section">
        <table>
            <tr>
                <td><strong>Subtotal:</strong></td>
                <td class="text-right">₹{{ number_format($invoice->subtotal, 2) }}</td>
            </tr>
            @if($invoice->discount_amount > 0)
                <tr>
                    <td><strong>Discount:</strong></td>
                    <td class="text-right">₹{{ number_format($invoice->discount_amount, 2) }}</td>
                </tr>
            @endif
            <tr>
                <td><strong>Tax Amount:</strong></td>
                <td class="text-right">₹{{ number_format($invoice->tax_amount, 2) }}</td>
            </tr>
            <tr class="total-row">
                <td><strong>TOTAL AMOUNT:</strong></td>
                <td class="text-right">₹{{ number_format($invoice->total_amount, 2) }}</td>
            </tr>
        </table>
    </div>

    @if($invoice->notes)
        <div class="notes">
            <h4>Notes:</h4>
            <p>{{ $invoice->notes }}</p>
        </div>
    @endif

    <div class="footer">
        <p>Thank you for your business!</p>
        <p>This is a computer-generated invoice and does not require a signature.</p>
        <p><strong>Konnectix Technologies</strong> • Generated on {{ now()->format('d M Y, H:i') }}</p>
    </div>
</body>
</html>