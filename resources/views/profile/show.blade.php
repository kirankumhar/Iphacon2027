@extends('shared.auth-delegate')
@section('title', 'My Profile - IPHACON 2027')

@php
    $inner_title = 'Delegate Profile';
    $primaryReg = $registration ?? ($user->registrations->first() ?? null);
    $abstracts = $abstractSubmissions ?? ($user->abstractSubmissions ?? collect());
    $latestAbs = $latestAbstract ?? ($abstracts->first() ?? null);
@endphp

@section('delegate-content')
<div class="container py-3 profile-page-container">

    {{-- Session & Alert Messages --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show py-2.5 px-3 mb-4 small fw-semibold shadow-xs"
            role="alert" style="border-radius: 12px; background-color: #DCFFF0; border-color: #6EE7B7; color: #065F46;">
            <div class="d-flex align-items-center">
                <i class="fas fa-check-circle me-2 fs-6"></i>
                <div class="flex-grow-1">{{ session('success') }}</div>
                <button type="button" class="btn-close btn-close-sm" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show py-2.5 px-3 mb-4 small fw-semibold shadow-xs"
            role="alert" style="border-radius: 12px; background-color: #FEE2E2; border-color: #FCA5A5; color: #991B1B;">
            <div class="d-flex align-items-center">
                <i class="fas fa-exclamation-circle me-2 fs-6"></i>
                <div class="flex-grow-1">{{ session('error') }}</div>
                <button type="button" class="btn-close btn-close-sm" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
    @endif

    @if (isset($errors) && $errors->any())
        <div class="alert alert-danger alert-dismissible fade show py-2.5 px-3 mb-4 small shadow-xs" role="alert"
            style="border-radius: 12px;">
            <div class="d-flex align-items-start gap-2">
                <i class="fas fa-exclamation-triangle mt-1"></i>
                <div class="flex-grow-1">
                    <strong class="d-block mb-1">Please correct the following:</strong>
                    <ul class="mb-0 ps-3">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                <button type="button" class="btn-close btn-close-sm" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
    @endif

    {{-- Reverted Alert Banner for Delegate --}}
    @if ($primaryReg && !empty($primaryReg->revert_reason) && ($primaryReg->status === 'Draft' || $primaryReg->status === 'Reverted' || !empty($primaryReg->reverted_at)))
        <div class="alert alert-warning border-0 shadow-sm p-3.5 mb-4 position-relative overflow-hidden"
            style="border-radius: 16px; background: linear-gradient(135deg, #FFFBEB 0%, #FEF3C7 100%); border-left: 5px solid #F59E0B !important;">
            <div class="d-flex align-items-start gap-3">
                <div class="rounded-circle p-2.5 d-flex align-items-center justify-content-center flex-shrink-0 shadow-xs"
                    style="width: 44px; height: 44px; background-color: #FDE68A; color: #B45309;">
                    <i class="fas fa-exclamation-triangle fs-5"></i>
                </div>
                <div class="flex-grow-1">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-1">
                        <h6 class="fw-bold mb-0 text-dark" style="font-size: 0.98rem;">
                            Action Required: Application Reverted for Modification
                        </h6>
                        @if ($primaryReg->reverted_at)
                            <span class="badge bg-warning bg-opacity-25 text-dark font-monospace extra-small px-2.5 py-1 rounded-pill border border-warning"
                                style="font-size: 0.72rem;">
                                <i class="far fa-clock me-1"></i>{{ \Carbon\Carbon::parse($primaryReg->reverted_at)->format('d M Y, h:i A') }}
                            </span>
                        @endif
                    </div>
                    <p class="text-dark mb-2" style="font-size: 0.88rem; line-height: 1.45;">
                        <strong>Admin Remarks:</strong> "{{ $primaryReg->revert_reason }}"
                    </p>
                    <div class="d-flex align-items-center gap-2 mt-2">
                        <a href="{{ route('registration.create') }}"
                            class="btn btn-sm btn-warning text-dark fw-bold px-3.5 py-1.5 shadow-xs rounded-pill"
                            style="font-size: 0.82rem;">
                            <i class="fas fa-edit me-1.5"></i>Update &amp; Resubmit Application
                        </a>
                        <a href="{{ route('registration.index') }}"
                            class="btn btn-sm btn-outline-secondary fw-semibold px-3 py-1.5 rounded-pill"
                            style="font-size: 0.82rem;">
                            View Details
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- Main Profile Hero Banner --}}
    <div class="card border-0 shadow-sm overflow-hidden mb-4 profile-hero-card"
        style="border-radius: 18px; background: linear-gradient(135deg, #01224d 0%, #013b82 45%, #0288d1 100%); box-shadow: 0 10px 30px rgba(1, 34, 77, 0.18) !important;">
        <div class="card-body p-4 p-md-4 text-white position-relative">
            <div class="row align-items-center g-4">
                
                {{-- Left: Avatar & Identity --}}
                <div class="col-lg-8">
                    <div class="d-flex flex-column flex-sm-row align-items-center align-items-sm-start gap-3.5 text-center text-sm-start">
                        
                        {{-- Avatar / Photo --}}
                        <div class="position-relative flex-shrink-0">
                            @if($primaryReg && $primaryReg->photo_path)
                                <img src="{{ asset('storage/' . $primaryReg->photo_path) }}"
                                     alt="{{ $user->full_name }}"
                                     class="rounded-circle shadow"
                                     style="width: 96px; height: 96px; object-fit: cover; border: 4px solid rgba(255, 255, 255, 0.9);">
                            @else
                                <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold shadow"
                                     style="width: 96px; height: 96px; font-size: 2.2rem; background: linear-gradient(135deg, #0288D1 0%, #00897B 100%); border: 4px solid rgba(255, 255, 255, 0.9);">
                                    {{ strtoupper(substr($user->full_name ?? 'D', 0, 1)) }}
                                </div>
                            @endif

                            {{-- Verification Badge Indicator --}}
                            @if ($user->hasVerifiedEmail())
                                <span class="position-absolute bottom-0 end-0 bg-success text-white rounded-circle d-flex align-items-center justify-content-center border border-2 border-white shadow-xs"
                                      style="width: 26px; height: 26px; font-size: 0.75rem;" title="Verified Email">
                                    <i class="fas fa-check"></i>
                                </span>
                            @endif
                        </div>

                        {{-- Details --}}
                        <div class="flex-grow-1 min-w-0">
                            {{-- Badges Row --}}
                            <div class="d-flex flex-wrap justify-content-center justify-content-sm-start align-items-center gap-2 mb-2">
                                <span class="badge bg-white text-primary px-3 py-1 rounded-pill fw-bold shadow-xs" style="font-size: 0.74rem;">
                                    <i class="fas fa-globe me-1"></i>{{ $user->delegate_type ?? 'Delegate' }}
                                </span>

                                @if ($user->hasVerifiedEmail())
                                    <span class="badge bg-success bg-opacity-90 text-white px-3 py-1 rounded-pill fw-semibold shadow-xs" style="font-size: 0.74rem;">
                                        <i class="fas fa-shield-alt me-1"></i>Email Verified
                                    </span>
                                @else
                                    <span class="badge bg-warning text-dark px-3 py-1 rounded-pill fw-semibold shadow-xs" style="font-size: 0.74rem;">
                                        <i class="fas fa-exclamation-triangle me-1"></i>Unverified
                                    </span>
                                @endif

                                @if($primaryReg)
                                    @php
                                        $regBadgeBg = '#E0F2FE';
                                        $regBadgeFg = '#0288D1';
                                        $regIcon = 'fa-clock';
                                        $regStatus = $primaryReg->status;
                                        if ($primaryReg->status === 'Approved') {
                                            $regBadgeBg = '#DCFFF0';
                                            $regBadgeFg = '#059669';
                                            $regIcon = 'fa-check-circle';
                                        } elseif ($primaryReg->status === 'Rejected') {
                                            $regBadgeBg = '#FEE2E2';
                                            $regBadgeFg = '#DC2626';
                                            $regIcon = 'fa-times-circle';
                                        } elseif (in_array($primaryReg->status, ['Payment Submitted', 'Submitted', 'Pending Payment', 'Pending'])) {
                                            $regBadgeBg = '#FEF9C3';
                                            $regBadgeFg = '#B45309';
                                            $regIcon = 'fa-hourglass-half';
                                            $regStatus = 'Under Verification';
                                        }
                                    @endphp
                                    <span class="badge px-3 py-1 rounded-pill fw-bold shadow-xs"
                                          style="font-size: 0.74rem; background-color: {{ $regBadgeBg }}; color: {{ $regBadgeFg }};">
                                        <i class="fas {{ $regIcon }} me-1"></i>{{ $regStatus }}
                                    </span>
                                @else
                                    <span class="badge bg-light text-secondary px-3 py-1 rounded-pill fw-semibold" style="font-size: 0.74rem;">
                                        <i class="fas fa-info-circle me-1"></i>Not Registered
                                    </span>
                                @endif
                            </div>

                            {{-- Name & Title --}}
                            <h3 class="fw-bold mb-1 text-white text-truncate" style="letter-spacing: -0.3px; font-size: 1.55rem;">
                                {{ $user->prefix }} {{ $user->full_name }}
                            </h3>

                            {{-- Designation & Institution --}}
                            <p class="text-white text-opacity-90 mb-2 small" style="font-size: 0.9rem;">
                                @php
                                    $currentDesignation = $user->designation ?: ($primaryReg->designation ?? null);
                                    $currentInstitution = $user->institution ?: ($primaryReg->institution ?? null);
                                @endphp
                                @if($currentDesignation)
                                    <span class="fw-semibold text-warning">{{ $currentDesignation }}</span>
                                @endif
                                @if($currentDesignation && $currentInstitution)
                                    <span class="mx-1.5 opacity-60">•</span>
                                @endif
                                @if($currentInstitution)
                                    <span>{{ $currentInstitution }}</span>
                                @endif
                                @if(!$currentDesignation && !$currentInstitution)
                                    <span class="text-white-50 fst-italic">Designation & Institution not set</span>
                                @endif
                            </p>

                            {{-- Quick Meta (Reg No, Email, Mobile) --}}
                            <div class="d-flex flex-wrap justify-content-center justify-content-sm-start gap-3 small text-white text-opacity-80" style="font-size: 0.82rem;">
                                @if($primaryReg && $primaryReg->status === 'Approved')
                                    <span><i class="fas fa-id-card text-warning me-1"></i>Reg No: <strong>{{ $primaryReg->registration_number }}</strong></span>
                                @elseif($primaryReg && $primaryReg->acknowledgement_id)
                                    <span><i class="fas fa-receipt text-warning me-1"></i>Ack ID: <strong>{{ $primaryReg->acknowledgement_id }}</strong></span>
                                @endif
                                <span><i class="fas fa-envelope text-warning me-1"></i>{{ $user->email }}</span>
                                <span><i class="fas fa-phone-alt text-warning me-1"></i>{{ $user->mobile_country_code }} {{ $user->mobile_number }}</span>
                            </div>
                        </div>

                    </div>
                </div>

                {{-- Right: Quick Action Buttons --}}
                <div class="col-lg-4 text-center text-lg-end">
                    <div class="d-flex flex-wrap flex-lg-column justify-content-center justify-content-lg-end gap-2">
                        
                        {{-- Edit Profile Modal Trigger --}}
                        <button type="button" class="btn btn-warning text-dark fw-bold btn-sm px-3.5 py-2 rounded-pill shadow-sm"
                                data-bs-toggle="modal" data-bs-target="#editProfileModal"
                                data-toggle="modal" data-target="#editProfileModal"
                                style="background: #F59E0B; border: none; font-size: 0.85rem;">
                            <i class="fas fa-user-edit me-1.5"></i>Edit Profile
                        </button>

                        {{-- Change Password --}}
                        <a href="{{ route('profile.change-password') }}" class="btn btn-outline-light btn-sm px-3.5 py-2 rounded-pill fw-semibold shadow-xs"
                           style="border-color: rgba(255,255,255,0.4); font-size: 0.85rem;">
                            <i class="fas fa-key me-1.5"></i>Change Password
                        </a>

                        {{-- Download Receipt / Registration Slip --}}
                        @if($primaryReg && $primaryReg->status === 'Approved' && !empty($primaryReg->registration_number))
                            <a href="{{ route('delgate.download.receipt', $primaryReg->registration_number) }}"
                               class="btn btn-success btn-sm px-3.5 py-2 rounded-pill fw-bold shadow-sm"
                               style="background: #10B981; border: none; font-size: 0.85rem;">
                                <i class="fas fa-file-download me-1.5"></i>Download Receipt
                            </a>
                        @elseif(!$primaryReg)
                            <a href="{{ route('registration.create') }}" class="btn btn-light text-primary btn-sm px-3.5 py-2 rounded-pill fw-bold shadow-sm"
                               style="font-size: 0.85rem;">
                                <i class="fas fa-paper-plane me-1.5"></i>Register for Conference
                            </a>
                        @elseif($primaryReg->status === 'Draft')
                            <a href="{{ route('registration.create') }}" class="btn btn-light text-primary btn-sm px-3.5 py-2 rounded-pill fw-bold shadow-sm"
                               style="font-size: 0.85rem;">
                                <i class="fas fa-arrow-right me-1.5"></i>Complete Registration
                            </a>
                        @endif

                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- Metrics / Quick Stat Strip --}}
    <div class="row g-3 mb-4">
        
        {{-- Card 1: Registration Status --}}
        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm h-100 p-3 profile-metric-card" style="border-radius: 14px; background: #ffffff; border-left: 4px solid #0288D1 !important;">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-3 d-flex align-items-center justify-content-center text-primary flex-shrink-0"
                         style="width: 44px; height: 44px; background: #E0F2FE;">
                        <i class="fas fa-id-badge fs-5"></i>
                    </div>
                    <div class="min-w-0">
                        <span class="text-muted d-block extra-small text-uppercase fw-semibold" style="font-size: 0.72rem; letter-spacing: 0.4px;">Registration</span>
                        <h6 class="fw-bold mb-0 text-dark text-truncate" style="font-size: 0.95rem;">
                            {{ $primaryReg ? $primaryReg->status : 'Not Started' }}
                        </h6>
                        <span class="text-secondary extra-small d-block text-truncate" style="font-size: 0.75rem;">
                            {{ $primaryReg->registration_number ?? ($primaryReg->acknowledgement_id ?? 'No Reg ID') }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Card 2: Delegate Category --}}
        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm h-100 p-3 profile-metric-card" style="border-radius: 14px; background: #ffffff; border-left: 4px solid #00897B !important;">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-3 d-flex align-items-center justify-content-center text-success flex-shrink-0"
                         style="width: 44px; height: 44px; background: #DCFCE7;">
                        <i class="fas fa-award fs-5"></i>
                    </div>
                    <div class="min-w-0">
                        <span class="text-muted d-block extra-small text-uppercase fw-semibold" style="font-size: 0.72rem; letter-spacing: 0.4px;">Category</span>
                        <h6 class="fw-bold mb-0 text-dark text-truncate" style="font-size: 0.95rem;">
                            {{ $primaryReg->delegateCategory->name ?? ($user->delegate_type ?? 'Delegate') }}
                        </h6>
                        <span class="text-secondary extra-small d-block text-truncate" style="font-size: 0.75rem;">
                            @if($primaryReg && $primaryReg->delegate_fee)
                                Fee: ₹{{ number_format($primaryReg->delegate_fee, 2) }}
                            @else
                                {{ $user->delegate_type ?? 'General' }}
                            @endif
                        </span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Card 3: CME Workshop --}}
        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm h-100 p-3 profile-metric-card" style="border-radius: 14px; background: #ffffff; border-left: 4px solid #F59E0B !important;">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-3 d-flex align-items-center justify-content-center text-warning flex-shrink-0"
                         style="width: 44px; height: 44px; background: #FEF3C7;">
                        <i class="fas fa-chalkboard-teacher fs-5"></i>
                    </div>
                    <div class="min-w-0">
                        <span class="text-muted d-block extra-small text-uppercase fw-semibold" style="font-size: 0.72rem; letter-spacing: 0.4px;">Pre-Conf Workshop</span>
                        <h6 class="fw-bold mb-0 text-dark text-truncate" style="font-size: 0.95rem;">
                            @if($primaryReg && $primaryReg->participate_in_cme)
                                {{ $primaryReg->cme_status ?? 'Enrolled' }}
                            @else
                                Not Enrolled
                            @endif
                        </h6>
                        <span class="text-secondary extra-small d-block text-truncate" style="font-size: 0.75rem;">
                            {{ $primaryReg && $primaryReg->participate_in_cme ? 'Workshop Opted' : 'CME Available' }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Card 4: Abstract Submissions --}}
        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm h-100 p-3 profile-metric-card" style="border-radius: 14px; background: #ffffff; border-left: 4px solid #8B5CF6 !important;">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-3 d-flex align-items-center justify-content-center text-purple flex-shrink-0"
                         style="width: 44px; height: 44px; background: #F3E8FF; color: #8B5CF6;">
                        <i class="fas fa-file-alt fs-5"></i>
                    </div>
                    <div class="min-w-0">
                        <span class="text-muted d-block extra-small text-uppercase fw-semibold" style="font-size: 0.72rem; letter-spacing: 0.4px;">Abstracts</span>
                        <h6 class="fw-bold mb-0 text-dark text-truncate" style="font-size: 0.95rem;">
                            {{ $abstracts->count() }} {{ Str::plural('Submission', $abstracts->count()) }}
                        </h6>
                        <span class="text-secondary extra-small d-block text-truncate" style="font-size: 0.75rem;">
                            {{ $latestAbs ? ($latestAbs->presentation_mode ?? 'Submitted') : 'None yet' }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

    </div>

    {{-- Main Content Grid --}}
    <div class="row g-4">
        
        {{-- Left Column: Personal, Professional, Contact, Registration, Payments --}}
        <div class="col-lg-8">

            {{-- 1. Personal & Identity Details --}}
            <div class="card border-0 shadow-sm mb-4 profile-section-card" style="border-radius: 16px; background: #ffffff;">
                <div class="card-header bg-transparent py-3 px-4 border-bottom d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center gap-2">
                        <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; background: #E0F2FE; color: #0288D1;">
                            <i class="fas fa-user fs-6"></i>
                        </div>
                        <h6 class="fw-bold mb-0 text-dark" style="font-size: 0.95rem;">Personal &amp; Demographic Details</h6>
                    </div>
                    <button type="button" class="btn btn-sm btn-light text-primary px-3 py-1 rounded-pill fw-semibold"
                            data-bs-toggle="modal" data-bs-target="#editProfileModal"
                            data-toggle="modal" data-target="#editProfileModal"
                            style="font-size: 0.8rem; background: #F0F9FF;">
                        <i class="fas fa-pen me-1"></i>Edit
                    </button>
                </div>
                <div class="card-body p-4">
                    <div class="row g-3">
                        
                        <div class="col-sm-6">
                            <div class="p-2.5 rounded-3 bg-light border-0">
                                <span class="text-muted d-block extra-small">Title &amp; Full Name</span>
                                <span class="fw-bold text-dark" style="font-size: 0.9rem;">
                                    {{ $user->prefix }} {{ $user->full_name }}
                                </span>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="p-2.5 rounded-3 bg-light border-0">
                                <span class="text-muted d-block extra-small">Gender</span>
                                <span class="fw-bold text-dark" style="font-size: 0.9rem;">
                                    <i class="fas {{ $user->gender === 'Female' ? 'fa-venus text-danger' : 'fa-mars text-primary' }} me-1"></i>
                                    {{ $user->gender ?: 'Not specified' }}
                                </span>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="p-2.5 rounded-3 bg-light border-0">
                                <span class="text-muted d-block extra-small">Date of Birth &amp; Age</span>
                                <span class="fw-bold text-dark" style="font-size: 0.9rem;">
                                    @if($user->date_of_birth)
                                        <i class="fas fa-birthday-cake text-warning me-1"></i>
                                        {{ $user->date_of_birth->format('d M, Y') }}
                                        <span class="text-muted fw-normal ms-1">({{ $user->date_of_birth->age }} years)</span>
                                    @else
                                        N/A
                                    @endif
                                </span>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="p-2.5 rounded-3 bg-light border-0">
                                <span class="text-muted d-block extra-small">Dietary Preference</span>
                                <span class="fw-bold text-dark" style="font-size: 0.9rem;">
                                    <i class="fas fa-utensils text-success me-1"></i>
                                    {{ $primaryReg->dietary_preference ?? 'Standard / Not Specified' }}
                                </span>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="p-2.5 rounded-3 bg-light border-0">
                                <span class="text-muted d-block extra-small">Country of Origin</span>
                                <span class="fw-bold text-dark" style="font-size: 0.9rem;">
                                    <i class="fas fa-flag text-primary me-1"></i>
                                    {{ $user->country->country_name ?? ($primaryReg->country->country_name ?? 'India') }}
                                </span>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="p-2.5 rounded-3 bg-light border-0">
                                <span class="text-muted d-block extra-small">ID Proof Verification</span>
                                <div class="d-flex align-items-center justify-content-between">
                                    <span class="fw-bold text-dark" style="font-size: 0.9rem;">
                                        @if($primaryReg && !empty($primaryReg->id_proof_type))
                                            {{ $primaryReg->id_proof_type }}: 
                                            <span class="font-monospace text-secondary">{{ $primaryReg->masked_id_proof_number }}</span>
                                        @else
                                            <span class="text-muted fw-normal">Not Provided</span>
                                        @endif
                                    </span>
                                    @if($primaryReg && !empty($primaryReg->id_proof_document_path))
                                        <a href="{{ asset('storage/' . $primaryReg->id_proof_document_path) }}" target="_blank"
                                           class="btn btn-outline-primary btn-xs py-0.5 px-2 rounded-pill extra-small"
                                           title="View Uploaded Document">
                                            <i class="fas fa-eye me-1"></i>View Doc
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            {{-- 2. Professional & Academic Affiliation --}}
            <div class="card border-0 shadow-sm mb-4 profile-section-card" style="border-radius: 16px; background: #ffffff;">
                <div class="card-header bg-transparent py-3 px-4 border-bottom d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center gap-2">
                        <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; background: #DCFCE7; color: #00897B;">
                            <i class="fas fa-briefcase fs-6"></i>
                        </div>
                        <h6 class="fw-bold mb-0 text-dark" style="font-size: 0.95rem;">Professional &amp; Academic Affiliation</h6>
                    </div>
                    <button type="button" class="btn btn-sm btn-light text-primary px-3 py-1 rounded-pill fw-semibold"
                            data-bs-toggle="modal" data-bs-target="#editProfileModal"
                            data-toggle="modal" data-target="#editProfileModal"
                            style="font-size: 0.8rem; background: #F0F9FF;">
                        <i class="fas fa-pen me-1"></i>Update
                    </button>
                </div>
                <div class="card-body p-4">
                    <div class="row g-3">
                        
                        <div class="col-sm-6">
                            <div class="p-2.5 rounded-3 bg-light border-0">
                                <span class="text-muted d-block extra-small">Current Designation</span>
                                <span class="fw-bold text-dark" style="font-size: 0.9rem;">
                                    <i class="fas fa-user-md text-primary me-1"></i>
                                    {{ $user->designation ?: ($primaryReg->designation ?? 'Not specified') }}
                                    @if($primaryReg && !empty($primaryReg->other_designation))
                                        <span class="text-muted fw-normal">({{ $primaryReg->other_designation }})</span>
                                    @endif
                                </span>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="p-2.5 rounded-3 bg-light border-0">
                                <span class="text-muted d-block extra-small">Institution / College / Hospital</span>
                                <span class="fw-bold text-dark" style="font-size: 0.9rem;">
                                    <i class="fas fa-university text-secondary me-1"></i>
                                    {{ $user->institution ?: ($primaryReg->institution ?? 'Not specified') }}
                                </span>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="p-2.5 rounded-3 bg-light border-0">
                                <span class="text-muted d-block extra-small">Medical Council Reg. No.</span>
                                <span class="fw-bold text-dark" style="font-size: 0.9rem;">
                                    <i class="fas fa-stethoscope text-info me-1"></i>
                                    {{ $latestAbs->medical_council_reg_no ?? 'Provided in Submissions' }}
                                </span>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="p-2.5 rounded-3 bg-light border-0">
                                <span class="text-muted d-block extra-small">Delegate Category</span>
                                <span class="fw-bold text-primary" style="font-size: 0.9rem;">
                                    <i class="fas fa-id-card-alt me-1"></i>
                                    {{ $primaryReg->delegateCategory->name ?? ($user->delegate_type ?? 'Delegate') }}
                                </span>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            {{-- 3. Contact & Communication Details --}}
            <div class="card border-0 shadow-sm mb-4 profile-section-card" style="border-radius: 16px; background: #ffffff;">
                <div class="card-header bg-transparent py-3 px-4 border-bottom d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center gap-2">
                        <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; background: #FEF3C7; color: #B45309;">
                            <i class="fas fa-address-book fs-6"></i>
                        </div>
                        <h6 class="fw-bold mb-0 text-dark" style="font-size: 0.95rem;">Contact &amp; Postal Address</h6>
                    </div>
                </div>
                <div class="card-body p-4">
                    <div class="row g-3">
                        
                        <div class="col-sm-6">
                            <div class="p-2.5 rounded-3 bg-light border-0">
                                <span class="text-muted d-block extra-small">Official Email Address</span>
                                <div class="d-flex align-items-center justify-content-between gap-2">
                                    <span class="fw-bold text-dark text-break" style="font-size: 0.88rem;">
                                        <i class="fas fa-envelope text-secondary me-1"></i>{{ $user->email }}
                                    </span>
                                    @if ($user->hasVerifiedEmail())
                                        <span class="badge bg-success-subtle text-success border border-success-subtle extra-small px-2 py-0.5 rounded-pill">
                                            Verified
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="p-2.5 rounded-3 bg-light border-0">
                                <span class="text-muted d-block extra-small">Mobile Phone (Calling)</span>
                                <span class="fw-bold text-dark" style="font-size: 0.88rem;">
                                    <i class="fas fa-phone text-success me-1"></i>
                                    {{ $user->mobile_country_code }} {{ $user->mobile_number }}
                                </span>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="p-2.5 rounded-3 bg-light border-0">
                                <span class="text-muted d-block extra-small">WhatsApp Number</span>
                                <span class="fw-bold text-dark" style="font-size: 0.88rem;">
                                    <i class="fab fa-whatsapp text-success me-1"></i>
                                    @if($primaryReg && !empty($primaryReg->whatsapp_number))
                                        {{ $primaryReg->whatsapp_country_code }} {{ $primaryReg->whatsapp_number }}
                                    @else
                                        <span class="text-muted fw-normal">Same as Mobile</span>
                                    @endif
                                </span>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="p-2.5 rounded-3 bg-light border-0">
                                <span class="text-muted d-block extra-small">State &amp; City</span>
                                <span class="fw-bold text-dark" style="font-size: 0.88rem;">
                                    <i class="fas fa-map-marker-alt text-danger me-1"></i>
                                    @if($primaryReg)
                                        {{ $primaryReg->city ?: 'N/A' }}, 
                                        {{ $primaryReg->state->state_name ?? ($primaryReg->other_state ?? 'N/A') }}
                                        @if($primaryReg->pin_code)
                                            - {{ $primaryReg->pin_code }}
                                        @endif
                                    @else
                                        {{ $user->country->country_name ?? 'India' }}
                                    @endif
                                </span>
                            </div>
                        </div>

                        @if($primaryReg && !empty($primaryReg->address))
                            <div class="col-12">
                                <div class="p-2.5 rounded-3 bg-light border-0">
                                    <span class="text-muted d-block extra-small">Postal / Residential Address</span>
                                    <span class="fw-semibold text-dark" style="font-size: 0.88rem;">
                                        <i class="fas fa-home text-secondary me-1"></i>
                                        {{ $primaryReg->address }}, {{ $primaryReg->city }}, 
                                        {{ $primaryReg->state->state_name ?? ($primaryReg->other_state ?? '') }} 
                                        {{ $primaryReg->pin_code ? '(' . $primaryReg->pin_code . ')' : '' }}
                                    </span>
                                </div>
                            </div>
                        @endif

                    </div>
                </div>
            </div>

            {{-- 4. Conference Registration & Professional Memberships --}}
            @if($primaryReg)
                <div class="card border-0 shadow-sm mb-4 profile-section-card" style="border-radius: 16px; background: #ffffff;">
                    <div class="card-header bg-transparent py-3 px-4 border-bottom d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center gap-2">
                            <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; background: #F3E8FF; color: #8B5CF6;">
                                <i class="fas fa-id-card fs-6"></i>
                            </div>
                            <h6 class="fw-bold mb-0 text-dark" style="font-size: 0.95rem;">Conference Details &amp; Memberships</h6>
                        </div>
                        <a href="{{ route('registration.index') }}" class="btn btn-sm btn-light text-primary px-3 py-1 rounded-pill fw-semibold"
                           style="font-size: 0.8rem; background: #F0F9FF;">
                            <i class="fas fa-external-link-alt me-1"></i>Registration Hub
                        </a>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-3">
                            
                            <div class="col-sm-6">
                                <div class="p-2.5 rounded-3 bg-light border-0">
                                    <span class="text-muted d-block extra-small">Registration Number</span>
                                    <span class="fw-bold text-dark font-monospace" style="font-size: 0.95rem;">
                                        {{ $primaryReg->registration_number ?: 'Pending Approval' }}
                                    </span>
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="p-2.5 rounded-3 bg-light border-0">
                                    <span class="text-muted d-block extra-small">Acknowledgement ID</span>
                                    <span class="fw-bold text-dark font-monospace" style="font-size: 0.95rem;">
                                        {{ $primaryReg->acknowledgement_id ?: 'N/A' }}
                                    </span>
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="p-2.5 rounded-3 bg-light border-0">
                                    <span class="text-muted d-block extra-small">Accompanying Person(s)</span>
                                    <span class="fw-bold text-dark" style="font-size: 0.9rem;">
                                        <i class="fas fa-user-friends text-secondary me-1"></i>
                                        {{ $primaryReg->accompanying_persons ?? 0 }} Person(s)
                                        @if(($primaryReg->accompanying_fee ?? 0) > 0)
                                            <span class="text-muted fw-normal ms-1">(₹{{ number_format($primaryReg->accompanying_fee, 2) }})</span>
                                        @endif
                                    </span>
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="p-2.5 rounded-3 bg-light border-0">
                                    <span class="text-muted d-block extra-small">IPHA Life Membership No.</span>
                                    <span class="fw-bold text-dark" style="font-size: 0.9rem;">
                                        {{ $primaryReg->membership_no ?: 'Non-Member / Not provided' }}
                                    </span>
                                </div>
                            </div>

                            {{-- Association Memberships Sub-grid --}}
                            <div class="col-12">
                                <div class="p-3 rounded-3" style="background: #F8FAFC; border: 1px dashed #CBD5E1;">
                                    <span class="fw-semibold text-secondary d-block mb-2 extra-small text-uppercase" style="letter-spacing: 0.3px;">
                                        Affiliated Associations Membership Status
                                    </span>
                                    <div class="row g-2">
                                        <div class="col-md-4">
                                            <div class="d-flex align-items-center justify-content-between p-2 rounded bg-white border">
                                                <span class="small text-muted">ISMM Member:</span>
                                                <span class="badge {{ $primaryReg->is_ismm_member ? 'bg-success' : 'bg-light text-secondary' }} rounded-pill">
                                                    {{ $primaryReg->is_ismm_member ? 'Yes (' . ($primaryReg->ismm_membership_no ?: 'Reg') . ')' : 'No' }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="d-flex align-items-center justify-content-between p-2 rounded bg-white border">
                                                <span class="small text-muted">ISHAM Member:</span>
                                                <span class="badge {{ $primaryReg->is_isham_member ? 'bg-success' : 'bg-light text-secondary' }} rounded-pill">
                                                    {{ $primaryReg->is_isham_member ? 'Yes (' . ($primaryReg->isham_membership_no ?: 'Reg') . ')' : 'No' }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="d-flex align-items-center justify-content-between p-2 rounded bg-white border">
                                                <span class="small text-muted">Young ISAM:</span>
                                                <span class="badge {{ $primaryReg->is_young_isam_member ? 'bg-success' : 'bg-light text-secondary' }} rounded-pill">
                                                    {{ $primaryReg->is_young_isam_member ? 'Yes (' . ($primaryReg->young_isam_membership_no ?: 'Reg') . ')' : 'No' }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            @else
                {{-- Banner if not registered yet --}}
                <div class="card border-0 shadow-sm mb-4 p-4 text-center" style="border-radius: 16px; background: linear-gradient(135deg, #F0F9FF 0%, #E0F2FE 100%); border: 1px dashed #0288D1 !important;">
                    <div class="py-2">
                        <i class="fas fa-clipboard-list text-primary mb-2" style="font-size: 2.2rem;"></i>
                        <h5 class="fw-bold text-dark mb-1">Conference Registration Not Found</h5>
                        <p class="text-secondary small mb-3">You have not completed your conference delegate registration yet. Register today to attend plenaries, CME workshops, and scientific sessions.</p>
                        <a href="{{ route('registration.create') }}" class="btn btn-primary btn-sm px-4 py-2 rounded-pill fw-bold shadow-xs">
                            <i class="fas fa-paper-plane me-1.5"></i>Start Conference Registration
                        </a>
                    </div>
                </div>
            @endif

            {{-- 5. Billing Breakdown & Payment History --}}
            @if($primaryReg && ($primaryReg->payments->isNotEmpty() || $primaryReg->total_amount > 0))
                <div class="card border-0 shadow-sm mb-4 profile-section-card" style="border-radius: 16px; background: #ffffff;">
                    <div class="card-header bg-transparent py-3 px-4 border-bottom d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center gap-2">
                            <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; background: #E0E7FF; color: #4338CA;">
                                <i class="fas fa-receipt fs-6"></i>
                            </div>
                            <h6 class="fw-bold mb-0 text-dark" style="font-size: 0.95rem;">Payment &amp; Billing History</h6>
                        </div>
                        @if($primaryReg->status === 'Approved' && !empty($primaryReg->registration_number))
                            <a href="{{ route('delgate.download.receipt', $primaryReg->registration_number) }}"
                               class="btn btn-sm btn-outline-success px-3 py-1 rounded-pill fw-semibold" style="font-size: 0.8rem;">
                                <i class="fas fa-download me-1"></i>Receipt PDF
                            </a>
                        @endif
                    </div>
                    <div class="card-body p-4">
                        
                        {{-- Fee Breakdown Row --}}
                        <div class="row g-2 mb-3">
                            <div class="col-6 col-md-3">
                                <div class="p-2.5 rounded bg-light border text-center">
                                    <span class="text-muted d-block extra-small">Delegate Fee</span>
                                    <span class="fw-bold text-dark" style="font-size: 0.9rem;">
                                        ₹{{ number_format($primaryReg->delegate_fee ?? 0, 2) }}
                                    </span>
                                </div>
                            </div>
                            <div class="col-6 col-md-3">
                                <div class="p-2.5 rounded bg-light border text-center">
                                    <span class="text-muted d-block extra-small">Accompanying Fee</span>
                                    <span class="fw-bold text-dark" style="font-size: 0.9rem;">
                                        ₹{{ number_format($primaryReg->accompanying_fee ?? 0, 2) }}
                                    </span>
                                </div>
                            </div>
                            <div class="col-6 col-md-3">
                                <div class="p-2.5 rounded bg-light border text-center">
                                    <span class="text-muted d-block extra-small">CME Workshop Fee</span>
                                    <span class="fw-bold text-dark" style="font-size: 0.9rem;">
                                        ₹{{ number_format($primaryReg->cme_fee ?? 0, 2) }}
                                    </span>
                                </div>
                            </div>
                            <div class="col-6 col-md-3">
                                <div class="p-2.5 rounded bg-light border text-center" style="background: #ECFDF5 !important; border-color: #A7F3D0 !important;">
                                    <span class="text-muted d-block extra-small">Total Paid Amount</span>
                                    <span class="fw-bold text-success" style="font-size: 0.9rem;">
                                        ₹{{ number_format($primaryReg->totalPaidAmount(), 2) }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        {{-- Payment Transactions Table --}}
                        @if($primaryReg->payments->isNotEmpty())
                            <div class="table-responsive rounded-3 border">
                                <table class="table table-hover align-middle mb-0 text-nowrap" style="font-size: 0.85rem;">
                                    <thead class="table-light">
                                        <tr>
                                            <th class="ps-3 py-2.5">Txn ID / Ref</th>
                                            <th class="py-2.5">Gateway / Mode</th>
                                            <th class="py-2.5">Amount</th>
                                            <th class="py-2.5">Date</th>
                                            <th class="pe-3 py-2.5 text-end">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($primaryReg->payments as $payment)
                                            @php
                                                $payStatus = strtoupper($payment->payment_status ?? 'PENDING');
                                                $badgeClass = 'bg-warning text-dark';
                                                if (in_array($payStatus, ['SUCCESS', 'PAID', 'APPROVED', 'COMPLETED', '0300', 'OTS0000']) || $payment->admin_verified) {
                                                    $badgeClass = 'bg-success text-white';
                                                    $payStatus = 'SUCCESS';
                                                } elseif (in_array($payStatus, ['FAILED', 'FAILURE', 'DECLINED', 'REJECTED'])) {
                                                    $badgeClass = 'bg-danger text-white';
                                                }
                                            @endphp
                                            <tr>
                                                <td class="ps-3 fw-bold font-monospace text-dark">
                                                    {{ $payment->transaction_id ?: ($payment->gateway_transaction_id ?: '#' . $payment->id) }}
                                                </td>
                                                <td>
                                                    <span class="badge bg-light text-dark border">
                                                        {{ $payment->payment_method ?: 'Online Gateway' }}
                                                    </span>
                                                </td>
                                                <td class="fw-bold text-dark">
                                                    ₹{{ number_format($payment->total_amount, 2) }}
                                                </td>
                                                <td class="text-secondary">
                                                    {{ $payment->payment_date ? $payment->payment_date->format('d M Y, h:i A') : ($payment->created_at ? $payment->created_at->format('d M Y, h:i A') : 'N/A') }}
                                                </td>
                                                <td class="pe-3 text-end">
                                                    <span class="badge {{ $badgeClass }} px-2.5 py-1 rounded-pill extra-small">
                                                        {{ $payStatus }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-muted small p-2 bg-light rounded text-center">
                                No online payment transaction recorded yet.
                            </div>
                        @endif

                    </div>
                </div>
            @endif

        </div>

        {{-- Right Column: Pre-Conference Workshop, Abstract Submissions, Account Security, Secretariat --}}
        <div class="col-lg-4">

            {{-- 1. Pre-Conference CME Workshop Card --}}
            <div class="card border-0 shadow-sm mb-4 profile-side-card" style="border-radius: 16px; background: #ffffff;">
                <div class="card-header bg-transparent py-3 px-3.5 border-bottom d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center gap-2">
                        <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 30px; height: 30px; background: #FEF3C7; color: #B45309;">
                            <i class="fas fa-stethoscope fs-6"></i>
                        </div>
                        <h6 class="fw-bold mb-0 text-dark" style="font-size: 0.92rem;">Pre-Conference Workshop</h6>
                    </div>
                    @if($primaryReg && $primaryReg->participate_in_cme)
                        <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-0.5 rounded-pill extra-small">
                            Enrolled
                        </span>
                    @endif
                </div>
                <div class="card-body p-3.5">
                    @if($primaryReg && $primaryReg->participate_in_cme)
                        <div class="mb-3">
                            <span class="text-muted d-block extra-small mb-1">Selected Workshop Topic:</span>
                            <div class="p-2.5 rounded-3 bg-light border-0 fw-semibold text-dark small" style="line-height: 1.45;">
                                <i class="fas fa-book-medical text-primary me-1.5"></i>
                                {{ $primaryReg->cme_topic ?? 'General Pre-Conference Workshop' }}
                            </div>
                        </div>
                        <div class="d-flex justify-content-between align-items-center small mb-3 p-2 rounded bg-light">
                            <span class="text-muted">Workshop Status:</span>
                            <span class="fw-bold text-primary">{{ $primaryReg->cme_status ?? 'Enrolled' }}</span>
                        </div>
                    @else
                        <p class="text-secondary small mb-3" style="font-size: 0.84rem; line-height: 1.45;">
                            Boost your skills with state-of-the-art hands-on workshops in epidemiology, biostatistics, modelling &amp; public health.
                        </p>
                        @php
                            $isRegPaid = $primaryReg && in_array($primaryReg->status, ['Approved', 'Payment Submitted']);
                            $isCmeApproved = $primaryReg ? $primaryReg->isCmeApproved() : false;
                            $isCmePending = $primaryReg ? $primaryReg->isCmePendingVerification() : false;
                        @endphp
                        @if($isRegPaid && !$isCmeApproved && !$isCmePending)
                            <a href="{{ route('cme.apply') }}" class="btn btn-outline-success btn-sm w-100 py-2 rounded-pill fw-bold shadow-xs" style="font-size: 0.82rem;">
                                <i class="fas fa-plus-circle me-1.5"></i>Apply for Workshop
                            </a>
                        @else
                            <div class="badge bg-light text-secondary border w-100 py-2 rounded-pill font-semibold small">
                                Available after Conference Registration
                            </div>
                        @endif
                    @endif
                </div>
            </div>

            {{-- 2. Abstract Submissions Summary Card --}}
            <div class="card border-0 shadow-sm mb-4 profile-side-card" style="border-radius: 16px; background: #ffffff;">
                <div class="card-header bg-transparent py-3 px-3.5 border-bottom d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center gap-2">
                        <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 30px; height: 30px; background: #F3E8FF; color: #8B5CF6;">
                            <i class="fas fa-file-alt fs-6"></i>
                        </div>
                        <h6 class="fw-bold mb-0 text-dark" style="font-size: 0.92rem;">Abstract Submissions</h6>
                    </div>
                    <span class="badge bg-primary px-2 py-0.5 rounded-pill extra-small">
                        {{ $abstracts->count() }}
                    </span>
                </div>
                <div class="card-body p-3.5">
                    @if($abstracts->isNotEmpty())
                        <div class="d-flex flex-column gap-2.5 mb-3">
                            @foreach($abstracts as $abs)
                                <div class="p-2.5 rounded-3 border bg-light">
                                    <div class="d-flex align-items-start justify-content-between gap-2 mb-1">
                                        <span class="badge bg-white text-dark border font-monospace extra-small">
                                            {{ $abs->acknowledgement_id }}
                                        </span>
                                        @php
                                            $absBadge = 'bg-warning text-dark';
                                            if (in_array(strtolower($abs->status), ['approved', 'accepted'])) {
                                                $absBadge = 'bg-success text-white';
                                            } elseif (in_array(strtolower($abs->status), ['rejected', 'declined'])) {
                                                $absBadge = 'bg-danger text-white';
                                            }
                                        @endphp
                                        <span class="badge {{ $absBadge }} extra-small rounded-pill">
                                            {{ $abs->status ?: 'Pending Review' }}
                                        </span>
                                    </div>
                                    <h6 class="fw-bold text-dark mb-1 text-truncate" style="font-size: 0.85rem;" title="{{ $abs->abstract_title }}">
                                        {{ $abs->abstract_title }}
                                    </h6>
                                    <div class="d-flex align-items-center justify-content-between text-muted extra-small">
                                        <span><i class="fas fa-microphone me-1 text-secondary"></i>{{ $abs->presentation_mode }}</span>
                                        <a href="{{ route('abstract.show', $abs->id) }}" class="text-primary fw-semibold">
                                            View <i class="fas fa-chevron-right ms-0.5"></i>
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-secondary small mb-3" style="font-size: 0.84rem; line-height: 1.45;">
                            Submit your research abstracts for Oral &amp; Poster presentations at IPHACON 2027.
                        </p>
                    @endif

                    <a href="{{ route('abstract.create') }}" class="btn btn-primary btn-sm w-100 py-2 rounded-pill fw-bold shadow-xs"
                       style="background: linear-gradient(135deg, #013069, #0288D1); border: none; font-size: 0.82rem;">
                        <i class="fas fa-upload me-1.5"></i>{{ $abstracts->isEmpty() ? 'Submit Research Abstract' : 'Submit Another Abstract' }}
                    </a>
                </div>
            </div>

            {{-- 3. Account Activity & Security Card --}}
            <div class="card border-0 shadow-sm mb-4 profile-side-card" style="border-radius: 16px; background: #ffffff;">
                <div class="card-header bg-transparent py-3 px-3.5 border-bottom d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center gap-2">
                        <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 30px; height: 30px; background: #F1F5F9; color: #475569;">
                            <i class="fas fa-shield-alt fs-6"></i>
                        </div>
                        <h6 class="fw-bold mb-0 text-dark" style="font-size: 0.92rem;">Security &amp; Activity</h6>
                    </div>
                </div>
                <div class="card-body p-3.5">
                    <div class="d-flex flex-column gap-2 small">
                        
                        <div class="d-flex justify-content-between align-items-center py-1.5 border-bottom">
                            <span class="text-muted extra-small">Member Since:</span>
                            <span class="fw-semibold text-dark" style="font-size: 0.82rem;">
                                {{ $user->created_at ? $user->created_at->format('d M, Y') : 'N/A' }}
                            </span>
                        </div>

                        <div class="d-flex justify-content-between align-items-center py-1.5 border-bottom">
                            <span class="text-muted extra-small">Last Login:</span>
                            <span class="fw-semibold text-dark" style="font-size: 0.82rem;">
                                {{ $user->last_login ? $user->last_login->format('d M Y, h:i A') : 'Current Session' }}
                            </span>
                        </div>

                        <div class="d-flex justify-content-between align-items-center py-1.5 border-bottom">
                            <span class="text-muted extra-small">Last Login IP:</span>
                            <span class="fw-semibold text-dark font-monospace" style="font-size: 0.8rem;">
                                {{ $user->last_ip ?: 'Recorded upon login' }}
                            </span>
                        </div>

                        <div class="d-flex justify-content-between align-items-center py-1.5">
                            <span class="text-muted extra-small">Email Status:</span>
                            <span class="fw-semibold {{ $user->hasVerifiedEmail() ? 'text-success' : 'text-warning' }}" style="font-size: 0.82rem;">
                                <i class="fas {{ $user->hasVerifiedEmail() ? 'fa-check-circle' : 'fa-clock' }} me-1"></i>
                                {{ $user->hasVerifiedEmail() ? 'Verified' : 'Unverified' }}
                            </span>
                        </div>

                    </div>

                    <a href="{{ route('profile.change-password') }}" class="btn btn-outline-secondary btn-sm w-100 py-1.5 mt-3 rounded-pill fw-semibold" style="font-size: 0.82rem;">
                        <i class="fas fa-key me-1.5"></i>Update Account Password
                    </a>
                </div>
            </div>

            {{-- 4. Conference Secretariat & Venue Info --}}
            <div class="card border-0 shadow-sm profile-side-card" style="border-radius: 16px; background: linear-gradient(135deg, #F8FAFC 0%, #F1F5F9 100%);">
                <div class="card-body p-3.5">
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <i class="fas fa-university text-primary fs-6"></i>
                        <h6 class="fw-bold mb-0 text-dark" style="font-size: 0.88rem;">IPHACON 2027 Secretariat</h6>
                    </div>
                    <p class="text-secondary extra-small mb-2" style="line-height: 1.45;">
                        Department of Community Medicine,<br>
                        Rajendra Institute of Medical Sciences (RIMS),<br>
                        Bariatu, Ranchi, Jharkhand - 834002
                    </p>
                    <div class="d-flex align-items-center gap-2 extra-small text-muted">
                        <i class="fas fa-envelope text-primary"></i>
                        <a href="mailto:iphacon2027@gmail.com" class="text-decoration-none fw-semibold text-primary">iphacon2027@gmail.com</a>
                    </div>
                </div>
            </div>

        </div>

    </div>

</div>

{{-- Edit Profile Modal --}}
<div class="modal fade" id="editProfileModal" tabindex="-1" aria-labelledby="editProfileModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 18px; overflow: hidden;">
            
            <div class="modal-header text-white py-3 px-4"
                 style="background: linear-gradient(135deg, #01224d 0%, #013b82 60%, #0288d1 100%); border-bottom: 3px solid #F59E0B;">
                <div class="d-flex align-items-center gap-2">
                    <i class="fas fa-user-edit fs-5 text-warning"></i>
                    <h5 class="modal-title fw-bold text-white mb-0" id="editProfileModalLabel">Edit Delegate Profile</h5>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" data-dismiss="modal" aria-label="Close"></button>
            </div>

            <form method="POST" action="{{ route('profile.update') }}" id="editProfileForm">
                @csrf
                @method('PUT')

                <div class="modal-body p-4">
                    
                    <div class="alert alert-info py-2 px-3 mb-3 small d-flex align-items-center gap-2" style="border-radius: 10px;">
                        <i class="fas fa-info-circle fs-6"></i>
                        <span>Keep your full name and designations updated for your official conference badge, certificates and receipts.</span>
                    </div>

                    <div class="row g-3">
                        
                        {{-- Prefix & Full Name --}}
                        <div class="col-md-3">
                            <label for="modal_prefix" class="form-label fw-semibold text-dark small">Prefix <span class="text-danger">*</span></label>
                            <select class="form-select form-select-sm" id="modal_prefix" name="prefix" required style="border-radius: 8px;">
                                <option value="Dr." {{ old('prefix', $user->prefix) == 'Dr.' ? 'selected' : '' }}>Dr.</option>
                                <option value="Mr." {{ old('prefix', $user->prefix) == 'Mr.' ? 'selected' : '' }}>Mr.</option>
                                <option value="Mrs." {{ old('prefix', $user->prefix) == 'Mrs.' ? 'selected' : '' }}>Mrs.</option>
                                <option value="Prof." {{ old('prefix', $user->prefix) == 'Prof.' ? 'selected' : '' }}>Prof.</option>
                            </select>
                        </div>

                        <div class="col-md-9">
                            <label for="modal_full_name" class="form-label fw-semibold text-dark small">Full Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control form-control-sm" id="modal_full_name" name="full_name"
                                   value="{{ old('full_name', $user->full_name) }}" required placeholder="Enter your full name" style="border-radius: 8px;">
                        </div>

                        {{-- Designation & Institution --}}
                        <div class="col-md-6">
                            <label for="modal_designation" class="form-label fw-semibold text-dark small">Designation</label>
                            <input type="text" class="form-control form-control-sm" id="modal_designation" name="designation"
                                   value="{{ old('designation', $user->designation ?: ($primaryReg->designation ?? '')) }}"
                                   placeholder="e.g. Associate Professor / Consultant / PG Resident" style="border-radius: 8px;">
                        </div>

                        <div class="col-md-6">
                            <label for="modal_institution" class="form-label fw-semibold text-dark small">Institution / Medical College</label>
                            <input type="text" class="form-control form-control-sm" id="modal_institution" name="institution"
                                   value="{{ old('institution', $user->institution ?: ($primaryReg->institution ?? '')) }}"
                                   placeholder="e.g. AIIMS New Delhi / RIMS Ranchi" style="border-radius: 8px;">
                        </div>

                        {{-- Gender & DOB --}}
                        <div class="col-md-6">
                            <label for="modal_gender" class="form-label fw-semibold text-dark small">Gender <span class="text-danger">*</span></label>
                            <select class="form-select form-select-sm" id="modal_gender" name="gender" required style="border-radius: 8px;">
                                <option value="Male" {{ old('gender', $user->gender) == 'Male' ? 'selected' : '' }}>Male</option>
                                <option value="Female" {{ old('gender', $user->gender) == 'Female' ? 'selected' : '' }}>Female</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="modal_date_of_birth" class="form-label fw-semibold text-dark small">Date of Birth <span class="text-danger">*</span></label>
                            <input type="date" class="form-control form-control-sm" id="modal_date_of_birth" name="date_of_birth"
                                   value="{{ old('date_of_birth', $user->date_of_birth ? $user->date_of_birth->format('Y-m-d') : '') }}"
                                   max="{{ date('Y-m-d', strtotime('-18 years')) }}" required style="border-radius: 8px;">
                        </div>

                        {{-- Mobile Code & Number --}}
                        <div class="col-md-4">
                            <label for="modal_mobile_country_code" class="form-label fw-semibold text-dark small">Country Code <span class="text-danger">*</span></label>
                            <select class="form-select form-select-sm" id="modal_mobile_country_code" name="mobile_country_code" required style="border-radius: 8px;">
                                @foreach($countries as $c)
                                    <option value="{{ $c->phone_code }}" {{ old('mobile_country_code', $user->mobile_country_code) == $c->phone_code ? 'selected' : '' }}>
                                        {{ $c->country_name }} ({{ $c->phone_code }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-8">
                            <label for="modal_mobile_number" class="form-label fw-semibold text-dark small">Mobile Number <span class="text-danger">*</span></label>
                            <input type="tel" class="form-control form-control-sm" id="modal_mobile_number" name="mobile_number"
                                   value="{{ old('mobile_number', $user->mobile_number) }}" required maxlength="15"
                                   placeholder="Enter mobile number" style="border-radius: 8px;">
                        </div>

                        {{-- Email (Read-only note) --}}
                        <div class="col-12">
                            <label class="form-label fw-semibold text-dark small">Registered Email Address</label>
                            <input type="email" class="form-control form-control-sm bg-light" value="{{ $user->email }}" readonly style="border-radius: 8px;">
                            <span class="text-muted extra-small">Primary delegate email cannot be changed. Contact the conference secretariat if modification is needed.</span>
                        </div>

                    </div>

                </div>

                <div class="modal-footer bg-light py-2.5 px-4 d-flex justify-content-between">
                    <button type="button" class="btn btn-secondary btn-sm px-3.5 py-1.5 rounded-pill" data-bs-dismiss="modal" data-dismiss="modal">
                        Cancel
                    </button>
                    <button type="submit" class="btn btn-primary btn-sm px-4 py-1.5 rounded-pill fw-bold shadow-xs"
                            style="background: linear-gradient(135deg, #013069, #0288D1); border: none;">
                        <i class="fas fa-save me-1.5"></i>Save Changes
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>

<style>
.profile-page-container {
    max-width: 1200px;
}
.profile-hero-card {
    transition: transform 0.25s ease, box-shadow 0.25s ease;
}
.profile-metric-card {
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}
.profile-metric-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 20px rgba(0,0,0,0.08) !important;
}
.profile-section-card {
    border: 1px solid #E2E8F0 !important;
}
.profile-side-card {
    border: 1px solid #E2E8F0 !important;
}
.extra-small {
    font-size: 0.75rem;
}
.btn-xs {
    padding: 0.15rem 0.5rem;
    font-size: 0.72rem;
}
</style>
@endsection
