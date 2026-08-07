@extends('shared.index')
@section('title', 'Verify Email OTP')

@php
    $inner_title = 'Email Verification via OTP';
    $currentEmail = old('email', session('email', Auth::user()?->email));
@endphp

@section('delegate-content')
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card shadow-lg border-0 overflow-hidden" style="border-radius: 16px;">
                
                <!-- Card Header -->
                <div class="card-header text-center py-3.5 px-3 border-0 position-relative"
                    style="background: linear-gradient(135deg, #161b40 0%, #1e255e 50%, #2e3192 100%);">
                    <div class="mb-2">
                        <div class="d-inline-flex align-items-center justify-content-center bg-white bg-opacity-10 text-white rounded-circle p-3 shadow-sm">
                            <i class="fas fa-shield-halved fa-2x"></i>
                        </div>
                    </div>
                    <h5 class="text-white fw-bold mb-1">Enter Verification Code</h5>
                    <p class="text-white-50 mb-0 extra-small">
                        Enter the 6-digit OTP sent to your registered email
                    </p>
                </div>

                <!-- Card Body -->
                <div class="card-body p-4 bg-white">

                    <!-- Alert Messages -->
                    @if (session('success'))
                        <div class="alert alert-custom-success alert-dismissible fade show p-2.5 mb-3 rounded-3 d-flex align-items-center gap-2" role="alert">
                            <i class="fas fa-check-circle text-success flex-shrink-0"></i>
                            <div class="extra-small fw-medium text-dark">{{ session('success') }}</div>
                            <button type="button" class="btn-close py-2 px-2" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="alert alert-custom-danger alert-dismissible fade show p-2.5 mb-3 rounded-3 d-flex align-items-center gap-2" role="alert">
                            <i class="fas fa-exclamation-triangle text-danger flex-shrink-0"></i>
                            <div class="extra-small text-dark">
                                @foreach ($errors->all() as $error)
                                    <div>{{ $error }}</div>
                                @endforeach
                            </div>
                            <button type="button" class="btn-close py-2 px-2" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <!-- Target Email Info Display Badge -->
                    <div class="email-notice-card p-2.5 px-3 mb-3.5 rounded-3 bg-light border text-center position-relative">
                        <span class="extra-small text-muted d-block mb-0.5">OTP code sent to email:</span>
                        <div class="d-flex align-items-center justify-content-center gap-1">
                            <i class="fas fa-envelope text-primary extra-small"></i>
                            <span class="fw-bold text-dark extra-small" id="display-email-text">
                                {{ $currentEmail ?: 'Click edit to set email' }}
                            </span>
                            <button type="button" class="btn btn-link p-0 ms-1 text-primary extra-small text-decoration-none" 
                                onclick="toggleEditEmail()" title="Change or Edit Email">
                                <i class="fas fa-pen-to-square"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Spam / Junk Folder Notice Box -->
                    <div class="p-2.5 px-3 mb-3 rounded-3 border text-start d-flex align-items-start gap-2.5" style="background-color: #F0F9FF; border-color: #BAE6FD !important;">
                        <i class="fas fa-info-circle text-primary mt-0.5" style="font-size: 0.9rem;"></i>
                        <div style="font-size: 0.78rem; line-height: 1.4; color: #0369A1;">
                            <strong>Note:</strong> If you do not receive the email in your primary Inbox, please check your <strong>Spam or Junk Mail folder</strong>.
                        </div>
                    </div>

                    <!-- OTP Submit Form -->
                    <form method="POST" action="{{ route('verification.verify-otp') }}" id="otpForm">
                        @csrf

                        <!-- Hidden Email Input (Auto populated) -->
                        <input type="hidden" id="email" name="email" value="{{ $currentEmail }}">

                        <!-- Optional Collapsible Email Edit Box (Hidden by default) -->
                        <div id="edit-email-box" class="mb-3 d-none">
                            <label for="visible_email" class="form-label fw-semibold text-dark extra-small mb-1">
                                Registered Email Address <span class="text-danger">*</span>
                            </label>
                            <div class="input-group modern-input-group">
                                <span class="input-group-text border-end-0 bg-light text-muted px-2.5">
                                    <i class="fas fa-envelope text-primary extra-small"></i>
                                </span>
                                <input type="email" class="form-control border-start-0 custom-input extra-small"
                                    id="visible_email" value="{{ $currentEmail }}" placeholder="enter email address">
                            </div>
                        </div>

                        <!-- OTP Code Input with Live Auto Status Icon -->
                        <div class="mb-3 position-relative">
                            <label for="otp" class="form-label fw-semibold text-dark extra-small mb-1 d-flex justify-content-between">
                                <span>Enter 6-Digit OTP Code <span class="text-danger">*</span></span>
                                <span class="text-muted extra-small">Valid for 15 mins</span>
                            </label>
                            <div class="input-group modern-input-group position-relative">
                                <span class="input-group-text border-end-0 bg-light text-muted px-2.5">
                                    <i class="fas fa-key text-primary extra-small"></i>
                                </span>
                                <input type="text"
                                    class="form-control border-start-0 custom-input text-center fw-bold letter-spacing-3 @error('otp') is-invalid @enderror"
                                    id="otp" name="otp" required maxlength="6" pattern="[0-9]{6}"
                                    placeholder="• • • • • •" autocomplete="one-time-code" autofocus
                                    style="font-size: 1.35rem; letter-spacing: 7px; padding-right: 42px;">

                                <!-- Live Auto-Verify Status Loader / Check Icon -->
                                <span id="otp-status-icon" class="position-absolute end-0 top-50 translate-middle-y me-3 z-3 d-none"></span>
                            </div>

                            <!-- Auto Verification Feedback Message -->
                            <div id="otp-ajax-feedback" class="extra-small mt-1.5 fw-semibold text-center d-none"></div>
                        </div>

                        <!-- Auto-Verify Info Pill (Replaces the big manual button) -->
                        <div class="text-center extra-small text-muted mb-3 py-1 bg-light rounded-2 border border-dashed">
                            <i class="fas fa-bolt text-warning me-1"></i> Auto-verifies automatically upon entering 6 digits
                        </div>

                        <!-- Hidden Fallback Submit Button -->
                        <button type="submit" id="submitBtn" class="d-none"></button>
                    </form>

                    <div class="text-center pt-1">
                        <p class="text-muted extra-small mb-1.5">Didn't receive the email OTP?</p>
                        
                        <!-- Resend OTP Form -->
                        <form method="POST" action="{{ route('verification.resend') }}" class="d-inline">
                            @csrf
                            <input type="hidden" name="email" id="resend_email" value="{{ $currentEmail }}">
                            <button type="submit" class="btn btn-sm btn-outline-primary rounded-pill px-3 py-1 extra-small fw-semibold">
                                <i class="fas fa-rotate-right me-1"></i>Resend New OTP Code
                            </button>
                        </form>
                    </div>

                    <hr class="my-3 opacity-25">

                    <div class="text-center extra-small text-muted">
                        Already verified? <a href="{{ route('login') }}" class="text-primary fw-bold text-decoration-none">Sign In Here</a>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleEditEmail() {
            const box = document.getElementById('edit-email-box');
            if (box) {
                box.classList.toggle('d-none');
                const visibleEmail = document.getElementById('visible_email');
                if (visibleEmail && !box.classList.contains('d-none')) {
                    visibleEmail.focus();
                }
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            const emailInput = document.getElementById('email');
            const visibleEmail = document.getElementById('visible_email');
            const resendEmailInput = document.getElementById('resend_email');
            const displayEmailText = document.getElementById('display-email-text');
            const otpInput = document.getElementById('otp');
            const statusIcon = document.getElementById('otp-status-icon');
            const feedback = document.getElementById('otp-ajax-feedback');

            let isVerifying = false;

            if (visibleEmail) {
                visibleEmail.addEventListener('input', function() {
                    if (emailInput) emailInput.value = this.value;
                    if (resendEmailInput) resendEmailInput.value = this.value;
                    if (displayEmailText) displayEmailText.textContent = this.value || 'Click edit to set email';
                });
            }

            if (otpInput) {
                otpInput.addEventListener('input', function() {
                    // Numbers only
                    this.value = this.value.replace(/[^0-9]/g, '');

                    if (this.value.length < 6) {
                        if (statusIcon) statusIcon.classList.add('d-none');
                        if (feedback) feedback.classList.add('d-none');
                        otpInput.classList.remove('is-invalid', 'is-valid');
                    }

                    // Auto-verify on 6 digits
                    if (this.value.length === 6 && !isVerifying) {
                        autoVerifyOtp();
                    }
                });
            }

            function autoVerifyOtp() {
                const email = emailInput ? emailInput.value.trim() : '';
                const otp = otpInput ? otpInput.value.trim() : '';

                if (!email) {
                    toggleEditEmail();
                    if (feedback) {
                        feedback.className = 'extra-small mt-1.5 text-danger fw-semibold text-center';
                        feedback.innerHTML = '<i class="fas fa-exclamation-circle me-1"></i> Please enter your registered email address.';
                        feedback.classList.remove('d-none');
                    }
                    return;
                }

                if (otp.length !== 6) return;

                isVerifying = true;

                // Show spinner loader on the right side of the input box
                if (statusIcon) {
                    statusIcon.className = 'position-absolute end-0 top-50 translate-middle-y me-3 z-3';
                    statusIcon.innerHTML = '<i class="fas fa-spinner fa-spin text-primary fs-5"></i>';
                    statusIcon.classList.remove('d-none');
                }

                if (feedback) {
                    feedback.className = 'extra-small mt-1.5 text-primary fw-semibold text-center';
                    feedback.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Verifying OTP...';
                    feedback.classList.remove('d-none');
                }

                const csrfToken = document.querySelector('input[name="_token"]')?.value || 
                                  document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

                fetch('{{ route("verification.verify-otp") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({ email: email, otp: otp, _token: csrfToken })
                })
                .then(response => response.json().then(data => ({ status: response.status, body: data })))
                .then(res => {
                    if (res.body.success) {
                        if (statusIcon) {
                            statusIcon.innerHTML = '<i class="fas fa-circle-check text-success fs-5"></i>';
                        }
                        if (feedback) {
                            feedback.className = 'extra-small mt-1.5 text-success fw-bold text-center';
                            feedback.innerHTML = '<i class="fas fa-check-circle me-1"></i> OTP Verified! Redirecting...';
                        }
                        otpInput.classList.remove('is-invalid');
                        otpInput.classList.add('is-valid');

                        setTimeout(function() {
                            window.location.href = res.body.redirect || '{{ route("login") }}';
                        }, 800);
                    } else {
                        isVerifying = false;

                        if (statusIcon) {
                            statusIcon.innerHTML = '<i class="fas fa-circle-xmark text-danger fs-5"></i>';
                        }
                        if (feedback) {
                            feedback.className = 'extra-small mt-1.5 text-danger fw-semibold text-center';
                            feedback.innerHTML = '<i class="fas fa-times-circle me-1"></i> ' + (res.body.message || 'Invalid OTP code.');
                        }
                        otpInput.classList.remove('is-valid');
                        otpInput.classList.add('is-invalid');
                    }
                })
                .catch(err => {
                    isVerifying = false;
                    if (statusIcon) statusIcon.classList.add('d-none');
                    if (feedback) {
                        feedback.className = 'extra-small mt-1.5 text-danger fw-semibold text-center';
                        feedback.innerHTML = '<i class="fas fa-exclamation-circle me-1"></i> Verification error. Please try again.';
                    }
                });
            }
        });
    </script>
@endsection
