@extends('layouts.admin')

@section('title', 'Global System & Agency Settings - BharatTravel CRM')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">System & Agency Settings</h1>
            <p class="text-sm text-gray-500">Configure company profile, GST SAC 9985 codes, bank coordinates, and B2B commissions</p>
        </div>
    </div>

    @if(session('success'))
        <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl text-sm flex items-center gap-2">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('admin.settings.update') }}" method="POST" class="space-y-6">
        @csrf

        <!-- Company Identity -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 space-y-4">
            <h2 class="text-base font-bold text-gray-900 flex items-center gap-2 border-b border-gray-100 pb-3">
                <i class="fas fa-building text-orange-500"></i> Legal Travel Agency Profile
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Company / Legal Entity Name</label>
                    <input type="text" name="company_name" value="{{ $settings['company_name'] ?? 'BharatTravel Holidays Pvt Ltd' }}" class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Agency Support Email</label>
                    <input type="email" name="company_email" value="{{ $settings['company_email'] ?? 'support@bharattravel.com' }}" class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Primary Hotline / Toll Free</label>
                    <input type="text" name="company_phone" value="{{ $settings['company_phone'] ?? '+91 11 4567 8900' }}" class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">24x7 WhatsApp Desk</label>
                    <input type="text" name="company_whatsapp" value="{{ $settings['company_whatsapp'] ?? '+91 98765 43210' }}" class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Registered Head Office Address (Appears on Tax Invoices)</label>
                    <textarea name="company_address" rows="2" class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2">{{ $settings['company_address'] ?? 'Suite 401-404, Connaught Place, New Delhi - 110001, India' }}</textarea>
                </div>
            </div>
        </div>

        <!-- Taxation & GST Configuration -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 space-y-4">
            <h2 class="text-base font-bold text-gray-900 flex items-center gap-2 border-b border-gray-100 pb-3">
                <i class="fas fa-file-invoice-dollar text-orange-500"></i> GST, SAC & Invoicing Parameters
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">GSTIN Number</label>
                    <input type="text" name="gstin" value="{{ $settings['gstin'] ?? '07AABCB1234F1Z8' }}" class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 font-mono">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Primary SAC Code</label>
                    <input type="text" name="sac_code" value="{{ $settings['sac_code'] ?? '998555' }}" class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 font-mono">
                    <span class="text-[10px] text-gray-400">998555 = Tour Operator Services</span>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Package GST Rate (%)</label>
                    <input type="number" step="0.5" name="default_gst_rate" value="{{ $settings['default_gst_rate'] ?? '5' }}" class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2">
                    <span class="text-[10px] text-gray-400">Standard 5% (without ITC) for Tour Operators</span>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Invoice Prefix</label>
                    <input type="text" name="invoice_prefix" value="{{ $settings['invoice_prefix'] ?? 'INV-2025/' }}" class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 font-mono">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Quotation Prefix</label>
                    <input type="text" name="quotation_prefix" value="{{ $settings['quotation_prefix'] ?? 'QTN-2025/' }}" class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 font-mono">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Default B2B Commission (%)</label>
                    <input type="number" step="0.5" name="b2b_default_commission" value="{{ $settings['b2b_default_commission'] ?? '5.0' }}" class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2">
                </div>
            </div>
        </div>

        <!-- Banking Information -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 space-y-4">
            <h2 class="text-base font-bold text-gray-900 flex items-center gap-2 border-b border-gray-100 pb-3">
                <i class="fas fa-university text-orange-500"></i> Bank Account for NEFT/RTGS/IMPS
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Bank Name</label>
                    <input type="text" name="bank_name" value="{{ $settings['bank_name'] ?? 'HDFC Bank Ltd' }}" class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Account Holder Name</label>
                    <input type="text" name="bank_account_holder" value="{{ $settings['bank_account_holder'] ?? 'BharatTravel Holidays Private Limited' }}" class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Current Account Number</label>
                    <input type="text" name="bank_account_no" value="{{ $settings['bank_account_no'] ?? '50200012345678' }}" class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 font-mono">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">IFSC Code</label>
                    <input type="text" name="bank_ifsc" value="{{ $settings['bank_ifsc'] ?? 'HDFC0000123' }}" class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 font-mono">
                </div>
            </div>
        </div>

        <!-- Payment Gateway Credentials -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 space-y-4">
            <h2 class="text-base font-bold text-gray-900 flex items-center gap-2 border-b border-gray-100 pb-3">
                <i class="fas fa-credit-card text-orange-500"></i> Online Payment Gateway (Razorpay)
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Razorpay Key ID</label>
                    <input type="text" name="razorpay_key_id" value="{{ $settings['razorpay_key_id'] ?? 'rzp_test_sampleKey123' }}" class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 font-mono">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Razorpay Key Secret</label>
                    <input type="password" name="razorpay_key_secret" value="{{ $settings['razorpay_key_secret'] ?? '••••••••••••••••' }}" class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 font-mono">
                </div>
            </div>
        </div>

        <div class="flex items-center justify-end gap-3 pt-2">
            <button type="submit" class="px-6 py-2.5 bg-orange-600 hover:bg-orange-700 text-white text-sm font-semibold rounded-lg shadow-sm transition-colors">
                Save & Apply Settings
            </button>
        </div>
    </form>
</div>
@endsection

