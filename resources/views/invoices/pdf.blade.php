<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoices Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #007bff;
            padding-bottom: 10px;
        }
        .company-name {
            font-size: 24px;
            font-weight: bold;
            color: #007bff;
            margin-bottom: 5px;
        }
        .report-title {
            font-size: 18px;
            font-weight: bold;
            margin-top: 10px;
        }
        .report-date {
            font-size: 12px;
            color: #666;
            margin-top: 5px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f8f9fa;
            font-weight: bold;
            font-size: 11px;
        }
        td {
            font-size: 10px;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .total-row {
            background-color: #f8f9fa;
            font-weight: bold;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
        .status-paid {
            background-color: #d4edda;
            color: #155724;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 9px;
        }
        .status-pending {
            background-color: #fff3cd;
            color: #856404;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 9px;
        }
        .status-overdue {
            background-color: #f8d7da;
            color: #721c24;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 9px;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="company-name">Konnectix Technologies</div>
        <div class="report-title">Invoices Report</div>
        <div class="report-date">Generated on {{ date('d M Y, H:i') }}</div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Invoice #</th>
                <th>Customer</th>
                <th>Type</th>
                <th>Date</th>
                <th>Status</th>
                <th class="text-right">Subtotal</th>
                <th class="text-right">Tax</th>
                <th class="text-right">Total</th>
            </tr>
        </thead>
        <tbody>
            @php
                $totalSubtotal = 0;
                $totalTax = 0;
                $totalAmount = 0;
            @endphp
            
            @foreach($invoices as $invoice)
                @php
                    $totalSubtotal += $invoice->subtotal;
                    $totalTax += $invoice->tax_amount;
                    $totalAmount += $invoice->total_amount;
                @endphp
                <tr>
                    <td>{{ $invoice->invoice_number }}</td>
                    <td>
                        {{ $invoice->customer->customer_name ?? 'N/A' }}
                        @if($invoice->customer->company_name)
                            <br><small>{{ $invoice->customer->company_name }}</small>
                        @endif
                    </td>
                    <td class="text-center">{{ ucfirst($invoice->invoice_type) }}</td>
                    <td class="text-center">{{ $invoice->invoice_date->format('d/m/Y') }}</td>
                    <td class="text-center">
                        <span class="status-{{ $invoice->status }}">
                            {{ ucfirst($invoice->status) }}
                        </span>
                    </td>
                    <td class="text-right">₹{{ number_format($invoice->subtotal, 2) }}</td>
                    <td class="text-right">₹{{ number_format($invoice->tax_amount, 2) }}</td>
                    <td class="text-right">₹{{ number_format($invoice->total_amount, 2) }}</td>
                </tr>
            @endforeach
            
            <tr class="total-row">
                <td colspan="5" class="text-right"><strong>TOTALS:</strong></td>
                <td class="text-right"><strong>₹{{ number_format($totalSubtotal, 2) }}</strong></td>
                <td class="text-right"><strong>₹{{ number_format($totalTax, 2) }}</strong></td>
                <td class="text-right"><strong>₹{{ number_format($totalAmount, 2) }}</strong></td>
            </tr>
        </tbody>
    </table>

    <div class="footer">
        <p>This report contains {{ $invoices->count() }} invoice(s) • Generated by Konnectix Technologies</p>
    </div>
</body>
</html>