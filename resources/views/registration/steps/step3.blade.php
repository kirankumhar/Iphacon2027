{{-- resources/views/registration/steps/step3.blade.php --}}
@php
    $step1Token = Crypt::encryptString(json_encode(['step' => 1, 'uid' => auth()->id()]));
    $step2Token = Crypt::encryptString(json_encode(['step' => 2, 'uid' => auth()->id()]));
@endphp

<!-- Step 3 Header -->
<div class="d-flex align-items-center justify-content-between mb-2.5 pb-2 border-bottom">
    <div class="d-flex align-items-center gap-2">
        <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
            style="width: 34px; height: 34px; background: linear-gradient(135deg, #2D69FF 0%, #1A52E0 100%); color: #ffffff; font-size: 0.95rem;">
            <i class="fas fa-eye"></i>
        </div>
        <div>
            <h6 class="fw-bold mb-0 text-dark" style="font-size: 1.05rem;">Step 3: Preview Registration</h6>
            <small class="text-muted extra-small">Review all information carefully before final submission</small>
        </div>
    </div>
    <span class="badge px-2.5 py-1 extra-small fw-bold" style="background-color: #E1F0FF; color: #2D69FF; border-radius: 20px;">
        <i class="fas fa-flag me-1"></i> {{ $registration->delegate_type }} Delegate
    </span>
</div>

