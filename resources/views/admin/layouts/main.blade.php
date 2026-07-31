<!DOCTYPE html>

<html lang="en" class="light-style layout-menu-fixed layout-compact" dir="ltr" data-theme="theme-default"
    data-assets-path="{{ asset('/assets/admin/') }}">

<head>
    <meta charset="utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>IPHACON 2027 - Dashboard</title>

    <meta name="description" content="IPHACON 2027" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/img/logo/favicon.png') }}" />

    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
        rel="stylesheet" />

    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/3.0.2/css/buttons.dataTables.css">
    <link rel="stylesheet" href="{{ asset('assets/admin/assets/vendor/libs/select2/select2.css') }} " />
    {{-- Multi select css --}}
    <link rel="stylesheet" href="{{ asset('assets/admin/assets/vendor/libs/DataTables/datatables.min.css') }}">

    <link rel="stylesheet" href="{{ asset('assets/admin/assets/vendor/fonts/boxicons.css') }}" />

    <link rel="stylesheet" href="{{ asset('assets/admin/assets/vendor/css/core.css') }}"
        class="template-customizer-core-css" />
    <link rel="stylesheet" href="{{ asset('assets/admin/assets/vendor/css/theme-default.css') }}"
        class="template-customizer-theme-css" />

    <link rel="stylesheet"
        href="{{ asset('assets/admin/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.cs') }}s" />
    <link rel="stylesheet" href="{{ asset('assets/admin/assets/vendor/libs/apex-charts/apex-charts.css') }}" />
    <script src="{{ asset('assets/admin/assets/vendor/js/helpers.js') }}"></script>
    <script src="{{ asset('assets/admin/assets/js/config.js') }}"></script>

    <link rel="stylesheet" type="text/css" href='{{ asset('assets/loader.css') }}'>

    <!-- Custom IPHACON Palette Theme (Alice Blue, Ultramarine Blue, Frosted Mint, Green Field) -->
    <style>
        :root {
            --alice-blue: #E1F0FF;
            --ultramarine-blue: #2D69FF;
            --frosted-mint: #DCFFF0;
            --green-field: #4BAA7D;
        }

        body {
            background-color: #E1F0FF !important;
            background: linear-gradient(135deg, #E1F0FF 0%, #F0F6FF 60%, #DCFFF0 100%) !important;
            min-height: 100vh;
        }

        /* Modern Gradient Sidebar Styling */
        #layout-menu.bg-menu-theme {
            background: linear-gradient(180deg, #0F172A 0%, #1E293B 60%, #0F172A 100%) !important;
            box-shadow: 4px 0 25px rgba(15, 23, 42, 0.2) !important;
            border-right: 1px solid rgba(255, 255, 255, 0.08) !important;
            overflow-x: hidden !important;
        }

        .bg-menu-theme .menu-inner-shadow {
            display: none !important;
        }

        .bg-menu-theme .menu-inner {
            background: transparent !important;
        }

        .bg-menu-theme .app-brand {
            background: #FFFFFF !important;
            margin: 12px auto !important;
            padding: 8px 12px !important;
            border-radius: 12px !important;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.15) !important;
            width: calc(100% - 24px) !important;
            box-sizing: border-box !important;
            height: auto !important;
            min-height: unset !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            overflow: hidden !important;
        }

        .bg-menu-theme .app-brand .app-brand-link {
            width: 100% !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
        }

        .bg-menu-theme .app-brand img {
            max-width: 135px !important;
            width: 100% !important;
            height: auto !important;
            max-height: 46px !important;
            object-fit: contain !important;
        }

        .bg-menu-theme .menu-link {
            color: #94A3B8 !important;
            border-radius: 10px !important;
            margin: 3px 12px !important;
            padding: 0.65rem 1rem !important;
            font-size: 0.9rem !important;
            font-weight: 500 !important;
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1) !important;
        }

        .bg-menu-theme .menu-link:hover {
            background: rgba(255, 255, 255, 0.08) !important;
            color: #38BDF8 !important;
            transform: translateX(4px);
        }

        .bg-menu-theme .menu-link:hover i {
            color: #38BDF8 !important;
        }

        .bg-menu-theme .menu-item.active > .menu-link {
            background: linear-gradient(135deg, #2D69FF 0%, #1A52E0 50%, #4BAA7D 100%) !important;
            color: #FFFFFF !important;
            box-shadow: 0 4px 18px rgba(45, 105, 255, 0.45) !important;
            font-weight: 600 !important;
        }

        .bg-menu-theme .menu-item.active > .menu-link i,
        .bg-menu-theme .menu-item.active > .menu-link div {
            color: #FFFFFF !important;
        }

        .bg-menu-theme .menu-link i {
            color: #64748B !important;
            font-size: 1.25rem !important;
            transition: color 0.2s ease;
        }

        .bg-menu-theme .menu-header {
            margin-top: 1.35rem !important;
            margin-bottom: 0.4rem !important;
        }

        .bg-menu-theme .menu-header span,
        .bg-menu-theme .menu-header .menu-header-text {
            color: #38BDF8 !important;
            font-weight: 700 !important;
            font-size: 0.725rem !important;
            letter-spacing: 1px !important;
            text-transform: uppercase !important;
            opacity: 0.9;
        }

        /* Navbar Styling */
        #layout-navbar.bg-navbar-theme {
            background: linear-gradient(135deg, #FFFFFF 0%, #F8FAFC 100%) !important;
            backdrop-filter: blur(12px) !important;
            box-shadow: 0 4px 20px rgba(45, 105, 255, 0.1) !important;
            border: 1px solid rgba(45, 105, 255, 0.15) !important;
            border-left: 5px solid #2D69FF !important;
            border-radius: 14px !important;
        }

        .navbar-welcome-badge {
            background: linear-gradient(135deg, rgba(45, 105, 255, 0.08) 0%, rgba(220, 255, 240, 0.5) 100%) !important;
            border: 1px solid rgba(45, 105, 255, 0.18) !important;
            padding: 6px 16px !important;
            border-radius: 30px !important;
            box-shadow: inset 0 1px 2px rgba(255, 255, 255, 0.8) !important;
        }

        .navbar-dropdown .avatar {
            border: 2px solid #2D69FF !important;
            border-radius: 50% !important;
            padding: 2px !important;
            background: #FFFFFF !important;
            box-shadow: 0 2px 10px rgba(45, 105, 255, 0.25) !important;
            transition: transform 0.25s ease, box-shadow 0.25s ease !important;
        }

        .navbar-dropdown .avatar:hover {
            transform: scale(1.08) !important;
            box-shadow: 0 4px 15px rgba(45, 105, 255, 0.4) !important;
        }

        .layout-navbar .dropdown-menu {
            border-radius: 14px !important;
            border: 1px solid rgba(45, 105, 255, 0.15) !important;
            box-shadow: 0 10px 30px rgba(15, 23, 42, 0.12) !important;
            padding: 8px !important;
        }

        .layout-navbar .dropdown-item {
            border-radius: 8px !important;
            padding: 8px 12px !important;
            font-weight: 500 !important;
            transition: all 0.2s ease !important;
        }

        .layout-navbar .dropdown-item:hover {
            background-color: #DCFFF0 !important;
            color: #2D69FF !important;
        }

        .layout-navbar .dropdown-item.text-danger:hover {
            background-color: #FEE2E2 !important;
            color: #DC2626 !important;
        }

        /* Cards & Buttons */
        .card {
            background-color: #FFFFFF;
            border: 1px solid rgba(45, 105, 255, 0.12);
            border-radius: 14px;
            box-shadow: 0 4px 18px rgba(45, 105, 255, 0.06);
        }

        .card.bg-primary {
            background: linear-gradient(135deg, #2D69FF 0%, #1A52E0 100%) !important;
            color: #FFFFFF !important;
        }

        .card.bg-primary h4,
        .card.bg-primary small,
        .card.bg-primary div {
            color: #FFFFFF !important;
        }

        .invert-text-white {
            color: #1e293b !important;
            font-weight: 600;
        }

        .btn-primary {
            background-color: #2D69FF !important;
            border-color: #2D69FF !important;
            color: #FFFFFF !important;
            box-shadow: 0 4px 12px rgba(45, 105, 255, 0.3) !important;
        }

        .btn-primary:hover {
            background-color: #1A52E0 !important;
            border-color: #1A52E0 !important;
        }

        .btn-outline-primary {
            border-color: #2D69FF !important;
            color: #2D69FF !important;
        }

        .btn-outline-primary:hover {
            background-color: #2D69FF !important;
            color: #FFFFFF !important;
        }

        .btn-success {
            background-color: #4BAA7D !important;
            border-color: #4BAA7D !important;
            color: #FFFFFF !important;
        }

        .badge.bg-primary {
            background-color: #2D69FF !important;
            color: #FFFFFF !important;
        }

        .badge.bg-success {
            background-color: #4BAA7D !important;
            color: #FFFFFF !important;
        }

        /* Footer */
        footer.content-footer {
            background-color: #FFFFFF !important;
            border-top: 1px solid rgba(45, 105, 255, 0.12) !important;
        }

        footer.content-footer a {
            color: #2D69FF !important;
        }
    </style>
</head>

<body>
    <div class="d-none" id="loader">
        <div class="loader-container">
            <div class="cube">
                <div class="face front"><img src="{{ asset('assets/admin/assets/img/logo.png') }}" alt="Logo"></div>
                <div class="face back"><img src="{{ asset('assets/admin/assets/img/logo.png') }}" alt="Logo"></div>
                <div class="face right"><img src="{{ asset('assets/admin/assets/img/logo.png') }}" alt="Logo"></div>
                <div class="face left"><img src="{{ asset('assets/admin/assets/img/logo.png') }}" alt="Logo"></div>
                <div class="face top"><img src="{{ asset('assets/admin/assets/img/logo.png') }}" alt="Logo"></div>
                <div class="face bottom"><img src="{{ asset('assets/admin/assets/img/logo.png') }}" alt="Logo"></div>
            </div>
        </div>
        <br>
        <div id="loading-text">Loading...</div>
    </div>

    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">

            <aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme pb-3">
                <div class="app-brand mt-3">
                    <a href="{{ route('admin.dashboard') }}" class="app-brand-link text-center w-100 px-2">
                        <span class="app-brand-logo w-100">
                            <img src="{{ asset('assets/img/logo/logo.png') }}" alt="IPHACON 2027 Logo" style="max-width: 155px; height: auto;">
                        </span>
                    </a>

                    <a href="javascript:void(0);"
                        class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none bg-info">
                        <i class="bx bx-chevron-left bx-sm align-middle"></i>
                    </a>
                </div>

                <div class="menu-inner-shadow"></div>

                @include('admin/layouts/menus')

            </aside>

            <div class="layout-page">

                <nav class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme"
                    id="layout-navbar">
                    <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
                        <a class="nav-item nav-link px-0 me-xl-4 text-primary" href="javascript:void(0)">
                            <i class="bx bx-menu bx-sm"></i>
                        </a>
                    </div>

                    <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
                        <div class="navbar-nav align-items-center">
                            <!-- For larger screens -->
                            <div class="nav-item d-none d-md-flex">
                                <div class="navbar-welcome-badge d-flex align-items-center gap-2">
                                    <span class="badge bg-primary text-white rounded-circle p-1 d-flex align-items-center justify-content-center" style="width: 24px; height: 24px;">
                                        <i class="bx bx-user fs-7"></i>
                                    </span>
                                    <span class="text-dark fs-7">
                                        Welcome, <strong class="text-primary fw-bold">{{ auth('admin')->user()->full_name ?? 'IPHACON 2027 Admin' }}</strong>
                                    </span>
                                    <span class="badge bg-success bg-opacity-15 text-success px-2 py-0.5 rounded-pill fw-bold fs-tiny">
                                        IPHACON 2027 Panel
                                    </span>
                                </div>
                            </div>

                            <!-- For mobile screens -->
                            <div class="nav-item d-flex d-md-none">
                                <div class="navbar-welcome-badge d-flex align-items-center gap-1.5 py-1 px-2.5">
                                    <span class="text-dark fs-7">
                                        Welcome, <strong class="text-primary fw-bold">{{ auth('admin')->user()->full_name ?? 'Admin' }}</strong>
                                    </span>
                                </div>
                            </div>
                        </div>

                        <ul class="navbar-nav flex-row align-items-center ms-auto">
                            <!-- User -->
                            <li class="nav-item navbar-dropdown dropdown-user dropdown">
                                <a class="nav-link dropdown-toggle hide-arrow p-0" href="javascript:void(0);"
                                    data-bs-toggle="dropdown">
                                    <div class="avatar avatar-online">
                                        <img src="{{ asset('assets/img/logo/favicon.png') }}" alt="User Avatar"
                                            class="w-px-40 h-auto rounded-circle" />
                                    </div>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                                    <li>
                                        <a class="dropdown-item" href="javascript:void(0);">
                                            <div class="d-flex align-items-center">
                                                <div class="flex-shrink-0 me-3">
                                                    <div class="avatar avatar-online">
                                                        <img src="{{ asset('assets/img/logo/favicon.png') }}"
                                                            alt class="w-px-40 h-auto rounded-circle" />
                                                    </div>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <span class="fw-bold text-dark d-block mb-0">{{ auth('admin')->user()->full_name ?? 'IPHACON 2027 Admin' }}</span>
                                                    <span class="badge bg-label-primary fs-tiny fw-bold text-uppercase">{{ auth('admin')->user()->role ?? 'ADMIN' }}</span>
                                                </div>
                                            </div>
                                        </a>
                                    </li>
                                    <li>
                                        <div class="dropdown-divider my-1"></div>
                                    </li>
                                    <li>
                                        <a class="dropdown-item d-flex align-items-center" href="{{ route('admin.profile.change-password') }}">
                                            <i class="bx bx-cog me-2 text-primary fs-5"></i>
                                            <span>Change Password</span>
                                        </a>
                                    </li>
                                    <li>
                                        <div class="dropdown-divider my-1"></div>
                                    </li>
                                    <form method="POST" action="{{ route('admin.logout') }}">
                                        @csrf
                                        <li>
                                            <button type="submit" class="dropdown-item d-flex align-items-center text-danger">
                                                <i class="bx bx-power-off me-2 fs-5"></i>
                                                <span>Log Out</span>
                                            </button>
                                        </li>
                                    </form>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </nav>

                <div class="content-wrapper">
                    @yield('admin-content')
                    <footer class="content-footer footer bg-footer-theme">
                        <div
                            class="container-xxl d-flex flex-wrap justify-content-between py-2 flex-md-row flex-column">
                            <div class="mb-2 mb-md-0">
                                Copyright ® 2010-
                                <script>
                                    document.write(new Date().getFullYear());
                                </script>| IPHACON 2027 , Technology Partner <img
                                    src="{{ asset('assets/admin/assets/img/insta-logo.png') }}" width="30px"
                                    alt="ced"> <b><a href="https://www.computered.in/" target="_blank"
                                        class="footer-link fw-medium">COMPUTER Ed</a></b>. All rights reserved.
                            </div>
                        </div>
                    </footer>

                    <div class="content-backdrop fade"></div>
                </div>
            </div>
        </div>
        <div class="layout-overlay layout-menu-toggle"></div>
    </div>
    <script src="{{ asset('assets/admin/assets/vendor/libs/jquery/jquery.js') }}"></script>
    <script src="{{ asset('assets/admin/assets/vendor/libs/popper/popper.js') }}"></script>
    <script src="{{ asset('assets/admin/assets/vendor/js/bootstrap.js') }}"></script>
    <script src="{{ asset('assets/admin/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>
    <script src="{{ asset('assets/admin/assets/vendor/js/menu.js') }}"></script>

    <script src="{{ asset('assets/admin/assets/vendor/libs/DataTables/datatables.min.js') }}"></script>
    <script src="https://cdn.datatables.net/buttons/3.0.2/js/dataTables.buttons.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.0.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.0.2/js/buttons.dataTables.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="{{ asset('assets/admin/assets/vendor/libs/select2/select2.js') }}"></script>

    <script src="{{ asset('assets/admin/js/form.js') }}"></script>
    <script>
        $('form').on('submit', function(e) {
            // Show loader
            $("#loader").removeClass('d-none');
            $("#loading-text").text("Please Wait...");
        });
    </script>
    @stack('script')

    <script src="{{ asset('assets/admin/assets/js/main.js') }}"></script>
</body>

</html>
