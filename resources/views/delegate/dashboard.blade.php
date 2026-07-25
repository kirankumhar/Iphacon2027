<x-layout>
    <x-slot:title>Delegate Dashboard | IPHACON 2027</x-slot:title>

    <div class="container py-3">
        <!-- Welcome Hero Banner -->
        <div class="card border-0 shadow-sm overflow-hidden mb-3" style="border-radius: 12px; background: linear-gradient(135deg, #1e255e, #2e3192, #4a5bcc);">
            <div class="card-body p-3 p-md-4 text-white position-relative">
                <div class="row align-items-center">
                    <div class="col-lg-8">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <span class="badge bg-white text-primary px-2.5 py-1 rounded-pill fw-bold" style="font-size: 0.75rem;">
                                <i class="fas fa-id-badge me-1"></i>{{ Auth::user()->delegate_type ?? 'Delegate' }}
                            </span>
                            <span class="badge bg-success bg-opacity-75 text-white px-2.5 py-1 rounded-pill fw-semibold" style="font-size: 0.75rem;">
                                <i class="fas fa-check-circle me-1"></i>Email Verified
                            </span>
                        </div>
                        <h3 class="fw-bold mb-1" style="letter-spacing: -0.3px;">
                            Welcome, {{ Auth::user()->full_name ?? 'Delegate' }}!
                        </h3>
                        <p class="text-white-50 mb-0 small">
                            IPHACON 2027 • 16th National Biennial Conference • RIMS, Ranchi
                        </p>
                    </div>
                    <div class="col-lg-4 text-lg-end mt-2 mt-lg-0">
                        <a href="{{ route('registration.create') }}" class="btn btn-light btn-sm fw-bold text-primary px-3 py-2 shadow-sm" style="border-radius: 8px;">
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
                <div class="card h-100 border-0 shadow-sm dashboard-card" style="border-radius: 12px; transition: transform 0.2s ease, box-shadow 0.2s ease;">
                    <div class="card-body p-3.5 d-flex flex-column justify-content-between">
                        <div>
                            <div class="d-flex align-items-center gap-3 mb-2">
                                <div class="rounded-circle bg-success bg-opacity-10 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 44px; height: 44px;">
                                    <i class="fas fa-edit text-success fs-5"></i>
                                </div>
                                <div>
                                    <h6 class="fw-bold text-dark mb-0">Conference Registration</h6>
                                    <span class="text-muted small">Register for sessions</span>
                                </div>
                            </div>
                            <p class="text-muted small mb-3">Register for workshops, sessions, and social events.</p>
                        </div>
                        <a href="{{ route('registration.create') }}" class="btn btn-outline-success btn-sm fw-bold w-100 py-1.5" style="border-radius: 8px;">
                            Register Now <i class="fas fa-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- 2. Registration History Card -->
            <div class="col-12 col-md-6 col-lg-4">
                <div class="card h-100 border-0 shadow-sm dashboard-card" style="border-radius: 12px; transition: transform 0.2s ease, box-shadow 0.2s ease;">
                    <div class="card-body p-3.5 d-flex flex-column justify-content-between">
                        <div>
                            <div class="d-flex align-items-center gap-3 mb-2">
                                <div class="rounded-circle bg-info bg-opacity-10 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 44px; height: 44px;">
                                    <i class="fas fa-history text-info fs-5"></i>
                                </div>
                                <div>
                                    <h6 class="fw-bold text-dark mb-0">My Registrations</h6>
                                    <span class="text-muted small">Status & receipts</span>
                                </div>
                            </div>
                            <p class="text-muted small mb-3">View your registration status, receipt, and pass details.</p>
                        </div>
                        <a href="{{ route('registration.index') }}" class="btn btn-outline-info btn-sm fw-bold w-100 py-1.5" style="border-radius: 8px;">
                            View History <i class="fas fa-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- 3. Profile & Account Settings Card -->
            <div class="col-12 col-md-6 col-lg-4">
                <div class="card h-100 border-0 shadow-sm dashboard-card" style="border-radius: 12px; transition: transform 0.2s ease, box-shadow 0.2s ease;">
                    <div class="card-body p-3.5 d-flex flex-column justify-content-between">
                        <div>
                            <div class="d-flex align-items-center gap-3 mb-2">
                                <div class="rounded-circle bg-primary bg-opacity-10 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 44px; height: 44px;">
                                    <i class="fas fa-user-circle text-primary fs-5"></i>
                                </div>
                                <div>
                                    <h6 class="fw-bold text-dark mb-0">My Profile</h6>
                                    <span class="text-muted small">Account settings</span>
                                </div>
                            </div>
                            <p class="text-muted small mb-3">Manage personal info, affiliation, and account security.</p>
                        </div>
                        <a href="{{ route('profile.show') }}" class="btn btn-outline-primary btn-sm fw-bold w-100 py-1.5" style="border-radius: 8px;">
                            View Profile <i class="fas fa-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Info Notice Section -->
        <div class="mt-3 p-3 rounded-3 bg-light border">
            <div class="row align-items-center">
                <div class="col-lg-8 mb-2 mb-lg-0">
                    <div class="d-flex align-items-center gap-2.5">
                        <i class="fas fa-calendar-alt text-primary fs-5"></i>
                        <div>
                            <h6 class="fw-bold mb-0 text-dark small">Important Dates & Schedule</h6>
                            <p class="text-muted extra-small mb-0" style="font-size: 0.82rem;">Pre-Conference Workshop: <strong>4th February 2027</strong> | Main Conference: <strong>5th - 7th February 2027</strong></p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 text-lg-end">
                    <span class="text-muted" style="font-size: 0.82rem;"><i class="fas fa-headset me-1 text-primary"></i>Help: <a href="mailto:iphacon2027@gmail.com" class="text-decoration-none fw-bold">iphacon2027@gmail.com</a></span>
                </div>
            </div>
        </div>
    </div>

    <style>
        .dashboard-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 18px rgba(0,0,0,0.07) !important;
        }
    </style>
</x-layout>
