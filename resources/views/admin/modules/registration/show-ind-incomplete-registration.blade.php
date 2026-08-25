@extends('admin.layouts.main')

@section('admin-content')
<div class="container-xxl flex-grow-1 mt-3.5 mb-4">
    <!-- Page Header -->
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 py-2 mb-3">
        <div>
            <h5 class="mb-0 fw-bold text-dark">
                <i class="bx bx-time-five me-2 text-warning fs-4"></i>Incomplete Registrations
            </h5>
            <small class="text-muted">Manage draft applications and signed-up users with 1 email per user per day strict limit</small>
        </div>
        <div class="d-flex align-items-center flex-wrap gap-2">
            <span class="badge bg-warning text-dark rounded-pill px-3 py-2 fs-7 fw-bold shadow-xs">
                Draft Forms: {{ method_exists($registrations, 'total') ? $registrations->total() : $registrations->count() }}
            </span>
            <span class="badge bg-secondary text-white rounded-pill px-3 py-2 fs-7 fw-bold shadow-xs">
                Signed Up (Not Started): {{ method_exists($usersWithoutReg, 'total') ? $usersWithoutReg->total() : $usersWithoutReg->count() }}
            </span>
            <button type="button" class="btn btn-warning text-dark fw-bold rounded-pill px-3 py-2 fs-7 shadow-xs d-flex align-items-center gap-1.5" onclick="openBulkReminderModal('all')">
                <i class="bx bx-mail-send fs-5"></i>
                <span>Send Reminder to Everyone</span>
            </button>
        </div>
    </div>

    <!-- Alert Messages -->
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show p-3 mb-3 rounded-3 shadow-xs" role="alert">
            <div class="d-flex align-items-center gap-2">
                <i class="bx bx-check-circle fs-4 text-success"></i>
                <div>{{ session('success') }}</div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show p-3 mb-3 rounded-3 shadow-xs" role="alert">
            <div class="d-flex align-items-center gap-2">
                <i class="bx bx-error-circle fs-4 text-danger"></i>
                <div>{{ session('error') }}</div>
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
            <div class="d-flex align-items-center flex-wrap gap-2">
                <button type="button" class="btn btn-sm btn-outline-warning text-dark fw-semibold d-flex align-items-center gap-1" onclick="openBulkReminderModal('drafts')">
                    <i class="bx bx-bell"></i>
                    <span>Remind All Drafts</span>
                </button>
                <div class="search-box" style="max-width: 280px; width: 100%;">
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
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="draftTable">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-3 py-3" style="width: 4%;">#</th>
                            <th class="py-3" style="width: 28%;">Delegate Info</th>
                            <th class="py-3" style="width: 22%;">Category & Type</th>
                            <th class="py-3" style="width: 18%;">Step Completed</th>
                            <th class="py-3" style="width: 14%;">Last Activity</th>
                            <th class="pe-3 py-3 text-end" style="width: 14%;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($registrations as $index => $reg)
                            @php
                                $cleanEmail = strtolower(trim($reg->user?->email ?? ''));
                                $sentTodayTime = $todayReminders['reg_' . $reg->id] ?? ($todayReminders['email_' . $cleanEmail] ?? null);
                                $delegateName = ($reg->user?->prefix ? $reg->user->prefix . ' ' : '') . ($reg->user?->full_name ?? 'Delegate');
                            @endphp
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
                                                {{ $delegateName }}
                                            </h6>
                                            <div class="extra-small text-muted mb-0.5" style="font-size: 0.76rem;">
                                                <i class="bx bx-envelope me-0.5"></i>{{ $reg->user?->email ?? 'N/A' }}
                                            </div>
                                            <div class="extra-small text-muted" style="font-size: 0.76rem;">
                                                <i class="bx bx-phone me-0.5"></i>{{ $reg->user?->mobile_number ?? ($reg->user?->mobile_no ?? 'N/A') }}
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
                                <td>
                                    <small class="text-muted extra-small">
                                        {{ $reg->updated_at ? $reg->updated_at->format('d M Y, h:i A') : '-' }}
                                    </small>
                                </td>
                                <td class="pe-3 text-end">
                                    @if($sentTodayTime)
                                        <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-2.5 py-1.5 rounded-2 extra-small fw-semibold d-inline-flex align-items-center gap-1" title="Reminder email already sent today at {{ $sentTodayTime }} (Max 1 email per day limit)">
                                            <i class="bx bx-check-double fs-6"></i> Sent ({{ $sentTodayTime }})
                                        </span>
                                    @else
                                        <button type="button" class="btn btn-xs btn-outline-warning text-dark px-2.5 py-1 rounded-2 extra-small fw-semibold"
                                            onclick="openSingleReminderModal('reg', '{{ $reg->id }}', '{{ addslashes($delegateName) }}', '{{ addslashes($reg->user?->email ?? '') }}', 'Draft (Step {{ $reg->step_completed ?? 1 }} of 4)', '')"
                                            title="Send Reminder Email">
                                            <i class="bx bx-mail-send me-0.5"></i> Remind
                                        </button>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5">
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
            <div class="d-flex align-items-center flex-wrap gap-2">
                <button type="button" class="btn btn-sm btn-outline-secondary fw-semibold d-flex align-items-center gap-1" onclick="openBulkReminderModal('users')">
                    <i class="bx bx-bell"></i>
                    <span>Remind All Signed-Up</span>
                </button>
                <div class="search-box" style="max-width: 280px; width: 100%;">
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
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="usersTable">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-3 py-3" style="width: 4%;">#</th>
                            <th class="py-3" style="width: 32%;">User Details</th>
                            <th class="py-3" style="width: 20%;">Delegate Type</th>
                            <th class="py-3" style="width: 16%;">Email Verified</th>
                            <th class="py-3" style="width: 14%;">Sign Up Date</th>
                            <th class="pe-3 py-3 text-end" style="width: 14%;">Action</th>
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
                                        <div class="avatar avatar-sm rounded-circle bg-label-primary text-primary fw-bold d-flex align-items-center justify-content-center" style="width: 36px; height: 36px; font-size: 0.85rem;">
                                            {{ strtoupper(substr($u->full_name ?? 'U', 0, 1)) }}
                                        </div>
                                        <div>
                                            <h6 class="mb-0 fw-bold text-dark" style="font-size: 0.88rem;">
                                                {{ $userNameFormatted }}
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
                                <td>
                                    <small class="text-muted extra-small">
                                        {{ $u->created_at ? $u->created_at->format('d M Y') : '-' }}
                                    </small>
                                </td>
                                <td class="pe-3 text-end">
                                    @if($userSentToday)
                                        <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-2.5 py-1.5 rounded-2 extra-small fw-semibold d-inline-flex align-items-center gap-1" title="Reminder email already sent today at {{ $userSentToday }} (Max 1 email per day limit)">
                                            <i class="bx bx-check-double fs-6"></i> Sent ({{ $userSentToday }})
                                        </span>
                                    @else
                                        <button type="button" class="btn btn-xs btn-outline-secondary px-2.5 py-1 rounded-2 extra-small fw-semibold"
                                            onclick="openSingleReminderModal('user', '{{ $u->id }}', '{{ addslashes($userNameFormatted) }}', '{{ addslashes($u->email ?? '') }}', 'Form Not Started', '')"
                                            title="Send Reminder Email">
                                            <i class="bx bx-mail-send me-0.5"></i> Remind
                                        </button>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5">
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

