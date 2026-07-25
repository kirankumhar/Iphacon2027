@extends('shared.index')
@section('title', 'Verify Email')

@php
    $inner_title = 'Verification Email Link Sent';
@endphp
@section('delegate-content')
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card shadow-sm border-0" style="border-radius: 12px; overflow: hidden;">
                <div class="card-header text-center py-3"
                    style="background: linear-gradient(135deg, #2e3192, #4a5bcc);">
                    <h5 class="text-white mb-0 fw-bold">
                        <i class="fas fa-envelope-circle-check me-2"></i>Verify Your Email
                    </h5>
                </div>

                <div class="card-body p-4 text-center">
                    @if (session('success'))
                        <div class="alert alert-success py-2 px-3 small mb-3 text-start" role="alert">
                            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                        </div>
                    @endif

                    <div class="mb-3">
                        <i class="fas fa-envelope-open-text text-primary" style="font-size: 2.75rem;"></i>
                    </div>

                    <h5 class="text-dark mb-2 fw-semibold">Check Your Email</h5>

                    <p class="text-muted small mb-3">
                        We've sent a verification link to your email address. Please click the link to verify your account and complete registration.
                    </p>

                    <div class="alert alert-info py-2 px-3 small mb-3 text-start" role="alert">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Didn't receive the email?</strong> Check your spam folder or request a new verification email below.
                    </div>

                    <!-- Resend Verification Form -->
                    <form method="POST" action="{{ route('verification.resend') }}">
                        @csrf
                        <div class="mb-3 text-start">
                            <input type="email" class="form-control form-control-sm @error('email') is-invalid @enderror" name="email"
                                value="{{ old('email') }}" placeholder="Enter your email address"
                                required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-paper-plane me-2"></i>Resend Verification Email
                        </button>
                    </form>

                    <hr class="my-3">

                    <div class="text-muted small">
                        Need help? <a href="mailto:support@conference.com" class="text-decoration-none">Contact Support</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
