@extends('layouts.agent')

@section('title', 'My Client Bookings | BharatTravel')

@section('content')
<div class="space-y-6">
    <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-xs flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-xl font-bold text-slate-900">Agency Client Bookings</h1>
            <p class="text-xs text-slate-500 mt-1">Review all holiday packages booked on behalf of your agency clients.</p>
        </div>
        <a href="{{ route('agent.packages') }}" class="px-4 py-2 bg-teal-600 hover:bg-teal-700 text-white text-xs font-bold rounded-xl transition shadow-xs flex items-center gap-2">
            <i data-lucide="plus" class="w-4 h-4"></i> New Client Booking
        </a>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-50 text-slate-500 font-semibold uppercase text-[10px] tracking-wider border-b border-slate-200">
                    <tr>
                        <th class="py-3.5 px-6">Booking Number</th>
                        <th class="py-3.5 px-6">Client Details</th>
                        <th class="py-3.5 px-6">Tour Package</th>
                        <th class="py-3.5 px-6">Departure</th>
                        <th class="py-3.5 px-6">Pax</th>
                        <th class="py-3.5 px-6">Invoice Total</th>
                        <th class="py-3.5 px-6">Payment Status</th>
                        <th class="py-3.5 px-6">Trip Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($bookings as $bk)
                        <tr class="hover:bg-slate-50/50">
                            <td class="py-4 px-6 font-bold text-slate-900">{{ $bk->booking_number }}</td>
                            <td class="py-4 px-6">
                                <span class="font-bold text-slate-900 block">{{ $bk->customer ? $bk->customer->name : 'N/A' }}</span>
                                <span class="text-[11px] text-slate-400">{{ $bk->customer?->phone }}</span>
                            </td>
                            <td class="py-4 px-6">
                                <span class="font-semibold text-slate-800">{{ $bk->package ? $bk->package->title : 'Custom Package' }}</span>
                                <span class="text-[11px] text-slate-400 block">{{ $bk->package?->destination?->name }}</span>
                            </td>
                            <td class="py-4 px-6">
                                {{ $bk->departure_date ? \Carbon\Carbon::parse($bk->departure_date)->format('M d, Y') : 'Open' }}
                            </td>
                            <td class="py-4 px-6">
                                {{ $bk->adults }} Adults {{ $bk->children > 0 ? '+ ' . $bk->children . ' Ch' : '' }}
                            </td>
                            <td class="py-4 px-6 font-bold text-slate-900">
                                ₹{{ number_format($bk->total_amount, 2) }}
                            </td>
                            <td class="py-4 px-6">
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold 
                                    {{ $bk->payment_status == 'paid' ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800' }}">
                                    {{ ucfirst($bk->payment_status) }}
                                </span>
                            </td>
                            <td class="py-4 px-6">
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold 
                                    {{ $bk->booking_status == 'confirmed' ? 'bg-teal-100 text-teal-800' : 'bg-slate-100 text-slate-700' }}">
                                    {{ ucfirst($bk->booking_status) }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-12 text-center text-slate-400">
                                <i data-lucide="ticket" class="w-10 h-10 text-slate-300 mx-auto mb-2"></i>
                                <p class="font-medium text-slate-600">No client bookings yet</p>
                                <p class="text-[11px] mt-1">Select a package from B2B Package Tariffs to book on behalf of your clients.</p>
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
</div>
@endsection

