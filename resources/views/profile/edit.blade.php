@extends('shared.auth-delegate')
@section('title', 'Edit Profile - IPHACON 2027')
@php
    $inner_title = 'Edit Delegate Profile';
    $primaryReg = $user->registrations->first();
    $currentPhoto = $user->photo_path ?: ($primaryReg?->photo_path);
@endphp

@section('delegate-content')
<div class="container py-3" style="max-width: 900px;">

    {{-- Breadcrumb / Back Link --}}
    <div class="mb-3 d-flex align-items-center justify-content-between">
        <a href="{{ route('profile.show') }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3 py-1.5 fw-semibold"
           style="font-size: 0.82rem;">
            <i class="fas fa-arrow-left me-1.5"></i>Back to Profile
        </a>
        <span class="text-muted extra-small">
            <i class="fas fa-shield-alt text-success me-1"></i>Secure Delegate Profile Editor
        </span>
    </div>

    {{-- Edit Card --}}
    <div class="card shadow-sm border-0 overflow-hidden" style="border-radius: 18px; border: 1px solid #E2E8F0 !important;">
        
        {{-- Card Header --}}
        <div class="card-header text-white py-3.5 px-4 d-flex align-items-center justify-content-between"
             style="background: linear-gradient(135deg, #01224d 0%, #013b82 60%, #0288d1 100%); border-bottom: 3px solid #F59E0B;">
            <div class="d-flex align-items-center gap-2.5">
                <div class="rounded-circle p-2 d-flex align-items-center justify-content-center"
                     style="width: 36px; height: 36px; background: rgba(245, 158, 11, 0.2); color: #F59E0B;">
                    <i class="fas fa-user-edit fs-6"></i>
                </div>
                <div>
                    <h5 class="fw-bold text-white mb-0" style="font-size: 1.05rem;">Edit Delegate Profile</h5>
                    <p class="text-white-50 mb-0 extra-small">Update your personal, professional and contact information</p>
                </div>
            </div>
            <a href="{{ route('profile.show') }}" class="btn btn-sm btn-outline-light rounded-pill px-3 py-1 extra-small fw-semibold"
               style="border-color: rgba(255,255,255,0.4);">
                Cancel
            </a>
        </div>

        {{-- Card Body --}}
        <div class="card-body p-4 p-md-4 bg-white">

            {{-- Informational Alert --}}
            <div class="alert alert-info py-2.5 px-3 mb-4 small d-flex align-items-center gap-2.5 border-0 shadow-xs"
                 style="border-radius: 12px; background-color: #E0F2FE; color: #0288D1;">
                <i class="fas fa-info-circle fs-5 flex-shrink-0"></i>
                <div style="font-size: 0.83rem; line-height: 1.45;">
                    Keep your full name and designations updated for your official <strong>IPHACON 2027</strong> conference badge, certificates, and receipts.
                </div>
            </div>

            {{-- Error Summary Alert --}}
            @if (isset($errors) && $errors->any())
                <div class="alert alert-danger alert-dismissible fade show p-3 mb-4 small shadow-xs" role="alert"
                     style="border-radius: 12px; background-color: #FEE2E2; border: 1px solid #FCA5A5; color: #991B1B;">
                    <div class="d-flex align-items-start gap-2">
                        <i class="fas fa-exclamation-triangle mt-1"></i>
                        <div class="flex-grow-1">
                            <strong class="d-block mb-1">Please correct the following errors:</strong>
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

            <form method="POST" action="{{ route('profile.update') }}" id="editProfileForm" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                {{-- Profile Photo Section --}}
                <div class="mb-4 p-3.5 rounded-3 border" id="photo-section"
                     style="background: linear-gradient(135deg, #F8FAFC 0%, #F1F5F9 100%); border: 1px dashed #94A3B8 !important; border-radius: 14px;">
                    <div class="d-flex flex-column flex-sm-row align-items-center align-items-sm-start gap-3.5">
                        
                        {{-- Avatar Preview Container --}}
                        <div class="position-relative flex-shrink-0 text-center">
                            <div class="profile-avatar-wrapper position-relative d-inline-block">
                                <img id="photoPreviewImg"
                                     src="{{ $currentPhoto ? asset('storage/' . $currentPhoto) : asset('images/default-avatar.svg') }}"
                                     alt="Profile Photo Preview"
                                     class="rounded-circle shadow-sm border border-3 border-white {{ $currentPhoto ? '' : 'd-none' }}"
                                     style="width: 105px; height: 105px; object-fit: cover;"
                                     onerror="this.onerror=null; this.src='{{ asset('images/default-avatar.svg') }}';">

                                <div id="photoPreviewInitial"
                                     class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold shadow-sm border border-3 border-white {{ $currentPhoto ? 'd-none' : '' }}"
                                     style="width: 105px; height: 105px; font-size: 2.3rem; background: linear-gradient(135deg, #0288D1 0%, #00897B 100%);">
                                    {{ strtoupper(substr($user->full_name ?? 'D', 0, 1)) }}
                                </div>

                                <button type="button"
                                        class="btn btn-sm btn-primary rounded-circle position-absolute bottom-0 end-0 d-flex align-items-center justify-content-center shadow"
                                        style="width: 34px; height: 34px; transform: translate(5%, 5%); border: 2px solid #fff;"
                                        onclick="document.getElementById('photoInput').click();"
                                        title="Click to choose a new photo">
                                    <i class="fas fa-camera text-white" style="font-size: 0.85rem;"></i>
                                </button>
                            </div>
                        </div>

                        {{-- Upload Controls & Instructions --}}
                        <div class="flex-grow-1 text-center text-sm-start min-w-0">
                            <label class="form-label fw-bold text-dark mb-1 d-flex align-items-center justify-content-center justify-content-sm-start gap-1.5" style="font-size: 0.95rem;">
                                <i class="fas fa-portrait text-primary"></i> Profile Photo
                                <span class="badge bg-white text-secondary border extra-small fw-normal">Badge &amp; Certificates</span>
                            </label>
                            <p class="text-muted extra-small mb-2.5" style="font-size: 0.78rem; line-height: 1.45;">
                                Upload a front-facing passport style photo. This photo will be used for your official <strong>IPHACON 2027</strong> delegate profile, abstract submissions, and conference badge.
                            </p>

                            <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-sm-start gap-2">
                                <button type="button"
                                        class="btn btn-sm btn-primary rounded-pill px-3 py-1.5 extra-small fw-semibold d-inline-flex align-items-center gap-1.5 shadow-xs"
                                        onclick="document.getElementById('photoInput').click();">
                                    <i class="fas fa-upload"></i>
                                    <span id="photoBtnLabel">{{ $currentPhoto ? 'Change Photo' : 'Upload Photo' }}</span>
                                </button>

                                @if($currentPhoto)
                                    <button type="button"
                                            id="removePhotoBtn"
                                            class="btn btn-sm btn-outline-danger rounded-pill px-3 py-1.5 extra-small fw-semibold d-inline-flex align-items-center gap-1.5"
                                            onclick="toggleRemovePhoto()">
                                        <i class="fas fa-trash-alt"></i>
                                        <span>Remove Photo</span>
                                    </button>
                                @endif

                                <button type="button"
                                        id="cancelNewPhotoBtn"
                                        class="btn btn-sm btn-outline-secondary rounded-pill px-3 py-1.5 extra-small fw-semibold d-none"
                                        onclick="cancelNewPhoto()">
                                    <i class="fas fa-undo me-1"></i>Reset
                                </button>
                            </div>

                            {{-- Hidden File Input & Removal Flag --}}
                            <input type="file"
                                   id="photoInput"
                                   name="photo"
                                   class="d-none"
                                   accept="image/jpeg,image/png,image/jpg,image/webp">

                            <input type="hidden" id="removePhotoInput" name="remove_photo" value="0">

                            <div class="extra-small text-muted mt-2" style="font-size: 0.72rem;">
                                <i class="fas fa-info-circle me-1 text-info"></i>Allowed formats: JPG, JPEG, PNG, WEBP &bull; Max size: 1 MB
                            </div>

                            @error('photo')
                                <div class="text-danger extra-small mt-1.5 fw-semibold">
                                    <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                </div>
                            @enderror
                            <div id="photoClientError" class="text-danger extra-small mt-1.5 fw-semibold d-none"></div>
                        </div>

                    </div>
                </div>

                {{-- Section 1: Personal Identification --}}
                <div class="mb-4">
                    <h6 class="fw-bold text-dark mb-3 pb-2 border-bottom d-flex align-items-center gap-2" style="font-size: 0.92rem;">
                        <i class="fas fa-id-card text-primary"></i> Personal Identification
                    </h6>

                    <div class="row g-3">
                        {{-- Prefix --}}
                        <div class="col-md-3">
                            <label for="prefix" class="form-label fw-semibold text-dark small">
                                Prefix <span class="text-danger">*</span>
                            </label>
                            <select class="form-select form-select-sm @error('prefix') is-invalid @enderror"
                                    id="prefix" name="prefix" required style="border-radius: 8px;">
                                <option value="" disabled>Select</option>
                                <option value="Dr." {{ old('prefix', $user->prefix) == 'Dr.' ? 'selected' : '' }}>Dr.</option>
                                <option value="Mr." {{ old('prefix', $user->prefix) == 'Mr.' ? 'selected' : '' }}>Mr.</option>
                                <option value="Mrs." {{ old('prefix', $user->prefix) == 'Mrs.' ? 'selected' : '' }}>Mrs.</option>
                                <option value="Prof." {{ old('prefix', $user->prefix) == 'Prof.' ? 'selected' : '' }}>Prof.</option>
                            </select>
                            @error('prefix')
                                <div class="invalid-feedback extra-small">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Full Name --}}
                        <div class="col-md-9">
                            <label for="full_name" class="form-label fw-semibold text-dark small">
                                Full Name <span class="text-danger">*</span>
                            </label>
                            <input type="text"
                                   class="form-control form-control-sm @error('full_name') is-invalid @enderror"
                                   id="full_name" name="full_name"
                                   value="{{ old('full_name', $user->full_name) }}"
                                   required placeholder="Enter your full name"
                                   style="border-radius: 8px;" />
                            @error('full_name')
                                <div class="invalid-feedback extra-small">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Gender --}}
                        <div class="col-md-6">
                            <label for="gender" class="form-label fw-semibold text-dark small">
                                Gender <span class="text-danger">*</span>
                            </label>
                            <select class="form-select form-select-sm @error('gender') is-invalid @enderror"
                                    id="gender" name="gender" required style="border-radius: 8px;">
                                <option value="" disabled>Select</option>
                                <option value="Male" {{ old('gender', $user->gender) == 'Male' ? 'selected' : '' }}>Male</option>
                                <option value="Female" {{ old('gender', $user->gender) == 'Female' ? 'selected' : '' }}>Female</option>
                            </select>
                            @error('gender')
                                <div class="invalid-feedback extra-small">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Date of Birth --}}
                        <div class="col-md-6">
                            <label for="date_of_birth" class="form-label fw-semibold text-dark small">
                                Date of Birth <span class="text-danger">*</span>
                            </label>
                            @php
                                $dobValue = old('date_of_birth');
                                if (!$dobValue && $user->date_of_birth) {
                                    $dobValue = $user->date_of_birth instanceof \Carbon\Carbon
                                        ? $user->date_of_birth->format('Y-m-d')
                                        : \Carbon\Carbon::parse($user->date_of_birth)->format('Y-m-d');
                                }
                            @endphp
                            <input type="date"
                                   class="form-control form-control-sm @error('date_of_birth') is-invalid @enderror"
                                   id="date_of_birth" name="date_of_birth"
                                   value="{{ $dobValue }}"
                                   required max="{{ date('Y-m-d', strtotime('-18 years')) }}"
                                   style="border-radius: 8px;" />
                            @error('date_of_birth')
                                <div class="invalid-feedback extra-small">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Section 2: Professional Details --}}
                <div class="mb-4">
                    <h6 class="fw-bold text-dark mb-3 pb-2 border-bottom d-flex align-items-center gap-2" style="font-size: 0.92rem;">
                        <i class="fas fa-briefcase text-success"></i> Professional &amp; Institutional Affiliation
                    </h6>

                    <div class="row g-3">
                        {{-- Designation --}}
                        <div class="col-md-6">
                            <label for="designation" class="form-label fw-semibold text-dark small">Designation</label>
                            <input type="text"
                                   class="form-control form-control-sm @error('designation') is-invalid @enderror"
                                   id="designation" name="designation"
                                   value="{{ old('designation', $user->designation ?: ($primaryReg->designation ?? '')) }}"
                                   placeholder="e.g. Associate Professor / Consultant / PG Resident"
                                   style="border-radius: 8px;" />
                            @error('designation')
                                <div class="invalid-feedback extra-small">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Institution --}}
                        <div class="col-md-6">
                            <label for="institution" class="form-label fw-semibold text-dark small">Institution / Medical College</label>
                            <input type="text"
                                   class="form-control form-control-sm @error('institution') is-invalid @enderror"
                                   id="institution" name="institution"
                                   value="{{ old('institution', $user->institution ?: ($primaryReg->institution ?? '')) }}"
                                   placeholder="e.g. AIIMS New Delhi / RIMS Ranchi"
                                   style="border-radius: 8px;" />
                            @error('institution')
                                <div class="invalid-feedback extra-small">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Section 3: Contact Information --}}
                <div class="mb-4">
                    <h6 class="fw-bold text-dark mb-3 pb-2 border-bottom d-flex align-items-center gap-2" style="font-size: 0.92rem;">
                        <i class="fas fa-phone-alt text-warning"></i> Contact Information
                    </h6>

                    <div class="row g-3">
                        {{-- Country Code --}}
                        <div class="col-md-4">
                            <label for="mobile_country_code" class="form-label fw-semibold text-dark small">
                                Country Code <span class="text-danger">*</span>
                            </label>
                            <select class="form-select form-select-sm @error('mobile_country_code') is-invalid @enderror"
                                    id="mobile_country_code" name="mobile_country_code" required
                                    style="border-radius: 8px;">
                                <option value="" disabled>Select Code</option>
                                @foreach ($countries as $country)
                                    <option value="{{ $country->phone_code }}"
                                        {{ old('mobile_country_code', $user->mobile_country_code) == $country->phone_code ? 'selected' : '' }}>
                                        {{ $country->country_name }} ({{ $country->phone_code }})
                                    </option>
                                @endforeach
                            </select>
                            @error('mobile_country_code')
                                <div class="invalid-feedback extra-small">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Mobile Number --}}
                        <div class="col-md-8">
                            <label for="mobile_number" class="form-label fw-semibold text-dark small">
                                Mobile Number <span class="text-danger">*</span>
                            </label>
                            <input type="tel"
                                   class="form-control form-control-sm @error('mobile_number') is-invalid @enderror"
                                   id="mobile_number" name="mobile_number"
                                   value="{{ old('mobile_number', $user->mobile_number) }}"
                                   required maxlength="15"
                                   placeholder="Enter 10-digit mobile number"
                                   style="border-radius: 8px;" />
                            @error('mobile_number')
                                <div class="invalid-feedback extra-small">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Registered Email Address (Read-only) --}}
                        <div class="col-12">
                            <label class="form-label fw-semibold text-dark small">Registered Email Address</label>
                            <input type="email" class="form-control form-control-sm bg-light"
                                   value="{{ $user->email }}" readonly
                                   style="border-radius: 8px; cursor: not-allowed;" />
                            <span class="text-muted extra-small">
                                Primary delegate email cannot be changed. Contact the conference secretariat if modification is needed.
                            </span>
                        </div>
                    </div>
                </div>

                {{-- Form Action Buttons --}}
                <div class="d-flex align-items-center justify-content-between pt-3 border-top mt-4">
                    <a href="{{ route('profile.show') }}" class="btn btn-secondary btn-sm px-4 py-2 rounded-pill fw-semibold shadow-xs">
                        <i class="fas fa-times me-1.5"></i>Cancel
                    </a>
                    <button type="submit" class="btn btn-primary btn-sm px-4 py-2 rounded-pill fw-bold shadow-sm"
                            style="background: linear-gradient(135deg, #013069, #0288D1); border: none;">
                        <i class="fas fa-save me-1.5"></i>Save Changes
                    </button>
                </div>

            </form>

        </div>
    </div>

