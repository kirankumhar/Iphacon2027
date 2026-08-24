@extends('admin.layouts.main')

@section('admin-content')
    <div class="container-xxl flex-grow-1 mt-3.5 mb-4">
        <!-- Top Title Header -->
        <div class="d-flex align-items-center justify-content-between py-2 mb-3">
            <div>
                <span class="text-muted extra-small d-block">IPHACON 2027 Admin Portal</span>
                <h5 class="mb-0 fw-bold text-dark d-flex align-items-center gap-2">
                    <i class="bx bx-paper-plane text-primary fs-4"></i>Submitted Delegates (Awaiting Approval)
                </h5>
            </div>
            <span class="badge bg-light text-dark border px-3 py-1.5 fs-7 fw-medium rounded-pill">
                <i class="bx bx-time-five text-primary me-1"></i>{{ $registrations->count() }} Pending Review
            </span>
        </div>

        <!-- Alert Messages -->
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show border-0 shadow-xs rounded-3 mb-3 d-flex align-items-center"
                role="alert" style="background-color: #DCFCE7; color: #065F46;">
                <i class="bx bx-check-circle fs-4 me-2 text-success"></i>
                <div class="fw-bold">{{ session('success') }}</div>
                <button type="button" class="btn-close ms-auto py-2 px-2" data-bs-dismiss="alert"
                    aria-label="Close"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show border-0 shadow-xs rounded-3 mb-3 d-flex align-items-center"
                role="alert" style="background-color: #FEE2E2; color: #991B1B;">
                <i class="bx bx-error-circle fs-4 me-2 text-danger"></i>
                <div class="fw-bold">{{ session('error') }}</div>
                <button type="button" class="btn-close ms-auto py-2 px-2" data-bs-dismiss="alert"
                    aria-label="Close"></button>
            </div>
        @endif

        <!-- Submitted Delegates List Table Card -->
        <div class="card border-0 shadow-sm rounded-3 overflow-hidden">
            <div
                class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between flex-wrap gap-2">
                <h6 class="mb-0 fw-bold text-dark d-flex align-items-center gap-2">
                    <i class="bx bx-list-check text-primary fs-5"></i>Pending Approval List
                </h6>
                <div class="search-box" style="max-width: 280px; width: 100%;">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-light border-end-0"><i class="bx bx-search text-muted"></i></span>
                        <input type="text" id="submittedSearchInput" class="form-control bg-light border-start-0"
                            placeholder="Search name, ack id, email...">
                    </div>
                </div>
            </div>

            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" id="submittedDelegatesTable">
                        <thead style="background-color: #F8FAFC; border-bottom: 1px solid #E2E8F0;">
                            <tr>
                                <th class="ps-3 py-3 text-secondary text-uppercase fw-bold extra-small"
                                    style="width: 35px;">#</th>
                                <th class="py-3 text-secondary text-uppercase fw-bold extra-small" style="width: 28%;"><i
                                        class="bx bx-user text-primary me-1"></i> Delegate Info</th>
                                <th class="py-3 text-secondary text-uppercase fw-bold extra-small" style="width: 22%;"><i
                                        class="bx bx-category text-info me-1"></i> Category &amp; Type</th>
                                <th class="py-3 text-secondary text-uppercase fw-bold extra-small" style="width: 20%;"><i
                                        class="bx bx-credit-card text-success me-1"></i> Payment Info</th>
                                <th class="py-3 text-secondary text-uppercase fw-bold extra-small" style="width: 10%;"><i
                                        class="bx bx-time me-1 text-warning"></i> Status</th>
                                <th class="pe-3 py-3 text-secondary text-uppercase fw-bold text-end extra-small"
                                    style="width: 17%;"><i class="bx bx-slider-alt text-warning me-1"></i> Actions</th>
                            </tr>
                        </thead>
                        <tbody class="table-border-bottom-0">
                            @forelse ($registrations as $index => $reg)
                                <tr>
                                    <td class="ps-3 fw-semibold text-muted small">{{ $index + 1 }}</td>
                                    <td class="py-2.5">
                                        <div class="d-flex align-items-center gap-2.5">
                                            <div class="avatar avatar-md flex-shrink-0" style="width: 38px; height: 38px;">
                                                @if ($reg->photo_path)
                                                    <img src="{{ asset('storage/' . $reg->photo_path) }}" alt="Avatar"
                                                        class="rounded-circle border w-100 h-100 shadow-xs"
                                                        style="object-fit: cover;"
                                                        onerror="this.onerror=null; this.src='{{ asset('images/default-avatar.svg') }}';" />
                                                @else
                                                    <div
                                                        class="avatar-initial rounded-circle bg-primary bg-opacity-10 text-primary fw-bold d-flex align-items-center justify-content-center w-100 h-100 fs-6 border border-primary border-opacity-25">
                                                        {{ strtoupper(substr($reg->user?->full_name ?? 'U', 0, 1)) }}
                                                    </div>
                                                @endif
                                            </div>
                                            <div>
                                                <a href="{{ route('show-registration-details', $reg->id) }}">
                                                    <div class="fw-bold text-dark"
                                                        style="font-size: 0.86rem; line-height: 1.2;">
                                                        {{ $reg->user?->prefix }} {{ $reg->user?->full_name ?? 'N/A' }}
                                                    </div>
                                                </a>
                                                <div class="text-muted extra-small text-truncate mb-1"
                                                    style="max-width: 200px; font-size: 0.75rem;">
                                                    {{ $reg->user?->email }}
                                                </div>
                                                <div class="d-flex align-items-center gap-1.5">
                                                    <span class="badge font-monospace extra-small px-2 py-0.5 rounded-2"
                                                        style="background-color: #F1F5F9; color: #334155; border: 1px solid #CBD5E1; font-size: 0.70rem;">
                                                        ACK: {{ $reg->acknowledgement_id ?? 'N/A' }}
                                                    </span>
                                                    <a href="{{ route('show-registration-details', $reg->acknowledgement_id ?? $reg->id) }}"
                                                        class="btn btn-xs btn-outline-primary rounded-pill px-2 py-0.5 extra-small fw-bold"
                                                        style="font-size: 0.70rem;">
                                                        <i class="bx bx-show me-0.5"></i> Details
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge rounded-pill px-2.5 py-1 extra-small fw-semibold mb-1"
                                            style="background-color: #EFF6FF; color: #1D4ED8; border: 1px solid #BFDBFE;">
                                            {{ $reg->delegate_type ?? 'Indian' }} Delegate
                                        </span>
                                        <div class="extra-small text-dark fw-medium mt-0.5" style="font-size: 0.76rem;">
                                            {{ $reg->delegateCategory?->category_name ?? 'N/A' }}
                                        </div>
                                    </td>
                                    <td>
                                        <div class="fw-bold fs-6 mb-0.5" style="color: #059669;">
                                            {{ $reg->delegate_type == 'International' ? '$' : '₹' }}{{ number_format($reg->total_amount, 2) }}
                                        </div>
                                        <div class="d-flex align-items-center gap-1.5 flex-wrap">
                                            @if ($reg->latestPayment?->transaction_id)
                                                <span class="badge font-monospace extra-small px-2 py-0.5"
                                                    style="background-color: #F8FAFC; color: #475569; border: 1px solid #E2E8F0; font-size: 0.70rem;">
                                                    <i class="bx bx-receipt text-primary me-0.5"></i>Txn ID:
                                                    {{ $reg->latestPayment->transaction_id }}
                                                </span>
                                            @endif
                                            @if ($reg->latestPayment?->payment_receipt_path)
                                                <a href="{{ asset('storage/' . $reg->latestPayment->payment_receipt_path) }}"
                                                    target="_blank"
                                                    class="extra-small text-primary text-decoration-none fw-semibold"
                                                    style="font-size: 0.72rem;">
                                                    <i class="bx bx-file me-0.5"></i>View Receipt
                                                </a>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge rounded-pill fw-semibold extra-small px-2.5 py-1"
                                            style="background-color: #FEF3C7; color: #92400E; border: 1px solid #FDE68A; font-size: 0.72rem;">
                                            <i class="bx bx-time-five me-1"></i>SUBMITTED
                                        </span>
                                    </td>
                                    <td class="pe-3 text-end">
                                        <div class="d-flex align-items-center justify-content-end gap-1"
                                            style="white-space: nowrap;">
                                            <!-- Send / Resend Submission Email Button -->
                                            {{-- <button type="button" class="btn btn-xs btn-outline-primary fw-bold px-2 py-1 rounded-2 shadow-xs d-inline-flex align-items-center gap-1" style="font-size: 0.74rem;" data-bs-toggle="modal" data-bs-target="#resendEmailModal{{ $reg->id }}" title="Send/Resend Submission Email">
                                            <i class="bx bx-envelope" style="font-size: 0.85rem;"></i> Send Email
                                        </button> --}}

                                            @if (in_array($reg->status, ['Payment Submitted', 'Submitted', 'Pending Payment']) || !empty($reg->latestPayment))
                                                <!-- Direct Approve Form -->
                                                <form method="POST" action="{{ route('student-approved-regis') }}"
                                                    class="d-inline m-0">
                                                    @csrf
                                                    <input type="hidden" name="registration_id"
                                                        value="{{ $reg->id }}">
                                                    <input type="hidden" name="acknowledgement_id"
                                                        value="{{ $reg->acknowledgement_id }}">
                                                    <button type="submit"
                                                        class="btn btn-xs btn-success fw-bold px-2.5 py-1 rounded-2 shadow-xs d-inline-flex align-items-center gap-1"
                                                        style="font-size: 0.74rem;"
                                                        onclick="return confirm('Approve registration for {{ $reg->user?->full_name }}?')"
                                                        title="Approve Registration">
                                                        <i class="bx bx-check-circle" style="font-size: 0.85rem;"></i>
                                                        Approve
                                                    </button>
                                                </form>
                                            @endif

                                            <!-- Revert Button Trigger Modal -->
                                            {{-- <button type="button" class="btn btn-xs btn-warning text-dark fw-bold px-2.5 py-1 rounded-2 shadow-xs d-inline-flex align-items-center gap-1" style="font-size: 0.74rem;" data-bs-toggle="modal" data-bs-target="#revertModal{{ $reg->id }}" title="Revert Back">
                                            <i class="bx bx-undo" style="font-size: 0.85rem;"></i> Revert
                                        </button> --}}

                                            <!-- Reject Button Trigger Modal -->
                                            <button type="button"
                                                class="btn btn-xs btn-outline-danger fw-bold px-2.5 py-1 rounded-2 d-inline-flex align-items-center gap-1"
                                                style="font-size: 0.74rem;" data-bs-toggle="modal"
                                                data-bs-target="#rejectModal{{ $reg->id }}"
                                                title="Reject Registration">
                                                <i class="bx bx-x-circle" style="font-size: 0.85rem;"></i> Reject
                                            </button>
                                        </div>

                                        <!-- Send / Resend Email Modal -->
                                        <div class="modal fade text-start" id="resendEmailModal{{ $reg->id }}"
                                            tabindex="-1" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content border-0 shadow-lg rounded-3">
                                                    <form method="POST"
                                                        action="{{ route('admin.resend-submission-email') }}">
                                                        @csrf
                                                        <input type="hidden" name="registration_id"
                                                            value="{{ $reg->id }}">
                                                        <input type="hidden" name="acknowledgement_id"
                                                            value="{{ $reg->acknowledgement_id }}">
                                                        <input type="hidden" name="email_type" value="submission">
                                                        <div
                                                            class="modal-header bg-primary bg-opacity-10 py-3 border-bottom-0">
                                                            <div class="d-flex align-items-center gap-2">
                                                                <div
                                                                    class="avatar avatar-sm bg-primary text-white rounded-circle d-flex align-items-center justify-content-center">
                                                                    <i class="bx bx-envelope fs-5"></i>
                                                                </div>
                                                                <h5 class="modal-title fw-bold text-dark mb-0">Send
                                                                    Submission Email</h5>
                                                            </div>
                                                            <button type="button" class="btn-close"
                                                                data-bs-dismiss="modal"></button>
                                                        </div>
                                                        <div class="modal-body py-3">
                                                            <div
                                                                class="alert alert-primary bg-light border-primary border-opacity-25 extra-small mb-3 d-flex align-items-start gap-2">
                                                                <i
                                                                    class="bx bx-info-circle fs-5 flex-shrink-0 mt-0.5 text-primary"></i>
                                                                <div>
                                                                    This will send the <strong>Registration Submission
                                                                        Confirmation Email</strong> (which is sent after
                                                                    final submit) with Ack ID
                                                                    <strong>({{ $reg->acknowledgement_id }})</strong>,
                                                                    delegate info, and payment summary.
                                                                </div>
                                                            </div>

                                                            <div class="mb-3 p-2.5 rounded-2 bg-light border">
                                                                <div class="extra-small text-muted mb-0.5">Delegate:</div>
                                                                <div class="fw-bold text-dark fs-7">
                                                                    {{ $reg->user?->prefix }} {{ $reg->user?->full_name }}
                                                                </div>
                                                                <div class="extra-small text-secondary mt-0.5">
                                                                    Ack ID: <span
                                                                        class="font-monospace fw-semibold">{{ $reg->acknowledgement_id ?? 'N/A' }}</span>
                                                                    |
                                                                    Category:
                                                                    {{ $reg->delegateCategory?->category_name ?? 'N/A' }}
                                                                </div>
                                                            </div>

                                                            <div class="mb-2">
                                                                <label
                                                                    class="form-label fw-semibold extra-small text-dark">Recipient
                                                                    Email Address <span
                                                                        class="text-danger">*</span></label>
                                                                <div class="input-group input-group-sm">
                                                                    <span class="input-group-text bg-light"><i
                                                                            class="bx bx-envelope text-muted"></i></span>
                                                                    <input type="email" name="email"
                                                                        class="form-control form-control-sm"
                                                                        value="{{ $reg->user?->email }}" required
                                                                        placeholder="Enter delegate email address">
                                                                </div>
                                                                <small class="text-muted extra-small mt-1 d-block">You can
                                                                    edit or enter email manually if delegate did not receive
                                                                    or provided a wrong email.</small>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer border-top-0 pt-0">
                                                            <button type="button" class="btn btn-sm btn-label-secondary"
                                                                data-bs-dismiss="modal">Cancel</button>
                                                            <button type="submit"
                                                                class="btn btn-sm btn-primary fw-bold px-3 shadow-xs">
                                                                <i class="bx bx-send me-1"></i> Send Email Now
                                                            </button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Revert Modal -->
                                        <div class="modal fade text-start" id="revertModal{{ $reg->id }}"
                                            tabindex="-1" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content border-0 shadow-lg rounded-3">
                                                    <form method="POST" action="{{ route('student-revert-regis') }}">
                                                        @csrf
                                                        <input type="hidden" name="registration_id"
                                                            value="{{ $reg->id }}">
                                                        <input type="hidden" name="acknowledgement_id"
                                                            value="{{ $reg->acknowledgement_id }}">
                                                        <div
                                                            class="modal-header bg-warning bg-opacity-10 py-3 border-bottom-0">
                                                            <div class="d-flex align-items-center gap-2">
                                                                <div
                                                                    class="avatar avatar-sm bg-warning text-white rounded-circle d-flex align-items-center justify-content-center">
                                                                    <i class="bx bx-undo fs-5"></i>
                                                                </div>
                                                                <h5 class="modal-title fw-bold text-dark mb-0">Revert
                                                                    Registration</h5>
                                                            </div>
                                                            <button type="button" class="btn-close"
                                                                data-bs-dismiss="modal"></button>
                                                        </div>
                                                        <div class="modal-body py-3">
                                                            <div
                                                                class="alert alert-warning border-0 extra-small mb-3 d-flex align-items-start gap-2">
                                                                <i class="bx bx-info-circle fs-5 flex-shrink-0 mt-0.5"></i>
                                                                <div>Reverting will allow delegate
                                                                    <strong>{{ $reg->user?->full_name }}</strong> to make
                                                                    corrections or re-upload payment screenshot.</div>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label
                                                                    class="form-label fw-semibold extra-small text-dark">Revert
                                                                    Reason / Remarks *</label>
                                                                <textarea name="reason" class="form-control form-control-sm" rows="3"
                                                                    placeholder="Enter reason for reverting..." required></textarea>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer border-top-0 pt-0">
                                                            <button type="button" class="btn btn-sm btn-label-secondary"
                                                                data-bs-dismiss="modal">Cancel</button>
                                                            <button type="submit"
                                                                class="btn btn-sm btn-warning text-dark fw-bold px-3"><i
                                                                    class="bx bx-send me-1"></i> Submit Revert</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Reject Modal -->
                                        <div class="modal fade text-start" id="rejectModal{{ $reg->id }}"
                                            tabindex="-1" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content border-0 shadow-lg rounded-3">
                                                    <form method="POST" action="{{ route('student-reject-regis') }}">
                                                        @csrf
                                                        <input type="hidden" name="registration_id"
                                                            value="{{ $reg->id }}">
                                                        <input type="hidden" name="acknowledgement_id"
                                                            value="{{ $reg->acknowledgement_id }}">
                                                        <div
                                                            class="modal-header bg-danger bg-opacity-10 py-3 border-bottom-0">
                                                            <div class="d-flex align-items-center gap-2">
                                                                <div
                                                                    class="avatar avatar-sm bg-danger text-white rounded-circle d-flex align-items-center justify-content-center">
                                                                    <i class="bx bx-x-circle fs-5"></i>
                                                                </div>
                                                                <h5 class="modal-title fw-bold text-danger mb-0">Reject
                                                                    Registration</h5>
                                                            </div>
                                                            <button type="button" class="btn-close"
                                                                data-bs-dismiss="modal"></button>
                                                        </div>
                                                        <div class="modal-body py-3">
                                                            <div
                                                                class="alert alert-danger border-0 extra-small mb-3 d-flex align-items-start gap-2">
                                                                <i
                                                                    class="bx bx-error-circle fs-5 flex-shrink-0 mt-0.5"></i>
                                                                <div>Are you sure you want to reject registration for
                                                                    <strong>{{ $reg->user?->full_name }}</strong>?</div>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label
                                                                    class="form-label fw-semibold extra-small text-dark">Rejection
                                                                    Reason *</label>
                                                                <textarea name="reason" class="form-control form-control-sm" rows="3"
                                                                    placeholder="Enter reason for rejection..." required></textarea>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer border-top-0 pt-0">
                                                            <button type="button" class="btn btn-sm btn-label-secondary"
                                                                data-bs-dismiss="modal">Cancel</button>
                                                            <button type="submit"
                                                                class="btn btn-sm btn-danger fw-bold px-3"><i
                                                                    class="bx bx-x me-1"></i> Confirm Reject</button>
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
                                        <div class="py-4 text-muted">
                                            <i class="bx bx-check-double text-success mb-2"
                                                style="font-size: 3.5rem;"></i>
                                            <h6 class="fw-bold text-dark mb-1">All Caught Up!</h6>
                                            <p class="mb-0 extra-small">No submitted registrations pending approval at this
                                                moment.</p>
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
