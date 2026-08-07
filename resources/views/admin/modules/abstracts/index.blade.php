@extends('admin.layouts.main')

@section('admin-content')
<div class="container-xxl flex-grow-1 mt-3.5 mb-4">
    <!-- Top Title Bar -->
    <div class="d-flex align-items-center justify-content-between py-2 mb-3">
        <h5 class="mb-0 fw-bold text-dark">
            <i class="bx bx-file-find me-2 text-info fs-4"></i>Abstract Submissions
        </h5>
        <span class="badge bg-primary rounded-pill px-3.5 py-2 fs-7 fw-bold shadow-xs">
            Total Abstracts: {{ number_format($totalAbstracts) }}
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
    <div class="card border-0 shadow-sm rounded-3 overflow-hidden">
        <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between flex-wrap gap-2">
            <h6 class="mb-0 fw-bold text-dark"><i class="bx bx-list-ul me-1.5 text-primary"></i>Submitted Abstracts List</h6>
            
            <!-- Filters Form -->
            <form method="GET" action="{{ route('admin.abstracts.index') }}" class="d-flex align-items-center gap-2 flex-wrap m-0">
                <div class="input-group input-group-sm" style="width: 200px;">
                    <input type="text" name="search" class="form-control" placeholder="Search..." value="{{ request('search') }}">
                    <button class="btn btn-outline-secondary" type="submit"><i class="bx bx-search"></i></button>
                </div>
                <select name="status" class="form-select form-select-sm" style="width: 150px;" onchange="this.form.submit()">
                    <option value="">All Statuses</option>
                    <option value="Draft" {{ request('status') === 'Draft' ? 'selected' : '' }}>Draft</option>
                    <option value="Submitted" {{ request('status') === 'Submitted' ? 'selected' : '' }}>Submitted</option>
                    <option value="Under Review" {{ request('status') === 'Under Review' ? 'selected' : '' }}>Under Review</option>
                    <option value="Accepted" {{ request('status') === 'Accepted' ? 'selected' : '' }}>Accepted</option>
                    <option value="Rejected" {{ request('status') === 'Rejected' ? 'selected' : '' }}>Rejected</option>
                    <option value="Reverted" {{ request('status') === 'Reverted' ? 'selected' : '' }}>Reverted</option>
                </select>
                <select name="presentation_mode" class="form-select form-select-sm" style="width: 170px;" onchange="this.form.submit()">
                    <option value="">All Modes</option>
                    <option value="Oral Presentation" {{ request('presentation_mode') === 'Oral Presentation' ? 'selected' : '' }}>Oral Presentation</option>
                    <option value="Poster Presentation" {{ request('presentation_mode') === 'Poster Presentation' ? 'selected' : '' }}>Poster Presentation</option>
                </select>
                @if(request('status') || request('presentation_mode') || request('search'))
                    <a href="{{ route('admin.abstracts.index') }}" class="btn btn-sm btn-light border text-danger" title="Reset Filters">
                        <i class="bx bx-x"></i> Clear
                    </a>
                @endif
            </form>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 w-100" id="abstractsTable">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4">Ack ID</th>
                            <th>Author Info</th>
                            <th>Abstract Title</th>
                            <th>Mode & Theme</th>
                            <th>Status</th>
                            <th>Submitted Date</th>
                            <th class="text-end pe-4">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($abstracts as $item)
                            <tr>
                                <td class="ps-4">
                                    <span class="fw-bold text-primary font-monospace" style="font-size: 0.88rem;">
                                        {{ $item->acknowledgement_id ?: 'Draft' }}
                                    </span>
                                </td>
                                <td>
                                    <div>
                                        <strong class="text-dark d-block" style="font-size: 0.88rem;">{{ $item->presenting_author_name }}</strong>
                                        <small class="text-muted extra-small">{{ $item->presenting_author_email }}</small>
                                    </div>
                                </td>
                                <td>
                                    @if($item->abstract_title)
                                        <span class="text-truncate d-inline-block fw-semibold text-dark" style="max-width: 280px;" title="{{ $item->abstract_title }}">
                                            {{ $item->abstract_title }}
                                        </span>
                                    @else
                                        <span class="text-muted fst-italic">Untitled Abstract</span>
                                    @endif
                                </td>
                                <td>
                                    <div>
                                        <span class="badge bg-label-info text-info border border-info border-opacity-25 px-2.5 py-1 rounded-2" style="font-size: 0.75rem;">
                                            <i class="bx bx-microphone me-1"></i>{{ $item->presentation_mode ?: 'N/A' }}
                                        </span>
                                        @if($item->conference_theme)
                                            <br><small class="text-muted extra-small"><i class="bx bx-tag me-0.5"></i>{{ $item->conference_theme }}</small>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    @php
                                        $badgeClass = 'bg-secondary';
                                        if ($item->status === 'Accepted') $badgeClass = 'bg-success';
                                        elseif ($item->status === 'Rejected') $badgeClass = 'bg-danger';
                                        elseif ($item->status === 'Submitted') $badgeClass = 'bg-warning text-dark';
                                        elseif ($item->status === 'Under Review') $badgeClass = 'bg-info text-white';
                                        elseif ($item->status === 'Reverted') $badgeClass = 'bg-dark';
                                    @endphp
                                    <span class="badge {{ $badgeClass }} px-2.5 py-1 rounded-pill fw-bold" style="font-size: 0.75rem;">
                                        {{ $item->status ?: 'Draft' }}
                                    </span>
                                </td>
                                <td>
                                    <small class="text-muted extra-small">
                                        {{ $item->created_at ? $item->created_at->format('d M Y') : '-' }}
                                    </small>
                                </td>
                                <td class="text-end pe-4">
                                    <a href="{{ route('admin.abstracts.show', $item->id) }}" class="btn btn-sm btn-outline-primary rounded-pill px-3 py-1 fw-bold" style="font-size: 0.78rem;">
                                        <i class="bx bx-show me-1"></i>View
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5">
                                    <div class="text-muted">
                                        <i class="bx bx-folder-open fs-1 mb-2 text-secondary"></i>
                                        <p class="mb-0 fw-semibold">No abstracts found in database.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($abstracts->hasPages())
                <div class="px-4 py-3 border-top d-flex justify-content-end">
                    {{ $abstracts->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
