<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            // Remove old customer columns
            $table->dropColumn(['customer_name', 'customer_email', 'customer_address']);
            
            // Add new customer relation column
            $table->foreignId('customer_id')->after('invoice_number')
                ->constrained()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            // Revert changes
            $table->string('customer_name')->after('invoice_number');
            $table->string('customer_email')->after('customer_name');
            $table->text('customer_address')->after('customer_email');
            
            $table->dropForeign(['customer_id']);
            $table->dropColumn('customer_id');
        });
    }
};
