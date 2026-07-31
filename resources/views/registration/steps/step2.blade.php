<!-- Step 2 Header -->
<div class="d-flex align-items-center justify-content-between mb-4 pb-3 border-bottom">
    <div class="d-flex align-items-center gap-3">
        <div class="rounded-circle d-flex align-items-center justify-content-center"
            style="width: 46px; height: 46px; background: linear-gradient(135deg, #2D69FF 0%, #1A52E0 100%); color: #ffffff; font-size: 1.25rem; box-shadow: 0 4px 12px rgba(45, 105, 255, 0.25);">
            <i class="fas fa-clipboard-list"></i>
        </div>
        <div>
            <h4 class="fw-bold mb-0" style="color: #1e293b;">Step 2: Conference Registration</h4>
            <small class="text-muted">Select your delegate category and optional conference add-ons</small>
        </div>
    </div>
    <span class="badge px-3 py-2 fs-6 fw-bold" style="background-color: #E1F0FF; color: #2D69FF; border-radius: 20px;">
        <i class="fas fa-flag me-1"></i> {{ $user->delegate_type }} Delegate
    </span>
</div>

<!-- Custom CSS for Step 2 Cards & Interactive Selector Cards -->
<style>
    .step2-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.03);
        transition: all 0.25s ease-in-out;
    }
    .step2-card:hover {
        border-color: rgba(45, 105, 255, 0.3);
        box-shadow: 0 8px 24px rgba(45, 105, 255, 0.08);
    }
    .step2-section-title {
        color: #2D69FF;
        font-weight: 700;
        font-size: 1.05rem;
    }
    .step2-radio-card {
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        padding: 14px 18px;
        cursor: pointer;
        transition: all 0.2s ease;
        background: #f8fafc;
        display: flex;
        align-items: center;
        justify-content: space-between;
        width: 100%;
    }
    .step2-radio-card:hover {
        border-color: #93c5fd;
        background: #f0f9ff;
    }
    .step2-radio-input:checked + .step2-radio-card {
        border-color: #2D69FF;
        background: #E1F0FF;
        box-shadow: 0 4px 12px rgba(45, 105, 255, 0.15);
    }
    .step2-radio-input:checked + .step2-radio-card .radio-title {
        color: #2D69FF;
        font-weight: 700;
    }
    .total-fee-banner {
        background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
        border-radius: 16px;
        color: #ffffff;
        box-shadow: 0 10px 25px rgba(15, 23, 42, 0.15);
        border: 1px solid rgba(45, 105, 255, 0.2);
    }
</style>

<!-- Delegate Type Information Banner -->
<div class="card step2-card mb-4 border-0" style="background: linear-gradient(135deg, #E1F0FF 0%, #F0F6FF 100%); border-left: 5px solid #2D69FF !important;">
    <div class="card-body p-3.5 px-4 d-flex align-items-center justify-content-between flex-wrap gap-2">
        <div class="d-flex align-items-center gap-3">
            <i class="fas fa-globe-americas fs-3 text-primary"></i>
            <div>
                <strong class="d-block text-dark" style="font-size: 0.95rem;">
                    Registration Mode: <span class="text-primary">{{ $user->delegate_type }} Delegate</span>
                </strong>
                <small class="text-muted">Pricing Currency: <strong>{{ $user->delegate_type == 'Indian' ? 'INR (₹)' : 'USD ($)' }}</strong></small>
            </div>
        </div>
        <span class="badge px-3 py-1.5 fw-semibold" style="background-color: #DCFFF0; color: #4BAA7D; border-radius: 20px;">
            <i class="fas fa-check-circle me-1"></i> Active Status
        </span>
    </div>
    <!-- Hidden input to maintain form data -->
    <input type="hidden" name="delegate_type" value="{{ $user->delegate_type }}">
</div>

