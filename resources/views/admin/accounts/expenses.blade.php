@extends('layouts.admin')

@section('title', 'Company Expenses & Disbursements | BharatTravel')

@section('content')
<div class="space-y-6">
    <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-xs flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-xl font-bold text-slate-900">Company Expenses & Vendor Outflows</h1>
            <p class="text-xs text-slate-500 mt-1">Operational overheads, office rent, Google/Meta digital marketing, local driver allowances, and staff payouts.</p>
        </div>
        <a href="{{ route('admin.accounts.expenses.create') }}" class="px-4 py-2 bg-amber-500 hover:bg-amber-600 text-slate-950 text-xs font-bold rounded-xl transition shadow-xs flex items-center gap-2">
            <i data-lucide="plus" class="w-4 h-4"></i> Record New Expense
        </a>
    </div>

    <!-- Total Expenses Card -->
    <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs max-w-sm">
        <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Total Settled Expenses</span>
        <div class="text-2xl font-extrabold text-slate-900 mt-1">₹{{ number_format($totalExpenses, 2) }}</div>
        <p class="text-[11px] text-slate-400 mt-1">Cumulative operational disbursements</p>
    </div>

    <!-- Expenses Table -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-50 text-slate-500 font-semibold uppercase text-[10px] tracking-wider border-b border-slate-200">
                    <tr>
                        <th class="py-3.5 px-6">Expense Ref</th>
                        <th class="py-3.5 px-6">Title & Category</th>
                        <th class="py-3.5 px-6">Paid To (Vendor)</th>
                        <th class="py-3.5 px-6">Disbursement Date</th>
                        <th class="py-3.5 px-6">Payment Mode</th>
                        <th class="py-3.5 px-6">Branch Office</th>
                        <th class="py-3.5 px-6">Amount (INR)</th>
                        <th class="py-3.5 px-6">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($expenses as $exp)
                        <tr class="hover:bg-slate-50/50">
                            <td class="py-4 px-6 font-bold text-slate-900">{{ $exp->expense_number }}</td>
                            <td class="py-4 px-6">
                                <span class="font-bold text-slate-900 block">{{ $exp->title }}</span>
                                <span class="text-[10px] uppercase font-bold text-slate-400">{{ $exp->category }}</span>
                            </td>
                            <td class="py-4 px-6 font-semibold text-slate-800">{{ $exp->paid_to ?? 'Internal' }}</td>
                            <td class="py-4 px-6">{{ \Carbon\Carbon::parse($exp->expense_date)->format('M d, Y') }}</td>
                            <td class="py-4 px-6 uppercase text-[10px] font-bold text-slate-500">{{ str_replace('_', ' ', $exp->payment_method) }}</td>
                            <td class="py-4 px-6">{{ $exp->branch?->name ?? 'Headquarters' }}</td>
                            <td class="py-4 px-6 font-extrabold text-slate-900">₹{{ number_format($exp->amount, 2) }}</td>
                            <td class="py-4 px-6">
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800 uppercase">
                                    {{ $exp->status }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-12 text-center text-slate-400">
                                No expenses logged.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($expenses->hasPages())
            <div class="p-4 border-t border-slate-100">
                {{ $expenses->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

