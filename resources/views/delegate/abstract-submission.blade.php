@extends('shared.auth-delegate')

@section('title', 'Abstract Submission')

@php
    $user = Auth::user();
    $registration = $registration ?? \App\Models\Registration::where('user_id', $user->id)->first();
@endphp

@section('delegate-content')
<style>
    .abstract-hero {
        background: linear-gradient(135deg, #013069 0%, #0d47a1 60%, #1565c0 100%);
        border-radius: 18px;
        color: #ffffff;
        box-shadow: 0 10px 28px rgba(1, 48, 105, 0.18);
        position: relative;
        overflow: hidden;
    }
    .abstract-avatar {
        width: 76px;
        height: 76px;
        border-radius: 50%;
        object-fit: cover;
        border: 3.5px solid rgba(255, 255, 255, 0.6);
        box-shadow: 0 4px 14px rgba(0, 0, 0, 0.2);
    }
    .abstract-avatar-placeholder {
        width: 76px;
        height: 76px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.2);
        border: 3.5px solid rgba(255, 255, 255, 0.6);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        color: #ffffff;
        box-shadow: 0 4px 14px rgba(0, 0, 0, 0.2);
    }
    .instructions-card {
        background: linear-gradient(180deg, #fffbeb 0%, #fef3c7 100%);
        border: 1.5px solid #fde68a;
        border-radius: 16px;
    }
    .form-section-card {
        background: #ffffff;
        border-radius: 16px;
        border: 1px solid rgba(226, 232, 240, 0.9);
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.03);
    }
    .section-badge {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background: #0d47a1;
        color: #ffffff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 0.9rem;
    }
    .section-title {
        color: #013069;
        font-weight: 700;
        font-size: 1.15rem;
    }
    .co-author-box {
        background: #f8fafc;
        border: 1px dashed #cbd5e1;
        border-radius: 14px;
        padding: 16px;
        position: relative;
    }
    .btn-remove-author {
        position: absolute;
        top: 12px;
        right: 12px;
        color: #ef4444;
        background: transparent;
        border: none;
        font-size: 0.9rem;
        cursor: pointer;
    }
    .word-counter-badge {
        font-weight: 700;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 0.8rem;
    }
    .badge-within-limit {
        background: #dcfce7;
        color: #15803d;
        border: 1px solid #bbf7d0;
    }
    .badge-exceeded-limit {
        background: #fee2e2;
        color: #b91c1c;
        border: 1px solid #fca5a5;
    }
    .form-control, .form-select {
        border-radius: 10px;
        border: 1.5px solid #cbd5e1;
        padding: 10px 14px;
        font-size: 0.92rem;
    }
    .form-control:focus, .form-select:focus {
        border-color: #0d47a1;
        box-shadow: 0 0 0 0.2rem rgba(13, 71, 161, 0.18);
    }
    .custom-radio-card {
        border: 1.5px solid #cbd5e1;
        border-radius: 12px;
        padding: 12px 16px;
        cursor: pointer;
        transition: all 0.2s ease;
        background: #ffffff;
    }
    .custom-radio-card:hover {
        border-color: #0d47a1;
        background: #f0f7ff;
    }
    .form-check-input:checked + .custom-radio-card {
        border-color: #0d47a1;
        background: #e8f2ff;
        box-shadow: 0 0 0 2px rgba(13, 71, 161, 0.2);
    }
</style>

