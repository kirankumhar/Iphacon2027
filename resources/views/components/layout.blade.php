<!DOCTYPE html>
<html lang="en">
<head>
    
    <!-- Character Encoding -->
    <meta charset="UTF-8">

    <!-- Browser Compatibility -->
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <!-- Responsive Viewport -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- SEO Meta Tags -->
    <meta name="description" content="Official website of the 71st Annual National Conference of the Indian Public Health Association (IPHACON 2027).">
    <meta name="keywords" content="IPHACON 2027, Indian Public Health Association, Public Health Conference, IPHA, Ranchi">
    <meta name="author" content="IPHACON 2027 Organizing Committee">
    <meta name="robots" content="index, follow">
    <meta name="theme-color" content="#0d6efd">

    <!-- Open Graph -->
    <meta property="og:title" content="71st Annual National Conference of the Indian Public Health Association (IPHACON 2027)">
    <meta property="og:description" content="Official website of IPHACON 2027.">
    <meta property="og:type" content="website"> 

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/img/logo/favicon.png') }}">

    <!-- CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/all-fontawesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/animate.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/magnific-popup.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/owl.carousel.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">

    <title>{{ $title ?? 'Online Registration | 71st Annual National Conference of the Indian Public Health Association (IPHACON 2027) | Ranchi | Jharkhand' }}</title>
</head>

<body class="home-3">
    <div class="header-top conference-topbar"
     role="banner"
     aria-label="Conference Information Bar"
     title="71st Annual National Conference of the Indian Public Health Association">

    <div class="container">
        <div class="row align-items-center">

            <!-- Conference Title -->
            <div class="col-lg-7 col-md-6 col-12">
                <div class="conference-title"
                     aria-label="Conference Title"
                     title="71st Annual National Conference of the Indian Public Health Association">

                    <i class="far fa-stethoscope"
                       aria-hidden="true"
                       title="Public Health Conference Icon"></i>

                    <span>
                        71<sup>st</sup> Annual National Conference of the Indian Public Health Association
                    </span>

                </div>
            </div>

            <!-- Venue -->
            <div class="col-lg-5 col-md-6 col-12">
                <div class="conference-right"
                     aria-label="Conference Venue and Registration">

                    <div class="conference-venue"
                         aria-label="Conference Venue"
                         title="Conference Venue: Rajendra Institute of Medical Sciences, Ranchi, Jharkhand, India">

                        <i class="far fa-location-dot"
                           aria-hidden="true"
                           title="Location Icon"></i>

                        <span>
                            RIMS, Ranchi, Jharkhand, India
                        </span>

                    </div>

                </div>
            </div>

        </div>
    </div>
</div>
        <div class="main-navigation" role="navigation" aria-label="Main Navigation Menu">
    <nav class="navbar navbar-expand-lg" aria-label="Primary Navigation">
        <div class="container position-relative">

            <a href="{{ url('/') }}"
               class="navbar-brand"
               title="IPHACON 2027 Home Page"
               aria-label="Go to IPHACON 2027 Home Page">

                <img src="{{ asset('assets/img/logo/logo.png') }}"
                     alt="IPHACON 2027 Official Conference Logo"
                     title="71st Annual National Conference of the Indian Public Health Association">
            </a>

               <div class="mobile-menu-right">

    <div class="mobile-ipha-logo">
        <img src="{{ asset('assets/img/logo/ipha_logo.png') }}"
             alt="Indian Public Health Association Official Logo"
             title="Indian Public Health Association">
    </div>

    <div class="mobile-rims-logo">
        <img src="{{ asset('assets/img/logo/rimslogo.png') }}"
             alt="Rajendra Institute of Medical Sciences Ranchi Official Logo"
             title="Rajendra Institute of Medical Sciences (RIMS), Ranchi">
    </div>

    <button class="navbar-toggler"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#main_nav"
            aria-controls="main_nav"
            aria-expanded="false"
            aria-label="Toggle Main Navigation Menu"
            title="Open or Close Navigation Menu">

        <span class="navbar-toggler-mobile-icon">
            <i class="far fa-bars"
               aria-hidden="true"
               title="Menu Icon"></i>
        </span>

    </button>

