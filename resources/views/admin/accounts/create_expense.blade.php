@extends('layouts.admin')

@section('title', 'Log New Expense | BharatTravel')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-xs flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <a href="{{ route('admin.accounts.expenses') }}" class="text-xs font-bold text-slate-500 hover:text-slate-900">&larr; Back to Expenses</a>
                <span class="text-slate-300">•</span>
                <span class="text-xs font-bold text-amber-600 uppercase">Cash Outflow</span>
            </div>
            <h1 class="text-xl font-bold font-serif text-slate-900">Record Operational Expense</h1>
            <p class="text-xs text-slate-500 mt-0.5">Post an administrative overhead, marketing cost, or vendor advance voucher.</p>
        </div>
    </div>

    @if($errors->any())
        <div class="p-4 bg-rose-50 border-l-4 border-rose-500 text-rose-700 text-xs rounded-r">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white rounded-2xl p-8 border border-slate-200 shadow-xs">
        <form action="{{ route('admin.accounts.expenses.store') }}" method="POST" class="space-y-6 text-xs">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="sm:col-span-2">
                    <label class="block font-semibold text-slate-700 mb-1">Expense Title / Description *</label>
                    <input type="text" name="title" required value="{{ old('title') }}"
                           class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-amber-500 text-xs"
                           placeholder="e.g. Google Ads Campaign - Kashmir Season Promo">
                </div>

                <div>
                    <label class="block font-semibold text-slate-700 mb-1">Expense Category *</label>
                    <select name="category" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-amber-500 text-xs">
                        <option value="marketing">Digital Marketing & Ads</option>
                        <option value="operational">Ground Operations</option>
                        <option value="supplier">Vendor / Hotel Payment</option>
                        <option value="office">Office Rent & Utilities</option>
                        <option value="salary">Staff Salary / Bonus</option>
                        <option value="travel">Internal Travel & Inspection</option>
                        <option value="other">Other Miscellaneous</option>
                    </select>
                </div>

                <div>
                    <label class="block font-semibold text-slate-700 mb-1">Amount (₹) *</label>
                    <input type="number" step="0.01" name="amount" min="1" required value="{{ old('amount', 5000) }}"
                           class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-amber-500 text-xs font-bold">
                </div>

                <div>
                    <label class="block font-semibold text-slate-700 mb-1">Disbursement Date *</label>
                    <input type="date" name="expense_date" required value="{{ date('Y-m-d') }}" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-xs">
                </div>

                <div>
                    <label class="block font-semibold text-slate-700 mb-1">Branch Office</label>
                    <select name="branch_id" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-xs">
                        <option value="">Headquarters</option>
                        @foreach($branches as $br)
                            <option value="{{ $br->id }}">{{ $br->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block font-semibold text-slate-700 mb-1">Paid To (Beneficiary / Vendor)</label>
                    <input type="text" name="paid_to" placeholder="e.g. Google India / Landlord" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-xs">
                </div>

                <div>
                    <label class="block font-semibold text-slate-700 mb-1">Payment Method *</label>
                    <select name="payment_method" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-xs">
                        <option value="bank_transfer" selected>NEFT / RTGS</option>
                        <option value="upi">UPI</option>
                        <option value="cash">Cash Voucher</option>
                        <option value="cheque">Cheque</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block font-semibold text-slate-700 mb-1">Notes & Reference Vouchers</label>
                <textarea name="notes" rows="2" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-xs" placeholder="Transaction ref, invoice copy remarks...">{{ old('notes') }}</textarea>
            </div>

            <button type="submit" class="px-8 py-3 bg-amber-500 hover:bg-amber-600 text-slate-950 font-bold rounded-xl text-xs transition shadow-sm flex items-center justify-center gap-2">
                <i data-lucide="check-circle" class="w-4 h-4"></i> Record Expense Voucher
            </button>
        </form>
    </div>
</div>
@endsection

