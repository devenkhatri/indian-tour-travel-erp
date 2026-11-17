@extends('layouts.admin')

@section('title', 'Supplier Profile: ' . $supplier->name)

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-xs flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <a href="{{ route('admin.suppliers.index') }}" class="text-xs font-bold text-slate-500 hover:text-slate-900">&larr; Back to Suppliers</a>
                <span class="text-slate-300">•</span>
                <span class="text-xs font-bold text-amber-600 uppercase">#{{ $supplier->code }}</span>
                <span class="text-slate-300">•</span>
                <span class="text-xs text-slate-500 uppercase">{{ $supplier->category }}</span>
            </div>
            <h1 class="text-2xl font-bold font-serif text-slate-900">{{ $supplier->name }}</h1>
            <p class="text-xs text-slate-500 mt-0.5">
                Contact: <span class="font-bold text-slate-800">{{ $supplier->contact_person ?? 'Direct' }}</span>
                &bull; Phone: <span class="text-slate-800">{{ $supplier->phone ?? 'N/A' }}</span>
                &bull; City: <span class="text-slate-800">{{ $supplier->city }}, {{ $supplier->state }}</span>
            </p>
        </div>

        <div class="flex items-center gap-2">
            <a href="{{ route('admin.suppliers.edit', $supplier->id) }}" class="px-3.5 py-2 bg-amber-500 hover:bg-amber-600 text-slate-950 font-bold rounded-xl text-xs transition shadow-xs flex items-center gap-1.5">
                <i data-lucide="edit" class="w-3.5 h-3.5"></i> Edit Supplier
            </a>
        </div>
    </div>

    <!-- Fulfillment History -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
            <h3 class="text-sm font-bold text-slate-900">Trip Service Fulfillments ({{ $supplier->assignments->count() }})</h3>
            <span class="text-xs font-bold text-slate-900">Total Procured: ₹{{ number_format($supplier->assignments->sum('cost'), 2) }}</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-50 text-slate-500 font-semibold uppercase text-[10px] tracking-wider border-b border-slate-200">
                    <tr>
                        <th class="py-3.5 px-6">Trip Code</th>
                        <th class="py-3.5 px-6">Booking Ref</th>
                        <th class="py-3.5 px-6">Service Type</th>
                        <th class="py-3.5 px-6">Procurement Cost</th>
                        <th class="py-3.5 px-6">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($supplier->assignments as $asg)
                        <tr class="hover:bg-slate-50/50">
                            <td class="py-3.5 px-6 font-bold text-slate-900">
                                <a href="{{ route('admin.operations.show', $asg->trip_id) }}" class="text-amber-600 hover:text-amber-700">
                                    {{ $asg->trip?->trip_code }}
                                </a>
                            </td>
                            <td class="py-3.5 px-6 font-semibold text-slate-800">
                                #{{ $asg->trip?->booking?->booking_number }}
                            </td>
                            <td class="py-3.5 px-6 uppercase text-[10px] font-bold text-slate-700">
                                {{ $asg->type }}
                            </td>
                            <td class="py-3.5 px-6 font-bold text-slate-900">
                                ₹{{ number_format($asg->cost, 2) }}
                            </td>
                            <td class="py-3.5 px-6">
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-100 text-emerald-800 uppercase">
                                    {{ $asg->status }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-8 text-center text-slate-400">
                                No trip assignments yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

