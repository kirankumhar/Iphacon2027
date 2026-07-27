@section('title', 'Personal Information')

<style>
    .form-section {
        background: #fff;
        border-radius: 14px;
        box-shadow: 0 4px 18px rgba(45, 105, 255, 0.06);
        overflow: hidden;
        border: 1px solid #e2e8f0;
    }

    .section-header {
        background: linear-gradient(135deg, #2D69FF 0%, #1A52E0 100%);
        color: white;
        padding: 14px 20px;
        margin: -1.5rem -1.5rem 1.25rem -1.5rem;
    }

    .form-label {
        font-weight: 600;
        color: #1e293b;
        margin-bottom: 4px;
        font-size: 0.85rem;
    }

    .form-control,
    .form-select {
        border: 1.5px solid #cbd5e1;
        border-radius: 8px;
        padding: 7px 12px;
        font-size: 0.88rem;
        transition: all 0.2s ease;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: #2D69FF;
        box-shadow: 0 0 0 0.2rem rgba(45, 105, 255, 0.15);
    }

    .photo-upload-area {
        border: 2px dashed #cbd5e1;
        border-radius: 12px;
        padding: 14px;
        text-align: center;
        transition: all 0.2s ease;
        cursor: pointer;
        background: #f8fafc;
    }

    .photo-upload-area:hover {
        border-color: #2D69FF;
        background: #E1F0FF;
    }

    .photo-preview {
        width: 100px;
        height: 100px;
        object-fit: cover;
        border-radius: 50%;
        border: 3px solid #fff;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        cursor: pointer;
        transition: transform 0.2s ease;
    }

    .photo-preview:hover {
        transform: scale(1.04);
    }

    .document-preview {
        width: 100%;
        max-width: 180px;
        height: 120px;
        object-fit: cover;
        border-radius: 8px;
        border: 2px solid #cbd5e1;
        cursor: pointer;
        transition: transform 0.2s ease;
    }

    .document-preview:hover {
        transform: scale(1.02);
        border-color: #2D69FF;
    }

    .upload-btn {
        background: linear-gradient(135deg, #2D69FF 0%, #1A52E0 100%);
        border: none;
        color: white;
        padding: 7px 16px;
        border-radius: 20px;
        font-weight: 500;
        font-size: 0.85rem;
        transition: all 0.2s ease;
    }

    .upload-btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(45, 105, 255, 0.3);
    }

    .sidebar-section {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        padding: 18px;
        height: fit-content;
        position: sticky;
        top: 20px;
    }

    .form-group {
        margin-bottom: 12px;
    }

    .required-star {
        color: #dc3545;
        font-weight: bold;
    }

    .form-icon {
        color: #2D69FF;
        margin-right: 6px;
    }
</style>

<div class="row">
    <!-- Left Column - Main Form -->
    <div class="col-lg-8">
        <div class="form-section p-4">
            <div class="section-header">
                <h5 class="mb-0 text-white fw-bold"><i class="fas fa-user me-2"></i>Step 1: Personal Information</h5>
            </div>

            <!-- Prefix and Full Name -->
            <div class="form-group mb-3">
                <label for="full_name" class="form-label">
                    <i class="fas fa-user form-icon"></i>Full Name<span class="required-star">*</span>
                </label>
                <div class="input-group">
                    <select class="form-select flex-grow-0 @error('prefix') is-invalid @enderror" id="prefix" name="prefix"
                        required style="width: 105px; border-top-right-radius: 0; border-bottom-right-radius: 0;">
                        <option value="Dr." {{ old('prefix', $user->prefix) == 'Dr.' ? 'selected' : '' }}>Dr.</option>
                        <option value="Prof." {{ old('prefix', $user->prefix) == 'Prof.' ? 'selected' : '' }}>Prof.</option>
                        <option value="Mr." {{ old('prefix', $user->prefix) == 'Mr.' ? 'selected' : '' }}>Mr.</option>
                        <option value="Mrs." {{ old('prefix', $user->prefix) == 'Mrs.' ? 'selected' : '' }}>Mrs.</option>
                    </select>
                    <input type="text" class="form-control @error('full_name') is-invalid @enderror" id="full_name" name="full_name"
                        value="{{ old('full_name', $user->full_name) }}" required maxlength="50"
                        pattern="[A-Za-z. ]{2,}" placeholder="Enter your full name"
                        oninput="this.value = this.value.replace(/[^A-Za-z. ]/g, '')"
                        style="border-top-left-radius: 0; border-bottom-left-radius: 0;">
                </div>
                @error('prefix')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
                @error('full_name')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>

            <!-- Gender and Date of Birth -->
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label for="gender" class="form-label">
                            <i class="fas fa-venus-mars form-icon"></i>Gender<span class="required-star">*</span>
                        </label>
                        <select class="form-select @error('gender') is-invalid @enderror" id="gender" name="gender"
                            required>
                            <option value="" disabled {{ old('gender', $user->gender) ? '' : 'selected' }}>Select Gender</option>
                            <option value="Male" {{ old('gender', $user->gender) == 'Male' ? 'selected' : '' }}>Male</option>
                            <option value="Female" {{ old('gender', $user->gender) == 'Female' ? 'selected' : '' }}>Female</option>
                        </select>
                        @error('gender')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label for="dob" class="form-label">
                            <i class="fas fa-calendar form-icon"></i>Date of Birth<span class="required-star">*</span>
                        </label>
                        <input type="date" class="form-control @error('dob') is-invalid @enderror" id="dob"
                            name="dob"
                            value="{{ old('dob', $user->date_of_birth ? $user->date_of_birth->format('Y-m-d') : '') }}"
                            required max="{{ date('Y-m-d', strtotime('-18 years')) }}" />
                        <small id="ageDisplay" class="form-text text-muted"></small>
                        @error('dob')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Mobile Number & Dietary Preference -->
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label class="form-label">
                            <i class="fas fa-phone form-icon"></i>Mobile Number<span class="required-star">*</span>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text px-2.5">{{ $user->mobile_country_code }}</span>
                            <input type="tel" class="form-control @error('mobile_number') is-invalid @enderror" id="mobile_number" name="mobile_number"
                                value="{{ old('mobile_number', $user->mobile_number) }}" required maxlength="{{(auth()->user()->delegate_type == 'Indian' ? '10' : '18')}}"
                                placeholder="Enter mobile number" oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                        </div>
                        @error('mobile_number')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label for="dietary_preference" class="form-label">
                            <i class="fas fa-utensils form-icon"></i>Dietary Preference<span class="required-star">*</span>
                        </label>
                        <select name="dietary_preference" id="dietary_preference" class="form-select {{ $errors->has('dietary_preference') ? 'is-invalid' : '' }}" required>
                            <option value="">Choose Preference</option>
                            <option value="Vegetarian"
                                {{ old('dietary_preference', $registration->dietary_preference) == 'Vegetarian' ? 'selected' : '' }}>
                                🥦 Vegetarian
                            </option>
                            <option value="Non-Vegetarian"
                                {{ old('dietary_preference', $registration->dietary_preference) == 'Non-Vegetarian' ? 'selected' : '' }}>
                                🍗 Non-Vegetarian
                            </option>
                        </select>
                        @error('dietary_preference')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Address -->
            <div class="form-group mb-3">
                <label for="address" class="form-label">
                    <i class="fas fa-home form-icon"></i>Address<span class="required-star">*</span>
                </label>
                <textarea class="form-control @error('address') is-invalid @enderror" id="address" name="address" rows="2" required maxlength="100"
                    placeholder="Enter your complete address" oninput="this.value = this.value.replace(/[^A-Za-z0-9 ,\/-]/g, '')"
                    pattern="[A-Za-z0-9 ,\/-]+">{{ old('address', $registration->address) }}</textarea>
                @error('address')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Country, State, City -->
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group mb-3">
                        <label for="country_id" class="form-label">
                            <i class="fas fa-globe form-icon"></i>Country<span class="required-star">*</span>
                        </label>
                        @foreach ($countries as $country)
                            @if (auth()->user()->country_id == $country->id)
                                <input class="form-control" name="country_id" value="{{ $country->country_name }}"
                                    readonly style="background-color: #f8fafc;" />
                            @endif
                        @endforeach
                        @error('country_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-5">
                    <div class="form-group mb-3">
                        <label for="state_id" class="form-label">
                            <i class="fas fa-map form-icon"></i>State<span class="required-star">*</span>
                        </label>
                        @if (auth()->user()->delegate_type == 'Indian')
                            <select class="form-select @error('state_id') is-invalid @enderror" id="state_id"
                                name="state_id" required>
                                <option value="">Select State</option>
                                @foreach ($states as $state)
                                    <option value="{{ $state->id }}"
                                        {{ old('state_id', $registration->state_id) == $state->id ? 'selected' : '' }}>
                                        {{ $state->state_name }}
                                    </option>
                                @endforeach
                            </select>
                        @else
                            <input class="form-control @error('state_id') is-invalid @enderror" id="state_id"
                                name="state_id" value="{{ old('state_id', $registration->other_state) }}" required
                                placeholder="Enter state"
                                oninput="this.value = this.value.replace(/[^A-Za-z0-9 ,\/-]/g, '')">
                        @endif
                        @error('state_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group mb-3">
                        <label for="city" class="form-label">
                            <i class="fas fa-city form-icon"></i>City<span class="required-star">*</span>
                        </label>
                        <input type="text" class="form-control @error('city') is-invalid @enderror" id="city" name="city"
                            value="{{ old('city', $registration->city) }}" required maxlength="50"
                            placeholder="Enter city" oninput="this.value = this.value.replace(/[^A-Za-z0-9]/g, '')">
                        @error('city')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- PIN Code and WhatsApp -->
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group mb-3">
                        <label for="pin_code" class="form-label">
                            <i class="fas fa-map-pin form-icon"></i>PIN/Zip Code<span class="required-star">*</span>
                        </label>
                        <input type="text" class="form-control @error('pin_code') is-invalid @enderror"
                            id="pin_code" name="pin_code" value="{{ old('pin_code', $registration->pin_code) }}"
                            required placeholder="Enter PIN/Zip" maxlength="8"
                            oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                        @error('pin_code')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="form-group mb-3">
                        <label class="form-label">
                            <i class="fab fa-whatsapp form-icon"></i>WhatsApp Number <small class="text-muted">(Optional)</small>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text px-2.5">{{ $user->mobile_country_code }}</span>
                            <input type="tel" class="form-control" name="whatsapp_number"
                                value="{{ old('whatsapp_number', $registration->whatsapp_number) }}"
                                placeholder="WhatsApp Number" maxlength="{{(auth()->user()->delegate_type == 'Indian' ? '10' : '18')}}"
                                oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Column - Photo & Documents -->
    <div class="col-lg-4">
        <!-- Profile Photo Section -->
        <div class="sidebar-section mb-4">
            <h5 class="text-primary mb-3">
                <i class="fas fa-camera me-2"></i>Profile Photo
                <small class="text-muted">(Optional)</small>
            </h5>

            <div class="text-center">
                <div class="photo-upload-area mb-3" onclick="handlePhotoAreaClick(event)">
                    <img id="photoPreview" class="photo-preview mb-2"
                        src="{{ $registration->photo_path ? asset('storage/' . $registration->photo_path) : asset('images/default-avatar.svg') }}"
                        alt="Profile photo" onclick="openPhotoModal(event)"
                        onerror="this.onerror=null; this.src='{{ asset('images/default-avatar.svg') }}';">
                    <div>
                        <button type="button" class="btn upload-btn btn-sm">
                            <i class="fas fa-camera me-1"></i>Choose Photo
                        </button>
                    </div>
                    <small class="text-muted d-block mt-2">JPG/JPEG/PNG only, max 500KB</small>
                </div>
                <input type="file" id="photo" name="photo"
                    style="position: absolute; left: -9999px; opacity: 0;" accept="image/jpeg,image/jpg,image/png">
                @error('photo')
                <div class="text-danger small">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <!-- Government ID Section -->
        <div class="sidebar-section">
            <h5 class="text-primary mb-3">
                <i class="fas fa-id-card me-2"></i>Government ID Proof
            </h5>

            <div class="form-group">
                <label for="id_proof_type" class="form-label">
                    ID Type<span class="required-star">*</span>
                </label>
                <select class="form-select @error('id_proof_type') is-invalid @enderror" id="id_proof_type"
                    name="id_proof_type" required onchange="updateIdProofValidation()">

                    @if ($user->delegate_type == 'International')
                    <option value="Passport"
                        {{ old('id_proof_type', $registration->id_proof_type) == 'Passport' ? 'selected' : '' }}>
                        Passport</option>
                    @else
                    <option value="">Select ID Type</option>
                    <option value="Aadhaar"
                        {{ old('id_proof_type', $registration->id_proof_type) == 'Aadhaar' ? 'selected' : '' }}>
                        Aadhaar
                    </option>
                    <option value="PAN"
                        {{ old('id_proof_type', $registration->id_proof_type) == 'PAN' ? 'selected' : '' }}>PAN
                    </option>
                    <option value="Voter-ID"
                        {{ old('id_proof_type', $registration->id_proof_type) == 'Voter-ID' ? 'selected' : '' }}>
                        Voter
                        ID</option>
                    <option value="Driving License"
                        {{ old('id_proof_type', $registration->id_proof_type) == 'Driving License' ? 'selected' : '' }}>
                        Driving License</option>
                    <option value="Passport"
                        {{ old('id_proof_type', $registration->id_proof_type) == 'Passport' ? 'selected' : '' }}>
                        Passport</option>
                    @endif
                </select>
                @error('id_proof_type')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="id_proof_document" class="form-label fw-semibold" style="color:#2D69FF;">
                    <i class="fas fa-file-shield me-1"></i>Upload Document <span
                        class="required-star text-danger">*</span>
                </label>

                <div class="text-center">
                    @php
                    $docPath = $registration->id_proof_document_path
                    ? asset('storage/' . $registration->id_proof_document_path)
                    : '';
                    $isPdf = $docPath && str_ends_with(strtolower($docPath), '.pdf');
                    $hasDoc = !empty($registration->id_proof_document_path);
                    @endphp

                    <div class="photo-upload-area" onclick="handleUploadAreaClick(event)">
                        <div id="documentPreviewContainer" data-doc-path="{{ $docPath }}" data-is-pdf="{{ $isPdf ? 'true' : 'false' }}" style="display: {{ $hasDoc ? 'block' : 'none' }}">

                            <!-- Always keep img for preview -->
                            <img id="idProofPreview" alt="Document Preview" src="{{ $docPath }}"
                                class="img-fluid rounded shadow-sm document-preview {{ ($hasDoc && !$isPdf) ? '' : 'd-none'}}"
                                style="max-height:220px; object-fit:contain;">

                            <!-- PDF chip placeholder -->
                            <div id="pdfChip"
                                class="d-inline-flex align-items-center gap-2 px-3 py-2 rounded-pill bg-light border shadow-sm {{ ($hasDoc && $isPdf) ? '' : 'd-none'}}">
                                <i class="fas fa-file-pdf text-danger"></i>
                                <span class="fw-semibold">PDF Uploaded</span>
                            </div>
                        </div>

                        <div id="uploadPrompt" style="display: {{ $hasDoc ? 'none' : 'block' }}">
                            <i class="fas fa-cloud-upload-alt fa-2x text-muted mb-2"></i>
                            <div>
                                <button type="button" class="btn upload-btn btn-sm">
                                    <i class="fas fa-upload me-1"></i>Upload Document
                                </button>
                            </div>
                        </div>

                        <div id="docActionsPrompt" class="mt-3 d-flex flex-wrap justify-content-center gap-2" style="display: {{ $hasDoc ? 'flex' : 'none' }}">
                            <button type="button"
                                class="btn btn-success d-flex align-items-center gap-2 px-4 py-2 shadow-sm rounded-pill"
                                title="Preview Document" onclick="openDocumentModal(event)">
                                <i class="fas fa-eye"></i> View
                            </button>

                            <button type="button" class="btn upload-btn btn-sm">
                                <i class="fas fa-upload me-1"></i>Replace Document
                            </button>
                        </div>

                    <small class="text-muted d-block mt-3">
                        Allowed: JPG/JPEG/PDF • Max size: 2,500KB • Clear, readable document only
                    </small>
                </div>
                <!-- File input positioned off-screen but still focusable -->
                <input type="file" id="id_proof_document" name="id_proof_document"
                    style="position: absolute; left: -9999px; opacity: 0;"
                    accept="image/jpeg,image/jpg,application/pdf"
                    {{ $registration->id_proof_document_path ? '' : 'required' }}>
                @error('id_proof_document')
                <div class="text-danger small mt-2">{{ $message }}</div>
                @enderror
            </div>
        </div>
    </div>
</div>
</div>


<!-- Photo Modal -->
<div class="modal fade" id="photoModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-user me-2"></i>Profile Photo</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <img id="modalPhotoPreview" class="img-fluid rounded" alt="Profile Photo">
            </div>
        </div>
    </div>
</div>

<!-- Document Modal -->
<div class="modal fade" id="documentModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-id-card me-2"></i>Government ID Document</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <img id="modalDocumentPreview" class="img-fluid d-none" alt="Modal Preview">
                <embed id="modalPdfPreview" class="w-100 d-none" style="height:500px;" type="application/pdf">
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Photo upload listener
        const photoInput = document.getElementById("photo");
        const photoPreview = document.getElementById("photoPreview");
        const modalPhotoPreview = document.getElementById("modalPhotoPreview");

        if (photoInput) {
            photoInput.addEventListener("change", function() {
                const file = this.files[0];
                if (!file) return;

                // Size check (500KB)
                if (file.size > 500 * 1024) {
                    if (typeof Swal !== "undefined") {
                        Swal.fire("File Too Large", "Profile photo size must be less than 500KB!", "error");
                    } else {
                        alert("Profile photo size must be less than 500KB!");
                    }
                    this.value = "";
                    return;
                }

                // Type check
                if (!["image/jpeg", "image/jpg", "image/png"].includes(file.type)) {
                    if (typeof Swal !== "undefined") {
                        Swal.fire("Invalid File Type", "Only JPG, JPEG, or PNG images are allowed!", "error");
                    } else {
                        alert("Only JPG, JPEG, or PNG images are allowed!");
                    }
                    this.value = "";
                    return;
                }

                const reader = new FileReader();
                reader.onload = function(e) {
                    if (photoPreview) {
                        photoPreview.src = e.target.result;
                    }
                    if (modalPhotoPreview) {
                        modalPhotoPreview.src = e.target.result;
                    }
                };
                reader.readAsDataURL(file);

                if (typeof Swal !== "undefined") {
                    Swal.fire({
                        icon: "success",
                        title: "Photo Selected",
                        text: "Profile photo selected successfully!",
                        timer: 1500,
                        showConfirmButton: false
                    });
                }
            });
        }

        // Government ID Document Listener
        const idProofInput = document.getElementById("id_proof_document");
        const previewContainer = document.getElementById("documentPreviewContainer");
        const uploadPrompt = document.getElementById("uploadPrompt");

        const existingPath = previewContainer?.dataset.docPath || "";
        const existingIsPdf = previewContainer?.dataset.isPdf === "true";

        const imgPreview = document.getElementById("idProofPreview");
        const pdfChip = document.getElementById("pdfChip");

        const modalImg = document.getElementById("modalDocumentPreview");
        const modalPdf = document.getElementById("modalPdfPreview");

        if (existingPath) {
            if (existingIsPdf) {
                if (modalPdf) {
                    modalPdf.src = existingPath;
                    modalPdf.classList.remove("d-none");
                }
            } else {
                if (modalImg) {
                    modalImg.src = existingPath;
                    modalImg.classList.remove("d-none");
                }
            }
        }

        function resetPreview() {
            if (imgPreview) imgPreview.classList.add("d-none");
            if (pdfChip) pdfChip.classList.add("d-none");

            if (modalImg) {
                modalImg.src = "";
                modalImg.classList.add("d-none");
            }
            if (modalPdf) {
                modalPdf.src = "";
                modalPdf.classList.add("d-none");
            }
        }

        if (imgPreview && imgPreview.src) {
            if (modalImg) {
                modalImg.src = imgPreview.src;
                modalImg.classList.remove("d-none");
            }
        } else if (pdfChip) {
            if (modalPdf) {
                modalPdf.src = "{{ $docPath ?? '' }}";
                modalPdf.classList.remove("d-none");
            }
        }

        if (idProofInput) {
            idProofInput.addEventListener("change", function() {
                const file = this.files[0];
                if (!file) return;

                // Size check (2.5MB)
                if (file.size > 2500 * 1024) {
                    if (typeof Swal !== "undefined") {
                        Swal.fire("File Too Large", "Max size 2.5MB!", "error");
                    } else {
                        alert("Max size 2.5MB!");
                    }
                    this.value = "";
                    return;
                }

                // Type check
                if (!["image/jpeg", "image/jpg", "application/pdf"].includes(file.type)) {
                    if (typeof Swal !== "undefined") {
                        Swal.fire("Invalid File Type", "Only JPG/JPEG/PDF allowed!", "error");
                    } else {
                        alert("Only JPG/JPEG/PDF allowed!");
                    }
                    this.value = "";
                    return;
                }

                resetPreview();
                if (previewContainer) previewContainer.style.display = "block";
                const uploadPrompt = document.getElementById("uploadPrompt");
                const docActionsPrompt = document.getElementById("docActionsPrompt");
                if (uploadPrompt) uploadPrompt.style.display = "none";
                if (docActionsPrompt) docActionsPrompt.style.display = "flex";

                if (file.type === "application/pdf") {
                    const fileURL = URL.createObjectURL(file);

                    if (pdfChip) pdfChip.classList.remove("d-none");
                    else if (previewContainer) {
                        const newChip = document.createElement("div");
                        newChip.id = "pdfChip";
                        newChip.className =
                            "d-inline-flex align-items-center gap-2 px-3 py-2 rounded-pill bg-light border shadow-sm";
                        newChip.innerHTML =
                            `<i class="fas fa-file-pdf text-danger"></i><span class="fw-semibold">PDF Uploaded</span>`;
                        previewContainer.appendChild(newChip);
                    }

                    if (modalPdf) {
                        modalPdf.src = fileURL;
                        modalPdf.classList.remove("d-none");
                    }

                } else {
                    const reader = new FileReader();
                    reader.onload = e => {
                        if (imgPreview) {
                            imgPreview.src = e.target.result;
                            imgPreview.classList.remove("d-none");
                        }

                        if (modalImg) {
                            modalImg.src = e.target.result;
                            modalImg.classList.remove("d-none");
                        }
                    };
                    reader.readAsDataURL(file);
                }

                if (typeof Swal !== "undefined") {
                    Swal.fire({
                        icon: "success",
                        title: "Uploaded",
                        text: "Document uploaded successfully!",
                        timer: 1500,
                        showConfirmButton: false
                    });
                }
            });
        }
    });

    // Handle upload area click
    function handleUploadAreaClick(event) {
        if (!event.target.classList.contains("document-preview")) {
            const docInput = document.getElementById("id_proof_document");
            if (docInput) docInput.click();
        }
    }

    // Open document modal
    function openDocumentModal(event) {
        event.stopPropagation();
        const docModal = document.getElementById("documentModal");
        if (docModal) {
            new bootstrap.Modal(docModal, {
                backdrop: false
            }).show();
        }
    }

    // Handle photo area click
    function handlePhotoAreaClick(event) {
        if (!event.target.classList.contains('photo-preview')) {
            const photoInput = document.getElementById('photo');
            if (photoInput) photoInput.click();
        }
    }

    // Open photo modal
    function openPhotoModal(event) {
        event.stopPropagation();
        const photoPreview = document.getElementById('photoPreview');
        const modalPhoto = document.getElementById('modalPhotoPreview');
        if (photoPreview && modalPhoto && photoPreview.src) {
            modalPhoto.src = photoPreview.src;
        }
        const photoModal = document.getElementById('photoModal');
        if (photoModal) {
            new bootstrap.Modal(photoModal, {
                backdrop: false
            }).show();
        }
    }
</script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>