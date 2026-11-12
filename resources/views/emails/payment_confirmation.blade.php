<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>IPHACON 2027 Payment Confirmation & Official Receipt</title>

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
      background: linear-gradient(135deg, #065f46 0%, #059669 45%, #0284c7 100%);
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
      padding: 28px 24px 12px;
      text-align: center;
    }
    .success-icon-wrap {
      display: inline-block;
      width: 56px;
      height: 56px;
      line-height: 56px;
      border-radius: 50%;
      background: #dcfce7;
      color: #16a34a;
      font-size: 28px;
      font-weight: bold;
      margin-bottom: 12px;
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
    .reg-highlight-box {
      margin: 16px 24px 8px;
      padding: 16px;
      background: #f0fdf4;
      border: 1.5px dashed #86efac;
      border-radius: 12px;
      text-align: center;
    }
    .reg-highlight-label {
      font-size: 11.5px;
      text-transform: uppercase;
      color: #15803d;
      font-weight: 700;
      letter-spacing: 0.5px;
      margin-bottom: 4px;
    }
    .reg-highlight-number {
      font-size: 22px;
      font-weight: 800;
      color: #166534;
      font-family: monospace;
      letter-spacing: 1.5px;
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
    .card-header-blue {
      color: #0284c7;
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
      border: 1px solid #86efac;
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
      background: linear-gradient(135deg, #059669 0%, #065f46 100%);
      color: #ffffff !important;
      border-radius: 25px;
      font-weight: 700;
      font-size: 14px;
      box-shadow: 0 4px 15px rgba(5, 150, 105, 0.3);
      text-transform: uppercase;
      letter-spacing: 0.5px;
      text-decoration: none;
    }
    .btn-secondary {
      background: #f1f5f9;
      color: #334155 !important;
      border: 1px solid #cbd5e1;
      box-shadow: none;
    }
    .footer {
      text-align: center;
      color: #64748b;
      font-size: 12px;
      padding: 20px 24px 30px;
      background: #f8fafc;
      border-top: 1px solid #e2e8f0;
      line-height: 1.6;
    }
    .footer strong {
      color: #0f172a;
    }
  </style>
</head>

<body style="margin:0; padding:0; background-color:#f0f4f8;">
  @php
    $user = $registration->user;
    $pay = $payment ?? ($registration->conferencePayment ?? $registration->latestPayment);
    $isForeign = ($registration->delegate_type === 'International');
    $currency = $pay?->currency ?: ($isForeign ? 'USD' : 'INR');
    $currencySymbol = ($currency === 'USD') ? '$' : '₹';

    if ($pay) {
        $delFee = (float)($pay->delegate_category_fee ?? 0);
        $cmeFee = (float)($pay->cme_fee ?? 0);
        $accFee = (float)($pay->accompanying_persons_fee ?? 0);
        $gstAmt = (float)($pay->gst_amount ?? 0);
        $totalPaid = (float)($pay->total_amount ?? 0);
        $payDate = $pay->payment_date ? \Carbon\Carbon::parse($pay->payment_date)->format('d M, Y h:i A') : ($pay->created_at ? \Carbon\Carbon::parse($pay->created_at)->format('d M, Y h:i A') : now()->format('d M, Y h:i A'));
        $payMethod = $pay->payment_method ?: ($pay->gateway_transaction_id ? 'SBI_ePay' : ($pay->payment_receipt_path ? 'QR_Code' : 'Online'));
        $txnId = $pay->transaction_id ?? ($pay->gateway_transaction_id ?? 'N/A');
    } else {
        $delFee = (float)($registration->delegate_fee ?? ($registration->delegateCategory->indian_fee ?? 0));
        $cmeFee = (float)($registration->cme_fee ?? 0);
        $accFee = (float)($registration->accompanying_fee ?? 0);
        $gstAmt = (float)($registration->gst_amount ?? round(($delFee + $cmeFee + $accFee) * 0.18, 2));
        $totalPaid = (float)($registration->total_amount ?: ($delFee + $cmeFee + $accFee + $gstAmt));
        $payDate = now()->format('d M, Y h:i A');
        $payMethod = 'SBI_ePay';
        $txnId = 'CONFIRMED';
    }

    $methodTitle = match(strtoupper($payMethod)) {
        'SBI_EPAY' => 'Online Payment Gateway (SBI ePay)',
        'QR_CODE'  => 'UPI QR Code / Direct Bank Transfer',
        'OFFLINE'  => 'Offline Bank Transfer',
        default    => str_replace('_', ' ', $payMethod),
    };

    $chosenTopic = $registration->pre_conference_topic 
        ?: ($registration->cmeApplication?->pre_conference_topic ?? null);
    $hasCme = ($cmeFee > 0) || $registration->isCmeApproved() || (bool)$chosenTopic;
  @endphp

  <div class="wrapper">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0">
      <tr>
        <td align="center">

          <table role="presentation" class="container" cellspacing="0" cellpadding="0" width="100%">
            
            <!-- Brand Header Banner -->
            <tr>
              <td class="brand-header">
                <span class="brand-badge">Official Payment Receipt & Confirmation</span>
                <h1 class="brand-title">71st Annual National Conference of IPHA</h1>
                <p class="brand-sub">IPHACON 2027 | 12th - 14th March 2027 | RIMS, Ranchi</p>
              </td>
            </tr>

            <!-- Hero Section -->
            <tr>
              <td class="hero">
                <div class="success-icon-wrap">✓</div>
                <h2>Payment Received Successfully!</h2>
                <p>Dear <strong>{{ $user?->prefix ?? '' }} {{ $user?->full_name ?? 'Delegate' }}</strong>, thank you for completing your payment for <strong>IPHACON 2027</strong>. Your payment has been received and verified, and your conference registration is officially confirmed.</p>
              </td>
            </tr>

            <!-- Official Registration Number Highlight Box -->
            <tr>
              <td>
                <div class="reg-highlight-box">
                  <div class="reg-highlight-label">Official Registration Number</div>
                  <div class="reg-highlight-number">{{ $registration->registration_number ?? ($registration->acknowledgement_id ?? 'IPHACON2027') }}</div>
                  <div style="font-size: 11px; color: #475569; margin-top: 4px;">
                    Please quote this number for all conference communications, badge collection & on-spot entry.
                  </div>
                </div>
              </td>
            </tr>

            <!-- Payment Details Card (Fintech Style) -->
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
                          <td class="label" style="font-size: 14px; font-weight: 700; color: #065f46;">Total Amount Paid</td>
                          <td class="value" style="font-size: 17px; font-weight: 800; color: #16a34a;">
                            {{ $currencySymbol }}{{ number_format($totalPaid, 2) }} {{ $currency }}
                          </td>
                        </tr>
                        <tr>
                          <td class="label">Payment Status</td>
                          <td class="value">
                            <span class="badge badge-success">✓ SUCCESS / PAID</span>
                          </td>
                        </tr>
                        <tr>
                          <td class="label">Transaction Reference</td>
                          <td class="value"><span style="font-family: monospace; font-size: 13px;">{{ $txnId }}</span></td>
                        </tr>
                        @if($pay?->gateway_transaction_id && $pay->gateway_transaction_id !== $txnId)
                        <tr>
                          <td class="label">Gateway Order ID</td>
                          <td class="value"><span style="font-family: monospace; font-size: 12.5px; color: #64748b;">{{ $pay->gateway_transaction_id }}</span></td>
                        </tr>
                        @endif
                        <tr>
                          <td class="label">Payment Mode</td>
                          <td class="value">{{ $methodTitle }}</td>
                        </tr>
                        <tr>
                          <td class="label">Payment Timestamp</td>
                          <td class="value">{{ $payDate }}</td>
                        </tr>
                      </table>
                    </td>
                  </tr>
                </table>
              </td>
            </tr>

            <!-- Registration & Fee Breakdown Card -->
            <tr>
              <td class="card-cell">
                <table role="presentation" class="card" width="100%" cellspacing="0" cellpadding="0">
                  <tr>
                    <td class="card-header card-header-blue">📋 Registration &amp; Fee Breakdown</td>
                  </tr>
                  <tr>
                    <td class="card-body">
                      <table role="presentation" class="info-table" cellspacing="0" cellpadding="0">
                        <tr>
                          <td class="label">Delegate Category</td>
                          <td class="value">{{ $registration->delegateCategory?->category_name ?? ($isForeign ? 'Foreign Delegate' : 'Indian Delegate') }}</td>
                        </tr>
                        @if($registration->acknowledgement_id)
                        <tr>
                          <td class="label">Acknowledgement ID</td>
                          <td class="value"><span class="badge badge-reg">{{ $registration->acknowledgement_id }}</span></td>
                        </tr>
                        @endif

                        @if($isForeign)
                          <tr>
                            <td class="label">Foreign Delegate Fee</td>
                            <td class="value">{{ $currencySymbol }}{{ number_format($delFee ?: $totalPaid, 2) }} {{ $currency }}</td>
                          </tr>
                        @else
                          @php
                            $grossSub = $delFee + $cmeFee + $accFee;
                            $expectedTot = round($grossSub * 1.18, 2);
                            $festiveDiscAmt = ($expectedTot > $totalPaid) ? round($grossSub * 0.05, 2) : 0;
                          @endphp
                          @if($delFee > 0)
                          <tr>
                            <td class="label">Delegate Fee (Excl. GST)</td>
                            <td class="value">₹{{ number_format($delFee, 2) }}</td>
                          </tr>
                          @endif

                          @if($hasCme)
                          <tr>
                            <td class="label">
                              Pre-Conf Workshop (CME)
                              @if($chosenTopic)
                                <div style="font-size: 11px; color: #0284c7; font-weight: normal; margin-top: 2px;">
                                  <strong>Topic:</strong> {{ $chosenTopic }}
                                </div>
                              @endif
                            </td>
                            <td class="value">₹{{ number_format($cmeFee ?: 2000, 2) }}</td>
                          </tr>
                          @endif

                          @if($accFee > 0)
                          <tr>
                            <td class="label">Accompanying Persons ({{ $registration->accompanying_persons ?? 0 }})</td>
                            <td class="value">₹{{ number_format($accFee, 2) }}</td>
                          </tr>
                          @endif

                          @if($festiveDiscAmt > 0)
                          <tr>
                            <td class="label" style="color: #16A34A; font-weight: 600;">Festive Benefit Discount (5%)</td>
                            <td class="value" style="color: #16A34A; font-weight: 600;">- ₹{{ number_format($festiveDiscAmt, 2) }}</td>
                          </tr>
                          @endif

                          <tr>
                            <td class="label">GST Amount (18%)</td>
                            <td class="value" style="color: #d97706;">+ ₹{{ number_format($gstAmt, 2) }}</td>
                          </tr>
                        @endif

                        <tr style="background: #f0fdf4;">
                          <td class="label" style="font-weight: 800; color: #166534; padding: 10px 0;">Total Paid (Incl. GST)</td>
                          <td class="value" style="font-size: 16px; font-weight: 800; color: #15803d; padding: 10px 0;">
                            {{ $currencySymbol }}{{ number_format($totalPaid, 2) }} {{ $currency }}
                          </td>
                        </tr>
                      </table>
                    </td>
                  </tr>
                </table>
              </td>
            </tr>

            <!-- PDF Attachment Callout -->
            <tr>
              <td style="padding: 10px 24px 18px;">
                <div style="background-color: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 10px; padding: 14px 16px; text-align: center;">
                  <p style="color: #166534; font-size: 13.5px; margin: 0; font-weight: 600; line-height: 1.5;">
                    📎 <strong>Official Receipt PDF Attached:</strong> Your official computer-generated <strong>Registration &amp; Payment Receipt PDF</strong> is attached to this email. You may also download it anytime from the portal.
                  </p>
                </div>
              </td>
            </tr>

            <!-- Action Buttons -->
            <tr>
              <td align="center" style="padding: 4px 24px 24px;">
                <table role="presentation" cellspacing="0" cellpadding="0" style="margin: 0 auto;">
                  <tr>
                    <td align="center" style="padding-right: 8px;">
                      <a href="{{ url('/login') }}" class="btn" target="_blank">Access Delegate Portal</a>
                    </td>
                  </tr>
                </table>
              </td>
            </tr>

            <!-- Footer Section -->
            <tr>
              <td class="footer">
                <p style="margin: 0 0 6px 0; font-weight: 700; color: #0f172a;">IPHACON 2027 Organizing Committee</p>
                <p style="margin: 0 0 4px 0;">Department of Community Medicine, Rajendra Institute of Medical Sciences (RIMS)</p>
                <p style="margin: 0 0 8px 0;">Bariatu, Ranchi - 834009, Jharkhand, India</p>
                <p style="margin: 0; font-size: 11.5px; color: #64748b;">
                  Need assistance? Email: <a href="mailto:registration@iphacon2027.com" style="color: #0284c7; text-decoration: none; font-weight: 600;">registration@iphacon2027.com</a> | Web: <a href="https://www.iphacon2027.com" style="color: #0284c7; text-decoration: none; font-weight: 600;">www.iphacon2027.com</a>
                </p>
                <p style="margin: 8px 0 0 0; font-size: 11px; color: #94a3b8;">
                  This is an automated payment confirmation receipt. Please retain this email for conference verification.
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

