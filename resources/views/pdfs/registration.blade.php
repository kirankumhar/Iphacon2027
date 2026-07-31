{{-- resources/views/registration/receipt.blade.php --}}
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Registration Cum Receipt - {{ $registration->registration_number }}</title>
    <style>
        @page {
            margin: 20mm 14mm;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            color: #222;
            font-size: 12px;
        }

        /* Header band */
        .header-wrap {
            margin-bottom: 14px;
        }

        .logo-row {
            width: 100%;
            display: table;
            border-collapse: collapse;
        }

        .logo-cell {
            display: table-cell;
            vertical-align: middle;
            text-align: center;
            width: 33.33%;
            padding: 4px 0;
        }

        .logo {
            height: 60px;
            /* adjust as needed */
        }

        .brand-block {
            text-align: center;
            margin-top: 8px;
            line-height: 1.4;
        }

        .brand-title {
            font-size: 16px;
            font-weight: 700;
            margin-bottom: 3px;
        }

        .brand-meta {
            font-size: 11.5px;
            color: #555;
        }

        /* Title strip */
        .doc-title {
            text-align: center;
            font-size: 14px;
            font-weight: 700;
            padding: 8px 10px;
            border: 1px solid #ddd;
            background: #f7f7f9;
            border-radius: 5px;
            margin-bottom: 10px;
        }

        /* Info Cards (two rows) */
        .info-grid {
            width: 100%;
            border: 1px solid #ddd;
            border-radius: 6px;
            padding: 10px;
            margin-bottom: 12px;
        }

        .row {
            display: table;
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            margin: 0 0 8px 0;
        }

        .col {
            display: table-cell;
            vertical-align: top;
            padding-right: 10px;
        }

        .col:last-child {
            padding-right: 0;
        }

        .label {
            color: #6b7280;
            font-size: 11px;
            margin-bottom: 2px;
        }

        .value {
            font-weight: 600;
        }

        /* Table */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 6px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
        }

        th {
            background: #f3f4f6;
            text-align: left;
        }

        .right {
            text-align: right;
        }

        .muted {
            color: #666;
        }

        /* Totals table (right aligned small table) */
        .totals {
            margin-top: 10px;
        }

        .totals table {
            width: 50%;
            margin-left: auto;
        }

        /* Footer area */
        .footer {
            margin-top: 16px;
            display: table;
            width: 100%;
            border-collapse: collapse;
        }

        .foot-col {
            display: table-cell;
            vertical-align: bottom;
            width: 50%;
        }

        .foot-left .label {
            font-weight: 600;
        }

        .sign {
            text-align: right;
        }

        .signature {
            height: 50px;
        }

        /* Watermark */
        .watermark {
            position: fixed;
            top: 40%;
            left: 18%;
            opacity: 0.06;
            font-size: 80px;
            transform: rotate(-20deg);
        }

        /* Small helpers */
        .sp-6 {
            height: 6px;
        }

        .sp-10 {
            height: 10px;
        }

        .center {
            text-align: center;
        }
    </style>
</head>

