<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="referrer" content="unsafe-url">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Redirecting to SBI ePay...</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: #f8fafc;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
        }
        .redirect-box {
            background: #ffffff;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            text-align: center;
            max-width: 420px;
        }
        .spinner {
            width: 48px;
            height: 48px;
            border: 4px solid #e2e8f0;
            border-top: 4px solid #2563eb;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin: 0 auto 20px;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        h2 { font-size: 20px; color: #1e293b; margin-bottom: 8px; }
        p { color: #64748b; font-size: 14px; margin-bottom: 20px; }
        .btn {
            display: inline-block;
            background: #2563eb;
            color: #fff;
            padding: 10px 24px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 600;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="redirect-box">
        <div class="spinner"></div>
        <h2>Connecting to SBI ePay...</h2>
        <p>Please wait while we securely redirect you to State Bank of India Payment Gateway.</p>
        <p><small>Do not refresh or close this window.</small></p>
        <a id="redirectLink" href="{{ $transactionUrl }}" class="btn" style="display:none;">Click here if not redirected automatically</a>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var targetUrl = {!! json_encode($transactionUrl) !!};
            if (targetUrl) {
                setTimeout(function() {
                    window.location.replace(targetUrl);
                }, 200);

                setTimeout(function() {
                    var btn = document.getElementById('redirectLink');
                    if (btn) {
                        btn.style.display = 'inline-block';
                    }
                }, 3000);
            }
        });
    </script>
</body>
</html>
