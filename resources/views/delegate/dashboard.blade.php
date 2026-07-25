<x-layout>
    <x-slot:title>Delegate Dashboard | IPHACON 2027</x-slot:title>

    <div class="container py-4 py-md-5">
        <!-- Welcome Hero Banner -->
        <div class="card border-0 shadow-sm overflow-hidden mb-4" style="border-radius: 16px; background: linear-gradient(135deg, #1e255e, #2e3192, #4a5bcc);">
            <div class="card-body p-4 p-md-5 text-white position-relative">
                <div class="row align-items-center">
                    <div class="col-lg-8">
                        <div class="d-flex align-items-center gap-3 mb-2">
                            <span class="badge bg-white text-primary px-3 py-1.5 rounded-pill fw-bold" style="font-size: 0.8rem;">
                                <i class="fas fa-id-badge me-1"></i>{{ Auth::user()->delegate_type ?? 'Delegate' }}
                            </span>
                            <span class="badge bg-success bg-opacity-75 text-white px-3 py-1.5 rounded-pill fw-semibold" style="font-size: 0.8rem;">
                                <i class="fas fa-check-circle me-1"></i>Email Verified
                            </span>
                        </div>
                        <h2 class="fw-bold mb-1" style="letter-spacing: -0.5px;">
                            Welcome, {{ Auth::user()->full_name ?? 'Delegate' }}!
                        </h2>
                        <p class="text-white-50 mb-0" style="font-size: 0.95rem;">
                            IPHACON 2027 • 16th National Biennial Conference • RIMS, Ranchi
                        </p>
                    </div>
                    <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                        <a href="{{ route('registration.create') }}" class="btn btn-light fw-bold text-primary px-4 py-2.5 shadow-sm" style="border-radius: 10px;">
                            <i class="fas fa-paper-plane me-2"></i>Complete Registration
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Session & Alert Messages -->
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show py-3 px-4 mb-4" role="alert" style="border-radius: 12px;">
                <i class="fas fa-check-circle me-2 fs-5"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show py-3 px-4 mb-4" role="alert" style="border-radius: 12px;">
                <i class="fas fa-exclamation-circle me-2 fs-5"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show py-3 px-4 mb-4" role="alert" style="border-radius: 12px;">
                <i class="fas fa-exclamation-triangle me-2 fs-5"></i><strong>Attention Required:</strong>
                <ul class="mb-0 mt-1 ps-3">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Quick Action Cards Grid -->
        <div class="row g-4">
            <!-- 1. Conference Registration Card -->
            <div class="col-12 col-md-6 col-lg-4">
                <div class="card h-100 border-0 shadow-sm dashboard-card" style="border-radius: 16px; transition: transform 0.2s ease, box-shadow 0.2s ease;">
                    <div class="card-body p-4 d-flex flex-column justify-content-between">
                        <div>
                            <div class="rounded-circle bg-success bg-opacity-10 d-flex align-items-center justify-content-center mb-3" style="width: 54px; height: 54px;">
                                <i class="fas fa-edit text-success fs-4"></i>
                            </div>
                            <h5 class="fw-bold text-dark mb-1">Conference Registration</h5>
                            <p class="text-muted small mb-3">Register for workshops, sessions, and social events.</p>
                        </div>
                        <a href="{{ route('registration.create') }}" class="btn btn-outline-success fw-bold w-100 py-2 mt-2" style="border-radius: 9px;">
                            Register Now <i class="fas fa-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- 2. Registration History Card -->
            <div class="col-12 col-md-6 col-lg-4">
                <div class="card h-100 border-0 shadow-sm dashboard-card" style="border-radius: 16px; transition: transform 0.2s ease, box-shadow 0.2s ease;">
                    <div class="card-body p-4 d-flex flex-column justify-content-between">
                        <div>
                            <div class="rounded-circle bg-info bg-opacity-10 d-flex align-items-center justify-content-center mb-3" style="width: 54px; height: 54px;">
                                <i class="fas fa-history text-info fs-4"></i>
                            </div>
                            <h5 class="fw-bold text-dark mb-1">My Registrations</h5>
                            <p class="text-muted small mb-3">View your registration status, receipt, and pass details.</p>
                        </div>
                        <a href="{{ route('registration.index') }}" class="btn btn-outline-info fw-bold w-100 py-2 mt-2" style="border-radius: 9px;">
                            View History <i class="fas fa-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- 3. Profile & Account Settings Card -->
            <div class="col-12 col-md-6 col-lg-4">
                <div class="card h-100 border-0 shadow-sm dashboard-card" style="border-radius: 16px; transition: transform 0.2s ease, box-shadow 0.2s ease;">
                    <div class="card-body p-4 d-flex flex-column justify-content-between">
                        <div>
                            <div class="rounded-circle bg-primary bg-opacity-10 d-flex align-items-center justify-content-center mb-3" style="width: 54px; height: 54px;">
                                <i class="fas fa-user-circle text-primary fs-4"></i>
                            </div>
                            <h5 class="fw-bold text-dark mb-1">My Profile</h5>
                            <p class="text-muted small mb-3">Manage personal info, affiliation, and account security.</p>
                        </div>
                        <a href="{{ route('profile.show') }}" class="btn btn-outline-primary fw-bold w-100 py-2 mt-2" style="border-radius: 9px;">
                            View Profile <i class="fas fa-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Info Notice Section -->
        <div class="mt-5 p-4 rounded-4 bg-light border">
            <div class="row align-items-center">
                <div class="col-lg-8 mb-3 mb-lg-0">
                    <div class="d-flex align-items-start gap-3">
                        <i class="fas fa-calendar-alt text-primary fs-3 mt-1"></i>
                        <div>
                            <h6 class="fw-bold mb-1 text-dark">Important Dates & Schedule</h6>
                            <p class="text-muted small mb-0">Pre-Conference Workshop: <strong>4th February 2027</strong> | Main Conference: <strong>5th - 7th February 2027</strong></p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 text-lg-end">
                    <span class="small text-muted"><i class="fas fa-headset me-1 text-primary"></i>Need Help? Contact Secretariat at <a href="mailto:ismm2027@ismmconference.com" class="text-decoration-none fw-bold">ismm2027@ismmconference.com</a></span>
                </div>
            </div>
        </div>
    </div>

    <style>
        .dashboard-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 24px rgba(0,0,0,0.08) !important;
        }
    </style>
</x-layout>
