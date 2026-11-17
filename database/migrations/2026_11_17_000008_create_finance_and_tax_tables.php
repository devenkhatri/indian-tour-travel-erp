<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tax_rates', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('type', 30)->default('gst'); // gst, tcs, cess
            $table->decimal('rate_percent', 5, 2);
            $table->decimal('cgst_percent', 5, 2)->default(0);
            $table->decimal('sgst_percent', 5, 2)->default(0);
            $table->decimal('igst_percent', 5, 2)->default(0);
            $table->string('hsn_sac_code', 20)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number', 50)->unique();
            $table->foreignId('booking_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('customer_id')->constrained()->cascadeOnDelete();
            $table->string('financial_year', 20)->default('2026-2027');
            $table->date('invoice_date');
            $table->date('due_date')->nullable();
            $table->decimal('subtotal', 12, 2)->default(0);
            $table->foreignId('tax_id')->nullable()->constrained('tax_rates')->nullOnDelete();
            $table->decimal('tax_rate', 5, 2)->default(5.00);
            $table->decimal('cgst_amount', 12, 2)->default(0);
            $table->decimal('sgst_amount', 12, 2)->default(0);
            $table->decimal('igst_amount', 12, 2)->default(0);
            $table->decimal('tcs_amount', 12, 2)->default(0);
            $table->decimal('discount_amount', 12, 2)->default(0);
            $table->decimal('total_amount', 12, 2)->default(0);
            $table->decimal('paid_amount', 12, 2)->default(0);
            $table->decimal('balance_amount', 12, 2)->default(0);
            $table->string('status', 30)->default('issued'); // draft, issued, partially_paid, paid, cancelled
            $table->json('items')->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->string('payment_number', 50)->unique();
            $table->foreignId('booking_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('invoice_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('customer_id')->nullable()->constrained()->nullOnDelete();
            $table->unsignedBigInteger('agent_id')->nullable();
            $table->decimal('amount', 12, 2);
            $table->string('payment_method', 30)->default('upi'); // cash, upi, bank_transfer, credit_card, debit_card, razorpay, cheque
            $table->string('transaction_id', 100)->nullable();
            $table->string('reference_number', 100)->nullable();
            $table->date('payment_date');
            $table->string('type', 30)->default('advance'); // advance, partial, full, refund
            $table->string('status', 30)->default('completed'); // pending, completed, failed, reversed
            $table->string('receipt_path')->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->string('expense_number', 50)->unique();
            $table->string('category', 50); // office, marketing, supplier, operational, salary, travel, other
            $table->string('title', 200);
            $table->decimal('amount', 12, 2);
            $table->date('expense_date');
            $table->foreignId('branch_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('booking_id')->nullable()->constrained()->nullOnDelete();
            $table->string('paid_to', 150)->nullable();
            $table->string('payment_method', 30)->default('bank_transfer');
            $table->string('receipt_file')->nullable();
            $table->string('status', 30)->default('paid'); // pending, approved, rejected, paid
            $table->text('notes')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('ledger_entries', function (Blueprint $table) {
            $table->id();
            $table->string('account_type', 30); // customer, supplier, agent, expense, bank, cash
            $table->string('reference_type', 50)->nullable(); // booking, invoice, payment, expense
            $table->unsignedBigInteger('reference_id')->nullable();
            $table->decimal('debit', 12, 2)->default(0);
            $table->decimal('credit', 12, 2)->default(0);
            $table->decimal('balance', 12, 2)->default(0);
            $table->date('entry_date');
            $table->string('description', 255);
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ledger_entries');
        Schema::dropIfExists('expenses');
        Schema::dropIfExists('payments');
        Schema::dropIfExists('invoices');
        Schema::dropIfExists('tax_rates');
    }
};
