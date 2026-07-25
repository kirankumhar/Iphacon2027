<h4 class="text-primary mb-4"><i class="fas fa-clipboard-list me-2"></i>Step 2: Conference Registration</h4>

<!-- Fixed Delegate Type Display -->
<div class="row mb-4">
    <div class="col-md-3">
        <label class="form-label fw-semibold" style="color:#2e3192">
            <i class="fas fa-globe me-1"></i>Delegate Type
        </label>
    </div>
    <div class="col-md-9">
        <div class="alert alert-info mb-0">
            <i class="fas fa-flag me-2"></i>
            <strong>{{ $user->delegate_type }}</strong> Delegate
            <small class="text-muted">({{ $user->delegate_type == 'Indian' ? 'INR' : 'USD' }} pricing)</small>
        </div>
        <!-- Hidden input to maintain form data -->
        <input type="hidden" name="delegate_type" value="{{ $user->delegate_type }}">
    </div>
</div>

@if ($user->delegate_type == 'Indian')
    <!-- Indian Delegate Fields -->

    <!-- Delegate Category -->
    <div class="row mb-3">
        <div class="col-md-3">
            <label for="delegate_category_id" class="form-label fw-semibold" style="color:#2e3192">
                <i class="fas fa-tags me-1"></i>Delegate Category<span class="text-danger">*</span>
            </label>
        </div>
        <div class="col-md-9">
            <select class="form-select @error('delegate_category_id') is-invalid @enderror" id="delegate_category_id"
                name="delegate_category_id" required>
                <option value="">Select Category</option>
                @foreach ($delegateCategories as $category)
                    <option value="{{ $category->id }}" data-fee="{{ $category->indian_fee }}"
                        {{ old('delegate_category_id', $registration->delegate_category_id) == $category->id ? 'selected' : '' }}>
                        {{ $category->category_name }} (₹{{ number_format($category->indian_fee) }})
                    </option>
                @endforeach
            </select>
            @error('delegate_category_id')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <!-- Category-Specific Membership Fields -->

    <!-- ISMM Membership (for Category ID 2) -->
    <div class="row mb-3" id="ismm_membership_row" style="display: none;">
        <div class="col-md-3">
            <label for="ismm_membership_no" class="form-label fw-semibold" style="color:#2e3192">
                <i class="fas fa-id-badge me-1"></i>ISMM Membership No.<span class="text-danger">*</span>
            </label>
        </div>
        <div class="col-md-9">
            <input type="text" class="form-control @error('ismm_membership_no') is-invalid @enderror"
                id="ismm_membership_no" name="ismm_membership_no"
                value="{{ old('ismm_membership_no', $registration->membership_no) }}"
                placeholder="Enter ISMM Membership Number">
            <small class="text-muted">Required for ISMM Members</small>
            @error('ismm_membership_no')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <!-- ISHAM Membership (for Category ID 3) -->
    <div class="row mb-3" id="isham_membership_row" style="display: none;">
        <div class="col-md-3">
            <label for="isham_membership_no" class="form-label fw-semibold" style="color:#2e3192">
                <i class="fas fa-id-badge me-1"></i>ISHAM Membership No.<span class="text-danger">*</span>
            </label>
        </div>
        <div class="col-md-9">
            <input type="text" class="form-control @error('isham_membership_no') is-invalid @enderror"
                id="isham_membership_no" name="isham_membership_no"
                value="{{ old('isham_membership_no', $registration->isham_membership_no) }}"
                placeholder="Enter ISHAM Membership Number">
            <small class="text-muted">Required for ISHAM Members</small>
            @error('isham_membership_no')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <!-- Young ISAM Membership -->
    <div class="row mb-3" id="young_isam_membership_row" style="display: none;">
        <div class="col-md-3">
            <label for="young_isam_membership_no" class="form-label fw-semibold" style="color:#2e3192">
                <i class="fas fa-id-badge me-1"></i>Young ISAM Membership No.<span class="text-danger">*</span>
            </label>
        </div>
        <div class="col-md-9">
            <input type="text" class="form-control @error('young_isam_membership_no') is-invalid @enderror"
                id="young_isam_membership_no" name="young_isam_membership_no"
                value="{{ old('young_isam_membership_no', $registration->membership_no) }}"
                placeholder="Enter Young ISAM Membership Number">
            <small class="text-muted">Required for Young ISAM Members</small>
            @error('young_isam_membership_no')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <!-- Accompanying Persons -->
    <div class="row mb-3">
        <div class="col-md-3">
            <label for="accompanying_persons" class="form-label fw-semibold" style="color:#2e3192">
                <i class="fas fa-users me-1"></i>Accompanying Persons
            </label>
        </div>
        <div class="col-md-6">
            {{-- <select class="form-select" id="accompanying_persons" name="accompanying_persons">
                <option value="0"
                    {{ old('accompanying_persons', $registration->accompanying_persons) == 0 ? 'selected' : '' }}>
                    No Accompanying Person
                </option>
                @for ($i = 1; $i <= 10; $i++)
                    <option value="{{ $i }}"
                        {{ old('accompanying_persons', $registration->accompanying_persons) == $i ? 'selected' : '' }}>
                        {{ $i }} Person{{ $i > 1 ? 's' : '' }} (₹{{ number_format($i * 4000) }})
                    </option>
                @endfor
            </select> --}}

            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="accompanying_persons" id="acc_yes" value="1"
                    {{ old('accompanying_persons', $registration->accompanying_persons) == 1 ? 'checked' : '' }}>
                <label class="form-check-label" for="acc_yes">
                    <strong>Yes</strong> <span class="badge bg-info ms-1">₹4,000</span>
                </label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="accompanying_persons" id="acc_no"
                    value="0"
                    {{ old('accompanying_persons', $registration->accompanying_persons) == 0 || old('accompanying_persons', $registration->accompanying_persons) === 0 ? 'checked' : '' }}>
                <label class="form-check-label" for="acc_no">
                    <strong>No</strong>
                </label>
            </div>
        </div>
    </div>

    <!-- CME Participation -->
    <div class="row mb-3">
        <div class="col-md-3">
            <label class="form-label fw-semibold" style="color:#2e3192">
                <i class="fas fa-graduation-cap me-1"></i>CME/Workshop Participation
            </label>
        </div>
        <div class="col-md-9">
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="participate_in_cme" id="cme_yes"
                    value="1"
                    {{ old('participate_in_cme', $registration->participate_in_cme) == 1 ? 'checked' : '' }}>
                <label class="form-check-label" for="cme_yes">
                    <strong>Yes</strong> <span class="badge bg-info ms-1">₹1,000</span>
                </label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="participate_in_cme" id="cme_no"
                    value="0"
                    {{ old('participate_in_cme', $registration->participate_in_cme) == 0 || old('participate_in_cme', $registration->participate_in_cme) === null ? 'checked' : '' }}>
                <label class="form-check-label" for="cme_no">
                    <strong>No</strong>
                </label>
            </div>
        </div>
    </div>

    <!-- Total Amount Display -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card bg-light">
                <div class="card-body">
                    <h5 class="card-title text-primary">
                        <i class="fas fa-calculator me-2"></i>Total Registration Fee
                    </h5>
                    <h3 class="text-success mb-0" id="total-amount">₹0.00</h3>
                </div>
            </div>
        </div>
    </div>
