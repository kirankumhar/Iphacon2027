@extends('admin.layouts.main')

@section('admin-content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="d-flex align-items-center justify-content-between py-3 mb-3">
        <h5 class="mb-0">
            <span class="invert-text-white"><i class="bx bx-file-find me-2 text-info"></i>Abstract Submissions</span>
        </h5>
        <span class="badge bg-info rounded-pill px-3 py-2 fs-7 fw-bold shadow-xs">
            Total Abstracts: {{ $totalAbstracts }}
        </span>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show p-3 mb-3 rounded-3" role="alert">
            <div class="d-flex align-items-center gap-2">
                <i class="bx bx-check-circle fs-4"></i>
                <div>{{ session('success') }}</div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Statistics Cards Grid -->
    <div class="row g-3 mb-4">
        <div class="col-sm-6 col-lg-2">
            <div class="card border-0 shadow-sm rounded-3 bg-white border-start border-4 border-primary">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <small class="text-muted fw-bold text-uppercase d-block" style="font-size: 0.7rem;">Total</small>
                            <h4 class="fw-bold mb-0 text-dark">{{ $totalAbstracts }}</h4>
                        </div>
                        <div class="avatar avatar-sm bg-label-primary rounded-circle p-2">
                            <i class="bx bx-file fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-2">
            <div class="card border-0 shadow-sm rounded-3 bg-white border-start border-4 border-warning">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <small class="text-muted fw-bold text-uppercase d-block" style="font-size: 0.7rem;">Submitted</small>
                            <h4 class="fw-bold mb-0 text-warning">{{ $submittedCount }}</h4>
                        </div>
                        <div class="avatar avatar-sm bg-label-warning rounded-circle p-2">
                            <i class="bx bx-send fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-2">
            <div class="card border-0 shadow-sm rounded-3 bg-white border-start border-4 border-success">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <small class="text-muted fw-bold text-uppercase d-block" style="font-size: 0.7rem;">Accepted</small>
                            <h4 class="fw-bold mb-0 text-success">{{ $acceptedCount }}</h4>
                        </div>
                        <div class="avatar avatar-sm bg-label-success rounded-circle p-2">
                            <i class="bx bx-check-circle fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-2">
            <div class="card border-0 shadow-sm rounded-3 bg-white border-start border-4 border-danger">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <small class="text-muted fw-bold text-uppercase d-block" style="font-size: 0.7rem;">Rejected</small>
                            <h4 class="fw-bold mb-0 text-danger">{{ $rejectedCount }}</h4>
                        </div>
                        <div class="avatar avatar-sm bg-label-danger rounded-circle p-2">
                            <i class="bx bx-x-circle fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-2">
            <div class="card border-0 shadow-sm rounded-3 bg-white border-start border-4 border-info">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <small class="text-muted fw-bold text-uppercase d-block" style="font-size: 0.7rem;">Oral</small>
                            <h4 class="fw-bold mb-0 text-info">{{ $oralCount }}</h4>
                        </div>
                        <div class="avatar avatar-sm bg-label-info rounded-circle p-2">
                            <i class="bx bx-microphone fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-2">
            <div class="card border-0 shadow-sm rounded-3 bg-white border-start border-4 border-secondary">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <small class="text-muted fw-bold text-uppercase d-block" style="font-size: 0.7rem;">Poster</small>
                            <h4 class="fw-bold mb-0 text-secondary">{{ $posterCount }}</h4>
                        </div>
                        <div class="avatar avatar-sm bg-label-secondary rounded-circle p-2">
                            <i class="bx bx-image fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Abstracts Table Card -->
    <div class="card border-0 shadow-sm rounded-3">
        <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between flex-wrap gap-2">
            <h6 class="mb-0 fw-bold text-dark"><i class="bx bx-list-ul me-1.5 text-primary"></i>Submitted Abstracts List</h6>
            <div class="d-flex align-items-center gap-2 flex-wrap">
                <select id="filterStatus" class="form-select form-select-sm" style="width: 160px;">
                    <option value="">All Statuses</option>
                    <option value="Draft">Draft</option>
                    <option value="Submitted">Submitted</option>
                    <option value="Under Review">Under Review</option>
                    <option value="Accepted">Accepted</option>
                    <option value="Rejected">Rejected</option>
                    <option value="Reverted">Reverted</option>
                </select>
                <select id="filterMode" class="form-select form-select-sm" style="width: 180px;">
                    <option value="">All Modes</option>
                    <option value="Oral Presentation">Oral Presentation</option>
                    <option value="Poster Presentation">Poster Presentation</option>
                </select>
            </div>
        </div>
        <div class="card-body p-3">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 w-100" id="abstractsTable">
                    <thead class="table-light">
                        <tr>
                            <th>Ack ID</th>
                            <th>Author Info</th>
                            <th>Abstract Title</th>
                            <th>Mode & Theme</th>
                            <th>Status</th>
                            <th>Submitted Date</th>
                            <th class="text-end">Action</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    var table = $('#abstractsTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "{{ route('admin.abstracts.data') }}",
            type: "POST",
            data: function(d) {
                d._token = "{{ csrf_token() }}";
                d.status = $('#filterStatus').val();
                d.presentation_mode = $('#filterMode').val();
            }
        },
        columns: [
            { 
                data: 'acknowledgement_id', 
                name: 'acknowledgement_id',
                render: function(data) {
                    return '<span class="fw-bold text-primary">' + (data ? data : 'N/A') + '</span>';
                }
            },
            { 
                data: 'presenting_author_name', 
                name: 'presenting_author_name',
                render: function(data, type, row) {
                    var email = row.presenting_author_email || '';
                    return '<div><strong class="text-dark">' + data + '</strong><br><small class="text-muted">' + email + '</small></div>';
                }
            },
            { 
                data: 'abstract_title', 
                name: 'abstract_title',
                render: function(data) {
                    if (!data) return '<span class="text-muted italic">No Title</span>';
                    return '<span class="text-truncate d-inline-block" style="max-width: 250px;" title="' + data + '">' + data + '</span>';
                }
            },
            { 
                data: 'presentation_mode', 
                name: 'presentation_mode',
                render: function(data, type, row) {
                    var theme = row.conference_theme || '';
                    return '<div><span class="badge bg-label-info">' + (data || 'N/A') + '</span><br><small class="text-muted">' + theme + '</small></div>';
                }
            },
            { 
                data: 'status', 
                name: 'status',
                render: function(data) {
                    var badgeClass = 'bg-secondary';
                    if (data === 'Accepted') badgeClass = 'bg-success';
                    else if (data === 'Rejected') badgeClass = 'bg-danger';
                    else if (data === 'Submitted') badgeClass = 'bg-warning text-dark';
                    else if (data === 'Under Review') badgeClass = 'bg-info';
                    else if (data === 'Reverted') badgeClass = 'bg-dark';
                    return '<span class="badge ' + badgeClass + '">' + (data || 'Draft') + '</span>';
                }
            },
            { 
                data: 'created_at', 
                name: 'created_at',
                render: function(data) {
                    if (!data) return '-';
                    return new Date(data).toLocaleDateString('en-GB', { day: '2-digit', month: 'short', year: 'numeric' });
                }
            },
            { 
                data: 'id', 
                orderable: false,
                searchable: false,
                className: 'text-end',
                render: function(data, type, row) {
                    var showUrl = "{{ route('admin.abstracts.show', ':id') }}".replace(':id', row.id);
                    return '<a href="' + showUrl + '" class="btn btn-sm btn-outline-primary rounded-pill"><i class="bx bx-show me-1"></i>View</a>';
                }
            }
        ],
        order: [[0, 'desc']]
    });

    $('#filterStatus, #filterMode').on('change', function() {
        table.draw();
    });
});
</script>
@endpush
