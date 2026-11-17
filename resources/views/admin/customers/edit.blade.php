@extends('layouts.admin')

@section('title', 'Edit Customer: ' . $customer->name)

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-xs flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <a href="{{ route('admin.customers.show', $customer->id) }}" class="text-xs font-bold text-slate-500 hover:text-slate-900">&larr; Back to Dossier</a>
                <span class="text-slate-300">•</span>
                <span class="text-xs font-bold text-amber-600 uppercase">#{{ $customer->customer_code }}</span>
            </div>
            <h1 class="text-xl font-bold font-serif text-slate-900">Edit Customer Profile</h1>
            <p class="text-xs text-slate-500 mt-0.5">Update personal details, passport expiration, or billing credentials.</p>
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
        <form action="{{ route('admin.customers.update', $customer->id) }}" method="POST" class="space-y-6 text-xs">
            @csrf
            @method('PUT')

            <!-- Demographics -->
            <div>
                <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider mb-4 flex items-center gap-2">
                    <i data-lucide="user" class="w-4 h-4 text-amber-500"></i> Primary Demographics
                </h3>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Full Legal Name *</label>
                        <input type="text" name="name" required value="{{ old('name', $customer->name) }}"
                               class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-amber-500 text-xs">
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Mobile Phone *</label>
                        <input type="tel" name="phone" required value="{{ old('phone', $customer->phone) }}"
                               class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-amber-500 text-xs">
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Email Address</label>
                        <input type="email" name="email" value="{{ old('email', $customer->email) }}"
                               class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-amber-500 text-xs">
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Alternate Phone</label>
                        <input type="tel" name="alternate_phone" value="{{ old('alternate_phone', $customer->alternate_phone) }}"
                               class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-amber-500 text-xs">
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Gender</label>
                        <select name="gender" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-amber-500 text-xs">
                            <option value="">Select Gender</option>
                            <option value="Male" {{ old('gender', $customer->gender) == 'Male' ? 'selected' : '' }}>Male</option>
                            <option value="Female" {{ old('gender', $customer->gender) == 'Female' ? 'selected' : '' }}>Female</option>
                            <option value="Other" {{ old('gender', $customer->gender) == 'Other' ? 'selected' : '' }}>Other</option>
                        </select>
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Date of Birth</label>
                        <input type="date" name="dob" value="{{ old('dob', $customer->dob ? \Carbon\Carbon::parse($customer->dob)->format('Y-m-d') : '') }}"
                               class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-amber-500 text-xs">
                    </div>
                </div>
            </div>

            <!-- Passport & GST -->
            <div class="pt-4 border-t border-slate-100">
                <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider mb-4 flex items-center gap-2">
                    <i data-lucide="shield" class="w-4 h-4 text-amber-500"></i> Passport & GST Compliance
                </h3>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Passport Number</label>
                        <input type="text" name="passport_number" value="{{ old('passport_number', $customer->passport_number) }}"
                               class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-amber-500 text-xs uppercase">
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Passport Expiry Date</label>
                        <input type="date" name="passport_expiry" value="{{ old('passport_expiry', $customer->passport_expiry ? \Carbon\Carbon::parse($customer->passport_expiry)->format('Y-m-d') : '') }}"
                               class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-amber-500 text-xs">
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">GSTIN Number</label>
                        <input type="text" name="gstin" value="{{ old('gstin', $customer->gstin) }}"
                               class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-amber-500 text-xs uppercase">
                    </div>
                </div>
            </div>

            <!-- Address -->
            <div class="pt-4 border-t border-slate-100">
                <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider mb-4 flex items-center gap-2">
                    <i data-lucide="map-pin" class="w-4 h-4 text-amber-500"></i> Postal Address
                </h3>
                <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
                    <div class="sm:col-span-4">
                        <label class="block font-semibold text-slate-700 mb-1">Street Address</label>
                        <input type="text" name="address" value="{{ old('address', $customer->address) }}"
                               class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-amber-500 text-xs">
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">City</label>
                        <input type="text" name="city" value="{{ old('city', $customer->city) }}"
                               class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-amber-500 text-xs">
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">State</label>
                        <input type="text" name="state" value="{{ old('state', $customer->state) }}"
                               class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-amber-500 text-xs">
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Pincode</label>
                        <input type="text" name="pincode" value="{{ old('pincode', $customer->pincode) }}"
                               class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-amber-500 text-xs">
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Account Status *</label>
                        <select name="status" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-amber-500 text-xs">
                            <option value="active" {{ old('status', $customer->status) == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ old('status', $customer->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            <option value="blacklisted" {{ old('status', $customer->status) == 'blacklisted' ? 'selected' : '' }}>Blacklisted</option>
                        </select>
                    </div>
                </div>
            </div>

            <button type="submit" class="w-full sm:w-auto px-8 py-3 bg-amber-500 hover:bg-amber-600 text-slate-950 font-bold rounded-xl text-xs transition shadow-sm flex items-center justify-center gap-2">
                <i data-lucide="check" class="w-4 h-4"></i> Save Profile Updates
            </button>
        </form>
    </div>
</div>
@endsection

