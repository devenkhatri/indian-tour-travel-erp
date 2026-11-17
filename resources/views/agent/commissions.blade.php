@extends('layouts.agent')

@section('title', 'Commission Statements | BharatTravel')

@section('content')
<div class="space-y-6">
    <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-xs flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-xl font-bold text-slate-900">Agency Commission Statements</h1>
            <p class="text-xs text-slate-500 mt-1">Detailed audit trail of contracted commissions earned per booking.</p>
        </div>
        <div class="flex items-center gap-2">
            <span class="px-3 py-1.5 rounded-xl bg-purple-50 border border-purple-200 text-purple-800 text-xs font-bold">
                Contracted Rate: {{ $agent->commission_rate }}%
            </span>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-50 text-slate-500 font-semibold uppercase text-[10px] tracking-wider border-b border-slate-200">
                    <tr>
                        <th class="py-3.5 px-6">Statement Date</th>
                        <th class="py-3.5 px-6">Booking Reference</th>
                        <th class="py-3.5 px-6">Client / Package</th>
                        <th class="py-3.5 px-6">Booking Net Value</th>
                        <th class="py-3.5 px-6">Commission %</th>
                        <th class="py-3.5 px-6">Credited Amount</th>
                        <th class="py-3.5 px-6">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($commissions as $comm)
                        <tr class="hover:bg-slate-50/50">
                            <td class="py-4 px-6">{{ $comm->created_at->format('M d, Y') }}</td>
                            <td class="py-4 px-6 font-bold text-slate-900">#{{ $comm->booking?->booking_number }}</td>
                            <td class="py-4 px-6">
                                <span class="font-bold text-slate-900 block">{{ $comm->booking?->customer?->name }}</span>
                                <span class="text-[11px] text-slate-400">{{ $comm->booking?->package?->title }}</span>
                            </td>
                            <td class="py-4 px-6 font-semibold text-slate-800">
                                ₹{{ number_format($comm->booking_amount, 2) }}
                            </td>
                            <td class="py-4 px-6 font-bold text-slate-700">
                                {{ $comm->commission_percent }}%
                            </td>
                            <td class="py-4 px-6 font-extrabold text-emerald-700">
                                + ₹{{ number_format($comm->commission_amount, 2) }}
                            </td>
                            <td class="py-4 px-6">
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold 
                                    {{ $comm->status == 'credited' ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }}">
                                    {{ ucfirst($comm->status) }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-12 text-center text-slate-400">
                                <i data-lucide="percent" class="w-10 h-10 text-slate-300 mx-auto mb-2"></i>
                                <p class="font-medium text-slate-600">No commissions earned yet</p>
                                <p class="text-[11px] mt-1">Book tours for your travellers to earn immediate automated commissions.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($commissions->hasPages())
            <div class="p-4 border-t border-slate-100">
                {{ $commissions->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

