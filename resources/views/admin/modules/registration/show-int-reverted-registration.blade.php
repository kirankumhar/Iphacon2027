@extends('admin.layouts.main')

@section('admin-content')
<div class="container-xxl flex-grow-1 mt-3.5 mb-4">
    <!-- Top Title Header -->
    <div class="d-flex align-items-center justify-content-between py-2 mb-3">
        <div>
            <span class="text-muted extra-small d-block">IPHACON 2027 Admin Portal</span>
            <h5 class="mb-0 fw-bold text-dark d-flex align-items-center gap-2">
                <i class="bx bx-undo text-warning fs-4"></i>Reverted Registrations List
            </h5>
        </div>
        <span class="badge bg-light text-dark border px-3 py-1.5 fs-7 fw-medium rounded-pill">
            <i class="bx bx-undo text-warning me-1"></i>Total Reverted: {{ $registrations->count() }}
        </span>
    </div>

    <!-- Alert Messages -->
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-xs rounded-3 mb-3 d-flex align-items-center" role="alert" style="background-color: #DCFCE7; color: #065F46;">
            <i class="bx bx-check-circle fs-4 me-2 text-success"></i>
            <div class="fw-bold">{{ session('success') }}</div>
            <button type="button" class="btn-close ms-auto py-2 px-2" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-xs rounded-3 mb-3 d-flex align-items-center" role="alert" style="background-color: #FEE2E2; color: #991B1B;">
            <i class="bx bx-error-circle fs-4 me-2 text-danger"></i>
            <div class="fw-bold">{{ session('error') }}</div>
            <button type="button" class="btn-close ms-auto py-2 px-2" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Reverted Delegates Table Card -->
    <div class="card border-0 shadow-sm rounded-3 overflow-hidden">
        <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between flex-wrap gap-2">
            <h6 class="mb-0 fw-bold text-dark d-flex align-items-center gap-2">
                <i class="bx bx-list-check text-primary fs-5"></i>Reverted Applications (Awaiting User Resubmission)
            </h6>
            <div class="search-box" style="max-width: 280px; width: 100%;">
                <div class="input-group input-group-sm">
                    <span class="input-group-text bg-light border-end-0"><i class="bx bx-search text-muted"></i></span>
                    <input type="text" id="revertedSearchInput" class="form-control bg-light border-start-0" placeholder="Search name, ack id, reason...">
                </div>
            </div>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="revertedDelegatesTable">
                    <thead style="background-color: #F8FAFC; border-bottom: 1px solid #E2E8F0;">
                        <tr>
                            <th class="ps-3 py-3 text-secondary text-uppercase fw-bold extra-small" style="width: 35px;">#</th>
                            <th class="py-3 text-secondary text-uppercase fw-bold extra-small" style="width: 26%;"><i class="bx bx-user text-primary me-1"></i> Delegate Info</th>
                            <th class="py-3 text-secondary text-uppercase fw-bold extra-small" style="width: 20%;"><i class="bx bx-category text-info me-1"></i> Category &amp; Type</th>
                            <th class="py-3 text-secondary text-uppercase fw-bold extra-small" style="width: 28%;"><i class="bx bx-comment-detail text-warning me-1"></i> Revert Reason &amp; Remarks</th>
                            <th class="py-3 text-secondary text-uppercase fw-bold extra-small" style="width: 10%;"><i class="bx bx-time me-1 text-warning"></i> Status</th>
                            <th class="pe-3 py-3 text-secondary text-uppercase fw-bold text-end extra-small" style="width: 16%;"><i class="bx bx-slider-alt text-warning me-1"></i> Actions</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @forelse ($registrations as $index => $reg)
                            <tr>
                                <td class="ps-3 fw-semibold text-muted small">{{ $index + 1 }}</td>
                                <td class="py-2.5">
                                    <div class="d-flex align-items-center gap-2.5">
                                        <div class="avatar avatar-md flex-shrink-0" style="width: 38px; height: 38px;">
                                            @if($reg->photo_path)
                                                <img src="{{ asset('storage/' . $reg->photo_path) }}" alt="Avatar" class="rounded-circle border w-100 h-100 shadow-xs" style="object-fit: cover;"
                                                    onerror="this.onerror=null; this.src='{{ asset('images/default-avatar.svg') }}';" />
                                            @else
                                                <div class="avatar-initial rounded-circle bg-primary bg-opacity-10 text-primary fw-bold d-flex align-items-center justify-content-center w-100 h-100 fs-6 border border-primary border-opacity-25">
                                                    {{ strtoupper(substr($reg->user?->full_name ?? 'U', 0, 1)) }}
                                                </div>
                                            @endif
                                        </div>
                                        <div>
                                            <div class="fw-bold text-dark" style="font-size: 0.86rem; line-height: 1.2;">
                                                {{ $reg->user?->prefix }} {{ $reg->user?->full_name ?? 'N/A' }}
                                            </div>
                                            <div class="text-muted extra-small text-truncate mb-1" style="max-width: 180px; font-size: 0.75rem;">
                                                {{ $reg->user?->email }}
                                            </div>
                                            <span class="badge font-monospace extra-small px-2 py-0.5 rounded-2" style="background-color: #F1F5F9; color: #334155; border: 1px solid #CBD5E1; font-size: 0.70rem;">
                                                ACK: {{ $reg->acknowledgement_id ?? 'N/A' }}
                                            </span>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge rounded-pill px-2.5 py-1 extra-small fw-semibold mb-1" style="background-color: #EFF6FF; color: #1D4ED8; border: 1px solid #BFDBFE;">
                                        {{ $reg->delegate_type ?? 'Indian' }} Delegate
                                    </span>
                                    <div class="extra-small text-dark fw-medium mt-0.5" style="font-size: 0.76rem;">
                                        {{ $reg->delegateCategory?->category_name ?? 'N/A' }}
                                    </div>
                                </td>
                                <td>
                                    <div class="p-2 rounded-2 border extra-small mb-1" style="background-color: #FEF3C7; color: #92400E; border-color: #FDE68A !important; font-size: 0.75rem;">
                                        <i class="bx bx-info-circle me-1"></i>{{ $reg->revert_reason ?? 'Application reverted for corrections.' }}
                                    </div>
                                    @if($reg->reverted_at)
                                        <div class="text-muted extra-small" style="font-size: 0.70rem;">
                                            <i class="bx bx-time me-0.5"></i>Reverted on: {{ \Carbon\Carbon::parse($reg->reverted_at)->format('d M Y, h:i A') }}
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge rounded-pill fw-semibold extra-small px-2.5 py-1" style="background-color: #FEF3C7; color: #92400E; border: 1px solid #FDE68A; font-size: 0.72rem;">
                                        <i class="bx bx-undo me-1"></i>REVERTED
                                    </span>
                                </td>
                                <td class="pe-3 text-end">
                                    <div class="d-flex align-items-center justify-content-end gap-1" style="white-space: nowrap;">
                                        <a href="{{ route('show-registration-details', $reg->acknowledgement_id ?? $reg->id) }}" class="btn btn-xs btn-outline-primary fw-bold px-2.5 py-1 rounded-2" style="font-size: 0.74rem;">
                                            <i class="bx bx-show me-0.5"></i> Details
                                        </a>

                                        @if($reg->latestPayment)
                                        <form method="POST" action="{{ route('student-approved-regis') }}" class="d-inline m-0">
                                            @csrf
                                            <input type="hidden" name="registration_id" value="{{ $reg->id }}">
                                            <input type="hidden" name="acknowledgement_id" value="{{ $reg->acknowledgement_id }}">
                                            <button type="submit" class="btn btn-xs btn-success fw-bold px-2.5 py-1 rounded-2 shadow-xs d-inline-flex align-items-center gap-1" style="font-size: 0.74rem;" onclick="return confirm('Approve registration for {{ $reg->user?->full_name }}?')">
                                                <i class="bx bx-check-circle" style="font-size: 0.85rem;"></i> Approve
                                            </button>
                                        </form>
                                        @endif

                                        <form action="{{ route('student-regis-delete') }}" method="POST" class="d-inline m-0">
                                            @csrf
                                            <input type="hidden" name="registration_id" value="{{ $reg->id }}">
                                            <input type="hidden" name="acknowledgement_id" value="{{ $reg->acknowledgement_id }}">
                                            <button type="submit" class="btn btn-xs btn-icon btn-light text-danger rounded-2" title="Delete Registration" onclick="return confirm('Are you sure you want to delete this registration?')">
                                                <i class="bx bx-trash" style="font-size: 0.85rem;"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <div class="py-4 text-muted">
                                        <i class="bx bx-check-double text-success mb-2" style="font-size: 3.5rem;"></i>
                                        <h6 class="fw-bold text-dark mb-1">No Reverted Registrations</h6>
                                        <p class="mb-0 extra-small">There are currently no reverted registrations awaiting delegate resubmission.</p>
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
    const searchInput = document.getElementById('revertedSearchInput');
    const tableRows = document.querySelectorAll('#revertedDelegatesTable tbody tr');

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
