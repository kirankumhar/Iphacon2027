@section('title', 'Personal Information')

<style>
    .form-section {
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(46, 49, 146, 0.08);
        overflow: hidden;
        padding: 0%;
    }

    .section-header {
        background: linear-gradient(135deg, #2e3192 0%, #4a5dab 100%);
        color: white;
        padding: 20px 25px;
        margin: -15px -15px 25px -15px;
    }

    .form-label {
        font-weight: 600;
        color: #2e3192;
        margin-bottom: 8px;
    }

    .form-control,
    .form-select {
        border: 1.5px solid #e1e5e9;
        border-radius: 8px;
        padding: 12px 15px;
        transition: all 0.3s ease;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: #2e3192;
        box-shadow: 0 0 0 0.2rem rgba(46, 49, 146, 0.15);
    }

    .photo-upload-area {
        border: 2px dashed #dee2e6;
        border-radius: 12px;
        padding: 20px;
        text-align: center;
        transition: all 0.3s ease;
        cursor: pointer;
        background: #f8f9fa;
    }

    .photo-upload-area:hover {
        border-color: #2e3192;
        background: #f0f1ff;
    }

    .photo-preview {
        width: 120px;
        height: 120px;
        object-fit: cover;
        border-radius: 50%;
        border: 4px solid #fff;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        cursor: pointer;
        transition: transform 0.3s ease;
    }

    .photo-preview:hover {
        transform: scale(1.05);
    }

    .document-preview {
        width: 100%;
        max-width: 200px;
        height: 150px;
        object-fit: cover;
        border-radius: 8px;
        border: 2px solid #dee2e6;
        cursor: pointer;
        transition: transform 0.3s ease;
    }

    .document-preview:hover {
        transform: scale(1.02);
        border-color: #2e3192;
    }

    .upload-btn {
        background: linear-gradient(135deg, #2e3192 0%, #4a5dab 100%);
        border: none;
        color: white;
        padding: 10px 20px;
        border-radius: 25px;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .upload-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(46, 49, 146, 0.3);
    }

    .modal-content {
        border-radius: 15px;
        overflow: hidden;
    }

    .modal-header {
        background: linear-gradient(135deg, #2e3192 0%, #4a5dab 100%);
        color: white;
        border: none;
    }

    .sidebar-section {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        border-radius: 12px;
        padding: 25px;
        height: fit-content;
        position: sticky;
        top: 20px;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .required-star {
        color: #dc3545;
        font-weight: bold;
    }

    .form-icon {
        color: #2e3192;
        margin-right: 8px;
    }
</style>

<div class="row">
    <!-- Left Column - Main Form -->
    <div class="col-lg-8">
        <div class="form-section p-4">
            <div class="section-header">
                <h4 class="mb-0"><i class="fas fa-user me-2"></i>Step 1: Personal Information</h4>
            </div>

            <!-- Prefix and Full Name -->
            <div class="form-group">
                <label for="prefix" class="form-label">
                    <i class="fas fa-user form-icon"></i>Full Name<span class="required-star">*</span>
                </label>
                <div class="row">
                    <div class="col-md-3">
                        <select class="form-select @error('prefix') is-invalid @enderror" id="prefix" name="prefix"
                            required>
                            <option value="Dr." {{ old('prefix', $user->prefix) == 'Dr.' ? 'selected' : '' }}>Dr.
                            </option>
                            <option value="Prof." {{ old('prefix', $user->prefix) == 'Prof.' ? 'selected' : '' }}>Prof.
                            </option>
                            <option value="Mr." {{ old('prefix', $user->prefix) == 'Mr.' ? 'selected' : '' }}>Mr.
                            </option>
                            <option value="Mrs." {{ old('prefix', $user->prefix) == 'Mrs.' ? 'selected' : '' }}>Mrs.
                            </option>

                        </select>
                        @error('prefix')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-9">
                        <input type="text" class="form-control" id="full_name" name="full_name"
                            value="{{ old('full_name', $user->full_name) }}" required maxlength="50"
                            pattern="[A-Za-z. ]{2,}" placeholder="Enter your full name"
                            oninput="this.value = this.value.replace(/[^A-Za-z. ]/g, '')">

                        @error('full_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Gender and Date of Birth -->
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="gender" class="form-label">
                            <i class="fas fa-venus-mars form-icon"></i>Gender<span class="required-star">*</span>
                        </label>
                        <select class="form-select @error('gender') is-invalid @enderror" id="gender" name="gender"
                            required>
                            <option value="" disabled {{ old('gender', $user->gender) ? '' : 'selected' }}>Select
                                Gender</option>
                            <option value="Male" {{ old('gender', $user->gender) == 'Male' ? 'selected' : '' }}>Male
                            </option>
                            <option value="Female" {{ old('gender', $user->gender) == 'Female' ? 'selected' : '' }}>
                                Female</option>
                        </select>
                        @error('gender')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
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

            <!-- Mobile Number -->
            <div class="form-group">
                <label class="form-label">
                    <i class="fas fa-phone form-icon"></i>Mobile Number<span class="required-star">*</span>
                </label>
                <div class="row">
                    <div class="col-md-4">
                        <input type="text" class="form-control" value="{{ $user->mobile_country_code }}" readonly
                            style="background-color: #f8f9fa;">
                        <small class="text-muted">Fixed from registration</small>
                    </div>
                    <div class="col-md-8">
                        <input type="tel" class="form-control" id="mobile_number" name="mobile_number"
                            value="{{ old('mobile_number', $user->mobile_number) }}" required maxlength="{{(auth()->user()->delegate_type == 'Indian' ? '10' : '18')}}"
                            placeholder="Enter mobile number" oninput="this.value = this.value.replace(/[^0-9]/g, '')">

                        @error('mobile_number')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="form-group">
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

            <!-- Address -->
            <div class="form-group">
                <label for="address" class="form-label">
                    <i class="fas fa-home form-icon"></i>Address<span class="required-star">*</span>
                </label>
                <textarea class="form-control" id="address" name="address" rows="3" required maxlength="100"
                    placeholder="Enter your complete address" oninput="this.value = this.value.replace(/[^A-Za-z0-9 ,\/-]/g, '')"
                    pattern="[A-Za-z0-9 ,\/-]+">{{ old('address', $registration->address) }}</textarea>

                @error('address')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Country, State, City -->
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="country_id" class="form-label">
                            <i class="fas fa-globe form-icon"></i>Country<span class="required-star">*</span>
                        </label>
                        @foreach ($countries as $country)
                        @if (auth()->user()->country_id == $country->id)
                        <input class="form-control" name="country_id" value="{{ $country->country_name }}"
                            readonly style="background-color: #f8f9fa;" />
                        @endif
                        @endforeach
                        @error('country_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
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
                    <div class="form-group">
                        <label for="city" class="form-label">
                            <i class="fas fa-city form-icon"></i>City<span class="required-star">*</span>
                        </label>
                        <input type="text" class="form-control" id="city" name="city"
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
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="pin_code" class="form-label">
                            <i class="fas fa-map-pin form-icon"></i>PIN/Zip Code<span class="required-star">*</span>
                        </label>
                        <input type="text" class="form-control @error('pin_code') is-invalid @enderror"
                            id="pin_code" name="pin_code" value="{{ old('pin_code', $registration->pin_code) }}"
                            required placeholder="Enter PIN/Zip code" maxlength="8"
                            oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                        @error('pin_code')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">
                            <i class="fab fa-whatsapp form-icon"></i>WhatsApp Number <small
                                class="text-muted">(Optional)</small>
                        </label>
                        <div class="row">
                            <div class="col-5">
                                <input type="text" class="form-control" value="{{ $user->mobile_country_code }}"
                                    name="whatsapp_country_code" readonly style="background-color: #f8f9fa;">
                            </div>
                            <div class="col-7">
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
                        src="{{ $registration->photo_path ? asset('storage/' . $registration->photo_path) : asset('images/default-avatar.png') }}"
                        alt="Profile photo" onclick="openPhotoModal(event)">
                    <div>
                        <button type="button" class="btn upload-btn btn-sm">
                            <i class="fas fa-camera me-1"></i>Choose Photo
                        </button>
                    </div>
                    <small class="text-muted d-block mt-2">JPG/JPEG only, max 500KB</small>
                </div>
                <input type="file" id="photo" name="photo"
                    style="position: absolute; left: -9999px; opacity: 0;" accept="image/jpeg,image/jpg">
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

            {{-- <div class="form-group">
                <label for="id_proof_number" class="form-label">
                    ID Number<span class="required-star">*</span>
                </label>
                <input type="text" class="form-control @error('id_proof_number') is-invalid @enderror"
                    id="id_proof_number" name="id_proof_number"
                    value="{{ old('id_proof_number', $registration->id_proof_number) }}" required>
            <small id="id-proof-help" class="text-muted"></small>
            @error('id_proof_number')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div> --}}
        <div class="form-group">
            <label for="id_proof_document" class="form-label fw-semibold" style="color:#2e3192;">
                <i class="fas fa-file-shield me-1"></i>Upload Document <span
                    class="required-star text-danger">*</span>
            </label>

            <div class="text-center">
                @php
                $docPath = $registration->id_proof_document_path
                ? asset('storage/' . $registration->id_proof_document_path)
                : '';
                $isPdf = $docPath && str_ends_with(strtolower($docPath), '.pdf');
                @endphp

                <div class="photo-upload-area" onclick="handleUploadAreaClick(event)">
                    <div id="documentPreviewContainer" data-doc-path="{{ $docPath }}" data-is-pdf="{{ $isPdf ? 'true' : 'false' }}" style="display: {{ $registration->id_proof_document_path ? 'block' : 'none' }}">

                        <!-- Always keep img for preview -->
                        <img id="idProofPreview" alt="Document Preview" src="{{ $docPath }}"
                            class="img-fluid rounded shadow-sm document-preview {{ ($docPath && !$isPdf) ? '' : 'd-none'}}"
                            style="max-height:220px; object-fit:contain;">

                        <!-- PDF chip placeholder -->
                        <div id="pdfChip"
                            class="d-inline-flex align-items-center gap-2 px-3 py-2 rounded-pill bg-light border shadow-sm {{ ($docPath && $isPdf) ? '' : 'd-none'}}">
                            <i class="fas fa-file-pdf text-danger"></i>
                            <span class="fw-semibold">PDF Uploaded</span>
                        </div>
                    </div>

                    <div class="mt-3 d-flex flex-wrap justify-content-center gap-2">
                        <button type="button"
                            class="btn btn-success d-flex align-items-center gap-2 px-4 py-2 shadow-sm rounded-pill"
                            title="Preview Document" onclick="openDocumentModal(event)">
                            <i class="fas fa-eye"></i> View
                        </button>

                        <div id="uploadPrompt">
                            <i class="fas fa-cloud-upload-alt fa-2x text-muted mb-2"></i>
                            <div>
                                <button type="button" class="btn upload-btn btn-sm">
                                    <i class="fas fa-upload me-1"></i>Upload / Replace Document
                                </button>
                            </div>
                        </div>
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
                modalPdf.src = existingPath; // Now reads from data attribute ✓
                modalPdf.classList.remove("d-none");
            } else {
                modalImg.src = existingPath; // Now reads from data attribute ✓
                modalImg.classList.remove("d-none");
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
                modalPdf.src = ""; // Clear old src to stop PDF rendering
                modalPdf.classList.add("d-none");
            }
        }

        if (imgPreview && imgPreview.src) {
            modalImg.src = imgPreview.src;
            modalImg.classList.remove("d-none");
        } else if (pdfChip) {
            modalPdf.src = "{{ $docPath ?? '' }}";
            modalPdf.classList.remove("d-none");
        }

        idProofInput.addEventListener("change", function() {
            const file = this.files[0];
            if (!file) return;

            // Size check (2.5MB)
            if (file.size > 2500 * 1024) {
                Swal.fire("File Too Large", "Max size 2.5MB!", "error");
                return this.value = "";
            }

            // Type check
            if (!["image/jpeg", "image/jpg", "application/pdf"].includes(file.type)) {
                Swal.fire("Invalid File Type", "Only JPG/JPEG/PDF allowed!", "error");
                return this.value = "";
            }

            resetPreview();
            previewContainer.style.display = "block";

            if (file.type === "application/pdf") {
                const fileURL = URL.createObjectURL(file);

                // Show PDF chip
                if (pdfChip) pdfChip.classList.remove("d-none");
                else {
                    const newChip = document.createElement("div");
                    newChip.id = "pdfChip";
                    newChip.className =
                        "d-inline-flex align-items-center gap-2 px-3 py-2 rounded-pill bg-light border shadow-sm";
                    newChip.innerHTML =
                        `<i class="fas fa-file-pdf text-danger"></i><span class="fw-semibold">PDF Uploaded</span>`;
                    previewContainer.appendChild(newChip);
                }

                modalPdf.src = fileURL;
                modalPdf.classList.remove("d-none");

            } else {
                // Image Preview
                const reader = new FileReader();
                reader.onload = e => {
                    imgPreview.src = e.target.result;
                    imgPreview.classList.remove("d-none");

                    modalImg.src = e.target.result;
                    modalImg.classList.remove("d-none");
                };
                reader.readAsDataURL(file);
            }

            Swal.fire({
                icon: "success",
                title: "Uploaded",
                text: "Document uploaded successfully!",
                timer: 1500,
                showConfirmButton: false
            });
        });
    });

    // Handle upload area click
    function handleUploadAreaClick(event) {
        if (!event.target.classList.contains("document-preview")) {
            document.getElementById("id_proof_document").click();
        }
    }

    // Open modal
    function openDocumentModal(event) {
        event.stopPropagation();
        new bootstrap.Modal(document.getElementById("documentModal"), {
            backdrop: false
        }).show();
    }

    function handlePhotoAreaClick(event) {
        if (!event.target.classList.contains('photo-preview')) {
            document.getElementById('photo').click();
        }
    }

    function openPhotoModal(event) {
        event.stopPropagation(); // Prevent bubbling to parent

        const fileDialogOpen = document.getElementById('photo').matches(':focus');

        if (!fileDialogOpen) {
            setTimeout(() => {
                const modal = new bootstrap.Modal(document.getElementById('photoModal'), {
                    backdrop: false
                });
                modal.show();
            }, 100);
        }
    }
</script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>