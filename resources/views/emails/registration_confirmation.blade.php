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
      font-family: 'Segoe UI', -apple-system, BlinkMacSystemFont, Roboto, Helvetica, Arial, sans-serif !important;
      -webkit-font-smoothing: antialiased;
    }
    img {
      border: 0;
      outline: none;
      text-decoration: none;
      display: inline-block;
    }
    table {
      border-collapse: collapse !important;
    }
    a {
      text-decoration: none;
    }
    .wrapper {
      width: 100%;
      background-color: #f0f4f8;
      padding: 30px 0;
    }
    .container {
      width: 100%;
      max-width: 620px;
      margin: 0 auto;
      background: #ffffff;
      border-radius: 16px;
      overflow: hidden;
      box-shadow: 0 10px 30px rgba(2, 136, 209, 0.08);
      border: 1px solid #e2e8f0;
    }
    .header-top-bar {
      background: #ffffff;
      padding: 20px 24px;
      border-bottom: 3px solid #0288D1;
    }
    .brand-header {
      background: linear-gradient(135deg, #01579B 0%, #0288D1 50%, #00897B 100%);
      color: #ffffff;
      padding: 24px 20px;
      text-align: center;
    }
    .brand-title {
      font-size: 20px;
      font-weight: 800;
      letter-spacing: 0.5px;
      margin: 0 0 4px 0;
      color: #ffffff;
      text-transform: uppercase;
    }
    .brand-sub {
      font-size: 12.5px;
      color: #e0f2fe;
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
      color: #01579B;
      font-weight: 800;
    }
    .hero p {
      font-size: 14px;
      color: #475569;
      margin: 0;
      line-height: 1.5;
    }
    .card {
      margin: 18px 24px;
      border: 1px solid #cbd5e1;
      border-radius: 12px;
      overflow: hidden;
      background: #ffffff;
    }
    .card-header {
      background: #f8fafc;
      padding: 12px 18px;
      font-weight: 700;
      color: #0288D1;
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
      background: #E0F2FE;
      color: #0288D1;
      font-family: monospace;
      font-size: 13px;
      padding: 4px 10px;
    }
    .btn {
      display: inline-block;
      padding: 12px 28px;
      background: linear-gradient(135deg, #0288D1 0%, #01579B 100%);
      color: #ffffff !important;
      border-radius: 25px;
      font-weight: 700;
      font-size: 14px;
      box-shadow: 0 4px 15px rgba(2, 136, 209, 0.3);
      text-transform: uppercase;
      letter-spacing: 0.5px;
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
            
            <!-- Top Logo Header Bar (IPHACON, IPHA, RIMS Logos) -->
            <tr>
              <td class="header-top-bar">
                <table role="presentation" width="100%" cellspacing="0" cellpadding="0">
                  <tr>
                    {{-- Left Logo: IPHACON 2027 Main Logo --}}
                    <td align="left" width="33%">
                      @if(isset($message) && file_exists(public_path('assets/img/logo/logo.png')))
                        <img src="{{ $message->embed(public_path('assets/img/logo/logo.png')) }}" alt="IPHACON 2027" height="55" style="max-height: 55px; width: auto;" />
                      @else
                        <img src="{{ asset('assets/img/logo/logo.png') }}" alt="IPHACON 2027" height="55" style="max-height: 55px; width: auto;" />
                      @endif
                    </td>

                    {{-- Center Logo: IPHA Emblem Logo --}}
                    <td align="center" width="34%">
                      @if(isset($message) && file_exists(public_path('assets/img/logo/ipha_logo.png')))
                        <img src="{{ $message->embed(public_path('assets/img/logo/ipha_logo.png')) }}" alt="IPHA Logo" height="55" style="max-height: 55px; width: auto;" />
                      @else
                        <img src="{{ asset('assets/img/logo/ipha_logo.png') }}" alt="IPHA Logo" height="55" style="max-height: 55px; width: auto;" />
                      @endif
                    </td>

                    {{-- Right Logo: RIMS Ranchi Logo --}}
                    <td align="right" width="33%">
                      @if(isset($message) && file_exists(public_path('assets/img/logo/rimslogo.png')))
                        <img src="{{ $message->embed(public_path('assets/img/logo/rimslogo.png')) }}" alt="RIMS Logo" height="55" style="max-height: 55px; width: auto;" />
                      @else
                        <img src="{{ asset('assets/img/logo/rimslogo.png') }}" alt="RIMS Logo" height="55" style="max-height: 55px; width: auto;" />
                      @endif
                    </td>
                  </tr>
                </table>
              </td>
            </tr>

            <!-- Conference Banner Header -->
            <tr>
              <td class="brand-header">
                <p class="brand-title">71st Annual National Conference of IPHA</p>
                <p class="brand-sub">IPHACON 2027 | 12th - 14th March 2027 | RIMS, Ranchi</p>
              </td>
            </tr>

            <!-- Hero Section -->
            <tr>
              <td class="hero">
                <h2>Registration Confirmed!</h2>
                <p>Dear <strong>{{ $registration->user->prefix ?? '' }} {{ $registration->user->full_name ?? 'Delegate' }}</strong>, thank you for completing your registration for IPHACON 2027.</p>
              </td>
            </tr>

            <!-- Core Registration Details Card -->
            <tr>
              <td>
                <table role="presentation" class="card" width="100%" cellspacing="0" cellpadding="0">
                  <tr>
                    <td class="card-header">📋 Registration Details</td>
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
                          <td class="value">{{ $registration->country->country_name ?? 'India' }}, {{ $registration->state->state_name ?? $registration->other_state ?? 'N/A' }}</td>
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
                    <td class="card-header">💳 Financial Summary</td>
                  </tr>
                  <tr>
                    <td class="card-body">
                      <table role="presentation" class="info-table" cellspacing="0" cellpadding="0">
                        @php
                          $isForeign = ($registration->delegate_type === 'International');
                          $catBase = $registration->delegateCategory ? (float)$registration->delegateCategory->indian_fee : 0;
                          $cmeBase = $registration->cme_fee ?: ($registration->participate_in_cme ? 1000 : 0);
                          $accBase = $registration->accompanying_fee ?: (($registration->accompanying_persons ?? 0) * 4000);
                          $subtotalBase = $catBase + $cmeBase + $accBase;
                          $gstAmt = $registration->gst_amount ?: round($subtotalBase * 0.18, 2);
                          $totalAmt = $registration->total_amount ?: round($subtotalBase + $gstAmt, 2);
                        @endphp

                        @if ($isForeign)
                          <tr>
                            <td class="label">Foreign Delegate Package</td>
                            <td class="value">${{ number_format($registration->delegate_fee ?: 175, 2) }} USD</td>
                          </tr>
                          <tr>
                            <td class="label" style="font-weight: 700; color: #01579B;">Total Paid Amount</td>
                            <td class="value" style="color: #0288D1; font-size: 16px;">${{ number_format($registration->total_amount ?: 175, 2) }} USD</td>
                          </tr>
                        @else
                          <tr>
                            <td class="label">Delegate Fee (Excl. GST)</td>
                            <td class="value">₹{{ number_format($catBase / 1.18, 2) }}</td>
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
                          <tr style="background: #f0f9ff;">
                            <td class="label" style="font-weight: 700; color: #01579B; padding: 10px 0;">Total Amount (Incl. GST)</td>
                            <td class="value" style="color: #0288D1; font-size: 16px; padding: 10px 0;">₹{{ number_format($totalAmt, 2) }} INR</td>
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
                <div style="background-color: #f0f9ff; border: 1px solid #bae6fd; border-radius: 10px; padding: 16px; margin-bottom: 20px;">
                  <p style="color: #0288D1; font-size: 13.5px; margin: 0; font-weight: 600;">
                    📄 Your official <strong>IPHACON Registration Acknowledgement PDF</strong> is attached to this email.
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
                  <strong>IPHACON 2027 Organizing Committee</strong>
                </div>
                <div style="margin-bottom: 8px;">
                  71st Annual National Conference of Indian Public Health Association<br>
                  Department of Community Medicine, RIMS, Ranchi, Jharkhand
                </div>
                <div>
                  Website: <a href="https://www.iphacon2027.com" target="_blank" style="color: #0288D1; text-decoration: underline;">www.iphacon2027.com</a>
                </div>
                <div style="margin-top: 12px; font-size: 11px; color: #94a3b8;">
                  If you have any questions, please contact conference support at <strong>info@iphacon2027.com</strong>.
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