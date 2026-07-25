@extends('shared.index')
@php
    $inner_title = 'Delegate Edit Profile';
@endphp
@section('delegate-content')
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow-lg border-0" style="border-radius: 15px;">
                <div class="card-header d-flex justify-content-between align-items-center py-4"
                    style="background: linear-gradient(135deg, #2e3192, #4a5bcc); border-radius: 15px 15px 0 0;">
                    <h3 class="text-white mb-0 fw-bold">
                        <i class="fas fa-user-edit me-2"></i>Edit Profile
                    </h3>
                    <a href="{{ route('profile.show') }}" class="btn btn-outline-light btn-sm">
                        <i class="fas fa-arrow-left me-1"></i>Back to Profile
                    </a>
                </div>

                <div class="card-body p-5">
                    <form method="POST" action="{{ route('profile.update') }}">
                        @csrf
                        @method('PUT')

                        <!-- Full Name Row -->
                        <div class="row mb-3 align-items-center">
                            <div class="col-md-3">
                                <label for="full_name" class="form-label fw-semibold" style="color:#2e3192">
                                    <i class="fas fa-user me-1"></i>Full Name<span class="text-danger">*</span>
                                </label>
                            </div>
                            <div class="col-md-2">
                                <select class="form-select form-select-sm @error('prefix') is-invalid @enderror"
                                    id="prefix" name="prefix" required style="border-radius: 8px;">
                                    <option value="" disabled>Prefix</option>
                                    <option value="Dr." {{ old('prefix', $user->prefix) == 'Dr.' ? 'selected' : '' }}>Dr.
                                    </option>
                                    <option value="Mr." {{ old('prefix', $user->prefix) == 'Mr.' ? 'selected' : '' }}>Mr.
                                    </option>
                                    <option value="Mrs." {{ old('prefix', $user->prefix) == 'Mrs.' ? 'selected' : '' }}>
                                        Mrs.</option>
                                    <option value="Prof." {{ old('prefix', $user->prefix) == 'Prof.' ? 'selected' : '' }}>
                                        Prof.</option>
                                </select>
                                @error('prefix')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-7">
                                <input type="text"
                                    class="form-control form-control-sm @error('full_name') is-invalid @enderror"
                                    id="full_name" name="full_name" value="{{ old('full_name', $user->full_name) }}"
                                    required pattern="[A-Za-z ]{2,}" placeholder="Enter your full name"
                                    style="border-radius: 8px;" />
                                @error('full_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Gender and DOB Row -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="row align-items-center">
                                    <div class="col-md-6">
                                        <label for="gender" class="form-label fw-semibold" style="color:#2e3192">
                                            <i class="fas fa-venus-mars me-1"></i>Gender<span class="text-danger">*</span>
                                        </label>
                                    </div>
                                    <div class="col-md-6">
                                        <select class="form-select form-select-sm @error('gender') is-invalid @enderror"
                                            id="gender" name="gender" required style="border-radius: 8px;">
                                            <option value="" disabled>Select</option>
                                            <option value="Male"
                                                {{ old('gender', $user->gender) == 'Male' ? 'selected' : '' }}>Male</option>
                                            <option value="Female"
                                                {{ old('gender', $user->gender) == 'Female' ? 'selected' : '' }}>Female
                                            </option>
                                        </select>
                                        @error('gender')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="row align-items-center">
                                    <div class="col-md-6">
                                        <label for="date_of_birth" class="form-label fw-semibold" style="color:#2e3192">
                                            <i class="fas fa-calendar me-1"></i>Date of Birth<span
                                                class="text-danger">*</span>
                                        </label>
                                    </div>
                                    <div class="col-md-6">
                                        <input type="date"
                                            class="form-control form-control-sm @error('date_of_birth') is-invalid @enderror"
                                            id="date_of_birth" name="date_of_birth"
                                            value="{{ old('date_of_birth', $user->date_of_birth->format('Y-m-d')) }}"
                                            required max="{{ date('Y-m-d', strtotime('-18 years')) }}"
                                            style="border-radius: 8px;" />
                                        @error('date_of_birth')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Mobile Row -->
                        <div class="row mb-3 align-items-center">
                            <div class="col-md-3">
                                <label for="mobile_number" class="form-label fw-semibold" style="color:#2e3192">
                                    <i class="fas fa-phone me-1"></i>Mobile<span class="text-danger">*</span>
                                </label>
                            </div>
                            <div class="col-md-3">
                                <select
                                    class="form-select form-select-sm @error('mobile_country_code') is-invalid @enderror"
                                    id="mobile_country_code" name="mobile_country_code" required
                                    style="border-radius: 8px;">
                                    <option value="" disabled>Select Code</option>
                                    @foreach ($countries as $country)
                                        <option value="{{ $country->phone_code }}"
                                            {{ old('mobile_country_code', $user->mobile_country_code) == $country->phone_code ? 'selected' : '' }}>
                                            {{ $country->phone_code }} ({{ $country->country_code }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('mobile_country_code')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <input type="tel"
                                    class="form-control form-control-sm @error('mobile_number') is-invalid @enderror"
                                    id="mobile_number" name="mobile_number"
                                    value="{{ old('mobile_number', $user->mobile_number) }}" required maxlength="15"
                                    placeholder="Mobile No" style="border-radius: 8px;" />
                                @error('mobile_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Email Display (Read-only) -->
                        <div class="row mb-3 align-items-center">
                            <div class="col-md-3">
                                <label class="form-label fw-semibold" style="color:#2e3192">
                                    <i class="fas fa-envelope me-1"></i>Email
                                </label>
                            </div>
                            <div class="col-md-9">
                                <input type="email" class="form-control form-control-sm" value="{{ $user->email }}"
                                    readonly style="border-radius: 8px; background-color: #f8f9fa;" />
                                <small class="text-muted">Email cannot be changed. Contact support if needed.</small>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="row">
                            <div class="col-md-12 text-end">
                                <button type="submit" class="btn btn-primary btn-lg px-4 py-2 fw-bold"
                                    style="background: linear-gradient(135deg, #2e3192, #4a5bcc); border: none; border-radius: 25px; box-shadow: 0 4px 15px rgba(46, 49, 146, 0.3);">
                                    <i class="fas fa-save me-2"></i>Update Profile
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
