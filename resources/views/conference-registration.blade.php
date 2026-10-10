<x-layout>
    <x-slot:title>Delegate Registration | IPHACON 2027</x-slot:title>
    <link rel="stylesheet" href="{{ asset('assets/css/delegates/registration.css') }}">
    <div class="registration-page-wrapper py-3 py-md-4">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-12 col-md-11 col-lg-10 col-xl-10">
                    <!-- Compact Registration Card -->
                    <div class="card registration-card shadow-lg border-0 overflow-hidden">

                        <!-- Accent Bar -->
                        <div class="card-accent-bar"></div>

                        <!-- Compact Card Header -->
                        <div class="card-header text-center py-3 px-3.5 border-0 position-relative">
                            <div class="header-bg-glow"></div>

                            <div class="position-relative z-1">
                                <!-- <div class="d-inline-flex align-items-center justify-content-center mb-1 px-3 py-1 rounded-pill header-badge-pill extra-small fw-semibold">
                                    <i class="fas fa-award text-warning me-1.5"></i> <span>71<sup>st</sup> Annual National Conference</span>
                                </div> -->
                                <h5 class="text-white fw-bold mb-0.5 tracking-wide">Delegate Registration</h5>
                                <p class="text-white-80 mb-0 extra-small">
                                    <i class="fas fa-map-marker-alt me-1 text-danger-light"></i> IPHACON 2027 • RIMS,
                                    Ranchi
                                </p>
                            </div>
                        </div>

                        <!-- Compact Card Body -->
                        <div class="card-body p-3.5 p-md-4 bg-white">

                            <!-- Alert Messages -->
                            @if (
                                $errors->any() &&
                                    !$errors->has('delegate_type') &&
                                    !$errors->has('country_id') &&
                                    !$errors->has('email') &&
                                    !$errors->has('password') &&
                                    !$errors->has('captcha'))
                                <div class="alert alert-custom-danger alert-dismissible fade show p-2.5 mb-3 rounded-3 d-flex align-items-center gap-2"
                                    role="alert">
                                    <i class="fas fa-exclamation-triangle small text-danger flex-shrink-0"></i>
                                    <div class="extra-small text-dark">
                                        @foreach ($errors->all() as $error)
                                            <div>{{ $error }}</div>
                                        @endforeach
                                    </div>
                                    <button type="button" class="btn-close py-2 px-2" data-bs-dismiss="alert"></button>
                                </div>
                            @endif

                            @if (session('success'))
                                <div class="alert alert-custom-success alert-dismissible fade show p-2.5 mb-3 rounded-3 d-flex align-items-center gap-2"
                                    role="alert">
                                    <i class="fas fa-check-circle small text-success flex-shrink-0"></i>
                                    <div class="extra-small fw-medium text-dark">{{ session('success') }}</div>
                                    <button type="button" class="btn-close py-2 px-2" data-bs-dismiss="alert"></button>
                                </div>
                            @endif

                            <form method="POST" action="{{ route('register') }}" id="registrationForm"
                                class="needs-validation" novalidate>
                                @csrf

                                <div class="row g-2.5">

                                    <!-- Delegate Category Selector -->
                                    <div class="col-12 mb-1">
                                        <label
                                            class="form-label font-heading fw-bold text-navy mb-1.5 extra-small uppercase-label">
                                            <i class="fas fa-globe-asia text-primary me-1"></i>Delegate Category <span
                                                class="text-danger">*</span>
                                        </label>

                                        <div class="row g-2">
                                            <!-- Indian Delegate Option -->
                                            <div class="col-6">
                                                <label
                                                    class="delegate-type-card position-relative d-flex align-items-center p-2 rounded-3 border cursor-pointer w-100 h-100 transition-all"
                                                    for="indian" id="card-indian">
                                                    <input class="form-check-input d-none" type="radio"
                                                        name="delegate_type" id="indian" value="Indian"
                                                        {{ old('delegate_type', 'Indian') == 'Indian' ? 'checked' : '' }}
                                                        required>

                                                    <div
                                                        class="card-radio-icon rounded-circle d-flex align-items-center justify-content-center me-2 bg-success-subtle text-success">
                                                        <i class="fas fa-flag extra-small"></i>
                                                    </div>

                                                    <div class="flex-grow-1 min-w-0">
                                                        <span
                                                            class="d-block fw-bold text-dark small leading-tight mb-0">Indian</span>
                                                        <span
                                                            class="text-muted d-block extra-small text-truncate">Domestic</span>
                                                    </div>

                                                    <div
                                                        class="selected-badge position-absolute top-0 end-0 m-1.5 d-none">
                                                        <i class="fas fa-check-circle text-primary extra-small"></i>
                                                    </div>
                                                </label>
                                            </div>

                                            <!-- International Delegate Option -->
                                            <div class="col-6">
                                                <label
                                                    class="delegate-type-card position-relative d-flex align-items-center p-2 rounded-3 border cursor-pointer w-100 h-100 transition-all"
                                                    for="international" id="card-international">
                                                    <input class="form-check-input d-none" type="radio"
                                                        name="delegate_type" id="international" value="International"
                                                        {{ old('delegate_type') == 'International' ? 'checked' : '' }}
                                                        required>

                                                    <div
                                                        class="card-radio-icon rounded-circle d-flex align-items-center justify-content-center me-2 bg-primary-subtle text-primary">
                                                        <i class="fas fa-globe-americas extra-small"></i>
                                                    </div>

                                                    <div class="flex-grow-1 min-w-0">
                                                        <span
                                                            class="d-block fw-bold text-dark small leading-tight mb-0">International</span>
                                                        <span
                                                            class="text-muted d-block extra-small text-truncate">Foreign</span>
                                                    </div>

                                                    <div
                                                        class="selected-badge position-absolute top-0 end-0 m-1.5 d-none">
                                                        <i class="fas fa-check-circle text-primary extra-small"></i>
                                                    </div>
                                                </label>
                                            </div>
                                        </div>
                                        @error('delegate_type')
                                            <div class="text-danger mt-1 extra-small"><i
                                                    class="fas fa-exclamation-circle me-1"></i>{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Country Select -->
                                    <div class="col-12">
                                        <label for="country_id"
                                            class="form-label fw-semibold text-dark extra-small mb-1">
                                            Country of Origin <span class="text-danger">*</span>
                                        </label>
                                        <div class="input-group modern-input-group">
                                            <span class="input-group-text border-end-0 bg-light text-muted px-2.5">
                                                <i class="fas fa-flag text-primary extra-small"></i>
                                            </span>
                                            @php
                                                $firstACountry = $countries->first(function ($c) {
                                                    return str_starts_with(strtoupper(trim($c->country_name)), 'A');
                                                });
                                            @endphp
                                            <select
                                                class="form-select border-start-0 custom-input @error('country_id') is-invalid @enderror"
                                                id="country_id" name="country_id" required>
                                                <option value="">Select Country</option>
                                                @foreach ($countries as $country)
                                                    <option value="{{ $country->id }}"
                                                        {{ old('country_id') == $country->id ||
                                                        (!old('country_id') &&
                                                            old('delegate_type', 'Indian') == 'Indian' &&
                                                            (strtolower(trim($country->country_name)) == 'india' ||
                                                                str_contains(strtolower($country->country_name), 'india'))) ||
                                                        (!old('country_id') && old('delegate_type') == 'International' && $firstACountry && $firstACountry->id == $country->id)
                                                            ? 'selected'
                                                            : '' }}>
                                                        {{ $country->country_name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        @error('country_id')
                                            <div class="invalid-feedback d-block extra-small mt-1"><i
                                                    class="fas fa-exclamation-circle me-1"></i>{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Email Input -->
                                    <div class="col-12">
                                        <label for="email" class="form-label fw-semibold text-dark extra-small mb-1">
                                            Email Address <span class="text-danger">*</span>
                                        </label>
                                        <div class="input-group modern-input-group">
                                            <span class="input-group-text border-end-0 bg-light text-muted px-2.5">
                                                <i class="fas fa-envelope text-primary extra-small"></i>
                                            </span>
                                            <input type="email"
                                                class="form-control border-start-0 custom-input @error('email') is-invalid @enderror"
                                                id="email" name="email" value="{{ old('email') }}" required
                                                placeholder="e.g. doctor@example.com">
                                        </div>
                                        @error('email')
                                            <div class="invalid-feedback d-block extra-small mt-1"><i
                                                    class="fas fa-exclamation-circle me-1"></i>{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Password Input -->
                                    <div class="col-12 col-sm-6">
                                        <label for="password"
                                            class="form-label fw-semibold text-dark extra-small mb-1">
                                            Password <span class="text-danger">*</span>
                                        </label>
                                        <div class="input-group modern-input-group">
                                            <span class="input-group-text border-end-0 bg-light text-muted px-2.5">
                                                <i class="fas fa-key text-primary extra-small"></i>
                                            </span>
                                            <input type="password"
                                                class="form-control border-start-0 border-end-0 custom-input @error('password') is-invalid @enderror"
                                                id="password" name="password" required minlength="8"
                                                placeholder="Min 8 characters">
                                            <button
                                                class="btn btn-light border border-start-0 text-muted px-2.5 toggle-pw-btn"
                                                type="button" id="togglePasswordBtn" title="Toggle visibility">
                                                <i class="fas fa-eye extra-small" id="toggleIcon"></i>
                                            </button>
                                        </div>

                                        <!-- Password Strength Indicator & Requirement Badges -->
                                        <div id="pw-strength-wrapper" class="mt-1.5 d-none">
                                            <div class="d-flex align-items-center justify-content-between mb-1">
                                                <span id="pw-strength-text"
                                                    class="extra-small fw-bold text-muted pw-strength-text"></span>
                                                <span id="pw-length-badge"
                                                    class="extra-small text-muted pw-length-badge"></span>
                                            </div>
                                            <div class="progress pw-progress">
                                                <div id="pw-strength-bar"
                                                    class="progress-bar transition-all pw-strength-bar"
                                                    role="progressbar"></div>
                                            </div>

                                            <div class="d-flex flex-wrap gap-1 mt-1.5 pw-requirements">
                                                <span id="req-length"
                                                    class="badge bg-light text-muted border fw-normal transition-all py-1 px-1.5">
                                                    <i class="far fa-circle me-1 opacity-50"></i>8+ Chars
                                                </span>
                                                <span id="req-letters"
                                                    class="badge bg-light text-muted border fw-normal transition-all py-1 px-1.5">
                                                    <i class="far fa-circle me-1 opacity-50"></i>4+ Letters
                                                </span>
                                                <span id="req-uppercase"
                                                    class="badge bg-light text-muted border fw-normal transition-all py-1 px-1.5">
                                                    <i class="far fa-circle me-1 opacity-50"></i>Uppercase
                                                </span>
                                                <span id="req-number"
                                                    class="badge bg-light text-muted border fw-normal transition-all py-1 px-1.5">
                                                    <i class="far fa-circle me-1 opacity-50"></i>Number
                                                </span>
                                                <span id="req-symbol"
                                                    class="badge bg-light text-muted border fw-normal transition-all py-1 px-1.5">
                                                    <i class="far fa-circle me-1 opacity-50"></i>Symbol (!@#$)
                                                </span>
                                            </div>
                                        </div>

                                        @error('password')
                                            <div class="invalid-feedback d-block extra-small mt-1"><i
                                                    class="fas fa-exclamation-circle me-1"></i>{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Confirm Password Input -->
                                    <div class="col-12 col-sm-6">
                                        <label for="password_confirmation"
                                            class="form-label fw-semibold text-dark extra-small mb-1">
                                            Confirm Password <span class="text-danger">*</span>
                                        </label>
                                        <div class="input-group modern-input-group">
                                            <span class="input-group-text border-end-0 bg-light text-muted px-2.5">
                                                <i class="fas fa-shield-alt text-primary extra-small"></i>
                                            </span>
                                            <input type="password"
                                                class="form-control border-start-0 custom-input @error('password_confirmation') is-invalid @enderror"
                                                id="password_confirmation" name="password_confirmation" required
                                                placeholder="Re-enter password">
                                        </div>
                                        <div id="pw-match-feedback" class="extra-small mt-1 d-none"></div>
                                        @error('password_confirmation')
                                            <div class="invalid-feedback d-block extra-small mt-1"><i
                                                    class="fas fa-exclamation-circle me-1"></i>{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Clean CAPTCHA Section -->
                                    <div class="col-12 mt-1">
                                        <div class="captcha-card p-3 rounded-3 border bg-light">
                                            <div class="d-flex align-items-center justify-content-between mb-2">
                                                <label for="captcha"
                                                    class="form-label fw-bold text-dark extra-small mb-0 d-flex align-items-center gap-1">
                                                    <i class="fas fa-shield-virus text-primary"></i> Security
                                                    Verification <span class="text-danger">*</span>
                                                </label>
                                                <span class="text-muted extra-small captcha-hint">Click image to
                                                    refresh</span>
                                            </div>

                                            <div class="row g-2 align-items-center">
                                                <div class="col-6 d-flex align-items-center">
                                                    <div class="captcha-img-wrapper position-relative rounded-2 border shadow-sm cursor-pointer bg-white px-1.5 py-1 d-flex align-items-center justify-content-center flex-grow-1"
                                                        id="captchaImgWrapper" title="Click image to refresh CAPTCHA">
                                                        <img src="{{ captcha_src() }}"
                                                            data-captcha-src="{{ captcha_src() }}" alt="CAPTCHA"
                                                            id="captchaImage" class="img-fluid captcha-img">
                                                        <div
                                                            class="captcha-overlay position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center bg-dark bg-opacity-25 opacity-0 hover-opacity-100 transition-all rounded-2">
                                                            <i class="fas fa-sync-alt text-white extra-small"></i>
                                                        </div>
                                                    </div>
                                                    <button type="button"
                                                        class="btn btn-sm btn-light border rounded-circle p-1.5 ms-2 flex-shrink-0 shadow-sm"
                                                        id="captchaRefreshBtn" title="Refresh Code">
                                                        <i class="fas fa-sync-alt text-primary extra-small"
                                                            id="captchaSpinner"></i>
                                                    </button>
                                                </div>

                                                <div class="col-6">
                                                    <input type="text"
                                                        class="form-control custom-input @error('captcha') is-invalid @enderror text-center fw-bold letter-spacing-2 captcha-input"
                                                        id="captcha" name="captcha" required
                                                        placeholder="ENTER CODE" autocomplete="off">
                                                </div>
                                            </div>
                                            @error('captcha')
                                                <div class="invalid-feedback d-block extra-small text-center mt-1.5"><i
                                                        class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Submit Button -->
                                    <div class="col-12 pt-1.5">
                                        <button type="submit"
                                            class="btn btn-submit-glow text-white fw-bold w-100 py-2.5 rounded-3 shadow-md position-relative overflow-hidden transition-all">
                                            <span class="d-flex align-items-center justify-content-center gap-2">
                                                <i class="fas fa-user-plus small"></i>
                                                <span class="small">Create Account & Proceed</span>
                                                <i class="fas fa-arrow-right extra-small submit-arrow"></i>
                                            </span>
                                        </button>
                                    </div>

                                    <!-- Already Have Account -->
                                    <div class="col-12 text-center pt-1">
                                        <p class="text-muted extra-small mb-0">
                                            Already registered?
                                            <a href="{{ route('login') }}"
                                                class="text-primary fw-bold text-decoration-none hover-underline ms-1">
                                                Sign In Here <i
                                                    class="fas fa-chevron-right extra-small icon-chevron-xs"></i>
                                            </a>
                                        </p>
                                    </div>

                                    <!-- Compact Info Notice -->
                                    <div class="col-12 mt-1">
                                        <div
                                            class="info-card p-2 px-2.5 rounded-2 border-0 d-flex align-items-center gap-2">
                                            <i class="fas fa-info-circle text-primary extra-small flex-shrink-0"></i>
                                            <div class="extra-small text-muted leading-tight">
                                                <strong>Note:</strong> Verify email after signup to complete profile &
                                                payment.
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('assets/js/delegates/conference-registration.js') }}" defer></script>
</x-layout>
