@extends('shared.auth-delegate')
@php
    $inner_title = '';
@endphp
@section('delegate-content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card shadow-lg border-0" style="border-radius: 15px;">
                <div class="card-header text-center py-4"
                     style="background: linear-gradient(135deg, #2e3192, #4a5bcc); border-radius: 15px 15px 0 0;">
                    <h3 class="text-white mb-0 fw-bold">
                        <i class="fas fa-edit me-2"></i>Conference Registration
                    </h3>
                </div>

                <div class="card-body p-5">
                    <form method="POST" action="{{ route('registration.store') }}" enctype="multipart/form-data">
                        @csrf

                        <!-- User Information Display -->
                        <div class="row mb-4">
                            <div class="col-md-12">
                                <div class="alert alert-info">
                                    <h5><i class="fas fa-user me-2"></i>Personal Information</h5>
                                    <p class="mb-0"><strong>Name:</strong> {{ $user->prefix }} {{ $user->full_name }}</p>
                                    <p class="mb-0"><strong>Email:</strong> {{ $user->email }}</p>
                                    <p class="mb-0"><strong>Mobile:</strong> {{ $user->mobile_country_code }} {{ $user->mobile_number }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Photo Upload -->
                        <div class="row mb-3">
                            <div class="col-md-3">
                                <label for="photo" class="form-label fw-semibold" style="color:#2e3192">
                                    <i class="fas fa-camera me-1"></i>Photo<span class="text-danger">*</span>
                                </label>
                            </div>
                            <div class="col-md-9">
                                <input type="file" class="form-control @error('photo') is-invalid @enderror"
                                       id="photo" name="photo" accept="image/*" required>
                                <small class="text-muted">Upload a passport-size photo (JPG, JPEG, PNG - Max 2MB)</small>
                                @error('photo')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Address -->
                        <div class="row mb-3">
                            <div class="col-md-3">
                                <label for="address" class="form-label fw-semibold" style="color:#2e3192">
                                    <i class="fas fa-home me-1"></i>Address<span class="text-danger">*</span>
                                </label>
                            </div>
                            <div class="col-md-9">
                                <textarea class="form-control @error('address') is-invalid @enderror"
                                          id="address" name="address" rows="3" required
                                          placeholder="Enter your complete address">{{ old('address') }}</textarea>
                                @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Country, State, City -->
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="country_id" class="form-label fw-semibold" style="color:#2e3192">
                                    Country<span class="text-danger">*</span>
                                </label>
                                <select class="form-select @error('country_id') is-invalid @enderror"
                                        id="country_id" name="country_id" required>
                                    <option value="">Select Country</option>
                                    @foreach($countries as $country)
                                        <option value="{{ $country->id }}" {{ (old('country_id') == $country->id || (!old('country_id') && str_contains(strtolower($country->country_name), 'india'))) ? 'selected' : '' }}>
                                            {{ $country->country_name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('country_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label for="state_id" class="form-label fw-semibold" style="color:#2e3192">
                                    State<span class="text-danger">*</span>
                                </label>
                                <select class="form-select @error('state_id') is-invalid @enderror"
                                        id="state_id" name="state_id" required>
                                    <option value="">Select State</option>
                                </select>
                                @error('state_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label for="pin_code" class="form-label fw-semibold" style="color:#2e3192">
                                    PIN Code<span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control @error('pin_code') is-invalid @enderror"
                                       id="pin_code" name="pin_code" value="{{ old('pin_code') }}" required>
                                @error('pin_code')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Delegate Category -->
                        <div class="row mb-3">
                            <div class="col-md-3">
                                <label for="delegate_category_id" class="form-label fw-semibold" style="color:#2e3192">
                                    <i class="fas fa-tags me-1"></i>Delegate Category<span class="text-danger">*</span>
                                </label>
                            </div>
                            <div class="col-md-9">
                                <select class="form-select @error('delegate_category_id') is-invalid @enderror"
                                        id="delegate_category_id" name="delegate_category_id" required>
                                    <option value="">Select Category</option>
                                    @foreach($delegateCategories as $category)
                                        <option value="{{ $category->id }}" {{ old('delegate_category_id') == $category->id ? 'selected' : '' }}>
                                            {{ $category->category_name }}
                                            (Indian: ₹{{ number_format($category->indian_fee) }}, Foreign: ${{ $category->foreign_fee }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('delegate_category_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Delegate Type -->
                        <div class="row mb-3">
                            <div class="col-md-3">
                                <label class="form-label fw-semibold" style="color:#2e3192">
                                    <i class="fas fa-globe me-1"></i>Delegate Type<span class="text-danger">*</span>
                                </label>
                            </div>
                            <div class="col-md-9">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="delegate_type"
                                           id="indian" value="Indian" {{ old('delegate_type') == 'Indian' ? 'checked' : '' }} required>
                                    <label class="form-check-label" for="indian">Indian</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="delegate_type"
                                           id="foreign" value="Foreign" {{ old('delegate_type') == 'Foreign' ? 'checked' : '' }} required>
                                    <label class="form-check-label" for="foreign">Foreign</label>
                                </div>
                                @error('delegate_type')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- ID Proof -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="id_proof_type" class="form-label fw-semibold" style="color:#2e3192">
                                    <i class="fas fa-id-card me-1"></i>ID Proof Type<span class="text-danger">*</span>
                                </label>
                                <select class="form-select @error('id_proof_type') is-invalid @enderror"
                                        id="id_proof_type" name="id_proof_type" required>
                                    <option value="">Select ID Type</option>
                                    <option value="Aadhaar" {{ old('id_proof_type') == 'Aadhaar' ? 'selected' : '' }}>Aadhaar</option>
                                    <option value="PAN" {{ old('id_proof_type') == 'PAN' ? 'selected' : '' }}>PAN</option>
                                    <option value="Voter-ID" {{ old('id_proof_type') == 'Voter-ID' ? 'selected' : '' }}>Voter ID</option>
                                    <option value="Driving License" {{ old('id_proof_type') == 'Driving License' ? 'selected' : '' }}>Driving License</option>
                                    <option value="Passport" {{ old('id_proof_type') == 'Passport' ? 'selected' : '' }}>Passport</option>
                                </select>
                                @error('id_proof_type')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="id_proof_number" class="form-label fw-semibold" style="color:#2e3192">
                                    ID Proof Number<span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control @error('id_proof_number') is-invalid @enderror"
                                       id="id_proof_number" name="id_proof_number" value="{{ old('id_proof_number') }}" required>
                                @error('id_proof_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- ID Proof Document -->
                        <div class="row mb-3">
                            <div class="col-md-3">
                                <label for="id_proof_document" class="form-label fw-semibold" style="color:#2e3192">
                                    <i class="fas fa-file-upload me-1"></i>ID Proof Document<span class="text-danger">*</span>
                                </label>
                            </div>
                            <div class="col-md-9">
                                <input type="file" class="form-control @error('id_proof_document') is-invalid @enderror"
                                       id="id_proof_document" name="id_proof_document"
                                       accept=".pdf,.jpg,.jpeg,.png" required>
                                <small class="text-muted">Upload ID proof document (PDF, JPG, JPEG, PNG - Max 2MB)</small>
                                @error('id_proof_document')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="row">
                            <div class="col-md-12 text-end">
                                <button type="submit" class="btn btn-primary btn-lg px-4 py-2 fw-bold"
                                    style="background: linear-gradient(135deg, #2e3192, #4a5bcc); border: none; border-radius: 25px; box-shadow: 0 4px 15px rgba(46, 49, 146, 0.3);">
                                    <i class="fas fa-save me-2"></i>Submit Registration
                                </button>
                            </div>
                        </div>

                        <!-- Error Summary -->
                        @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
                            <strong><i class="fas fa-exclamation-triangle me-2"></i>Please fix the following errors:</strong>
                            <ul class="mb-0 mt-2">
                                @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                        @endif
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
