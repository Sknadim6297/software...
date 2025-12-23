<?php

namespace App\Console\Commands;

use App\Models\Invoice;
use Illuminate\Console\Command;

class FixInvoiceNumbers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'invoices:fix-numbers';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fix invoice numbers that contain literal str_pad strings';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Fixing invoice numbers...');
        
        // Get all invoices with malformed invoice numbers
        $invoices = Invoice::where('invoice_number', 'like', '%str_pad%')
            ->orWhere('invoice_number', 'like', '%{%')
            ->get();
        
        if ($invoices->isEmpty()) {
            $this->info('No invoices found with malformed invoice numbers.');
            return 0;
        }
        
        $this->info("Found {$invoices->count()} invoices to fix.");
        
        $bar = $this->output->createProgressBar($invoices->count());
        $bar->start();
        
        foreach ($invoices as $invoice) {
            // Generate new proper invoice number based on type
            $newNumber = Invoice::generateInvoiceNumber($invoice->invoice_type);
            
            // Update the invoice
            $invoice->update(['invoice_number' => $newNumber]);
            
            $bar->advance();
        }
        
        $bar->finish();
        $this->newLine();
        $this->info('All invoice numbers have been fixed!');
        
        return 0;
    }
}
