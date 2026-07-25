@extends('admin.layouts.main')

@section('admin-content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h6 class="py-3 mb-4"><span class="invert-text-white">Indian Paid Registration </span>
    </h6>
    <div class="row">
        <div class="col-12 mb-4">
            <div class="card bg-primary text-white">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <h4 class="text-white mb-0">Registration: {{ $delegate->registration_number }}</h4>
                        <small>Submitted on {{ \Carbon\Carbon::parse($delegate->created_at)->format('d M, Y h:i A') }}</small>
                    </div>
                    <span class="badge bg-white text-primary fw-bold px-3 py-2 text-uppercase">
                        {{ $delegate->status }}
                    </span>
                </div>
            </div>
        </div>

        <div class="col-md-7">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="bx bx-user me-2"></i>Personal Details</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-sm-6">
                            <label class="text-muted small text-uppercase">Delegate Name</label>
                            <p class="fw-bold">{{ $delegate->user->prefix }} {{ $delegate->user->full_name }}</p>
                        </div>
                        <div class="col-sm-6">
                            <label class="text-muted small text-uppercase">Gender</label>
                            <p class="fw-bold">{{ $delegate->user->gender }}</p>
                        </div>
                        <div class="col-sm-6">
                            <label class="text-muted small text-uppercase">Date of Birth</label>
                            <p class="fw-bold">{{ date('d-m-Y', strtotime($delegate->user->date_of_birth)) }}</p>
                        </div>
                        <div class="col-sm-6">
                            <label class="text-muted small text-uppercase">Email ID</label>
                            <p class="fw-bold">{{ $delegate->user->email }}</p>
                        </div>
                        <div class="col-sm-6">
                            <label class="text-muted small text-uppercase">Delegate</label>
                            <p class="fw-bold">{{ $delegate->delegate_type }}</p>
                        </div>
                        <div class="col-sm-6">
                            <label class="text-muted small text-uppercase">Country</label>
                            <p class="fw-bold">{{ $delegate->country->country_name }}</p>
                        </div>
                        <div class="col-sm-6">
                            <label class="text-muted small text-uppercase">Type</label>
                            <p class="fw-bold">{{ $delegate->delegateCategory->category_name }}</p>
                        </div>
                        <div class="col-sm-6">
                            <label class="text-muted small text-uppercase">Address - City & State</label>
                            <p class="fw-bold">{{ $delegate->address }} - {{ $delegate->city }}, {{ $delegate?->state?->state_name }} ({{ $delegate->pin_code }})</p>
                        </div>
                        <div class="col-sm-6">
                            <label class="text-muted small text-uppercase">Mobile No.</label>
                            <p class="fw-bold"><i class="bx bxl-whatsapp text-success"></i> {{ $delegate->user->mobile_country_code }} {{ $delegate->user->mobile_number }}</p>
                        </div>
                        @if($delegate->whatsapp_number)
                        <div class="col-sm-6">
                            <label class="text-muted small text-uppercase">WhatsApp</label>
                            <p class="fw-bold"><i class="bx bxl-whatsapp text-success"></i>{{ $delegate->user->mobile_country_code }} {{ $delegate->whatsapp_number }}</p>
                        </div>
                        @endif

                        @if($delegate->dietary_preference)
                        <div class="col-sm-6">
                            <label class="text-muted small text-uppercase">Dietary Preference</label>
                            <p class="fw-bold">{{ $delegate->delegateCategory->dietary_preference }}</p>
                        </div>
                        @endif

                        @if($delegate->membership_no)
                        <div class="col-sm-6">
                            <label class="text-muted small text-uppercase">Membership ID</label>
                            <p class="fw-bold">{{ $delegate->membership_no ?? 'N/A' }}</p>
                        </div>
                        @endif

                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-12">
                            <h6 class="mb-3">Identity Proof ({{ $delegate->id_proof_type }})</h6>
                            @if($delegate->id_proof_document_path)
                            <a href="/storage/{{ $delegate->id_proof_document_path }}" target="_blank" class="btn btn-outline-primary btn-sm">
                                <i class="bx bx-show me-1"></i> View ID Document
                            </a>
                            @else
                            <span class="text-danger">No document uploaded</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-5">
            <div class="card mb-4 border-2 border-primary">
                <div class="card-header bg-label-primary">
                    <h5 class="mb-0"><i class="bx bx-wallet me-2"></i>Financial Summary</h5>
                </div>
                <div class="card-body pt-3">
                    <div class="d-flex justify-content-between mb-2">
                        <span>Transaction ID</span>
                        <span class="fw-bold">{{ $delegate->latestPayment->transaction_id }}</span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Registration Fee</span>
                        <span class="fw-bold">₹{{ $delegate->delegate_type == 'International' ? number_format($delegate->delegateCategory->foreign_fee, 2) : number_format($delegate->delegateCategory->indian_fee, 2) }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>CME Fee</span>
                        <span class="fw-bold">₹{{ number_format($delegate->cme_fee, 2) }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Accompanying Persons ({{ $delegate->accompanying_persons }})</span>
                        <span class="fw-bold">₹{{ number_format($delegate->accompanying_fee, 2) }}</span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Total Paid</h5>
                        <h4 class="text-primary mb-0">{{ $delegate->delegate_type == 'International' ? '$' : '₹' }} {{ number_format($delegate->total_amount, 2) }}</h4>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <h6 class="card-title">Downloads</h6>
                    <a href="{{ route('download.receipt', $delegate->registration_number) }}"
                        target="_blank"
                        class="btn btn-danger w-100 mb-2">
                        <i class="bx bxs-file-pdf me-2"></i>Download Receipt
                    </a>
                    <!-- <button class="btn btn-label-secondary w-100">
                        <i class="bx bx-printer me-2"></i>Print Details
                    </button> -->
                </div>
            </div>
        </div>
    </div>
</div>
@endsection