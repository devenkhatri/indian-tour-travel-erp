@extends('layouts.admin')

@section('title', 'Passport File: ' . $application->application_number)

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-xs flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <a href="{{ route('admin.passport.index') }}" class="text-xs font-bold text-slate-500 hover:text-slate-900">&larr; Back to Passport Desk</a>
                <span class="text-slate-300">•</span>
                <span class="text-xs font-bold text-indigo-600 uppercase">#{{ $application->application_number }}</span>
                <span class="text-slate-300">•</span>
                <span class="text-xs text-slate-500">{{ $application->created_at->format('M d, Y') }}</span>
            </div>
            <h1 class="text-2xl font-bold font-serif text-slate-900">{{ $application->applicant_name }} &bull; PSK Assistance</h1>
            <p class="text-xs text-slate-500 mt-0.5">
                Service: <span class="font-bold uppercase text-indigo-700">{{ $application->service_type }}</span>
                &bull; Phone: <span class="text-slate-800">{{ $application->contact_phone }}</span>
                &bull; Email: <span class="text-slate-800">{{ $application->contact_email }}</span>
            </p>
        </div>

        <div>
            <span class="px-3.5 py-1.5 rounded-full text-xs font-bold 
                {{ $application->status == 'dispatched' ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }}">
                Status: {{ ucfirst(str_replace('_', ' ', $application->status)) }}
            </span>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Col 1: Overview -->
        <div class="space-y-6">
            <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-xs text-xs space-y-3">
                <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider mb-2 flex items-center gap-2">
                    <i data-lucide="book-open" class="w-4 h-4 text-indigo-600"></i> Passport Application Specs
                </h3>

                <div class="flex justify-between py-1.5 border-b border-slate-100">
                    <span class="text-slate-500">Applicant Legal Name:</span>
                    <span class="font-bold text-slate-800">{{ $application->applicant_name }}</span>
                </div>
                <div class="flex justify-between py-1.5 border-b border-slate-100">
                    <span class="text-slate-500">Service Category:</span>
                    <span class="font-bold uppercase text-indigo-700">{{ $application->service_type }}</span>
                </div>
                <div class="flex justify-between py-1.5 border-b border-slate-100">
                    <span class="text-slate-500">PSK Slot Date:</span>
                    <span class="font-bold text-slate-800">{{ $application->appointment_date ? \Carbon\Carbon::parse($application->appointment_date)->format('M d, Y') : 'Not Slotted' }}</span>
                </div>
                <div class="flex justify-between py-1.5 border-b border-slate-100">
                    <span class="text-slate-500">PSK Center / Location:</span>
                    <span class="font-bold text-slate-800">{{ $application->appointment_location ?? 'TBD' }}</span>
                </div>
                <div class="flex justify-between py-1.5 border-b border-slate-100">
                    <span class="text-slate-500">Assigned Case Officer:</span>
                    <span class="font-bold text-slate-800">{{ $application->assignedEmployee?->name ?? 'Unassigned' }}</span>
                </div>
                <div class="flex justify-between py-1.5">
                    <span class="text-slate-500">Service Fee:</span>
                    <span class="font-bold text-emerald-700">{{ $application->fee ? '₹' . number_format($application->fee, 2) : 'Pending' }}</span>
                </div>

                @if($application->remarks)
                    <div class="pt-2 border-t border-slate-100">
                        <p class="text-slate-400 font-semibold mb-1">Notes & ARN:</p>
                        <p class="p-2.5 bg-slate-50 rounded-xl text-slate-700 leading-relaxed">{{ $application->remarks }}</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Col 2 & 3: PSK Scheduling Form -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-xs text-xs">
                <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider mb-4 flex items-center gap-2">
                    <i data-lucide="refresh-cw" class="w-4 h-4 text-indigo-600"></i> Update PSK Appointment & Status
                </h3>

                <form action="{{ route('admin.passport.status', $application->id) }}" method="POST" class="space-y-4">
                    @csrf
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block font-semibold text-slate-700 mb-1">Passport File Status *</label>
                            <select name="status" required class="w-full px-3 py-2 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 text-xs">
                                <option value="enquiry" {{ $application->status == 'enquiry' ? 'selected' : '' }}>Enquiry Received</option>
                                <option value="documents_pending" {{ $application->status == 'documents_pending' ? 'selected' : '' }}>Annexure / Docs Pending</option>
                                <option value="appointment_scheduled" {{ $application->status == 'appointment_scheduled' ? 'selected' : '' }}>PSK Slot Booked</option>
                                <option value="submitted" {{ $application->status == 'submitted' ? 'selected' : '' }}>Biometrics Completed at PSK</option>
                                <option value="police_verification" {{ $application->status == 'police_verification' ? 'selected' : '' }}>Police Verification Initiated</option>
                                <option value="printing" {{ $application->status == 'printing' ? 'selected' : '' }}>Printing at India Security Press</option>
                                <option value="dispatched" {{ $application->status == 'dispatched' ? 'selected' : '' }}>Dispatched via Speed Post</option>
                            </select>
                        </div>

                        <div>
                            <label class="block font-semibold text-slate-700 mb-1">Assign Passport Executive</label>
                            <select name="assigned_to" class="w-full px-3 py-2 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 text-xs">
                                <option value="">Select Staff</option>
                                @foreach($staffMembers as $stf)
                                    <option value="{{ $stf->id }}" {{ $application->assigned_to == $stf->id ? 'selected' : '' }}>{{ $stf->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block font-semibold text-slate-700 mb-1">PSK Appointment Slot Date</label>
                            <input type="date" name="appointment_date" value="{{ old('appointment_date', $application->appointment_date ? \Carbon\Carbon::parse($application->appointment_date)->format('Y-m-d') : '') }}" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs">
                        </div>

                        <div>
                            <label class="block font-semibold text-slate-700 mb-1">PSK Location / POPSK Center</label>
                            <input type="text" name="appointment_location" value="{{ old('appointment_location', $application->appointment_location) }}" placeholder="e.g. PSK Herald House, ITO, Delhi" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs">
                        </div>

                        <div>
                            <label class="block font-semibold text-slate-700 mb-1">Facilitation Fee (₹)</label>
                            <input type="number" step="0.01" name="fee" value="{{ old('fee', $application->fee) }}" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs font-bold" placeholder="e.g. 2500">
                        </div>
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">ARN Number / Speed Post Tracking & Remarks</label>
                        <textarea name="remarks" rows="3" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs" placeholder="Application Reference Number (ARN) or Speed Post Tracking Number...">{{ old('remarks', $application->remarks) }}</textarea>
                    </div>

                    <button type="submit" class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl text-xs transition shadow-xs">
                        Save Passport Details
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

