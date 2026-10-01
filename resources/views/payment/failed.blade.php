@extends('shared.auth-delegate')
@section('title', 'Payment Incomplete / Failed')
@section('delegate-content')
<div class="container py-3">
    <div class="row justify-content-center">
        <div class="col-12 col-md-8 col-lg-6 col-xl-5">
            <div class="card border-0 shadow-sm" style="border-radius: 16px; overflow: hidden; background: #ffffff; box-shadow: 0 10px 25px rgba(0,0,0,0.06) !important;">
                
                <!-- Header Badge (Crimson Red Gradient) -->
                @php
                    $isCancelled = isset($reason) && (stripos($reason, 'cancel') !== false || stripos($reason, 'abort') !== false);
                @endphp
                <div class="py-3 px-4 text-center text-white" 
                     style="background: linear-gradient(135deg, #ef4444 0%, #b91c1c 100%);">
                    <div class="d-inline-flex align-items-center justify-content-center bg-white text-danger rounded-circle mb-2 shadow-sm" 
                         style="width: 48px; height: 48px;">
                        <i class="fas {{ $isCancelled ? 'fa-ban' : 'fa-times' }}" style="font-size: 1.4rem;"></i>
                    </div>
                    <h5 class="mb-1 fw-bold text-white tracking-wide">
                        {{ $isCancelled ? 'Payment Cancelled' : 'Payment Failed' }}
                    </h5>
                    <p class="mb-0 text-white-50 small">
                        {{ $isCancelled ? 'Transaction was cancelled. You can retry anytime.' : 'Transaction could not be completed by SBI ePay.' }}
                    </p>
                </div>

                <div class="card-body p-3 p-md-4">
                    
                    <!-- Bank Refund / Reversal Notice -->
                    <div class="p-2.5 mb-3 rounded-3 d-flex align-items-start gap-2.5" 
                         style="background: #f8fafc; border-left: 4px solid #3b82f6;">
                        <i class="fas fa-info-circle text-primary mt-1" style="font-size: 0.95rem;"></i>
                        <div style="font-size: 0.78rem; line-height: 1.45;" class="text-secondary">
                            <strong class="text-dark d-block mb-0.5">Money debited from your account?</strong>
                            Please do not worry. If amount was deducted, your bank will automatically reverse/refund it within <strong>5-7 business days</strong>.
                        </div>
                    </div>

                    <!-- Transaction Summary Details -->
                    <div class="rounded-3 p-3 mb-3 bg-light border border-1" style="font-size: 0.82rem;">
                        <div class="row g-2 text-start">
                            @if($registration)
                                <div class="col-6">
                                    <span class="text-muted d-block" style="font-size: 0.72rem;">Delegate Name</span>
                                    <span class="fw-semibold text-dark text-truncate d-block">
                                        {{ $registration->user->full_name ?? ($registration->user->name ?? 'N/A') }}
                                    </span>
                                </div>
                                <div class="col-6">
                                    <span class="text-muted d-block" style="font-size: 0.72rem;">
                                        {{ $registration->status === 'Approved' ? 'Registration No.' : 'Acknowledgement No.' }}
                                    </span>
                                    <span class="fw-semibold font-monospace text-dark text-truncate d-block">
                                        {{ $registration->registration_number ?? ($registration->acknowledgement_id ?? 'N/A') }}
                                    </span>
                                </div>
                            @endif

                            <div class="col-6 pt-2 border-top">
                                <span class="text-muted d-block" style="font-size: 0.72rem;">Payable Amount</span>
                                <span class="fw-bold text-danger fs-6">
                                    @if(isset($type) && $type === 'cme')
                                        ₹2,360.00 <span class="text-muted small fw-normal">(Workshop)</span>
                                    @elseif($registration)
                                        ₹{{ number_format($registration->total_amount, 2) }}
                                    @else
                                        --
                                    @endif
                                </span>
                            </div>

                            <div class="col-6 pt-2 border-top">
                                <span class="text-muted d-block" style="font-size: 0.72rem;">Payment Gateway</span>
                                <span class="fw-semibold text-dark d-block">
                                    <i class="fas fa-university text-primary me-1"></i>SBI ePay
                                </span>
                            </div>

                            @if(!empty($txn) || !empty($failedPayment?->transaction_id) || !empty($failedPayment?->gateway_transaction_id))
                                <div class="col-12 pt-2 border-top">
                                    <span class="text-muted d-block" style="font-size: 0.72rem;">Reference Order ID</span>
                                    <span class="fw-semibold font-monospace text-secondary text-truncate d-block" style="font-size: 0.75rem;">
                                        {{ $txn ?? ($failedPayment->transaction_id ?? $failedPayment->gateway_transaction_id) }}
                                    </span>
                                </div>
                            @endif

                            @if(!empty($reason))
                                <div class="col-12 pt-2 border-top">
                                    <span class="text-muted d-block" style="font-size: 0.72rem;">Gateway Message</span>
                                    <span class="text-danger fw-medium d-block" style="font-size: 0.76rem;">
                                        <i class="fas fa-exclamation-circle me-1"></i>{{ $reason }}
                                    </span>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Call To Action Buttons -->
                    <div class="d-grid gap-2">
                        @if(isset($type) && $type === 'cme' && $cmeApp)
                            {{-- Direct Retry for CME --}}
                            <a href="{{ route('cme.payment.sbi.initiate', $cmeApp->id) }}" 
                               class="btn btn-danger fw-bold shadow-sm py-2.5 d-flex align-items-center justify-content-center gap-2" 
                               style="border-radius: 8px; font-size: 0.92rem; background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); border: none;">
                                <i class="fas fa-redo-alt"></i>
                                <span>Retry Workshop Payment (SBI ePay)</span>
                            </a>
                            <a href="{{ route('cme.payment.gateway') }}" 
                               class="btn btn-outline-secondary fw-semibold py-2" 
                               style="border-radius: 8px; font-size: 0.84rem;">
                                <i class="fas fa-wallet me-1.5"></i>Select Another Payment Option
                            </a>
                        @elseif($registration)
                            {{-- Direct Retry for Delegate Registration --}}
                            <a href="{{ route('payment.sbi.initiate', $registration->id) }}" 
                               class="btn btn-danger fw-bold shadow-sm py-2.5 d-flex align-items-center justify-content-center gap-2" 
                               style="border-radius: 8px; font-size: 0.92rem; background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); border: none;">
                                <i class="fas fa-redo-alt"></i>
                                <span>Retry Payment Now (SBI ePay)</span>
                            </a>
                            <a href="{{ route('payment.gateway', $registration->id) }}" 
                               class="btn btn-outline-secondary fw-semibold py-2" 
                               style="border-radius: 8px; font-size: 0.84rem;">
                                <i class="fas fa-wallet me-1.5"></i>Change Payment Method / Gateway Options
                            </a>
                        @else
                            <a href="{{ route('registration.index') }}" 
                               class="btn btn-primary fw-bold shadow-sm py-2.5" 
                               style="border-radius: 8px; font-size: 0.92rem;">
                                <i class="fas fa-redo-alt me-1.5"></i>Go to My Registrations to Retry
                            </a>
                        @endif

                        <div class="row g-2 mt-1">
                            <div class="col-6">
                                <a href="{{ route('registration.index') }}" 
                                   class="btn btn-light border text-secondary w-100 fw-medium py-2" 
                                   style="border-radius: 8px; font-size: 0.82rem;">
                                    <i class="fas fa-clipboard-list me-1"></i>My Registrations
                                </a>
                            </div>
                            <div class="col-6">
                                <a href="{{ route('dashboard') }}" 
                                   class="btn btn-light border text-secondary w-100 fw-medium py-2" 
                                   style="border-radius: 8px; font-size: 0.82rem;">
                                    <i class="fas fa-tachometer-alt me-1"></i>Dashboard
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Helpdesk Footer Note -->
                    <div class="text-center mt-3 pt-2 border-top">
                        <small class="text-muted d-block" style="font-size: 0.72rem;">
                            Need assistance with your payment? Contact conference support at 
                            <a href="mailto:registration@iphacon2027.com" class="text-decoration-none fw-semibold">registration@iphacon2027.com</a>
                        </small>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
