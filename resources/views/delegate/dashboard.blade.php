<x-layout>
    <x-slot:title>Delegate Dashboard | IPHACON 2027</x-slot:title>

    <div class="container py-3">
        <!-- Welcome Hero Banner -->
        <div class="card border-0 shadow-sm overflow-hidden mb-3" style="border-radius: 10px; background: linear-gradient(135deg, #2e3192, #4a5bcc);">
            <div class="card-body py-2.5 px-3 px-md-4 text-white position-relative">
                <div class="row align-items-center">
                    <div class="col-lg-8">
                        <div class="d-flex align-items-center gap-2 mb-1">
                            <span class="badge bg-white text-primary px-2 py-0.5 rounded-pill fw-bold" style="font-size: 0.7rem;">
                                <i class="fas fa-id-badge me-1"></i>{{ Auth::user()->delegate_type ?? 'Delegate' }}
                            </span>
                            <span class="badge bg-success bg-opacity-75 text-white px-2 py-0.5 rounded-pill fw-semibold" style="font-size: 0.7rem;">
                                <i class="fas fa-check-circle me-1"></i>Email Verified
                            </span>
                        </div>
                        <h5 class="fw-bold mb-0.5" style="letter-spacing: -0.3px;">
                            Welcome, {{ Auth::user()->full_name ?? 'Delegate' }}!
                        </h5>
                        <p class="text-white-50 mb-0" style="font-size: 0.8rem;">
                            IPHACON 2027 • 16th National Biennial Conference • RIMS, Ranchi
                        </p>
                    </div>
                    <div class="col-lg-4 text-lg-end mt-2 mt-lg-0">
                        <a href="{{ route('registration.create') }}" class="btn btn-light btn-sm fw-bold text-primary px-3 py-1.5 shadow-sm" style="border-radius: 6px; font-size: 0.85rem;">
                            <i class="fas fa-paper-plane me-1.5"></i>Complete Registration
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Session & Alert Messages -->
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show py-2 px-3 mb-3 small" role="alert" style="border-radius: 10px;">
                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close btn-close-sm" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show py-2 px-3 mb-3 small" role="alert" style="border-radius: 10px;">
                <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close btn-close-sm" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show py-2 px-3 mb-3 small" role="alert" style="border-radius: 10px;">
                <i class="fas fa-exclamation-triangle me-2"></i><strong>Attention Required:</strong>
                <ul class="mb-0 mt-1 ps-3">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close btn-close-sm" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Quick Action Cards Grid -->
        <div class="row g-3">
            <!-- 1. Conference Registration Card -->
            <div class="col-12 col-md-6 col-lg-4">
                <div class="card h-100 border-0 shadow-sm dashboard-card position-relative overflow-hidden" style="border-radius: 14px; transition: all 0.25s ease; border-top: 4px solid #2e3192 !important;">
                    <div class="card-body p-3.5 d-flex flex-column justify-content-between">
                        <div>
                            <div class="d-flex align-items-center gap-3 mb-3">
                                <div class="rounded-3 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 44px; height: 44px; background: rgba(46, 49, 146, 0.1); color: #2e3192;">
                                    <i class="fas fa-edit fs-5"></i>
                                </div>
                                <div>
                                    <h6 class="fw-bold text-dark mb-0">Conference Registration</h6>
                                    <span class="text-muted extra-small" style="font-size: 0.78rem;">Register for sessions</span>
                                </div>
                            </div>
                            <p class="text-muted small mb-3" style="font-size: 0.85rem; line-height: 1.4;">Register for workshops, scientific sessions, and social events.</p>
                        </div>
                        <a href="{{ route('registration.create') }}" class="btn btn-primary btn-sm fw-semibold w-100 py-2 shadow-xs" style="background: linear-gradient(135deg, #2e3192, #4a5bcc); border: none; border-radius: 8px;">
                            Register Now <i class="fas fa-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- 2. Registration History Card -->
            <div class="col-12 col-md-6 col-lg-4">
                <div class="card h-100 border-0 shadow-sm dashboard-card position-relative overflow-hidden" style="border-radius: 14px; transition: all 0.25s ease; border-top: 4px solid #0dcaf0 !important;">
                    <div class="card-body p-3.5 d-flex flex-column justify-content-between">
                        <div>
                            <div class="d-flex align-items-center gap-3 mb-3">
                                <div class="rounded-3 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 44px; height: 44px; background: rgba(13, 202, 240, 0.12); color: #0aa2c0;">
                                    <i class="fas fa-history fs-5"></i>
                                </div>
                                <div>
                                    <h6 class="fw-bold text-dark mb-0">My Registrations</h6>
                                    <span class="text-muted extra-small" style="font-size: 0.78rem;">Status & receipts</span>
                                </div>
                            </div>
                            <p class="text-muted small mb-3" style="font-size: 0.85rem; line-height: 1.4;">View your registration status, receipt, and pass details.</p>
                        </div>
                        <a href="{{ route('registration.index') }}" class="btn btn-info text-white btn-sm fw-semibold w-100 py-2 shadow-xs" style="border: none; border-radius: 8px;">
                            View History <i class="fas fa-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- 3. Profile & Account Settings Card -->
            <div class="col-12 col-md-6 col-lg-4">
                <div class="card h-100 border-0 shadow-sm dashboard-card position-relative overflow-hidden" style="border-radius: 14px; transition: all 0.25s ease; border-top: 4px solid #4a5bcc !important;">
                    <div class="card-body p-3.5 d-flex flex-column justify-content-between">
                        <div>
                            <div class="d-flex align-items-center gap-3 mb-3">
                                <div class="rounded-3 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 44px; height: 44px; background: rgba(74, 91, 204, 0.12); color: #4a5bcc;">
                                    <i class="fas fa-user-circle fs-5"></i>
                                </div>
                                <div>
                                    <h6 class="fw-bold text-dark mb-0">My Profile</h6>
                                    <span class="text-muted extra-small" style="font-size: 0.78rem;">Account settings</span>
                                </div>
                            </div>
                            <p class="text-muted small mb-3" style="font-size: 0.85rem; line-height: 1.4;">Manage personal info, affiliation, and account security.</p>
                        </div>
                        <a href="{{ route('profile.show') }}" class="btn btn-outline-primary btn-sm fw-semibold w-100 py-2" style="border-radius: 8px; border-color: #4a5bcc; color: #2e3192;">
                            View Profile <i class="fas fa-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Info Notice Section -->
        <div class="mt-3 p-3 rounded-3 bg-white shadow-sm border">
            <div class="row align-items-center">
                <div class="col-lg-8 mb-2 mb-lg-0">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle bg-primary bg-opacity-10 text-primary p-2 d-flex align-items-center justify-content-center me-3 flex-shrink-0" style="width: 42px; height: 42px;">
                            <i class="fas fa-calendar-alt fs-5 text-primary"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold mb-0 text-dark small">Important Dates & Schedule</h6>
                            <p class="text-muted mb-0" style="font-size: 0.82rem;">Pre-Conference Workshop: <strong>4th February 2027</strong> | Main Conference: <strong>5th - 7th February 2027</strong></p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 text-lg-end">
                    <span class="text-muted" style="font-size: 0.82rem;">
                        <i class="fas fa-headset me-1 text-primary"></i>Help: 
                        <a href="mailto:iphacon2027@gmail.com" class="text-decoration-none fw-bold text-primary">iphacon2027@gmail.com</a>
                    </span>
                </div>
            </div>
        </div>
    </div>

    <style>
        .dashboard-card {
            background: #ffffff;
        }
        .dashboard-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 24px rgba(46, 49, 146, 0.12) !important;
        }
    </style>
</x-layout>
