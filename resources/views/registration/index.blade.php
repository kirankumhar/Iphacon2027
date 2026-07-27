@extends('shared.auth-delegate')
@php
    $inner_title = '';
@endphp
@section('delegate-content')
<div class="container py-3">
    <div class="row justify-content-center">
        <div class="col-lg-11 col-xl-10">
            <div class="card shadow-md border-0" style="border-radius: 16px; overflow: hidden; background: #ffffff;">
                <div class="card-header d-flex justify-content-between align-items-center py-3 px-4"
                     style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%); border-bottom: 3px solid #2D69FF;">
                    <div class="d-flex align-items-center gap-2.5">
                        <i class="fas fa-clipboard-list text-primary fs-5"></i>
                        <h5 class="text-white mb-0 fw-bold" style="letter-spacing: 0.5px;">My Registrations</h5>
                    </div>
                    @if($registrations->isEmpty())
                        <a href="{{ route('registration.create') }}" class="btn btn-primary btn-sm px-3 fw-semibold" style="border-radius: 8px;">
                            <i class="fas fa-plus me-1"></i>New Registration
                        </a>
                    @endif
                </div>

                <div class="card-body p-3 p-md-4">
                    @if($registrations->isEmpty())
                        <div class="text-center py-4 px-3 my-2" style="background: #f8fafc; border-radius: 12px; border: 1px dashed #cbd5e1;">
                            <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px; background: #E1F0FF; color: #2D69FF;">
                                <i class="fas fa-clipboard-list fs-3"></i>
                            </div>
                            <h5 class="fw-bold text-dark mb-1">No Registrations Found</h5>
                            <p class="text-muted small mb-3">You haven't registered for the conference yet.</p>
                            <a href="{{ route('registration.create') }}" class="btn btn-primary px-4 py-2 fw-semibold" style="background: linear-gradient(135deg, #2D69FF 0%, #1A52E0 100%); border: none; border-radius: 10px;">
                                <i class="fas fa-plus me-1.5"></i>Start Registration Now
                            </a>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0" style="font-size: 0.9rem;">
                                <thead class="table-light">
                                    <tr>
                                        <th class="fw-bold">Registration No.</th>
                                        <th class="fw-bold">Delegate Category</th>
                                        <th class="fw-bold">Status</th>
                                        <th class="fw-bold">Submitted Date</th>
                                        <th class="fw-bold text-end">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($registrations as $registration)
                                        <tr>
                                            <td class="fw-semibold text-dark">{{ $registration->registration_number }}</td>
                                            <td><span class="badge bg-light text-dark border px-2.5 py-1.5">{{ $registration->delegateCategory->category_name ?? 'Pending Selection' }}</span></td>
                                            <td>
                                                <span class="badge px-3 py-1.5 fw-semibold" style="background-color: {{ $registration->status == 'Approved' ? '#DCFFF0' : ($registration->status == 'Rejected' ? '#ffe2e2' : '#E1F0FF') }}; color: {{ $registration->status == 'Approved' ? '#4BAA7D' : ($registration->status == 'Rejected' ? '#dc2626' : '#2D69FF') }}; border-radius: 20px;">
                                                    {{ $registration->status }}
                                                </span>
                                            </td>
                                            <td class="text-muted small">{{ $registration->submitted_at ? $registration->submitted_at->format('d M, Y') : 'Not Submitted Yet' }}</td>
                                            <td class="text-end">
                                                <a href="{{ route('registration.show', $registration->id) }}" class="btn btn-sm btn-outline-primary px-3 py-1 fw-semibold me-1" style="border-radius: 6px;">
                                                    <i class="fas fa-eye me-1"></i>View
                                                </a>
                                                @if($registration->status == 'Draft')
                                                    <a href="{{ route('registration.create') }}" class="btn btn-sm btn-primary px-3 py-1 fw-semibold" style="background: #2D69FF; border: none; border-radius: 6px;">
                                                        <i class="fas fa-edit me-1"></i>Edit
                                                    </a>
                                                @elseif($registration->status == 'Approved' || $registration->status == 'Payment Submitted')
                                                    <a href="{{ route('delgate.download.receipt', $registration->registration_number) }}" class="btn btn-sm btn-success px-3 py-1 fw-semibold" style="border-radius: 6px;">
                                                        <i class="fas fa-download me-1"></i>Receipt
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
