@section('title', 'Personal Information')

<style>
    .form-section {
        background: #ffffff;
        border-radius: 16px;
        box-shadow: 0 4px 24px rgba(15, 23, 42, 0.04);
        overflow: hidden;
        border: 1px solid #e2e8f0;
    }

    .section-header {
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #2D69FF 100%);
        color: white;
        padding: 16px 24px;
        margin: -1.5rem -1.5rem 1.5rem -1.5rem;
        box-shadow: 0 4px 12px rgba(15, 23, 42, 0.1);
    }

    .form-label {
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 6px;
        font-size: 0.84rem;
        letter-spacing: 0.2px;
    }

    .form-control,
    .form-select {
        border: 1.5px solid #cbd5e1;
        border-radius: 10px;
        padding: 8.5px 14px;
        font-size: 0.88rem;
        color: #0f172a;
        background-color: #f8fafc;
        transition: all 0.2s ease-in-out;
    }

    .form-control:focus,
    .form-select:focus {
        background-color: #ffffff;
        border-color: #2D69FF;
        box-shadow: 0 0 0 3px rgba(45, 105, 255, 0.14);
    }

    .input-group-text {
        border: 1.5px solid #cbd5e1;
        background-color: #e2e8f0;
        color: #334155;
        font-weight: 600;
        font-size: 0.88rem;
        border-radius: 10px 0 0 10px;
    }

    .photo-upload-area {
        border: 2px dashed #93c5fd;
        border-radius: 12px;
        padding: 10px 8px;
        text-align: center;
        transition: all 0.2s ease;
        cursor: pointer;
        background: #f0f9ff;
    }

    .photo-upload-area:hover {
        border-color: #2D69FF;
        background: #e0f2fe;
        box-shadow: 0 4px 12px rgba(45, 105, 255, 0.08);
    }

    .photo-preview {
        width: 72px;
        height: 72px;
        object-fit: cover;
        border-radius: 50%;
        border: 2px solid #ffffff;
        box-shadow: 0 3px 10px rgba(15, 23, 42, 0.12);
        cursor: pointer;
        transition: transform 0.2s ease;
    }

    .photo-preview:hover {
        transform: scale(1.04);
    }

    .document-preview {
        width: 100%;
        max-width: 190px;
        height: 120px;
        object-fit: contain;
        border-radius: 10px;
        border: 2px solid #cbd5e1;
        cursor: pointer;
        transition: transform 0.2s ease;
        background: #ffffff;
    }

    .upload-btn {
        background: linear-gradient(135deg, #2D69FF 0%, #1A52E0 100%);
        border: none;
        color: white !important;
        padding: 8px 20px;
        border-radius: 20px;
        font-weight: 600;
        font-size: 0.84rem;
        transition: all 0.2s ease;
        box-shadow: 0 3px 10px rgba(45, 105, 255, 0.2);
    }

    .upload-btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 5px 15px rgba(45, 105, 255, 0.3);
    }

    .sidebar-section {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        padding: 20px;
        box-shadow: 0 4px 20px rgba(15, 23, 42, 0.04);
    }

    .form-group {
        margin-bottom: 14px;
    }

    .required-star {
        color: #ef4444;
        font-weight: bold;
        margin-left: 2px;
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
            <div class="section-header d-flex align-items-center justify-content-between">
                <div>
                    <h5 class="mb-0 text-white fw-bold"><i class="fas fa-user-circle me-2"></i>Step 1: Personal & Contact Information</h5>
                    <small class="text-white-50 extra-small">Please ensure all identity details match your government ID proof</small>
                </div>
            </div>

            <!-- Prefix and Full Name -->
            <div class="form-group mb-3">
                <label for="full_name" class="form-label">
                    <i class="fas fa-user form-icon"></i>Full Name<span class="required-star">*</span>
                </label>
                <div class="input-group">
                    <select class="form-select flex-grow-0 @error('prefix') is-invalid @enderror" id="prefix" name="prefix"
                        required style="width: 110px; border-top-right-radius: 0; border-bottom-right-radius: 0;">
                        <option value="Dr." {{ old('prefix', $user->prefix) == 'Dr.' ? 'selected' : '' }}>Dr.</option>
                        <option value="Prof." {{ old('prefix', $user->prefix) == 'Prof.' ? 'selected' : '' }}>Prof.</option>
                        <option value="Mr." {{ old('prefix', $user->prefix) == 'Mr.' ? 'selected' : '' }}>Mr.</option>
                        <option value="Ms." {{ old('prefix', $user->prefix) == 'Mrs.' ? 'selected' : '' }}>Mrs.</option>
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
                        @php
                            $maxDob18Years = date('Y-m-d', strtotime('-18 years'));
                            $dobVal = old('dob');
                            if (!$dobVal && $user->date_of_birth) {
                                $formattedUserDob = $user->date_of_birth->format('Y-m-d');
                                if ($formattedUserDob !== '1990-01-01') {
                                    $dobVal = $formattedUserDob;
                                }
                            }
                            if (!$dobVal) {
                                $dobVal = $maxDob18Years;
                            }
                        @endphp
                        <input type="date" class="form-control @error('dob') is-invalid @enderror" id="dob"
                            name="dob"
                            value="{{ $dobVal }}"
                            required max="{{ $maxDob18Years }}" />
                        <small id="ageDisplay" class="form-text text-muted"></small>
                        @error('dob')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Mobile Number, WhatsApp Number & Dietary Preference -->
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group mb-3">
                        <label class="form-label">
                            <i class="fas fa-phone form-icon"></i>Mobile Number<span class="required-star">*</span>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text">{{ $user->mobile_country_code }}</span>
                            <input type="tel" class="form-control @error('mobile_number') is-invalid @enderror" id="mobile_number" name="mobile_number"
                                value="{{ old('mobile_number', $user->mobile_number) }}" required maxlength="{{(auth()->user()->delegate_type == 'Indian' ? '10' : '18')}}"
                                placeholder="Mobile number" oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                        </div>
                        @error('mobile_number')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group mb-3">
                        <label for="whatsapp_number" class="form-label">
                            <i class="fab fa-whatsapp text-success me-1"></i>WhatsApp Number<span class="required-star text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text">{{ $user->mobile_country_code }}</span>
                            <input type="tel" class="form-control @error('whatsapp_number') is-invalid @enderror" id="whatsapp_number" name="whatsapp_number"
                                value="{{ old('whatsapp_number', $registration->whatsapp_number ?: $user->mobile_number) }}" required maxlength="{{(auth()->user()->delegate_type == 'Indian' ? '10' : '18')}}"
                                placeholder="WhatsApp number" oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                        </div>
                        @error('whatsapp_number')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-4">
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

            <!-- Country, State, City, PIN Code -->
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
                <div class="col-md-3">
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
                <div class="col-md-3">
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
                <div class="col-md-3">
                    <div class="form-group mb-3">
                        <label for="pin_code" class="form-label">
                            <i class="fas fa-map-pin form-icon"></i>{{ auth()->user()->delegate_type == 'Indian' ? 'PIN Code' : 'Zip / Postal Code' }}<span class="required-star">*</span>
                        </label>
                        @if(auth()->user()->delegate_type == 'Indian')
                            <input type="text" class="form-control @error('pin_code') is-invalid @enderror"
                                id="pin_code" name="pin_code" value="{{ old('pin_code', $registration->pin_code) }}"
                                required placeholder="6-digit PIN Code" maxlength="6" minlength="6"
                                oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                        @else
                            <input type="text" class="form-control @error('pin_code') is-invalid @enderror"
                                id="pin_code" name="pin_code" value="{{ old('pin_code', $registration->pin_code) }}"
                                required placeholder="Zip Code (max 10)" maxlength="10"
                                oninput="this.value = this.value.replace(/[^A-Za-z0-9 -]/g, '')">
                        @endif
                        @error('pin_code')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Column - Photo & Government ID Upload -->
    <div class="col-lg-4">
        <!-- Profile Photo Section -->
        <div class="sidebar-section p-3 mb-3">
            <h6 class="fw-bold text-dark mb-2 extra-small">
                <i class="fas fa-camera text-primary me-1.5"></i>Profile Photo <span class="required-star">*</span>
            </h6>

            <div class="text-center">
                <div class="photo-upload-area py-2 px-2" onclick="document.getElementById('photo').click()" style="cursor: pointer;">
                    <img id="photoPreview" class="photo-preview mb-1.5"
                        src="{{ $registration->photo_path ? asset('storage/' . $registration->photo_path) : asset('images/default-avatar.svg') }}"
                        alt="Profile photo"
                        onerror="this.onerror=null; this.src='{{ asset('images/default-avatar.svg') }}';">
                    <div>
                        <button type="button" class="btn upload-btn btn-sm py-1 px-3 extra-small">
                            <i class="fas fa-camera me-1"></i>Choose Photo
                        </button>
                    </div>
                    <small class="text-muted extra-small d-block mt-1">JPG/PNG • Max 500KB</small>
                </div>
                <input type="file" id="photo" name="photo"
                    style="position: absolute; left: -9999px; opacity: 0;" accept="image/jpeg,image/jpg,image/png"
                    {{ $registration->photo_path ? '' : 'required' }}>
                @error('photo')
                <div class="text-danger extra-small mt-1"><i class="fas fa-exclamation-circle me-1"></i>{{ $message }}</div>
                @enderror
            </div>
        </div>

        <!-- Government ID Section -->
        <div class="sidebar-section">
            <h6 class="fw-bold text-dark mb-3">
                <i class="fas fa-id-card text-primary me-2"></i>Government ID Proof
            </h6>

            <div class="form-group mb-3">
                <label for="id_proof_type" class="form-label">
                    ID Document Type<span class="required-star">*</span>
                </label>
                <select class="form-select @error('id_proof_type') is-invalid @enderror" id="id_proof_type"
                    name="id_proof_type" required onchange="updateIdProofValidation()">

                    @if ($user->delegate_type == 'International')
                    <option value="">Select ID Type</option>
                    <option value="Passport"
                        {{ old('id_proof_type', $registration->id_proof_type) == 'Passport' ? 'selected' : '' }}>
                        Passport</option>
                    <option value="Driving License"
                        {{ old('id_proof_type', $registration->id_proof_type) == 'Driving License' ? 'selected' : '' }}>
                        Driving Licence</option>
                    @else
                    <option value="">Select ID Type</option>
                    <option value="Aadhaar"
                        {{ old('id_proof_type', $registration->id_proof_type) == 'Aadhaar' ? 'selected' : '' }}>
                        Aadhaar Card
                    </option>
                    <option value="PAN"
                        {{ old('id_proof_type', $registration->id_proof_type) == 'PAN' ? 'selected' : '' }}>PAN Card
                    </option>
                    <option value="Voter-ID"
                        {{ old('id_proof_type', $registration->id_proof_type) == 'Voter-ID' ? 'selected' : '' }}>
                        Voter ID</option>
                    <option value="Driving License"
                        {{ old('id_proof_type', $registration->id_proof_type) == 'Driving License' ? 'selected' : '' }}>
                        Driving License</option>
                    <option value="Passport"
                        {{ old('id_proof_type', $registration->id_proof_type) == 'Passport' ? 'selected' : '' }}>
                        Passport</option>
                    @endif
                </select>
            </div>

            <div class="form-group mb-3">
                <label for="id_proof_number" class="form-label" id="id_proof_number_label">
                    ID Proof Number <span class="required-star text-danger">*</span>
                </label>
                <input type="text" class="form-control @error('id_proof_number') is-invalid @enderror"
                    id="id_proof_number" name="id_proof_number"
                    value="{{ old('id_proof_number', $registration->id_proof_number) }}"
                    placeholder="Enter Aadhaar / PAN / ID Number" required
                    oninput="formatIdProofNumber(this)">
                @error('id_proof_number')
                <div class="invalid-feedback d-block extra-small mt-1"><i class="fas fa-exclamation-circle me-1"></i>{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group mb-0">
                <label for="id_proof_document" class="form-label fw-bold text-dark">
                    <i class="fas fa-file-shield text-primary me-1"></i>Upload ID Document <span class="required-star text-danger">*</span>
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
                                <span class="fw-semibold">PDF Document Uploaded</span>
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
                                <i class="fas fa-eye"></i> View Document
                            </button>
                        </div>

                        <small class="text-muted extra-small d-block mt-3">
                            JPG, JPEG or PDF • Max size: 2,500KB • Clear, readable document only
                        </small>
                    </div>
                    <!-- File input positioned off-screen but still focusable -->
                    <input type="file" id="id_proof_document" name="id_proof_document"
                        style="position: absolute; left: -9999px; opacity: 0;"
                        accept="image/jpeg,image/jpg,application/pdf"
                        {{ $registration->id_proof_document_path ? '' : 'required' }}>
                    @error('id_proof_document')
                    <div class="text-danger extra-small mt-2">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Photo Modal -->
<div class="modal fade" id="photoModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-3">
            <div class="modal-header bg-primary text-white py-3">
                <h5 class="modal-title text-white mb-0"><i class="fas fa-user me-2"></i>Profile Photo Preview</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center p-3">
                <img id="modalPhotoPreview" class="img-fluid rounded shadow-sm" alt="Profile Photo">
            </div>
            <div class="modal-footer bg-light py-2">
                <button type="button" class="btn btn-secondary btn-sm px-4 rounded-pill" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1.5"></i>Close
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Document Modal -->
<div class="modal fade" id="documentModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-3">
            <div class="modal-header bg-primary text-white py-3">
                <h5 class="modal-title text-white mb-0"><i class="fas fa-id-card me-2"></i>Government ID Document Preview</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center p-3">
                <img id="modalDocumentPreview" class="img-fluid d-none rounded shadow-sm" alt="Modal Preview">
                <embed id="modalPdfPreview" class="w-100 d-none rounded" style="height:500px;" type="application/pdf">
            </div>
            <div class="modal-footer bg-light py-2">
                <button type="button" class="btn btn-secondary btn-sm px-4 rounded-pill" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1.5"></i>Close
                </button>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        updateIdProofValidation();

        // Photo upload listener
        const photoInput = document.getElementById("photo");
        const photoPreview = document.getElementById("photoPreview");

        if (photoInput) {
            photoInput.addEventListener("change", function() {
                const file = this.files[0];
                if (!file) return;

                if (file.size > 500 * 1024) {
                    alert("Profile photo size must be less than 500KB!");
                    this.value = "";
                    return;
                }

                const reader = new FileReader();
                reader.onload = function(e) {
                    if (photoPreview) photoPreview.src = e.target.result;
                };
                reader.readAsDataURL(file);
            });
        }

        // ID Proof document listener
        const docInput = document.getElementById("id_proof_document");
        const idProofPreview = document.getElementById("idProofPreview");
        const pdfChip = document.getElementById("pdfChip");
        const uploadPrompt = document.getElementById("uploadPrompt");
        const docActionsPrompt = document.getElementById("docActionsPrompt");
        const container = document.getElementById("documentPreviewContainer");

        if (docInput) {
            docInput.addEventListener("change", function() {
                const file = this.files[0];
                if (!file) return;

                if (file.size > 2500 * 1024) {
                    alert("Document size must be less than 2,500KB (2.5MB)!");
                    this.value = "";
                    return;
                }

                const isPdf = file.type === "application/pdf" || file.name.endsWith(".pdf");

                if (container) container.style.display = "block";
                if (uploadPrompt) uploadPrompt.style.display = "none";
                if (docActionsPrompt) docActionsPrompt.style.display = "flex";

                if (isPdf) {
                    if (idProofPreview) idProofPreview.classList.add("d-none");
                    if (pdfChip) pdfChip.classList.remove("d-none");
                    if (container) {
                        container.setAttribute("data-is-pdf", "true");
                        container.setAttribute("data-doc-path", URL.createObjectURL(file));
                    }
                } else {
                    if (pdfChip) pdfChip.classList.add("d-none");
                    if (idProofPreview) {
                        idProofPreview.classList.remove("d-none");
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            idProofPreview.src = e.target.result;
                            if (container) {
                                container.setAttribute("data-is-pdf", "false");
                                container.setAttribute("data-doc-path", e.target.result);
                            }
                        };
                        reader.readAsDataURL(file);
                    }
                }
            });
        }
    });

    function handlePhotoAreaClick(event) {
        document.getElementById('photo').click();
    }

    function handleUploadAreaClick(event) {
        if (event.target.closest('#docActionsPrompt') || event.target.id === 'idProofPreview') {
            return;
        }
        document.getElementById('id_proof_document').click();
    }

    function openPhotoModal(event) {
        document.getElementById('photo').click();
    }

    document.addEventListener('DOMContentLoaded', function () {
        const documentModal = document.getElementById('documentModal');
        if (documentModal && documentModal.parentElement !== document.body) {
            document.body.appendChild(documentModal);
        }
        const photoModal = document.getElementById('photoModal');
        if (photoModal && photoModal.parentElement !== document.body) {
            document.body.appendChild(photoModal);
        }
    });

    document.addEventListener('hidden.bs.modal', function () {
        document.querySelectorAll('.modal-backdrop').forEach(b => b.remove());
        document.body.classList.remove('modal-open');
        document.body.style.overflow = '';
        document.body.style.paddingRight = '';
    });

    function openDocumentModal(event) {
        if (event) event.stopPropagation();
        const container = document.getElementById('documentPreviewContainer');
        if (!container) return;

        const docPath = container.getAttribute('data-doc-path');
        const isPdf = container.getAttribute('data-is-pdf') === 'true';

        const modalImg = document.getElementById('modalDocumentPreview');
        const modalPdf = document.getElementById('modalPdfPreview');

        if (isPdf) {
            if (modalImg) modalImg.classList.add('d-none');
            if (modalPdf) {
                modalPdf.src = docPath;
                modalPdf.classList.remove('d-none');
            }
        } else {
            if (modalPdf) modalPdf.classList.add('d-none');
            if (modalImg) {
                modalImg.src = docPath;
                modalImg.classList.remove('d-none');
            }
        }

        const documentModal = document.getElementById('documentModal');
        if (documentModal) {
            if (documentModal.parentElement !== document.body) {
                document.body.appendChild(documentModal);
            }
            const modalInstance = bootstrap.Modal.getOrCreateInstance(documentModal);
            modalInstance.show();
        }
    }

    function updateIdProofValidation() {
        const typeSelect = document.getElementById('id_proof_type');
        const numberInput = document.getElementById('id_proof_number');
        const label = document.getElementById('id_proof_number_label');
        if (!typeSelect || !numberInput) return;

        const selectedType = typeSelect.value;
        if (selectedType === 'Aadhaar') {
            if (label) label.innerHTML = 'Aadhaar Number <span class="required-star text-danger">*</span>';
            numberInput.placeholder = 'Enter 12-digit Aadhaar Number';
            numberInput.maxLength = 12;
        } else if (selectedType === 'PAN') {
            if (label) label.innerHTML = 'PAN Card Number <span class="required-star text-danger">*</span>';
            numberInput.placeholder = 'Enter 10-character PAN Number (e.g. ABCDE1234F)';
            numberInput.maxLength = 10;
        } else if (selectedType === 'Passport') {
            if (label) label.innerHTML = 'Passport Number <span class="required-star text-danger">*</span>';
            numberInput.placeholder = 'Enter Passport Number';
            numberInput.maxLength = 12;
        } else if (selectedType === 'Voter-ID') {
            if (label) label.innerHTML = 'Voter ID Number <span class="required-star text-danger">*</span>';
            numberInput.placeholder = 'Enter Voter ID Number';
            numberInput.maxLength = 12;
        } else if (selectedType === 'Driving License') {
            if (label) label.innerHTML = 'Driving License Number <span class="required-star text-danger">*</span>';
            numberInput.placeholder = 'Enter Driving License Number';
            numberInput.maxLength = 20;
        } else {
            if (label) label.innerHTML = 'ID Proof Number <span class="required-star text-danger">*</span>';
            numberInput.placeholder = 'Enter ID Proof Number';
            numberInput.removeAttribute('maxlength');
        }
        formatIdProofNumber(numberInput);
    }

    function formatIdProofNumber(input) {
        if (!input) return;
        const typeSelect = document.getElementById('id_proof_type');
        const selectedType = typeSelect ? typeSelect.value : '';
        if (selectedType === 'Aadhaar') {
            input.value = input.value.replace(/[^0-9]/g, '');
        } else if (selectedType === 'PAN' || selectedType === 'Voter-ID' || selectedType === 'Passport') {
            input.value = input.value.toUpperCase().replace(/[^A-Z0-9]/g, '');
        } else if (selectedType === 'Driving License') {
            input.value = input.value.toUpperCase().replace(/[^A-Z0-9\/-]/g, '');
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        updateIdProofValidation();
    });
</script>