<x-delegate.layout>
    <x-slot:title>Delegate Dashboard | IPHACON 2027</x-slot:title>

    <div class="container py-3">
        <!-- Welcome Hero Banner -->
        <div class="card border-0 shadow-sm overflow-hidden mb-4" style="border-radius: 16px; background: linear-gradient(135deg, #013069 0%, #0d47a1 50%, #0288D1 100%); margin-bottom: 2rem !important; box-shadow: 0 10px 28px rgba(1, 48, 105, 0.18) !important;">
            <div class="card-body py-3.5 px-3 px-md-4 text-white position-relative">
                <div class="row align-items-center">
                    <div class="col-lg-8">
                        <div class="d-flex align-items-center gap-2 mb-1.5">
                            <span class="badge bg-white text-primary px-3 py-1 rounded-pill fw-bold shadow-xs" style="font-size: 0.73rem;">
                                <i class="fas fa-id-badge me-1"></i>{{ Auth::user()->delegate_type ?? 'Delegate' }}
                            </span>
                            <span class="badge bg-success bg-opacity-90 text-white px-3 py-1 rounded-pill fw-semibold shadow-xs" style="font-size: 0.73rem;">
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
                    <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                        @if(!$registration)
                            <a href="{{ route('registration.create') }}" class="btn btn-warning btn-sm fw-bold px-3.5 py-2 shadow-sm btn-capsule" style="background: #FF6B00; border: none; color: #ffffff; font-size: 0.85rem;">
                                <i class="fas fa-paper-plane me-1.5"></i>Register Now
                            </a>
                        @else
                            <a href="{{ route('abstract.create') }}" class="btn btn-light btn-sm fw-bold text-primary px-3.5 py-2 shadow-sm btn-capsule" style="font-size: 0.85rem; color: #0d47a1 !important;">
                                <i class="fas fa-file-alt me-1.5"></i>Submit / View Abstract
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Session & Alert Messages -->
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show py-2.5 px-3 mb-4 small fw-semibold shadow-xs" role="alert" style="border-radius: 12px; background-color: #DCFFF0; border-color: #6EE7B7; color: #065F46;">
                <i class="fas fa-check-circle me-2 fs-6"></i>{{ session('success') }}
                <button type="button" class="btn-close btn-close-sm" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show py-2.5 px-3 mb-4 small fw-semibold shadow-xs" role="alert" style="border-radius: 12px; background-color: #FEE2E2; border-color: #FCA5A5; color: #991B1B;">
                <i class="fas fa-exclamation-circle me-2 fs-6"></i>{{ session('error') }}
                <button type="button" class="btn-close btn-close-sm" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show py-2.5 px-3 mb-4 small shadow-xs" role="alert" style="border-radius: 12px;">
                <i class="fas fa-exclamation-triangle me-2"></i><strong>Attention Required:</strong>
                <ul class="mb-0 mt-1 ps-3">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close btn-close-sm" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Quick Action Cards Grid (4 Columns with Premium Styling) -->
        <div class="row g-3.5 mb-4.5 pt-1">
            <!-- 1. Conference Registration Card -->
            <div class="col-12 col-sm-6 col-lg-3">
                <div class="card h-100 dashboard-card position-relative overflow-hidden">
                    <div style="height: 4px; background: linear-gradient(90deg, #013069 0%, #0d47a1 100%);"></div>
                    <div class="card-body p-3.5 d-flex flex-column justify-content-between">
                        <div>
                            <div class="d-flex align-items-center gap-3 mb-3">
                                <div class="rounded-3 d-flex align-items-center justify-content-center flex-shrink-0 card-icon-box" style="width: 44px; height: 44px; background: #E8F2FF; color: #0d47a1;">
                                    <i class="fas fa-edit fs-5"></i>
                                </div>
                                <div>
                                    <h6 class="fw-bold text-dark mb-0.5" style="letter-spacing: -0.2px;">Registration</h6>
                                    <span class="badge bg-light text-primary border border-primary border-opacity-25 extra-small px-2 py-0.5 rounded-pill" style="font-size: 0.7rem;">Conference Portal</span>
                                </div>
                            </div>
                            <p class="text-secondary small mb-3" style="font-size: 0.82rem; line-height: 1.4;">Register for sessions, workshops & accompanying details.</p>
                        </div>
                        <a href="{{ route('registration.create') }}" class="btn btn-primary btn-sm btn-capsule w-100 py-2 shadow-xs" style="background: linear-gradient(135deg, #013069, #0d47a1); border: none;">
                            {{ $registration ? 'Update Details' : 'Register Now' }} <i class="fas fa-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- 2. Registration Status & Receipt Card -->
            <div class="col-12 col-sm-6 col-lg-3">
                <div class="card h-100 dashboard-card position-relative overflow-hidden">
                    <div style="height: 4px; background: linear-gradient(90deg, #0288D1 0%, #00897B 100%);"></div>
                    <div class="card-body p-3.5 d-flex flex-column justify-content-between">
                        <div>
                            <div class="d-flex align-items-center gap-3 mb-3">
                                <div class="rounded-3 d-flex align-items-center justify-content-center flex-shrink-0 card-icon-box" style="width: 44px; height: 44px; background: #E0F2FE; color: #0288D1;">
                                    <i class="fas fa-receipt fs-5"></i>
                                </div>
                                <div>
                                    <h6 class="fw-bold text-dark mb-0.5" style="letter-spacing: -0.2px;">My Registrations</h6>
                                    <span class="badge bg-light text-info border border-info border-opacity-25 extra-small px-2 py-0.5 rounded-pill" style="font-size: 0.7rem; color: #0288D1 !important;">Status & Receipt PDF</span>
                                </div>
                            </div>
                            <p class="text-secondary small mb-3" style="font-size: 0.82rem; line-height: 1.4;">View registration status, payment & download PDF receipt.</p>
                        </div>
                        <a href="{{ route('registration.index') }}" class="btn text-white btn-sm btn-capsule w-100 py-2 shadow-xs" style="background: linear-gradient(135deg, #0288D1, #00897B); border: none;">
                            View Details <i class="fas fa-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- 3. Abstract Submission Card -->
            <div class="col-12 col-sm-6 col-lg-3">
                <div class="card h-100 dashboard-card position-relative overflow-hidden">
                    <div style="height: 4px; background: linear-gradient(90deg, #FF6B00 0%, #E65100 100%);"></div>
                    <div class="card-body p-3.5 d-flex flex-column justify-content-between">
                        <div>
                            <div class="d-flex align-items-center gap-3 mb-3">
                                <div class="rounded-3 d-flex align-items-center justify-content-center flex-shrink-0 card-icon-box" style="width: 44px; height: 44px; background: #FFF3E0; color: #FF6B00;">
                                    <i class="fas fa-file-alt fs-5"></i>
                                </div>
                                <div>
                                    <h6 class="fw-bold text-dark mb-0.5" style="letter-spacing: -0.2px;">Abstract Submission</h6>
                                    <span class="badge bg-light text-warning border border-warning border-opacity-25 extra-small px-2 py-0.5 rounded-pill" style="font-size: 0.7rem; color: #E65100 !important;">Oral & Poster</span>
                                </div>
                            </div>
                            <p class="text-secondary small mb-3" style="font-size: 0.82rem; line-height: 1.4;">Submit scientific abstract for Oral or Poster presentation.</p>
                        </div>
                        <a href="{{ route('abstract.create') }}" class="btn text-white btn-sm btn-capsule w-100 py-2 shadow-xs" style="background: linear-gradient(135deg, #FF6B00, #E65100); border: none;">
                            {{ $abstract ? 'View Abstract' : 'Submit Abstract' }} <i class="fas fa-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- 4. Profile & Account Card -->
            <div class="col-12 col-sm-6 col-lg-3">
                <div class="card h-100 dashboard-card position-relative overflow-hidden">
                    <div style="height: 4px; background: linear-gradient(90deg, #2E7D32 0%, #00897B 100%);"></div>
                    <div class="card-body p-3.5 d-flex flex-column justify-content-between">
                        <div>
                            <div class="d-flex align-items-center gap-3 mb-3">
                                <div class="rounded-3 d-flex align-items-center justify-content-center flex-shrink-0 card-icon-box" style="width: 44px; height: 44px; background: #E8F5E9; color: #2E7D32;">
                                    <i class="fas fa-user-cog fs-5"></i>
                                </div>
                                <div>
                                    <h6 class="fw-bold text-dark mb-0.5" style="letter-spacing: -0.2px;">My Profile</h6>
                                    <span class="badge bg-light text-success border border-success border-opacity-25 extra-small px-2 py-0.5 rounded-pill" style="font-size: 0.7rem;">Account Settings</span>
                                </div>
                            </div>
                            <p class="text-secondary small mb-3" style="font-size: 0.82rem; line-height: 1.4;">Manage personal profile, email, and security settings.</p>
                        </div>
                        <a href="{{ route('profile.show') }}" class="btn text-white btn-sm btn-capsule w-100 py-2 shadow-xs" style="background: linear-gradient(135deg, #2E7D32, #00897B); border: none;">
                            My Profile <i class="fas fa-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Detailed Submitted Abstract Card (If Abstract Exists) -->
        @if ($abstract)
            <div class="card border-0 shadow-sm mb-4 overflow-hidden" style="border-radius: 14px; border-left: 4px solid #FF6B00 !important; box-shadow: 0 4px 16px rgba(255, 107, 0, 0.06) !important;">
                <div class="card-header bg-white py-2.5 px-3 border-bottom d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <div class="d-flex align-items-center gap-2">
                        <div class="rounded-circle text-warning p-1.5 d-flex align-items-center justify-content-center" style="width: 34px; height: 34px; color: #FF6B00 !important; background: #FFF3E0;">
                            <i class="fas fa-file-invoice fs-6"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold text-dark mb-0" style="font-size: 0.95rem;"><i class="fas fa-clipboard-check text-primary me-1"></i>Submitted Abstract</h6>
                            <small class="text-muted extra-small" style="font-size: 0.75rem;">ID: <strong class="text-primary font-monospace ms-0.5">{{ $abstract->acknowledgement_id ?? 'Draft' }}</strong></small>
                        </div>
                    </div>
                    <div>
                        @if ($abstract->status === 'Submitted' || !empty($abstract->acknowledgement_id))
                            <span class="badge bg-success text-white px-2.5 py-1 rounded-pill fw-bold" style="font-size: 0.72rem; background-color: #10B981 !important;">
                                <i class="fas fa-check-circle me-1"></i>SUBMITTED
                            </span>
                        @else
                            <span class="badge bg-warning text-dark px-2.5 py-1 rounded-pill fw-bold" style="font-size: 0.72rem;">
                                <i class="fas fa-edit me-1"></i>DRAFT
                            </span>
                        @endif
                    </div>
                </div>

                <div class="card-body p-3">
                    <h6 class="fw-bold text-primary mb-2" style="letter-spacing: -0.2px; font-size: 1.05rem;">
                        {{ $abstract->abstract_title ?: 'Untitled Abstract' }}
                    </h6>

                    <div class="row g-2 mb-2.5">
                        <div class="col-md-4">
                            <span class="text-muted extra-small d-block mb-0.5" style="font-size: 0.72rem;">Mode</span>
                            <span class="badge bg-light border text-dark fw-bold px-2.5 py-1 rounded-2" style="font-size: 0.78rem;">
                                <i class="fas fa-microphone-alt text-primary me-1"></i>{{ $abstract->presentation_mode ?? 'Not Specified' }}
                            </span>
                        </div>
                        <div class="col-md-4">
                            <span class="text-muted extra-small d-block mb-0.5" style="font-size: 0.72rem;">Category</span>
                            <span class="badge bg-light border text-dark fw-bold px-2.5 py-1 rounded-2" style="font-size: 0.78rem;">
                                <i class="fas fa-user-tag text-info me-1"></i>{{ $abstract->presenter_category ?? 'N/A' }}
                            </span>
                        </div>
                        <div class="col-md-4">
                            <span class="text-muted extra-small d-block mb-0.5" style="font-size: 0.72rem;">Author</span>
                            <span class="fw-bold text-dark extra-small" style="font-size: 0.8rem;">
                                <i class="fas fa-user text-secondary me-1"></i>{{ $abstract->presenting_author_name }}
                            </span>
                        </div>
                    </div>

                    @if(!empty($abstract->conference_theme))
                        <div class="mb-2.5 p-2 bg-light rounded-2 border">
                            <span class="text-muted extra-small d-block" style="font-size: 0.7rem;">Theme:</span>
                            <span class="fw-bold text-dark extra-small" style="font-size: 0.78rem;"><i class="fas fa-tag text-warning me-1"></i>{{ $abstract->conference_theme }}</span>
                        </div>
                    @endif

                    @if(!empty($abstract->keywords))
                        <div class="mb-2.5">
                            <div class="d-flex gap-1 flex-wrap align-items-center">
                                <span class="text-muted extra-small me-1" style="font-size: 0.72rem;">Keywords:</span>
                                @foreach(explode(',', $abstract->keywords) as $kw)
                                    @if(trim($kw))
                                        <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 px-2 py-0.5 rounded-2" style="font-size: 0.72rem;">
                                            #{{ trim($kw) }}
                                        </span>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <div class="d-flex justify-content-between align-items-center pt-2.5 border-top flex-wrap gap-2">
                        <span class="text-muted extra-small" style="font-size: 0.75rem;">
                            <i class="far fa-clock me-1"></i>Date: {{ $abstract->updated_at ? $abstract->updated_at->format('d M Y, h:i A') : 'N/A' }}
                        </span>
                        <a href="{{ route('abstract.create') }}" class="btn btn-sm btn-primary btn-capsule px-3 py-1 shadow-xs" style="background: linear-gradient(135deg, #013069, #0d47a1); border: none; font-size: 0.8rem;">
                            <i class="fas fa-external-link-alt me-1"></i>View Abstract Details
                        </a>
                    </div>
                </div>
            </div>
        @endif

        <!-- Info Notice Section -->
        <div class="p-3.5 rounded-3 bg-white shadow-sm border" style="border-radius: 16px !important;">
            <div class="row align-items-center">
                <div class="col-lg-8 mb-2 mb-lg-0">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle bg-primary bg-opacity-10 text-primary p-2 d-flex align-items-center justify-content-center me-3 flex-shrink-0" style="width: 44px; height: 44px;">
                            <i class="fas fa-calendar-alt fs-5 text-primary"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold mb-0.5 text-dark small">Important Conference Schedule</h6>
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
            border-radius: 16px !important;
            transition: all 0.3s cubic-bezier(0.165, 0.84, 0.44, 1) !important;
            border: 1px solid rgba(226, 232, 240, 0.9) !important;
        }
        .dashboard-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 16px 32px rgba(2, 136, 209, 0.12) !important;
            border-color: rgba(2, 136, 209, 0.3) !important;
        }
        .dashboard-card .card-icon-box {
            transition: transform 0.3s ease;
        }
        .dashboard-card:hover .card-icon-box {
            transform: scale(1.1) rotate(-4deg);
        }
        .btn-capsule {
            border-radius: 25px !important;
            font-weight: 700 !important;
            letter-spacing: 0.3px;
            transition: all 0.25s ease !important;
        }
        .btn-capsule:hover {
            transform: translateY(-1.5px);
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.18) !important;
        }
    </style>
</x-delegate.layout>
