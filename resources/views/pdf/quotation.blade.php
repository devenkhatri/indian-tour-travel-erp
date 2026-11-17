<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Travel Proposal - {{ $quotation->quotation_number }}</title>
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 11px; line-height: 1.4; color: #1e293b; margin: 0; padding: 20px; }
        .header-table { width: 100%; border-bottom: 2px solid #0d9488; padding-bottom: 15px; margin-bottom: 20px; }
        .logo-title { font-size: 20px; font-weight: bold; color: #0f172a; }
        .logo-subtitle { font-size: 9px; color: #0d9488; text-transform: uppercase; font-weight: bold; letter-spacing: 1px; }
        .company-info { text-align: right; font-size: 10px; color: #64748b; }
        .badge { background-color: #0f172a; color: #ffffff; padding: 4px 8px; border-radius: 4px; font-weight: bold; font-size: 10px; text-transform: uppercase; }
        .section-title { font-size: 12px; font-weight: bold; color: #0f172a; border-bottom: 1px solid #e2e8f0; padding-bottom: 4px; margin-top: 15px; margin-bottom: 10px; }
        .info-table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
        .info-table td { padding: 6px 8px; font-size: 11px; vertical-align: top; border: 1px solid #e2e8f0; }
        .info-label { font-weight: bold; color: #475569; width: 25%; background-color: #f8fafc; }
        .pricing-box { background-color: #f0fdfa; border: 1px solid #ccfbf1; border-radius: 6px; padding: 12px; margin-top: 15px; }
    </style>
</head>
<body>

    <table class="header-table">
        <tr>
            <td>
                <div class="logo-title">BHARAT<span style="color:#0d9488;">TRAVEL</span></div>
                <div class="logo-subtitle">Tailored Holiday Proposal & Price Quotation</div>
            </td>
            <td class="company-info">
                <strong>BharatTravel Holidays Pvt Ltd</strong><br>
                Plot 45, Barakhamba Road, Connaught Place, New Delhi - 110001<br>
                Direct: +91 11 4567 8900 | quotes@bharattravel.com
            </td>
        </tr>
    </table>

    <table style="width: 100%; margin-bottom: 15px;">
        <tr>
            <td>
                <span class="badge">QUOTATION #{{ $quotation->quotation_number }}</span>
            </td>
            <td style="text-align: right; font-size: 10px; color: #64748b;">
                Valid Until: {{ $quotation->valid_until ? $quotation->valid_until->format('d M Y') : date('d M Y', strtotime('+14 days')) }}
            </td>
        </tr>
    </table>

    <div class="section-title">Client & Trip Specifications</div>
    <table class="info-table">
        <tr>
            <td class="info-label">Customer Name:</td>
            <td><strong>{{ $quotation->customer ? $quotation->customer->name : 'Valued Client' }}</strong></td>
            <td class="info-label">Destination:</td>
            <td><strong>{{ $quotation->destination }}</strong></td>
        </tr>
        <tr>
            <td class="info-label">Travel Window:</td>
            <td>{{ $quotation->start_date ? $quotation->start_date->format('d M Y') : 'Flexible' }} to {{ $quotation->end_date ? $quotation->end_date->format('d M Y') : 'Flexible' }}</td>
            <td class="info-label">Guest Count:</td>
            <td>{{ $quotation->adults }} Adults, {{ $quotation->children }} Children</td>
        </tr>
        <tr>
            <td class="info-label">Quotation Title:</td>
            <td colspan="3"><strong>{{ $quotation->title }}</strong></td>
        </tr>
    </table>

    @if(!empty($quotation->itinerary_details))
    <div class="section-title">Proposed Day-Wise Itinerary</div>
    <div style="margin-bottom: 15px;">
        @foreach($quotation->itinerary_details as $idx => $day)
            <div style="margin-bottom: 6px; padding: 6px 8px; background-color: #f8fafc; border-left: 3px solid #0d9488;">
                <strong>Day {{ $idx + 1 }}:</strong> {{ $day }}
            </div>
        @endforeach
    </div>
    @endif

    <div class="section-title">Inclusions & Special Additions</div>
    @if(!empty($quotation->inclusions))
        <ul style="margin: 0; padding-left: 20px;">
            @foreach($quotation->inclusions as $inc)
                <li style="margin-bottom: 4px;">{{ $inc }}</li>
            @endforeach
        </ul>
    @else
        <p>Accommodation in 4-star properties, daily breakfast and dinners, private dedicated AC cab for transfers and sightseeing, toll and driver allowances included.</p>
    @endif

    <div class="section-title">Price Breakdown</div>
    <div class="pricing-box">
        <table style="width: 100%;">
            <tr>
                <td style="font-size: 11px;">Package Base Price:</td>
                <td style="text-align: right; font-size: 11px;">₹{{ number_format($quotation->subtotal, 2) }}</td>
            </tr>
            @if($quotation->discount_amount > 0)
            <tr>
                <td style="font-size: 11px; color:#e11d48;">Special Promotional Discount:</td>
                <td style="text-align: right; font-size: 11px; color:#e11d48;">-₹{{ number_format($quotation->discount_amount, 2) }}</td>
            </tr>
            @endif
            <tr>
                <td style="font-size: 11px;">GST ({{ $quotation->tax_rate }}%):</td>
                <td style="text-align: right; font-size: 11px;">₹{{ number_format($quotation->tax_amount, 2) }}</td>
            </tr>
            <tr>
                <td style="font-size: 14px; font-weight: bold; border-top: 1px solid #0d9488; padding-top: 6px;">Total Quotation Price (INR):</td>
                <td style="text-align: right; font-size: 14px; font-weight: bold; border-top: 1px solid #0d9488; padding-top: 6px; color: #0d9488;">
                    ₹{{ number_format($quotation->total_amount, 2) }}
                </td>
            </tr>
        </table>
    </div>

    <div style="font-size: 9px; color: #64748b; margin-top: 30px; border-top: 1px solid #e2e8f0; padding-top: 10px;">
        To accept this quotation and confirm your booking with a 25% advance, please contact your travel specialist or reply via email. Prices and room allocations remain subject to availability upon actual confirmation.
    </div>

</body>
</html>

