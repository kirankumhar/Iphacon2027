<!DOCTYPE html>
<html lang="en" xmlns:v="urn:schemas-microsoft-com:vml">

<head>
  <meta charset="utf-8">
  <meta name="x-apple-disable-message-reformatting">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Registration Confirmation</title>

  <style>
    /* Base Resets */
    body,
    table,
    td,
    a {
      font-family: 'Segoe UI', Roboto, Arial, sans-serif !important;
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

    /* Layout */
    .wrapper {
      width: 100%;
      background: #f4f6f8;
      padding: 24px 0;
    }

    .container {
      width: 100%;
      max-width: 640px;
      margin: 0 auto;
      background: #ffffff;
      border-radius: 12px;
      overflow: hidden;
      box-shadow: 0 8px 24px rgba(27, 39, 51, 0.08);
    }

    /* Header */
    .brand-header {
      background: linear-gradient(135deg, #2e3192, #4a5bcc);
      color: #ffffff;
      padding: 24px;
      text-align: center;
    }

    .brand-logo {
      height: 48px;
      margin: 0 auto 10px;
    }

    .brand-title {
      font-size: 18px;
      font-weight: 700;
      margin: 0;
    }

    .brand-sub {
      font-size: 12px;
      opacity: 0.85;
      margin: 4px 0 0;
    }

    /* Hero */
    .hero {
      padding: 18px 24px 0;
      text-align: center;
    }

    .hero h1 {
      font-size: 20px;
      margin: 6px 0 2px;
      color: #111827;
    }

    .hero p {
      font-size: 13px;
      color: #6b7280;
      margin: 4px 0 0;
    }

    /* Card */
    .card {
      margin: 16px 24px;
      border: 1px solid #e5e7eb;
      border-radius: 10px;
      overflow: hidden;
    }

    .card-header {
      background: #f8fafc;
      padding: 10px 14px;
      font-weight: 700;
      color: #111827;
      font-size: 13px;
    }

    .card-body {
      padding: 14px;
    }

    /* Definition rows */
    .row {
      width: 100%;
    }

    .row td {
      padding: 6px 0;
      vertical-align: top;
      font-size: 13px;
    }

    .label {
      color: #6b7280;
      width: 42%;
    }

    .value {
      color: #111827;
      font-weight: 600;
      width: 58%;
    }

    /* Divider */
    .divider {
      height: 1px;
      background: #e5e7eb;
      margin: 16px 24px;
    }

    /* Button */
    .btn {
      display: inline-block;
      padding: 12px 18px;
      background: #2e3192;
      color: #ffffff;
      border-radius: 8px;
      font-weight: 700;
      font-size: 14px;
    }

    .btn:hover {
      background: #25286f;
    }

    /* Footer */
    .footer {
      text-align: center;
      color: #6b7280;
      font-size: 12px;
      padding: 18px 24px 28px;
    }

    .muted {
      color: #6b7280;
      font-size: 12px;
    }

    /* Status badge */
    .badge {
      display: inline-block;
      padding: 3px 8px;
      font-size: 12px;
      border-radius: 9999px;
      font-weight: 700;
    }

    .badge-success {
      background: #ecfdf5;
      color: #059669;
      border: 1px solid #a7f3d0;
    }

    .badge-pending {
      background: #fff7ed;
      color: #c2410c;
      border: 1px solid #fed7aa;
    }
  </style>
</head>

<body style="margin:0; padding:0; background:#f4f6f8;">
  <div class="wrapper">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0">
      <tr>
        <td align="center">

          <table role="presentation" class="container" cellspacing="0" cellpadding="0" width="100%">
            <!-- Brand Header -->
            <tr>
              <td class="brand-header">
                @if(file_exists(public_path('shared/user/images/ismm_logo.png')))
                <img class="brand-logo" src="{{ asset('shared/user/images/ismm_logo.png') }}" alt="Brand">
                @endif
                <p class="brand-title">{{ config('app.name') }}</p>
              </td>
            </tr>

            <!-- Hero -->
            <tr>
              <td class="hero">
                <h1>Registration Confirmed</h1>
                <p>Hello {{ $registration->user->prefix }} {{ $registration->user->full_name }}, thank you for registering.</p>
              </td>
            </tr>

            <!-- Core Details -->
            <tr>
              <td>
                <table role="presentation" class="card" width="100%" cellspacing="0" cellpadding="0">
                  <tr>
                    <td class="card-header">Registration Summary</td>
                  </tr>
                  <tr>
                    <td class="card-body">
                      <table role="presentation" width="100%" cellspacing="0" cellpadding="0">
                        <tr class="row">
                          <td class="label">Registration No.</td>
                          <td class="value">{{ $registration->registration_number ?? '—' }}</td>
                        </tr>
                        <tr class="row">
                          <td class="label">Delegate Type</td>
                          <td class="value">{{ $registration->delegate_type ?? '—' }}</td>
                        </tr>
                        <tr class="row">
                          <td class="label">Category</td>
                          <td class="value">{{ $registration->delegateCategory->category_name ?? '—' }}</td>
                        </tr>
                        <tr class="row">
                          <td class="label">Country</td>
                          <td class="value">{{ $registration->country->country_name ?? '—' }}</td>
                        </tr>
                        <tr class="row">
                          <td class="label">Payment Status</td>
                          <td class="value">
                            @php $pstatus = $registration->latestPayment->payment_status ?? 'Pending'; @endphp
                            @if(strtolower($pstatus) === 'success')
                            <span class="badge badge-success">PAID</span>
                            @else
                            <span class="badge badge-pending">{{ strtoupper($pstatus) }}</span>
                            @endif
                          </td>
                        </tr>
                        @if($registration->latestPayment?->transaction_id)
                        <tr class="row">
                          <td class="label">Transaction ID</td>
                          <td class="value">{{ $registration->latestPayment->transaction_id }}</td>
                        </tr>
                        @endif
                      </table>
                    </td>
                  </tr>
                </table>
              </td>
            </tr>

            <!-- Amounts -->
            <tr>
              <td>
                <table role="presentation" class="card" width="100%" cellspacing="0" cellpadding="0">
                  <tr>
                    <td class="card-header">Fee Details</td>
                  </tr>
                  <tr>
                    <td class="card-body">
                      <table role="presentation" width="100%" cellspacing="0" cellpadding="0">
                        <tr class="row">
                          <td class="label">Delegate Fee</td>
                          <td class="value">
                            @if ($registration->delegate_type === 'International')
                            ${{ number_format($registration->latestPayment->delegate_category_fee ?? 175, 2) }} USD
                            @else
                            ₹{{ number_format($registration->delegateCategory->indian_fee ?? 0) }} INR
                            @endif
                          </td>
                        </tr>

                        @if(($registration->accompanying_persons ?? 0) > 0 && $registration->delegate_type === 'Indian')
                        <tr class="row">
                          <td class="label">Accompanying Persons</td>
                          <td class="value">₹{{ number_format(($registration->accompanying_persons ?? 0) * 4000) }} INR</td>
                        </tr>
                        @endif

                        @if(($registration->participate_in_cme ?? false) && $registration->delegate_type === 'Indian')
                        <tr class="row">
                          <td class="label">CME/Workshop</td>
                          <td class="value">₹1,000.00 INR</td>
                        </tr>
                        @endif

                        <tr class="row">
                          <td class="label">Total</td>
                          <td class="value">
                            @if ($registration->delegate_type === 'International')
                            ${{ number_format($registration->latestPayment->total_amount ?? 175, 2) }} USD
                            @else
                            ₹{{ number_format($registration->calculateTotalAmount()) }}.00 INR
                            @endif
                          </td>
                        </tr>
                      </table>
                    </td>
                  </tr>
                </table>
              </td>
            </tr>

            <!-- Receipt note -->
            <tr>
              <td class="divider"></td>
            </tr>
            <tr>
              <td style="padding: 0 24px;">
                @php $paid = strtolower($registration->latestPayment->payment_status ?? 'pending') === 'success'; @endphp
                @if($paid)
                <p class="muted" style="margin: 0;">
                  A PDF copy of your Registration Confirmation cum Payment Receipt is attached to this email.
                </p>
                @else
                <p class="muted" style="margin: 0;">
                  Your payment is currently pending/under verification. Once verified, the receipt PDF will be available for download.
                </p>
                @endif
              </td>
            </tr>

            <!-- Footer -->
            <tr>
              <td class="footer">
                <div style="margin-bottom: 6px;">
                  {{ config('app.name') }}
                </div>
                <div style="margin-bottom: 2px;">
                  www.iphacon2027.com
                </div>
                <div class="muted" style="margin-top: 8px;">
                  If you need help, reply to this email or contact support at {{ config('mail.from.address') }}.
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