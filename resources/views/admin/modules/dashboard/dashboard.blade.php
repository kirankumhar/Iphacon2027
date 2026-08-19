@extends('admin.layouts.main')

@section('admin-content')
    <div class="container-xxl flex-grow-1 mt-3.5 mb-4">
        <!-- Welcome Hero Banner -->
        <div class="card mb-4 overflow-hidden border-0 shadow-sm position-relative hero-banner-card">
            <div class="card-body py-4 px-3 px-md-4 position-relative z-2">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                    <div>
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <span class="badge px-3 py-1.5 fw-bold shadow-xs border border-white border-opacity-25"
                                style="background-color: rgba(255, 255, 255, 0.15); color: #FFFFFF; border-radius: 20px; font-size: 0.73rem;">
                                <i class="bx bx-shield-check me-1 text-info"></i> IPHACON 2027 Executive Control Panel
                            </span>
                            <span class="badge px-2.5 py-1 fw-medium"
                                style="background-color: rgba(16, 185, 129, 0.2); color: #34D399; border-radius: 20px; font-size: 0.7rem;">
                                <i class="bx bx-radio-circle-marked me-0.5"></i> System Active
                            </span>
                        </div>
                        <h3 class="text-white fw-bold mt-1 mb-1 fs-3" style="letter-spacing: -0.4px;">
                            Welcome back, {{ auth('admin')->user()->full_name ?? auth('admin')->user()->username }} 👋
                        </h3>
                        <p class="text-white-50 mb-0 small" style="font-size: 0.88rem;">
                            Live monitoring & real-time registration metrics for the 71st Annual National Conference of
                            IPHA.
                        </p>
                    </div>
                    <div class="d-flex align-items-center gap-2 flex-wrap">
                        <a href="{{ route('indian-approved-delegates') }}"
                            class="btn btn-sm btn-light fw-bold shadow-xs px-3.5 py-2 btn-capsule d-flex align-items-center gap-1.5"
                            style="border-radius: 25px; font-size: 0.825rem; color: #0F172A !important;">
                            <i class="bx bx-list-check text-primary fs-5"></i> View Registrations
                        </a>
                        @if (Route::has('admin.abstracts.index'))
                            <a href="{{ route('admin.abstracts.index') }}"
                                class="btn btn-sm btn-primary fw-bold text-white shadow-xs px-3.5 py-2 btn-capsule d-flex align-items-center gap-1.5"
                                style="border-radius: 25px; font-size: 0.825rem; background: linear-gradient(135deg, #2563EB 0%, #1D4ED8 100%); border: none;">
                                <i class="bx bx-file-find fs-5"></i> Manage Abstracts
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Metric Stat Cards Grid (4 Columns) -->
        <div class="row g-3.5 mb-4">
            <!-- 1. Indian Approved Delegates Card -->
            <div class="col-sm-6 col-xl-3">
                <div class="card h-100 border-0 shadow-sm admin-stat-card overflow-hidden position-relative">
                    <div class="stat-top-bar" style="background: linear-gradient(90deg, #10B981 0%, #059669 100%);"></div>
                    <div class="card-body p-3.5 d-flex flex-column justify-content-between">
                        <div>
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <div class="rounded-3 d-flex align-items-center justify-content-center stat-icon-box"
                                    style="width: 48px; height: 48px; background-color: #DCFCE7; color: #059669;">
                                    <i class="bx bx-user-check fs-3"></i>
                                </div>
                                <span class="badge px-3 py-1.5 fs-6 fw-bold rounded-pill shadow-xs"
                                    style="background-color: #DCFCE7; color: #065F46; border: 1px solid #A7F3D0;">
                                    {{ number_format($IndApprovedCount) }}
                                </span>
                            </div>
                            <h6 class="text-muted fw-bold mb-0.5 text-uppercase extra-small"
                                style="letter-spacing: 0.6px; font-size: 0.72rem;">Indian Delegates</h6>
                            <h5 class="fw-bold text-dark mb-3" style="font-size: 1.05rem;">Indian Approved</h5>
                        </div>
                        <a href="{{ route('indian-approved-delegates') }}"
                            class="btn btn-sm w-100 fw-bold d-flex align-items-center justify-content-center gap-1.5 btn-capsule py-2"
                            style="background-color: #F0FDF4; color: #059669; border: 1px solid #BBF7D0; font-size: 0.8rem;">
                            <span>View Indian List</span>
                            <i class="bx bx-right-arrow-alt fs-5"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- 2. Foreign Payment Submitted Card -->
            <div class="col-sm-6 col-xl-3">
                <div class="card h-100 border-0 shadow-sm admin-stat-card overflow-hidden position-relative">
                    <div class="stat-top-bar" style="background: linear-gradient(90deg, #0288D1 0%, #00897B 100%);"></div>
                    <div class="card-body p-3.5 d-flex flex-column justify-content-between">
                        <div>
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <div class="rounded-3 d-flex align-items-center justify-content-center stat-icon-box"
                                    style="width: 48px; height: 48px; background-color: #E0F2FE; color: #0288D1;">
                                    <i class="bx bx-credit-card-front fs-3"></i>
                                </div>
                                <span class="badge px-3 py-1.5 fs-6 fw-bold rounded-pill shadow-xs"
                                    style="background-color: #E0F2FE; color: #0369A1; border: 1px solid #BAE6FD;">
                                    {{ number_format($appliedCount) }}
                                </span>
                            </div>
                            <h6 class="text-muted fw-bold mb-0.5 text-uppercase extra-small"
                                style="letter-spacing: 0.6px; font-size: 0.72rem;">Delegates</h6>
                            <h5 class="fw-bold text-dark mb-3" style="font-size: 1.05rem;">Payment Submitted</h5>
                        </div>
                        <a href="{{ route('international-payment-submitted-delegates') }}"
                            class="btn btn-sm w-100 fw-bold d-flex align-items-center justify-content-center gap-1.5 btn-capsule py-2"
                            style="background-color: #F0F9FF; color: #0288D1; border: 1px solid #BAE6FD; font-size: 0.8rem;">
                            <span>Review Submissions</span>
                            <i class="bx bx-right-arrow-alt fs-5"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- 3. Approved Foreign Delegate Card -->
            <div class="col-sm-6 col-xl-3">
                <div class="card h-100 border-0 shadow-sm admin-stat-card overflow-hidden position-relative">
                    <div class="stat-top-bar" style="background: linear-gradient(90deg, #2563EB 0%, #1D4ED8 100%);"></div>
                    <div class="card-body p-3.5 d-flex flex-column justify-content-between">
                        <div>
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <div class="rounded-3 d-flex align-items-center justify-content-center stat-icon-box"
                                    style="width: 48px; height: 48px; background-color: #DBEAFE; color: #1D4ED8;">
                                    <i class="bx bx-globe fs-3"></i>
                                </div>
                                <span class="badge px-3 py-1.5 fs-6 fw-bold rounded-pill shadow-xs"
                                    style="background-color: #2563EB; color: #FFFFFF;">
                                    {{ number_format($IntApprovedCount) }}
                                </span>
                            </div>
                            <h6 class="text-muted fw-bold mb-0.5 text-uppercase extra-small"
                                style="letter-spacing: 0.6px; font-size: 0.72rem;">Foreign Delegates</h6>
                            <h5 class="fw-bold text-dark mb-3" style="font-size: 1.05rem;">Approved Foreign</h5>
                        </div>
                        <a href="{{ route('international-approved-delegates') }}"
                            class="btn btn-sm w-100 fw-bold d-flex align-items-center justify-content-center gap-1.5 btn-capsule py-2 text-white"
                            style="background: linear-gradient(135deg, #2563EB 0%, #1D4ED8 100%); border: none; font-size: 0.8rem;">
                            <span>View Foreign List</span>
                            <i class="bx bx-right-arrow-alt fs-5"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- 4. Abstract Submissions Card -->
            <div class="col-sm-6 col-xl-3">
                <div class="card h-100 border-0 shadow-sm admin-stat-card overflow-hidden position-relative">
                    <div class="stat-top-bar" style="background: linear-gradient(90deg, #F59E0B 0%, #D97706 100%);"></div>
                    <div class="card-body p-3.5 d-flex flex-column justify-content-between">
                        <div>
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <div class="rounded-3 d-flex align-items-center justify-content-center stat-icon-box"
                                    style="width: 48px; height: 48px; background-color: #FEF3C7; color: #D97706;">
                                    <i class="bx bx-file-find fs-3"></i>
                                </div>
                                <span class="badge px-3 py-1.5 fs-6 fw-bold rounded-pill shadow-xs"
                                    style="background-color: #FEF3C7; color: #B45309; border: 1px solid #FDE68A;">
                                    {{ number_format($abstractCount ?? 0) }}
                                </span>
                            </div>
                            <h6 class="text-muted fw-bold mb-0.5 text-uppercase extra-small"
                                style="letter-spacing: 0.6px; font-size: 0.72rem;">Scientific Committee</h6>
                            <h5 class="fw-bold text-dark mb-3" style="font-size: 1.05rem;">Abstract Submissions</h5>
                        </div>
                        @if (Route::has('admin.abstracts.index'))
                            <a href="{{ route('admin.abstracts.index') }}"
                                class="btn btn-sm w-100 fw-bold d-flex align-items-center justify-content-center gap-1.5 btn-capsule py-2 text-white"
                                style="background: linear-gradient(135deg, #F59E0B 0%, #D97706 100%); border: none; font-size: 0.8rem;">
                                <span>Manage Abstracts</span>
                                <i class="bx bx-right-arrow-alt fs-5"></i>
                            </a>
                        @else
                            <span
                                class="btn btn-sm w-100 fw-bold d-flex align-items-center justify-content-center gap-1.5 btn-capsule py-2 disabled"
                                style="background-color: #FEF3C7; color: #D97706; border: none; font-size: 0.8rem;">
                                <span>Abstract Portal</span>
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions & System Modules Panel -->
        <div class="row g-3.5 mb-4">
            <!-- Left Side: Quick Navigation Shortcuts -->
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm rounded-3 overflow-hidden h-100">
                    <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between">
                        <h6 class="mb-0 fw-bold text-dark d-flex align-items-center gap-2">
                            <i class="bx bx-grid-alt text-primary fs-5"></i>Quick Navigation &amp; Management Shortcuts
                        </h6>
                    </div>
                    <div class="card-body p-3.5">
                        <div class="row g-3">
                            <div class="col-sm-6 col-md-4">
                                <a href="{{ route('submitted-delegates') }}"
                                    class="shortcut-card p-3 rounded-3 d-flex align-items-center gap-3 text-decoration-none border transition-all h-100">
                                    <div class="shortcut-icon rounded-circle d-flex align-items-center justify-content-center"
                                        style="width: 42px; height: 42px; background-color: #EFF6FF; color: #2563EB;">
                                        <i class="bx bx-paper-plane fs-4"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-0 fw-bold text-dark small">Submitted Delegates</h6>
                                        <span class="extra-small text-muted" style="font-size: 0.74rem;">Pending
                                            verification</span>
                                    </div>
                                </a>
                            </div>

                            <div class="col-sm-6 col-md-4">
                                <a href="{{ route('admin.cme-delegates') }}"
                                    class="shortcut-card p-3 rounded-3 d-flex align-items-center gap-3 text-decoration-none border transition-all h-100">
                                    <div class="shortcut-icon rounded-circle d-flex align-items-center justify-content-center"
                                        style="width: 42px; height: 42px; background-color: #ECFDF5; color: #059669;">
                                        <i class="bx bx-book-reader fs-4"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-0 fw-bold text-dark small">Pre-Conf. Workshop</h6>
                                        <span class="extra-small text-muted" style="font-size: 0.74rem;">Workshop
                                            delegates</span>
                                    </div>
                                </a>
                            </div>

                            <div class="col-sm-6 col-md-4">
                                <a href="{{ route('indian-incomplete-delegates') }}"
                                    class="shortcut-card p-3 rounded-3 d-flex align-items-center gap-3 text-decoration-none border transition-all h-100">
                                    <div class="shortcut-icon rounded-circle d-flex align-items-center justify-content-center"
                                        style="width: 42px; height: 42px; background-color: #FEF3C7; color: #D97706;">
                                        <i class="bx bx-time-five fs-4"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-0 fw-bold text-dark small">Incomplete Reg.</h6>
                                        <span class="extra-small text-muted" style="font-size: 0.74rem;">Draft
                                            applications</span>
                                    </div>
                                </a>
                            </div>

                            <div class="col-sm-6 col-md-4">
                                <a href="{{ route('pending-payments') }}"
                                    class="shortcut-card p-3 rounded-3 d-flex align-items-center gap-3 text-decoration-none border transition-all h-100">
                                    <div class="shortcut-icon rounded-circle d-flex align-items-center justify-content-center"
                                        style="width: 42px; height: 42px; background-color: #FFF7ED; color: #EA580C;">
                                        <i class="bx bx-hourglass fs-4"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-0 fw-bold text-dark small">Pending Payments</h6>
                                        <span class="extra-small text-muted" style="font-size: 0.74rem;">Awaiting
                                            verification</span>
                                    </div>
                                </a>
                            </div>

                            <div class="col-sm-6 col-md-4">
                                <a href="{{ route('paid-payments') }}"
                                    class="shortcut-card p-3 rounded-3 d-flex align-items-center gap-3 text-decoration-none border transition-all h-100">
                                    <div class="shortcut-icon rounded-circle d-flex align-items-center justify-content-center"
                                        style="width: 42px; height: 42px; background-color: #F0FDF4; color: #16A34A;">
                                        <i class="bx bx-check-shield fs-4"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-0 fw-bold text-dark small">Successful Payments</h6>
                                        <span class="extra-small text-muted" style="font-size: 0.74rem;">Verified
                                            transactions</span>
                                    </div>
                                </a>
                            </div>

                            <div class="col-sm-6 col-md-4">
                                <a href="{{ route('international-rejected-delegates') }}"
                                    class="shortcut-card p-3 rounded-3 d-flex align-items-center gap-3 text-decoration-none border transition-all h-100">
                                    <div class="shortcut-icon rounded-circle d-flex align-items-center justify-content-center"
                                        style="width: 42px; height: 42px; background-color: #FEF2F2; color: #DC2626;">
                                        <i class="bx bx-x-circle fs-4"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-0 fw-bold text-dark small">Rejected List</h6>
                                        <span class="extra-small text-muted" style="font-size: 0.74rem;">Declined
                                            delegates</span>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Side: Conference Overview Card -->
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm rounded-3 overflow-hidden h-100 bg-white">
                    <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between">
                        <h6 class="mb-0 fw-bold text-dark d-flex align-items-center gap-2">
                            <i class="bx bx-calendar-event text-primary fs-5"></i>Conference Overview
                        </h6>
                        <span class="badge bg-light text-dark border px-2.5 py-1 extra-small fw-medium">
                            IPHACON 2027
                        </span>
                    </div>
                    <div class="card-body p-3.5 d-flex flex-column justify-content-between">
                        <div>
                            <!-- Event Title Header -->
                            <div class="p-3 mb-3 rounded-3 border"
                                style="background-color: #F8FAFC; border-color: #E2E8F0 !important;">
                                <div class="d-flex align-items-center gap-2 mb-1">
                                    <span class="badge bg-primary text-white extra-small px-2 py-0.5 fw-semibold">71st
                                        Annual</span>
                                    <span class="text-muted extra-small">National Conference</span>
                                </div>
                                <h6 class="mb-1 fw-bold text-dark" style="font-size: 0.92rem; line-height: 1.35;">
                                    Indian Public Health Association
                                </h6>
                                <p class="extra-small text-muted mb-0" style="font-size: 0.76rem;">
                                    <i class="bx bx-map-pin me-1 text-danger"></i>IPHACON Official Administration Portal
                                </p>
                            </div>

                            <!-- User & System Info -->
                            <div class="d-flex flex-column gap-2">
                                <div
                                    class="d-flex align-items-center justify-content-between p-2.5 px-3 rounded-2 bg-light border">
                                    <span class="text-muted extra-small fw-medium">Logged-in Administrator</span>
                                    <span
                                        class="fw-bold text-dark extra-small">{{ auth('admin')->user()->full_name ?? auth('admin')->user()->username }}</span>
                                </div>

                                <div
                                    class="d-flex align-items-center justify-content-between p-2.5 px-3 rounded-2 bg-light border">
                                    <span class="text-muted extra-small fw-medium">Access Role</span>
                                    <span
                                        class="badge bg-primary text-white extra-small fw-semibold px-2.5 py-1 rounded-2">{{ strtoupper(auth('admin')->user()->role ?? 'SUPERADMIN') }}</span>
                                </div>

                                <div
                                    class="d-flex align-items-center justify-content-between p-2.5 px-3 rounded-2 bg-light border">
                                    <span class="text-muted extra-small fw-medium">Portal Security &amp; Database</span>
                                    <span
                                        class="badge bg-success text-white extra-small fw-semibold px-2.5 py-1 rounded-2"><i
                                            class="bx bx-check me-0.5"></i>Active</span>
                                </div>
                            </div>
                        </div>

                        <div class="pt-3 mt-2 border-top">
                            <div class="d-flex align-items-center justify-content-between text-muted extra-small">
                                <span><i class="bx bx-time-five me-1 text-primary"></i>Server Time:</span>
                                <span class="fw-semibold font-monospace text-dark">{{ date('d M Y, h:i A') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .hero-banner-card {
            background: linear-gradient(135deg, #0F172A 0%, #1E293B 50%, #2563EB 100%) !important;
            color: #FFFFFF;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(15, 23, 42, 0.25) !important;
        }

        .admin-stat-card {
            background: #ffffff;
            border-radius: 16px !important;
            transition: all 0.3s cubic-bezier(0.165, 0.84, 0.44, 1) !important;
            border: 1px solid rgba(226, 232, 240, 0.9) !important;
        }

        .stat-top-bar {
            height: 4px;
        }

        .admin-stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 16px 32px rgba(15, 23, 42, 0.1) !important;
            border-color: rgba(37, 99, 235, 0.3) !important;
        }

        .admin-stat-card .stat-icon-box {
            transition: transform 0.3s ease;
        }

        .admin-stat-card:hover .stat-icon-box {
            transform: scale(1.1) rotate(-4deg);
        }

        .shortcut-card {
            background: #FFFFFF;
            border-color: #E2E8F0 !important;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .shortcut-card:hover {
            background: #F8FAFC !important;
            border-color: #2563EB !important;
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(37, 99, 235, 0.08) !important;
        }

        .btn-capsule {
            border-radius: 25px !important;
            transition: all 0.25s ease !important;
        }

        .btn-capsule:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.15) !important;
        }

        .pulse-dot {
            width: 7px;
            height: 7px;
            background-color: #10B981;
            border-radius: 50%;
            display: inline-block;
            box-shadow: 0 0 0 rgba(16, 185, 129, 0.4);
            animation: pulse-animation 2s infinite;
        }

        @keyframes pulse-animation {
            0% {
                box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7);
            }

            70% {
                box-shadow: 0 0 0 6px rgba(16, 185, 129, 0);
            }

            100% {
                box-shadow: 0 0 0 0 rgba(16, 185, 129, 0);
            }
        }
    </style>
@endsection

@push('scripts')
    <script>
        window.history.pushState(null, '', window.location.href);
        window.onpopstate = function() {
            window.history.go(1);
        };
    </script>
@endpush