<div class="container-fluid px-0">

    <!-- Header Hero Banner -->
    <div class="abstract-hero p-4 p-md-4.5 mb-4">
        <div class="row align-items-center gy-3">
            <div class="col-lg-8">
                <div class="d-flex align-items-center gap-3.5 flex-wrap">
                    @if (!empty($registration?->photo_path))
                        <img src="/storage/{{ $registration->photo_path }}" alt="Author Profile Photo" class="abstract-avatar">
                    @else
                        <div class="abstract-avatar-placeholder">
                            <i class="fas fa-user"></i>
                        </div>
                    @endif
                    <div>
                        <span class="badge bg-white text-primary fw-bold mb-1 px-3 py-1.5 rounded-pill shadow-xs" style="font-size: 0.78rem;">
                            <i class="fas fa-award me-1"></i> IPHACON 2027 Scientific Portal
                        </span>
                        <h2 class="fw-bold mb-1 text-white" style="font-size: 1.55rem;">
                            Abstract Submission
                        </h2>
                        <p class="mb-0 text-white-50 small">
                            <i class="fas fa-user-circle me-1 text-warning"></i> {{ $user->prefix ?? '' }} {{ $user->full_name }}
                            @if(!empty($registration?->registration_number))
                                <span class="ms-2">• Reg No: <strong>#{{ $registration->registration_number }}</strong></span>
                            @endif
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 text-lg-end">
                <a href="{{ route('dashboard') }}" class="btn btn-outline-light px-3.5 py-2 fw-semibold" style="border-radius: 10px; font-size: 0.88rem;">
                    <i class="fas fa-arrow-left me-1.5"></i> Back to Dashboard
                </a>
            </div>
        </div>
    </div>

    <!-- General Instructions Box -->
    <div class="instructions-card p-4 mb-4">
        <div class="d-flex align-items-start gap-3">
            <div class="text-warning-emphasis fs-4 mt-1">
                <i class="fas fa-info-circle text-warning"></i>
            </div>
            <div>
                <h5 class="fw-bold text-dark mb-2" style="font-size: 1.05rem;">
                    General Instructions for Abstract Submission
                </h5>
                <ul class="mb-0 text-dark small ps-3" style="line-height: 1.6;">
                    <li><strong>Online Only:</strong> Abstracts must be submitted online only through this portal.</li>
                    <li><strong>Word Limit:</strong> Maximum word limit is <strong>300 words</strong> (excluding title, authors, and affiliations).</li>
                    <li><strong>Language:</strong> Abstracts should be written in <strong>English</strong>.</li>
                    <li><strong>Originality:</strong> Previously published abstracts are <strong>not eligible</strong> for submission.</li>
                    <li><strong>Registration Mandate:</strong> The presenting author <strong>must be registered</strong> for the conference.</li>
                    <li><strong>Scientific Committee Discretion:</strong> Decision of category for presentation and acceptance of the abstract will depend upon the discretion of the Scientific Committee.</li>
                </ul>
            </div>
        </div>
    </div>

    <form id="abstractForm" onsubmit="event.preventDefault(); handleAbstractSubmit();">
        @csrf

        <!-- SECTION 1: Author Details -->
        <div class="form-section-card p-4 mb-4">
            <div class="d-flex align-items-center gap-3 mb-3.5 pb-2 border-bottom">
                <div class="section-badge">1</div>
                <div>
                    <h5 class="section-title mb-0">Author Details</h5>
                    <small class="text-muted">Information about the presenting author and co-authors</small>
                </div>
            </div>

            <!-- Presenting Author Sub-section -->
            <div class="d-flex align-items-center gap-3 mb-3 p-3 bg-light rounded-3 border">
                @if (!empty($registration?->photo_path))
                    <img src="/storage/{{ $registration->photo_path }}" alt="Author Photo" style="width: 50px; height: 50px; border-radius: 50%; object-fit: cover; border: 2px solid #0d47a1; box-shadow: 0 2px 8px rgba(0,0,0,0.12);">
                @else
                    <div style="width: 50px; height: 50px; border-radius: 50%; background: rgba(13, 71, 161, 0.1); color: #0d47a1; display: flex; align-items: center; justify-content: center; font-size: 1.4rem; border: 2px solid #0d47a1;">
                        <i class="fas fa-user"></i>
                    </div>
                @endif
                <div>
                    <h6 class="fw-bold text-primary mb-0"><i class="fas fa-user-tag me-1.5"></i>Presenting Author Details</h6>
                    <small class="text-muted">{{ $user->prefix ?? '' }} {{ $user->full_name }} @if(!empty($registration?->registration_number)) (Reg No: #{{ $registration->registration_number }}) @endif</small>
                </div>
            </div>

            <div class="row g-3 mb-4">
                <!-- Full Name -->
                <div class="col-md-6 col-lg-4">
                    <label class="form-label fw-bold text-dark small">Full Name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="presenting_author_name" value="{{ $user->prefix ?? '' }} {{ $user->full_name }}" required placeholder="e.g. Dr. Kieran Kumar">
                </div>

                <!-- Designation -->
                <div class="col-md-6 col-lg-4">
                    <label class="form-label fw-bold text-dark small">Designation <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="presenting_author_designation" required placeholder="e.g. Associate Professor / Scholar">
                </div>

                <!-- Department -->
                <div class="col-md-6 col-lg-4">
                    <label class="form-label fw-bold text-dark small">Department <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="presenting_author_department" required placeholder="e.g. Department of Microbiology">
                </div>

                <!-- Institution -->
                <div class="col-md-6 col-lg-4">
                    <label class="form-label fw-bold text-dark small">Institution / Organization <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="presenting_author_institution" required placeholder="e.g. RIMS, Ranchi">
                </div>

                <!-- City -->
                <div class="col-md-6 col-lg-4">
                    <label class="form-label fw-bold text-dark small">City <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="presenting_author_city" required placeholder="e.g. Ranchi">
                </div>

                <!-- State -->
                <div class="col-md-6 col-lg-4">
                    <label class="form-label fw-bold text-dark small">State <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="presenting_author_state" required placeholder="e.g. Jharkhand">
                </div>

                <!-- Country -->
                <div class="col-md-6 col-lg-4">
                    <label class="form-label fw-bold text-dark small">Country <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="presenting_author_country" value="{{ $user->country?->country_name ?? 'India' }}" required placeholder="e.g. India">
                </div>

                <!-- Email Address -->
                <div class="col-md-6 col-lg-4">
                    <label class="form-label fw-bold text-dark small">Email Address <span class="text-danger">*</span></label>
                    <input type="email" class="form-control" name="presenting_author_email" value="{{ $user->email }}" required placeholder="email@domain.com">
                </div>

                <!-- Mobile Number -->
                <div class="col-md-6 col-lg-4">
                    <label class="form-label fw-bold text-dark small">Mobile Number <span class="text-danger">*</span></label>
                    <input type="tel" class="form-control" name="presenting_author_mobile" value="{{ $user->mobile_country_code }} {{ $user->mobile_number }}" required placeholder="+91 9876543210">
                </div>

                <!-- Medical Council Registration Number (optional) -->
                <div class="col-md-12">
                    <label class="form-label fw-bold text-dark small">Medical Council Registration Number <span class="text-muted fw-normal">(Optional)</span></label>
                    <input type="text" class="form-control" name="medical_council_reg_no" placeholder="Enter Medical Council Reg No if applicable">
                </div>
            </div>

            <!-- Co-authors Sub-section -->
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="fw-bold text-primary mb-0">
                    <i class="fas fa-user-friends me-1.5"></i>Co-Authors Details
                </h6>
                <button type="button" class="btn btn-sm btn-outline-primary fw-bold" id="addCoAuthorBtn" onclick="addCoAuthorRow()" style="border-radius: 8px;">
                    <i class="fas fa-plus me-1"></i> Add Another Author
                </button>
            </div>

            <div id="coAuthorsContainer" class="d-flex flex-column gap-3 mb-2">
                <!-- Dynamic Co-author rows will be inserted here -->
            </div>
        </div>

        <!-- SECTION 2: Presentation Details -->
        <div class="form-section-card p-4 mb-4">
            <div class="d-flex align-items-center gap-3 mb-3.5 pb-2 border-bottom">
                <div class="section-badge">2</div>
                <div>
                    <h5 class="section-title mb-0">Presentation Details</h5>
                    <small class="text-muted">Select presentation mode, delegate category, and conference theme</small>
                </div>
            </div>

            <!-- Preferred Mode (Radio buttons) -->
            <div class="mb-4">
                <label class="form-label fw-bold text-dark small d-block mb-2">Preferred Mode of Presentation <span class="text-danger">*</span></label>
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="w-100">
                            <input type="radio" class="form-check-input d-none" name="presentation_mode" value="Oral Presentation" required checked>
                            <div class="custom-radio-card d-flex align-items-center gap-2.5">
                                <i class="fas fa-microphone-alt text-primary fs-5"></i>
                                <div>
                                    <div class="fw-bold text-dark small">Oral Presentation</div>
                                    <div class="text-muted" style="font-size: 0.75rem;">Podium presentation</div>
                                </div>
                            </div>
                        </label>
                    </div>

                    <div class="col-md-4">
                        <label class="w-100">
                            <input type="radio" class="form-check-input d-none" name="presentation_mode" value="Poster Presentation">
                            <div class="custom-radio-card d-flex align-items-center gap-2.5">
                                <i class="fas fa-image text-success fs-5"></i>
                                <div>
                                    <div class="fw-bold text-dark small">Poster Presentation</div>
                                    <div class="text-muted" style="font-size: 0.75rem;">Poster display session</div>
                                </div>
                            </div>
                        </label>
                    </div>

                    <div class="col-md-4">
                        <label class="w-100">
                            <input type="radio" class="form-check-input d-none" name="presentation_mode" value="No Preference">
                            <div class="custom-radio-card d-flex align-items-center gap-2.5">
                                <i class="fas fa-balance-scale text-info fs-5"></i>
                                <div>
                                    <div class="fw-bold text-dark small">No Preference</div>
                                    <div class="text-muted" style="font-size: 0.75rem;">Decided by committee</div>
                                </div>
                            </div>
                        </label>
                    </div>
                </div>
            </div>

            <div class="row g-3">
                <!-- Category Dropdown -->
                <div class="col-md-6">
                    <label class="form-label fw-bold text-dark small">Category <span class="text-danger">*</span></label>
                    <select class="form-select" name="presenter_category" id="presenter_category" required onchange="toggleOtherCategory(this)">
                        <option value="" disabled selected>Select Category</option>
                        <option value="Faculty">Faculty</option>
                        <option value="Postgraduate">Postgraduate</option>
                        <option value="Undergraduate">Undergraduate</option>
                        <option value="Public Health Professional">Public Health Professional</option>
                        <option value="Other">Other, please specify</option>
                    </select>
                </div>

                <!-- Other Category Field (Conditional) -->
                <div class="col-md-6 d-none" id="other_category_wrapper">
                    <label class="form-label fw-bold text-dark small">Please Specify Category <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="other_category_text" id="other_category_text" placeholder="Specify your category">
                </div>

                <!-- Theme Dropdown Field -->
                <div class="col-md-6">
                    <label class="form-label fw-bold text-dark small">Conference Theme <span class="text-danger">*</span></label>
                    <select class="form-select" name="conference_theme" id="conference_theme" required>
                        <option value="" disabled selected>Select Conference Theme</option>
                        <option value="Antifungal agents including pharmacokinetics, pharmacodnamics and therapeutic drug monitoring">Antifungal agents including pharmacokinetics, pharmacodnamics and therapeutic drug monitoring</option>
                        <option value="Antifungal susceptibility and resistance">Antifungal susceptibility and resistance</option>
                        <option value="Management of fungal diseases">Management of fungal diseases</option>
                        <option value="Cutaneous/superficial and subcutaneous fungal infections">Cutaneous/superficial and subcutaneous fungal infections</option>
                        <option value="Diagnostic Mycology">Diagnostic Mycology</option>
                        <option value="Epidemiology">Epidemiology</option>
                        <option value="Infection control, prevention and antifungal stewardship">Infection control, prevention and antifungal stewardship</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- SECTION 3: Abstract Details -->
        <div class="form-section-card p-4 mb-4">
            <div class="d-flex align-items-center justify-content-between mb-3.5 pb-2 border-bottom flex-wrap gap-2">
                <div class="d-flex align-items-center gap-3">
                    <div class="section-badge">3</div>
                    <div>
                        <h5 class="section-title mb-0">Abstract Details</h5>
                        <small class="text-muted">Structured abstract content with live word counts</small>
                    </div>
                </div>

                <!-- Live Total Word Counter -->
                <div class="d-flex align-items-center gap-2">
                    <span class="text-muted small fw-semibold">Structured Body Words:</span>
                    <span id="totalWordCountBadge" class="word-counter-badge badge-within-limit">
                        0 / 300 Words
                    </span>
                </div>
            </div>

            <!-- Abstract Title -->
            <div class="mb-3.5">
                <div class="d-flex justify-content-between align-items-center mb-1">
                    <label class="form-label fw-bold text-dark small mb-0">Abstract Title <span class="text-danger">*</span></label>
                    <span class="text-muted" style="font-size: 0.75rem;">Max 25 words (<span id="titleWordCount">0</span>/25)</span>
                </div>
                <input type="text" class="form-control" name="abstract_title" id="abstract_title" required placeholder="Enter the complete title of your abstract" oninput="countTitleWords()">
            </div>

            <!-- Keywords -->
            <div class="mb-4">
                <div class="d-flex justify-content-between align-items-center mb-1">
                    <label class="form-label fw-bold text-dark small mb-0">
                        Keywords <span class="text-danger">*</span> <span class="text-muted fw-normal">(3 to 5 keywords separated by commas)</span>
                    </label>
                    <span class="text-muted" style="font-size: 0.75rem;">
                        Keywords Count: <span id="keywordsCountBadge" class="fw-bold text-primary">0</span> (Min: 3, Max: 5)
                    </span>
                </div>
                <input type="text" class="form-control" name="keywords" id="keywordsInput" required placeholder="e.g. Candida auris, Antifungal Resistance, Epidemiology, Mycology" oninput="countKeywords()">
                <div id="keywordsError" class="text-danger small mt-1 d-none">
                    <i class="fas fa-exclamation-circle me-1"></i>Please enter between 3 and 5 keywords separated by commas.
                </div>
            </div>

            <h6 class="fw-bold text-primary mb-3">
                <i class="fas fa-align-left me-1.5"></i>Structured Abstract Sections
            </h6>

            <!-- 1. Background -->
            <div class="mb-3.5">
                <label class="form-label fw-bold text-dark small mb-1">Background <span class="text-danger">*</span></label>
                <textarea class="form-control abstract-body-part" name="abstract_background" rows="3" required placeholder="Provide background and rationale of the study..." oninput="updateTotalWordCount()"></textarea>
            </div>

            <!-- 2. Objectives -->
            <div class="mb-3.5">
                <label class="form-label fw-bold text-dark small mb-1">Objectives <span class="text-danger">*</span></label>
                <textarea class="form-control abstract-body-part" name="abstract_objectives" rows="2" required placeholder="State specific objectives or aims..." oninput="updateTotalWordCount()"></textarea>
            </div>

            <!-- 3. Methodology -->
            <div class="mb-3.5">
                <label class="form-label fw-bold text-dark small mb-1">Methodology <span class="text-danger">*</span></label>
                <textarea class="form-control abstract-body-part" name="abstract_methodology" rows="3.5" required placeholder="Describe study design, materials, methods, and statistical analysis..." oninput="updateTotalWordCount()"></textarea>
            </div>

            <!-- 4. Results -->
            <div class="mb-3.5">
                <label class="form-label fw-bold text-dark small mb-1">Results <span class="text-danger">*</span></label>
                <textarea class="form-control abstract-body-part" name="abstract_results" rows="3.5" required placeholder="Present key findings with data..." oninput="updateTotalWordCount()"></textarea>
            </div>

            <!-- 5. Conclusion -->
            <div class="mb-2">
                <label class="form-label fw-bold text-dark small mb-1">Conclusion <span class="text-danger">*</span></label>
                <textarea class="form-control abstract-body-part" name="abstract_conclusion" rows="2.5" required placeholder="State key conclusions and scientific impact..." oninput="updateTotalWordCount()"></textarea>
            </div>
        </div>

        <!-- Submission Disclaimer & Action Buttons Card -->
        <div class="form-section-card p-4 mb-4" style="background: linear-gradient(180deg, #ffffff 0%, #f8fafc 100%);">
            <div class="alert alert-warning border-warning shadow-xs mb-4" role="alert" style="border-radius: 12px;">
                <div class="d-flex align-items-center">
                    <i class="fas fa-exclamation-triangle me-2.5 fs-5 text-warning-emphasis"></i>
                    <div class="small fw-semibold text-dark">
                        <strong>Important Notice Before Final Submission:</strong> Please review your abstract carefully. No editing may be allowed after final submission.
                    </div>
                </div>
            </div>

            <div class="form-check mb-4">
                <input class="form-check-input" type="checkbox" id="confirmReview" required style="border-radius: 4px; cursor: pointer;">
                <label class="form-check-label text-dark small fw-semibold cursor-pointer" for="confirmReview">
                    I confirm that I have reviewed the abstract and all author details thoroughly. I agree that the information is accurate.
                </label>
            </div>

            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                <button type="button" class="btn btn-outline-secondary px-3.5 py-2.5 fw-bold" onclick="saveAsDraft()" style="border-radius: 10px;">
                    <i class="fas fa-save me-1.5"></i> Save as Draft
                </button>

                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-outline-primary px-4 py-2.5 fw-bold" onclick="openPreviewModal()" style="border-radius: 10px;">
                        <i class="fas fa-eye me-1.5"></i> Preview
                    </button>

                    <button type="submit" class="btn btn-primary px-4 py-2.5 fw-bold" style="background: linear-gradient(135deg, #013069 0%, #0d47a1 100%); border: none; border-radius: 10px; box-shadow: 0 4px 14px rgba(1, 48, 105, 0.25);">
                        <i class="fas fa-paper-plane me-1.5"></i> Submit Abstract
                    </button>
                </div>
            </div>
        </div>
    </form>

</div>

<!-- Abstract Preview Modal -->
<div class="modal fade" id="abstractPreviewModal" tabindex="-1" aria-labelledby="previewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content" style="border-radius: 16px;">
            <div class="modal-header text-white" style="background: linear-gradient(135deg, #013069 0%, #0d47a1 100%); border-radius: 16px 16px 0 0;">
                <h5 class="modal-header-title text-white fw-bold mb-0" id="previewModalLabel">
                    <i class="fas fa-search me-2"></i>Abstract Preview
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4" id="modalPreviewContent">
                <!-- Filled dynamically via JS -->
            </div>
            <div class="modal-footer bg-light" style="border-radius: 0 0 16px 16px;">
                <button type="button" class="btn btn-secondary px-4 fw-bold" data-bs-dismiss="modal" style="border-radius: 8px;">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Submission Confirmation / Acknowledgement Modal -->
<div class="modal fade" id="acknowledgementModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content text-center p-4 position-relative" style="border-radius: 20px;">
            <button type="button" class="btn-close position-absolute top-0 end-0 m-3" data-bs-dismiss="modal" aria-label="Close"></button>

            <div class="modal-body pt-2">
                <div class="bg-success bg-opacity-10 text-success rounded-circle d-inline-flex p-3 mb-3">
                    <i class="fas fa-check-circle fa-3x"></i>
                </div>
                <h4 class="fw-bold text-dark mb-1">Abstract Submitted Successfully!</h4>
                <p class="text-secondary small mb-3">Your abstract has been registered for ISMM 2027 review.</p>

                <div class="bg-light p-3 rounded-3 border mb-3 text-start">
                    <div class="text-muted small uppercase fw-bold mb-1">Acknowledgement ID</div>
                    <div class="h4 text-primary font-monospace fw-bold mb-0" id="ackIDDisplay">
                        ABS-2027-8942
                    </div>
                </div>

                <p class="text-muted small mb-4">
                    <i class="fas fa-envelope-open-text me-1 text-primary"></i>
                    A confirmation email has been dispatched to your email address.
                </p>

                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-outline-secondary px-3.5 py-2.5 fw-bold w-50" data-bs-dismiss="modal" style="border-radius: 10px;">
                        <i class="fas fa-times me-1"></i> Close
                    </button>
                    <a href="{{ route('dashboard') }}" class="btn btn-primary px-3.5 py-2.5 fw-bold w-50" style="background: linear-gradient(135deg, #013069 0%, #0d47a1 100%); border-radius: 10px;">
                        <i class="fas fa-home me-1"></i> Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    let coAuthorCount = 0;

    function addCoAuthorRow() {
        coAuthorCount++;
        const container = document.getElementById('coAuthorsContainer');
        const box = document.createElement('div');
        box.className = 'co-author-box';
        box.id = `coAuthorBox_${coAuthorCount}`;

        box.innerHTML = `
            <button type="button" class="btn-remove-author" onclick="removeCoAuthorRow(${coAuthorCount})" title="Remove Author">
                <i class="fas fa-trash-alt me-1"></i> Remove
            </button>
            <div class="fw-bold text-secondary mb-2.5" style="font-size: 0.85rem;">
                <i class="fas fa-user-plus me-1 text-primary"></i> Co-Author #${coAuthorCount}
            </div>
            <div class="row g-3">
                <div class="col-md-6 col-lg-4">
                    <label class="form-label fw-semibold text-dark small mb-1">Full Name</label>
                    <input type="text" class="form-control form-control-sm" name="co_author_name[]" placeholder="Co-author full name">
                </div>
                <div class="col-md-6 col-lg-4">
                    <label class="form-label fw-semibold text-dark small mb-1">Designation</label>
                    <input type="text" class="form-control form-control-sm" name="co_author_designation[]" placeholder="Designation">
                </div>
                <div class="col-md-6 col-lg-4">
                    <label class="form-label fw-semibold text-dark small mb-1">Department</label>
                    <input type="text" class="form-control form-control-sm" name="co_author_department[]" placeholder="Department">
                </div>
                <div class="col-md-6 col-lg-6">
                    <label class="form-label fw-semibold text-dark small mb-1">Institution</label>
                    <input type="text" class="form-control form-control-sm" name="co_author_institution[]" placeholder="Institution">
                </div>
                <div class="col-md-6 col-lg-6">
                    <label class="form-label fw-semibold text-dark small mb-1">Email <span class="text-muted fw-normal">(Optional)</span></label>
                    <input type="email" class="form-control form-control-sm" name="co_author_email[]" placeholder="Email address">
                </div>
            </div>
        `;
        container.appendChild(box);
    }

    function removeCoAuthorRow(id) {
        const el = document.getElementById(`coAuthorBox_${id}`);
        if (el) el.remove();
    }

    function toggleOtherCategory(select) {
        const wrapper = document.getElementById('other_category_wrapper');
        const input = document.getElementById('other_category_text');
        if (select.value === 'Other') {
            wrapper.classList.remove('d-none');
            input.setAttribute('required', 'required');
        } else {
            wrapper.classList.add('d-none');
            input.removeAttribute('required');
        }
    }

    function countWordsInText(str) {
        if (!str) return 0;
        return str.trim().split(/\s+/).filter(w => w.length > 0).length;
    }

    function countKeywords() {
        const input = document.getElementById('keywordsInput') ? document.getElementById('keywordsInput').value : '';
        const keywords = input.split(',').map(k => k.trim()).filter(k => k.length > 0);
        const count = keywords.length;
        const badge = document.getElementById('keywordsCountBadge');
        const err = document.getElementById('keywordsError');

        if (badge) badge.innerText = count;
        if (count >= 3 && count <= 5) {
            if (badge) badge.className = 'fw-bold text-success';
            if (err) err.classList.add('d-none');
        } else {
            if (badge) badge.className = 'fw-bold text-danger';
            if (err && count > 0) err.classList.remove('d-none');
        }
        return count;
    }

    function countTitleWords() {
        const val = document.getElementById('abstract_title').value;
        const count = countWordsInText(val);
        document.getElementById('titleWordCount').innerText = count;
    }

    function updateTotalWordCount() {
        const textareas = document.querySelectorAll('.abstract-body-part');
        let total = 0;
        textareas.forEach(ta => {
            total += countWordsInText(ta.value);
        });

        const badge = document.getElementById('totalWordCountBadge');
        badge.innerText = `${total} / 300 Words`;

        if (total > 300) {
            badge.className = 'word-counter-badge badge-exceeded-limit';
        } else {
            badge.className = 'word-counter-badge badge-within-limit';
        }
    }

    function openPreviewModal() {
        const form = document.getElementById('abstractForm');
        const formData = new FormData(form);

        let coAuthorsHtml = '';
        const names = formData.getAll('co_author_name[]');
        const desgs = formData.getAll('co_author_designation[]');
        const depts = formData.getAll('co_author_department[]');
        const insts = formData.getAll('co_author_institution[]');

        for(let i=0; i<names.length; i++) {
            if (names[i].trim() !== '') {
                coAuthorsHtml += `<li><strong>${names[i]}</strong> (${desgs[i] || ''}, ${depts[i] || ''}, ${insts[i] || ''})</li>`;
            }
        }

        const html = `
            <div class="border-bottom pb-3 mb-3">
                <h4 class="fw-bold text-primary mb-2">${formData.get('abstract_title') || 'Untitled Abstract'}</h4>
                <div class="small text-secondary mb-1">
                    <strong>Presenting Author:</strong> ${formData.get('presenting_author_name')} (${formData.get('presenting_author_designation')}, ${formData.get('presenting_author_institution')})
                </div>
                ${coAuthorsHtml ? `<div class="small text-secondary mb-1"><strong>Co-Authors:</strong> <ol class="mb-0 ps-3">${coAuthorsHtml}</ol></div>` : ''}
                <div class="d-flex gap-2 mt-2 flex-wrap">
                    <span class="badge bg-label-primary text-primary">${formData.get('presentation_mode')}</span>
                    <span class="badge bg-label-info text-info">${formData.get('presenter_category')}</span>
                    <span class="badge bg-label-success text-success">${formData.get('conference_theme')}</span>
                </div>
            </div>

            <div class="mb-3">
                <strong class="text-dark">Keywords:</strong> <span class="fst-italic text-secondary">${formData.get('keywords') || 'N/A'}</span>
            </div>

            <div class="d-flex flex-column gap-3">
                <div><h6 class="fw-bold text-dark mb-1">Background</h6><p class="small text-secondary mb-0">${formData.get('abstract_background') || '-'}</p></div>
                <div><h6 class="fw-bold text-dark mb-1">Objectives</h6><p class="small text-secondary mb-0">${formData.get('abstract_objectives') || '-'}</p></div>
                <div><h6 class="fw-bold text-dark mb-1">Methodology</h6><p class="small text-secondary mb-0">${formData.get('abstract_methodology') || '-'}</p></div>
                <div><h6 class="fw-bold text-dark mb-1">Results</h6><p class="small text-secondary mb-0">${formData.get('abstract_results') || '-'}</p></div>
                <div><h6 class="fw-bold text-dark mb-1">Conclusion</h6><p class="small text-secondary mb-0">${formData.get('abstract_conclusion') || '-'}</p></div>
            </div>
        `;

        document.getElementById('modalPreviewContent').innerHTML = html;
        const modal = new bootstrap.Modal(document.getElementById('abstractPreviewModal'));
        modal.show();
    }

    function saveAsDraft() {
        const form = document.getElementById('abstractForm');
        const formData = new FormData(form);
        formData.append('action', 'save_draft');

        fetch("{{ route('abstract.store') }}", {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                alert(data.message || 'Abstract draft saved successfully!');
            } else {
                alert(data.message || 'Error saving draft.');
            }
        })
        .catch(err => {
            console.error(err);
            alert('An error occurred while saving draft.');
        });
    }

    function handleAbstractSubmit() {
        const form = document.getElementById('abstractForm');

        if (!form.checkValidity()) {
            form.reportValidity();
            return;
        }

        // Title word count check (Max 25 words)
        const titleVal = document.getElementById('abstract_title').value;
        const titleWords = countWordsInText(titleVal);
        if (titleWords > 25) {
            alert(`Abstract title exceeds maximum limit of 25 words. Current title word count: ${titleWords} words.`);
            document.getElementById('abstract_title').focus();
            return;
        }

        // Keywords count check (3 to 5 keywords separated by commas)
        const kwCount = countKeywords();
        if (kwCount < 3 || kwCount > 5) {
            alert('Please enter between 3 and 5 keywords separated by commas.');
            document.getElementById('keywordsError').classList.remove('d-none');
            document.getElementById('keywordsInput').focus();
            return;
        }

        // Structured body word count check (Max 300 words)
        const textareas = document.querySelectorAll('.abstract-body-part');
        let total = 0;
        textareas.forEach(ta => { total += countWordsInText(ta.value); });

        if (total > 300) {
            alert(`Your structured abstract exceeds the maximum 300-word limit. Current count: ${total} words. Please shorten your text before submitting.`);
            return;
        }

        // Review confirmation checkbox check
        if (!document.getElementById('confirmReview').checked) {
            alert('Please confirm that you have reviewed the abstract and author details by checking the declaration checkbox.');
            document.getElementById('confirmReview').focus();
            return;
        }

        const formData = new FormData(form);

        fetch("{{ route('abstract.store') }}", {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                document.getElementById('ackIDDisplay').innerText = data.acknowledgement_id || 'ABS-2027-SUCCESS';
                const modal = new bootstrap.Modal(document.getElementById('acknowledgementModal'));
                modal.show();
            } else {
                if (data.errors) {
                    const firstErr = Object.values(data.errors)[0];
                    alert(Array.isArray(firstErr) ? firstErr[0] : firstErr);
                } else {
                    alert(data.message || 'Validation error while submitting abstract.');
                }
            }
        })
        .catch(err => {
            console.error(err);
            alert('An error occurred while submitting the abstract.');
        });
    }
</script>
@endsection
