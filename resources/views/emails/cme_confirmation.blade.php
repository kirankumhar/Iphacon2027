<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>IPHACON 2027 Pre-Conference Workshop Confirmation</title>

  <style>
    * {
      box-sizing: border-box;
    }
    body, table, td, a {
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
      box-shadow: 0 10px 30px rgba(16, 185, 129, 0.08);
      border: 1px solid #cbd5e1;
    }
    .brand-header {
      background: linear-gradient(135deg, #065f46 0%, #059669 50%, #0284c7 100%);
      color: #ffffff;
      padding: 28px 24px;
      text-align: center;
    }
    .brand-badge {
      display: inline-block;
      background: rgba(255, 255, 255, 0.22);
      color: #ffffff;
      font-size: 11px;
      font-weight: 700;
      padding: 4px 14px;
      border-radius: 20px;
      letter-spacing: 0.5px;
      margin-bottom: 8px;
      text-transform: uppercase;
      border: 1px solid rgba(255, 255, 255, 0.35);
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
      color: #ecfdf5;
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
      color: #065f46;
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
      background: #f8fafc;
      padding: 12px 18px;
      font-weight: 700;
      color: #059669;
      font-size: 13.5px;
      border-bottom: 1px solid #e2e8f0;
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }
    .card-body {
      padding: 14px 18px;
    }
    .info-table {
      width: 100% !important;
      border-collapse: collapse;
    }
    .info-table td {
      padding: 8px 4px 8px 0;
      vertical-align: top;
      font-size: 13.5px;
      border-bottom: 1px solid #f1f5f9;
    }
    .info-table tr:last-child td {
      border-bottom: none;
    }
    .label {
      color: #64748b;
      width: 42%;
      font-weight: 500;
      text-align: left;
      padding-right: 8px;
    }
    .value {
      color: #0f172a;
      font-weight: 700;
      width: 58%;
      text-align: right;
      word-break: break-word;
    }
    .badge {
      display: inline-block;
      padding: 4px 10px;
      font-size: 11.5px;
      border-radius: 20px;
      font-weight: 700;
      text-transform: uppercase;
    }
    .badge-success {
      background: #DCFFF0;
      color: #15803d;
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
      background: linear-gradient(135deg, #059669 0%, #047857 100%);
      color: #ffffff !important;
      border-radius: 25px;
      font-weight: 700;
      font-size: 14px;
      box-shadow: 0 4px 15px rgba(5, 150, 105, 0.3);
      text-transform: uppercase;
      letter-spacing: 0.5px;
      text-decoration: none;
    }
    .attachment-notice {
      background: #f0fdf4;
      border-left: 4px solid #10b981;
      padding: 12px 16px;
      border-radius: 8px;
      margin: 10px 0;
      font-size: 13px;
      color: #166534;
      line-height: 1.45;
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
                <span class="brand-badge">Workshop Payment Confirmation</span>
                <h1 class="brand-title">71st Annual National Conference of IPHA</h1>
                <p class="brand-sub">IPHACON 2027 • Pre-Conference Workshop • 11th March 2027 • RIMS, Ranchi</p>
              </td>
            </tr>

            <!-- Hero Section -->
            <tr>
              <td class="hero">
                <h2>Workshop Registration Confirmed!</h2>
                <p>
                  Dear <strong>{{ $user->prefix ?? '' }} {{ $user->full_name ?? ($user->name ?? 'Delegate') }}</strong>, 
                  your registration and fee payment for the <strong>Pre-Conference CME Workshop</strong> at <strong>IPHACON 2027</strong> have been successfully confirmed!
                </p>
              </td>
            </tr>

            <!-- Delegate & Workshop Details Card -->
            <tr>
              <td class="card-cell">
                <table role="presentation" class="card" width="100%" cellspacing="0" cellpadding="0">
                  <tr>
                    <td class="card-header">🔬 Workshop &amp; Delegate Details</td>
                  </tr>
                  <tr>
                    <td class="card-body">
                      <table role="presentation" class="info-table" cellspacing="0" cellpadding="0">
                        <tr>
                          <td class="label">Delegate Name</td>
                          <td class="value">{{ $user->prefix ?? '' }} {{ $user->full_name ?? ($user->name ?? 'N/A') }}</td>
                        </tr>
                        <tr>
                          <td class="label">Event / Activity</td>
                          <td class="value" style="color: #059669;">Pre-Conference CME Workshop</td>
                        </tr>
                        <tr>
                          <td class="label">Registration No.</td>
                          <td class="value">
                            @if(!empty($registration->registration_number))
                              <span class="badge badge-reg">{{ $registration->registration_number }}</span>
                            @else
                              <span class="badge badge-reg">{{ $registration->acknowledgement_id ?? 'N/A' }}</span>
                            @endif
                          </td>
                        </tr>
                        @if(!empty($registration->designation))
                        <tr>
                          <td class="label">Designation</td>
                          <td class="value">{{ $registration->designation }}</td>
                        </tr>
                        @endif
                        @if(!empty($registration->institution))
                        <tr>
                          <td class="label">Institution</td>
                          <td class="value">{{ $registration->institution }}</td>
                        </tr>
                        @endif
                        <tr>
                          <td class="label">Venue</td>
                          <td class="value">Rajendra Institute of Medical Sciences (RIMS), Ranchi</td>
                        </tr>
                        <tr>
                          <td class="label">Workshop Date</td>
                          <td class="value">11th March 2027</td>
                        </tr>
                      </table>
                    </td>
                  </tr>
                </table>
              </td>
            </tr>

            <!-- Payment Breakdown Card -->
            <tr>
              <td class="card-cell">
                <table role="presentation" class="card" width="100%" cellspacing="0" cellpadding="0">
                  <tr>
                    <td class="card-header">💳 Payment &amp; Transaction Details</td>
                  </tr>
                  <tr>
                    <td class="card-body">
                      <table role="presentation" class="info-table" cellspacing="0" cellpadding="0">
                        <tr>
                          <td class="label">Workshop Fee</td>
                          <td class="value">₹{{ number_format((float)($cmeApp->cme_fee ?: 2000.00), 2) }}</td>
                        </tr>
                        <tr>
                          <td class="label">GST (18%)</td>
                          <td class="value">₹{{ number_format((float)($cmeApp->gst_amount ?: 360.00), 2) }}</td>
                        </tr>
                        <tr style="background-color: #f8fafc;">
                          <td class="label" style="font-weight: 700; color: #0f172a; padding: 10px 4px 10px 0;">Total Amount Paid</td>
                          <td class="value" style="font-size: 15px; color: #059669; padding: 10px 0;">
                            ₹{{ number_format((float)($payment->total_amount ?? ($cmeApp->total_amount ?: 2360.00)), 2) }}
                          </td>
                        </tr>
                        <tr>
                          <td class="label">Payment Mode</td>
                          <td class="value">{{ str_replace('_', ' ', $payment->payment_method ?? 'SBI_ePay') }}</td>
                        </tr>
                        <tr>
                          <td class="label">Transaction Reference</td>
                          <td class="value" style="font-family: monospace; font-size: 12.5px;">
                            {{ $payment->transaction_id ?? ($payment->gateway_transaction_id ?? ($cmeApp->transaction_id ?? 'N/A')) }}
                          </td>
                        </tr>
                        <tr>
                          <td class="label">Payment Date</td>
                          <td class="value">
                            {{ $payment->payment_date ? \Carbon\Carbon::parse($payment->payment_date)->format('d M, Y h:i A') : now()->format('d M, Y h:i A') }}
                          </td>
                        </tr>
                        <tr>
                          <td class="label">Status</td>
                          <td class="value">
                            <span class="badge badge-success">SUCCESS / CONFIRMED</span>
                          </td>
                        </tr>
                      </table>
                    </td>
                  </tr>
                </table>
              </td>
            </tr>

            <!-- Attached Receipt Notice -->
            <tr>
              <td class="card-cell">
                <div class="attachment-notice">
                  <strong>📎 Receipt Attached:</strong> 
                  Your official <em>Pre-Conference Workshop Payment Receipt cum Registration Slip</em> is attached to this email as a PDF document. Please keep it handy for on-spot verification.
                </div>
              </td>
            </tr>

            <!-- Action CTA Button -->
            <tr>
              <td align="center" style="padding: 20px 24px 25px;">
                <table role="presentation" cellspacing="0" cellpadding="0">
                  <tr>
                    <td align="center" style="border-radius: 25px;">
                      @php
                        $regNum = $registration->registration_number ?? ($registration->acknowledgement_id ?? $registration->id);
                        $receiptUrl = route('delgate.download.receipt', ['registration_number' => $regNum, 'type' => 'cme']);
                      @endphp
                      <a href="{{ $receiptUrl }}" class="btn" target="_blank">
                        Download Workshop Receipt PDF
                      </a>
                    </td>
                  </tr>
                </table>
                <div style="margin-top: 12px;">
                  <a href="{{ route('dashboard') }}" style="color: #059669; font-size: 13px; text-decoration: underline; font-weight: 600;">
                    Go to Delegate Dashboard →
                  </a>
                </div>
              </td>
            </tr>

            <!-- Footer Section -->
            <tr>
              <td class="footer">
                <p style="margin: 0 0 8px 0;">
                  This is an automated system confirmation from <strong>IPHACON 2027 Secretariat</strong>.
                </p>
                <p style="margin: 0 0 8px 0; color: #64748b;">
                  Conference Venue: <strong>Rajendra Institute of Medical Sciences (RIMS), Ranchi, Jharkhand - 834009</strong>
                </p>
                <p style="margin: 0; color: #94a3b8; font-size: 11px;">
                  For queries or assistance, contact: 
                  <a href="mailto:registration@iphacon2027.com" style="color: #059669; text-decoration: none;">registration@iphacon2027.com</a>
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
