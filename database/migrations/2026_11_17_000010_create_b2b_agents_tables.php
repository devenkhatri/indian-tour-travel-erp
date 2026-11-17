<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('agents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('agency_name', 150);
            $table->string('agency_code', 50)->unique();
            $table->string('owner_name', 100);
            $table->string('email', 150)->unique();
            $table->string('phone', 25);
            $table->string('city', 100);
            $table->string('state', 100);
            $table->string('gstin', 30)->nullable();
            $table->string('pan', 20)->nullable();
            $table->string('agency_logo')->nullable();
            $table->decimal('credit_limit', 12, 2)->default(0);
            $table->decimal('used_credit', 12, 2)->default(0);
            $table->decimal('wallet_balance', 12, 2)->default(0);
            $table->decimal('commission_rate', 5, 2)->default(5.00); // 5% default
            $table->string('status', 30)->default('pending'); // pending, approved, rejected, suspended
            $table->dateTime('approved_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('agent_commissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('agent_id')->constrained()->cascadeOnDelete();
            $table->foreignId('booking_id')->constrained()->cascadeOnDelete();
            $table->decimal('booking_amount', 12, 2);
            $table->decimal('commission_percent', 5, 2);
            $table->decimal('commission_amount', 12, 2);
            $table->string('status', 30)->default('pending'); // pending, credited, paid
            $table->timestamps();
        });

        Schema::table('bookings', function (Blueprint $table) {
            $table->foreign('agent_id')->references('id')->on('agents')->nullOnDelete();
        });

        Schema::table('payments', function (Blueprint $table) {
            $table->foreign('agent_id')->references('id')->on('agents')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropForeign(['agent_id']);
        });

        Schema::table('bookings', function (Blueprint $table) {
            $table->dropForeign(['agent_id']);
        });

        Schema::dropIfExists('agent_commissions');
        Schema::dropIfExists('agents');
    }
};
