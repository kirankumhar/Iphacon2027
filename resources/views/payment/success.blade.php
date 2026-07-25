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

                    <div class="alert alert-success">
                        <h5><strong>Registration Number: {{ $registration->registration_number }}</strong></h5>
                        <p class="mb-0">Please save this number for your records.</p>
                    </div>

                    @if($registration->status === 'Payment Submitted')
                        <div class="alert alert-info">
                            <i class="fas fa-clock me-2"></i>
                            Your payment is being verified. You will receive a confirmation email within 24-48 hours.
                        </div>
                    @endif

                    <div class="row mt-4">
                        <div class="col-md-6">
                            <a href="{{ route('registration.show', $registration->registration_number) }}" class="btn btn-primary btn-lg w-100">
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
