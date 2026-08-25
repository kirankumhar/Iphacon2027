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
                            @php
                                $profilePhotoUrl = $delegate->photo_path ? asset('storage/' . $delegate->photo_path) : asset('images/default-avatar.svg');
                            @endphp
                            <div class="avatar avatar-xl flex-shrink-0 position-relative" style="cursor: pointer;" onclick="openMediaLightbox('{{ $profilePhotoUrl }}', 'image', 'Profile Photo - {{ $delegate->user?->full_name }}')" title="Click to view full photo">
                                <img src="{{ $profilePhotoUrl }}"
                                    alt="Delegate Photo" class="w-100 h-100 rounded-circle border border-3 border-white shadow-sm" style="object-fit: cover; width: 68px; height: 68px;"
                                    onerror="this.onerror=null; this.src='{{ asset('images/default-avatar.svg') }}';" />
                                <span class="position-absolute bottom-0 end-0 bg-primary text-white rounded-circle d-flex align-items-center justify-content-center border border-white" style="width: 22px; height: 22px; font-size: 0.65rem;">
                                    <i class="bx bx-search"></i>
                                </span>
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
                                    <div class="mb-1.5 d-flex align-items-center gap-2">
                                        <span class="badge bg-light text-primary border px-2.5 py-1">{{ $delegate->id_proof_type ?? 'ID Proof' }}</span>
                                        <strong class="font-monospace text-dark">{{ $delegate->masked_id_proof_number }}</strong>
                                    </div>
                                    @if($delegate->id_proof_document_path)
                                        @php
                                            $idDocUrl = asset('storage/' . $delegate->id_proof_document_path);
                                            $isPdf = str_ends_with(strtolower($delegate->id_proof_document_path), '.pdf');
                                        @endphp
                                        <div class="d-flex align-items-center gap-1">
                                            <button type="button" class="btn btn-xs btn-outline-primary rounded-pill px-3 py-1 fw-bold d-inline-flex align-items-center gap-1"
                                                onclick="openMediaLightbox('{{ $idDocUrl }}', '{{ $isPdf ? 'pdf' : 'image' }}', 'ID Proof Document - {{ $delegate->id_proof_type }}')">
                                                <i class="bx bx-show me-0.5"></i> View ID Document (Lightbox)
                                            </button>
                                            <a href="{{ $idDocUrl }}" target="_blank" class="btn btn-xs btn-light text-secondary rounded-pill px-2.5 py-1 fw-semibold" title="Open in new tab">
                                                <i class="bx bx-export"></i>
                                            </a>
                                        </div>
                                    @else
                                        <span class="text-danger extra-small">No document uploaded</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th class="text-muted fw-semibold py-3 px-3.5">Profile Photo</th>
                                <td class="py-3 px-3.5">
                                    @if($delegate->photo_path)
                                        @php
                                            $photoUrl = asset('storage/' . $delegate->photo_path);
                                        @endphp
                                        <div class="d-flex align-items-center gap-1">
                                            <button type="button" class="btn btn-xs btn-outline-success rounded-pill px-3 py-1 fw-bold d-inline-flex align-items-center gap-1"
                                                onclick="openMediaLightbox('{{ $photoUrl }}', 'image', 'Profile Photo - {{ $delegate->user?->full_name }}')">
                                                <i class="bx bx-image me-0.5"></i> View Profile Photo (Lightbox)
                                            </button>
                                            <a href="{{ $photoUrl }}" target="_blank" class="btn btn-xs btn-light text-secondary rounded-pill px-2.5 py-1 fw-semibold" title="Open in new tab">
                                                <i class="bx bx-export"></i>
                                            </a>
                                        </div>
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
                    @php
                        $receiptUrl = asset('storage/' . $delegate->latestPayment->payment_receipt_path);
                        $isReceiptPdf = str_ends_with(strtolower($delegate->latestPayment->payment_receipt_path), '.pdf');
                    @endphp
                    <div class="d-flex justify-content-between align-items-center mb-3 p-2.5 rounded-2 bg-light border">
                        <span class="extra-small fw-semibold text-dark"><i class="bx bx-file text-primary me-1"></i>Payment Screenshot</span>
                        <div class="d-flex align-items-center gap-1">
                            <button type="button" class="btn btn-xs btn-outline-primary py-1 px-3 fw-bold rounded-pill"
                                onclick="openMediaLightbox('{{ $receiptUrl }}', '{{ $isReceiptPdf ? 'pdf' : 'image' }}', 'Payment Receipt - Txn #{{ $delegate->latestPayment->transaction_id }}')">
                                <i class="bx bx-show me-1"></i>View Lightbox
                            </button>
                            <a href="{{ $receiptUrl }}" target="_blank" class="btn btn-xs btn-light text-secondary rounded-pill px-2.5 py-1 fw-semibold" title="Open in new tab">
                                <i class="bx bx-export"></i>
                            </a>
                        </div>
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
                <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between">
                    <h6 class="mb-0 fw-bold text-dark d-flex align-items-center gap-2">
                        <i class="bx bx-shield-quarter text-primary fs-5"></i>Admin Control &amp; Actions
                    </h6>
                    @if($delegate->status === 'Approved')
                        <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 rounded-pill px-2.5 py-1 extra-small fw-bold">
                            <i class="bx bx-check me-1"></i>Approved
                        </span>
                    @elseif($delegate->status === 'Rejected')
                        <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 rounded-pill px-2.5 py-1 extra-small fw-bold">
                            <i class="bx bx-x me-1"></i>Rejected
                        </span>
                    @else
                        <span class="badge bg-warning bg-opacity-10 text-warning border border-warning border-opacity-25 rounded-pill px-2.5 py-1 extra-small fw-bold">
                            <i class="bx bx-time-five me-1"></i>{{ $delegate->status }}
                        </span>
                    @endif
                </div>
                <div class="card-body p-3.5">
                    <div class="d-flex flex-column gap-3">
                        @php
                            $hasPaymentProof = !empty($delegate->latestPayment?->payment_receipt_path) || !empty($delegate->latestPayment?->transaction_id);
                            $isPaymentSubmitted = in_array($delegate->status, ['Payment Submitted', 'Submitted']) || ($hasPaymentProof && !in_array($delegate->status, ['Pending Payment', 'Draft', 'Incomplete', 'Rejected']));
                            $isPaymentPending = in_array($delegate->status, ['Pending Payment', 'Pending', 'Draft', 'Incomplete']) || (!$hasPaymentProof && $delegate->status !== 'Approved' && $delegate->status !== 'Rejected');
                        @endphp

                        <!-- Primary Approval / Status Callout -->
                        @if($delegate->status === 'Approved')
                        <div class="p-3 rounded-3 border d-flex align-items-center gap-3" style="background-color: #ECFDF5; border-color: #A7F3D0 !important;">
                            <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width: 40px; height: 40px; background-color: #10B981; color: #FFFFFF;">
                                <i class="bx bx-check-double fs-4"></i>
                            </div>
                            <div class="flex-grow-1">
                                <div class="fw-bold text-dark" style="font-size: 0.88rem; color: #065F46 !important;">Registration Approved</div>
                                <div class="text-muted extra-small">Reg No: <strong class="font-monospace text-dark">{{ $delegate->registration_number ?? 'Assigned' }}</strong></div>
                            </div>
                        </div>
                        @elseif($isPaymentSubmitted && $delegate->status !== 'Rejected')
                        <div class="p-3 rounded-3 border bg-light">
                            <div class="d-flex align-items-start gap-2.5 mb-2.5">
                                <i class="bx bx-info-circle text-primary fs-5 mt-0.5"></i>
                                <div>
                                    <div class="fw-bold text-dark extra-small">Ready for Approval</div>
                                    <div class="text-muted extra-small">Payment submitted. Verify receipt details before approving.</div>
                                </div>
                            </div>
                            <form action="{{ route('student-approved-regis') }}" method="POST" class="d-grid m-0">
                                @csrf
                                <input type="hidden" name="registration_number" value="{{ $delegate->registration_number ?? ($delegate->acknowledgement_id ?? $delegate->id) }}">
                                <button type="submit" class="btn btn-success fw-bold py-2.5 rounded-3 shadow-xs d-flex align-items-center justify-content-center gap-2" style="background: linear-gradient(135deg, #10B981 0%, #059669 100%); border: none; font-size: 0.85rem;" onclick="return confirm('Are you sure you want to approve this registration and issue registration number?')">
                                    <i class="bx bx-check-circle fs-5"></i> Approve Registration
                                </button>
                            </form>
                        </div>
                        @elseif($delegate->status === 'Rejected')
                        <div class="p-3 rounded-3 border d-flex align-items-center gap-3" style="background-color: #FEF2F2; border-color: #FECACA !important;">
                            <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width: 40px; height: 40px; background-color: #EF4444; color: #FFFFFF;">
                                <i class="bx bx-x fs-4"></i>
                            </div>
                            <div class="flex-grow-1">
                                <div class="fw-bold text-danger" style="font-size: 0.88rem;">Registration Rejected</div>
                                <div class="text-muted extra-small">This application has been marked as rejected.</div>
                            </div>
                        </div>
                        @else
                        <!-- Payment Pending State -->
                        <div class="p-3 rounded-3 border bg-light">
                            <div class="d-flex align-items-start gap-2.5 mb-2.5">
                                <i class="bx bx-time-five text-warning fs-5 mt-0.5"></i>
                                <div>
                                    <div class="fw-bold text-dark extra-small">Payment Pending</div>
                                    <div class="text-muted extra-small">Delegate has not submitted payment. Approval is disabled until payment is received.</div>
                                </div>
                            </div>
                            <button type="button" class="btn btn-secondary w-100 py-2.5 rounded-3 fw-bold d-flex align-items-center justify-content-center gap-2 opacity-75" disabled style="cursor: not-allowed; font-size: 0.85rem;" title="Approve is disabled because payment is pending">
                                <i class="bx bx-lock-alt fs-5"></i> Approve Registration (Disabled)
                            </button>
                        </div>
                        @endif

                        <!-- Action Buttons Section -->
                        <div class="d-flex flex-column gap-2">
                            @if($delegate->status !== 'Approved')
                                @if(!empty($reminderSentTime))
                                    <!-- Reminder Sent Today Button -->
                                    <button type="button" class="btn btn-light border text-muted w-100 py-2.5 rounded-3 fw-semibold d-flex align-items-center justify-content-between px-3 shadow-2xs" data-bs-toggle="modal" data-bs-target="#paymentReminderModal" style="font-size: 0.825rem;" title="Reminder already sent today at {{ $reminderSentTime }}">
                                        <span class="d-flex align-items-center gap-2">
                                            <i class="bx bx-check-double fs-5 text-success"></i>
                                            <span>Reminder Sent Today ({{ $reminderSentTime }})</span>
                                        </span>
                                        <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 extra-small">Sent</span>
                                    </button>
                                @else
                                    <!-- Send Payment Reminder Button -->
                                    <button type="button" class="btn btn-outline-warning text-dark w-100 py-2.5 rounded-3 fw-semibold d-flex align-items-center justify-content-between px-3 shadow-2xs" data-bs-toggle="modal" data-bs-target="#paymentReminderModal" style="font-size: 0.825rem;">
                                        <span class="d-flex align-items-center gap-2">
                                            <i class="bx bx-bell fs-5 text-warning"></i>
                                            <span>Send Payment Reminder</span>
                                        </span>
                                        <i class="bx bx-chevron-right text-muted"></i>
                                    </button>
                                @endif
                            @endif

                            <!-- Send / Resend Confirmation Email Button -->
                            <button type="button" class="btn btn-outline-primary w-100 py-2.5 rounded-3 fw-semibold d-flex align-items-center justify-content-between px-3 shadow-2xs" data-bs-toggle="modal" data-bs-target="#resendEmailModal" style="font-size: 0.825rem;">
                                <span class="d-flex align-items-center gap-2">
                                    <i class="bx bx-envelope fs-5 text-primary"></i>
                                    <span>Send Confirmation Email</span>
                                </span>
                                <i class="bx bx-chevron-right text-muted"></i>
                            </button>

                            <!-- Reject Button -->
                            <button type="button" class="btn btn-outline-danger w-100 py-2.5 rounded-3 fw-semibold d-flex align-items-center justify-content-between px-3" style="font-size: 0.825rem;"
                                @if($delegate->status !== 'Approved') data-bs-toggle="modal" data-bs-target="#rejectModal" @else disabled title="Disabled for Approved delegates" @endif>
                                <span class="d-flex align-items-center gap-2">
                                    <i class="bx bx-x-circle fs-5 text-danger"></i>
                                    <span>Reject Registration</span>
                                </span>
                                <i class="bx bx-chevron-right text-muted"></i>
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
                        class="btn btn-primary w-100 py-2.5 fw-bold rounded-3 shadow-xs d-flex align-items-center justify-content-center gap-2" style="background: linear-gradient(135deg, #2563EB 0%, #1D4ED8 100%); border: none; font-size: 0.825rem;">
                        <i class="bx bxs-file-pdf fs-5"></i>Download Receipt PDF
                    </a>
                    @else
                    <button class="btn btn-light border text-muted w-100 py-2.5 fw-semibold rounded-3 d-flex align-items-center justify-content-center gap-2" style="font-size: 0.825rem;" disabled title="Receipt download is available after approval">
                        <i class="bx bxs-file-pdf fs-5 text-muted"></i>Download Receipt PDF (Pending Approval)
                    </button>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Reject Registration Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-3">
            <form action="{{ route('student-reject-regis') }}" method="POST">
                @csrf
                <input type="hidden" name="registration_id" value="{{ $delegate->id }}">
                <input type="hidden" name="acknowledgement_id" value="{{ $delegate->acknowledgement_id }}">
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

