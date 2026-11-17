@extends('layouts.customer')

@section('title', 'Visa & Passport Tracker | BharatTravel')

@section('customer_content')
<div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-xs flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
    <div>
        <h1 class="text-xl font-bold text-slate-900">Visa & Passport Application Tracker</h1>
        <p class="text-xs text-slate-500 mt-1">Real-time status updates from our consular desks, VFS/BLS biometric centers, and Regional Passport Offices.</p>
    </div>
    <div class="flex items-center gap-2">
        <a href="{{ route('services.visa') }}" class="px-3.5 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-xl transition shadow-xs">
            Apply Visa
        </a>
        <a href="{{ route('services.passport') }}" class="px-3.5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-xl transition shadow-xs">
            Passport Help
        </a>
    </div>
</div>

<!-- Visa Applications Section -->
<div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
    <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
        <h3 class="text-sm font-bold text-slate-900 flex items-center gap-2">
            <i data-lucide="stamp" class="w-4 h-4 text-blue-600"></i> International Visa Files
        </h3>
        <span class="text-xs font-bold text-slate-500">{{ $visas->count() }} Records</span>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-left text-xs text-slate-600">
            <thead class="bg-slate-50 text-slate-500 font-semibold uppercase text-[10px] tracking-wider border-b border-slate-200">
                <tr>
                    <th class="py-3 px-6">App Ref</th>
                    <th class="py-3 px-6">Country</th>
                    <th class="py-3 px-6">Applicant Name</th>
                    <th class="py-3 px-6">Category</th>
                    <th class="py-3 px-6">Travel Date</th>
                    <th class="py-3 px-6">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($visas as $visa)
                    <tr class="hover:bg-slate-50/50">
                        <td class="py-4 px-6 font-bold text-slate-900">{{ $visa->application_number }}</td>
                        <td class="py-4 px-6 font-semibold text-slate-800">{{ $visa->country?->name ?? 'International' }}</td>
                        <td class="py-4 px-6">{{ $visa->applicant_name }}</td>
                        <td class="py-4 px-6">{{ $visa->visa_category }}</td>
                        <td class="py-4 px-6">{{ $visa->travel_date ? \Carbon\Carbon::parse($visa->travel_date)->format('M d, Y') : 'N/A' }}</td>
                        <td class="py-4 px-6">
                            <span class="inline-block px-2.5 py-1 rounded-full text-[10px] font-bold 
                                {{ $visa->status == 'approved' ? 'bg-emerald-100 text-emerald-800' : ($visa->status == 'submitted' ? 'bg-blue-100 text-blue-800' : 'bg-amber-100 text-amber-800') }}">
                                {{ ucfirst($visa->status) }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="py-8 text-center text-slate-400">
                            No visa applications found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Passport Applications Section -->
<div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
    <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
        <h3 class="text-sm font-bold text-slate-900 flex items-center gap-2">
            <i data-lucide="book-open" class="w-4 h-4 text-indigo-600"></i> Indian Passport Seva Files
        </h3>
        <span class="text-xs font-bold text-slate-500">{{ $passports->count() }} Records</span>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-left text-xs text-slate-600">
            <thead class="bg-slate-50 text-slate-500 font-semibold uppercase text-[10px] tracking-wider border-b border-slate-200">
                <tr>
                    <th class="py-3 px-6">File Number</th>
                    <th class="py-3 px-6">Applicant Name</th>
                    <th class="py-3 px-6">Service Type</th>
                    <th class="py-3 px-6">Appointment Date</th>
                    <th class="py-3 px-6">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($passports as $pass)
                    <tr class="hover:bg-slate-50/50">
                        <td class="py-4 px-6 font-bold text-slate-900">{{ $pass->application_number }}</td>
                        <td class="py-4 px-6 font-semibold text-slate-800">{{ $pass->applicant_name }}</td>
                        <td class="py-4 px-6 uppercase text-[10px] font-bold text-indigo-700">{{ $pass->service_type }}</td>
                        <td class="py-4 px-6">{{ $pass->appointment_date ? \Carbon\Carbon::parse($pass->appointment_date)->format('M d, Y') : 'Pending Slot' }}</td>
                        <td class="py-4 px-6">
                            <span class="inline-block px-2.5 py-1 rounded-full text-[10px] font-bold 
                                {{ $pass->status == 'dispatched' ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }}">
                                {{ ucfirst($pass->status) }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="py-8 text-center text-slate-400">
                            No passport assistance applications logged.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

