@extends('shared.index')

@section('title', 'Reset Password')

@section('delegate-content')

    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card shadow-lg border-0" style="border-radius: 20px; backdrop-filter: blur(10px);">
                <!-- Header -->
                <div class="card-header text-center py-4 border-0"
                    style="background: linear-gradient(135deg, #2e3192, #4a5bcc); border-radius: 20px 20px 0 0;">
                    <div class="mb-3">
                        <i class="fas fa-lock fa-4x text-white"></i>
                    </div>
                    <h3 class="text-white mb-0 fw-bold">Reset Password</h3>
                    <p class="text-white-50 mb-0">Create your new password</p>
                </div>

                <!-- Body -->
                <div class="card-body p-5">
                    <form method="POST" action="{{ route('password.update') }}">
                        @csrf
                        <input type="hidden" name="token" value="{{ $token }}">

                        <!-- Email -->
                        <div class="mb-4">
                            <label for="email" class="form-label fw-semibold" style="color: #2e3192;">
                                <i class="fas fa-envelope me-2"></i>Email Address
                            </label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email"
                                name="email" value="{{ old('email', request()->email) }}" required readonly autofocus
                                style="border-radius: 12px; padding: 12px;">
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Password -->
                        <div class="mb-4">
                            <label for="password" class="form-label fw-semibold" style="color: #2e3192;">
                                <i class="fas fa-lock me-2"></i>New Password
                            </label>
                            <div class="input-group">
                                <input type="password"
                                    class="form-control border-end-0 @error('password') is-invalid @enderror" id="password"
                                    name="password" required style="border-radius: 12px 0 0 12px; padding: 12px;">
                                <span class="input-group-text bg-light border-start-0 cursor-pointer"
                                    style="border-radius: 0 12px 12px 0;" onclick="togglePassword()">
                                    <i class="fas fa-eye text-muted" id="toggleIcon"></i>
                                </span>
                            </div>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Confirm Password -->
                        <div class="mb-4">
                            <label for="password_confirmation" class="form-label fw-semibold" style="color: #2e3192;">
                                <i class="fas fa-lock me-2"></i>Confirm Password
                            </label>
                            <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror"
                                id="password_confirmation" name="password_confirmation" required
                                style="border-radius: 12px; padding: 12px;">
                            @error('password_confirmation')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- CAPTCHA -->
                        <div class="mb-4">
                            <label for="captcha" class="form-label fw-semibold" style="color: #2e3192;">
                                <i class="fas fa-shield-alt me-2"></i>Verification
                            </label>
                            <div class="row align-items-center">
                                <div class="col-6">
                                    <img src="{{ captcha_src() }}" alt="CAPTCHA" id="captchaImage"
                                        class="img-fluid border rounded cursor-pointer" onclick="refreshCaptcha()"
                                        style="height: 50px; border-radius: 8px;">
                                </div>
                                <div class="col-6">
                                    <input type="text" class="form-control @error('captcha') is-invalid @enderror"
                                        id="captcha" name="captcha" required placeholder="Enter CAPTCHA"
                                        style="border-radius: 12px; padding: 12px;">
                                </div>
                            </div>
                            @error('captcha')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Submit Button -->
                        <div class="d-grid mb-4">
                            <button type="submit" class="btn btn-lg fw-bold text-white"
                                style="background: linear-gradient(135deg, #2e3192, #4a5bcc); border: none; border-radius: 12px; padding: 15px;">
                                <i class="fas fa-check me-2"></i>Reset Password
                            </button>
                        </div>

                        <!-- Links -->
                        <div class="text-center">
                            <a href="{{ route('login') }}" class="text-decoration-none fw-semibold" style="color: #2e3192;">
                                <i class="fas fa-arrow-left me-1"></i>Back to Login
                            </a>
                        </div>
                    </form>
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

        function refreshCaptcha() {
            document.getElementById('captchaImage').src = '{{ captcha_src() }}?' + new Date().getTime();
            document.getElementById('captcha').value = '';
        }
    </script>
@endsection
