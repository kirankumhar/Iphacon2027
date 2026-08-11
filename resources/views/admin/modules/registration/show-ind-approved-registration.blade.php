@extends('admin.layouts.main')

@section('admin-content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="d-flex align-items-center justify-content-between py-3 mb-3">
        <h5 class="mb-0 fw-semibold"><span class="invert-text-white"><i class="bx bx-check-double me-2 text-success"></i>Indian Approved Registrations</span></h5>
        <span class="badge bg-success rounded-pill px-3 py-2 fs-7 fw-semibold shadow-xs">
            Total Approved: {{ $registrations->count() }}
        </span>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show p-3 mb-3 rounded-3" role="alert">
            <div class="d-flex align-items-center gap-2">
                <i class="bx bx-check-circle fs-4"></i>
                <div>{{ session('success') }}</div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show p-3 mb-3 rounded-3" role="alert">
            <div class="d-flex align-items-center gap-2">
                <i class="bx bx-error-circle fs-4"></i>
                <div>{{ session('error') }}</div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card border-0 shadow-sm rounded-3">
        <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between flex-wrap gap-2">
            <h6 class="mb-0 fw-semibold text-dark"><i class="bx bx-list-check me-1.5 text-primary"></i>Approved Indian Delegates List</h6>
            <div class="search-box" style="max-width: 300px; width: 100%;">
                <div class="input-group input-group-sm">
                    <span class="input-group-text bg-light border-end-0"><i class="bx bx-search text-muted"></i></span>
                    <input type="text" id="approvedSearchInput" class="form-control bg-light border-start-0" placeholder="Search by name, reg no, email...">
                </div>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="approvedDelegatesTable">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-3 py-3 fw-semibold" style="width: 5%;">#</th>
                            <th class="py-3 fw-semibold" style="width: 25%;">Delegate Info</th>
                            <th class="py-3 fw-semibold" style="width: 20%;">Category & Type</th>
                            <th class="py-3 fw-semibold" style="width: 20%;">Payment Info</th>
                            <th class="py-3 fw-semibold" style="width: 15%;">Status</th>
                            <th class="pe-3 py-3 text-end fw-semibold" style="width: 15%;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($registrations as $index => $reg)
                            <tr>
                                <td class="ps-3 fw-medium text-muted">{{ $index + 1 }}</td>
                                <td>
                                    <div class="d-flex align-items-center gap-2.5">
                                        <div class="avatar avatar-md flex-shrink-0">
                                            <img src="{{ $reg->photo_path ? asset('storage/' . $reg->photo_path) : asset('images/default-avatar.svg') }}"
                                                alt="Avatar" class="rounded-circle w-100 h-100 border shadow-xs" style="object-fit: cover;"
                                                onerror="this.onerror=null; this.src='{{ asset('images/default-avatar.svg') }}';" />
                                        </div>
                                        <div>
                                            <h6 class="mb-0 fw-semibold text-dark">{{ $reg->user?->prefix }} {{ $reg->user?->full_name }}</h6>
                                            <div class="extra-small text-muted mb-0.5">
                                                <i class="bx bx-hash me-0.5 text-primary"></i><span class="fw-semibold text-dark">{{ $reg->registration_number ?? 'N/A' }}</span>
                                            </div>
                                            <div class="extra-small text-muted">
                                                <i class="bx bx-envelope me-0.5"></i>{{ $reg->user?->email }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-label-primary px-2.5 py-1 mb-1 fw-semibold extra-small">{{ $reg->delegate_type ?? 'Indian' }} Delegate</span>
                                    <div class="fw-medium text-dark extra-small">
                                        <i class="bx bx-tag-alt text-muted me-1"></i>{{ $reg->delegateCategory?->category_name ?? 'N/A' }}
                                    </div>
                                </td>
                                <td>
                                    <div class="fw-semibold text-success fs-6 mb-1">
                                        ₹{{ number_format($reg->total_amount ?: $reg->calculateTotalAmount(), 2) }}
                                    </div>
                                    @if($reg->latestPayment && $reg->latestPayment->payment_receipt_path)
                                        <a href="{{ asset('storage/' . $reg->latestPayment->payment_receipt_path) }}" target="_blank" class="btn btn-xs btn-outline-primary rounded-pill px-2.5 py-0.5 extra-small fw-medium">
                                            <i class="bx bx-file me-1"></i>Receipt: {{ $reg->latestPayment->transaction_id ?? 'View' }}
                                        </a>
                                    @elseif($reg->latestPayment && $reg->latestPayment->transaction_id)
                                        <span class="badge bg-label-secondary extra-small font-monospace"><i class="bx bx-hash me-0.5"></i>{{ $reg->latestPayment->transaction_id }}</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-success px-3 py-1.5 rounded-pill fw-semibold text-uppercase shadow-xs" style="font-size: 0.75rem;">
                                        <i class="bx bx-check-double me-1"></i> {{ $reg->status }}
                                    </span>
                                </td>
                                <td class="pe-3 text-end">
                                    <div class="d-inline-flex gap-1.5 align-items-center">
                                        <a href="{{ route('show-registration-details', $reg->registration_number ?? ($reg->acknowledgement_id ?? $reg->id)) }}" class="btn btn-sm btn-primary py-1 px-2.5 fw-medium rounded-2 shadow-xs" title="View Full Details">
                                            <i class="bx bx-show me-1"></i>Details
                                        </a>
                                        @if($reg->registration_number)
                                            <a href="{{ route('download.receipt', $reg->registration_number) }}" target="_blank" class="btn btn-sm btn-outline-danger py-1 px-2 fw-medium rounded-2" title="Download Receipt PDF">
                                                <i class="bx bxs-file-pdf"></i>
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <div class="py-4">
                                        <i class="bx bx-check-double text-success mb-2" style="font-size: 3.5rem;"></i>
                                        <h6 class="fw-semibold text-dark mb-1">No Approved Registrations Yet</h6>
                                        <p class="text-muted small mb-0">Approved Indian registrations will be listed here once payment is verified and approved.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $('#approvedSearchInput').on('keyup', function() {
            var value = $(this).val().toLowerCase();
            $('#approvedDelegatesTable tbody tr').filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
            });
        });
    });
</script>
@endsection
