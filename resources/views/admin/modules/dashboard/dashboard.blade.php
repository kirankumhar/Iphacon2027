@extends('admin.layouts.main')

@section('admin-content')
    <div class="container-xxl flex-grow-1 mt-3.5 mb-4">
        <!-- Welcome Hero Banner -->
        <div class="card mb-4 overflow-hidden border-0 shadow-sm position-relative" style="background: linear-gradient(135deg, #013069 0%, #0d47a1 50%, #0288D1 100%); color: #FFFFFF; border-radius: 16px; box-shadow: 0 10px 28px rgba(1, 48, 105, 0.18) !important;">
            <div class="card-body py-3.5 px-3 px-md-4 position-relative z-2">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                    <div>
                        <div class="d-flex align-items-center gap-2 mb-1.5">
                            <span class="badge px-3 py-1 fw-bold shadow-xs" style="background-color: #DCFFF0; color: #065F46; border-radius: 20px; font-size: 0.73rem;">
                                <i class="bx bx-shield-check me-1"></i> IPHACON 2027 Admin Portal
                            </span>
                        </div>
                        <h4 class="text-white fw-bold mt-1 fs-4" style="letter-spacing: -0.3px;">
                            Welcome back, {{ auth('admin')->user()->full_name ?? auth('admin')->user()->username }}! 👋
                        </h4>
                        <p class="text-white-50 mb-0 small" style="font-size: 0.85rem;">
                            Live monitoring and registration activity dashboard for 71st Annual National Conference of IPHA.
                        </p>
                    </div>
                    <div class="d-flex gap-2">
                        <a href="{{ route('indian-approved-delegates') }}" class="btn btn-sm btn-light fw-bold text-primary shadow-xs px-3.5 py-2 btn-capsule" style="border-radius: 25px; font-size: 0.825rem; color: #0d47a1 !important;">
                            <i class="bx bx-list-check me-1"></i> View Registrations
                        </a>
                        @if(Route::has('admin.abstracts.index'))
                        <a href="{{ route('admin.abstracts.index') }}" class="btn btn-sm btn-warning fw-bold text-white shadow-xs px-3.5 py-2 btn-capsule" style="border-radius: 25px; font-size: 0.825rem; background: #FF6B00; border: none;">
                            <i class="bx bx-file-find me-1"></i> View Abstracts
                        </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Stat Cards Grid (4 Columns) -->
        <div class="row g-3.5 mb-4">
            <!-- 1. Indian Approved Delegates Card -->
            <div class="col-sm-6 col-xl-3">
                <div class="card h-100 border-0 shadow-sm admin-stat-card overflow-hidden">
                    <div style="height: 4px; background: linear-gradient(90deg, #10B981 0%, #059669 100%);"></div>
                    <div class="card-body p-3.5 d-flex flex-column justify-content-between">
                        <div>
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <div class="rounded-3 d-flex align-items-center justify-content-center stat-icon-box" style="width: 46px; height: 46px; background-color: #DCFFF0; color: #059669;">
                                    <i class="bx bx-user-check fs-3"></i>
                                </div>
                                <span class="badge px-3 py-1.5 fs-6 fw-bold rounded-pill shadow-xs" style="background-color: #DCFFF0; color: #059669;">
                                    {{ number_format($IndApprovedCount) }}
                                </span>
                            </div>
                            <h6 class="text-muted fw-bold mb-0.5 text-uppercase extra-small" style="letter-spacing: 0.5px; font-size: 0.72rem;">Indian Delegates</h6>
                            <h5 class="fw-bold text-dark mb-3" style="font-size: 1.05rem;">Indian Approved</h5>
                        </div>
                        <a href="{{ route('indian-approved-delegates') }}" class="btn btn-sm w-100 fw-bold d-flex align-items-center justify-content-center gap-1 btn-capsule py-1.5" style="background-color: #DCFFF0; color: #059669; border: 1px solid #A7F3D0; font-size: 0.8rem;">
                            <span>View Delegates</span>
                            <i class="bx bx-right-arrow-alt fs-5"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- 2. International Payment Submitted Card -->
            <div class="col-sm-6 col-xl-3">
                <div class="card h-100 border-0 shadow-sm admin-stat-card overflow-hidden">
                    <div style="height: 4px; background: linear-gradient(90deg, #0288D1 0%, #00897B 100%);"></div>
                    <div class="card-body p-3.5 d-flex flex-column justify-content-between">
                        <div>
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <div class="rounded-3 d-flex align-items-center justify-content-center stat-icon-box" style="width: 46px; height: 46px; background-color: #E0F2FE; color: #0288D1;">
                                    <i class="bx bx-credit-card-front fs-3"></i>
                                </div>
                                <span class="badge px-3 py-1.5 fs-6 fw-bold rounded-pill shadow-xs" style="background-color: #E0F2FE; color: #0288D1;">
                                    {{ number_format($appliedCount) }}
                                </span>
                            </div>
                            <h6 class="text-muted fw-bold mb-0.5 text-uppercase extra-small" style="letter-spacing: 0.5px; font-size: 0.72rem;">Foreign Delegates</h6>
                            <h5 class="fw-bold text-dark mb-3" style="font-size: 1.05rem;">Payment Submitted</h5>
                        </div>
                        <a href="{{ route('international-payment-submitted-delegates') }}" class="btn btn-sm w-100 fw-bold d-flex align-items-center justify-content-center gap-1 btn-capsule py-1.5" style="background-color: #E0F2FE; color: #0288D1; border: 1px solid #BAE6FD; font-size: 0.8rem;">
                            <span>Review Submissions</span>
                            <i class="bx bx-right-arrow-alt fs-5"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- 3. Approved International Delegate Card -->
            <div class="col-sm-6 col-xl-3">
                <div class="card h-100 border-0 shadow-sm admin-stat-card overflow-hidden">
                    <div style="height: 4px; background: linear-gradient(90deg, #0D47A1 0%, #1565C0 100%);"></div>
                    <div class="card-body p-3.5 d-flex flex-column justify-content-between">
                        <div>
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <div class="rounded-3 d-flex align-items-center justify-content-center stat-icon-box" style="width: 46px; height: 46px; background-color: #E8F2FF; color: #0D47A1;">
                                    <i class="bx bx-globe fs-3"></i>
                                </div>
                                <span class="badge px-3 py-1.5 fs-6 fw-bold rounded-pill shadow-xs" style="background-color: #0D47A1; color: #FFFFFF;">
                                    {{ number_format($IntApprovedCount) }}
                                </span>
                            </div>
                            <h6 class="text-muted fw-bold mb-0.5 text-uppercase extra-small" style="letter-spacing: 0.5px; font-size: 0.72rem;">Foreign Delegates</h6>
                            <h5 class="fw-bold text-dark mb-3" style="font-size: 1.05rem;">Approved Foreign</h5>
                        </div>
                        <a href="{{ route('international-approved-delegates') }}" class="btn btn-sm w-100 fw-bold d-flex align-items-center justify-content-center gap-1 btn-capsule py-1.5" style="background-color: #0D47A1; color: #FFFFFF; border: none; font-size: 0.8rem;">
                            <span>View Delegates</span>
                            <i class="bx bx-right-arrow-alt fs-5"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- 4. Abstract Submissions Card -->
            <div class="col-sm-6 col-xl-3">
                <div class="card h-100 border-0 shadow-sm admin-stat-card overflow-hidden">
                    <div style="height: 4px; background: linear-gradient(90deg, #FF6B00 0%, #E65100 100%);"></div>
                    <div class="card-body p-3.5 d-flex flex-column justify-content-between">
                        <div>
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <div class="rounded-3 d-flex align-items-center justify-content-center stat-icon-box" style="width: 46px; height: 46px; background-color: #FFF3E0; color: #FF6B00;">
                                    <i class="bx bx-file-find fs-3"></i>
                                </div>
                                <span class="badge px-3 py-1.5 fs-6 fw-bold rounded-pill shadow-xs" style="background-color: #FFF3E0; color: #FF6B00;">
                                    {{ number_format($abstractCount ?? 0) }}
                                </span>
                            </div>
                            <h6 class="text-muted fw-bold mb-0.5 text-uppercase extra-small" style="letter-spacing: 0.5px; font-size: 0.72rem;">Scientific Committee</h6>
                            <h5 class="fw-bold text-dark mb-3" style="font-size: 1.05rem;">Abstract Submissions</h5>
                        </div>
                        @if(Route::has('admin.abstracts.index'))
                        <a href="{{ route('admin.abstracts.index') }}" class="btn btn-sm w-100 fw-bold d-flex align-items-center justify-content-center gap-1 btn-capsule py-1.5" style="background-color: #FF6B00; color: #FFFFFF; border: none; font-size: 0.8rem;">
                            <span>Manage Abstracts</span>
                            <i class="bx bx-right-arrow-alt fs-5"></i>
                        </a>
                        @else
                        <span class="btn btn-sm w-100 fw-bold d-flex align-items-center justify-content-center gap-1 btn-capsule py-1.5 disabled" style="background-color: #FFF3E0; color: #FF6B00; border: none; font-size: 0.8rem;">
                            <span>Abstract Portal</span>
                        </span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

    </div>

    <style>
        .admin-stat-card {
            background: #ffffff;
            border-radius: 16px !important;
            transition: all 0.3s cubic-bezier(0.165, 0.84, 0.44, 1) !important;
            border: 1px solid rgba(226, 232, 240, 0.9) !important;
        }
        .admin-stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 16px 32px rgba(2, 136, 209, 0.12) !important;
            border-color: rgba(2, 136, 209, 0.3) !important;
        }
        .admin-stat-card .stat-icon-box {
            transition: transform 0.3s ease;
        }
        .admin-stat-card:hover .stat-icon-box {
            transform: scale(1.1) rotate(-4deg);
        }
        .btn-capsule {
            border-radius: 25px !important;
            transition: all 0.25s ease !important;
        }
        .btn-capsule:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.15) !important;
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
