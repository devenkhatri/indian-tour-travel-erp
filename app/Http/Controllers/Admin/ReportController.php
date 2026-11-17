<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Lead;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;

class ReportController extends Controller
{
    public function index(Request $request): View
    {
        $startDate = $request->get('start_date', now()->startOfYear()->toDateString());
        $endDate = $request->get('end_date', now()->toDateString());

        $bookings = Booking::whereBetween('created_at', [$startDate.' 00:00:00', $endDate.' 23:59:59'])->get();
        $totalBilled = $bookings->sum('total_amount');
        $totalPaid = $bookings->sum('paid_amount');
        $totalBalance = $bookings->sum('balance_amount');
        $totalCost = $bookings->sum('supplier_cost');
        $estimatedProfit = max(0, $totalBilled - $totalCost);

        $leads = Lead::whereBetween('created_at', [$startDate.' 00:00:00', $endDate.' 23:59:59'])->get();
        $leadsCount = $leads->count();
        $convertedCount = $leads->where('status', 'converted')->count();
        $conversionRate = $leadsCount > 0 ? round(($convertedCount / $leadsCount) * 100, 1) : 0;

        $packageSales = Booking::select('package_id', \DB::raw('count(*) as count'), \DB::raw('sum(total_amount) as total_revenue'))
            ->whereNotNull('package_id')
            ->whereBetween('created_at', [$startDate.' 00:00:00', $endDate.' 23:59:59'])
            ->groupBy('package_id')
            ->with('package.destination')
            ->orderBy('total_revenue', 'desc')
            ->take(8)
            ->get();

        return view('admin.reports.index', compact(
            'startDate',
            'endDate',
            'totalBilled',
            'totalPaid',
            'totalBalance',
            'totalCost',
            'estimatedProfit',
            'leadsCount',
            'convertedCount',
            'conversionRate',
            'packageSales'
        ));
    }

    public function exportBookingsCsv(Request $request): Response
    {
        $bookings = Booking::with(['customer', 'package'])->orderBy('created_at', 'desc')->get();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="BharatTravel_Bookings_'.date('Y-m-d').'.csv"',
        ];

        $callback = function () use ($bookings) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Booking Number', 'Customer Name', 'Phone', 'Service Type', 'Package', 'Travel Date', 'Total Amount (INR)', 'Paid (INR)', 'Balance (INR)', 'Status']);

            foreach ($bookings as $b) {
                fputcsv($file, [
                    $b->booking_number,
                    $b->customer ? $b->customer->name : 'N/A',
                    $b->customer ? $b->customer->phone : 'N/A',
                    strtoupper($b->service_type),
                    $b->package ? $b->package->title : 'Custom Reservation',
                    $b->departure_date ? $b->departure_date->format('Y-m-d') : 'TBD',
                    $b->total_amount,
                    $b->paid_amount,
                    $b->balance_amount,
                    strtoupper($b->booking_status),
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
