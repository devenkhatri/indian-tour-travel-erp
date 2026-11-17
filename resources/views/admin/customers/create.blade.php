@extends('layouts.admin')

@section('title', 'Register New Customer Profile | BharatTravel')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-xs flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <a href="{{ route('admin.customers.index') }}" class="text-xs font-bold text-slate-500 hover:text-slate-900">&larr; Back to Directory</a>
                <span class="text-slate-300">•</span>
                <span class="text-xs font-bold text-amber-600 uppercase">New Traveller Master</span>
            </div>
            <h1 class="text-xl font-bold font-serif text-slate-900">Add Customer Profile</h1>
            <p class="text-xs text-slate-500 mt-0.5">Register customer demographics, passport documentation, and billing credentials.</p>
        </div>
    </div>

    @if($errors->any())
        <div class="p-4 bg-rose-50 border-l-4 border-rose-500 text-rose-700 text-xs rounded-r">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white rounded-2xl p-8 border border-slate-200 shadow-xs">
        <form action="{{ route('admin.customers.store') }}" method="POST" class="space-y-6 text-xs">
            @csrf

            <!-- Demographics -->
            <div>
                <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider mb-4 flex items-center gap-2">
                    <i data-lucide="user" class="w-4 h-4 text-amber-500"></i> Primary Demographics
                </h3>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Full Legal Name *</label>
                        <input type="text" name="name" required value="{{ old('name') }}"
                               class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-amber-500 text-xs"
                               placeholder="e.g. Meenakshi Sundaram">
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Mobile Phone (WhatsApp) *</label>
                        <input type="tel" name="phone" required value="{{ old('phone') }}"
                               class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-amber-500 text-xs"
                               placeholder="+91 98765 43210">
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Email Address</label>
                        <input type="email" name="email" value="{{ old('email') }}"
                               class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-amber-500 text-xs"
                               placeholder="meenakshi@gmail.com">
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Alternate Contact</label>
                        <input type="tel" name="alternate_phone" value="{{ old('alternate_phone') }}"
                               class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-amber-500 text-xs"
                               placeholder="+91 99999 00000">
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Gender</label>
                        <select name="gender" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-amber-500 text-xs">
                            <option value="">Select Gender</option>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Date of Birth</label>
                        <input type="date" name="dob" value="{{ old('dob') }}"
                               class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-amber-500 text-xs">
                    </div>
                </div>
            </div>

            <!-- Passport & Tax Details -->
            <div class="pt-4 border-t border-slate-100">
                <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider mb-4 flex items-center gap-2">
                    <i data-lucide="shield" class="w-4 h-4 text-amber-500"></i> Passport & GST Compliance
                </h3>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Passport Number</label>
                        <input type="text" name="passport_number" value="{{ old('passport_number') }}"
                               class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-amber-500 text-xs uppercase"
                               placeholder="e.g. Z9876543">
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Passport Expiry Date</label>
                        <input type="date" name="passport_expiry" value="{{ old('passport_expiry') }}"
                               class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-amber-500 text-xs">
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">GSTIN Number (Corporate Invoicing)</label>
                        <input type="text" name="gstin" value="{{ old('gstin') }}"
                               class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-amber-500 text-xs uppercase"
                               placeholder="e.g. 07AAAAA0000A1Z5">
                    </div>
                </div>
            </div>

            <!-- Address Details -->
            <div class="pt-4 border-t border-slate-100">
                <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider mb-4 flex items-center gap-2">
                    <i data-lucide="map-pin" class="w-4 h-4 text-amber-500"></i> Postal Address
                </h3>
                <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
                    <div class="sm:col-span-4">
                        <label class="block font-semibold text-slate-700 mb-1">Full Street Address</label>
                        <input type="text" name="address" value="{{ old('address') }}"
                               class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-amber-500 text-xs"
                               placeholder="Apartment, Street, Landmark...">
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">City</label>
                        <input type="text" name="city" value="{{ old('city') }}"
                               class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-amber-500 text-xs">
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">State</label>
                        <input type="text" name="state" value="{{ old('state') }}"
                               class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-amber-500 text-xs">
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Pincode</label>
                        <input type="text" name="pincode" value="{{ old('pincode') }}"
                               class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-amber-500 text-xs">
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Status *</label>
                        <select name="status" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-amber-500 text-xs">
                            <option value="active" selected>Active</option>
                            <option value="inactive">Inactive</option>
                            <option value="blacklisted">Blacklisted</option>
                        </select>
                    </div>
                </div>
            </div>

            <div>
                <label class="block font-semibold text-slate-700 mb-1">Guest Notes & Preferences</label>
                <textarea name="notes" rows="2" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-amber-500 text-xs" placeholder="VIP customer, dietary restrictions, preferred hotel chains...">{{ old('notes') }}</textarea>
            </div>

            <button type="submit" class="w-full sm:w-auto px-8 py-3 bg-amber-500 hover:bg-amber-600 text-slate-950 font-bold rounded-xl text-xs transition shadow-sm flex items-center justify-center gap-2">
                <i data-lucide="check" class="w-4 h-4"></i> Create Customer Profile
            </button>
        </form>
    </div>
</div>
@endsection

