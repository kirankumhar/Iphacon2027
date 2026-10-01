<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <title>IPHACON 2027 – Under Maintenance</title>
    <link rel="icon" type="image/png" href="{{ asset('assets/img/logo/favicon.png') }}">
    <link rel="shortcut icon" type="image/png" href="{{ asset('assets/img/logo/favicon.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&family=Roboto:wght@300;400;500;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/css/503.css') }}">
</head>

<body>

    <main class="card" role="main">
        <div class="brand">IPHACON 2027</div>
        <h1>We're making the site better. Back shortly.</h1>
        <p>The IPHACON 2027 website is down for scheduled maintenance. Registration and conference details will be
            available again as soon as we're done.</p>
        <div class="bar" aria-hidden="true"><span></span></div>
        <p class="contact">Need help right now? Write to <a
                href="mailto:iphacon2027@gmail.com">iphacon2027@gmail.com</a></p>
    </main>
    <script>
        // Automatic background status check to reload when portal is back online
        setInterval(function() {
            fetch(window.location.href, {
                    method: 'HEAD',
                    cache: 'no-store'
                })
                .then(function(res) {
                    if (res.status === 200) {
                        window.location.reload();
                    }
                })
                .catch(function() {});
        }, 30000);
    </script>
</body>

</html>
