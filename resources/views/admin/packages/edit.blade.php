@extends('layouts.admin')

@section('title', 'Edit Package: ' . $package->title)

@section('content')
<div class="max-w-5xl mx-auto space-y-6">
    <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-xs flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <a href="{{ route('admin.packages.show', $package->id) }}" class="text-xs font-bold text-slate-500 hover:text-slate-900">&larr; Back to Package Console</a>
                <span class="text-slate-300">•</span>
                <span class="text-xs font-bold text-amber-600 uppercase">#{{ $package->package_code }}</span>
            </div>
            <h1 class="text-xl font-bold font-serif text-slate-900">Edit Tour Package</h1>
            <p class="text-xs text-slate-500 mt-0.5">Modify package pricing, destination mapping, duration, and policy content.</p>
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
        <form action="{{ route('admin.packages.update', $package->id) }}" method="POST" class="space-y-6 text-xs">
            @csrf
            @method('PUT')

            <!-- Basic Details -->
            <div>
                <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider mb-4 flex items-center gap-2">
                    <i data-lucide="map" class="w-4 h-4 text-amber-500"></i> Package Information
                </h3>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div class="sm:col-span-2">
                        <label class="block font-semibold text-slate-700 mb-1">Tour Title *</label>
                        <input type="text" name="title" required value="{{ old('title', $package->title) }}"
                               class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-amber-500 text-xs">
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Destination *</label>
                        <select name="destination_id" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-amber-500 text-xs">
                            @foreach($destinations as $dest)
                                <option value="{{ $dest->id }}" {{ old('destination_id', $package->destination_id) == $dest->id ? 'selected' : '' }}>{{ $dest->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Package Category</label>
                        <select name="category_id" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-amber-500 text-xs">
                            <option value="">Select Category</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ old('category_id', $package->category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Tour Type *</label>
                        <select name="type" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-amber-500 text-xs">
                            <option value="domestic" {{ old('type', $package->type) == 'domestic' ? 'selected' : '' }}>Domestic (India)</option>
                            <option value="international" {{ old('type', $package->type) == 'international' ? 'selected' : '' }}>International</option>
                        </select>
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Duration Days / Nights *</label>
                        <div class="grid grid-cols-2 gap-2">
                            <input type="number" name="duration_days" min="1" required value="{{ old('duration_days', $package->duration_days) }}" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs">
                            <input type="number" name="duration_nights" min="0" required value="{{ old('duration_nights', $package->duration_nights) }}" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs">
                        </div>
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Departure City</label>
                        <input type="text" name="departure_city" value="{{ old('departure_city', $package->departure_city) }}" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-xs">
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Pickup / Drop Info</label>
                        <input type="text" name="pickup_drop_info" value="{{ old('pickup_drop_info', $package->pickup_drop_info) }}" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-xs">
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Featured Hero Image URL</label>
                        <input type="url" name="featured_image" value="{{ old('featured_image', $package->featured_image) }}" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-xs">
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
                        <input type="number" step="0.01" name="adult_price" required value="{{ old('adult_price', $package->adult_price) }}" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-xs">
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Discounted Sale Price</label>
                        <input type="number" step="0.01" name="discount_price" value="{{ old('discount_price', $package->discount_price) }}" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-xs">
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Starting Display Price *</label>
                        <input type="number" step="0.01" name="starting_price" required value="{{ old('starting_price', $package->starting_price) }}" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-xs">
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Child Price (2-12 Yrs)</label>
                        <input type="number" step="0.01" name="child_price" value="{{ old('child_price', $package->child_price) }}" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-xs">
                    </div>
                </div>
            </div>

            <!-- Inclusions & Exclusions -->
            <div class="pt-4 border-t border-slate-100">
                <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider mb-4 flex items-center gap-2">
                    <i data-lucide="check-square" class="w-4 h-4 text-amber-500"></i> Inclusions, Exclusions & Highlights
                </h3>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Key Highlights</label>
                        <textarea name="highlights" rows="5" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs font-mono">{{ old('highlights', is_array($package->highlights) ? implode("\n", $package->highlights) : '') }}</textarea>
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Inclusions</label>
                        <textarea name="inclusions" rows="5" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs font-mono">{{ old('inclusions', is_array($package->inclusions) ? implode("\n", $package->inclusions) : '') }}</textarea>
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Exclusions</label>
                        <textarea name="exclusions" rows="5" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs font-mono">{{ old('exclusions', is_array($package->exclusions) ? implode("\n", $package->exclusions) : '') }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Publishing Flags -->
            <div class="pt-4 border-t border-slate-100 flex flex-wrap items-center justify-between gap-4">
                <div class="flex items-center gap-6">
                    <label class="inline-flex items-center text-xs font-semibold text-slate-700 cursor-pointer">
                        <input type="checkbox" name="is_featured" value="1" {{ old('is_featured', $package->is_featured) ? 'checked' : '' }} class="text-amber-500 rounded">
                        <span class="ml-2">Feature on Home Page</span>
                    </label>
                    <label class="inline-flex items-center text-xs font-semibold text-slate-700 cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $package->is_active) ? 'checked' : '' }} class="text-amber-500 rounded">
                        <span class="ml-2">Active in Catalog</span>
                    </label>
                </div>

                <div class="flex items-center gap-3">
                    <select name="status" class="px-3.5 py-2.5 rounded-xl border border-slate-300 text-xs font-bold">
                        <option value="published" {{ old('status', $package->status) == 'published' ? 'selected' : '' }}>Published & Bookable</option>
                        <option value="draft" {{ old('status', $package->status) == 'draft' ? 'selected' : '' }}>Save as Draft</option>
                    </select>

                    <button type="submit" class="px-8 py-3 bg-amber-500 hover:bg-amber-600 text-slate-950 font-bold rounded-xl text-xs transition shadow-sm">
                        Save Package Changes
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

