<x-layout>
    <x-slot:title>Delegate Login | IPHACON 2027</x-slot:title>

    <div class="login-page-wrapper py-4 py-md-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-12 col-md-10 col-lg-8 col-xl-7">

                    <!-- Compact Login Card -->
                    <div class="card login-card shadow-lg border-0 overflow-hidden">
                        
                        <!-- Accent Top Bar -->
                        <div class="card-accent-bar"></div>

                        <!-- Compact Header -->
                        <div class="card-header text-center py-2.5 px-3 border-0 position-relative">
                            <div class="header-bg-glow"></div>
                            
                            <div class="position-relative z-1">
                                <h5 class="text-white fw-bold mb-0 tracking-wide" style="font-size: 1.05rem;">Sign In to Your Account</h5>
                                <p class="text-white-80 mb-0 extra-small" style="font-size: 0.78rem;">
                                    IPHACON 2027 • RIMS, Ranchi
                                </p>
                            </div>
                        </div>

                        <!-- Card Body -->
                        <div class="card-body p-3.5 p-md-4 bg-white">

                            <!-- Error Summary -->
                            @if ($errors->any() && !$errors->has('email') && !$errors->has('password') && !$errors->has('captcha'))
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

                            <!-- Success Message -->
                            @if (session('success'))
                                <div class="alert alert-custom-success alert-dismissible fade show p-2.5 mb-3 rounded-3 d-flex align-items-center gap-2" role="alert">
                                    <i class="fas fa-check-circle small text-success flex-shrink-0"></i>
                                    <div class="extra-small fw-medium text-dark">{{ session('success') }}</div>
                                    <button type="button" class="btn-close py-2 px-2" data-bs-dismiss="alert"></button>
                                </div>
                            @endif

                            <form method="POST" action="{{ route('login') }}" class="needs-validation" novalidate>
                                @csrf

                                <div class="row g-2.5">

                                    <!-- Email Address -->
                                    <div class="col-12">
                                        <label for="email" class="form-label fw-semibold text-dark extra-small mb-1">
                                            Email Address <span class="text-danger">*</span>
                                        </label>
                                        <div class="input-group modern-input-group">
                                            <span class="input-group-text border-end-0 bg-light text-muted px-2.5">
                                                <i class="fas fa-envelope text-primary extra-small"></i>
                                            </span>
                                            <input type="email"
                                                class="form-control border-start-0 custom-input @error('email') is-invalid @enderror"
                                                id="email" name="email" value="{{ old('email') }}" required autofocus
                                                placeholder="e.g. doctor@hospital.com">
                                        </div>
                                        @error('email')
                                            <div class="invalid-feedback d-block extra-small mt-1"><i class="fas fa-exclamation-circle me-1"></i>{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Password -->
                                    <div class="col-12">
                                        <div class="d-flex align-items-center justify-content-between mb-1">
                                            <label for="password" class="form-label fw-semibold text-dark extra-small mb-0">
                                                Password <span class="text-danger">*</span>
                                            </label>
                                            @if (Route::has('password.request'))
                                                <a href="{{ route('password.request') }}" class="text-primary extra-small text-decoration-none hover-underline fw-medium">
                                                    Forgot Password?
                                                </a>
                                            @endif
                                        </div>
                                        <div class="input-group modern-input-group">
                                            <span class="input-group-text border-end-0 bg-light text-muted px-2.5">
                                                <i class="fas fa-lock text-primary extra-small"></i>
                                            </span>
                                            <input type="password"
                                                class="form-control border-start-0 border-end-0 custom-input @error('password') is-invalid @enderror"
                                                id="password" name="password" required placeholder="Enter your password">
                                            <button class="btn btn-light border border-start-0 text-muted px-2.5 toggle-pw-btn" type="button"
                                                onclick="togglePassword()" title="Toggle visibility">
                                                <i class="fas fa-eye extra-small" id="toggleIcon"></i>
                                            </button>
                                        </div>
                                        @error('password')
                                            <div class="invalid-feedback d-block extra-small mt-1"><i class="fas fa-exclamation-circle me-1"></i>{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- CAPTCHA Section -->
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
                                                        id="captchaImgWrapper" title="Click image to refresh CAPTCHA" style="min-height: 42px;">
                                                        <img src="{{ captcha_src('default') }}?t={{ time() }}" alt="CAPTCHA" id="captchaImage"
                                                            class="img-fluid"
                                                            style="height: 38px; object-fit: contain; max-width: 100%;">
                                                        <div class="captcha-overlay position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center bg-dark bg-opacity-25 opacity-0 hover-opacity-100 transition-all rounded-2">
                                                            <i class="fas fa-sync-alt text-white extra-small"></i>
                                                        </div>
                                                    </div>
                                                    <button type="button" class="btn btn-sm btn-light border rounded-circle p-1.5 ms-2 flex-shrink-0 shadow-sm"
                                                        id="captchaRefreshBtn" title="Refresh Code">
                                                        <i class="fas fa-sync-alt text-primary extra-small" id="captchaSpinner"></i>
                                                    </button>
                                                </div>

                                                <div class="col-6">
                                                    <input type="text"
                                                        class="form-control custom-input @error('captcha') is-invalid @enderror text-center fw-bold letter-spacing-2"
                                                        id="captcha" name="captcha" required placeholder="ENTER CODE" autocomplete="off" autocorrect="off"
                                                        autocapitalize="off" spellcheck="false"
                                                        style="height: 42px; font-size: 0.95rem;">
                                                </div>
                                            </div>
                                            @error('captcha')
                                                <div class="invalid-feedback d-block extra-small text-center mt-1.5"><i class="fas fa-exclamation-circle me-1"></i>{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Remember Me Checkbox -->
                                    <div class="col-12 mt-1">
                                        <div class="form-check">
                                            <input class="form-check-input custom-checkbox" type="checkbox" id="remember" name="remember"
                                                {{ old('remember') ? 'checked' : '' }}>
                                            <label class="form-check-label extra-small text-dark fw-medium" for="remember">
                                                Remember me on this device
                                            </label>
                                        </div>
                                    </div>

                                    <!-- Submit Button -->
                                    <div class="col-12 pt-1.5">
                                        <button type="submit" class="btn btn-submit-glow text-white fw-bold w-100 py-2.5 rounded-3 shadow-md position-relative overflow-hidden transition-all">
                                            <span class="d-flex align-items-center justify-content-center gap-2">
                                                <i class="fas fa-sign-in-alt small"></i>
                                                <span class="small">Sign In to Dashboard</span>
                                                <i class="fas fa-arrow-right extra-small submit-arrow"></i>
                                            </span>
                                        </button>
                                    </div>

                                    <!-- Register Option -->
                                    <div class="col-12 text-center pt-2 border-top mt-2">
                                        <p class="text-muted extra-small mb-1">
                                            Don't have an account yet?
                                            <a href="{{ route('register') }}" class="text-primary fw-bold text-decoration-none hover-underline ms-1">
                                                Register as Delegate <i class="fas fa-arrow-right extra-small" style="font-size: 0.65rem;"></i>
                                            </a>
                                        </p>
                                    </div>

                                </div>
                            </form>

                        </div>

                        <!-- Card Footer -->
                        <div class="card-footer text-center bg-light border-0 py-2 px-3">
                            <small class="text-muted extra-small">
                                <i class="fas fa-user-shield me-1 text-primary"></i> Administrator access?
                                <a href="{{ route('admin.login') }}" class="text-primary fw-bold text-decoration-none hover-underline ms-1">
                                    Admin Login
                                </a>
                            </small>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- Enhanced Styling -->
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

        .login-page-wrapper {
            background: linear-gradient(135deg, #f4f7fc 0%, #e9eef8 50%, #f0f4fd 100%);
            min-height: 85vh;
            font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
        }

        .login-card {
            border-radius: 16px !important;
            box-shadow: 0 15px 35px -10px rgba(30, 37, 94, 0.12), 0 0 1px rgba(0, 0, 0, 0.08);
            max-width: 650px;
            margin: 0 auto;
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

        .text-white-80 {
            color: rgba(255, 255, 255, 0.85);
        }

        .letter-spacing-2 {
            letter-spacing: 2px;
        }

        .extra-small {
            font-size: 0.78rem;
        }

        /* Input Controls */
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

        .custom-checkbox:checked {
            background-color: #2e3192;
            border-color: #2e3192;
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

        /* Alerts */
        .alert-custom-danger {
            background-color: #fef2f2;
            border: 1px solid #fecaca;
        }

        .alert-custom-success {
            background-color: #f0fdf4;
            border: 1px solid #bbf7d0;
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

        (function() {
            const presetUrl = @json(captcha_src('default'));
            const img = document.getElementById('captchaImage');
            const input = document.getElementById('captcha');
            const wrapper = document.getElementById('captchaImgWrapper');
            const refreshBtn = document.getElementById('captchaRefreshBtn');
            const spinner = document.getElementById('captchaSpinner');

            function refreshCaptcha() {
                if (spinner) spinner.classList.add('fa-spin');
                img.src = presetUrl + (presetUrl.includes('?') ? '&' : '?') + 't=' + Date.now();
                if (input) input.value = '';
                setTimeout(() => {
                    if (spinner) spinner.classList.remove('fa-spin');
                }, 500);
            }

            if (wrapper) wrapper.addEventListener('click', refreshCaptcha);
            if (refreshBtn) refreshBtn.addEventListener('click', refreshCaptcha);

            const THREE_MIN = 180000;
            let lastRefresh = Date.now();

            function trackedRefresh() {
                lastRefresh = Date.now();
                refreshCaptcha();
            }

            setInterval(trackedRefresh, THREE_MIN);

            const form = img ? img.closest('form') : null;
            if (form) {
                form.addEventListener('submit', function(e) {
                    const age = Date.now() - lastRefresh;
                    if (age > THREE_MIN - 1000) {
                        e.preventDefault();
                        trackedRefresh();
                        alert('Captcha timed out. A new code has been loaded, please enter it.');
                    }
                });
            }
        })();
    </script>
</x-layout>
