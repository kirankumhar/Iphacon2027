
$(document).ready(function () {
    function updateOTP() {
        let otp = '';
        $('.numeral-mask').each(function () {
            otp += $(this).val();
        });
        $('[name="otp"]').val(otp);
    }

    $('.numeral-mask').on('keyup', function (e) {
        if (this.value.length === this.maxLength) {
            $(this).next('.numeral-mask').focus();
        }
        if (e.key === "Backspace" && $(this).prev('.numeral-mask').length) {
            $(this).prev('.numeral-mask').focus();
        }
        updateOTP();
    });

    $('#twoStepsForm').on('submit', function (e) {
        e.preventDefault();
        let isValid = true;

        $('.numeral-mask').each(function () {
            if ($(this).val() === '') {
                isValid = false;
                alert('Please enter OTP');
                return false;
            }
        });

        if (isValid) {
            // alert('OTP is: ' + $('[name="otp"]').val());
            $("#jspc-loader").removeClass('d-none');
            $("#loading-text").text("Validating your account OTP.");

            $(this).unbind('submit').submit();  // Unbind the submit handler and submit the form
        }
    });
});
