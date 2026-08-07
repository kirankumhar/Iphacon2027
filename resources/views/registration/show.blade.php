@extends('shared.auth-delegate')
@section('title', 'Registration Details')
@section('delegate-content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-11 col-xl-10">

            {{-- Top Header / Banner Card --}}
            <div class="card border-0 shadow-sm mb-4" style="border-radius: 16px; overflow: hidden; background: linear-gradient(135deg, #0288D1 0%, #01579B 60%, #00897B 100%);">
                <div class="card-body p-4 text-white">
                    <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
                        <div class="d-flex align-items-center gap-3">
                            @if($registration->photo_path)
                                <img src="{{ asset('storage/' . $registration->photo_path) }}" alt="Delegate Photo" class="rounded-circle border border-3 border-white shadow-sm" style="width: 80px; height: 80px; object-fit: cover;">
                            @else
                                <div class="rounded-circle bg-white bg-opacity-20 d-flex align-items-center justify-content-center text-white fw-bold shadow-sm" style="width: 80px; height: 80px; font-size: 2rem; border: 3px solid #ffffff;">
                                    {{ strtoupper(substr($registration->user->full_name ?? 'U', 0, 1)) }}
                                </div>
                            @endif
                            <div>
                                <span class="badge bg-white text-dark fw-bold mb-1 px-3 py-1 rounded-pill small shadow-xs" style="font-size: 0.78rem;">
                                    <i class="fas fa-user-shield text-primary me-1"></i> {{ $registration->delegate_type }} Delegate
                                </span>
                                <h3 class="fw-bold mb-1 text-white" style="letter-spacing: -0.3px;">
                                    {{ $registration->user->prefix }} {{ $registration->user->full_name }}
                                </h3>
                                <div class="text-white opacity-90 small d-flex flex-wrap gap-3 mt-1">
                                    <span><i class="fas fa-id-badge text-warning me-1"></i>Reg No: <strong>{{ $registration->registration_number ?? 'Not Generated' }}</strong></span>
                                    <span><i class="fas fa-envelope text-warning me-1"></i>{{ $registration->user->email }}</span>
                                    <span><i class="fas fa-calendar-alt text-warning me-1"></i>Registered: {{ $registration->created_at ? $registration->created_at->format('d M, Y') : 'N/A' }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex flex-wrap align-items-center gap-2">
                            @php
                                $statusBg = '#E0F2FE';
                                $statusFg = '#0288D1';
                                $statusIcon = 'fa-clock';
                                if ($registration->status === 'Approved') {
                                    $statusBg = '#DCFFF0';
                                    $statusFg = '#4BAA7D';
                                    $statusIcon = 'fa-check-circle';
                                } elseif ($registration->status === 'Rejected') {
                                    $statusBg = '#FFE2E2';
                                    $statusFg = '#DC2626';
                                    $statusIcon = 'fa-times-circle';
                                } elseif ($registration->status === 'Payment Submitted') {
                                    $statusBg = '#FEF9C3';
                                    $statusFg = '#CA8A04';
                                    $statusIcon = 'fa-receipt';
                                }
                            @endphp
                            <span class="badge px-3.5 py-2.5 fw-bold shadow-xs" style="font-size: 0.88rem; border-radius: 20px; background-color: {{ $statusBg }}; color: {{ $statusFg }};">
                                <i class="fas {{ $statusIcon }} me-1.5"></i> {{ $registration->status }}
                            </span>

                            @if($registration->status == 'Approved' || $registration->status == 'Payment Submitted')
                                <a href="{{ route('delgate.download.receipt', $registration->registration_number) }}" class="btn btn-warning btn-sm px-3.5 py-2 fw-bold shadow-sm rounded-pill">
                                    <i class="fas fa-file-download me-1.5"></i>Download Receipt
                                </a>
                            @elseif($registration->status == 'Draft')
                                <a href="{{ route('registration.create') }}" class="btn btn-light btn-sm px-3.5 py-2 fw-bold text-primary shadow-sm rounded-pill">
                                    <i class="fas fa-edit me-1.5"></i>Continue Registration
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- Status Alerts (Reverted / Rejected) --}}
            @if($registration->status == 'Rejected' && $registration->rejection_reason)
                <div class="alert alert-danger border-0 shadow-sm rounded-3 mb-4 p-3 d-flex align-items-start gap-3">
                    <i class="fas fa-exclamation-triangle fs-4 text-danger mt-1"></i>
                    <div>
                        <h6 class="fw-bold mb-1">Registration Rejected</h6>
                        <p class="mb-0 small">{{ $registration->rejection_reason }}</p>
                    </div>
                </div>
            @endif

            @if($registration->status == 'Reverted' && $registration->revert_reason)
                <div class="alert alert-warning border-0 shadow-sm rounded-3 mb-4 p-3 d-flex align-items-start gap-3">
                    <i class="fas fa-info-circle fs-4 text-warning mt-1"></i>
                    <div>
                        <h6 class="fw-bold mb-1">Registration Reverted for Modification</h6>
                        <p class="mb-0 small">{{ $registration->revert_reason }}</p>
                    </div>
                </div>
            @endif

            <div class="row g-4">
                {{-- Left Column: User Profile & Contact Info --}}
                <div class="col-lg-6">

                    {{-- Personal Information Card --}}
                    <div class="card border-0 shadow-sm h-100" style="border-radius: 16px; border: 1px solid #E2E8F0 !important;">
                        <div class="card-header bg-white py-3 px-4 border-bottom-0 d-flex align-items-center gap-2.5">
                            <div class="rounded-circle bg-primary bg-opacity-10 p-2 text-primary d-inline-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                                <i class="fas fa-user"></i>
                            </div>
                            <h6 class="fw-bold mb-0 text-dark">Personal Information</h6>
                        </div>
                        <div class="card-body px-4 pt-1 pb-4">
                            <div class="row g-3 small">
                                <div class="col-sm-6">
                                    <span class="text-muted d-block fw-bold" style="font-size: 0.75rem;">Full Name</span>
                                    <span class="fw-semibold text-dark fs-6">{{ $registration->user->prefix }} {{ $registration->user->full_name }}</span>
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

                            <hr class="my-3 text-muted opacity-25">

                            <div class="d-flex align-items-center gap-2 mb-2.5">
                                <div class="rounded-circle bg-success bg-opacity-10 p-1.5 text-success d-inline-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
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
                    <div class="card border-0 shadow-sm mb-4" style="border-radius: 16px; border: 1px solid #E2E8F0 !important;">
                        <div class="card-header bg-white py-3 px-4 border-bottom-0 d-flex align-items-center gap-2.5">
                            <div class="rounded-circle bg-info bg-opacity-10 p-2 text-info d-inline-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                                <i class="fas fa-id-card"></i>
                            </div>
                            <h6 class="fw-bold mb-0 text-dark">Registration & Category</h6>
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
                                    <span class="text-muted d-block fw-bold" style="font-size: 0.75rem;">Workshop / CME</span>
                                    <span class="fw-bold {{ $registration->participate_in_cme ? 'text-success' : 'text-muted' }}">
                                        {{ $registration->participate_in_cme ? 'Participating' : 'Not Participating' }}
                                    </span>
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
                                <hr class="my-3 text-muted opacity-25">
                                <div class="d-flex align-items-center gap-2 mb-2.5">
                                    <div class="rounded-circle bg-warning bg-opacity-10 p-1.5 text-warning d-inline-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
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
                    <div class="card border-0 shadow-sm" style="border-radius: 16px; border: 1px solid #E2E8F0 !important; border-top: 4px solid #0288D1 !important;">
                        <div class="card-header bg-white py-3 px-4 border-bottom-0 d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center gap-2.5">
                                <div class="rounded-circle bg-primary bg-opacity-10 p-2 text-primary d-inline-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                                    <i class="fas fa-receipt"></i>
                                </div>
                                <h6 class="fw-bold mb-0 text-dark">Financial Summary</h6>
                            </div>
                        </div>
                        <div class="card-body px-4 pt-1 pb-4">
                            @php
                                $currencySymbol = $registration->delegate_type == 'International' ? '$' : '₹';
                                $delFee = $registration->delegate_fee ?: ($registration->delegateCategory ? round($registration->delegateCategory->indian_fee / 1.18, 2) : 0);
                                $gstAmt = $registration->gst_amount ?: ($registration->delegateCategory ? round($registration->delegateCategory->indian_fee - $delFee, 2) : 0);
                                $cmeFee = $registration->cme_fee ?: ($registration->participate_in_cme ? 1000 : 0);
                                $accFee = $registration->accompanying_fee ?: (($registration->accompanying_persons ?? 0) * 4000);
                                $totalAmt = $registration->total_amount ?: ($registration->delegateCategory ? ($registration->delegateCategory->indian_fee + $cmeFee + $accFee) : $registration->calculateTotalAmount());
                            @endphp
                            <div class="d-flex justify-content-between align-items-center py-2 border-bottom border-light small">
                                <span class="text-muted">Delegate Registration Fee (Excl. GST)</span>
                                <span class="fw-semibold text-dark">
                                    {{ $currencySymbol }} {{ number_format($delFee, 2) }}
                                </span>
                            </div>
                            @if($registration->participate_in_cme)
                            <div class="d-flex justify-content-between align-items-center py-2 border-bottom border-light small">
                                <span class="text-muted">CME / Workshop Fee</span>
                                <span class="fw-semibold text-dark">
                                    ₹{{ number_format($cmeFee, 2) }}
                                </span>
                            </div>
                            @endif
                            @if(($registration->accompanying_persons ?? 0) > 0)
                            <div class="d-flex justify-content-between align-items-center py-2 border-bottom border-light small">
                                <span class="text-muted">Accompanying Persons Fee ({{ $registration->accompanying_persons }})</span>
                                <span class="fw-semibold text-dark">
                                    ₹{{ number_format($accFee, 2) }}
                                </span>
                            </div>
                            @endif
                            @if($registration->delegate_type != 'International')
                            <div class="d-flex justify-content-between align-items-center py-2 border-bottom border-light small">
                                <span class="text-muted">GST Amount (18%)</span>
                                <span class="fw-semibold text-dark">
                                    {{ $currencySymbol }} {{ number_format($gstAmt, 2) }}
                                </span>
                            </div>
                            @endif
                            <div class="d-flex justify-content-between align-items-center py-3 bg-light rounded-3 px-3 my-3">
                                <span class="fw-bold text-dark small">Total Amount (Incl. GST)</span>
                                <span class="fw-bold text-primary fs-5">
                                    {{ $currencySymbol }} {{ number_format($totalAmt, 2) }}
                                </span>
                            </div>

                            {{-- Payment Information --}}
                            @if($registration->payments && $registration->payments->count() > 0)
                                <div class="mt-3">
                                    <h6 class="fw-bold text-dark mb-2 small">Payment Details</h6>
                                    @foreach($registration->payments as $payment)
                                        <div class="p-3 bg-light rounded-3 mb-2 small">
                                            <div class="d-flex justify-content-between mb-1">
                                                <span class="text-muted">Transaction ID:</span>
                                                <span class="fw-bold text-dark">{{ $payment->transaction_id ?? 'N/A' }}</span>
                                            </div>
                                            <div class="d-flex justify-content-between mb-1">
                                                <span class="text-muted">Payment Status:</span>
                                                <span class="badge bg-success-subtle text-success px-2 py-0.5 rounded-pill">{{ $payment->payment_status ?? 'Pending' }}</span>
                                            </div>
                                            <div class="d-flex justify-content-between">
                                                <span class="text-muted">Payment Method:</span>
                                                <span class="fw-semibold text-dark">{{ $payment->payment_method ?? 'Online' }}</span>
                                            </div>
                                            @if($payment->payment_receipt_path)
                                                <div class="mt-2 text-end">
                                                    <a href="{{ asset('storage/' . $payment->payment_receipt_path) }}" target="_blank" class="btn btn-sm btn-outline-info rounded-pill px-3 py-0.5" style="font-size: 0.75rem;">
                                                        <i class="fas fa-paperclip me-1"></i>View Receipt
                                                    </a>
                                                </div>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>

                </div>
            </div>

            {{-- Bottom Navigation Buttons --}}
            <div class="d-flex flex-wrap justify-content-between align-items-center mt-4 pt-2">
                <a href="{{ route('registration.index') }}" class="btn btn-outline-secondary px-4 py-2 fw-semibold rounded-pill">
                    <i class="fas fa-arrow-left me-2"></i>Back to My Registrations
                </a>
                <a href="{{ route('dashboard') }}" class="btn btn-primary px-4 py-2 fw-bold rounded-pill" style="background: linear-gradient(135deg, #0288D1 0%, #01579B 100%); border: none; box-shadow: 0 4px 15px rgba(2, 136, 209, 0.3);">
                    <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                </a>
            </div>

        </div>
    </div>
</div>
@endsection