<div class="row g-2.5 mb-2">
    <!-- Left Column: Personal & Contact Info -->
    <div class="col-md-6">
        <div class="card border rounded-3 h-100 shadow-sm">
            <div class="card-header bg-light py-2 px-3 d-flex align-items-center justify-content-between border-bottom">
                <span class="fw-bold text-dark extra-small"><i class="fas fa-user text-primary me-1.5"></i>Personal & Contact Info</span>
                <a href="{{ route('registration.wizard', ['token' => $step1Token]) }}" class="btn btn-sm btn-link p-0 extra-small text-decoration-none fw-semibold">
                    <i class="fas fa-edit me-1"></i>Edit
                </a>
            </div>
            <div class="card-body p-2.5 extra-small">
                <table class="table table-sm table-borderless mb-0 align-middle">
                    <tbody>
                        <tr>
                            <td class="text-muted py-1" style="width: 38%;">Full Name:</td>
                            <td class="fw-bold text-dark py-1">{{ $user->prefix }} {{ $user->full_name }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted py-1">Email:</td>
                            <td class="py-1">
                                <span class="fw-semibold text-dark">{{ $user->email }}</span>
                                <span class="badge bg-success-subtle text-success extra-small ms-1 py-0.5 px-1.5">Verified</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-muted py-1">Mobile:</td>
                            <td class="fw-semibold text-dark py-1">{{ $user->mobile_country_code }} {{ $user->mobile_number }}</td>
                        </tr>
                        @if ($registration->whatsapp_number)
                            <tr>
                                <td class="text-muted py-1">WhatsApp:</td>
                                <td class="fw-semibold text-dark py-1">{{ $registration->whatsapp_country_code }} {{ $registration->whatsapp_number }}</td>
                            </tr>
                        @endif
                        <tr>
                            <td class="text-muted py-1">Gender / Age:</td>
                            <td class="fw-semibold text-dark py-1">{{ $user->gender }}, {{ $user->date_of_birth ? $user->date_of_birth->age : 'N/A' }} yrs ({{ $user->date_of_birth ? $user->date_of_birth->format('d M, Y') : '' }})</td>
                        </tr>
                        <tr>
                            <td class="text-muted py-1">Address:</td>
                            <td class="fw-semibold text-dark py-1">{{ $registration->address }}, {{ $registration->city }}, {{ $registration->country->country_name ?? 'N/A' }} - {{ $registration->pin_code }}</td>
                        </tr>
                        @if ($registration->dietary_preference)
                            <tr>
                                <td class="text-muted py-1">Dietary:</td>
                                <td class="fw-semibold text-dark py-1">{{ $registration->dietary_preference }}</td>
                            </tr>
                        @endif
                        <tr>
                            <td class="text-muted py-1">ID Proof:</td>
                            <td class="py-1">
                                <span class="fw-bold text-dark">{{ $registration->id_proof_type ?: 'N/A' }}</span>
                                @if ($registration->id_proof_number)
                                    <span class="badge bg-light text-dark border ms-1 font-monospace">{{ $registration->id_proof_number }}</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td class="text-muted py-1">Documents:</td>
                            <td class="py-1">
                                @if ($registration->photo_path)
                                    <span class="badge bg-success-subtle text-success extra-small me-1"><i class="fas fa-check me-1"></i>Photo</span>
                                @endif
                                <span class="badge bg-success-subtle text-success extra-small"><i class="fas fa-check me-1"></i>ID Doc</span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Right Column: Registration & Fee Summary -->
    <div class="col-md-6">
        <div class="card border rounded-3 h-100 shadow-sm d-flex flex-column justify-content-between">
            <div>
                <div class="card-header bg-light py-2 px-3 d-flex align-items-center justify-content-between border-bottom">
                    <span class="fw-bold text-dark extra-small"><i class="fas fa-clipboard-list text-primary me-1.5"></i>Registration & Fee Details</span>
                    <a href="{{ route('registration.wizard', ['token' => $step2Token]) }}" class="btn btn-sm btn-link p-0 extra-small text-decoration-none fw-semibold">
                        <i class="fas fa-edit me-1"></i>Edit
                    </a>
                </div>
                <div class="card-body p-2.5 extra-small">
                    @if ($registration->delegate_type == 'Indian')
                        @php
                            $catBase = $registration->delegateCategory ? (float)$registration->delegateCategory->indian_fee : 0;
                            $cmeBase = $registration->cme_fee ?: ($registration->participate_in_cme ? 2000 : 0);
                            $accBase = $registration->accompanying_fee ?: (($registration->accompanying_persons ?? 0) * 5000);
                            $subtotalBase = $catBase + $cmeBase + $accBase;
                            $gstAmt = $registration->gst_amount ?: round($subtotalBase * 0.18, 2);
                            $totalAmt = $registration->total_amount ?: round($subtotalBase + $gstAmt, 2);
                        @endphp
                        <table class="table table-sm table-borderless mb-0 align-middle">
                            <tbody>
                                <tr>
                                    <td class="text-muted py-1" style="width: 45%;">Category:</td>
                                    <td class="fw-bold text-dark py-1">{{ $registration->delegateCategory->category_name ?? 'N/A' }}</td>
                                </tr>
                                @if ($registration->membership_no)
                                    <tr>
                                        <td class="text-muted py-1">Membership No:</td>
                                        <td class="py-1"><span class="badge bg-primary-subtle text-primary fw-bold font-monospace">{{ $registration->membership_no }}</span></td>
                                    </tr>
                                @endif
                                <tr>
                                    <td class="text-muted py-1">Base Reg. Fee:</td>
                                    <td class="fw-semibold text-dark py-1">₹{{ number_format($catBase, 2) }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted py-1">CME Workshop:</td>
                                    <td class="py-1">
                                        @if ($registration->participate_in_cme)
                                            <span class="text-success fw-bold">Yes (+₹{{ number_format($cmeBase, 2) }})</span>
                                        @else
                                            <span class="text-muted">No</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-muted py-1">Accompanying Person:</td>
                                    <td class="py-1">
                                        @if (($registration->accompanying_persons ?? 0) > 0)
                                            <span class="text-success fw-bold">Yes ({{ $registration->accompanying_persons }} person - +₹{{ number_format($accBase, 2) }})</span>
                                        @else
                                            <span class="text-muted">No</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-muted py-1">GST (18%):</td>
                                    <td class="fw-bold text-warning py-1">+ ₹{{ number_format($gstAmt, 2) }}</td>
                                </tr>
                            </tbody>
                        </table>
                    @else
                        <!-- International Delegate Summary -->
                        <table class="table table-sm table-borderless mb-0 align-middle">
                            <tbody>
                                <tr>
                                    <td class="text-muted py-1">Package Type:</td>
                                    <td class="fw-bold text-dark py-1">International Delegate Package</td>
                                </tr>
                                <tr>
                                    <td class="text-muted py-1">Includes:</td>
                                    <td class="text-dark py-1">All Scientific Sessions & CME Programs</td>
                                </tr>
                                <tr>
                                    <td class="text-muted py-1">Total Fee:</td>
                                    <td class="fw-bold text-success py-1 fs-6">$175.00 USD</td>
                                </tr>
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>

            <!-- Compact Total Payable Banner -->
            @if ($registration->delegate_type == 'Indian')
                <div class="p-2.5 mx-2.5 mb-2.5 rounded-3 d-flex align-items-center justify-content-between" style="background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%); color: #ffffff;">
                    <div>
                        <span class="d-block text-white-50 extra-small fw-semibold">Total Payable Amount</span>
                        <small class="text-white-50 extra-small">Inclusive of all taxes & GST</small>
                    </div>
                    <h5 class="mb-0 fw-extrabold" style="color: #DCFFF0;">₹{{ number_format($totalAmt ?? 175, 2) }}</h5>
                </div>
            @else
                <div class="p-2.5 mx-2.5 mb-2.5 rounded-3 d-flex align-items-center justify-content-between" style="background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%); color: #ffffff;">
                    <div>
                        <span class="d-block text-white-50 extra-small fw-semibold">Total Payable Amount</span>
                        <small class="text-white-50 extra-small">Fixed fee USD</small>
                    </div>
                    <h5 class="mb-0 fw-extrabold" style="color: #DCFFF0;">$175.00 USD</h5>
                </div>
            @endif
        </div>
    </div>
</div>
