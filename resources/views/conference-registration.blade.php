<x-layout>
    <x-slot:title>Delegate Registration | IPHACON 2027</x-slot:title>

    <div class="container py-4 py-md-5">
        <div class="row justify-content-center">
            <div class="col-12 col-md-10 col-lg-8 col-xl-7">
                <div class="card shadow-lg border-0 overflow-hidden" style="border-radius: 16px; background: #ffffff;">
                    <!-- Header -->
                    <div class="card-header text-center py-3.5 px-4 border-0 text-white"
                        style="background: linear-gradient(135deg, #1e255e, #2e3192, #4a5bcc);">
                        <div class="d-flex align-items-center justify-content-center gap-2.5">
                            <div class="header-icon-box bg-white bg-opacity-20 rounded-circle d-flex align-items-center justify-content-center"
                                style="width: 46px; height: 46px;">
                                <i class="fas fa-user-plus text-white fs-5"></i>
                            </div>
                            <div class="text-start">
                                <h5 class="text-white mb-0 fw-bold" style="letter-spacing: 0.3px;">Delegate Registration</h5>
                                <small class="text-white-50" style="font-size: 0.85rem;">IPHACON 2027 • RIMS, Ranchi</small>
                            </div>
                        </div>
                    </div>

                    <!-- Body -->
                    <div class="card-body p-4 p-md-5">
                        <!-- Alert Messages -->
                        @if (
                            $errors->any() &&
                                !$errors->has('delegate_type') &&
                                !$errors->has('country_id') &&
                                !$errors->has('email') &&
                                !$errors->has('password') &&
                                !$errors->has('captcha'))
                            <div class="alert alert-danger alert-dismissible fade show py-2.5 px-3 small mb-4" role="alert"
                                style="border-radius: 10px;">
                                @foreach ($errors->all() as $error)
                                    <div><i class="fas fa-exclamation-circle me-1"></i>{{ $error }}</div>
                                @endforeach
                                <button type="button" class="btn-close py-2.5" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show py-2.5 px-3 small mb-4" role="alert"
                                style="border-radius: 10px;">
                                <i class="fas fa-check-circle me-1"></i>{{ session('success') }}
                                <button type="button" class="btn-close py-2.5" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('register') }}" id="registrationForm">
                            @csrf

                            <div class="row g-3 g-md-4">
                                <!-- Delegate Type Selector -->
                                <div class="col-12 mb-1">
                                    <label class="form-label fw-bold text-dark mb-2">
                                        <i class="fas fa-globe text-primary me-1.5"></i>Delegate Category <span class="text-danger">*</span>
                                    </label>
                                    <div class="row g-3">
                                        <div class="col-6">
                                            <label class="delegate-type-card d-flex align-items-center justify-content-center p-3 rounded border cursor-pointer w-100 mb-0"
                                                for="indian" id="card-indian">
                                                <input class="form-check-input d-none" type="radio" name="delegate_type"
                                                    id="indian" value="Indian"
                                                    {{ old('delegate_type') == 'Indian' ? 'checked' : '' }} required>
                                                <i class="fas fa-flag text-success me-2.5 fs-5"></i>
                                                <div>
                                                    <span class="d-block fw-bold text-dark leading-tight">Indian</span>
                                                    <small class="text-muted d-block" style="font-size: 0.78rem;">Domestic Delegate</small>
                                                </div>
                                            </label>
                                        </div>
                                        <div class="col-6">
                                            <label class="delegate-type-card d-flex align-items-center justify-content-center p-3 rounded border cursor-pointer w-100 mb-0"
                                                for="international" id="card-international">
                                                <input class="form-check-input d-none" type="radio" name="delegate_type"
                                                    id="international" value="International"
                                                    {{ old('delegate_type') == 'International' ? 'checked' : '' }} required>
                                                <i class="fas fa-globe-americas text-primary me-2.5 fs-5"></i>
                                                <div>
                                                    <span class="d-block fw-bold text-dark leading-tight">International</span>
                                                    <small class="text-muted d-block" style="font-size: 0.78rem;">Foreign Delegate</small>
                                                </div>
                                            </label>
                                        </div>
                                    </div>
                                    @error('delegate_type')
                                        <div class="text-danger mt-1.5 small"><i class="fas fa-exclamation-circle me-1"></i>{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Country Select -->
                                <div class="col-12 col-md-6">
                                    <label for="country_id" class="form-label fw-bold text-dark mb-1.5 small">
                                        <i class="fas fa-map-marker-alt text-primary me-1"></i>Country <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0 text-muted px-3">
                                            <i class="fas fa-flag"></i>
                                        </span>
                                        <select class="form-select border-start-0 @error('country_id') is-invalid @enderror"
                                            id="country_id" name="country_id" required style="height: 45px; font-size: 0.9rem;">
                                            <option value="">Select Country</option>
                                            @foreach ($countries as $country)
                                                <option value="{{ $country->id }}"
                                                    {{ old('country_id') == $country->id ? 'selected' : '' }}>
                                                    {{ $country->country_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @error('country_id')
                                        <div class="invalid-feedback d-block small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Email Input -->
                                <div class="col-12 col-md-6">
                                    <label for="email" class="form-label fw-bold text-dark mb-1.5 small">
                                        <i class="fas fa-envelope text-primary me-1"></i>Email Address <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0 text-muted px-3">
                                            <i class="fas fa-envelope"></i>
                                        </span>
                                        <input type="email" class="form-control border-start-0 @error('email') is-invalid @enderror"
                                            id="email" name="email" value="{{ old('email') }}" required
                                            placeholder="name@example.com" style="height: 45px; font-size: 0.9rem;">
                                    </div>
                                    @error('email')
                                        <div class="invalid-feedback d-block small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Password Input -->
                                <div class="col-12 col-md-6">
                                    <label for="password" class="form-label fw-bold text-dark mb-1.5 small">
                                        <i class="fas fa-lock text-primary me-1"></i>Password <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0 text-muted px-3">
                                            <i class="fas fa-lock"></i>
                                        </span>
                                        <input type="password"
                                            class="form-control border-start-0 border-end-0 @error('password') is-invalid @enderror"
                                            id="password" name="password" required placeholder="Min 8 chars" style="height: 45px; font-size: 0.9rem;">
                                        <button class="btn btn-outline-secondary border-start-0 bg-light text-muted px-3" type="button"
                                            onclick="togglePassword()" style="height: 45px;">
                                            <i class="fas fa-eye" id="toggleIcon"></i>
                                        </button>
                                    </div>
                                    @error('password')
                                        <div class="invalid-feedback d-block small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Confirm Password Input -->
                                <div class="col-12 col-md-6">
                                    <label for="password_confirmation" class="form-label fw-bold text-dark mb-1.5 small">
                                        <i class="fas fa-shield-alt text-primary me-1"></i>Confirm Password <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0 text-muted px-3">
                                            <i class="fas fa-lock"></i>
                                        </span>
                                        <input type="password"
                                            class="form-control border-start-0 @error('password_confirmation') is-invalid @enderror"
                                            id="password_confirmation" name="password_confirmation" required
                                            placeholder="Repeat password" style="height: 45px; font-size: 0.9rem;">
                                    </div>
                                    @error('password_confirmation')
                                        <div class="invalid-feedback d-block small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- CAPTCHA Section -->
                                <div class="col-12 my-1">
                                    <div class="p-3 rounded bg-light border">
                                        <label for="captcha" class="form-label fw-bold text-dark mb-2 small d-block">
                                            <i class="fas fa-robot text-primary me-1"></i>Security Verification <span class="text-danger">*</span>
                                        </label>
                                        <div class="row g-2.5 align-items-center">
                                            <div class="col-6 col-sm-5 d-flex align-items-center">
                                                <img src="{{ captcha_src() }}" alt="CAPTCHA" id="captchaImage"
                                                    class="img-fluid rounded cursor-pointer border w-100"
                                                    onclick="refreshCaptcha()" title="Click to refresh CAPTCHA"
                                                    style="height: 44px; object-fit: cover;">
                                                <button type="button" class="btn btn-sm btn-link text-primary p-1.5 ms-1"
                                                    onclick="refreshCaptcha()" title="Refresh Code">
                                                    <i class="fas fa-sync-alt fs-6" id="captchaSpinner"></i>
                                                </button>
                                            </div>
                                            <div class="col-6 col-sm-7">
                                                <input type="text"
                                                    class="form-control @error('captcha') is-invalid @enderror text-center fw-bold"
                                                    id="captcha" name="captcha" required placeholder="Enter Code"
                                                    style="height: 44px; font-size: 0.95rem; letter-spacing: 2px;">
                                            </div>
                                        </div>
                                        @error('captcha')
                                            <div class="invalid-feedback d-block small text-center mt-1.5">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Submit Button -->
                                <div class="col-12 mt-3">
                                    <button type="submit" class="btn btn-primary fw-bold w-100 py-2.5 shadow-sm"
                                        style="background: linear-gradient(135deg, #2e3192, #4a5bcc); border: none; border-radius: 10px; font-size: 1rem;">
                                        <i class="fas fa-user-plus me-2"></i>Create Account
                                    </button>
                                </div>

                                <!-- Already Have Account -->
                                <div class="col-12 text-center my-1">
                                    <span class="text-muted small">Already registered? </span>
                                    <a href="{{ route('login') }}" class="text-decoration-none fw-bold small" style="color: #2e3192;">
                                        Sign In Here <i class="fas fa-arrow-right ms-1"></i>
                                    </a>
                                </div>

                                <!-- Info Banner -->
                                <div class="col-12 mt-2">
                                    <div class="p-2.5 px-3 rounded border-0 d-flex align-items-center gap-2.5"
                                        style="background-color: #f0f4ff; color: #1e255e; font-size: 0.82rem;">
                                        <i class="fas fa-info-circle text-primary fs-6 flex-shrink-0"></i>
                                        <span><strong>Note:</strong> Complete your profile & registration after email verification.</span>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Custom CSS for Sleek Compact Design -->
    <style>
        .delegate-type-card {
            background-color: #f8f9fa;
            transition: all 0.2s ease-in-out;
            border-color: #dee2e6 !important;
            user-select: none;
        }

        .delegate-type-card:hover {
            border-color: #2e3192 !important;
            background-color: rgba(46, 49, 146, 0.04);
        }

        .delegate-type-card.active {
            border-color: #2e3192 !important;
            background-color: rgba(46, 49, 146, 0.08) !important;
            box-shadow: 0 0 0 2px rgba(46, 49, 146, 0.25);
        }

        .input-group-text {
            background-color: #f8f9fa;
        }

        .form-control:focus, .form-select:focus {
            border-color: #2e3192;
            box-shadow: 0 0 0 0.15rem rgba(46, 49, 146, 0.2);
        }

        .cursor-pointer {
            cursor: pointer;
        }

        .leading-tight {
            line-height: 1.2;
        }

        @media (max-width: 575.98px) {
            .card-body {
                padding: 1rem !important;
            }
        }
    </style>

    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const toggleIcon = document.getElementById('toggleIcon');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.classList.replace('fa-eye', 'fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                toggleIcon.classList.replace('fa-eye-slash', 'fa-eye');
            }
        }

        function refreshCaptcha() {
            const spinner = document.getElementById('captchaSpinner');
            if (spinner) spinner.classList.add('fa-spin');
            document.getElementById('captchaImage').src = '{{ captcha_src() }}?' + new Date().getTime();
            document.getElementById('captcha').value = '';
            setTimeout(() => {
                if (spinner) spinner.classList.remove('fa-spin');
            }, 600);
        }

        // Delegate type card highlight & Country selection logic
        document.addEventListener('DOMContentLoaded', function() {
            const delegateRadios = document.querySelectorAll('input[name="delegate_type"]');
            const countrySelect = document.getElementById('country_id');

            function updateCardState() {
                delegateRadios.forEach(radio => {
                    const card = document.getElementById('card-' + radio.id);
                    if (card) {
                        if (radio.checked) {
                            card.classList.add('active');
                        } else {
                            card.classList.remove('active');
                        }
                    }
                });
            }

            // Initial active state
            updateCardState();

            delegateRadios.forEach(radio => {
                radio.addEventListener('change', function() {
                    updateCardState();

                    if (this.value === 'Indian') {
                        // Auto-select India
                        for (let option of countrySelect.options) {
                            if (option.text.includes('India')) {
                                option.selected = true;
                                break;
                            }
                        }
                        countrySelect.style.backgroundColor = '#e9ecef';
                        countrySelect.style.color = '#495057';
                        countrySelect.title = 'Auto-selected for Indian delegates';
                    } else {
                        countrySelect.selectedIndex = 0;
                        countrySelect.style.backgroundColor = '';
                        countrySelect.style.color = '';
                        countrySelect.title = '';
                    }
                });
            });

            // Password confirmation check
            const passwordInput = document.getElementById('password');
            const confirmInput = document.getElementById('password_confirmation');

            if (confirmInput && passwordInput) {
                confirmInput.addEventListener('input', function() {
                    if (this.value === passwordInput.value && this.value.length > 0) {
                        this.classList.remove('is-invalid');
                        this.classList.add('is-valid');
                    } else if (this.value.length > 0) {
                        this.classList.remove('is-valid');
                        this.classList.add('is-invalid');
                    }
                });
            }

            // Auto-focus next field on mobile
            const inputs = document.querySelectorAll('input, select');
            inputs.forEach((input, index) => {
                input.addEventListener('keypress', function(e) {
                    if (e.key === 'Enter' && index < inputs.length - 1) {
                        e.preventDefault();
                        inputs[index + 1].focus();
                    }
                });
            });
        });
    </script>
</x-layout>
