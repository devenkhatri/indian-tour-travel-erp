<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('suppliers', function (Blueprint $table) {
            $table->id();
            $table->string('name', 150);
            $table->string('code', 50)->unique();
            $table->string('category', 50); // airline, hotel, transport, cab, dmc, visa, insurance, cruise, other
            $table->string('contact_person', 100)->nullable();
            $table->string('email', 150)->nullable();
            $table->string('phone', 25)->nullable();
            $table->string('city', 100)->nullable();
            $table->string('state', 100)->nullable();
            $table->string('country', 100)->default('India');
            $table->string('gstin', 30)->nullable();
            $table->string('pan', 20)->nullable();
            $table->json('bank_details')->nullable();
            $table->decimal('rating', 3, 2)->default(5.00);
            $table->boolean('is_active')->default(true);
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('trips', function (Blueprint $table) {
            $table->id();
            $table->string('trip_code', 50)->unique();
            $table->foreignId('booking_id')->constrained()->cascadeOnDelete();
            $table->foreignId('package_id')->nullable()->constrained()->nullOnDelete();
            $table->string('title', 200);
            $table->date('start_date');
            $table->date('end_date');
            $table->foreignId('tour_leader_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('status', 30)->default('upcoming'); // upcoming, ongoing, completed, delayed, cancelled
            $table->string('vehicle_info')->nullable();
            $table->string('driver_info')->nullable();
            $table->string('guide_info')->nullable();
            $table->text('special_instructions')->nullable();
            $table->timestamps();
        });

        Schema::create('trip_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('trip_id')->constrained()->cascadeOnDelete();
            $table->foreignId('supplier_id')->nullable()->constrained()->nullOnDelete();
            $table->string('type', 30); // vehicle, driver, hotel, guide, transfer, activity
            $table->json('details')->nullable();
            $table->string('status', 30)->default('assigned'); // assigned, confirmed, completed
            $table->decimal('cost', 12, 2)->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('trip_assignments');
        Schema::dropIfExists('trips');
        Schema::dropIfExists('suppliers');
    }
};
