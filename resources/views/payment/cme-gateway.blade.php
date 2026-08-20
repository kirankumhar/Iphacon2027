@extends('shared.auth-delegate')
@section('title', 'Pre-Conference Workshop Payment - Scan QR Code')

@section('delegate-content')
    <div class="container py-2">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card shadow border-0" style="border-radius: 12px;">
                    <div class="card-header text-center py-2.5 px-3"
                        style="background: linear-gradient(135deg, #10B981, #059669); border-radius: 12px 12px 0 0;">
                        <h5 class="text-white mb-0 fw-bold">
                            <i class="fas fa-stethoscope me-2"></i>Pre-Conference Workshop Payment - Scan QR Code
                        </h5>
                    </div>

                    <div class="card-body p-3 p-md-4">

                        <!-- Session / Alert Messages -->
                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show mb-4" role="alert" style="border-radius: 10px;">
                                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        @if (session('error'))
                            <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert" style="border-radius: 10px;">
                                <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        @if ($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert" style="border-radius: 10px;">
                                <i class="fas fa-exclamation-triangle me-2"></i><strong>Attention Required:</strong>
                                <ul class="mb-0 mt-1 ps-3">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        <div class="row g-4">
                            <!-- Left Column: CME Payment Summary & QR Code -->
                            <div class="col-lg-6">
                                <div class="card border shadow-sm h-100" style="border-radius: 12px;">
                                    <div class="card-header bg-success text-white py-3">
                                        <h5 class="mb-0 fw-bold"><i class="fas fa-file-invoice-dollar me-2"></i>Pre-Conference Workshop Fee & QR Code</h5>
                                    </div>
                                    <div class="card-body p-4 text-center">

                                        <!-- Amount Badge -->
                                        <div class="mb-3">
                                            <span class="badge bg-success fs-5 px-4 py-2.5 rounded-pill shadow-sm">
                                                Total Payable: ₹2,360.00 INR
                                            </span>
                                        </div>

                                        <!-- QR Code Image -->
                                        <div class="p-3 bg-light rounded-3 border d-inline-block shadow-sm my-2">
                                            <img src="{{ asset('images/iphacon_qrcode.jpeg') }}" 
                                                 onerror="this.onerror=null; this.src='{{ asset('public/images/iphacon_qrcode.jpeg') }}';" 
                                                 alt="Payment QR Code" class="img-fluid rounded" style="max-width: 220px; height: auto;">
                                        </div>

                                        <p class="mt-2 mb-1 fw-bold text-dark fs-6">
                                            <i class="fas fa-camera me-1 text-success"></i>Scan QR Code to Pay for Pre-Conference Workshop
                                        </p>
                                        <p class="text-muted small mb-3">Use GPay, PhonePe, Paytm, BHIM or any UPI app</p>

                                        <!-- Summary Table -->
                                        <div class="table-responsive text-start mt-3">
                                            <table class="table table-bordered align-middle small mb-0">
                                                <tr>
                                                    <td><strong>Pre-Conference CME / Workshop Base Fee</strong></td>
                                                    <td class="text-end fw-bold">₹2,000.00</td>
                                                </tr>
                                                <tr>
                                                    <td><strong>GST Amount (18%)</strong></td>
                                                    <td class="text-end text-warning fw-bold">+ ₹360.00</td>
                                                </tr>
                                                <tr class="table-success fw-bold">
                                                    <td><strong>Total CME Fee Payable</strong></td>
                                                    <td class="text-end text-success fs-6">₹2,360.00</td>
                                                </tr>
                                            </table>
                                        </div>

                                    </div>
                                </div>
                            </div>

                            <!-- Right Column: Upload CME Payment Receipt Form -->
                            <div class="col-lg-6">
                                <div class="card border-success border-2 shadow-sm h-100" style="border-radius: 12px;">
                                    <div class="card-header bg-success text-white py-3">
                                        <h5 class="mb-0 fw-bold"><i class="fas fa-upload me-2"></i>Upload CME Payment Proof</h5>
                                    </div>
                                    <div class="card-body p-4">
                                        <div class="alert alert-warning small mb-3" style="border-radius: 8px;">
                                            <i class="fas fa-info-circle me-1"></i>
                                            <strong>Payment Steps:</strong>
                                            <ol class="mb-0 ps-3 mt-1">
                                                <li>Scan QR Code on the left and pay ₹2,360.00.</li>
                                                <li>Enter the 12-digit UTR / Transaction ID below.</li>
                                                <li>Upload screenshot/receipt of successful CME payment.</li>
                                            </ol>
                                        </div>

                                        <form id="cmePaymentProcessForm" method="POST" action="{{ route('cme.payment.process', $cmeApp->id) }}" enctype="multipart/form-data">
                                            @csrf

                                            <div class="mb-3">
                                                <label for="transaction_id" class="form-label fw-semibold">
                                                    Transaction ID / UTR Number <span class="text-danger">*</span>
                                                </label>
                                                <input type="text"
                                                    class="form-control @error('transaction_id') is-invalid @enderror"
                                                    id="transaction_id" name="transaction_id"
                                                    placeholder="Enter 12-digit UTR or Transaction ID"
                                                    value="{{ old('transaction_id') }}" required style="border-radius: 8px; padding: 10px;">
                                                <small class="text-muted">Example: 420192837465</small>
                                                @error('transaction_id')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="mb-4">
                                                <label for="payment_receipt" class="form-label fw-semibold">
                                                    Payment Receipt / Screenshot <span class="text-danger">*</span>
                                                </label>
                                                <input type="file"
                                                    class="form-control @error('payment_receipt') is-invalid @enderror"
                                                    id="payment_receipt" name="payment_receipt" accept="image/*,.pdf" required style="border-radius: 8px;">
                                                <small class="text-muted">Allowed formats: JPG, PNG, PDF (Max 5MB)</small>
                                                @error('payment_receipt')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <button type="submit" id="submitCmePaymentBtn" class="btn btn-success btn-lg w-100 fw-bold shadow-sm" style="border-radius: 8px; background: linear-gradient(135deg, #10B981, #059669); border: none;">
                                                <i class="fas fa-paper-plane me-2"></i>Submit CME Payment Proof
                                            </button>
                                        </form>

                                    </div>
                                </div>
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
                    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Submitting...';
                    form.submit();
                }
            });
        }
    });
    </script>
@endsection