<!-- ========================================================================= -->
<!-- Modal 1: Bulk Reminder to Everyone / Target Audience                      -->
<!-- ========================================================================= -->
<div class="modal fade" id="bulkReminderModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-3">
            <form action="{{ route('admin.send-incomplete-registration-reminder') }}" method="POST" id="bulkReminderForm" onsubmit="handleFormSubmit(this)">
                @csrf
                <div class="modal-header bg-warning py-3">
                    <h5 class="modal-title fw-bold text-dark mb-0 d-flex align-items-center gap-2">
                        <i class="bx bx-mail-send fs-4"></i>
                        <span>Send Registration Reminders</span>
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="alert alert-info border-0 bg-info bg-opacity-10 p-3 mb-3 rounded-3 text-dark d-flex gap-2.5 align-items-start">
                        <i class="bx bx-shield-quarter text-info fs-4 flex-shrink-0 mt-0.5"></i>
                        <div class="fs-7">
                            <strong>Strict Policy (1 Email per User per Day):</strong> Each user can receive only <strong>one reminder email per day</strong>. Recipients who have already received a reminder today will be <strong>automatically skipped</strong> to prevent duplicate emails.
                        </div>
                    </div>

                    <!-- Target Group Selection -->
                    <div class="mb-3">
                        <label class="form-label fw-bold text-dark fs-7">Select Target Audience <span class="text-danger">*</span></label>
                        <div class="d-flex flex-column gap-2">
                            <div class="form-check p-2.5 border rounded-2 bg-light">
                                <input class="form-check-input ms-0 me-2" type="radio" name="target" id="target_all" value="all" checked>
                                <label class="form-check-label fw-semibold text-dark cursor-pointer w-100" for="target_all">
                                    <span>All Incomplete Delegates (Drafts + Signed Up)</span>
                                    <div class="extra-small text-muted">Total: {{ (method_exists($registrations, 'total') ? $registrations->total() : $registrations->count()) + (method_exists($usersWithoutReg, 'total') ? $usersWithoutReg->total() : $usersWithoutReg->count()) }} recipients</div>
                                </label>
                            </div>
                            <div class="form-check p-2.5 border rounded-2 bg-light">
                                <input class="form-check-input ms-0 me-2" type="radio" name="target" id="target_drafts" value="drafts">
                                <label class="form-check-label fw-semibold text-dark cursor-pointer w-100" for="target_drafts">
                                    <span>Only Draft / Incomplete Application Forms</span>
                                    <div class="extra-small text-muted">Total: {{ method_exists($registrations, 'total') ? $registrations->total() : $registrations->count() }} recipients</div>
                                </label>
                            </div>
                            <div class="form-check p-2.5 border rounded-2 bg-light">
                                <input class="form-check-input ms-0 me-2" type="radio" name="target" id="target_users" value="users">
                                <label class="form-check-label fw-semibold text-dark cursor-pointer w-100" for="target_users">
                                    <span>Only Signed Up Users (Form Not Started)</span>
                                    <div class="extra-small text-muted">Total: {{ method_exists($usersWithoutReg, 'total') ? $usersWithoutReg->total() : $usersWithoutReg->count() }} recipients</div>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Custom Message Field -->
                    <div class="mb-0">
                        <label for="bulk_custom_message" class="form-label fw-bold text-dark fs-7">
                            Custom Note / Secretariat Message <small class="text-muted fw-normal">(Optional)</small>
                        </label>
                        <textarea class="form-control" name="custom_message" id="bulk_custom_message" rows="3" placeholder="e.g. Early bird registration is closing soon. Please complete your submission today to lock in discounted fee rates."></textarea>
                    </div>
                </div>
                <div class="modal-footer bg-light py-2 px-4">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal" id="bulkCancelBtn">Cancel</button>
                    <button type="submit" class="btn btn-warning text-dark btn-sm fw-bold px-3 d-flex align-items-center gap-1.5" id="bulkSubmitBtn">
                        <i class="bx bx-send"></i>
                        <span id="bulkSubmitText">Send Reminder Emails</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ========================================================================= -->
