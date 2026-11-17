@extends('layouts.app')

@section('title', 'Contact Us - BharatTravel Advisory & Offices')

@section('content')
<!-- Hero Section -->
<section class="bg-gradient-to-r from-slate-900 to-slate-800 text-white py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h1 class="text-4xl md:text-5xl font-bold font-serif mb-4">Let's Plan Your Next Adventure</h1>
        <p class="text-slate-300 max-w-2xl mx-auto text-base md:text-lg">
            Have a question about an upcoming departure, customized honeymoon, visa requirements, or B2B agent partnership? Our travel specialists are here 7 days a week.
        </p>
    </div>
</section>

<section class="py-16 bg-slate-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Contact Details & Offices -->
            <div class="space-y-6">
                <!-- Quick Contact -->
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200">
                    <h3 class="text-lg font-bold text-slate-900 mb-4 flex items-center gap-2">
                        <i data-lucide="phone-call" class="w-5 h-5 text-amber-500"></i>
                        Direct Helpline
                    </h3>
                    <div class="space-y-3 text-sm text-slate-600">
                        <div class="flex items-start gap-3">
                            <i data-lucide="phone" class="w-4 h-4 text-slate-400 mt-1 shrink-0"></i>
                            <div>
                                <p class="font-semibold text-slate-800">+91 (011) 4567 8900 / 01</p>
                                <p class="text-xs text-slate-500">General Enquiries & Bookings</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3">
                            <i data-lucide="message-circle" class="w-4 h-4 text-emerald-500 mt-1 shrink-0"></i>
                            <div>
                                <p class="font-semibold text-slate-800">+91 98765 43210 (WhatsApp)</p>
                                <p class="text-xs text-slate-500">Instant Itinerary & Rates Desk</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3">
                            <i data-lucide="mail" class="w-4 h-4 text-slate-400 mt-1 shrink-0"></i>
                            <div>
                                <p class="font-semibold text-slate-800">holiday@bharattravel.com</p>
                                <p class="text-xs text-slate-500">Support: support@bharattravel.com</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Delhi HQ Office -->
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200">
                    <span class="inline-block px-2.5 py-0.5 rounded text-[11px] font-bold bg-amber-100 text-amber-800 uppercase mb-2">Corporate HQ</span>
                    <h4 class="font-bold text-slate-900 text-base mb-1">New Delhi Flagship Office</h4>
                    <p class="text-sm text-slate-600 leading-relaxed">
                        Suite 401-403, Gopal Das Bhawan, 28 Barakhamba Road, Connaught Place, New Delhi - 110001
                    </p>
                    <p class="text-xs text-slate-500 mt-2">Mon - Sat: 09:30 AM – 07:00 PM IST</p>
                </div>

                <!-- Mumbai Branch Office -->
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200">
                    <span class="inline-block px-2.5 py-0.5 rounded text-[11px] font-bold bg-blue-100 text-blue-800 uppercase mb-2">West India Operations</span>
                    <h4 class="font-bold text-slate-900 text-base mb-1">Mumbai Regional Office</h4>
                    <p class="text-sm text-slate-600 leading-relaxed">
                        7th Floor, The Capital, G Block, Bandra Kurla Complex (BKC), Bandra East, Mumbai - 400051
                    </p>
                    <p class="text-xs text-slate-500 mt-2">Mon - Sat: 10:00 AM – 06:30 PM IST</p>
                </div>
            </div>

            <!-- Contact Form -->
            <div class="lg:col-span-2 bg-white p-8 sm:p-10 rounded-2xl shadow-sm border border-slate-200">
                <h2 class="text-2xl font-serif font-bold text-slate-900 mb-2">Send an Inquiry or Message</h2>
                <p class="text-slate-600 text-sm mb-6">Fill out your query and our assigned destination manager will call you back with curated recommendations.</p>

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

                <form action="{{ route('contact.submit') }}" method="POST" class="space-y-5">
                    @csrf
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Your Full Name *</label>
                            <input type="text" name="name" required value="{{ old('name') }}"
                                   class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-amber-500 text-sm"
                                   placeholder="e.g. Dr. Ramesh Kumar">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Email Address *</label>
                            <input type="email" name="email" required value="{{ old('email') }}"
                                   class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-amber-500 text-sm"
                                   placeholder="ramesh@gmail.com">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Phone Number (WhatsApp) *</label>
                            <input type="tel" name="phone" required value="{{ old('phone') }}"
                                   class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-amber-500 text-sm"
                                   placeholder="+91 98765 43210">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Subject *</label>
                            <input type="text" name="subject" required value="{{ old('subject') }}"
                                   class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-amber-500 text-sm"
                                   placeholder="e.g. Kashmir Family Trip Quote for 6 Pax">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Your Message or Custom Travel Request *</label>
                        <textarea name="message" rows="5" required
                                  class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-amber-500 text-sm"
                                  placeholder="Mention intended travel dates, budget preference, number of passengers, and any specific requirements...">{{ old('message') }}</textarea>
                    </div>

                    <button type="submit" class="inline-flex items-center justify-center px-8 py-3.5 bg-gradient-to-r from-amber-500 to-orange-600 hover:from-amber-600 hover:to-orange-700 text-white font-bold rounded-xl shadow-lg hover:shadow-xl transition-all">
                        <i data-lucide="send" class="w-4 h-4 mr-2"></i>
                        Send Message to Travel Desk
                    </button>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection

