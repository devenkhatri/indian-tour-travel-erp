<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Tax Invoice - {{ $invoice->invoice_number }}</title>
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 11px; line-height: 1.4; color: #1e293b; margin: 0; padding: 20px; }
        .header-table { width: 100%; border-bottom: 2px solid #0f172a; padding-bottom: 15px; margin-bottom: 20px; }
        .invoice-title { font-size: 22px; font-weight: bold; color: #0f172a; text-transform: uppercase; }
        .invoice-subtitle { font-size: 9px; color: #64748b; letter-spacing: 1px; }
        .company-info { text-align: right; font-size: 10px; color: #475569; }
        .parties-table { width: 100%; margin-bottom: 20px; }
        .parties-table td { width: 50%; vertical-align: top; font-size: 10px; line-height: 1.5; }
        .box-title { font-size: 11px; font-weight: bold; color: #0f172a; margin-bottom: 5px; text-transform: uppercase; border-bottom: 1px solid #cbd5e1; padding-bottom: 3px; }
        .items-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .items-table th { background-color: #0f172a; color: #ffffff; padding: 7px 8px; font-size: 10px; text-align: left; text-transform: uppercase; }
        .items-table td { padding: 8px; border-bottom: 1px solid #e2e8f0; font-size: 10px; }
        .items-table .text-right { text-align: right; }
        .totals-table { width: 45%; margin-left: auto; border-collapse: collapse; margin-bottom: 25px; }
        .totals-table td { padding: 5px 8px; font-size: 10px; border-bottom: 1px solid #e2e8f0; }
        .totals-table .grand-total { font-size: 12px; font-weight: bold; background-color: #f1f5f9; color: #0f172a; border-top: 2px solid #0f172a; }
        .bank-details { background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 4px; padding: 10px; font-size: 10px; width: 60%; margin-bottom: 20px; }
        .signature-box { text-align: right; margin-top: 25px; font-size: 10px; color: #475569; }
    </style>
</head>
<body>

    <table class="header-table">
        <tr>
            <td>
                <div class="invoice-title">TAX INVOICE</div>
                <div class="invoice-subtitle">ORIGINAL FOR RECIPIENT</div>
                <div style="margin-top: 6px; font-size: 10px;">
                    <strong>Invoice No:</strong> {{ $invoice->invoice_number }}<br>
                    <strong>Date of Issue:</strong> {{ $invoice->invoice_date ? $invoice->invoice_date->format('d M Y') : date('d M Y') }}<br>
                    <strong>Financial Year:</strong> {{ $invoice->financial_year }}
                </div>
            </td>
            <td class="company-info">
                <strong style="font-size: 14px; color: #0f172a;">BharatTravel Holidays Pvt Ltd</strong><br>
                Plot 45, Barakhamba Road, Connaught Place, New Delhi - 110001<br>
                State: Delhi (Code: 07) | Country: India<br>
                <strong>GSTIN:</strong> 07AAACB1234F1Z9<br>
                <strong>PAN:</strong> AAACB1234F<br>
                SAC Code: 998551 (Tour Operator Services)
            </td>
        </tr>
    </table>

    <table class="parties-table">
        <tr>
            <td style="padding-right: 15px;">
                <div class="box-title">Billed To (Customer)</div>
                <strong>{{ $invoice->customer ? $invoice->customer->name : 'Valued Guest' }}</strong><br>
                {{ $invoice->customer ? $invoice->customer->address : 'Address on File' }}<br>
                {{ $invoice->customer ? $invoice->customer->city : '' }}, {{ $invoice->customer ? $invoice->customer->state : '' }} {{ $invoice->customer ? $invoice->customer->pincode : '' }}<br>
                Phone: {{ $invoice->customer ? $invoice->customer->phone : 'N/A' }}<br>
                Email: {{ $invoice->customer ? $invoice->customer->email : 'N/A' }}<br>
                @if($invoice->customer && $invoice->customer->gstin)
                    <strong>Customer GSTIN:</strong> {{ $invoice->customer->gstin }}
                @endif
            </td>
            <td style="padding-left: 15px;">
                <div class="box-title">Booking Reference</div>
                <strong>Booking #:</strong> {{ $invoice->booking ? $invoice->booking->booking_number : 'Direct Invoice' }}<br>
                <strong>Service:</strong> {{ $invoice->booking && $invoice->booking->package ? $invoice->booking->package->title : 'Travel Reservation' }}<br>
                <strong>Travel Date:</strong> {{ $invoice->booking && $invoice->booking->departure_date ? $invoice->booking->departure_date->format('d M Y') : 'N/A' }}<br>
                <strong>Payment Status:</strong> {{ strtoupper($invoice->status) }}
            </td>
        </tr>
    </table>

    <table class="items-table">
        <thead>
            <tr>
                <th style="width: 5%;">#</th>
                <th style="width: 50%;">Description of Goods / Services</th>
                <th style="width: 15%;">SAC Code</th>
                <th style="width: 10%; text-align: center;">Qty</th>
                <th style="width: 20%;" class="text-right">Taxable Amount (INR)</th>
            </tr>
        </thead>
        <tbody>
            @if(!empty($invoice->items))
                @foreach($invoice->items as $idx => $item)
                    <tr>
                        <td>{{ $idx + 1 }}</td>
                        <td>
                            <strong>{{ $item['description'] ?? 'Tour Operator Services' }}</strong>
                        </td>
                        <td>998551</td>
                        <td style="text-align: center;">{{ $item['qty'] ?? 1 }}</td>
                        <td class="text-right">₹{{ number_format($item['amount'] ?? 0, 2) }}</td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td>1</td>
                    <td><strong>{{ $invoice->booking && $invoice->booking->package ? $invoice->booking->package->title : 'Holiday Tour Reservation' }}</strong></td>
                    <td>998551</td>
                    <td style="text-align: center;">1</td>
                    <td class="text-right">₹{{ number_format($invoice->subtotal, 2) }}</td>
                </tr>
            @endif
        </tbody>
    </table>

    <table class="totals-table">
        <tr>
            <td>Subtotal Taxable:</td>
            <td class="text-right">₹{{ number_format($invoice->subtotal, 2) }}</td>
        </tr>
        @if($invoice->discount_amount > 0)
        <tr>
            <td>Discount Applied:</td>
            <td class="text-right" style="color:#e11d48;">-₹{{ number_format($invoice->discount_amount, 2) }}</td>
        </tr>
        @endif
        @if($invoice->cgst_amount > 0)
        <tr>
            <td>CGST (2.5%):</td>
            <td class="text-right">₹{{ number_format($invoice->cgst_amount, 2) }}</td>
        </tr>
        <tr>
            <td>SGST (2.5%):</td>
            <td class="text-right">₹{{ number_format($invoice->sgst_amount, 2) }}</td>
        </tr>
        @else
        <tr>
            <td>GST ({{ $invoice->tax_rate }}%):</td>
            <td class="text-right">₹{{ number_format($invoice->subtotal * ($invoice->tax_rate / 100), 2) }}</td>
        </tr>
        @endif
        @if($invoice->tcs_amount > 0)
        <tr>
            <td>TCS u/s 206C(1G) (5%):</td>
            <td class="text-right">₹{{ number_format($invoice->tcs_amount, 2) }}</td>
        </tr>
        @endif
        <tr class="grand-total">
            <td>Grand Total (INR):</td>
            <td class="text-right">₹{{ number_format($invoice->total_amount, 2) }}</td>
        </tr>
        <tr>
            <td>Amount Received:</td>
            <td class="text-right" style="color: #059669; font-weight: bold;">₹{{ number_format($invoice->paid_amount, 2) }}</td>
        </tr>
        <tr>
            <td><strong>Balance Payable:</strong></td>
            <td class="text-right" style="color: #e11d48; font-weight: bold;">₹{{ number_format($invoice->balance_amount, 2) }}</td>
        </tr>
    </table>

    <div class="bank-details">
        <strong>Bank Electronic Remittance Coordinates:</strong><br>
        Beneficiary Name: <strong>BharatTravel Holidays Pvt Ltd</strong><br>
        Bank Name: HDFC Bank Ltd | Branch: Connaught Place, New Delhi<br>
        Current Account Number: 50200098765432 | IFSC Code: HDFC0000045<br>
        UPI ID: bharattravel@hdfcbank
    </div>

    <div class="signature-box">
        For <strong>BharatTravel Holidays Pvt Ltd</strong><br><br><br>
        <strong>Authorized Signatory & Accounts Head</strong>
    </div>

</body>
</html>

