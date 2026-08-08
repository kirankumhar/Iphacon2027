<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>IPHACON Registration Acknowledgement Receipt - {{ $registration->registration_number }}</title>
    <style>
        @page {
            margin: 10mm 10mm 10mm 10mm;
        }

        body {
            font-family: 'DejaVu Sans', sans-serif;
            color: #1E293B;
            font-size: 11px;
            line-height: 1.4;
            background: #FFFFFF;
        }

        /* Container Border */
        .pdf-box {
            border: 2px solid #0288D1;
            padding: 16px 18px;
            border-radius: 6px;
            position: relative;
        }

        /* Top Header Band */
        .header-wrap {
            margin-bottom: 10px;
            border-bottom: 2px solid #0288D1;
            padding-bottom: 10px;
        }

        .logo-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 8px;
        }

        .logo-cell-left {
            width: 30%;
            text-align: left;
            vertical-align: middle;
        }

        .logo-cell-center {
            width: 40%;
            text-align: center;
            vertical-align: middle;
        }

        .logo-cell-right {
            width: 30%;
            text-align: right;
            vertical-align: middle;
        }

        .header-logo {
            max-height: 65px;
            width: auto;
        }

        .brand-block {
            text-align: center;
            margin-top: 4px;
        }

        .brand-title {
            font-size: 14px;
            font-weight: bold;
            color: #01579B;
            text-transform: uppercase;
            margin-bottom: 2px;
        }

        .brand-subtitle {
            font-size: 11.5px;
            font-weight: bold;
            color: #0288D1;
            margin-bottom: 2px;
        }

        .brand-meta {
            font-size: 9.5px;
            color: #475569;
        }

        /* Document Title Badge - Solid Color for Dompdf Compatibility */
        .doc-title-badge {
            text-align: center;
            background-color: #0288D1;
            color: #FFFFFF !important;
            font-weight: bold;
            font-size: 12px;
            padding: 7px 10px;
            border-radius: 4px;
            margin-bottom: 12px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            display: block;
            width: 100%;
        }

        /* Info Grid Table */
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 12px;
            background: #F8FAFC;
            border: 1px solid #CBD5E1;
        }

        .info-table td {
            padding: 6px 8px;
            vertical-align: top;
            border-bottom: 1px solid #E2E8F0;
            border-right: 1px solid #E2E8F0;
        }

        .info-table td:last-child {
            border-right: none;
        }

        .info-table tr:last-child td {
            border-bottom: none;
        }

        .info-label {
            font-size: 9px;
            color: #64748B;
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 2px;
        }

        .info-value {
            font-size: 10.5px;
            font-weight: bold;
            color: #0F172A;
        }

        /* Financial Breakdown Table */
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 12px;
        }

        .items-table th {
            background-color: #0288D1;
            color: #FFFFFF !important;
            font-weight: bold;
            font-size: 10px;
            text-transform: uppercase;
            padding: 7px 8px;
            border: 1px solid #0288D1;
        }

        .items-table td {
            padding: 7px 8px;
            border: 1px solid #CBD5E1;
            font-size: 10px;
        }

        .items-table tr:nth-child(even) {
            background-color: #F8FAFC;
        }

        .total-row td {
            background-color: #E0F2FE !important;
            color: #01579B;
            font-weight: bold;
            font-size: 11px;
            border-top: 2px solid #0288D1 !important;
        }

        /* Watermark */
        .watermark {
            position: absolute;
            top: 40%;
            left: 15%;
            opacity: 0.08;
            font-size: 70px;
            font-weight: bold;
            color: #0288D1;
            transform: rotate(-25deg);
            z-index: 0;
        }

        /* Footer */
        .footer-wrap {
            border-top: 1px solid #CBD5E1;
            padding-top: 8px;
            margin-top: 8px;
        }

        .footer-table {
            width: 100%;
            border-collapse: collapse;
        }

        .footer-left {
            font-size: 9px;
            color: #64748B;
            vertical-align: bottom;
        }

        .footer-right {
            text-align: right;
            vertical-align: bottom;
        }

        .stamp-box {
            display: inline-block;
            border: 1px dashed #0288D1;
            padding: 5px 12px;
            border-radius: 4px;
            background-color: #F0F9FF;
            text-align: center;
        }

        .stamp-title {
            font-size: 8.5px;
            font-weight: bold;
            color: #01579B;
            text-transform: uppercase;
        }

        .status-paid {
            color: #16A34A;
            font-weight: bold;
        }

        .status-pending {
            color: #D97706;
            font-weight: bold;
        }
    </style>
</head>

