@extends('admin.layouts.main')

@section('admin-content')
    <div class="container-xxl flex-grow-1 mt-4">
        <!-- Welcome Hero Banner -->
        <div class="card mb-4 overflow-hidden border-0 shadow-sm" style="background: linear-gradient(135deg, #2D69FF 0%, #1A52E0 60%, #4BAA7D 100%); color: #FFFFFF; border-radius: 16px;">
            <div class="card-body p-4 p-md-5">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                    <div>
                        <span class="badge mb-2 px-3 py-1.5 fs-7 fw-bold" style="background-color: #DCFFF0; color: #4BAA7D; border-radius: 30px;">
                            <i class="bx bx-shield-check me-1"></i> IPHACON 2027 Admin Portal
                        </span>
                        <h2 class="text-white fw-bold mb-1">Welcome back, {{ auth('admin')->user()->full_name ?? auth('admin')->user()->username }}! 👋</h2>
                        <p class="text-white-50 mb-0">Here is the latest registration activity overview for IPHACON 2027 Conference.</p>
                    </div>
                    <div class="d-flex gap-2">
                        <a href="{{ route('indian-approved-delegates') }}" class="btn btn-light fw-bold text-primary shadow-sm" style="border-radius: 10px;">
                            <i class="bx bx-list-check me-1"></i> View Registrations
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Dashboard Stat Cards Grid -->
        <div class="row g-3">
            <!-- Indian Approved Delegates Card -->
            <div class="col-sm-6 col-lg-4">
                <div class="card h-100 border-0 shadow-sm" style="background: #FFFFFF; border-radius: 16px; border-left: 5px solid #4BAA7D !important;">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 52px; height: 52px; background-color: #DCFFF0; color: #4BAA7D;">
                                <i class="bx bx-user-check fs-2"></i>
                            </div>
                            <span class="badge px-3 py-2 fs-6 fw-bold" style="background-color: #DCFFF0; color: #4BAA7D; border-radius: 20px;">
                                {{ $IndApprovedCount }}
                            </span>
                        </div>
                        <h6 class="text-muted fw-semibold mb-1 text-uppercase small" style="letter-spacing: 0.5px;">Indian Delegates</h6>
                        <h5 class="fw-bold text-dark mb-3">Indian Approved</h5>
                        <a href="{{ route('indian-approved-delegates') }}" class="btn btn-sm w-100 fw-bold d-flex align-items-center justify-content-center gap-1" style="background-color: #DCFFF0; color: #4BAA7D; border: 1px solid #4BAA7D; border-radius: 8px;">
                            <span>View Details</span>
                            <i class="bx bx-right-arrow-alt fs-5"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- International Payment Submitted Card -->
            <div class="col-sm-6 col-lg-4">
                <div class="card h-100 border-0 shadow-sm" style="background: #FFFFFF; border-radius: 16px; border-left: 5px solid #2D69FF !important;">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 52px; height: 52px; background-color: #E1F0FF; color: #2D69FF;">
                                <i class="bx bx-credit-card-front fs-2"></i>
                            </div>
                            <span class="badge px-3 py-2 fs-6 fw-bold" style="background-color: #E1F0FF; color: #2D69FF; border-radius: 20px;">
                                {{ $appliedCount }}
                            </span>
                        </div>
                        <h6 class="text-muted fw-semibold mb-1 text-uppercase small" style="letter-spacing: 0.5px;">International Delegates</h6>
                        <h5 class="fw-bold text-dark mb-3">Payment Submitted</h5>
                        <a href="{{ route('international-payment-submitted-delegates') }}" class="btn btn-sm w-100 fw-bold d-flex align-items-center justify-content-center gap-1" style="background-color: #E1F0FF; color: #2D69FF; border: 1px solid #2D69FF; border-radius: 8px;">
                            <span>View Details</span>
                            <i class="bx bx-right-arrow-alt fs-5"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Approved International Delegate Card -->
            <div class="col-sm-6 col-lg-4">
                <div class="card h-100 border-0 shadow-sm" style="background: #FFFFFF; border-radius: 16px; border-left: 5px solid #2D69FF !important;">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 52px; height: 52px; background-color: #E1F0FF; color: #2D69FF;">
                                <i class="bx bx-globe fs-2"></i>
                            </div>
                            <span class="badge px-3 py-2 fs-6 fw-bold" style="background-color: #2D69FF; color: #FFFFFF; border-radius: 20px;">
                                {{ $IntApprovedCount }}
                            </span>
                        </div>
                        <h6 class="text-muted fw-semibold mb-1 text-uppercase small" style="letter-spacing: 0.5px;">International Delegates</h6>
                        <h5 class="fw-bold text-dark mb-3">Approved International</h5>
                        <a href="{{ route('international-approved-delegates') }}" class="btn btn-sm w-100 fw-bold d-flex align-items-center justify-content-center gap-1" style="background-color: #2D69FF; color: #FFFFFF; border: none; border-radius: 8px;">
                            <span>View Details</span>
                            <i class="bx bx-right-arrow-alt fs-5"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        window.history.pushState(null, '', window.location.href);
        window.onpopstate = function() {
            window.history.go(1);
        };
    </script>
@endpush
