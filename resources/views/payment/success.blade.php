@extends('shared.auth-delegate')
@section('title', 'Registration Completed')
@section('delegate-content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-lg border-0" style="border-radius: 15px;">
                <div class="card-header text-center py-4"
                     style="background: linear-gradient(135deg, #28a745, #20c997); border-radius: 15px 15px 0 0;">
                    <h3 class="text-white mb-0 fw-bold">
                        <i class="fas fa-check-circle me-2"></i>Payment Successful!
                    </h3>
                </div>

                <div class="card-body p-5 text-center">
                    <div class="mb-4">
                        <i class="fas fa-check-circle text-success" style="font-size: 5rem;"></i>
                    </div>

                    <h4 class="text-success mb-3">Registration Completed Successfully!</h4>

                    @if($registration->status === 'Approved')
                        <div class="alert alert-success">
                            <h5><strong>Registration Number: {{ $registration->registration_number }}</strong></h5>
                            <p class="mb-0">Please save your official registration number for your records.</p>
                        </div>
                    @else
                        <div class="alert alert-warning border border-warning border-opacity-25" style="background: #fffbeb;">
                            <h5 class="text-dark fw-bold mb-1"><strong>Acknowledgement Number: ACK-IPHACON-{{ sprintf('%04d', $registration->id) }}</strong></h5>
                            <p class="mb-0 text-muted small">Please save this acknowledgement number for your records.</p>
                        </div>
                        <div class="alert alert-info">
                            <i class="fas fa-hourglass-half me-2"></i>
                            Your registration status is currently <strong>Pending for Verification</strong>. Once verified and approved by the organizing committee, your official Registration Number and Download Receipt PDF will be unlocked.
                        </div>
                    @endif

                    <div class="row mt-4">
                        <div class="col-md-6">
                            <a href="{{ route('registration.show', $registration->id) }}" class="btn btn-primary btn-lg w-100">
                                <i class="fas fa-eye me-2"></i>View Registration
                            </a>
                        </div>
                        <div class="col-md-6">
                            <a href="{{ route('dashboard') }}" class="btn btn-secondary btn-lg w-100">
                                <i class="fas fa-home me-2"></i>Go to Dashboard
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
