@extends('shared.auth-delegate')
@section('title', 'Change Password')

@php
    $inner_title = 'Delegate Change Password';
@endphp

@section('delegate-content')
<div class="container py-3">
    <div class="row justify-content-center">
        <div class="col-md-7 col-lg-5">

            {{-- Compact Card Container --}}
            <div class="card border-0 shadow-sm" style="border-radius: 14px; overflow: hidden; background: #ffffff;">

                {{-- Compact Header --}}
                <div class="card-header d-flex justify-content-between align-items-center py-3 px-4"
                     style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%); border-bottom: 3px solid #2D69FF;">
                    <div class="d-flex align-items-center gap-2">
                        <i class="fas fa-key text-primary fs-5"></i>
                        <h5 class="text-white mb-0 fw-bold" style="letter-spacing: 0.3px;">Change Password</h5>
                    </div>
                    <a href="{{ route('profile.show') }}" class="btn btn-outline-light btn-sm px-2.5 py-1 fw-semibold" style="border-radius: 8px; font-size: 0.825rem;">
                        <i class="fas fa-arrow-left me-1"></i>Back
                    </a>
                </div>

                <div class="card-body p-3 p-md-4">
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show py-2 px-3 mb-3 small" role="alert">
                            <i class="fas fa-check-circle me-1.5"></i>{{ session('success') }}
                            <button type="button" class="btn-close py-2" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show py-2 px-3 mb-3 small" role="alert">
                            <div class="fw-bold mb-1"><i class="fas fa-exclamation-triangle me-1"></i>Please fix the following:</div>
                            <ul class="mb-0 ps-3">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close py-2" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('profile.update-password') }}">
                        @csrf
                        @method('PUT')

                        {{-- Current Password --}}
                        <div class="mb-3">
                            <label for="current_password" class="form-label fw-semibold text-dark mb-1" style="font-size: 0.875rem;">
                                Current Password <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-muted border-end-0" style="border-radius: 8px 0 0 8px;">
                                    <i class="fas fa-lock"></i>
                                </span>
                                <input type="password"
                                    class="form-control border-start-0 border-end-0 @error('current_password') is-invalid @enderror"
                                    id="current_password" name="current_password" required placeholder="Enter current password"
                                    style="font-size: 0.9rem;" />
                                <button type="button" class="btn btn-light border border-start-0 toggle-password text-muted"
                                    data-target="current_password" style="border-radius: 0 8px 8px 0;" title="Toggle password visibility">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            @error('current_password')
                                <div class="text-danger small mt-1" style="font-size: 0.8rem;">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- New Password --}}
                        <div class="mb-3">
                            <label for="password" class="form-label fw-semibold text-dark mb-1" style="font-size: 0.875rem;">
                                New Password <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-muted border-end-0" style="border-radius: 8px 0 0 8px;">
                                    <i class="fas fa-key"></i>
                                </span>
                                <input type="password"
                                    class="form-control border-start-0 border-end-0 @error('password') is-invalid @enderror"
                                    id="password" name="password" required placeholder="Enter new password"
                                    style="font-size: 0.9rem;" />
                                <button type="button" class="btn btn-light border border-start-0 toggle-password text-muted"
                                    data-target="password" style="border-radius: 0 8px 8px 0;" title="Toggle password visibility">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            <div class="text-muted mt-1" style="font-size: 0.78rem;">
                                Must have: 1 uppercase, 1 lowercase, 1 number, 1 special char (min 6 chars).
                            </div>
                            @error('password')
                                <div class="text-danger small mt-1" style="font-size: 0.8rem;">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Confirm Password --}}
                        <div class="mb-4">
                            <label for="password_confirmation" class="form-label fw-semibold text-dark mb-1" style="font-size: 0.875rem;">
                                Confirm New Password <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-muted border-end-0" style="border-radius: 8px 0 0 8px;">
                                    <i class="fas fa-check-double"></i>
                                </span>
                                <input type="password"
                                    class="form-control border-start-0 border-end-0 @error('password_confirmation') is-invalid @enderror"
                                    id="password_confirmation" name="password_confirmation" required
                                    placeholder="Confirm new password" style="font-size: 0.9rem;" />
                                <button type="button" class="btn btn-light border border-start-0 toggle-password text-muted"
                                    data-target="password_confirmation" style="border-radius: 0 8px 8px 0;" title="Toggle password visibility">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            @error('password_confirmation')
                                <div class="text-danger small mt-1" style="font-size: 0.8rem;">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Submit Button --}}
                        <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold shadow-sm"
                            style="background: linear-gradient(135deg, #2D69FF 0%, #1A52E0 100%); border: none; border-radius: 10px; font-size: 0.95rem;">
                            <i class="fas fa-save me-1.5"></i>Update Password
                        </button>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const toggleButtons = document.querySelectorAll('.toggle-password');
    toggleButtons.forEach(function (button) {
        button.addEventListener('click', function () {
            const targetId = this.getAttribute('data-target');
            const input = document.getElementById(targetId);
            const icon = this.querySelector('i');

            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        });
    });
});
</script>
@endsection


