/**
 * Registration Step 2 Script (IPHACON 2027)
 * Handles category selection, dynamic membership fields visibility,
 * and live fee/GST calculations without inline scripts.
 */
(function ($) {
    'use strict';

    function setHiddenMembershipValues() {
        var membershipTypes = ['ismm', 'isham', 'young_isam'];

        $.each(membershipTypes, function (index, type) {
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

    function setMembershipFlags(activeType) {
        var membershipTypes = ['ismm', 'isham', 'young_isam'];

        $.each(membershipTypes, function (index, type) {
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

    function hideAllMembershipFields() {
        var membershipRows = ['ismm_membership_row', 'isham_membership_row', 'young_isam_membership_row'];

        $.each(membershipRows, function (index, rowId) {
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

    function calculateTotal() {
        var baseSubtotal = 0;
        var form = document.getElementById('wizardForm');
        var delegateType = (form && form.getAttribute('data-delegate-type')) ? form.getAttribute('data-delegate-type') : '';

        if (delegateType === 'International') {
            baseSubtotal = 45000;
        } else {
            var $categorySelect = $('#delegate_category_id');
            if ($categorySelect.length && $categorySelect.val()) {
                var selectedOption = $categorySelect.find('option:selected');
                var fee = parseFloat(selectedOption.data('fee') || 0);
                baseSubtotal += fee;
            }
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

    function handleCategoryChange() {
        var selectedValue = $('#delegate_category_id').val();

        hideAllMembershipFields();

        if (selectedValue === '1') {
            showMembershipField('ismm');
        }

        calculateTotal();
    }

    function bindEventHandlers() {
        $('#delegate_category_id').off('change.calculation').on('change.calculation', function () {
            handleCategoryChange();
        });

        $('input[name="accompanying_persons"]').off('change.calculation').on('change.calculation', function () {
            calculateTotal();
        });

        $('input[name="participate_in_cme"]').off('change.calculation').on('change.calculation', function () {
            calculateTotal();
        });
    }

    $(document).ready(function () {
        handleCategoryChange();
        bindEventHandlers();
        calculateTotal();
    });

    $(window).on('load', function () {
        setTimeout(function () {
            calculateTotal();
        }, 200);
    });

    window.calculateRegistrationTotal = calculateTotal;
    window.handleRegistrationCategoryChange = handleCategoryChange;

})(jQuery);
