@extends('layouts.admin')

@section('title', 'Staff & Employee Directory - BharatTravel CRM')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Staff & Team Management</h1>
            <p class="text-sm text-gray-500">Manage internal travel consultants, visa specialists, accounts & operations team</p>
        </div>
        <a href="{{ route('admin.employees.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-orange-600 hover:bg-orange-700 text-white rounded-lg text-sm font-semibold transition-colors shadow-sm">
            <i class="fas fa-user-plus"></i> Onboard New Staff
        </a>
    </div>

    <!-- Filter & Search Bar -->
    <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100">
        <form action="{{ route('admin.employees.index') }}" method="GET" class="flex flex-wrap items-center gap-3">
            <div class="flex-1 min-w-[240px]">
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                        <i class="fas fa-search"></i>
                    </span>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name, email, code or designation..." class="w-full pl-9 pr-4 py-2 text-sm border border-gray-200 rounded-lg focus:ring-orange-500 focus:border-orange-500">
                </div>
            </div>

            <div class="w-48">
                <select name="branch_id" class="w-full py-2 px-3 text-sm border border-gray-200 rounded-lg focus:ring-orange-500 focus:border-orange-500">
                    <option value="">All Branches</option>
                    @foreach($branches as $b)
                        <option value="{{ $b->id }}" {{ request('branch_id') == $b->id ? 'selected' : '' }}>{{ $b->name }} ({{ $b->city }})</option>
                    @endforeach
                </select>
            </div>

            <button type="submit" class="px-4 py-2 bg-gray-900 hover:bg-gray-800 text-white text-sm font-medium rounded-lg transition-colors">
                Filter
            </button>
            @if(request()->hasAny(['search', 'branch_id']))
                <a href="{{ route('admin.employees.index') }}" class="px-3 py-2 text-sm text-gray-500 hover:text-gray-700">Clear</a>
            @endif
        </form>
    </div>

    <!-- Employees Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-gray-50 text-gray-500 font-semibold uppercase text-xs border-b border-gray-100">
                    <tr>
                        <th class="px-6 py-4">Employee</th>
                        <th class="px-6 py-4">Department & Role</th>
                        <th class="px-6 py-4">Branch Office</th>
                        <th class="px-6 py-4 text-right">Sales Target</th>
                        <th class="px-6 py-4 text-center">Comm. %</th>
                        <th class="px-6 py-4 text-center">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($employees as $emp)
                        <tr class="hover:bg-gray-50/60 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-orange-100 text-orange-600 font-bold flex items-center justify-center flex-shrink-0">
                                        {{ strtoupper(substr($emp->user->name ?? 'E', 0, 2)) }}
                                    </div>
                                    <div>
                                        <div class="font-semibold text-gray-900">{{ $emp->user->name ?? 'N/A' }}</div>
                                        <div class="text-xs text-gray-500 flex items-center gap-2 mt-0.5">
                                            <span><i class="far fa-envelope mr-1"></i>{{ $emp->user->email ?? 'N/A' }}</span>
                                            <span>&bull;</span>
                                            <span><i class="fas fa-phone-alt mr-1"></i>{{ $emp->user->phone ?? 'N/A' }}</span>
                                        </div>
                                        <span class="inline-block mt-1 font-mono text-[11px] bg-gray-100 text-gray-700 px-2 py-0.5 rounded">
                                            {{ $emp->employee_code }}
                                        </span>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-medium text-gray-900">{{ $emp->designation }}</div>
                                <div class="text-xs text-gray-500">{{ $emp->department->name ?? 'General Staff' }}</div>
                            </td>
                            <td class="px-6 py-4 text-gray-700">
                                @if($emp->branch)
                                    <div class="font-medium">{{ $emp->branch->name }}</div>
                                    <div class="text-xs text-gray-500">{{ $emp->branch->city }}</div>
                                @else
                                    <span class="text-gray-400">Head Office</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right font-medium text-gray-900">
                                ₹{{ number_format($emp->sales_target ?? 0) }}
                            </td>
                            <td class="px-6 py-4 text-center font-medium text-gray-700">
                                {{ $emp->commission_rate ? $emp->commission_rate . '%' : '0%' }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if($emp->is_active)
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Active
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-600">
                                        <span class="w-1.5 h-1.5 rounded-full bg-gray-400"></span> Inactive
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                                <i class="fas fa-users text-4xl text-gray-300 mb-3 block"></i>
                                No staff members found matching criteria.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($employees->hasPages())
            <div class="px-6 py-4 border-t border-gray-100 bg-gray-50">
                {{ $employees->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

