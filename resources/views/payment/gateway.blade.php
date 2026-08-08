@extends('shared.auth-delegate')
@section('title', 'Payment - Scan QR Code')

@section('delegate-content')
    <div class="container py-2">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card shadow border-0" style="border-radius: 12px;">
                    <div class="card-header text-center py-2.5 px-3"
                        style="background: linear-gradient(135deg, #2e3192, #4a5bcc); border-radius: 12px 12px 0 0;">
                        <h5 class="text-white mb-0 fw-bold">
                            <i class="fas fa-qrcode me-2"></i>Payment - Scan QR Code
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
                            <!-- Left Column: Payment Summary & QR Code Image -->
                            <div class="col-lg-6">
                                <div class="card border shadow-sm h-100" style="border-radius: 12px;">
                                    <div class="card-header bg-primary text-white py-3">
                                        <h5 class="mb-0 fw-bold"><i class="fas fa-file-invoice-dollar me-2"></i>Payment Details & QR Code</h5>
                                    </div>
                                    <div class="card-body p-4 text-center">

                                        @php
                                            $catBase = $registration->delegateCategory ? (float)$registration->delegateCategory->indian_fee : 0;
                                            $cmeBase = $registration->cme_fee ?: ($registration->participate_in_cme ? 2000 : 0);
                                            $accBase = $registration->accompanying_fee ?: (($registration->accompanying_persons ?? 0) * 5000);
                                            $subtotalBase = $catBase + $cmeBase + $accBase;
                                            $gstAmt = $registration->gst_amount ?: round($subtotalBase * 0.18, 2);
                                            $totalAmt = $registration->total_amount ?: round($subtotalBase + $gstAmt, 2);
                                        @endphp

                                        <!-- Amount Badge -->
                                        <div class="mb-3">
                                            <span class="badge bg-success fs-5 px-4 py-2.5 rounded-pill shadow-sm">
                                                Total Payable:
                                                @if ($registration->delegate_type === 'International')
                                                    $175.00 USD
                                                @else
                                                    ₹{{ number_format($totalAmt, 2) }} INR
                                                @endif
                                            </span>
                                        </div>

                                        <!-- QR Code Image -->
                                        <div class="p-3 bg-light rounded-3 border d-inline-block shadow-sm my-2">
                                            <img src="{{ asset('images/iphacon_qrcode.jpeg') }}" 
                                                 onerror="this.onerror=null; this.src='{{ asset('public/images/iphacon_qrcode.jpeg') }}';" 
                                                 alt="Payment QR Code" class="img-fluid rounded" style="max-width: 220px; height: auto;">
                                        </div>

                                        <p class="mt-2 mb-1 fw-bold text-dark fs-6">
                                            <i class="fas fa-camera me-1 text-primary"></i>Scan QR Code to Pay
                                        </p>
                                        <p class="text-muted small mb-3">Use GPay, PhonePe, Paytm, BHIM or any UPI app</p>

                                        <!-- Summary Table -->
                                        <div class="table-responsive text-start mt-3">
                                            <table class="table table-bordered align-middle small mb-0">
                                                @if ($registration->delegate_type === 'International')
                                                    <tr>
                                                        <td><strong>Delegate Category (Foreign)</strong></td>
                                                        <td class="text-end fw-bold">$175.00</td>
                                                    </tr>
                                                @else
                                                    <tr>
                                                        <td><strong>Delegate Category (Base Price)</strong></td>
                                                        <td class="text-end">₹{{ number_format($catBase, 2) }}</td>
                                                    </tr>
                                                    @if ($registration->participate_in_cme)
                                                        <tr>
                                                            <td><strong>CME/Workshop Participation</strong></td>
                                                            <td class="text-end">₹{{ number_format($cmeBase, 2) }}</td>
                                                        </tr>
                                                    @endif
                                                    @if (($registration->accompanying_persons ?? 0) > 0)
                                                        <tr>
                                                            <td><strong>Accompanying Persons ({{ $registration->accompanying_persons }})</strong></td>
                                                            <td class="text-end">₹{{ number_format($accBase, 2) }}</td>
                                                        </tr>
                                                    @endif
                                                    <tr>
                                                        <td><strong>GST Amount (18%)</strong></td>
                                                        <td class="text-end text-warning fw-bold">+ ₹{{ number_format($gstAmt, 2) }}</td>
                                                    </tr>
                                                    <tr class="table-success fw-bold">
                                                        <td><strong>Total Amount Payable</strong></td>
                                                        <td class="text-end">₹{{ number_format($totalAmt, 2) }}</td>
                                                    </tr>
                                                @endif
                                            </table>
                                        </div>

                                    </div>
                                </div>
                            </div>

                            <!-- Right Column: Upload Payment Receipt Form -->
                            <div class="col-lg-6">
                                <div class="card border-success border-2 shadow-sm h-100" style="border-radius: 12px;">
                                    <div class="card-header bg-success text-white py-3">
                                        <h5 class="mb-0 fw-bold"><i class="fas fa-upload me-2"></i>Upload Payment Proof</h5>
                                    </div>
                                    <div class="card-body p-4">
                                        <div class="alert alert-warning small mb-3" style="border-radius: 8px;">
                                            <i class="fas fa-info-circle me-1"></i>
                                            <strong>Payment Steps:</strong>
                                            <ol class="mb-0 ps-3 mt-1">
                                                <li>Scan QR Code on the left and complete payment.</li>
                                                <li>Enter the 12-digit UTR / Transaction ID below.</li>
                                                <li>Upload screenshot/receipt of successful payment.</li>
                                            </ol>
                                        </div>

                                        <form method="POST" action="{{ route('payment.process', $registration->id) }}" enctype="multipart/form-data">
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
                                                    Upload Receipt / Screenshot <span class="text-danger">*</span>
                                                </label>
                                                <input type="file"
                                                    class="form-control @error('payment_receipt') is-invalid @enderror"
                                                    id="payment_receipt" name="payment_receipt"
                                                    accept=".pdf,.jpg,.jpeg,.png" required style="border-radius: 8px; padding: 10px;">
                                                <small class="text-muted">Allowed formats: PDF, JPG, JPEG, PNG (Max 5MB)</small>
                                                @error('payment_receipt')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <button type="submit" class="btn btn-success btn-lg w-100 fw-bold shadow-sm" style="border-radius: 8px;">
                                                <i class="fas fa-check-circle me-2"></i>Submit Payment Details
                                            </button>
                                        </form>

                                        <div class="alert alert-info mt-4 mb-0 small" style="border-radius: 8px;">
                                            <i class="fas fa-clock me-1"></i>
                                            After submission, your registration status will be updated and verified by the organizing team.
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Registration Info Summary -->
                        <div class="row justify-content-center mt-4">
                            <div class="col-12">
                                <div class="card bg-light border-0 shadow-sm" style="border-radius: 12px;">
                                    <div class="card-header bg-white border-bottom fw-bold text-dark">
                                        <i class="fas fa-user-check me-2 text-primary"></i>Registration Information
                                    </div>
                                    <div class="card-body p-3.5">
                                        <div class="row g-2">
                                            <div class="col-md-6">
                                                <p class="mb-1"><strong>Full Name:</strong> {{ $registration->user->prefix ?? '' }} {{ $registration->user->full_name ?? '' }}</p>
                                                <p class="mb-0"><strong>Email:</strong> {{ $registration->user->email ?? '' }}</p>
                                            </div>
                                            <div class="col-md-6">
                                                <p class="mb-1"><strong>Delegate Type:</strong> {{ $registration->delegate_type }}</p>
                                                <p class="mb-0"><strong>Category:</strong> {{ $registration->delegateCategory->category_name ?? 'N/A' }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
