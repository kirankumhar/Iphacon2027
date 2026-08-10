@extends('admin.layouts.main')

@section('admin-content')
<div class="container-xxl flex-grow-1 mt-3.5 mb-4">
    <div class="d-flex align-items-center justify-content-between py-2 mb-3">
        <h5 class="mb-0 fw-bold text-dark">
            <i class="fas fa-graduation-cap me-2 text-info fs-4"></i>Pre-Conference Workshop Participants
        </h5>
        <span class="badge bg-info text-white rounded-pill px-3.5 py-2 fs-7 fw-bold shadow-xs">
            Total Workshop Participants: {{ $registrations->count() }}
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

    <div class="card border-0 shadow-sm rounded-3 overflow-hidden">
        <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between flex-wrap gap-2">
            <h6 class="mb-0 fw-bold text-dark"><i class="bx bx-list-check me-1.5 text-primary"></i>Pre-Conference Workshop Delegates List</h6>
            <div class="search-box" style="max-width: 300px; width: 100%;">
                <div class="input-group input-group-sm">
                    <span class="input-group-text bg-light border-end-0"><i class="bx bx-search text-muted"></i></span>
                    <input type="text" id="cmeSearchInput" class="form-control bg-light border-start-0" placeholder="Search name, reg no, email...">
                </div>
            </div>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="cmeDelegatesTable">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-3 py-3" style="width: 5%;">#</th>
                            <th class="py-3" style="width: 30%;">Delegate Info</th>
                            <th class="py-3" style="width: 25%;">Category & Type</th>
                            <th class="py-3" style="width: 20%;">Status</th>
                            <th class="pe-3 py-3 text-end" style="width: 20%;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($registrations as $index => $reg)
                            <tr>
                                <td class="ps-3 fw-bold text-muted">{{ $index + 1 }}</td>
                                <td>
                                    <div class="d-flex align-items-center gap-2.5">
                                        <div class="avatar avatar-md flex-shrink-0" style="width: 40px; height: 40px;">
                                            <img src="{{ $reg->photo_path ? asset('storage/' . $reg->photo_path) : asset('images/default-avatar.svg') }}"
                                                alt="Avatar" class="rounded-circle w-100 h-100 border shadow-xs" style="object-fit: cover;"
                                                onerror="this.onerror=null; this.src='{{ asset('images/default-avatar.svg') }}';" />
                                        </div>
                                        <div>
                                            <h6 class="mb-0 fw-bold text-dark" style="font-size: 0.9rem;">
                                                {{ $reg->user?->prefix }} {{ $reg->user?->full_name ?? 'N/A' }}
                                            </h6>
                                            <div class="extra-small text-muted mb-0.5" style="font-size: 0.76rem;">
                                                <i class="bx bx-hash me-0.5 text-primary"></i><strong>{{ $reg->registration_number ?? 'N/A' }}</strong>
                                            </div>
                                            <div class="extra-small text-muted" style="font-size: 0.76rem;">
                                                <i class="bx bx-envelope me-0.5"></i>{{ $reg->user?->email }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-label-info text-info px-2.5 py-1 mb-1 fw-semibold extra-small" style="font-size: 0.72rem;">
                                        <i class="fas fa-graduation-cap me-1"></i>CME Workshop
                                    </span>
                                    <div class="fw-bold text-dark extra-small" style="font-size: 0.78rem;">
                                        {{ $reg->delegateCategory?->category_name ?? 'Delegate' }} ({{ $reg->delegate_type ?? 'Indian' }})
                                    </div>
                                </td>
                                <td>
                                    @if(strtolower($reg->status) === 'approved')
                                        <span class="badge bg-success text-white px-2.5 py-1 rounded-pill fw-bold" style="font-size: 0.72rem;">
                                            <i class="bx bx-check-circle me-1"></i>APPROVED
                                        </span>
                                    @else
                                        <span class="badge bg-warning text-dark px-2.5 py-1 rounded-pill fw-bold" style="font-size: 0.72rem;">
                                            {{ strtoupper($reg->status ?? 'PENDING') }}
                                        </span>
                                    @endif
                                </td>
                                <td class="pe-3 text-end">
                                    <a href="{{ route('show-registration-details', $reg->registration_number ?? ($reg->acknowledgement_id ?? $reg->id)) }}" class="btn btn-sm btn-outline-primary px-3 py-1 rounded-2" style="font-size: 0.75rem;">
                                        <i class="bx bx-show me-1"></i> Details
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5">
                                    <div class="text-muted">
                                        <i class="fas fa-graduation-cap fs-1 mb-2 text-secondary"></i>
                                        <p class="mb-0 fw-semibold">No CME Workshop participants found.</p>
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
    const searchInput = document.getElementById('cmeSearchInput');
    const tableRows = document.querySelectorAll('#cmeDelegatesTable tbody tr');

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
