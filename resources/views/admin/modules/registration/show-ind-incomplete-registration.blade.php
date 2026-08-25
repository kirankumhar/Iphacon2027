@extends('admin.layouts.main')

@section('admin-content')
<div class="container-xxl flex-grow-1 container-p-y">
    <!-- Page Header -->
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-3 mb-4">
        <div>
            <h4 class="mb-1 fw-bold text-dark d-flex align-items-center gap-2">
                <span class="badge bg-label-warning p-2 rounded-3">
                    <i class="bx bx-time-five fs-4 text-warning"></i>
                </span>
                <span>Incomplete Registrations</span>
            </h4>
            <p class="text-muted mb-0 fs-7">Track draft application forms and signed-up users who haven't finished registration</p>
        </div>
    </div>

    <!-- Alert Messages -->
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show p-3 mb-4 rounded-3 shadow-xs border-0 border-start border-4 border-success bg-success bg-opacity-10" role="alert">
            <div class="d-flex align-items-center gap-2.5">
                <i class="bx bx-check-circle fs-4 text-success"></i>
                <div class="fw-medium text-success text-opacity-75">{{ session('success') }}</div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show p-3 mb-4 rounded-3 shadow-xs border-0 border-start border-4 border-danger bg-danger bg-opacity-10" role="alert">
            <div class="d-flex align-items-center gap-2.5">
                <i class="bx bx-error-circle fs-4 text-danger"></i>
                <div class="fw-medium text-danger text-opacity-75">{{ session('error') }}</div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Metric Summary Cards -->
    <div class="row g-3 mb-4">
        <!-- Draft Forms Metric -->
        <div class="col-12 col-sm-6 col-lg-4">
            <div class="card border-0 shadow-sm rounded-3 h-100 stat-card">
                <div class="card-body p-3.5 d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-muted fs-7 fw-semibold text-uppercase tracking-wider mb-1">Draft Forms</div>
                        <h3 class="fw-bold text-dark mb-0">{{ method_exists($registrations, 'total') ? $registrations->total() : $registrations->count() }}</h3>
                        <small class="text-muted extra-small">Application started, in progress</small>
                    </div>
                    <div class="avatar avatar-md rounded-3 bg-label-warning d-flex align-items-center justify-content-center">
                        <i class="bx bx-edit text-warning fs-3"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Signed Up Users Metric -->
        <div class="col-12 col-sm-6 col-lg-4">
            <div class="card border-0 shadow-sm rounded-3 h-100 stat-card">
                <div class="card-body p-3.5 d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-muted fs-7 fw-semibold text-uppercase tracking-wider mb-1">Signed Up (Not Started)</div>
                        <h3 class="fw-bold text-dark mb-0">{{ method_exists($usersWithoutReg, 'total') ? $usersWithoutReg->total() : $usersWithoutReg->count() }}</h3>
                        <small class="text-muted extra-small">Portal accounts without form</small>
                    </div>
                    <div class="avatar avatar-md rounded-3 bg-label-primary d-flex align-items-center justify-content-center">
                        <i class="bx bx-user-plus text-primary fs-3"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Incomplete Summary Metric -->
        <div class="col-12 col-lg-4">
            <div class="card border-0 shadow-sm rounded-3 h-100 stat-card">
                <div class="card-body p-3.5 d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-muted fs-7 fw-semibold text-uppercase tracking-wider mb-1">Total Pending Actions</div>
                        <h3 class="fw-bold text-dark mb-0">
                            {{ (method_exists($registrations, 'total') ? $registrations->total() : $registrations->count()) + (method_exists($usersWithoutReg, 'total') ? $usersWithoutReg->total() : $usersWithoutReg->count()) }}
                        </h3>
                        <small class="text-muted extra-small">Eligible for individual reminder</small>
                    </div>
                    <div class="avatar avatar-md rounded-3 bg-label-info d-flex align-items-center justify-content-center">
                        <i class="bx bx-paper-plane text-info fs-3"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Navigation Tabs / Section Switcher -->
    <div class="nav-align-top mb-4">
        <ul class="nav nav-pills gap-2 p-1.5 bg-white rounded-3 shadow-sm border" role="tablist">
            <li class="nav-item" role="presentation">
                <button type="button" class="nav-link active rounded-2 px-3 py-2 fw-semibold d-flex align-items-center gap-2" role="tab" data-bs-toggle="tab" data-bs-target="#nav-drafts" aria-controls="nav-drafts" aria-selected="true">
                    <i class="bx bx-edit fs-5 text-warning"></i>
                    <span>Draft Forms</span>
                    <span class="badge bg-warning text-dark rounded-pill ms-1 px-2 py-0.5 extra-small fw-bold">
                        {{ method_exists($registrations, 'total') ? $registrations->total() : $registrations->count() }}
                    </span>
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button type="button" class="nav-link rounded-2 px-3 py-2 fw-semibold d-flex align-items-center gap-2" role="tab" data-bs-toggle="tab" data-bs-target="#nav-users" aria-controls="nav-users" aria-selected="false">
                    <i class="bx bx-user-plus fs-5 text-primary"></i>
                    <span>Signed Up (Not Started)</span>
                    <span class="badge bg-secondary text-white rounded-pill ms-1 px-2 py-0.5 extra-small fw-bold">
                        {{ method_exists($usersWithoutReg, 'total') ? $usersWithoutReg->total() : $usersWithoutReg->count() }}
                    </span>
                </button>
            </li>
        </ul>
    </div>

    <!-- Tab Content -->
    <div class="tab-content p-0 border-0 bg-transparent">
        <!-- ========================================================================= -->
        <!-- Tab 1: Draft / Incomplete Application Forms                               -->
        <!-- ========================================================================= -->
        <div class="tab-pane fade show active" id="nav-drafts" role="tabpanel">
            <div class="card border-0 shadow-sm rounded-3 overflow-hidden">
                <div class="card-header bg-white py-3.5 border-bottom d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <div>
                        <h6 class="mb-0 fw-bold text-dark d-flex align-items-center gap-2">
                            <i class="bx bx-edit text-warning fs-5"></i>
                            <span>Draft Application Forms</span>
                        </h6>
                        <small class="text-muted">Delegates who began registration but haven't submitted or paid</small>
                    </div>
                    <div class="search-box" style="max-width: 280px; width: 100%;">
                        <form method="GET" action="{{ route('indian-incomplete-delegates') }}">
                            @if(request('user_page'))
                                <input type="hidden" name="user_page" value="{{ request('user_page') }}">
                            @endif
                            @if(request('user_search'))
                                <input type="hidden" name="user_search" value="{{ request('user_search') }}">
                            @endif
                            <div class="input-group input-group-sm">
                                <span class="input-group-text bg-light border-end-0 text-muted"><i class="bx bx-search"></i></span>
                                <input type="text" name="draft_search" id="draftSearchInput" class="form-control bg-light border-start-0" placeholder="Search by name, email..." value="{{ request('draft_search') }}">
                                @if(request('draft_search'))
                                    <a href="{{ request()->fullUrlWithQuery(['draft_search' => null, 'draft_page' => null]) }}" class="btn btn-outline-secondary btn-sm" title="Clear search">
                                        <i class="bx bx-x"></i>
                                    </a>
                                @endif
                            </div>
                        </form>
                    </div>
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0 custom-admin-table" id="draftTable">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-3 py-3" style="width: 4%;">#</th>
                                    <th class="py-3" style="width: 30%;">Delegate Info</th>
                                    <th class="py-3" style="width: 22%;">Category & Type</th>
                                    <th class="py-3" style="width: 18%;">Progress & Status</th>
                                    <th class="py-3" style="width: 13%;">Last Activity</th>
                                    <th class="pe-3 py-3 text-end" style="width: 13%;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($registrations as $index => $reg)
                                    @php
                                        $cleanEmail = strtolower(trim($reg->user?->email ?? ''));
                                        $sentTodayTime = $todayReminders['reg_' . $reg->id] ?? ($todayReminders['email_' . $cleanEmail] ?? null);
                                        $delegateName = ($reg->user?->prefix ? $reg->user->prefix . ' ' : '') . ($reg->user?->full_name ?? 'Delegate');
                                        $step = (int)($reg->step_completed ?? 1);
                                        $stepPercent = min(100, max(25, $step * 25));
                                    @endphp
                                    <tr>
                                        <td class="ps-3 fw-bold text-muted">{{ (method_exists($registrations, 'firstItem') && $registrations->firstItem()) ? ($registrations->firstItem() + $index) : ($index + 1) }}</td>
                                        <td>
                                            <div class="d-flex align-items-center gap-2.5">
                                                <div class="avatar avatar-md flex-shrink-0" style="width: 42px; height: 42px;">
                                                    <img src="{{ $reg->photo_path ? asset('storage/' . $reg->photo_path) : asset('images/default-avatar.svg') }}"
                                                        alt="Avatar" class="rounded-circle w-100 h-100 border shadow-xs" style="object-fit: cover;"
                                                        onerror="this.onerror=null; this.src='{{ asset('images/default-avatar.svg') }}';" />
                                                </div>
                                                <div>
                                                    <h6 class="mb-0 fw-bold text-dark" style="font-size: 0.9rem;">
                                                        {{ $delegateName }}
                                                    </h6>
                                                    <div class="extra-small text-muted mb-0.5" style="font-size: 0.76rem;">
                                                        <i class="bx bx-envelope me-1 text-secondary"></i>{{ $reg->user?->email ?? 'N/A' }}
                                                    </div>
                                                    @if($reg->user?->mobile_number || $reg->user?->mobile_no)
                                                        <div class="extra-small text-muted" style="font-size: 0.76rem;">
                                                            <i class="bx bx-phone me-1 text-secondary"></i>{{ $reg->user?->mobile_number ?? $reg->user?->mobile_no }}
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-label-primary px-2.5 py-1 mb-1 fw-semibold extra-small">
                                                {{ $reg->delegate_type ?? 'Indian' }} Delegate
                                            </span>
                                            <div class="fw-semibold text-dark extra-small">
                                                <i class="bx bx-tag-alt text-muted me-1"></i>{{ $reg->delegateCategory?->category_name ?? 'Delegate' }}
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center justify-content-between mb-1" style="max-width: 140px;">
                                                <span class="badge bg-label-warning px-2 py-0.5 rounded-pill fw-bold extra-small">
                                                    Step {{ $step }} of 4
                                                </span>
                                                <span class="extra-small fw-bold text-muted">{{ $stepPercent }}%</span>
                                            </div>
                                            <div class="progress" style="height: 5px; max-width: 140px;">
                                                <div class="progress-bar bg-warning rounded-pill" role="progressbar" style="width: {{ $stepPercent }}%;" aria-valuenow="{{ $stepPercent }}" aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                            <div class="extra-small text-muted mt-1" style="font-size: 0.72rem;">
                                                Status: <strong class="text-dark">{{ $reg->status ?: 'Draft' }}</strong>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="fw-semibold text-dark extra-small">
                                                {{ $reg->updated_at ? $reg->updated_at->format('d M Y') : '-' }}
                                            </div>
                                            <small class="text-muted extra-small">
                                                {{ $reg->updated_at ? $reg->updated_at->format('h:i A') : '' }}
                                            </small>
                                        </td>
                                        <td class="pe-3 text-end">
                                            @if($sentTodayTime)
                                                <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-2.5 py-1.5 rounded-2 extra-small fw-semibold d-inline-flex align-items-center gap-1" title="Reminder email already sent today at {{ $sentTodayTime }} (Max 1 email per day limit)">
                                                    <i class="bx bx-check-double fs-6"></i> Sent ({{ $sentTodayTime }})
                                                </span>
                                            @else
                                                <button type="button" class="btn btn-sm btn-outline-warning text-dark px-2.5 py-1 rounded-2 extra-small fw-semibold shadow-xs"
                                                    onclick="openSingleReminderModal('reg', '{{ $reg->id }}', '{{ addslashes($delegateName) }}', '{{ addslashes($reg->user?->email ?? '') }}', 'Draft (Step {{ $step }} of 4)', '')"
                                                    title="Send Reminder Email">
                                                    <i class="bx bx-mail-send me-1"></i> Remind
                                                </button>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-5">
                                            <div class="empty-state py-4">
                                                <div class="avatar avatar-xl bg-label-success rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center">
                                                    <i class="bx bx-check-double fs-1 text-success"></i>
                                                </div>
                                                <h6 class="fw-bold text-dark mb-1">No Draft Registrations</h6>
                                                <p class="text-muted extra-small mb-0">All delegates have completed and submitted their registration forms.</p>
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
        </div>

        <!-- ========================================================================= -->
        <!-- Tab 2: Registered Users (Form Not Started)                                -->
        <!-- ========================================================================= -->
        <div class="tab-pane fade" id="nav-users" role="tabpanel">
            <div class="card border-0 shadow-sm rounded-3 overflow-hidden">
                <div class="card-header bg-white py-3.5 border-bottom d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <div>
                        <h6 class="mb-0 fw-bold text-dark d-flex align-items-center gap-2">
                            <i class="bx bx-user-plus text-primary fs-5"></i>
                            <span>Signed Up Users (Form Not Started)</span>
                        </h6>
                        <small class="text-muted">Users who created an account on the portal but haven't started filling their registration form</small>
                    </div>
                    <div class="search-box" style="max-width: 280px; width: 100%;">
                        <form method="GET" action="{{ route('indian-incomplete-delegates') }}">
                            @if(request('draft_page'))
                                <input type="hidden" name="draft_page" value="{{ request('draft_page') }}">
                            @endif
                            @if(request('draft_search'))
                                <input type="hidden" name="draft_search" value="{{ request('draft_search') }}">
                            @endif
                            <div class="input-group input-group-sm">
                                <span class="input-group-text bg-light border-end-0 text-muted"><i class="bx bx-search"></i></span>
                                <input type="text" name="user_search" id="userSearchInput" class="form-control bg-light border-start-0" placeholder="Search by name, email..." value="{{ request('user_search') }}">
                                @if(request('user_search'))
                                    <a href="{{ request()->fullUrlWithQuery(['user_search' => null, 'user_page' => null]) }}" class="btn btn-outline-secondary btn-sm" title="Clear search">
                                        <i class="bx bx-x"></i>
                                    </a>
                                @endif
                            </div>
                        </form>
                    </div>
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0 custom-admin-table" id="usersTable">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-3 py-3" style="width: 4%;">#</th>
                                    <th class="py-3" style="width: 32%;">User Details</th>
                                    <th class="py-3" style="width: 20%;">Delegate Type</th>
                                    <th class="py-3" style="width: 16%;">Email Status</th>
                                    <th class="py-3" style="width: 15%;">Sign Up Date</th>
                                    <th class="pe-3 py-3 text-end" style="width: 13%;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($usersWithoutReg as $index => $u)
                                    @php
                                        $cleanUserEmail = strtolower(trim($u->email ?? ''));
                                        $userSentToday = $todayReminders['user_' . $u->id] ?? ($todayReminders['email_' . $cleanUserEmail] ?? null);
                                        $userNameFormatted = ($u->prefix ? $u->prefix . ' ' : '') . ($u->full_name ?? 'User');
                                    @endphp
                                    <tr>
                                        <td class="ps-3 fw-bold text-muted">{{ (method_exists($usersWithoutReg, 'firstItem') && $usersWithoutReg->firstItem()) ? ($usersWithoutReg->firstItem() + $index) : ($index + 1) }}</td>
                                        <td>
                                            <div class="d-flex align-items-center gap-2.5">
                                                <div class="avatar avatar-md rounded-circle bg-label-primary text-primary fw-bold d-flex align-items-center justify-content-center border shadow-xs" style="width: 40px; height: 40px; font-size: 0.95rem;">
                                                    {{ strtoupper(substr($u->full_name ?? 'U', 0, 1)) }}
                                                </div>
                                                <div>
                                                    <h6 class="mb-0 fw-bold text-dark" style="font-size: 0.9rem;">
                                                        {{ $userNameFormatted }}
                                                    </h6>
                                                    <div class="extra-small text-muted" style="font-size: 0.76rem;">
                                                        <i class="bx bx-envelope me-1 text-secondary"></i>{{ $u->email }}
                                                    </div>
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
                                                <span class="badge bg-label-success px-2.5 py-1 rounded-pill extra-small fw-semibold">
                                                    <i class="bx bx-check me-0.5"></i> Verified
                                                </span>
                                            @else
                                                <span class="badge bg-label-secondary px-2.5 py-1 rounded-pill extra-small fw-semibold">
                                                    Unverified
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="fw-semibold text-dark extra-small">
                                                {{ $u->created_at ? $u->created_at->format('d M Y') : '-' }}
                                            </div>
                                            <small class="text-muted extra-small">
                                                {{ $u->created_at ? $u->created_at->format('h:i A') : '' }}
                                            </small>
                                        </td>
                                        <td class="pe-3 text-end">
                                            @if($userSentToday)
                                                <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-2.5 py-1.5 rounded-2 extra-small fw-semibold d-inline-flex align-items-center gap-1" title="Reminder email already sent today at {{ $userSentToday }} (Max 1 email per day limit)">
                                                    <i class="bx bx-check-double fs-6"></i> Sent ({{ $userSentToday }})
                                                </span>
                                            @else
                                                <button type="button" class="btn btn-sm btn-outline-primary px-2.5 py-1 rounded-2 extra-small fw-semibold shadow-xs"
                                                    onclick="openSingleReminderModal('user', '{{ $u->id }}', '{{ addslashes($userNameFormatted) }}', '{{ addslashes($u->email ?? '') }}', 'Form Not Started', '')"
                                                    title="Send Reminder Email">
                                                    <i class="bx bx-mail-send me-1"></i> Remind
                                                </button>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-5">
                                            <div class="empty-state py-4">
                                                <div class="avatar avatar-xl bg-label-success rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center">
                                                    <i class="bx bx-user-check fs-1 text-success"></i>
                                                </div>
                                                <h6 class="fw-bold text-dark mb-1">All Signed-Up Users Have Started</h6>
                                                <p class="text-muted extra-small mb-0">Every registered user on the portal has initiated their registration form.</p>
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
    </div>
