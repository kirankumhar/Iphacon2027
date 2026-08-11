@extends('admin.layouts.main')

@section('admin-content')
<div class="container-xxl flex-grow-1 mt-3.5 mb-4">
    
    <!-- Page Title & Stats Bar -->
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 py-2 mb-3">
        <div>
            <h5 class="mb-1 fw-semibold text-dark">
                <i class="bx bx-time-five me-2 text-secondary fs-4"></i>Pending & Verification Payments
            </h5>
            <p class="text-muted extra-small mb-0">Review transactions and submitted payment proofs waiting for verification.</p>
        </div>
        <div class="d-flex align-items-center gap-2">
            <span class="badge bg-light text-dark border px-3 py-1.5 fs-7 fw-medium rounded-2">
                Total Pending: {{ $pendingRegistrations->count() + $payments->count() }}
            </span>
        </div>
    </div>

    <!-- Alert Messages -->
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show p-3 mb-3.5 rounded-3 border-0 d-flex align-items-center gap-2.5" role="alert">
            <i class="bx bx-check-circle text-success fs-5"></i>
            <div class="extra-small fw-medium text-dark">{{ session('success') }}</div>
            <button type="button" class="btn-close py-2.5 px-3" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show p-3 mb-3.5 rounded-3 border-0 d-flex align-items-center gap-2.5" role="alert">
            <i class="bx bx-error-circle text-danger fs-5"></i>
            <div class="extra-small text-dark">{{ session('error') }}</div>
            <button type="button" class="btn-close py-2.5 px-3" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Main Card -->
    <div class="card border-0 shadow-sm rounded-3 overflow-hidden">
        
        <!-- Card Header & Live Search -->
        <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between flex-wrap gap-2">
            <div class="d-flex align-items-center gap-2">
                <i class="bx bx-hourglass text-secondary fs-5"></i>
                <h6 class="mb-0 fw-semibold text-dark">Pending Payment Verification List</h6>
            </div>
            <div class="search-box" style="max-width: 320px; width: 100%;">
                <div class="input-group input-group-sm">
                    <span class="input-group-text bg-light border-end-0"><i class="bx bx-search text-muted"></i></span>
                    <input type="text" id="pendingSearchInput" class="form-control bg-light border-start-0" placeholder="Search name, ack id, email, txn id...">
                </div>
            </div>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="pendingPaymentsTable">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-3 py-3 fw-semibold" style="width: 4%;">#</th>
                            <th class="py-3 fw-semibold" style="width: 32%;">Delegate & Contact</th>
                            <th class="py-3 fw-semibold" style="width: 20%;">Ack ID</th>
                            <th class="py-3 fw-semibold" style="width: 15%;">Amount</th>
                            <th class="py-3 fw-semibold" style="width: 15%;">Status</th>
                            <th class="pe-3 py-3 text-end fw-semibold" style="width: 14%;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $counter = 1; @endphp
                        
                        <!-- Pending Delegations from Registrations Table -->
                        @forelse ($pendingRegistrations as $reg)
                            <tr>
                                <td class="ps-3 fw-medium text-muted">{{ $counter++ }}</td>
                                <td>
                                    <div class="d-flex align-items-center gap-2.5">
                                        <div class="avatar avatar-md flex-shrink-0" style="width: 40px; height: 40px;">
                                            <img src="{{ $reg->photo_path ? asset('storage/' . $reg->photo_path) : asset('images/default-avatar.svg') }}"
                                                alt="Avatar" class="rounded-circle w-100 h-100 border" style="object-fit: cover;"
                                                onerror="this.onerror=null; this.src='{{ asset('images/default-avatar.svg') }}';" />
                                        </div>
                                        <div>
                                            <h6 class="mb-0 fw-semibold" style="font-size: 0.88rem;">
                                                <a href="{{ route('show-registration-details', $reg->registration_number ?? ($reg->acknowledgement_id ?? $reg->id)) }}" class="text-dark text-decoration-none">
                                                    {{ $reg->user?->prefix }} {{ $reg->user?->full_name ?? 'N/A' }}
                                                </a>
                                            </h6>
                                            @if($reg->user?->mobile_no)
                                                <div class="extra-small text-muted" style="font-size: 0.74rem;">
                                                    <i class="bx bx-phone me-1"></i>{{ $reg->user?->mobile_no }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-light text-secondary border font-monospace extra-small px-2 py-0.5 d-inline-block">
                                        Ack: {{ $reg->acknowledgement_id ?? 'N/A' }}
                                    </span>
                                </td>
                                <td>
                                    <div class="fw-semibold text-dark" style="font-size: 0.9rem;">
                                        ₹{{ number_format($reg->total_amount, 2) }}
                                    </div>
                                </td>
                                <td>
                                    @php
                                        $st = strtolower($reg->status ?? 'pending');
                                        $bgStyle = 'background-color: #FEF3C7; color: #0F172A; border: 1px solid #FDE68A;';
                                        if (str_contains($st, 'pending')) {
                                            $bgStyle = 'background-color: #FFEDD5; color: #0F172A; border: 1px solid #FED7AA;';
                                        } elseif (str_contains($st, 'approved') || str_contains($st, 'completed')) {
                                            $bgStyle = 'background-color: #DCFCE7; color: #0F172A; border: 1px solid #BBF7D0;';
                                        } elseif (str_contains($st, 'reject') || str_contains($st, 'cancel')) {
                                            $bgStyle = 'background-color: #FEE2E2; color: #0F172A; border: 1px solid #FECACA;';
                                        }
                                    @endphp
                                    <span class="badge extra-small px-2.5 py-1 rounded-2 fw-semibold" style="{{ $bgStyle }}">
                                        <i class="bx bx-time me-1"></i>{{ $reg->status }}
                                    </span>
                                </td>
                                <td class="pe-3 text-end">
                                    <div class="d-inline-flex gap-1.5 align-items-center">
                                        <a href="{{ route('show-registration-details', $reg->registration_number ?? ($reg->acknowledgement_id ?? $reg->id)) }}" 
                                            class="btn btn-xs btn-primary px-2.5 py-1 rounded-2 extra-small fw-medium" title="View Full Details">
                                            <i class="bx bx-show me-0.5"></i> View
                                        </a>
                                        
                                        @if($reg->status === 'Payment Submitted')
                                        <!-- Approve Modal Button -->
                                        <form method="POST" action="{{ route('student-approved-regis') }}" class="d-inline">
                                            @csrf
                                            <input type="hidden" name="registration_id" value="{{ $reg->id }}">
                                            <button type="submit" class="btn btn-xs btn-success px-2.5 py-1 rounded-2 extra-small fw-medium"
                                                onclick="return confirm('Are you sure you want to approve this delegate registration?')" title="Approve Delegate">
                                                <i class="bx bx-check-circle me-0.5"></i> Approve
                                            </button>
                                        </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                        @endforelse

                        <!-- Pending Transactions from Payments Table -->
                        @forelse ($payments as $pay)
                            @if(!$pendingRegistrations->contains('id', $pay->registration_id))
                                <tr>
                                    <td class="ps-3 fw-medium text-muted">{{ $counter++ }}</td>
                                    <td>
                                        <h6 class="mb-0 fw-semibold text-dark" style="font-size: 0.88rem;">
                                            {{ $pay->registration?->user?->prefix }} {{ $pay->registration?->user?->full_name ?? 'N/A' }}
                                        </h6>
                                        <small class="text-muted extra-small d-block">
                                            <i class="bx bx-envelope me-0.5"></i>{{ $pay->registration?->user?->email }}
                                        </small>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-secondary border font-monospace extra-small px-2 py-0.5">
                                            Ack: {{ $pay->registration?->acknowledgement_id ?? 'N/A' }}
                                        </span>
                                        <div class="extra-small font-monospace text-muted mt-1">
                                            Txn: {{ $pay->transaction_id ?: ($pay->gateway_transaction_id ?: 'N/A') }}
                                        </div>
                                    </td>
                                    <td>
                                        <span class="fw-semibold text-dark" style="font-size: 0.9rem;">
                                            ₹{{ number_format($pay->total_amount, 2) }}
                                        </span>
                                    </td>
                                    <td>
                                        @php
                                            $pst = strtolower($pay->payment_status ?: 'Pending');
                                            $pbgStyle = 'background-color: #FEF3C7; color: #0F172A; border: 1px solid #FDE68A;';
                                            if (str_contains($pst, 'pending')) {
                                                $pbgStyle = 'background-color: #FFEDD5; color: #0F172A; border: 1px solid #FED7AA;';
                                            } elseif (str_contains($pst, 'paid') || str_contains($pst, 'success') || str_contains($pst, 'approved')) {
                                                $pbgStyle = 'background-color: #DCFCE7; color: #0F172A; border: 1px solid #BBF7D0;';
                                            }
                                        @endphp
                                        <span class="badge extra-small px-2.5 py-1 rounded-2 fw-semibold" style="{{ $pbgStyle }}">
                                            {{ $pay->payment_status ?: 'Pending' }}
                                        </span>
                                    </td>
                                    <td class="pe-3 text-end">
                                        @if($pay->registration)
                                            <a href="{{ route('show-registration-details', $pay->registration->registration_number ?? ($pay->registration->acknowledgement_id ?? $pay->registration->id)) }}" 
                                                class="btn btn-xs btn-primary px-2.5 py-1 rounded-2 extra-small fw-medium">
                                                <i class="bx bx-show me-0.5"></i> View
                                            </a>
                                        @else
                                            <span class="text-muted extra-small">-</span>
                                        @endif
                                    </td>
                                </tr>
                            @endif
                        @empty
                        @endforelse

                        @if($pendingRegistrations->isEmpty() && $payments->isEmpty())
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <div class="text-muted">
                                        <i class="bx bx-check-shield fs-1 mb-2 text-success"></i>
                                        <p class="mb-0 fw-semibold text-dark">No pending payment verifications.</p>
                                        <p class="extra-small text-muted mb-0">All submitted delegate payments are currently up to date!</p>
                                    </div>
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
        
        <!-- Pagination Footer -->
        @if(method_exists($pendingRegistrations, 'hasPages') && $pendingRegistrations->hasPages())
            <div class="card-footer bg-white py-3 border-top d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div class="extra-small text-muted">
                    Showing <strong>{{ $pendingRegistrations->firstItem() }}</strong> to <strong>{{ $pendingRegistrations->lastItem() }}</strong> of <strong>{{ $pendingRegistrations->total() }}</strong> entries
                </div>
                <div>
                    {{ $pendingRegistrations->links('pagination::bootstrap-5') }}
                </div>
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('pendingSearchInput');
    const tableRows = document.querySelectorAll('#pendingPaymentsTable tbody tr');

    if (searchInput) {
        searchInput.addEventListener('keyup', function() {
            const query = this.value.toLowerCase().trim();
            tableRows.forEach(row => {
                const text = row.innerText.toLowerCase();
                row.style.display = text.includes(query) ? '' : 'none';
            });
        });
    }
});
</script>
@endpush
