<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>IPHACON 2027 Abstract Decision Update</title>

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
      color: #0288D1;
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
      width: 40%;
      font-weight: 500;
      text-align: left;
      padding-right: 8px;
    }
    .value {
      color: #0f172a;
      font-weight: 700;
      width: 60%;
      text-align: right;
      word-break: break-word;
    }
    .badge {
      display: inline-block;
      padding: 4px 12px;
      font-size: 11.5px;
      border-radius: 20px;
      font-weight: 700;
      text-transform: uppercase;
      white-space: nowrap;
    }
    .badge-success {
      background: #DCFFF0;
      color: #15803d;
    }
    .badge-danger {
      background: #fee2e2;
      color: #991b1b;
    }
    .badge-pending {
      background: #fef3c7;
      color: #b45309;
    }
    .badge-reg {
      background: #E0F2FE;
      color: #0288D1;
      font-family: monospace;
      font-size: 12.5px;
      padding: 4px 10px;
      word-break: break-all;
    }
    .notice-box {
      background-color: #f0f9ff;
      border: 1px solid #bae6fd;
      border-radius: 10px;
      padding: 16px 20px;
      text-align: left;
    }
    .notice-box p {
      font-size: 13.5px;
      margin: 0 0 6px 0;
      line-height: 1.5;
    }
    .notice-box p:last-child {
      margin-bottom: 0;
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
                <span class="brand-badge">Abstract Review Status</span>
                <h1 class="brand-title">71st Annual National Conference of IPHA</h1>
                <p class="brand-sub">IPHACON 2027 | 12th - 14th March 2027 | RIMS, Ranchi</p>
              </td>
            </tr>

            <!-- Hero Section -->
            <tr>
              <td class="hero">
                <h2>Abstract Review Update</h2>
                <p>Dear <strong>{{ $abstract->presenting_author_name }}</strong>,</p>
                <p style="margin-top: 6px;">The Scientific Committee of <strong>IPHACON 2027</strong> has completed the evaluation of your submitted abstract.</p>
              </td>
            </tr>

            <!-- Core Abstract Details Card -->
            <tr>
              <td class="card-cell">
                <table role="presentation" class="card" width="100%" cellspacing="0" cellpadding="0">
                  <tr>
                    <td class="card-header">📋 Decision Details</td>
                  </tr>
                  <tr>
                    <td class="card-body">
                      <table role="presentation" class="info-table" cellspacing="0" cellpadding="0">
                        <tr>
                          <td class="label">Acknowledgement ID</td>
                          <td class="value">
                            <span class="badge badge-reg">{{ $abstract->acknowledgement_id ?? 'N/A' }}</span>
                          </td>
                        </tr>
                        <tr>
                          <td class="label">Abstract Title</td>
                          <td class="value">{{ $abstract->abstract_title ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                          <td class="label">Assigned Presentation Mode</td>
                          <td class="value">{{ $abstract->presentation_mode ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                          <td class="label">Review Status</td>
                          <td class="value">
                            @if(strtolower($abstract->status) === 'accepted')
                              <span class="badge badge-success">✓ ACCEPTED</span>
                            @elseif(strtolower($abstract->status) === 'rejected')
                              <span class="badge badge-danger">✕ REJECTED</span>
                            @else
                              <span class="badge badge-pending">{{ strtoupper($abstract->status) }}</span>
                            @endif
                          </td>
                        </tr>
                      </table>
                    </td>
                  </tr>
                </table>
              </td>
            </tr>

            <!-- Reviewer Comments / Instructions Banner -->
            <tr>
              <td style="padding: 10px 24px 20px;">
                <div class="notice-box" style="{{ strtolower($abstract->status) === 'accepted' ? 'background-color: #f0fdf4; border-color: #bbf7d0;' : '' }}">
                  @if(strtolower($abstract->status) === 'accepted')
                    <p style="font-weight: 700; color: #15803d; font-size: 14px; margin-bottom: 6px;">🎉 Congratulations!</p>
                    <p style="color: #166534;">We are pleased to inform you that your abstract has been <strong>ACCEPTED</strong> for presentation at IPHACON 2027 in the <strong>{{ $abstract->presentation_mode }}</strong> category. Please check your delegate portal for detailed instructions on presentation submission guidelines.</p>
                  @elseif(strtolower($abstract->status) === 'rejected')
                    <p style="font-weight: 700; color: #991b1b; font-size: 14px; margin-bottom: 6px;">ℹ️ Abstract Decision Outcome</p>
                    <p style="color: #7f1d1d;">Thank you for your interest in presenting at IPHACON 2027. After careful review by the Scientific Committee, we regret to inform you that your abstract could not be accepted for presentation this year due to competitive slot limits.</p>
                  @else
                    <p style="font-weight: 700; color: #0288D1; font-size: 14px; margin-bottom: 6px;">ℹ️ Status Update</p>
                    <p>Your abstract status has been updated to: <strong>{{ $abstract->status }}</strong>.</p>
                  @endif

                  @if(!empty($abstract->review_comments))
                    <div style="margin-top: 10px; padding-top: 10px; border-top: 1px dashed #cbd5e1;">
                      <strong style="color: #334155; font-size: 12.5px;">Reviewer Comments:</strong>
                      <p style="color: #475569; font-style: italic; font-size: 13px; margin-top: 4px;">"{{ $abstract->review_comments }}"</p>
                    </div>
                  @endif
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
                  <strong>IPHACON 2027 Scientific Committee & Secretariat</strong>
                </div>
                <div style="margin-bottom: 8px;">
                  71st Annual National Conference of Indian Public Health Association<br>
                  Department of Community Medicine, RIMS, Ranchi, Jharkhand
                </div>
                <div>
                  Website: <a href="https://www.iphacon2027.com" target="_blank" style="color: #0288D1; text-decoration: underline;">www.iphacon2027.com</a>
                </div>
                <div style="margin-top: 12px; font-size: 11px; color: #94a3b8;">
                  If you have any questions, please contact conference support at <strong>iphacon2027@gmail.com</strong>.
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
