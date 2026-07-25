@extends('shared.index')

@section('title', 'Forgot Password')

@section('delegate-content')

    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card shadow-lg border-0" style="border-radius: 20px; backdrop-filter: blur(10px);">
                <!-- Header -->
                <div class="card-header text-center py-4 border-0"
                    style="background: linear-gradient(135deg, #2e3192, #4a5bcc); border-radius: 20px 20px 0 0;">
                    <div class="mb-3">
                        <i class="fas fa-key fa-4x text-white"></i>
                    </div>
                    <h3 class="text-white mb-0 fw-bold">Forgot Password</h3>
                    <p class="text-white-50 mb-0">Enter your email to reset your password</p>
                </div>

                <!-- Body -->
                <div class="card-body p-5">
                    @if (session('status'))
                        <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                            <i class="fas fa-check-circle me-2"></i>{{ session('status') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('password.email') }}">
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
                                    placeholder="Enter your email address"
                                    style="border-radius: 0 12px 12px 0; padding: 12px;">
                            </div>
                            @error('email')
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
                                <i class="fas fa-paper-plane me-2"></i>Send Reset Link
                            </button>
                        </div>

                        <!-- Links -->
                        <div class="text-center">
                            <div class="row">
                                <div class="col-12 mb-2">
                                    <span class="text-muted">Remember your password? </span>
                                    <a href="{{ route('login') }}" class="text-decoration-none fw-semibold"
                                        style="color: #2e3192;">
                                        <i class="fas fa-sign-in-alt me-1"></i>Back to Login
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
            </div>
        </div>
    </div>


    <script>
        function refreshCaptcha() {
            document.getElementById('captchaImage').src = '{{ captcha_src() }}?' + new Date().getTime();
            document.getElementById('captcha').value = '';
        }
    </script>
@endsection
