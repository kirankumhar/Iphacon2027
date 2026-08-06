<!DOCTYPE html>
<html lang="en" xmlns:v="urn:schemas-microsoft-com:vml">

<head>
  <meta charset="utf-8">
  <meta name="x-apple-disable-message-reformatting">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>IPHACON 2027 Registration Confirmation</title>

  <style>
    body, table, td, a {
      font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif !important;
      -webkit-font-smoothing: antialiased;
    }
    img {
      border: 0;
      outline: none;
      text-decoration: none;
      display: block;
    }
    table {
      border-collapse: collapse !important;
    }
    a {
      text-decoration: none;
    }
    .wrapper {
      width: 100%;
      background-color: #f1f5f9;
      padding: 30px 0;
    }
    .container {
      width: 100%;
      max-width: 620px;
      margin: 0 auto;
      background: #ffffff;
      border-radius: 16px;
      overflow: hidden;
      box-shadow: 0 10px 30px rgba(15, 23, 42, 0.08);
      border: 1px solid #e2e8f0;
    }
    .brand-header {
      background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #2D69FF 100%);
      color: #ffffff;
      padding: 32px 24px;
      text-align: center;
    }
    .brand-title {
      font-size: 22px;
      font-weight: 800;
      letter-spacing: 0.5px;
      margin: 0 0 4px 0;
      color: #ffffff;
      text-transform: uppercase;
    }
    .brand-sub {
      font-size: 12.5px;
      color: #93c5fd;
      margin: 0;
      font-weight: 600;
    }
    .hero {
      padding: 28px 30px 10px;
      text-align: center;
    }
    .hero h2 {
      font-size: 22px;
      margin: 0 0 8px;
      color: #0f172a;
      font-weight: 800;
    }
    .hero p {
      font-size: 14px;
      color: #64748b;
      margin: 0;
      line-height: 1.5;
    }
    .card {
      margin: 18px 24px;
      border: 1px solid #e2e8f0;
      border-radius: 12px;
      overflow: hidden;
      background: #ffffff;
    }
    .card-header {
      background: #f8fafc;
      padding: 12px 18px;
      font-weight: 700;
      color: #2D69FF;
      font-size: 13.5px;
      border-bottom: 1px solid #e2e8f0;
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }
    .card-body {
      padding: 16px 18px;
    }
    .info-table {
      width: 100%;
    }
    .info-table td {
      padding: 8px 0;
      vertical-align: top;
      font-size: 13.5px;
      border-bottom: 1px solid #f1f5f9;
    }
    .info-table tr:last-child td {
      border-bottom: none;
    }
    .label {
      color: #64748b;
      width: 45%;
      font-weight: 500;
    }
    .value {
      color: #0f172a;
      font-weight: 700;
      width: 55%;
      text-align: right;
    }
    .badge {
      display: inline-block;
      padding: 4px 12px;
      font-size: 11.5px;
      border-radius: 20px;
      font-weight: 700;
      text-transform: uppercase;
    }
    .badge-success {
      background: #DCFFF0;
      color: #15803d;
    }
    .badge-pending {
      background: #fef3c7;
      color: #b45309;
    }
    .badge-reg {
      background: #E1F0FF;
      color: #2D69FF;
      font-family: monospace;
      font-size: 13px;
      padding: 4px 10px;
    }
    .divider {
      height: 1px;
      background: #e2e8f0;
      margin: 20px 24px;
    }
    .btn {
      display: inline-block;
      padding: 12px 24px;
      background: linear-gradient(135deg, #2D69FF 0%, #1A52E0 100%);
      color: #ffffff !important;
      border-radius: 8px;
      font-weight: 700;
      font-size: 14px;
      box-shadow: 0 4px 12px rgba(45, 105, 255, 0.25);
    }
    .footer {
      text-align: center;
      color: #94a3b8;
      font-size: 12px;
      padding: 20px 24px 30px;
      background: #f8fafc;
      border-top: 1px solid #e2e8f0;
    }
    .footer strong {
      color: #475569;
    }
  </style>
</head>

<body style="margin:0; padding:0; background-color:#f1f5f9;">
  <div class="wrapper">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0">
      <tr>
        <td align="center">

          <table role="presentation" class="container" cellspacing="0" cellpadding="0" width="100%">
            <!-- Brand Header -->
            <tr>
              <td class="brand-header">
                <p class="brand-title">{{ config('app.name', 'IPHACON 2027') }}</p>
                <p class="brand-sub">71st National Annual Conference of Indian Public Health Association</p>
              </td>
            </tr>

            <!-- Hero Section -->
            <tr>
              <td class="hero">
                <h2>Registration Confirmed!</h2>
                <p>Dear <strong>{{ $registration->user->prefix }} {{ $registration->user->full_name }}</strong>, thank you for completing your conference registration.</p>
              </td>
            </tr>

            <!-- Core Registration Details Card -->
            <tr>
              <td>
                <table role="presentation" class="card" width="100%" cellspacing="0" cellpadding="0">
                  <tr>
                    <td class="card-header">📋 Registration Summary</td>
                  </tr>
                  <tr>
                    <td class="card-body">
                      <table role="presentation" class="info-table" cellspacing="0" cellpadding="0">
                        <tr>
                          <td class="label">Registration No.</td>
                          <td class="value">
                            <span class="badge badge-reg">{{ $registration->registration_number ?? 'Pending' }}</span>
                          </td>
                        </tr>
                        <tr>
                          <td class="label">Delegate Type</td>
                          <td class="value">{{ $registration->delegate_type ?? 'Indian' }} Delegate</td>
                        </tr>
                        <tr>
                          <td class="label">Selected Category</td>
                          <td class="value">{{ $registration->delegateCategory->category_name ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                          <td class="label">Country / State</td>
                          <td class="value">{{ $registration->country->country_name ?? 'India' }}</td>
                        </tr>
                        <tr>
                          <td class="label">Registration Status</td>
                          <td class="value">
                            @php $status = $registration->status ?? 'Payment Submitted'; @endphp
                            @if(strtolower($status) === 'approved')
                              <span class="badge badge-success">✓ APPROVED</span>
                            @else
                              <span class="badge badge-pending">⏳ {{ strtoupper($status) }}</span>
                            @endif
                          </td>
                        </tr>
                        @if($registration->latestPayment?->transaction_id)
                        <tr>
                          <td class="label">Transaction Reference</td>
                          <td class="value"><span style="font-family: monospace;">{{ $registration->latestPayment->transaction_id }}</span></td>
                        </tr>
                        @endif
                      </table>
                    </td>
                  </tr>
                </table>
              </td>
            </tr>

            <!-- Financial Breakdown Card -->
            <tr>
              <td>
                <table role="presentation" class="card" width="100%" cellspacing="0" cellpadding="0">
                  <tr>
                    <td class="card-header">💳 Financial Breakdown</td>
                  </tr>
                  <tr>
                    <td class="card-body">
                      <table role="presentation" class="info-table" cellspacing="0" cellpadding="0">
                        @php
                          $isForeign = ($registration->delegate_type === 'International');
                          $catBase = $registration->delegateCategory ? (float)$registration->delegateCategory->indian_fee : 0;
                          $cmeBase = $registration->cme_fee ?: ($registration->participate_in_cme ? 1000 : 0);
                          $accBase = $registration->accompanying_fee ?: (($registration->accompanying_persons ?? 0) * 5000);
                          $subtotalBase = $catBase + $cmeBase + $accBase;
                          $gstAmt = $registration->gst_amount ?: round($subtotalBase * 0.18, 2);
                          $totalAmt = $registration->total_amount ?: round($subtotalBase + $gstAmt, 2);
                        @endphp

                        @if ($isForeign)
                          <tr>
                            <td class="label">Foreign Delegate Package</td>
                            <td class="value">$175.00 USD</td>
                          </tr>
                          <tr>
                            <td class="label" style="font-weight: 700; color: #0f172a;">Total Paid Amount</td>
                            <td class="value" style="color: #15803d; font-size: 16px;">$175.00 USD</td>
                          </tr>
                        @else
                          <tr>
                            <td class="label">Delegate Category (Base Price)</td>
                            <td class="value">₹{{ number_format($catBase, 2) }}</td>
                          </tr>
                          @if ($registration->participate_in_cme)
                          <tr>
                            <td class="label">CME Workshop Fee</td>
                            <td class="value">₹{{ number_format($cmeBase, 2) }}</td>
                          </tr>
                          @endif
                          @if (($registration->accompanying_persons ?? 0) > 0)
                          <tr>
                            <td class="label">Accompanying Persons ({{ $registration->accompanying_persons }})</td>
                            <td class="value">₹{{ number_format($accBase, 2) }}</td>
                          </tr>
                          @endif
                          <tr>
                            <td class="label">GST Amount (18%)</td>
                            <td class="value" style="color: #d97706;">+ ₹{{ number_format($gstAmt, 2) }}</td>
                          </tr>
                          <tr style="background: #f8fafc;">
                            <td class="label" style="font-weight: 700; color: #0f172a; padding: 10px 0;">Total Amount (Incl. GST)</td>
                            <td class="value" style="color: #15803d; font-size: 16px; padding: 10px 0;">₹{{ number_format($totalAmt, 2) }} INR</td>
                          </tr>
                        @endif
                      </table>
                    </td>
                  </tr>
                </table>
              </td>
            </tr>

            <!-- Notice & PDF Attachment Banner -->
            <tr>
              <td style="padding: 0 24px; text-align: center;">
                <div style="background-color: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 10px; padding: 16px; margin-bottom: 20px;">
                  <p style="color: #166534; font-size: 13.5px; margin: 0; font-weight: 600;">
                    📄 Your official Registration Acknowledgement PDF is attached to this email.
                  </p>
                </div>
              </td>
            </tr>

            <!-- Action Button -->
            <tr>
              <td align="center" style="padding: 0 24px 24px;">
                <a href="{{ url('/login') }}" class="btn" target="_blank">Access Delegate Portal</a>
              </td>
            </tr>

            <!-- Footer -->
            <tr>
              <td class="footer">
                <div style="margin-bottom: 4px;">
                  <strong>{{ config('app.name', 'IPHACON 2027') }}</strong>
                </div>
                <div style="margin-bottom: 8px;">
                  71st National Annual Conference of Indian Public Health Association
                </div>
                <div>
                  Website: <a href="https://www.iphacon2027.com" target="_blank" style="color: #2D69FF; text-decoration: underline;">www.iphacon2027.com</a>
                </div>
                <div style="margin-top: 12px; font-size: 11px; color: #94a3b8;">
                  If you have any questions, please contact conference support at <strong>{{ config('mail.from.address') }}</strong>.
                </div>
              </td>
            </tr>
          </table>

        </td>
      </tr>
    </table>
  </div>
</body>

</html>