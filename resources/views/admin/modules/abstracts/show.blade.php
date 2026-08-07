@extends('admin.layouts.main')

@section('admin-content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="d-flex align-items-center justify-content-between py-3 mb-3">
        <div>
            <a href="{{ route('admin.abstracts.index') }}" class="btn btn-sm btn-outline-secondary rounded-pill me-2">
                <i class="bx bx-arrow-back me-1"></i> Back to List
            </a>
            <h5 class="d-inline mb-0">
                Abstract Details: <span class="text-primary">{{ $abstract->acknowledgement_id }}</span>
            </h5>
        </div>
        <div>
            @php
                $badgeClass = 'bg-secondary';
                if ($abstract->status === 'Accepted') $badgeClass = 'bg-success';
                elseif ($abstract->status === 'Rejected') $badgeClass = 'bg-danger';
                elseif ($abstract->status === 'Submitted') $badgeClass = 'bg-warning text-dark';
                elseif ($abstract->status === 'Under Review') $badgeClass = 'bg-info';
            @endphp
            <span class="badge {{ $badgeClass }} fs-6 px-3 py-2 rounded-pill shadow-xs">
                {{ $abstract->status }}
            </span>
        </div>
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

    <div class="row g-4">
        <!-- Main Content (Abstract Details) -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-3 mb-4">
                <div class="card-header bg-white py-3 border-bottom">
                    <h6 class="mb-0 fw-bold text-dark"><i class="bx bx-file me-2 text-primary"></i>Abstract Title & Metadata</h6>
                </div>
                <div class="card-body p-4">
                    <h5 class="fw-bold text-dark mb-3">{{ $abstract->abstract_title ?: 'Untitled Abstract' }}</h5>
                    
                    <div class="row g-3 mb-4 bg-light p-3 rounded-3">
                        <div class="col-md-6">
                            <small class="text-muted d-block fw-bold">Presentation Mode</small>
                            <span class="badge bg-label-info fs-7">{{ $abstract->presentation_mode }}</span>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted d-block fw-bold">Conference Theme</small>
                            <span class="fw-semibold text-dark">{{ $abstract->conference_theme }}</span>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted d-block fw-bold">Presenter Category</small>
                            <span class="text-dark">{{ $abstract->presenter_category }}</span>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted d-block fw-bold">Keywords</small>
                            <span class="text-dark">{{ $abstract->keywords }}</span>
                        </div>
                    </div>

                    <hr class="my-4">

                    <!-- Structured Abstract Sections -->
                    <div class="abstract-body">
                        <div class="mb-3">
                            <h6 class="fw-bold text-primary">Background:</h6>
                            <p class="text-secondary">{{ $abstract->abstract_background ?: 'N/A' }}</p>
                        </div>
                        <div class="mb-3">
                            <h6 class="fw-bold text-primary">Objectives:</h6>
                            <p class="text-secondary">{{ $abstract->abstract_objectives ?: 'N/A' }}</p>
                        </div>
                        <div class="mb-3">
                            <h6 class="fw-bold text-primary">Methodology:</h6>
                            <p class="text-secondary">{{ $abstract->abstract_methodology ?: 'N/A' }}</p>
                        </div>
                        <div class="mb-3">
                            <h6 class="fw-bold text-primary">Results:</h6>
                            <p class="text-secondary">{{ $abstract->abstract_results ?: 'N/A' }}</p>
                        </div>
                        <div class="mb-3">
                            <h6 class="fw-bold text-primary">Conclusion:</h6>
                            <p class="text-secondary">{{ $abstract->abstract_conclusion ?: 'N/A' }}</p>
                        </div>
                    </div>

                    @if ($abstract->attachment_path)
                        <div class="mt-4 p-3 bg-label-secondary rounded-3 d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center gap-2">
                                <i class="bx bx-paperclip fs-4 text-primary"></i>
                                <span class="fw-bold text-dark">Attachment File</span>
                            </div>
                            <a href="{{ asset('storage/' . $abstract->attachment_path) }}" target="_blank" class="btn btn-sm btn-primary rounded-pill">
                                <i class="bx bx-download me-1"></i> Download File
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Co-Authors Card if any -->
            @if (!empty($abstract->co_authors) && is_array($abstract->co_authors))
                <div class="card border-0 shadow-sm rounded-3 mb-4">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h6 class="mb-0 fw-bold text-dark"><i class="bx bx-group me-2 text-primary"></i>Co-Authors</h6>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Name</th>
                                        <th>Designation</th>
                                        <th>Department / Institution</th>
                                        <th>Email</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($abstract->co_authors as $author)
                                        <tr>
                                            <td class="fw-bold text-dark">{{ $author['name'] ?? '-' }}</td>
                                            <td>{{ $author['designation'] ?? '-' }}</td>
                                            <td>{{ $author['department'] ?? '-' }} ({{ $author['institution'] ?? '-' }})</td>
                                            <td>{{ $author['email'] ?? '-' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Sidebar: Author Info & Status Review Form -->
        <div class="col-lg-4">
            <!-- Author Info -->
            <div class="card border-0 shadow-sm rounded-3 mb-4">
                <div class="card-header bg-white py-3 border-bottom">
                    <h6 class="mb-0 fw-bold text-dark"><i class="bx bx-user me-2 text-primary"></i>Presenting Author</h6>
                </div>
                <div class="card-body p-4">
                    <div class="mb-3">
                        <small class="text-muted d-block">Full Name</small>
                        <strong class="fs-6 text-dark">{{ $abstract->presenting_author_name }}</strong>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted d-block">Email & Mobile</small>
                        <span class="text-dark d-block">{{ $abstract->presenting_author_email }}</span>
                        <span class="text-dark d-block">{{ $abstract->presenting_author_mobile }}</span>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted d-block">Institution & Department</small>
                        <span class="text-dark d-block">{{ $abstract->presenting_author_department }}, {{ $abstract->presenting_author_institution }}</span>
                    </div>
                    <div class="mb-0">
                        <small class="text-muted d-block">Location</small>
                        <span class="text-dark">{{ $abstract->presenting_author_city }}, {{ $abstract->presenting_author_state }}, {{ $abstract->presenting_author_country }}</span>
                    </div>
                </div>
            </div>

            <!-- Status Review Form -->
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-header bg-white py-3 border-bottom">
                    <h6 class="mb-0 fw-bold text-dark"><i class="bx bx-edit me-2 text-warning"></i>Update Review Status</h6>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('admin.abstracts.update-status', $abstract->id) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="status" class="form-label fw-bold">Abstract Status</label>
                            <select name="status" id="status" class="form-select" required>
                                <option value="Draft" {{ $abstract->status === 'Draft' ? 'selected' : '' }}>Draft</option>
                                <option value="Submitted" {{ $abstract->status === 'Submitted' ? 'selected' : '' }}>Submitted</option>
                                <option value="Under Review" {{ $abstract->status === 'Under Review' ? 'selected' : '' }}>Under Review</option>
                                <option value="Accepted" {{ $abstract->status === 'Accepted' ? 'selected' : '' }}>Accepted</option>
                                <option value="Rejected" {{ $abstract->status === 'Rejected' ? 'selected' : '' }}>Rejected</option>
                                <option value="Reverted" {{ $abstract->status === 'Reverted' ? 'selected' : '' }}>Reverted</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="review_comments" class="form-label fw-bold">Review Comments / Notes</label>
                            <textarea name="review_comments" id="review_comments" rows="4" class="form-control" placeholder="Enter comments or reasons for reviewer decision...">{{ $abstract->review_comments }}</textarea>
                        </div>
                        <button type="submit" class="btn btn-primary w-100 rounded-pill fw-bold">
                            <i class="bx bx-save me-1"></i> Update Decision
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
