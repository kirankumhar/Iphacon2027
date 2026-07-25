<x-layout>
    <x-slot:title>Delegate Login | IPHACON 2027</x-slot:title>

    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card shadow-lg border-0" style="border-radius: 20px; backdrop-filter: blur(10px);">
                <!-- Header -->
                <div class="card-header text-center py-3 border-0"
                    style="background: linear-gradient(135deg, #2e3192, #4a5bcc); border-radius: 20px 20px 0 0;">
                    <div class="mb-3">
                        <i class="fas fa-user-circle fa-4x text-white"></i>
                    </div>
                    <h3 class="text-white mb-0 fw-bold">Delegate Login</h3>
                    <p class="text-white-50 mb-0">Welcome back! Please sign in to your account</p>
                </div>

                <!-- Body -->
                <div class="card-body p-4">

                    <!-- Error/Success Messages -->
                    @if ($errors->any() && !$errors->has('email') && !$errors->has('password') && !$errors->has('captcha'))
                        <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
                            @foreach ($errors->all() as $error)
                                <div><i class="fas fa-exclamation-circle me-2"></i>{{ $error }}</div>
                            @endforeach
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
                            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <!-- Email -->
                        <div class="mb-4">
                            <label for="email" class="form-label fw-semibold" style="color: #2e3192;">
                                <i class="fas fa-envelope me-2"></i>Email Address
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0" style="border-radius: 12px 0 0 12px;">
                                    <i class="fas fa-envelope text-muted"></i>
                                </span>
                                <input type="email"
                                    class="form-control border-start-0 @error('email') is-invalid @enderror" id="email"
                                    name="email" value="{{ old('email') }}" required autofocus
                                    placeholder="Enter your email" style="border-radius: 0 12px 12px 0; padding: 12px;">
                            </div>
                            @error('email')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Password -->
                        <div class="mb-4">
                            <label for="password" class="form-label fw-semibold" style="color: #2e3192;">
                                <i class="fas fa-lock me-2"></i>Password
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0" style="border-radius: 12px 0 0 12px;">
                                    <i class="fas fa-lock text-muted"></i>
                                </span>
                                <input type="password"
                                    class="form-control border-start-0 border-end-0 @error('password') is-invalid @enderror"
                                    id="password" name="password" required placeholder="Enter your password"
                                    style="padding: 12px;">
                                <span class="input-group-text bg-light border-start-0 cursor-pointer"
                                    style="border-radius: 0 12px 12px 0;" onclick="togglePassword()">
                                    <i class="fas fa-eye text-muted" id="toggleIcon"></i>
                                </span>
                            </div>
                            @error('password')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- CAPTCHA -->
                        <div class="mb-4">
                            <label for="captcha" class="form-label fw-semibold" style="color: #2e3192;">
                                <i class="fas fa-shield-alt me-2"></i>Verification
                            </label>
                            <div class="row align-items-center">
                                <div class="col-6">
                                    <img src="{{ captcha_src('default') }}?t={{ time() }}" alt="CAPTCHA"
                                        id="captchaImage" class="img-fluid border rounded cursor-pointer"
                                        onclick="refreshCaptcha()"
                                        style="height: 55px; border-radius: 10px; border: 2px solid #2e3192;">
                                    <small class="text-muted d-block cursor-pointer mt-1" onclick="refreshCaptcha()">Click
                                        to
                                        refresh Captcha</small>
                                </div>
                                <div class="col-6">
                                    <input type="text" class="form-control @error('captcha') is-invalid @enderror"
                                        id="captcha" name="captcha" required placeholder="Enter CAPTCHA"
                                        style="border-radius: 12px; padding: 12px;" autocomplete="off" autocorrect="off"
                                        autocapitalize="off" spellcheck="false">

                                </div>
                            </div>
                            @error('captcha')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Remember Me -->
                        <div class="mb-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="remember" name="remember"
                                    {{ old('remember') ? 'checked' : '' }}>
                                <label class="form-check-label" for="remember">
                                    Remember me for 30 days
                                </label>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="d-grid mb-4">
                            <button type="submit" class="btn btn-lg fw-bold text-white"
                                style="background: linear-gradient(135deg, #2e3192, #4a5bcc); border: none; border-radius: 12px; padding: 15px;">
                                <i class="fas fa-sign-in-alt me-2"></i>Sign In
                            </button>
                        </div>

                        <!-- Links -->
                        <div class="text-center">
                            <div class="row">
                                <div class="col-12 mb-2">
                                    <a href="{{ route('password.request') }}" class="text-decoration-none"
                                        style="color: #2e3192;">
                                        <i class="fas fa-key me-1"></i>Forgot Your Password?
                                    </a>
                                </div>
                                <div class="col-12">
                                    <span class="text-muted">Don't have an account? </span>
                                    <a href="{{ route('register') }}" class="text-decoration-none fw-semibold"
                                        style="color: #2e3192;">
                                        <i class="fas fa-user-plus me-1"></i>Sign Up
                                    </a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Footer -->
                <div class="card-footer text-center border-0"
                    style="background: transparent; border-radius: 0 0 20px 20px;">
                    <div class="row">
                        <div class="col-12">
                            <small class="text-muted">
                                <i class="fas fa-users-cog me-1"></i>
                                Are you an administrator?
                                <a href="{{ route('admin.login') }}" class="text-decoration-none"
                                    style="color: #2e3192;">
                                    <strong>Admin Login</strong>
                                </a>
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


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

        // function refreshCaptcha() {
        //     document.getElementById('captchaImage').src = '{{ captcha_src('default') }}' + '&t=' + Date.now();
        //     document.getElementById('captcha').value = '';
        // }
    </script>

    <script>
        (function() {
            const presetUrl = @json(captcha_src('default')); // server-side value
            const img = document.getElementById('captchaImage');
            const input = document.getElementById('captcha');

            function refreshCaptcha() {
                img.src = presetUrl + (presetUrl.includes('?') ? '&' : '?') + 't=' + Date.now();
                if (input) input.value = '';
            }

            img.addEventListener('click', refreshCaptcha);

            const THREE_MIN = 180000;
            setInterval(refreshCaptcha, THREE_MIN);

            let lastRefresh = Date.now();
            const originalRefresh = refreshCaptcha;

            function trackedRefresh() {
                lastRefresh = Date.now();
                originalRefresh();
            }
            img.removeEventListener('click', refreshCaptcha);
            img.addEventListener('click', trackedRefresh);
            setInterval(() => {
                lastRefresh = Date.now();
                trackedRefresh();
            }, THREE_MIN);

            const form = img.closest('form');
            if (form) {
                form.addEventListener('submit', function(e) {
                    const age = Date.now() - lastRefresh;
                    if (age > THREE_MIN - 1000) {
                        e.preventDefault();
                        trackedRefresh();
                        alert('Captcha was refreshed due to timeout. Please enter the new code.');
                    }
                });
            }
        })();
    </script>
</x-layout>