@else
    <!-- International Delegate -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="alert alert-warning">
                <h5><i class="fas fa-info-circle me-2"></i>International Delegate Information</h5>
                <p class="mb-2"><strong>Registration Fee: $175.00 (Fixed)</strong></p>
                <p class="mb-0">This includes all conference activities. No additional options are available for
                    international delegates.</p>
            </div>
        </div>
    </div>

    <!-- Total Amount Display for International -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card bg-light">
                <div class="card-body">
                    <h5 class="card-title text-primary">
                        <i class="fas fa-calculator me-2"></i>Total Registration Fee
                    </h5>
                    <h3 class="text-success mb-0">$175.00</h3>
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
                    // case '4': // ISHAM Member category (if applicable)
                    //     showMembershipField('isham');
                    //     break;
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

            // var $accompanyingSelect = $('#accompanying_persons');
            // if ($accompanyingSelect.length) {
            //     var accompanyingPersons = parseInt($accompanyingSelect.val() || 0);
            //     var accompanyingFee = accompanyingPersons * 4000;
            //     total += accompanyingFee;
            // }

            var $accYes = $('#acc_yes');
            if ($accYes.length && $accYes.is(':checked')) {
                var accFee = 4000;
                total += accFee;
            }

            // Get CME fee
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

        // function monitorTotalElement() {
        //     var $totalElement = $('#total-amount');
        //     if ($totalElement.length && window.MutationObserver) {
        //         var observer = new MutationObserver(function(mutations) {
        //             $.each(mutations, function(index, mutation) {
        //                 console.log('Total element changed:', mutation, 'New value:', $totalElement.text());
        //                 console.trace('Stack trace:');
        //             });
        //         });

        //         observer.observe($totalElement[0], {
        //             childList: true,
        //             characterData: true,
        //             subtree: true
        //         });

        //         console.log('Total element monitor activated');
        //     }
        // }

        // Activate monitoring (uncomment for debugging)
        // $(document).ready(function() {
        //     setTimeout(monitorTotalElement, 500);
        // });

        // Additional safety check to ensure calculations are correct

        $(window).on('load', function() {
            setTimeout(function() {
                calculateTotal();
            }, 200);
        });
    </script>
@endif
