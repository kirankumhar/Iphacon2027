@extends('admin.layouts.main')

@section('admin-content')
<div class="container-xxl flex-grow-1 mt-3.5 mb-4">
    <!-- Page Header -->
    <div class="d-flex align-items-center justify-content-between py-2 mb-3">
        <h5 class="mb-0 fw-bold text-dark">
            <i class="bx bx-time-five me-2 text-warning fs-4"></i>Incomplete Registrations
        </h5>
        <div class="d-flex gap-2">
            <span class="badge bg-warning text-dark rounded-pill px-3 py-2 fs-7 fw-bold shadow-xs">
                Draft Forms: {{ method_exists($registrations, 'total') ? $registrations->total() : $registrations->count() }}
            </span>
            <span class="badge bg-secondary text-white rounded-pill px-3 py-2 fs-7 fw-bold shadow-xs">
                Signed Up (Not Started): {{ method_exists($usersWithoutReg, 'total') ? $usersWithoutReg->total() : $usersWithoutReg->count() }}
            </span>
        </div>
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

    <!-- Card 1: Draft Registration Forms (Started but not submitted) -->
    <div class="card border-0 shadow-sm rounded-3 overflow-hidden mb-4">
        <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between flex-wrap gap-2">
            <h6 class="mb-0 fw-bold text-dark">
                <i class="bx bx-edit text-warning me-1.5"></i>Draft / Incomplete Application Forms
            </h6>
            <div class="search-box" style="max-width: 320px; width: 100%;">
                <form method="GET" action="{{ route('indian-incomplete-delegates') }}">
                    @if(request('user_page'))
                        <input type="hidden" name="user_page" value="{{ request('user_page') }}">
                    @endif
                    @if(request('user_search'))
                        <input type="hidden" name="user_search" value="{{ request('user_search') }}">
                    @endif
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-light border-end-0"><i class="bx bx-search text-muted"></i></span>
                        <input type="text" name="draft_search" id="draftSearchInput" class="form-control bg-light border-start-0" placeholder="Search draft by name, email..." value="{{ request('draft_search') }}">
                        @if(request('draft_search'))
                            <a href="{{ request()->fullUrlWithQuery(['draft_search' => null, 'draft_page' => null]) }}" class="btn btn-outline-secondary btn-sm" title="Clear filter">
                                <i class="bx bx-x"></i>
                            </a>
                        @endif
                    </div>
                </form>
            </div>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="draftTable">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-3 py-3" style="width: 5%;">#</th>
                            <th class="py-3" style="width: 30%;">Delegate Info</th>
                            <th class="py-3" style="width: 25%;">Category & Type</th>
                            <th class="py-3" style="width: 20%;">Step Completed</th>
                            <th class="pe-3 py-3 text-end" style="width: 20%;">Last Activity</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($registrations as $index => $reg)
                            <tr>
                                <td class="ps-3 fw-bold text-muted">{{ (method_exists($registrations, 'firstItem') && $registrations->firstItem()) ? ($registrations->firstItem() + $index) : ($index + 1) }}</td>
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
                                                <i class="bx bx-envelope me-0.5"></i>{{ $reg->user?->email }}
                                            </div>
                                            <div class="extra-small text-muted" style="font-size: 0.76rem;">
                                                <i class="bx bx-phone me-0.5"></i>{{ $reg->user?->mobile_number ?? 'N/A' }}
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
                                    <span class="badge bg-warning text-dark px-2.5 py-1 rounded-pill fw-bold" style="font-size: 0.72rem;">
                                        <i class="bx bx-loader-circle me-1"></i>Step {{ $reg->step_completed ?? 1 }} / 4
                                    </span>
                                    <div class="extra-small text-muted mt-1" style="font-size: 0.72rem;">
                                        Status: <strong>{{ $reg->status ?: 'Draft' }}</strong>
                                    </div>
                                </td>
                                <td class="pe-3 text-end">
                                    <small class="text-muted extra-small">
                                        {{ $reg->updated_at ? $reg->updated_at->format('d M Y, h:i A') : '-' }}
                                    </small>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5">
                                    <div class="text-muted">
                                        <i class="bx bx-check-circle fs-1 mb-2 text-success"></i>
                                        <p class="mb-0 fw-semibold">No draft / incomplete form submissions found.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination Footer for Draft Table -->
        @if(method_exists($registrations, 'hasPages') && $registrations->hasPages())
            <div class="card-footer bg-white py-3 border-top d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div class="extra-small text-muted">
                    Showing <strong>{{ $registrations->firstItem() }}</strong> to <strong>{{ $registrations->lastItem() }}</strong> of <strong>{{ $registrations->total() }}</strong> entries
                </div>
                <div>
                    {{ $registrations->links('pagination::bootstrap-5') }}
                </div>
            </div>
        @endif
    </div>

    <!-- Card 2: Registered Users who haven't started registration form -->
    <div class="card border-0 shadow-sm rounded-3 overflow-hidden">
        <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between flex-wrap gap-2">
            <h6 class="mb-0 fw-bold text-dark">
                <i class="bx bx-user-plus text-secondary me-1.5"></i>Signed Up Users (Form Not Started)
            </h6>
            <div class="search-box" style="max-width: 320px; width: 100%;">
                <form method="GET" action="{{ route('indian-incomplete-delegates') }}">
                    @if(request('draft_page'))
                        <input type="hidden" name="draft_page" value="{{ request('draft_page') }}">
                    @endif
                    @if(request('draft_search'))
                        <input type="hidden" name="draft_search" value="{{ request('draft_search') }}">
                    @endif
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-light border-end-0"><i class="bx bx-search text-muted"></i></span>
                        <input type="text" name="user_search" id="userSearchInput" class="form-control bg-light border-start-0" placeholder="Search user by name, email..." value="{{ request('user_search') }}">
                        @if(request('user_search'))
                            <a href="{{ request()->fullUrlWithQuery(['user_search' => null, 'user_page' => null]) }}" class="btn btn-outline-secondary btn-sm" title="Clear filter">
                                <i class="bx bx-x"></i>
                            </a>
                        @endif
                    </div>
                </form>
            </div>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="usersTable">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-3 py-3" style="width: 5%;">#</th>
                            <th class="py-3" style="width: 35%;">User Details</th>
                            <th class="py-3" style="width: 25%;">Delegate Type</th>
                            <th class="py-3" style="width: 20%;">Email Verified</th>
                            <th class="pe-3 py-3 text-end" style="width: 15%;">Sign Up Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($usersWithoutReg as $index => $u)
                            <tr>
                                <td class="ps-3 fw-bold text-muted">{{ (method_exists($usersWithoutReg, 'firstItem') && $usersWithoutReg->firstItem()) ? ($usersWithoutReg->firstItem() + $index) : ($index + 1) }}</td>
                                <td>
                                    <div class="d-flex align-items-center gap-2.5">
                                        <div class="avatar avatar-sm rounded-circle bg-label-primary text-primary fw-bold d-flex align-items-center justify-content-center" style="width: 36px; height: 36px; font-size: 0.85rem;">
                                            {{ strtoupper(substr($u->full_name ?? 'U', 0, 1)) }}
                                        </div>
                                        <div>
                                            <h6 class="mb-0 fw-bold text-dark" style="font-size: 0.88rem;">
                                                {{ $u->prefix }} {{ $u->full_name }}
                                            </h6>
                                            <small class="text-muted extra-small"><i class="bx bx-envelope me-0.5"></i>{{ $u->email }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border px-2.5 py-1 rounded-pill extra-small">
                                        {{ $u->delegate_type ?? 'Indian' }} Delegate
                                    </span>
                                </td>
                                <td>
                                    @if ($u->email_verified_at)
                                        <span class="badge bg-success text-white px-2.5 py-1 rounded-pill extra-small fw-bold">
                                            ✓ Verified
                                        </span>
                                    @else
                                        <span class="badge bg-secondary text-white px-2.5 py-1 rounded-pill extra-small">
                                            Unverified
                                        </span>
                                    @endif
                                </td>
                                <td class="pe-3 text-end">
                                    <small class="text-muted extra-small">
                                        {{ $u->created_at ? $u->created_at->format('d M Y') : '-' }}
                                    </small>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5">
                                    <div class="text-muted">
                                        <i class="bx bx-user-check fs-1 mb-2 text-success"></i>
                                        <p class="mb-0 fw-semibold">All signed-up users have started their registration form.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination Footer for Signed Up Users Table -->
        @if(method_exists($usersWithoutReg, 'hasPages') && $usersWithoutReg->hasPages())
            <div class="card-footer bg-white py-3 border-top d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div class="extra-small text-muted">
                    Showing <strong>{{ $usersWithoutReg->firstItem() }}</strong> to <strong>{{ $usersWithoutReg->lastItem() }}</strong> of <strong>{{ $usersWithoutReg->total() }}</strong> entries
                </div>
                <div>
                    {{ $usersWithoutReg->links('pagination::bootstrap-5') }}
                </div>
            </div>
        @endif
    </div>
</div>
@endsection

@push('styles')
<style>
.pagination {
    margin-bottom: 0;
}
.pagination .page-item .page-link {
    border-radius: 6px;
    margin: 0 2px;
    font-size: 0.8125rem;
    padding: 0.35rem 0.65rem;
}
</style>
@endpush