<body>
    @if (($registration->latestPayment->payment_status ?? '') === 'Success')
        <div class="watermark">PAID</div>
    @endif

    <div class="header-wrap">
        <!-- Logos row (Left - Center - Right) -->
        <div class="logo-row">
            <div class="logo-cell">
                @if (file_exists(public_path('shared/user/images/rimslogo.png')))
                    <img src="{{ public_path('shared/user/images/rimslogo.png') }}" class="logo" alt="Left Logo">
                @endif
            </div>
            <div class="logo-cell">
                @if (file_exists(public_path('shared/user/images/iphacon_logo.png')))
                    <img src="{{ public_path('shared/user/images/iphacon_logo.png') }}" class="logo" alt="Center Logo">
                @endif
            </div>
            <div class="logo-cell">
                @if (file_exists(public_path('shared/user/images/rimslogo.png')))
                    <img src="{{ public_path('shared/user/images/rimslogo.png') }}" class="logo" alt="Right Logo">
                @endif
            </div>
        </div>

        <!-- Organization info centered -->
        <div class="brand-block">
            <div class="brand-title">{{ config('app.name') }}</div>
            <div class="brand-meta">
                <h1 class="ismm-subtitle mb-0">
                    71<sup>st</sup> Annual National Conference of the Indian Public Health Association, IPHACON 2027, Ranchi
                    <!-- 16<sup>th</sup> National Biennial Conference of IPHACON 2027, Ranchi -->
                </h1>
                <h2 class="ismm-location mb-0">
                    04 - 07 February, 2027
                </h2>
                www.iphacon2027.com
            </div>
        </div>
    </div>

    <div class="doc-title">Registration Confirmation Cum Receipt</div>

    <!-- Info Grid -->
    <div class="info-grid">
        <div class="row">
            <div class="col">
                <div class="label">Registration No.</div>
                <div class="value">{{ $registration->registration_number }}</div>
            </div>
            <div class="col">
                <div class="label">Date</div>
                <div class="value">{{ now()->format('d M Y') }}</div>
            </div>
            <div class="col">
                <div class="label">Payment Status</div>
                <div class="value">{{ $registration->latestPayment->payment_status ?? 'Pending' }}</div>
            </div>
        </div>

        <div class="row">
            <div class="col">
                <div class="label">Delegate Name</div>
                <div class="value">{{ $registration->user->prefix }} {{ $registration->user->full_name }}</div>
            </div>
            <div class="col">
                <div class="label">Email</div>
                <div class="value">{{ $registration->user->email }}</div>
            </div>
            <div class="col">
                <div class="label">Delegate Type</div>
                <div class="value">{{ $registration->delegate_type }}</div>
            </div>
        </div>

        <div class="row" style="margin-bottom:0;">
            <div class="col">
                <div class="label">Category</div>
                <div class="value">{{ $registration->delegateCategory->category_name ?? 'N/A' }}</div>
            </div>
            <div class="col">
                <div class="label">Country</div>
                <div class="value">{{ $registration->country->country_name ?? 'N/A' }}</div>
            </div>
            <div class="col">
                <div class="label">Transaction ID</div>
                <div class="value">{{ $registration->latestPayment->transaction_id ?? 'N/A' }}</div>
            </div>
        </div>
    </div>

    <!-- Items Table -->
    <table>
        <thead>
            <tr>
                <th>Description</th>
                <th class="right">Amount</th>
                <th>Currency</th>
            </tr>
        </thead>
        <tbody>
            @if ($registration->delegate_type === 'International')
                <tr>
                    <td>Delegate Category Fee</td>
                    <td class="right">${{ number_format($registration->delegate_fee ?: 175, 2) }}</td>
                    <td>USD</td>
                </tr>
                <tr>
                    <td><strong>Total Amount</strong></td>
                    <td class="right"><strong>${{ number_format($registration->total_amount ?: 175, 2) }}</strong></td>
                    <td>USD</td>
                </tr>
            @else
                @php
                    $catFee = $registration->delegateCategory ? (float)$registration->delegateCategory->indian_fee : 0;
                    $delFee = $registration->delegate_fee ?: round($catFee / 1.18, 2);
                    $gstAmt = $registration->gst_amount ?: round($catFee - $delFee, 2);
                    $cmeFee = $registration->cme_fee ?: ($registration->participate_in_cme ? 1000 : 0);
                    $accFee = $registration->accompanying_fee ?: (($registration->accompanying_persons ?? 0) * 4000);
                    $totalAmt = $registration->total_amount ?: ($catFee + $cmeFee + $accFee);
                @endphp
                <tr>
                    <td>Delegate Category Fee (Excl. GST)</td>
                    <td class="right">₹{{ number_format($delFee, 2) }}</td>
                    <td>INR</td>
                </tr>
                @if ($registration->participate_in_cme)
                    <tr>
                        <td>CME / Workshop Participation</td>
                        <td class="right">₹{{ number_format($cmeFee, 2) }}</td>
                        <td>INR</td>
                    </tr>
                @endif
                @if (($registration->accompanying_persons ?? 0) > 0)
                    <tr>
                        <td>Accompanying Persons ({{ $registration->accompanying_persons }})</td>
                        <td class="right">₹{{ number_format($accFee, 2) }}</td>
                        <td>INR</td>
                    </tr>
                @endif
                <tr>
                    <td>GST Amount (18%)</td>
                    <td class="right">₹{{ number_format($gstAmt, 2) }}</td>
                    <td>INR</td>
                </tr>
                <tr>
                    <td><strong>Total Amount (Incl. GST)</strong></td>
                    <td class="right"><strong>₹{{ number_format($totalAmt, 2) }}</strong></td>
                    <td>INR</td>
                </tr>
            @endif
        </tbody>
    </table>

    <!-- Totals / Payment summary -->
    <div class="totals">
        <table>
            <tr>
                <th style="width:65%;">Payment Method</th>
                <td class="right">{{ $registration->latestPayment->payment_method ?? 'N/A' }}</td>
            </tr>
            <tr>
                <th>Payment Status</th>
                <td class="right">{{ $registration->latestPayment->payment_status ?? 'Pending' }}</td>
            </tr>
        </table>
    </div>

    <div class="sp-6"></div>
    <div class="muted">Note: This is a computer-generated document and does not require a physical signature. Please
        keep this receipt for your records.</div>

    <!-- Footer -->
    <div class="footer">
        <div class="foot-col foot-left">
            <div class="label">Conference Secretariat</div>
            <div>www.iphacon2027.com</div>
        </div>
       <!-- <div class="foot-col sign">
            @if (file_exists(public_path('images/signature.png')))
                <img src="{{ public_path('images/signature.png') }}" alt="Signature" class="signature">
            @endif
            <div class="label">Authorized Signatory</div>
        </div> -->
    </div>
</body>

</html>
