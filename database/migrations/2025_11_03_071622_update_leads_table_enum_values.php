<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update platform enum to include more values
        DB::statement("ALTER TABLE leads MODIFY COLUMN platform ENUM('website', 'facebook', 'instagram', 'linkedin', 'referral', 'cold_call', 'email', 'justdial', 'google', 'other') DEFAULT 'other'");
        
        // Update status enum to match the application statuses
        DB::statement("ALTER TABLE leads MODIFY COLUMN status ENUM('new', 'contacted', 'qualified', 'meeting_scheduled', 'proposal_sent', 'negotiation', 'won', 'lost', 'converted', 'pending', 'callback_scheduled', 'did_not_receive', 'not_required', 'not_interested') DEFAULT 'new'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert to original enum values
        DB::statement("ALTER TABLE leads MODIFY COLUMN platform ENUM('facebook', 'justdial', 'google', 'other') DEFAULT 'other'");
        DB::statement("ALTER TABLE leads MODIFY COLUMN status ENUM('pending', 'callback_scheduled', 'did_not_receive', 'not_required', 'meeting_scheduled', 'not_interested', 'converted') DEFAULT 'pending'");
    }
};
