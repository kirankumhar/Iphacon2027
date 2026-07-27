<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="IPHACON 2027: 16th National Biennial Conference at RIMS, Ranchi, Jharkhand.">
    <meta name="author" content="IPHACON 2027">
    <meta name="robots" content="index, follow">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Mobile & Responsive -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=3.0">
    <meta name="format-detection" content="telephone=no">

    <!-- Favicons (must be inside head!) -->
    <link rel="icon" href="{{ asset('shared/user/images/favicon.png') }}" type="image/png">
    <link rel="shortcut icon" href="{{ asset('shared/user/images/favicon.png') }}" type="image/png">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Canonical URL -->
    <link rel="canonical" href="https://www.iphacon2027.com/">

    <meta property="og:type" content="website">
    <meta property="og:title" content="IPHACON 2027 | National Conference">
    <meta property="og:description" content="Join IPHACON 2027 at RIMS, Ranchi for plenaries, workshops & networking.">
    <meta property="og:url" content="https://iphacon2027.com">
    <meta property="og:site_name" content="IPHACON 2027">

    <!-- Twitter Card (image commented out) -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:site" content="@IPHACON2027">
    <meta name="twitter:creator" content="@IPHACON2027">
    <meta name="twitter:title" content="IPHACON 2027 | National Conference">
    <meta name="twitter:description" content="IPHACON 2027 at RIMS, Ranchi—leading national conference.">

    <!-- Stylesheets -->
    <link href="{{ asset('shared/user/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('shared/user/css/style_in.css') }}" rel="stylesheet">
    <link href="{{ asset('shared/user/css/responsive.css') }}" rel="stylesheet">

    <link rel="shortcut icon" href="{{ asset('shared/user/images/favicon.png') }}" type="image/x-icon">
    <link rel="icon" href="{{ asset('shared/user/images/favicon.png') }}" type="image/x-icon">
    <!--Color Switcher Mockup-->
    <link href="{{ asset('shared/user/css/color-switcher-design.css') }}" rel="stylesheet">

    <!-- Responsive -->
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">

    <title>@yield('title') - {{ config('app.name') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <style>
        .sticky-header .logo img {
            max-height: 50px !important;
            width: auto;
            object-fit: contain;
        }

        .main-menu .navigation > li > a::before,
        .main-menu .navigation > li > a::after,
        .main-menu .navigation > li:hover > a::before,
        .main-menu .navigation > li.current > a::before,
        .header-style-three .header-lower .main-menu .navigation > li > a::before,
        .header-style-three .header-lower .main-menu .navigation > li > a::after {
            display: none !important;
            content: none !important;
            width: 0 !important;
            height: 0 !important;
            opacity: 0 !important;
        }
    </style>

    <style>
        .sticky-header .logo img {
            max-height: 50px !important;
            width: auto;
            object-fit: contain;
        }

        .header-logo {
            max-height: 80px;
            object-fit: contain;
        }

        @media (min-width: 992px) {
            .ismm-location {
                text-align: left;
            }
        }
    </style>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Custom Styles -->
    <style>
        body {
            font-family: 'Figtree', sans-serif;
            margin: 0;
            padding: 0;
            overflow-x: hidden;
        }

        .cursor-pointer {
            cursor: pointer;
        }

        .shadow-2xl {
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25) !important;
        }

        .input-group-text {
            transition: all 0.3s ease;
        }

        .form-control:focus {
            box-shadow: 0 0 0 0.2rem rgba(46, 49, 146, 0.25);
            border-color: #2e3192;
        }

        .btn {
            transition: all 0.3s ease;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
        }

        /* Floating animation */
        @keyframes floating {
            0% {
                transform: translateY(0px);
            }

            50% {
                transform: translateY(-10px);
            }

            100% {
                transform: translateY(0px);
            }
        }

        .floating {
            animation: floating 3s ease-in-out infinite;
        }

        /* Background animations */
        .bg-animated::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(-45deg, #ee7752, #e73c7e, #23a6d5, #23d5ab);
            background-size: 400% 400%;
            animation: gradient 15s ease infinite;
            z-index: -1;
        }

        @keyframes gradient {
            0% {
                background-position: 0% 50%;
            }

            50% {
                background-position: 100% 50%;
            }

            100% {
                background-position: 0% 50%;
            }
        }
    </style>

</head>

<body>

    <div class="page-wrapper">

        <header class="main-header header-style-two">
            <div class="header-lower">
                <div class="auto-container">
                    <div class="row align-items-center text-center text-lg-start">

                        <!-- Left Logo (iphacon Main) -->
                        <div class="col-lg-2 col-md-2 col-4 d-flex justify-content-center align-items-center mb-2">
                            <a href="https://www.iphacon2027.com/index.php" title="ISMM Logo Main">
                                <img src="{{ asset('shared/user/images/ismm_logo_main.png') }}" alt="ISMM Main Logo" class="img-fluid header-logo">
                            </a>
                        </div>

                        <!-- Center Block: iphacon 2027 Logo + Title -->
                        <div
                            class="col-lg-8 col-md-8 col-12 d-flex flex-column flex-lg-row align-items-center justify-content-center text-center mb-2">
                            <div class="me-lg-3 mb-2 mb-lg-0">
                                <a href="https://www.iphacon2027.com/index.php" title="ISMM 2027">
                                    <img src="{{ asset('shared/user/images/ismm_logo.png') }}" alt="ISMM 2027 Logo" class="img-fluid header-logo">
                                </a>
                            </div>
                            <div>
                                <h1 class="ismm-subtitle mb-0">
                                    IPHACON 2027 National Conference, Ranchi
                                </h1>
                                <p class="ismm-location mb-0">
                                    04–07 February, 2027
                                </p>
                            </div>
                        </div>

                        <!-- Right Logo (RIMS) -->
                        <div class="col-lg-2 col-md-2 col-4 d-flex justify-content-center align-items-center mb-2">
                            <a href="https://www.iphacon2027.com/index.php" title="RIMS Logo">
                                <img src="{{ asset('shared/user/images/rimslogo.png') }}" alt="RIMS Logo" class="img-fluid header-logo">
                            </a>
                        </div>

                    </div>
                </div>
            </div>
        </header>

        <header class="main-header header-style-three" role="banner">
            <div class="header-lower">
                <div class="auto-container">
                    <div class="main-box d-flex justify-content-between align-items-center">
                        <div class="nav-outer" role="navigation" aria-label="Main menu">
                            <nav class="main-menu navbar-expand-md" role="navigation">
                                <div class="navbar-header">
                                    <button class="navbar-toggler" type="button" data-toggle="collapse"
                                        data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                                        aria-expanded="false" aria-label="Toggle navigation">
                                        <span class="icon-bar"></span>
                                        <span class="icon-bar"></span>
                                        <span class="icon-bar"></span>
                                    </button>
                                </div>

                                <div class="navbar-collapse collapse clearfix" id="navbarSupportedContent">
                                    <ul class="navigation clearfix" id="mainmenu">
                                        <li><a class="nav-link active" href="https://www.iphacon2027.com/" alt="Go To Home"
                                                title="Go To Home"><i class="fas fa-home"
                                                    aria-hidden="true"></i><span
                                                    class="visually-hidden">Home</span></a></li>
													<li class="nav-item">
                                                    <a href="https://registration.iphacon2027.com/login/"><button class="btn btn-pink d-flex align-items-center" style="background-color:#3236a9; color:#fff; border:none; padding:6px 14px; border-radius:8px;">
                                                    <i class="fas fa-sign-out-alt me-2"></i> Login
                                                </button></a></li>
													
													 </ul>

                                </div>

                               
                            </nav>

                            <div class="outer-box clearfix">
                                <div class="btn-box">
                                    <a href="https://registration.iphacon2027.com/" class="theme-btn btn-style-one" role="button"
                                        aria-label="Become Member"><span class="btn-title"><i
                                                class="flaticon-chair"></i> Register Now</span></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="sticky-header" aria-hidden="true">
                <div class="auto-container">
                    <div class="main-box">
                        <div class="logo-box">
                            <div class="logo"><a href="https://www.iphacon2027.com/index.php" aria-label="ISMM 2027 Home"><img
                                        src="{{ asset('shared/user/images/ismm_logo.png') }}" alt="ISMM 2027 Logo" title="ISMM 2027 Logo"></a>
                            </div>
                            <div class="upper-right">
                                <div class="search-box">
                                    <button class="search-btn mobile-search-btn" aria-label="Open Search"><i
                                            class="flaticon-search-2"></i></button>
                                </div>
                                <a href="#nav-mobile" class="mobile-nav-toggler navbar-trigger"
                                    aria-label="Toggle Mobile Navigation"><i class="flaticon-menu"></i></a>
                            </div>
                        </div>
                        <nav class="main-menu navbar-expand-md" aria-hidden="true"></nav>
                    </div>
                </div>
            </div>

            <div class="mobile-header" role="complementary">
                <div class="logo"><a href="https://www.iphacon2027.com/index.php"><img src="shared/user/images/ismm_logo.png" alt="ISMM 2027 Logo"
                            title="ISMM 2027 Logo"></a></div>
                <div class="nav-outer clearfix">
                    <div class="outer-box">
                        <div class="search-box">
                            <button class="search-btn mobile-search-btn" aria-label="Open Search"><i
                                    class="flaticon-search-2"></i></button>
                        </div>
                        <a href="#nav-mobile" class="mobile-nav-toggler navbar-trigger"
                            aria-label="Toggle Navigation"><i class="flaticon-menu"></i></a>
                    </div>
                </div>
            </div>

            <div class="mobile-menu" role="dialog" aria-modal="true" aria-label="Mobile Menu">
                <div class="menu-backdrop"></div>
                <nav class="menu-box">
                    <div class="upper-box">
                        <div class="nav-logo"><a href=""><img src="shared/user/images/ismm_logo.png" alt="iphacon 2027 Logo"
                                    title="iphacon 2027 Logo"></a></div>
                        <div class="close-btn" role="button" aria-label="Close Menu"><i
                                class="icon flaticon-close"></i></div>
                    </div>

                    <ul class="navigation clearfix"></ul>

                    <!--<div class="text-center">
        <a href="#" class="theme-btn" role="button">Become Member</a>
      </div>-->

                    <ul class="contact-list-one">
                        <li><i class="flaticon-location"></i> Rajendra Institute of Medical Sciences RIMS, Bariatu,
                            Ranchi - 834002 <strong>Address</strong></li>
                        <li><i class="flaticon-alarm-clock-1"></i> Mon - Sat 9:00 - 18:00 <strong>Timing</strong></li>
                        <li><i class="flaticon-email-1"></i> <a
                                href="mailto:iphacon2027@gmail.com">iphacon2027@gmail.com</a> <strong>Mail
                                to us</strong></li>
                    </ul>

                    <!--<ul class="social-links" aria-label="Social Media Links">
        <li><a href="#" aria-label="Facebook"><span class="fab fa-facebook-f"></span></a></li>
        <li><a href="#" aria-label="Twitter"><span class="fab fa-twitter"></span></a></li>
        <li><a href="#" aria-label="LinkedIn"><span class="fab fa-linkedin-in"></span></a></li>
      </ul>-->
                </nav>
            </div>

            
        </header>

        <main id="main-content" role="main">
            <div class="form-back-drop"></div>

            <!--Page Title-->
            <section class="page-title">
                <img src="{{ asset('shared/user/images/slider_in/about_banner') }}.jpg"
                    alt="Iphacon 2027, 16<sup>th</sup> National Biennial Conference of the Indian Society of Medical Mycologist, RIMS, Ranchi, Jharkhand, India"
                    title="Iphacon 2027, 16<sup>th</sup> National Biennial Conference of the Indian Society of Medical Mycologist, RIMS, Ranchi, Jharkhand, India"
                    style="width:100%; height:auto;">
                <div class="anim-icons full-width">
                    <span class="icon icon-bull-eye"></span>
                    <span class="icon icon-dotted-circle"></span>
                </div>

            </section>
            <!--End Page Title-->

            <!-- About Section Two -->
            <section class="about-section-two">
                <div class="auto-container">
                    <div class="row">
                        <!-- Content Column -->
                        <div class="content-column col-lg-12 col-md-12 col-sm-12">
                            <div class="inner-column">
                                <div class="sec-title">
                                    <!--<span class="sub-title">Get the latest info about</span>-->
                                    <h2>@yield('inner-title')</h2>
                                    <span class="divider"></span>

                                </div>
                            </div>
                        </div>
                    </div>

                    {!! $slot ?? '' !!}
                    @yield('delegate-content')
                    @yield('content')

                </div>
            </section>

            <footer class="main-footer style-three">
                <!-- Widgets Section -->
                <div class="widgets-section">
                    <div class="auto-container">
                        <div class="row">
                            <!-- Big Column -->
                            <div class="big-column col-xl-5 col-lg-12 col-md-12 col-sm-12">
                                <div class="row">
                                    <!-- Footer Column -->
                                    <div class="footer-column col-xl-12 col-lg-12 col-md-12 col-sm-12">
                                        <div class="footer-widget about-widget">

                                            <div class="logo">

                                                <a href="https://www.iphacon2027.com/index.php"><img
                                                        src="{{ asset('shared/user/images/ismm_logo_footer.png') }}"
                                                        alt=""></a>

                                            </div>

                                            <div class="text">

                                                <p>This event will bring developments in medical mycology to
                                                    students,
                                                    faculty and clinicians in India.</p>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Big Column -->
                            <div class="big-column col-xl-7 col-lg-12 col-md-12 col-sm-12">
                                <div class="row">
                                    <!-- Footer Column -->
                                    <div class="footer-column col-lg-6 col-md-6 col-sm-12">
                                        <div class="footer-widget contact-widget">
                                            <h2 class="widget-title">Contact Information</h2>
                                            <div class="widget-content">
                                                <ul class="contact-list-three">
                                                    <li>
                                                        <i class="icon flaticon-alarm-clock-1"></i>
                                                        <div class="text">
                                                            Monday - Saturday, 9am - 6pm <br>
                                                            <strong>Operating Hours</strong>
                                                        </div>
                                                    </li>
                                                    <li>
                                                        <i class="icon flaticon-email-1"></i>
                                                        <div class="text">
                                                            <a
                                                                href="mailto:iphacon2027@gmail.com">iphacon2027@gmail.com</a><br>
                                                            <strong>Email Us</strong>
                                                        </div>
                                                    </li>
                                                    <li>
                                                        <i class="icon flaticon-location"></i>
                                                        <div class="text">
                                                            Ranchi, Jharkhand, India<br>
                                                            <strong>Venue: RIMS, Bariatu, Ranchi</strong>
                                                        </div>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Footer Column -->
                                    <div class="footer-column col-lg-6 col-md-6 col-sm-12">
                                        <div class="footer-widget">
                                            <h2 class="widget-title">Important Dates</h2>
                                            <div class="widget-content">
                                                <ul class="user-links1">

                                                    <li><i class="fa fa-calendar"
                                                            style="color:#cad90a; margin-right:6px;"></i>Pre-Conf.
                                                        Workshop : 4<sup>th</sup> February 2027</li>
                                                    <li><i class="fa fa-calendar"
                                                            style="color:#cad90a; margin-right:6px;"></i>Conference
                                                        Dates: 5<sup>th</sup>-7<sup>th</sup> February 2027</li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Footer Bottom -->
                <div class="footer-bottom">
                    <div class="auto-container">
                        <div class="d-flex justify-content-between align-items-center flex-wrap">
                            <div class="copyright-text">
                                <p>Copyright © <?php echo date('Y'); ?> <a href="https://www.iphacon2027.com/index.php">IPHACON 2027</a> All Rights
                                    Reserved.
                                </p>
                            </div>
                            <div class="tech-partner">
                                Technology Partner :
                                <a aria-label="COMPUTER Ed. Ranchi - External site that opens in a new window" href="https://www.computered.in/" target="_blank" title="Image of www.computered.in" onclick="return confirm('You are being redirected to an external website. Please note that this website is not responsible for external websites content &amp; privacy policies.');"><img src="{{ asset('shared/user/images/lg1.png') }}" style="width:50px; height:50px;"
                                    alt="">
                                <span style="font-size: 15px; color: #cad90a; font-family: 'Old Bookmark', serif;">
                                    <b>COMPUTER Ed.</b>
                                </span></a>
                            </div>
                        </div>
                    </div>
                </div>
            </footer>

            <!-- End Page Wrapper -->
        </main>
    </div>
    <!--Scroll to top-->
    <div class="scroll-to-top scroll-to-target" data-target="html"><span class="fa fa-angle-up"></span></div>

    <script src="{{ asset('shared/user/js/jquery.js') }}"></script>
    <script src="{{ asset('shared/user/js/popper.min.js') }}"></script>
    <script src="{{ asset('shared/user/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('shared/user/js/jquery-ui.js') }}"></script>
    <script src="{{ asset('shared/user/js/jquery.fancybox.js') }}"></script>
    <script src="{{ asset('shared/user/js/jquery.countdown.js') }}"></script>
    <script src="{{ asset('shared/user/js/appear.js') }}"></script>
    <script src="{{ asset('shared/user/js/owl.js') }}"></script>
    <script src="{{ asset('shared/user/js/wow.js') }}"></script>
    <script src="{{ asset('shared/user/js/script.js') }}"></script>
    <!-- Color Setting -->
    <script src="{{ asset('shared/user/js/color-settings.js') }}"></script>
    <script>
        // setTimeout(function() {
        //     const alerts = document.querySelectorAll('.alert');
        //     alerts.forEach(function(alert) {
        //         const bsAlert = new bootstrap.Alert(alert);
        //         bsAlert.close();
        //     });
        // }, 5000);
    </script>
</body>

</html>