@if ($user->delegate_type == 'Indian')
    <!-- Indian Delegate Form Section -->
    <div class="card step2-card mb-4">
        <div class="card-header bg-transparent py-3 px-4 border-bottom d-flex align-items-center gap-2">
            <i class="fas fa-layer-group text-primary"></i>
            <span class="step2-section-title">1. Delegate Category Selection</span>
        </div>
        <div class="card-body p-4">
            <!-- Delegate Category Select -->
            <div class="mb-3">
                <label for="delegate_category_id" class="form-label fw-bold text-dark mb-2">
                    <i class="fas fa-tags text-primary me-1.5"></i>Select Category <span class="text-danger">*</span>
                </label>
                <div class="input-group">
                    <span class="input-group-text bg-light text-muted px-3" style="border-radius: 10px 0 0 10px;">
                        <i class="fas fa-list-ul"></i>
                    </span>
                    <select class="form-select @error('delegate_category_id') is-invalid @enderror py-2.5"
                        id="delegate_category_id" name="delegate_category_id" required style="border-radius: 0 10px 10px 0; font-size: 0.95rem;">
                        <option value="">-- Choose Delegate Category --</option>
                        @foreach ($delegateCategories as $category)
                            <option value="{{ $category->id }}" data-fee="{{ $category->indian_fee }}"
                                {{ old('delegate_category_id', $registration->delegate_category_id) == $category->id ? 'selected' : '' }}>
                                {{ $category->category_name }} — ₹{{ number_format($category->indian_fee) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                @error('delegate_category_id')
                    <div class="invalid-feedback d-block mt-1.5"><i class="fas fa-exclamation-circle me-1"></i>{{ $message }}</div>
                @enderror
            </div>

            <!-- Dynamic Membership Fields Section -->
            <!-- ISMM Membership (Category ID 2) -->
            <div class="mt-3 p-3.5 rounded bg-light border" id="ismm_membership_row" style="display: none; border-left: 4px solid #2D69FF !important;">
                <label for="ismm_membership_no" class="form-label fw-bold text-dark mb-1.5">
                    <i class="fas fa-id-card text-primary me-1.5"></i>IPHACON Membership Number <span class="text-danger">*</span>
                </label>
                <div class="input-group">
                    <span class="input-group-text bg-white text-muted px-3">
                        <i class="fas fa-hashtag"></i>
                    </span>
                    <input type="text" class="form-control @error('ismm_membership_no') is-invalid @enderror"
                        id="ismm_membership_no" name="ismm_membership_no"
                        value="{{ old('ismm_membership_no', $registration->membership_no) }}"
                        placeholder="Enter your ISMM Membership Number">
                </div>
                <small class="text-muted mt-1 d-block"><i class="fas fa-info-circle me-1"></i>Verification required for IPHACON Members discount.</small>
                @error('ismm_membership_no')
                    <div class="invalid-feedback d-block mt-1">{{ $message }}</div>
                @enderror
            </div>

            <!-- ISHAM Membership (Category ID 4) -->
            <div class="mt-3 p-3.5 rounded bg-light border" id="isham_membership_row" style="display: none; border-left: 4px solid #2D69FF !important;">
                <label for="isham_membership_no" class="form-label fw-bold text-dark mb-1.5">
                    <i class="fas fa-id-card text-primary me-1.5"></i>ISHAM Membership Number <span class="text-danger">*</span>
                </label>
                <div class="input-group">
                    <span class="input-group-text bg-white text-muted px-3">
                        <i class="fas fa-hashtag"></i>
                    </span>
                    <input type="text" class="form-control @error('isham_membership_no') is-invalid @enderror"
                        id="isham_membership_no" name="isham_membership_no"
                        value="{{ old('isham_membership_no', $registration->isham_membership_no) }}"
                        placeholder="Enter your ISHAM Membership Number">
                </div>
                <small class="text-muted mt-1 d-block"><i class="fas fa-info-circle me-1"></i>Required for verified ISHAM Members.</small>
                @error('isham_membership_no')
                    <div class="invalid-feedback d-block mt-1">{{ $message }}</div>
                @enderror
            </div>

            <!-- Young ISAM Membership (Category ID 3) -->
            <div class="mt-3 p-3.5 rounded bg-light border" id="young_isam_membership_row" style="display: none; border-left: 4px solid #2D69FF !important;">
                <label for="young_isam_membership_no" class="form-label fw-bold text-dark mb-1.5">
                    <i class="fas fa-id-card text-primary me-1.5"></i>Young ISAM Membership Number <span class="text-danger">*</span>
                </label>
                <div class="input-group">
                    <span class="input-group-text bg-white text-muted px-3">
                        <i class="fas fa-hashtag"></i>
                    </span>
                    <input type="text" class="form-control @error('young_isam_membership_no') is-invalid @enderror"
                        id="young_isam_membership_no" name="young_isam_membership_no"
                        value="{{ old('young_isam_membership_no', $registration->membership_no) }}"
                        placeholder="Enter your Young ISAM Membership Number">
                </div>
                <small class="text-muted mt-1 d-block"><i class="fas fa-info-circle me-1"></i>Required for verified Young ISAM Members.</small>
                @error('young_isam_membership_no')
                    <div class="invalid-feedback d-block mt-1">{{ $message }}</div>
                @enderror
            </div>
        </div>
    </div>

    <!-- Additional Options & Add-ons Section -->
    <div class="card step2-card mb-4">
        <div class="card-header bg-transparent py-3 px-4 border-bottom d-flex align-items-center gap-2">
            <i class="fas fa-plus-circle text-primary"></i>
            <span class="step2-section-title">2. Optional Conference Add-ons</span>
        </div>
        <div class="card-body p-4">
            <div class="row g-4">
                <!-- Accompanying Person Option -->
                <div class="col-md-6">
                    <div class="p-3 rounded border bg-white h-100">
                        <label class="form-label fw-bold text-dark mb-1">
                            <i class="fas fa-user-friends text-primary me-1.5"></i>Accompanying Person
                        </label>
                        <p class="text-muted small mb-3">Would you like to bring an accompanying person?</p>
                        
                        <div class="row g-2">
                            <div class="col-6">
                                <label class="w-100 m-0">
                                    <input class="d-none step2-radio-input" type="radio" name="accompanying_persons" id="acc_yes" value="1"
                                        {{ old('accompanying_persons', $registration->accompanying_persons) == 1 ? 'checked' : '' }}>
                                    <div class="step2-radio-card">
                                        <div class="d-flex align-items-center gap-2">
                                            <i class="fas fa-user-check text-primary"></i>
                                            <span class="radio-title fw-semibold">Yes</span>
                                        </div>
                                        <span class="badge" style="background-color: #DCFFF0; color: #4BAA7D; font-weight: 700;">+ ₹4,000</span>
                                    </div>
                                </label>
                            </div>
                            <div class="col-6">
                                <label class="w-100 m-0">
                                    <input class="d-none step2-radio-input" type="radio" name="accompanying_persons" id="acc_no" value="0"
                                        {{ old('accompanying_persons', $registration->accompanying_persons) == 0 || old('accompanying_persons', $registration->accompanying_persons) === 0 ? 'checked' : '' }}>
                                    <div class="step2-radio-card">
                                        <div class="d-flex align-items-center gap-2">
                                            <i class="fas fa-user-times text-muted"></i>
                                            <span class="radio-title fw-semibold">No</span>
                                        </div>
                                        <span class="badge bg-light text-muted">₹0</span>
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- CME Workshop Participation Option -->
                <div class="col-md-6">
                    <div class="p-3 rounded border bg-white h-100">
                        <label class="form-label fw-bold text-dark mb-1">
                            <i class="fas fa-graduation-cap text-primary me-1.5"></i>CME / Workshop Participation
                        </label>
                        <p class="text-muted small mb-3">Join the specialized CME Academic Workshop?</p>

                        <div class="row g-2">
                            <div class="col-6">
                                <label class="w-100 m-0">
                                    <input class="d-none step2-radio-input" type="radio" name="participate_in_cme" id="cme_yes" value="1"
                                        {{ old('participate_in_cme', $registration->participate_in_cme) == 1 ? 'checked' : '' }}>
                                    <div class="step2-radio-card">
                                        <div class="d-flex align-items-center gap-2">
                                            <i class="fas fa-check-circle text-primary"></i>
                                            <span class="radio-title fw-semibold">Yes</span>
                                        </div>
                                        <span class="badge" style="background-color: #DCFFF0; color: #4BAA7D; font-weight: 700;">+ ₹1,000</span>
                                    </div>
                                </label>
                            </div>
                            <div class="col-6">
                                <label class="w-100 m-0">
                                    <input class="d-none step2-radio-input" type="radio" name="participate_in_cme" id="cme_no" value="0"
                                        {{ old('participate_in_cme', $registration->participate_in_cme) == 0 || old('participate_in_cme', $registration->participate_in_cme) === null ? 'checked' : '' }}>
                                    <div class="step2-radio-card">
                                        <div class="d-flex align-items-center gap-2">
                                            <i class="fas fa-times-circle text-muted"></i>
                                            <span class="radio-title fw-semibold">No</span>
                                        </div>
                                        <span class="badge bg-light text-muted">₹0</span>
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Total Registration Fee Summary Card -->
    <div class="total-fee-banner p-4 mb-4">
        <div class="row align-items-center">
            <div class="col-md-7 mb-3 mb-md-0">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-circle d-flex align-items-center justify-content-center"
                        style="width: 52px; height: 52px; background: rgba(255, 255, 255, 0.15); font-size: 1.5rem; color: #DCFFF0;">
                        <i class="fas fa-calculator"></i>
                    </div>
                    <div>
                        <h6 class="text-uppercase text-white-50 mb-1 fw-bold small" style="letter-spacing: 0.5px;">Summary Overview</h6>
                        <h5 class="text-white fw-bold mb-0">Total Calculated Registration Fee</h5>
                    </div>
                </div>
            </div>
            <div class="col-md-5 text-md-end">
                <div class="d-inline-block px-4 py-2.5 rounded-3 text-center" style="background: rgba(255, 255, 255, 0.1); backdrop-filter: blur(10px); border: 1px solid rgba(255, 255, 255, 0.2);">
                    <span class="d-block text-white-50 small fw-semibold">Final Amount Payable</span>
                    <h2 class="mb-0 fw-extrabold" id="total-amount" style="color: #DCFFF0; font-size: 2.2rem; letter-spacing: 0.5px;">₹0.00</h2>
                </div>
            </div>
        </div>
    </div>
@else
    <!-- International Delegate Layout -->
    <div class="card step2-card mb-4 border-0" style="background: linear-gradient(135deg, #2D69FF 0%, #1e293b 100%); color: #ffffff;">
        <div class="card-body p-4 p-md-5">
            <div class="d-flex align-items-start gap-3">
                <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                    style="width: 48px; height: 48px; background: rgba(255, 255, 255, 0.15); font-size: 1.25rem;">
                    <i class="fas fa-globe"></i>
                </div>
                <div>
                    <h5 class="text-white fw-bold mb-2">International Delegate Package</h5>
                    <p class="text-white-50 mb-3">All-inclusive registration package covering all scientific sessions, CME programs, and conference collateral.</p>
                    <span class="badge px-3 py-2 fs-6 fw-bold" style="background-color: #DCFFF0; color: #4BAA7D; border-radius: 20px;">
                        Fixed Fee: $175.00 USD
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Total Amount Display for International -->
    <div class="total-fee-banner p-4 mb-4">
        <div class="row align-items-center">
            <div class="col-md-7 mb-3 mb-md-0">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-circle d-flex align-items-center justify-content-center"
                        style="width: 52px; height: 52px; background: rgba(255, 255, 255, 0.15); font-size: 1.5rem; color: #DCFFF0;">
                        <i class="fas fa-calculator"></i>
                    </div>
                    <div>
                        <h6 class="text-uppercase text-white-50 mb-1 fw-bold small">Summary Overview</h6>
                        <h5 class="text-white fw-bold mb-0">Total Registration Fee</h5>
                    </div>
                </div>
            </div>
            <div class="col-md-5 text-md-end">
                <div class="d-inline-block px-4 py-2.5 rounded-3 text-center" style="background: rgba(255, 255, 255, 0.1); border: 1px solid rgba(255, 255, 255, 0.2);">
                    <span class="d-block text-white-50 small fw-semibold">Final Amount Payable</span>
                    <h2 class="mb-0 fw-extrabold" style="color: #DCFFF0; font-size: 2.2rem;">$175.00</h2>
                </div>
            </div>
        </div>
    </div>

    <!-- Hidden fields for international delegates -->
    <input type="hidden" name="delegate_category_id" value="1">
    <input type="hidden" name="accompanying_persons" value="0">
    <input type="hidden" name="participate_in_cme" value="0">
    <input type="hidden" name="is_ismm_member" value="0">
    <input type="hidden" name="is_isham_member" value="0">
    <input type="hidden" name="is_young_isam_member" value="0">
@endif

@if ($user->delegate_type == 'Indian')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            removeConflictingHandlers();

            setTimeout(function() {
                handleCategoryChange();
                bindEventHandlers();
                calculateTotal();
            }, 100);
        });

        function removeConflictingHandlers() {
            $('[onchange], [onclick]').each(function() {
                var id = $(this).attr('id') || $(this).attr('name');
                $(this).removeAttr('onchange').removeAttr('onclick');
            });
        }

        function bindEventHandlers() {
            $('#delegate_category_id').off('change.calculation').on('change.calculation', function() {
                handleCategoryChange();
            });

            $('input[name="accompanying_persons"]').off('change.calculation').on('change.calculation', function() {
                calculateTotal();
            });

            $('input[name="participate_in_cme"]').off('change.calculation').on('change.calculation', function() {
                calculateTotal();
            });
        }

        function handleCategoryChange() {
            var selectedValue = $('#delegate_category_id').val();

            hideAllMembershipFields();

            switch (selectedValue) {
                case '2':
                    showMembershipField('ismm');
                    break;
                case '3':
                    showMembershipField('young_isam');
                    break;
                default:
                    break;
            }

            calculateTotal();
        }

        function hideAllMembershipFields() {
            var membershipRows = ['ismm_membership_row', 'isham_membership_row', 'young_isam_membership_row'];

            $.each(membershipRows, function(index, rowId) {
                var $row = $('#' + rowId);
                if ($row.length) {
                    $row.hide();
                    var $input = $row.find('input');
                    if ($input.length) {
                        $input.prop('required', false);
                    }
                }
            });

            setHiddenMembershipValues();
        }

        function showMembershipField(type) {
            var rowId = type + '_membership_row';
            var inputId = type + '_membership_no';

            var $row = $('#' + rowId);
            var $input = $('#' + inputId);

            if ($row.length && $input.length) {
                $row.show();
                $input.prop('required', true);
                setMembershipFlags(type);
            }
        }

        function setMembershipFlags(activeType) {
            var membershipTypes = ['ismm', 'isham', 'young_isam'];

            $.each(membershipTypes, function(index, type) {
                var hiddenId = 'is_' + type + '_member_hidden';
                var $hiddenInput = $('#' + hiddenId);
                var value = (type === activeType) ? '1' : '0';

                if ($hiddenInput.length === 0) {
                    $('<input>', {
                        type: 'hidden',
                        id: hiddenId,
                        name: 'is_' + type + '_member',
                        value: value
                    }).appendTo('form');
                } else {
                    $hiddenInput.val(value);
                }
            });
        }

        function setHiddenMembershipValues() {
            var membershipTypes = ['ismm', 'isham', 'young_isam'];

            $.each(membershipTypes, function(index, type) {
                var hiddenId = 'is_' + type + '_member_hidden';
                var $hiddenInput = $('#' + hiddenId);

                if ($hiddenInput.length === 0) {
                    $('<input>', {
                        type: 'hidden',
                        id: hiddenId,
                        name: 'is_' + type + '_member',
                        value: '0'
                    }).appendTo('form');
                } else {
                    $hiddenInput.val('0');
                }
            });
        }

        function calculateTotal() {
            var total = 0;

            var $categorySelect = $('#delegate_category_id');
            if ($categorySelect.length && $categorySelect.val()) {
                var selectedOption = $categorySelect.find('option:selected');
                var fee = parseInt(selectedOption.data('fee') || 0);
                total += fee;
            }

            var $accYes = $('#acc_yes');
            if ($accYes.length && $accYes.is(':checked')) {
                var accFee = 4000;
                total += accFee;
            }

            var $cmeYes = $('#cme_yes');
            if ($cmeYes.length && $cmeYes.is(':checked')) {
                var cmeFee = 1000;
                total += cmeFee;
            }

            var $totalElement = $('#total-amount');
            if ($totalElement.length) {
                var formattedTotal = '₹' + total.toLocaleString('en-IN') + '.00';
                $totalElement.text(formattedTotal);
            }
        }

        $(window).on('load', function() {
            setTimeout(function() {
                calculateTotal();
            }, 200);
        });
    </script>
@endif
