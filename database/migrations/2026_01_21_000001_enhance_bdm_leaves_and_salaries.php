<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Enhance BDM Leave Applications table
        if (Schema::hasTable('bdm_leave_applications')) {
            Schema::table('bdm_leave_applications', function (Blueprint $table) {
                // Make leave_date nullable since we're using from_date/to_date now
                if (Schema::hasColumn('bdm_leave_applications', 'leave_date')) {
                    $table->date('leave_date')->nullable()->change();
                }
                // Add from_date and to_date for date range support
                if (!Schema::hasColumn('bdm_leave_applications', 'from_date')) {
                    $table->date('from_date')->after('leave_date')->nullable();
                }
                if (!Schema::hasColumn('bdm_leave_applications', 'to_date')) {
                    $table->date('to_date')->after('from_date')->nullable();
                }
                if (!Schema::hasColumn('bdm_leave_applications', 'number_of_days')) {
                    $table->integer('number_of_days')->after('to_date')->default(1);
                }
                if (!Schema::hasColumn('bdm_leave_applications', 'is_editable')) {
                    $table->boolean('is_editable')->after('admin_remarks')->default(true);
                }
            });
        }

        // Enhance BDM Leave Balance table
        if (Schema::hasTable('bdm_leave_balances')) {
            Schema::table('bdm_leave_balances', function (Blueprint $table) {
                // Add allocated leave columns
                if (!Schema::hasColumn('bdm_leave_balances', 'casual_leave_allocated')) {
                    $table->integer('casual_leave_allocated')->after('casual_leave_balance')->default(0);
                }
                if (!Schema::hasColumn('bdm_leave_balances', 'sick_leave_allocated')) {
                    $table->integer('sick_leave_allocated')->after('sick_leave_balance')->default(0);
                }
                if (!Schema::hasColumn('bdm_leave_balances', 'year_month')) {
                    $table->string('year_month')->after('current_month')->nullable();
                }
            });
        }

        // Enhance BDM Salaries table with attendance and leave details
        if (Schema::hasTable('bdm_salaries')) {
            Schema::table('bdm_salaries', function (Blueprint $table) {
                if (!Schema::hasColumn('bdm_salaries', 'total_present_days')) {
                    $table->integer('total_present_days')->after('remarks')->default(0);
                }
                if (!Schema::hasColumn('bdm_salaries', 'casual_leave_taken')) {
                    $table->integer('casual_leave_taken')->after('total_present_days')->default(0);
                }
                if (!Schema::hasColumn('bdm_salaries', 'sick_leave_taken')) {
                    $table->integer('sick_leave_taken')->after('casual_leave_taken')->default(0);
                }
                if (!Schema::hasColumn('bdm_salaries', 'unpaid_leave_taken')) {
                    $table->integer('unpaid_leave_taken')->after('sick_leave_taken')->default(0);
                }
                if (!Schema::hasColumn('bdm_salaries', 'per_day_salary')) {
                    $table->decimal('per_day_salary', 10, 2)->after('unpaid_leave_taken')->default(0);
                }
                if (!Schema::hasColumn('bdm_salaries', 'leave_deduction')) {
                    $table->decimal('leave_deduction', 10, 2)->after('per_day_salary')->default(0);
                }
                if (!Schema::hasColumn('bdm_salaries', 'attendance_notes')) {
                    $table->text('attendance_notes')->after('leave_deduction')->nullable();
                }
                if (!Schema::hasColumn('bdm_salaries', 'is_regenerated')) {
                    $table->boolean('is_regenerated')->after('attendance_notes')->default(false);
                }
                if (!Schema::hasColumn('bdm_salaries', 'generated_by')) {
                    $table->string('generated_by')->after('is_regenerated')->nullable();
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('bdm_leave_applications')) {
            Schema::table('bdm_leave_applications', function (Blueprint $table) {
                if (Schema::hasColumn('bdm_leave_applications', 'from_date')) {
                    $table->dropColumn('from_date');
                }
                if (Schema::hasColumn('bdm_leave_applications', 'to_date')) {
                    $table->dropColumn('to_date');
                }
                if (Schema::hasColumn('bdm_leave_applications', 'number_of_days')) {
                    $table->dropColumn('number_of_days');
                }
                if (Schema::hasColumn('bdm_leave_applications', 'is_editable')) {
                    $table->dropColumn('is_editable');
                }
            });
        }

        if (Schema::hasTable('bdm_leave_balances')) {
            Schema::table('bdm_leave_balances', function (Blueprint $table) {
                if (Schema::hasColumn('bdm_leave_balances', 'casual_leave_allocated')) {
                    $table->dropColumn('casual_leave_allocated');
                }
                if (Schema::hasColumn('bdm_leave_balances', 'sick_leave_allocated')) {
                    $table->dropColumn('sick_leave_allocated');
                }
                if (Schema::hasColumn('bdm_leave_balances', 'year_month')) {
                    $table->dropColumn('year_month');
                }
            });
        }

        if (Schema::hasTable('bdm_salaries')) {
            Schema::table('bdm_salaries', function (Blueprint $table) {
                $columns = [
                    'total_present_days',
                    'casual_leave_taken',
                    'sick_leave_taken',
                    'unpaid_leave_taken',
                    'per_day_salary',
                    'leave_deduction',
                    'attendance_notes',
                    'is_regenerated',
                    'generated_by'
                ];
                foreach ($columns as $column) {
                    if (Schema::hasColumn('bdm_salaries', $column)) {
                        $table->dropColumn($column);
                    }
                }
            });
        }
    }
};