</div>

<style>
.extra-small {
    font-size: 0.75rem;
}
.profile-avatar-wrapper {
    cursor: pointer;
    transition: transform 0.2s ease, opacity 0.2s ease;
}
.profile-avatar-wrapper:hover {
    transform: scale(1.02);
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const photoInput = document.getElementById('photoInput');
    const photoPreviewImg = document.getElementById('photoPreviewImg');
    const photoPreviewInitial = document.getElementById('photoPreviewInitial');
    const photoBtnLabel = document.getElementById('photoBtnLabel');
    const cancelNewPhotoBtn = document.getElementById('cancelNewPhotoBtn');
    const removePhotoBtn = document.getElementById('removePhotoBtn');
    const removePhotoInput = document.getElementById('removePhotoInput');
    const photoClientError = document.getElementById('photoClientError');

    const originalSrc = photoPreviewImg ? photoPreviewImg.src : '';
    const hadOriginalPhoto = @json(!empty($currentPhoto));

    if (photoInput) {
        photoInput.addEventListener('change', function(e) {
            if (photoClientError) {
                photoClientError.classList.add('d-none');
                photoClientError.innerText = '';
            }

            const file = e.target.files && e.target.files[0];
            if (!file) return;

            // Validate file type
            const validTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'];
            if (!validTypes.includes(file.type.toLowerCase())) {
                showPhotoError('Invalid image type. Please select a JPG, JPEG, PNG, or WEBP file.');
                photoInput.value = '';
                return;
            }

            // Validate file size (1MB = 1048576 bytes)
            if (file.size > 1048576) {
                showPhotoError('Photo size exceeds 1MB limit. Please upload an image smaller than 1MB.');
                photoInput.value = '';
                return;
            }

            // Reset remove flag if user selects a new file
            if (removePhotoInput) removePhotoInput.value = '0';
            if (removePhotoBtn) {
                removePhotoBtn.classList.remove('btn-danger', 'text-white');
                removePhotoBtn.classList.add('btn-outline-danger');
                removePhotoBtn.innerHTML = '<i class="fas fa-trash-alt me-1"></i><span>Remove Photo</span>';
            }

            // Preview image
            const reader = new FileReader();
            reader.onload = function(evt) {
                if (photoPreviewImg) {
                    photoPreviewImg.src = evt.target.result;
                    photoPreviewImg.classList.remove('d-none');
                }
                if (photoPreviewInitial) {
                    photoPreviewInitial.classList.add('d-none');
                }
                if (cancelNewPhotoBtn) {
                    cancelNewPhotoBtn.classList.remove('d-none');
                }
                if (photoBtnLabel) {
                    photoBtnLabel.innerText = 'Change Selected Photo';
                }
            };
            reader.readAsDataURL(file);
        });
    }

    function showPhotoError(msg) {
        if (photoClientError) {
            photoClientError.innerText = msg;
            photoClientError.classList.remove('d-none');
        } else if (typeof toastr !== 'undefined') {
            toastr.error(msg, 'Validation Error');
        } else {
            alert(msg);
        }
    }

    window.cancelNewPhoto = function() {
        if (photoInput) photoInput.value = '';
        if (photoClientError) photoClientError.classList.add('d-none');
        if (cancelNewPhotoBtn) cancelNewPhotoBtn.classList.add('d-none');
        if (removePhotoInput) removePhotoInput.value = '0';

        if (hadOriginalPhoto) {
            if (photoPreviewImg) {
                photoPreviewImg.src = originalSrc;
                photoPreviewImg.classList.remove('d-none');
            }
            if (photoPreviewInitial) {
                photoPreviewInitial.classList.add('d-none');
            }
            if (photoBtnLabel) {
                photoBtnLabel.innerText = 'Change Photo';
            }
        } else {
            if (photoPreviewImg) {
                photoPreviewImg.classList.add('d-none');
            }
            if (photoPreviewInitial) {
                photoPreviewInitial.classList.remove('d-none');
            }
            if (photoBtnLabel) {
                photoBtnLabel.innerText = 'Upload Photo';
            }
        }
    };

    window.toggleRemovePhoto = function() {
        if (!removePhotoInput) return;
        const isRemoving = removePhotoInput.value === '1';

        if (isRemoving) {
            // Undo removal
            removePhotoInput.value = '0';
            if (removePhotoBtn) {
                removePhotoBtn.classList.remove('btn-danger', 'text-white');
                removePhotoBtn.classList.add('btn-outline-danger');
                removePhotoBtn.innerHTML = '<i class="fas fa-trash-alt me-1"></i><span>Remove Photo</span>';
            }
            window.cancelNewPhoto();
        } else {
            // Mark for removal
            removePhotoInput.value = '1';
            if (photoInput) photoInput.value = '';
            if (cancelNewPhotoBtn) cancelNewPhotoBtn.classList.add('d-none');

            if (photoPreviewImg) {
                photoPreviewImg.classList.add('d-none');
            }
            if (photoPreviewInitial) {
                photoPreviewInitial.classList.remove('d-none');
            }

            if (removePhotoBtn) {
                removePhotoBtn.classList.remove('btn-outline-danger');
                removePhotoBtn.classList.add('btn-danger', 'text-white');
                removePhotoBtn.innerHTML = '<i class="fas fa-undo me-1"></i><span>Keep Current Photo</span>';
            }
            if (photoBtnLabel) {
                photoBtnLabel.innerText = 'Upload Replacement Photo';
            }
        }
    };
});
</script>
@endsection
