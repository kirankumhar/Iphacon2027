<h4 class="text-primary mb-4"><i class="fas fa-credit-card me-2"></i>Step 4: Payment</h4>

@if ($registration->delegate_type == 'International')
    <!-- Foreign Delegate Payment -->

    @if ($registration->reverted_at)
        <div class="alert alert-warning alert-dismissible mx-3 mt-3" role="alert">
            Revert Reason : {{ $registration->revert_reason }}
        </div>
    @endif

    <div class="row">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header text-white text-center py-3"
                    style="background: linear-gradient(135deg, #17a2b8, #138496);">
                    <h5 class="mb-0"><i class="fas fa-dollar-sign me-2"></i>Foreign Delegate Payment</h5>
                </div>
                <div class="card-body text-center">
                    <div class="mb-3">
                        <span class="badge bg-success fs-5 px-3 py-2">Total Amount: $175.00</span>
                    </div>

                        <div class="border rounded p-3 text-center" style="background: #f8f9fa;">
                            <img src="{{ asset('images/iphacon_qrcode.jpeg') }}" 
                                 onerror="this.onerror=null; this.src='{{ asset('public/images/iphacon_qrcode.jpeg') }}';"
                                 alt="Payment QR Code"
                                 class="img-fluid border rounded shadow-sm" style="max-width: 220px; height: auto;">
                            <p class="mt-3 mb-0"><strong><i class="fas fa-qrcode me-1"></i>Scan QR Code to Pay
                                    $175.00</strong></p>
                        </div>

                    <div class="alert alert-warning text-start">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>Payment Instructions:</strong>
                        <ol class="mb-0 mt-2">
                            <li>Scan the QR code above using any payment app</li>
                            <li>Pay exactly <strong>$175.00 USD</strong></li>
                            <li>Take screenshot of successful payment</li>
                            <li>Fill transaction details on the right side</li>
                            <li>Upload payment receipt/screenshot</li>
                        </ol>
                    </div>

                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        <small>Accepted: PayPal, Bank Transfer, Credit/Debit Cards</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header text-white py-3" style="background: linear-gradient(135deg, #28a745, #218838);">
                    <h5 class="mb-0"><i class="fas fa-upload me-2"></i>Payment Verification</h5>
                </div>
                <div class="card-body">
                    <!-- Transaction Number -->
                    <div class="mb-3">
                        <label for="transaction_id" class="form-label fw-semibold" style="color:#2e3192">
                            <i class="fas fa-hashtag me-1"></i>Transaction Number<span class="text-danger">*</span>
                        </label>
                        <input type="text" class="form-control" id="transaction_id" name="transaction_id"
                            value="" required maxlength="30" placeholder="Enter transaction/reference number"
                            style="border-radius: 8px; padding: 10px;"
                            oninput="this.value = this.value.replace(/[^A-Za-z0-9]/g, '')">
                        <small class="text-muted">Enter the transaction ID from your payment confirmation</small>
                        @error('transaction_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Payment Receipt Upload -->
                    <div class="mb-3">
                        <label for="payment_receipt" class="form-label fw-semibold" style="color:#2e3192">
                            <i class="fas fa-file-upload me-1"></i>Upload Payment Receipt<span
                                class="text-danger">*</span>
                        </label>
                        <input type="file" class="form-control @error('payment_receipt') is-invalid @enderror"
                            id="payment_receipt" name="payment_receipt" accept=".pdf,.jpg,.jpeg,.png" required
                            style="border-radius: 8px; padding: 10px;">
                        <small class="text-muted">Upload payment receipt/screenshot (PDF, JPG, JPEG, PNG - Max
                            1MB)</small>

                        <!-- Inline Thumbnail Preview -->
                        <div class="mt-2">
                            <img id="receiptPreviewThumb" alt="Receipt preview"
                                style="max-width:200px; max-height:150px; border-radius:8px; border: 2px solid #dee2e6; display:none; cursor:pointer;">
                            <div id="fileInfo" class="mt-1 text-muted small" style="display:none;">
                                <i class="fas fa-file me-1"></i><span id="fileName"></span>
                            </div>
                        </div>

                        <!-- Live Preview Modal -->
                        <div class="modal fade" id="receiptPreviewModal" tabindex="-1"
                            aria-labelledby="receiptPreviewModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered modal-xl">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="receiptPreviewModalLabel">Payment Receipt Preview
                                        </h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body text-center" id="receiptModalBody">
                                        <!-- Content added by JS -->
                                    </div>
                                </div>
                            </div>
                        </div>

                        @error('payment_receipt')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="alert alert-info">
                        <i class="fas fa-clock me-2"></i>
                        After submitting, your registration will be marked as <strong>"Payment Submitted"</strong>
                        and will be verified by our team within 24-48 hours.
                    </div>

                    <!-- Payment Status Info -->
                    <div class="border rounded p-3" style="background: #f8f9fa;">
                        <h6 class="text-primary mb-2"><i class="fas fa-info-circle me-1"></i>What happens next?</h6>
                        <ul class="small mb-0">
                            <li>Payment verification: 24-48 hours</li>
                            <li>Email confirmation upon verification</li>
                            <li>Registration certificate will be available for download</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@else
    <!-- Indian Delegate Payment -->
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header text-white py-3"
                    style="background: linear-gradient(135deg, #2e3192, #4a5bcc);">
                    <h5 class="mb-0 text-center"><i class="fas fa-rupee-sign me-2"></i>Payment Summary</h5>
                </div>
                <div class="card-body">
                    @php
                        $catBase = $registration->delegateCategory ? (float)$registration->delegateCategory->indian_fee : 0;
                        $cmeBase = $registration->cme_fee ?: ($registration->participate_in_cme ? 1000 : 0);
                        $accBase = $registration->accompanying_fee ?: (($registration->accompanying_persons ?? 0) * 5000);
                        $subtotalBase = $catBase + $cmeBase + $accBase;
                        $gstAmt = $registration->gst_amount ?: round($subtotalBase * 0.18, 2);
                        $totalAmt = $registration->total_amount ?: round($subtotalBase + $gstAmt, 2);
                    @endphp
                    <table class="table table-bordered mb-0">
                        <tr>
                            <td><strong>Delegate Category (Base Price)</strong></td>
                            <td class="text-end">₹{{ number_format($catBase, 2) }}</td>
                        </tr>
                        @if ($registration->participate_in_cme)
                            <tr>
                                <td><strong>CME / Workshop Participation</strong></td>
                                <td class="text-end">₹{{ number_format($cmeBase, 2) }}</td>
                            </tr>
                        @endif
                        @if (($registration->accompanying_persons ?? 0) > 0)
                            <tr>
                                <td><strong>Accompanying Person ({{ $registration->accompanying_persons }})</strong></td>
                                <td class="text-end">₹{{ number_format($accBase, 2) }}</td>
                            </tr>
                        @endif
                        <tr>
                            <td><strong>GST Amount (18%)</strong></td>
                            <td class="text-end text-warning fw-bold">+ ₹{{ number_format($gstAmt, 2) }}</td>
                        </tr>
                        <tr class="table-success">
                            <td><strong>Total Amount Payable</strong></td>
                            <td class="text-end">
                                <strong class="fs-5">₹{{ number_format($totalAmt, 2) }}</strong>
                            </td>
                        </tr>
                    </table>

                    <div class="text-center mt-4">
                        <button type="button" class="btn btn-lg px-5 py-3" onclick="proceedToPayment()"
                            style="background: linear-gradient(135deg, #2e3192, #4a5bcc); border: none; border-radius: 25px; color: white;">
                            <i class="fas fa-credit-card me-2"></i>Pay Now -
                            ₹{{ number_format($registration->calculateTotalAmount()) }}
                        </button>
                    </div>

                    <div class="alert alert-info mt-4">
                        <i class="fas fa-shield-alt me-2"></i>
                        <strong>Secure Payment:</strong> You will be redirected to our secure payment gateway.
                        After successful payment, your registration will be completed automatically.
                    </div>

                    <!-- Accepted Payment Methods -->
                    <div class="text-center mt-3">
                        <small class="text-muted">
                            <i class="fas fa-credit-card me-1"></i>
                            We accept: Credit Cards, Debit Cards, Net Banking, UPI, Wallets
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    // File upload preview and validation for foreign delegates
    @if ($registration->delegate_type == 'International')
        document.addEventListener('DOMContentLoaded', function() {
            const fileInput = document.getElementById('payment_receipt');
            const previewThumb = document.getElementById('receiptPreviewThumb');
            const fileInfo = document.getElementById('fileInfo');
            const fileName = document.getElementById('fileName');
            const modalBody = document.getElementById('receiptModalBody');

            fileInput.addEventListener('change', function() {
                const file = this.files[0];
                if (!file) return;

                // Validate file size
                if (file.size > 1 * 1024 * 1024) {
                    alert('File size must be less than 1MB!');
                    this.value = '';
                    previewThumb.style.display = 'none';
                    fileInfo.style.display = 'none';
                    return;
                }

                fileName.textContent = file.name;
                fileInfo.style.display = 'block';

                // If it's an image, show thumbnail and modal
                if (file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        previewThumb.src = e.target.result;
                        previewThumb.style.display = 'block';

                        // Click thumbnail to open modal
                        previewThumb.onclick = function() {
                            modalBody.innerHTML =
                                `<img src="${e.target.result}" class="img-fluid rounded shadow">`;
                            new bootstrap.Modal(document.getElementById(
                                'receiptPreviewModal'), {
                                backdrop: false
                            }).show();
                        };
                    };
                    reader.readAsDataURL(file);
                }
                // If it's a PDF, show icon and allow modal open
                else if (file.type === 'application/pdf') {
                    previewThumb.style.display = 'none';
                    previewThumb.onclick = null;
                    previewThumb.src = '';

                    // Click file name to open PDF modal
                    fileInfo.onclick = function() {
                        const pdfURL = URL.createObjectURL(file);
                        modalBody.innerHTML =
                            `<iframe src="${pdfURL}" width="100%" height="600px" style="border:none;"></iframe>`;
                        new bootstrap.Modal(document.getElementById('receiptPreviewModal'), {
                            backdrop: false
                        }).show();
                    };
                } else {
                    alert('Only PDF, JPG, JPEG, and PNG formats are allowed!');
                    this.value = '';
                    previewThumb.style.display = 'none';
                    fileInfo.style.display = 'none';
                }
            });
        });
    @else
        @php
            $gatewayData = json_encode([
                'reg_id' => $registration->id,
                'uid' => auth()->id(),
            ]);
            $encrypted = Crypt::encryptString($gatewayData);

        @endphp
        // Redirect to payment gateway
        setTimeout(() => {
            window.location.href = '{{ route('payment.gateway', $encrypted) }}';
        }, 1000);
    @endif

    @if ($registration->reverted_at)
        $(document).ready(function() {
            $(".btn.btn-outline-secondary.btn-lg.px-4").remove();
        });
    @endif
</script>
