{{-- resources/views/registration/steps/step3.blade.php --}}
<h4 class="text-primary mb-4"><i class="fas fa-eye me-2"></i>Step 3: Preview Registration</h4>

<div class="alert alert-info mb-4">
    <i class="fas fa-info-circle me-2"></i>
    Please review all information carefully. You can edit any section except the email address by going back to the
    previous steps.
</div>

<!-- Personal Information -->
<div class="card mb-4">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0"><i class="fas fa-user me-2"></i>Personal Information</h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <table class="table table-borderless">
                    <tr>
                        <td><strong>Full Name:</strong></td>
                        <td>{{ $user->prefix }} {{ $user->full_name }}</td>
                    </tr>
                    <tr>
                        <td><strong>Email:</strong></td>
                        <td>{{ $user->email }} <span class="badge bg-success">Verified</span></td>
                    </tr>
                    <tr>
                        <td><strong>Mobile:</strong></td>
                        <td>{{ $user->mobile_country_code }} {{ $user->mobile_number }}</td>
                    </tr>
                    <tr>
                        <td><strong>Gender:</strong></td>
                        <td>{{ $user->gender }}</td>
                    </tr>
                    <tr>
                        <td><strong>Date of Birth:</strong></td>
                        <td>{{ $user->date_of_birth->format('d M, Y') }} ({{ $user->date_of_birth->age }} years)</td>
                    </tr>
                </table>
            </div>
            <div class="col-md-6">
                <table class="table table-borderless">
                    <tr>
                        <td><strong>Address:</strong></td>
                        <td>{{ $registration->address }}</td>
                    </tr>
                    <tr>
                        <td><strong>City:</strong></td>
                        <td>{{ $registration->city }}</td>
                    </tr>
                    <tr>
                        <td><strong>Country:</strong></td>
                        <td>{{ $registration->country->country_name ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td><strong>PIN Code:</strong></td>
                        <td>{{ $registration->pin_code }}</td>
                    </tr>
                    @if ($registration->whatsapp_number)
                        <tr>
                            <td><strong>WhatsApp:</strong></td>
                            <td>{{ $registration->whatsapp_country_code }} {{ $registration->whatsapp_number }}</td>
                        </tr>
                    @endif
                </table>
            </div>
        </div>

        @if ($registration->dietary_preference)
            <div class="row">
                <div class="col-12">
                    <strong>Dietary Preference:</strong> {{ $registration->dietary_preference }}
                </div>
            </div>
        @endif

        <div class="row mt-3">
            <div class="col-md-6">
                <strong>ID Proof Type:</strong> {{ $registration->id_proof_type }}
            </div>
            <div class="col-md-6">
                @if ($registration->photo_path)
                    <strong>Photo:</strong> <span class="badge bg-success">Uploaded</span><br>
                @endif
                <strong>ID Document:</strong> <span class="badge bg-success">Uploaded</span>
            </div>
        </div>
    </div>
</div>

<!-- Conference Registration -->
<div class="card mb-4">
    <div class="card-header bg-success text-white">
        <h5 class="mb-0"><i class="fas fa-clipboard-list me-2"></i>Conference Registration</h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <table class="table table-borderless">
                    <tr>
                        <td><strong>Delegate Type:</strong></td>
                        <td>
                            <span class="badge bg-{{ $registration->delegate_type == 'Indian' ? 'primary' : 'info' }}">
                                {{ $registration->delegate_type }}
                            </span>
                        </td>
                    </tr>

                    @if ($registration->delegate_type == 'Indian')
                        <tr>
                            <td><strong>Category:</strong></td>
                            <td>{{ $registration->delegateCategory->category_name ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td><strong>Membership No.:</strong></td>
                            <td>{{ $registration->membership_no ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td><strong>Category Fee:</strong></td>
                            <td>₹{{ number_format($registration->delegateCategory->indian_fee ?? 0) }}</td>
                        </tr>
                        @if ($registration->accompanying_persons > 0)
                            <tr>
                                <td><strong>Accompanying Persons:</strong></td>
                                <td>{{ $registration->accompanying_persons }}
                                    (₹{{ number_format($registration->accompanying_persons * 4000) }})</td>
                            </tr>
                        @endif
                        @if ($registration->participate_in_cme)
                            <tr>
                                <td><strong>CME/Workshop Participation:</strong></td>
                                <td>Yes (₹1,000)</td>
                            </tr>
                        @endif
                    @else
                        <tr>
                            <td><strong>Registration Fee:</strong></td>
                            <td><strong>$175.00</strong></td>
                        </tr>
                    @endif
                </table>
            </div>
            <div class="col-md-6">
                <!-- Membership Information -->
                @if ($registration->is_ismm_member)
                    <p><strong>ISMM Member:</strong> Yes ({{ $registration->ismm_membership_no }})</p>
                @endif
                @if ($registration->is_isham_member)
                    <p><strong>ISHAM Member:</strong> Yes ({{ $registration->isham_membership_no }})</p>
                @endif
                @if ($registration->is_young_isam_member)
                    <p><strong>Young ISAM Member:</strong> Yes ({{ $registration->young_isam_membership_no }})</p>
                @endif
            </div>
        </div>
    </div>
</div>

@if ($registration->delegate_type == 'Indian')
    <!-- Fee Summary -->
    <div class="card mb-4">
        <div class="card-header bg-warning text-dark">
            <h5 class="mb-0"><i class="fas fa-calculator me-2"></i>Fee Summary</h5>
        </div>
        <div class="card-body">
            @if ($registration->delegate_type == 'Foreign')
                <div class="row">
                    <div class="col-md-6">
                        <h4 class="text-success">Total Registration Fee: <strong>$175.00</strong></h4>
                        <p class="text-muted">Fixed fee for all foreign delegates including all conference activities.
                        </p>
                    </div>
                    <div class="col-md-6 text-center">
                        <img src="{{ asset('images/qr-code-usd.png') }}" alt="Payment QR Code" class="img-fluid"
                            style="max-width: 200px;">
                        <p class="mt-2"><small>Scan to pay $175.00</small></p>
                    </div>
                </div>
            @else
                <table class="table">
                    <tr>
                        <td><strong>Delegate Category:</strong></td>
                        <td class="text-end">₹{{ number_format($registration->delegateCategory->indian_fee ?? 0) }}
                        </td>
                    </tr>
                    @if ($registration->accompanying_persons > 0)
                        <tr>
                            <td><strong>Accompanying Persons:</strong></td>
                            <td class="text-end">₹{{ number_format($registration->accompanying_persons * 4000) }}</td>
                        </tr>
                    @endif
                    @if ($registration->participate_in_cme)
                        <tr>
                            <td><strong>CME/Workshop Participation:</strong></td>
                            <td class="text-end">₹1,000</td>
                        </tr>
                    @endif
                    <tr class="table-success">
                        <td><strong>Total Amount:</strong></td>
                        <td class="text-end">
                            <strong>₹{{ number_format($registration->calculateTotalAmount()) }}</strong>
                        </td>
                    </tr>
                </table>
            @endif
        </div>
    </div>
@endif

<!-- Edit Options -->
<div class="row mb-4">
    <div class="col-md-12">
        <div class="card bg-light">
            <div class="card-body">
                <h6><i class="fas fa-edit me-2"></i>Need to make changes?</h6>
                <p class="mb-2">You can go back and edit any information:</p>
                @php
                    $stepData = json_encode([
                        'step' => 1,
                        'uid' => auth()->id(),
                    ]);

                    $encryptedToken = Crypt::encryptString($stepData);

                @endphp

                <a href="{{ route('registration.wizard', ['token' => $encryptedToken]) }}"
                    class="btn btn-outline-primary btn-sm me-2">
                    <i class="fas fa-user me-1"></i>Edit Personal Info
                </a>

                @php
                    $stepData = json_encode([
                        'step' => 2,
                        'uid' => auth()->id(),
                    ]);

                    $encryptedToken = Crypt::encryptString($stepData);

                @endphp
                <a href="{{ route('registration.wizard', ['token' => $encryptedToken]) }}"
                    class="btn btn-outline-success btn-sm">
                    <i class="fas fa-clipboard-list me-1"></i>Edit Registration Details
                </a>
            </div>
        </div>
    </div>
</div>
