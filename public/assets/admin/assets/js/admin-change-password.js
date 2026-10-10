/**
 * Admin Change Password & 2FA Script
 * CSP Compliant (no inline scripts, no inline event handlers)
 */

$(document).ready(function() {
    'use strict';

    const $password = $('#newPassword');
    const $confirmPassword = $('#confirmPassword');
    const $submitBtn = $('#submitBtn');
    const $proceedBtn = $('#proceedBtn');
    const mobile = $('#mobileNo').val();
    const $passwordHelp = $("#passwordHelpBlock");

    $("#submitBtn").attr('disabled', true);

    function validatePassword() {
        const password = $password.val() || '';
        const confirmPassword = $confirmPassword.val() || '';

        const conditions = [
            { regex: /.{8,}/, message: "At least 8 characters." },
            { regex: /[A-Z]/, message: "At least one uppercase letter." },
            { regex: /[a-z]/, message: "At least one lowercase letter." },
            { regex: /[0-9]/, message: "At least one number." },
            { regex: /[!@#$]/, message: "At least one special character (!, @, #, $)." }
        ];

        let isValid = true;
        let feedbackHtml = '';

        conditions.forEach(condition => {
            const meetsCondition = condition.regex.test(password);
            isValid = isValid && meetsCondition;
            feedbackHtml += `<div class="${meetsCondition ? 'text-success' : 'text-danger'}">
                <i class="fas fa-${meetsCondition ? 'check' : 'times'}"></i> ${condition.message}
             </div>`;
        });

        const doMatch = password === confirmPassword;
        $confirmPassword.toggleClass('is-invalid', !doMatch && confirmPassword.length > 0);

        if (confirmPassword.length > 0) {
            feedbackHtml += `<div class="${doMatch ? 'text-success' : 'text-danger'}">
                <i class="fas fa-${doMatch ? 'check' : 'times'}"></i> Passwords match
             </div>`;
        }

        $passwordHelp.html(feedbackHtml);
        $submitBtn.prop('disabled', !(isValid && doMatch));
        $proceedBtn.prop('disabled', !(isValid && doMatch));
    }

    if ($password.length) {
        $password.on('input', validatePassword);
        $confirmPassword.on('input', validatePassword);
        validatePassword();
    }

    function resendOTPCounter() {
        let countdownDuration = 2 * 60;
        const $countdownElement = $('#countdown');
        const $resendElement = $('#resend');

        const countdownInterval = setInterval(function() {
            const minutes = Math.floor(countdownDuration / 60);
            const seconds = countdownDuration % 60;
            $countdownElement.text(
                "Resend available in " +
                (minutes < 10 ? '0' + minutes : minutes) + ":" +
                (seconds < 10 ? '0' + seconds : seconds)
            );
            countdownDuration--;
            if (countdownDuration < 0) {
                clearInterval(countdownInterval);
                $countdownElement.text('');
                $resendElement.removeClass('disabled').css({
                    'pointer-events': 'auto',
                    'opacity': '1'
                });
            }
        }, 1000);
    }

    $(document).on('click', '#proceedBtn, #resend', function() {
        $("#jspc-loader").removeClass('d-none');
        $("#submitBtn").prop('disabled', true);

        fetch("/admin/updateAdminOTP/password", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            body: JSON.stringify({
                mobile_number: mobile
            })
        })
        .then(response => response.json())
        .then(data => {
            $("#jspc-loader").addClass('d-none');
            if (data.type === 'success') {
                $('.numeral-mask').val('');
                resendOTPCounter();
            } else if (data.type === 'exists') {
                $("#mobile_no").addClass("is-invalid");
                $('#mobile_no').siblings('.invalid-feedback').text(data.message);
            }
        })
        .catch(error => {
            $("#jspc-loader").addClass('d-none');
        });
    });

    $('.numeral-mask').val('');

    $('.numeral-mask').on('keyup', function() {
        const otp = $("#otp").val();
        if (otp && otp.length === 6) {
            verifyOTP();
        }
    });

    function verifyOTP() {
        const mobileNumber = mobile;
        $(".numeral-mask").removeClass("is-invalid");
        const otp = $("#otp").val();

        fetch('/admin/admin-pass-verify-otp', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            body: JSON.stringify({
                otp: otp,
                mobile_number: mobileNumber
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.type === 'error') {
                $(".numeral-mask").addClass('is-invalid').removeClass('is-valid');
                $("#verifyFeed").html(data.message);
            } else {
                $(".numeral-mask").addClass('is-valid').removeClass('is-invalid');
                $("#verifyFeed").html('');
                $(".numeral-mask").prop('disabled', true);
                $("#submitBtn").prop('disabled', false);
            }
        })
        .catch(error => {
            // Error handling
        });
    }

    $(document).on('click', '#submitBtn', function(e) {
        e.preventDefault();
        $("#jspc-loader").removeClass('d-none');
        const $form = $('#formAccountSettings');
        const formData = $form.serialize();
        const updateUrl = $form.data('update-url') || '/admin/update-password';

        $.ajax({
            url: updateUrl,
            method: "POST",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: formData,
            dataType: 'json',
            success: function(data) {
                $("#jspc-loader").addClass('d-none');
                if (data.type === 'success') {
                    location.reload();
                } else if (data.type === 'error') {
                    alert(data.message || 'An error occurred. Please try again.');
                }
            },
            error: function(xhr, status, error) {
                $("#jspc-loader").addClass('d-none');
                console.error(xhr.responseText);
                alert('An error occurred. Please try again.');
            }
        });
    });
});
