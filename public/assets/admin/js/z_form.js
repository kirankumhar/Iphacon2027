function copyCorrespondence() {
    if (document.getElementById('sameAsCorrespondence').checked) {
        document.getElementById('permanent_address').value = document.getElementById('correspondence_address').value;
        document.getElementById('permanent_city').value = document.getElementById('correspondence_city').value;
        document.getElementById('permanent_state').value = document.getElementById('correspondence_state').value;
        document.getElementById('permanent_district').value = document.getElementById('correspondence_district').value;
        document.getElementById('permanent_pin').value = document.getElementById('correspondence_pin').value;
    } else {
        document.getElementById('permanent_address').value = '';
        document.getElementById('permanent_city').value = '';
        document.getElementById('permanent_state').value = '';
        document.getElementById('permanent_district').value = '';
        document.getElementById('permanent_pin').value = '';
    }
}

function validateInput(input, maxlength) {
    input.value = input.value.replace(/[^0-9]/g, '');
    // Limit length to 10 characters
    if (input.value.length > maxlength) {
        input.value = input.value.substring(0, maxlength);
    }
}


$(document).ready(function () {

    $('#changePasswordModal').modal('show');

    $('#submitPasswordChange').on('click', function () {
        var form = $('#changePasswordForm');
        var newPassword = $('#newPassword').val();
        var confirmPassword = $('#confirmPassword').val();
        var errorMessage = '';

        if (newPassword.length < 8) {
            errorMessage = 'Password must be at least 8 characters long.';
        }

        var regex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\W).*$/;
        if (!regex.test(newPassword)) {
            errorMessage = 'Password must include uppercase, lowercase, and special characters.';
        }

        if (newPassword !== confirmPassword) {
            errorMessage = 'Passwords do not match.';
        }

        if (errorMessage) {
            $('#error-message').text(errorMessage);
        } else {
            $('#error-message').text('');
            form.submit(); 
            $('#changePasswordModal').modal('hide');
        }
    });

    // Functions start

    function updateFee() {
        let category = $('#category').val();
        let generalFee = parseInt($('.general_fees').val());
        let othersFee = parseInt($('.others_fee').val());
        let lateFee = parseInt($('#late_fee').val());
        let totalFees;

        if (category === 'General' || category === 'OBC') {
            $('#caste_certificate').prop('disabled', true).prop('required', false);
            $('.general_fees').prop('disabled', false).show();
            $('.others_fee').prop('disabled', true).hide();
            $('#caste_div').hide();
            totalFees = generalFee + lateFee;
        } 
		/*else if (category === 'OBC') {
            $('#caste_certificate').prop('disabled', false).prop('required', true);
            $('.general_fees').prop('disabled', false).show();
            $('.others_fee').prop('disabled', true).hide();
            $('#caste_div').show();
            totalFees = generalFee + lateFee;
        }*/
		else {
            $('#caste_certificate').prop('disabled', false).prop('required', true);
            $('.general_fees').prop('disabled', true).hide();
            $('.others_fee').prop('disabled', false).show();
            $('#caste_div').show();
            totalFees = othersFee + lateFee;
        }

        $('#total_fees').val(totalFees);
    }

    function setRequiredAttribute(fileType) {
        var viewButton = $('button[data-file-type="' + fileType + '"]');
        var fileUrl = viewButton.data('file-url');
        var fileInput = $('#' + fileType);

        if ((fileUrl && fileUrl !== 'NA') || fileInput.is(':disabled')) {
            fileInput.removeAttr('required');
        } else {
            fileInput.attr('required', 'required');
        }
    }

    // Forms validation rules 
    function validateRequiredFields() {
        var validation = {
            isValid: true,
            fieldName: ''
        };

        $('input[required], select[required], textarea[required]').each(function () {
            var inputType = $(this).attr('type');
            var inputId = $(this).attr('id');

            if (inputType === 'radio') {
                var radioGroupName = $(this).attr('name');
                if (!$('input[name="' + radioGroupName + '"]:checked').length) {
                    validation.isValid = false;
                    validation.fieldName = 'Relation Type Checbox';
                    return false;
                }
            }
            else if (inputType === 'file') {
                var file = this.files[0];
                if (!file) {
                    validation.isValid = false;
                    validation.fieldName = $('label[for="' + inputId + '"]').text();
                    return false;
                }

                var fileValidationRules = {
                    'coloured_photograph': { types: ['image/jpeg'], maxSize: 300 * 1024 }, // 300 KB
                    'signature': { types: ['image/jpeg'], maxSize: 150 * 1024 }, // 150 KB
                    'admit_card': { types: ['image/jpeg', 'application/pdf'], maxSize: 500 * 1024 }, // 500 KB
                    'caste_certificate': { types: ['image/jpeg', 'application/pdf'], maxSize: 500 * 1024 } // 500 KB
                };

                var fileRule = fileValidationRules[inputId];
                if (fileRule) {
                    if (!fileRule.types.includes(file.type) || file.size > fileRule.maxSize) {
                        validation.isValid = false;
                        validation.fieldName = $('label[for="' + inputId + '"]').text();
                        return false;
                    }
                }
            }
            else {
                if ($(this).attr('id') === 'email') {
                    var emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;
                    if (!emailPattern.test($(this).val())) {
                        validation.isValid = false;
                        validation.fieldName = 'Email';
                        validation.errorMessage = validation.fieldName + ' must be a valid email address.';
                        return false;
                    }
                }
                if ($(this).val() === '') {
                    validation.isValid = false;
                    validation.fieldName = $('label[for="' + inputId + '"]').text();
                    return false;
                }
            }
        });

        return validation;
    }

    // Calling for Toast appears
    function showToast(message) {
        var toastElement = $('<div class="bs-toast toast fade show bg-danger" role="alert" aria-live="assertive" aria-atomic="true"></div>');
        var toastHeader = $('<div class="toast-header"><i class="bx bx-bell me-2"></i><div class="me-auto fw-medium">Required Field</div><small>Just now</small><button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button></div>');
        var toastBody = $('<div class="toast-body">' + message + '</div>');
        toastElement.append(toastHeader, toastBody);
        $('.toast-container').append(toastElement);

        setTimeout(function () {
            toastElement.alert('close');
        }, 5000);
    }

    // End Functions

    var today = new Date().toISOString().split('T')[0];
    $('#date_of_admission, #date_of_birth').attr('max', today);

    updateFee();

    $('#category').change(updateFee);

    $('#edit_form').on('click', function () {
        $('#form-tab').tab('show');
    });

    $('#category').trigger('change');

    $('#date_of_admission, #date_of_birth').on('input change', function () {
        var inputDate = $(this).val();
        if (inputDate > today) {
            $(this).val(today);
            showToast('Future date is not allowed.');
        }
    });

    if ($('#type').val() === 'Regular') {
        $('#admit_card').prop('disabled', true).prop('required', false);
        $('#admit_div').hide();
    }

    $('#type').on('change', function () {
        if ($(this).val() === 'Regular') {
            $('#admit_card').prop('disabled', true).prop('required', false);
            $('#admit_div').hide();
        } else {
            $('#admit_card').prop('disabled', false).prop('required', true);
            $('#admit_div').show();
        }
    });

    $('#course_type').on('change', function () {

        var courseTypeId = $(this).val();
        if (courseTypeId === 'Diploma') {
            $('#educational_qualification').empty();
            $('#educational_qualification').append('<option value="12">Inter</option>');
        } else if (courseTypeId === 'Certificate') {
            $('#educational_qualification').empty();
            $('#educational_qualification').append('<option value="10">Matric</option>');
        }
        if (courseTypeId) {
            $.ajax({
                url: '/get-courses/' + courseTypeId,
                type: 'GET',
                dataType: 'json',
                success: function (data) {
                    $('#course_name').empty();
                    $('#course_name').append('<option value="">Select Course Name</option>');

                    $('#acad_session').empty();
                    $('#acad_session').append('<option value="">Select Academic Session Year</option>');

                    $.each(data.course_names, function (index, course) {
                        $('#course_name').append('<option value="' + course.id + '">' + course.course_name + '</option>');
                    });

                    $.each(data.academic_session, function (index, session) {
                        $('#acad_session').append('<option value="' + session.session_year + '">' + session.session_year + '</option>');
                    });
                }
            });
        } else {
            $('#course_name').empty();
            $('#course_name').append('<option value="">Select Course Name</option>');
        }
    });

    $('button[data-bs-target="#previewForm"]').on('click', function (event) {
        event.preventDefault();
        var button = $(this);
        var fileType = button.data('file-type');
        var fileURL = button.data('file-url');

        var validation = validateRequiredFields();

        if (validation.isValid === true) {
            $('#preview-tab').tab('show');
            var formData = $('#stuRegistrationForm').serializeArray();
            var tableBody = $('#previewTableBody');
            tableBody.empty();
            $.each(formData, function (index, field) {
                var labelName = $('label[for="' + field.name + '"]').text();
                if (labelName != '') {
                    if (labelName == 'Name of Course *') {
                        var selectedText = $("#course_name option:selected").text();
                        tableBody.append('<tr><td><strong>' + labelName + '</strong></td><td>' + selectedText + '</td><td></td></tr>');
                    } else if (labelName == 'Exam Session *') {
                        let exams = field.value;
                        var year = exams.substring(0, 4);
                        var monthNumber = exams.substring(4, 6);
                        exam_sess = (year + ' ' + (monthNumber == '06' ? 'June' : 'December'))
                        tableBody.append('<tr><td><strong>' + labelName + '</strong></td><td>' + exam_sess + '</td><td></td></tr>');
                    } else if (labelName == 'Educational Qualification *') {
                        var selectedText = $("#educational_qualification option:selected").text();
                        tableBody.append('<tr><td><strong>' + labelName + '</strong></td><td>' + selectedText + '</td><td></td></tr>');
                    } else {
                        tableBody.append('<tr><td><strong>' + labelName + '</strong></td><td>' + field.value + '</td><td></td></tr>');
                    }
                }
            });

            $('input[type="file"]').each(function (index) {
                var fileName = $(this).val().split('\\').pop();
                var fileId = $(this).attr('id');
                var l_name = $('label[for="' + fileId + '"]').text();

                if (fileName) {
                    tableBody.append('<tr><td><strong>' + l_name + '</strong></td><td>' + fileName + '</td><td><button type="button" class="btn btn-primary previewBtn" data-file-id="' + fileId + '">Preview</button></td></tr>');
                }
            });
        } else {
            var fieldName = validation.fieldName;
            showToast(fieldName + ' is required..');
        }

    });

    $('button[data-bs-target="#updateForm"]').on('click', function (event) {
        event.preventDefault();
        var button = $(this);
        var fileType = button.data('file-type');
        var fileURL = button.data('file-url');

        var validation = validateRequiredFields();

        if (validation.isValid === true) {
            var userConfirmed = confirm("Are you sure you want to update student details?");

            if (userConfirmed) {
                $('#stuRegistrationUpdate').submit();
            }

        } else {
            var fieldName = validation.fieldName;
            showToast(fieldName + ' is required..');
        }

    });


    $('#previewModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var fileType = button.data('file-type');
        var fileURL = button.data('file-url');
        var fileInput = document.getElementById(fileType).files[0];

        if (fileInput) {
            var reader = new FileReader();

            reader.onload = function (e) {
                var result = e.target.result;
                var content = '';

                if (fileInput.type.startsWith('image/')) {
                    content = '<img src="' + result + '" class="img-fluid" alt="File Preview">';
                } else if (fileInput.type === 'application/pdf') {
                    content = '<embed src="' + result + '" type="application/pdf" width="100%" height="600px">';
                } else {
                    content = 'Preview not available for this file type.';
                }

                $('#fileContent').html(content);
            };

            reader.readAsDataURL(fileInput);
        } else if (fileURL && fileURL !== 'NA') {
            var content = '';

            if (fileURL.endsWith('.jpg') || fileURL.endsWith('.jpeg')) {
                content = '<img src="' + fileURL + '" class="img-fluid" alt="File Preview">';
            } else if (fileURL.endsWith('.jpg') || fileURL.endsWith('.jpeg') || fileURL.endsWith('.pdf')) {
                content = '<embed src="' + fileURL + '" type="application/pdf" width="100%" height="600px">';
            } else {
                content = 'Preview not available for this file type.';
            }

            $('#fileContent').html(content);
        } else {
            $('#fileContent').html('No file selected.');
        }
    });

    $('#previewModal').on('hidden.bs.modal', function () {
        $('#documentPreview').empty();
    });

    $(document).on('click', '.previewBtn', function () {
        var fileId = $(this).data('file-id');
        var fileInput = $('#' + fileId)[0].files[0];

        if (fileInput) {
            var fileURL = URL.createObjectURL(fileInput);

            window.open(fileURL);
        }
    });

    setRequiredAttribute('coloured_photograph');
    setRequiredAttribute('signature');
    setRequiredAttribute('admit_card');
    setRequiredAttribute('caste_certificate');


    // Submit button of add student registration form
    $('#finalSubmitBtn').on('click', function () {
        var validation = validateRequiredFields();
        if (validation.isValid) {
            $('#stuRegistrationForm').submit();
        } else {
            var fieldName = validation.fieldName;
            showToast(fieldName + ' is required.');
        }
    });

    $('#add-exam-session').on('click', function () {
        var validation = validateRequiredFields();
        if (validation.isValid) {
            $('#examSessionForm').submit();
        } else {
            var fieldName = validation.fieldName;
            showToast(fieldName + ' is required.');
        }
    });

    // DataTable Initialize
    $('#studentListTable').DataTable({});

});
