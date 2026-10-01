@extends('shared.auth-delegate')
@section('title', 'Registration & Payment Successful')
@section('delegate-content')
<div class="container py-2">
    <div class="row justify-content-center">
        <div class="col-12 col-md-8 col-lg-6 col-xl-5">
            <div class="card border-0 shadow-sm" style="border-radius: 16px; overflow: hidden; background: #ffffff; box-shadow: 0 10px 25px rgba(0,0,0,0.06) !important;">
                
                <!-- Compact Header Badge -->
                <div class="py-3 px-4 text-center text-white" 
                     style="background: linear-gradient(135deg, #10b981 0%, #059669 100%);">
                    <div class="d-inline-flex align-items-center justify-content-center bg-white text-success rounded-circle mb-2 shadow-sm" 
                         style="width: 48px; height: 48px;">
                        <i class="fas fa-check" style="font-size: 1.4rem;"></i>
                    </div>
                    <h5 class="mb-1 fw-bold text-white tracking-wide">Payment Successful!</h5>
                    <p class="mb-0 text-white-50 small">Your conference registration is confirmed.</p>
                </div>

                <div class="card-body p-3 p-md-4">
                    @if($registration->status === 'Approved')
                        <!-- Registration Number Box -->
                        <div class="p-3 mb-3 text-center rounded-3 position-relative" 
                             style="background: #f0fdf4; border: 1px dashed #86efac;">
                            <div class="text-uppercase fw-semibold text-muted small mb-1" style="font-size: 0.75rem; letter-spacing: 0.5px;">
                                Official Registration Number
                            </div>
                            <div class="d-flex align-items-center justify-content-center gap-2">
                                <span id="regNumber" class="fw-bold font-monospace text-success fs-4" style="letter-spacing: 1px;">
                                    {{ $registration->registration_number }}
                                </span>
                                <button type="button" class="btn btn-sm btn-light border text-secondary px-2 py-1" 
                                        onclick="copyRegNumber(this)" title="Copy Registration Number" style="border-radius: 6px;">
                                    <i class="fas fa-copy"></i>
                                </button>
                            </div>
                            <small class="text-muted d-block mt-1" style="font-size: 0.72rem;">
                                Save this number for on-spot badge collection & verification.
                            </small>
                        </div>

                        <!-- Compact Summary Grid -->
                        <div class="rounded-3 p-2 mb-3 bg-light border border-1" style="font-size: 0.82rem;">
                            <div class="row g-2 text-start">
                                <div class="col-6">
                                    <span class="text-muted d-block" style="font-size: 0.72rem;">Delegate Name</span>
                                    <span class="fw-semibold text-dark text-truncate d-block">
                                        {{ $registration->user->name ?? 'N/A' }}
                                    </span>
                                </div>
                                <div class="col-6">
                                    <span class="text-muted d-block" style="font-size: 0.72rem;">Category</span>
                                    <span class="fw-semibold text-dark text-truncate d-block">
                                        {{ $registration->delegateCategory->name ?? 'Indian Delegate' }}
                                    </span>
                                </div>
                                <div class="col-6 pt-1 border-top">
                                    <span class="text-muted d-block" style="font-size: 0.72rem;">Total Amount Paid</span>
                                    <span class="fw-bold text-success">
                                        ₹{{ number_format($registration->total_amount, 2) }}
                                    </span>
                                </div>
                                <div class="col-6 pt-1 border-top">
                                    <span class="text-muted d-block" style="font-size: 0.72rem;">Payment Reference</span>
                                    <span class="fw-semibold text-dark font-monospace text-truncate d-block" style="font-size: 0.75rem;" title="{{ $registration->payments->last()?->transaction_id ?? $registration->payments->last()?->gateway_transaction_id ?? 'SBI ePay' }}">
                                        {{ $registration->payments->last()?->transaction_id ?? $registration->payments->last()?->gateway_transaction_id ?? 'SBI ePay' }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-grid gap-2">
                            <a href="{{ route('delgate.download.receipt', $registration->registration_number) }}" 
                               class="btn btn-success fw-semibold shadow-sm py-2" 
                               style="border-radius: 8px; font-size: 0.9rem;">
                                <i class="fas fa-file-pdf me-2"></i>Download Registration Slip / Receipt
                            </a>

                            <div class="row g-2 mt-1">
                                <div class="col-6">
                                    <a href="{{ route('registration.show', $registration->id) }}" 
                                       class="btn btn-outline-primary w-100 fw-medium py-2" 
                                       style="border-radius: 8px; font-size: 0.85rem;">
                                        <i class="fas fa-id-card me-1"></i>View Details
                                    </a>
                                </div>
                                <div class="col-6">
                                    <a href="{{ route('dashboard') }}" 
                                       class="btn btn-outline-secondary w-100 fw-medium py-2" 
                                       style="border-radius: 8px; font-size: 0.85rem;">
                                        <i class="fas fa-th-large me-1"></i>Dashboard
                                    </a>
                                </div>
                            </div>
                        </div>

                    @else
                        <!-- Pending Verification Box -->
                        <div class="p-3 mb-3 text-center rounded-3" 
                             style="background: #fffbeb; border: 1px dashed #fcd34d;">
                            <div class="text-uppercase fw-semibold text-muted small mb-1" style="font-size: 0.75rem;">
                                Acknowledgement Number
                            </div>
                            <div class="fw-bold font-monospace text-dark fs-4 mb-1">
                                {{ $registration->acknowledgement_id ?? 'N/A' }}
                            </div>
                            <span class="badge bg-warning text-dark px-2 py-1" style="font-size: 0.72rem;">
                                <i class="fas fa-clock me-1"></i>Pending Verification
                            </span>
                        </div>

                        <p class="text-muted small mb-3 text-center" style="font-size: 0.8rem;">
                            Your payment has been logged. The organizing committee is verifying your documents. You will receive your official registration slip once approved.
                        </p>

                        <div class="row g-2">
                            <div class="col-6">
                                <a href="{{ route('registration.show', $registration->id) }}" class="btn btn-outline-primary w-100 py-2" style="border-radius: 8px; font-size: 0.85rem;">
                                    <i class="fas fa-eye me-1"></i>View Registration
                                </a>
                            </div>
                            <div class="col-6">
                                <a href="{{ route('dashboard') }}" class="btn btn-secondary w-100 py-2" style="border-radius: 8px; font-size: 0.85rem;">
                                    <i class="fas fa-home me-1"></i>Dashboard
                                </a>
                            </div>
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</div>

<script>
function copyRegNumber(btn) {
    const regText = document.getElementById('regNumber').innerText.trim();
    navigator.clipboard.writeText(regText).then(() => {
        const originalHtml = btn.innerHTML;
        btn.innerHTML = '<i class="fas fa-check text-success"></i>';
        btn.classList.add('border-success');
        setTimeout(() => {
            btn.innerHTML = originalHtml;
            btn.classList.remove('border-success');
        }, 2000);
    });
}
</script>
@endsection
