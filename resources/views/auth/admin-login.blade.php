<x-layout>
    <x-slot:title>Admin Portal Login | IPHACON 2027</x-slot:title>

    <div class="container py-4 py-md-5">
        <div class="row justify-content-center">
            <div class="col-12 col-md-9 col-lg-7 col-xl-6">
                <div class="card shadow-lg border-0 overflow-hidden" style="border-radius: 16px; background: #ffffff; max-width: 650px; margin: 0 auto;">
                    <!-- Admin Header -->
                    <div class="card-header text-center py-4 px-4 border-0 text-white"
                        style="background: linear-gradient(135deg, #0f172a, #1e293b, #334155);">
                        <div class="d-flex align-items-center justify-content-center gap-3">
                            <div class="header-icon-box bg-white bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center"
                                style="width: 48px; height: 48px; border: 1px solid rgba(255,255,255,0.15);">
                                <i class="fas fa-user-shield text-danger fs-4"></i>
                            </div>
                            <div class="text-start">
                                <h5 class="text-white mb-0 fw-bold" style="letter-spacing: 0.5px;">Admin Portal Login</h5>
                                <small class="text-white-50" style="font-size: 0.83rem;">Authorized Personnel Access Only</small>
                            </div>
                        </div>
                    </div>

                    <!-- Body -->
                    <div class="card-body p-4 p-md-5">
                        <!-- Alert Messages -->
                        @if ($errors->any() && !$errors->has('username') && !$errors->has('password') && !$errors->has('captcha'))
                            <div class="alert alert-danger alert-dismissible fade show py-2.5 px-3 small mb-4" role="alert"
                                style="border-radius: 10px;">
                                @foreach ($errors->all() as $error)
                                    <div><i class="fas fa-exclamation-circle me-1.5"></i>{{ $error }}</div>
                                @endforeach
                                <button type="button" class="btn-close py-2.5" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show py-2.5 px-3 small mb-4" role="alert"
                                style="border-radius: 10px;">
                                <i class="fas fa-check-circle me-1.5"></i>{{ session('success') }}
                                <button type="button" class="btn-close py-2.5" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('admin.login') }}">
                            @csrf

                            <!-- Username -->
                            <div class="mb-3.5">
                                <label for="username" class="form-label fw-bold text-dark mb-1.5 small">
                                    <i class="fas fa-user text-danger me-1"></i>Admin Username <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0 text-muted px-3">
                                        <i class="fas fa-user-circle"></i>
                                    </span>
                                    <input type="text"
                                        class="form-control border-start-0 @error('username') is-invalid @enderror"
                                        id="username" name="username" value="{{ old('username') }}"
                                        required autofocus placeholder="Enter admin username"
                                        style="height: 45px; font-size: 0.9rem;">
                                </div>
                                @error('username')
                                    <div class="invalid-feedback d-block small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Password -->
                            <div class="mb-3.5">
                                <label for="password" class="form-label fw-bold text-dark mb-1.5 small">
                                    <i class="fas fa-key text-danger me-1"></i>Password <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0 text-muted px-3">
                                        <i class="fas fa-lock"></i>
                                    </span>
                                    <input type="password"
                                        class="form-control border-start-0 border-end-0 @error('password') is-invalid @enderror"
                                        id="admin_password" name="password" required
                                        placeholder="Enter admin password"
                                        style="height: 45px; font-size: 0.9rem;">
                                    <button class="btn btn-outline-secondary border-start-0 bg-light text-muted px-3" type="button"
                                        onclick="toggleAdminPassword()" style="height: 45px;">
                                        <i class="fas fa-eye" id="adminToggleIcon"></i>
                                    </button>
                                </div>
                                @error('password')
                                    <div class="invalid-feedback d-block small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- CAPTCHA Section -->
                            <div class="mb-4">
                                <div class="p-3 rounded bg-light border">
                                    <label for="admin_captcha" class="form-label fw-bold text-dark mb-2 small d-block">
                                        <i class="fas fa-shield-alt text-danger me-1"></i>Security Verification <span class="text-danger">*</span>
                                    </label>
                                    <div class="row g-2.5 align-items-center">
                                        <div class="col-6 col-sm-5 d-flex align-items-center">
                                            <img src="{{ captcha_src() }}" alt="CAPTCHA" id="adminCaptchaImage"
                                                class="img-fluid rounded cursor-pointer border w-100"
                                                onclick="refreshAdminCaptcha()" title="Click to refresh CAPTCHA"
                                                style="height: 44px; object-fit: cover;">
                                            <button type="button" class="btn btn-sm btn-link text-danger p-1.5 ms-1"
                                                onclick="refreshAdminCaptcha()" title="Refresh Code">
                                                <i class="fas fa-sync-alt fs-6" id="adminCaptchaSpinner"></i>
                                            </button>
                                        </div>
                                        <div class="col-6 col-sm-7">
                                            <input type="text"
                                                class="form-control @error('captcha') is-invalid @enderror text-center fw-bold"
                                                id="admin_captcha" name="captcha" required placeholder="Enter Code"
                                                style="height: 44px; font-size: 0.95rem; letter-spacing: 2px;">
                                        </div>
                                    </div>
                                    @error('captcha')
                                        <div class="invalid-feedback d-block small text-center mt-1.5">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            

                            <!-- Remember Me -->
                            <div class="mb-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="remember" name="remember"
                                        {{ old('remember') ? 'checked' : '' }}>
                                    <label class="form-check-label small text-muted" for="remember">
                                        Keep me signed in on this device
                                    </label>
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <div class="mb-3">
                                <button type="submit" class="btn btn-danger fw-bold w-100 py-2.5 shadow-sm"
                                    style="background: linear-gradient(135deg, #b91c1c, #dc2626); border: none; border-radius: 10px; font-size: 1rem;">
                                    <i class="fas fa-sign-in-alt me-2"></i>Secure Admin Login
                                </button>
                            </div>

                            <!-- Security Notice Banner -->
                            <div class="p-2.5 px-3 rounded border-0 d-flex align-items-center gap-2.5 mt-3"
                                style="background-color: #fff1f2; color: #991b1b; font-size: 0.8rem;">
                                <i class="fas fa-exclamation-triangle text-danger fs-6 flex-shrink-0"></i>
                                <span><strong>Restricted Portal:</strong> Unauthorized login attempts are monitored & recorded.</span>
                            </div>

                            <!-- User Login Link -->
                            <div class="text-center mt-3">
                                <span class="text-muted small">Not an administrator? </span>
                                <a href="{{ route('login') }}" class="text-decoration-none fw-bold small text-danger">
                                    Delegate Login <i class="fas fa-arrow-right ms-1"></i>
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleAdminPassword() {
            const passwordInput = document.getElementById('admin_password');
            const toggleIcon = document.getElementById('adminToggleIcon');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.classList.replace('fa-eye', 'fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                toggleIcon.classList.replace('fa-eye-slash', 'fa-eye');
            }
        }

        function refreshAdminCaptcha() {
            const spinner = document.getElementById('adminCaptchaSpinner');
            if (spinner) spinner.classList.add('fa-spin');
            document.getElementById('adminCaptchaImage').src = '{{ captcha_src() }}?' + new Date().getTime();
            document.getElementById('admin_captcha').value = '';
            setTimeout(() => {
                if (spinner) spinner.classList.remove('fa-spin');
            }, 600);
        }
    </script>
</x-layout>
