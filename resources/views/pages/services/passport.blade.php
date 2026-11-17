@extends('layouts.app')

@section('title', 'Passport Assistance Services - BharatTravel')

@section('content')
<section class="bg-gradient-to-r from-slate-900 to-indigo-950 text-white py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-semibold bg-indigo-500/20 text-indigo-400 border border-indigo-500/30 uppercase tracking-widest mb-4">
            Passport Seva Kendra (PSK) Assistance
        </span>
        <h1 class="text-4xl md:text-5xl font-bold font-serif mb-4">Complete Indian Passport Facilitation</h1>
        <p class="text-slate-300 max-w-2xl mx-auto text-base md:text-lg">
            Guaranteed slot booking at your nearest PSK/POPSK, document scrutiny, Annexure preparation, and Tatkal expedited processing.
        </p>
    </div>
</section>

<section class="py-16 bg-slate-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="space-y-6">
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200">
                    <h3 class="font-bold text-slate-900 text-lg mb-4 flex items-center gap-2">
                        <i data-lucide="award" class="w-5 h-5 text-indigo-600"></i>
                        Our Passport Services
                    </h3>
                    <div class="space-y-3 text-sm text-slate-600">
                        <div class="p-3 bg-indigo-50/50 rounded-xl border border-indigo-100">
                            <p class="font-bold text-indigo-900">Fresh Passport</p>
                            <p class="text-xs text-indigo-700 mt-0.5">For first-time applicants (Adult & Minor) with proof of address and DOB.</p>
                        </div>
                        <div class="p-3 bg-indigo-50/50 rounded-xl border border-indigo-100">
                            <p class="font-bold text-indigo-900">Renewal / Reissue</p>
                            <p class="text-xs text-indigo-700 mt-0.5">Due to expiry, pages exhausted, address change, or marital status update.</p>
                        </div>
                        <div class="p-3 bg-rose-50/50 rounded-xl border border-rose-100">
                            <p class="font-bold text-rose-900">Tatkal Fast-Track</p>
                            <p class="text-xs text-rose-700 mt-0.5">Urgent dispatch within 3-5 working days with priority PSK slots.</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-2 bg-white p-8 sm:p-10 rounded-2xl shadow-sm border border-slate-200">
                <h2 class="text-2xl font-serif font-bold text-slate-900 mb-2">Request Passport Consultation</h2>
                <p class="text-slate-600 text-sm mb-6">Fill in your information and our PSK concierge will guide you with verified slots.</p>

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

                <form action="{{ route('services.passport.submit') }}" method="POST" class="space-y-5">
                    @csrf
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Service Required *</label>
                            <select name="service_type" required class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 text-sm">
                                <option value="fresh">Fresh (First Time Applicant)</option>
                                <option value="renewal">Renewal (Expired / Expiring Soon)</option>
                                <option value="reissue">Reissue (Pages Full / Name / Address Change)</option>
                                <option value="tatkal">Tatkal (Urgent Express Dispatch)</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Applicant Legal Name *</label>
                            <input type="text" name="applicant_name" required value="{{ old('applicant_name', Auth::user()?->name) }}"
                                   class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 text-sm"
                                   placeholder="Full Name as on Aadhaar">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Mobile Number (WhatsApp) *</label>
                            <input type="tel" name="contact_phone" required value="{{ old('contact_phone', Auth::user()?->phone) }}"
                                   class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 text-sm"
                                   placeholder="+91 98765 43210">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Email Address *</label>
                            <input type="email" name="contact_email" required value="{{ old('contact_email', Auth::user()?->email) }}"
                                   class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 text-sm"
                                   placeholder="you@email.com">
                        </div>

                        <div class="sm:col-span-2">
                            <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Place of Birth (City / State)</label>
                            <input type="text" name="place_of_birth" value="{{ old('place_of_birth') }}"
                                   class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 text-sm"
                                   placeholder="e.g. New Delhi, Delhi">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Additional Remarks</label>
                        <textarea name="remarks" rows="3"
                                  class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 text-sm"
                                  placeholder="Mention any existing passport number, date of expiry, preferred PSK location...">{{ old('remarks') }}</textarea>
                    </div>

                    <button type="submit" class="w-full sm:w-auto inline-flex items-center justify-center px-8 py-3.5 bg-gradient-to-r from-indigo-600 to-slate-900 hover:from-indigo-700 hover:to-black text-white font-bold rounded-xl shadow-lg hover:shadow-xl transition-all">
                        <i data-lucide="send" class="w-4 h-4 mr-2"></i>
                        Request PSK Assistance
                    </button>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection

