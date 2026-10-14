@extends('admin.layouts.main')

@section('admin-content')
<style>
    .delegate-table-card {
        border: 1px solid #E2E8F0 !important;
        border-radius: 16px !important;
        background: #FFFFFF;
        box-shadow: 0 4px 20px -2px rgba(15, 23, 42, 0.05);
        overflow: hidden;
    }
    .delegate-table thead th {
        background-color: #F8FAFC !important;
        color: #475569 !important;
        font-size: 0.74rem !important;
        font-weight: 700 !important;
        text-transform: uppercase !important;
        letter-spacing: 0.6px !important;
        border-bottom: 1px solid #E2E8F0 !important;
        padding-top: 14px !important;
        padding-bottom: 14px !important;
    }
    .delegate-table tbody tr {
        transition: background-color 0.15s ease-in-out;
        border-bottom: 1px solid #F1F5F9;
    }
    .delegate-table tbody tr:hover {
        background-color: #F8FAFC !important;
    }
    .delegate-table tbody tr:last-child {
        border-bottom: none;
    }
    .delegate-table tbody td {
        padding-top: 13px !important;
        padding-bottom: 13px !important;
        vertical-align: middle;
    }
    .avatar-wrapper {
        width: 38px;
        height: 38px;
        border-radius: 50%;
        overflow: hidden;
        border: 2px solid #FFFFFF;
        box-shadow: 0 2px 6px rgba(15, 23, 42, 0.08);
        background: #F1F5F9;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 0.85rem;
        color: #2D69FF;
        flex-shrink: 0;
    }
    .avatar-wrapper img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .reg-code-badge {
        font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace;
        font-size: 0.72rem;
        color: #475569;
        background: #F1F5F9;
        padding: 2px 7px;
        border-radius: 5px;
        border: 1px solid #E2E8F0;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 3px;
    }
    .badge-category {
        font-size: 0.75rem;
        font-weight: 600;
        padding: 4px 10px;
        border-radius: 20px;
        background: #EFF6FF;
        color: #1E40AF;
        border: 1px solid #DBEAFE;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }
    .badge-cme-tag {
        font-size: 0.68rem;
        font-weight: 700;
        padding: 2px 7px;
        border-radius: 12px;
        background: #ECFDF5;
        color: #047857;
        border: 1px solid #A7F3D0;
    }
    .badge-pay-sbi {
        font-size: 0.7rem;
        font-weight: 600;
        padding: 3px 8px;
        border-radius: 14px;
        background: #EFF6FF;
        color: #1D4ED8;
        border: 1px solid #BFDBFE;
        display: inline-flex;
        align-items: center;
        gap: 3px;
    }
    .badge-pay-qr {
        font-size: 0.7rem;
        font-weight: 600;
        padding: 3px 8px;
        border-radius: 14px;
        background: #F0FDF4;
        color: #15803D;
        border: 1px solid #BBF7D0;
        display: inline-flex;
        align-items: center;
        gap: 3px;
    }
    .badge-pay-other {
        font-size: 0.7rem;
        font-weight: 600;
        padding: 3px 8px;
        border-radius: 14px;
        background: #F8FAFC;
        color: #475569;
        border: 1px solid #E2E8F0;
        display: inline-flex;
        align-items: center;
        gap: 3px;
    }
    .badge-approved-status {
        font-size: 0.72rem;
        font-weight: 700;
        padding: 3px 9px;
        border-radius: 14px;
        background: #DCFCE7;
        color: #14532D;
        border: 1px solid #86EFAC;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }
    .btn-action-view {
        background: #F8FAFC;
        color: #1E293B;
        border: 1px solid #CBD5E1;
        font-size: 0.75rem;
        font-weight: 600;
        padding: 5px 12px;
        border-radius: 20px;
        transition: all 0.15s ease;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        text-decoration: none;
    }
    .btn-action-view:hover {
        background: #2D69FF;
        color: #FFFFFF;
        border-color: #2D69FF;
        box-shadow: 0 3px 8px rgba(45, 105, 255, 0.25);
    }
    .btn-action-pdf {
        background: #F0FDF4;
        color: #166534;
        border: 1px solid #BBF7D0;
        font-size: 0.75rem;
        font-weight: 600;
        padding: 5px 12px;
        border-radius: 20px;
        transition: all 0.15s ease;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        text-decoration: none;
    }
    .btn-action-pdf:hover {
        background: #16A34A;
        color: #FFFFFF;
        border-color: #16A34A;
        box-shadow: 0 3px 8px rgba(22, 163, 74, 0.25);
    }
    .search-input-pill {
        border-radius: 30px !important;
        border: 1px solid #CBD5E1 !important;
        padding-left: 14px !important;
        font-size: 0.82rem !important;
        background-color: #F8FAFC !important;
        transition: all 0.2s ease;
    }
    .search-input-pill:focus {
        background-color: #FFFFFF !important;
        border-color: #2D69FF !important;
        box-shadow: 0 0 0 3px rgba(45, 105, 255, 0.1) !important;
    }
</style>

<div class="container-xxl flex-grow-1 container-p-y">
    {{-- Page Header --}}
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 py-3 mb-2">
        <div>
            <div class="d-flex align-items-center gap-2">
                <div class="rounded-circle d-flex align-items-center justify-content-center shadow-xs" style="width: 36px; height: 36px; background: #DCFCE7; color: #16A34A;">
                    <i class="bx bxs-badge-check fs-4"></i>
                </div>
                <div>
                    <h5 class="mb-0 fw-bold text-dark" style="letter-spacing: -0.3px;">Indian Approved Registrations</h5>
                    <p class="text-muted small mb-0" style="font-size: 0.78rem;">Verified & approved delegate list for IPHACON 2027</p>
                </div>
            </div>
        </div>
        <div class="d-flex align-items-center gap-2">
            <span class="badge px-3 py-2 rounded-pill fw-bold shadow-xs" style="background: #ECFDF5; color: #047857; border: 1px solid #A7F3D0; font-size: 0.8rem;">
                <i class="bx bx-user-check me-1"></i> <span id="totalBadgeCount">{{ $registrations->count() }}</span> Approved
            </span>
        </div>
    </div>

    {{-- Session Alerts --}}
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

    {{-- Main Table Card --}}
    <div class="delegate-table-card">
        {{-- Card Header with Filter & Live Search --}}
        <div class="py-3 px-4 bg-white border-bottom d-flex align-items-center justify-content-between flex-wrap gap-3">
            <div class="d-flex align-items-center gap-2">
                <h6 class="mb-0 fw-bold text-dark" style="font-size: 0.92rem;">
                    <i class="bx bx-list-ul me-1.5 text-primary"></i>Delegates Directory
                </h6>
                <span class="text-muted extra-small" style="font-size: 0.75rem;">(Showing <strong id="visibleCountText">{{ $registrations->count() }}</strong> records)</span>
            </div>
            <div class="search-box" style="max-width: 320px; width: 100%;">
                <div class="input-group input-group-sm">
                    <span class="input-group-text bg-light border-end-0 rounded-start-pill" style="border-color: #CBD5E1;"><i class="bx bx-search text-muted"></i></span>
                    <input type="text" id="approvedSearchInput" class="form-control bg-light border-start-0 rounded-end-pill search-input-pill" placeholder="Search name, reg no, category..." style="padding-top: 7px; padding-bottom: 7px;">
                </div>
            </div>
        </div>

        {{-- Table --}}
        <div class="table-responsive">
            <table class="table delegate-table align-middle mb-0" id="approvedDelegatesTable">
                <thead>
                    <tr>
                        <th class="ps-4 text-center" style="width: 5%;">#</th>
                        <th style="width: 32%;">Delegate</th>
                        <th style="width: 22%;">Category</th>
                        <th style="width: 17%;">Paid Amount</th>
                        <th style="width: 12%;">Approved Date</th>
                        <th class="pe-4 text-end" style="width: 12%;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($registrations as $index => $reg)
                        @php
                            $user = $reg->user;
                            $payRec = $reg->latestPayment;
                            $paidAmount = ($payRec && (float)$payRec->total_amount > 0)
                                ? (float)$payRec->total_amount
                                : (float)($reg->total_amount ?: $reg->calculateTotalAmount());
                            $method = $payRec?->payment_method;
                            $hasCme = $reg->participate_in_cme || ($reg->cmePayment && in_array(strtoupper($reg->cmePayment->payment_status ?? ''), ['SUCCESS', 'PAID', 'APPROVED', 'COMPLETED']));
                            $regNum = $reg->registration_number ?? ($reg->acknowledgement_id ?? $reg->id);
                            $approvedDate = $reg->approved_at 
                                ? \Carbon\Carbon::parse($reg->approved_at)->format('d M, Y') 
                                : ($reg->created_at ? \Carbon\Carbon::parse($reg->created_at)->format('d M, Y') : 'N/A');
                            $initials = strtoupper(substr($user?->full_name ?? 'D', 0, 1));
                        @endphp
                        <tr class="delegate-row">
                            <td class="ps-4 text-center fw-semibold text-muted" style="font-size: 0.8rem;">
                                {{ $index + 1 }}
                            </td>
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    <div class="avatar-wrapper">
                                        @if($reg->photo_path)
                                            <img src="{{ asset('storage/' . $reg->photo_path) }}" alt="{{ $user?->full_name }}" onerror="this.style.display='none'; this.nextElementSibling.style.display='block';" />
                                            <span style="display: none;">{{ $initials }}</span>
                                        @else
                                            <span>{{ $initials }}</span>
                                        @endif
                                    </div>
                                    <div>
                                        <div class="fw-bold text-dark" style="font-size: 0.88rem; line-height: 1.3;">
                                            <a href="{{ route('show-registration-details', $regNum) }}" class="text-dark text-decoration-none">
                                                {{ $user?->prefix }} {{ $user?->full_name ?? 'N/A' }}
                                            </a>
                                        </div>
                                        <div class="mt-1 d-flex align-items-center gap-1.5">
                                            <span class="reg-code-badge" title="Registration Number">
                                                <i class="bx bx-hash"></i>{{ $reg->registration_number ?? ($reg->acknowledgement_id ?? 'Pending') }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex flex-column align-items-start gap-1">
                                    <span class="badge-category">
                                        <i class="bx bx-id-card"></i>{{ $reg->delegateCategory?->category_name ?? 'Indian Delegate' }}
                                    </span>
                                    @if($hasCme)
                                        <span class="badge-cme-tag">
                                            <i class="bx bx-check me-0.5"></i>Pre-Conf Workshop (CME)
                                        </span>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <div>
                                    <div class="fw-bold text-dark" style="font-size: 0.95rem; line-height: 1.2;">
                                        ₹{{ number_format($paidAmount, 2) }}
                                    </div>
                                    <div class="mt-1">
                                        @if($method === 'SBI_ePay' || $payRec?->gateway_transaction_id)
                                            <span class="badge-pay-sbi" title="Paid via State Bank of India ePay Gateway">
                                                <i class="bx bx-credit-card"></i>SBI ePay
                                            </span>
                                        @elseif($method === 'QR_Code' || $payRec?->payment_receipt_path)
                                            <span class="badge-pay-qr" title="Paid via UPI QR Code Transfer">
                                                <i class="bx bx-qr-scan"></i>UPI QR
                                            </span>
                                        @elseif($method)
                                            <span class="badge-pay-other">
                                                <i class="bx bx-wallet"></i>{{ str_replace('_', ' ', $method) }}
                                            </span>
                                        @else
                                            <span class="badge-pay-other">
                                                <i class="bx bx-check"></i>Verified
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="small fw-semibold text-dark" style="font-size: 0.8rem;">
                                    {{ $approvedDate }}
                                </div>
                                <div class="mt-0.5">
                                    <span class="badge-approved-status">
                                        <i class="bx bx-check-circle"></i>Approved
                                    </span>
                                </div>
                            </td>
                            <td class="pe-4 text-end">
                                <div class="d-inline-flex align-items-center gap-1.5">
                                    <a href="{{ route('show-registration-details', $regNum) }}" class="btn-action-view" title="View Full Details">
                                        <i class="bx bx-show"></i>Details
                                    </a>
                                    <a href="{{ route('download.receipt', $regNum) }}" target="_blank" class="btn-action-pdf" title="Download Official Receipt PDF">
                                        <i class="bx bxs-file-pdf"></i>PDF
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr id="emptyRow">
                            <td colspan="6" class="text-center py-5">
                                <div class="py-4">
                                    <div class="rounded-circle bg-light d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                                        <i class="bx bx-user-check text-muted fs-1"></i>
                                    </div>
                                    <h6 class="fw-bold text-dark mb-1">No Approved Registrations Found</h6>
                                    <p class="text-muted small mb-0">Approved registrations will appear here once verified.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                    <tr id="noResultsRow" style="display: none;">
                        <td colspan="6" class="text-center py-5">
                            <div class="py-4">
                                <i class="bx bx-search-alt text-muted fs-1 mb-2"></i>
                                <h6 class="fw-bold text-dark mb-1">No matching delegates found</h6>
                                <p class="text-muted small mb-0">Try searching with a different name, registration number, or category.</p>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('approvedSearchInput');
    const tableRows = document.querySelectorAll('#approvedDelegatesTable tbody tr.delegate-row');
    const countDisplay = document.getElementById('visibleCountText');
    const noResultsRow = document.getElementById('noResultsRow');
    const totalCount = {{ $registrations->count() }};

    if (searchInput) {
        searchInput.addEventListener('input', function() {
            const query = this.value.toLowerCase().trim();
            let visibleCount = 0;

            tableRows.forEach(row => {
                const text = row.innerText.toLowerCase();
                if (text.includes(query)) {
                    row.style.display = '';
                    visibleCount++;
                } else {
                    row.style.display = 'none';
                }
            });

            if (countDisplay) {
                countDisplay.textContent = visibleCount;
            }

            if (noResultsRow) {
                noResultsRow.style.display = (visibleCount === 0 && totalCount > 0) ? '' : 'none';
            }
        });
    }
});
</script>
@endsection
