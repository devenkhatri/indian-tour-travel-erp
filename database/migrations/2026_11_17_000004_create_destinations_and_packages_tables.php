<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('countries', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('code', 5)->unique(); // IN, TH, AE, US, etc.
            $table->string('iso3', 5)->nullable();
            $table->string('phone_code', 10)->nullable();
            $table->string('currency', 10)->default('INR');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('destinations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('country_id')->constrained()->cascadeOnDelete();
            $table->string('name', 150);
            $table->string('slug', 150)->unique();
            $table->string('type', 20)->default('domestic'); // domestic, international
            $table->string('state', 100)->nullable();
            $table->string('banner_image')->nullable();
            $table->text('description')->nullable();
            $table->string('best_time_to_visit', 150)->nullable();
            $table->string('weather_info', 150)->nullable();
            $table->json('popular_attractions')->nullable();
            $table->json('popular_activities')->nullable();
            $table->text('travel_tips')->nullable();
            $table->json('faqs')->nullable();
            $table->boolean('is_popular')->default(false);
            $table->boolean('is_trending')->default(false);
            $table->boolean('is_active')->default(true);
            $table->string('seo_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->timestamps();
        });

        Schema::create('package_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('slug', 100)->unique();
            $table->string('icon', 50)->nullable();
            $table->string('image')->nullable();
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('packages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('destination_id')->constrained()->cascadeOnDelete();
            $table->foreignId('category_id')->nullable()->constrained('package_categories')->nullOnDelete();
            $table->string('package_code', 50)->unique();
            $table->string('title', 200);
            $table->string('slug', 200)->unique();
            $table->string('type', 20)->default('domestic'); // domestic, international
            $table->unsignedSmallInteger('duration_days')->default(1);
            $table->unsignedSmallInteger('duration_nights')->default(0);
            $table->decimal('starting_price', 12, 2);
            $table->decimal('discount_price', 12, 2)->nullable();
            $table->decimal('adult_price', 12, 2);
            $table->decimal('child_price', 12, 2)->default(0);
            $table->decimal('infant_price', 12, 2)->default(0);
            $table->string('departure_city', 100)->nullable();
            $table->string('pickup_drop_info')->nullable();
            $table->string('featured_image')->nullable();
            $table->json('gallery')->nullable();
            $table->json('highlights')->nullable();
            $table->json('inclusions')->nullable();
            $table->json('exclusions')->nullable();
            $table->text('terms_conditions')->nullable();
            $table->text('cancellation_policy')->nullable();
            $table->unsignedSmallInteger('min_group_size')->default(1);
            $table->unsignedSmallInteger('max_group_size')->default(50);
            $table->string('difficulty_level', 30)->nullable();
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_active')->default(true);
            $table->string('status', 20)->default('published'); // draft, published
            $table->json('tags')->nullable();
            $table->string('seo_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('package_itineraries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('package_id')->constrained()->cascadeOnDelete();
            $table->unsignedSmallInteger('day_number');
            $table->string('title', 200);
            $table->text('description');
            $table->string('hotel_info')->nullable();
            $table->string('meals_info')->nullable();
            $table->string('transport_info')->nullable();
            $table->string('activity_info')->nullable();
            $table->string('image')->nullable();
            $table->timestamps();
        });

        Schema::create('package_departures', function (Blueprint $table) {
            $table->id();
            $table->foreignId('package_id')->constrained()->cascadeOnDelete();
            $table->date('departure_date');
            $table->date('return_date');
            $table->unsignedSmallInteger('total_seats')->default(30);
            $table->unsignedSmallInteger('available_seats')->default(30);
            $table->decimal('price_override', 12, 2)->nullable();
            $table->string('status', 20)->default('open'); // open, fast_filling, closed, completed
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('package_departures');
        Schema::dropIfExists('package_itineraries');
        Schema::dropIfExists('packages');
        Schema::dropIfExists('package_categories');
        Schema::dropIfExists('destinations');
        Schema::dropIfExists('countries');
    }
};
