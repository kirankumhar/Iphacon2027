<!DOCTYPE html>

<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>IPHACON 2027 - Admin Login</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/img/logo/favicon.png') }}" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
        rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('assets/admin/assets/vendor/fonts/boxicons.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/admin/assets/vendor/css/core.css') }}"
        class="template-customizer-core-css" />
    <link rel="stylesheet" href="{{ asset('assets/admin/assets/vendor/css/theme-default.css') }}"
        class="template-customizer-theme-css" />

    <link rel="stylesheet" type="text/css" href='{{ asset('assets/loader.css') }}'>
    <!-- Toastr Notifications CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" />
    <script src="{{ asset('assets/admin/assets/vendor/js/helpers.js') }}"></script>
</head>

<body>
    <div class="d-none" id="loader">
        <div class="loader-container">
            <div class="cube">
                <div class="face front"><img src="{{ asset('assets/admin/assets/img/logo.png') }}"
                        alt="Logo">
                </div>
                <div class="face back"><img src="{{ asset('assets/admin/assets/img/logo.png') }}"
                        alt="Logo">
                </div>
                <div class="face right"><img src="{{ asset('assets/admin/assets/img/logo.png') }}"
                        alt="Logo">
                </div>
                <div class="face left"><img src="{{ asset('assets/admin/assets/img/logo.png') }}"
                        alt="Logo">
                </div>
                <div class="face top"><img src="{{ asset('assets/admin/assets/img/logo.png') }}"
                        alt="Logo">
                </div>
                <div class="face bottom"><img src="{{ asset('assets/admin/assets/img/logo.png') }}"
                        alt="Logo"></div>
            </div>
        </div>
        <br>
        <div id="loading-text">Loading...</div>
    </div>
    <div class="authentication-wrapper bg-dark py-0">
        <div class="authentication-inner row m-0">
            <div class="d-lg-flex col-lg-6 col-xl-7 align-items-center px-5 bg-light">
                <div class="w-100 d-flex justify-content-center">
                    <img src="{{ asset('assets/admin/assets/img/logo.png') }}" class="img-fluid" alt="Login image"
                        width="700" data-app-dark-img="illustrations/boy-with-rocket-dark.png"
                        data-app-light-img="illustrations/boy-with-rocket-light.png">
                </div>
            </div>
            <div class="d-flex col-12 col-lg-6 col-xl-5 align-items-center bg-light p-sm-12 p-6">
                <div class="mx-auto mt-12 px-2 w-100" style="max-width: 600px;">
                    <div class="app-brand justify-content-center py-5 my-4">
                        <a href="#" class="app-brand-link gap-2">
                            <span class="app-brand-logo">
                                <img src="{{ asset('assets/img/logo/iphacon_logo.png') }}" alt="IPHACON 2027 Logo"
                                    width="155px">
                            </span>
                        </a>
                    </div>
                    <h4 class="mb-2">Welcome to IPHACON 2027! 👋</h4>
                    @yield('content')
                </div>
            </div>
        </div>
    </div>
    <script src="{{ asset('assets/admin/assets/vendor/libs/jquery/jquery.js') }}"></script>
    <script src="{{ asset('assets/admin/assets/vendor/libs/popper/popper.js') }}"></script>
    <script src="{{ asset('assets/admin/assets/vendor/js/bootstrap.js') }}"></script>
    <script src="{{ asset('assets/admin/assets/js/main.js') }}"></script>
    <script src="{{ asset('assets/admin/js/two-factor-auth-pages.js') }}"></script>
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

                @if(Session::has('success'))
                    toastr.success("{!! addslashes(Session::get('success')) !!}", "Success");
                @endif
                @if(Session::has('error'))
                    toastr.error("{!! addslashes(Session::get('error')) !!}", "Error");
                @endif
                @if(Session::has('info'))
                    toastr.info("{!! addslashes(Session::get('info')) !!}", "Notice");
                @endif
                @if(Session::has('warning'))
                    toastr.warning("{!! addslashes(Session::get('warning')) !!}", "Warning");
                @endif
                @if(isset($errors) && $errors->any())
                    @foreach($errors->all() as $error)
                        toastr.error("{!! addslashes($error) !!}", "Validation Error");
                    @endforeach
                @endif
            }
        });
    </script>
    @stack('scripts')
</body>

</html>
