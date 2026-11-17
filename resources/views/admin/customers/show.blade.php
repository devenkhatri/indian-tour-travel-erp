@extends('layouts.admin')

@section('title', 'Customer 360 Dossier: ' . $customer->name)

@section('content')
<div class="space-y-6">
    <!-- Dossier Header -->
    <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-xs flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <a href="{{ route('admin.customers.index') }}" class="text-xs font-bold text-slate-500 hover:text-slate-900">&larr; Back to Directory</a>
                <span class="text-slate-300">•</span>
                <span class="text-xs font-bold text-amber-600 uppercase">#{{ $customer->customer_code }}</span>
                <span class="text-slate-300">•</span>
                <span class="text-xs text-slate-500">Member since {{ $customer->created_at->format('M Y') }}</span>
            </div>
            <h1 class="text-2xl font-bold font-serif text-slate-900">{{ $customer->name }}</h1>
            <p class="text-xs text-slate-500 mt-0.5">
                Phone: <span class="font-bold text-slate-800">{{ $customer->phone }}</span>
                &bull; Email: <span class="text-slate-700">{{ $customer->email ?? 'N/A' }}</span>
                @if($customer->city) &bull; Location: <span class="text-slate-700">{{ $customer->city }}, {{ $customer->state }}</span> @endif
            </p>
        </div>

        <div class="flex items-center gap-2">
            <a href="{{ route('admin.customers.edit', $customer->id) }}" class="px-3.5 py-2 bg-slate-100 hover:bg-slate-200 text-slate-800 rounded-xl text-xs font-bold transition flex items-center gap-1.5">
                <i data-lucide="edit-3" class="w-3.5 h-3.5"></i> Edit Profile
            </a>
            <a href="{{ route('admin.quotations.create', ['customer_id' => $customer->id]) }}" class="px-3.5 py-2 bg-amber-500 hover:bg-amber-600 text-slate-950 rounded-xl text-xs font-bold transition shadow-xs flex items-center gap-1.5">
                <i data-lucide="file-text" class="w-3.5 h-3.5"></i> Create Quotation
            </a>
        </div>
    </div>

    <!-- Metric Summary Grid -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs">
            <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Total Bookings</span>
            <div class="text-2xl font-extrabold text-slate-900 mt-1">{{ $customer->bookings->count() }} Trips</div>
            <p class="text-[11px] text-slate-400 mt-1">Confirmed Vacation Tours</p>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs">
            <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Lifetime Spends</span>
            <div class="text-2xl font-extrabold text-emerald-600 mt-1">₹{{ number_format($customer->bookings->sum('total_amount'), 2) }}</div>
            <p class="text-[11px] text-slate-400 mt-1">Paid: ₹{{ number_format($customer->payments->sum('amount'), 2) }}</p>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs">
            <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Saved Family Pax</span>
            <div class="text-2xl font-extrabold text-slate-900 mt-1">{{ $customer->passengers->count() }} Members</div>
            <p class="text-[11px] text-slate-400 mt-1">Co-travellers on File</p>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs">
            <span class="text-xs font-bold uppercase tracking-wider text-slate-400">CRM Inquiries</span>
            <div class="text-2xl font-extrabold text-blue-600 mt-1">{{ $customer->leads->count() }} Leads</div>
            <p class="text-[11px] text-slate-400 mt-1">Quotes: {{ $customer->quotations->count() }}</p>
        </div>
    </div>

    <!-- Main 2-Column Section -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left 2 Cols: Bookings & Financial Invoices -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Bookings History -->
            <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                    <h3 class="text-sm font-bold text-slate-900 flex items-center gap-2">
                        <i data-lucide="ticket" class="w-4 h-4 text-amber-500"></i> Tour Bookings History
                    </h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs text-slate-600">
                        <thead class="bg-slate-50 text-slate-500 font-semibold uppercase text-[10px] tracking-wider border-b border-slate-200">
                            <tr>
                                <th class="py-3 px-6">Booking Ref</th>
                                <th class="py-3 px-6">Package</th>
                                <th class="py-3 px-6">Departure</th>
                                <th class="py-3 px-6">Total Amount</th>
                                <th class="py-3 px-6">Status</th>
                                <th class="py-3 px-6 text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($customer->bookings as $bk)
                                <tr class="hover:bg-slate-50/50">
                                    <td class="py-3.5 px-6 font-bold text-slate-900">
                                        <a href="{{ route('admin.bookings.show', $bk->id) }}" class="text-amber-600 hover:text-amber-700">
                                            {{ $bk->booking_number }}
                                        </a>
                                    </td>
                                    <td class="py-3.5 px-6 font-semibold text-slate-800">{{ $bk->package ? $bk->package->title : 'Custom Package' }}</td>
                                    <td class="py-3.5 px-6">{{ $bk->departure_date ? \Carbon\Carbon::parse($bk->departure_date)->format('M d, Y') : 'Open' }}</td>
                                    <td class="py-3.5 px-6 font-bold text-slate-900">₹{{ number_format($bk->total_amount, 2) }}</td>
                                    <td class="py-3.5 px-6">
                                        <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-100 text-emerald-800">
                                            {{ ucfirst($bk->booking_status) }}
                                        </span>
                                    </td>
                                    <td class="py-3.5 px-6 text-right">
                                        <a href="{{ route('admin.bookings.show', $bk->id) }}" class="text-amber-600 hover:text-amber-800 font-bold">
                                            View &rarr;
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="py-8 text-center text-slate-400">
                                        No bookings recorded for this customer.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Tax Invoices -->
            <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                    <h3 class="text-sm font-bold text-slate-900 flex items-center gap-2">
                        <i data-lucide="file-text" class="w-4 h-4 text-teal-600"></i> Invoices & Tax SAC 9985
                    </h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs text-slate-600">
                        <thead class="bg-slate-50 text-slate-500 font-semibold uppercase text-[10px] tracking-wider border-b border-slate-200">
                            <tr>
                                <th class="py-3 px-6">Invoice Number</th>
                                <th class="py-3 px-6">Date</th>
                                <th class="py-3 px-6">Taxable Value</th>
                                <th class="py-3 px-6">GST Amount</th>
                                <th class="py-3 px-6">Total (INR)</th>
                                <th class="py-3 px-6 text-right">PDF</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($customer->invoices as $inv)
                                <tr class="hover:bg-slate-50/50">
                                    <td class="py-3.5 px-6 font-bold text-slate-900">{{ $inv->invoice_number }}</td>
                                    <td class="py-3.5 px-6">{{ \Carbon\Carbon::parse($inv->invoice_date)->format('M d, Y') }}</td>
                                    <td class="py-3.5 px-6">₹{{ number_format($inv->subtotal, 2) }}</td>
                                    <td class="py-3.5 px-6">₹{{ number_format($inv->tax_amount, 2) }}</td>
                                    <td class="py-3.5 px-6 font-bold text-slate-900">₹{{ number_format($inv->total_amount, 2) }}</td>
                                    <td class="py-3.5 px-6 text-right">
                                        <a href="{{ route('admin.accounts.invoice.pdf', $inv->id) }}" class="text-teal-600 hover:text-teal-800 font-bold inline-flex items-center gap-1">
                                            <i data-lucide="download" class="w-3 h-3"></i> PDF
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="py-8 text-center text-slate-400">
                                        No invoices issued yet.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Right Col: Saved Family & Add Passenger Form -->
        <div class="space-y-6">
            <!-- Saved Passengers List -->
            <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-xs text-xs">
                <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider mb-4 flex items-center gap-2">
                    <i data-lucide="users" class="w-4 h-4 text-amber-500"></i> Family Passengers ({{ $customer->passengers->count() }})
                </h3>
                <div class="space-y-3">
                    @forelse($customer->passengers as $pax)
                        <div class="p-3 bg-slate-50 rounded-xl border border-slate-100">
                            <p class="font-bold text-slate-900">{{ $pax->first_name }} {{ $pax->last_name }}</p>
                            <p class="text-[11px] text-slate-500 mt-0.5">
                                {{ $pax->gender }} &bull; Rel: {{ $pax->relationship }}
                                @if($pax->passport_number) &bull; PPT: <span class="font-bold text-teal-700">{{ $pax->passport_number }}</span> @endif
                            </p>
                        </div>
                    @empty
                        <p class="text-slate-400">No co-passengers registered.</p>
                    @endforelse
                </div>
            </div>

            <!-- Add Co-Passenger Form -->
            <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-xs text-xs">
                <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider mb-3 flex items-center gap-2">
                    <i data-lucide="user-plus" class="w-4 h-4 text-amber-500"></i> Add Family Member
                </h3>
                <form action="{{ route('admin.customers.passengers.store', $customer->id) }}" method="POST" class="space-y-3">
                    @csrf
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">First Name *</label>
                        <input type="text" name="first_name" required class="w-full px-3 py-2 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-amber-500 text-xs">
                    </div>
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Last Name *</label>
                        <input type="text" name="last_name" required class="w-full px-3 py-2 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-amber-500 text-xs">
                    </div>
                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <label class="block font-semibold text-slate-700 mb-1">Gender *</label>
                            <select name="gender" required class="w-full px-2 py-2 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-amber-500 text-xs">
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                        <div>
                            <label class="block font-semibold text-slate-700 mb-1">Relationship *</label>
                            <input type="text" name="relationship" required placeholder="e.g. Spouse" class="w-full px-2 py-2 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-amber-500 text-xs">
                        </div>
                    </div>
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Passport Number (Optional)</label>
                        <input type="text" name="passport_number" class="w-full px-3 py-2 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-amber-500 text-xs uppercase" placeholder="Z1234567">
                    </div>
                    <button type="submit" class="w-full py-2 bg-slate-900 hover:bg-black text-white font-bold rounded-xl text-xs transition">
                        Save Co-Passenger
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

