<x-delegate.layout>
    <x-slot:title>Delegate Dashboard | IPHACON 2027</x-slot:title>

    <div class="container py-3">
        <!-- Welcome Hero Banner -->
        <div class="card border-0 shadow-sm overflow-hidden mb-4" style="border-radius: 14px; background: linear-gradient(135deg, #013069 0%, #0d47a1 60%, #1565c0 100%); margin-bottom: 1.85rem !important;">
            <div class="card-body py-3.5 px-3 px-md-4 text-white position-relative">
                <div class="row align-items-center">
                    <div class="col-lg-8">
                        <div class="d-flex align-items-center gap-2 mb-1">
                            <span class="badge bg-white text-primary px-2.5 py-1 rounded-pill fw-bold" style="font-size: 0.72rem;">
                                <i class="fas fa-id-badge me-1"></i>{{ Auth::user()->delegate_type ?? 'Delegate' }}
                            </span>
                            <span class="badge bg-success bg-opacity-75 text-white px-2.5 py-1 rounded-pill fw-semibold" style="font-size: 0.72rem;">
                                <i class="fas fa-check-circle me-1"></i>Verified Account
                            </span>
                        </div>
                        <h4 class="fw-bold mb-1" style="letter-spacing: -0.3px;">
                            Welcome, {{ Auth::user()->prefix ?? '' }} {{ Auth::user()->full_name ?? 'Delegate' }}!
                        </h4>
                        <p class="text-white-50 mb-0" style="font-size: 0.85rem;">
                            71st Annual National Conference of IPHA • IPHACON 2027 • RIMS, Ranchi
                        </p>
                    </div>
                    <div class="col-lg-4 text-lg-end mt-2 mt-lg-0">
                        @if(!$registration)
                            <a href="{{ route('registration.create') }}" class="btn btn-warning btn-sm fw-bold px-3 py-2 shadow-sm" style="border-radius: 8px; font-size: 0.85rem;">
                                <i class="fas fa-paper-plane me-1.5"></i>Register Now
                            </a>
                        @else
                            <a href="{{ route('abstract.create') }}" class="btn btn-light btn-sm fw-bold text-primary px-3 py-2 shadow-sm" style="border-radius: 8px; font-size: 0.85rem;">
                                <i class="fas fa-file-alt me-1.5"></i>Submit / View Abstract
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Session & Alert Messages -->
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show py-2.5 px-3 mb-4 small fw-semibold" role="alert" style="border-radius: 10px;">
                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close btn-close-sm" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show py-2.5 px-3 mb-4 small fw-semibold" role="alert" style="border-radius: 10px;">
                <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close btn-close-sm" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show py-2.5 px-3 mb-4 small" role="alert" style="border-radius: 10px;">
                <i class="fas fa-exclamation-triangle me-2"></i><strong>Attention Required:</strong>
                <ul class="mb-0 mt-1 ps-3">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close btn-close-sm" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Quick Action Cards Grid (4 Columns) -->
        <div class="row g-3 mb-4 pt-1">
            <!-- 1. Conference Registration Card -->
            <div class="col-12 col-sm-6 col-lg-3">
                <div class="card h-100 border-0 shadow-sm dashboard-card position-relative overflow-hidden" style="border-radius: 14px; border-top: 4px solid #0d47a1 !important;">
                    <div class="card-body p-3.5 d-flex flex-column justify-content-between">
                        <div>
                            <div class="d-flex align-items-center gap-2.5 mb-2.5">
                                <div class="rounded-3 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 40px; height: 40px; background: rgba(13, 71, 161, 0.1); color: #0d47a1;">
                                    <i class="fas fa-edit fs-5"></i>
                                </div>
                                <div>
                                    <h6 class="fw-bold text-dark mb-0">Registration</h6>
                                    <span class="text-muted extra-small" style="font-size: 0.75rem;">Conference Portal</span>
                                </div>
                            </div>
                            <p class="text-muted small mb-3" style="font-size: 0.82rem; line-height: 1.35;">Register for sessions, CME workshops & accompanying details.</p>
                        </div>
                        <a href="{{ route('registration.create') }}" class="btn btn-primary btn-sm fw-semibold w-100 py-1.5 shadow-xs" style="background: linear-gradient(135deg, #013069, #0d47a1); border: none; border-radius: 8px;">
                            {{ $registration ? 'Update Details' : 'Register Now' }} <i class="fas fa-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- 2. Registration Status & Receipt Card -->
            <div class="col-12 col-sm-6 col-lg-3">
                <div class="card h-100 border-0 shadow-sm dashboard-card position-relative overflow-hidden" style="border-radius: 14px; border-top: 4px solid #0288d1 !important;">
                    <div class="card-body p-3.5 d-flex flex-column justify-content-between">
                        <div>
                            <div class="d-flex align-items-center gap-2.5 mb-2.5">
                                <div class="rounded-3 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 40px; height: 40px; background: rgba(2, 136, 209, 0.12); color: #0288d1;">
                                    <i class="fas fa-receipt fs-5"></i>
                                </div>
                                <div>
                                    <h6 class="fw-bold text-dark mb-0">My Registrations</h6>
                                    <span class="text-muted extra-small" style="font-size: 0.75rem;">Status & Receipt PDF</span>
                                </div>
                            </div>
                            <p class="text-muted small mb-3" style="font-size: 0.82rem; line-height: 1.35;">Check status, payment summary & download PDF receipt.</p>
                        </div>
                        <a href="{{ route('registration.index') }}" class="btn btn-info text-white btn-sm fw-semibold w-100 py-1.5 shadow-xs" style="border: none; border-radius: 8px; background: #0288d1;">
                            View Details <i class="fas fa-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- 3. Abstract Submission Card -->
            <div class="col-12 col-sm-6 col-lg-3">
                <div class="card h-100 border-0 shadow-sm dashboard-card position-relative overflow-hidden" style="border-radius: 14px; border-top: 4px solid #ff6b00 !important;">
                    <div class="card-body p-3.5 d-flex flex-column justify-content-between">
                        <div>
                            <div class="d-flex align-items-center gap-2.5 mb-2.5">
                                <div class="rounded-3 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 40px; height: 40px; background: rgba(255, 107, 0, 0.12); color: #ff6b00;">
                                    <i class="fas fa-file-alt fs-5"></i>
                                </div>
                                <div>
                                    <h6 class="fw-bold text-dark mb-0">Abstract Submission</h6>
                                    <span class="text-muted extra-small" style="font-size: 0.75rem;">Oral & Poster</span>
                                </div>
                            </div>
                            <p class="text-muted small mb-3" style="font-size: 0.82rem; line-height: 1.35;">Submit scientific abstract for Oral or Poster presentation.</p>
                        </div>
                        <a href="{{ route('abstract.create') }}" class="btn btn-warning text-white btn-sm fw-semibold w-100 py-1.5 shadow-xs" style="background: linear-gradient(135deg, #ff6b00, #e65100); border: none; border-radius: 8px;">
                            {{ $abstract ? 'View Abstract' : 'Submit Abstract' }} <i class="fas fa-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- 4. Profile & Account Card -->
            <div class="col-12 col-sm-6 col-lg-3">
                <div class="card h-100 border-0 shadow-sm dashboard-card position-relative overflow-hidden" style="border-radius: 14px; border-top: 4px solid #00897b !important;">
                    <div class="card-body p-3.5 d-flex flex-column justify-content-between">
                        <div>
                            <div class="d-flex align-items-center gap-2.5 mb-2.5">
                                <div class="rounded-3 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 40px; height: 40px; background: rgba(0, 137, 123, 0.12); color: #00897b;">
                                    <i class="fas fa-user-cog fs-5"></i>
                                </div>
                                <div>
                                    <h6 class="fw-bold text-dark mb-0">My Profile</h6>
                                    <span class="text-muted extra-small" style="font-size: 0.75rem;">Account Settings</span>
                                </div>
                            </div>
                            <p class="text-muted small mb-3" style="font-size: 0.82rem; line-height: 1.35;">Manage personal profile, email, and security settings.</p>
                        </div>
                        <a href="{{ route('profile.show') }}" class="btn btn-outline-secondary btn-sm fw-semibold w-100 py-1.5" style="border-radius: 8px;">
                            My Profile <i class="fas fa-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Detailed Submitted Abstract Card (If Abstract Exists) -->
        @if ($abstract)
            <div class="card border-0 shadow-sm mb-4 overflow-hidden" style="border-radius: 16px; border-left: 5px solid #ff6b00 !important;">
                <div class="card-header bg-white py-3 px-3 px-md-4 border-bottom d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <div class="d-flex align-items-center gap-2.5">
                        <div class="rounded-circle bg-warning bg-opacity-10 text-warning p-2 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; color: #ff6b00 !important;">
                            <i class="fas fa-file-invoice fs-5"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold text-dark mb-0"><i class="fas fa-clipboard-check text-primary me-1"></i>Submitted Abstract Details</h6>
                            <small class="text-muted" style="font-size: 0.78rem;">Acknowledgement ID: <strong class="text-primary font-monospace fs-6 ms-1">{{ $abstract->acknowledgement_id ?? 'Draft' }}</strong></small>
                        </div>
                    </div>
                    <div>
                        @if ($abstract->status === 'Submitted' || !empty($abstract->acknowledgement_id))
                            <span class="badge bg-success text-white px-3 py-1.5 rounded-pill fw-bold" style="font-size: 0.78rem;">
                                <i class="fas fa-check-circle me-1"></i>SUBMITTED
                            </span>
                        @else
                            <span class="badge bg-warning text-dark px-3 py-1.5 rounded-pill fw-bold" style="font-size: 0.78rem;">
                                <i class="fas fa-edit me-1"></i>DRAFT SAVED
                            </span>
                        @endif
                    </div>
                </div>

                <div class="card-body p-3.5 p-md-4">
                    <h5 class="fw-bold text-primary mb-3" style="letter-spacing: -0.3px;">
                        {{ $abstract->abstract_title ?: 'Untitled Abstract' }}
                    </h5>

                    <div class="row g-3 mb-3">
                        <div class="col-md-4">
                            <span class="text-muted extra-small d-block mb-1">Presentation Mode</span>
                            <span class="badge bg-light border text-dark fw-bold px-2.5 py-1.5 rounded-2">
                                <i class="fas fa-microphone-alt text-primary me-1"></i>{{ $abstract->presentation_mode ?? 'Not Specified' }}
                            </span>
                        </div>
                        <div class="col-md-4">
                            <span class="text-muted extra-small d-block mb-1">Presenter Category</span>
                            <span class="badge bg-light border text-dark fw-bold px-2.5 py-1.5 rounded-2">
                                <i class="fas fa-user-tag text-info me-1"></i>{{ $abstract->presenter_category ?? 'N/A' }}
                            </span>
                        </div>
                        <div class="col-md-4">
                            <span class="text-muted extra-small d-block mb-1">Presenting Author</span>
                            <span class="fw-semibold text-dark small">
                                <i class="fas fa-user text-secondary me-1"></i>{{ $abstract->presenting_author_name }}
                            </span>
                        </div>
                    </div>

                    @if(!empty($abstract->conference_theme))
                        <div class="mb-3 p-2.5 bg-light rounded-3 border">
                            <span class="text-muted extra-small d-block mb-0.5">Conference Theme:</span>
                            <span class="fw-semibold text-dark small"><i class="fas fa-tag text-warning me-1.5"></i>{{ $abstract->conference_theme }}</span>
                        </div>
                    @endif

                    @if(!empty($abstract->keywords))
                        <div class="mb-3">
                            <span class="text-muted extra-small d-block mb-1">Keywords:</span>
                            <div class="d-flex gap-1.5 flex-wrap">
                                @foreach(explode(',', $abstract->keywords) as $kw)
                                    @if(trim($kw))
                                        <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 px-2.5 py-1 rounded-2" style="font-size: 0.75rem;">
                                            #{{ trim($kw) }}
                                        </span>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <div class="d-flex justify-content-between align-items-center pt-3 border-top flex-wrap gap-2">
                        <span class="text-muted extra-small">
                            <i class="far fa-clock me-1"></i>Submission Date: {{ $abstract->updated_at ? $abstract->updated_at->format('d M Y, h:i A') : 'N/A' }}
                        </span>
                        <a href="{{ route('abstract.create') }}" class="btn btn-sm btn-primary fw-bold px-3 py-1.5 shadow-xs" style="background: linear-gradient(135deg, #013069, #0d47a1); border: none; border-radius: 8px;">
                            <i class="fas fa-external-link-alt me-1"></i>View Full Abstract Details
                        </a>
                    </div>
                </div>
            </div>
        @endif

        <!-- Info Notice Section -->
        <div class="p-3 rounded-3 bg-white shadow-sm border">
            <div class="row align-items-center">
                <div class="col-lg-8 mb-2 mb-lg-0">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle bg-primary bg-opacity-10 text-primary p-2 d-flex align-items-center justify-content-center me-3 flex-shrink-0" style="width: 42px; height: 42px;">
                            <i class="fas fa-calendar-alt fs-5 text-primary"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold mb-0 text-dark small">Important Conference Schedule</h6>
                            <p class="text-muted mb-0" style="font-size: 0.82rem;">IPHACON 2027 Main Conference: <strong>12th - 14th March 2027</strong> | Venue: <strong>RIMS, Ranchi</strong></p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 text-lg-end">
                    <span class="text-muted" style="font-size: 0.82rem;">
                        <i class="fas fa-headset me-1 text-primary"></i>Support: 
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
            box-shadow: 0 10px 24px rgba(1, 48, 105, 0.12) !important;
        }
    </style>
</x-delegate.layout>