</div>

<!-- ========================================================================= -->
<!-- Single Reminder Modal (Individual Only)                                   -->
<!-- ========================================================================= -->
<div class="modal fade" id="singleReminderModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <form action="{{ route('admin.send-incomplete-registration-reminder') }}" method="POST" id="singleReminderForm" onsubmit="handleFormSubmit(this)">
                @csrf
                <input type="hidden" name="target" id="single_target" value="single_reg">
                <input type="hidden" name="registration_id" id="single_reg_id">
                <input type="hidden" name="user_id" id="single_user_id">

                <div class="modal-header bg-light py-3 px-4 border-bottom">
                    <h5 class="modal-title fw-bold text-dark mb-0 d-flex align-items-center gap-2">
                        <i class="bx bx-envelope text-warning fs-4"></i>
                        <span>Send Registration Reminder</span>
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <!-- Recipient Info Card -->
                    <div class="bg-light p-3.5 rounded-3 border mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-1.5">
                            <span class="text-muted extra-small fw-semibold text-uppercase">Recipient:</span>
                            <span class="badge bg-label-warning extra-small" id="single_status_badge">Draft</span>
                        </div>
                        <h6 class="mb-0 fw-bold text-dark" id="single_recipient_name">Delegate Name</h6>
                        <small class="text-muted" id="single_recipient_email">email@example.com</small>
                    </div>

                    <!-- Custom Message Field -->
                    <div class="mb-3">
                        <label for="single_custom_message" class="form-label fw-bold text-dark fs-7 mb-1.5">
                            Custom Note / Message <small class="text-muted fw-normal">(Optional)</small>
                        </label>
                        <textarea class="form-control rounded-3" name="custom_message" id="single_custom_message" rows="3" placeholder="Add any specific instructions or note for this delegate..."></textarea>
                    </div>

                    <div class="alert alert-light border p-2.5 mb-0 rounded-2">
                        <div class="d-flex gap-2 align-items-center">
                            <i class="bx bx-info-circle text-muted fs-5 flex-shrink-0"></i>
                            <div class="extra-small text-muted">
                                <strong>Strict Policy:</strong> Only one reminder email is permitted per user per day.
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light py-2.5 px-4 border-top">
                    <button type="button" class="btn btn-secondary btn-sm px-3 rounded-2" data-bs-dismiss="modal" id="singleCancelBtn">Cancel</button>
                    <button type="submit" class="btn btn-warning text-dark btn-sm fw-bold px-3.5 py-1.5 rounded-2 d-flex align-items-center gap-1.5 shadow-sm" id="singleSubmitBtn">
                        <i class="bx bx-send"></i>
                        <span id="singleSubmitText">Send Reminder</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.stat-card {
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}
.stat-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.08) !important;
}
.custom-admin-table thead th {
    font-size: 0.75rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    font-weight: 700;
    color: #64748b;
    border-bottom: 1px solid #e2e8f0;
}
.custom-admin-table tbody tr {
    transition: background-color 0.15s ease;
}
.custom-admin-table tbody tr:hover {
    background-color: #f8fafc !important;
}
.nav-pills .nav-link {
    color: #475569;
    transition: all 0.2s ease;
}
.nav-pills .nav-link:hover {
    background-color: #f1f5f9;
    color: #0f172a;
}
.nav-pills .nav-link.active {
    background-color: #0f172a;
    color: #ffffff;
}
.nav-pills .nav-link.active .badge.bg-warning {
    background-color: #fbbf24 !important;
    color: #000000 !important;
}
.pagination {
    margin-bottom: 0;
}
.pagination .page-item .page-link {
    border-radius: 6px;
    margin: 0 2px;
    font-size: 0.8125rem;
    padding: 0.35rem 0.65rem;
}
.cursor-pointer {
    cursor: pointer;
}
</style>
@endpush

