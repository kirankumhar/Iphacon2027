@extends('shared.auth-delegate')
@php
    $inner_title = '';
@endphp
@section('delegate-content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card shadow-lg border-0" style="border-radius: 15px;">
                <div class="card-header d-flex justify-content-between align-items-center py-4"
                     style="background: linear-gradient(135deg, #2e3192, #4a5bcc); border-radius: 15px 15px 0 0;">
                    <h3 class="text-white mb-0 fw-bold">
                        <i class="fas fa-clipboard-list me-2"></i>My Registrations
                    </h3>
                    @if($registrations->isEmpty())
                        <a href="{{ route('registration.create') }}" class="btn btn-light btn-sm">
                            <i class="fas fa-plus me-1"></i>New Registration
                        </a>
                    @endif
                </div>

                <div class="card-body p-5">
                    @if($registrations->isEmpty())
                        <div class="text-center py-5">
                            <i class="fas fa-clipboard-list fa-5x text-muted mb-3"></i>
                            <h4 class="text-muted">No Registrations Found</h4>
                            <p class="text-muted">You haven't registered for the conference yet.</p>
                            <a href="{{ route('registration.create') }}" class="btn btn-primary btn-lg">
                                <i class="fas fa-plus me-2"></i>Start Registration
                            </a>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Registration No.</th>
                                        <th>Delegate Category</th>
                                        <th>Status</th>
                                        <th>Submitted Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($registrations as $registration)
                                        <tr>
                                            <td>{{ $registration->registration_number }}</td>
                                            <td>{{ $registration->delegateCategory->category_name }}</td>
                                            <td>
                                                <span class="badge bg-{{ $registration->status == 'Approved' ? 'success' : ($registration->status == 'Rejected' ? 'danger' : 'warning') }}">
                                                    {{ $registration->status }}
                                                </span>
                                            </td>
                                            <td>{{ $registration->submitted_at ? $registration->submitted_at->format('d M, Y') : 'Not Submitted Yet' }}</td>
                                            <td>
                                                <a href="{{ route('registration.show', $registration->id) }}" class="btn btn-sm btn-info">
                                                    <i class="fas fa-eye"></i> View
                                                </a>
                                                @if($registration->status == 'Draft')
                                                    <a href="{{ route('registration.create') }}" class="btn btn-sm btn-warning">
                                                        <i class="fas fa-edit"></i> Edit
                                                    </a>
                                                @elseif($registration->status == 'Approved' || $registration->status == 'Payment Submitted')
                                                    <a href="{{ route('delgate.download.receipt', $registration->registration_number) }}" class="btn btn-sm btn-warning">
                                                        <i class="fas fa-download"></i> Receipt
                                                    </a>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
