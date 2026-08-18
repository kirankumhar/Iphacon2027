@extends('admin.layouts.main')
@section('admin-content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h5 class="py-3 mb-4"><span class="text-muted fw-light">System /</span> Activity Logs</h5>
        <div class="card mb-4 border-0 shadow-sm rounded-3">
            <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between">
                <h6 class="mb-0 fw-bold text-dark d-flex align-items-center gap-2">
                    <i class="bx bx-history text-primary fs-5"></i> System Activity Logs
                </h6>
                <span class="badge bg-label-primary px-3 py-1.5 rounded-pill">Total: {{ count($activities) }}</span>
            </div>
            <div class="card-body p-4">
                <div class="table-responsive">
                    <table id="activityLogTable" class="table table-hover table-striped align-middle w-100">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 50px;">#</th>
                                <th>Actor / User</th>
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
                                    <td>{{ $loop->iteration }}</td>
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
                                    <td style="max-width: 320px;">
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
                                    <td colspan="7" class="text-center py-4 text-muted">
                                        <i class="bx bx-info-circle fs-3 d-block mb-1"></i>
                                        No activity logs found.
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
@push('script')
    <script>
        $(document).ready(function() {
            if ($.fn.DataTable) {
                $('#activityLogTable').DataTable({
                    "paging": true,
                    "lengthChange": true,
                    "searching": true,
                    "ordering": true,
                    "info": true,
                    "autoWidth": false,
                    "responsive": true,
                    "order": [[6, "desc"]]
                });
            }
        });
    </script>
@endpush