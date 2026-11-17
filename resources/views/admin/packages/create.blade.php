@extends('layouts.admin')

@section('title', 'Create Tour Package | BharatTravel')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">
    <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-xs flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <a href="{{ route('admin.packages.index') }}" class="text-xs font-bold text-slate-500 hover:text-slate-900">&larr; Back to Packages</a>
                <span class="text-slate-300">•</span>
                <span class="text-xs font-bold text-amber-600 uppercase">Product Builder</span>
            </div>
            <h1 class="text-xl font-bold font-serif text-slate-900">Create New Holiday Package</h1>
            <p class="text-xs text-slate-500 mt-0.5">Define package specifications, retail & wholesale pricing, inclusions, and policies.</p>
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
        <form action="{{ route('admin.packages.store') }}" method="POST" class="space-y-6 text-xs">
            @csrf

            <!-- Basic Details -->
            <div>
                <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider mb-4 flex items-center gap-2">
                    <i data-lucide="map" class="w-4 h-4 text-amber-500"></i> Package Information
                </h3>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div class="sm:col-span-2">
                        <label class="block font-semibold text-slate-700 mb-1">Tour Title *</label>
                        <input type="text" name="title" required value="{{ old('title') }}"
                               class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-amber-500 text-xs"
                               placeholder="e.g. Magical Kashmir: Gulmarg & Pahalgam Honeymoon Special">
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Destination *</label>
                        <select name="destination_id" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-amber-500 text-xs">
                            <option value="">Select Destination</option>
                            @foreach($destinations as $dest)
                                <option value="{{ $dest->id }}" {{ old('destination_id') == $dest->id ? 'selected' : '' }}>{{ $dest->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Package Category</label>
                        <select name="category_id" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-amber-500 text-xs">
                            <option value="">Select Category</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Tour Type *</label>
                        <select name="type" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-amber-500 text-xs">
                            <option value="domestic" selected>Domestic (India)</option>
                            <option value="international">International (Overseas)</option>
                        </select>
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Duration Days / Nights *</label>
                        <div class="grid grid-cols-2 gap-2">
                            <input type="number" name="duration_days" min="1" required value="{{ old('duration_days', 6) }}" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs" placeholder="Days">
                            <input type="number" name="duration_nights" min="0" required value="{{ old('duration_nights', 5) }}" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs" placeholder="Nights">
                        </div>
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Departure City</label>
                        <input type="text" name="departure_city" value="{{ old('departure_city', 'Srinagar / Ex-Delhi') }}" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-xs">
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Pickup / Drop Info</label>
                        <input type="text" name="pickup_drop_info" value="{{ old('pickup_drop_info', 'Pickup & Drop from Srinagar Airport (SXR)') }}" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-xs">
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Featured Hero Image URL</label>
                        <input type="url" name="featured_image" value="{{ old('featured_image', 'https://images.unsplash.com/photo-1595815771614-ade9d652a65d?auto=format&fit=crop&w=1200&q=80') }}" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-xs">
                    </div>
                </div>
            </div>

            <!-- Pricing Structure -->
            <div class="pt-4 border-t border-slate-100">
                <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider mb-4 flex items-center gap-2">
                    <i data-lucide="credit-card" class="w-4 h-4 text-amber-500"></i> Pricing Matrix (INR)
                </h3>
                <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Adult Regular Price (MRP) *</label>
                        <input type="number" step="0.01" name="adult_price" required value="{{ old('adult_price', 35000) }}" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-xs">
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Discounted Sale Price</label>
                        <input type="number" step="0.01" name="discount_price" value="{{ old('discount_price', 29999) }}" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-xs">
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Starting Display Price *</label>
                        <input type="number" step="0.01" name="starting_price" required value="{{ old('starting_price', 29999) }}" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-xs">
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Child Price (2-12 Yrs)</label>
                        <input type="number" step="0.01" name="child_price" value="{{ old('child_price', 15000) }}" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-xs">
                    </div>
                </div>
            </div>

            <!-- Inclusions & Exclusions -->
            <div class="pt-4 border-t border-slate-100">
                <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider mb-4 flex items-center gap-2">
                    <i data-lucide="check-square" class="w-4 h-4 text-amber-500"></i> Package Inclusions & Exclusions (One per line)
                </h3>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Key Highlights</label>
                        <textarea name="highlights" rows="5" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs font-mono" placeholder="Shikara ride on Dal Lake&#10;Gondola Phase 1 & 2 tickets&#10;Pahalgam Betaab Valley excursion">{{ old('highlights') }}</textarea>
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Inclusions</label>
                        <textarea name="inclusions" rows="5" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs font-mono" placeholder="5 Nights 4-Star Accommodation&#10;Daily Breakfast & Dinner&#10;Private Sedan Vehicle for all transfers">{{ old('inclusions') }}</textarea>
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Exclusions</label>
                        <textarea name="exclusions" rows="5" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs font-mono" placeholder="Airfare / Train tickets&#10;Pony rides in Gulmarg&#10;Personal expenses & laundry">{{ old('exclusions') }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Publishing Flags -->
            <div class="pt-4 border-t border-slate-100 flex flex-wrap items-center justify-between gap-4">
                <div class="flex items-center gap-6">
                    <label class="inline-flex items-center text-xs font-semibold text-slate-700 cursor-pointer">
                        <input type="checkbox" name="is_featured" value="1" class="text-amber-500 rounded">
                        <span class="ml-2">Feature on Home Page</span>
                    </label>
                    <label class="inline-flex items-center text-xs font-semibold text-slate-700 cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" checked class="text-amber-500 rounded">
                        <span class="ml-2">Active in Catalog</span>
                    </label>
                </div>

                <div class="flex items-center gap-3">
                    <select name="status" class="px-3.5 py-2.5 rounded-xl border border-slate-300 text-xs font-bold">
                        <option value="published" selected>Published & Bookable</option>
                        <option value="draft">Save as Draft</option>
                    </select>

                    <button type="submit" class="px-8 py-3 bg-amber-500 hover:bg-amber-600 text-slate-950 font-bold rounded-xl text-xs transition shadow-sm">
                        Create Package & Next: Itinerary &rarr;
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

