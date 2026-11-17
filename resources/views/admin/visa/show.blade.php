@extends('layouts.admin')

@section('title', 'Visa File: ' . $application->application_number)

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-xs flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <a href="{{ route('admin.visa.index') }}" class="text-xs font-bold text-slate-500 hover:text-slate-900">&larr; Back to Visa Desk</a>
                <span class="text-slate-300">•</span>
                <span class="text-xs font-bold text-blue-600 uppercase">#{{ $application->application_number }}</span>
                <span class="text-slate-300">•</span>
                <span class="text-xs text-slate-500">{{ $application->created_at->format('M d, Y') }}</span>
            </div>
            <h1 class="text-2xl font-bold font-serif text-slate-900">{{ $application->applicant_name }} &bull; {{ $application->country?->name }}</h1>
            <p class="text-xs text-slate-500 mt-0.5">
                Category: <span class="font-bold text-slate-800">{{ $application->visa_category }}</span>
                &bull; Passport: <span class="font-bold uppercase text-slate-800">{{ $application->passport_number ?? 'Awaiting Copy' }}</span>
                &bull; Phone: <span class="text-slate-800">{{ $application->contact_phone }}</span>
            </p>
        </div>

        <div>
            <span class="px-3.5 py-1.5 rounded-full text-xs font-bold 
                {{ $application->status == 'approved' ? 'bg-emerald-100 text-emerald-800' : ($application->status == 'submitted' ? 'bg-blue-100 text-blue-800' : 'bg-amber-100 text-amber-800') }}">
                Status: {{ ucfirst(str_replace('_', ' ', $application->status)) }}
            </span>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Col 1: Overview & Applicant Details -->
        <div class="space-y-6">
            <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-xs text-xs space-y-3">
                <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider mb-2 flex items-center gap-2">
                    <i data-lucide="user" class="w-4 h-4 text-blue-600"></i> Applicant Dossier
                </h3>

                <div class="flex justify-between py-1.5 border-b border-slate-100">
                    <span class="text-slate-500">Applicant:</span>
                    <span class="font-bold text-slate-800">{{ $application->applicant_name }}</span>
                </div>
                <div class="flex justify-between py-1.5 border-b border-slate-100">
                    <span class="text-slate-500">Target Country:</span>
                    <span class="font-bold text-slate-800">{{ $application->country?->name }} ({{ $application->country?->code }})</span>
                </div>
                <div class="flex justify-between py-1.5 border-b border-slate-100">
                    <span class="text-slate-500">Visa Category:</span>
                    <span class="font-bold text-slate-800">{{ $application->visa_category }}</span>
                </div>
                <div class="flex justify-between py-1.5 border-b border-slate-100">
                    <span class="text-slate-500">Passport Number:</span>
                    <span class="font-bold uppercase text-slate-800">{{ $application->passport_number ?? 'N/A' }}</span>
                </div>
                <div class="flex justify-between py-1.5 border-b border-slate-100">
                    <span class="text-slate-500">Tentative Travel:</span>
                    <span class="font-bold text-slate-800">{{ $application->travel_date ? \Carbon\Carbon::parse($application->travel_date)->format('M d, Y') : 'Open' }}</span>
                </div>
                <div class="flex justify-between py-1.5 border-b border-slate-100">
                    <span class="text-slate-500">Assigned Officer:</span>
                    <span class="font-bold text-slate-800">{{ $application->assignedEmployee?->name ?? 'Unassigned' }}</span>
                </div>
                <div class="flex justify-between py-1.5">
                    <span class="text-slate-500">Consular Fee:</span>
                    <span class="font-bold text-emerald-700">{{ $application->fee ? '₹' . number_format($application->fee, 2) : 'TBD' }}</span>
                </div>

                @if($application->remarks)
                    <div class="pt-2 border-t border-slate-100">
                        <p class="text-slate-400 font-semibold mb-1">Remarks & Notes:</p>
                        <p class="p-2.5 bg-slate-50 rounded-xl text-slate-700 leading-relaxed">{{ $application->remarks }}</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Col 2 & 3: Consular Processing Form & Biometrics -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-xs text-xs">
                <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider mb-4 flex items-center gap-2">
                    <i data-lucide="refresh-cw" class="w-4 h-4 text-blue-600"></i> Update Visa Processing Stage & Embassy Tracking
                </h3>

                <form action="{{ route('admin.visa.status', $application->id) }}" method="POST" class="space-y-4">
                    @csrf
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block font-semibold text-slate-700 mb-1">Processing Status *</label>
                            <select name="status" required class="w-full px-3 py-2 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-blue-500 text-xs">
                                <option value="new" {{ $application->status == 'new' ? 'selected' : '' }}>New Application</option>
                                <option value="documents_pending" {{ $application->status == 'documents_pending' ? 'selected' : '' }}>Documents Pending from Client</option>
                                <option value="documents_verified" {{ $application->status == 'documents_verified' ? 'selected' : '' }}>Documents Scrutinized & Verified</option>
                                <option value="appointment_scheduled" {{ $application->status == 'appointment_scheduled' ? 'selected' : '' }}>VFS / Embassy Slot Scheduled</option>
                                <option value="submitted" {{ $application->status == 'submitted' ? 'selected' : '' }}>Submitted to Consular Section</option>
                                <option value="approved" {{ $application->status == 'approved' ? 'selected' : '' }}>Approved & Visa Issued</option>
                                <option value="rejected" {{ $application->status == 'rejected' ? 'selected' : '' }}>Rejected by Embassy</option>
                                <option value="delivered" {{ $application->status == 'delivered' ? 'selected' : '' }}>Dispatched / Delivered to Client</option>
                            </select>
                        </div>

                        <div>
                            <label class="block font-semibold text-slate-700 mb-1">Assign Visa Executive</label>
                            <select name="assigned_to" class="w-full px-3 py-2 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-blue-500 text-xs">
                                <option value="">Select Staff</option>
                                @foreach($staffMembers as $stf)
                                    <option value="{{ $stf->id }}" {{ $application->assigned_to == $stf->id ? 'selected' : '' }}>{{ $stf->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block font-semibold text-slate-700 mb-1">VFS / Biometric Appointment Date</label>
                            <input type="date" name="appointment_date" value="{{ old('appointment_date', $application->appointment_date ? \Carbon\Carbon::parse($application->appointment_date)->format('Y-m-d') : '') }}" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs">
                        </div>

                        <div>
                            <label class="block font-semibold text-slate-700 mb-1">Embassy Submission Date</label>
                            <input type="date" name="submission_date" value="{{ old('submission_date', $application->submission_date ? \Carbon\Carbon::parse($application->submission_date)->format('Y-m-d') : '') }}" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs">
                        </div>

                        <div>
                            <label class="block font-semibold text-slate-700 mb-1">Visa Issued / Approved Date</label>
                            <input type="date" name="actual_date" value="{{ old('actual_date', $application->actual_date ? \Carbon\Carbon::parse($application->actual_date)->format('Y-m-d') : '') }}" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs">
                        </div>

                        <div>
                            <label class="block font-semibold text-slate-700 mb-1">Consular & Service Fee (₹)</label>
                            <input type="number" step="0.01" name="fee" value="{{ old('fee', $application->fee) }}" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs font-bold" placeholder="e.g. 8500">
                        </div>
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Officer Remarks & Tracking Notes</label>
                        <textarea name="remarks" rows="3" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs" placeholder="Passport courier tracking number, VFS reference ID...">{{ old('remarks', $application->remarks) }}</textarea>
                    </div>

                    <button type="submit" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl text-xs transition shadow-xs">
                        Save Visa Status & Tracking Updates
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

