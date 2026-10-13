@extends('admin.layouts.main')

@section('admin-content')
<div class="container-xxl flex-grow-1 mt-3.5 mb-4">
    <!-- Page Header -->
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 py-2 mb-3">
        <div>
            <h5 class="mb-0 fw-semibold text-dark d-flex align-items-center gap-2">
                <i class="bx bx-receipt text-primary fs-3"></i> Payment Gateway Logs
            </h5>
            <small class="text-muted font-monospace" style="font-size: 0.75rem;">
                <i class="bx bx-folder me-1"></i>storage/logs/payment/
            </small>
        </div>

        <div class="d-flex align-items-center gap-2">
            @if(!empty($selectedFile))
                <a href="{{ route('admin.payment-logs.download', $selectedFile) }}" class="btn btn-sm btn-outline-primary d-flex align-items-center gap-1 shadow-xs">
                    <i class="bx bx-download"></i> Download Log File
                </a>
            @endif
            <a href="{{ route('admin.payment-logs') }}" class="btn btn-sm btn-light border d-flex align-items-center gap-1 shadow-xs" title="Refresh">
                <i class="bx bx-refresh"></i> Refresh
            </a>
        </div>
    </div>

    <!-- Stats Summary Row -->
    <div class="row g-3 mb-3">
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm rounded-3 p-3 bg-white">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted extra-small text-uppercase fw-semibold">Total Logs</span>
                        <h4 class="mb-0 fw-bold text-dark mt-1">{{ $stats['total'] }}</h4>
                    </div>
                    <div class="avatar avatar-sm bg-light-primary text-primary rounded-3 d-flex align-items-center justify-content-center p-2">
                        <i class="bx bx-list-ul fs-4 text-primary"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm rounded-3 p-3 bg-white">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted extra-small text-uppercase fw-semibold">Success Events</span>
                        <h4 class="mb-0 fw-bold text-success mt-1">{{ $stats['success'] }}</h4>
                    </div>
                    <div class="avatar avatar-sm bg-light-success text-success rounded-3 d-flex align-items-center justify-content-center p-2">
                        <i class="bx bx-check-circle fs-4 text-success"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm rounded-3 p-3 bg-white">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted extra-small text-uppercase fw-semibold">Warnings / Failed</span>
                        <h4 class="mb-0 fw-bold text-warning mt-1">{{ $stats['warning'] }}</h4>
                    </div>
                    <div class="avatar avatar-sm bg-light-warning text-warning rounded-3 d-flex align-items-center justify-content-center p-2">
                        <i class="bx bx-error fs-4 text-warning"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm rounded-3 p-3 bg-white">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted extra-small text-uppercase fw-semibold">Exceptions / Errors</span>
                        <h4 class="mb-0 fw-bold text-danger mt-1">{{ $stats['error'] }}</h4>
                    </div>
                    <div class="avatar avatar-sm bg-light-danger text-danger rounded-3 d-flex align-items-center justify-content-center p-2">
                        <i class="bx bx-x-circle fs-4 text-danger"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter and Search Toolbar -->
    <div class="card border-0 shadow-sm rounded-3 mb-3">
        <div class="card-body p-3">
            <form method="GET" action="{{ route('admin.payment-logs') }}" class="row g-2 align-items-center">
                <!-- Log File Selector -->
                <div class="col-md-4 col-sm-6">
                    <label class="form-label extra-small text-muted fw-semibold mb-1">Select Log File / Date</label>
                    <select name="file" class="form-select form-select-sm" onchange="this.form.submit()">
                        @forelse($files as $f)
                            <option value="{{ $f['filename'] }}" {{ $selectedFile === $f['filename'] ? 'selected' : '' }}>
                                {{ $f['filename'] }} ({{ $f['size_readable'] }}) - {{ $f['modified_at'] }}
                            </option>
                        @empty
                            <option value="">No log files found</option>
                        @endforelse
                    </select>
                </div>

                <!-- Level Filter -->
                <div class="col-md-2 col-sm-6">
                    <label class="form-label extra-small text-muted fw-semibold mb-1">Severity Level</label>
                    <select name="level" class="form-select form-select-sm" onchange="this.form.submit()">
                        <option value="" {{ empty($level) ? 'selected' : '' }}>All Levels</option>
                        <option value="INFO" {{ $level === 'INFO' ? 'selected' : '' }}>INFO</option>
                        <option value="WARNING" {{ $level === 'WARNING' ? 'selected' : '' }}>WARNING</option>
                        <option value="ERROR" {{ $level === 'ERROR' ? 'selected' : '' }}>ERROR</option>
                    </select>
                </div>

                <!-- Search Input -->
                <div class="col-md-4 col-sm-8">
                    <label class="form-label extra-small text-muted fw-semibold mb-1">Search in Log</label>
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-light border-end-0"><i class="bx bx-search text-muted"></i></span>
                        <input type="text" name="search" value="{{ $search }}" class="form-control bg-light border-start-0" placeholder="Search orderRef, transactionId, action...">
                    </div>
                </div>

                <!-- Buttons -->
                <div class="col-md-2 col-sm-4 d-flex align-items-end gap-1 mt-auto">
                    <button type="submit" class="btn btn-sm btn-primary w-100 fw-semibold">
                        <i class="bx bx-filter-alt"></i> Filter
                    </button>
                    @if($search || $level)
                        <a href="{{ route('admin.payment-logs', ['file' => $selectedFile]) }}" class="btn btn-sm btn-outline-secondary" title="Clear Filters">
                            <i class="bx bx-x"></i>
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <!-- Log Entries List Card -->
    <div class="card border-0 shadow-sm rounded-3 overflow-hidden">
        <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between">
            <h6 class="mb-0 fw-semibold text-dark d-flex align-items-center gap-2">
                <i class="bx bx-file text-secondary"></i> Log Activity Stream: <span class="text-primary font-monospace">{{ $selectedFile ?? 'None' }}</span>
            </h6>
            <span class="badge bg-light text-muted border px-2 py-1 extra-small">
                Showing {{ count($entries) }} entries (Latest First)
            </span>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="paymentLogsTable">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-3 py-3 fw-semibold text-muted" style="width: 15%;">Timestamp</th>
                            <th class="py-3 fw-semibold text-muted" style="width: 10%;">Level</th>
                            <th class="py-3 fw-semibold text-muted" style="width: 18%;">Action</th>
                            <th class="py-3 fw-semibold text-muted" style="width: 45%;">Message / Details</th>
                            <th class="pe-3 py-3 text-end fw-semibold text-muted" style="width: 12%;">Context JSON</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($entries as $index => $entry)
                            @php
                                $badgeClass = match($entry['level']) {
                                    'ERROR'   => 'bg-danger text-white',
                                    'WARNING' => 'bg-warning text-dark',
                                    default   => 'bg-info text-white'
                                };
                                $actionBadge = match($entry['action']) {
                                    'PAYMENT_SUCCESS', 'CME_PAYMENT_SUCCESS' => 'bg-success text-white',
                                    'PAYMENT_FAILED', 'CME_PAYMENT_FAILED'   => 'bg-danger text-white',
                                    'ORDER_CREATED', 'CME_ORDER_CREATED'     => 'bg-primary text-white',
                                    'CALLBACK_RECEIVED', 'CALLBACK_DECRYPTED'=> 'bg-secondary text-white',
                                    default => 'bg-light text-dark border'
                                };
                            @endphp
                            <tr>
                                <td class="ps-3 font-monospace text-muted" style="font-size: 0.8rem; white-space: nowrap;">
                                    {{ $entry['timestamp'] }}
                                </td>
                                <td>
                                    <span class="badge {{ $badgeClass }} px-2 py-1 extra-small fw-bold">
                                        {{ $entry['level'] }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge {{ $actionBadge }} px-2 py-1 extra-small fw-semibold">
                                        {{ $entry['action'] }}
                                    </span>
                                </td>
                                <td>
                                    <div class="fw-medium text-dark text-break" style="font-size: 0.85rem;">
                                        {{ $entry['message'] }}
                                    </div>
                                    @if(!empty($entry['context']['orderRef']))
                                        <small class="text-muted font-monospace extra-small d-block mt-0.5">
                                            Ref: <strong>{{ $entry['context']['orderRef'] }}</strong>
                                            @if(!empty($entry['context']['sbiOrderRef']) && $entry['context']['sbiOrderRef'] !== 'N/A')
                                                | SBI Ref: {{ $entry['context']['sbiOrderRef'] }}
                                            @endif
                                            @if(!empty($entry['context']['amount']))
                                                | ₹{{ number_format((float)$entry['context']['amount'], 2) }}
                                            @endif
                                        </small>
                                    @endif
                                </td>
                                <td class="pe-3 text-end">
                                    @if(!empty($entry['context']))
                                        <button type="button" class="btn btn-sm btn-outline-secondary px-2 py-0.5 extra-small" data-bs-toggle="modal" data-bs-target="#contextModal{{ $index }}">
                                            <i class="bx bx-code-alt me-0.5"></i> JSON
                                        </button>

                                        <!-- Modal for JSON Context -->
                                        <div class="modal fade text-start" id="contextModal{{ $index }}" tabindex="-1" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered modal-lg">
                                                <div class="modal-content border-0 shadow">
                                                    <div class="modal-header py-2.5 bg-dark text-white">
                                                        <h6 class="modal-title text-white mb-0 fs-6 d-flex align-items-center gap-2">
                                                            <i class="bx bx-code-curly text-info"></i> {{ $entry['action'] }} - Payload Context
                                                        </h6>
                                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body p-3 bg-light">
                                                        <div class="mb-2 d-flex justify-content-between align-items-center">
                                                            <small class="text-muted font-monospace">{{ $entry['timestamp'] }} | {{ $entry['level'] }}</small>
                                                            <button class="btn btn-xs btn-outline-primary copy-btn" onclick="navigator.clipboard.writeText(this.nextElementSibling.innerText); this.innerText='Copied!'; setTimeout(()=>this.innerText='Copy', 1500);">Copy</button>
                                                            <span style="display:none;">{{ json_encode($entry['context'], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</span>
                                                        </div>
                                                        <pre class="p-3 bg-white border rounded-2 text-dark font-monospace extra-small mb-0" style="max-height: 400px; overflow-y: auto;"><code>{{ json_encode($entry['context'], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</code></pre>
                                                    </div>
                                                    <div class="modal-footer py-2">
                                                        <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Close</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <span class="text-muted extra-small">—</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5">
                                    <div class="text-muted">
                                        <i class="bx bx-file-blank fs-1 d-block mb-2 text-secondary"></i>
                                        <h6 class="text-secondary fw-semibold">No Log Entries Found</h6>
                                        <p class="extra-small mb-0 text-muted">
                                            @if($search || $level)
                                                No results match your active search / level filter.
                                            @else
                                                Payment log file is currently empty or does not contain events yet.
                                            @endif
                                        </p>
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
