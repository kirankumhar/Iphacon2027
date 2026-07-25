@extends('admin.layouts.main')

@section('admin-content')

    @if (session('success'))
        <div class="alert alert-success alert-dismissible mx-3" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if ($errors->any())
        <div class="bs-toast toast fade show bg-danger " role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header">
                <i class="bx bx-bell me-2"></i>
                <div class="me-auto fw-medium">Error</div>
                <small>Just now</small>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif
    <h2>hello</h2>
    {{-- @if ($updatePasswordModal) --}}
    <div class="modal fade" id="changePasswordModal" tabindex="-1" aria-labelledby="changePasswordModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="changePasswordModalLabel">Change Password</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="changePasswordForm" action="" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="newPassword">New Password</label>
                            <input type="password" class="form-control" id="newPassword" name="newPassword" required>
                            <small class="form-text text-muted">Password must be at least 8 characters long, and include
                                uppercase, lowercase, and special characters.</small>
                        </div>
                        <div class="form-group">
                            <label for="confirmPassword">Confirm Password</label>
                            <input type="text" class="form-control" id="confirmPassword" name="confirmPassword" required>
                        </div>
                        <div id="error-message" class="text-danger"></div>
                    </form>
                </div>
                <div class="modal-footer">
                    {{-- <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button> --}}
                    <button type="button" class="btn btn-primary" id="submitPasswordChange">Update Password</button>
                </div>
            </div>
        </div>
    </div>
    {{-- @endif --}}

@endsection

@push('scripts')
    <script>
        window.history.pushState(null, '', window.location.href);
        window.onpopstate = function() {
            window.history.go(1);
        };
    </script>
@endpush
