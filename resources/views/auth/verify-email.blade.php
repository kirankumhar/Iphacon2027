@extends('shared.index')
@section('title', 'Verify Email')

@php
    $inner_title = 'Verification Email Link Sent';
@endphp
@section('delegate-content')
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-lg border-0" style="border-radius: 15px;">
                <div class="card-header text-center py-4"
                    style="background: linear-gradient(135deg, #2e3192, #4a5bcc); border-radius: 15px 15px 0 0;">
                    <h3 class="text-white mb-0 fw-bold">
                        <i class="fas fa-envelope-circle-check me-2"></i>Verify Your Email
                    </h3>
                </div>

                <div class="card-body p-5 text-center">
                    <div class="mb-4">
                        <i class="fas fa-envelope-open-text text-primary" style="font-size: 4rem;"></i>
                    </div>

                    <h4 class="text-dark mb-3">Check Your Email</h4>

                    <p class="text-muted mb-4">
                        We've sent a verification link to your email address. Please click the link in the email to verify
                        your account and complete your registration.
                    </p>

                    <div class="alert alert-info" role="alert">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Didn't receive the email?</strong> Check your spam folder or request a new verification
                        email below.
                    </div>

                    <!-- Resend Verification Form -->
                    <form method="POST" action="{{ route('verification.resend') }}">
                        @csrf
                        <div class="mb-3">
                            <input type="email" class="form-control @error('email') is-invalid @enderror" name="email"
                                value="{{ old('email') }}" placeholder="Enter your email address"
                                required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary btn-lg px-4 me-3">
                            <i class="fas fa-paper-plane me-2"></i>Resend Verification Email
                        </button>
                    </form>

                    <hr class="my-4">

                    <div class="text-muted">
                        <p class="mb-0">Need help? <a href="mailto:support@conference.com">Contact Support</a></p>
                    </div>

                    @if (session('success'))
                        <div class="alert alert-success mt-3" role="alert">
                            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