<!-- Send / Resend Email Modal -->
<div class="modal fade" id="resendEmailModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-3">
            <form action="{{ route('admin.resend-submission-email') }}" method="POST">
                @csrf
                <input type="hidden" name="registration_id" value="{{ $delegate->id }}">
                <input type="hidden" name="acknowledgement_id" value="{{ $delegate->acknowledgement_id }}">
                <input type="hidden" name="registration_number" value="{{ $delegate->registration_number }}">
                <div class="modal-header bg-primary bg-opacity-10 py-3 border-bottom-0">
                    <div class="d-flex align-items-center gap-2">
                        <div class="avatar avatar-sm bg-primary text-white rounded-circle d-flex align-items-center justify-content-center">
                            <i class="bx bx-envelope fs-5"></i>
                        </div>
                        <h5 class="modal-title fw-bold text-dark mb-0">Send Registration Email</h5>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body py-3">
                    <div class="alert alert-primary bg-light border-primary border-opacity-25 extra-small mb-3 d-flex align-items-start gap-2">
                        <i class="bx bx-info-circle fs-5 flex-shrink-0 mt-0.5 text-primary"></i>
                        <div>
                            Send confirmation email directly to the delegate. If the delegate did not receive it or provided an incorrect email, you can specify the target email below.
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold extra-small text-dark">Select Email Template to Send <span class="text-danger">*</span></label>
                        <select name="email_type" class="form-select form-select-sm">
                            <option value="submission" selected>Submission Confirmation Email (Ack ID &amp; Payment Summary)</option>
                            @if($delegate->status === 'Approved')
                                <option value="approval">Approval Confirmation Email (With Reg No &amp; PDF Receipt)</option>
                            @endif
                        </select>
                    </div>

                    <div class="mb-2">
                        <label class="form-label fw-semibold extra-small text-dark">Recipient Email Address <span class="text-danger">*</span></label>
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-light"><i class="bx bx-envelope text-muted"></i></span>
                            <input type="email" name="email" class="form-control form-control-sm" value="{{ $delegate->user?->email }}" required placeholder="Enter delegate email address">
                        </div>
                        <small class="text-muted extra-small mt-1 d-block">You can edit or enter the email address if needed.</small>
                    </div>
                </div>
                <div class="modal-footer border-top-0 pt-0">
                    <button type="button" class="btn btn-sm btn-label-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-sm btn-primary fw-bold px-3 shadow-xs">
                        <i class="bx bx-send me-1"></i> Send Email Now
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Send Payment Reminder Modal -->
<div class="modal fade" id="paymentReminderModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-3">
            <form action="{{ route('admin.send-payment-reminder') }}" method="POST">
                @csrf
                <input type="hidden" name="registration_id" value="{{ $delegate->id }}">
                <input type="hidden" name="acknowledgement_id" value="{{ $delegate->acknowledgement_id }}">

                <div class="modal-header bg-warning bg-opacity-15 py-3 border-bottom">
                    <div class="d-flex align-items-center gap-2">
                        <div class="avatar avatar-sm bg-warning text-dark rounded-circle d-flex align-items-center justify-content-center fw-bold">
                            <i class="bx bx-bell fs-5"></i>
                        </div>
                        <div>
                            <h5 class="modal-title fw-bold text-dark mb-0 fs-6">Send Payment Reminder</h5>
                            <span class="extra-small text-muted">Notify delegate to complete pending fee payment</span>
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body py-3.5">
                    @if(!empty($reminderSentTime))
                    <div class="alert alert-warning border-warning" role="alert">
                        <div class="d-flex align-items-start gap-2">
                            <i class="bx bx-error-circle fs-5 text-warning flex-shrink-0 mt-0.5"></i>
                            <div class="extra-small">
                                <strong>Reminder Already Sent Today:</strong> An email was already sent to this delegate today at <strong>{{ $reminderSentTime }}</strong>. As per policy, only <strong>1 email per user per day</strong> is permitted.
                            </div>
                        </div>
                    </div>
                    @endif

                    <div class="p-3 bg-light rounded-3 mb-3 border">
                        <div class="d-flex align-items-center justify-content-between mb-1">
                            <span class="extra-small text-muted">Delegate:</span>
                            <span class="fw-bold text-dark extra-small">{{ $delegate->user?->prefix }} {{ $delegate->user?->full_name ?? 'Delegate' }}</span>
                        </div>
                        <div class="d-flex align-items-center justify-content-between mb-1">
                            <span class="extra-small text-muted">Ack ID:</span>
                            <span class="badge bg-secondary font-monospace extra-small">{{ $delegate->acknowledgement_id ?? 'N/A' }}</span>
                        </div>
                        <div class="d-flex align-items-center justify-content-between">
                            <span class="extra-small text-muted">Pending Amount:</span>
                            <span class="fw-bold text-danger extra-small">₹{{ number_format($delegate->total_amount, 2) }}</span>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold extra-small text-dark">Recipient Email Address <span class="text-danger">*</span></label>
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-light"><i class="bx bx-envelope text-muted"></i></span>
                            <input type="email" name="email" class="form-control" value="{{ $delegate->user?->email }}" required placeholder="Enter delegate email address">
                        </div>
                        <small class="text-muted extra-small mt-1 d-block">The payment reminder email will be delivered to this address.</small>
                    </div>

                    <div class="mb-2">
                        <label class="form-label fw-semibold extra-small text-dark">Custom Note / Remark <span class="text-muted fw-normal">(Optional)</span></label>
                        <textarea name="custom_message" class="form-control form-control-sm" rows="2" placeholder="e.g. Kindly complete your pending payment to secure early bird rates."></textarea>
                        <small class="text-muted extra-small mt-1 d-block">This note will appear prominently in the reminder email.</small>
                    </div>
                </div>

                <div class="modal-footer bg-light border-top py-2.5">
                    <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    @if(!empty($reminderSentTime))
                        <button type="button" class="btn btn-sm btn-secondary opacity-50 px-3 shadow-xs" disabled>
                            <i class="bx bx-block me-1"></i> Already Sent Today ({{ $reminderSentTime }})
                        </button>
                    @else
                        <button type="submit" class="btn btn-sm btn-warning text-dark fw-bold px-3 shadow-xs">
                            <i class="bx bx-send me-1"></i> Send Reminder Email
                        </button>
                    @endif
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Media Lightbox Modal -->
<div class="modal fade" id="mediaLightboxModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content border-0 shadow-lg" style="background-color: #0F172A; color: #ffffff; border-radius: 16px;">
            <div class="modal-header border-bottom border-secondary border-opacity-25 py-3 px-4">
                <div class="d-flex align-items-center gap-2">
                    <i class="bx bx-image-alt text-primary fs-4"></i>
                    <h5 class="modal-title fw-bold text-white mb-0" id="lightboxTitle">Media Lightbox</h5>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <a id="lightboxDownloadBtn" href="#" target="_blank" class="btn btn-sm btn-outline-light rounded-pill px-3 py-1 extra-small fw-semibold">
                        <i class="bx bx-export me-1"></i>Open Full Size
                    </a>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
            </div>
            <div class="modal-body p-4 text-center overflow-auto position-relative" style="min-height: 420px; max-height: 75vh; display: flex; align-items: center; justify-content: center; background-color: #020617;">
                <!-- Image Viewer -->
                <img id="lightboxImage" src="" alt="Media Preview" class="img-fluid rounded shadow-sm d-none" style="max-height: 70vh; object-fit: contain; transition: transform 0.2s ease;">
                
                <!-- PDF Viewer -->
                <iframe id="lightboxPdf" src="" class="w-100 rounded d-none" style="height: 70vh; border: none;"></iframe>
            </div>
            <div class="modal-footer border-top border-secondary border-opacity-25 py-2.5 px-4 justify-content-between">
                <div class="d-flex align-items-center gap-2" id="imageControls">
                    <button type="button" class="btn btn-xs btn-outline-light" onclick="zoomLightboxImage(1.25)" title="Zoom In"><i class="bx bx-zoom-in me-1"></i> Zoom In</button>
                    <button type="button" class="btn btn-xs btn-outline-light" onclick="zoomLightboxImage(0.8)" title="Zoom Out"><i class="bx bx-zoom-out me-1"></i> Zoom Out</button>
                    <button type="button" class="btn btn-xs btn-outline-light" onclick="resetLightboxImage()" title="Reset Zoom"><i class="bx bx-reset me-1"></i> Reset</button>
                </div>
                <button type="button" class="btn btn-sm btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
