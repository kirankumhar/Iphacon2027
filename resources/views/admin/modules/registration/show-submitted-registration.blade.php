@extends('admin.layouts.main')

@section('admin-content')
<div class="container-xxl flex-grow-1 mt-3.5 mb-4">
    <!-- Top Title Header -->
    <div class="d-flex align-items-center justify-content-between py-2 mb-3">
        <h5 class="mb-0 fw-bold text-dark">
            <i class="bx bx-paper-plane me-2 text-warning fs-4"></i>Submitted Delegates (Awaiting Approval)
        </h5>
        <span class="badge bg-warning text-dark rounded-pill px-3.5 py-2 fs-7 fw-bold shadow-xs">
            Total Submitted: {{ $registrations->count() }}
        </span>
    </div>

    <!-- Alert Messages -->
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

    <!-- Submitted Delegates List Table Card -->
    <div class="card border-0 shadow-sm rounded-3 overflow-hidden">
        <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between flex-wrap gap-2">
            <h6 class="mb-0 fw-bold text-dark">
                <i class="bx bx-list-check me-1.5 text-primary"></i>Pending Approval List
            </h6>
            <div class="search-box" style="max-width: 300px; width: 100%;">
                <div class="input-group input-group-sm">
                    <span class="input-group-text bg-light border-end-0"><i class="bx bx-search text-muted"></i></span>
                    <input type="text" id="submittedSearchInput" class="form-control bg-light border-start-0" placeholder="Search name, reg no, email...">
                </div>
            </div>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="submittedDelegatesTable">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-3 py-3" style="width: 4%;">#</th>
                            <th class="py-3" style="width: 25%;">Delegate Info</th>
                            <th class="py-3" style="width: 20%;">Category & Type</th>
                            <th class="py-3" style="width: 20%;">Payment Info</th>
                            <th class="py-3" style="width: 13%;">Status</th>
                            <th class="pe-3 py-3 text-end" style="width: 18%;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($registrations as $index => $reg)
                            <tr>
                                <td class="ps-3 fw-bold text-muted">{{ $index + 1 }}</td>
                                <td>
                                    <div class="d-flex align-items-center gap-2.5">
                                        <div class="avatar avatar-md flex-shrink-0" style="width: 42px; height: 42px;">
                                            <img src="{{ $reg->photo_path ? asset('storage/' . $reg->photo_path) : asset('images/default-avatar.svg') }}"
                                                alt="Avatar" class="rounded-circle w-100 h-100 border shadow-xs" style="object-fit: cover;"
                                                onerror="this.onerror=null; this.src='{{ asset('images/default-avatar.svg') }}';" />
                                        </div>
                                        <div>
                                            <h6 class="mb-0 fw-bold text-dark" style="font-size: 0.9rem;">
                                                {{ $reg->user?->prefix }} {{ $reg->user?->full_name ?? 'N/A' }}
                                            </h6>
                                            <div class="extra-small text-muted mb-0.5" style="font-size: 0.76rem;">
                                                <i class="bx bx-barcode me-0.5 text-primary"></i>Ack ID: <strong class="text-dark font-monospace">{{ $reg->acknowledgement_id ?? 'N/A' }}</strong>
                                            </div>
                                            <div class="extra-small text-muted" style="font-size: 0.76rem;">
                                                <i class="bx bx-envelope me-0.5"></i>{{ $reg->user?->email }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-label-primary px-2.5 py-1 mb-1 fw-semibold extra-small" style="font-size: 0.72rem;">
                                        {{ $reg->delegate_type ?? 'Indian' }} Delegate
                                    </span>
                                    <div class="fw-bold text-dark extra-small" style="font-size: 0.78rem;">
                                        <i class="bx bx-tag-alt text-muted me-1"></i>{{ $reg->delegateCategory?->category_name ?? 'N/A' }}
                                    </div>
                                </td>
                                <td>
                                    <div class="fw-bold text-dark extra-small" style="font-size: 0.82rem;">
                                        ₹{{ number_format($reg->total_amount, 2) }}
                                    </div>
                                    @if ($reg->latestPayment?->transaction_id)
                                        <div class="extra-small text-muted" style="font-size: 0.75rem;">
                                            Txn: <span class="font-monospace text-primary fw-bold">{{ $reg->latestPayment->transaction_id }}</span>
                                        </div>
                                    @endif
                                    @if ($reg->latestPayment?->payment_receipt_path)
                                        <a href="{{ asset('storage/' . $reg->latestPayment->payment_receipt_path) }}" target="_blank" class="extra-small text-decoration-none fw-bold text-info" style="font-size: 0.72rem;">
                                            <i class="bx bx-receipt me-0.5"></i>View Receipt
                                        </a>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-warning text-dark px-2.5 py-1.5 rounded-pill fw-bold" style="font-size: 0.72rem;">
                                        <i class="bx bx-time-five me-1"></i>SUBMITTED
                                    </span>
                                </td>
                                <td class="pe-3 text-end">
                                    <div class="d-flex align-items-center justify-content-end gap-1 flex-wrap">
                                        <a href="{{ route('show-registration-details', $reg->registration_number ?? ($reg->acknowledgement_id ?? $reg->id)) }}" class="btn btn-sm btn-outline-primary px-2 py-1 rounded-2" title="View Details" style="font-size: 0.75rem;">
                                            <i class="bx bx-show"></i> Details
                                        </a>

                                        <!-- Direct Approve Form -->
                                        <form method="POST" action="{{ route('student-approved-regis') }}" class="d-inline">
                                            @csrf
                                            <input type="hidden" name="registration_id" value="{{ $reg->id }}">
                                            <input type="hidden" name="acknowledgement_id" value="{{ $reg->acknowledgement_id }}">
                                            <button type="submit" class="btn btn-sm btn-success px-2 py-1 rounded-2" onclick="return confirm('Approve registration for {{ $reg->user?->full_name }}?')" title="Approve Registration" style="font-size: 0.75rem;">
                                                <i class="bx bx-check"></i> Approve
                                            </button>
                                        </form>

                                        <!-- Revert Button Trigger Modal -->
                                        <button type="button" class="btn btn-sm btn-outline-warning px-2 py-1 rounded-2" data-bs-toggle="modal" data-bs-target="#revertModal{{ $reg->id }}" title="Revert Back" style="font-size: 0.75rem;">
                                            <i class="bx bx-undo"></i>
                                        </button>

                                        <!-- Reject Button Trigger Modal -->
                                        <button type="button" class="btn btn-sm btn-outline-danger px-2 py-1 rounded-2" data-bs-toggle="modal" data-bs-target="#rejectModal{{ $reg->id }}" title="Reject Registration" style="font-size: 0.75rem;">
                                            <i class="bx bx-x"></i>
                                        </button>
                                    </div>

                                    <!-- Revert Modal -->
                                    <div class="modal fade text-start" id="revertModal{{ $reg->id }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <form method="POST" action="{{ route('student-revert-regis') }}">
                                                    @csrf
                                                    <input type="hidden" name="registration_number" value="{{ $reg->registration_number }}">
                                                    <div class="modal-header bg-warning bg-opacity-10 py-3">
                                                        <h6 class="modal-title fw-bold text-dark"><i class="bx bx-undo text-warning me-1"></i> Revert Registration</h6>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body py-3">
                                                        <p class="small text-muted mb-2">Reverting will allow delegate <strong>{{ $reg->user?->full_name }}</strong> to make corrections.</p>
                                                        <div class="mb-3">
                                                            <label class="form-label fw-semibold small">Revert Reason / Remarks *</label>
                                                            <textarea name="revert_reason" class="form-control form-control-sm" rows="3" placeholder="Enter reason for reverting..." required></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer py-2">
                                                        <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                        <button type="submit" class="btn btn-sm btn-warning text-dark fw-bold">Submit Revert</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Reject Modal -->
                                    <div class="modal fade text-start" id="rejectModal{{ $reg->id }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <form method="POST" action="{{ route('student-reject-regis') }}">
                                                    @csrf
                                                    <input type="hidden" name="registration_number" value="{{ $reg->registration_number }}">
                                                    <div class="modal-header bg-danger bg-opacity-10 py-3">
                                                        <h6 class="modal-title fw-bold text-danger"><i class="bx bx-x-circle text-danger me-1"></i> Reject Registration</h6>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body py-3">
                                                        <p class="small text-muted mb-2">Are you sure you want to reject registration for <strong>{{ $reg->user?->full_name }}</strong>?</p>
                                                        <div class="mb-3">
                                                            <label class="form-label fw-semibold small">Rejection Reason *</label>
                                                            <textarea name="rejection_reason" class="form-control form-control-sm" rows="3" placeholder="Enter reason for rejection..." required></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer py-2">
                                                        <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                        <button type="submit" class="btn btn-sm btn-danger fw-bold">Confirm Reject</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <div class="text-muted">
                                        <i class="bx bx-check-double fs-1 mb-2 text-success"></i>
                                        <p class="mb-0 fw-semibold">No submitted registrations pending approval.</p>
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
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('submittedSearchInput');
    const tableRows = document.querySelectorAll('#submittedDelegatesTable tbody tr');

    if (searchInput) {
        searchInput.addEventListener('keyup', function() {
            const query = this.value.toLowerCase().trim();
            tableRows.forEach(row => {
                const text = row.innerText.toLowerCase();
                if (text.includes(query)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    }
});
</script>
@endpush
