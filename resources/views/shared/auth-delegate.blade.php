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
    <link rel="icon" href="{{ asset('assets/img/logo/favicon.png') }}" type="image/png">
    <link rel="shortcut icon" href="{{ asset('assets/img/logo/favicon.png') }}" type="image/png">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Canonical URL -->
    <link rel="canonical" href="https://www.iphacon2027.com/">

    <meta property="og:type" content="website">
    <meta property="og:title" content="IPHACON 2027 | National Conference">
    <meta property="og:description" content="Join IPHACON 2027 at RIMS, Ranchi for plenaries, workshops & networking.">
    <meta property="og:url" content="https://www.iphacon2027.com/">
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
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">

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

        /* Delegate Navigation Color Palette & Position Override */
        .header-style-three .header-lower .main-menu:before {
            background: linear-gradient(135deg, #2D69FF 0%, #1A52E0 100%) !important;
            box-shadow: 0 4px 15px rgba(45, 105, 255, 0.2);
            z-index: 0 !important;
        }
        .header-style-three .header-lower .main-menu:after {
            background: #4BAA7D !important;
            z-index: 0 !important;
            bottom: -8px !important;
        }

        /* Ensure nav text renders cleanly ON TOP of background & underline pseudo-elements */
        .header-style-three .header-lower .main-menu .navigation,
        .header-style-three .header-lower .main-menu .navigation > li,
        .header-style-three .header-lower .main-menu .navigation > li > a {
            position: relative !important;
            z-index: 10 !important;
            color: #ffffff !important;
            font-weight: 600 !important;
        }

        /* Active link highlight and remove overlapping hover underline */
        .header-style-three .header-lower .main-menu .navigation > li > a:hover,
        .header-style-three .header-lower .main-menu .navigation > li.current > a,
        .header-style-three .header-lower .main-menu .navigation > li > a.active {
            color: #DCFFF0 !important;
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

            <!-- End Header Top -->
            <div class="header-lower">
                <div class="auto-container">
                    <div class="row align-items-center text-center">

                        <!-- Left Logo (IPHACON) -->
                        <div
                            class="col-lg-3 col-md-3 col-12 mb-2 mb-md-2 d-flex justify-content-center align-items-center">
                            <a href="https://www.iphacon2027.com/index.php" title="IPHACON 2027">
                                <img src="{{ asset('assets/img/logo/logo.png') }}" alt="IPHACON 2027 Logo"
                                    title="IPHACON 2027 Logo" class="img-fluid"
                                    style="max-height: 90px; object-fit: contain;">
                            </a>
                        </div>

                        <!-- Center Title -->
                        <div class="col-lg-6 col-md-6 col-12 mb-2 mb-md-2 text-center event-heading">

                        </div>

                        <!-- Right Logo (Jharkhand/RIMS) -->
                        <div
                            class="col-lg-3 col-md-3 col-12 mb-2 mb-md-2 d-flex justify-content-center align-items-center">
                            <a href="https://www.iphacon2027.com/index.php" title="RIMS Logo">
                                <img src="{{ asset('shared/user/images/rimslogo.png') }}" alt="RIMS Logo"
                                    title="RIMS Logo" class="img-fluid" style="max-height: 90px; object-fit: contain;">
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
                            <nav class="main-menu navbar navbar-expand-md" role="navigation">
                                <div class="navbar-header">
                                    <button class="navbar-toggler" type="button" data-toggle="collapse"
                                        data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                                        aria-expanded="false" aria-label="Toggle navigation">
                                        <span class="icon-bar"></span>
                                        <span class="icon-bar"></span>
                                        <span class="icon-bar"></span>
                                    </button>
                                </div>

                                <div class="collapse navbar-collapse clearfix" id="navbarSupportedContent">
                                    <ul class="navigation navbar-nav me-auto" id="mainmenu">

                                        <!-- Home -->
                                        <!-- <li class="nav-item">
                                            <a class="nav-link active" href="https://www.iphacon2027.com/"
                                                target="_blank" title="Go To Home">
                                                <i class="fas fa-home" aria-hidden="true"></i>
                                                <span class="visually-hidden">Home</span>
                                            </a>
                                        </li> -->

                                        <!-- Dashboard -->
                                        <li class="nav-item">
                                            <a class="nav-link" href="{{ url('/dashboard') }}"
                                                title="Delegate Dashboard">
                                                Dashboard
                                            </a>
                                        </li>

                                        <!-- My Registration -->
                                        <li class="nav-item">
                                            <a class="nav-link" href="{{ url('/registration') }}"
                                                title="My Registration">
                                                My Registration
                                            </a>
                                        </li>

                                        <!-- Submit Abstract -->
                                        <!-- <li class="nav-item">
                                            <a class="nav-link" href="#" title="Submit Abstract">
                                                Submit Abstract
                                            </a>
                                        </li> -->

                                        <!-- Paper Submission -->
                                        <li class="nav-item">
                                            <a class="nav-link" href="#" title="Paper Submission">
                                                Paper Submission
                                            </a>
                                        </li>

                                        <!-- Passes -->
                                        <li class="nav-item">
                                            <a class="nav-link" href="#" title="Passes">
                                                Passes
                                            </a>
                                        </li>

                                        <li class="dropdown"><a href="#" title="Settings">Settings</a>
                                            <ul>
                                                <li><a href="/profile" title="My Profile">My Profile</a></li>
                                                <li><a href="/profile/change-password" title="Change Password">Change
                                                        Password</a></li>

                                            </ul>
                                        </li>

                                        <!-- Logout -->
                                        <li class="nav-item">
                                            <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                                class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn d-flex align-items-center"
                                                    style="background-color: #4BAA7D; color: #ffffff; border: none; padding: 6px 16px; border-radius: 8px; font-weight: 600; box-shadow: 0 2px 8px rgba(75, 170, 125, 0.3);">
                                                    <i class="fas fa-sign-out-alt me-2"></i> Logout
                                                </button>
                                            </form>
                                        </li>

                                    </ul>
                                </div>
                            </nav>

                            {{-- <div class="outer-box clearfix">
                                <div class="btn-box">
                                    <a href="registration_form.php" class="theme-btn btn-style-one btn-sm"
                                        role="button" aria-label="Become Member"><span class="btn-title"><i
                                                class="flaticon-chair"></i> Registration</span></a>
                                </div>
                            </div> --}}
                        </div>
                    </div>
                </div>
            </div>

            <div class="sticky-header" aria-hidden="true">
                <div class="auto-container">
                    <div class="main-box">
                        <div class="logo-box">
                            <div class="logo"><a href="https://www.iphacon2027.com/index.php" aria-label="Iphacon 2027 Home"><img
                                        src="{{ asset('assets/img/logo/logo.png') }}" alt="Iphacon 2027 Logo"
                                        title="Iphacon 2027 Logo"></a>
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
                <div class="logo"><a href="https://www.iphacon2027.com/index.php"><img src="{{ asset('shared/user/images/ismm_logo.png') }}"
                            alt="Iphacon 2027 Logo" title="Iphacon 2027 Logo"></a></div>
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
                        <div class="nav-logo"><a href="https://www.iphacon2027.com/index.php"><img
                                    src="{{ asset('assets/img/logo/logo.png') }}" alt="Iphacon 2027 Logo"
                                    title="Iphacon 2027 Logo"></a></div>
                        <div class="close-btn" role="button" aria-label="Close Menu"><i
                                class="icon flaticon-close"></i></div>
                    </div>

                    <ul class="navigation clearfix"></ul>

                    <!--<div class="text-center">
              <a href="#" class="theme-btn" role="button">Become Member</a>
            </div>-->

                    <ul class="contact-list-one">
                        <li><i class="flaticon-location"></i> Rajendra Institute of Medical Sciences RIMS, Bariatu,
                            Ranchi -
                            834002 <strong>Address</strong></li>
                        <li><i class="flaticon-alarm-clock-1"></i> Mon - Sat 9:00 - 18:00 <strong>Timing</strong></li>
                        <li><i class="flaticon-email-1"></i> <a
                                href="mailto:iphacon2027@gmail.com">iphacon2027@gmail.com</a>
                            <strong>Mail to us</strong>
                        </li>
                    </ul>

                    <!--<ul class="social-links" aria-label="Social Media Links">
              <li><a href="#" aria-label="Facebook"><span class="fab fa-facebook-f"></span></a></li>
              <li><a href="#" aria-label="Twitter"><span class="fab fa-twitter"></span></a></li>
              <li><a href="#" aria-label="LinkedIn"><span class="fab fa-linkedin-in"></span></a></li>
            </ul>-->
                </nav>
            </div>

            <div class="search-popup" role="search">
                <button class="close-search" aria-label="Close Search"><i class="flaticon-close"></i></button>
                <form method="post" action="#">
                    <div class="form-group">
                        <label for="search-field" class="visually-hidden">Search site</label>
                        <input type="search" id="search-field" name="search-field" placeholder="Search" required>
                        <button type="submit"><i class="fa fa-search"></i><span class="visually-hidden">Submit
                                Search</span>
                        </button>
                    </div>
                </form>
            </div>
        </header>

        <main id="main-content" role="main">
            <div class="form-back-drop"></div>

            @hasSection('show-page-title')
            <!--Page Title-->
            <section class="page-title">
                <div class="anim-icons full-width">
                    <span class="icon icon-bull-eye"></span>
                    <span class="icon icon-dotted-circle"></span>
                </div>
            </section>
            <!--End Page Title-->
            @endif

            <!-- About Section Two -->
            <section class="about-section-two py-2">
                <div class="auto-container">
                    @yield('delegate-content')
                </div>
            </section>
        </main>

      <footer class="footer-area">
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

            <!-- End Page Wrapper -->
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
