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
    <!-- Toastr Notifications CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/custom-toast.css') }}">
    <!-- Custom Theme & CSP Stylesheets -->
    <link rel="stylesheet" href="{{ asset('assets/css/common-csp.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/admin/assets/css/custom-theme.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/admin/assets/css/admin-csp.css') }}">

</head>

<body>
    <div class="d-none" id="loader">
        <div class="loader-container">
            <div class="cube">
                <div class="face front"><img src="{{ asset('assets/admin/assets/img/logo.png') }}" alt="Logo">
                </div>
                <div class="face back"><img src="{{ asset('assets/admin/assets/img/logo.png') }}" alt="Logo"></div>
                <div class="face right"><img src="{{ asset('assets/admin/assets/img/logo.png') }}" alt="Logo">
                </div>
                <div class="face left"><img src="{{ asset('assets/admin/assets/img/logo.png') }}" alt="Logo"></div>
                <div class="face top"><img src="{{ asset('assets/admin/assets/img/logo.png') }}" alt="Logo"></div>
                <div class="face bottom"><img src="{{ asset('assets/admin/assets/img/logo.png') }}" alt="Logo">
                </div>
            </div>
        </div>
        <br>
        <div id="loading-text">Loading...</div>
    </div>

    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">

            <aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme pb-3">
                <div class="app-brand mt-3">
                    <a href="{{ auth('admin')->user() && auth('admin')->user()->isModerator() ? route('admin.abstracts.index') : route('admin.dashboard') }}"
                        class="app-brand-link text-center w-100 px-2">
                        <span class="app-brand-logo w-100">
                            <img src="{{ asset('assets/img/logo/logo.png') }}" alt="IPHACON 2027 Logo"
                                style="max-width: 155px; height: auto;">
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

                <nav class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme mb-3"
                    id="layout-navbar">
                    <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
                        <a class="nav-item nav-link px-0 me-xl-4 text-primary" href="javascript:void(0)">
                            <i class="bx bx-menu fs-4"></i>
                        </a>
                    </div>

                    <div class="navbar-nav-right d-flex align-items-center justify-content-between w-100"
                        id="navbar-collapse">
                        <div class="navbar-nav align-items-center">
                            <!-- Welcome Badge for larger screens -->
                            <div class="nav-item d-none d-md-flex">
                                <div class="navbar-welcome-badge d-flex align-items-center gap-2.5">
                                    <div class="avatar avatar-online flex-shrink-0"
                                        style="width: 32px; height: 32px;">
                                        <img src="{{ !empty(auth()->user()->adminDetails->profile_pic) ? asset(auth()->user()->adminDetails->profile_pic) : asset('assets/img/logo/favicon.png') }}"
                                            alt="Avatar" class="w-100 h-100 rounded-circle border border-primary"
                                            style="object-fit: cover;" />
                                    </div>
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="text-dark extra-small">
                                            Welcome, <strong
                                                class="text-primary fw-bold">{{ auth('admin')->user()->full_name ?? 'IPHACON 2027 Admin' }}</strong>
                                        </span>
                                        <span
                                            class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 px-2.5 py-0.5 rounded-pill extra-small fw-semibold">
                                            {{ strtoupper(auth('admin')->user()->role ?? 'ADMIN') }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <!-- Welcome Badge for mobile screens -->
                            <div class="nav-item d-flex d-md-none">
                                <div class="navbar-welcome-badge d-flex align-items-center gap-1.5 py-1 px-2.5">
                                    <span class="text-dark extra-small">
                                        Hi, <strong
                                            class="text-primary fw-bold">{{ auth('admin')->user()->full_name ?? 'Admin' }}</strong>
                                    </span>
                                </div>
                            </div>
                        </div>

                        <ul class="navbar-nav flex-row align-items-center ms-auto gap-2">
                            <!-- Real-time Live Clock Badge -->
                            <li class="nav-item d-none d-sm-block me-1">
                                <div class="navbar-welcome-badge d-flex align-items-center gap-1.5 py-1 px-3"
                                    style="background-color: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 30px;">
                                    <i class="bx bx-time-five text-primary fs-5"></i>
                                    <span id="headerLiveClock" class="fw-bold font-monospace text-dark extra-small"
                                        style="font-size: 0.8rem; letter-spacing: 0.5px;"></span>
                                    <span id="headerLiveDate" class="text-muted extra-small ms-1 d-none d-md-inline"
                                        style="font-size: 0.74rem;"></span>
                                </div>
                            </li>
                            <!-- User Profile Dropdown -->
                            <li class="nav-item navbar-dropdown dropdown-user dropdown">
                                <a class="nav-link dropdown-toggle hide-arrow p-0" href="javascript:void(0);"
                                    data-bs-toggle="dropdown">
                                    <div class="avatar avatar-online" style="width: 38px; height: 38px;">
                                        <img src="{{ !empty(auth()->user()->adminDetails->profile_pic) ? asset(auth()->user()->adminDetails->profile_pic) : asset('assets/img/logo/favicon.png') }}"
                                            alt="User Avatar"
                                            class="w-100 h-100 rounded-circle border border-2 border-primary"
                                            style="object-fit: cover;" />
                                    </div>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 mt-2"
                                    style="border-radius: 14px; min-width: 220px; font-size: 0.82rem;">
                                    <li class="p-2">
                                        <div class="d-flex align-items-center p-2 rounded-3 bg-light">
                                            <div class="flex-shrink-0 me-2.5">
                                                <div class="avatar avatar-online" style="width: 38px; height: 38px;">
                                                    <img src="{{ !empty(auth()->user()->adminDetails->profile_pic) ? asset(auth()->user()->adminDetails->profile_pic) : asset('assets/img/logo/favicon.png') }}"
                                                        alt class="w-100 h-100 rounded-circle border"
                                                        style="object-fit: cover;" />
                                                </div>
                                            </div>
                                            <div class="flex-grow-1 overflow-hidden">
                                                <span class="fw-bold text-dark d-block text-truncate mb-0"
                                                    style="font-size: 0.84rem;">{{ auth('admin')->user()->full_name ?? 'IPHACON 2027 Admin' }}</span>
                                                <span class="badge bg-primary extra-small fw-semibold text-uppercase"
                                                    style="font-size: 0.65rem;">{{ auth('admin')->user()->role ?? 'ADMIN' }}</span>
                                            </div>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="dropdown-divider my-1"></div>
                                    </li>
                                    <li>
                                        <a class="dropdown-item d-flex align-items-center py-2 rounded-2"
                                            href="{{ route('admin.profile.change-password') }}">
                                            <i class="bx bx-cog me-2 text-primary fs-5"></i>
                                            <span class="fw-medium text-dark">Change Password</span>
                                        </a>
                                    </li>
                                    <li>
                                        <div class="dropdown-divider my-1"></div>
                                    </li>
                                    <form method="POST" action="{{ route('admin.logout') }}" class="m-0">
                                        @csrf
                                        <li>
                                            <button type="submit"
                                                class="dropdown-item d-flex align-items-center py-2 rounded-2 text-danger">
                                                <i class="bx bx-power-off me-2 fs-5 text-danger"></i>
                                                <span class="fw-bold">Log Out</span>
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
    <script>
        function updateHeaderClock() {
            const now = new Date();
            const timeOptions = {
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit',
                hour12: true
            };
            const dateOptions = {
                day: '2-digit',
                month: 'short',
                year: 'numeric'
            };

            const clockEl = document.getElementById('headerLiveClock');
            const dateEl = document.getElementById('headerLiveDate');

            if (clockEl) clockEl.innerText = now.toLocaleTimeString('en-US', timeOptions);
            if (dateEl) dateEl.innerText = '• ' + now.toLocaleDateString('en-US', dateOptions);
        }
        updateHeaderClock();
        setInterval(updateHeaderClock, 1000);
    </script>
    <script src="{{ asset('assets/admin/assets/js/main.js') }}"></script>
    <!-- Toastr Notifications JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script>
        $(document).ready(function() {
            if (typeof toastr !== 'undefined') {
                toastr.options = {
                    "closeButton": true,
                    "progressBar": true,
                    "positionClass": "toast-top-right",
                    "timeOut": "5000",
                    "extendedTimeOut": "2000"
                };

                @if (Session::has('success'))
                    toastr.success("{!! addslashes(Session::get('success')) !!}", "Success");
                @endif
                @if (Session::has('error'))
                    toastr.error("{!! addslashes(Session::get('error')) !!}", "Error");
                @endif
                @if (Session::has('info'))
                    toastr.info("{!! addslashes(Session::get('info')) !!}", "Notice");
                @endif
                @if (Session::has('warning'))
                    toastr.warning("{!! addslashes(Session::get('warning')) !!}", "Warning");
                @endif
                @if (isset($errors) && $errors->any())
                    @foreach ($errors->all() as $error)
                        toastr.error("{!! addslashes($error) !!}", "Validation Error");
                    @endforeach
                @endif
            }
        });
    </script>
    @stack('scripts')
</body>

</html>
