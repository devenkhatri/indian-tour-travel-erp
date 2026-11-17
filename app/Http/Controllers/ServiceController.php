<?php

namespace App\Http\Controllers;

use App\Models\BusBooking;
use App\Models\Country;
use App\Models\FlightEnquiry;
use App\Models\HotelBooking;
use App\Models\Lead;
use App\Models\LeadActivity;
use App\Models\PassportApplication;
use App\Models\TrainBooking;
use App\Models\VisaApplication;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ServiceController extends Controller
{
    public function index(): View
    {
        return view('pages.services.index');
    }

    public function visa(): View
    {
        $countries = Country::where('is_active', true)->get();

        return view('pages.services.visa', compact('countries'));
    }

    public function submitVisa(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'applicant_name' => 'required|string|max:150',
            'contact_phone' => 'required|string|max:25',
            'contact_email' => 'required|email|max:150',
            'country_id' => 'required|exists:countries,id',
            'visa_category' => 'required|string',
            'passport_number' => 'nullable|string|max:50',
            'travel_date' => 'nullable|date',
            'remarks' => 'nullable|string',
        ]);

        $appNumber = 'VISA-'.date('Y').'-'.strtoupper(Str::random(6));

        $customer = Auth::check() ? Auth::user()->customer : null;

        VisaApplication::create([
            'application_number' => $appNumber,
            'customer_id' => $customer ? $customer->id : null,
            'country_id' => $validated['country_id'],
            'visa_category' => $validated['visa_category'],
            'applicant_name' => $validated['applicant_name'],
            'passport_number' => $validated['passport_number'] ?? null,
            'travel_date' => $validated['travel_date'] ?? null,
            'status' => 'new',
            'contact_phone' => $validated['contact_phone'],
            'contact_email' => $validated['contact_email'],
            'remarks' => $validated['remarks'] ?? null,
        ]);

        // Auto-generate CRM lead
        $lead = Lead::create([
            'lead_number' => 'LEAD-'.date('Y').'-'.rand(1000, 9999),
            'customer_id' => $customer ? $customer->id : null,
            'name' => $validated['applicant_name'],
            'email' => $validated['contact_email'],
            'phone' => $validated['contact_phone'],
            'destination' => 'Visa Application: '.$appNumber,
            'category' => 'visa',
            'priority' => 'high',
            'status' => 'new',
            'notes' => "Visa Assistance Application #{$appNumber}. ".($validated['remarks'] ?? ''),
        ]);

        LeadActivity::create([
            'lead_id' => $lead->id,
            'type' => 'status_change',
            'summary' => "New Visa Lead: {$appNumber}",
            'details' => 'Customer submitted online visa enquiry. Document checklist required.',
        ]);

        return back()->with('success', "Your Visa assistance request has been submitted under reference #{$appNumber}. Our visa desk will contact you shortly with the required checklist.");
    }

    public function passport(): View
    {
        return view('pages.services.passport');
    }

    public function submitPassport(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'applicant_name' => 'required|string|max:150',
            'contact_phone' => 'required|string|max:25',
            'contact_email' => 'required|email|max:150',
            'service_type' => 'required|string|in:fresh,renewal,reissue,tatkal',
            'place_of_birth' => 'nullable|string|max:100',
            'remarks' => 'nullable|string',
        ]);

        $appNumber = 'PASS-'.date('Y').'-'.strtoupper(Str::random(6));
        $customer = Auth::check() ? Auth::user()->customer : null;

        PassportApplication::create([
            'application_number' => $appNumber,
            'customer_id' => $customer ? $customer->id : null,
            'service_type' => $validated['service_type'],
            'applicant_name' => $validated['applicant_name'],
            'place_of_birth' => $validated['place_of_birth'] ?? null,
            'status' => 'enquiry',
            'contact_phone' => $validated['contact_phone'],
            'contact_email' => $validated['contact_email'],
            'remarks' => $validated['remarks'] ?? null,
        ]);

        Lead::create([
            'lead_number' => 'LEAD-'.date('Y').'-'.rand(1000, 9999),
            'customer_id' => $customer ? $customer->id : null,
            'name' => $validated['applicant_name'],
            'email' => $validated['contact_email'],
            'phone' => $validated['contact_phone'],
            'destination' => 'Passport Assistance',
            'category' => 'passport',
            'priority' => 'medium',
            'status' => 'new',
            'notes' => "Passport Service ({$validated['service_type']}) application #{$appNumber}",
        ]);

        return back()->with('success', "Your Passport assistance inquiry #{$appNumber} has been received. Our executive will reach out to help schedule your PSK appointment.");
    }

    public function flights(): View
    {
        return view('pages.services.flights');
    }

    public function submitFlight(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'contact_name' => 'required|string|max:150',
            'contact_phone' => 'required|string|max:25',
            'contact_email' => 'required|email|max:150',
            'trip_type' => 'required|string|in:one_way,round_trip,multi_city',
            'departure_airport' => 'required|string|max:100',
            'arrival_airport' => 'required|string|max:100',
            'departure_date' => 'required|date|after_or_equal:today',
            'return_date' => 'nullable|date',
            'adults' => 'required|integer|min:1',
            'children' => 'nullable|integer|min:0',
            'infants' => 'nullable|integer|min:0',
            'cabin_class' => 'required|string|in:economy,premium_economy,business,first',
            'preferred_airline' => 'nullable|string|max:100',
            'remarks' => 'nullable|string',
        ]);

        $flight = FlightEnquiry::create([
            'trip_type' => $validated['trip_type'],
            'departure_airport' => $validated['departure_airport'],
            'arrival_airport' => $validated['arrival_airport'],
            'departure_date' => $validated['departure_date'],
            'return_date' => $validated['return_date'] ?? null,
            'adults' => $validated['adults'],
            'children' => $validated['children'] ?? 0,
            'infants' => $validated['infants'] ?? 0,
            'cabin_class' => $validated['cabin_class'],
            'preferred_airline' => $validated['preferred_airline'] ?? null,
            'contact_name' => $validated['contact_name'],
            'contact_email' => $validated['contact_email'],
            'contact_phone' => $validated['contact_phone'],
            'remarks' => $validated['remarks'] ?? null,
            'status' => 'enquiry',
        ]);

        Lead::create([
            'lead_number' => 'LEAD-'.date('Y').'-'.rand(1000, 9999),
            'name' => $validated['contact_name'],
            'email' => $validated['contact_email'],
            'phone' => $validated['contact_phone'],
            'departure_city' => $validated['departure_airport'],
            'destination' => 'Flight: '.$validated['departure_airport'].' -> '.$validated['arrival_airport'],
            'category' => 'flight',
            'priority' => 'high',
            'status' => 'new',
            'travel_start_date' => $validated['departure_date'],
            'adults' => $validated['adults'],
            'notes' => "Flight Enquiry #{$flight->id}. Cabin: {$validated['cabin_class']}. ".($validated['remarks'] ?? ''),
        ]);

        return back()->with('success', 'Your flight booking enquiry has been received! Our flight ticketing desk will send optimal routing and best fare quotes within 30 minutes.');
    }

    public function hotels(): View
    {
        return view('pages.services.hotels');
    }

    public function submitHotel(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'contact_name' => 'required|string|max:150',
            'contact_phone' => 'required|string|max:25',
            'contact_email' => 'required|email|max:150',
            'city' => 'required|string|max:100',
            'hotel_name' => 'nullable|string|max:200',
            'check_in_date' => 'required|date|after_or_equal:today',
            'check_out_date' => 'required|date|after:check_in_date',
            'rooms_count' => 'required|integer|min:1',
            'adults' => 'required|integer|min:1',
            'children' => 'nullable|integer|min:0',
            'room_type' => 'nullable|string|max:100',
            'meal_plan' => 'nullable|string|max:50',
        ]);

        HotelBooking::create([
            'city' => $validated['city'],
            'hotel_name' => $validated['hotel_name'] ?? 'Hotel in '.$validated['city'],
            'check_in_date' => $validated['check_in_date'],
            'check_out_date' => $validated['check_out_date'],
            'rooms_count' => $validated['rooms_count'],
            'adults' => $validated['adults'],
            'children' => $validated['children'] ?? 0,
            'room_type' => $validated['room_type'] ?? 'Deluxe Room',
            'meal_plan' => $validated['meal_plan'] ?? 'CP (Breakfast Included)',
            'contact_name' => $validated['contact_name'],
            'contact_phone' => $validated['contact_phone'],
            'contact_email' => $validated['contact_email'],
            'status' => 'pending',
        ]);

        Lead::create([
            'lead_number' => 'LEAD-'.date('Y').'-'.rand(1000, 9999),
            'name' => $validated['contact_name'],
            'email' => $validated['contact_email'],
            'phone' => $validated['contact_phone'],
            'destination' => 'Hotel: '.$validated['city'],
            'category' => 'hotel',
            'priority' => 'medium',
            'status' => 'new',
            'travel_start_date' => $validated['check_in_date'],
            'travel_end_date' => $validated['check_out_date'],
            'adults' => $validated['adults'],
            'notes' => "Hotel booking request for {$validated['rooms_count']} room(s) in {$validated['city']}",
        ]);

        return back()->with('success', 'Your hotel reservation request has been submitted. Our contracted hotel specialists will verify room availability and confirm.');
    }

    public function trains(): View
    {
        return view('pages.services.trains');
    }

    public function submitTrain(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'contact_name' => 'required|string|max:150',
            'contact_phone' => 'required|string|max:25',
            'from_station' => 'required|string|max:100',
            'to_station' => 'required|string|max:100',
            'journey_date' => 'required|date|after_or_equal:today',
            'class_type' => 'required|string|max:30',
            'passenger_count' => 'required|integer|min:1',
            'remarks' => 'nullable|string',
        ]);

        TrainBooking::create([
            'from_station' => $validated['from_station'],
            'to_station' => $validated['to_station'],
            'journey_date' => $validated['journey_date'],
            'class_type' => $validated['class_type'],
            'passenger_count' => $validated['passenger_count'],
            'contact_name' => $validated['contact_name'],
            'contact_phone' => $validated['contact_phone'],
            'remarks' => $validated['remarks'] ?? null,
            'status' => 'enquiry',
        ]);

        return back()->with('success', 'Train booking assistance request registered! Our rail desk will contact you regarding availability and ticket booking.');
    }

    public function buses(): View
    {
        return view('pages.services.buses');
    }

    public function submitBus(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'contact_name' => 'required|string|max:150',
            'contact_phone' => 'required|string|max:25',
            'from_city' => 'required|string|max:100',
            'to_city' => 'required|string|max:100',
            'journey_date' => 'required|date|after_or_equal:today',
            'bus_type' => 'required|string|max:30',
            'passenger_count' => 'required|integer|min:1',
            'remarks' => 'nullable|string',
        ]);

        BusBooking::create([
            'from_city' => $validated['from_city'],
            'to_city' => $validated['to_city'],
            'journey_date' => $validated['journey_date'],
            'bus_type' => $validated['bus_type'],
            'passenger_count' => $validated['passenger_count'],
            'contact_name' => $validated['contact_name'],
            'contact_phone' => $validated['contact_phone'],
            'remarks' => $validated['remarks'] ?? null,
            'status' => 'enquiry',
        ]);

        return back()->with('success', 'Bus ticket booking request received! We will check luxury Volvo/sleeper coach availability and get back to you.');
    }

    public function customTour(): View
    {
        return view('pages.services.custom_tour');
    }

    public function submitCustomTour(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:150',
            'email' => 'required|email|max:150',
            'phone' => 'required|string|max:25',
            'departure_city' => 'nullable|string|max:100',
            'destination' => 'required|string|max:150',
            'travel_start_date' => 'required|date|after_or_equal:today',
            'travel_end_date' => 'nullable|date|after_or_equal:travel_start_date',
            'adults' => 'required|integer|min:1',
            'children' => 'nullable|integer|min:0',
            'infants' => 'nullable|integer|min:0',
            'budget' => 'nullable|numeric|min:0',
            'hotel_category' => 'nullable|string|max:50',
            'transport_preference' => 'nullable|string|max:50',
            'special_requirements' => 'nullable|string',
        ]);

        $leadNumber = 'LEAD-'.date('Y').'-'.rand(1000, 9999);

        $customer = Auth::check() ? Auth::user()->customer : null;

        $lead = Lead::create([
            'lead_number' => $leadNumber,
            'customer_id' => $customer ? $customer->id : null,
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'departure_city' => $validated['departure_city'] ?? null,
            'destination' => $validated['destination'],
            'category' => 'custom_holiday',
            'priority' => 'high',
            'status' => 'new',
            'budget' => $validated['budget'] ?? null,
            'adults' => $validated['adults'],
            'children' => $validated['children'] ?? 0,
            'infants' => $validated['infants'] ?? 0,
            'travel_start_date' => $validated['travel_start_date'],
            'travel_end_date' => $validated['travel_end_date'] ?? null,
            'notes' => 'Customized holiday enquiry. Hotel preference: '.($validated['hotel_category'] ?? 'Standard').'. Transport: '.($validated['transport_preference'] ?? 'Sedan/SUV').'. Notes: '.($validated['special_requirements'] ?? 'None'),
        ]);

        LeadActivity::create([
            'lead_id' => $lead->id,
            'type' => 'status_change',
            'summary' => 'Custom Tour Enquiry Generated',
            'details' => "Lead created from website Custom Tour Planner for {$validated['destination']}.",
        ]);

        return back()->with('success', "Thank you! Your customized itinerary request #{$leadNumber} has been handed over to our holiday architects. A personalized quote with hotel choices will be shared with you shortly.");
    }
}
