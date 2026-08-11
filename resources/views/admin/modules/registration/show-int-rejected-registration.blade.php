@extends('admin.layouts.main')

@section('admin-content')
<div class="container-xxl flex-grow-1 mt-3.5 mb-4">
    <!-- Header Title Bar -->
    <div class="d-flex align-items-center justify-content-between py-2 mb-3">
        <h5 class="mb-0 fw-semibold text-dark">
            <i class="bx bx-x-circle me-2 text-danger fs-4"></i>Rejected Delegates
        </h5>
        <span class="badge bg-light text-dark border px-3 py-1.5 fs-7 fw-medium rounded-2">
            Total Rejected: {{ $registrations->count() }}
        </span>
    </div>

    <!-- Alert Messages -->
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show p-3 mb-3 rounded-3" role="alert">
            <div class="d-flex align-items-center gap-2">
                <i class="bx bx-check-circle fs-4 text-success"></i>
                <div>{{ session('success') }}</div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show p-3 mb-3 rounded-3" role="alert">
            <div class="d-flex align-items-center gap-2">
                <i class="bx bx-error-circle fs-4 text-danger"></i>
                <div>{{ session('error') }}</div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Rejected Delegates Card -->
    <div class="card border-0 shadow-sm rounded-3 overflow-hidden">
        <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between flex-wrap gap-2">
            <h6 class="mb-0 fw-semibold text-dark">
                <i class="bx bx-list-minus me-1.5 text-danger"></i>Rejected List
            </h6>
            <div class="search-box" style="max-width: 300px; width: 100%;">
                <div class="input-group input-group-sm">
                    <span class="input-group-text bg-light border-end-0"><i class="bx bx-search text-muted"></i></span>
                    <input type="text" id="rejectedSearchInput" class="form-control bg-light border-start-0" placeholder="Search name, ack id, category...">
                </div>
            </div>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="rejectedDelegatesTable">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-3 py-3 fw-semibold" style="width: 4%;">#</th>
                            <th class="py-3 fw-semibold" style="width: 28%;">Delegate Info</th>
                            <th class="py-3 fw-semibold" style="width: 20%;">Category & Type</th>
                            <th class="py-3 fw-semibold" style="width: 24%;">Rejection Reason</th>
                            <th class="py-3 fw-semibold" style="width: 12%;">Status</th>
                            <th class="pe-3 py-3 text-end fw-semibold" style="width: 12%;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($registrations as $index => $reg)
                            <tr>
                                <td class="ps-3 fw-medium text-muted">{{ $index + 1 }}</td>
                                <td>
                                    <div class="d-flex align-items-center gap-2.5">
                                        <div class="avatar avatar-md flex-shrink-0" style="width: 40px; height: 40px;">
                                            <img src="{{ $reg->photo_path ? asset('storage/' . $reg->photo_path) : asset('images/default-avatar.svg') }}"
                                                alt="Avatar" class="rounded-circle w-100 h-100 border" style="object-fit: cover;"
                                                onerror="this.onerror=null; this.src='{{ asset('images/default-avatar.svg') }}';" />
                                        </div>
                                        <div>
                                            <h6 class="mb-0 fw-semibold" style="font-size: 0.88rem;">
                                                <a href="{{ route('show-registration-details', $reg->acknowledgement_id ?? $reg->id) }}" class="text-dark text-decoration-none">
                                                    {{ $reg->user?->prefix }} {{ $reg->user?->full_name ?? 'N/A' }}
                                                </a>
                                            </h6>
                                            <div class="extra-small text-muted mb-0.5" style="font-size: 0.76rem;">
                                                <i class="bx bx-barcode me-0.5 text-muted"></i>Ack ID: <span class="fw-semibold font-monospace text-dark">{{ $reg->acknowledgement_id ?? 'N/A' }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-light text-secondary border px-2.5 py-0.5 mb-1 fw-medium extra-small" style="font-size: 0.72rem;">
                                        {{ $reg->delegate_type ?? 'Indian' }} Delegate
                                    </span>
                                    <div class="fw-medium text-dark extra-small" style="font-size: 0.78rem;">
                                        <i class="bx bx-tag-alt text-muted me-1"></i>{{ $reg->delegateCategory?->category_name ?? 'N/A' }}
                                    </div>
                                </td>
                                <td>
                                    <div class="extra-small text-dark fw-medium bg-light p-2 rounded-2 border text-break" style="font-size: 0.78rem;">
                                        {{ $reg->rejection_reason ?? 'No specific reason provided.' }}
                                    </div>
                                </td>
                                <td>
                                    <span class="badge extra-small px-2.5 py-1 rounded-2 fw-semibold" style="background-color: #FEE2E2; color: #0F172A; border: 1px solid #FECACA;">
                                        <i class="bx bx-x me-0.5 text-danger"></i>REJECTED
                                    </span>
                                </td>
                                <td class="pe-3 text-end">
                                    <a href="{{ route('show-registration-details', $reg->acknowledgement_id ?? $reg->id) }}" class="btn btn-xs btn-primary px-2.5 py-1 rounded-2 extra-small fw-medium" title="View Full Details">
                                        <i class="bx bx-show me-0.5"></i> Details
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <div class="text-muted">
                                        <i class="bx bx-check-circle fs-1 mb-2 text-secondary"></i>
                                        <p class="mb-0 fw-semibold text-dark">No rejected registrations found.</p>
                                        <p class="extra-small text-muted mb-0">No delegate registrations have been marked as rejected.</p>
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
    const searchInput = document.getElementById('rejectedSearchInput');
    const tableRows = document.querySelectorAll('#rejectedDelegatesTable tbody tr');

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
