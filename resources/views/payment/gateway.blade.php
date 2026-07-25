@extends('shared.auth-delegate')
@section('title', 'Make Payment')

@section('delegate-content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card shadow-lg border-0" style="border-radius: 15px;">
                    <div class="card-header text-center py-4"
                        style="background: linear-gradient(135deg, #2e3192, #4a5bcc); border-radius: 15px 15px 0 0;">
                        <h3 class="text-white mb-0 fw-bold">
                            <i class="fas fa-credit-card me-2"></i>Payment Gateway
                        </h3>
                    </div>

                    <div class="card-body p-5">
                        @if ($registration->delegate_type === 'Foreign')
                            <!-- Foreign Delegate Payment -->
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="card">
                                        <div class="card-header bg-info text-white">
                                            <h5 class="mb-0">Payment Information</h5>
                                        </div>
                                        <div class="card-body">
                                            <h3 class="text-center text-success mb-3">Total Amount: $175.00</h3>

                                            <div class="text-center mb-4">
                                                <div class="p-4 bg-light rounded">
                                                    <i class="fas fa-qrcode fa-5x text-primary mb-2"></i>
                                                    <p><strong>Scan QR Code to Pay</strong></p>
                                                    <small class="text-muted">Use any UPI app or international payment
                                                        method</small>
                                                </div>
                                            </div>

                                            <div class="alert alert-warning">
                                                <strong>Payment Instructions:</strong>
                                                <ul class="mb-0 mt-2">
                                                    <li>Pay $175.00 using the QR code</li>
                                                    <li>Upload payment receipt below</li>
                                                    <li>Enter transaction details</li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="card">
                                        <div class="card-header bg-success text-white">
                                            <h5 class="mb-0">Upload Payment Proof</h5>
                                        </div>
                                        <div class="card-body">
                                            <form method="POST" action="{{ route('payment.process', $registration->id) }}"
                                                enctype="multipart/form-data">
                                                @csrf

                                                <div class="mb-3">
                                                    <label for="transaction_id" class="form-label fw-semibold">
                                                        Transaction ID<span class="text-danger">*</span>
                                                    </label>
                                                    <input type="text"
                                                        class="form-control @error('transaction_id') is-invalid @enderror"
                                                        id="transaction_id" name="transaction_id"
                                                        value="{{ old('transaction_id') }}" required>
                                                    @error('transaction_id')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>

                                                <div class="mb-3">
                                                    <label for="payment_receipt" class="form-label fw-semibold">
                                                        Payment Receipt<span class="text-danger">*</span>
                                                    </label>
                                                    <input type="file"
                                                        class="form-control @error('payment_receipt') is-invalid @enderror"
                                                        id="payment_receipt" name="payment_receipt"
                                                        accept=".pdf,.jpg,.jpeg,.png" required>
                                                    <small class="text-muted">Upload receipt/screenshot (PDF, JPG, PNG - Max
                                                        5MB)</small>
                                                    @error('payment_receipt')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>

                                                <button type="submit" class="btn btn-success btn-lg w-100">
                                                    <i class="fas fa-upload me-2"></i>Submit Payment Details
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @else
                            <!-- Indian Delegate Payment -->
                            <div class="row justify-content-center">
                                <div class="col-md-8">
                                    <div class="card">
                                        <div class="card-header bg-primary text-white">
                                            <h5 class="mb-0">Payment Summary</h5>
                                        </div>
                                        <div class="card-body">
                                            <table class="table table-bordered">
                                                <tr>
                                                    <td><strong>Delegate Category</strong></td>
                                                    <td class="text-end">
                                                        ₹{{ number_format($registration->delegateCategory->indian_fee) }}
                                                    </td>
                                                </tr>
                                                @if ($registration->accompanying_persons > 0)
                                                    <tr>
                                                        <td><strong>Accompanying Person </strong></td>
                                                        <td class="text-end">
                                                            ₹{{ number_format($registration->accompanying_persons * 4000) }}
                                                        </td>
                                                    </tr>
                                                @endif
                                                @if ($registration->participate_in_cme)
                                                    <tr>
                                                        <td><strong> CME/Workshop Participation</strong></td>
                                                        <td class="text-end">₹1,500</td>
                                                    </tr>
                                                @endif
                                                <tr class="table-success">
                                                    <td><strong>Total Amount</strong></td>
                                                    <td class="text-end">
                                                        <strong>₹{{ number_format($registration->calculateTotalAmount()) }}</strong>
                                                    </td>
                                                </tr>
                                            </table>

                                            <div class="text-center mt-4">

                                                <a class="btn btn-primary btn-lg px-5 py-3" href="javascript:openPay()"
                                                    role="button" <i class="fas fa-credit-card me-2"></i>Pay Now -
                                                    ₹{{ number_format($registration->calculateTotalAmount()) }}
                                                </a>

                                            </div>

                                            <div class="alert alert-info mt-4">
                                                <i class="fas fa-shield-alt me-2"></i>
                                                <strong>Secure Payment:</strong> Your payment is processed securely.
                                                After successful payment, your registration will be completed automatically.
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Registration Summary -->
                        <div class="row mt-4">
                            <div class="col-md-12">
                                <div class="card bg-light">
                                    <div class="card-header">
                                        <h6 class="mb-0">Registration Summary</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <p><strong>Full Name:</strong> {{ $registration->user->prefix }}
                                                    {{ $registration->user->full_name }}</p>
                                                <p><strong>Email:</strong> {{ $registration->user->email }}</p>
                                            </div>
                                            <div class="col-md-6">
                                                <p><strong>Delegate Type:</strong> {{ $registration->delegate_type }}</p>
                                                <p><strong>Category:</strong>
                                                    {{ $registration->delegateCategory->category_name }}</p>
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

    <script src="https://psa.atomtech.in/staticdata/ots/js/atomcheckout.js"></script>
    <script>
        function openPay() {

            const options = {
                "atomTokenId": {{ $data['atomTokenId'] }},
                "merchId": {{ $data['mid'] }},
                "custEmail": "{{ $data['email'] }}",
                "custMobile": "{{ $data['mobile'] }}",
                "returnUrl": "{{ $data['return_url'] }}"
            }
            let atom = new AtomPaynetz(options, 'uat');
        }
    </script>
@endsection
