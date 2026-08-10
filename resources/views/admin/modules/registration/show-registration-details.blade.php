@extends('admin.layouts.main')

@section('admin-content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="d-flex align-items-center justify-content-between py-3 mb-3">
        <h5 class="mb-0"><span class="invert-text-white"><i class="bx bx-user-pin me-2 text-primary"></i>Delegate Registration Details</span></h5>
        <a href="{{ url()->previous() }}" class="btn btn-outline-secondary btn-sm rounded-pill px-3">
            <i class="bx bx-arrow-back me-1"></i>Back
        </a>
    </div>

    <!-- Alert Messages -->
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show p-3 mb-3 rounded-3 d-flex align-items-center justify-content-between" role="alert">
            <div class="d-flex align-items-center gap-2">
                <i class="bx bx-check-circle fs-4 text-success"></i>
                <div class="fw-bold text-dark">{{ session('success') }}</div>
            </div>
            <button type="button" class="btn-close py-2 px-2" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show p-3 mb-3 rounded-3 d-flex align-items-center justify-content-between" role="alert">
            <div class="d-flex align-items-center gap-2">
                <i class="bx bx-error-circle fs-4 text-danger"></i>
                <div class="fw-bold text-dark">{{ session('error') }}</div>
            </div>
            <button type="button" class="btn-close py-2 px-2" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Header Banner -->
    <div class="row">
        <div class="col-12 mb-4">
            <div class="card text-white shadow-sm border-0" style="background: linear-gradient(135deg, #161b40 0%, #1e255e 50%, #2e3192 100%) !important; border-radius: 14px;">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                        <div class="d-flex align-items-center gap-3">
                            <div class="avatar avatar-xl flex-shrink-0">
                                <img src="{{ $delegate->photo_path ? asset('storage/' . $delegate->photo_path) : asset('images/default-avatar.svg') }}"
                                    alt="Delegate Photo" class="w-100 h-100 rounded-circle border border-3 border-white shadow-sm" style="object-fit: cover; width: 64px; height: 64px;"
                                    onerror="this.onerror=null; this.src='{{ asset('images/default-avatar.svg') }}';" />
                            </div>
                            <div>
                                <h4 class="text-white mb-1 fw-bold">{{ $delegate->user?->prefix }} {{ $delegate->user?->full_name }}</h4>
                                <div class="d-flex align-items-center gap-2 flex-wrap text-white-50 extra-small">
                                    <span class="badge font-monospace extra-small px-2.5 py-1.5 rounded-2" style="background-color: rgba(255, 255, 255, 0.18) !important; color: #FFFFFF !important; border: 1px solid rgba(255, 255, 255, 0.35) !important; font-size: 0.78rem;">
                                        <i class="bx bx-barcode me-1" style="color: #93C5FD !important;"></i>Ack ID: <strong style="color: #FFFFFF !important;">{{ $delegate->acknowledgement_id ?? ('IPHA-ACK-'.$delegate->id) }}</strong>
                                    </span>
                                    @if($delegate->registration_number)
                                        <span class="badge font-monospace extra-small px-2.5 py-1.5 rounded-2" style="background-color: rgba(16, 185, 129, 0.25) !important; color: #6EE7B7 !important; border: 1px solid rgba(16, 185, 129, 0.45) !important; font-size: 0.78rem;">
                                            <i class="bx bx-check-shield me-1" style="color: #34D399 !important;"></i>Reg No: <strong style="color: #FFFFFF !important;">{{ $delegate->registration_number }}</strong>
                                        </span>
                                    @else
                                        <span class="badge extra-small px-2.5 py-1.5 rounded-2 fw-bold" style="background-color: rgba(245, 158, 11, 0.25) !important; color: #FDE047 !important; border: 1px solid rgba(245, 158, 11, 0.45) !important; font-size: 0.78rem;">
                                            <i class="bx bx-time me-1"></i>Reg No: Pending Approval
                                        </span>
                                    @endif
                                    <span>•</span>
                                    <span><i class="bx bx-calendar me-1"></i>Submitted: {{ $delegate->created_at ? \Carbon\Carbon::parse($delegate->created_at)->format('d M, Y h:i A') : 'N/A' }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge bg-white text-dark fw-bold px-3 py-2 text-uppercase shadow-sm" style="font-size: 0.85rem; border-radius: 20px;">
                                <i class="bx bx-check-shield text-success me-1"></i> {{ $delegate->status }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Left Column: Personal & Contact Information -->
        <div class="col-lg-7">
            <!-- Personal Details Card -->
            <div class="card mb-4 shadow-sm border-0" style="border-radius: 12px;">
                <div class="card-header py-3 bg-light border-bottom d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 fw-bold text-primary"><i class="bx bx-user me-2"></i>Personal & Contact Details</h6>
                </div>
                <div class="card-body pt-2 pb-3">
                    <table class="table table-sm table-borderless align-middle mb-0" style="font-size: 0.88rem;">
                        <tbody>
                            <tr class="border-bottom border-light">
                                <th class="text-muted fw-semibold py-2.5" style="width: 38%;">Full Name</th>
                                <td class="fw-bold text-dark py-2.5">{{ $delegate->user?->prefix }} {{ $delegate->user?->full_name }}</td>
                            </tr>
                            <tr class="border-bottom border-light">
                                <th class="text-muted fw-semibold py-2.5">Gender & Date of Birth</th>
                                <td class="fw-semibold text-dark py-2.5">
                                    {{ $delegate->user?->gender ?? 'N/A' }} 
                                    @if($delegate->user?->date_of_birth)
                                        | {{ date('d-m-Y', strtotime($delegate->user->date_of_birth)) }}
                                    @endif
                                </td>
                            </tr>
                            <tr class="border-bottom border-light">
                                <th class="text-muted fw-semibold py-2.5">Email Address</th>
                                <td class="fw-semibold text-dark py-2.5">
                                    <i class="bx bx-envelope text-primary me-1"></i>{{ $delegate->user?->email }}
                                </td>
                            </tr>
                            <tr class="border-bottom border-light">
                                <th class="text-muted fw-semibold py-2.5">Mobile Number</th>
                                <td class="fw-semibold text-dark py-2.5">
                                    <i class="bx bx-phone text-primary me-1"></i>{{ $delegate->user?->mobile_country_code ?? '+91' }} {{ $delegate->user?->mobile_number }}
                                </td>
                            </tr>
                            <tr class="border-bottom border-light">
                                <th class="text-muted fw-semibold py-2.5">WhatsApp Number</th>
                                <td class="fw-semibold text-dark py-2.5">
                                    <i class="bx bxl-whatsapp text-success me-1"></i>{{ $delegate->whatsapp_country_code ?? '+91' }} {{ $delegate->whatsapp_number ?: ($delegate->user?->mobile_number) }}
                                </td>
                            </tr>
                            <tr class="border-bottom border-light">
                                <th class="text-muted fw-semibold py-2.5">Address & Location</th>
                                <td class="fw-semibold text-dark py-2.5">
                                    {{ $delegate->address }}<br>
                                    <span class="text-muted extra-small">
                                        {{ $delegate->city }}, {{ $delegate->state?->state_name ?? $delegate->other_state }} - {{ $delegate->pin_code }}, {{ $delegate->country?->country_name }}
                                    </span>
                                </td>
                            </tr>
                            <tr class="border-bottom border-light">
                                <th class="text-muted fw-semibold py-2.5">Dietary Preference</th>
                                <td class="fw-semibold text-dark py-2.5">
                                    <span class="badge bg-label-secondary"><i class="bx bx-restaurant me-1"></i>{{ $delegate->dietary_preference ?? 'Not Specified' }}</span>
                                </td>
                            </tr>
                            <tr class="border-bottom border-light">
                                <th class="text-muted fw-semibold py-2.5">ID Proof Details</th>
                                <td class="fw-semibold text-dark py-2.5">
                                    <div class="mb-1">
                                        <span class="badge bg-label-info me-1">{{ $delegate->id_proof_type ?? 'ID Proof' }}</span>
                                        <strong>{{ $delegate->id_proof_number ?? 'N/A' }}</strong>
                                    </div>
                                    @if($delegate->id_proof_document_path)
                                        <a href="{{ asset('storage/' . $delegate->id_proof_document_path) }}" target="_blank" class="btn btn-xs btn-outline-primary rounded-pill px-2.5 py-1 fw-bold">
                                            <i class="bx bx-show me-1"></i> View Document
                                        </a>
                                    @else
                                        <span class="text-danger extra-small">No document uploaded</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th class="text-muted fw-semibold py-2.5">Profile Photo</th>
                                <td class="py-2.5">
                                    @if($delegate->photo_path)
                                        <a href="{{ asset('storage/' . $delegate->photo_path) }}" target="_blank" class="btn btn-xs btn-outline-success rounded-pill px-2.5 py-1 fw-bold">
                                            <i class="bx bx-image me-1"></i> View Full Photo
                                        </a>
                                    @else
                                        <span class="text-muted extra-small">Default Avatar</span>
                                    @endif
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Conference & Membership Details Card -->
            <div class="card mb-4 shadow-sm border-0" style="border-radius: 12px;">
                <div class="card-header py-3 bg-light border-bottom">
                    <h6 class="mb-0 fw-bold text-primary"><i class="bx bx-id-card me-2"></i>Conference & Membership Information</h6>
                </div>
                <div class="card-body pt-2 pb-3">
                    <table class="table table-sm table-borderless align-middle mb-0" style="font-size: 0.88rem;">
                        <tbody>
                            <tr class="border-bottom border-light">
                                <th class="text-muted fw-semibold py-2.5" style="width: 38%;">Delegate Type</th>
                                <td class="py-2.5">
                                    <span class="badge bg-label-primary px-3 py-1 fw-bold">{{ $delegate->delegate_type }} Delegate</span>
                                </td>
                            </tr>
                            <tr class="border-bottom border-light">
                                <th class="text-muted fw-semibold py-2.5">Selected Category</th>
                                <td class="fw-bold text-dark py-2.5">
                                    {{ $delegate->delegateCategory?->category_name ?? 'N/A' }}
                                </td>
                            </tr>
                            <tr class="border-bottom border-light">
                                <th class="text-muted fw-semibold py-2.5">CME Workshop</th>
                                <td class="py-2.5">
                                    @if($delegate->participate_in_cme)
                                        <span class="badge bg-success"><i class="bx bx-check me-1"></i> Yes (Participating)</span>
                                    @else
                                        <span class="badge bg-light text-muted">No</span>
                                    @endif
                                </td>
                            </tr>
                            <tr class="border-bottom border-light">
                                <th class="text-muted fw-semibold py-2.5">Accompanying Persons</th>
                                <td class="fw-bold text-dark py-2.5">
                                    {{ $delegate->accompanying_persons ?? 0 }} Person(s)
                                </td>
                            </tr>
                            @if($delegate->is_ismm_member || $delegate->membership_no || $delegate->ismm_membership_no)
                            <tr class="border-bottom border-light">
                                <th class="text-muted fw-semibold py-2.5">IPHACON Membership</th>
                                <td class="fw-semibold text-dark py-2.5">
                                    <span class="badge bg-label-success me-1">Member</span> 
                                    ID: <strong>{{ $delegate->membership_no ?: $delegate->ismm_membership_no }}</strong>
                                </td>
                            </tr>
                            @endif
                            @if($delegate->is_isham_member || $delegate->isham_membership_no)
                            <tr class="border-bottom border-light">
                                <th class="text-muted fw-semibold py-2.5">ISHAM Membership</th>
                                <td class="fw-semibold text-dark py-2.5">
                                    <span class="badge bg-label-success me-1">Member</span> 
                                    ID: <strong>{{ $delegate->isham_membership_no }}</strong>
                                </td>
                            </tr>
                            @endif
                            @if($delegate->is_young_isam_member || $delegate->young_isam_membership_no)
                            <tr>
                                <th class="text-muted fw-semibold py-2.5">Young ISAM Membership</th>
                                <td class="fw-semibold text-dark py-2.5">
                                    <span class="badge bg-label-success me-1">Member</span> 
                                    ID: <strong>{{ $delegate->young_isam_membership_no }}</strong>
                                </td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Right Column: Financial Summary & Admin Actions -->
        <div class="col-lg-5">
            <!-- Financial Breakdown Card -->
            <div class="card mb-4 shadow-sm border-2 border-primary" style="border-radius: 12px;">
                <div class="card-header bg-label-primary py-3">
                    <h6 class="mb-0 fw-bold text-primary"><i class="bx bx-calculator me-2"></i>Financial & Payment Summary</h6>
                </div>
                <div class="card-body pt-3">
                    <div class="d-flex justify-content-between align-items-center mb-2.5">
                        <span class="text-muted extra-small fw-semibold">Transaction Reference ID</span>
                        <span class="fw-bold text-dark extra-small">{{ $delegate->latestPayment?->transaction_id ?? 'N/A' }}</span>
                    </div>

                    @if($delegate->latestPayment && $delegate->latestPayment->payment_receipt_path)
                    <div class="d-flex justify-content-between align-items-center mb-2.5 p-2 rounded bg-light border">
                        <span class="extra-small fw-semibold text-dark"><i class="bx bx-file text-primary me-1"></i>Payment Screenshot</span>
                        <a href="{{ asset('storage/' . $delegate->latestPayment->payment_receipt_path) }}" target="_blank" class="btn btn-xs btn-outline-primary py-1 px-2.5 fw-bold rounded-pill">
                            <i class="bx bx-show me-1"></i>View Screenshot
                        </a>
                    </div>
                    @endif

                    <hr class="my-2 opacity-25">

                    <div class="d-flex justify-content-between mb-2 extra-small">
                        <span class="text-muted">Delegate Category Fee (Base)</span>
                        <span class="fw-semibold text-dark">{{ $delegate->delegate_type == 'International' ? '$' : '₹' }}{{ number_format($delegate->delegate_fee, 2) }}</span>
                    </div>

                    @if($delegate->participate_in_cme)
                    <div class="d-flex justify-content-between mb-2 extra-small">
                        <span class="text-muted">CME / Workshop Fee</span>
                        <span class="fw-semibold text-dark">₹{{ number_format($delegate->cme_fee ?: 2000, 2) }}</span>
                    </div>
                    @endif

                    @if($delegate->accompanying_persons > 0)
                    <div class="d-flex justify-content-between mb-2 extra-small">
                        <span class="text-muted">Accompanying Persons ({{ $delegate->accompanying_persons }})</span>
                        <span class="fw-semibold text-dark">₹{{ number_format($delegate->accompanying_fee ?: ($delegate->accompanying_persons * 5000), 2) }}</span>
                    </div>
                    @endif

                    @if($delegate->delegate_type != 'International')
                    <div class="d-flex justify-content-between mb-2 extra-small">
                        <span class="text-muted">GST Amount (18%)</span>
                        <span class="fw-bold text-warning">+ ₹{{ number_format($delegate->gst_amount, 2) }}</span>
                    </div>
                    @endif

                    <hr class="my-2 opacity-50">

                    <div class="d-flex justify-content-between align-items-center pt-1">
                        <h6 class="mb-0 fw-bold text-dark">Total Amount (Incl. GST)</h6>
                        <h4 class="text-primary mb-0 fw-extrabold">{{ $delegate->delegate_type == 'International' ? '$' : '₹' }}{{ number_format($delegate->total_amount, 2) }}</h4>
                    </div>
                </div>
            </div>

            <!-- Admin Registration Action Control Card -->
            <div class="card shadow-sm border-0 mb-3" style="border-radius: 10px; border-left: 3px solid #28a745 !important;">
                <div class="card-header bg-light py-2 px-3">
                    <h6 class="mb-0 fw-bold text-dark extra-small"><i class="bx bx-slider-alt me-1.5 text-success"></i>Admin Registration Action</h6>
                </div>
                <div class="card-body p-2.5">
                    <div class="d-grid gap-2">
                        @if($delegate->status !== 'Approved')
                        <form action="{{ route('student-approved-regis') }}" method="POST" class="d-grid m-0">
                            @csrf
                            <input type="hidden" name="registration_number" value="{{ $delegate->registration_number }}">
                            <button type="submit" class="btn btn-sm btn-success fw-bold py-1.5 rounded-2 shadow-xs" style="font-size: 0.8rem;" onclick="return confirm('Are you sure you want to approve this registration?')">
                                <i class="bx bx-check-circle me-1"></i> Approve Registration
                            </button>
                        </form>
                        @else
                        <div class="alert alert-success p-1.5 px-2 mb-1 rounded-2 extra-small fw-bold text-center" style="font-size: 0.76rem;">
                            <i class="bx bx-check-double me-1"></i> Registration Status: Approved
                        </div>
                        @endif

                        <div class="d-flex gap-2">
                            <button type="button" class="btn btn-sm btn-warning text-dark fw-bold w-50 py-1.5 rounded-2 shadow-xs" style="font-size: 0.78rem;" 
                                data-bs-toggle="modal" data-bs-target="#revertModal">
                                <i class="bx bx-undo me-1"></i> Revert
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-danger fw-bold w-50 py-1.5 rounded-2" style="font-size: 0.78rem;" 
                                data-bs-toggle="modal" data-bs-target="#rejectModal">
                                <i class="bx bx-x-circle me-1"></i> Reject
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Download & Document Actions Card -->
            <div class="card shadow-sm border-0 mb-3" style="border-radius: 10px;">
                <div class="card-header bg-light py-2 px-3">
                    <h6 class="mb-0 fw-bold text-dark extra-small"><i class="bx bx-download me-1.5 text-primary"></i>Documents & Downloads</h6>
                </div>
                <div class="card-body p-2.5">
                    @if($delegate->registration_number)
                    <a href="{{ route('download.receipt', $delegate->registration_number) }}"
                        target="_blank"
                        class="btn btn-sm btn-primary w-100 py-1.5 fw-bold rounded-2 shadow-xs" style="font-size: 0.8rem;">
                        <i class="bx bxs-file-pdf me-1.5"></i>Download Acknowledgement PDF
                    </a>
                    @else
                    <button class="btn btn-sm btn-secondary w-100 py-1.5 fw-bold rounded-2" style="font-size: 0.8rem;" disabled>
                        <i class="bx bxs-file-pdf me-1.5"></i>PDF Receipt (Pending Reg No)
                    </button>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Revert Registration Modal -->
<div class="modal fade" id="revertModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-3">
            <form action="{{ route('student-revert-regis') }}" method="POST">
                @csrf
                <input type="hidden" name="registration_number" value="{{ $delegate->registration_number }}">
                <div class="modal-header bg-warning bg-opacity-10 border-bottom-0 pb-0">
                    <div class="d-flex align-items-center gap-2">
                        <div class="avatar avatar-sm bg-warning text-white rounded-circle d-flex align-items-center justify-content-center">
                            <i class="bx bx-undo fs-5"></i>
                        </div>
                        <h5 class="modal-title fw-bold text-dark">Revert Registration</h5>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body py-3">
                    <p class="text-muted extra-small mb-3">Specify the reason for reverting this application back to draft status for correction.</p>
                    <div class="mb-3">
                        <label for="revert_reason" class="form-label fw-bold text-dark extra-small">Reason for Reverting <span class="text-danger">*</span></label>
                        <textarea class="form-control" name="reason" rows="3" required placeholder="Enter revert reason details for delegate..."></textarea>
                    </div>
                </div>
                <div class="modal-footer bg-light border-top-0 pt-0">
                    <button type="button" class="btn btn-sm btn-label-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-sm btn-warning text-dark fw-bold px-3">Confirm Revert</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Reject Registration Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-3">
            <form action="{{ route('student-reject-regis') }}" method="POST">
                @csrf
                <input type="hidden" name="registration_number" value="{{ $delegate->registration_number }}">
                <div class="modal-header bg-danger bg-opacity-10 border-bottom-0 pb-0">
                    <div class="d-flex align-items-center gap-2">
                        <div class="avatar avatar-sm bg-danger text-white rounded-circle d-flex align-items-center justify-content-center">
                            <i class="bx bx-x-circle fs-5"></i>
                        </div>
                        <h5 class="modal-title fw-bold text-danger">Reject Registration</h5>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body py-3">
                    <p class="text-muted extra-small mb-3">Are you sure you want to reject this registration?</p>
                    <div class="mb-3">
                        <label for="reject_reason" class="form-label fw-bold text-dark extra-small">Reason for Rejection <span class="text-danger">*</span></label>
                        <textarea class="form-control" name="reason" rows="3" required placeholder="Enter rejection reason for delegate..."></textarea>
                    </div>
                </div>
                <div class="modal-footer bg-light border-top-0 pt-0">
                    <button type="button" class="btn btn-sm btn-label-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-sm btn-danger fw-bold px-3">Confirm Reject</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection