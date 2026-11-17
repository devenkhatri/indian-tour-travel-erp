@extends('layouts.customer')

@section('title', 'My Tour Inquiries & Quotations | BharatTravel')

@section('customer_content')
<div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-xs flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
    <div>
        <h1 class="text-xl font-bold text-slate-900">My Tour Inquiries & Quotes</h1>
        <p class="text-xs text-slate-500 mt-1">Track the status of all your custom holiday planning, flight, hotel, and visa assistance requests.</p>
    </div>
    <a href="{{ route('services.custom_tour') }}" class="px-4 py-2 bg-teal-600 hover:bg-teal-700 text-white text-xs font-bold rounded-xl transition shadow-xs flex items-center gap-2">
        <i data-lucide="plus" class="w-4 h-4"></i> New Custom Trip Request
    </a>
</div>

<div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left text-xs text-slate-600">
            <thead class="bg-slate-50 text-slate-500 font-semibold uppercase text-[10px] tracking-wider border-b border-slate-200">
                <tr>
                    <th class="py-3.5 px-6">Lead ID</th>
                    <th class="py-3.5 px-6">Service / Destination</th>
                    <th class="py-3.5 px-6">Category</th>
                    <th class="py-3.5 px-6">Travel Dates</th>
                    <th class="py-3.5 px-6">Travellers</th>
                    <th class="py-3.5 px-6">Submitted Date</th>
                    <th class="py-3.5 px-6">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($inquiries as $lead)
                    <tr class="hover:bg-slate-50/50">
                        <td class="py-4 px-6 font-bold text-slate-900">
                            {{ $lead->lead_number }}
                        </td>
                        <td class="py-4 px-6">
                            <span class="font-bold text-slate-800 block">{{ $lead->destination }}</span>
                            @if($lead->notes)
                                <span class="text-[11px] text-slate-400 line-clamp-1">{{ $lead->notes }}</span>
                            @endif
                        </td>
                        <td class="py-4 px-6">
                            <span class="inline-block px-2 py-0.5 rounded text-[10px] font-bold bg-slate-100 text-slate-700 uppercase">
                                {{ $lead->category }}
                            </span>
                        </td>
                        <td class="py-4 px-6">
                            {{ $lead->travel_start_date ? \Carbon\Carbon::parse($lead->travel_start_date)->format('M d, Y') : 'Flexible' }}
                        </td>
                        <td class="py-4 px-6">
                            {{ $lead->adults ?? 1 }} Pax
                        </td>
                        <td class="py-4 px-6 text-slate-500">
                            {{ $lead->created_at->format('M d, Y') }}
                        </td>
                        <td class="py-4 px-6">
                            <span class="inline-block px-2.5 py-1 rounded-full text-[10px] font-bold 
                                {{ $lead->status == 'converted' ? 'bg-emerald-100 text-emerald-800' : ($lead->status == 'contacted' ? 'bg-blue-100 text-blue-800' : 'bg-amber-100 text-amber-800') }}">
                                {{ ucfirst($lead->status) }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="py-12 text-center text-slate-400">
                            <i data-lucide="help-circle" class="w-10 h-10 text-slate-300 mx-auto mb-2"></i>
                            <p class="font-medium text-slate-600">No active inquiries</p>
                            <p class="text-[11px] mt-1">Submit a custom tour or visa enquiry to get started.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($inquiries->hasPages())
        <div class="p-4 border-t border-slate-100">
            {{ $inquiries->links() }}
        </div>
    @endif
</div>
@endsection

