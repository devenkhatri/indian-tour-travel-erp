<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lead_sources', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('slug', 100)->unique();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('customer_code', 50)->unique();
            $table->string('name', 150);
            $table->string('email', 150)->nullable()->index();
            $table->string('phone', 25)->index();
            $table->string('alternate_phone', 25)->nullable();
            $table->string('passport_number', 50)->nullable();
            $table->date('passport_expiry')->nullable();
            $table->date('dob')->nullable();
            $table->string('gender', 15)->nullable();
            $table->text('address')->nullable();
            $table->string('city', 100)->nullable();
            $table->string('state', 100)->nullable();
            $table->string('country', 100)->default('India');
            $table->string('pincode', 20)->nullable();
            $table->string('gstin', 30)->nullable();
            $table->string('status', 20)->default('active'); // active, inactive, blacklisted
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('customer_passengers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained()->cascadeOnDelete();
            $table->string('first_name', 100);
            $table->string('last_name', 100);
            $table->string('gender', 15);
            $table->date('dob')->nullable();
            $table->string('relationship', 50)->nullable(); // self, spouse, child, parent, friend
            $table->string('passport_number', 50)->nullable();
            $table->date('passport_expiry')->nullable();
            $table->string('passport_country', 100)->default('India');
            $table->timestamps();
        });

        Schema::create('customer_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained()->cascadeOnDelete();
            $table->string('document_type', 50); // passport, aadhaar, pan, visa, photo, ticket
            $table->string('title', 150);
            $table->string('file_path');
            $table->unsignedBigInteger('file_size')->nullable();
            $table->string('mime_type', 50)->nullable();
            $table->string('verified_status', 20)->default('pending'); // pending, verified, rejected
            $table->foreignId('verified_by')->nullable()->constrained('users')->nullOnDelete();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('leads', function (Blueprint $table) {
            $table->id();
            $table->string('lead_number', 50)->unique();
            $table->foreignId('customer_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name', 150);
            $table->string('email', 150)->nullable()->index();
            $table->string('phone', 25)->index();
            $table->string('departure_city', 100)->nullable();
            $table->string('destination', 150);
            $table->foreignId('lead_source_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('branch_id')->nullable()->constrained()->nullOnDelete();
            $table->string('category', 50)->default('holiday'); // holiday, flight, hotel, visa, corporate, etc.
            $table->string('priority', 20)->default('medium'); // low, medium, high, urgent
            $table->string('status', 30)->default('new'); // new, contacted, interested, follow_up, quotation_sent, negotiation, converted, lost
            $table->string('lost_reason')->nullable();
            $table->decimal('budget', 12, 2)->nullable();
            $table->unsignedSmallInteger('adults')->default(1);
            $table->unsignedSmallInteger('children')->default(0);
            $table->unsignedSmallInteger('infants')->default(0);
            $table->date('travel_start_date')->nullable();
            $table->date('travel_end_date')->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('lead_followups', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lead_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->dateTime('followup_date');
            $table->string('status', 20)->default('pending'); // pending, completed, missed, cancelled
            $table->text('remarks')->nullable();
            $table->dateTime('reminder_at')->nullable();
            $table->timestamps();
        });

        Schema::create('lead_activities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lead_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('type', 30); // call, email, whatsapp, note, status_change, quotation
            $table->string('summary', 255);
            $table->text('details')->nullable();
            $table->timestamps();
        });

        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->string('title', 200);
            $table->text('description')->nullable();
            $table->foreignId('assigned_to')->constrained('users')->cascadeOnDelete();
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
            $table->string('priority', 20)->default('medium');
            $table->string('status', 20)->default('pending'); // pending, in_progress, completed, deferred
            $table->date('due_date')->nullable();
            $table->nullableMorphs('taskable');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tasks');
        Schema::dropIfExists('lead_activities');
        Schema::dropIfExists('lead_followups');
        Schema::dropIfExists('leads');
        Schema::dropIfExists('customer_documents');
        Schema::dropIfExists('customer_passengers');
        Schema::dropIfExists('customers');
        Schema::dropIfExists('lead_sources');
    }
};
