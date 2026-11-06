@extends('admin.layouts.main')

@section('admin-content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 py-3 mb-2">
        <div>
            <div class="d-flex align-items-center gap-2">
                <div class="rounded-circle d-flex align-items-center justify-content-center shadow-xs" style="width: 36px; height: 36px; background: #EEF2FF; color: #4F46E5;">
                    <i class="bx bx-group fs-4"></i>
                </div>
                <div>
                    <h5 class="mb-0 fw-bold text-dark" style="letter-spacing: -0.3px;">User Account Management</h5>
                    <p class="text-muted small mb-0" style="font-size: 0.78rem;">Manage portal users, view registration status and enable/disable account access</p>
                </div>
            </div>
        </div>
        <div class="d-flex align-items-center gap-2">
            <span class="badge px-3 py-2 rounded-pill fw-bold shadow-xs" style="background: #EEF2FF; color: #4338CA; border: 1px solid #C7D2FE; font-size: 0.8rem;">
                <i class="bx bx-user me-1"></i> Total Users: {{ $users->total() }}
            </span>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show p-3 mb-3 rounded-3 shadow-xs border-0" role="alert" style="background: #ECFDF5; border-left: 4px solid #10B981 !important;">
            <div class="d-flex align-items-center gap-2 text-dark">
                <i class="bx bx-check-circle fs-4 text-success"></i>
                <div class="small fw-semibold">{{ session('success') }}</div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show p-3 mb-3 rounded-3 shadow-xs border-0" role="alert" style="background: #FEF2F2; border-left: 4px solid #EF4444 !important;">
            <div class="d-flex align-items-center gap-2 text-dark">
                <i class="bx bx-error-circle fs-4 text-danger"></i>
                <div class="small fw-semibold">{{ session('error') }}</div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card border-0 shadow-sm rounded-3 overflow-hidden">
        <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between flex-wrap gap-2">
            <h6 class="mb-0 fw-bold text-dark d-flex align-items-center gap-2">
                <i class="bx bx-list-ul text-primary fs-5"></i>Registered Users
            </h6>
            <div style="max-width: 280px; width: 100%;">
                <input type="text" id="userFilterInput" class="form-control form-control-sm rounded-pill" placeholder="Filter by name or email...">
            </div>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="usersTable">
                    <thead style="background-color: #F8FAFC; border-bottom: 1px solid #E2E8F0;">
                        <tr>
                            <th class="ps-3 py-3 text-secondary text-uppercase fw-bold" style="font-size: 0.72rem; width: 40px;">#</th>
                            <th class="py-3 text-secondary text-uppercase fw-bold" style="font-size: 0.72rem;">User Details</th>
                            <th class="py-3 text-secondary text-uppercase fw-bold" style="font-size: 0.72rem;">Contact</th>
                            <th class="py-3 text-secondary text-uppercase fw-bold" style="font-size: 0.72rem;">Delegate Type</th>
                            <th class="py-3 text-secondary text-uppercase fw-bold" style="font-size: 0.72rem;">Registration</th>
                            <th class="py-3 text-secondary text-uppercase fw-bold" style="font-size: 0.72rem;">Account Status</th>
                            <th class="pe-3 py-3 text-secondary text-uppercase fw-bold text-end" style="font-size: 0.72rem;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $index => $u)
                            @php
                                $isDisabled = $u->isDisabled();
                                $reg = $u->registrations->first();
                            @endphp
                            <tr class="user-row">
                                <td class="ps-3 text-muted fw-semibold" style="font-size: 0.8rem;">
                                    {{ $users->firstItem() ? ($users->firstItem() + $index) : ($index + 1) }}
                                </td>
                                <td>
                                    <div class="fw-bold text-dark" style="font-size: 0.88rem;">
                                        {{ $u->prefix }} {{ $u->full_name }}
                                    </div>
                                    <div class="text-muted extra-small" style="font-size: 0.75rem;">
                                        <i class="bx bx-envelope text-primary me-0.5"></i>{{ $u->email }}
                                    </div>
                                    @if($u->designation)
                                        <div class="text-muted extra-small" style="font-size: 0.72rem;">
                                            {{ $u->designation }} {{ $u->institution ? '• ' . $u->institution : '' }}
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <div class="text-dark small">
                                        <i class="bx bx-phone text-secondary me-0.5"></i>{{ $u->mobile_country_code }} {{ $u->mobile_number }}
                                    </div>
                                    <div class="extra-small text-muted" style="font-size: 0.72rem;">
                                        Joined: {{ $u->created_at ? $u->created_at->format('d M, Y') : 'N/A' }}
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-light text-primary border px-2.5 py-1 rounded-pill extra-small fw-semibold">
                                        {{ $u->delegate_type ?? 'Indian' }}
                                    </span>
                                </td>
                                <td>
                                    @if($reg)
                                        <a href="{{ route('show-registration-details', $reg->registration_number ?? ($reg->acknowledgement_id ?? $reg->id)) }}" class="badge bg-light text-dark border px-2 py-1 rounded-2 text-decoration-none">
                                            <i class="bx bx-link-external me-0.5"></i>{{ $reg->registration_number ?? ($reg->acknowledgement_id ?? 'Draft') }}
                                        </a>
                                        <div class="extra-small mt-0.5 {{ $reg->status === 'Approved' ? 'text-success fw-bold' : 'text-muted' }}" style="font-size: 0.7rem;">
                                            Status: {{ $reg->status }}
                                        </div>
                                    @else
                                        <span class="badge bg-light text-muted border px-2 py-0.5 rounded-2 extra-small">No Registration</span>
                                    @endif
                                </td>
                                <td>
                                    @if($isDisabled)
                                        <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 rounded-pill px-2.5 py-1 extra-small fw-bold">
                                            <i class="bx bx-block me-0.5"></i>Disabled
                                        </span>
                                    @else
                                        <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 rounded-pill px-2.5 py-1 extra-small fw-bold">
                                            <i class="bx bx-check-circle me-0.5"></i>Active
                                        </span>
                                    @endif
                                </td>
                                <td class="pe-3 text-end">
                                    <form action="{{ route('admin.user.toggle-status', $u->id) }}" method="POST" class="d-inline m-0">
                                        @csrf
                                        @if($isDisabled)
                                            <button type="submit" class="btn btn-xs btn-outline-success rounded-pill px-2.5 py-1 fw-bold shadow-2xs"
                                                onclick="return confirm('Enable user account for {{ addslashes($u->full_name) }}?')" title="Enable user login">
                                                <i class="bx bx-check me-0.5"></i>Enable
                                            </button>
                                        @else
                                            <button type="submit" class="btn btn-xs btn-outline-danger rounded-pill px-2.5 py-1 fw-bold shadow-2xs"
                                                onclick="return confirm('Disable user account for {{ addslashes($u->full_name) }}? The user will be logged out and blocked from logging in.')" title="Disable user login">
                                                <i class="bx bx-block me-0.5"></i>Disable
                                            </button>
                                        @endif
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5 text-muted">
                                    <i class="bx bx-user-x fs-1 mb-2 d-block"></i>
                                    No users found in database.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="px-3 py-3 border-top d-flex justify-content-between align-items-center flex-wrap gap-2">
                <span class="text-muted extra-small">
                    Showing {{ $users->firstItem() ?? 0 }} to {{ $users->lastItem() ?? 0 }} of {{ $users->total() }} users
                </span>
                <div>
                    {{ $users->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.getElementById('userFilterInput')?.addEventListener('keyup', function() {
    const filter = this.value.toLowerCase().trim();
    const rows = document.querySelectorAll('#usersTable tbody tr.user-row');
    rows.forEach(row => {
        const text = row.innerText.toLowerCase();
        row.style.display = text.includes(filter) ? '' : 'none';
    });
});
</script>
@endpush
@endsection