</div>

            <div class="collapse navbar-collapse" id="main_nav">

                <ul class="navbar-nav ms-auto me-3 align-items-center" role="menubar">
                    <li class="nav-item me-2" role="none">
                        <a href="{{ route('register') }}"
                           class="register-btn"
                           title="Register for IPHACON 2027 Conference"
                           aria-label="Register Now for IPHACON 2027"
                           role="menuitem">
                            <i class="far fa-user-plus"></i> Register
                        </a>
                    </li>

                    <li class="nav-item" role="none">
                        <a href="{{ route('login') }}"
                           class="login-btn"
                           title="Login to IPHACON 2027 Portal"
                           aria-label="Login to IPHACON 2027 Portal"
                           role="menuitem">
                            <i class="far fa-sign-in-alt"></i> Login
                        </a>
                    </li>
                </ul>

               <div class="nav-right">
                    <div class="header-logos">

                        <div class="ipha-nav-logo">
                            <img src="{{ asset('assets/img/logo/ipha_logo.png') }}"
                                alt="Indian Public Health Association Official Logo"
                                title="Indian Public Health Association">
                        </div>

                        <div class="rims-nav-logo">
                            <img src="{{ asset('assets/img/logo/rimslogo.png') }}"
                                alt="Rajendra Institute of Medical Sciences Ranchi Official Logo"
                                title="Rajendra Institute of Medical Sciences (RIMS), Ranchi">
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </nav>
</div>
    </header>
    <main class="main">
        <div class="site-breadcrumb">
            <img src="{{ asset('assets/img/htop_img/mast_iphacon2027_online-reg.jpg') }}" alt="Online Registration" title="Online Registration" class="breadcrumb-banner-img">
            <div class="breadcrumb-content">
                <h5 class="breadcrumb-title">Online Registration</h5>
            </div>
        </div>

        <div class="py-4">
            {{ $slot }}
        </div>
    </main>
      <footer class="footer-area">
        <div class="footer-shape">
            <img src="{{ asset('images/join_conf_img.png') }}" alt="">
        </div>
        <div class="footer-widget">
            <div class="container">
                <div class="row footer-widget-wrapper pt-50 pb-0">
                    <div class="col-md-6 col-lg-4">
                        <div class="footer-widget-box about-us">
                            <h4 class="footer-widget-title">IPHACON 2027</h4>
                            <p class="mb-3">
                                71<sup>st</sup> Annual National Conference of the Indian Public Health Association (IPHACON 2027)
                            </p>

                            <p>
                                Theme: Synergizing AI and One Health: Precision Analytics for Zoonotic Control,
                                Nutritional Equity and Environmental Resilience.
                            </p>        
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-4">
                        <div class="footer-widget-box list">
                            <h4 class="footer-widget-title">Important Dates</h4>
                            <ul class="footer-list">
                                <li> <i class="fas fa-calendar-alt"></i> Abstract Opens - 01 Aug 2026 </li>
                                <li> <i class="fas fa-calendar-alt"></i> Abstract Closes - 31 Oct 2026 </li>
                                <li> <i class="fas fa-calendar-alt"></i> Early Bird Ends - 30 Dec 2026 </li>
                                <li> <i class="fas fa-calendar-alt"></i> Registration Ends - 31 Jan 2027 </li>
                                <li> <i class="fas fa-calendar-alt"></i> Workshop - 11 Mar 2027 </li>
                                <li> <i class="fas fa-calendar-alt"></i> Conference - 12–14 Mar 2027 </li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-4">
                        <div class="footer-widget-box list">
                            <h4 class="footer-widget-title">Conference Secretariat</h4>
                            <ul class="footer-contact">
                                <li> <i class="far fa-building"></i> Department of Community Medicine, Rajendra Institute of Medical Sciences (RIMS) </li>
                                <li> <i class="far fa-map-marker-alt"></i> Bariatu, Ranchi, Jharkhand - 834009, India </li>
                                <li> <a href="tel:+919097736688"> <i class="far fa-phone"></i> +91-9097736688 </a> </li>
                                <li> <a href="mailto:iphacon2027@gmail.com"> <i class="far fa-envelope"></i> iphacon2027@gmail.com </a> </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="copyright">
                <div class="row">
                    <div class="col-md-6 align-self-center">
                        <p class="copyright-text">
                            &copy; Copyright <?php echo date("Y"); ?> <a href="{{ url('/') }}"> IPHACON 2027 </a> All Rights Reserved.
                        </p>
                    </div>
                   <div class="col-md-6 align-self-center text-md-end text-center"> <p class="copyright-text"> Technology Partner : <a aria-label="RIMS Ranchi - External site that opens in a new window" href="https://www.computered.in/" target="_blank" title="COMPUTER Ed." onclick="return confirm('You are being redirected to an external website. Please note that this website is not responsible for external websites content & privacy policies.');"> <img src="{{ asset('images/ced.png') }}" alt="COMPUTER Ed." style="width:30px;height:30px;vertical-align:middle;"> <span style="font-size:15px;color:#cad90a;font-family:old-bookmark;"> <b>COMPUTER Ed.</b> </span> </a> </p> </div>
                </div>
            </div>
        </div>
    </footer>
    <!-- footer area end -->


     <!-- scroll-top -->
    <a href="#" id="scroll-top"><i class="far fa-arrow-up"></i></a>
    <!-- scroll-top end -->

    <script src="{{ asset('assets/js/modernizr.min.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/js/imagesloaded.pkgd.min.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.magnific-popup.min.js') }}"></script>
    <script src="{{ asset('assets/js/isotope.pkgd.min.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.appear.min.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.easing.min.js') }}"></script>
    <script src="{{ asset('assets/js/owl.carousel.min.js') }}"></script>
    <script src="{{ asset('assets/js/counter-up.js') }}"></script>
    <script src="{{ asset('assets/js/wow.min.js') }}"></script>
    <script src="{{ asset('assets/js/countdown.min.js') }}"></script>
    <script src="{{ asset('assets/js/main.js') }}"></script>
</body>
</html>