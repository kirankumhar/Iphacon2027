@extends('shared.auth-delegate')
@section('title', 'Registration Details')

@section('delegate-content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-11 col-xl-10">

            {{-- Top Header / Banner Card --}}
            <div class="card border-0 shadow-sm mb-4" style="border-radius: 20px; overflow: hidden; background: linear-gradient(135deg, #0288D1 0%, #1A52E0 50%, #4BAA7D 100%);">
                <div class="card-body p-4 p-md-4.5 text-white position-relative">
                    <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
                        <div class="d-flex align-items-center gap-3.5">
                            @if($registration->photo_path)
                                <img src="{{ asset('storage/' . $registration->photo_path) }}" alt="Delegate Photo" class="rounded-circle border border-4 border-white shadow" style="width: 85px; height: 85px; object-fit: cover;">
                            @else
                                <div class="rounded-circle bg-white bg-opacity-25 d-flex align-items-center justify-content-center text-white fw-bold shadow" style="width: 85px; height: 85px; font-size: 2.2rem; border: 3px solid #ffffff;">
                                    {{ strtoupper(substr($registration->user->full_name ?? 'U', 0, 1)) }}
                                </div>
                            @endif
                            <div>
                                <span class="badge bg-white text-primary fw-bold mb-1 px-3 py-1.5 rounded-pill shadow-xs" style="font-size: 0.8rem;">
                                    <i class="fas fa-award text-warning me-1"></i> {{ $registration->delegate_type }} Delegate
                                </span>
                                <h2 class="fw-bold mb-1 text-white" style="letter-spacing: -0.5px; font-size: 1.6rem;">
                                    {{ $registration->user->prefix }} {{ $registration->user->full_name }}
                                </h2>
                                <div class="text-white opacity-90 small d-flex flex-wrap gap-3 mt-1.5" style="font-size: 0.85rem;">
                                    @if($registration->status === 'Approved')
                                        <span><i class="fas fa-id-badge text-warning me-1"></i>Reg No: <strong>{{ $registration->registration_number }}</strong></span>
                                    @else
                                        <span><i class="fas fa-receipt text-warning me-1"></i>Acknowledgement No: <strong>{{ $registration->acknowledgement_id ?? 'N/A' }}</strong></span>
                                    @endif
                                    <span><i class="fas fa-envelope text-warning me-1"></i>{{ $registration->user->email }}</span>
                                    <span><i class="fas fa-calendar-alt text-warning me-1"></i>Registered: {{ $registration->created_at ? $registration->created_at->format('d M, Y') : 'N/A' }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex flex-wrap align-items-center gap-2.5">
                            @php
                                $statusBg = '#E0F2FE';
                                $statusFg = '#0288D1';
                                $statusIcon = 'fa-clock';
                                $statusText = $registration->status;

                                if ($registration->status === 'Approved') {
                                    $statusBg = '#DCFFF0';
                                    $statusFg = '#4BAA7D';
                                    $statusIcon = 'fa-check-circle';
                                    $statusText = 'Approved';
                                } elseif ($registration->status === 'Rejected') {
                                    $statusBg = '#FFE2E2';
                                    $statusFg = '#DC2626';
                                    $statusIcon = 'fa-times-circle';
                                    $statusText = 'Rejected';
                                } elseif (in_array($registration->status, ['Payment Submitted', 'Submitted', 'Pending Payment', 'Pending'])) {
                                    $statusBg = '#FEF9C3';
                                    $statusFg = '#CA8A04';
                                    $statusIcon = 'fa-hourglass-half';
                                    $statusText = 'Pending for Verification';
                                }
                            @endphp
                            <span class="badge px-4 py-2.5 fw-bold shadow-xs" style="font-size: 0.9rem; border-radius: 30px; background-color: {{ $statusBg }}; color: {{ $statusFg }};">
                                <i class="fas {{ $statusIcon }} me-1.5"></i> {{ $statusText }}
                            </span>

                            @if($registration->status === 'Approved')
                                <a href="{{ route('delgate.download.receipt', $registration->registration_number) }}" class="btn btn-warning btn-sm px-4 py-2.5 fw-bold shadow-sm rounded-pill text-dark">
                                    <i class="fas fa-file-download me-1.5"></i>Download Receipt
                                </a>
                            @elseif($registration->status === 'Draft')
                                <a href="{{ route('registration.create') }}" class="btn btn-light btn-sm px-4 py-2.5 fw-bold text-primary shadow-sm rounded-pill">
                                    <i class="fas fa-edit me-1.5"></i>Continue Registration
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- Status Alerts (Reverted / Rejected) --}}
            @if($registration->status == 'Rejected' && $registration->rejection_reason)
                <div class="alert alert-danger border-0 shadow-sm rounded-4 mb-4 p-3.5 d-flex align-items-start gap-3">
                    <i class="fas fa-exclamation-triangle fs-4 text-danger mt-1"></i>
                    <div>
                        <h6 class="fw-bold mb-1">Registration Rejected</h6>
                        <p class="mb-0 small">{{ $registration->rejection_reason }}</p>
                    </div>
                </div>
            @endif

            @if(!empty($registration->revert_reason) && ($registration->status === 'Draft' || $registration->status === 'Reverted' || !empty($registration->reverted_at)))
                <div class="alert alert-warning border-0 shadow-sm rounded-4 mb-4 p-4 d-flex align-items-start gap-3" style="background: #FFFBEB; border-left: 5px solid #F59E0B !important;">
                    <i class="fas fa-exclamation-triangle fs-4 text-warning mt-1"></i>
                    <div>
                        <h6 class="fw-bold text-dark mb-1">Registration Application Reverted for Modification</h6>
                        <p class="mb-2 text-dark font-medium">{{ $registration->revert_reason }}</p>
                        <a href="{{ route('registration.create') }}" class="btn btn-sm btn-warning text-dark fw-bold px-3.5 py-1.5 rounded-pill shadow-xs">
                            <i class="fas fa-edit me-1"></i>Update &amp; Resubmit Application Now
                        </a>
                    </div>
                </div>
            @endif

            <div class="row g-4">
                {{-- Left Column: User Profile & Contact Info --}}
                <div class="col-lg-6">

                    {{-- Personal Information Card --}}
                    <div class="card border-0 shadow-sm h-100" style="border-radius: 16px; border: 1px solid #E2E8F0 !important; border-top: 4px solid #0288D1 !important;">
                        <div class="card-header bg-white py-3.5 px-4 border-bottom-0 d-flex align-items-center gap-3">
                            <div class="rounded-circle p-2 text-primary d-inline-flex align-items-center justify-content-center" style="width: 40px; height: 40px; background-color: #E0F2FE;">
                                <i class="fas fa-user fs-6"></i>
                            </div>
                            <h6 class="fw-bold mb-0 text-dark fs-6">Personal Information</h6>
                        </div>
                        <div class="card-body px-4 pt-1 pb-4">
                            <div class="row g-3 small">
                                <div class="col-sm-6">
                                    <span class="text-muted d-block fw-bold" style="font-size: 0.75rem;">Full Name</span>
                                    <span class="fw-semibold text-dark fs-6">{{ $registration->user->prefix }} {{ $registration->user->full_name }}</span>
                                </div>
                                <div class="col-sm-6">
                                    <span class="text-muted d-block fw-bold" style="font-size: 0.75rem;">Designation</span>
                                    <span class="fw-semibold text-dark">{{ $registration->designation === 'Other' ? ($registration->other_designation ?: 'Other') : ($registration->designation ?? $registration->user->designation ?? 'N/A') }}</span>
                                </div>
                                <div class="col-sm-6">
                                    <span class="text-muted d-block fw-bold" style="font-size: 0.75rem;">Gender & DOB</span>
                                    <span class="fw-semibold text-dark">{{ $registration->user->gender ?? 'N/A' }} | {{ $registration->user->date_of_birth ? \Carbon\Carbon::parse($registration->user->date_of_birth)->format('d M, Y') : 'N/A' }}</span>
                                </div>
                                <div class="col-sm-6">
                                    <span class="text-muted d-block fw-bold" style="font-size: 0.75rem;">Email Address</span>
                                    <span class="fw-semibold text-dark">{{ $registration->user->email }}</span>
                                    @if($registration->user->hasVerifiedEmail())
                                        <span class="badge bg-success-subtle text-success border border-success-subtle ms-1 px-2 py-0.5 rounded-pill" style="font-size: 0.7rem;">Verified</span>
                                    @endif
                                </div>
                                <div class="col-sm-6">
                                    <span class="text-muted d-block fw-bold" style="font-size: 0.75rem;">Mobile / WhatsApp</span>
                                    <span class="fw-semibold text-dark">
                                        {{ $registration->user->mobile_country_code }} {{ $registration->user->mobile_number }}
                                        @if($registration->whatsapp_number && $registration->whatsapp_number != $registration->user->mobile_number)
                                            / {{ $registration->whatsapp_number }}
                                        @endif
                                    </span>
                                </div>
                            </div>

                            <hr class="my-3.5 text-muted opacity-25">

                            <div class="d-flex align-items-center gap-2.5 mb-3">
                                <div class="rounded-circle p-2 text-success d-inline-flex align-items-center justify-content-center" style="width: 36px; height: 36px; background-color: #DCFFF0;">
                                    <i class="fas fa-map-marker-alt small"></i>
                                </div>
                                <h6 class="fw-bold mb-0 text-dark small">Address & Location</h6>
                            </div>

                            <div class="row g-3 small">
                                <div class="col-sm-6">
                                    <span class="text-muted d-block fw-bold" style="font-size: 0.75rem;">Country & State</span>
                                    <span class="fw-semibold text-dark">{{ $registration->country->country_name ?? $registration->user->country->country_name ?? 'N/A' }}, {{ $registration->state->state_name ?? $registration->other_state ?? 'N/A' }}</span>
                                </div>
                                <div class="col-sm-6">
                                    <span class="text-muted d-block fw-bold" style="font-size: 0.75rem;">City & Pincode</span>
                                    <span class="fw-semibold text-dark">{{ $registration->city ?: 'N/A' }} ({{ $registration->pin_code ?: 'N/A' }})</span>
                                </div>
                                <div class="col-12">
                                    <span class="text-muted d-block fw-bold" style="font-size: 0.75rem;">Street Address</span>
                                    <span class="fw-semibold text-dark">{{ $registration->address ?: 'N/A' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                {{-- Right Column: Registration & Financial Details --}}
                <div class="col-lg-6">

                    {{-- Registration & Category Details Card --}}
                    <div class="card border-0 shadow-sm mb-4" style="border-radius: 16px; border: 1px solid #E2E8F0 !important; border-top: 4px solid #4BAA7D !important;">
                        <div class="card-header bg-white py-3.5 px-4 border-bottom-0 d-flex align-items-center gap-3">
                            <div class="rounded-circle p-2 text-success d-inline-flex align-items-center justify-content-center" style="width: 40px; height: 40px; background-color: #DCFFF0;">
                                <i class="fas fa-id-card fs-6"></i>
                            </div>
                            <h6 class="fw-bold mb-0 text-dark fs-6">Registration & Category</h6>
                        </div>
                        <div class="card-body px-4 pt-1 pb-4">
                            <div class="row g-3 small">
                                <div class="col-sm-6">
                                    <span class="text-muted d-block fw-bold" style="font-size: 0.75rem;">Delegate Type & Category</span>
                                    <span class="fw-semibold text-dark">{{ $registration->delegate_type }} - {{ $registration->delegateCategory->category_name ?? 'N/A' }}</span>
                                </div>
                                <div class="col-sm-6">
                                    <span class="text-muted d-block fw-bold" style="font-size: 0.75rem;">Dietary Preference</span>
                                    <span class="fw-semibold text-dark">{{ $registration->dietary_preference ?: 'N/A' }}</span>
                                </div>
                                <div class="col-sm-6">
                                    <span class="text-muted d-block fw-bold" style="font-size: 0.75rem;">Pre-Conference Workshop</span>
                                    @php
                                        $cmeStatus = $registration->cmeApplication?->status;
                                        $isCmeApproved = $registration->participate_in_cme || $cmeStatus === 'Approved';
                                        $isCmePending = $cmeStatus === 'Payment Submitted';
                                    @endphp
                                    @if ($isCmeApproved)
                                        <span class="fw-bold text-success"><i class="fas fa-check-circle me-1"></i>Participating (Approved)</span>
                                    @elseif ($isCmePending)
                                        <div class="d-flex align-items-center gap-2 mt-1">
                                            <span class="badge bg-warning text-dark border px-2 py-1 rounded-pill small">
                                                <i class="fas fa-hourglass-half me-1"></i>Pre-Conference Workshop Verification Pending
                                            </span>
                                        </div>
                                    @elseif ($registration->status !== 'Rejected')
                                        <div class="d-flex align-items-center gap-2 mt-1 flex-wrap">
                                            <span class="fw-semibold text-muted small">Not Registered</span>
                                            <a href="{{ route('cme.apply') }}" class="btn btn-sm btn-outline-success px-2.5 py-0.5 fw-semibold rounded-pill shadow-xs" style="font-size: 0.76rem;">
                                                <i class="fas fa-plus-circle me-1"></i>Apply for Pre-Conference Workshop
                                            </a>
                                        </div>
                                    @else
                                        <span class="fw-semibold text-muted small">Not Eligible (Registration Rejected)</span>
                                    @endif
                                </div>
                                <div class="col-sm-6">
                                    <span class="text-muted d-block fw-bold" style="font-size: 0.75rem;">Accompanying Persons</span>
                                    <span class="fw-semibold text-dark">{{ $registration->accompanying_persons ?? 0 }} Person(s)</span>
                                </div>
                                <div class="col-sm-6">
                                    <span class="text-muted d-block fw-bold" style="font-size: 0.75rem;">ID Proof Type</span>
                                    <span class="fw-semibold text-dark">{{ $registration->id_proof_type ?: 'N/A' }}</span>
                                </div>

                                @if($registration->id_proof_document_path)
                                    <div class="col-sm-6">
                                        <span class="text-muted d-block fw-bold" style="font-size: 0.75rem;">ID Document</span>
                                        <a href="{{ asset('storage/' . $registration->id_proof_document_path) }}" target="_blank" class="btn btn-sm btn-outline-primary rounded-pill px-3 py-1 mt-1" style="font-size: 0.75rem;">
                                            <i class="fas fa-external-link-alt me-1"></i>View Document
                                        </a>
                                    </div>
                                @endif
                            </div>

                            @if($registration->membership_no || $registration->is_ismm_member || $registration->is_isham_member || $registration->is_young_isam_member)
                                <hr class="my-3.5 text-muted opacity-25">
                                <div class="d-flex align-items-center gap-2.5 mb-2.5">
                                    <div class="rounded-circle p-1.5 text-warning d-inline-flex align-items-center justify-content-center" style="width: 32px; height: 32px; background-color: #FEF9C3;">
                                        <i class="fas fa-award small"></i>
                                    </div>
                                    <h6 class="fw-bold mb-0 text-dark small">Membership Details</h6>
                                </div>
                                <div class="row g-3 small">
                                    @if($registration->membership_no)
                                        <div class="col-sm-6">
                                            <span class="text-muted d-block fw-bold" style="font-size: 0.75rem;">Membership ID</span>
                                            <span class="fw-semibold text-dark">{{ $registration->membership_no }}</span>
                                        </div>
                                    @endif
                                    @if($registration->is_ismm_member)
                                        <div class="col-sm-6">
                                            <span class="text-muted d-block fw-bold" style="font-size: 0.75rem;">IPHACON Member No.</span>
                                            <span class="fw-semibold text-dark">{{ $registration->ismm_membership_no ?? 'Yes' }}</span>
                                        </div>
                                    @endif
                                    @if($registration->is_isham_member)
                                        <div class="col-sm-6">
                                            <span class="text-muted d-block fw-bold" style="font-size: 0.75rem;">ISHAM Member No.</span>
                                            <span class="fw-semibold text-dark">{{ $registration->isham_membership_no ?? 'Yes' }}</span>
                                        </div>
                                    @endif
                                    @if($registration->is_young_isam_member)
                                        <div class="col-sm-6">
                                            <span class="text-muted d-block fw-bold" style="font-size: 0.75rem;">Young ISAM Member No.</span>
                                            <span class="fw-semibold text-dark">{{ $registration->young_isam_membership_no ?? 'Yes' }}</span>
                                        </div>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Financial Summary Card --}}
                    <div class="card border-0 shadow-sm" style="border-radius: 16px; border: 1px solid #E2E8F0 !important; border-top: 4px solid #FF6B00 !important;">
                        <div class="card-header bg-white py-3.5 px-4 border-bottom-0 d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center gap-3">
                                <div class="rounded-circle p-2 text-warning d-inline-flex align-items-center justify-content-center" style="width: 40px; height: 40px; background-color: #FFF7ED;">
                                    <i class="fas fa-receipt fs-6 text-warning"></i>
                                </div>
                                <h6 class="fw-bold mb-0 text-dark fs-6">Financial Summary</h6>
                            </div>
                        </div>
                        <div class="card-body px-4 pt-1 pb-4">
                            @php
                                $currencySymbol = '₹';
                                if ($registration->delegate_type == 'International') {
                                    $catBase = 45000.00;
                                    $cmeFee = $registration->participate_in_cme ? 2000.00 : 0.00;
                                    $accFee = ($registration->accompanying_persons ?? 0) * 5000.00;
                                    $subtotalBase = $catBase + $cmeFee + $accFee;
                                    $gstAmt = round($subtotalBase * 0.18, 2);
                                    $totalAmt = round($subtotalBase + $gstAmt, 2);
                                    $delFee = $catBase;
                                } else {
                                    $catBase = $registration->delegateCategory ? (float)$registration->delegateCategory->indian_fee : 0;
                                    $cmeFee = $registration->cme_fee ?: ($registration->participate_in_cme ? 2000.00 : 0.00);
                                    $accFee = $registration->accompanying_fee ?: (($registration->accompanying_persons ?? 0) * 5000.00);
                                    $subtotalBase = $catBase + $cmeFee + $accFee;
                                    $gstAmt = $registration->gst_amount ?: round($subtotalBase * 0.18, 2);
                                    $totalAmt = $registration->total_amount ?: round($subtotalBase + $gstAmt, 2);
                                    $delFee = $registration->delegate_fee ?: $catBase;
                                }
                            @endphp
                            <div class="d-flex justify-content-between align-items-center py-2 border-bottom border-light small">
                                <span class="text-muted">Delegate Registration Fee (Excl. GST)</span>
                                <span class="fw-semibold text-dark">
                                    {{ $currencySymbol }} {{ number_format($delFee, 2) }}
                                </span>
                            </div>
                            @if($registration->participate_in_cme)
                            <div class="d-flex justify-content-between align-items-center py-2 border-bottom border-light small">
                                <span class="text-muted">Pre-Conference Workshop Fee</span>
                                <span class="fw-semibold text-success">
                                    + ₹{{ number_format($cmeFee, 2) }}
                                </span>
                            </div>
                            @endif
                            @if(($registration->accompanying_persons ?? 0) > 0)
                            <div class="d-flex justify-content-between align-items-center py-2 border-bottom border-light small">
                                <span class="text-muted">Accompanying Persons Fee ({{ $registration->accompanying_persons }})</span>
                                <span class="fw-semibold text-success">
                                    + ₹{{ number_format($accFee, 2) }}
                                </span>
                            </div>
                            @endif
                            <div class="d-flex justify-content-between align-items-center py-2 border-bottom border-light small">
                                <span class="text-muted">GST Amount (18%)</span>
                                <span class="fw-semibold text-dark">
                                    {{ $currencySymbol }} {{ number_format($gstAmt, 2) }}
                                </span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center py-3 rounded-3 px-3.5 my-3" style="background-color: #F0F9FF; border: 1px solid #BAE6FD;">
                                <span class="fw-bold text-dark small">Total Amount (Incl. GST)</span>
                                <span class="fw-bold fs-5" style="color: #0288D1;">
                                    {{ $currencySymbol }} {{ number_format($totalAmt, 2) }}
                                </span>
                            </div>

                            {{-- Payment Information --}}
                            @if($registration->payments && $registration->payments->count() > 0)
                                <div class="mt-3">
                                    <h6 class="fw-bold text-dark mb-2 small">Main Registration Payment Details</h6>
                                    @foreach($registration->payments as $payment)
                                        {{-- Skip CME payments in main registration list --}}
                                        @if(($registration->cmeApplication && $registration->cmeApplication->transaction_id && $payment->transaction_id === $registration->cmeApplication->transaction_id) || (isset($payment->payment_type) && strtolower($payment->payment_type) === 'cme'))
                                            @continue
                                        @endif
                                        <div class="p-3 bg-light rounded-3 mb-2 small" style="border: 1px solid #E2E8F0;">
                                            <div class="d-flex justify-content-between mb-1">
                                                <span class="text-muted">Transaction ID:</span>
                                                <span class="fw-bold text-dark">{{ $payment->transaction_id ?? 'N/A' }}</span>
                                            </div>
                                            <div class="d-flex justify-content-between mb-1">
                                                <span class="text-muted">Payment Status:</span>
                                                @if ($payment->payment_status === 'Approved' || $payment->payment_status === 'PAID' || $payment->payment_status === 'Success' || $registration->status === 'Approved')
                                                    <span class="badge bg-success-subtle text-success border border-success px-2.5 py-1 rounded-pill fw-bold">
                                                        <i class="fas fa-check-circle me-1"></i>PAID
                                                    </span>
                                                @else
                                                    <span class="badge bg-warning-subtle text-warning border border-warning px-2.5 py-1 rounded-pill fw-bold">
                                                        <i class="fas fa-hourglass-half me-1"></i>Pending for Verification
                                                    </span>
                                                @endif
                                            </div>
                                            <div class="d-flex justify-content-between">
                                                <span class="text-muted">Payment Method:</span>
                                                <span class="fw-semibold text-dark">{{ $payment->payment_method ?? 'Online' }}</span>
                                            </div>
                                            @if($payment->payment_receipt_path)
                                                <div class="mt-2 text-end">
                                                    <a href="{{ asset('storage/' . $payment->payment_receipt_path) }}" target="_blank" class="btn btn-sm btn-outline-info rounded-pill px-3 py-1" style="font-size: 0.75rem;">
                                                        <i class="fas fa-paperclip me-1"></i>View Receipt
                                                    </a>
                                                </div>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            @endif

                            {{-- Dedicated CME Workshop Payment Details --}}
                            @php
                                $cmeApp = $registration->cmeApplication;
                            @endphp
                            @if($cmeApp)
                                <div class="mt-4 pt-3 border-top border-light">
                                    <div class="d-flex align-items-center justify-content-between mb-2.5">
                                        <h6 class="fw-bold text-dark mb-0 small d-flex align-items-center gap-1.5">
                                            <i class="fas fa-stethoscope text-success"></i> Pre-Conference Workshop Payment Details
                                        </h6>
                                        @php
                                            $cmeBadgeClass = 'bg-warning-subtle text-warning border-warning';
                                            $cmeStatusText = 'Pending for Verification';
                                            if ($cmeApp->status === 'Approved' || $registration->status === 'Approved') {
                                                $cmeBadgeClass = 'bg-success-subtle text-success border-success';
                                                $cmeStatusText = 'PAID';
                                            } elseif ($cmeApp->status === 'Rejected') {
                                                $cmeBadgeClass = 'bg-danger-subtle text-danger border-danger';
                                                $cmeStatusText = 'Rejected';
                                            } elseif ($cmeApp->status === 'Pending Payment') {
                                                $cmeBadgeClass = 'bg-info-subtle text-info border-info';
                                                $cmeStatusText = 'Payment Pending';
                                            }
                                        @endphp
                                        <span class="badge {{ $cmeBadgeClass }} border px-2.5 py-1 rounded-pill fw-bold" style="font-size: 0.75rem;">
                                            @if($cmeStatusText === 'PAID')
                                                <i class="fas fa-check-circle me-1"></i>
                                            @endif
                                            {{ $cmeStatusText }}
                                        </span>
                                    </div>
                                    <div class="p-3 bg-light rounded-3 small" style="border: 1px dashed #10B981;">
                                        <div class="d-flex justify-content-between mb-1.5">
                                            <span class="text-muted">Pre-Conference Workshop Fee + 18% GST:</span>
                                            <span class="fw-bold text-success">₹2,360.00</span>
                                        </div>
                                        <div class="d-flex justify-content-between mb-1.5">
                                            <span class="text-muted">Transaction ID / UTR:</span>
                                            <span class="fw-bold text-dark">{{ $cmeApp->transaction_id ?: 'N/A' }}</span>
                                        </div>
                                        <div class="d-flex justify-content-between mb-1.5">
                                            <span class="text-muted">Submitted Date:</span>
                                            <span class="fw-semibold text-dark">{{ $cmeApp->submitted_at ? $cmeApp->submitted_at->format('d M, Y h:i A') : 'N/A' }}</span>
                                        </div>
                                        @if($cmeApp->payment_receipt_path)
                                            <div class="mt-2 text-end">
                                                <a href="{{ asset('storage/' . $cmeApp->payment_receipt_path) }}" target="_blank" class="btn btn-sm btn-outline-success rounded-pill px-3 py-1" style="font-size: 0.75rem;">
                                                    <i class="fas fa-file-invoice me-1"></i>View CME Payment Receipt
                                                </a>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                </div>
            </div>

            {{-- Bottom Navigation Buttons --}}
            <div class="d-flex flex-wrap justify-content-between align-items-center mt-4 pt-2">
                <a href="{{ route('registration.index') }}" class="btn btn-outline-secondary px-4 py-2.5 fw-semibold rounded-pill shadow-xs">
                    <i class="fas fa-arrow-left me-2"></i>Back to My Registrations
                </a>
                <a href="{{ route('dashboard') }}" class="btn btn-primary px-4 py-2.5 fw-bold rounded-pill shadow-sm" style="background: linear-gradient(135deg, #0288D1 0%, #01579B 100%); border: none;">
                    <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                </a>
            </div>

        </div>
    </div>
</div>
@endsection
