@extends('admin.layouts.main')
@section('admin-content')
    <div class="container-xxl flex-grow-1 ">
        <h6 class="py-3 mb-4"><span class="invert-text-white">Activity Log</span>
        </h6>
        <div class="card mb-4">
            <h5 class="card-header text-white bg-info">Activity Log</h5>
            <div class="card-body mt-2">
                <div class="table-responsive">
                    <table id="activityLogTable" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>User</th>
                                <th>Activity</th>
                                <th>URL</th>
                                <th>IP Address</th>
                                <th>User Agent</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($activities as $activity)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $activity->user_name }}</td>
                                    <td>{{ $activity->activity }}</td>
                                    <td>{{ $activity->url }}</td>
                                    <td>{{ $activity->ip_address }}</td>
                                    <td>{{ $activity->user_agent }}</td>
                                    <td>{{ $activity->created_at->format('Y-m-d H:i:s') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center">No activity logs found</td>
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
            $('#activityLogTable').DataTable({
                "paging": true,
                "lengthChange": true,
                "searching": true,
                "ordering": true,
                "info": true,
                "autoWidth": true,
                "responsive": true
            });
        });
    </script>
@endpush