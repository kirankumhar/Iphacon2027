@extends('shared.auth-delegate')
@section('title', 'Change Password')

@php
    $inner_title = 'Delegate Change Password';
@endphp

@section('delegate-content')
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-lg border-0" style="border-radius: 15px;">
                <div class="card-header d-flex justify-content-between align-items-center py-4"
                    style="background: linear-gradient(135deg, #2e3192, #4a5bcc); border-radius: 15px 15px 0 0;">
                    <h3 class="text-white mb-0 fw-bold">
                        <i class="fas fa-key me-2"></i>Change Password
                    </h3>
                    <a href="{{ route('profile.show') }}" class="btn btn-outline-light btn-sm">
                        <i class="fas fa-arrow-left me-1"></i>Back to Profile
                    </a>
                </div>

                <div class="card-body p-5">
                    <form method="POST" action="{{ route('profile.update-password') }}">
                        @csrf
                        @method('PUT')

                        <!-- Current Password -->
                        <div class="row mb-3 align-items-center">
                            <div class="col-md-4">
                                <label for="current_password" class="form-label fw-semibold" style="color:#2e3192">
                                    <i class="fas fa-lock me-1"></i>Current Password<span class="text-danger">*</span>
                                </label>
                            </div>
                            <div class="col-md-8">
                                <input type="password"
                                    class="form-control form-control-sm @error('current_password') is-invalid @enderror"
                                    id="current_password" name="current_password" required style="border-radius: 8px;" />
                                @error('current_password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- New Password -->
                        <div class="row mb-3 align-items-center">
                            <div class="col-md-4">
                                <label for="password" class="form-label fw-semibold" style="color:#2e3192">
                                    <i class="fas fa-lock me-1"></i>New Password<span class="text-danger">*</span>
                                </label>
                            </div>
                            <div class="col-md-8">
                                <input type="password"
                                    class="form-control form-control-sm @error('password') is-invalid @enderror"
                                    id="password" name="password" required style="border-radius: 8px;" />
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">
                                    Must contain: 1 lowercase, 1 uppercase, 1 number, 1 special character (min 6 chars)
                                </small>
                            </div>
                        </div>

                        <!-- Confirm Password -->
                        <div class="row mb-3 align-items-center">
                            <div class="col-md-4">
                                <label for="password_confirmation" class="form-label fw-semibold" style="color:#2e3192">
                                    <i class="fas fa-lock me-1"></i>Confirm Password<span class="text-danger">*</span>
                                </label>
                            </div>
                            <div class="col-md-8">
                                <input type="password"
                                    class="form-control form-control-sm @error('password_confirmation') is-invalid @enderror"
                                    id="password_confirmation" name="password_confirmation" required
                                    placeholder="Confirm new password" style="border-radius: 8px;" />
                                @error('password_confirmation')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="row">
                            <div class="col-md-12 text-end">
                                <button type="submit" class="btn btn-primary btn-lg px-4 py-2 fw-bold"
                                    style="background: linear-gradient(135deg, #2e3192, #4a5bcc); border: none; border-radius: 25px; box-shadow: 0 4px 15px rgba(46, 49, 146, 0.3);">
                                    <i class="fas fa-key me-2"></i>Change Password
                                </button>
                            </div>
                        </div>

                        <!-- Error Summary -->
                        @if ($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
                                <strong><i class="fas fa-exclamation-triangle me-2"></i>Please fix the following
                                    errors:</strong>
                                <ul class="mb-0 mt-2">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
