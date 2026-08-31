@extends('admin.auth.layouts.main')

@section('content')
    @if (session('error'))
        <div class="bs-toast toast fade show bg-danger" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header">
                <i class="bx bx-bell me-2"></i>
                <div class="me-auto fw-medium">Error</div>
                <small>Just now</small>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body">
                {{ session('error') }}
            </div>
        </div>
    @endif

    <p class="mb-4">Please sign-in to your account.</p>
    <form id="formAuthentication" class="mb-3 needs-validation was-validated" novalidate="novalidate" method="POST"
        action="{{ route('admin.login') }}" autocomplete="off">
        @csrf

        <div class="mb-3">
            <label for="mobile_no" class="form-label">Email </label>
            <input type="text" class="form-control @error('mobile_no') is-invalid @enderror" id="mobile_no" required
                name="mobile_no" :value="old('mobile_no')" placeholder="Enter your email id" autofocus autocomplete="off" />

            @error('mobile_no')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>
        <div class=" form-password-toggle">
            <div class="d-flex justify-content-between">
                <label class="form-label" for="password">Password</label>
            </div>
            <div class="input-group input-group-merge">
                <input type="password" id="password" required class="form-control" name="password"
                    placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                    aria-describedby="password" autocomplete="off" />
                <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label fw-bold small text-dark mb-1">Security Verification</label>
            <div class="d-flex align-items-center gap-2">
                <img src="{{ route('captcha') }}" alt="captcha" id="captcha-image" class="rounded border p-1 bg-white" style="height: 48px; object-fit: contain; min-width: 140px;">
                <button type="button" class="btn btn-outline-secondary" title="Refresh Captcha" onclick="refreshCaptcha()">↻</button>
                <input type="text" class="form-control" name="captcha" placeholder="Enter numbers" maxlength="6" required style="height: 48px;">
            </div>
            @error('captcha')
                <span class="invalid-feedback d-block small mt-1">{{ $message }}</span>
            @enderror
        </div>

        <div class="mb-5">
            <button class="btn btn-primary d-grid w-100" type="submit">Sign in</button>
        </div>
    </form>

    <script>
        function refreshCaptcha() {
            document.getElementById('captcha-image').src = '{{ route("captcha") }}?' + Date.now();
        }
    </script>
@endsection