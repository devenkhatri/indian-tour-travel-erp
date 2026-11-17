<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('flight_enquiries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('customer_id')->nullable()->constrained()->nullOnDelete();
            $table->string('trip_type', 20)->default('round_trip'); // one_way, round_trip, multi_city
            $table->string('departure_airport', 100);
            $table->string('arrival_airport', 100);
            $table->date('departure_date');
            $table->date('return_date')->nullable();
            $table->unsignedSmallInteger('adults')->default(1);
            $table->unsignedSmallInteger('children')->default(0);
            $table->unsignedSmallInteger('infants')->default(0);
            $table->string('cabin_class', 30)->default('economy'); // economy, premium_economy, business, first
            $table->string('preferred_airline', 100)->nullable();
            $table->string('pnr_number', 50)->nullable();
            $table->string('ticket_file')->nullable();
            $table->string('status', 30)->default('enquiry'); // enquiry, quoted, booked, ticket_issued, cancelled
            $table->string('contact_name', 150);
            $table->string('contact_email', 150);
            $table->string('contact_phone', 25);
            $table->decimal('quoted_price', 12, 2)->nullable();
            $table->text('remarks')->nullable();
            $table->timestamps();
        });

        Schema::create('hotel_bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('customer_id')->nullable()->constrained()->nullOnDelete();
            $table->string('hotel_name', 200);
            $table->string('city', 100);
            $table->date('check_in_date');
            $table->date('check_out_date');
            $table->unsignedSmallInteger('rooms_count')->default(1);
            $table->unsignedSmallInteger('adults')->default(1);
            $table->unsignedSmallInteger('children')->default(0);
            $table->string('room_type', 100)->nullable();
            $table->string('meal_plan', 50)->nullable(); // EP, CP, MAP, AP
            $table->string('confirmation_code', 50)->nullable();
            $table->string('status', 30)->default('pending'); // pending, confirmed, cancelled
            $table->string('voucher_file')->nullable();
            $table->decimal('total_price', 12, 2)->default(0);
            $table->string('contact_name', 150);
            $table->string('contact_phone', 25);
            $table->string('contact_email', 150);
            $table->timestamps();
        });

        Schema::create('train_bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('customer_id')->nullable()->constrained()->nullOnDelete();
            $table->string('from_station', 100);
            $table->string('to_station', 100);
            $table->date('journey_date');
            $table->string('class_type', 30)->default('3A'); // 1A, 2A, 3A, SL, CC, EC
            $table->string('train_number', 50)->nullable();
            $table->string('pnr_number', 50)->nullable();
            $table->unsignedSmallInteger('passenger_count')->default(1);
            $table->string('status', 30)->default('enquiry'); // enquiry, booked, waitlist, cancelled
            $table->string('ticket_file')->nullable();
            $table->string('contact_name', 150);
            $table->string('contact_phone', 25);
            $table->text('remarks')->nullable();
            $table->timestamps();
        });

        Schema::create('bus_bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('customer_id')->nullable()->constrained()->nullOnDelete();
            $table->string('from_city', 100);
            $table->string('to_city', 100);
            $table->date('journey_date');
            $table->string('bus_type', 30)->default('ac_sleeper'); // ac_sleeper, non_ac_sleeper, ac_seater, non_ac_seater
            $table->string('boarding_point', 150)->nullable();
            $table->string('dropping_point', 150)->nullable();
            $table->unsignedSmallInteger('passenger_count')->default(1);
            $table->string('ticket_number', 50)->nullable();
            $table->string('status', 30)->default('enquiry'); // enquiry, booked, cancelled
            $table->string('ticket_file')->nullable();
            $table->string('contact_name', 150);
            $table->string('contact_phone', 25);
            $table->text('remarks')->nullable();
            $table->timestamps();
        });

        Schema::create('visa_applications', function (Blueprint $table) {
            $table->id();
            $table->string('application_number', 50)->unique();
            $table->foreignId('booking_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('customer_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('country_id')->constrained()->cascadeOnDelete();
            $table->string('visa_category', 50)->default('tourist'); // tourist, business, student, medical, transit, work
            $table->string('applicant_name', 150);
            $table->string('passport_number', 50)->nullable();
            $table->date('passport_expiry')->nullable();
            $table->date('travel_date')->nullable();
            $table->decimal('fee', 12, 2)->default(0);
            $table->decimal('supplier_cost', 12, 2)->default(0);
            $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete();
            $table->string('status', 30)->default('new');
            // new, documents_pending, documents_received, under_verification, ready_for_submission, submitted, appointment_scheduled, under_processing, approved, rejected, delivered, cancelled
            $table->date('appointment_date')->nullable();
            $table->date('submission_date')->nullable();
            $table->date('expected_date')->nullable();
            $table->date('actual_date')->nullable();
            $table->string('visa_document')->nullable();
            $table->text('remarks')->nullable();
            $table->string('contact_phone', 25);
            $table->string('contact_email', 150);
            $table->timestamps();
        });

        Schema::create('visa_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('visa_application_id')->constrained()->cascadeOnDelete();
            $table->string('document_name', 150);
            $table->string('file_path');
            $table->boolean('is_verified')->default(false);
            $table->text('remarks')->nullable();
            $table->timestamps();
        });

        Schema::create('passport_applications', function (Blueprint $table) {
            $table->id();
            $table->string('application_number', 50)->unique();
            $table->foreignId('customer_id')->nullable()->constrained()->nullOnDelete();
            $table->string('service_type', 50)->default('fresh'); // fresh, reissue, renewal, tatkal
            $table->string('applicant_name', 150);
            $table->string('gender', 15)->nullable();
            $table->date('dob')->nullable();
            $table->string('place_of_birth', 100)->nullable();
            $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete();
            $table->date('appointment_date')->nullable();
            $table->string('appointment_location', 150)->nullable();
            $table->string('status', 30)->default('enquiry');
            // enquiry, documents_pending, verified, appointment_scheduled, submitted, in_progress, dispatched, completed, cancelled
            $table->decimal('fee', 12, 2)->default(0);
            $table->text('remarks')->nullable();
            $table->string('contact_phone', 25);
            $table->string('contact_email', 150);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('passport_applications');
        Schema::dropIfExists('visa_documents');
        Schema::dropIfExists('visa_applications');
        Schema::dropIfExists('bus_bookings');
        Schema::dropIfExists('train_bookings');
        Schema::dropIfExists('hotel_bookings');
        Schema::dropIfExists('flight_enquiries');
    }
};
