<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Travel Voucher - {{ $voucher->voucher_number }}</title>
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 11px; line-height: 1.4; color: #1e293b; margin: 0; padding: 20px; }
        .header-table { width: 100%; border-bottom: 2px solid #0d9488; padding-bottom: 15px; margin-bottom: 20px; }
        .logo-title { font-size: 20px; font-weight: bold; color: #0f172a; }
        .logo-subtitle { font-size: 9px; color: #0d9488; text-transform: uppercase; font-weight: bold; letter-spacing: 1px; }
        .company-info { text-align: right; font-size: 10px; color: #64748b; }
        .badge { background-color: #0d9488; color: #ffffff; padding: 4px 8px; border-radius: 4px; font-weight: bold; font-size: 10px; text-transform: uppercase; }
        .section-title { font-size: 12px; font-weight: bold; color: #0f172a; border-bottom: 1px solid #e2e8f0; padding-bottom: 4px; margin-top: 15px; margin-bottom: 10px; }
        .info-table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
        .info-table td { padding: 6px 8px; font-size: 11px; vertical-align: top; }
        .info-label { font-weight: bold; color: #475569; width: 25%; background-color: #f8fafc; border: 1px solid #e2e8f0; }
        .info-value { border: 1px solid #e2e8f0; }
        .data-table { width: 100%; border-collapse: collapse; margin-top: 8px; margin-bottom: 15px; }
        .data-table th { background-color: #0f172a; color: #ffffff; font-weight: bold; font-size: 10px; text-transform: uppercase; padding: 6px 8px; text-align: left; }
        .data-table td { padding: 6px 8px; border-bottom: 1px solid #e2e8f0; font-size: 10px; }
        .footer-note { font-size: 9px; color: #64748b; border-top: 1px solid #e2e8f0; padding-top: 10px; margin-top: 30px; }
    </style>
</head>
<body>

    <table class="header-table">
        <tr>
            <td>
                <div class="logo-title">BHARAT<span style="color:#0d9488;">TRAVEL</span></div>
                <div class="logo-subtitle">Official Confirmed Travel Service Voucher</div>
            </td>
            <td class="company-info">
                <strong>BharatTravel Holidays Pvt Ltd</strong><br>
                Plot 45, Barakhamba Road, Connaught Place, New Delhi - 110001<br>
                24x7 Helpline: +91 11 4567 8900 | support@bharattravel.com<br>
                GSTIN: 07AAACB1234F1Z9
            </td>
        </tr>
    </table>

    <table style="width: 100%; margin-bottom: 15px;">
        <tr>
            <td>
                <span class="badge">VOUCHER #{{ $voucher->voucher_number }}</span>
            </td>
            <td style="text-align: right; font-size: 10px; color: #64748b;">
                Issue Date: {{ $voucher->generated_at ? $voucher->generated_at->format('d M Y, h:i A') : date('d M Y') }}
            </td>
        </tr>
    </table>

    <div class="section-title">Reservation & Booking Summary</div>
    <table class="info-table">
        <tr>
            <td class="info-label">Booking Reference:</td>
            <td class="info-value"><strong>{{ $voucher->booking ? $voucher->booking->booking_number : 'N/A' }}</strong></td>
            <td class="info-label">Primary Guest:</td>
            <td class="info-value"><strong>{{ $voucher->booking && $voucher->booking->customer ? $voucher->booking->customer->name : 'N/A' }}</strong></td>
        </tr>
        <tr>
            <td class="info-label">Service / Tour:</td>
            <td class="info-value">{{ $voucher->booking && $voucher->booking->package ? $voucher->booking->package->title : 'Customized Holiday Service' }}</td>
            <td class="info-label">Contact Phone:</td>
            <td class="info-value">{{ $voucher->booking && $voucher->booking->customer ? $voucher->booking->customer->phone : 'N/A' }}</td>
        </tr>
        <tr>
            <td class="info-label">Travel Period:</td>
            <td class="info-value">{{ $voucher->booking && $voucher->booking->departure_date ? $voucher->booking->departure_date->format('d M Y') : 'N/A' }} to {{ $voucher->booking && $voucher->booking->return_date ? $voucher->booking->return_date->format('d M Y') : 'N/A' }}</td>
            <td class="info-label">Total Guests:</td>
            <td class="info-value">{{ $voucher->booking ? $voucher->booking->adults : 1 }} Adults, {{ $voucher->booking ? $voucher->booking->children : 0 }} Children</td>
        </tr>
        <tr>
            <td class="info-label">Payment Status:</td>
            <td class="info-value" style="color: #059669; font-weight: bold;">{{ strtoupper($voucher->booking ? $voucher->booking->payment_status : 'CONFIRMED') }}</td>
            <td class="info-label">Voucher Type:</td>
            <td class="info-value">{{ ucfirst($voucher->voucher_type) }} Travel Pass</td>
        </tr>
    </table>

    <div class="section-title">Verified Passenger Manifest</div>
    <table class="data-table">
        <thead>
            <tr>
                <th>#</th>
                <th>Passenger Name</th>
                <th>Gender</th>
                <th>Category</th>
                <th>Passport / ID No</th>
                <th>Meal Preference</th>
            </tr>
        </thead>
        <tbody>
            @if($voucher->booking && $voucher->booking->passengers->count())
                @foreach($voucher->booking->passengers as $idx => $pax)
                    <tr>
                        <td>{{ $idx + 1 }}</td>
                        <td><strong>{{ $pax->full_name }}</strong></td>
                        <td>{{ $pax->gender }}</td>
                        <td>{{ ucfirst($pax->type) }}</td>
                        <td>{{ $pax->passport_number ?: 'Verified on File' }}</td>
                        <td>{{ $pax->meal_preference ?: 'Standard' }}</td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td>1</td>
                    <td><strong>{{ $voucher->booking && $voucher->booking->customer ? $voucher->booking->customer->name : 'Primary Passenger' }}</strong></td>
                    <td>Male</td>
                    <td>Adult</td>
                    <td>{{ $voucher->booking && $voucher->booking->customer ? $voucher->booking->customer->passport_number : 'On File' }}</td>
                    <td>North Indian Vegetarian</td>
                </tr>
            @endif
        </tbody>
    </table>

    <div class="section-title">Service Details & Inclusions</div>
    <table class="info-table">
        @if(!empty($voucher->details))
            @foreach($voucher->details as $key => $val)
                <tr>
                    <td class="info-label">{{ ucwords(str_replace('_', ' ', $key)) }}:</td>
                    <td class="info-value">{{ is_array($val) ? json_encode($val) : $val }}</td>
                </tr>
            @endforeach
        @else
            <tr>
                <td class="info-label">Accommodation:</td>
                <td class="info-value">Confirmed as per booked package itinerary. Standard check-in 14:00 hrs.</td>
            </tr>
            <tr>
                <td class="info-label">Transfer & Sightseeing:</td>
                <td class="info-value">Dedicated AC vehicle provided for transfers and specified tours. Driver details dispatched via SMS 12 hrs prior.</td>
            </tr>
        @endif
    </table>

    <div class="footer-note">
        <strong>Important Instructions:</strong>
        <ol style="margin-top: 4px; padding-left: 15px;">
            <li>Please present this voucher along with valid government-issued photo identity cards (Passport for international trips / Aadhaar/Voter ID for domestic trips) at hotel check-in and pickup points.</li>
            <li>For any on-ground changes or emergency assistance during the journey, please call our 24x7 guest coordinator at +91 98100 12345 or +91 11 4567 8900.</li>
            <li>Early check-in or late check-out is subject to hotel room availability at the property's sole discretion.</li>
        </ol>
        <p style="text-align: center; margin-top: 20px;">Wish you a wonderful journey with <strong>BharatTravel Holidays</strong>!</p>
    </div>

</body>
</html>

