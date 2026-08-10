@extends('admin.layouts.main')

@section('admin-content')
<div class="container-xxl flex-grow-1 container-p-y">
    <!-- Top Action Bar -->
    <div class="d-flex align-items-center justify-content-between py-3 mb-3">
        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('admin.abstracts.index') }}" class="btn btn-sm btn-outline-secondary rounded-pill me-1">
                <i class="bx bx-arrow-back me-1"></i> Back to List
            </a>
            <h5 class="mb-0 fw-bold text-dark">
                Abstract Details: <span class="text-primary font-monospace">{{ $abstract->acknowledgement_id }}</span>
            </h5>
        </div>
        <div>
            @if($abstract->status === 'Accepted' && $abstract->presentation_mode === 'Oral Presentation')
                <span class="badge rounded-pill px-3 py-2 fw-bold shadow-xs" style="background-color: #059669 !important; color: #FFFFFF !important; font-size: 0.85rem;">
                    <i class="bx bx-microphone me-1" style="color: #FFFFFF !important;"></i> ACCEPTED FOR ORAL
                </span>
            @elseif($abstract->status === 'Accepted')
                <span class="badge rounded-pill px-3 py-2 fw-bold shadow-xs" style="background-color: #0288D1 !important; color: #FFFFFF !important; font-size: 0.85rem;">
                    <i class="bx bx-file me-1" style="color: #FFFFFF !important;"></i> ACCEPTED FOR PAPER
                </span>
            @elseif($abstract->status === 'Rejected')
                <span class="badge rounded-pill px-3 py-2 fw-bold shadow-xs" style="background-color: #DC2626 !important; color: #FFFFFF !important; font-size: 0.85rem;">
                    <i class="bx bx-x-circle me-1" style="color: #FFFFFF !important;"></i> REJECTED
                </span>
            @else
                <span class="badge bg-warning text-dark fs-6 px-3 py-2 rounded-pill shadow-xs fw-bold">
                    {{ $abstract->status ?: 'Submitted' }}
                </span>
            @endif
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show p-3 mb-4 rounded-3 shadow-xs border-0" role="alert" style="background-color: #ECFDF5; border-left: 4px solid #10B981 !important;">
            <div class="d-flex align-items-center gap-2">
                <i class="bx bx-check-circle fs-4 text-success"></i>
                <div class="fw-semibold text-dark">{{ session('success') }}</div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row g-4">
        <!-- LEFT COLUMN: Main Abstract Details & Text Content -->
        <div class="col-lg-8">
            <!-- 1. Abstract Title & Metadata -->
            <div class="card border-0 shadow-sm rounded-3 mb-4">
                <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between">
                    <h6 class="mb-0 fw-bold text-dark d-flex align-items-center gap-2">
                        <i class="bx bx-file text-primary fs-5" style="color: #0288D1 !important;"></i> Abstract Title & Submission Info
                    </h6>
                    <span class="badge bg-light text-secondary border rounded-pill extra-small px-3 py-1">
                        {{ $abstract->presenter_category ?: 'Abstract Submission' }}
                    </span>
                </div>
                <div class="card-body p-4">
                    <h5 class="fw-bold text-dark mb-4" style="line-height: 1.45; font-size: 1.15rem;">
                        {{ $abstract->abstract_title ?: 'Untitled Abstract' }}
                    </h5>
                    
                    <div class="row g-3 mb-4 bg-light p-3.5 rounded-3 border">
                        <div class="col-md-6">
                            <small class="text-muted d-block extra-small fw-bold text-uppercase mb-1">Preferred Presentation Mode</small>
                            <span class="badge bg-label-info text-info border border-info border-opacity-25 px-2.5 py-1 rounded-pill" style="font-size: 0.78rem;">
                                <i class="bx bx-microphone me-1"></i>{{ $abstract->presentation_mode }}
                            </span>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted d-block extra-small fw-bold text-uppercase mb-1">Conference Theme</small>
                            <span class="fw-semibold text-dark d-block small">{{ $abstract->conference_theme ?: 'N/A' }}</span>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted d-block extra-small fw-bold text-uppercase mb-1">Presenter Category</small>
                            <span class="text-dark small d-block">{{ $abstract->presenter_category ?: 'N/A' }}</span>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted d-block extra-small fw-bold text-uppercase mb-1">Keywords</small>
                            <span class="text-dark small d-block">{{ $abstract->keywords ?: 'N/A' }}</span>
                        </div>
                    </div>

                    <hr class="my-4">

                    <!-- Structured Abstract Text Content -->
                    <div class="abstract-body">
                        <div class="mb-4">
                            <h6 class="fw-bold text-primary mb-1.5"><i class="bx bx-book-open me-1"></i>Background:</h6>
                            <p class="text-secondary small mb-0" style="line-height: 1.65; font-size: 0.9rem;">{{ $abstract->abstract_background ?: 'N/A' }}</p>
                        </div>
                        <div class="mb-4">
                            <h6 class="fw-bold text-primary mb-1.5"><i class="bx bx-target-lock me-1"></i>Objectives:</h6>
                            <p class="text-secondary small mb-0" style="line-height: 1.65; font-size: 0.9rem;">{{ $abstract->abstract_objectives ?: 'N/A' }}</p>
                        </div>
                        <div class="mb-4">
                            <h6 class="fw-bold text-primary mb-1.5"><i class="bx bx-cog me-1"></i>Methodology:</h6>
                            <p class="text-secondary small mb-0" style="line-height: 1.65; font-size: 0.9rem;">{{ $abstract->abstract_methodology ?: 'N/A' }}</p>
                        </div>
                        <div class="mb-4">
                            <h6 class="fw-bold text-primary mb-1.5"><i class="bx bx-bar-chart-alt-2 me-1"></i>Results:</h6>
                            <p class="text-secondary small mb-0" style="line-height: 1.65; font-size: 0.9rem;">{{ $abstract->abstract_results ?: 'N/A' }}</p>
                        </div>
                        <div class="mb-3">
                            <h6 class="fw-bold text-primary mb-1.5"><i class="bx bx-check-square me-1"></i>Conclusion:</h6>
                            <p class="text-secondary small mb-0" style="line-height: 1.65; font-size: 0.9rem;">{{ $abstract->abstract_conclusion ?: 'N/A' }}</p>
                        </div>
                    </div>

                    @if ($abstract->attachment_path)
                        <div class="mt-4 p-3 bg-light border rounded-3 d-flex align-items-center justify-content-between flex-wrap gap-2">
                            <div class="d-flex align-items-center gap-2.5">
                                <i class="bx bx-paperclip fs-3" style="color: #0288D1 !important;"></i>
                                <div>
                                    <span class="fw-bold text-dark d-block small">Attachment Document</span>
                                    <small class="text-muted extra-small">PDF / File uploaded by presenter</small>
                                </div>
                            </div>
                            <a href="{{ asset('storage/' . $abstract->attachment_path) }}" target="_blank" class="btn btn-sm btn-primary rounded-pill px-3 py-1.5 fw-bold" style="background-color: #0288D1 !important; border: none;">
                                <i class="bx bx-download me-1"></i> Download Attachment
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <!-- 2. Co-Authors Table Card -->
            @if (!empty($abstract->co_authors) && is_array($abstract->co_authors))
                <div class="card border-0 shadow-sm rounded-3 mb-4">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h6 class="mb-0 fw-bold text-dark d-flex align-items-center gap-2">
                            <i class="bx bx-group me-1 text-primary"></i> Co-Authors List
                        </h6>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="ps-3 extra-small fw-bold text-uppercase">Name</th>
                                        <th class="extra-small fw-bold text-uppercase">Designation</th>
                                        <th class="extra-small fw-bold text-uppercase">Department / Institution</th>
                                        <th class="pe-3 extra-small fw-bold text-uppercase">Email</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($abstract->co_authors as $author)
                                        <tr>
                                            <td class="ps-3 fw-bold text-dark small">{{ $author['name'] ?? '-' }}</td>
                                            <td class="small">{{ $author['designation'] ?? '-' }}</td>
                                            <td class="small">{{ $author['department'] ?? '-' }} ({{ $author['institution'] ?? '-' }})</td>
                                            <td class="pe-3 small text-primary">{{ $author['email'] ?? '-' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- RIGHT COLUMN: Author Info, Moderation Audit Log & Evaluation Desk -->
        <div class="col-lg-4">
            <!-- 1. Presenting Author Information Card -->
            <div class="card border-0 shadow-sm rounded-3 mb-4">
                <div class="card-header bg-white py-3 border-bottom">
                    <h6 class="mb-0 fw-bold text-dark d-flex align-items-center gap-2">
                        <i class="bx bx-user text-primary" style="color: #0288D1 !important;"></i> Presenting Author
                    </h6>
                </div>
                <div class="card-body p-4">
                    <div class="mb-3">
                        <small class="text-muted d-block extra-small fw-bold text-uppercase">Full Name</small>
                        <strong class="fs-6 text-dark d-block mt-0.5">{{ $abstract->presenting_author_name }}</strong>
                        @if($abstract->presenting_author_designation)
                            <small class="text-muted extra-small d-block">{{ $abstract->presenting_author_designation }}</small>
                        @endif
                    </div>
                    <div class="mb-3">
                        <small class="text-muted d-block extra-small fw-bold text-uppercase">Email & Mobile</small>
                        <span class="text-dark d-block small mt-0.5"><i class="bx bx-envelope me-1 text-primary"></i>{{ $abstract->presenting_author_email }}</span>
                        <span class="text-dark d-block small"><i class="bx bx-phone me-1 text-success"></i>{{ $abstract->presenting_author_mobile }}</span>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted d-block extra-small fw-bold text-uppercase">Institution & Department</small>
                        <span class="text-dark d-block small mt-0.5">{{ $abstract->presenting_author_department ?: 'N/A' }}</span>
                        <small class="text-secondary d-block">{{ $abstract->presenting_author_institution }}</small>
                    </div>
                    <div class="mb-0">
                        <small class="text-muted d-block extra-small fw-bold text-uppercase">Location</small>
                        <span class="text-dark small d-block mt-0.5">{{ $abstract->presenting_author_city }}, {{ $abstract->presenting_author_state }}, {{ $abstract->presenting_author_country }}</span>
                        @if($abstract->medical_council_reg_no)
                            <small class="text-muted extra-small d-block mt-1">Medical Reg: {{ $abstract->medical_council_reg_no }}</small>
                        @endif
                    </div>
                </div>
            </div>

            <!-- 2. Moderation Audit Log & Decision Record -->
            <div class="card border-0 shadow-sm rounded-3 mb-4 overflow-hidden" style="border-left: 4px solid #0288D1 !important;">
                <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between">
                    <h6 class="mb-0 fw-bold text-dark d-flex align-items-center gap-2">
                        <i class="bx bx-history fs-5" style="color: #0288D1 !important;"></i> Moderation Audit Log
                    </h6>
                    @if($abstract->reviewed_at || in_array($abstract->status, ['Accepted', 'Rejected']))
                        <span class="badge bg-light text-success border border-success border-opacity-25 extra-small font-monospace fw-bold">EVALUATED</span>
                    @else
                        <span class="badge bg-warning bg-opacity-15 text-warning extra-small fw-bold">PENDING</span>
                    @endif
                </div>
                <div class="card-body p-3.5">
                    @if($abstract->reviewed_at || in_array($abstract->status, ['Accepted', 'Rejected']))
                        <!-- Decision Badge -->
                        <div class="mb-3 p-3 rounded-3" style="background-color: #F8FAFC; border: 1px solid #E2E8F0;">
                            <small class="text-muted extra-small d-block fw-bold text-uppercase mb-1.5" style="letter-spacing: 0.5px;">Current Decision</small>
                            @if($abstract->status === 'Accepted' && $abstract->presentation_mode === 'Oral Presentation')
                                <span class="badge rounded-pill shadow-xs d-inline-flex align-items-center gap-1" style="background-color: #059669 !important; color: #FFFFFF !important; font-weight: 700; font-size: 0.82rem; padding: 6px 14px;">
                                    <i class="bx bx-microphone" style="color: #FFFFFF !important;"></i> Accepted for Oral (OP)
                                </span>
                            @elseif($abstract->status === 'Accepted')
                                <span class="badge rounded-pill shadow-xs d-inline-flex align-items-center gap-1" style="background-color: #0288D1 !important; color: #FFFFFF !important; font-weight: 700; font-size: 0.82rem; padding: 6px 14px;">
                                    <i class="bx bx-file" style="color: #FFFFFF !important;"></i> Accepted for Paper (PP)
                                </span>
                            @elseif($abstract->status === 'Rejected')
                                <span class="badge rounded-pill shadow-xs d-inline-flex align-items-center gap-1" style="background-color: #DC2626 !important; color: #FFFFFF !important; font-weight: 700; font-size: 0.82rem; padding: 6px 14px;">
                                    <i class="bx bx-x-circle" style="color: #FFFFFF !important;"></i> Rejected
                                </span>
                            @else
                                <span class="badge bg-secondary text-white rounded-pill px-3 py-1 fw-bold">{{ $abstract->status }}</span>
                            @endif
                        </div>

                        <!-- Date & Time Row -->
                        <div class="mb-2 pb-2 border-bottom d-flex align-items-center justify-content-between">
                            <small class="text-muted extra-small fw-bold">Decision Date & Time:</small>
                            <span class="text-dark extra-small font-monospace fw-bold">
                                <i class="bx bx-calendar me-1" style="color: #0288D1 !important;"></i>{{ $abstract->reviewed_at ? $abstract->reviewed_at->format('d M Y, h:i A') : ($abstract->updated_at ? $abstract->updated_at->format('d M Y, h:i A') : 'N/A') }}
                            </span>
                        </div>

                        <!-- Evaluator Row -->
                        <div class="mb-3 pb-2 border-bottom d-flex align-items-center justify-content-between">
                            <small class="text-muted extra-small fw-bold">Evaluated By:</small>
                            <span class="text-dark extra-small fw-bold">
                                <i class="bx bx-user-check me-1" style="color: #059669 !important;"></i>{{ $abstract->reviewed_by ?: 'Content Moderator' }}
                            </span>
                        </div>

                        @if($abstract->review_comments)
                            <div class="p-2.5 rounded-3 bg-light border">
                                <small class="text-muted d-block extra-small fw-bold mb-1"><i class="bx bx-comment-detail me-1 text-warning"></i>Review Remarks:</small>
                                <p class="mb-0 extra-small text-dark" style="font-style: italic;">"{{ $abstract->review_comments }}"</p>
                            </div>
                        @endif
                    @else
                        <div class="text-center py-3 text-muted">
                            <i class="bx bx-time-five fs-2 text-warning mb-1"></i>
                            <p class="mb-0 extra-small fw-semibold">This abstract has not been evaluated yet.</p>
                            <small class="text-muted extra-small">Select a decision from the evaluation desk below to record moderation logs.</small>
                        </div>
                    @endif
                </div>
            </div>

            <!-- 3. Moderator Evaluation Desk Form -->
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-header bg-white py-3 border-bottom">
                    <h6 class="mb-0 fw-bold text-dark d-flex align-items-center gap-2">
                        <i class="bx bx-check-shield me-1" style="color: #0288D1 !important;"></i> Moderator Evaluation Desk
                    </h6>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('admin.abstracts.update-status', $abstract->id) }}" method="POST">
                        @csrf
                        <label class="form-label fw-bold text-dark mb-2">Select Decision:</label>

                        <div class="d-flex flex-column gap-2.5 mb-3.5">
                            <!-- Option 1: Accept for Oral -->
                            <label class="border rounded-3 p-3 d-flex align-items-center justify-content-between decision-option-card cursor-pointer {{ ($abstract->status === 'Accepted' && $abstract->presentation_mode === 'Oral Presentation') ? 'active-oral' : '' }}" style="transition: all 0.2s ease;">
                                <div class="d-flex align-items-center gap-2.5">
                                    <input type="radio" name="decision" value="accept_oral" class="form-check-input mt-0" {{ ($abstract->status === 'Accepted' && $abstract->presentation_mode === 'Oral Presentation') ? 'checked' : '' }} required>
                                    <div>
                                        <strong class="d-block text-dark small" style="font-size: 0.88rem;">Accept for Oral</strong>
                                        <small class="text-muted extra-small">Approved as Oral Presentation</small>
                                    </div>
                                </div>
                                <span class="badge rounded-pill shadow-xs d-inline-flex align-items-center" style="background-color: #059669 !important; color: #FFFFFF !important; font-weight: 700; font-size: 0.72rem; padding: 5px 10px;">
                                    <i class="bx bx-microphone me-1" style="color: #FFFFFF !important;"></i> Oral
                                </span>
                            </label>

                            <!-- Option 2: Accept for Paper -->
                            <label class="border rounded-3 p-3 d-flex align-items-center justify-content-between decision-option-card cursor-pointer {{ ($abstract->status === 'Accepted' && $abstract->presentation_mode !== 'Oral Presentation') ? 'active-paper' : '' }}" style="transition: all 0.2s ease;">
                                <div class="d-flex align-items-center gap-2.5">
                                    <input type="radio" name="decision" value="accept_paper" class="form-check-input mt-0" {{ ($abstract->status === 'Accepted' && $abstract->presentation_mode !== 'Oral Presentation') ? 'checked' : '' }}>
                                    <div>
                                        <strong class="d-block text-dark small" style="font-size: 0.88rem;">Accept for Paper</strong>
                                        <small class="text-muted extra-small">Approved as Paper / Poster</small>
                                    </div>
                                </div>
                                <span class="badge rounded-pill shadow-xs d-inline-flex align-items-center" style="background-color: #0288D1 !important; color: #FFFFFF !important; font-weight: 700; font-size: 0.72rem; padding: 5px 10px;">
                                    <i class="bx bx-file me-1" style="color: #FFFFFF !important;"></i> Paper
                                </span>
                            </label>

                            <!-- Option 3: Reject -->
                            <label class="border rounded-3 p-3 d-flex align-items-center justify-content-between decision-option-card cursor-pointer {{ $abstract->status === 'Rejected' ? 'active-reject' : '' }}" style="transition: all 0.2s ease;">
                                <div class="d-flex align-items-center gap-2.5">
                                    <input type="radio" name="decision" value="reject" class="form-check-input mt-0" {{ $abstract->status === 'Rejected' ? 'checked' : '' }}>
                                    <div>
                                        <strong class="d-block text-danger small" style="font-size: 0.88rem;">Reject</strong>
                                        <small class="text-muted extra-small">Decline abstract submission</small>
                                    </div>
                                </div>
                                <span class="badge rounded-pill shadow-xs d-inline-flex align-items-center" style="background-color: #DC2626 !important; color: #FFFFFF !important; font-weight: 700; font-size: 0.72rem; padding: 5px 10px;">
                                    <i class="bx bx-x-circle me-1" style="color: #FFFFFF !important;"></i> Reject
                                </span>
                            </label>
                        </div>

                        <div class="mb-3">
                            <label for="review_comments" class="form-label fw-bold small text-dark">Review Remarks / Feedback</label>
                            <textarea name="review_comments" id="review_comments" rows="3" class="form-control form-control-sm" placeholder="Enter comments or justification for the presenter...">{{ $abstract->review_comments }}</textarea>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 rounded-pill fw-bold py-2.5 shadow-sm d-flex align-items-center justify-content-center gap-2" style="background: linear-gradient(135deg, #013069 0%, #0d47a1 100%) !important; color: #FFFFFF !important; border: none; font-size: 0.88rem;">
                            <i class="bx bx-check-double fs-5" style="color: #FFFFFF !important;"></i>
                            <span style="color: #FFFFFF !important;">Save Moderation Decision</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .decision-option-card:hover {
        border-color: #0288D1 !important;
        background-color: #F8FAFC;
    }
    .decision-option-card.active-oral {
        border-color: #10B981 !important;
        background-color: #ECFDF5;
    }
    .decision-option-card.active-paper {
        border-color: #0288D1 !important;
        background-color: #F0F9FF;
    }
    .decision-option-card.active-reject {
        border-color: #EF4444 !important;
        background-color: #FEF2F2;
    }
</style>
@endsection
