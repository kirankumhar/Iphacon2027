@extends('shared.auth-delegate')
@section('title', 'Pre-Conference Workshop Payment')

@section('delegate-content')
    <style>
        .payment-checkout-container {
            max-width: 1060px;
            margin: 0 auto;
        }
        .hero-header-card {
            background: linear-gradient(135deg, #064e3b 0%, #059669 60%, #10b981 100%);
            border-radius: 16px 16px 0 0;
            color: #ffffff;
        }
        .method-card {
            border-radius: 16px;
            transition: all 0.25s ease;
            position: relative;
            overflow: hidden;
        }
        .method-card:hover {
            box-shadow: 0 10px 30px rgba(0,0,0,0.08) !important;
        }
        .method-card-sbi {
            border: 2px solid #059669 !important;
            background: #ffffff;
        }
        .method-card-qr {
            border: 2px solid #2e3192 !important;
            background: #ffffff;
        }
        .method-header-sbi {
            background: linear-gradient(135deg, #059669, #10b981);
            color: #ffffff;
            padding: 14px 20px;
            border-radius: 13px 13px 0 0;
        }
        .method-header-qr {
            background: linear-gradient(135deg, #2e3192, #4a5bcc);
            color: #ffffff;
            padding: 14px 20px;
            border-radius: 13px 13px 0 0;
        }
        .qr-frame-box {
            background: #ffffff;
            border: 2px dashed #cbd5e1;
            border-radius: 16px;
            padding: 16px;
            display: inline-block;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            position: relative;
        }
        .qr-frame-box img {
            max-width: 220px;
            height: auto;
            border-radius: 10px;
        }
        .divider-or {
            display: flex;
            align-items: center;
            text-align: center;
            margin: 32px 0;
        }
        .divider-or::before,
        .divider-or::after {
            content: '';
            flex: 1;
            border-bottom: 2px dashed #cbd5e1;
        }
        .divider-or span {
            padding: 8px 18px;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 30px;
            font-size: 13px;
            font-weight: 700;
            letter-spacing: 0.5px;
            color: #64748b;
            text-transform: uppercase;
        }
        .pill-badge {
            font-size: 12px;
            padding: 4px 10px;
            border-radius: 20px;
            font-weight: 600;
        }
        .payment-channel-badge {
            background: #f1f5f9;
            color: #334155;
            font-size: 12px;
            font-weight: 600;
            padding: 6px 12px;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            border: 1px solid #e2e8f0;
        }
        .step-bubble {
            width: 28px;
            height: 28px;
            background: #2e3192;
            color: #fff;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 13px;
            font-weight: 700;
            flex-shrink: 0;
        }
        .btn-sbi-cme-pay {
            background: linear-gradient(135deg, #059669 0%, #10b981 100%);
            border: none;
            color: #fff;
            padding: 14px 24px;
            border-radius: 12px;
            font-weight: 700;
            font-size: 17px;
            box-shadow: 0 6px 20px rgba(5, 150, 105, 0.35);
            transition: all 0.2s ease;
        }
        .btn-sbi-cme-pay:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(5, 150, 105, 0.45);
            color: #ffffff;
        }
        .btn-qr-submit {
            background: linear-gradient(135deg, #2e3192 0%, #4a5bcc 100%);
            border: none;
            color: #fff;
            padding: 14px 24px;
            border-radius: 12px;
            font-weight: 700;
            font-size: 16px;
            box-shadow: 0 6px 20px rgba(46, 49, 146, 0.3);
            transition: all 0.2s ease;
        }
        .btn-qr-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(46, 49, 146, 0.4);
            color: #ffffff;
        }
    </style>

    <div class="container py-3">
        <div class="payment-checkout-container">

            <!-- Main Container Card -->
            <div class="card shadow-lg border-0" style="border-radius: 16px;">
                
                <!-- Hero Header (Compact) -->
                <div class="hero-header-card px-3 px-md-4 py-2.5 py-md-3">
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2.5">
                        <div>
                            <div class="d-flex align-items-center gap-2 mb-1">
                                <span class="badge bg-warning text-dark px-2.5 py-0.5 rounded-pill fw-bold" style="font-size: 10px; letter-spacing: 0.5px;">
                                    PRE-CONFERENCE WORKSHOP
                                </span>
                                <span class="text-white-50" style="font-size: 11px;"><i class="fas fa-shield-alt text-warning me-1"></i>256-Bit SSL Secured</span>
                            </div>
                            <h4 class="text-white mb-0.5 fw-bold d-flex align-items-center" style="font-size: 1.25rem;">
                                <i class="fas fa-stethoscope me-2 text-warning fs-5"></i>Pre-Conference Workshop Payment
                            </h4>
                            <p class="text-white-50 mb-0" style="font-size: 0.78rem;">
                                IPHACON 2027 • RIMS Ranchi • Specialized CME Workshop
                            </p>
                        </div>
                        <div class="text-md-end px-3 py-2 rounded-3 shadow-sm d-flex flex-column justify-content-center" style="background: #ffffff !important; border: 1px solid #e2e8f0; min-width: 210px;">
                            <span class="d-block text-muted text-uppercase fw-bold" style="letter-spacing: 0.5px; font-size: 10.5px; margin-bottom: 1px;">Total Workshop Fee</span>
                            <div class="fw-bolder" style="color: #059669; font-size: 1.5rem; line-height: 1.15;">
                                ₹2,360.00 <span class="fw-normal text-muted" style="font-size: 0.75rem;">INR</span>
                            </div>
                            <small class="text-success fw-bold d-block" style="font-size: 10.5px; margin-top: 1px;">
                                <i class="fas fa-check-circle me-1"></i>(₹2,000 + 18% GST ₹360)
                            </small>
                        </div>
                    </div>
                </div>

                <div class="card-body p-3 p-md-4 bg-light">

                    <!-- Session / Alert Messages -->
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show mb-4 border-0 shadow-sm" role="alert" style="border-radius: 12px; background: #ecfdf5; border-left: 5px solid #10b981 !important;">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-check-circle text-success fs-4 me-3"></i>
                                <div>
                                    <h6 class="mb-0 fw-bold text-success">Success</h6>
                                    <div class="text-dark small">{{ session('success') }}</div>
                                </div>
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show mb-4 border-0 shadow-sm" role="alert" style="border-radius: 12px; background: #fef2f2; border-left: 5px solid #ef4444 !important;">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-exclamation-circle text-danger fs-4 me-3"></i>
                                <div>
                                    <h6 class="mb-0 fw-bold text-danger">Payment Error</h6>
                                    <div class="text-dark small">{{ session('error') }}</div>
                                </div>
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show mb-4 border-0 shadow-sm" role="alert" style="border-radius: 12px; background: #fef2f2; border-left: 5px solid #ef4444 !important;">
                            <div class="d-flex">
                                <i class="fas fa-exclamation-triangle text-danger fs-4 me-3 mt-1"></i>
                                <div>
                                    <h6 class="mb-1 fw-bold text-danger">Please correct the following:</h6>
                                    <ul class="mb-0 ps-3 small text-dark">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <!-- ========================================================================= -->
                    <!-- SECTION 1: SBI ONLINE PAYMENT (DEDICATED SECTION)                        -->
                    <!-- ========================================================================= -->
                    <div class="method-card method-card-sbi shadow-sm mb-4">
                        <div class="method-header-sbi d-flex flex-wrap justify-content-between align-items-center gap-2">
                            <div class="d-flex align-items-center gap-2">
                                <div class="bg-white text-success rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                                    <i class="fas fa-bolt text-warning fs-5"></i>
                                </div>
                                <div>
                                    <h5 class="mb-0 fw-bold text-white">OPTION 1: Instant Online Payment (SBI ePay)</h5>
                                    <small class="text-white-50">State Bank of India Official Payment Gateway</small>
                                </div>
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                <span class="badge bg-warning text-dark pill-badge fw-bold shadow-sm">
                                    <i class="fas fa-star me-1"></i>RECOMMENDED
                                </span>
                                <span class="badge bg-white text-success pill-badge fw-bold shadow-sm">
                                    <i class="fas fa-check-circle text-success me-1"></i>INSTANT CONFIRMATION
                                </span>
                            </div>
                        </div>

                        <div class="card-body p-4 bg-white">
                            <div class="row align-items-center g-4">
                                <div class="col-lg-7">
                                    <h6 class="fw-bold text-dark mb-3">
                                        <i class="fas fa-shield-alt text-success me-2"></i>Pay Instantly & Get Confirmed Workshop Seat
                                    </h6>
                                    <ul class="list-unstyled mb-3 text-secondary small">
                                        <li class="mb-2 d-flex align-items-start gap-2">
                                            <i class="fas fa-check-circle text-success mt-1"></i>
                                            <span><strong>Instant Workshop Confirmation:</strong> Seat is immediately reserved upon successful transaction.</span>
                                        </li>
                                        <li class="mb-2 d-flex align-items-start gap-2">
                                            <i class="fas fa-check-circle text-success mt-1"></i>
                                            <span><strong>Automated Receipt:</strong> Instant download of official GST invoice/receipt.</span>
                                        </li>
                                        <li class="mb-0 d-flex align-items-start gap-2">
                                            <i class="fas fa-check-circle text-success mt-1"></i>
                                            <span><strong>All Cards & Banks Supported:</strong> Debit/Credit Cards, Net Banking, UPI, and Wallets.</span>
                                        </li>
                                    </ul>

                                    <div class="d-flex flex-wrap gap-2 pt-2 border-top">
                                        <span class="payment-channel-badge"><i class="fas fa-credit-card text-primary"></i> Credit / Debit Cards</span>
                                        <span class="payment-channel-badge"><i class="fas fa-university text-success"></i> Net Banking</span>
                                        <span class="payment-channel-badge"><i class="fas fa-mobile-alt text-info"></i> UPI</span>
                                        <span class="payment-channel-badge"><i class="fas fa-wallet text-warning"></i> Wallets</span>
                                    </div>
                                </div>

                                <div class="col-lg-5">
                                    <div class="p-4 bg-light rounded-4 border text-center shadow-sm">
                                        <small class="text-muted d-block text-uppercase fw-semibold mb-1" style="font-size: 11px;">Workshop Fee Payable</small>
                                        <h3 class="fw-bolder text-success mb-3">₹2,360.00</h3>
                                        
                                        <a href="{{ route('cme.payment.sbi.initiate', $cmeApp->id) }}" class="btn btn-sbi-cme-pay w-100 mb-2 text-white">
                                            <i class="fas fa-lock me-2"></i>Pay Online with SBI ePay <i class="fas fa-arrow-right ms-2"></i>
                                        </a>

                                        <p class="text-muted mb-0" style="font-size: 11px;">
                                            <i class="fas fa-info-circle me-1"></i>Redirects to State Bank of India secure gateway.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- OR DIVIDER -->
                    <div class="divider-or">
                        <span>OR PAY VIA UPI QR CODE / DIRECT TRANSFER</span>
                    </div>

                    <!-- ========================================================================= -->
                    <!-- SECTION 2: UPI QR CODE & PROOF UPLOAD (DEDICATED SECTION)                 -->
                    <!-- ========================================================================= -->
                    <div class="method-card method-card-qr shadow-sm mb-4">
                        <div class="method-header-qr d-flex flex-wrap justify-content-between align-items-center gap-2">
                            <div class="d-flex align-items-center gap-2">
                                <div class="bg-white text-primary rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                                    <i class="fas fa-qrcode text-primary fs-5"></i>
                                </div>
                                <div>
                                    <h5 class="mb-0 fw-bold text-white">OPTION 2: Pay via UPI QR Code & Upload Proof</h5>
                                    <small class="text-white-50">Scan & Pay or Bank Transfer</small>
                                </div>
                            </div>
                            <span class="badge bg-white text-primary pill-badge fw-bold shadow-sm">
                                <i class="fas fa-clock text-warning me-1"></i>MANUAL VERIFICATION REQUIRED
                            </span>
                        </div>

                        <div class="card-body p-4 bg-white">
                            <div class="row g-4">
                                <div class="col-lg-5 text-center">
                                    <div class="p-3 bg-light rounded-4 border mb-3">
                                        <h6 class="fw-bold text-dark mb-2">
                                            <i class="fas fa-camera text-primary me-1"></i>Scan QR Code for Workshop
                                        </h6>
                                        <p class="text-muted small mb-3">Scan via GPay, PhonePe, Paytm, BHIM</p>

                                        <div class="qr-frame-box mb-2">
                                            <img src="{{ asset('images/iphacon_qrcode.jpeg') }}"
                                                onerror="this.onerror=null; this.src='{{ asset('public/images/iphacon_qrcode.jpeg') }}';"
                                                alt="Payment QR Code" class="img-fluid shadow-sm">
                                        </div>

                                        <div class="mt-2">
                                            <span class="badge bg-primary text-white px-3 py-1.5 rounded-pill fw-bold" style="font-size: 13px;">
                                                Pay Exact: ₹2,360.00
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-7">
                                    <div class="p-3 bg-light rounded-4 border mb-3">
                                        <h6 class="fw-bold text-dark mb-2">
                                            <i class="fas fa-list-ol text-primary me-2"></i>Follow 3 Steps:
                                        </h6>
                                        <div class="d-flex align-items-start gap-2 mb-2">
                                            <span class="step-bubble">1</span>
                                            <span class="small text-secondary">Scan QR code on the left & pay <strong>₹2,360.00</strong>.</span>
                                        </div>
                                        <div class="d-flex align-items-start gap-2 mb-2">
                                            <span class="step-bubble">2</span>
                                            <span class="small text-secondary">Copy the <strong>12-digit UTR / Transaction ID</strong>.</span>
                                        </div>
                                        <div class="d-flex align-items-start gap-2">
                                            <span class="step-bubble">3</span>
                                            <span class="small text-secondary">Enter UTR & upload receipt screenshot below.</span>
                                        </div>
                                    </div>

                                    <form id="cmePaymentProcessForm" method="POST" action="{{ route('cme.payment.process', $cmeApp->id) }}" enctype="multipart/form-data" class="bg-white p-3 rounded-4 border">
                                        @csrf

                                        <div class="mb-3">
                                            <label for="transaction_id" class="form-label fw-bold text-dark small">
                                                Transaction ID / UTR Number <span class="text-danger">*</span>
                                            </label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-light text-muted"><i class="fas fa-receipt"></i></span>
                                                <input type="text"
                                                    class="form-control @error('transaction_id') is-invalid @enderror"
                                                    id="transaction_id" name="transaction_id"
                                                    placeholder="Enter 12-digit UTR or Transaction ID"
                                                    value="{{ old('transaction_id') }}" required style="padding: 10px;">
                                            </div>
                                            <small class="text-muted d-block mt-1" style="font-size: 11px;">Example: 420192837465</small>
                                            @error('transaction_id')
                                                <div class="text-danger small mt-1">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-4">
                                            <label for="payment_receipt" class="form-label fw-bold text-dark small">
                                                Upload Receipt / Screenshot <span class="text-danger">*</span>
                                            </label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-light text-muted"><i class="fas fa-file-upload"></i></span>
                                                <input type="file"
                                                    class="form-control @error('payment_receipt') is-invalid @enderror"
                                                    id="payment_receipt" name="payment_receipt" accept="image/*,.pdf"
                                                    required style="padding: 10px;">
                                            </div>
                                            <small class="text-muted d-block mt-1" style="font-size: 11px;">Allowed formats: JPG, PNG, PDF (Max 5MB)</small>
                                            @error('payment_receipt')
                                                <div class="text-danger small mt-1">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <button type="submit" id="submitCmePaymentBtn" class="btn btn-qr-submit w-100">
                                            <i class="fas fa-paper-plane me-2"></i>Submit CME Payment Proof
                                        </button>
                                    </form>

                                    <div class="d-flex align-items-center gap-2 mt-3 text-muted small px-1">
                                        <i class="fas fa-info-circle text-primary"></i>
                                        <span>Organizing team will verify the payment within <strong>24 to 48 hours</strong>.</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ========================================================================= -->
                    <!-- SECTION 3: WORKSHOP FEE BREAKDOWN SUMMARY                                 -->
                    <!-- ========================================================================= -->
                    <div class="card border-0 bg-white shadow-sm mt-4" style="border-radius: 14px;">
                        <div class="card-header bg-white border-bottom py-3 px-4 d-flex justify-content-between align-items-center">
                            <h6 class="mb-0 fw-bold text-dark">
                                <i class="fas fa-file-invoice-dollar me-2 text-success"></i>Pre-Conference Workshop Fee Breakdown
                            </h6>
                            <span class="badge bg-light text-dark border px-3 py-1.5 rounded-pill">
                                App ID: CME#{{ $cmeApp->id }}
                            </span>
                        </div>
                        <div class="card-body p-4">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Description</th>
                                            <th class="text-center">Rate / Details</th>
                                            <th class="text-end">Amount (INR)</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>
                                                <strong>Pre-Conference CME / Workshop Base Fee</strong>
                                                <div class="text-muted small">Specialized medical training session</div>
                                            </td>
                                            <td class="text-center">Fixed Workshop Fee</td>
                                            <td class="text-end">₹2,000.00</td>
                                        </tr>
                                        <tr>
                                            <td colspan="2" class="text-end text-warning"><strong>GST (18% Applicable):</strong></td>
                                            <td class="text-end text-warning fw-bold">+ ₹360.00</td>
                                        </tr>
                                        <tr class="table-success fw-bolder fs-6">
                                            <td colspan="2" class="text-end">Total Amount Payable:</td>
                                            <td class="text-end text-success">₹2,360.00</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('cmePaymentProcessForm');
            if (form) {
                form.addEventListener('submit', function(e) {
                    const btn = document.getElementById('submitCmePaymentBtn');
                    if (btn && !btn.disabled) {
                        btn.disabled = true;
                        btn.innerHTML =
                            '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Submitting Proof...';
                        form.submit();
                    }
                });
            }
        });
    </script>
@endsection
