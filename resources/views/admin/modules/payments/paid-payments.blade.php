@extends('admin.layouts.main')

@section('admin-content')
<div class="container-xxl flex-grow-1 mt-3.5 mb-4">
    <div class="d-flex align-items-center justify-content-between py-2 mb-3">
        <h5 class="mb-0 fw-semibold text-dark">
            <i class="bx bx-credit-card me-2 text-secondary fs-4"></i>Successful Payments
        </h5>
        <span class="badge bg-light text-dark border px-3 py-1.5 fs-7 fw-medium rounded-2">
            Total Paid: {{ $payments->count() }}
        </span>
    </div>

    <div class="card border-0 shadow-sm rounded-3 overflow-hidden">
        <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between flex-wrap gap-2">
            <h6 class="mb-0 fw-semibold text-dark"><i class="bx bx-list-check me-1.5 text-secondary"></i>Paid Transactions History</h6>
            <div class="search-box" style="max-width: 300px; width: 100%;">
                <div class="input-group input-group-sm">
                    <span class="input-group-text bg-light border-end-0"><i class="bx bx-search text-muted"></i></span>
                    <input type="text" id="paidSearchInput" class="form-control bg-light border-start-0" placeholder="Search txn id, name, reg no...">
                </div>
            </div>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="paidPaymentsTable">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-3 py-3 fw-semibold" style="width: 5%;">#</th>
                            <th class="py-3 fw-semibold" style="width: 25%;">Delegate Name</th>
                            <th class="py-3 fw-semibold" style="width: 25%;">Transaction ID</th>
                            <th class="py-3 fw-semibold" style="width: 15%;">Amount</th>
                            <th class="py-3 fw-semibold" style="width: 15%;">Date</th>
                            <th class="pe-3 py-3 text-end fw-semibold" style="width: 15%;">Receipt</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($payments as $pay)
                            <tr>
                                <td class="ps-3 fw-medium text-muted">{{ $loop->iteration }}</td>
                                <td>
                                    <h6 class="mb-0 fw-semibold text-dark" style="font-size: 0.88rem;">
                                        {{ $pay->registration?->user?->prefix }} {{ $pay->registration?->user?->full_name ?? 'N/A' }}
                                    </h6>
                                    <small class="text-muted extra-small font-monospace">
                                        <i class="bx bx-hash me-0.5 text-muted"></i>{{ $pay->registration?->registration_number ?? 'N/A' }}
                                    </small>
                                </td>
                                <td>
                                    <span class="font-monospace text-dark extra-small">
                                        {{ $pay->transaction_id ?: ($pay->gateway_transaction_id ?: 'N/A') }}
                                    </span>
                                </td>
                                <td>
                                    <span class="fw-semibold text-dark" style="font-size: 0.88rem;">
                                        ₹{{ number_format($pay->total_amount, 2) }}
                                    </span>
                                </td>
                                <td>
                                    <small class="text-muted extra-small">
                                        {{ $pay->payment_date ? $pay->payment_date->format('d M Y, h:i A') : ($pay->created_at ? $pay->created_at->format('d M Y, h:i A') : '-') }}
                                    </small>
                                </td>
                                <td class="pe-3 text-end">
                                    @if($pay->payment_receipt_path)
                                        <a href="{{ asset('storage/' . $pay->payment_receipt_path) }}" target="_blank" class="btn btn-xs btn-outline-secondary px-2.5 py-1 rounded-2 extra-small fw-medium">
                                            <i class="bx bx-file me-1"></i> Receipt
                                        </a>
                                    @elseif($pay->registration?->registration_number)
                                        <a href="{{ route('download.receipt', $pay->registration->registration_number) }}" target="_blank" class="btn btn-xs btn-outline-secondary px-2.5 py-1 rounded-2 extra-small fw-medium">
                                            <i class="bx bx-download me-1"></i> PDF
                                        </a>
                                    @else
                                        <span class="text-muted extra-small">-</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <div class="text-muted">
                                        <i class="bx bx-credit-card fs-1 mb-2 text-secondary"></i>
                                        <p class="mb-0 fw-medium">No successful payment records found.</p>
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
    const searchInput = document.getElementById('paidSearchInput');
    const tableRows = document.querySelectorAll('#paidPaymentsTable tbody tr');

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
