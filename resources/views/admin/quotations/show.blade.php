@extends('layouts.admin')

@section('title', 'Quotation Proposal: ' . $quotation->quotation_number)

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-xs flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <a href="{{ route('admin.quotations.index') }}" class="text-xs font-bold text-slate-500 hover:text-slate-900">&larr; Back to Quotations</a>
                <span class="text-slate-300">•</span>
                <span class="text-xs font-bold text-amber-600 uppercase">#{{ $quotation->quotation_number }}</span>
                <span class="text-slate-300">•</span>
                <span class="text-xs text-slate-500">{{ $quotation->created_at->format('M d, Y') }}</span>
            </div>
            <h1 class="text-2xl font-bold font-serif text-slate-900">{{ $quotation->title }}</h1>
            <p class="text-xs text-slate-500 mt-0.5">
                Client: <a href="{{ route('admin.customers.show', $quotation->customer_id) }}" class="font-bold text-slate-900 hover:text-amber-600">{{ $quotation->customer?->name }}</a>
                &bull; Destination: <span class="font-bold text-slate-800">{{ $quotation->destination }}</span>
                &bull; Adults: <span class="font-bold text-slate-800">{{ $quotation->adults }}</span>
            </p>
        </div>

        <div class="flex flex-wrap items-center gap-2">
            <a href="{{ route('admin.quotations.pdf.download', $quotation->id) }}" class="px-3.5 py-2 bg-slate-900 hover:bg-black text-white rounded-xl text-xs font-bold transition shadow-xs flex items-center gap-1.5">
                <i data-lucide="download" class="w-3.5 h-3.5"></i> Download PDF Proposal
            </a>

            @if($quotation->status !== 'converted')
                <form action="{{ route('admin.quotations.convert', $quotation->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="px-3.5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold transition shadow-xs flex items-center gap-1.5">
                        <i data-lucide="check-circle" class="w-3.5 h-3.5"></i> Convert to Confirmed Booking
                    </button>
                </form>
            @else
                <span class="px-3.5 py-2 bg-emerald-50 border border-emerald-200 text-emerald-800 font-bold text-xs rounded-xl flex items-center gap-1.5">
                    <i data-lucide="check" class="w-3.5 h-3.5"></i> Converted to Booking
                </span>
            @endif
        </div>
    </div>

    <!-- Commercial Breakdown & Margin Cards -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-xs">
            <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Total Client Quote</span>
            <div class="text-xl font-extrabold text-slate-900 mt-1">₹{{ number_format($quotation->total_amount, 2) }}</div>
            <p class="text-[10px] text-slate-400 mt-0.5">Includes {{ $quotation->tax_rate }}% GST</p>
        </div>

        <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-xs">
            <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Estimated Supplier Cost</span>
            <div class="text-xl font-extrabold text-slate-700 mt-1">₹{{ number_format($quotation->supplier_cost, 2) }}</div>
            <p class="text-[10px] text-slate-400 mt-0.5">Hotels, transport & tickets</p>
        </div>

        <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-xs">
            <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Gross Margin Profit</span>
            <div class="text-xl font-extrabold text-emerald-600 mt-1">₹{{ number_format($quotation->profit_margin, 2) }}</div>
            <p class="text-[10px] text-emerald-600 font-semibold mt-0.5">
                {{ $quotation->subtotal > 0 ? round(($quotation->profit_margin / $quotation->subtotal) * 100, 1) : 0 }}% Margin
            </p>
        </div>

        <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-xs">
            <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Proposal Validity</span>
            <div class="text-xl font-extrabold text-amber-600 mt-1">
                {{ $quotation->valid_until ? \Carbon\Carbon::parse($quotation->valid_until)->format('M d, Y') : '14 Days' }}
            </div>
            <p class="text-[10px] text-slate-400 mt-0.5">Status: {{ ucfirst($quotation->status) }}</p>
        </div>
    </div>

    <!-- Proposal Details Preview Card -->
    <div class="bg-white rounded-2xl p-8 border border-slate-200 shadow-xs space-y-6">
        <!-- Client Details -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pb-6 border-b border-slate-100 text-xs">
            <div>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Prepared For</p>
                <h3 class="font-bold text-slate-900 text-base mt-0.5">{{ $quotation->customer?->name }}</h3>
                <p class="text-slate-500 mt-0.5">{{ $quotation->customer?->phone }} &bull; {{ $quotation->customer?->email }}</p>
                @if($quotation->customer?->city)
                    <p class="text-slate-400">{{ $quotation->customer->city }}, {{ $quotation->customer->state }}</p>
                @endif
            </div>

            <div class="sm:text-right">
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Prepared By</p>
                <h3 class="font-bold text-slate-900 text-base mt-0.5">{{ $quotation->creator?->name ?? 'BharatTravel Sales Desk' }}</h3>
                <p class="text-slate-500 mt-0.5">BharatTravel Tourism & DMC India</p>
                <p class="text-slate-400">GST SAC 9985 Tour Operator</p>
            </div>
        </div>

        <!-- Itinerary Items -->
        @if(is_array($quotation->itinerary_details) && count($quotation->itinerary_details) > 0)
            <div>
                <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider mb-3">Planned Day-by-Day Schedule</h3>
                <div class="space-y-2 text-xs">
                    @foreach($quotation->itinerary_details as $idx => $line)
                        <div class="p-3 bg-slate-50 rounded-xl border border-slate-100 flex items-start gap-3">
                            <span class="w-5 h-5 rounded-full bg-amber-500 text-slate-950 font-bold flex items-center justify-center text-[10px] shrink-0 mt-0.5">
                                {{ $idx + 1 }}
                            </span>
                            <p class="text-slate-800 leading-relaxed">{{ $line }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Inclusions & Exclusions Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 pt-4 border-t border-slate-100 text-xs">
            <div>
                <h4 class="font-bold text-slate-900 mb-2 flex items-center gap-1.5 text-emerald-700">
                    <i data-lucide="check" class="w-4 h-4"></i> Inclusions
                </h4>
                <ul class="space-y-1.5 text-slate-600 list-disc list-inside">
                    @if(is_array($quotation->inclusions))
                        @foreach($quotation->inclusions as $inc)
                            <li>{{ $inc }}</li>
                        @endforeach
                    @endif
                </ul>
            </div>

            <div>
                <h4 class="font-bold text-slate-900 mb-2 flex items-center gap-1.5 text-rose-700">
                    <i data-lucide="x" class="w-4 h-4"></i> Exclusions
                </h4>
                <ul class="space-y-1.5 text-slate-600 list-disc list-inside">
                    @if(is_array($quotation->exclusions))
                        @foreach($quotation->exclusions as $exc)
                            <li>{{ $exc }}</li>
                        @endforeach
                    @endif
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