<body>

    <div class="pdf-box">

        {{-- Watermark --}}
        @if (($registration->latestPayment->payment_status ?? '') === 'Success' || $registration->status === 'Approved')
            <div class="watermark">PAID RECEIPT</div>
        @endif

        {{-- Top Header Wrap --}}
        <div class="header-wrap">
            <table class="logo-table">
                <tr>
                    {{-- Left Logo (IPHACON Main Logo) --}}
                    <td class="logo-cell-left">
                        @if (file_exists(public_path('assets/img/logo/logo.png')))
                            <img src="{{ public_path('assets/img/logo/logo.png') }}" class="header-logo" alt="IPHACON 2027">
                        @elseif (file_exists(public_path('shared/user/images/rimslogo.png')))
                            <img src="{{ public_path('shared/user/images/rimslogo.png') }}" class="header-logo" alt="RIMS Logo">
                        @endif
                    </td>

                    {{-- Center Logo (IPHA Emblem Logo) --}}
                    <td class="logo-cell-center">
                        @if (file_exists(public_path('assets/img/logo/ipha_logo.png')))
                            <img src="{{ public_path('assets/img/logo/ipha_logo.png') }}" class="header-logo" alt="IPHA Emblem">
                        @elseif (file_exists(public_path('shared/user/images/iphacon_logo.png')))
                            <img src="{{ public_path('shared/user/images/iphacon_logo.png') }}" class="header-logo" alt="IPHA Logo">
                        @endif
                    </td>

                    {{-- Right Logo (RIMS Ranchi Logo) --}}
                    <td class="logo-cell-right">
                        @if (file_exists(public_path('shared/user/images/rimslogo.png')))
                            <img src="{{ public_path('shared/user/images/rimslogo.png') }}" class="header-logo" alt="RIMS Logo">
                        @elseif (file_exists(public_path('assets/img/logo/iphacon_logo.png')))
                            <img src="{{ public_path('assets/img/logo/iphacon_logo.png') }}" class="header-logo" alt="IPHACON Logo">
                        @endif
                    </td>
                </tr>
            </table>

            {{-- Organization Meta --}}
            <div class="brand-block">
                <div class="brand-title">71<sup>st</sup> Annual National Conference of IPHA</div>
                <div class="brand-subtitle">IPHACON 2027 | RIMS, RANCHI, JHARKHAND</div>
                <div class="brand-meta">
                    <strong>Dates:</strong> 12<sup>th</sup> - 14<sup>th</sup> March 2027 &nbsp;|&nbsp; <strong>Venue:</strong> Rajendra Institute of Medical Sciences (RIMS), Ranchi &nbsp;|&nbsp; <strong>Web:</strong> www.iphacon2027.com
                </div>
            </div>
        </div>

        {{-- Document Title Badge - Fixed for Dompdf --}}
        <table style="width: 100%; border-collapse: collapse; margin-bottom: 12px;">
            <tr>
                <td style="background-color: #0288D1; color: #FFFFFF; font-weight: bold; font-size: 12px; padding: 8px; text-align: center; text-transform: uppercase;">
                    IPHACON Registration Acknowledgement Receipt
                </td>
            </tr>
        </table>

        {{-- Info Grid Table --}}
        <table class="info-table">
            <tr>
                <td style="width: 33%;">
                    <div class="info-label">Registration Number</div>
                    <div class="info-value" style="color: #0288D1;">{{ $registration->registration_number }}</div>
                </td>
                <td style="width: 33%;">
                    <div class="info-label">Receipt Issue Date</div>
                    <div class="info-value">{{ now()->format('d M Y') }}</div>
                </td>
                <td style="width: 34%;">
                    <div class="info-label">Payment Status</div>
                    <div class="info-value">
                        @if(($registration->latestPayment->payment_status ?? '') === 'Success' || $registration->status === 'Approved')
                            <span class="status-paid">SUCCESSFUL</span>
                        @else
                            <span class="status-pending">{{ strtoupper($registration->status ?? 'PENDING') }}</span>
                        @endif
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <div class="info-label">Delegate Name</div>
                    <div class="info-value">{{ $registration->user?->prefix }} {{ $registration->user?->full_name ?? ($registration->full_name ?? 'N/A') }}</div>
                </td>
                <td>
                    <div class="info-label">Email Address</div>
                    <div class="info-value">{{ $registration->user?->email ?? 'N/A' }}</div>
                </td>
                <td>
                    <div class="info-label">Mobile Number</div>
                    <div class="info-value">{{ $registration->user?->mobile_country_code }} {{ $registration->user?->mobile_number ?? 'N/A' }}</div>
                </td>
            </tr>
            <tr>
                <td>
                    <div class="info-label">Delegate Category</div>
                    <div class="info-value">{{ $registration->delegate_type }} - {{ $registration->delegateCategory->category_name ?? 'N/A' }}</div>
                </td>
                <td>
                    <div class="info-label">Country & State</div>
                    <div class="info-value">{{ $registration->country->country_name ?? 'India' }}, {{ $registration->state->state_name ?? $registration->other_state ?? 'N/A' }}</div>
                </td>
                <td>
                    <div class="info-label">Transaction ID / Reference</div>
                    <div class="info-value">{{ $registration->latestPayment->transaction_id ?? 'N/A' }}</div>
                </td>
            </tr>
        </table>

        {{-- Financial Items Table --}}
        <table class="items-table">
            <thead>
                <tr>
                    <th style="width: 55%; text-align: left;">Fee Description</th>
                    <th style="width: 25%; text-align: right;">Amount</th>
                    <th style="width: 20%; text-align: center;">Currency</th>
                </tr>
            </thead>
            <tbody>
                @if ($registration->delegate_type === 'International')
                    <tr>
                        <td>Delegate Category Registration Fee</td>
                        <td style="text-align: right;">${{ number_format($registration->delegate_fee ?: 175, 2) }}</td>
                        <td style="text-align: center;">USD</td>
                    </tr>
                    <tr class="total-row">
                        <td><strong>TOTAL AMOUNT PAID</strong></td>
                        <td style="text-align: right;"><strong>${{ number_format($registration->total_amount ?: 175, 2) }}</strong></td>
                        <td style="text-align: center;"><strong>USD</strong></td>
                    </tr>
                @else
                    @php
                        $catFee = $registration->delegateCategory ? (float)$registration->delegateCategory->indian_fee : 0;
                        $delFee = $registration->delegate_fee ?: round($catFee / 1.18, 2);
                        $gstAmt = $registration->gst_amount ?: round($catFee - $delFee, 2);
                        $cmeFee = $registration->cme_fee ?: ($registration->participate_in_cme ? 2000 : 0);
                        $accFee = $registration->accompanying_fee ?: (($registration->accompanying_persons ?? 0) * 4000);
                        $totalAmt = $registration->total_amount ?: ($catFee + $cmeFee + $accFee);
                    @endphp
                    <tr>
                        <td>Delegate Registration Fee (Excl. GST)</td>
                        <td style="text-align: right;">&#8377;{{ number_format($delFee, 2) }}</td>
                        <td style="text-align: center;">INR</td>
                    </tr>
                    @if ($registration->participate_in_cme)
                        <tr>
                            <td>CME / Workshop Participation Fee</td>
                            <td style="text-align: right;">&#8377;{{ number_format($cmeFee, 2) }}</td>
                            <td style="text-align: center;">INR</td>
                        </tr>
                    @endif
                    @if (($registration->accompanying_persons ?? 0) > 0)
                        <tr>
                            <td>Accompanying Persons Fee ({{ $registration->accompanying_persons }} Person(s))</td>
                            <td style="text-align: right;">&#8377;{{ number_format($accFee, 2) }}</td>
                            <td style="text-align: center;">INR</td>
                        </tr>
                    @endif
                    <tr>
                        <td>GST Amount (18%)</td>
                        <td style="text-align: right;">&#8377;{{ number_format($gstAmt, 2) }}</td>
                        <td style="text-align: center;">INR</td>
                    </tr>
                    <tr class="total-row">
                        <td><strong>TOTAL AMOUNT PAID (INCL. GST)</strong></td>
                        <td style="text-align: right;"><strong>&#8377;{{ number_format($totalAmt, 2) }}</strong></td>
                        <td style="text-align: center;"><strong>INR</strong></td>
                    </tr>
                @endif
            </tbody>
        </table>

        {{-- Footer Section --}}
        <div class="footer-wrap">
            <table class="footer-table">
                <tr>
                    <td class="footer-left">
                        <strong>IPHACON 2027 Organizing Committee</strong><br>
                        Department of Community Medicine, RIMS, Ranchi, Jharkhand<br>
                        Email: info@iphacon2027.com &nbsp;|&nbsp; Web: www.iphacon2027.com<br>
                        <span style="font-size: 8px; color: #94A3B8; margin-top: 3px; display: block;">* Computer-generated official receipt. No physical signature required.</span>
                    </td>
                    <td class="footer-right">
                        <div class="stamp-box">
                            <div class="stamp-title">IPHACON 2027 VERIFIED</div>
                            <div style="font-size: 8.5px; color: #16A34A; font-weight: bold; margin-top: 2px;">OFFICIAL RECEIPT</div>
                        </div>
                    </td>
                </tr>
            </table>
        </div>

    </div>

</body>

</html>
