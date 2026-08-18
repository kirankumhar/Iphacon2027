@extends('admin.layouts.main')
@section('admin-content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
            <h5 class="mb-0"><span class="text-muted fw-light">System /</span> Activity Logs</h5>
            <span class="badge bg-label-primary px-3 py-2 rounded-pill fs-7">
                Total Logs: {{ $activities->total() }}
            </span>
        </div>

        <div class="card mb-4 border-0 shadow-sm rounded-3">
            <div class="card-header bg-white py-3 border-bottom">
                <form method="GET" action="{{ route('admin.activity-log') }}" class="row g-2 align-items-center">
                    <div class="col-12 col-md-5">
                        <div class="input-group input-group-merge">
                            <span class="input-group-text"><i class="bx bx-search"></i></span>
                            <input type="text" name="search" class="form-control" placeholder="Search by user, action, description, IP..." value="{{ request('search') }}">
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <select name="type" class="form-select">
                            <option value="">All Types (User / Admin)</option>
                            <option value="Admin" {{ request('type') === 'Admin' ? 'selected' : '' }}>Admin Only</option>
                            <option value="User" {{ request('type') === 'User' ? 'selected' : '' }}>Delegate Only</option>
                        </select>
                    </div>
                    <div class="col-6 col-md-2">
                        <select name="per_page" class="form-select">
                            <option value="20" {{ request('per_page', 20) == 20 ? 'selected' : '' }}>20 per page</option>
                            <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50 per page</option>
                            <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100 per page</option>
                        </select>
                    </div>
                    <div class="col-12 col-md-2 d-flex gap-2">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bx bx-filter-alt me-1"></i>Filter
                        </button>
                        @if(request()->hasAny(['search', 'type', 'per_page']))
                            <a href="{{ route('admin.activity-log') }}" class="btn btn-outline-secondary" title="Reset Filters">
                                <i class="bx bx-reset"></i>
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            <div class="card-body p-0">
                <div class="table-responsive text-nowrap">
                    <table class="table table-hover table-striped align-middle w-100 mb-0">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 60px;">#</th>
                                <th>User</th>
                                <th>Type</th>
                                <th>Action</th>
                                <th>Description</th>
                                <th>IP Address</th>
                                <th>Date & Time</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($activities as $activity)
                                <tr>
                                    <td>{{ $activities->firstItem() + $loop->index }}</td>
                                    <td>
                                        <div class="fw-bold text-dark">
                                            {{ $activity->subject_name ?: ($activity->user?->full_name ?? ($activity->admin?->full_name ?? $activity->admin?->username ?? 'System')) }}
                                        </div>
                                        @if($activity->user?->email)
                                            <small class="text-muted">{{ $activity->user->email }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        @if($activity->user_type === 'Admin')
                                            <span class="badge bg-label-danger rounded-pill px-2.5 py-1">Admin</span>
                                        @elseif($activity->user_type === 'User')
                                            <span class="badge bg-label-primary rounded-pill px-2.5 py-1">Delegate</span>
                                        @else
                                            <span class="badge bg-label-secondary rounded-pill px-2.5 py-1">{{ $activity->user_type ?: 'System' }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-label-info font-monospace" style="font-size: 0.75rem;">
                                            {{ $activity->action }}
                                        </span>
                                    </td>
                                    <td style="max-width: 320px; white-space: normal;">
                                        <span class="text-secondary small">{{ $activity->description }}</span>
                                    </td>
                                    <td>
                                        <span class="font-monospace small text-muted">{{ $activity->ip_address ?: 'N/A' }}</span>
                                    </td>
                                    <td>
                                        <small class="text-muted">{{ $activity->created_at ? $activity->created_at->format('d M, Y h:i A') : 'N/A' }}</small>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-5 text-muted">
                                        <i class="bx bx-info-circle fs-2 d-block mb-2 text-secondary"></i>
                                        No activity logs found matching the criteria.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            @if($activities->hasPages() || $activities->total() > 0)
                <div class="card-footer bg-white border-top py-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <small class="text-muted">
                        Showing <strong>{{ $activities->firstItem() ?? 0 }}</strong> to <strong>{{ $activities->lastItem() ?? 0 }}</strong> of <strong>{{ $activities->total() }}</strong> entries
                    </small>
                    <div>
                        {{ $activities->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection