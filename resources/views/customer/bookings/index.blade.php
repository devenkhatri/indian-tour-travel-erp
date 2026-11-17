@extends('layouts.customer')

@section('title', 'My Tour Bookings | BharatTravel')

@section('customer_content')
<div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-xs flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
    <div>
        <h1 class="text-xl font-bold text-slate-900">My Tour Bookings</h1>
        <p class="text-xs text-slate-500 mt-1">Access confirmed tour confirmations, passenger lists, and download official travel vouchers & tax invoices.</p>
    </div>
    <a href="{{ route('packages.index') }}" class="px-4 py-2 bg-teal-600 hover:bg-teal-700 text-white text-xs font-bold rounded-xl transition shadow-xs flex items-center gap-2">
        <i data-lucide="plus" class="w-4 h-4"></i> Explore More Packages
    </a>
</div>

<div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left text-xs text-slate-600">
            <thead class="bg-slate-50 text-slate-500 font-semibold uppercase text-[10px] tracking-wider border-b border-slate-200">
                <tr>
                    <th class="py-3.5 px-6">Booking Ref</th>
                    <th class="py-3.5 px-6">Tour Package</th>
                    <th class="py-3.5 px-6">Departure Date</th>
                    <th class="py-3.5 px-6">Travellers</th>
                    <th class="py-3.5 px-6">Total Amount</th>
                    <th class="py-3.5 px-6">Balance Due</th>
                    <th class="py-3.5 px-6">Status</th>
                    <th class="py-3.5 px-6 text-right">Details</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($bookings as $booking)
                    <tr class="hover:bg-slate-50/50">
                        <td class="py-4 px-6 font-bold text-slate-900">
                            {{ $booking->booking_reference }}
                        </td>
                        <td class="py-4 px-6">
                            <span class="font-bold text-slate-800 block">{{ $booking->package ? $booking->package->title : 'Custom Package' }}</span>
                            <span class="text-[11px] text-slate-400">{{ $booking->package?->duration_days }}D / {{ $booking->package?->duration_nights }}N</span>
                        </td>
                        <td class="py-4 px-6">
                            {{ $booking->departure_date ? \Carbon\Carbon::parse($booking->departure_date)->format('D, M d, Y') : 'Open Date' }}
                        </td>
                        <td class="py-4 px-6">
                            {{ $booking->adults_count }} Adults {{ $booking->children_count > 0 ? '+ ' . $booking->children_count . ' Child' : '' }}
                        </td>
                        <td class="py-4 px-6 font-bold text-slate-900">
                            ₹{{ number_format($booking->total_amount, 2) }}
                        </td>
                        <td class="py-4 px-6 font-semibold {{ $booking->balance_amount > 0 ? 'text-amber-600' : 'text-emerald-600' }}">
                            ₹{{ number_format($booking->balance_amount, 2) }}
                        </td>
                        <td class="py-4 px-6">
                            <span class="inline-block px-2.5 py-1 rounded-full text-[10px] font-bold 
                                {{ $booking->booking_status == 'confirmed' ? 'bg-teal-100 text-teal-800' : ($booking->booking_status == 'cancelled' ? 'bg-rose-100 text-rose-800' : 'bg-amber-100 text-amber-800') }}">
                                {{ ucfirst($booking->booking_status) }}
                            </span>
                        </td>
                        <td class="py-4 px-6 text-right">
                            <a href="{{ route('customer.bookings.show', $booking->id) }}" class="inline-flex items-center gap-1 px-3 py-1.5 bg-teal-50 hover:bg-teal-100 text-teal-700 font-bold rounded-lg transition">
                                Dossier <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="py-12 text-center text-slate-400">
                            <i data-lucide="calendar" class="w-10 h-10 text-slate-300 mx-auto mb-2"></i>
                            <p class="font-medium text-slate-600">No bookings found</p>
                            <p class="text-[11px] mt-1">Start planning your next getaway with BharatTravel.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($bookings->hasPages())
        <div class="p-4 border-t border-slate-100">
            {{ $bookings->links() }}
        </div>
    @endif
</div>
@endsection

