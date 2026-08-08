@extends('admin.layouts.main')

@section('admin-content')
    <div class="container-xxl flex-grow-1 ">

        <div class="card my-4">
            <h5 class="card-header">Change Password </h5>
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

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible mx-3" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            <div class="card-body">
                <form id="changePasswordForm" action="{{ route('admin.user.update.password') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="newPassword">New Password</label>
                        <input type="password" class="form-control" id="newPassword" name="newPassword" required>
                        <small class="form-text text-muted">
                            Password must be at least 8 characters long and include uppercase, lowercase, number, and
                            special character (!, @, #, $).
                        </small>
                    </div>

                    <div class="form-group">
                        <label for="confirmPassword">Confirm Password</label>
                        <input type="password" class="form-control" id="confirmPassword" name="confirmPassword" required>
                    </div>

                    <div id="error-message" class="text-danger mt-2"></div>

                    <button type="submit" class="btn btn-primary mt-3" id="submitPasswordChange">Update Password</button>
                </form>
            </div>
        </div>
    </div>
@endsection

