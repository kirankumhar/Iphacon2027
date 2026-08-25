<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>IPHACON 2027 - Payment & Registration Reminder</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body,
        table,
        td,
        a {
            font-family: 'Segoe UI', -apple-system, BlinkMacSystemFont, Roboto, Helvetica, Arial, sans-serif !important;
            -webkit-font-smoothing: antialiased;
        }

        .wrapper {
            width: 100%;
            background-color: #f0f4f8;
            padding: 30px 0;
        }

        .container {
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(2, 136, 209, 0.08);
            border: 1px solid #cbd5e1;
        }

        .brand-header {
            background: linear-gradient(135deg, #B45309 0%, #D97706 50%, #F59E0B 100%);
            color: #ffffff;
            padding: 28px 24px;
            text-align: center;
        }

        .brand-badge {
            display: inline-block;
            background: rgba(255, 255, 255, 0.25);
            color: #ffffff;
            font-size: 11px;
            font-weight: 700;
            padding: 4px 14px;
            border-radius: 20px;
            letter-spacing: 0.5px;
            margin-bottom: 8px;
            text-transform: uppercase;
        }

        .brand-title {
            font-size: 20px;
            font-weight: 800;
            letter-spacing: -0.3px;
            margin: 0 0 4px 0;
            color: #ffffff;
        }

        .brand-sub {
            font-size: 12.5px;
            color: #FEF3C7;
            margin: 0;
            font-weight: 600;
        }

        .hero {
            padding: 28px 24px 10px;
            text-align: center;
        }

        .hero h2 {
            font-size: 22px;
            margin: 0 0 8px;
            color: #92400E;
            font-weight: 800;
        }

        .hero p {
            font-size: 14px;
            color: #475569;
            margin: 0;
            line-height: 1.5;
        }

        .card-cell {
            padding: 10px 24px;
        }

        .card {
            width: 100% !important;
            border: 1px solid #cbd5e1;
            border-radius: 12px;
            overflow: hidden;
            background: #ffffff;
            border-collapse: separate;
        }

        .card-header {
            background: #FFFBEB;
            padding: 12px 18px;
            font-weight: 700;
            color: #B45309;
            font-size: 13.5px;
            border-bottom: 1px solid #FDE68A;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .card-body {
            padding: 18px;
        }

        .info-table {
            width: 100%;
            border-collapse: collapse;
        }

        .info-table td {
            padding: 9px 0;
            font-size: 13.5px;
            border-bottom: 1px dashed #e2e8f0;
            vertical-align: middle;
        }

        .info-table tr:last-child td {
            border-bottom: none;
            padding-bottom: 0;
        }

        .label {
            color: #64748b;
            font-weight: 600;
            width: 42%;
        }

        .value {
            color: #0f172a;
            font-weight: 700;
            width: 58%;
        }

        .amount-highlight {
            font-size: 18px;
            color: #B45309;
            font-weight: 800;
        }

        .badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 700;
        }

        .badge-pending {
            background: #FEF3C7;
            color: #92400E;
            border: 1px solid #FDE68A;
        }

        .badge-reg {
            background: #eff6ff;
            color: #1d4ed8;
            border: 1px solid #bfdbfe;
            font-family: monospace;
            font-size: 12px;
        }

        .notice-box {
            background-color: #FEF3C7;
            border: 1px solid #FDE68A;
            border-radius: 10px;
            padding: 16px 20px;
            text-align: left;
        }

        .notice-box p {
            color: #78350F;
            font-size: 13.5px;
            margin: 0 0 6px 0;
            line-height: 1.5;
        }

        .notice-box p:last-child {
            margin-bottom: 0;
        }

        .custom-note-box {
            background-color: #F0FDF4;
            border: 1px solid #BBF7D0;
            border-radius: 10px;
            padding: 14px 18px;
            text-align: left;
            margin-bottom: 12px;
        }

        .custom-note-box p {
            color: #166534;
            font-size: 13px;
            margin: 0;
            line-height: 1.5;
        }

        .btn-container {
            text-align: center;
            padding: 20px 24px;
        }

        .btn {
            display: inline-block;
            padding: 14px 32px;
            background: linear-gradient(135deg, #0288D1 0%, #01579B 100%);
            color: #ffffff !important;
            border-radius: 30px;
            font-weight: 700;
            font-size: 14.5px;
            box-shadow: 0 4px 15px rgba(2, 136, 209, 0.35);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            text-decoration: none;
        }

        .footer {
            text-align: center;
            color: #64748b;
            font-size: 12px;
            padding: 20px 24px 30px;
            background: #f8fafc;
            border-top: 1px solid #e2e8f0;
        }

        .footer strong {
            color: #0f172a;
        }
    </style>
</head>

<body style="margin:0; padding:0; background-color:#f0f4f8;">
    <div class="wrapper">
        <table role="presentation" width="100%" cellspacing="0" cellpadding="0">
            <tr>
                <td align="center">

                    <table role="presentation" class="container" cellspacing="0" cellpadding="0" width="100%">

                        <!-- Header Banner -->
                        <tr>
                            <td class="brand-header">
                                <span class="brand-badge">🔔 Action Required • Payment Reminder</span>
                                <h1 class="brand-title">71st Annual National Conference of IPHA</h1>
                                <p class="brand-sub">IPHACON 2027 | 12th - 14th March 2027 | RIMS, Ranchi</p>
                            </td>
                        </tr>

                        <!-- Hero Section -->
                        <tr>
                            <td class="hero">
                                <h2>Complete Your Delegate Registration</h2>
                                <p>Dear <strong>{{ $userPrefix ?? '' }} {{ $userName ?? 'Delegate' }}</strong>,</p>
                                <p style="margin-top: 6px;">
                                    We noticed that your registration for <strong>IPHACON 2027</strong> is currently
                                    <strong>pending payment or incomplete</strong>. To secure your participation and
                                    confirm your delegate registration, please complete your pending payment at the
                                    earliest.
                                </p>
                            </td>
                        </tr>

                        <!-- Custom Message / Note from Secretariat if provided -->
                        @if (!empty($customMessage))
                            <tr>
                                <td class="card-cell">
                                    <div class="custom-note-box">
                                        <p><strong><i class="bx bx-message-detail"></i> Message from IPHACON
                                                Secretariat:</strong></p>
                                        <p style="margin-top: 4px; font-style: italic;">"{{ $customMessage }}"</p>
                                    </div>
                                </td>
                            </tr>
                        @endif

                        <!-- Registration & Payment Details Card -->
                        <tr>
                            <td class="card-cell">
                                <table role="presentation" class="card" width="100%" cellspacing="0"
                                    cellpadding="0">
                                    <tr>
                                        <td class="card-header">📋 Registration & Fee Summary</td>
                                    </tr>
                                    <tr>
                                        <td class="card-body">
                                            <table role="presentation" class="info-table" cellspacing="0"
                                                cellpadding="0">
                                                @if (!empty($acknowledgementId))
                                                    <tr>
                                                        <td class="label">Acknowledgement ID</td>
                                                        <td class="value">
                                                            <span
                                                                class="badge badge-reg">{{ $acknowledgementId }}</span>
                                                        </td>
                                                    </tr>
                                                @endif
                                                @if (!empty($categoryName))
                                                    <tr>
                                                        <td class="label">Delegate Category</td>
                                                        <td class="value">{{ $categoryName }}</td>
                                                    </tr>
                                                @endif
                                                @if (!empty($delegateType))
                                                    <tr>
                                                        <td class="label">Delegate Type</td>
                                                        <td class="value">{{ $delegateType }} Delegate</td>
                                                    </tr>
                                                @endif
                                                <tr>
                                                    <td class="label">Payment Status</td>
                                                    <td class="value">
                                                        <span class="badge badge-pending">
                                                            {{ $paymentStatus ?? 'Pending Payment' }}
                                                        </span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="label">Pending Fee Amount</td>
                                                    <td class="value amount-highlight">
                                                        {{ $currencySymbol ?? '₹' }}{{ number_format((float) ($pendingAmount ?? 0), 2) }}
                                                    </td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>

                        <!-- Action Button -->
                        <tr>
                            <td class="btn-container">
                                <a href="{{ $paymentUrl ?? url('/login') }}" class="btn" target="_blank">
                                    Complete Registration &amp; Pay Now &rarr;
                                </a>
                                <p style="font-size: 12px; color: #64748b; margin-top: 10px; margin-bottom: 0;">
                                    Click above to login and complete your registration payment securely.
                                </p>
                            </td>
                        </tr>

                        <!-- How to Complete Payment Steps -->
                        <tr>
                            <td class="card-cell">
                                <div class="notice-box">
                                    <p><strong>📌 Easy Steps to Complete Your Payment:</strong></p>
                                    <p>1. Log in to your IPHACON 2027 account at <a href="{{ url('/login') }}"
                                            style="color: #0288D1; font-weight: bold;">{{ url('/login') }}</a> using
                                        your registered email.</p>
                                    <p>2. Proceed to the <strong>Registration &amp; Payment</strong> tab.</p>
                                    <p>3. Choose your preferred online payment mode (UPI, Credit/Debit Card, Net
                                        Banking) or upload your offline payment receipt.</p>
                                    <p>4. Once verified, your official Registration Number and confirmation receipt will
                                        be issued instantly.</p>
                                </div>
                            </td>
                        </tr>

                        <!-- Bank Transfer Details (For NEFT / RTGS / Offline) -->
                        {{-- <tr>
                          <td class="card-cell">
                            <table role="presentation" class="card" width="100%" cellspacing="0" cellpadding="0">
                              <tr>
                                <td class="card-header" style="background: #F8FAFC; color: #334155; border-bottom: 1px solid #E2E8F0;">
                                  🏦 Bank Details (For Direct NEFT / RTGS / IMPS)
                                </td>
                              </tr>
                              <tr>
                                <td class="card-body">
                                  <table role="presentation" class="info-table" cellspacing="0" cellpadding="0">
                                    <tr>
                                      <td class="label">Account Name</td>
                                      <td class="value">IPHACON 2027</td>
                                    </tr>
                                    <tr>
                                      <td class="label">Account Number</td>
                                      <td class="value" style="font-family: monospace; font-size: 14px;">925020005721245</td>
                                    </tr>
                                    <tr>
                                      <td class="label">IFSC Code</td>
                                      <td class="value" style="font-family: monospace; font-size: 14px;">UTIB0000183</td>
                                    </tr>
                                    <tr>
                                      <td class="label">Bank &amp; Branch</td>
                                      <td class="value">Axis Bank, Main Road Ranchi Branch</td>
                                    </tr>
                                  </table>
                                  <p style="font-size: 11.5px; color: #64748b; margin-top: 10px; margin-bottom: 0;">
                                    * Note: If paying via Bank Transfer, please upload the transaction reference number (UTR/Txn ID) &amp; receipt in your delegate portal.
                                  </p>
                                </td>
                              </tr>
                            </table>
                          </td>
                        </tr> --}}

                        <!-- Support / Footer -->
                        <tr>
                            <td style="padding: 10px 24px 20px;">
                                <p style="font-size: 13px; color: #475569; text-align: center; margin: 0;">
                                    If you have already made the payment or need any assistance, please contact us at <a
                                        href="mailto:iphacon2027@gmail.com"
                                        style="color: #0288D1; font-weight: 600;">iphacon2027@gmail.com</a>.
                                </p>
                            </td>
                        </tr>

                        <!-- Footer -->
                        <tr>
                            <td class="footer">
                                <p style="margin: 0 0 6px 0;"><strong>IPHACON 2027 Secretariat</strong></p>
                                <p style="margin: 0 0 6px 0;">Department of Community Medicine, Rajendra Institute of
                                    Medical Sciences (RIMS), Ranchi, Jharkhand - 834009</p>
                                <p style="margin: 0; font-size: 11px; color: #94a3b8;">
                                    This is an automated reminder regarding your pending registration fee for IPHACON
                                    2027.
                                </p>
                            </td>
                        </tr>

                    </table>

                </td>
            </tr>
        </table>
    </div>
</body>

</html>