let currentZoom = 1;

function openMediaLightbox(url, type, title) {
    const modalElement = document.getElementById('mediaLightboxModal');
    const lightboxTitle = document.getElementById('lightboxTitle');
    const lightboxImage = document.getElementById('lightboxImage');
    const lightboxPdf = document.getElementById('lightboxPdf');
    const lightboxDownloadBtn = document.getElementById('lightboxDownloadBtn');
    const imageControls = document.getElementById('imageControls');

    if (!url || !modalElement) return;

    if (lightboxTitle) lightboxTitle.innerText = title || 'Document Preview';
    if (lightboxDownloadBtn) lightboxDownloadBtn.href = url;
    currentZoom = 1;
    if (lightboxImage) lightboxImage.style.transform = 'scale(1)';

    if (type === 'pdf' || url.toLowerCase().endsWith('.pdf')) {
        if (lightboxImage) lightboxImage.classList.add('d-none');
        if (lightboxPdf) {
            lightboxPdf.classList.remove('d-none');
            lightboxPdf.src = url;
        }
        if (imageControls) imageControls.classList.add('d-none');
    } else {
        if (lightboxPdf) lightboxPdf.classList.add('d-none');
        if (lightboxImage) {
            lightboxImage.classList.remove('d-none');
            lightboxImage.src = url;
        }
        if (imageControls) imageControls.classList.remove('d-none');
    }

    if (window.bootstrap && bootstrap.Modal) {
        let modal = bootstrap.Modal.getInstance(modalElement);
        if (!modal) {
            modal = new bootstrap.Modal(modalElement);
        }
        modal.show();
    } else if (window.jQuery && $.fn.modal) {
        $(modalElement).modal('show');
    }
}

function zoomLightboxImage(factor) {
    const lightboxImage = document.getElementById('lightboxImage');
    if (!lightboxImage) return;
    currentZoom *= factor;
    currentZoom = Math.max(0.5, Math.min(4, currentZoom));
    lightboxImage.style.transform = `scale(${currentZoom})`;
}

function resetLightboxImage() {
    const lightboxImage = document.getElementById('lightboxImage');
    if (!lightboxImage) return;
    currentZoom = 1;
    lightboxImage.style.transform = 'scale(1)';
}
</script>
@endpush
@endsection