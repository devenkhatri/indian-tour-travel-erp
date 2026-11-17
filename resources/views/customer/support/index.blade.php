@extends('layouts.customer')

@section('title', 'Guest Support Tickets | BharatTravel')

@section('customer_content')
<div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-xs flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
    <div>
        <h1 class="text-xl font-bold text-slate-900">Guest Support & Assistance</h1>
        <p class="text-xs text-slate-500 mt-1">Need help with voucher corrections, date modifications, or billing queries? Open a priority support ticket.</p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Existing Tickets List -->
    <div class="lg:col-span-2 space-y-4">
        <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-xs">
            <h3 class="text-sm font-bold text-slate-900 mb-4 flex items-center gap-2">
                <i data-lucide="message-square" class="w-4 h-4 text-teal-600"></i>
                Support Tickets ({{ $tickets->count() }})
            </h3>

            <div class="divide-y divide-slate-100">
                @forelse($tickets as $tck)
                    <div class="py-4 flex items-center justify-between text-xs">
                        <div>
                            <div class="flex items-center gap-2 mb-1">
                                <span class="font-bold text-slate-900 text-sm">#{{ $tck->ticket_number }}</span>
                                <span class="text-slate-300">&bull;</span>
                                <span class="text-slate-500">{{ $tck->created_at->format('M d, Y') }}</span>
                                <span class="text-slate-300">&bull;</span>
                                <span class="text-slate-500 uppercase text-[10px] font-bold">{{ $tck->category }}</span>
                            </div>
                            <h4 class="font-bold text-slate-800 text-sm hover:text-teal-600 transition">
                                <a href="{{ route('customer.support.show', $tck->id) }}">{{ $tck->subject }}</a>
                            </h4>
                            <p class="text-[11px] text-slate-400 mt-0.5">{{ $tck->messages_count }} message(s) logged</p>
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="px-2.5 py-1 rounded-full text-[10px] font-bold 
                                {{ $tck->status == 'open' ? 'bg-amber-100 text-amber-800' : ($tck->status == 'resolved' ? 'bg-emerald-100 text-emerald-800' : 'bg-blue-100 text-blue-800') }}">
                                {{ ucfirst($tck->status) }}
                            </span>
                            <a href="{{ route('customer.support.show', $tck->id) }}" class="p-2 bg-slate-50 hover:bg-slate-100 rounded-lg text-slate-600">
                                <i data-lucide="chevron-right" class="w-4 h-4"></i>
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8 text-slate-400">
                        <i data-lucide="check-circle" class="w-8 h-8 text-emerald-400 mx-auto mb-2"></i>
                        <p>No support tickets. Everything is running smoothly!</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Create Ticket Form -->
    <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-xs">
        <h3 class="text-sm font-bold text-slate-900 mb-4 flex items-center gap-2">
            <i data-lucide="plus-circle" class="w-4 h-4 text-teal-600"></i>
            Raise Support Request
        </h3>

        <form action="{{ route('customer.support.store') }}" method="POST" class="space-y-4 text-xs">
            @csrf
            <div>
                <label class="block font-semibold text-slate-700 mb-1">Subject *</label>
                <input type="text" name="subject" required value="{{ old('subject') }}"
                       class="w-full px-3 py-2 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-teal-500 text-xs"
                       placeholder="e.g. Flight baggage inquiry">
            </div>

            <div class="grid grid-cols-2 gap-2">
                <div>
                    <label class="block font-semibold text-slate-700 mb-1">Category *</label>
                    <select name="category" required class="w-full px-2 py-2 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-teal-500 text-xs">
                        <option value="Booking">Booking & Itinerary</option>
                        <option value="Billing">Billing & Invoice</option>
                        <option value="Visa">Visa Assistance</option>
                        <option value="Voucher">Travel Voucher Issue</option>
                        <option value="General">Other Inquiries</option>
                    </select>
                </div>
                <div>
                    <label class="block font-semibold text-slate-700 mb-1">Priority *</label>
                    <select name="priority" required class="w-full px-2 py-2 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-teal-500 text-xs">
                        <option value="low">Low</option>
                        <option value="medium" selected>Medium</option>
                        <option value="high">High (Urgent)</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block font-semibold text-slate-700 mb-1">Detailed Message *</label>
                <textarea name="message" rows="4" required
                          class="w-full px-3 py-2 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-teal-500 text-xs"
                          placeholder="Describe your question or issue in detail...">{{ old('message') }}</textarea>
            </div>

            <button type="submit" class="w-full py-2.5 bg-teal-600 hover:bg-teal-700 text-white font-bold rounded-xl text-xs transition shadow-xs flex items-center justify-center gap-2">
                <i data-lucide="send" class="w-3.5 h-3.5"></i> Submit Ticket
            </button>
        </form>
    </div>
</div>
@endsection