@push('scripts')
<script>
function openSingleReminderModal(type, id, name, email, status, sentTime) {
    const modalEl = document.getElementById('singleReminderModal');
    if (!modalEl) return;

    document.getElementById('single_reg_id').value = (type === 'reg') ? id : '';
    document.getElementById('single_user_id').value = (type === 'user') ? id : '';
    document.getElementById('single_target').value = (type === 'reg') ? 'single_reg' : 'single_user';
    document.getElementById('single_recipient_name').innerText = name || 'Delegate';
    document.getElementById('single_recipient_email').innerText = email || '';
    document.getElementById('single_status_badge').innerText = status || 'Incomplete';

    const modal = new bootstrap.Modal(modalEl);
    modal.show();
}

function handleFormSubmit(form) {
    const submitBtn = form.querySelector('button[type="submit"]');
    if (submitBtn && !submitBtn.disabled) {
        submitBtn.disabled = true;
        submitBtn.classList.add('disabled');
        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1.5" role="status" aria-hidden="true"></span> Sending email, please wait...';
        
        form.querySelectorAll('button').forEach(btn => {
            btn.disabled = true;
        });

        const closeBtn = form.closest('.modal-content')?.querySelector('.btn-close');
        if (closeBtn) closeBtn.style.pointerEvents = 'none';
    }
}
</script>
@endpush
