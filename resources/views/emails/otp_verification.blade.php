<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Email Verification OTP - IPHACON 2027</title>

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
      max-width: 580px;
      margin: 0 auto;
      background: #ffffff;
      border-radius: 16px;
      overflow: hidden;
      box-shadow: 0 10px 30px rgba(2, 136, 209, 0.08);
      border: 1px solid #cbd5e1;
    }
    .brand-header {
      background: linear-gradient(135deg, #01579B 0%, #0288D1 50%, #00897B 100%);
      color: #ffffff;
      padding: 28px 24px;
      text-align: center;
    }
    .brand-badge {
      display: inline-block;
      background: rgba(255, 255, 255, 0.2);
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
      color: #e0f2fe;
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
      background: #F0F9FF;
      border: 2px dashed #0288D1;
      border-radius: 14px;
      padding: 22px;
      text-align: center;
    }
    .otp-label {
      font-size: 11.5px;
      font-weight: 700;
      color: #64748B;
      text-transform: uppercase;
      letter-spacing: 1px;
      margin-bottom: 6px;
    }
    .otp-code {
      font-family: 'Courier New', Courier, monospace;
      font-size: 38px;
      font-weight: 800;
      color: #01579B;
      letter-spacing: 8px;
      margin: 8px 0;
      display: inline-block;
    }
    .otp-timer {
      font-size: 12px;
      color: #D97706;
      font-weight: 600;
      margin-top: 4px;
    }
    .info-box {
      font-size: 13.5px;
      color: #475569;
      line-height: 1.6;
      text-align: center;
    }
    .footer {
      text-align: center;
      color: #64748b;
      font-size: 12px;
      padding: 20px 24px 26px;
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
            
            <!-- Text-based Header Banner (No Images Used) -->
            <tr>
              <td class="brand-header">
                <span class="brand-badge">Official Verification</span>
                <h1 class="brand-title">71st Annual National Conference of IPHA</h1>
                <p class="brand-sub">IPHACON 2027 | 12th - 14th March 2027 | RIMS, Ranchi</p>
              </td>
            </tr>

            <!-- Hero Section -->
            <tr>
              <td class="hero">
                <h2>Your Verification OTP is</h2>
                <p>Hello <strong>{{ $user->full_name ?? 'Delegate' }}</strong>, Welcome to IPHACON 2027 Registration.</p>
              </td>
            </tr>

            <!-- OTP Box Card -->
            <tr>
              <td style="padding: 10px 24px 20px;">
                <div class="otp-card">
                  <div class="otp-label">Your One-Time Password (OTP)</div>
                  <div class="otp-code">{{ $otp }}</div>
                  <div class="otp-timer">⏱ Valid for 15 minutes only</div>
                </div>
              </td>
            </tr>

            <!-- Info Message -->
            <tr>
              <td style="padding: 0 24px 24px;">
                <div class="info-box">
                  <p style="margin: 0 0 8px 0;">Please enter this OTP code on the verification page to complete your registration process.</p>
                  <p style="margin: 0; font-size: 12px; color: #94A3B8;">If you did not initiate this registration request, please ignore this email.</p>
                </div>
              </td>
            </tr>

            <!-- Footer -->
            <tr>
              <td class="footer">
                <div style="margin-bottom: 4px;">
                  <strong>IPHACON 2027 Organizing Committee</strong>
                </div>
                <div style="margin-bottom: 6px;">
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
