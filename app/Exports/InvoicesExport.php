<?php

namespace App\Exports;

use App\Models\Invoice;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class InvoicesExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    private $filters;

    public function __construct($filters = [])
    {
        $this->filters = $filters;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $query = Invoice::with('customer');

        // Apply filters
        if (isset($this->filters['customer_id']) && $this->filters['customer_id']) {
            $query->where('customer_id', $this->filters['customer_id']);
        }

        if (isset($this->filters['status']) && $this->filters['status']) {
            $query->where('status', $this->filters['status']);
        }

        if (isset($this->filters['invoice_type']) && $this->filters['invoice_type']) {
            $query->where('invoice_type', $this->filters['invoice_type']);
        }

        if (isset($this->filters['date_from']) && $this->filters['date_from']) {
            $query->whereDate('invoice_date', '>=', $this->filters['date_from']);
        }

        if (isset($this->filters['date_to']) && $this->filters['date_to']) {
            $query->whereDate('invoice_date', '<=', $this->filters['date_to']);
        }

        return $query->latest()->get();
    }

    public function headings(): array
    {
        return [
            'Invoice Number',
            'Customer Name',
            'Company Name',
            'Invoice Type',
            'Invoice Date',
            'Due Date',
            'Status',
            'Subtotal',
            'Discount',
            'Tax Amount',
            'Total Amount',
            'Created At'
        ];
    }

    public function map($invoice): array
    {
        return [
            $invoice->invoice_number,
            $invoice->customer->customer_name ?? '',
            $invoice->customer->company_name ?? '',
            ucfirst($invoice->invoice_type),
            $invoice->invoice_date->format('d/m/Y'),
            $invoice->due_date ? $invoice->due_date->format('d/m/Y') : '',
            ucfirst($invoice->status),
            '₹' . number_format($invoice->subtotal, 2),
            '₹' . number_format($invoice->discount_amount, 2),
            '₹' . number_format($invoice->tax_amount, 2),
            '₹' . number_format($invoice->total_amount, 2),
            $invoice->created_at->format('d/m/Y H:i')
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