<!-- Modal 2: Single Reminder Modal                                            -->
<!-- ========================================================================= -->
<div class="modal fade" id="singleReminderModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-3">
            <form action="{{ route('admin.send-incomplete-registration-reminder') }}" method="POST" id="singleReminderForm" onsubmit="handleFormSubmit(this)">
                @csrf
                <input type="hidden" name="target" id="single_target" value="single_reg">
                <input type="hidden" name="registration_id" id="single_reg_id">
                <input type="hidden" name="user_id" id="single_user_id">

                <div class="modal-header bg-light py-3 border-bottom">
                    <h5 class="modal-title fw-bold text-dark mb-0 d-flex align-items-center gap-2">
                        <i class="bx bx-envelope text-warning fs-4"></i>
                        <span>Send Registration Reminder</span>
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <!-- Recipient Info Card -->
                    <div class="bg-light p-3 rounded-3 border mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span class="text-muted extra-small">Recipient:</span>
                            <span class="badge bg-label-warning extra-small" id="single_status_badge">Draft</span>
                        </div>
                        <h6 class="mb-0 fw-bold text-dark" id="single_recipient_name">Delegate Name</h6>
                        <small class="text-muted" id="single_recipient_email">email@example.com</small>
                    </div>

                    <!-- Custom Message Field -->
                    <div class="mb-3">
                        <label for="single_custom_message" class="form-label fw-bold text-dark fs-7">
                            Custom Note / Message <small class="text-muted fw-normal">(Optional)</small>
                        </label>
                        <textarea class="form-control" name="custom_message" id="single_custom_message" rows="3" placeholder="Add any specific instructions or note for this delegate..."></textarea>
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
                <div class="modal-footer bg-light py-2 px-4">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal" id="singleCancelBtn">Cancel</button>
                    <button type="submit" class="btn btn-warning text-dark btn-sm fw-bold px-3 d-flex align-items-center gap-1.5" id="singleSubmitBtn">
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
function openBulkReminderModal(targetType) {
    const modalEl = document.getElementById('bulkReminderModal');
    if (!modalEl) return;

    if (targetType === 'drafts') {
        document.getElementById('target_drafts').checked = true;
    } else if (targetType === 'users') {
        document.getElementById('target_users').checked = true;
    } else {
        document.getElementById('target_all').checked = true;
    }

    const modal = new bootstrap.Modal(modalEl);
    modal.show();
}

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
        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1.5" role="status" aria-hidden="true"></span> Sending emails, please wait...';
        
        // Disable cancel & close buttons to avoid closing or double-submission during batch dispatch
        form.querySelectorAll('button').forEach(btn => {
            btn.disabled = true;
        });

        // Hide close button in header
        const closeBtn = form.closest('.modal-content')?.querySelector('.btn-close');
        if (closeBtn) closeBtn.style.pointerEvents = 'none';
    }
}
</script>
@endpush
