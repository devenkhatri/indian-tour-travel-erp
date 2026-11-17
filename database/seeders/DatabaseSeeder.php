<?php

namespace Database\Seeders;

use App\Models\ActivityLog;
use App\Models\Agent;
use App\Models\Banner;
use App\Models\Blog;
use App\Models\Booking;
use App\Models\BookingPassenger;
use App\Models\BookingVoucher;
use App\Models\Branch;
use App\Models\Country;
use App\Models\Coupon;
use App\Models\Customer;
use App\Models\CustomerPassenger;
use App\Models\Department;
use App\Models\Destination;
use App\Models\Employee;
use App\Models\Expense;
use App\Models\Faq;
use App\Models\Invoice;
use App\Models\Lead;
use App\Models\LeadActivity;
use App\Models\LeadFollowup;
use App\Models\LeadSource;
use App\Models\PackageCategory;
use App\Models\PackageDeparture;
use App\Models\PackageItinerary;
use App\Models\PassportApplication;
use App\Models\Payment;
use App\Models\Role;
use App\Models\Setting;
use App\Models\Supplier;
use App\Models\TaxRate;
use App\Models\Testimonial;
use App\Models\TourPackage;
use App\Models\Trip;
use App\Models\TripAssignment;
use App\Models\User;
use App\Models\VisaApplication;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Roles & Permissions
        $roles = [
            ['name' => 'Super Admin', 'slug' => 'super_admin', 'description' => 'Complete control over all CRM and portal modules'],
            ['name' => 'Admin', 'slug' => 'admin', 'description' => 'Administrative management access'],
            ['name' => 'Sales Manager', 'slug' => 'sales_manager', 'description' => 'Manages leads, quotations, and sales team'],
            ['name' => 'Operations Manager', 'slug' => 'operations_manager', 'description' => 'Oversees trips, vouchers, drivers, and hotels'],
            ['name' => 'Accounts Manager', 'slug' => 'accounts_manager', 'description' => 'Oversees invoicing, GST, payments, and ledger'],
            ['name' => 'Travel Consultant', 'slug' => 'travel_consultant', 'description' => 'Handles package consultations and customer enquiries'],
            ['name' => 'B2B Agent', 'slug' => 'agent', 'description' => 'External partner travel agency'],
            ['name' => 'Customer', 'slug' => 'customer', 'description' => 'Registered retail travel client'],
        ];

        foreach ($roles as $r) {
            Role::firstOrCreate(['slug' => $r['slug']], $r);
        }

        $superAdminRole = Role::where('slug', 'super_admin')->first();
        $adminRole = Role::where('slug', 'admin')->first();
        $agentRole = Role::where('slug', 'agent')->first();
        $customerRole = Role::where('slug', 'customer')->first();

        // 2. Branches
        $branchDelhi = Branch::firstOrCreate(['code' => 'DEL-01'], [
            'name' => 'New Delhi Head Office',
            'code' => 'DEL-01',
            'city' => 'New Delhi',
            'state' => 'Delhi',
            'phone' => '+91 11 4567 8900',
            'email' => 'delhi@bharattravel.com',
            'address' => 'Plot 45, Barakhamba Road, Connaught Place, New Delhi - 110001',
            'is_active' => true,
        ]);

        $branchMumbai = Branch::firstOrCreate(['code' => 'BOM-01'], [
            'name' => 'Mumbai Western Hub',
            'code' => 'BOM-01',
            'city' => 'Mumbai',
            'state' => 'Maharashtra',
            'phone' => '+91 22 2890 1234',
            'email' => 'mumbai@bharattravel.com',
            'address' => 'Unit 302, Maker Chambers V, Nariman Point, Mumbai - 400021',
            'is_active' => true,
        ]);

        // 3. Departments
        $deptSales = Department::firstOrCreate(['code' => 'SALES'], ['name' => 'Sales & Business Development', 'code' => 'SALES']);
        $deptOps = Department::firstOrCreate(['code' => 'OPS'], ['name' => 'Tour Operations & Logistics', 'code' => 'OPS']);
        $deptAccounts = Department::firstOrCreate(['code' => 'ACC'], ['name' => 'Accounts & Taxation', 'code' => 'ACC']);

        // 4. Core Users
        $superAdminUser = User::firstOrCreate(['email' => 'admin@bharattravel.com'], [
            'name' => 'Vikramaditya Singhania',
            'email' => 'admin@bharattravel.com',
            'password' => Hash::make('password'),
            'phone' => '+91 98100 12345',
            'role' => 'super_admin',
            'city' => 'New Delhi',
            'state' => 'Delhi',
            'country' => 'India',
        ]);
        $superAdminUser->roles()->sync([$superAdminRole->id]);

        $salesUser = User::firstOrCreate(['email' => 'sales@bharattravel.com'], [
            'name' => 'Ananya Deshmukh',
            'email' => 'sales@bharattravel.com',
            'password' => Hash::make('password'),
            'phone' => '+91 98200 54321',
            'role' => 'staff',
            'city' => 'Mumbai',
            'state' => 'Maharashtra',
            'country' => 'India',
        ]);
        $salesUser->roles()->sync([$adminRole->id]);

        Employee::firstOrCreate(['employee_code' => 'EMP-001'], [
            'user_id' => $salesUser->id,
            'branch_id' => $branchMumbai->id,
            'department_id' => $deptSales->id,
            'employee_code' => 'EMP-001',
            'designation' => 'Senior Travel Consultant & Sales Lead',
            'sales_target' => 2500000.00,
            'commission_rate' => 2.50,
            'joining_date' => Carbon::now()->subYears(2),
            'is_active' => true,
        ]);

        $agentUser = User::firstOrCreate(['email' => 'agent@shreetravels.com'], [
            'name' => 'Rajesh Kothari',
            'email' => 'agent@shreetravels.com',
            'password' => Hash::make('password'),
            'phone' => '+91 94250 99887',
            'role' => 'agent',
            'city' => 'Ahmedabad',
            'state' => 'Gujarat',
            'country' => 'India',
        ]);
        $agentUser->roles()->sync([$agentRole->id]);

        $agent = Agent::firstOrCreate(['agency_code' => 'AGT-GJ-101'], [
            'user_id' => $agentUser->id,
            'agency_name' => 'Shree Travels & Holidays Ltd',
            'agency_code' => 'AGT-GJ-101',
            'owner_name' => 'Rajesh Kothari',
            'email' => 'agent@shreetravels.com',
            'phone' => '+91 94250 99887',
            'city' => 'Ahmedabad',
            'state' => 'Gujarat',
            'gstin' => '24AAACS1234F1Z8',
            'credit_limit' => 500000.00,
            'used_credit' => 125000.00,
            'wallet_balance' => 45000.00,
            'commission_rate' => 7.50,
            'status' => 'approved',
            'approved_at' => Carbon::now()->subMonths(6),
            'notes' => 'Top performing tier-1 partner agency in Gujarat region',
        ]);

        $customerUser = User::firstOrCreate(['email' => 'rahul.sharma@gmail.com'], [
            'name' => 'Rahul Sharma',
            'email' => 'rahul.sharma@gmail.com',
            'password' => Hash::make('password'),
            'phone' => '+91 98765 43210',
            'role' => 'customer',
            'city' => 'Bengaluru',
            'state' => 'Karnataka',
            'country' => 'India',
        ]);
        $customerUser->roles()->sync([$customerRole->id]);

        $customer = Customer::firstOrCreate(['customer_code' => 'CUST-2026-001'], [
            'user_id' => $customerUser->id,
            'customer_code' => 'CUST-2026-001',
            'name' => 'Rahul Sharma',
            'email' => 'rahul.sharma@gmail.com',
            'phone' => '+91 98765 43210',
            'alternate_phone' => '+91 80 2345 6789',
            'passport_number' => 'Z9876543',
            'passport_expiry' => Carbon::now()->addYears(5),
            'dob' => Carbon::parse('1988-06-15'),
            'gender' => 'Male',
            'address' => 'Flat 402, Brigade Gateway, Malleshwaram West',
            'city' => 'Bengaluru',
            'state' => 'Karnataka',
            'country' => 'India',
            'pincode' => '560055',
            'gstin' => '29ABCDE1234F1Z5',
            'status' => 'active',
            'notes' => 'Frequent holiday traveller, prefers 4-star and 5-star properties with breakfast included.',
        ]);

        CustomerPassenger::firstOrCreate(['passport_number' => 'Z9876543'], [
            'customer_id' => $customer->id,
            'first_name' => 'Rahul',
            'last_name' => 'Sharma',
            'gender' => 'Male',
            'dob' => Carbon::parse('1988-06-15'),
            'relationship' => 'self',
            'passport_number' => 'Z9876543',
            'passport_expiry' => Carbon::now()->addYears(5),
            'passport_country' => 'India',
        ]);

        CustomerPassenger::firstOrCreate(['passport_number' => 'Z8765432'], [
            'customer_id' => $customer->id,
            'first_name' => 'Pooja',
            'last_name' => 'Sharma',
            'gender' => 'Female',
            'dob' => Carbon::parse('1991-09-22'),
            'relationship' => 'spouse',
            'passport_number' => 'Z8765432',
            'passport_expiry' => Carbon::now()->addYears(6),
            'passport_country' => 'India',
        ]);

        // 5. Tax Rates (Indian GST / TCS)
        TaxRate::firstOrCreate(['name' => 'GST on Tour Packages (5%)'], [
            'name' => 'GST on Tour Packages (5%)',
            'type' => 'gst',
            'rate_percent' => 5.00,
            'cgst_percent' => 2.50,
            'sgst_percent' => 2.50,
            'igst_percent' => 5.00,
            'hsn_sac_code' => '998551',
            'is_active' => true,
        ]);

        TaxRate::firstOrCreate(['name' => 'GST on Travel Agency Commission (18%)'], [
            'name' => 'GST on Travel Agency Commission (18%)',
            'type' => 'gst',
            'rate_percent' => 18.00,
            'cgst_percent' => 9.00,
            'sgst_percent' => 9.00,
            'igst_percent' => 18.00,
            'hsn_sac_code' => '998559',
            'is_active' => true,
        ]);

        TaxRate::firstOrCreate(['name' => 'TCS on Overseas Tour Packages (5%)'], [
            'name' => 'TCS on Overseas Tour Packages (5%)',
            'type' => 'tcs',
            'rate_percent' => 5.00,
            'cgst_percent' => 0.00,
            'sgst_percent' => 0.00,
            'igst_percent' => 0.00,
            'hsn_sac_code' => 'TCS206C',
            'is_active' => true,
        ]);

        // 6. Countries
        $countryIndia = Country::firstOrCreate(['code' => 'IN'], [
            'name' => 'India',
            'code' => 'IN',
            'iso3' => 'IND',
            'phone_code' => '+91',
            'currency' => 'INR',
            'is_active' => true,
        ]);

        $countryUAE = Country::firstOrCreate(['code' => 'AE'], [
            'name' => 'United Arab Emirates',
            'code' => 'AE',
            'iso3' => 'ARE',
            'phone_code' => '+971',
            'currency' => 'AED',
            'is_active' => true,
        ]);

        $countryThailand = Country::firstOrCreate(['code' => 'TH'], [
            'name' => 'Thailand',
            'code' => 'TH',
            'iso3' => 'THA',
            'phone_code' => '+66',
            'currency' => 'THB',
            'is_active' => true,
        ]);

        $countryIndonesia = Country::firstOrCreate(['code' => 'ID'], [
            'name' => 'Indonesia (Bali)',
            'code' => 'ID',
            'iso3' => 'IDN',
            'phone_code' => '+62',
            'currency' => 'IDR',
            'is_active' => true,
        ]);

        $countrySwiss = Country::firstOrCreate(['code' => 'CH'], [
            'name' => 'Switzerland',
            'code' => 'CH',
            'iso3' => 'CHE',
            'phone_code' => '+41',
            'currency' => 'CHF',
            'is_active' => true,
        ]);

        // 7. Package Categories
        $catHoneymoon = PackageCategory::firstOrCreate(['slug' => 'honeymoon-specials'], [
            'name' => 'Honeymoon Specials',
            'slug' => 'honeymoon-specials',
            'icon' => 'heart',
            'image' => 'https://images.unsplash.com/photo-1510414842594-a61c69b5ae57?auto=format&fit=crop&w=800&q=80',
            'description' => 'Romantic escapes, candlelit beach dinners, and luxury suites crafted for couples.',
        ]);

        $catFamily = PackageCategory::firstOrCreate(['slug' => 'family-holidays'], [
            'name' => 'Family Holidays',
            'slug' => 'family-holidays',
            'icon' => 'users',
            'image' => 'https://images.unsplash.com/photo-1540555700478-4be289fbecef?auto=format&fit=crop&w=800&q=80',
            'description' => 'Comfortable itineraries with theme parks, scenic relaxation, and kid-friendly experiences.',
        ]);

        $catPilgrimage = PackageCategory::firstOrCreate(['slug' => 'pilgrimage-tours'], [
            'name' => 'Spiritual & Pilgrimage',
            'slug' => 'pilgrimage-tours',
            'icon' => 'sparkles',
            'image' => 'https://images.unsplash.com/photo-1561361513-2d000a50f0dc?auto=format&fit=crop&w=800&q=80',
            'description' => 'Sacred journeys across Kedarnath, Badrinath, Varanasi, Tirupati and Golden Temple.',
        ]);

        $catAdventure = PackageCategory::firstOrCreate(['slug' => 'adventure-tours'], [
            'name' => 'Adventure & Trekking',
            'slug' => 'adventure-tours',
            'icon' => 'compass',
            'image' => 'https://images.unsplash.com/photo-1464822759023-fed622ff2c3b?auto=format&fit=crop&w=800&q=80',
            'description' => 'River rafting, high-altitude passes, desert camping, and wildlife safaris.',
        ]);

        $catInternational = PackageCategory::firstOrCreate(['slug' => 'international-holidays'], [
            'name' => 'International Escapes',
            'slug' => 'international-holidays',
            'icon' => 'globe',
            'image' => 'https://images.unsplash.com/photo-1512453979798-5ea266f8880c?auto=format&fit=crop&w=800&q=80',
            'description' => 'Curated world tours across Dubai, Southeast Asia, Europe, and tropical island resorts.',
        ]);

        // 8. Destinations
        $destKashmir = Destination::firstOrCreate(['slug' => 'kashmir'], [
            'country_id' => $countryIndia->id,
            'name' => 'Kashmir - Paradise on Earth',
            'slug' => 'kashmir',
            'type' => 'domestic',
            'state' => 'Jammu and Kashmir',
            'banner_image' => 'https://images.unsplash.com/photo-1595815771614-ade9d652a65d?auto=format&fit=crop&w=1200&q=80',
            'description' => 'Experience tranquil Dal Lake shikaras, snow-clad peaks in Gulmarg, and saffron fields in Pahalgam.',
            'best_time_to_visit' => 'March to October (Summer & Autumn), Dec to Feb for Snow',
            'weather_info' => 'Pleasant summer (15°C - 30°C), Sub-zero winters (-2°C to 8°C)',
            'popular_attractions' => ['Dal Lake', 'Gulmarg Gondola', 'Betaab Valley', 'Shankaracharya Temple', 'Sonamarg'],
            'popular_activities' => ['Shikara Ride', 'Skiing in Gulmarg', 'Pony Trekking', 'Houseboat Stay'],
            'is_popular' => true,
            'is_trending' => true,
            'seo_title' => 'Kashmir Tour Packages 2026 | Houseboats & Snow Trips',
            'meta_description' => 'Book the best Kashmir tour packages with Dal Lake houseboat stay, Gulmarg gondola tickets and Pahalgam tours.',
        ]);

        $destKerala = Destination::firstOrCreate(['slug' => 'kerala'], [
            'country_id' => $countryIndia->id,
            'name' => 'Kerala - God’s Own Country',
            'slug' => 'kerala',
            'type' => 'domestic',
            'state' => 'Kerala',
            'banner_image' => 'https://images.unsplash.com/photo-1602216056096-3b40cc0c9944?auto=format&fit=crop&w=1200&q=80',
            'description' => 'Serene backwaters of Alleppey, rolling green tea estates of Munnar, and pristine beaches of Kovalam.',
            'best_time_to_visit' => 'September to March',
            'weather_info' => 'Moderate tropical (22°C - 32°C)',
            'popular_attractions' => ['Alleppey Backwaters', 'Munnar Tea Gardens', 'Periyar Wildlife Sanctuary', 'Fort Kochi'],
            'popular_activities' => ['Houseboat Cruise', 'Kathakali Performance', 'Ayurvedic Spa Massage', 'Spice Plantation Walk'],
            'is_popular' => true,
            'is_trending' => false,
            'seo_title' => 'Kerala Tour Packages 2026 | Munnar & Alleppey Houseboat',
            'meta_description' => 'Explore lush tea gardens, scenic backwaters, and Ayurvedic wellness packages in Kerala.',
        ]);

        $destDubai = Destination::firstOrCreate(['slug' => 'dubai'], [
            'country_id' => $countryUAE->id,
            'name' => 'Dubai - Futuristic Marvel',
            'slug' => 'dubai',
            'type' => 'international',
            'state' => 'Dubai',
            'banner_image' => 'https://images.unsplash.com/photo-1512453979798-5ea266f8880c?auto=format&fit=crop&w=1200&q=80',
            'description' => 'Iconic skyscrapers, golden desert safaris, world-class shopping festivals, and luxury yacht cruises.',
            'best_time_to_visit' => 'November to April',
            'weather_info' => 'Winter warmth (20°C - 28°C)',
            'popular_attractions' => ['Burj Khalifa', 'Desert Safari Camp', 'Dubai Mall & Fountain', 'Palm Jumeirah', 'Museum of the Future'],
            'popular_activities' => ['Dune Bashing & BBQ', 'Skydiving', 'Marina Dhow Dinner Cruise', 'Aquaventure Waterpark'],
            'is_popular' => true,
            'is_trending' => true,
            'seo_title' => 'Dubai Holiday Packages with Visa Assistance 2026',
            'meta_description' => 'Experience Burj Khalifa, Desert Safari BBQ, Marina Cruise and luxury shopping with Dubai packages.',
        ]);

        $destBali = Destination::firstOrCreate(['slug' => 'bali'], [
            'country_id' => $countryIndonesia->id,
            'name' => 'Bali - Island of the Gods',
            'slug' => 'bali',
            'type' => 'international',
            'state' => 'Bali',
            'banner_image' => 'https://images.unsplash.com/photo-1537996194471-e657df975ab4?auto=format&fit=crop&w=1200&q=80',
            'description' => 'Ancient Hindu water temples, emerald rice terraces, cliffside sunset beach clubs, and private pool villas.',
            'best_time_to_visit' => 'April to October',
            'weather_info' => 'Warm tropical climate (26°C - 31°C)',
            'popular_attractions' => ['Uluwatu Temple', 'Ubud Monkey Forest', 'Tegallalang Rice Terrace', 'Nusa Penida Kelingking Beach'],
            'popular_activities' => ['Bali Swing', 'Kecak Fire Dance', 'Snorkeling with Manta Rays', 'Mount Batur Sunrise Trek'],
            'is_popular' => true,
            'is_trending' => true,
            'seo_title' => 'Bali Tour Packages from India | Private Pool Villas & Nusa Penida',
            'meta_description' => 'Romantic honeymoon and family packages to Bali with private pool villas, sightseeing and Indian meal options.',
        ]);

        // 9. Tour Packages
        $pkgKashmir = TourPackage::firstOrCreate(['slug' => 'magical-kashmir-with-gulmarg-pahalgam'], [
            'destination_id' => $destKashmir->id,
            'category_id' => $catHoneymoon->id,
            'package_code' => 'PKG-KAS-001',
            'title' => 'Magical Kashmir: Srinagar, Gulmarg & Pahalgam with Houseboat Stay',
            'slug' => 'magical-kashmir-with-gulmarg-pahalgam',
            'type' => 'domestic',
            'duration_days' => 6,
            'duration_nights' => 5,
            'starting_price' => 24999.00,
            'discount_price' => 21999.00,
            'adult_price' => 21999.00,
            'child_price' => 14999.00,
            'infant_price' => 4999.00,
            'departure_city' => 'Srinagar Airport (SXR)',
            'pickup_drop_info' => 'Airport pickup and drop included in dedicated AC sedan/SUV',
            'featured_image' => 'https://images.unsplash.com/photo-1595815771614-ade9d652a65d?auto=format&fit=crop&w=800&q=80',
            'gallery' => [
                'https://images.unsplash.com/photo-1595815771614-ade9d652a65d?auto=format&fit=crop&w=800&q=80',
                'https://images.unsplash.com/photo-1566837945700-30057527ade0?auto=format&fit=crop&w=800&q=80',
            ],
            'highlights' => [
                '1 Night stay in luxury Dal Lake Super Deluxe Houseboat',
                'Complimentary 1-hour sunset Shikara ride',
                'Excursion to Gulmarg with Phase 1 Gondola cable car experience',
                'Visit Betaab Valley, Aru Valley and Chandanwari in Pahalgam',
                'Daily breakfast and dinner (MAP meal plan) included',
            ],
            'inclusions' => [
                '5 Nights accommodation in 4-star hotels & luxury houseboat',
                'Daily buffet breakfast and dinners',
                'Exclusive private sedan/SUV for transfers & sightseeing',
                'Shikara ride on Dal Lake',
                'Toll taxes, driver allowances, parking & state permits',
            ],
            'exclusions' => [
                'Airfare to and from Srinagar',
                'Gondola tickets Phase 2 (optional)',
                'Union cab in Pahalgam & pony rides',
                'Personal expenses, laundry, monument entrance fees',
                'Travel insurance & 5% GST',
            ],
            'terms_conditions' => 'Advance 25% payable at booking confirmation. Balance due 14 days before journey. Government photo ID required.',
            'cancellation_policy' => '100% refund if cancelled 30+ days prior. 50% refund 15-29 days prior. No refund within 14 days.',
            'min_group_size' => 2,
            'max_group_size' => 30,
            'difficulty_level' => 'Easy',
            'is_featured' => true,
            'is_active' => true,
            'status' => 'published',
            'tags' => ['Kashmir', 'Houseboat', 'Snow', 'Honeymoon', 'Gulmarg'],
            'seo_title' => 'Kashmir 5N/6D Package with Houseboat & Gulmarg Gondola',
            'meta_description' => 'Book 6 days Kashmir tour package covering Srinagar, Dal lake, Gulmarg and Pahalgam. Includes hotels, cab and meals.',
        ]);

        // Itinerary for Kashmir
        $itineraries = [
            [
                'day_number' => 1,
                'title' => 'Arrival in Srinagar - Check-in & Dal Lake Shikara Ride',
                'description' => 'Arrive at Srinagar Airport. Our chauffeur will welcome you and transfer you to the hotel. In the evening, enjoy a memorable 1-hour sunset Shikara ride on Dal Lake, visiting floating gardens and local handicraft markets.',
                'hotel_info' => '4-Star Luxury Srinagar Hotel',
                'meals_info' => 'Dinner Included',
                'transport_info' => 'Private AC Vehicle Airport Transfer',
            ],
            [
                'day_number' => 2,
                'title' => 'Srinagar to Gulmarg Day Trip - Meadow of Flowers',
                'description' => 'After an early breakfast, drive to Gulmarg (2,730 meters). Board the famous Gulmarg Gondola, one of the world’s highest cable cars, rising to Kongdoori and Apharwat peaks. Enjoy snow activities and return to Srinagar in the evening.',
                'hotel_info' => '4-Star Luxury Srinagar Hotel',
                'meals_info' => 'Breakfast & Dinner',
                'transport_info' => 'Full-day Private Cab',
            ],
            [
                'day_number' => 3,
                'title' => 'Srinagar to Pahalgam - Valley of Shepherds',
                'description' => 'Drive through the picturesque saffron fields of Pampore and walnut orchards to Pahalgam. Check in at your riverside hotel. Visit the Lidder River and relax in the crisp pine air.',
                'hotel_info' => 'Pahalgam Riverside Resort',
                'meals_info' => 'Breakfast & Dinner',
                'transport_info' => 'Intercity Transfer',
            ],
            [
                'day_number' => 4,
                'title' => 'Pahalgam Sightseeing: Betaab Valley & Aru Valley',
                'description' => 'Explore the scenic Betaab Valley, named after the Bollywood blockbuster, Chandanwari (the start point of Amarnath Yatra), and pristine Aru Valley. Evening at leisure strolling in Pahalgam market.',
                'hotel_info' => 'Pahalgam Riverside Resort',
                'meals_info' => 'Breakfast & Dinner',
                'transport_info' => 'Local Sightseeing Vehicle',
            ],
            [
                'day_number' => 5,
                'title' => 'Pahalgam to Srinagar - Royal Houseboat Stay',
                'description' => 'Drive back to Srinagar and check in to your handcrafted cedarwood Super Deluxe Houseboat on Nigeen/Dal Lake. Evening at leisure overlooking the misty Zabarwan mountain range.',
                'hotel_info' => 'Super Deluxe Heritage Houseboat',
                'meals_info' => 'Breakfast & Traditional Wazwan/Indian Dinner',
                'transport_info' => 'Private Cab',
            ],
            [
                'day_number' => 6,
                'title' => 'Departure from Srinagar Airport',
                'description' => 'Relish a warm breakfast on the houseboat deck. Transfer to Srinagar Airport with sweet memories of the Himalayan wonderland.',
                'hotel_info' => 'Checkout',
                'meals_info' => 'Breakfast',
                'transport_info' => 'Airport Drop Cab',
            ],
        ];

        foreach ($itineraries as $iti) {
            PackageItinerary::firstOrCreate([
                'package_id' => $pkgKashmir->id,
                'day_number' => $iti['day_number'],
            ], array_merge($iti, ['package_id' => $pkgKashmir->id]));
        }

        PackageDeparture::firstOrCreate([
            'package_id' => $pkgKashmir->id,
            'departure_date' => Carbon::now()->addDays(15)->toDateString(),
        ], [
            'package_id' => $pkgKashmir->id,
            'departure_date' => Carbon::now()->addDays(15)->toDateString(),
            'return_date' => Carbon::now()->addDays(20)->toDateString(),
            'total_seats' => 20,
            'available_seats' => 12,
            'price_override' => 21999.00,
            'status' => 'open',
        ]);

        // Dubai Package
        $pkgDubai = TourPackage::firstOrCreate(['slug' => 'dubai-desert-safari-burj-khalifa-dhow-cruise'], [
            'destination_id' => $destDubai->id,
            'category_id' => $catInternational->id,
            'package_code' => 'PKG-DXB-002',
            'title' => 'Dazzling Dubai: Burj Khalifa, Desert Safari BBQ & Marina Dhow Cruise',
            'slug' => 'dubai-desert-safari-burj-khalifa-dhow-cruise',
            'type' => 'international',
            'duration_days' => 5,
            'duration_nights' => 4,
            'starting_price' => 45000.00,
            'discount_price' => 39999.00,
            'adult_price' => 39999.00,
            'child_price' => 29999.00,
            'infant_price' => 9999.00,
            'departure_city' => 'Delhi / Mumbai / Bangalore (Direct Flight Available)',
            'pickup_drop_info' => 'Dubai International Airport (DXB) return transfers included',
            'featured_image' => 'https://images.unsplash.com/photo-1512453979798-5ea266f8880c?auto=format&fit=crop&w=800&q=80',
            'gallery' => [
                'https://images.unsplash.com/photo-1512453979798-5ea266f8880c?auto=format&fit=crop&w=800&q=80',
                'https://images.unsplash.com/photo-1546412414-e1885259563a?auto=format&fit=crop&w=800&q=80',
            ],
            'highlights' => [
                '4 Nights in 4-Star Bur Dubai/Deira Hotel with daily breakfast',
                'Entry ticket to Burj Khalifa 124th & 125th Floor Observation Deck (Non-prime hours)',
                'Desert Safari in 4x4 Land Cruiser with dune bashing, camel ride & BBQ dinner show',
                'Marina luxury Dhow Cruise dinner with Tanoura dance entertainment',
                'Half-day Dubai city tour with Dubai Frame and Jumeirah Beach photo stop',
            ],
            'inclusions' => [
                '4 Nights Hotel Stay with breakfast',
                'Airport pick & drop from DXB',
                'Desert Safari with BBQ dinner & belly dance show',
                'Burj Khalifa At The Top tickets',
                'Marina Dhow Cruise dinner',
                'Tourism Dirham taxes included',
            ],
            'exclusions' => [
                'International flights (can be bundled upon request)',
                'UAE tourist visa fee (₹6,500 assistance available)',
                'Lunch & extra personal shopping expenses',
                '5% TCS and 5% GST',
            ],
            'terms_conditions' => 'Passport must be valid for at least 6 months from travel date. Visa approval is subject to GDRFA/ICP rules.',
            'cancellation_policy' => 'Non-refundable within 10 days of travel due to attraction advance ticketing.',
            'min_group_size' => 1,
            'max_group_size' => 40,
            'difficulty_level' => 'Easy',
            'is_featured' => true,
            'is_active' => true,
            'status' => 'published',
            'tags' => ['Dubai', 'Burj Khalifa', 'Desert Safari', 'Luxury', 'International'],
            'seo_title' => 'Dubai 4N/5D Holiday Package with Burj Khalifa & Safari',
            'meta_description' => 'Complete Dubai holiday package with Burj Khalifa tickets, Desert Safari, Marina Cruise and luxury hotels.',
        ]);

        // 10. Lead Sources & Leads
        $srcWebsite = LeadSource::firstOrCreate(['slug' => 'website'], ['name' => 'Website Enquiry', 'slug' => 'website']);
        $srcWhatsApp = LeadSource::firstOrCreate(['slug' => 'whatsapp'], ['name' => 'WhatsApp Chat', 'slug' => 'whatsapp']);
        $srcGoogle = LeadSource::firstOrCreate(['slug' => 'google'], ['name' => 'Google Search / Ads', 'slug' => 'google']);

        $lead1 = Lead::firstOrCreate(['lead_number' => 'LEAD-2026-001'], [
            'lead_number' => 'LEAD-2026-001',
            'customer_id' => $customer->id,
            'name' => 'Rahul Sharma',
            'email' => 'rahul.sharma@gmail.com',
            'phone' => '+91 98765 43210',
            'departure_city' => 'Bengaluru',
            'destination' => 'Kashmir',
            'lead_source_id' => $srcWebsite->id,
            'assigned_to' => $salesUser->id,
            'branch_id' => $branchMumbai->id,
            'category' => 'holiday',
            'priority' => 'high',
            'status' => 'converted',
            'budget' => 60000.00,
            'adults' => 2,
            'children' => 0,
            'infants' => 0,
            'travel_start_date' => Carbon::now()->addDays(20),
            'travel_end_date' => Carbon::now()->addDays(26),
            'notes' => 'Anniversary trip, wants houseboat stay and flower decoration in room.',
            'created_by' => $salesUser->id,
        ]);

        LeadFollowup::firstOrCreate(['lead_id' => $lead1->id, 'followup_date' => Carbon::now()->subDays(2)], [
            'lead_id' => $lead1->id,
            'user_id' => $salesUser->id,
            'followup_date' => Carbon::now()->subDays(2),
            'status' => 'completed',
            'remarks' => 'Discussed flight options and sent customized itinerary with houseboat upgrade.',
        ]);

        LeadActivity::firstOrCreate(['lead_id' => $lead1->id, 'summary' => 'Quotation Sent'], [
            'lead_id' => $lead1->id,
            'user_id' => $salesUser->id,
            'type' => 'quotation',
            'summary' => 'Quotation Sent',
            'details' => 'Detailed 5N/6D quotation sent via WhatsApp and Email for ₹55,000 all inclusive.',
        ]);

        $lead2 = Lead::firstOrCreate(['lead_number' => 'LEAD-2026-002'], [
            'lead_number' => 'LEAD-2026-002',
            'name' => 'Meera Nair',
            'email' => 'meera.nair@hotmail.com',
            'phone' => '+91 98450 11223',
            'departure_city' => 'Kochi',
            'destination' => 'Dubai',
            'lead_source_id' => $srcWhatsApp->id,
            'assigned_to' => $salesUser->id,
            'branch_id' => $branchDelhi->id,
            'category' => 'international',
            'priority' => 'urgent',
            'status' => 'quotation_sent',
            'budget' => 120000.00,
            'adults' => 2,
            'children' => 1,
            'infants' => 0,
            'travel_start_date' => Carbon::now()->addDays(35),
            'travel_end_date' => Carbon::now()->addDays(40),
            'notes' => 'Looking for Dubai family package with Atlantis Aquaventure pass.',
            'created_by' => $superAdminUser->id,
        ]);

        // 11. Bookings & Accounting
        $booking = Booking::firstOrCreate(['booking_number' => 'BK-2026-1001'], [
            'booking_number' => 'BK-2026-1001',
            'customer_id' => $customer->id,
            'user_id' => $salesUser->id,
            'service_type' => 'package',
            'package_id' => $pkgKashmir->id,
            'departure_date' => Carbon::now()->addDays(20),
            'return_date' => Carbon::now()->addDays(26),
            'adults' => 2,
            'children' => 0,
            'infants' => 0,
            'selling_amount' => 43998.00,
            'discount_amount' => 2000.00,
            'tax_amount' => 2099.90, // 5% GST
            'total_amount' => 44097.90,
            'paid_amount' => 20000.00,
            'balance_amount' => 24097.90,
            'supplier_cost' => 32000.00,
            'expected_profit' => 9998.00,
            'assigned_to' => $salesUser->id,
            'booking_status' => 'confirmed',
            'payment_status' => 'partially_paid',
            'notes' => 'Advance ₹20,000 received via UPI. Houseboat room flower decoration requested.',
        ]);

        BookingPassenger::firstOrCreate(['booking_id' => $booking->id, 'first_name' => 'Rahul'], [
            'booking_id' => $booking->id,
            'first_name' => 'Rahul',
            'last_name' => 'Sharma',
            'gender' => 'Male',
            'dob' => Carbon::parse('1988-06-15'),
            'type' => 'adult',
            'passport_number' => 'Z9876543',
            'meal_preference' => 'North Indian Vegetarian',
        ]);

        BookingPassenger::firstOrCreate(['booking_id' => $booking->id, 'first_name' => 'Pooja'], [
            'booking_id' => $booking->id,
            'first_name' => 'Pooja',
            'last_name' => 'Sharma',
            'gender' => 'Female',
            'dob' => Carbon::parse('1991-09-22'),
            'type' => 'adult',
            'passport_number' => 'Z8765432',
            'meal_preference' => 'Jain Vegetarian',
        ]);

        BookingVoucher::firstOrCreate(['voucher_number' => 'VCH-KAS-1001'], [
            'booking_id' => $booking->id,
            'voucher_number' => 'VCH-KAS-1001',
            'voucher_type' => 'customer',
            'details' => [
                'package' => 'Magical Kashmir 5N/6D',
                'hotel_srinagar' => 'Grand Mamta / Similar (3 Nights)',
                'hotel_pahalgam' => 'Hotel Pine Spring / Similar (1 Night)',
                'houseboat' => 'Wangnoo Heritage Luxury Houseboat (1 Night)',
                'vehicle' => 'Toyota Innova AC (Driver: Farooq Ahmed - +91 94190 55443)',
            ],
            'generated_at' => Carbon::now(),
        ]);

        // Invoice for booking
        $invoice = Invoice::firstOrCreate(['invoice_number' => 'INV-2026-0001'], [
            'invoice_number' => 'INV-2026-0001',
            'booking_id' => $booking->id,
            'customer_id' => $customer->id,
            'financial_year' => '2026-2027',
            'invoice_date' => Carbon::now()->subDays(1),
            'due_date' => Carbon::now()->addDays(10),
            'subtotal' => 41998.00,
            'tax_id' => 1,
            'tax_rate' => 5.00,
            'cgst_amount' => 1049.95,
            'sgst_amount' => 1049.95,
            'igst_amount' => 0.00,
            'discount_amount' => 2000.00,
            'total_amount' => 44097.90,
            'paid_amount' => 20000.00,
            'balance_amount' => 24097.90,
            'status' => 'partially_paid',
            'items' => [
                ['description' => 'Magical Kashmir 5N/6D Package (2 Adults)', 'qty' => 1, 'rate' => 43998.00, 'amount' => 43998.00],
                ['description' => 'Loyalty Discount Voucher', 'qty' => 1, 'rate' => -2000.00, 'amount' => -2000.00],
            ],
            'notes' => 'Balance ₹24,097.90 due before departure.',
            'created_by' => $superAdminUser->id,
        ]);

        // Payment record
        Payment::firstOrCreate(['payment_number' => 'PAY-2026-0001'], [
            'payment_number' => 'PAY-2026-0001',
            'booking_id' => $booking->id,
            'invoice_id' => $invoice->id,
            'customer_id' => $customer->id,
            'amount' => 20000.00,
            'payment_method' => 'upi',
            'transaction_id' => 'UPI/612345678901/HDFC',
            'reference_number' => 'TXN-UPI-998811',
            'payment_date' => Carbon::now()->subDays(1),
            'type' => 'advance',
            'status' => 'completed',
            'notes' => 'Received via PhonePe UPI Gateway.',
            'created_by' => $salesUser->id,
        ]);

        // Expenses
        Expense::firstOrCreate(['expense_number' => 'EXP-2026-001'], [
            'expense_number' => 'EXP-2026-001',
            'category' => 'supplier',
            'title' => 'Advance to Srinagar Hotel Grand Mamta for BK-2026-1001',
            'amount' => 12000.00,
            'expense_date' => Carbon::now()->subDays(1),
            'branch_id' => $branchDelhi->id,
            'booking_id' => $booking->id,
            'paid_to' => 'Grand Mamta Hospitality Ltd',
            'payment_method' => 'bank_transfer',
            'status' => 'paid',
            'approved_by' => $superAdminUser->id,
            'created_by' => $salesUser->id,
        ]);

        // 12. Suppliers & Operations
        $supplierHotel = Supplier::firstOrCreate(['code' => 'SUP-HTL-KAS-01'], [
            'name' => 'Grand Mamta Hospitality Group',
            'code' => 'SUP-HTL-KAS-01',
            'category' => 'hotel',
            'contact_person' => 'Javed Mir',
            'email' => 'reservations@grandmamta.com',
            'phone' => '+91 94190 12345',
            'city' => 'Srinagar',
            'state' => 'Jammu and Kashmir',
            'gstin' => '01AAACG1234H1Z1',
            'rating' => 4.80,
            'is_active' => true,
            'notes' => 'Preferred 4-star hotel partner near Dal Lake',
        ]);

        $supplierTransport = Supplier::firstOrCreate(['code' => 'SUP-CAB-KAS-01'], [
            'name' => 'Kashmir Valley Tourist Taxi Association',
            'code' => 'SUP-CAB-KAS-01',
            'category' => 'transport',
            'contact_person' => 'Farooq Ahmed',
            'email' => 'farooq@kashmircabs.com',
            'phone' => '+91 94190 55443',
            'city' => 'Srinagar',
            'state' => 'Jammu and Kashmir',
            'rating' => 4.90,
            'is_active' => true,
        ]);

        $trip = Trip::firstOrCreate(['trip_code' => 'TRIP-KAS-2026-01'], [
            'trip_code' => 'TRIP-KAS-2026-01',
            'booking_id' => $booking->id,
            'package_id' => $pkgKashmir->id,
            'title' => 'Kashmir Romantic Odyssey - Rahul & Pooja Sharma',
            'start_date' => Carbon::now()->addDays(20),
            'end_date' => Carbon::now()->addDays(26),
            'tour_leader_id' => $salesUser->id,
            'status' => 'upcoming',
            'vehicle_info' => 'Toyota Innova Crysta (JK01-AB-1234)',
            'driver_info' => 'Farooq Ahmed (+91 94190 55443)',
            'guide_info' => 'Bilal Lone (Govt Certified Guide)',
            'special_instructions' => 'Guest anniversary on Day 3. Complimentary almond kehwa upon houseboat check-in.',
        ]);

        TripAssignment::firstOrCreate(['trip_id' => $trip->id, 'type' => 'hotel'], [
            'trip_id' => $trip->id,
            'supplier_id' => $supplierHotel->id,
            'type' => 'hotel',
            'details' => ['hotel' => 'Grand Mamta', 'nights' => 3, 'rooms' => 1, 'category' => 'Deluxe Mountain View'],
            'status' => 'confirmed',
            'cost' => 15000.00,
        ]);

        TripAssignment::firstOrCreate(['trip_id' => $trip->id, 'type' => 'vehicle'], [
            'trip_id' => $trip->id,
            'supplier_id' => $supplierTransport->id,
            'type' => 'vehicle',
            'details' => ['vehicle' => 'Toyota Innova Crysta', 'driver' => 'Farooq Ahmed', 'days' => 6],
            'status' => 'confirmed',
            'cost' => 16000.00,
        ]);

        // 13. Visa & Passport Services
        VisaApplication::firstOrCreate(['application_number' => 'VISA-2026-0001'], [
            'application_number' => 'VISA-2026-0001',
            'customer_id' => $customer->id,
            'country_id' => $countryUAE->id,
            'visa_category' => 'tourist',
            'applicant_name' => 'Rahul Sharma',
            'passport_number' => 'Z9876543',
            'passport_expiry' => Carbon::now()->addYears(5),
            'travel_date' => Carbon::now()->addDays(45),
            'fee' => 7500.00,
            'supplier_cost' => 5200.00,
            'assigned_to' => $salesUser->id,
            'status' => 'approved',
            'submission_date' => Carbon::now()->subDays(5),
            'expected_date' => Carbon::now()->subDays(1),
            'actual_date' => Carbon::now()->subDays(1),
            'remarks' => '30 Days Single Entry Tourist Visa issued by GDRFA Dubai.',
            'contact_phone' => '+91 98765 43210',
            'contact_email' => 'rahul.sharma@gmail.com',
        ]);

        PassportApplication::firstOrCreate(['application_number' => 'PASS-2026-0001'], [
            'application_number' => 'PASS-2026-0001',
            'customer_id' => $customer->id,
            'service_type' => 'renewal',
            'applicant_name' => 'Pooja Sharma',
            'gender' => 'Female',
            'dob' => Carbon::parse('1991-09-22'),
            'place_of_birth' => 'Bengaluru, Karnataka',
            'assigned_to' => $salesUser->id,
            'appointment_date' => Carbon::now()->addDays(7),
            'appointment_location' => 'Passport Seva Kendra (PSK), Lalbagh Road, Bengaluru',
            'status' => 'appointment_scheduled',
            'fee' => 2500.00,
            'remarks' => 'Document checklist verified. Slot booked for 10:30 AM.',
            'contact_phone' => '+91 98765 43210',
            'contact_email' => 'rahul.sharma@gmail.com',
        ]);

        // 14. CMS Banners, Testimonials, Blogs & FAQs
        Banner::firstOrCreate(['title' => 'Discover the Soul of Incredible India'], [
            'title' => 'Discover the Soul of Incredible India',
            'subtitle' => 'From pristine snow peaks in Kashmir to royal palaces in Rajasthan and turquoise waters of Bali.',
            'button_text' => 'Explore Holiday Packages',
            'button_url' => '/packages',
            'image' => 'https://images.unsplash.com/photo-1524492412937-b28074a5d7da?auto=format&fit=crop&w=1600&q=80',
            'position' => 'home_hero',
            'is_active' => true,
            'sort_order' => 1,
        ]);

        Banner::firstOrCreate(['title' => 'Dubai Summer & Winter Shopping Escapes'], [
            'title' => 'Dubai Summer & Winter Shopping Escapes',
            'subtitle' => 'Experience Burj Khalifa, Desert Safaris and Luxury Marina Cruises with hassle-free visa processing.',
            'button_text' => 'View Dubai Offers',
            'button_url' => '/packages/dubai-desert-safari-burj-khalifa-dhow-cruise',
            'image' => 'https://images.unsplash.com/photo-1512453979798-5ea266f8880c?auto=format&fit=crop&w=1600&q=80',
            'position' => 'home_hero',
            'is_active' => true,
            'sort_order' => 2,
        ]);

        Testimonial::firstOrCreate(['name' => 'Dr. Arvind Swaminathan'], [
            'name' => 'Dr. Arvind Swaminathan',
            'designation_or_city' => 'Senior Surgeon, Chennai',
            'rating' => 5,
            'content' => 'BharatTravel organized our family vacation to Kashmir flawlessly. The private Innova was spotless, hotels were top-class, and the Dal Lake houseboat evening was unforgettable. Highly recommended!',
            'avatar' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&w=200&q=80',
            'destination_visited' => 'Kashmir & Gulmarg',
            'is_featured' => true,
            'is_active' => true,
        ]);

        Testimonial::firstOrCreate(['name' => 'Sunita & Deepak Singhal'], [
            'name' => 'Sunita & Deepak Singhal',
            'designation_or_city' => 'Gurugram',
            'rating' => 5,
            'content' => 'Our Dubai trip was arranged down to the finest detail. Visas were approved in 48 hours, private desert safari was thrilling, and the support team was available on WhatsApp 24/7.',
            'avatar' => 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=200&q=80',
            'destination_visited' => 'Dubai & Abu Dhabi',
            'is_featured' => true,
            'is_active' => true,
        ]);

        Blog::firstOrCreate(['slug' => 'top-10-places-to-visit-in-kashmir'], [
            'title' => 'Top 10 Breathtaking Places to Visit in Kashmir in 2026',
            'slug' => 'top-10-places-to-visit-in-kashmir',
            'category' => 'Destination Guide',
            'image' => 'https://images.unsplash.com/photo-1595815771614-ade9d652a65d?auto=format&fit=crop&w=800&q=80',
            'summary' => 'From the snow-draped pine slopes of Gulmarg to the tranquil shikara rides on Dal Lake, here is your essential Kashmir itinerary.',
            'content' => '<p>Kashmir, justly acclaimed as Paradise on Earth, is one of the most stunning destinations in South Asia. Whether you are gliding on a wooden shikara or riding the high-altitude Gulmarg Gondola, the valley offers unmatched mountain beauty.</p><h3>1. Srinagar & Dal Lake</h3><p>Start your journey by staying in a heritage cedarwood houseboat on Dal or Nigeen Lake. The morning floating vegetable market is a cultural wonder.</p><h3>2. Gulmarg - Meadow of Flowers</h3><p>Gulmarg transforms into India’s premier ski destination in winter and a green golfing paradise in summer. The Gulmarg Gondola Phase 1 & 2 is a must-do.</p><h3>3. Pahalgam & Betaab Valley</h3><p>Fed by the gushing Lidder River, Pahalgam is the heart of pine forests, pony trails, and saffron valleys.</p>',
            'author' => 'Priya Sengupta, Senior Travel Writer',
            'published_at' => Carbon::now()->subWeeks(2),
            'is_published' => true,
            'seo_title' => 'Top 10 Places to Visit in Kashmir 2026 - Travel Guide',
            'meta_description' => 'Comprehensive guide to Srinagar, Gulmarg Gondola, Pahalgam valleys and houseboat stays in Kashmir.',
        ]);

        Blog::firstOrCreate(['slug' => 'dubai-visa-guide-for-indians'], [
            'title' => 'Complete Dubai Tourist Visa Guide for Indian Citizens (2026)',
            'slug' => 'dubai-visa-guide-for-indians',
            'category' => 'Visa & Travel Tips',
            'image' => 'https://images.unsplash.com/photo-1512453979798-5ea266f8880c?auto=format&fit=crop&w=800&q=80',
            'summary' => 'Everything you need to know about UAE tourist visas, 30-day vs 60-day options, document checklists, and fast processing.',
            'content' => '<p>Planning a holiday to Dubai? Indian passport holders require an electronic tourist visa prior to departure. Here is the step-by-step procedure, required documents, and processing timeline.</p><h3>Required Documents</h3><ul><li>Clear color copy of Passport Front & Back (valid for at least 6 months)</li><li>White background passport-size photograph</li><li>Confirmed return flight tickets & hotel voucher</li></ul><h3>Processing Time</h3><p>Standard processing takes 48 to 72 working hours through our authorized agency portal.</p>',
            'author' => 'Rohit Verma, Head of Visa Operations',
            'published_at' => Carbon::now()->subWeek(),
            'is_published' => true,
            'seo_title' => 'Dubai Tourist Visa for Indians 2026: Fees & Requirements',
            'meta_description' => 'Fast Dubai tourist visa for Indian travelers. Learn documents, fees, processing time and online assistance.',
        ]);

        Faq::firstOrCreate(['question' => 'How do I book a tour package with BharatTravel?'], [
            'category' => 'booking',
            'question' => 'How do I book a tour package with BharatTravel?',
            'answer' => 'You can browse any domestic or international package on our website, select your preferred departure date, and click "Book Now" or "Enquire Now". You can pay a 25% advance to secure your booking, and our assigned travel consultant will coordinate your itinerary, vouchers, and flights.',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        Faq::firstOrCreate(['question' => 'What is the payment policy for holiday packages?'], [
            'category' => 'payment',
            'question' => 'What is the payment policy for holiday packages?',
            'answer' => 'We require 25% at the time of booking confirmation, 50% thirty days prior to departure, and the remaining 25% balance fifteen days before travel. We accept UPI, Net Banking (IMPS/NEFT), Credit/Debit Cards, and Razorpay.',
            'sort_order' => 2,
            'is_active' => true,
        ]);

        Faq::firstOrCreate(['question' => 'Do you provide Visa and Passport assistance?'], [
            'category' => 'visa',
            'question' => 'Do you provide Visa and Passport assistance?',
            'answer' => 'Yes! We have a dedicated visa department assisting Indian citizens with Dubai, Thailand, Singapore, Bali, Schengen, UK, and USA visa filing, document vetting, and PSK passport appointments.',
            'sort_order' => 3,
            'is_active' => true,
        ]);

        // Coupons
        Coupon::firstOrCreate(['code' => 'BHARAT5000'], [
            'code' => 'BHARAT5000',
            'description' => 'Flat ₹5,000 off on international holiday bookings above ₹50,000',
            'discount_type' => 'fixed',
            'discount_value' => 5000.00,
            'min_spend' => 50000.00,
            'valid_from' => Carbon::now()->subMonth()->toDateString(),
            'valid_to' => Carbon::now()->addMonths(6)->toDateString(),
            'usage_limit' => 500,
            'used_count' => 12,
            'is_active' => true,
        ]);

        Coupon::firstOrCreate(['code' => 'WELCOME10'], [
            'code' => 'WELCOME10',
            'description' => '10% instant discount (up to ₹2,500) on your first holiday package',
            'discount_type' => 'percentage',
            'discount_value' => 10.00,
            'min_spend' => 15000.00,
            'max_discount' => 2500.00,
            'valid_from' => Carbon::now()->subMonth()->toDateString(),
            'valid_to' => Carbon::now()->addYear()->toDateString(),
            'usage_limit' => 1000,
            'used_count' => 45,
            'is_active' => true,
        ]);

        // General Settings
        Setting::set('site_name', 'BharatTravel ERP & Tourism', 'general', true);
        Setting::set('company_phone', '+91 11 4567 8900', 'contact', true);
        Setting::set('company_whatsapp', '+91 98100 12345', 'contact', true);
        Setting::set('company_email', 'contact@bharattravel.com', 'contact', true);
        Setting::set('company_address', 'Plot 45, Barakhamba Road, Connaught Place, New Delhi - 110001', 'contact', true);
        Setting::set('company_gstin', '07AAACB1234F1Z9', 'finance', true);
        Setting::set('default_currency', 'INR', 'finance', true);
        Setting::set('currency_symbol', '₹', 'finance', true);
        Setting::set('meta_title', 'BharatTravel | Leading Indian Tour Operator & Travel Agency ERP', 'seo', true);
        Setting::set('meta_description', 'Book domestic & international holiday packages, flight assistance, visa filing, hotel vouchers and B2B agent solutions.', 'seo', true);

        // Rich Packages and Travel Stories
        $this->call(RichPackagesAndBlogsSeeder::class);

        // Activity log
        ActivityLog::create([
            'user_id' => $superAdminUser->id,
            'action' => 'seed_system',
            'module' => 'system',
            'description' => 'Initialized master database with roles, branches, departments, packages, bookings and financial records.',
            'ip_address' => '127.0.0.1',
        ]);
    }
}
