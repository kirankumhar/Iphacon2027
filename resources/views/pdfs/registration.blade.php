<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>IPHACON Registration Acknowledgement Receipt -
        {{ $registration->registration_number ?? $registration->acknowledgement_id }}</title>
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
            margin-bottom: 10px;
            background: #F8FAFC;
            border: 1px solid #CBD5E1;
        }

        .info-table td {
            padding: 4.5px 6.5px;
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
            font-size: 8px;
            color: #64748B;
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 1.5px;
        }

        .info-value {
            font-size: 9.5px;
            font-weight: bold;
            color: #0F172A;
        }

        /* Financial Breakdown Table */
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }

        .items-table th {
            background-color: #0288D1;
            color: #FFFFFF !important;
            font-weight: bold;
            font-size: 9.5px;
            text-transform: uppercase;
            padding: 6px 7px;
            border: 1px solid #0288D1;
        }

        .items-table td {
            padding: 5.5px 7px;
            border: 1px solid #CBD5E1;
            font-size: 9.5px;
        }

        .items-table tr:nth-child(even) {
            background-color: #F8FAFC;
        }

        .total-row td {
            background-color: #E0F2FE !important;
            color: #01579B;
            font-weight: bold;
            font-size: 10.5px;
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
            padding-top: 6px;
            margin-top: 6px;
        }

        .footer-table {
            width: 100%;
            border-collapse: collapse;
        }

        .footer-left {
            font-size: 8.5px;
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
            padding: 4px 10px;
            border-radius: 4px;
            background-color: #F0F9FF;
            text-align: center;
        }

        .stamp-title {
            font-size: 8px;
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

    @php
        $pay = $payment ?? ($registration->conferencePayment ?? $registration->latestPayment);

        // Auto-detect receiptType if not explicitly passed
        if (empty($receiptType)) {
            if (isset($receiptTitle) && (str_contains(strtolower($receiptTitle), 'workshop') || str_contains(strtolower($receiptTitle), 'cme'))) {
                $receiptType = 'cme';
            } elseif ($pay && (float)($pay->cme_fee ?? 0) > 0 && (float)($pay->delegate_category_fee ?? 0) > 0) {
                $receiptType = 'consolidated';
            } elseif ($pay && (float)($pay->cme_fee ?? 0) > 0 && (float)($pay->delegate_category_fee ?? 0) == 0) {
                $receiptType = 'cme';
            } else {
                $receiptType = 'conference';
            }
        }

        if ($receiptType === 'cme') {
            $themeColor = '#0F766E';
            $themeDark = '#115E59';
            $themeLight = '#CCFBF1';
            $themeBorder = '#99F6E4';
            $watermarkText = 'CME WORKSHOP PAID';
            $badgeText = 'OFFICIAL RECEIPT: PRE-CONFERENCE WORKSHOP ONLY';
            $receiptTitle = $receiptTitle ?? 'IPHACON 2027 PRE-CONFERENCE WORKSHOP (CME) RECEIPT';
            $stampTitle = 'IPHACON 2027 CME VERIFIED';
        } elseif ($receiptType === 'consolidated') {
            $themeColor = '#1E3A8A';
            $themeDark = '#1E40AF';
            $themeLight = '#EEF2FF';
            $themeBorder = '#C7D2FE';
            $watermarkText = 'CONSOLIDATED PAID';
            $badgeText = 'OFFICIAL RECEIPT: MAIN CONFERENCE + PRE-CONFERENCE WORKSHOP';
            $receiptTitle = $receiptTitle ?? 'IPHACON 2027 CONSOLIDATED CONFERENCE & WORKSHOP RECEIPT';
            $stampTitle = 'IPHACON 2027 VERIFIED';
        } else {
            $themeColor = '#0288D1';
            $themeDark = '#01579B';
            $themeLight = '#E0F2FE';
            $themeBorder = '#BAE6FD';
            $watermarkText = 'CONFERENCE PAID';
            $badgeText = 'OFFICIAL RECEIPT: MAIN CONFERENCE REGISTRATION';
            $receiptTitle = $receiptTitle ?? 'IPHACON 2027 MAIN CONFERENCE REGISTRATION RECEIPT';
            $stampTitle = 'IPHACON 2027 VERIFIED';
        }

        $abstractSub = $registration->abstractSubmission 
            ?? ($registration->user?->abstractSubmission ?? $registration->user?->abstractSubmissions?->first());
        $dispDesignation = $registration->designation === 'Other'
            ? ($registration->other_designation ?: 'Other')
            : ($registration->designation ?? ($registration->user?->designation ?? 'N/A'));
        $dispInstitute = $registration->institution 
            ?? ($registration->user?->institution 
                ?? ($abstractSub?->presenting_author_institution 
                    ?? ($registration->user?->abstractSubmissions?->first()?->presenting_author_institution ?? 'N/A')));

        $dispTxnId = $pay?->transaction_id ?? ($pay?->gateway_transaction_id ?? 'N/A');
        $dispPayMode = 'N/A';
        if (!empty($pay?->payment_method)) {
            $dispPayMode = match ($pay->payment_method) {
                'SBI_ePay' => 'Online (SBI ePay)',
                'QR_Code' => 'UPI / QR Code',
                default => str_replace('_', ' ', $pay->payment_method),
            };
        } elseif (!empty($pay?->gateway_transaction_id)) {
            $dispPayMode = 'Online (SBI ePay)';
        } elseif (!empty($pay?->payment_receipt_path)) {
            $dispPayMode = 'UPI / QR Code';
        } elseif ($registration->status === 'Approved') {
            $dispPayMode = 'Online / Verified';
        }

        $preConfTopic = $registration->pre_conference_topic 
            ?: ($registration->cmeApplication?->pre_conference_topic ?? 'General CME Workshop');

        // Financial fee calculations based on receiptType
        if ($receiptType === 'cme') {
            $delFee = 0.00;
            $accFee = 0.00;
            $cmeFee = (float)($pay && (float)($pay->cme_fee ?? 0) > 0 
                ? $pay->cme_fee 
                : ($registration->cmeApplication?->fee_amount ?: 2000.00));
            $gstAmt = (float)($pay && (float)($pay->gst_amount ?? 0) > 0 
                ? $pay->gst_amount 
                : ($registration->cmeApplication?->gst_amount ?: round($cmeFee * 0.18, 2)));
            $totalAmt = (float)($pay && (float)($pay->total_amount ?? 0) > 0 
                ? $pay->total_amount 
                : ($registration->cmeApplication?->total_amount ?: round($cmeFee + $gstAmt, 2)));
        } elseif ($receiptType === 'consolidated') {
            if ($registration->delegate_type === 'International') {
                $delFee = (float)($pay ? ($pay->delegate_category_fee ?? 0) : ($registration->delegate_fee ?: 45000));
                $cmeFee = (float)($pay ? ($pay->cme_fee ?? 0) : ($registration->cme_fee ?: 2000));
                $accFee = (float)($pay ? ($pay->accompanying_persons_fee ?? 0) : ($registration->accompanying_fee ?: 0));
                $gstAmt = (float)($pay ? ($pay->gst_amount ?? 0) : round(($delFee + $cmeFee + $accFee) * 0.18, 2));
                $totalAmt = (float)($pay ? ($pay->total_amount ?? 0) : round($delFee + $cmeFee + $accFee + $gstAmt, 2));
            } else {
                $delFee = (float)($pay ? ($pay->delegate_category_fee ?? 0) : ($registration->delegate_fee ?: ($registration->delegateCategory?->indian_fee ?? 0)));
                $cmeFee = (float)($pay ? ($pay->cme_fee ?? 0) : ($registration->cme_fee ?: 2000));
                $accFee = (float)($pay ? ($pay->accompanying_persons_fee ?? 0) : ($registration->accompanying_fee ?: 0));
                $gstAmt = (float)($pay ? ($pay->gst_amount ?? 0) : round(($delFee + $cmeFee + $accFee) * 0.18, 2));
                $totalAmt = (float)($pay ? ($pay->total_amount ?? 0) : round($delFee + $cmeFee + $accFee + $gstAmt, 2));
            }
        } else {
            $cmeFee = 0.00;
            if ($registration->delegate_type === 'International') {
                $delFee = (float)($pay ? ($pay->delegate_category_fee ?? 0) : ($registration->delegate_fee ?: 45000));
                $accFee = (float)($pay ? ($pay->accompanying_persons_fee ?? 0) : ($registration->accompanying_fee ?: 0));
                $gstAmt = (float)($pay ? ($pay->gst_amount ?? 0) : round(($delFee + $accFee) * 0.18, 2));
                $totalAmt = (float)($pay ? ($pay->total_amount ?? 0) : round($delFee + $accFee + $gstAmt, 2));
            } else {
                $delFee = (float)($pay ? ($pay->delegate_category_fee ?? 0) : ($registration->delegate_fee ?: ($registration->delegateCategory?->indian_fee ?? 0)));
                $accFee = (float)($pay ? ($pay->accompanying_persons_fee ?? 0) : ($registration->accompanying_fee ?: 0));
                $gstAmt = (float)($pay ? ($pay->gst_amount ?? 0) : round(($delFee + $accFee) * 0.18, 2));
                $totalAmt = (float)($pay ? ($pay->total_amount ?? 0) : round($delFee + $accFee + $gstAmt, 2));
            }
        }

        $dispTotalAmount = '₹' . number_format($totalAmt, 2);
        $regDate = $registration->submitted_at
            ? \Carbon\Carbon::parse($registration->submitted_at)->format('d M Y')
            : ($registration->created_at ? \Carbon\Carbon::parse($registration->created_at)->format('d M Y') : 'N/A');
        $gender = $registration->user?->gender ?? 'N/A';
        $dietPref = $registration->dietary_preference ?? 'N/A';
        $abstractStatusText = $abstractSub
            ? 'Yes' . ($abstractSub->acknowledgement_id ? ' (' . $abstractSub->acknowledgement_id . ')' : '')
            : 'No';
    @endphp

    <div class="pdf-box" style="border: 2px solid {{ $themeColor }};">

        {{-- Watermark --}}
        @if (($pay->payment_status ?? '') === 'Success' || ($pay->payment_status ?? '') === 'PAID' || $registration->status === 'Approved')
            <div class="watermark" style="color: {{ $themeColor }};">{{ $watermarkText }}</div>
        @endif

        {{-- Top Header Wrap --}}
        <div class="header-wrap" style="border-bottom: 2px solid {{ $themeColor }};">
            <table class="logo-table">
                <tr>
                    {{-- Left Logo (IPHACON Main Logo) --}}
                    <td class="logo-cell-left">
                        @if (file_exists(public_path('assets/img/logo/logo.png')))
                            <img src="{{ public_path('assets/img/logo/logo.png') }}" class="header-logo"
                                alt="IPHACON 2027">
                        @elseif (file_exists(public_path('shared/user/images/rimslogo.png')))
                            <img src="{{ public_path('shared/user/images/rimslogo.png') }}" class="header-logo"
                                alt="RIMS Logo">
                        @endif
                    </td>

                    {{-- Center Logo (IPHA Emblem Logo) --}}
                    <td class="logo-cell-center">
                        @if (file_exists(public_path('assets/img/logo/ipha_logo.png')))
                            <img src="{{ public_path('assets/img/logo/ipha_logo.png') }}" class="header-logo"
                                alt="IPHA Emblem">
                        @elseif (file_exists(public_path('shared/user/images/iphacon_logo.png')))
                            <img src="{{ public_path('shared/user/images/iphacon_logo.png') }}" class="header-logo"
                                alt="IPHA Logo">
                        @endif
                    </td>

                    {{-- Right Logo (RIMS Ranchi Logo) --}}
                    <td class="logo-cell-right">
                        @if (file_exists(public_path('shared/user/images/rimslogo.png')))
                            <img src="{{ public_path('shared/user/images/rimslogo.png') }}" class="header-logo"
                                alt="RIMS Logo">
                        @elseif (file_exists(public_path('assets/img/logo/iphacon_logo.png')))
                            <img src="{{ public_path('assets/img/logo/iphacon_logo.png') }}" class="header-logo"
                                alt="IPHACON Logo">
                        @endif
                    </td>
                </tr>
            </table>

            {{-- Organization Meta --}}
            <div class="brand-block">
                <div class="brand-title">71<sup>st</sup> Annual National Conference of IPHA</div>
                <div class="brand-subtitle" style="color: {{ $themeColor }};">IPHACON 2027 | RIMS, RANCHI, JHARKHAND</div>
                <div class="brand-meta">
                    <strong>Dates:</strong> 12<sup>th</sup> - 14<sup>th</sup> March 2027 &nbsp;|&nbsp;
                    <strong>Venue:</strong> Rajendra Institute of Medical Sciences (RIMS), Ranchi &nbsp;|&nbsp;
                    <strong>Web:</strong> www.iphacon2027.com
                </div>
            </div>
        </div>

        {{-- Document Title Badge --}}
        <table style="width: 100%; border-collapse: collapse; margin-bottom: 10px;">
            <tr>
                <td style="background-color: {{ $themeColor }}; color: #FFFFFF; font-weight: bold; font-size: 11.5px; padding: 7px 8px; text-align: center; text-transform: uppercase; letter-spacing: 0.5px;">
                    <div>{{ $receiptTitle }}</div>
                    <div style="font-size: 8px; font-weight: bold; color: #FFFFFF; background-color: {{ $themeDark }}; padding: 2px 7px; border-radius: 3px; display: inline-block; margin-top: 3px; letter-spacing: 0.6px;">
                        [{{ $badgeText }}]
                    </div>
                </td>
            </tr>
        </table>

        {{-- Info Grid Table --}}
        <table class="info-table">
            <tr>
                <td style="width: 25%;">
                    <div class="info-label">IPHACON Regn. No.</div>
                    <div class="info-value" style="color: {{ $themeColor }}; font-family: monospace; font-size: 10.5px;">
                        {{ $registration->registration_number ?? ($registration->status === 'Approved' ? 'N/A' : 'Pending Approval') }}
                    </div>
                </td>
                <td style="width: 25%;">
                    <div class="info-label">IPHA Membership No.</div>
                    <div class="info-value" style="color: #01579B; font-family: monospace; font-size: 10.5px;">
                        {{ $registration->membership_no ?: ($registration->ismm_membership_no ?: 'N/A') }}
                    </div>
                </td>
                <td style="width: 25%;">
                    <div class="info-label">
                        {{ $receiptType === 'cme' ? 'Date of Payment' : 'Date of Registration' }}
                    </div>
                    <div class="info-value">{{ $regDate }}</div>
                </td>
                <td style="width: 25%;">
                    <div class="info-label">Payment Status</div>
                    <div class="info-value">
                        @if (
                            ($pay?->payment_status ?? '') === 'Success' ||
                                ($pay?->payment_status ?? '') === 'PAID' ||
                                $registration->status === 'Approved')
                            <span class="status-paid">PAID</span>
                        @else
                            <span class="status-pending">{{ strtoupper($registration->status ?? 'PENDING') }}</span>
                        @endif
                    </div>
                </td>
            </tr>
            <tr>
                <td style="width: 25%;">
                    <div class="info-label">Delegate Name</div>
                    <div class="info-value">{{ $registration->user?->prefix }}
                        {{ $registration->user?->full_name ?? ($registration->full_name ?? 'N/A') }}</div>
                </td>
                <td style="width: 25%;">
                    <div class="info-label">Gender</div>
                    <div class="info-value">{{ $gender }}</div>
                </td>
                <td style="width: 25%;">
                    <div class="info-label">Designation</div>
                    <div class="info-value">{{ $dispDesignation }}</div>
                </td>
                <td style="width: 25%;">
                    <div class="info-label">Mobile Number</div>
                    <div class="info-value">{{ $registration->user?->mobile_country_code }}
                        {{ $registration->user?->mobile_number ?? 'N/A' }}</div>
                </td>
            </tr>
            <tr>
                <td style="width: 25%;">
                    <div class="info-label">Institute / Affiliation</div>
                    <div class="info-value">{{ $dispInstitute }}</div>
                </td>
                <td style="width: 25%;">
                    <div class="info-label">Email Address</div>
                    <div class="info-value">{{ $registration->user?->email ?? 'N/A' }}</div>
                </td>
                <td style="width: 25%;">
                    <div class="info-label">Delegate Category</div>
                    <div class="info-value">{{ $registration->delegate_type }} -
                        {{ $registration->delegateCategory->category_name ?? 'N/A' }}</div>
                </td>
                <td style="width: 25%;">
                    <div class="info-label">Country & State</div>
                    <div class="info-value">{{ $registration->country->country_name ?? 'India' }},
                        {{ $registration->state->state_name ?? ($registration->other_state ?? 'N/A') }}</div>
                </td>
            </tr>
            <tr>
                <td style="width: 25%;">
                    <div class="info-label">Total Amount Paid</div>
                    <div class="info-value" style="color: #16A34A; font-weight: bold; font-size: 11px;">{{ $dispTotalAmount }}</div>
                </td>
                <td style="width: 25%;">
                    <div class="info-label">Mode of Payment</div>
                    <div class="info-value" style="color: {{ $themeColor }};">{{ $dispPayMode }}</div>
                </td>
                <td style="width: 25%;">
                    <div class="info-label">Transaction ID</div>
                    <div class="info-value" style="font-family: monospace; font-size: 9.5px;">{{ $dispTxnId }}</div>
                </td>
                <td style="width: 25%;">
                    <div class="info-label">Diet Preference</div>
                    <div class="info-value">{{ $dietPref }}</div>
                </td>
            </tr>
            <tr>
                <td colspan="3" style="width: 75%;">
                    @if($receiptType === 'cme')
                        <div class="info-label" style="color: {{ $themeColor }}; font-weight: bold;">Enrolled Workshop Topic</div>
                        <div class="info-value" style="color: {{ $themeColor }}; font-size: 10px;">
                            {{ $preConfTopic }}
                        </div>
                        <div style="font-size: 7.5px; color: #64748B; margin-top: 2px;">
                            Conference Regn No: <strong style="font-family: monospace;">{{ $registration->registration_number ?? ($registration->acknowledgement_id ?? 'N/A') }}</strong> (Conference registration billed separately)
                        </div>
                    @elseif($receiptType === 'consolidated')
                        <div class="info-label">Pre-Conference Workshop (Bundled)</div>
                        <div class="info-value">
                            Yes &nbsp;&mdash;&nbsp; <span style="font-weight: normal; color: #475569;">{{ $preConfTopic }}</span>
                        </div>
                    @else
                        <div class="info-label">Pre-Conference Workshop</div>
                        <div class="info-value">
                            @if($registration->participate_in_cme || $registration->cmeApplication)
                                Yes &nbsp;&mdash;&nbsp; <span style="font-weight: normal; color: #475569;">{{ $preConfTopic }}</span>
                                <span style="font-size: 7.5px; color: #64748B; display: block; margin-top: 1px;">(Separate enrollment &mdash; billed under CME Workshop Receipt)</span>
                            @else
                                No
                            @endif
                        </div>
                    @endif
                </td>
                <td style="width: 25%;">
                    <div class="info-label">Abstract Submission</div>
                    <div class="info-value">{{ $abstractStatusText }}</div>
                </td>
            </tr>
        </table>

        {{-- Financial Items Table --}}
        <table class="items-table">
            <thead>
                <tr>
                    <th style="width: 55%; text-align: left; background-color: {{ $themeColor }};">
                        @if($receiptType === 'cme')
                            Pre-Conference Workshop Fee Breakdown
                        @elseif($receiptType === 'consolidated')
                            Consolidated Conference &amp; Workshop Fee Breakdown
                        @else
                            Main Conference Registration Fee Breakdown
                        @endif
                    </th>
                    <th style="width: 25%; text-align: right; background-color: {{ $themeColor }};">Amount</th>
                    <th style="width: 20%; text-align: center; background-color: {{ $themeColor }};">Currency</th>
                </tr>
            </thead>
            <tbody>
                @if ($receiptType === 'cme')
                    <tr>
                        <td>
                            <strong>Pre-Conference Workshop Participation Fee (Excl. GST)</strong>
                            <div style="font-size: 8px; color: #64748B; margin-top: 2px;">
                                Enrolled Workshop: {{ $preConfTopic }}
                            </div>
                        </td>
                        <td style="text-align: right;">&#8377;{{ number_format($cmeFee, 2) }}</td>
                        <td style="text-align: center;">INR</td>
                    </tr>
                    <tr>
                        <td>GST Amount (18%)</td>
                        <td style="text-align: right;">&#8377;{{ number_format($gstAmt, 2) }}</td>
                        <td style="text-align: center;">INR</td>
                    </tr>
                    <tr class="total-row" style="background-color: {{ $themeLight }} !important; color: {{ $themeDark }}; border-top: 2px solid {{ $themeColor }} !important;">
                        <td><strong>TOTAL WORKSHOP FEE PAID (INCL. GST)</strong></td>
                        <td style="text-align: right;"><strong>&#8377;{{ number_format($totalAmt, 2) }}</strong></td>
                        <td style="text-align: center;"><strong>INR</strong></td>
                    </tr>
                @elseif ($receiptType === 'consolidated')
                    <tr>
                        <td>Delegate Registration Fee (Excl. GST) &mdash; {{ $registration->delegateCategory?->category_name ?? 'Indian Delegate' }}</td>
                        <td style="text-align: right;">&#8377;{{ number_format($delFee, 2) }}</td>
                        <td style="text-align: center;">INR</td>
                    </tr>
                    <tr>
                        <td>
                            Pre-Conference Workshop Participation Fee (Excl. GST)
                            <div style="font-size: 8px; color: #64748B; margin-top: 2px;">
                                Topic: {{ $preConfTopic }}
                            </div>
                        </td>
                        <td style="text-align: right;">&#8377;{{ number_format($cmeFee, 2) }}</td>
                        <td style="text-align: center;">INR</td>
                    </tr>
                    @if ($accFee > 0)
                        <tr>
                            <td>Accompanying Persons Fee ({{ $registration->accompanying_persons ?? 0 }} Person(s))</td>
                            <td style="text-align: right;">&#8377;{{ number_format($accFee, 2) }}</td>
                            <td style="text-align: center;">INR</td>
                        </tr>
                    @endif
                    <tr>
                        <td>GST Amount (18%)</td>
                        <td style="text-align: right;">&#8377;{{ number_format($gstAmt, 2) }}</td>
                        <td style="text-align: center;">INR</td>
                    </tr>
                    <tr class="total-row" style="background-color: {{ $themeLight }} !important; color: {{ $themeDark }}; border-top: 2px solid {{ $themeColor }} !important;">
                        <td><strong>TOTAL BUNDLED AMOUNT PAID (INCL. GST)</strong></td>
                        <td style="text-align: right;"><strong>&#8377;{{ number_format($totalAmt, 2) }}</strong></td>
                        <td style="text-align: center;"><strong>INR</strong></td>
                    </tr>
                @else
                    <tr>
                        <td>Delegate Registration Fee (Excl. GST) &mdash; {{ $registration->delegateCategory?->category_name ?? 'Indian Delegate' }}</td>
                        <td style="text-align: right;">&#8377;{{ number_format($delFee, 2) }}</td>
                        <td style="text-align: center;">INR</td>
                    </tr>
                    @if ($accFee > 0)
                        <tr>
                            <td>Accompanying Persons Fee ({{ $registration->accompanying_persons ?? 0 }} Person(s))</td>
                            <td style="text-align: right;">&#8377;{{ number_format($accFee, 2) }}</td>
                            <td style="text-align: center;">INR</td>
                        </tr>
                    @endif
                    <tr>
                        <td>GST Amount (18%)</td>
                        <td style="text-align: right;">&#8377;{{ number_format($gstAmt, 2) }}</td>
                        <td style="text-align: center;">INR</td>
                    </tr>
                    <tr class="total-row" style="background-color: {{ $themeLight }} !important; color: {{ $themeDark }}; border-top: 2px solid {{ $themeColor }} !important;">
                        <td><strong>TOTAL REGISTRATION AMOUNT PAID (INCL. GST)</strong></td>
                        <td style="text-align: right;"><strong>&#8377;{{ number_format($totalAmt, 2) }}</strong></td>
                        <td style="text-align: center;"><strong>INR</strong></td>
                    </tr>
                @endif
            </tbody>
        </table>

        @if($receiptType === 'conference' && ($registration->participate_in_cme || $registration->cmeApplication))
            <div style="margin-top: -4px; margin-bottom: 8px; font-size: 8px; color: #64748B; font-style: italic;">
                * Note: Delegate has also enrolled in Pre-Conference Workshop ({{ $preConfTopic }}). Workshop fee is billed under an independent Pre-Conference Workshop Receipt.
            </div>
        @elseif($receiptType === 'cme')
            <div style="margin-top: -4px; margin-bottom: 8px; font-size: 8px; color: #64748B; font-style: italic;">
                * Note: This receipt acknowledges payment solely for Pre-Conference Workshop participation. Main Conference registration is covered by a separate Conference Registration Receipt.
            </div>
        @endif

        {{-- Footer Section --}}
        <div class="footer-wrap" style="border-top: 1px solid #CBD5E1;">
            <table class="footer-table">
                <tr>
                    <td class="footer-left">
                        <strong>IPHACON 2027 Organizing Committee</strong><br>
                        Department of Community Medicine, RIMS, Ranchi, Jharkhand<br>
                        Email: info@iphacon2027.com &nbsp;|&nbsp; Web: www.iphacon2027.com<br>
                        <span style="font-size: 8px; color: #94A3B8; margin-top: 3px; display: block;">*
                            Computer-generated official receipt. No physical signature required.</span>
                    </td>
                    <td class="footer-right">
                        <div class="stamp-box" style="border: 1px dashed {{ $themeColor }}; background-color: {{ $themeLight }};">
                            <div class="stamp-title" style="color: {{ $themeDark }};">{{ $stampTitle }}</div>
                            <div style="font-size: 8.5px; color: #16A34A; font-weight: bold; margin-top: 2px;">OFFICIAL RECEIPT</div>
                        </div>
                    </td>
                </tr>
            </table>
        </div>

    </div>

</body>

</html>
