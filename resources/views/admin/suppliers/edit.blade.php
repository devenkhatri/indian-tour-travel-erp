@extends('layouts.admin')

@section('title', 'Edit Supplier: ' . $supplier->name)

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-xs flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <a href="{{ route('admin.suppliers.show', $supplier->id) }}" class="text-xs font-bold text-slate-500 hover:text-slate-900">&larr; Back to Supplier</a>
                <span class="text-slate-300">•</span>
                <span class="text-xs font-bold text-amber-600 uppercase">#{{ $supplier->code }}</span>
            </div>
            <h1 class="text-xl font-bold font-serif text-slate-900">Edit Supplier Partner</h1>
        </div>
    </div>

    <div class="bg-white rounded-2xl p-8 border border-slate-200 shadow-xs">
        <form action="{{ route('admin.suppliers.update', $supplier->id) }}" method="POST" class="space-y-6 text-xs">
            @csrf
            @method('PUT')

            <div>
                <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider mb-4 flex items-center gap-2">
                    <i data-lucide="briefcase" class="w-4 h-4 text-amber-500"></i> Vendor Identity
                </h3>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div class="sm:col-span-2">
                        <label class="block font-semibold text-slate-700 mb-1">Company / Supplier Legal Name *</label>
                        <input type="text" name="name" required value="{{ old('name', $supplier->name) }}"
                               class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-amber-500 text-xs">
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Supplier Category *</label>
                        <select name="category" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-amber-500 text-xs">
                            <option value="hotel" {{ old('category', $supplier->category) == 'hotel' ? 'selected' : '' }}>Hotels & Resorts</option>
                            <option value="transport" {{ old('category', $supplier->category) == 'transport' ? 'selected' : '' }}>Transporters & Cabs</option>
                            <option value="dmc" {{ old('category', $supplier->category) == 'dmc' ? 'selected' : '' }}>Destination Management Company (DMC)</option>
                            <option value="airline" {{ old('category', $supplier->category) == 'airline' ? 'selected' : '' }}>Airlines</option>
                            <option value="visa" {{ old('category', $supplier->category) == 'visa' ? 'selected' : '' }}>Visa Facilitation</option>
                            <option value="insurance" {{ old('category', $supplier->category) == 'insurance' ? 'selected' : '' }}>Travel Insurance</option>
                            <option value="cruise" {{ old('category', $supplier->category) == 'cruise' ? 'selected' : '' }}>Cruise Liners</option>
                            <option value="other" {{ old('category', $supplier->category) == 'other' ? 'selected' : '' }}>Other Service Vendor</option>
                        </select>
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Contact Person Name</label>
                        <input type="text" name="contact_person" value="{{ old('contact_person', $supplier->contact_person) }}" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-xs">
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Phone Number</label>
                        <input type="tel" name="phone" value="{{ old('phone', $supplier->phone) }}" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-xs">
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Email Address</label>
                        <input type="email" name="email" value="{{ old('email', $supplier->email) }}" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-xs">
                    </div>
                </div>
            </div>

            <!-- Tax & Address -->
            <div class="pt-4 border-t border-slate-100">
                <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider mb-4 flex items-center gap-2">
                    <i data-lucide="map-pin" class="w-4 h-4 text-amber-500"></i> Location & Tax Details
                </h3>
                <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">City</label>
                        <input type="text" name="city" value="{{ old('city', $supplier->city) }}" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-xs">
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">State</label>
                        <input type="text" name="state" value="{{ old('state', $supplier->state) }}" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-xs">
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">GSTIN Number</label>
                        <input type="text" name="gstin" value="{{ old('gstin', $supplier->gstin) }}" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-xs uppercase">
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">PAN Number</label>
                        <input type="text" name="pan" value="{{ old('pan', $supplier->pan) }}" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-xs uppercase">
                    </div>
                </div>
            </div>

            <button type="submit" class="px-8 py-3 bg-amber-500 hover:bg-amber-600 text-slate-950 font-bold rounded-xl text-xs transition shadow-sm">
                Save Changes
            </button>
        </form>
    </div>
</div>
@endsection

