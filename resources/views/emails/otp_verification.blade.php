<!DOCTYPE html>
<html lang="en" xmlns:v="urn:schemas-microsoft-com:vml">

<head>
  <meta charset="utf-8">
  <meta name="x-apple-disable-message-reformatting">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Email Verification OTP - IPHACON 2027</title>

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
      max-width: 600px;
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
      font-size: 19px;
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
      padding: 30px 30px 15px;
      text-align: center;
    }
    .hero h2 {
      font-size: 22px;
      margin: 0 0 10px;
      color: #01579B;
      font-weight: 800;
    }
    .hero p {
      font-size: 14.5px;
      color: #475569;
      margin: 0;
      line-height: 1.5;
    }
    .otp-card {
      margin: 20px 30px;
      background: #F0F9FF;
      border: 2px dashed #0288D1;
      border-radius: 14px;
      padding: 24px;
      text-align: center;
    }
    .otp-label {
      font-size: 12px;
      font-weight: 700;
      color: #64748B;
      text-transform: uppercase;
      letter-spacing: 1px;
      margin-bottom: 8px;
    }
    .otp-code {
      font-family: 'Courier New', Courier, monospace;
      font-size: 36px;
      font-weight: 800;
      color: #01579B;
      letter-spacing: 8px;
      margin: 10px 0;
      display: inline-block;
    }
    .otp-timer {
      font-size: 12px;
      color: #D97706;
      font-weight: 600;
      margin-top: 6px;
    }
    .info-box {
      margin: 0 30px 24px;
      font-size: 13.5px;
      color: #475569;
      line-height: 1.6;
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
                    {{-- Left Logo --}}
                    <td align="left" width="33%">
                      @if(isset($message) && file_exists(public_path('assets/img/logo/logo.png')))
                        <img src="{{ $message->embed(public_path('assets/img/logo/logo.png')) }}" alt="IPHACON 2027" height="55" style="max-height: 55px; width: auto;" />
                      @else
                        <img src="{{ asset('assets/img/logo/logo.png') }}" alt="IPHACON 2027" height="55" style="max-height: 55px; width: auto;" />
                      @endif
                    </td>

                    {{-- Center Logo --}}
                    <td align="center" width="34%">
                      @if(isset($message) && file_exists(public_path('assets/img/logo/ipha_logo.png')))
                        <img src="{{ $message->embed(public_path('assets/img/logo/ipha_logo.png')) }}" alt="IPHA Logo" height="55" style="max-height: 55px; width: auto;" />
                      @else
                        <img src="{{ asset('assets/img/logo/ipha_logo.png') }}" alt="IPHA Logo" height="55" style="max-height: 55px; width: auto;" />
                      @endif
                    </td>

                    {{-- Right Logo --}}
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
                <h2>Email Verification OTP</h2>
                <p>Hello <strong>{{ $user->full_name ?? 'Delegate' }}</strong>, welcome to IPHACON 2027 registration.</p>
              </td>
            </tr>

            <!-- OTP Box Card -->
            <tr>
              <td>
                <div class="otp-card">
                  <div class="otp-label">Your One-Time Password (OTP)</div>
                  <div class="otp-code">{{ $otp }}</div>
                  <div class="otp-timer">⏱ Valid for 15 minutes only</div>
                </div>
              </td>
            </tr>

            <!-- Info Message -->
            <tr>
              <td class="info-box">
                <p style="margin: 0 0 10px 0;">Please enter this OTP code on the verification page to complete your registration process.</p>
                <p style="margin: 0; font-size: 12.5px; color: #94A3B8;">If you did not initiate this registration request, please ignore this email.</p>
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
              </td>
            </tr>
          </table>

        </td>
      </tr>
    </table>
  </div>
</body>

</html>
