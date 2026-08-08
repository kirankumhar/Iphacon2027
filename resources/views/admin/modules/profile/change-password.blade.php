{{-- {{dd(auth('admin')->user()->mobile_no);}} --}}
@extends('admin.layouts.main')

@section('admin-content')
    <style>
        .user-profile-header-banner img {
            width: 100%;
            object-fit: cover;
            height: 120px
        }

        .user-profile-header {
            margin-top: -2rem
        }

        .user-profile-header .user-profile-img {
            border: 5px solid;
            width: 120px
        }

        .light-style .user-profile-header .user-profile-img {
            border-color: #fff
        }

        .dark-style .user-profile-header .user-profile-img {
            border-color: #2b2c40
        }

        .dataTables_wrapper .card-header .dataTables_filter label {
            margin-top: 0 !important;
            margin-bottom: 0 !important
        }

        @media(max-width: 767.98px) {
            .user-profile-header-banner img {
                height: 150px
            }

            .user-profile-header .user-profile-img {
                width: 100px
            }
        }
    </style>
    {{-- <link rel="stylesheet" href="{{ asset('assets/admin/core1.css') }}" /> --}}

    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row fv-plugins-icon-container">
            <div class="col-md-12">
                <div class="nav-align-top mb-3">
                    <ul class="nav nav-pills flex-column flex-md-row mb-6">
                        <li class="nav-item"><a class="nav-link text-white" href="{{ route('profile.my-setting') }}"><i
                                    class="bx bx-sm bx-user me-1_5"></i> Account</a></li>
                        <li class="nav-item"><a class="nav-link active" href="javascript:void(0);"><i
                                    class="bx bx-sm bx-lock-alt me-1_5"></i> Security</a></li>
                    </ul>
                </div>
                <div class="card mb-6">
                    <h5 class="card-header my-3">Change Password</h5>
                    <div class="card-body pt-1">
                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible mx-3" role="alert">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif

                        <form id="formAccountSettings" method="POST" class="fv-plugins-bootstrap5 fv-plugins-framework"
                            novalidate="novalidate">
                            <div class="row">
                                <div class="mb-6 col-md-6 form-password-toggle fv-plugins-icon-container">
                                    <label class="form-label" for="newPassword">New Password</label>
                                    <div class="input-group input-group-merge has-validation">
                                        <input class="form-control" type="password" id="newPassword" name="newPassword"
                                            placeholder="············">
                                        <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                                    </div>
                                    <div
                                        class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                                    </div>
                                </div>

                                <div class="mb-6 col-md-6 form-password-toggle fv-plugins-icon-container">
                                    <label class="form-label" for="confirmPassword">Confirm New Password</label>
                                    <div class="input-group input-group-merge has-validation">
                                        <input class="form-control" type="password" name="confirmPassword"
                                            id="confirmPassword" placeholder="············">
                                        <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                                    </div>
                                    <div
                                        class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                                    </div>
                                </div>
                            </div>
                            <h6 class="text-body mt-4">Password Requirements:</h6>
                            <ul class="ps-4 mb-4">
                                <span id="passwordHelpBlock" class="form-text text-muted">
                                </span>
                            </ul>
                            <div>
                                <!-- Button trigger modal -->
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                    data-bs-target="#otpModal" id="proceedBtn">
                                    Proceed
                                </button>

                                <!-- Modal -->
                                <div class="modal fade" id="otpModal" data-bs-backdrop="static" data-bs-keyboard="false"
                                    tabindex="-1" aria-labelledby="otpModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h1 class="modal-title fs-5" id="otpModalLabel">Change Password OTP
                                                </h1>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                Dear Admin, The chnage password request otp Send to Your Mobile No.:
                                                <strong> {{ auth()->user()->mobile_no }}</strong>
                                                <div class="mb-6 fv-plugins-icon-container">
                                                    <div
                                                        class="auth-input-wrapper d-flex align-items-center justify-content-between numeral-mask-wrapper">
                                                        <input type="tel"
                                                            class="form-control auth-input h-px-50 text-center numeral-mask mx-sm-1 my-2"
                                                            maxlength="1"
                                                            oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 1)"
                                                            autofocus="">
                                                        <input type="tel"
                                                            class="form-control auth-input h-px-50 text-center numeral-mask mx-sm-1 my-2"
                                                            oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 1)"
                                                            maxlength="1">
                                                        <input type="tel"
                                                            class="form-control auth-input h-px-50 text-center numeral-mask mx-sm-1 my-2"
                                                            oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 1)"
                                                            maxlength="1">
                                                        <input type="tel"
                                                            class="form-control auth-input h-px-50 text-center numeral-mask mx-sm-1 my-2"
                                                            oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 1)"
                                                            maxlength="1">
                                                        <input type="tel"
                                                            class="form-control auth-input h-px-50 text-center numeral-mask mx-sm-1 my-2"
                                                            oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 1)"
                                                            maxlength="1">
                                                        <input type="tel"
                                                            class="form-control auth-input h-px-50 text-center numeral-mask mx-sm-1 my-2"
                                                            oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 1)"
                                                            maxlength="1">
                                                    </div>
                                                    <span class="mb-3 text-danger" id="verifyFeed"></span>
                                                    <input type="hidden" name="otp" id="otp">
                                                    <div
                                                        class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                                                    </div>
                                                </div>
                                                <button type="button" id="submitBtn"
                                                    class="btn btn-primary d-grid mt-3 w-100 py-6">
                                                    Change Password
                                                </button>
                                                <div class="text-center">Didn't get the code?
                                                    <a href="javascript:void(0);" id="resend"
                                                        style="pointer-events: none; opacity: 0.5;" class="disabled">
                                                        Resend
                                                    </a><br>
                                                    <span id="countdown"></span>
                                                </div>
                                                <input type="hidden" name="mobileNo" id="mobileNo"
                                                    value="{{ auth('admin')->user()->mobile_no }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <!-- /Account -->
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script src="{{ asset('assets/admin/js/two-factor-auth-pages.js') }}"></script>
    <script>
        $(document).ready(function() {
            const $password = $('#newPassword');
            const $confirmPassword = $('#confirmPassword');
            const $submitBtn = $('#submitBtn');
            const $proceedBtn = $('#proceedBtn');
            const mobile = $('#mobileNo').val();
            const $passwordHelp = $("#passwordHelpBlock");
            const $passwordFeedback = $('<div id="passwordFeedback"></div>').insertBefore($passwordHelp);


            $("#submitBtn").attr('disabled', true);

            function validatePassword() {
                const password = $password.val();
                const confirmPassword = $confirmPassword.val();

                const conditions = [{
                        regex: /.{8,}/,
                        message: "At least 8 characters."
                    },
                    {
                        regex: /[A-Z]/,
                        message: "At least one uppercase letter."
                    },
                    {
                        regex: /[a-z]/,
                        message: "At least one lowercase letter."
                    },
                    {
                        regex: /[0-9]/,
                        message: "At least one number."
                    },
                    {
                        regex: /[!@#$]/,
                        message: "At least one special character (!, @, #, $)."
                    }

                ];

                let isValid = true;
                let feedbackHtml = '';

                conditions.forEach(condition => {
                    const meetsCondition = condition.regex.test(password);
                    isValid = isValid && meetsCondition;
                    feedbackHtml += `<div class="${meetsCondition ? 'text-success' : 'text-danger'}">
                        <i class="fas fa-${meetsCondition ? 'check' : 'times'}"></i> ${condition.message}
                     </div>`;
                });

                $passwordHelp.html(feedbackHtml);

                const doMatch = password === confirmPassword;
                $confirmPassword.toggleClass('is-invalid', !doMatch && confirmPassword.length > 0);

                if (confirmPassword.length > 0) {
                    feedbackHtml += `<div class="${doMatch ? 'text-success' : 'text-danger'}">
                        <i class="fas fa-${doMatch ? 'check' : 'times'}"></i> Passwords match
                     </div>`;
                }

                $passwordHelp.html(feedbackHtml);
                $submitBtn.prop('disabled', !(isValid && doMatch));
                $proceedBtn.prop('disabled', !(isValid && doMatch));
            }

            $password.on('input', validatePassword);
            $confirmPassword.on('input', validatePassword);

            validatePassword();

            $(document).on('click', '#proceedBtn, #resend', function() {
                $("#jspc-jspc-loader").removeClass('d-none');

                $("#submitBtn").prop('disabled', true);

                fetch("/admin/updateAdminOTP/password", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        body: JSON.stringify({
                            mobile_number: mobile
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        $("#jspc-loader").addClass('d-none');
                        if (data.type === 'success') {
                            $('.numeral-mask').val('');
                        } else if (data.type === 'exists') {
                            $("#mobile_no").addClass("is-invalid");
                            $('#mobile_no').siblings('.invalid-feedback').text(data.message);
                        }
                    })
                    .catch(error => {
                        $("#jspc-loader").addClass('d-none');
                    });
            });

            $('.numeral-mask').val('');

            $('.numeral-mask').on('keyup', function(e) {
                var otp = $("#otp").val();
                if (otp.length == 6) {
                    verifyOTP();
                }
            });

            // Verify OTP
            function verifyOTP() {
                var mobileNumber = mobile;
                $(".numeral-mask").removeClass("is-invalid");
                var otp = $("#otp").val();

                fetch('/admin/admin-pass-verify-otp', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        body: JSON.stringify({
                            otp: otp,
                            mobile_number: mobileNumber
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.type === 'error') {
                            $(".numeral-mask").addClass('is-invalid').removeClass('is-valid');
                            $("#verifyFeed").html(data.message);
                        } else {
                            $(".numeral-mask").addClass('is-valid').removeClass('is-invalid');
                            $("#verifyFeed").html('');
                            $(".numeral-mask").prop('disabled', true);

                            $("#submitBtn").prop('disabled', false);
                        }
                    })
                    .catch(error => {
                        // console.error('Error:', error);
                        // $("#mobileOtp").addClass('is-valid').removeClass('is-invalid');
                        // $("#verifyFeed").html(data.message);
                        // $("#submitBtn").prop('disabled', false);
                    });

            }

            function resendOTPCounter() {
                var countdownDuration = 2 * 60; // 2 minutes in seconds
                var $countdownElement = $('#countdown');
                var $resendElement = $('#resend');

                var countdownInterval = setInterval(function() {
                    var minutes = Math.floor(countdownDuration / 60);
                    var seconds = countdownDuration % 60;
                    $countdownElement.text(
                        "Resend available in " +
                        (minutes < 10 ? '0' + minutes : minutes) + ":" +
                        (seconds < 10 ? '0' + seconds : seconds)
                    );
                    countdownDuration--;
                    if (countdownDuration < 0) {
                        clearInterval(countdownInterval);
                        $countdownElement.text(''); // Clear the countdown text
                        $resendElement.removeClass('disabled').css({
                            'pointer-events': 'auto',
                            'opacity': '1'
                        });
                    }
                }, 1000);
            }

            $(document).on('click', '#proceedBtn, #resend', function() {
                $("#jspc-loader").removeClass('d-none');

                fetch("/admin/updateAdminOTP/password", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        body: JSON.stringify({
                            mobile_number: mobile
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        $("#jspc-loader").addClass('d-none');
                        if (data.type === 'success') {
                            $('.numeral-mask').val('');
                            resendOTPCounter();
                        } else if (data.type === 'exists') {
                            $("#mobile_no").addClass("is-invalid");
                            $('#mobile_no').siblings('.invalid-feedback').text(data.message);
                        }
                    })
                    .catch(error => {
                        $("#jspc-loader").addClass('d-none');
                    });
            });
        });
    </script>

    <script>
        $(document).ready(function() {
            $(document).on('click', '#submitBtn', function(e) {
                e.preventDefault();
                $("#jspc-loader").removeClass('d-none');
                var formData = $('#formAccountSettings').serialize();

                $.ajax({
                    url: "{{ route('update.admin-password') }}",
                    method: "POST",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: formData,
                    dataType: 'json',
                    success: function(data) {
                        $("#jspc-loader").addClass('d-none');
                        if (data.type === 'success') {
                            location.reload();
                        } else if (data.type === 'error') {
                            alert(data.message || 'An error occurred. Please try again.');
                        }
                    },
                    error: function(xhr, status, error) {
                        $("#jspc-loader").addClass('d-none');
                        console.error(xhr.responseText);
                        alert('An error occurred. Please try again.');
                    }
                });
            });
        });
    </script>
@endpush
