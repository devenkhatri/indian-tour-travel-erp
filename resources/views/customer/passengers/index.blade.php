@extends('layouts.customer')

@section('title', 'Saved Travellers & Passports | BharatTravel')

@section('customer_content')
<div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-xs flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
    <div>
        <h1 class="text-xl font-bold text-slate-900">Saved Travellers & Family Members</h1>
        <p class="text-xs text-slate-500 mt-1">Pre-save passenger details and passport info for instant 1-click booking and flight ticket issuance.</p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Existing Travellers List -->
    <div class="lg:col-span-2 space-y-4">
        <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-xs">
            <h3 class="text-sm font-bold text-slate-900 mb-4 flex items-center gap-2">
                <i data-lucide="users" class="w-4 h-4 text-teal-600"></i>
                Saved Travellers ({{ $passengers->count() }})
            </h3>

            <div class="space-y-3">
                @forelse($passengers as $pax)
                    <div class="p-4 bg-slate-50 rounded-xl border border-slate-200/80 flex items-center justify-between text-xs">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-teal-100 text-teal-800 flex items-center justify-center font-bold text-sm">
                                {{ substr($pax->first_name, 0, 1) }}{{ substr($pax->last_name, 0, 1) }}
                            </div>
                            <div>
                                <h4 class="font-bold text-slate-900 text-sm">{{ $pax->first_name }} {{ $pax->last_name }}</h4>
                                <p class="text-slate-500 text-[11px]">
                                    {{ $pax->gender }} &bull; Relation: <span class="font-semibold text-slate-700">{{ $pax->relationship }}</span>
                                    @if($pax->dob) &bull; DOB: {{ \Carbon\Carbon::parse($pax->dob)->format('M d, Y') }} @endif
                                </p>
                                @if($pax->passport_number)
                                    <p class="text-[11px] text-teal-700 font-medium mt-0.5">
                                        <i data-lucide="shield" class="w-3 h-3 inline mr-0.5"></i>
                                        Passport: {{ $pax->passport_number }} (Exp: {{ $pax->passport_expiry ? \Carbon\Carbon::parse($pax->passport_expiry)->format('M Y') : 'N/A' }})
                                    </p>
                                @endif
                            </div>
                        </div>
                        <span class="px-2.5 py-1 bg-white border border-slate-200 rounded-lg text-[10px] font-bold text-slate-600">
                            Verified
                        </span>
                    </div>
                @empty
                    <div class="text-center py-8 text-slate-400">
                        <i data-lucide="user-plus" class="w-8 h-8 text-slate-300 mx-auto mb-2"></i>
                        <p>No co-travellers saved yet. Add your family members below.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Add New Traveller Form -->
    <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-xs">
        <h3 class="text-sm font-bold text-slate-900 mb-4 flex items-center gap-2">
            <i data-lucide="plus-circle" class="w-4 h-4 text-teal-600"></i>
            Add Family / Co-Traveller
        </h3>

        <form action="{{ route('customer.passengers.store') }}" method="POST" class="space-y-4 text-xs">
            @csrf
            <div>
                <label class="block font-semibold text-slate-700 mb-1">First Name *</label>
                <input type="text" name="first_name" required value="{{ old('first_name') }}"
                       class="w-full px-3 py-2 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-teal-500 text-xs"
                       placeholder="e.g. Priya">
            </div>

            <div>
                <label class="block font-semibold text-slate-700 mb-1">Last Name *</label>
                <input type="text" name="last_name" required value="{{ old('last_name') }}"
                       class="w-full px-3 py-2 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-teal-500 text-xs"
                       placeholder="e.g. Sharma">
            </div>

            <div class="grid grid-cols-2 gap-2">
                <div>
                    <label class="block font-semibold text-slate-700 mb-1">Gender *</label>
                    <select name="gender" required class="w-full px-2 py-2 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-teal-500 text-xs">
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                        <option value="Other">Other</option>
                    </select>
                </div>
                <div>
                    <label class="block font-semibold text-slate-700 mb-1">Relationship *</label>
                    <select name="relationship" required class="w-full px-2 py-2 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-teal-500 text-xs">
                        <option value="Spouse">Spouse</option>
                        <option value="Child">Child</option>
                        <option value="Parent">Parent</option>
                        <option value="Friend">Friend / Colleague</option>
                        <option value="Self">Self</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block font-semibold text-slate-700 mb-1">Date of Birth</label>
                <input type="date" name="dob" value="{{ old('dob') }}"
                       class="w-full px-3 py-2 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-teal-500 text-xs">
            </div>

            <div class="pt-2 border-t border-slate-100">
                <label class="block font-semibold text-slate-700 mb-1">Passport Number (For International)</label>
                <input type="text" name="passport_number" value="{{ old('passport_number') }}"
                       class="w-full px-3 py-2 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-teal-500 text-xs uppercase"
                       placeholder="e.g. Z9876543">
            </div>

            <div class="grid grid-cols-2 gap-2">
                <div>
                    <label class="block font-semibold text-slate-700 mb-1">Passport Expiry</label>
                    <input type="date" name="passport_expiry" value="{{ old('passport_expiry') }}"
                           class="w-full px-2 py-2 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-teal-500 text-xs">
                </div>
                <div>
                    <label class="block font-semibold text-slate-700 mb-1">Issuing Country</label>
                    <input type="text" name="passport_country" value="{{ old('passport_country', 'India') }}"
                           class="w-full px-2 py-2 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-teal-500 text-xs">
                </div>
            </div>

            <button type="submit" class="w-full py-2.5 bg-teal-600 hover:bg-teal-700 text-white font-bold rounded-xl text-xs transition shadow-xs flex items-center justify-center gap-2">
                <i data-lucide="save" class="w-3.5 h-3.5"></i> Save Traveller
            </button>
        </form>
    </div>
</div>
@endsection

