@extends('layouts.admin')

@section('title', 'Onboard New Staff Member - BharatTravel CRM')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <a href="{{ route('admin.employees.index') }}" class="text-xs font-semibold text-gray-500 hover:text-orange-600 flex items-center gap-1 mb-1">
                <i class="fas fa-arrow-left"></i> Back to Staff Directory
            </a>
            <h1 class="text-2xl font-bold text-gray-900">Onboard New Staff Member</h1>
            <p class="text-sm text-gray-500">Create login credentials and operational assignment for new employee</p>
        </div>
    </div>

    @if ($errors->any())
        <div class="p-4 bg-red-50 border border-red-200 rounded-xl">
            <div class="flex items-center gap-2 text-red-800 font-semibold mb-2">
                <i class="fas fa-exclamation-circle"></i> Please fix the errors below:
            </div>
            <ul class="list-disc list-inside text-xs text-red-700 space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.employees.store') }}" method="POST" class="space-y-6">
        @csrf

        <!-- Account Credentials Section -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 space-y-4">
            <h2 class="text-base font-bold text-gray-900 flex items-center gap-2 border-b border-gray-100 pb-3">
                <i class="fas fa-user-circle text-orange-500"></i> Identity & Login Credentials
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Full Legal Name <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}" required placeholder="e.g. Priya Sharma" class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 focus:ring-orange-500 focus:border-orange-500">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Work Email Address <span class="text-red-500">*</span></label>
                    <input type="email" name="email" value="{{ old('email') }}" required placeholder="e.g. priya@bharattravel.com" class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 focus:ring-orange-500 focus:border-orange-500">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Mobile / WhatsApp Number <span class="text-red-500">*</span></label>
                    <input type="text" name="phone" value="{{ old('phone') }}" required placeholder="e.g. +91 98765 43210" class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 focus:ring-orange-500 focus:border-orange-500">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Temporary Password <span class="text-red-500">*</span></label>
                    <input type="password" name="password" required placeholder="Minimum 8 characters" class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 focus:ring-orange-500 focus:border-orange-500">
                </div>
            </div>
        </div>

        <!-- Role & Assignment Section -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 space-y-4">
            <h2 class="text-base font-bold text-gray-900 flex items-center gap-2 border-b border-gray-100 pb-3">
                <i class="fas fa-briefcase text-orange-500"></i> Role, Designation & Branch
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">System Permission Role <span class="text-red-500">*</span></label>
                    <select name="role_slug" required class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 focus:ring-orange-500 focus:border-orange-500">
                        <option value="">Select Role...</option>
                        @foreach($roles as $r)
                            <option value="{{ $r->slug }}" {{ old('role_slug') == $r->slug ? 'selected' : '' }}>{{ $r->name }} ({{ $r->slug }})</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Designation Title <span class="text-red-500">*</span></label>
                    <input type="text" name="designation" value="{{ old('designation') }}" required placeholder="e.g. Senior Holiday Consultant" class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 focus:ring-orange-500 focus:border-orange-500">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Department</label>
                    <select name="department_id" class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 focus:ring-orange-500 focus:border-orange-500">
                        <option value="">Select Department...</option>
                        @foreach($departments as $d)
                            <option value="{{ $d->id }}" {{ old('department_id') == $d->id ? 'selected' : '' }}>{{ $d->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Branch Location</label>
                    <select name="branch_id" class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 focus:ring-orange-500 focus:border-orange-500">
                        <option value="">Head Office / Main Branch</option>
                        @foreach($branches as $b)
                            <option value="{{ $b->id }}" {{ old('branch_id') == $b->id ? 'selected' : '' }}>{{ $b->name }} ({{ $b->city }})</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <!-- Targets & Compensation -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 space-y-4">
            <h2 class="text-base font-bold text-gray-900 flex items-center gap-2 border-b border-gray-100 pb-3">
                <i class="fas fa-chart-line text-orange-500"></i> Targets & Joining Details
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Monthly Sales Target (₹)</label>
                    <input type="number" step="0.01" name="sales_target" value="{{ old('sales_target', 250000) }}" placeholder="e.g. 500000" class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 focus:ring-orange-500 focus:border-orange-500">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Commission Rate (%)</label>
                    <input type="number" step="0.1" name="commission_rate" value="{{ old('commission_rate', 2.5) }}" placeholder="e.g. 2.5" class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 focus:ring-orange-500 focus:border-orange-500">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Joining Date</label>
                    <input type="date" name="joining_date" value="{{ old('joining_date', date('Y-m-d')) }}" class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 focus:ring-orange-500 focus:border-orange-500">
                </div>
            </div>
        </div>

        <div class="flex items-center justify-end gap-3 pt-2">
            <a href="{{ route('admin.employees.index') }}" class="px-5 py-2.5 border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 transition-colors">
                Cancel
            </a>
            <button type="submit" class="px-6 py-2.5 bg-orange-600 hover:bg-orange-700 text-white text-sm font-semibold rounded-lg shadow-sm transition-colors">
                Create & Onboard Staff
            </button>
        </div>
    </form>
</div>
@endsection

