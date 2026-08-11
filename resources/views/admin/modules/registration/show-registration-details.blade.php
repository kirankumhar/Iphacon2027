@extends('admin.layouts.main')

@section('admin-content')
<div class="container-xxl flex-grow-1 mt-3 mb-4">
    <!-- Top Navigation Header -->
    <div class="d-flex align-items-center justify-content-between py-2 mb-3">
        <div>
            <span class="text-muted extra-small d-block">IPHACON 2027 Admin Portal</span>
            <h5 class="mb-0 fw-bold text-dark d-flex align-items-center gap-2">
                <i class="bx bx-user-pin text-primary fs-4"></i>Delegate Profile &amp; Registration Details
            </h5>
        </div>
        <a href="{{ url()->previous() }}" class="btn btn-sm btn-light fw-bold text-dark shadow-2xs border rounded-pill px-3.5 py-1.5 d-flex align-items-center gap-1.5" style="font-size: 0.825rem;">
            <i class="bx bx-arrow-back text-primary fs-5"></i> Back to List
        </a>
    </div>

    <!-- Alert Messages -->
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show p-3 mb-3 rounded-3 d-flex align-items-center justify-content-between shadow-xs border-0" role="alert" style="background-color: #DCFCE7; color: #065F46;">
            <div class="d-flex align-items-center gap-2">
                <i class="bx bx-check-circle fs-4 text-success"></i>
                <div class="fw-bold">{{ session('success') }}</div>
            </div>
            <button type="button" class="btn-close py-2 px-2" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show p-3 mb-3 rounded-3 d-flex align-items-center justify-content-between shadow-xs border-0" role="alert" style="background-color: #FEE2E2; color: #991B1B;">
            <div class="d-flex align-items-center gap-2">
                <i class="bx bx-error-circle fs-4 text-danger"></i>
                <div class="fw-bold">{{ session('error') }}</div>
            </div>
            <button type="button" class="btn-close py-2 px-2" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Profile Executive Hero Banner -->
    <div class="row">
        <div class="col-12 mb-4">
            <div class="card text-white shadow-sm border-0 position-relative overflow-hidden" style="background: linear-gradient(135deg, #0F172A 0%, #1E293B 50%, #2563EB 100%) !important; border-radius: 16px;">
                <div class="card-body p-4 position-relative z-2">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                        <div class="d-flex align-items-center gap-3.5">
                            <div class="avatar avatar-xl flex-shrink-0 position-relative">
                                <img src="{{ $delegate->photo_path ? asset('storage/' . $delegate->photo_path) : asset('images/default-avatar.svg') }}"
                                    alt="Delegate Photo" class="w-100 h-100 rounded-circle border border-3 border-white shadow-sm" style="object-fit: cover; width: 68px; height: 68px;"
                                    onerror="this.onerror=null; this.src='{{ asset('images/default-avatar.svg') }}';" />
                            </div>
                            <div>
                                <h4 class="text-white mb-1 fw-bold fs-3" style="letter-spacing: -0.3px;">
                                    {{ $delegate->user?->prefix }} {{ $delegate->user?->full_name }}
                                </h4>
                                <div class="d-flex align-items-center gap-2 flex-wrap text-white-50 extra-small">
                                    <span class="badge font-monospace extra-small px-2.5 py-1 rounded-pill" style="background: rgba(255, 255, 255, 0.15); color: #FFFFFF; border: 1px solid rgba(255, 255, 255, 0.3);">
                                        <i class="bx bx-barcode me-1 text-info"></i>Ack ID: <strong>{{ $delegate->acknowledgement_id ?? 'N/A' }}</strong>
                                    </span>
                                    @if($delegate->registration_number)
                                        <span class="badge font-monospace extra-small px-2.5 py-1 rounded-pill" style="background: rgba(16, 185, 129, 0.25); color: #6EE7B7; border: 1px solid rgba(16, 185, 129, 0.4);">
                                            <i class="bx bx-check-shield me-1 text-success"></i>Reg No: <strong>{{ $delegate->registration_number }}</strong>
                                        </span>
                                    @else
                                        <span class="badge extra-small px-2.5 py-1 rounded-pill fw-semibold" style="background: rgba(245, 158, 11, 0.25); color: #FDE047; border: 1px solid rgba(245, 158, 11, 0.4);">
                                            <i class="bx bx-time me-1"></i>Reg No: Pending Approval
                                        </span>
                                    @endif
                                    <span class="text-white-50">•</span>
                                    <span><i class="bx bx-calendar me-1"></i>Submitted: {{ $delegate->created_at ? \Carbon\Carbon::parse($delegate->created_at)->format('d M, Y h:i A') : 'N/A' }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex align-items-center gap-2">
                            @if($delegate->status === 'Approved')
                                <span class="badge px-3.5 py-2 fw-bold text-uppercase shadow-sm d-flex align-items-center gap-1.5" style="background-color: #DCFCE7; color: #065F46; border: 1px solid #86EFAC; border-radius: 20px; font-size: 0.82rem;">
                                    <i class="bx bx-check-circle fs-5" style="color: #059669;"></i> Approved
                                </span>
                            @elseif($delegate->status === 'Rejected')
                                <span class="badge px-3.5 py-2 fw-bold text-uppercase shadow-sm d-flex align-items-center gap-1.5" style="background-color: #FEE2E2; color: #991B1B; border: 1px solid #FCA5A5; border-radius: 20px; font-size: 0.82rem;">
                                    <i class="bx bx-x-circle fs-5" style="color: #DC2626;"></i> Rejected
                                </span>
                            @else
                                <span class="badge px-3.5 py-2 fw-bold text-uppercase shadow-sm d-flex align-items-center gap-1.5" style="background-color: #FEF3C7; color: #92400E; border: 1px solid #FDE68A; border-radius: 20px; font-size: 0.82rem;">
                                    <i class="bx bx-time-five fs-5" style="color: #D97706;"></i> {{ $delegate->status }}
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Left Column: Personal & Contact Information -->
        <div class="col-lg-7">
            <!-- Personal Details Card -->
            <div class="card mb-4 shadow-sm border-0 rounded-3 overflow-hidden">
                <div class="card-header py-3 bg-white border-bottom d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 fw-bold text-dark d-flex align-items-center gap-2">
                        <i class="bx bx-user text-primary fs-5"></i>Personal &amp; Contact Details
                    </h6>
                </div>
                <div class="card-body p-0">
                    <table class="table table-hover align-middle mb-0" style="font-size: 0.88rem;">
                        <tbody>
                            <tr class="border-bottom border-light">
                                <th class="text-muted fw-semibold py-3 px-3.5" style="width: 36%;">Full Name</th>
                                <td class="fw-bold text-dark py-3 px-3.5">{{ $delegate->user?->prefix }} {{ $delegate->user?->full_name }}</td>
                            </tr>
                            <tr class="border-bottom border-light">
                                <th class="text-muted fw-semibold py-3 px-3.5">Gender &amp; Date of Birth</th>
                                <td class="fw-semibold text-dark py-3 px-3.5">
                                    {{ $delegate->user?->gender ?? 'N/A' }} 
                                    @if($delegate->user?->date_of_birth)
                                        <span class="text-muted mx-1">|</span> {{ date('d-m-Y', strtotime($delegate->user->date_of_birth)) }}
                                    @endif
                                </td>
                            </tr>
                            <tr class="border-bottom border-light">
                                <th class="text-muted fw-semibold py-3 px-3.5">Email Address</th>
                                <td class="fw-semibold text-dark py-3 px-3.5">
                                    <i class="bx bx-envelope text-primary me-1"></i>{{ $delegate->user?->email }}
                                </td>
                            </tr>
                            <tr class="border-bottom border-light">
                                <th class="text-muted fw-semibold py-3 px-3.5">Mobile Number</th>
                                <td class="fw-semibold text-dark py-3 px-3.5">
                                    <i class="bx bx-phone text-primary me-1"></i>{{ $delegate->user?->mobile_country_code ?? '+91' }} {{ $delegate->user?->mobile_number }}
                                </td>
                            </tr>
                            <tr class="border-bottom border-light">
                                <th class="text-muted fw-semibold py-3 px-3.5">WhatsApp Number</th>
                                <td class="fw-semibold text-dark py-3 px-3.5">
                                    <i class="bx bxl-whatsapp text-success me-1"></i>{{ $delegate->whatsapp_country_code ?? '+91' }} {{ $delegate->whatsapp_number ?: ($delegate->user?->mobile_number) }}
                                </td>
                            </tr>
                            <tr class="border-bottom border-light">
                                <th class="text-muted fw-semibold py-3 px-3.5">Address &amp; Location</th>
                                <td class="fw-semibold text-dark py-3 px-3.5">
                                    {{ $delegate->address }}<br>
                                    <span class="text-muted extra-small">
                                        {{ $delegate->city }}, {{ $delegate->state?->state_name ?? $delegate->other_state }} - {{ $delegate->pin_code }}, {{ $delegate->country?->country_name }}
                                    </span>
                                </td>
                            </tr>
                            <tr class="border-bottom border-light">
                                <th class="text-muted fw-semibold py-3 px-3.5">Dietary Preference</th>
                                <td class="fw-semibold text-dark py-3 px-3.5">
                                    <span class="badge bg-light text-dark border px-2.5 py-1 rounded-2"><i class="bx bx-restaurant me-1 text-primary"></i>{{ $delegate->dietary_preference ?? 'Not Specified' }}</span>
                                </td>
                            </tr>
                            <tr class="border-bottom border-light">
                                <th class="text-muted fw-semibold py-3 px-3.5">ID Proof Details</th>
                                <td class="fw-semibold text-dark py-3 px-3.5">
                                    <div class="mb-1.5">
                                        <span class="badge bg-light text-primary border me-1.5 px-2.5 py-1">{{ $delegate->id_proof_type ?? 'ID Proof' }}</span>
                                        <strong class="font-monospace text-dark">{{ $delegate->masked_id_proof_number }}</strong>
                                    </div>
                                    @if($delegate->id_proof_document_path)
                                        <a href="{{ asset('storage/' . $delegate->id_proof_document_path) }}" target="_blank" class="btn btn-xs btn-outline-primary rounded-pill px-3 py-1 fw-bold">
                                            <i class="bx bx-show me-1"></i> View Document
                                        </a>
                                    @else
                                        <span class="text-danger extra-small">No document uploaded</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th class="text-muted fw-semibold py-3 px-3.5">Profile Photo</th>
                                <td class="py-3 px-3.5">
                                    @if($delegate->photo_path)
                                        <a href="{{ asset('storage/' . $delegate->photo_path) }}" target="_blank" class="btn btn-xs btn-outline-success rounded-pill px-3 py-1 fw-bold">
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
            <div class="card mb-4 shadow-sm border-0 rounded-3 overflow-hidden">
                <div class="card-header py-3 bg-white border-bottom">
                    <h6 class="mb-0 fw-bold text-dark d-flex align-items-center gap-2">
                        <i class="bx bx-id-card text-primary fs-5"></i>Conference &amp; Membership Information
                    </h6>
                </div>
                <div class="card-body p-0">
                    <table class="table table-hover align-middle mb-0" style="font-size: 0.88rem;">
                        <tbody>
                            <tr class="border-bottom border-light">
                                <th class="text-muted fw-semibold py-3 px-3.5" style="width: 36%;">Delegate Type</th>
                                <td class="py-3 px-3.5">
                                    <span class="badge bg-primary px-3 py-1.5 fw-bold rounded-pill">{{ $delegate->delegate_type }} Delegate</span>
                                </td>
                            </tr>
                            <tr class="border-bottom border-light">
                                <th class="text-muted fw-semibold py-3 px-3.5">Selected Category</th>
                                <td class="fw-bold text-dark py-3 px-3.5">
                                    {{ $delegate->delegateCategory?->category_name ?? 'N/A' }}
                                </td>
                            </tr>
                            <tr class="border-bottom border-light">
                                <th class="text-muted fw-semibold py-3 px-3.5">Pre-Conference Workshop</th>
                                <td class="py-3 px-3.5">
                                    @if($delegate->participate_in_cme)
                                        <span class="badge bg-success px-3 py-1 rounded-pill"><i class="bx bx-check me-1"></i> Participating</span>
                                    @else
                                        <span class="badge bg-light text-muted border px-2.5 py-1">No</span>
                                    @endif
                                </td>
                            </tr>
                            <tr class="border-bottom border-light">
                                <th class="text-muted fw-semibold py-3 px-3.5">Accompanying Persons</th>
                                <td class="fw-bold text-dark py-3 px-3.5">
                                    {{ $delegate->accompanying_persons ?? 0 }} Person(s)
                                </td>
                            </tr>
                            @if($delegate->is_ismm_member || $delegate->membership_no || $delegate->ismm_membership_no)
                            <tr class="border-bottom border-light">
                                <th class="text-muted fw-semibold py-3 px-3.5">IPHACON Membership</th>
                                <td class="fw-semibold text-dark py-3 px-3.5">
                                    <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 me-1.5">Member</span> 
                                    ID: <strong class="font-monospace">{{ $delegate->membership_no ?: $delegate->ismm_membership_no }}</strong>
                                </td>
                            </tr>
                            @endif
                            @if($delegate->is_isham_member || $delegate->isham_membership_no)
                            <tr class="border-bottom border-light">
                                <th class="text-muted fw-semibold py-3 px-3.5">ISHAM Membership</th>
                                <td class="fw-semibold text-dark py-3 px-3.5">
                                    <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 me-1.5">Member</span> 
                                    ID: <strong class="font-monospace">{{ $delegate->isham_membership_no }}</strong>
                                </td>
                            </tr>
                            @endif
                            @if($delegate->is_young_isam_member || $delegate->young_isam_membership_no)
                            <tr>
                                <th class="text-muted fw-semibold py-3 px-3.5">Young ISAM Membership</th>
                                <td class="fw-semibold text-dark py-3 px-3.5">
                                    <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 me-1.5">Member</span> 
                                    ID: <strong class="font-monospace">{{ $delegate->young_isam_membership_no }}</strong>
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
            <div class="card mb-4 shadow-sm border-0 rounded-3 overflow-hidden">
                <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between">
                    <h6 class="mb-0 fw-bold text-dark d-flex align-items-center gap-2">
                        <i class="bx bx-calculator text-primary fs-5"></i>Financial &amp; Payment Summary
                    </h6>
                </div>
                <div class="card-body p-3.5">
                    <div class="d-flex justify-content-between align-items-center mb-3 p-2.5 rounded-2 bg-light border">
                        <span class="text-muted extra-small fw-semibold">Transaction Reference ID</span>
                        <span class="fw-bold font-monospace text-dark extra-small">{{ $delegate->latestPayment?->transaction_id ?? 'N/A' }}</span>
                    </div>

                    @if($delegate->latestPayment && $delegate->latestPayment->payment_receipt_path)
                    <div class="d-flex justify-content-between align-items-center mb-3 p-2.5 rounded-2 bg-light border">
                        <span class="extra-small fw-semibold text-dark"><i class="bx bx-file text-primary me-1"></i>Payment Screenshot</span>
                        <a href="{{ asset('storage/' . $delegate->latestPayment->payment_receipt_path) }}" target="_blank" class="btn btn-xs btn-outline-primary py-1 px-3 fw-bold rounded-pill">
                            <i class="bx bx-show me-1"></i>View Screenshot
                        </a>
                    </div>
                    @endif

                    <hr class="my-2.5 text-muted opacity-25">

                    <div class="d-flex justify-content-between mb-2 extra-small">
                        <span class="text-muted">Delegate Category Base Fee</span>
                        <span class="fw-semibold text-dark">{{ $delegate->delegate_type == 'International' ? '$' : '₹' }}{{ number_format($delegate->delegate_fee, 2) }}</span>
                    </div>

                    @if($delegate->participate_in_cme)
                    <div class="d-flex justify-content-between mb-2 extra-small">
                        <span class="text-muted">Pre-Conference Workshop Fee</span>
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

                    <hr class="my-3 text-muted opacity-50">

                    <div class="d-flex justify-content-between align-items-center p-3 rounded-3" style="background-color: #F8FAFC; border: 1px solid #E2E8F0;">
                        <div>
                            <span class="text-muted extra-small d-block">Total Payable Amount</span>
                            <strong class="text-dark small">Incl. All Taxes &amp; GST</strong>
                        </div>
                        <h4 class="text-primary mb-0 fw-extrabold" style="letter-spacing: -0.5px;">
                            {{ $delegate->delegate_type == 'International' ? '$' : '₹' }}{{ number_format($delegate->total_amount, 2) }}
                        </h4>
                    </div>
                </div>
            </div>

            <!-- Admin Action Control Card -->
            <div class="card shadow-sm border-0 mb-4 rounded-3 overflow-hidden">
                <div class="card-header bg-white py-3 border-bottom">
                    <h6 class="mb-0 fw-bold text-dark d-flex align-items-center gap-2">
                        <i class="bx bx-slider-alt text-success fs-5"></i>Admin Control &amp; Actions
                    </h6>
                </div>
                <div class="card-body p-3.5">
                    <div class="d-grid gap-2.5">
                        @if($delegate->status === 'Payment Submitted')
                        <form action="{{ route('student-approved-regis') }}" method="POST" class="d-grid m-0">
                            @csrf
                            <input type="hidden" name="registration_number" value="{{ $delegate->registration_number ?? ($delegate->acknowledgement_id ?? $delegate->id) }}">
                            <button type="submit" class="btn btn-sm btn-success fw-bold py-2.5 rounded-2 shadow-xs d-flex align-items-center justify-content-center gap-1.5" style="font-size: 0.85rem;" onclick="return confirm('Are you sure you want to approve this registration?')">
                                <i class="bx bx-check-circle fs-5"></i> Approve Registration
                            </button>
                        </form>
                        @elseif($delegate->status === 'Approved')
                        <div class="p-3 mb-1 rounded-3 text-center border fw-semibold d-flex align-items-center justify-content-center gap-2 shadow-xs" style="background-color: #DCFCE7 !important; color: #065F46 !important; border-color: #86EFAC !important; font-size: 0.88rem;">
                            <i class="bx bx-check-double fs-4" style="color: #059669 !important;"></i>
                            <span>Registration Status: <strong>Approved</strong></span>
                        </div>
                        @else
                        <div class="p-2.5 mb-1 rounded-2 text-center border text-muted extra-small bg-light">
                            <i class="bx bx-info-circle me-1 text-primary"></i>Approve button will appear once delegate submits payment.
                        </div>
                        @endif

                        <div class="d-flex gap-2">
                            <button type="button" class="btn btn-sm btn-warning text-dark fw-bold w-50 py-2 rounded-2 shadow-xs" style="font-size: 0.8rem;" 
                                @if($delegate->status !== 'Approved') data-bs-toggle="modal" data-bs-target="#revertModal" @else disabled title="Disabled for Approved delegates" @endif>
                                <i class="bx bx-undo me-1"></i> Revert
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-danger fw-bold w-50 py-2 rounded-2" style="font-size: 0.8rem;" 
                                @if($delegate->status !== 'Approved') data-bs-toggle="modal" data-bs-target="#rejectModal" @else disabled title="Disabled for Approved delegates" @endif>
                                <i class="bx bx-x-circle me-1"></i> Reject
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Documents & Receipt Downloads Card -->
            <div class="card shadow-sm border-0 mb-4 rounded-3 overflow-hidden">
                <div class="card-header bg-white py-3 border-bottom">
                    <h6 class="mb-0 fw-bold text-dark d-flex align-items-center gap-2">
                        <i class="bx bx-download text-primary fs-5"></i>Documents &amp; Downloads
                    </h6>
                </div>
                <div class="card-body p-3.5">
                    @if($delegate->status === 'Approved')
                    <a href="{{ route('download.receipt', $delegate->registration_number ?? ($delegate->acknowledgement_id ?? $delegate->id)) }}"
                        target="_blank"
                        class="btn btn-sm btn-success w-100 py-2.5 fw-bold rounded-2 shadow-xs d-flex align-items-center justify-content-center gap-1.5" style="font-size: 0.825rem;">
                        <i class="bx bxs-file-pdf fs-5"></i>Download Receipt PDF
                    </a>
                    @else
                    <button class="btn btn-sm btn-secondary w-100 py-2.5 fw-bold rounded-2 opacity-75 d-flex align-items-center justify-content-center gap-1.5" style="font-size: 0.825rem;" disabled title="Receipt download is available after approval">
                        <i class="bx bxs-file-pdf fs-5"></i>Download Receipt PDF (Pending Approval)
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