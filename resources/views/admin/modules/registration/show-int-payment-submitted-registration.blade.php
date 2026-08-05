@extends('admin.layouts.main')

@section('admin-content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <!-- Header & Breadcrumbs -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
            <div>
                <h4 class="fw-bold mb-1 d-flex align-items-center text-dark">
                    <i class="bx bx-receipt text-primary me-2 fs-3"></i> Payment Submitted Delegates
                </h4>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-style1 mb-0 small">
                        <li class="breadcrumb-item"><a href="javascript:void(0);">Delegates</a></li>
                        <li class="breadcrumb-item active text-primary fw-semibold">Payment Submitted</li>
                    </ol>
                </nav>
            </div>
            <div>
                <span class="badge bg-label-primary px-3 py-2 fs-7 rounded-pill shadow-xs">
                    <i class="bx bx-time-five me-1 align-middle fs-6"></i>
                    <strong class="fs-6 align-middle">{{ count($registrations) }}</strong> Pending Review
                </span>
            </div>
        </div>

        <!-- Session Alert Messages -->
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm rounded-3 mb-4 d-flex align-items-center" role="alert">
                <i class="bx bx-check-circle fs-4 me-2"></i>
                <div>{{ session('success') }}</div>
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm rounded-3 mb-4 d-flex align-items-center" role="alert">
                <i class="bx bx-error-circle fs-4 me-2"></i>
                <div>{{ session('error') }}</div>
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Main Content Card -->
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
            <div class="card-header border-bottom py-3 px-4 d-flex align-items-center justify-content-between" style="background: linear-gradient(135deg, #0F172A 0%, #1E293B 100%);">
                <div class="d-flex align-items-center gap-2">
                    <i class="bx bx-list-check text-info fs-4"></i>
                    <h5 class="card-title text-white mb-0 fw-semibold">Submitted Registrations List</h5>
                </div>
                <span class="badge bg-white bg-opacity-10 text-white border border-white border-opacity-25 px-2.5 py-1 rounded-pill small">
                    Showing {{ count($registrations) }} entries
                </span>
            </div>

            <div class="card-body p-0">
                <div class="table-responsive text-nowrap">
                    <table id="customSimpleTable" class="table table-hover align-middle mb-0">
                        <thead style="background-color: #f8fafc; border-bottom: 2px solid #e2e8f0;">
                            <tr>
                                <th class="py-3 px-3 text-secondary text-uppercase fw-bold" style="font-size: 0.75rem; letter-spacing: 0.5px; width: 50px;">#</th>
                                <th class="py-3 px-3 text-secondary text-uppercase fw-bold" style="font-size: 0.75rem; letter-spacing: 0.5px;"><i class="bx bx-user text-primary me-1"></i> Delegate Info</th>
                                <th class="py-3 px-3 text-secondary text-uppercase fw-bold" style="font-size: 0.75rem; letter-spacing: 0.5px;"><i class="bx bx-category text-info me-1"></i> Category</th>
                                <th class="py-3 px-3 text-secondary text-uppercase fw-bold" style="font-size: 0.75rem; letter-spacing: 0.5px;"><i class="bx bx-credit-card text-success me-1"></i> Payment Details</th>
                                <th class="py-3 px-3 text-secondary text-uppercase fw-bold text-center" style="font-size: 0.75rem; letter-spacing: 0.5px;"><i class="bx bx-slider-alt text-warning me-1"></i> Actions</th>
                            </tr>
                        </thead>
                        <tbody class="table-border-bottom-0">
                            @forelse($registrations as $key => $reg)
                                <tr>
                                    <td class="px-3 fw-semibold text-muted small">{{ $key + 1 }}</td>
                                    <td class="px-3 py-3">
                                        <div class="d-flex align-items-center">
                                            <div class="avatar me-3 flex-shrink-0">
                                                @if($reg->photo_path)
                                                    <img src="{{ asset('storage/' . $reg->photo_path) }}" alt="Photo" class="rounded-circle border border-2 border-white shadow-xs" style="width: 44px; height: 44px; object-fit: cover;">
                                                @else
                                                    <div class="avatar-initial rounded-circle bg-label-primary text-primary fw-bold shadow-xs d-flex align-items-center justify-content-center" style="width: 44px; height: 44px; font-size: 1.1rem;">
                                                        {{ strtoupper(substr($reg->user->full_name ?? 'U', 0, 1)) }}
                                                    </div>
                                                @endif
                                            </div>
                                            <div>
                                                <h6 class="fw-bold text-dark mb-0 fs-6">{{ $reg->user->prefix ?? '' }} {{ $reg->user->full_name ?? 'N/A' }}</h6>
                                                <small class="text-muted d-block mb-1"><i class="bx bx-envelope me-1 opacity-75"></i>{{ $reg->user->email ?? 'N/A' }}</small>
                                                <div class="d-flex flex-wrap align-items-center gap-1.5 mt-1">
                                                    <a href="{{ route('download.receipt', $reg->registration_number) }}" target="_blank" class="badge bg-label-danger text-danger border border-danger border-opacity-25 px-2 py-1 small rounded-2 text-decoration-none shadow-xs" title="Download Receipt PDF">
                                                        <i class="bx bxs-file-pdf me-1"></i>{{ $reg->registration_number }}
                                                    </a>
                                                    <a href="{{ url('/admin/show-registration-details/' . $reg->registration_number) }}" target="_blank" class="badge bg-label-primary text-primary border border-primary border-opacity-25 px-2 py-1 small rounded-2 text-decoration-none shadow-xs">
                                                        <i class="bx bx-show me-1"></i>Details
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-3">
                                        <span class="badge bg-label-info text-info rounded-pill px-3 py-1 fw-semibold fs-7 mb-1">{{ $reg->delegate_type }}</span>
                                        <div class="small fw-semibold text-dark mt-1">
                                            <i class="bx bx-tag-alt text-muted me-1"></i>{{ $reg->delegateCategory->category_name ?? 'N/A' }}
                                        </div>
                                    </td>
                                    <td class="px-3">
                                        <div class="fw-bold text-dark fs-6 mb-1">
                                            <span class="text-success fw-bold">₹{{ number_format($reg->total_amount, 2) }}</span>
                                        </div>
                                        @if($reg->latestPayment && $reg->latestPayment->payment_receipt_path)
                                            <a href="{{ asset('storage/' . $reg->latestPayment->payment_receipt_path) }}" target="_blank" class="btn btn-xs btn-outline-primary rounded-pill px-2.5 py-1 fw-semibold">
                                                <i class="bx bx-file me-1"></i>{{ $reg->latestPayment->transaction_id ?? 'View Receipt' }}
                                            </a>
                                        @elseif($reg->latestPayment && $reg->latestPayment->transaction_id)
                                            <span class="badge bg-label-secondary font-monospace small"><i class="bx bx-hash me-0.5"></i>{{ $reg->latestPayment->transaction_id }}</span>
                                        @endif
                                    </td>
                                    <td class="px-3 text-center">
                                        <div class="d-flex align-items-center justify-content-center flex-wrap gap-1.5">
                                            {{-- Approve Form --}}
                                            <form action="{{ route('student-approved-regis') }}" method="POST" class="d-inline">
                                                @csrf
                                                <input type="hidden" name="registration_number" value="{{ $reg->registration_number }}">
                                                <button type="submit" class="btn btn-sm btn-success d-inline-flex align-items-center gap-1 shadow-xs rounded-2 px-2.5 py-1.5 fw-semibold" onclick="return confirm('Are you sure you want to approve this registration?')">
                                                    <i class="bx bx-check-circle fs-6"></i> Approve
                                                </button>
                                            </form>

                                            {{-- Revert Modal Trigger --}}
                                            <button type="button" class="btn btn-sm btn-warning text-dark d-inline-flex align-items-center gap-1 rounded-2 px-2.5 py-1.5 fw-semibold shadow-xs" onclick="openRevertModal('{{ $reg->id }}', '{{ $reg->registration_number }}', '{{ addslashes($reg->user->full_name ?? '') }}')">
                                                <i class="bx bx-undo fs-6"></i> Revert
                                            </button>

                                            {{-- Reject Modal Trigger --}}
                                            <button type="button" class="btn btn-sm btn-outline-danger d-inline-flex align-items-center gap-1 rounded-2 px-2.5 py-1.5 fw-semibold" onclick="openRejectModal('{{ $reg->id }}', '{{ $reg->registration_number }}', '{{ addslashes($reg->user->full_name ?? '') }}')">
                                                <i class="bx bx-x-circle fs-6"></i> Reject
                                            </button>

                                            {{-- Delete Form --}}
                                            <form action="{{ route('student-regis-delete') }}" method="POST" class="d-inline">
                                                @csrf
                                                <input type="hidden" name="registration_number" value="{{ $reg->registration_number }}">
                                                <button type="submit" class="btn btn-sm btn-icon btn-label-secondary text-danger rounded-2" title="Delete Registration" onclick="return confirm('Are you sure you want to delete this registration?')">
                                                    <i class="bx bx-trash fs-6"></i>
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
                                            <p class="text-muted small mb-0">No submitted registrations pending review at this moment.</p>
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
                    <input type="hidden" name="registration_number" id="modal_revert_registration_number">
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
                        <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-warning text-dark fw-semibold"><i class="bx bx-send me-1"></i> Submit Revert</button>
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
                    <input type="hidden" name="registration_number" id="modal_reject_registration_number">
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
                        <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger fw-semibold"><i class="bx bx-x me-1"></i> Confirm Reject</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openRevertModal(id, regNo, name) {
            document.getElementById('modal_revert_registration_number').value = regNo;
            document.getElementById('modal_revert_delegate_info').innerText = name + ' (' + regNo + ')';
            document.getElementById('modal_revert_reason').value = '';
            var modalElement = document.getElementById('revertModal');
            var modal = new bootstrap.Modal(modalElement);
            modal.show();
        }

        function openRejectModal(id, regNo, name) {
            document.getElementById('modal_reject_registration_number').value = regNo;
            document.getElementById('modal_reject_delegate_info').innerText = name + ' (' + regNo + ')';
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

