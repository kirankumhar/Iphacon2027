<x-layout>
    <x-slot:title>Delegate Registration | IPHACON 2027</x-slot:title>

    <div class="registration-page-wrapper py-3 py-md-4">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-12 col-md-8 col-lg-6 col-xl-5">

                    <!-- Compact Registration Card -->
                    <div class="card registration-card shadow-lg border-0 overflow-hidden">
                        
                        <!-- Accent Bar -->
                        <div class="card-accent-bar"></div>

                        <!-- Compact Card Header -->
                        <div class="card-header text-center py-3 px-3.5 border-0 position-relative">
                            <div class="header-bg-glow"></div>
                            
                            <div class="position-relative z-1">
                                <div class="d-inline-flex align-items-center justify-content-center mb-1 px-3 py-1 rounded-pill header-badge-pill extra-small fw-semibold">
                                    <i class="fas fa-award text-warning me-1.5"></i> <span>71<sup>st</sup> Annual National Conference</span>
                                </div>
                                <h5 class="text-white fw-bold mb-0.5 tracking-wide">Delegate Registration 2027</h5>
                                <p class="text-white-80 mb-0 extra-small">
                                    <i class="fas fa-map-marker-alt me-1 text-danger-light"></i> IPHACON 2027 • RIMS, Ranchi
                                </p>
                            </div>
                        </div>

                        <!-- Compact Card Body -->
                        <div class="card-body p-3.5 p-md-4 bg-white">

                            <!-- Alert Messages -->
                            @if (
                                $errors->any() &&
                                    !$errors->has('delegate_type') &&
                                    !$errors->has('country_id') &&
                                    !$errors->has('email') &&
                                    !$errors->has('password') &&
                                    !$errors->has('captcha'))
                                <div class="alert alert-custom-danger alert-dismissible fade show p-2.5 mb-3 rounded-3 d-flex align-items-center gap-2" role="alert">
                                    <i class="fas fa-exclamation-triangle small text-danger flex-shrink-0"></i>
                                    <div class="extra-small text-dark">
                                        @foreach ($errors->all() as $error)
                                            <div>{{ $error }}</div>
                                        @endforeach
                                    </div>
                                    <button type="button" class="btn-close py-2 px-2" data-bs-dismiss="alert"></button>
                                </div>
                            @endif

                            @if (session('success'))
                                <div class="alert alert-custom-success alert-dismissible fade show p-2.5 mb-3 rounded-3 d-flex align-items-center gap-2" role="alert">
                                    <i class="fas fa-check-circle small text-success flex-shrink-0"></i>
                                    <div class="extra-small fw-medium text-dark">{{ session('success') }}</div>
                                    <button type="button" class="btn-close py-2 px-2" data-bs-dismiss="alert"></button>
                                </div>
                            @endif

                            <form method="POST" action="{{ route('register') }}" id="registrationForm" class="needs-validation" novalidate>
                                @csrf

                                <div class="row g-2.5">

                                    <!-- Delegate Category Selector -->
                                    <div class="col-12 mb-1">
                                        <label class="form-label font-heading fw-bold text-navy mb-1.5 extra-small uppercase-label">
                                            <i class="fas fa-globe-asia text-primary me-1"></i>Delegate Category <span class="text-danger">*</span>
                                        </label>

                                        <div class="row g-2">
                                            <!-- Indian Delegate Option -->
                                            <div class="col-6">
                                                <label class="delegate-type-card position-relative d-flex align-items-center p-2 rounded-3 border cursor-pointer w-100 h-100 transition-all"
                                                    for="indian" id="card-indian">
                                                    <input class="form-check-input d-none" type="radio" name="delegate_type"
                                                        id="indian" value="Indian"
                                                        {{ old('delegate_type') == 'Indian' ? 'checked' : '' }} required>
                                                    
                                                    <div class="card-radio-icon rounded-circle d-flex align-items-center justify-content-center me-2 bg-success-subtle text-success">
                                                        <i class="fas fa-flag extra-small"></i>
                                                    </div>
                                                    
                                                    <div class="flex-grow-1 min-w-0">
                                                        <span class="d-block fw-bold text-dark small leading-tight mb-0">Indian</span>
                                                        <span class="text-muted d-block extra-small text-truncate">Domestic</span>
                                                    </div>

                                                    <div class="selected-badge position-absolute top-0 end-0 m-1.5 d-none">
                                                        <i class="fas fa-check-circle text-primary extra-small"></i>
                                                    </div>
                                                </label>
                                            </div>

                                            <!-- International Delegate Option -->
                                            <div class="col-6">
                                                <label class="delegate-type-card position-relative d-flex align-items-center p-2 rounded-3 border cursor-pointer w-100 h-100 transition-all"
                                                    for="international" id="card-international">
                                                    <input class="form-check-input d-none" type="radio" name="delegate_type"
                                                        id="international" value="International"
                                                        {{ old('delegate_type') == 'International' ? 'checked' : '' }} required>
                                                    
                                                    <div class="card-radio-icon rounded-circle d-flex align-items-center justify-content-center me-2 bg-primary-subtle text-primary">
                                                        <i class="fas fa-globe-americas extra-small"></i>
                                                    </div>
                                                    
                                                    <div class="flex-grow-1 min-w-0">
                                                        <span class="d-block fw-bold text-dark small leading-tight mb-0">International</span>
                                                        <span class="text-muted d-block extra-small text-truncate">Foreign</span>
                                                    </div>

                                                    <div class="selected-badge position-absolute top-0 end-0 m-1.5 d-none">
                                                        <i class="fas fa-check-circle text-primary extra-small"></i>
                                                    </div>
                                                </label>
                                            </div>
                                        </div>
                                        @error('delegate_type')
                                            <div class="text-danger mt-1 extra-small"><i class="fas fa-exclamation-circle me-1"></i>{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Country Select -->
                                    <div class="col-12">
                                        <label for="country_id" class="form-label fw-semibold text-dark extra-small mb-1">
                                            Country of Origin <span class="text-danger">*</span>
                                        </label>
                                        <div class="input-group modern-input-group">
                                            <span class="input-group-text border-end-0 bg-light text-muted px-2.5">
                                                <i class="fas fa-flag text-primary extra-small"></i>
                                            </span>
                                            <select class="form-select border-start-0 custom-input @error('country_id') is-invalid @enderror"
                                                id="country_id" name="country_id" required>
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
                                            <div class="invalid-feedback d-block extra-small mt-1"><i class="fas fa-exclamation-circle me-1"></i>{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Email Input -->
                                    <div class="col-12">
                                        <label for="email" class="form-label fw-semibold text-dark extra-small mb-1">
                                            Email Address <span class="text-danger">*</span>
                                        </label>
                                        <div class="input-group modern-input-group">
                                            <span class="input-group-text border-end-0 bg-light text-muted px-2.5">
                                                <i class="fas fa-envelope text-primary extra-small"></i>
                                            </span>
                                            <input type="email" class="form-control border-start-0 custom-input @error('email') is-invalid @enderror"
                                                id="email" name="email" value="{{ old('email') }}" required
                                                placeholder="e.g. doctor@hospital.com">
                                        </div>
                                        @error('email')
                                            <div class="invalid-feedback d-block extra-small mt-1"><i class="fas fa-exclamation-circle me-1"></i>{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Password Input -->
                                    <div class="col-12 col-sm-6">
                                        <label for="password" class="form-label fw-semibold text-dark extra-small mb-1">
                                            Password <span class="text-danger">*</span>
                                        </label>
                                        <div class="input-group modern-input-group">
                                            <span class="input-group-text border-end-0 bg-light text-muted px-2.5">
                                                <i class="fas fa-key text-primary extra-small"></i>
                                            </span>
                                            <input type="password"
                                                class="form-control border-start-0 border-end-0 custom-input @error('password') is-invalid @enderror"
                                                id="password" name="password" required placeholder="Min 8 chars">
                                            <button class="btn btn-light border border-start-0 text-muted px-2.5 toggle-pw-btn" type="button"
                                                onclick="togglePassword()" title="Toggle visibility">
                                                <i class="fas fa-eye extra-small" id="toggleIcon"></i>
                                            </button>
                                        </div>
                                        @error('password')
                                            <div class="invalid-feedback d-block extra-small mt-1"><i class="fas fa-exclamation-circle me-1"></i>{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Confirm Password Input -->
                                    <div class="col-12 col-sm-6">
                                        <label for="password_confirmation" class="form-label fw-semibold text-dark extra-small mb-1">
                                            Confirm Password <span class="text-danger">*</span>
                                        </label>
                                        <div class="input-group modern-input-group">
                                            <span class="input-group-text border-end-0 bg-light text-muted px-2.5">
                                                <i class="fas fa-shield-alt text-primary extra-small"></i>
                                            </span>
                                            <input type="password"
                                                class="form-control border-start-0 custom-input @error('password_confirmation') is-invalid @enderror"
                                                id="password_confirmation" name="password_confirmation" required
                                                placeholder="Repeat password">
                                        </div>
                                        <div id="pw-match-feedback" class="extra-small mt-1 d-none"></div>
                                        @error('password_confirmation')
                                            <div class="invalid-feedback d-block extra-small mt-1"><i class="fas fa-exclamation-circle me-1"></i>{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Clean CAPTCHA Section -->
                                    <div class="col-12 mt-1">
                                        <div class="captcha-card p-3 rounded-3 border bg-light">
                                            <div class="d-flex align-items-center justify-content-between mb-2">
                                                <label for="captcha" class="form-label fw-bold text-dark extra-small mb-0 d-flex align-items-center gap-1">
                                                    <i class="fas fa-shield-virus text-primary"></i> Security Verification <span class="text-danger">*</span>
                                                </label>
                                                <span class="text-muted extra-small" style="font-size: 0.72rem;">Click image to refresh</span>
                                            </div>

                                            <div class="row g-2 align-items-center">
                                                <div class="col-6 d-flex align-items-center">
                                                    <div class="captcha-img-wrapper position-relative rounded-2 border shadow-sm cursor-pointer bg-white px-1.5 py-1 d-flex align-items-center justify-content-center flex-grow-1" 
                                                        onclick="refreshCaptcha()" title="Click image to refresh CAPTCHA" style="min-height: 42px;">
                                                        <img src="{{ captcha_src() }}" alt="CAPTCHA" id="captchaImage"
                                                            class="img-fluid"
                                                            style="height: 38px; object-fit: contain; max-width: 100%;">
                                                        <div class="captcha-overlay position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center bg-dark bg-opacity-25 opacity-0 hover-opacity-100 transition-all rounded-2">
                                                            <i class="fas fa-sync-alt text-white extra-small"></i>
                                                        </div>
                                                    </div>
                                                    <button type="button" class="btn btn-sm btn-light border rounded-circle p-1.5 ms-2 flex-shrink-0 shadow-sm"
                                                        onclick="refreshCaptcha()" title="Refresh Code">
                                                        <i class="fas fa-sync-alt text-primary extra-small" id="captchaSpinner"></i>
                                                    </button>
                                                </div>

                                                <div class="col-6">
                                                    <input type="text"
                                                        class="form-control custom-input @error('captcha') is-invalid @enderror text-center fw-bold letter-spacing-2"
                                                        id="captcha" name="captcha" required placeholder="ENTER CODE" autocomplete="off"
                                                        style="height: 42px; font-size: 0.95rem;">
                                                </div>
                                            </div>
                                            @error('captcha')
                                                <div class="invalid-feedback d-block extra-small text-center mt-1.5"><i class="fas fa-exclamation-circle me-1"></i>{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Submit Button -->
                                    <div class="col-12 pt-1.5">
                                        <button type="submit" class="btn btn-submit-glow text-white fw-bold w-100 py-2.5 rounded-3 shadow-md position-relative overflow-hidden transition-all">
                                            <span class="d-flex align-items-center justify-content-center gap-2">
                                                <i class="fas fa-user-plus small"></i>
                                                <span class="small">Create Account & Proceed</span>
                                                <i class="fas fa-arrow-right extra-small submit-arrow"></i>
                                            </span>
                                        </button>
                                    </div>

                                    <!-- Already Have Account -->
                                    <div class="col-12 text-center pt-1">
                                        <p class="text-muted extra-small mb-0">
                                            Already registered?
                                            <a href="{{ route('login') }}" class="text-primary fw-bold text-decoration-none hover-underline ms-1">
                                                Sign In Here <i class="fas fa-chevron-right extra-small" style="font-size: 0.65rem;"></i>
                                            </a>
                                        </p>
                                    </div>

                                    <!-- Compact Info Notice -->
                                    <div class="col-12 mt-1">
                                        <div class="info-card p-2 px-2.5 rounded-2 border-0 d-flex align-items-center gap-2">
                                            <i class="fas fa-info-circle text-primary extra-small flex-shrink-0"></i>
                                            <div class="extra-small text-muted leading-tight">
                                                <strong>Note:</strong> Verify email after signup to complete profile & payment.
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </form>

                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- Enhanced Compact Styling -->
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

        .registration-page-wrapper {
            background: linear-gradient(135deg, #f4f7fc 0%, #e9eef8 50%, #f0f4fd 100%);
            min-height: 85vh;
            font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
        }

        .registration-card {
            border-radius: 16px !important;
            box-shadow: 0 15px 35px -10px rgba(30, 37, 94, 0.12), 0 0 1px rgba(0, 0, 0, 0.08);
        }

        .card-accent-bar {
            height: 4px;
            background: linear-gradient(90deg, #ff9900 0%, #2e3192 50%, #00c6ff 100%);
        }

        .card-header {
            background: linear-gradient(135deg, #161b40 0%, #1e255e 50%, #2e3192 100%);
            border-bottom: none;
        }

        .header-bg-glow {
            position: absolute;
            top: -50%;
            left: 50%;
            transform: translateX(-50%);
            width: 250px;
            height: 150px;
            background: radial-gradient(circle, rgba(74, 91, 204, 0.35) 0%, rgba(255, 255, 255, 0) 70%);
            pointer-events: none;
        }

        .header-badge-pill {
            background: rgba(255, 255, 255, 0.16) !important;
            border: 1px solid rgba(255, 255, 255, 0.3) !important;
            color: #ffffff !important;
            backdrop-filter: blur(4px);
        }

        .header-badge-pill span {
            color: #ffffff !important;
        }

        .text-navy {
            color: #1e255e;
        }

        .text-white-80 {
            color: rgba(255, 255, 255, 0.85);
        }

        .text-danger-light {
            color: #ff6b6b;
        }

        .uppercase-label {
            letter-spacing: 0.5px;
            font-size: 0.78rem;
        }

        .letter-spacing-2 {
            letter-spacing: 2px;
        }

        .extra-small {
            font-size: 0.78rem;
        }

        .leading-tight {
            line-height: 1.25;
        }

        /* Delegate Type Cards - Compact */
        .delegate-type-card {
            background-color: #f8fafc;
            border-color: #e2e8f0 !important;
            transition: all 0.2s ease;
            user-select: none;
        }

        .delegate-type-card:hover {
            border-color: #2e3192 !important;
            background-color: rgba(46, 49, 146, 0.03);
            transform: translateY(-1px);
        }

        .delegate-type-card.active {
            border-color: #2e3192 !important;
            background-color: rgba(46, 49, 146, 0.06) !important;
            box-shadow: 0 0 0 2px rgba(46, 49, 146, 0.18);
        }

        .delegate-type-card.active .selected-badge {
            display: block !important;
        }

        .card-radio-icon {
            width: 32px;
            height: 32px;
            flex-shrink: 0;
        }

        /* Input Controls - Compact */
        .modern-input-group {
            border-radius: 9px;
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
            transition: all 0.2s ease;
        }

        .modern-input-group:focus-within {
            box-shadow: 0 0 0 3px rgba(46, 49, 146, 0.15);
        }

        .custom-input {
            height: 40px;
            font-size: 0.88rem;
            border-color: #cbd5e1;
            transition: all 0.2s ease;
        }

        .custom-input:focus {
            border-color: #2e3192;
            box-shadow: none;
        }

        .input-group-text {
            border-color: #cbd5e1;
        }

        .toggle-pw-btn {
            border-color: #cbd5e1;
            transition: all 0.2s ease;
        }

        .toggle-pw-btn:hover {
            background-color: #e2e8f0 !important;
            color: #1e255e !important;
        }

        /* CAPTCHA Card */
        .captcha-card {
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            border-color: #cbd5e1 !important;
        }

        .captcha-overlay {
            backdrop-filter: blur(2px);
        }

        .captcha-img-wrapper:hover .captcha-overlay {
            opacity: 1 !important;
        }

        /* Submit Button Glow */
        .btn-submit-glow {
            background: linear-gradient(135deg, #1e255e 0%, #2e3192 50%, #4a5bcc 100%);
            border: none;
            letter-spacing: 0.3px;
        }

        .btn-submit-glow:hover {
            background: linear-gradient(135deg, #161b40 0%, #242775 50%, #3e4eb3 100%);
            transform: translateY(-1px);
            box-shadow: 0 8px 18px -4px rgba(46, 49, 146, 0.35) !important;
        }

        .btn-submit-glow:hover .submit-arrow {
            transform: translateX(3px);
        }

        .submit-arrow {
            transition: transform 0.2s ease;
        }

        /* Alert Custom */
        .alert-custom-danger {
            background-color: #fef2f2;
            border: 1px solid #fecaca;
        }

        .alert-custom-success {
            background-color: #f0fdf4;
            border: 1px solid #bbf7d0;
        }

        /* Info Card */
        .info-card {
            background-color: #eff6ff;
            border: 1px solid #dbeafe !important;
        }

        .cursor-pointer {
            cursor: pointer;
        }

        .transition-all {
            transition: all 0.2s ease;
        }

        .hover-underline:hover {
            text-decoration: underline !important;
        }

        @media (max-width: 575.98px) {
            .card-body {
                padding: 1rem !important;
            }
            .delegate-type-card {
                padding: 0.5rem !important;
            }
            .card-radio-icon {
                width: 28px;
                height: 28px;
                margin-right: 0.4rem !important;
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

            // Initial active state check
            updateCardState();

            delegateRadios.forEach(radio => {
                radio.addEventListener('change', function() {
                    updateCardState();

                    if (this.value === 'Indian') {
                        // Auto-select India
                        for (let option of countrySelect.options) {
                            if (option.text.trim().toLowerCase() === 'india' || option.text.includes('India')) {
                                option.selected = true;
                                break;
                            }
                        }
                        countrySelect.style.backgroundColor = '#f1f5f9';
                        countrySelect.style.color = '#334155';
                        countrySelect.title = 'Auto-selected for Indian delegates';
                    } else {
                        countrySelect.selectedIndex = 0;
                        countrySelect.style.backgroundColor = '';
                        countrySelect.style.color = '';
                        countrySelect.title = '';
                    }
                });
            });

            // Live Password Match Checker
            const passwordInput = document.getElementById('password');
            const confirmInput = document.getElementById('password_confirmation');
            const matchFeedback = document.getElementById('pw-match-feedback');

            function checkPasswordMatch() {
                if (!confirmInput || !passwordInput || !matchFeedback) return;
                
                if (confirmInput.value.length === 0) {
                    matchFeedback.classList.add('d-none');
                    confirmInput.classList.remove('is-invalid', 'is-valid');
                    return;
                }

                matchFeedback.classList.remove('d-none');
                if (confirmInput.value === passwordInput.value && passwordInput.value.length >= 8) {
                    confirmInput.classList.remove('is-invalid');
                    confirmInput.classList.add('is-valid');
                    matchFeedback.className = 'extra-small mt-1 text-success font-semibold';
                    matchFeedback.innerHTML = '<i class="fas fa-check-circle me-1"></i> Passwords match';
                } else {
                    confirmInput.classList.remove('is-valid');
                    confirmInput.classList.add('is-invalid');
                    matchFeedback.className = 'extra-small mt-1 text-danger font-semibold';
                    matchFeedback.innerHTML = '<i class="fas fa-times-circle me-1"></i> Passwords do not match';
                }
            }

            if (confirmInput && passwordInput) {
                confirmInput.addEventListener('input', checkPasswordMatch);
                passwordInput.addEventListener('input', checkPasswordMatch);
            }

            // Auto-focus next field on Enter key
            const inputs = document.querySelectorAll('input, select');
            inputs.forEach((input, index) => {
                input.addEventListener('keypress', function(e) {
                    if (e.key === 'Enter' && input.type !== 'submit' && index < inputs.length - 1) {
                        e.preventDefault();
                        inputs[index + 1].focus();
                    }
                });
            });
        });
    </script>
</x-layout>
