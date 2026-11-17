<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Customer;
use App\Models\Expense;
use App\Models\Lead;
use App\Models\LeadFollowup;
use App\Models\PassportApplication;
use App\Models\Payment;
use App\Models\TourPackage;
use App\Models\VisaApplication;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        // Core KPI Counts
        $totalCustomers = Customer::count();
        $totalLeads = Lead::count();
        $newEnquiries = Lead::where('status', 'new')->count();
        $convertedLeads = Lead::where('status', 'converted')->count();

        $totalBookings = Booking::count();
        $confirmedBookings = Booking::where('booking_status', 'confirmed')->count();
        $pendingBookings = Booking::whereIn('booking_status', ['enquiry', 'quotation', 'pending_payment'])->count();
        $cancelledBookings = Booking::where('booking_status', 'cancelled')->count();

        // Financial KPIs
        $totalRevenue = Booking::where('booking_status', '!=', 'cancelled')->sum('total_amount');
        $totalPaidRevenue = Payment::where('status', 'completed')->where('type', '!=', 'refund')->sum('amount');
        $totalExpenses = Expense::where('status', 'paid')->sum('amount');
        $grossProfit = max(0, $totalRevenue - $totalExpenses);
        $outstandingPayments = Booking::where('booking_status', '!=', 'cancelled')->where('balance_amount', '>', 0)->sum('balance_amount');

        // Operational Alerts
        $upcomingDepartures = Booking::where('booking_status', 'confirmed')
            ->whereBetween('departure_date', [now()->toDateString(), now()->addDays(7)->toDateString()])
            ->with(['customer', 'package'])
            ->orderBy('departure_date', 'asc')
            ->get();

        $pendingVisas = VisaApplication::whereNotIn('status', ['approved', 'rejected', 'delivered', 'cancelled'])->count();
        $pendingPassports = PassportApplication::whereNotIn('status', ['completed', 'cancelled'])->count();

        $upcomingFollowups = LeadFollowup::where('status', 'pending')
            ->where('followup_date', '<=', now()->addDays(2))
            ->with(['lead', 'user'])
            ->orderBy('followup_date', 'asc')
            ->take(5)
            ->get();

        // Recent Activity
        $recentBookings = Booking::with(['customer', 'package'])
            ->orderBy('created_at', 'desc')
            ->take(6)
            ->get();

        $recentLeads = Lead::with(['source', 'assignedEmployee'])
            ->orderBy('created_at', 'desc')
            ->take(6)
            ->get();

        // Top Performing Packages
        $topPackages = TourPackage::withCount('bookings')
            ->orderBy('bookings_count', 'desc')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalCustomers',
            'totalLeads',
            'newEnquiries',
            'convertedLeads',
            'totalBookings',
            'confirmedBookings',
            'pendingBookings',
            'cancelledBookings',
            'totalRevenue',
            'totalPaidRevenue',
            'totalExpenses',
            'grossProfit',
            'outstandingPayments',
            'upcomingDepartures',
            'pendingVisas',
            'pendingPassports',
            'upcomingFollowups',
            'recentBookings',
            'recentLeads',
            'topPackages'
        ));
    }
}
