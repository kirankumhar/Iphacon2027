<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="robots" content="noindex, nofollow">
<title>404 - Page Not Found | IPHACON 2027</title>
<link rel="icon" type="image/png" href="{{ asset('assets/img/logo/favicon.png') }}">
<link rel="shortcut icon" type="image/png" href="{{ asset('assets/img/logo/favicon.png') }}">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
:root{
  --navy:#0a1a33; --navy-2:#102848; --gold:#e0b45a; --teal:#2ec4b6;
  --text:#eaf0f8; --muted:#9db0c9;
}
*{box-sizing:border-box;margin:0;padding:0}
html,body{height:100%}
body{
  font-family:"Poppins","Roboto",system-ui,-apple-system,"Segoe UI",sans-serif;
  color:var(--text);
  background:
    radial-gradient(60vmax 60vmax at 85% -10%,rgba(46,196,182,.28),transparent 60%),
    radial-gradient(50vmax 50vmax at -10% 110%,rgba(224,180,90,.22),transparent 60%),
    linear-gradient(160deg,var(--navy),var(--navy-2));
  display:flex;align-items:center;justify-content:center;
  padding:24px;overflow:hidden;
}
.card{
  width:min(640px,100%);
  padding:clamp(28px,5vw,52px);
  border-radius:24px;
  background:rgba(255,255,255,.07);
  border:1px solid rgba(255,255,255,.16);
  backdrop-filter:blur(18px);-webkit-backdrop-filter:blur(18px);
  box-shadow:0 30px 80px rgba(0,0,0,.4), inset 0 1px 0 rgba(255,255,255,.18);
  text-align:center;
}
.brand{
  font-family:"Poppins",sans-serif;font-weight:700;
  font-size:1.05rem;letter-spacing:.08em;color:var(--gold);
  margin-bottom:12px;text-transform:uppercase;
}
.error-code{
  font-family:"Poppins",sans-serif;
  font-size:clamp(4.5rem,14vw,7rem);
  font-weight:800;
  line-height:1;
  letter-spacing:-0.03em;
  background:linear-gradient(135deg,var(--teal) 0%,var(--gold) 100%);
  -webkit-background-clip:text;
  -webkit-text-fill-color:transparent;
  margin-bottom:8px;
  display:inline-block;
  text-shadow:0 10px 30px rgba(46,196,182,.2);
}
h1{
  font-family:"Poppins",sans-serif;font-weight:700;
  font-size:clamp(1.5rem,4.5vw,2.1rem);line-height:1.2;margin-bottom:12px;
  letter-spacing:-0.02em;color:var(--text);
}
p{
  font-family:"Roboto","Poppins",sans-serif;color:var(--muted);
  line-height:1.6;font-size:0.96rem;max-width:48ch;
  margin:0 auto 24px auto;
}
.actions{
  display:flex;align-items:center;justify-content:center;
  gap:14px;flex-wrap:wrap;margin-bottom:24px;
}
.btn{
  font-family:"Poppins",sans-serif;font-weight:600;font-size:0.88rem;
  padding:10px 22px;border-radius:10px;text-decoration:none;
  display:inline-flex;align-items:center;gap:8px;
  transition:all .25s ease;cursor:pointer;border:none;
}
.btn-primary{
  background:linear-gradient(135deg,var(--teal),#1ca89b);
  color:#07212b;box-shadow:0 8px 20px rgba(46,196,182,.3);
}
.btn-primary:hover{
  transform:translateY(-2px);box-shadow:0 12px 25px rgba(46,196,182,.45);
  color:#03141a;
}
.btn-outline{
  background:rgba(255,255,255,.08);color:var(--text);
  border:1px solid rgba(255,255,255,.2);
}
.btn-outline:hover{
  background:rgba(255,255,255,.16);color:#ffffff;
  border-color:rgba(255,255,255,.35);transform:translateY(-2px);
}
.contact{font-size:.88rem;color:var(--muted);margin-bottom:0}
.contact a{color:var(--gold);text-decoration:none;border-bottom:1px solid rgba(224,180,90,.4);transition:all .2s ease}
.contact a:hover{color:var(--teal);border-color:var(--teal)}
</style>
</head>
<body>
<main class="card" role="main">
  <div class="brand">IPHACON 2027</div>
  <div class="error-code">404</div>
  <h1>Page Not Found</h1>
  <p>The page you are looking for might have been removed, had its name changed, or is temporarily unavailable.</p>
  <div class="actions">
    <a href="{{ url('/') }}" class="btn btn-primary">
      <i class="fas fa-home"></i> Back to Home
    </a>
    <a href="https://www.iphacon2027.com/index.php" class="btn btn-outline">
      <i class="fas fa-globe"></i> Conference Website
    </a>
  </div>
  <p class="contact">Need assistance? Write to <a href="mailto:iphacon2027@gmail.com">iphacon2027@gmail.com</a></p>
</main>
</body>
</html>
