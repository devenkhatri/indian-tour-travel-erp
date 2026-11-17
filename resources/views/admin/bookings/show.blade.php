@extends('layouts.admin')

@section('title', 'Booking Control Console: ' . $booking->booking_number)

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-xs flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <a href="{{ route('admin.bookings.index') }}" class="text-xs font-bold text-slate-500 hover:text-slate-900">&larr; Back to Bookings</a>
                <span class="text-slate-300">•</span>
                <span class="text-xs font-bold text-amber-600 uppercase">#{{ $booking->booking_number }}</span>
                <span class="text-slate-300">•</span>
                <span class="text-xs text-slate-500">{{ $booking->created_at->format('M d, Y h:i A') }}</span>
            </div>
            <h1 class="text-2xl font-bold font-serif text-slate-900">{{ $booking->package ? $booking->package->title : 'Custom Reservation' }}</h1>
            <p class="text-xs text-slate-500 mt-0.5">
                Guest: <a href="{{ route('admin.customers.show', $booking->customer_id) }}" class="font-bold text-slate-900 hover:text-amber-600">{{ $booking->customer?->name }}</a>
                &bull; Departure: <span class="font-bold text-slate-800">{{ $booking->departure_date ? \Carbon\Carbon::parse($booking->departure_date)->format('D, M d, Y') : 'Open' }}</span>
                &bull; Pax: <span class="font-bold text-slate-800">{{ $booking->adults }} Adults {{ $booking->children > 0 ? '+ ' . $booking->children . ' Children' : '' }}</span>
            </p>
        </div>

        <div class="flex flex-wrap items-center gap-2">
            <!-- Voucher Generator Form -->
            <form action="{{ route('admin.bookings.voucher.generate', $booking->id) }}" method="POST">
                @csrf
                <button type="submit" class="px-3.5 py-2 bg-teal-600 hover:bg-teal-700 text-white rounded-xl text-xs font-bold transition shadow-xs flex items-center gap-1.5">
                    <i data-lucide="ticket" class="w-3.5 h-3.5"></i> Generate Travel Voucher
                </button>
            </form>

            <!-- Issue Tax Invoice Form -->
            @if($booking->invoices->count() == 0)
                <form action="{{ route('admin.bookings.invoice.issue', $booking->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="px-3.5 py-2 bg-slate-900 hover:bg-black text-white rounded-xl text-xs font-bold transition shadow-xs flex items-center gap-1.5">
                        <i data-lucide="file-text" class="w-3.5 h-3.5"></i> Issue Tax Invoice
                    </button>
                </form>
            @endif
        </div>
    </div>

    <!-- 4 Mini Financial Status Cards -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-xs">
            <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Total Invoice Value</span>
            <div class="text-xl font-extrabold text-slate-900 mt-1">₹{{ number_format($booking->total_amount, 2) }}</div>
            <p class="text-[10px] text-slate-400 mt-0.5">Base: ₹{{ number_format($booking->selling_amount, 2) }} + GST</p>
        </div>

        <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-xs">
            <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Total Paid to Date</span>
            <div class="text-xl font-extrabold text-emerald-600 mt-1">₹{{ number_format($booking->paid_amount, 2) }}</div>
            <p class="text-[10px] text-emerald-600 font-semibold mt-0.5">{{ ucfirst($booking->payment_status) }}</p>
        </div>

        <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-xs">
            <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Balance Outstanding</span>
            <div class="text-xl font-extrabold {{ $booking->balance_amount > 0 ? 'text-rose-600' : 'text-slate-900' }} mt-1">
                ₹{{ number_format($booking->balance_amount, 2) }}
            </div>
            <p class="text-[10px] text-slate-400 mt-0.5">Due from guest/agent</p>
        </div>

        <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-xs">
            <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Trip & Booking State</span>
            <div class="text-xl font-extrabold text-teal-700 mt-1">{{ ucfirst($booking->booking_status) }}</div>
            <p class="text-[10px] text-slate-400 mt-0.5">{{ $booking->agent ? 'B2B: ' . $booking->agent->agency_name : 'Direct Guest' }}</p>
        </div>
    </div>

    <!-- Main 3-Column Work Area -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Col 1 & 2: Passengers, Itinerary, Vouchers & Invoices -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Passenger Manifest -->
            <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-xs">
                <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider mb-4 flex items-center gap-2">
                    <i data-lucide="users" class="w-4 h-4 text-amber-500"></i> Passenger Manifest ({{ $booking->passengers->count() }})
                </h3>
                <div class="divide-y divide-slate-100 text-xs">
                    @forelse($booking->passengers as $idx => $pax)
                        <div class="py-3 flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <span class="w-6 h-6 rounded-full bg-slate-100 text-slate-600 font-bold flex items-center justify-center text-[10px]">
                                    {{ $idx + 1 }}
                                </span>
                                <div>
                                    <p class="font-bold text-slate-900">{{ $pax->first_name }} {{ $pax->last_name }}</p>
                                    <p class="text-[11px] text-slate-400">
                                        {{ $pax->gender }}
                                        @if($pax->passport_number) &bull; PPT: {{ $pax->passport_number }} @endif
                                    </p>
                                </div>
                            </div>
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-slate-100 text-slate-700 uppercase">
                                {{ $pax->type ?? 'Adult' }}
                            </span>
                        </div>
                    @empty
                        <p class="text-slate-400 py-2">No passengers listed yet.</p>
                    @endforelse
                </div>
            </div>

            <!-- Generated Vouchers List -->
            <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                    <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider flex items-center gap-2">
                        <i data-lucide="ticket" class="w-4 h-4 text-teal-600"></i> Issued Travel Vouchers ({{ $booking->vouchers->count() }})
                    </h3>
                </div>
                <div class="divide-y divide-slate-100 p-4 space-y-2 text-xs">
                    @forelse($booking->vouchers as $vch)
                        <div class="p-3 bg-slate-50 rounded-xl border border-slate-100 flex items-center justify-between">
                            <div>
                                <p class="font-bold text-slate-900">Voucher Ref: {{ $vch->voucher_number }}</p>
                                <p class="text-[10px] text-slate-400">Generated: {{ $vch->created_at->format('M d, Y h:i A') }}</p>
                            </div>
                            <a href="{{ route('customer.voucher.download', $vch->id) }}" class="px-3 py-1.5 bg-teal-600 hover:bg-teal-700 text-white rounded-lg font-bold flex items-center gap-1">
                                <i data-lucide="download" class="w-3 h-3"></i> Download PDF
                            </a>
                        </div>
                    @empty
                        <p class="text-slate-400 py-3 text-center">No vouchers issued yet. Click "Generate Travel Voucher" above.</p>
                    @endforelse
                </div>
            </div>

            <!-- Invoices List -->
            <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                    <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider flex items-center gap-2">
                        <i data-lucide="file-text" class="w-4 h-4 text-slate-700"></i> Official Tax Invoices ({{ $booking->invoices->count() }})
                    </h3>
                </div>
                <div class="divide-y divide-slate-100 p-4 space-y-2 text-xs">
                    @forelse($booking->invoices as $inv)
                        <div class="p-3 bg-slate-50 rounded-xl border border-slate-100 flex items-center justify-between">
                            <div>
                                <p class="font-bold text-slate-900">{{ $inv->invoice_number }} &bull; ₹{{ number_format($inv->total_amount, 2) }}</p>
                                <p class="text-[10px] text-slate-400">Date: {{ \Carbon\Carbon::parse($inv->invoice_date)->format('M d, Y') }} &bull; GST SAC 9985</p>
                            </div>
                            <a href="{{ route('admin.accounts.invoice.pdf', $inv->id) }}" class="px-3 py-1.5 bg-slate-900 hover:bg-black text-white rounded-lg font-bold flex items-center gap-1">
                                <i data-lucide="download" class="w-3 h-3"></i> Invoice PDF
                            </a>
                        </div>
                    @empty
                        <p class="text-slate-400 py-3 text-center">No tax invoices issued yet. Click "Issue Tax Invoice" above.</p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Col 3: Collect Payment Form & Status Management -->
        <div class="space-y-6">
            <!-- Collect Payment Card -->
            <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-xs text-xs">
                <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider mb-4 flex items-center gap-2">
                    <i data-lucide="credit-card" class="w-4 h-4 text-emerald-600"></i> Record Guest Payment
                </h3>

                @if($booking->balance_amount > 0)
                    <form action="{{ route('admin.bookings.payment.collect', $booking->id) }}" method="POST" class="space-y-3">
                        @csrf
                        <div>
                            <label class="block font-semibold text-slate-700 mb-1">Amount to Collect (₹) *</label>
                            <input type="number" step="0.01" name="amount" max="{{ $booking->balance_amount }}" required value="{{ $booking->balance_amount }}"
                                   class="w-full px-3 py-2 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-emerald-500 text-xs font-bold">
                            <span class="text-[10px] text-slate-400">Max collectable: ₹{{ number_format($booking->balance_amount, 2) }}</span>
                        </div>

                        <div>
                            <label class="block font-semibold text-slate-700 mb-1">Payment Method *</label>
                            <select name="payment_method" required class="w-full px-3 py-2 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-emerald-500 text-xs">
                                <option value="upi">UPI / QR Code</option>
                                <option value="bank_transfer" selected>NEFT / RTGS / Bank Transfer</option>
                                <option value="credit_card">Credit Card (POS/Gateway)</option>
                                <option value="debit_card">Debit Card</option>
                                <option value="cash">Cash (Receipt at Branch)</option>
                                <option value="cheque">Cheque</option>
                            </select>
                        </div>

                        <div>
                            <label class="block font-semibold text-slate-700 mb-1">Transaction Ref / UTR *</label>
                            <input type="text" name="transaction_id" placeholder="Bank UTR or Cheque #" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs">
                        </div>

                        <div class="grid grid-cols-2 gap-2">
                            <div>
                                <label class="block font-semibold text-slate-700 mb-1">Payment Date *</label>
                                <input type="date" name="payment_date" required value="{{ date('Y-m-d') }}" class="w-full px-2 py-2 rounded-xl border border-slate-300 text-xs">
                            </div>
                            <div>
                                <label class="block font-semibold text-slate-700 mb-1">Type *</label>
                                <select name="type" required class="w-full px-2 py-2 rounded-xl border border-slate-300 text-xs">
                                    <option value="full">Full Settlement</option>
                                    <option value="advance">Advance</option>
                                    <option value="partial">Partial</option>
                                </select>
                            </div>
                        </div>

                        <button type="submit" class="w-full py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl text-xs transition shadow-xs">
                            Record & Issue Receipt
                        </button>
                    </form>
                @else
                    <div class="p-4 bg-emerald-50 rounded-xl border border-emerald-200 text-center text-emerald-800">
                        <i data-lucide="check-circle-2" class="w-8 h-8 text-emerald-600 mx-auto mb-1"></i>
                        <p class="font-bold">Fully Settled</p>
                        <p class="text-[11px] mt-0.5">Zero balance due on this booking.</p>
                    </div>
                @endif

                <!-- Payment History Log -->
                <div class="mt-4 pt-4 border-t border-slate-100 space-y-2">
                    <p class="font-bold text-slate-800 text-[11px] uppercase tracking-wider">Payment Log ({{ $booking->payments->count() }})</p>
                    @forelse($booking->payments as $pay)
                        <div class="p-2.5 bg-slate-50 rounded-xl flex items-center justify-between text-xs">
                            <div>
                                <p class="font-bold text-slate-900">₹{{ number_format($pay->amount, 2) }}</p>
                                <p class="text-[10px] text-slate-400">{{ $pay->payment_number }} &bull; {{ $pay->payment_method }}</p>
                            </div>
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-100 text-emerald-800">
                                {{ ucfirst($pay->status) }}
                            </span>
                        </div>
                    @empty
                        <p class="text-slate-400 py-1">No payments recorded.</p>
                    @endforelse
                </div>
            </div>

            <!-- Booking Status Updater -->
            <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-xs text-xs">
                <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider mb-3 flex items-center gap-2">
                    <i data-lucide="refresh-cw" class="w-4 h-4 text-amber-500"></i> Booking Lifecycle
                </h3>
                <form action="{{ route('admin.bookings.status.update', $booking->id) }}" method="POST" class="space-y-3">
                    @csrf
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Update Status</label>
                        <select name="booking_status" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs">
                            <option value="confirmed" {{ $booking->booking_status == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                            <option value="in_progress" {{ $booking->booking_status == 'in_progress' ? 'selected' : '' }}>In Progress (Tour Active)</option>
                            <option value="completed" {{ $booking->booking_status == 'completed' ? 'selected' : '' }}>Completed (Guest Returned)</option>
                            <option value="cancelled" {{ $booking->booking_status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Remarks</label>
                        <input type="text" name="remarks" placeholder="Status change reason..." class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs">
                    </div>

                    <button type="submit" class="w-full py-2 bg-slate-900 hover:bg-black text-white font-bold rounded-xl text-xs transition">
                        Update Status
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

