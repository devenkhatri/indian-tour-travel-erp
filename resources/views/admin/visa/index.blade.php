@extends('layouts.admin')

@section('title', 'International Visa Assistance Desk | BharatTravel')

@section('content')
<div class="space-y-6">
    <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-xs flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-xl font-bold text-slate-900">Visa Processing & Embassy Desk</h1>
            <p class="text-xs text-slate-500 mt-1">Manage Schengen, Dubai eVisa, UK, US slot bookings, biometric appointments, and consular tracking.</p>
        </div>
    </div>

    <!-- Filter Bar -->
    <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-xs">
        <form action="{{ route('admin.visa.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-4 gap-3">
            <div class="sm:col-span-2">
                <input type="text" name="search" value="{{ request('search') }}"
                       class="w-full px-3 py-2 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-amber-500 text-xs"
                       placeholder="Search applicant name, app number, passport, phone...">
            </div>

            <div>
                <select name="status" class="w-full px-3 py-2 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-amber-500 text-xs">
                    <option value="">All Statuses</option>
                    <option value="new" {{ request('status') == 'new' ? 'selected' : '' }}>New Applications</option>
                    <option value="documents_pending" {{ request('status') == 'documents_pending' ? 'selected' : '' }}>Docs Pending</option>
                    <option value="submitted" {{ request('status') == 'submitted' ? 'selected' : '' }}>Submitted to Embassy</option>
                    <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved / Stamped</option>
                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                </select>
            </div>

            <div class="flex gap-2">
                <button type="submit" class="flex-1 py-2 bg-slate-900 hover:bg-black text-white font-bold rounded-xl text-xs transition">
                    Filter
                </button>
                <a href="{{ route('admin.visa.index') }}" class="px-3 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-xl text-xs transition text-center">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Visa Table -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-50 text-slate-500 font-semibold uppercase text-[10px] tracking-wider border-b border-slate-200">
                    <tr>
                        <th class="py-3.5 px-6">App Ref</th>
                        <th class="py-3.5 px-6">Target Country</th>
                        <th class="py-3.5 px-6">Applicant Name</th>
                        <th class="py-3.5 px-6">Passport No</th>
                        <th class="py-3.5 px-6">Category</th>
                        <th class="py-3.5 px-6">Travel Date</th>
                        <th class="py-3.5 px-6">Assigned Staff</th>
                        <th class="py-3.5 px-6">Status</th>
                        <th class="py-3.5 px-6 text-right">Dossier</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($applications as $visa)
                        <tr class="hover:bg-slate-50/50">
                            <td class="py-4 px-6 font-bold text-slate-900">
                                <a href="{{ route('admin.visa.show', $visa->id) }}" class="text-amber-600 hover:text-amber-700">
                                    {{ $visa->application_number }}
                                </a>
                            </td>
                            <td class="py-4 px-6 font-bold text-slate-800">
                                {{ $visa->country?->name ?? 'International' }}
                            </td>
                            <td class="py-4 px-6">
                                <span class="font-bold text-slate-900 block">{{ $visa->applicant_name }}</span>
                                <span class="text-[11px] text-slate-400">{{ $visa->contact_phone }}</span>
                            </td>
                            <td class="py-4 px-6 uppercase font-semibold text-slate-700">
                                {{ $visa->passport_number ?? 'Pending' }}
                            </td>
                            <td class="py-4 px-6">
                                {{ $visa->visa_category }}
                            </td>
                            <td class="py-4 px-6">
                                {{ $visa->travel_date ? \Carbon\Carbon::parse($visa->travel_date)->format('M d, Y') : 'N/A' }}
                            </td>
                            <td class="py-4 px-6">
                                {{ $visa->assignedEmployee?->name ?? 'Unassigned' }}
                            </td>
                            <td class="py-4 px-6">
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold 
                                    {{ $visa->status == 'approved' ? 'bg-emerald-100 text-emerald-800' : ($visa->status == 'submitted' ? 'bg-blue-100 text-blue-800' : 'bg-amber-100 text-amber-800') }}">
                                    {{ ucfirst(str_replace('_', ' ', $visa->status)) }}
                                </span>
                            </td>
                            <td class="py-4 px-6 text-right">
                                <a href="{{ route('admin.visa.show', $visa->id) }}" class="inline-flex items-center gap-1 px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-800 font-bold rounded-lg transition">
                                    Manage &rarr;
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="py-12 text-center text-slate-400">
                                No visa applications found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($applications->hasPages())
            <div class="p-4 border-t border-slate-100">
                {{ $applications->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

