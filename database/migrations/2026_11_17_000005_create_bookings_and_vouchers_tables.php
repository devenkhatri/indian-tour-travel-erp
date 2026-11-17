<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->string('booking_number', 50)->unique();
            $table->foreignId('customer_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete(); // Creator/Staff
            $table->unsignedBigInteger('agent_id')->nullable(); // B2B agent (foreign key added after agents table is created)
            $table->string('service_type', 30)->default('package'); // package, flight, hotel, train, bus, visa, passport, customized
            $table->foreignId('package_id')->nullable()->constrained()->nullOnDelete();
            $table->date('departure_date')->nullable();
            $table->date('return_date')->nullable();
            $table->unsignedSmallInteger('adults')->default(1);
            $table->unsignedSmallInteger('children')->default(0);
            $table->unsignedSmallInteger('infants')->default(0);
            $table->decimal('selling_amount', 12, 2)->default(0);
            $table->decimal('discount_amount', 12, 2)->default(0);
            $table->decimal('tax_amount', 12, 2)->default(0);
            $table->decimal('total_amount', 12, 2)->default(0);
            $table->decimal('paid_amount', 12, 2)->default(0);
            $table->decimal('balance_amount', 12, 2)->default(0);
            $table->decimal('supplier_cost', 12, 2)->default(0);
            $table->decimal('expected_profit', 12, 2)->default(0);
            $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete();
            $table->string('booking_status', 30)->default('confirmed'); // enquiry, quotation, pending_payment, partially_paid, confirmed, in_progress, completed, cancelled, refunded
            $table->string('payment_status', 30)->default('unpaid'); // unpaid, partially_paid, paid, refunded
            $table->text('notes')->nullable();
            $table->text('cancellation_reason')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('booking_passengers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained()->cascadeOnDelete();
            $table->string('first_name', 100);
            $table->string('last_name', 100);
            $table->string('gender', 15);
            $table->date('dob')->nullable();
            $table->string('type', 15)->default('adult'); // adult, child, infant
            $table->string('passport_number', 50)->nullable();
            $table->date('passport_expiry')->nullable();
            $table->string('passport_country', 100)->default('India');
            $table->string('ticket_number', 50)->nullable();
            $table->string('seat_number', 20)->nullable();
            $table->string('meal_preference', 50)->nullable();
            $table->timestamps();
        });

        Schema::create('booking_vouchers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained()->cascadeOnDelete();
            $table->string('voucher_number', 50)->unique();
            $table->string('voucher_type', 30)->default('customer'); // customer, hotel, transport, activity
            $table->json('details')->nullable();
            $table->dateTime('generated_at');
            $table->string('file_path')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('booking_vouchers');
        Schema::dropIfExists('booking_passengers');
        Schema::dropIfExists('bookings');
    }
};
