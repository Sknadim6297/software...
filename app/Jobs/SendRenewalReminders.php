<?php

namespace App\Jobs;

use App\Models\ServiceRenewal;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Mail\RenewalReminderMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class SendRenewalReminders implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Get all services due for renewal within 7 days
        $services = ServiceRenewal::where('renewal_date', '<=', Carbon::now()->addDays(7))
            ->where('renewal_date', '>=', Carbon::now())
            ->where('service_status', 'Active')
            ->where('auto_renewal', true)
            ->where(function($query) {
                $query->where('renewal_mail_sent', false)
                      ->orWhereNull('last_renewal_mail_sent_at')
                      ->orWhere('last_renewal_mail_sent_at', '<=', Carbon::now()->subDays(3));
            })
            ->get();

        foreach ($services as $service) {
            try {
                // Create invoice for renewal
                $invoice = Invoice::create([
                    'customer_id' => $service->customer_id,
                    'invoice_number' => Invoice::generateInvoiceNumber('tax_invoice'),
                    'invoice_type' => 'tax_invoice',
                    'invoice_date' => Carbon::now(),
                    'due_date' => $service->renewal_date,
                    'payment_status' => 'unpaid',
                    'subtotal' => $service->amount,
                    'tax_total' => 0,
                    'grand_total' => $service->amount,
                    'notes' => "Service Renewal: {$service->service_type}",
                ]);

                // Create invoice item
                InvoiceItem::create([
                    'invoice_id' => $invoice->id,
                    'product_description' => "{$service->service_type} - {$service->renewal_type} Renewal",
                    'quantity' => 1,
                    'rate' => $service->amount,
                    'total_amount' => $service->amount,
                ]);

                // Send email with invoice
                Mail::to($service->customer->email)
                    ->send(new RenewalReminderMail($service, $invoice));

                // Update service
                $service->update([
                    'renewal_mail_sent' => true,
                    'last_renewal_mail_sent_at' => Carbon::now(),
                ]);

                \Log::info("Renewal reminder sent for service #{$service->id}");
            } catch (\Exception $e) {
                \Log::error("Failed to send renewal reminder for service #{$service->id}: " . $e->getMessage());
            }
        }
    }
}
