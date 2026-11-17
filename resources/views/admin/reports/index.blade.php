@extends('layouts.admin')

@section('title', 'Business Intelligence & Reports - BharatTravel CRM')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Reports & Analytics</h1>
            <p class="text-sm text-gray-500">Revenue performance, profit margins, conversion metrics and top destinations</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.reports.bookings.csv') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-sm font-semibold transition-colors shadow-sm">
                <i class="fas fa-file-csv"></i> Export Bookings CSV
            </a>
        </div>
    </div>

    <!-- Date Range Filter -->
    <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100">
        <form action="{{ route('admin.reports.index') }}" method="GET" class="flex flex-wrap items-center gap-4">
            <div class="flex items-center gap-2">
                <label class="text-xs font-semibold text-gray-700">From:</label>
                <input type="date" name="start_date" value="{{ $startDate }}" class="text-sm border border-gray-200 rounded-lg px-3 py-1.5 focus:ring-orange-500 focus:border-orange-500">
            </div>
            <div class="flex items-center gap-2">
                <label class="text-xs font-semibold text-gray-700">To:</label>
                <input type="date" name="end_date" value="{{ $endDate }}" class="text-sm border border-gray-200 rounded-lg px-3 py-1.5 focus:ring-orange-500 focus:border-orange-500">
            </div>
            <button type="submit" class="px-4 py-2 bg-gray-900 hover:bg-gray-800 text-white text-sm font-medium rounded-lg transition-colors">
                Apply Date Filter
            </button>
            <a href="{{ route('admin.reports.index') }}" class="text-xs text-gray-500 hover:text-gray-800">Reset to YTD</a>
        </form>
    </div>

    <!-- Key Metrics Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white p-5 rounded-xl border border-gray-100 shadow-sm">
            <div class="flex items-center justify-between text-gray-500 text-xs font-medium mb-2">
                <span>Total Gross Bookings</span>
                <span class="p-2 bg-blue-50 text-blue-600 rounded-lg"><i class="fas fa-receipt"></i></span>
            </div>
            <div class="text-2xl font-bold text-gray-900">₹{{ number_format($totalBilled) }}</div>
            <div class="text-xs text-gray-500 mt-1">Period total revenue billed</div>
        </div>

        <div class="bg-white p-5 rounded-xl border border-gray-100 shadow-sm">
            <div class="flex items-center justify-between text-gray-500 text-xs font-medium mb-2">
                <span>Collections / Realized</span>
                <span class="p-2 bg-emerald-50 text-emerald-600 rounded-lg"><i class="fas fa-hand-holding-usd"></i></span>
            </div>
            <div class="text-2xl font-bold text-emerald-600">₹{{ number_format($totalPaid) }}</div>
            <div class="text-xs text-gray-500 mt-1">₹{{ number_format($totalBalance) }} outstanding balance</div>
        </div>

        <div class="bg-white p-5 rounded-xl border border-gray-100 shadow-sm">
            <div class="flex items-center justify-between text-gray-500 text-xs font-medium mb-2">
                <span>Direct Supplier Costs</span>
                <span class="p-2 bg-purple-50 text-purple-600 rounded-lg"><i class="fas fa-file-invoice-dollar"></i></span>
            </div>
            <div class="text-2xl font-bold text-purple-600">₹{{ number_format($totalCost) }}</div>
            <div class="text-xs text-gray-500 mt-1">Hotels, DMC & transport costs</div>
        </div>

        <div class="bg-white p-5 rounded-xl border border-gray-100 shadow-sm">
            <div class="flex items-center justify-between text-gray-500 text-xs font-medium mb-2">
                <span>Estimated Gross Margin</span>
                <span class="p-2 bg-amber-50 text-amber-600 rounded-lg"><i class="fas fa-chart-line"></i></span>
            </div>
            <div class="text-2xl font-bold text-amber-600">₹{{ number_format($estimatedProfit) }}</div>
            <div class="text-xs text-gray-500 mt-1">
                Margin: {{ $totalBilled > 0 ? round(($estimatedProfit / $totalBilled) * 100, 1) : 0 }}%
            </div>
        </div>
    </div>

    <!-- Sales & Lead Funnel Breakdown -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Lead Funnel -->
        <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm space-y-4">
            <h3 class="font-bold text-gray-900 text-base flex items-center justify-between">
                <span>Lead Conversion Funnel</span>
                <span class="text-xs font-normal text-gray-500">{{ $startDate }} - {{ $endDate }}</span>
            </h3>

            <div class="space-y-4 pt-2">
                <div>
                    <div class="flex justify-between text-xs font-semibold text-gray-700 mb-1">
                        <span>Total Inquiries Captured</span>
                        <span>{{ $leadsCount }}</span>
                    </div>
                    <div class="w-full bg-gray-100 rounded-full h-2.5">
                        <div class="bg-blue-600 h-2.5 rounded-full" style="width: 100%"></div>
                    </div>
                </div>

                <div>
                    <div class="flex justify-between text-xs font-semibold text-gray-700 mb-1">
                        <span>Converted to Booked Deals</span>
                        <span>{{ $convertedCount }} ({{ $conversionRate }}%)</span>
                    </div>
                    <div class="w-full bg-gray-100 rounded-full h-2.5">
                        <div class="bg-emerald-500 h-2.5 rounded-full" style="width: {{ min(100, $conversionRate) }}%"></div>
                    </div>
                </div>

                <div class="p-4 bg-orange-50 rounded-lg border border-orange-100 text-xs text-orange-900 leading-relaxed">
                    <div class="font-bold mb-1 flex items-center gap-1.5">
                        <i class="fas fa-lightbulb"></i> Funnel Efficiency Insight
                    </div>
                    Your team successfully converts <strong>{{ $conversionRate }}%</strong> of all inbound enquiries into booked passengers. Follow-ups logged within 2 hours increase closure probability by 3.4x.
                </div>
            </div>
        </div>

        <!-- Top Selling Holiday Packages -->
        <div class="lg:col-span-2 bg-white p-6 rounded-xl border border-gray-100 shadow-sm space-y-4">
            <h3 class="font-bold text-gray-900 text-base flex items-center justify-between">
                <span>Top Selling Packages by Revenue</span>
                <span class="text-xs font-normal text-gray-500">Ranked by gross sales</span>
            </h3>

            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="bg-gray-50 text-gray-500 uppercase text-xs">
                        <tr>
                            <th class="px-4 py-3">Holiday Package</th>
                            <th class="px-4 py-3">Destination</th>
                            <th class="px-4 py-3 text-center">Bookings</th>
                            <th class="px-4 py-3 text-right">Gross Sales</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($packageSales as $sale)
                            <tr>
                                <td class="px-4 py-3 font-semibold text-gray-900">
                                    {{ $sale->package ? $sale->package->title : 'Customised Package' }}
                                </td>
                                <td class="px-4 py-3 text-xs text-gray-600">
                                    {{ $sale->package && $sale->package->destination ? $sale->package->destination->name : 'Multiple' }}
                                </td>
                                <td class="px-4 py-3 text-center font-medium">
                                    <span class="px-2 py-0.5 rounded bg-gray-100 text-gray-800 text-xs font-bold">{{ $sale->count }}</span>
                                </td>
                                <td class="px-4 py-3 text-right font-bold text-gray-900">
                                    ₹{{ number_format($sale->total_revenue) }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-4 py-8 text-center text-gray-500">
                                    No package bookings recorded in selected date range.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

