@extends('layouts.customer')

@section('title', 'My Profile & Preferences | BharatTravel')

@section('customer_content')
<div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-xs flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
    <div>
        <h1 class="text-xl font-bold text-slate-900">My Traveller Profile</h1>
        <p class="text-xs text-slate-500 mt-1">Keep your primary contact info, postal billing address, and passport details up to date.</p>
    </div>
    <span class="px-3 py-1 bg-teal-50 border border-teal-200 text-teal-800 rounded-xl text-xs font-bold">
        Account Status: Active
    </span>
</div>

<div class="bg-white rounded-2xl p-8 border border-slate-200 shadow-xs">
    <form action="{{ route('customer.profile.update') }}" method="POST" class="space-y-6">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-xs">
            <div>
                <label class="block font-semibold text-slate-700 uppercase tracking-wider mb-1">Full Legal Name *</label>
                <input type="text" name="name" required value="{{ old('name', $user->name) }}"
                       class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-teal-500 text-sm">
            </div>

            <div>
                <label class="block font-semibold text-slate-700 uppercase tracking-wider mb-1">Email Address</label>
                <input type="email" disabled value="{{ $user->email }}"
                       class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-100 text-slate-500 text-sm cursor-not-allowed">
            </div>

            <div>
                <label class="block font-semibold text-slate-700 uppercase tracking-wider mb-1">Mobile Phone (WhatsApp) *</label>
                <input type="tel" name="phone" required value="{{ old('phone', $user->phone) }}"
                       class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-teal-500 text-sm">
            </div>

            <div>
                <label class="block font-semibold text-slate-700 uppercase tracking-wider mb-1">Alternate Phone</label>
                <input type="tel" name="alternate_phone" value="{{ old('alternate_phone', $customer->alternate_phone) }}"
                       class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-teal-500 text-sm"
                       placeholder="+91 99999 88888">
            </div>

            <div class="md:col-span-2">
                <label class="block font-semibold text-slate-700 uppercase tracking-wider mb-1">Billing / Residential Address</label>
                <textarea name="address" rows="2"
                          class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-teal-500 text-sm">{{ old('address', $customer->address ?? $user->address) }}</textarea>
            </div>

            <div>
                <label class="block font-semibold text-slate-700 uppercase tracking-wider mb-1">City</label>
                <input type="text" name="city" value="{{ old('city', $customer->city ?? $user->city) }}"
                       class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-teal-500 text-sm">
            </div>

            <div>
                <label class="block font-semibold text-slate-700 uppercase tracking-wider mb-1">State / Union Territory</label>
                <input type="text" name="state" value="{{ old('state', $customer->state ?? $user->state) }}"
                       class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-teal-500 text-sm">
            </div>

            <div>
                <label class="block font-semibold text-slate-700 uppercase tracking-wider mb-1">Postal Pincode</label>
                <input type="text" name="pincode" value="{{ old('pincode', $customer->pincode ?? $user->pincode) }}"
                       class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-teal-500 text-sm">
            </div>

            <div>
                <label class="block font-semibold text-slate-700 uppercase tracking-wider mb-1">Passport Number (For Overseas Tours)</label>
                <input type="text" name="passport_number" value="{{ old('passport_number', $customer->passport_number) }}"
                       class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-teal-500 text-sm uppercase">
            </div>
        </div>

        <div class="pt-4 border-t border-slate-100 flex justify-end">
            <button type="submit" class="px-6 py-3 bg-teal-600 hover:bg-teal-700 text-white font-bold rounded-xl text-xs transition shadow-xs flex items-center gap-2">
                <i data-lucide="check" class="w-4 h-4"></i> Save Profile Changes
            </button>
        </div>
    </form>
</div>
@endsection

