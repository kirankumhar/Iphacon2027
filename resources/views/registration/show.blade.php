@extends('shared.auth-delegate')
@section('title', 'Registration Details')
@section('delegate-content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-11 col-xl-10">

            {{-- Top Header / Banner Card --}}
            <div class="card border-0 shadow-sm mb-4" style="border-radius: 16px; overflow: hidden; background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);">
                <div class="card-body p-4 text-white">
                    <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
                        <div class="d-flex align-items-center gap-3">
                            @if($registration->photo_path)
                                <img src="{{ asset('storage/' . $registration->photo_path) }}" alt="Delegate Photo" class="rounded-circle border border-3 border-white shadow-sm" style="width: 75px; height: 75px; object-fit: cover;">
                            @else
                                <div class="rounded-circle bg-primary bg-gradient d-flex align-items-center justify-content-center text-white fw-bold shadow-sm" style="width: 75px; height: 75px; font-size: 1.8rem; border: 3px solid #ffffff;">
                                    {{ strtoupper(substr($registration->user->full_name ?? 'U', 0, 1)) }}
                                </div>
                            @endif
                            <div>
                                <span class="badge bg-primary bg-opacity-25 text-info border border-info border-opacity-25 mb-1 px-3 py-1 rounded-pill small">
                                    {{ $registration->delegate_type }} Delegate
                                </span>
                                <h3 class="fw-bold mb-1 text-white">
                                    {{ $registration->user->prefix }} {{ $registration->user->full_name }}
                                </h3>
                                <div class="text-light opacity-75 small d-flex flex-wrap gap-3">
                                    <span><i class="fas fa-id-badge text-primary me-1"></i>Reg No: <strong>{{ $registration->registration_number ?? 'Not Generated' }}</strong></span>
                                    <span><i class="fas fa-envelope text-primary me-1"></i>{{ $registration->user->email }}</span>
                                    <span><i class="fas fa-calendar-alt text-primary me-1"></i>Registered: {{ $registration->created_at ? $registration->created_at->format('d M, Y') : 'N/A' }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex flex-wrap align-items-center gap-2">
                            <span class="badge px-3 py-2 fw-semibold" style="font-size: 0.9rem; border-radius: 20px; background-color: {{ $registration->status == 'Approved' ? '#DCFFF0' : ($registration->status == 'Rejected' ? '#ffe2e2' : '#E1F0FF') }}; color: {{ $registration->status == 'Approved' ? '#4BAA7D' : ($registration->status == 'Rejected' ? '#dc2626' : '#2D69FF') }};">
                                <i class="fas {{ $registration->status == 'Approved' ? 'fa-check-circle' : ($registration->status == 'Rejected' ? 'fa-times-circle' : 'fa-clock') }} me-1"></i>
                                {{ $registration->status }}
                            </span>

                            @if($registration->status == 'Approved' || $registration->status == 'Payment Submitted')
                                <a href="{{ route('delgate.download.receipt', $registration->registration_number) }}" class="btn btn-success btn-sm px-3 py-2 fw-semibold shadow-sm" style="border-radius: 8px;">
                                    <i class="fas fa-file-download me-1.5"></i>Download Acknowledgement
                                </a>
                            @elseif($registration->status == 'Draft')
                                <a href="{{ route('registration.create') }}" class="btn btn-warning btn-sm px-3 py-2 fw-semibold shadow-sm" style="border-radius: 8px;">
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
                    <div class="card border-0 shadow-sm h-100" style="border-radius: 14px;">
                        <div class="card-header bg-white py-3 px-4 border-bottom-0 d-flex align-items-center gap-2">
                            <div class="rounded-circle bg-primary bg-opacity-10 p-2 text-primary d-inline-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                                <i class="fas fa-user fs-6"></i>
                            </div>
                            <h5 class="fw-bold mb-0 text-dark">Personal Information</h5>
                        </div>
                        <div class="card-body px-4 pt-1 pb-4">
                            <div class="row g-3">
                                <div class="col-sm-6">
                                    <span class="text-muted small d-block">Full Name</span>
                                    <span class="fw-semibold text-dark">{{ $registration->user->prefix }} {{ $registration->user->full_name }}</span>
                                </div>
                                <div class="col-sm-6">
                                    <span class="text-muted small d-block">Gender</span>
                                    <span class="fw-semibold text-dark">{{ $registration->user->gender ?? 'N/A' }}</span>
                                </div>
                                <div class="col-sm-6">
                                    <span class="text-muted small d-block">Date of Birth</span>
                                    <span class="fw-semibold text-dark">
                                        {{ $registration->user->date_of_birth ? \Carbon\Carbon::parse($registration->user->date_of_birth)->format('d M, Y') : 'N/A' }}
                                    </span>
                                </div>
                                <div class="col-sm-6">
                                    <span class="text-muted small d-block">Email Address</span>
                                    <span class="fw-semibold text-dark">{{ $registration->user->email }}</span>
                                    @if($registration->user->hasVerifiedEmail())
                                        <span class="badge bg-success-subtle text-success border border-success-subtle ms-1 px-1.5 py-0.5 rounded small" style="font-size: 0.75rem;">Verified</span>
                                    @endif
                                </div>
                                <div class="col-sm-6">
                                    <span class="text-muted small d-block">Mobile Number</span>
                                    <span class="fw-semibold text-dark">
                                        {{ $registration->user->mobile_country_code }} {{ $registration->user->mobile_number }}
                                    </span>
                                </div>
                                <div class="col-sm-6">
                                    <span class="text-muted small d-block">WhatsApp Number</span>
                                    <span class="fw-semibold text-dark">
                                        @if($registration->whatsapp_number)
                                            {{ $registration->whatsapp_country_code ?? $registration->user->mobile_country_code }} {{ $registration->whatsapp_number }}
                                        @else
                                            N/A
                                        @endif
                                    </span>
                                </div>
                            </div>

                            <hr class="my-4 text-muted opacity-25">

                            <div class="d-flex align-items-center gap-2 mb-3">
                                <div class="rounded-circle bg-success bg-opacity-10 p-2 text-success d-inline-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                                    <i class="fas fa-map-marker-alt fs-6"></i>
                                </div>
                                <h6 class="fw-bold mb-0 text-dark">Address & Location</h6>
                            </div>

                            <div class="row g-3">
                                <div class="col-sm-6">
                                    <span class="text-muted small d-block">Country</span>
                                    <span class="fw-semibold text-dark">{{ $registration->country->country_name ?? $registration->user->country->country_name ?? 'N/A' }}</span>
                                </div>
                                <div class="col-sm-6">
                                    <span class="text-muted small d-block">State</span>
                                    <span class="fw-semibold text-dark">{{ $registration->state->state_name ?? $registration->other_state ?? 'N/A' }}</span>
                                </div>
                                <div class="col-sm-6">
                                    <span class="text-muted small d-block">City</span>
                                    <span class="fw-semibold text-dark">{{ $registration->city ?: 'N/A' }}</span>
                                </div>
                                <div class="col-sm-6">
                                    <span class="text-muted small d-block">Pincode / Zip Code</span>
                                    <span class="fw-semibold text-dark">{{ $registration->pin_code ?: 'N/A' }}</span>
                                </div>
                                <div class="col-12">
                                    <span class="text-muted small d-block">Street Address</span>
                                    <span class="fw-semibold text-dark">{{ $registration->address ?: 'N/A' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                {{-- Right Column: Registration & Financial Details --}}
                <div class="col-lg-6">

                    {{-- Registration & Category Details Card --}}
                    <div class="card border-0 shadow-sm mb-4" style="border-radius: 14px;">
                        <div class="card-header bg-white py-3 px-4 border-bottom-0 d-flex align-items-center gap-2">
                            <div class="rounded-circle bg-info bg-opacity-10 p-2 text-info d-inline-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                                <i class="fas fa-id-card fs-6"></i>
                            </div>
                            <h5 class="fw-bold mb-0 text-dark">Registration & Category</h5>
                        </div>
                        <div class="card-body px-4 pt-1 pb-4">
                            <div class="row g-3">
                                <div class="col-sm-6">
                                    <span class="text-muted small d-block">Delegate Type</span>
                                    <span class="fw-semibold text-dark">{{ $registration->delegate_type }}</span>
                                </div>
                                <div class="col-sm-6">
                                    <span class="text-muted small d-block">Category</span>
                                    <span class="fw-semibold text-dark">{{ $registration->delegateCategory->category_name ?? 'Pending Selection' }}</span>
                                </div>
                                <div class="col-sm-6">
                                    <span class="text-muted small d-block">Dietary Preference</span>
                                    <span class="fw-semibold text-dark">{{ $registration->dietary_preference ?: 'N/A' }}</span>
                                </div>
                                <div class="col-sm-6">
                                    <span class="text-muted small d-block">Workshop / CME</span>
                                    <span class="fw-semibold {{ $registration->participate_in_cme ? 'text-success' : 'text-muted' }}">
                                        {{ $registration->participate_in_cme ? 'Participating' : 'Not Participating' }}
                                    </span>
                                </div>
                                <div class="col-sm-6">
                                    <span class="text-muted small d-block">Accompanying Persons</span>
                                    <span class="fw-semibold text-dark">{{ $registration->accompanying_persons ?? 0 }} Person(s)</span>
                                </div>
                                <div class="col-sm-6">
                                    <span class="text-muted small d-block">ID Proof Type</span>
                                    <span class="fw-semibold text-dark">{{ $registration->id_proof_type ?: 'N/A' }}</span>
                                </div>

                                @if($registration->id_proof_document_path)
                                    <div class="col-12 mt-2">
                                        <a href="{{ asset('storage/' . $registration->id_proof_document_path) }}" target="_blank" class="btn btn-sm btn-outline-primary px-3 py-1.5" style="border-radius: 6px;">
                                            <i class="fas fa-external-link-alt me-1.5"></i>View ID Proof Document
                                        </a>
                                    </div>
                                @endif
                            </div>

                            @if($registration->membership_no || $registration->is_ismm_member || $registration->is_isham_member || $registration->is_young_isam_member)
                                <hr class="my-4 text-muted opacity-25">
                                <div class="d-flex align-items-center gap-2 mb-3">
                                    <div class="rounded-circle bg-warning bg-opacity-10 p-2 text-warning d-inline-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                                        <i class="fas fa-award fs-6"></i>
                                    </div>
                                    <h6 class="fw-bold mb-0 text-dark">Membership Details</h6>
                                </div>
                                <div class="row g-3">
                                    @if($registration->membership_no)
                                        <div class="col-sm-6">
                                            <span class="text-muted small d-block">Membership ID</span>
                                            <span class="fw-semibold text-dark">{{ $registration->membership_no }}</span>
                                        </div>
                                    @endif
                                    @if($registration->is_ismm_member)
                                        <div class="col-sm-6">
                                            <span class="text-muted small d-block">ISMM Member No.</span>
                                            <span class="fw-semibold text-dark">{{ $registration->ismm_membership_no ?? 'Yes' }}</span>
                                        </div>
                                    @endif
                                    @if($registration->is_isham_member)
                                        <div class="col-sm-6">
                                            <span class="text-muted small d-block">ISHAM Member No.</span>
                                            <span class="fw-semibold text-dark">{{ $registration->isham_membership_no ?? 'Yes' }}</span>
                                        </div>
                                    @endif
                                    @if($registration->is_young_isam_member)
                                        <div class="col-sm-6">
                                            <span class="text-muted small d-block">Young ISAM Member No.</span>
                                            <span class="fw-semibold text-dark">{{ $registration->young_isam_membership_no ?? 'Yes' }}</span>
                                        </div>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Financial Summary Card --}}
                    <div class="card border-0 shadow-sm" style="border-radius: 14px; border-top: 4px solid #2D69FF !important;">
                        <div class="card-header bg-white py-3 px-4 border-bottom-0 d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center gap-2">
                                <div class="rounded-circle bg-primary bg-opacity-10 p-2 text-primary d-inline-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                                    <i class="fas fa-receipt fs-6"></i>
                                </div>
                                <h5 class="fw-bold mb-0 text-dark">Financial Summary</h5>
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
                            <div class="d-flex justify-content-between align-items-center py-2 border-bottom border-light">
                                <span class="text-muted">Delegate Registration Fee (Excl. GST)</span>
                                <span class="fw-semibold text-dark">
                                    {{ $currencySymbol }} {{ number_format($delFee, 2) }}
                                </span>
                            </div>
                            @if($registration->participate_in_cme)
                            <div class="d-flex justify-content-between align-items-center py-2 border-bottom border-light">
                                <span class="text-muted">CME / Workshop Fee</span>
                                <span class="fw-semibold text-dark">
                                    ₹{{ number_format($cmeFee, 2) }}
                                </span>
                            </div>
                            @endif
                            @if(($registration->accompanying_persons ?? 0) > 0)
                            <div class="d-flex justify-content-between align-items-center py-2 border-bottom border-light">
                                <span class="text-muted">Accompanying Persons Fee ({{ $registration->accompanying_persons }})</span>
                                <span class="fw-semibold text-dark">
                                    ₹{{ number_format($accFee, 2) }}
                                </span>
                            </div>
                            @endif
                            @if($registration->delegate_type != 'International')
                            <div class="d-flex justify-content-between align-items-center py-2 border-bottom border-light">
                                <span class="text-muted">GST Amount (18%)</span>
                                <span class="fw-semibold text-dark">
                                    {{ $currencySymbol }} {{ number_format($gstAmt, 2) }}
                                </span>
                            </div>
                            @endif
                            <div class="d-flex justify-content-between align-items-center py-3 bg-light rounded-3 px-3 my-3">
                                <span class="fw-bold text-dark fs-6">Total Amount (Incl. GST)</span>
                                <span class="fw-bold text-primary fs-5">
                                    {{ $currencySymbol }} {{ number_format($totalAmt, 2) }}
                                </span>
                            </div>

                            {{-- Payment Information --}}
                            @if($registration->payments && $registration->payments->count() > 0)
                                <div class="mt-3">
                                    <h6 class="fw-bold text-dark mb-2">Payment Details</h6>
                                    @foreach($registration->payments as $payment)
                                        <div class="p-3 bg-light rounded-3 mb-2 small">
                                            <div class="d-flex justify-content-between mb-1">
                                                <span class="text-muted">Transaction ID:</span>
                                                <span class="fw-semibold text-dark">{{ $payment->transaction_id ?? 'N/A' }}</span>
                                            </div>
                                            <div class="d-flex justify-content-between mb-1">
                                                <span class="text-muted">Payment Status:</span>
                                                <span class="badge bg-info-subtle text-info px-2 py-0.5 rounded">{{ $payment->payment_status ?? 'Pending' }}</span>
                                            </div>
                                            <div class="d-flex justify-content-between">
                                                <span class="text-muted">Payment Method:</span>
                                                <span class="fw-semibold text-dark">{{ $payment->payment_method ?? 'Online' }}</span>
                                            </div>
                                            @if($payment->payment_receipt_path)
                                                <div class="mt-2 text-end">
                                                    <a href="{{ asset('storage/' . $payment->payment_receipt_path) }}" target="_blank" class="text-primary fw-semibold small">
                                                        <i class="fas fa-paperclip me-1"></i>View Uploaded Receipt
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
                <a href="{{ route('registration.index') }}" class="btn btn-outline-secondary px-4 py-2 fw-semibold" style="border-radius: 10px;">
                    <i class="fas fa-arrow-left me-2"></i>Back to My Registrations
                </a>
                <a href="{{ route('dashboard') }}" class="btn btn-primary px-4 py-2 fw-semibold" style="background: linear-gradient(135deg, #2D69FF 0%, #1A52E0 100%); border: none; border-radius: 10px;">
                    <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                </a>
            </div>

        </div>
    </div>
</div>
@endsection

