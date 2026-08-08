@extends('shared.auth-delegate')
@section('title', 'Pre-Conference CME / Workshop Registration')

@section('delegate-content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-9 col-xl-8">

            {{-- Banner Header Card --}}
            <div class="card border-0 shadow-sm mb-4 overflow-hidden" style="border-radius: 20px; background: linear-gradient(135deg, #013069 0%, #0d47a1 50%, #00897B 100%);">
                <div class="card-body p-4 text-white position-relative">
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-circle bg-white bg-opacity-25 d-flex align-items-center justify-content-center text-white fw-bold shadow-sm" style="width: 60px; height: 60px; font-size: 1.6rem; border: 2px solid rgba(255,255,255,0.4);">
                            <i class="fas fa-stethoscope"></i>
                        </div>
                        <div>
                            <span class="badge bg-warning text-dark fw-bold mb-1 px-3 py-1 rounded-pill shadow-xs" style="font-size: 0.75rem;">
                                <i class="fas fa-microscope me-1"></i> Pre-Conference Workshop
                            </span>
                            <h3 class="fw-bold mb-1 text-white" style="letter-spacing: -0.5px;">CME / Workshop Registration</h3>
                            <p class="mb-0 text-white opacity-90 small">IPHACON 2027 • RIMS, Ranchi</p>
                        </div>
                    </div>
                </div>
            </div>

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show rounded-4 mb-4 small shadow-xs" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            {{-- Application Form --}}
            <form action="{{ route('cme.process') }}" method="POST" id="cmeForm">
                @csrf
                <div class="card border-0 shadow-sm mb-4" style="border-radius: 16px; border: 1px solid #E2E8F0 !important;">
                    <div class="card-header bg-white py-3.5 px-4 border-bottom d-flex align-items-center justify-content-between">
                        <h6 class="fw-bold text-dark mb-0">
                            <i class="fas fa-user-check text-primary me-2"></i>Delegate Details
                        </h6>
                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-3 py-1 rounded-pill small">
                            Reg No / Ack: {{ $registration->status === 'Approved' ? $registration->registration_number : ('ACK-IPHACON-' . sprintf('%04d', $registration->id)) }}
                        </span>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-3 small mb-4">
                            <div class="col-sm-6">
                                <span class="text-muted d-block fw-bold" style="font-size: 0.75rem;">Delegate Full Name</span>
                                <span class="fw-semibold text-dark fs-6">{{ $user->prefix }} {{ $user->full_name }}</span>
                            </div>
                            <div class="col-sm-6">
                                <span class="text-muted d-block fw-bold" style="font-size: 0.75rem;">Email Address</span>
                                <span class="fw-semibold text-dark fs-6">{{ $user->email }}</span>
                            </div>
                            <div class="col-sm-6">
                                <span class="text-muted d-block fw-bold" style="font-size: 0.75rem;">Delegate Category</span>
                                <span class="fw-semibold text-dark">{{ $registration->delegateCategory->category_name ?? 'Delegate' }}</span>
                            </div>
                            <div class="col-sm-6">
                                <span class="text-muted d-block fw-bold" style="font-size: 0.75rem;">Mobile Number</span>
                                <span class="fw-semibold text-dark">{{ $user->mobile_number }}</span>
                            </div>
                        </div>

                        <hr class="my-4 text-muted opacity-25">

                        {{-- CME Selection Checkbox Card --}}
                        <h6 class="fw-bold text-dark mb-3">
                            <i class="fas fa-tasks text-success me-2"></i>Workshop Selection
                        </h6>

                        <div class="p-3.5 rounded-3 border transition-all shadow-xs" id="cmeBox" style="background: #F8FAFC; border: 2px solid #E2E8F0 !important;">
                            <div class="form-check d-flex align-items-start gap-3 ps-0">
                                <input class="form-check-input mt-1 ms-0 me-2" type="checkbox" name="participate_in_cme" value="1" id="cmeCheckbox" checked onchange="toggleCmePricing()" style="width: 22px; height: 22px; cursor: pointer;">
                                <label class="form-check-label w-100" for="cmeCheckbox" style="cursor: pointer;">
                                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                                        <div>
                                            <h6 class="fw-bold text-dark mb-0.5" style="font-size: 1rem;">
                                                Pre-Conference CME / Hands-on Workshop
                                            </h6>
                                            <p class="text-muted small mb-0">
                                                Gain access to interactive pre-conference CME sessions, hands-on learning modules, certificate & workshop material.
                                            </p>
                                        </div>
                                        <div class="text-end">
                                            <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-1.5 rounded-pill fw-bold fs-6">
                                                ₹ 2,000 + 18% GST
                                            </span>
                                        </div>
                                    </div>
                                </label>
                            </div>
                        </div>

                        {{-- Price Breakdown Summary Box --}}
                        <div class="mt-4 p-4 rounded-3" style="background: #F0F9FF; border: 1px dashed #0288D1;" id="pricingBreakdown">
                            <h6 class="fw-bold text-primary mb-3" style="font-size: 0.95rem;">
                                <i class="fas fa-calculator me-1.5"></i>Fee Breakdown Summary
                            </h6>
                            <div class="d-flex justify-content-between align-items-center mb-2 small">
                                <span class="text-secondary">CME Workshop Base Fee</span>
                                <span class="fw-semibold text-dark" id="baseFeeDisplay">₹ 2,000.00</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mb-2 small">
                                <span class="text-secondary">GST (18%)</span>
                                <span class="fw-semibold text-dark" id="gstFeeDisplay">₹ 360.00</span>
                            </div>
                            <hr class="my-2 text-primary opacity-25">
                            <div class="d-flex justify-content-between align-items-center pt-1">
                                <span class="fw-bold text-dark fs-6">Total Amount Payable</span>
                                <span class="fw-bold fs-4 text-primary" id="totalFeeDisplay">₹ 2,360.00</span>
                            </div>
                        </div>

                        <div class="d-none mt-4 p-4 rounded-3 bg-light text-center border" id="unselectedMessage">
                            <i class="fas fa-info-circle text-muted fs-4 mb-2 d-block"></i>
                            <p class="text-muted small mb-0">Please check the CME / Workshop checkbox above to proceed to payment.</p>
                        </div>
                    </div>

                    <div class="card-footer bg-white p-4 border-top d-flex justify-content-between align-items-center flex-wrap gap-3">
                        <a href="{{ route('registration.show', $registration->id) }}" class="btn btn-outline-secondary px-4 py-2.5 fw-semibold rounded-pill">
                            <i class="fas fa-arrow-left me-1.5"></i>Back
                        </a>
                        <button type="submit" class="btn btn-success px-4 py-2.5 fw-bold rounded-pill shadow" id="btnSubmitPayment" style="background: linear-gradient(135deg, #10B981 0%, #059669 100%); border: none;">
                            <i class="fas fa-credit-card me-2"></i>Proceed to Pay <span id="btnPayAmount">₹ 2,360.00</span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function toggleCmePricing() {
    const cb = document.getElementById('cmeCheckbox');
    const breakdown = document.getElementById('pricingBreakdown');
    const unselected = document.getElementById('unselectedMessage');
    const btnSubmit = document.getElementById('btnSubmitPayment');
    const cmeBox = document.getElementById('cmeBox');

    if (cb.checked) {
        breakdown.classList.remove('d-none');
        unselected.classList.add('d-none');
        btnSubmit.disabled = false;
        cmeBox.style.borderColor = '#10B981';
        cmeBox.style.background = '#ECFDF5';
    } else {
        breakdown.classList.add('d-none');
        unselected.classList.remove('d-none');
        btnSubmit.disabled = true;
        cmeBox.style.borderColor = '#E2E8F0';
        cmeBox.style.background = '#F8FAFC';
    }
}
document.addEventListener('DOMContentLoaded', toggleCmePricing);
</script>
@endsection
