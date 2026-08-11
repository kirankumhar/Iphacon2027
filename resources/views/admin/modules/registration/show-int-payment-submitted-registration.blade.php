@extends('admin.layouts.main')

@section('admin-content')
    <div class="container-xxl flex-grow-1 mt-3.5 mb-4">
        <!-- Header & Breadcrumbs -->
        <div class="d-flex align-items-center justify-content-between py-2 mb-3">
            <div>
                <span class="text-muted extra-small d-block">IPHACON 2027 Admin Portal</span>
                <h5 class="mb-0 fw-bold text-dark d-flex align-items-center gap-2">
                    <i class="bx bx-globe text-primary fs-4"></i>Submitted Foreign Delegates (Awaiting Approval)
                </h5>
            </div>
            <span class="badge bg-light text-dark border px-3 py-1.5 fs-7 fw-medium rounded-pill">
                <i class="bx bx-time-five text-primary me-1"></i>{{ count($registrations) }} Pending Review
            </span>
        </div>

        <!-- Session Alert Messages -->
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show border-0 shadow-xs rounded-3 mb-3 d-flex align-items-center" role="alert" style="background-color: #DCFCE7; color: #065F46;">
                <i class="bx bx-check-circle fs-4 me-2 text-success"></i>
                <div class="fw-bold">{{ session('success') }}</div>
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show border-0 shadow-xs rounded-3 mb-3 d-flex align-items-center" role="alert" style="background-color: #FEE2E2; color: #991B1B;">
                <i class="bx bx-error-circle fs-4 me-2 text-danger"></i>
                <div class="fw-bold">{{ session('error') }}</div>
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Main Content Card -->
        <div class="card border-0 shadow-sm rounded-3 overflow-hidden mb-4">
            <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between flex-wrap gap-2">
                <h6 class="mb-0 fw-bold text-dark d-flex align-items-center gap-2">
                    <i class="bx bx-list-check text-primary fs-5"></i>Foreign Delegates Pending List
                </h6>
                <span class="badge bg-light text-dark border px-2.5 py-1 extra-small fw-medium">
                    Showing {{ count($registrations) }} entries
                </span>
            </div>

            <div class="card-body p-0">
                <div class="table-responsive">
                    <table id="customSimpleTable" class="table table-hover align-middle mb-0">
                        <thead style="background-color: #F8FAFC; border-bottom: 1px solid #E2E8F0;">
                            <tr>
                                <th class="ps-3 py-3 text-secondary text-uppercase fw-bold extra-small" style="width: 35px;">#</th>
                                <th class="py-3 text-secondary text-uppercase fw-bold extra-small" style="width: 28%;"><i class="bx bx-user text-primary me-1"></i> Delegate Info</th>
                                <th class="py-3 text-secondary text-uppercase fw-bold extra-small" style="width: 22%;"><i class="bx bx-category text-info me-1"></i> Category</th>
                                <th class="py-3 text-secondary text-uppercase fw-bold extra-small" style="width: 20%;"><i class="bx bx-credit-card text-success me-1"></i> Payment Details</th>
                                <th class="pe-3 py-3 text-secondary text-uppercase fw-bold text-end extra-small" style="width: 27%;"><i class="bx bx-slider-alt text-warning me-1"></i> Actions</th>
                            </tr>
                        </thead>
                        <tbody class="table-border-bottom-0">
                            @forelse($registrations as $key => $reg)
                                <tr>
                                    <td class="ps-3 fw-semibold text-muted small">{{ $key + 1 }}</td>
                                    <td class="py-2.5">
                                        <div class="d-flex align-items-center gap-2.5">
                                            <div class="avatar avatar-md flex-shrink-0" style="width: 38px; height: 38px;">
                                                @if($reg->photo_path)
                                                    <img src="{{ asset('storage/' . $reg->photo_path) }}" alt="Photo" class="rounded-circle border w-100 h-100 shadow-xs" style="object-fit: cover;">
                                                @else
                                                    <div class="avatar-initial rounded-circle bg-primary bg-opacity-10 text-primary fw-bold d-flex align-items-center justify-content-center w-100 h-100 fs-6 border border-primary border-opacity-25">
                                                        {{ strtoupper(substr($reg->user->full_name ?? 'U', 0, 1)) }}
                                                    </div>
                                                @endif
                                            </div>
                                            <div>
                                                <div class="fw-bold text-dark" style="font-size: 0.86rem; line-height: 1.2;">
                                                    {{ $reg->user->prefix ?? '' }} {{ $reg->user->full_name ?? 'N/A' }}
                                                </div>
                                                <div class="text-muted extra-small text-truncate mb-1" style="max-width: 200px; font-size: 0.75rem;">
                                                    {{ $reg->user->email ?? 'N/A' }}
                                                </div>
                                                <div class="d-flex align-items-center gap-1.5">
                                                    <span class="badge font-monospace extra-small px-2 py-0.5 rounded-2" style="background-color: #F1F5F9; color: #334155; border: 1px solid #CBD5E1; font-size: 0.70rem;">
                                                        ACK: {{ $reg->acknowledgement_id ?? 'N/A' }}
                                                    </span>
                                                    <a href="{{ route('show-registration-details', $reg->acknowledgement_id ?? $reg->id) }}" class="btn btn-xs btn-outline-primary rounded-pill px-2 py-0.5 extra-small fw-bold" style="font-size: 0.70rem;">
                                                        <i class="bx bx-show me-0.5"></i> Details
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge rounded-pill px-2.5 py-1 extra-small fw-semibold mb-1" style="background-color: #EFF6FF; color: #1D4ED8; border: 1px solid #BFDBFE;">
                                            {{ $reg->delegate_type ?? 'International' }}
                                        </span>
                                        <div class="extra-small text-dark fw-medium mt-0.5" style="font-size: 0.76rem;">
                                            {{ $reg->delegateCategory->category_name ?? 'N/A' }}
                                        </div>
                                    </td>
                                    <td>
                                        <div class="fw-bold fs-6 mb-0.5" style="color: #059669;">
                                            {{ $reg->delegate_type == 'International' ? '$' : '₹' }}{{ number_format($reg->total_amount, 2) }}
                                        </div>
                                        <div class="d-flex align-items-center gap-1.5 flex-wrap">
                                            @if($reg->latestPayment && $reg->latestPayment->transaction_id)
                                                <span class="badge font-monospace extra-small px-2 py-0.5" style="background-color: #F8FAFC; color: #475569; border: 1px solid #E2E8F0; font-size: 0.70rem;">
                                                    <i class="bx bx-receipt text-primary me-0.5"></i>Txn ID: {{ $reg->latestPayment->transaction_id }}
                                                </span>
                                            @endif
                                            @if($reg->latestPayment && $reg->latestPayment->payment_receipt_path)
                                                <a href="{{ asset('storage/' . $reg->latestPayment->payment_receipt_path) }}" target="_blank" class="extra-small text-primary text-decoration-none fw-semibold" style="font-size: 0.72rem;">
                                                    <i class="bx bx-file me-0.5"></i>View Screenshot
                                                </a>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="pe-3 text-end">
                                        <div class="d-flex align-items-center justify-content-end gap-1" style="white-space: nowrap;">
                                            @if(in_array($reg->status, ['Payment Submitted', 'Submitted', 'Pending Payment']) || !empty($reg->latestPayment))
                                                {{-- Approve Form --}}
                                                <form action="{{ route('student-approved-regis') }}" method="POST" class="d-inline m-0">
                                                    @csrf
                                                    <input type="hidden" name="registration_id" value="{{ $reg->id }}">
                                                    <input type="hidden" name="acknowledgement_id" value="{{ $reg->acknowledgement_id }}">
                                                    <button type="submit" class="btn btn-xs btn-success fw-bold px-2.5 py-1 rounded-2 shadow-xs d-inline-flex align-items-center gap-1" style="font-size: 0.74rem;" onclick="return confirm('Are you sure you want to approve this registration?')">
                                                        <i class="bx bx-check-circle" style="font-size: 0.85rem;"></i> Approve
                                                    </button>
                                                </form>
                                            @endif

                                            {{-- Revert Modal Trigger --}}
                                            <button type="button" class="btn btn-xs btn-warning text-dark fw-bold px-2.5 py-1 rounded-2 shadow-xs d-inline-flex align-items-center gap-1" style="font-size: 0.74rem;" onclick="openRevertModal('{{ $reg->id }}', '{{ $reg->acknowledgement_id }}', '{{ addslashes($reg->user->full_name ?? '') }}')">
                                                <i class="bx bx-undo" style="font-size: 0.85rem;"></i> Revert
                                            </button>

                                            {{-- Reject Modal Trigger --}}
                                            <button type="button" class="btn btn-xs btn-outline-danger fw-bold px-2.5 py-1 rounded-2 d-inline-flex align-items-center gap-1" style="font-size: 0.74rem;" onclick="openRejectModal('{{ $reg->id }}', '{{ $reg->acknowledgement_id }}', '{{ addslashes($reg->user->full_name ?? '') }}')">
                                                <i class="bx bx-x-circle" style="font-size: 0.85rem;"></i> Reject
                                            </button>

                                            {{-- Delete Form --}}
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
                                    <td colspan="5" class="text-center py-5">
                                        <div class="py-4">
                                            <i class="bx bx-check-double text-success mb-2" style="font-size: 3.5rem;"></i>
                                            <h6 class="fw-bold text-dark mb-1">All Caught Up!</h6>
                                            <p class="text-muted small mb-0">No submitted foreign registrations pending review at this moment.</p>
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

    <!-- Revert Registration Modal -->
    <div class="modal fade" id="revertModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-3">
                <form action="{{ route('student-revert-regis') }}" method="POST">
                    @csrf
                    <input type="hidden" name="registration_id" id="modal_revert_registration_id">
                    <input type="hidden" name="acknowledgement_id" id="modal_revert_acknowledgement_id">
                    <div class="modal-header bg-warning bg-opacity-10 border-bottom-0 pb-0">
                        <div class="d-flex align-items-center gap-2">
                            <div class="avatar avatar-sm bg-warning text-white rounded-circle d-flex align-items-center justify-content-center">
                                <i class="bx bx-undo fs-5"></i>
                            </div>
                            <h5 class="modal-title fw-bold text-dark">Revert Registration</h5>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body py-3">
                        <div class="alert alert-warning border-0 small mb-3 d-flex align-items-start gap-2">
                            <i class="bx bx-info-circle fs-5 flex-shrink-0 mt-0.5"></i>
                            <div>Reverting sends this registration back to the delegate for editing or resubmitting payment details.</div>
                        </div>
                        <p class="small text-muted mb-2">Delegate: <strong id="modal_revert_delegate_info" class="text-dark"></strong></p>
                        <div class="mb-3">
                            <label for="modal_revert_reason" class="form-label fw-semibold small text-dark">Reason for Revert <span class="text-danger">*</span></label>
                            <textarea class="form-control" name="reason" id="modal_revert_reason" rows="3" placeholder="Enter reason (e.g., Payment receipt unclear, please re-upload)..." required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer border-top-0 pt-0">
                        <button type="button" class="btn btn-sm btn-label-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-sm btn-warning text-dark fw-semibold px-3"><i class="bx bx-send me-1"></i> Submit Revert</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Reject Registration Modal -->
    <div class="modal fade" id="rejectModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-3">
                <form action="{{ route('student-reject-regis') }}" method="POST">
                    @csrf
                    <input type="hidden" name="registration_id" id="modal_reject_registration_id">
                    <input type="hidden" name="acknowledgement_id" id="modal_reject_acknowledgement_id">
                    <div class="modal-header bg-danger bg-opacity-10 border-bottom-0 pb-0">
                        <div class="d-flex align-items-center gap-2">
                            <div class="avatar avatar-sm bg-danger text-white rounded-circle d-flex align-items-center justify-content-center">
                                <i class="bx bx-x-circle fs-5"></i>
                            </div>
                            <h5 class="modal-title fw-bold text-danger">Reject Registration</h5>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body py-3">
                        <div class="alert alert-danger border-0 small mb-3 d-flex align-items-start gap-2">
                            <i class="bx bx-error-circle fs-5 flex-shrink-0 mt-0.5"></i>
                            <div>Rejecting declines this registration application. The user will be informed.</div>
                        </div>
                        <p class="small text-muted mb-2">Delegate: <strong id="modal_reject_delegate_info" class="text-dark"></strong></p>
                        <div class="mb-3">
                            <label for="modal_reject_reason" class="form-label fw-semibold small text-dark">Reason for Rejection <span class="text-danger">*</span></label>
                            <textarea class="form-control" name="reason" id="modal_reject_reason" rows="3" placeholder="Enter reason for rejection..." required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer border-top-0 pt-0">
                        <button type="button" class="btn btn-sm btn-label-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-sm btn-danger fw-semibold px-3"><i class="bx bx-x me-1"></i> Confirm Reject</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openRevertModal(id, ackNo, name) {
            document.getElementById('modal_revert_registration_id').value = id;
            document.getElementById('modal_revert_acknowledgement_id').value = ackNo;
            document.getElementById('modal_revert_delegate_info').innerText = name + ' (Ack: ' + ackNo + ')';
            document.getElementById('modal_revert_reason').value = '';
            var modalElement = document.getElementById('revertModal');
            var modal = new bootstrap.Modal(modalElement);
            modal.show();
        }

        function openRejectModal(id, ackNo, name) {
            document.getElementById('modal_reject_registration_id').value = id;
            document.getElementById('modal_reject_acknowledgement_id').value = ackNo;
            document.getElementById('modal_reject_delegate_info').innerText = name + ' (Ack: ' + ackNo + ')';
            document.getElementById('modal_reject_reason').value = '';
            var modalElement = document.getElementById('rejectModal');
            var modal = new bootstrap.Modal(modalElement);
            modal.show();
        }

        // Initialize DataTable if plugin is present and table has valid data rows
        document.addEventListener("DOMContentLoaded", function() {
            if (typeof $ !== 'undefined' && $.fn && $.fn.DataTable) {
                $.fn.dataTable.ext.errMode = 'none'; // Suppress browser alert popups
                var $table = $('#customSimpleTable');
                if ($table.length && $table.find('tbody tr:first td').length > 1) {
                    if (!$.fn.DataTable.isDataTable('#customSimpleTable')) {
                        $table.DataTable({
                            responsive: true,
                            pageLength: 10,
                            order: [],
                            language: {
                                search: "_INPUT_",
                                searchPlaceholder: "Search delegates..."
                            },
                            drawCallback: function(settings) {
                                var api = this.api();
                                var pageInfo = api.page.info();
                                var wrapper = $(api.table().container());
                                // Hide pagination if total items fit within a single page
                                if (pageInfo.pages <= 1) {
                                    wrapper.find('.dataTables_paginate').hide();
                                } else {
                                    wrapper.find('.dataTables_paginate').show();
                                }
                            }
                        });
                    }
                }
            }
        });
    </script>
@endsection

