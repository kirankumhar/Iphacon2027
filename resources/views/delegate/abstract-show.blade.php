@extends('shared.auth-delegate')
@section('title', 'Abstract Details - IPHACON 2027')

@section('delegate-content')
<div class="container py-3">
    <div class="row justify-content-center">
        <div class="col-lg-11 col-xl-10">

            <!-- Top Header Card -->
            <div class="card border-0 shadow-sm mb-4" style="border-radius: 20px; overflow: hidden; background: linear-gradient(135deg, #FF6B00 0%, #E65100 50%, #D84315 100%);">
                <div class="card-body p-4 text-white">
                    <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
                        <div>
                            <span class="badge bg-white text-danger fw-bold mb-2 px-3 py-1.5 rounded-pill shadow-xs" style="font-size: 0.8rem; color: #D84315 !important;">
                                <i class="fas fa-file-alt me-1"></i> Abstract Submission Details
                            </span>
                            <h3 class="fw-bold mb-1 text-white" style="letter-spacing: -0.5px;">
                                {{ $abstract->abstract_title ?: 'Untitled Abstract' }}
                            </h3>
                            <div class="text-white opacity-90 small d-flex flex-wrap gap-3 mt-2" style="font-size: 0.88rem;">
                                <span><i class="fas fa-barcode text-warning me-1"></i>Ack ID: <strong>{{ $abstract->acknowledgement_id }}</strong></span>
                                <span><i class="fas fa-bullhorn text-warning me-1"></i>Mode: <strong>{{ $abstract->presentation_mode ?: 'N/A' }}</strong></span>
                                <span><i class="fas fa-calendar-alt text-warning me-1"></i>Submitted: {{ $abstract->submitted_at ? $abstract->submitted_at->format('d M, Y h:i A') : $abstract->created_at->format('d M, Y') }}</span>
                            </div>
                        </div>

                        <!-- Header Action Buttons -->
                        <div class="d-flex flex-wrap align-items-center gap-2">
                            <a href="{{ route('abstract.download-pdf', $abstract->id) }}" class="btn btn-light btn-lg fw-bold text-success rounded-pill shadow-sm px-3.5 py-2" style="font-size: 0.9rem;">
                                <i class="fas fa-file-pdf me-2 text-danger fs-5"></i>Generate PDF
                            </a>
                            <a href="{{ route('abstract.create') }}" class="btn btn-outline-light btn-lg fw-bold rounded-pill px-3.5 py-2" style="font-size: 0.9rem;">
                                <i class="fas fa-edit me-1"></i>Edit
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Overview Section Grid -->
            <div class="row g-3 mb-4">
                <!-- Status & Category Card -->
                <div class="col-md-6 col-lg-3">
                    <div class="card border-0 shadow-sm h-100 p-3" style="border-radius: 14px; background: #F8FAFC; border-left: 4px solid #FF6B00 !important;">
                        <span class="text-muted extra-small fw-bold text-uppercase">Submission Status</span>
                        <div class="mt-1">
                            @if(strtolower($abstract->status) === 'submitted' || strtolower($abstract->status) === 'approved')
                                <span class="badge bg-success text-white px-3 py-1.5 rounded-pill fw-bold" style="font-size: 0.8rem;">
                                    <i class="fas fa-check-circle me-1"></i>Submitted
                                </span>
                            @else
                                <span class="badge bg-warning text-dark px-3 py-1.5 rounded-pill fw-bold" style="font-size: 0.8rem;">
                                    <i class="fas fa-pencil-alt me-1"></i>Draft Mode
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Presentation Mode Card -->
                <div class="col-md-6 col-lg-3">
                    <div class="card border-0 shadow-sm h-100 p-3" style="border-radius: 14px; background: #F8FAFC; border-left: 4px solid #0288D1 !important;">
                        <span class="text-muted extra-small fw-bold text-uppercase">Presentation Mode</span>
                        <h6 class="fw-bold text-dark mb-0 mt-1" style="font-size: 0.92rem;">
                            <i class="fas fa-microphone text-info me-1"></i> {{ $abstract->presentation_mode ?: 'N/A' }}
                        </h6>
                    </div>
                </div>

                <!-- Category Card -->
                <div class="col-md-6 col-lg-3">
                    <div class="card border-0 shadow-sm h-100 p-3" style="border-radius: 14px; background: #F8FAFC; border-left: 4px solid #10B981 !important;">
                        <span class="text-muted extra-small fw-bold text-uppercase">Presenter Category</span>
                        <h6 class="fw-bold text-dark mb-0 mt-1" style="font-size: 0.92rem;">
                            <i class="fas fa-user-tag text-success me-1"></i> {{ $abstract->presenter_category == 'Other' ? $abstract->other_category_text : $abstract->presenter_category }}
                        </h6>
                    </div>
                </div>

                <!-- Total Words Card -->
                <div class="col-md-6 col-lg-3">
                    <div class="card border-0 shadow-sm h-100 p-3" style="border-radius: 14px; background: #F8FAFC; border-left: 4px solid #8B5CF6 !important;">
                        <span class="text-muted extra-small fw-bold text-uppercase">Total Word Count</span>
                        <h6 class="fw-bold text-dark mb-0 mt-1" style="font-size: 0.92rem;">
                            <i class="fas fa-font text-purple me-1"></i> {{ $abstract->total_word_count ?: 0 }} Words
                        </h6>
                    </div>
                </div>
            </div>

            <!-- Conference Sub-Theme Banner -->
            <div class="alert alert-info border-0 shadow-xs mb-4 p-3.5 d-flex align-items-center gap-3" style="border-radius: 14px; background: #EFF6FF; border: 1px solid #BFDBFE !important;">
                <div class="rounded-circle bg-primary text-white p-2 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 40px; height: 40px;">
                    <i class="fas fa-layer-group fs-5"></i>
                </div>
                <div>
                    <span class="text-muted extra-small fw-bold text-uppercase d-block">Conference Theme / Sub-Theme</span>
                    <h6 class="fw-bold text-primary mb-0" style="font-size: 0.95rem;">{{ $abstract->conference_theme }}</h6>
                </div>
            </div>

            <!-- Main Details Card -->
            <div class="card border-0 shadow-sm mb-4" style="border-radius: 16px; border: 1px solid #E2E8F0 !important;">
                <div class="card-header bg-white py-3.5 px-4 border-bottom d-flex align-items-center justify-content-between">
                    <h6 class="fw-bold mb-0 text-dark fs-6">
                        <i class="fas fa-user-circle text-primary me-2"></i>Presenting Author Information
                    </h6>
                </div>
                <div class="card-body p-4">
                    <div class="row g-3">
                        <div class="col-sm-6 col-md-4">
                            <span class="text-muted d-block fw-bold" style="font-size: 0.75rem;">Full Name</span>
                            <span class="fw-bold text-dark" style="font-size: 0.92rem;">{{ $abstract->presenting_author_name }}</span>
                        </div>
                        <div class="col-sm-6 col-md-4">
                            <span class="text-muted d-block fw-bold" style="font-size: 0.75rem;">Designation</span>
                            <span class="fw-semibold text-dark">{{ $abstract->presenting_author_designation }}</span>
                        </div>
                        <div class="col-sm-6 col-md-4">
                            <span class="text-muted d-block fw-bold" style="font-size: 0.75rem;">Department</span>
                            <span class="fw-semibold text-dark">{{ $abstract->presenting_author_department }}</span>
                        </div>
                        <div class="col-sm-6 col-md-4">
                            <span class="text-muted d-block fw-bold" style="font-size: 0.75rem;">Institution / Organization</span>
                            <span class="fw-semibold text-dark">{{ $abstract->presenting_author_institution }}</span>
                        </div>
                        <div class="col-sm-6 col-md-4">
                            <span class="text-muted d-block fw-bold" style="font-size: 0.75rem;">City, State, Country</span>
                            <span class="fw-semibold text-dark">{{ $abstract->presenting_author_city }}, {{ $abstract->presenting_author_state }}, {{ $abstract->presenting_author_country }}</span>
                        </div>
                        <div class="col-sm-6 col-md-4">
                            <span class="text-muted d-block fw-bold" style="font-size: 0.75rem;">Email & Contact Number</span>
                            <span class="fw-semibold text-dark">{{ $abstract->presenting_author_email }} | {{ $abstract->presenting_author_mobile }}</span>
                        </div>
                        @if($abstract->medical_council_reg_no)
                            <div class="col-sm-6 col-md-4">
                                <span class="text-muted d-block fw-bold" style="font-size: 0.75rem;">Medical Council Reg No</span>
                                <span class="fw-semibold text-dark">{{ $abstract->medical_council_reg_no }}</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Co-Authors Card -->
            @if(!empty($abstract->co_authors) && is_array($abstract->co_authors) && count($abstract->co_authors) > 0)
                <div class="card border-0 shadow-sm mb-4" style="border-radius: 16px; border: 1px solid #E2E8F0 !important;">
                    <div class="card-header bg-white py-3.5 px-4 border-bottom d-flex align-items-center justify-content-between">
                        <h6 class="fw-bold mb-0 text-dark fs-6">
                            <i class="fas fa-users text-success me-2"></i>Co-Authors ({{ count($abstract->co_authors) }})
                        </h6>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0 small">
                                <thead class="table-light">
                                    <tr>
                                        <th class="ps-4">#</th>
                                        <th>Co-Author Name</th>
                                        <th>Designation</th>
                                        <th>Department</th>
                                        <th>Institution</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($abstract->co_authors as $index => $coAuthor)
                                        <tr>
                                            <td class="ps-4 fw-bold text-muted">{{ $index + 1 }}</td>
                                            <td class="fw-bold text-dark">{{ $coAuthor['name'] ?? 'N/A' }}</td>
                                            <td>{{ $coAuthor['designation'] ?? 'N/A' }}</td>
                                            <td>{{ $coAuthor['department'] ?? 'N/A' }}</td>
                                            <td>{{ $coAuthor['institution'] ?? 'N/A' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Abstract Structured Content -->
            <div class="card border-0 shadow-sm mb-4" style="border-radius: 16px; border: 1px solid #E2E8F0 !important;">
                <div class="card-header bg-white py-3.5 px-4 border-bottom d-flex align-items-center justify-content-between">
                    <h6 class="fw-bold mb-0 text-dark fs-6">
                        <i class="fas fa-file-invoice text-warning me-2"></i>Structured Abstract Content
                    </h6>
                </div>
                <div class="card-body p-4">

                    <!-- Title -->
                    <div class="mb-4 pb-3 border-bottom">
                        <span class="text-muted extra-small fw-bold text-uppercase d-block mb-1">Abstract Title</span>
                        <h5 class="fw-bold text-dark mb-0" style="line-height: 1.5;">{{ $abstract->abstract_title }}</h5>
                    </div>

                    <!-- Background -->
                    <div class="mb-4">
                        <h6 class="fw-bold text-primary small text-uppercase mb-1.5"><i class="fas fa-angle-right me-1"></i>Background</h6>
                        <p class="text-dark small mb-0" style="white-space: pre-line; line-height: 1.6;">{{ $abstract->abstract_background ?: 'N/A' }}</p>
                    </div>

                    <!-- Objectives -->
                    <div class="mb-4">
                        <h6 class="fw-bold text-primary small text-uppercase mb-1.5"><i class="fas fa-angle-right me-1"></i>Objectives</h6>
                        <p class="text-dark small mb-0" style="white-space: pre-line; line-height: 1.6;">{{ $abstract->abstract_objectives ?: 'N/A' }}</p>
                    </div>

                    <!-- Methodology -->
                    <div class="mb-4">
                        <h6 class="fw-bold text-primary small text-uppercase mb-1.5"><i class="fas fa-angle-right me-1"></i>Methods / Methodology</h6>
                        <p class="text-dark small mb-0" style="white-space: pre-line; line-height: 1.6;">{{ $abstract->abstract_methodology ?: 'N/A' }}</p>
                    </div>

                    <!-- Results -->
                    <div class="mb-4">
                        <h6 class="fw-bold text-primary small text-uppercase mb-1.5"><i class="fas fa-angle-right me-1"></i>Results</h6>
                        <p class="text-dark small mb-0" style="white-space: pre-line; line-height: 1.6;">{{ $abstract->abstract_results ?: 'N/A' }}</p>
                    </div>

                    <!-- Conclusion -->
                    <div class="mb-4 pb-3 border-bottom">
                        <h6 class="fw-bold text-primary small text-uppercase mb-1.5"><i class="fas fa-angle-right me-1"></i>Conclusion</h6>
                        <p class="text-dark small mb-0" style="white-space: pre-line; line-height: 1.6;">{{ $abstract->abstract_conclusion ?: 'N/A' }}</p>
                    </div>

                    <!-- Keywords -->
                    @if($abstract->keywords)
                        <div class="mb-3">
                            <span class="text-muted extra-small fw-bold text-uppercase d-block mb-1.5">Keywords</span>
                            <div>
                                @foreach(explode(',', $abstract->keywords) as $keyword)
                                    <span class="badge bg-light text-dark border me-1 mb-1 px-3 py-1.5 rounded-pill font-normal" style="font-size: 0.8rem;">
                                        <i class="fas fa-hashtag text-primary me-1"></i>{{ trim($keyword) }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Attachment Document -->
                    @if($abstract->attachment_path)
                        <div class="mt-4 p-3 bg-light rounded-3 d-flex align-items-center justify-content-between border">
                            <div class="d-flex align-items-center gap-2">
                                <i class="fas fa-paperclip text-primary fs-5"></i>
                                <div>
                                    <span class="fw-bold text-dark d-block small">Abstract File Attachment</span>
                                    <span class="text-muted extra-small">Uploaded document file</span>
                                </div>
                            </div>
                            <a href="{{ asset('storage/' . $abstract->attachment_path) }}" target="_blank" class="btn btn-sm btn-outline-primary rounded-pill px-3 py-1.5 font-semibold">
                                <i class="fas fa-download me-1"></i>View / Download File
                            </a>
                        </div>
                    @endif

                </div>
            </div>

            <!-- Bottom Navigation -->
            <div class="d-flex flex-wrap justify-content-between align-items-center mt-4">
                <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary px-4 py-2.5 fw-semibold rounded-pill">
                    <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
                </a>
                <a href="{{ route('abstract.download-pdf', $abstract->id) }}" class="btn btn-success px-4 py-2.5 fw-bold rounded-pill shadow-sm" style="background: linear-gradient(135deg, #10B981, #059669); border: none;">
                    <i class="fas fa-file-pdf me-2"></i>Generate PDF Receipt
                </a>
            </div>

        </div>
    </div>
</div>
@endsection
