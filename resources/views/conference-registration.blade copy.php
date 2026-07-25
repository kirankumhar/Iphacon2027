@extends('shared.index')
@php
    $inner_title = 'Delegate Registration';
@endphp
@section('delegate-content')
    <div class="row justify-content-center">
        <div class="col-lg-12 col-md-12 col-sm-12">
            <div class="card shadow-lg border-0" style="border-radius: 15px;">
                <div class="card-header bg-gradient text-white text-center py-3"
                    style="background: linear-gradient(135deg, #2e3192, #4a5bcc) !important; border-radius: 15px 15px 0 0;">
                    <h4 class="mb-0 fw-bold">
                        <i class="fas fa-user-plus me-2"></i>Registration Form
                    </h4>
                </div>
                <div class="card-body p-4">
                    <form id="myForm" method="POST" action="{{ route('register') }}">
                        @csrf

                        <!-- Full Name Row -->
                        <div class="row mb-3 align-items-center">
                            <div class="col-md-3">
                                <label for="fullName" class="form-label fw-semibold" style="color:#2e3192">
                                    <i class="fas fa-user me-1"></i>Full Name<span class="text-danger">*</span>
                                </label>
                            </div>
                            <div class="col-md-2">
                                <select class="form-select form-select-sm @error('title') is-invalid @enderror"
                                    id="title" name="title" required style="border-radius: 8px;">
                                    <option value="" disabled {{ old('title') ? '' : 'selected' }}>Prefix</option>
                                    <option value="Dr." {{ old('title') == 'Dr.' ? 'selected' : '' }}>Dr.</option>
                                    <option value="Mr." {{ old('title') == 'Mr.' ? 'selected' : '' }}>Mr.</option>
                                    <option value="Mrs." {{ old('title') == 'Mrs.' ? 'selected' : '' }}>Mrs.</option>
                                    <option value="Prof." {{ old('title') == 'Prof.' ? 'selected' : '' }}>Prof.</option>
                                </select>
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-7">
                                <input type="text"
                                    class="form-control form-control-sm @error('fullName') is-invalid @enderror"
                                    id="fullName" name="fullName" value="{{ old('fullName') }}" required
                                    pattern="[A-Za-z ]{2,}" placeholder="Enter your full name"
                                    style="border-radius: 8px;" />
                                @error('fullName')
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
                                            <option value="" disabled {{ old('gender') ? '' : 'selected' }}>Select
                                            </option>
                                            <option value="Male" {{ old('gender') == 'Male' ? 'selected' : '' }}>Male
                                            </option>
                                            <option value="Female" {{ old('gender') == 'Female' ? 'selected' : '' }}>Female
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
                                        <label for="dob" class="form-label fw-semibold" style="color:#2e3192">
                                            <i class="fas fa-calendar me-1"></i>Date of Birth<span
                                                class="text-danger">*</span>
                                        </label>
                                    </div>
                                    <div class="col-md-6">
                                        <input type="date"
                                            class="form-control form-control-sm @error('dob') is-invalid @enderror"
                                            id="dob" name="dob" value="{{ old('dob') }}" required
                                            max="{{ date('Y-m-d', strtotime('-18 years')) }}"
                                            style="border-radius: 8px;" />
                                        @error('dob')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Mobile and Email Row -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="row align-items-center">
                                    <div class="col-md-4">
                                        <label for="mobile" class="form-label fw-semibold" style="color:#2e3192">
                                            <i class="fas fa-phone me-1"></i>Mobile<span class="text-danger">*</span>
                                        </label>
                                    </div>
                                    <div class="col-md-3">
                                        <select class="form-select form-select-sm @error('cont_code') is-invalid @enderror"
                                            id="cont_code" name="cont_code" required style="border-radius: 8px;">
                                            <option value="" disabled>Select Code</option>
                                            @foreach ($countries as $country)
                                                <option value="{{ $country->phone_code }}"
                                                    {{ old('cont_code', '+91') == $country->phone_code ? 'selected' : '' }}>
                                                    {{ $country->phone_code }} ({{ $country->country_code }})
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('cont_code')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-5">
                                        <input type="tel"
                                            class="form-control form-control-sm @error('mobile') is-invalid @enderror"
                                            id="mobile" name="mobile" value="{{ old('mobile') }}" required
                                            maxlength="10" pattern="[0-9]{10}" placeholder="Mobile No"
                                            style="border-radius: 8px;" />
                                        @error('mobile')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="row align-items-center">
                                    <div class="col-md-4">
                                        <label for="email" class="form-label fw-semibold" style="color:#2e3192">
                                            <i class="fas fa-envelope me-1"></i>Email<span class="text-danger">*</span>
                                        </label>
                                    </div>
                                    <div class="col-md-8">
                                        <input type="email"
                                            class="form-control form-control-sm @error('email') is-invalid @enderror"
                                            id="email" name="email" value="{{ old('email') }}" required
                                            placeholder="example@domain.com" style="border-radius: 8px;" />
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Password Row -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="row align-items-center">
                                    <div class="col-md-4">
                                        <label for="password" class="form-label fw-semibold" style="color:#2e3192">
                                            <i class="fas fa-lock me-1"></i>Password<span class="text-danger">*</span>
                                        </label>
                                    </div>
                                    <div class="col-md-7">
                                        <input type="password"
                                            class="form-control form-control-sm @error('password') is-invalid @enderror"
                                            id="password" name="password" required style="border-radius: 8px;" />
                                        @error('password')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-1">
                                        <span id="tick" class="tick text-success fs-5"
                                            style="display: none;">✅</span>
                                    </div>
                                </div>
                                <small class="text-muted" style="font-size: 10px;">
                                    Must contain: 1 lowercase, 1 uppercase, 1 number, 1 special character (min 6 chars)
                                </small>
                            </div>
                            <div class="col-md-6">
                                <div class="row align-items-center">
                                    <div class="col-md-5">
                                        <label for="password_confirmation" class="form-label fw-semibold"
                                            style="color:#2e3192">
                                            <i class="fas fa-lock me-1"></i>Confirm<span class="text-danger">*</span>
                                        </label>
                                    </div>
                                    <div class="col-md-7">
                                        <input type="password"
                                            class="form-control form-control-sm @error('password_confirmation') is-invalid @enderror"
                                            id="password_confirmation" name="password_confirmation" required
                                            placeholder="Confirm password" style="border-radius: 8px;" />
                                        @error('password_confirmation')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Captcha Row -->
                        <div class="row mb-3 align-items-center">
                            <div class="col-md-3">
                                <label for="captcha" class="form-label fw-semibold" style="color:#2e3192">
                                    <i class="fas fa-shield-alt me-1"></i>Captcha<span class="text-danger">*</span>
                                </label>
                            </div>
                            <div class="col-md-3">
                                <img src="{{ captcha_src() }}" alt="CAPTCHA" id="captchaImage"
                                    class="img-fluid border rounded" style="border-radius: 8px; cursor: pointer;"
                                    onclick="refreshCaptcha()">
                            </div>
                            <div class="col-md-6">
                                <input type="text"
                                    class="form-control form-control-sm @error('captcha') is-invalid @enderror"
                                    id="captcha" name="captcha" required placeholder="Enter CAPTCHA"
                                    style="border-radius: 8px;" />
                                @error('captcha')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Consent and Submit Row -->
                        <div class="row mb-3 align-items-center">
                            <div class="col-md-8">
                                <div class="form-check">
                                    <input class="form-check-input @error('consent') is-invalid @enderror"
                                        type="checkbox" id="consent" name="consent" value="1"
                                        {{ old('consent') ? 'checked' : '' }} required />
                                    <label class="form-check-label small fw-semibold" for="consent"
                                        style="color:#2e3192">
                                        <i class="fas fa-check-circle me-1"></i>
                                        I consent to my data and photos being used for conference-related purposes.<span
                                            class="text-danger">*</span>
                                    </label>
                                    @error('consent')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4 text-end">
                                <button type="submit" class="btn btn-primary btn-lg px-4 py-2 fw-bold" id="submitBtn"
                                    style="background: linear-gradient(135deg, #2e3192, #4a5bcc); border: none; border-radius: 25px; box-shadow: 0 4px 15px rgba(46, 49, 146, 0.3);">
                                    <i class="fas fa-user-check me-2"></i>Register
                                </button>
                            </div>
                        </div>

                        <!-- Error Summary -->
                        @if ($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
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

                        <!-- Success Message -->
                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif
                    </form>
                </div>
            </div>
        </div>
    </div>

    <style>
        .form-control-sm,
        .form-select-sm {
            padding: 0.375rem 0.75rem;
            font-size: 0.875rem;
            border: 1px solid #dee2e6;
            transition: all 0.3s ease;
        }

        .form-control-sm:focus,
        .form-select-sm:focus {
            border-color: #2e3192;
            box-shadow: 0 0 0 0.2rem rgba(46, 49, 146, 0.25);
        }

        .is-invalid {
            border-color: #dc3545;
        }

        .invalid-feedback {
            display: block;
            font-size: 0.75rem;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(46, 49, 146, 0.4);
        }

        .btn-primary:disabled {
            opacity: 0.6;
            transform: none;
        }

        .card {
            transition: all 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
        }

        .form-label {
            margin-bottom: 0;
            font-size: 0.9rem;
        }

        .password-strength {
            height: 5px;
            border-radius: 3px;
            margin-top: 5px;
            transition: all 0.3s ease;
        }

        .strength-weak {
            background-color: #dc3545;
        }

        .strength-medium {
            background-color: #ffc107;
        }

        .strength-strong {
            background-color: #28a745;
        }

        @media (max-width: 768px) {

            .col-md-3,
            .col-md-4,
            .col-md-5,
            .col-md-6,
            .col-md-7,
            .col-md-8 {
                margin-bottom: 0.5rem;
            }

            .form-label {
                font-size: 0.85rem;
            }
        }
    </style>

    <script>
        // Form validation script
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('myForm');
            const fullNameInput = document.getElementById('fullName');
            const passwordInput = document.getElementById('password');
            const confirmPasswordInput = document.getElementById(
                'password_confirmation'); // Updated to match Laravel naming
            const emailInput = document.getElementById('email');
            const mobileInput = document.getElementById('mobile');
            const dobInput = document.getElementById('dob');
            const submitBtn = document.getElementById('submitBtn');
            const tickSpan = document.getElementById('tick');

            // Real-time validation for full name
            fullNameInput.addEventListener('input', function() {
                validateFullName();
                checkFormValidity();
            });

            // Real-time validation for password
            passwordInput.addEventListener('input', function() {
                validatePassword();
                if (confirmPasswordInput.value) {
                    validateConfirmPassword(); // Re-validate confirm password when password changes
                }
                checkFormValidity();
            });

            // Real-time validation for confirm password
            confirmPasswordInput.addEventListener('input', function() {
                validateConfirmPassword();
                checkFormValidity();
            });

            // Email validation
            emailInput.addEventListener('input', function() {
                validateEmail();
                checkFormValidity();
            });

            // Mobile validation
            mobileInput.addEventListener('input', function() {
                validateMobile();
                checkFormValidity();
            });

            // DOB validation
            dobInput.addEventListener('change', function() {
                validateDOB();
                checkFormValidity();
            });

            // Title and Gender validation
            document.getElementById('title').addEventListener('change', function() {
                validateRequired(this, 'Please select a title');
                checkFormValidity();
            });

            document.getElementById('gender').addEventListener('change', function() {
                validateRequired(this, 'Please select gender');
                checkFormValidity();
            });

            document.getElementById('captcha').addEventListener('input', function() {
                validateRequired(this, 'Please enter CAPTCHA');
                checkFormValidity();
            });

            document.getElementById('consent').addEventListener('change', function() {
                validateConsent();
                checkFormValidity();
            });

            // Full Name Validation
            function validateFullName() {
                const nameValue = fullNameInput.value.trim();
                const namePattern = /^[A-Za-z\s]{2,50}$/;

                if (nameValue === '') {
                    showError(fullNameInput, 'Full name is required');
                    return false;
                } else if (!namePattern.test(nameValue)) {
                    showError(fullNameInput, 'Name should contain only letters and spaces (2-50 characters)');
                    return false;
                } else if (nameValue.length < 2) {
                    showError(fullNameInput, 'Name must be at least 2 characters long');
                    return false;
                } else {
                    showSuccess(fullNameInput);
                    return true;
                }
            }

            // Password Validation
            function validatePassword() {
                const passwordValue = passwordInput.value;
                const passwordPattern = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{6,}$/;

                if (passwordValue === '') {
                    showError(passwordInput, 'Password is required');
                    tickSpan.style.display = 'none';
                    return false;
                } else if (passwordValue.length < 6) {
                    showError(passwordInput, 'Password must be at least 6 characters long');
                    tickSpan.style.display = 'none';
                    return false;
                } else if (!passwordPattern.test(passwordValue)) {
                    showError(passwordInput,
                        'Password must contain at least 1 lowercase, 1 uppercase, 1 number, and 1 special character'
                    );
                    tickSpan.style.display = 'none';
                    return false;
                } else {
                    showSuccess(passwordInput);
                    tickSpan.style.display = 'inline';
                    tickSpan.innerHTML = '✅';
                    return true;
                }
            }

            // Confirm Password Validation
            function validateConfirmPassword() {
                const confirmPasswordValue = confirmPasswordInput.value;
                const passwordValue = passwordInput.value;

                if (confirmPasswordValue === '') {
                    showError(confirmPasswordInput, 'Please confirm your password');
                    return false;
                } else if (confirmPasswordValue !== passwordValue) {
                    showError(confirmPasswordInput, 'Passwords do not match');
                    return false;
                } else {
                    showSuccess(confirmPasswordInput);
                    return true;
                }
            }

            // Email Validation
            function validateEmail() {
                const emailValue = emailInput.value.trim();
                const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

                if (emailValue === '') {
                    showError(emailInput, 'Email is required');
                    return false;
                } else if (!emailPattern.test(emailValue)) {
                    showError(emailInput, 'Please enter a valid email address');
                    return false;
                } else {
                    showSuccess(emailInput);
                    return true;
                }
            }

            // Mobile Validation
            function validateMobile() {
                let mobileValue = mobileInput.value.trim();

                // Remove any non-numeric characters
                mobileValue = mobileValue.replace(/[^0-9]/g, '');
                mobileInput.value = mobileValue;

                // Limit to 10 digits
                if (mobileValue.length > 10) {
                    mobileValue = mobileValue.slice(0, 10);
                    mobileInput.value = mobileValue;
                }

                const mobilePattern = /^[6-9]\d{9}$/; // Indian mobile number pattern

                if (mobileValue === '') {
                    showError(mobileInput, 'Mobile number is required');
                    return false;
                } else if (mobileValue.length !== 10) {
                    showError(mobileInput, 'Mobile number must be exactly 10 digits');
                    return false;
                } else if (!mobilePattern.test(mobileValue)) {
                    showError(mobileInput, 'Mobile number must start with 6, 7, 8, or 9');
                    return false;
                } else {
                    showSuccess(mobileInput);
                    return true;
                }
            }

            // DOB Validation (18+ years)
            function validateDOB() {
                const dobValue = dobInput.value;

                if (dobValue === '') {
                    showError(dobInput, 'Date of birth is required');
                    return false;
                }

                const dob = new Date(dobValue);
                const today = new Date();
                const age = today.getFullYear() - dob.getFullYear();
                const monthDiff = today.getMonth() - dob.getMonth();

                let actualAge = age;
                if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < dob.getDate())) {
                    actualAge--;
                }

                if (actualAge < 18) {
                    showError(dobInput, 'You must be at least 18 years old');
                    return false;
                } else {
                    showSuccess(dobInput);
                    return true;
                }
            }

            // Required field validation
            function validateRequired(input, message) {
                if (input.value === '' || input.value === null) {
                    showError(input, message);
                    return false;
                } else {
                    showSuccess(input);
                    return true;
                }
            }

            // Consent validation
            function validateConsent() {
                const consentCheckbox = document.getElementById('consent');
                if (!consentCheckbox.checked) {
                    showError(consentCheckbox, 'You must consent to data usage');
                    return false;
                } else {
                    showSuccess(consentCheckbox);
                    return true;
                }
            }

            // Show error message
            function showError(input, message) {
                input.classList.remove('is-valid');
                input.classList.add('is-invalid');

                // Remove existing error message
                const existingError = input.parentNode.querySelector('.invalid-feedback');
                if (existingError) {
                    existingError.remove();
                }

                // Add new error message
                const errorDiv = document.createElement('div');
                errorDiv.className = 'invalid-feedback';
                errorDiv.innerHTML = message;
                input.parentNode.appendChild(errorDiv);
            }

            // Show success state
            function showSuccess(input) {
                input.classList.remove('is-invalid');
                input.classList.add('is-valid');

                // Remove error message
                const existingError = input.parentNode.querySelector('.invalid-feedback');
                if (existingError) {
                    existingError.remove();
                }
            }

            // Check overall form validity and enable/disable submit button
            function checkFormValidity() {
                const isValid = validateAllFields(false); // Silent validation

                if (submitBtn) {
                    if (isValid) {
                        submitBtn.disabled = false;
                        submitBtn.classList.remove('btn-secondary');
                        submitBtn.classList.add('btn-primary');
                        submitBtn.style.opacity = '1';
                    } else {
                        submitBtn.disabled = true;
                        submitBtn.classList.remove('btn-primary');
                        submitBtn.classList.add('btn-secondary');
                        submitBtn.style.opacity = '0.6';
                    }
                }
            }

            // Validate all required fields
            function validateAllFields(showErrors = true) {
                let isValid = true;
                const errors = [];

                // Validate each field
                if (!validateRequired(document.getElementById('title'), 'Please select a title')) {
                    isValid = false;
                    if (showErrors) errors.push('Title is required');
                }

                if (!validateFullName()) {
                    isValid = false;
                    if (showErrors) errors.push('Full name is invalid');
                }

                if (!validateRequired(document.getElementById('gender'), 'Please select gender')) {
                    isValid = false;
                    if (showErrors) errors.push('Gender is required');
                }

                if (!validateDOB()) {
                    isValid = false;
                    if (showErrors) errors.push('Valid date of birth is required (18+ years)');
                }

                if (!validateMobile()) {
                    isValid = false;
                    if (showErrors) errors.push('Valid mobile number is required');
                }

                if (!validateEmail()) {
                    isValid = false;
                    if (showErrors) errors.push('Valid email is required');
                }

                if (!validatePassword()) {
                    isValid = false;
                    if (showErrors) errors.push('Strong password is required');
                }

                if (!validateConfirmPassword()) {
                    isValid = false;
                    if (showErrors) errors.push('Password confirmation must match');
                }

                if (!validateRequired(document.getElementById('captcha'), 'Please enter CAPTCHA')) {
                    isValid = false;
                    if (showErrors) errors.push('CAPTCHA is required');
                }

                if (!validateConsent()) {
                    isValid = false;
                    if (showErrors) errors.push('Consent is required');
                }

                // Show error summary if there are errors and showErrors is true
                if (!isValid && showErrors && errors.length > 0) {
                    showErrorSummary(errors);
                } else {
                    hideErrorSummary();
                }

                return isValid;
            }

            // Show error summary
            function showErrorSummary(errors) {
                // Remove existing error summary
                hideErrorSummary();

                const errorSummary = document.createElement('div');
                errorSummary.className = 'alert alert-danger alert-dismissible fade show mt-3';
                errorSummary.id = 'errorSummary';
                errorSummary.innerHTML = `
            <strong><i class="fas fa-exclamation-triangle me-2"></i>Please fix the following errors:</strong>
            <ul class="mb-0 mt-2">
                ${errors.map(error => `<li>${error}</li>`).join('')}
            </ul>
            <button type="button" class="btn-close" onclick="hideErrorSummary()"></button>
        `;

                // Insert before the submit button row
                const submitRow = document.querySelector('.row:last-child');
                submitRow.parentNode.insertBefore(errorSummary, submitRow);
            }

            // Hide error summary
            function hideErrorSummary() {
                const existingSummary = document.getElementById('errorSummary');
                if (existingSummary) {
                    existingSummary.remove();
                }
            }

            // Make function globally accessible
            window.hideErrorSummary = hideErrorSummary;

            // Form submission handler
            form.addEventListener('submit', function(e) {
                e.preventDefault(); // Always prevent default submission initially

                if (validateAllFields(true)) {
                    // If validation passes, show loading state
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Registering...';

                    // Allow form to submit
                    setTimeout(() => {
                        form.submit(); // Submit the form to Laravel
                    }, 500);
                } else {
                    // Scroll to first error
                    const firstError = document.querySelector('.is-invalid');
                    if (firstError) {
                        firstError.scrollIntoView({
                            behavior: 'smooth',
                            block: 'center'
                        });
                        firstError.focus();
                    }
                }
            });

            // Initial form validity check
            checkFormValidity();

            // Make validateAllFields globally accessible for the save() function
            window.validateForm = validateAllFields;
        });

        // Refresh CAPTCHA function
        function refreshCaptcha() {
            document.getElementById('captchaImage').src = '{{ captcha_src() }}?' + new Date().getTime();
            document.getElementById('captcha').value = '';
        }

        // Optional: Keep the save function for backward compatibility
        function save() {
            // Trigger form submission which will handle validation
            document.getElementById('myForm').dispatchEvent(new Event('submit'));
        }
    </script>


@endsection
