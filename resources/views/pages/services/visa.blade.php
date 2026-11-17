@extends('layouts.app')

@section('title', 'Visa Assistance Services - BharatTravel')

@section('content')
<section class="bg-gradient-to-r from-slate-900 to-slate-800 text-white py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-semibold bg-blue-500/20 text-blue-400 border border-blue-500/30 uppercase tracking-widest mb-4">
            Global Visa Facilitation
        </span>
        <h1 class="text-4xl md:text-5xl font-bold font-serif mb-4">Hassle-Free Visa Services for Indian Travellers</h1>
        <p class="text-slate-300 max-w-2xl mx-auto text-base md:text-lg">
            99.2% approval track record. We handle document verification, embassy appointment slots, cover letter drafting, and biometric guidance.
        </p>
    </div>
</section>

<section class="py-16 bg-slate-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Sidebar: Why Us & Popular Countries -->
            <div class="space-y-6">
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200">
                    <h3 class="font-bold text-slate-900 text-lg mb-4 flex items-center gap-2">
                        <i data-lucide="check-circle-2" class="w-5 h-5 text-emerald-500"></i>
                        The BharatTravel Visa Advantage
                    </h3>
                    <ul class="space-y-3 text-sm text-slate-600">
                        <li class="flex items-start gap-2">
                            <i data-lucide="check" class="w-4 h-4 text-emerald-500 shrink-0 mt-0.5"></i>
                            <span>Pre-submission audit of bank statements & ITRs</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <i data-lucide="check" class="w-4 h-4 text-emerald-500 shrink-0 mt-0.5"></i>
                            <span>Confirmed flight itinerary & hotel voucher proof</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <i data-lucide="check" class="w-4 h-4 text-emerald-500 shrink-0 mt-0.5"></i>
                            <span>Express appointment scheduling for VFS & BLS</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <i data-lucide="check" class="w-4 h-4 text-emerald-500 shrink-0 mt-0.5"></i>
                            <span>Doorstep passport pickup and secure courier delivery</span>
                        </li>
                    </ul>
                </div>

                <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200">
                    <h4 class="font-bold text-slate-900 text-sm uppercase tracking-wider mb-3">Popular Visa Categories</h4>
                    <div class="space-y-2 text-xs">
                        <div class="p-2.5 bg-slate-50 rounded-lg flex items-center justify-between border border-slate-100">
                            <span class="font-semibold text-slate-800">Dubai (UAE) 30/60 Days eVisa</span>
                            <span class="text-emerald-600 font-bold">24-48 Hrs</span>
                        </div>
                        <div class="p-2.5 bg-slate-50 rounded-lg flex items-center justify-between border border-slate-100">
                            <span class="font-semibold text-slate-800">Schengen (Europe) Tourist Visa</span>
                            <span class="text-amber-600 font-bold">15-20 Days</span>
                        </div>
                        <div class="p-2.5 bg-slate-50 rounded-lg flex items-center justify-between border border-slate-100">
                            <span class="font-semibold text-slate-800">Singapore e-Visa</span>
                            <span class="text-blue-600 font-bold">3-4 Days</span>
                        </div>
                        <div class="p-2.5 bg-slate-50 rounded-lg flex items-center justify-between border border-slate-100">
                            <span class="font-semibold text-slate-800">United States B1/B2 Visa Prep</span>
                            <span class="text-purple-600 font-bold">Slot Assist</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Application Form -->
            <div class="lg:col-span-2 bg-white p-8 sm:p-10 rounded-2xl shadow-sm border border-slate-200">
                <h2 class="text-2xl font-serif font-bold text-slate-900 mb-2">Apply for Visa Assistance</h2>
                <p class="text-slate-600 text-sm mb-6">Submit your destination details and our visa officer will share the bespoke checklist.</p>

                @if(session('success'))
                    <div class="mb-6 p-4 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-800 text-sm rounded-r flex items-center gap-3">
                        <i data-lucide="check-circle" class="w-5 h-5 text-emerald-600"></i>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif

                @if($errors->any())
                    <div class="mb-6 p-4 bg-rose-50 border-l-4 border-rose-500 text-rose-700 text-sm rounded-r">
                        <ul class="list-disc list-inside">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('services.visa.submit') }}" method="POST" class="space-y-5">
                    @csrf
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Destination Country *</label>
                            <select name="country_id" required class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-amber-500 text-sm">
                                <option value="">Select Target Country</option>
                                @foreach($countries as $country)
                                    <option value="{{ $country->id }}" {{ old('country_id') == $country->id ? 'selected' : '' }}>
                                        {{ $country->name }} ({{ $country->code }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Visa Category *</label>
                            <select name="visa_category" required class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-amber-500 text-sm">
                                <option value="Tourist" {{ old('visa_category') == 'Tourist' ? 'selected' : '' }}>Tourist / Visitor Visa</option>
                                <option value="Business" {{ old('visa_category') == 'Business' ? 'selected' : '' }}>Business Visa / Conference</option>
                                <option value="Transit" {{ old('visa_category') == 'Transit' ? 'selected' : '' }}>Transit Visa</option>
                                <option value="Student" {{ old('visa_category') == 'Student' ? 'selected' : '' }}>Student / Educational</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Applicant Name (As on Passport) *</label>
                            <input type="text" name="applicant_name" required value="{{ old('applicant_name', Auth::user()?->name) }}"
                                   class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-amber-500 text-sm"
                                   placeholder="Full Name">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Passport Number (Optional)</label>
                            <input type="text" name="passport_number" value="{{ old('passport_number') }}"
                                   class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-amber-500 text-sm uppercase"
                                   placeholder="e.g. Z1234567">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Mobile Phone (WhatsApp) *</label>
                            <input type="tel" name="contact_phone" required value="{{ old('contact_phone', Auth::user()?->phone) }}"
                                   class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-amber-500 text-sm"
                                   placeholder="+91 98765 43210">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Email Address *</label>
                            <input type="email" name="contact_email" required value="{{ old('contact_email', Auth::user()?->email) }}"
                                   class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-amber-500 text-sm"
                                   placeholder="you@email.com">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Tentative Travel Date</label>
                            <input type="date" name="travel_date" value="{{ old('travel_date') }}" min="{{ date('Y-m-d') }}"
                                   class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-amber-500 text-sm">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Remarks or Special Requirements</label>
                        <textarea name="remarks" rows="3"
                                  class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-amber-500 text-sm"
                                  placeholder="Mention number of applicants, previous visa rejections (if any), urgency...">{{ old('remarks') }}</textarea>
                    </div>

                    <button type="submit" class="w-full sm:w-auto inline-flex items-center justify-center px-8 py-3.5 bg-gradient-to-r from-blue-600 to-indigo-700 hover:from-blue-700 hover:to-indigo-800 text-white font-bold rounded-xl shadow-lg hover:shadow-xl transition-all">
                        <i data-lucide="send" class="w-4 h-4 mr-2"></i>
                        Submit Visa Request
                    </button>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection

