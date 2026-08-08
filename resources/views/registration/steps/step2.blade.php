<!-- Step 2 Header -->
<div class="d-flex align-items-center justify-content-between mb-3 pb-2 border-bottom">
    <div class="d-flex align-items-center gap-2.5">
        <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
            style="width: 38px; height: 38px; background: linear-gradient(135deg, #2D69FF 0%, #1A52E0 100%); color: #ffffff; font-size: 1.1rem; box-shadow: 0 3px 10px rgba(45, 105, 255, 0.22);">
            <i class="fas fa-clipboard-list"></i>
        </div>
        <div>
            <h5 class="fw-bold mb-0 text-dark" style="font-size: 1.15rem;">Step 2: Conference Registration</h5>
            <small class="text-muted extra-small">Select delegate category and optional conference add-ons</small>
        </div>
    </div>
    <span class="badge px-2.5 py-1.5 extra-small fw-bold" style="background-color: #E1F0FF; color: #2D69FF; border-radius: 20px;">
        <i class="fas fa-flag me-1"></i> {{ $user->delegate_type }} Delegate
    </span>
</div>

<!-- Custom CSS for Step 2 Cards & Interactive Selector Cards -->
<style>
    .step2-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.02);
        transition: all 0.2s ease-in-out;
    }
    .step2-card:hover {
        border-color: rgba(45, 105, 255, 0.3);
        box-shadow: 0 4px 16px rgba(45, 105, 255, 0.06);
    }
    .step2-section-title {
        color: #2D69FF;
        font-weight: 700;
        font-size: 0.95rem;
    }
    .step2-radio-card {
        border: 1.5px solid #e2e8f0;
        border-radius: 10px;
        padding: 8px 12px;
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
        box-shadow: 0 2px 8px rgba(45, 105, 255, 0.12);
    }
    .step2-radio-input:checked + .step2-radio-card .radio-title {
        color: #2D69FF;
        font-weight: 700;
    }
    .total-fee-banner {
        background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
        border-radius: 14px;
        color: #ffffff;
        box-shadow: 0 6px 18px rgba(15, 23, 42, 0.12);
        border: 1px solid rgba(45, 105, 255, 0.2);
    }
</style>

<!-- Delegate Type Information Banner -->
<div class="card step2-card mb-3 border-0" style="background: linear-gradient(135deg, #E1F0FF 0%, #F0F6FF 100%); border-left: 4px solid #2D69FF !important;">
    <div class="card-body p-2.5 px-3 d-flex align-items-center justify-content-between flex-wrap gap-2">
        <div class="d-flex align-items-center gap-2.5">
            <i class="fas fa-globe-americas text-primary" style="font-size: 1.25rem;"></i>
            <div>
                <strong class="d-block text-dark extra-small">
                    Registration Mode: <span class="text-primary">{{ $user->delegate_type }} Delegate</span>
                </strong>
                <small class="text-muted extra-small">Currency: <strong>{{ $user->delegate_type == 'Indian' ? 'INR (₹)' : 'USD ($)' }}</strong></small>
            </div>
        </div>
        <span class="badge px-2.5 py-1 extra-small fw-semibold" style="background-color: #DCFFF0; color: #4BAA7D; border-radius: 20px;">
            <i class="fas fa-check-circle me-1"></i> Active
        </span>
    </div>
    <!-- Hidden input to maintain form data -->
    <input type="hidden" name="delegate_type" value="{{ $user->delegate_type }}">
</div>

@if ($user->delegate_type == 'Indian')
    <!-- Indian Delegate Form Section -->
    <div class="card step2-card mb-3">
        <div class="card-header bg-transparent py-2.5 px-3 border-bottom d-flex align-items-center gap-2">
            <i class="fas fa-layer-group text-primary extra-small"></i>
            <span class="step2-section-title">1. Delegate Category Selection</span>
        </div>
        <div class="card-body p-3">
            <!-- Delegate Category Select -->
            <div>
                <label for="delegate_category_id" class="form-label fw-bold text-dark extra-small mb-1.5">
                    <i class="fas fa-tags text-primary me-1"></i>Select Category <span class="text-danger">*</span>
                </label>
                <div class="input-group">
                    <span class="input-group-text bg-light text-muted px-2.5" style="border-radius: 8px 0 0 8px;">
                        <i class="fas fa-list-ul extra-small"></i>
                    </span>
                    <select class="form-select @error('delegate_category_id') is-invalid @enderror py-2 extra-small"
                        id="delegate_category_id" name="delegate_category_id" required style="border-radius: 0 8px 8px 0;">
                        <option value="">-- Choose Delegate Category --</option>
                        @foreach ($delegateCategories as $category)
                            <option value="{{ $category->id }}" data-fee="{{ $category->indian_fee }}"
                                {{ old('delegate_category_id', $registration->delegate_category_id) == $category->id ? 'selected' : '' }}>
                                {{ $category->category_name }} — ₹{{ number_format($category->indian_fee) }} (Base)
                            </option>
                        @endforeach
                    </select>
                </div>
                @error('delegate_category_id')
                    <div class="invalid-feedback d-block mt-1 extra-small"><i class="fas fa-exclamation-circle me-1"></i>{{ $message }}</div>
                @enderror
            </div>

            <!-- Dynamic Membership Fields Section -->
            <!-- ISMM Membership (Category ID 2) -->
            <div class="mt-2.5 p-2.5 rounded bg-light border" id="ismm_membership_row" style="display: none; border-left: 3px solid #2D69FF !important;">
                <label for="ismm_membership_no" class="form-label fw-bold text-dark extra-small mb-1">
                    <i class="fas fa-id-card text-primary me-1"></i>IPHACON Membership Number <span class="text-danger">*</span>
                </label>
                <div class="input-group">
                    <span class="input-group-text bg-white text-muted px-2.5">
                        <i class="fas fa-hashtag extra-small"></i>
                    </span>
                    <input type="text" class="form-control extra-small py-1.5 @error('ismm_membership_no') is-invalid @enderror"
                        id="ismm_membership_no" name="ismm_membership_no"
                        value="{{ old('ismm_membership_no', $registration->membership_no) }}"
                        placeholder="Enter your IPHACON Membership Number">
                </div>
                <small class="text-muted extra-small mt-1 d-block"><i class="fas fa-info-circle me-1"></i>Required for IPHACON Member discount.</small>
                @error('ismm_membership_no')
                    <div class="invalid-feedback d-block mt-1 extra-small">{{ $message }}</div>
                @enderror
            </div>

            <!-- ISHAM Membership (Category ID 4) -->
            <div class="mt-2.5 p-2.5 rounded bg-light border" id="isham_membership_row" style="display: none; border-left: 3px solid #2D69FF !important;">
                <label for="isham_membership_no" class="form-label fw-bold text-dark extra-small mb-1">
                    <i class="fas fa-id-card text-primary me-1"></i>ISHAM Membership Number <span class="text-danger">*</span>
                </label>
                <div class="input-group">
                    <span class="input-group-text bg-white text-muted px-2.5">
                        <i class="fas fa-hashtag extra-small"></i>
                    </span>
                    <input type="text" class="form-control extra-small py-1.5 @error('isham_membership_no') is-invalid @enderror"
                        id="isham_membership_no" name="isham_membership_no"
                        value="{{ old('isham_membership_no', $registration->isham_membership_no) }}"
                        placeholder="Enter your ISHAM Membership Number">
                </div>
                <small class="text-muted extra-small mt-1 d-block"><i class="fas fa-info-circle me-1"></i>Required for verified ISHAM Members.</small>
                @error('isham_membership_no')
                    <div class="invalid-feedback d-block mt-1 extra-small">{{ $message }}</div>
                @enderror
            </div>

            <!-- Young ISAM Membership (Category ID 3) -->
            <div class="mt-2.5 p-2.5 rounded bg-light border" id="young_isam_membership_row" style="display: none; border-left: 3px solid #2D69FF !important;">
                <label for="young_isam_membership_no" class="form-label fw-bold text-dark extra-small mb-1">
                    <i class="fas fa-id-card text-primary me-1"></i>Young ISAM Membership Number <span class="text-danger">*</span>
                </label>
                <div class="input-group">
                    <span class="input-group-text bg-white text-muted px-2.5">
                        <i class="fas fa-hashtag extra-small"></i>
                    </span>
                    <input type="text" class="form-control extra-small py-1.5 @error('young_isam_membership_no') is-invalid @enderror"
                        id="young_isam_membership_no" name="young_isam_membership_no"
                        value="{{ old('young_isam_membership_no', $registration->membership_no) }}"
                        placeholder="Enter your Young ISAM Membership Number">
                </div>
                <small class="text-muted extra-small mt-1 d-block"><i class="fas fa-info-circle me-1"></i>Required for verified Young ISAM Members.</small>
                @error('young_isam_membership_no')
                    <div class="invalid-feedback d-block mt-1 extra-small">{{ $message }}</div>
                @enderror
            </div>
        </div>
    </div>

    <!-- Additional Options & Add-ons Section -->
    <div class="card step2-card mb-3">
        <div class="card-header bg-transparent py-2.5 px-3 border-bottom d-flex align-items-center gap-2">
            <i class="fas fa-plus-circle text-primary extra-small"></i>
            <span class="step2-section-title">2. Optional Conference Add-ons</span>
        </div>
        <div class="card-body p-3">
            <div class="row g-3">
                <!-- Accompanying Person Option -->
                <div class="col-md-6">
                    <div class="p-2.5 rounded border bg-white h-100">
                        <div class="d-flex align-items-center justify-content-between mb-1.5">
                            <label class="form-label fw-bold text-dark mb-0 extra-small">
                                <i class="fas fa-user-friends text-primary me-1"></i>Accompanying Person
                            </label>
                        </div>

                        <div class="row g-2">
                            <div class="col-6">
                                <label class="w-100 m-0">
                                    <input class="d-none step2-radio-input" type="radio" name="accompanying_persons" id="acc_yes" value="1"
                                        {{ old('accompanying_persons', $registration->accompanying_persons) == 1 ? 'checked' : '' }}>
                                    <div class="step2-radio-card">
                                        <div class="d-flex align-items-center gap-1.5">
                                            <i class="fas fa-user-check text-primary extra-small"></i>
                                            <span class="radio-title extra-small fw-semibold">Yes</span>
                                        </div>
                                        <span class="badge extra-small" style="background-color: #DCFFF0; color: #4BAA7D; font-weight: 700;">+ ₹5,000</span>
                                    </div>
                                </label>
                            </div>
                            <div class="col-6">
                                <label class="w-100 m-0">
                                    <input class="d-none step2-radio-input" type="radio" name="accompanying_persons" id="acc_no" value="0"
                                        {{ old('accompanying_persons', $registration->accompanying_persons) == 0 || old('accompanying_persons', $registration->accompanying_persons) === 0 ? 'checked' : '' }}>
                                    <div class="step2-radio-card">
                                        <div class="d-flex align-items-center gap-1.5">
                                            <i class="fas fa-user-times text-muted extra-small"></i>
                                            <span class="radio-title extra-small fw-semibold">No</span>
                                        </div>
                                        <span class="badge bg-light text-muted extra-small">₹0</span>
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- CME Workshop Participation Option -->
                <div class="col-md-6">
                    <div class="p-2.5 rounded border bg-white h-100">
                        <div class="d-flex align-items-center justify-content-between mb-1.5">
                            <label class="form-label fw-bold text-dark mb-0 extra-small">
                                <i class="fas fa-graduation-cap text-primary me-1"></i>CME Workshop
                            </label>
                        </div>

                        <div class="row g-2">
                            <div class="col-6">
                                <label class="w-100 m-0">
                                    <input class="d-none step2-radio-input" type="radio" name="participate_in_cme" id="cme_yes" value="1"
                                        {{ old('participate_in_cme', $registration->participate_in_cme) == 1 ? 'checked' : '' }}>
                                    <div class="step2-radio-card">
                                        <div class="d-flex align-items-center gap-1.5">
                                            <i class="fas fa-check-circle text-primary extra-small"></i>
                                            <span class="radio-title extra-small fw-semibold">Yes</span>
                                        </div>
                                        <span class="badge extra-small" style="background-color: #DCFFF0; color: #4BAA7D; font-weight: 700;">+ ₹2,000</span>
                                    </div>
                                </label>
                            </div>
                            <div class="col-6">
                                <label class="w-100 m-0">
                                    <input class="d-none step2-radio-input" type="radio" name="participate_in_cme" id="cme_no" value="0"
                                        {{ old('participate_in_cme', $registration->participate_in_cme) == 0 || old('participate_in_cme', $registration->participate_in_cme) === null ? 'checked' : '' }}>
                                    <div class="step2-radio-card">
                                        <div class="d-flex align-items-center gap-1.5">
                                            <i class="fas fa-times-circle text-muted extra-small"></i>
                                            <span class="radio-title extra-small fw-semibold">No</span>
                                        </div>
                                        <span class="badge bg-light text-muted extra-small">₹0</span>
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
    <div class="total-fee-banner p-3 mb-3">
        <div class="row align-items-center">
            <div class="col-md-5 mb-2 mb-md-0">
                <div class="d-flex align-items-center gap-2.5">
                    <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                        style="width: 42px; height: 42px; background: rgba(255, 255, 255, 0.15); font-size: 1.25rem; color: #DCFFF0;">
                        <i class="fas fa-calculator"></i>
                    </div>
                    <div>
                        <h6 class="text-uppercase text-white-50 mb-0.5 fw-bold extra-small" style="letter-spacing: 0.5px;">Summary Breakdown</h6>
                        <h6 class="text-white fw-bold mb-0 extra-small">Calculated Registration Fee</h6>
                    </div>
                </div>
            </div>
            <div class="col-md-7 text-md-end">
                <div class="d-inline-flex flex-wrap gap-2 align-items-center justify-content-md-end">
                    <div class="px-2.5 py-1.5 rounded-3 text-center" style="background: rgba(255, 255, 255, 0.08); border: 1px solid rgba(255, 255, 255, 0.15);">
                        <span class="d-block text-white-50 extra-small fw-semibold">Base Price</span>
                        <span class="fw-bold text-white extra-small" id="base-amount">₹0.00</span>
                    </div>
                    <div class="px-2.5 py-1.5 rounded-3 text-center" style="background: rgba(255, 255, 255, 0.08); border: 1px solid rgba(255, 255, 255, 0.15);">
                        <span class="d-block text-white-50 extra-small fw-semibold">GST (18%)</span>
                        <span class="fw-bold text-warning extra-small" id="gst-amount">+ ₹0.00</span>
                    </div>
                    <div class="px-3 py-1.5 rounded-3 text-center" style="background: rgba(255, 255, 255, 0.15); backdrop-filter: blur(10px); border: 1px solid rgba(255, 255, 255, 0.25);">
                        <span class="d-block text-white-50 extra-small fw-semibold">Total Payable</span>
                        <h4 class="mb-0 fw-extrabold" id="total-amount" style="color: #DCFFF0; font-size: 1.35rem;">₹0.00</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
@else
    <!-- International Delegate Layout -->
    <div class="card step2-card mb-3 border-0" style="background: linear-gradient(135deg, #2D69FF 0%, #1e293b 100%); color: #ffffff;">
        <div class="card-body p-3.5 p-md-4">
            <div class="d-flex align-items-start gap-3">
                <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                    style="width: 42px; height: 42px; background: rgba(255, 255, 255, 0.15); font-size: 1.15rem;">
                    <i class="fas fa-globe"></i>
                </div>
                <div>
                    <h6 class="text-white fw-bold mb-1">International Delegate Package</h6>
                    <p class="text-white-50 mb-2 extra-small">All-inclusive registration package covering all scientific sessions, CME programs, and conference collateral.</p>
                    <span class="badge px-2.5 py-1.5 extra-small fw-bold" style="background-color: #DCFFF0; color: #4BAA7D; border-radius: 20px;">
                        Fixed Fee: $175.00 USD
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Total Amount Display for International -->
    <div class="total-fee-banner p-3 mb-3">
        <div class="row align-items-center">
            <div class="col-md-7 mb-2 mb-md-0">
                <div class="d-flex align-items-center gap-2.5">
                    <div class="rounded-circle d-flex align-items-center justify-content-center"
                        style="width: 42px; height: 42px; background: rgba(255, 255, 255, 0.15); font-size: 1.25rem; color: #DCFFF0;">
                        <i class="fas fa-calculator"></i>
                    </div>
                    <div>
                        <h6 class="text-uppercase text-white-50 mb-0.5 fw-bold extra-small">Summary Overview</h6>
                        <h6 class="text-white fw-bold mb-0 extra-small">Total Registration Fee</h6>
                    </div>
                </div>
            </div>
            <div class="col-md-5 text-md-end">
                <div class="d-inline-block px-3 py-1.5 rounded-3 text-center" style="background: rgba(255, 255, 255, 0.1); border: 1px solid rgba(255, 255, 255, 0.2);">
                    <span class="d-block text-white-50 extra-small fw-semibold">Final Amount Payable</span>
                    <h3 class="mb-0 fw-extrabold" style="color: #DCFFF0; font-size: 1.4rem;">$175.00</h3>
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
            var baseSubtotal = 0;

            var $categorySelect = $('#delegate_category_id');
            if ($categorySelect.length && $categorySelect.val()) {
                var selectedOption = $categorySelect.find('option:selected');
                var fee = parseFloat(selectedOption.data('fee') || 0);
                baseSubtotal += fee;
            }

            var $accYes = $('#acc_yes');
            if ($accYes.length && $accYes.is(':checked')) {
                var accFee = 5000;
                baseSubtotal += accFee;
            }

            var $cmeYes = $('#cme_yes');
            if ($cmeYes.length && $cmeYes.is(':checked')) {
                var cmeFee = 2000;
                baseSubtotal += cmeFee;
            }

            var gstAmount = Math.round(baseSubtotal * 0.18);
            var totalPayable = baseSubtotal + gstAmount;

            var $baseElement = $('#base-amount');
            if ($baseElement.length) {
                $baseElement.text('₹' + baseSubtotal.toLocaleString('en-IN') + '.00');
            }

            var $gstElement = $('#gst-amount');
            if ($gstElement.length) {
                $gstElement.text('+ ₹' + gstAmount.toLocaleString('en-IN') + '.00');
            }

            var $totalElement = $('#total-amount');
            if ($totalElement.length) {
                $totalElement.text('₹' + totalPayable.toLocaleString('en-IN') + '.00');
            }
        }

        $(window).on('load', function() {
            setTimeout(function() {
                calculateTotal();
            }, 200);
        });
    </script>
@endif
