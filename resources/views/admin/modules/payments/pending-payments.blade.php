@extends('admin.layouts.main')

@section('admin-content')
<div class="container-xxl flex-grow-1 mt-3.5 mb-4">
    
    <!-- Page Title & Stats Bar -->
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 py-2 mb-3">
        <div>
            <h5 class="mb-1 fw-bold text-dark">
                <i class="bx bx-time-five me-2 text-warning fs-4"></i>Pending & Verification Payments
            </h5>
            <p class="text-muted extra-small mb-0">Review transactions and submitted payment proofs waiting for verification.</p>
        </div>
        <div class="d-flex align-items-center gap-2">
            <span class="badge bg-warning text-dark rounded-pill px-3.5 py-2 fs-7 fw-bold shadow-xs">
                Total Pending: {{ $pendingRegistrations->count() + $payments->count() }}
            </span>
        </div>
    </div>

    <!-- Alert Messages -->
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show p-3 mb-3.5 rounded-3 border-0 d-flex align-items-center gap-2.5" role="alert">
            <i class="bx bx-check-circle text-success fs-5"></i>
            <div class="extra-small fw-semibold text-dark">{{ session('success') }}</div>
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
                <i class="bx bx-hourglass text-warning fs-5"></i>
                <h6 class="mb-0 fw-bold text-dark">Pending Payment Verification List</h6>
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
                            <th class="ps-3 py-3" style="width: 4%;">#</th>
                            <th class="py-3" style="width: 26%;">Delegate & Contact</th>
                            <th class="py-3" style="width: 18%;">Ack ID / Reg No</th>
                            <th class="py-3" style="width: 18%;">Amount & Category</th>
                            <th class="py-3" style="width: 18%;">Proof / Status</th>
                            <th class="pe-3 py-3 text-end" style="width: 16%;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $counter = 1; @endphp
                        
                        <!-- Pending Delegations from Registrations Table -->
                        @forelse ($pendingRegistrations as $reg)
                            <tr>
                                <td class="ps-3 fw-bold text-muted">{{ $counter++ }}</td>
                                <td>
                                    <div class="d-flex align-items-center gap-2.5">
                                        <div class="avatar avatar-md flex-shrink-0" style="width: 40px; height: 40px;">
                                            <img src="{{ $reg->photo_path ? asset('storage/' . $reg->photo_path) : asset('images/default-avatar.svg') }}"
                                                alt="Avatar" class="rounded-circle w-100 h-100 border shadow-xs" style="object-fit: cover;"
                                                onerror="this.onerror=null; this.src='{{ asset('images/default-avatar.svg') }}';" />
                                        </div>
                                        <div>
                                            <h6 class="mb-0 fw-bold text-dark" style="font-size: 0.88rem;">
                                                {{ $reg->user?->prefix }} {{ $reg->user?->full_name ?? 'N/A' }}
                                            </h6>
                                            <div class="extra-small text-muted" style="font-size: 0.76rem;">
                                                <i class="bx bx-envelope me-0.5"></i>{{ $reg->user?->email }}
                                            </div>
                                            @if($reg->user?->mobile_no)
                                                <div class="extra-small text-muted" style="font-size: 0.74rem;">
                                                    <i class="bx bx-phone me-0.5"></i>{{ $reg->user?->mobile_no }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border font-monospace extra-small d-inline-block mb-1">
                                        <i class="bx bx-barcode me-0.5 text-primary"></i>Ack: {{ $reg->acknowledgement_id ?? ('IPHA-ACK-'.$reg->id) }}
                                    </span>
                                    @if($reg->registration_number)
                                        <div class="extra-small text-success fw-bold font-monospace">
                                            Reg: {{ $reg->registration_number }}
                                        </div>
                                    @else
                                        <div class="extra-small text-warning fw-semibold">
                                            <i class="bx bx-time me-0.5"></i>Pending Approval
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <div class="fw-bold text-dark" style="font-size: 0.9rem;">
                                        ₹{{ number_format($reg->total_amount, 2) }}
                                    </div>
                                    <span class="badge bg-label-info extra-small px-2 py-0.5 mt-0.5">
                                        {{ $reg->delegateCategory?->category_name ?? ($reg->delegate_type ?? 'Delegate') }}
                                    </span>
                                </td>
                                <td>
                                    @if($reg->latestPayment?->payment_receipt_path)
                                        <a href="{{ asset('storage/' . $reg->latestPayment->payment_receipt_path) }}" target="_blank" 
                                            class="btn btn-sm btn-outline-success px-2.5 py-1 rounded-2 mb-1 d-inline-flex align-items-center gap-1 extra-small">
                                            <i class="bx bx-receipt"></i> View Proof
                                        </a>
                                    @endif
                                    <div>
                                        <span class="badge rounded-pill extra-small px-2 py-0.5" style="background-color: #FEF3C7; color: #92400E; border: 1px solid #FDE68A;">
                                            <i class="bx bx-time me-0.5"></i>{{ $reg->status }}
                                        </span>
                                    </div>
                                </td>
                                <td class="pe-3 text-end">
                                    <div class="d-inline-flex gap-1">
                                        <a href="{{ route('show-registration-details', $reg->registration_number ?? ($reg->acknowledgement_id ?? $reg->id)) }}" 
                                            class="btn btn-sm btn-primary px-2.5 py-1 rounded-2 extra-small fw-bold" title="View Full Details">
                                            <i class="bx bx-show me-0.5"></i> View
                                        </a>
                                        
                                        <!-- Approve Modal Button -->
                                        <form method="POST" action="{{ route('student-approved-regis') }}" class="d-inline">
                                            @csrf
                                            <input type="hidden" name="registration_id" value="{{ $reg->id }}">
                                            <button type="submit" class="btn btn-sm btn-success px-2.5 py-1 rounded-2 extra-small fw-bold"
                                                onclick="return confirm('Are you sure you want to approve this delegate registration?')" title="Approve Delegate">
                                                <i class="bx bx-check-circle me-0.5"></i> Approve
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                        @endforelse

                        <!-- Pending Transactions from Payments Table -->
                        @forelse ($payments as $pay)
                            @if(!$pendingRegistrations->contains('id', $pay->registration_id))
                                <tr>
                                    <td class="ps-3 fw-bold text-muted">{{ $counter++ }}</td>
                                    <td>
                                        <h6 class="mb-0 fw-bold text-dark" style="font-size: 0.88rem;">
                                            {{ $pay->registration?->user?->prefix }} {{ $pay->registration?->user?->full_name ?? 'N/A' }}
                                        </h6>
                                        <small class="text-muted extra-small d-block">
                                            <i class="bx bx-envelope me-0.5"></i>{{ $pay->registration?->user?->email }}
                                        </small>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark border font-monospace extra-small">
                                            Ack: {{ $pay->registration?->acknowledgement_id ?? 'N/A' }}
                                        </span>
                                        <div class="extra-small font-monospace text-primary mt-0.5">
                                            Txn: {{ $pay->transaction_id ?: ($pay->gateway_transaction_id ?: 'N/A') }}
                                        </div>
                                    </td>
                                    <td>
                                        <span class="fw-bold text-dark" style="font-size: 0.9rem;">
                                            ₹{{ number_format($pay->total_amount, 2) }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($pay->payment_receipt_path)
                                            <a href="{{ asset('storage/' . $pay->payment_receipt_path) }}" target="_blank" 
                                                class="btn btn-sm btn-outline-success px-2.5 py-1 rounded-2 extra-small">
                                                <i class="bx bx-receipt me-1"></i> Receipt
                                            </a>
                                        @else
                                            <span class="badge bg-warning text-dark extra-small">
                                                {{ $pay->payment_status ?: 'Pending' }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="pe-3 text-end">
                                        @if($pay->registration)
                                            <a href="{{ route('show-registration-details', $pay->registration->registration_number ?? ($pay->registration->acknowledgement_id ?? $pay->registration->id)) }}" 
                                                class="btn btn-sm btn-primary px-2.5 py-1 rounded-2 extra-small fw-bold">
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
