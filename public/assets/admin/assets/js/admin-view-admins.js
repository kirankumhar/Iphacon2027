/**
 * Admin View & Inline Edit Admins List
 * CSP Compliant (no inline scripts)
 */

$(document).ready(function() {
    'use strict';

    // Store original values when editing so cancel can truly revert
    const originalValues = {};

    // Edit button click event
    $(document).on('click', '.edit-row', function() {
        const $row = $(this).closest('tr');
        const id = $row.data('id');

        originalValues[id] = {
            name: $row.find('.name').text().trim(),
            gender: $row.find('.gender').text().trim(),
            email: $row.find('.email').text().trim(),
            whatsapp: $row.find('.whatsapp').text().trim(),
            mobile_no: $row.find('.mobile_no').text().trim(),
            alt_mobile_no: $row.find('.alt_mobile_no').text().trim(),
            role: $row.find('.role').text().trim()
        };

        $row.find('.name').html('<input type="text" class="form-control form-control-sm" value="' + $('<div>').text(originalValues[id].name).html() + '">');
        $row.find('.gender').html('<input type="text" class="form-control form-control-sm" value="' + $('<div>').text(originalValues[id].gender).html() + '">');
        $row.find('.email').html('<input type="text" class="form-control form-control-sm" value="' + $('<div>').text(originalValues[id].email).html() + '">');
        $row.find('.whatsapp').html('<input type="text" class="form-control form-control-sm" value="' + $('<div>').text(originalValues[id].whatsapp).html() + '">');
        $row.find('.mobile_no').html('<input type="text" class="form-control form-control-sm" value="' + $('<div>').text(originalValues[id].mobile_no).html() + '">');
        $row.find('.alt_mobile_no').html('<input type="text" class="form-control form-control-sm" value="' + $('<div>').text(originalValues[id].alt_mobile_no).html() + '">');
        $row.find('.role').html('<input type="text" class="form-control form-control-sm" value="' + $('<div>').text(originalValues[id].role).html() + '">');

        $(this).addClass('d-none');
        $row.find('.save-row, .cancel-row').removeClass('d-none');
    });

    // Save button click event
    $(document).on('click', '.save-row', function() {
        const $row = $(this).closest('tr');
        const id = $row.data('id');

        const updatedData = {
            name: $row.find('.name input').val(),
            gender: $row.find('.gender input').val(),
            email: $row.find('.email input').val(),
            whatsapp: $row.find('.whatsapp input').val(),
            mobile_no: $row.find('.mobile_no input').val(),
            alt_mobile_no: $row.find('.alt_mobile_no input').val(),
            role: $row.find('.role input').val(),
            _token: $('meta[name="csrf-token"]').attr('content')
        };

        $.ajax({
            url: '/admin/admins/' + id,
            method: 'PUT',
            data: updatedData,
            success: function() {
                $row.find('.name').text(updatedData.name);
                $row.find('.gender').text(updatedData.gender);
                $row.find('.email').text(updatedData.email);
                $row.find('.whatsapp').text(updatedData.whatsapp);
                $row.find('.mobile_no').text(updatedData.mobile_no);
                $row.find('.alt_mobile_no').text(updatedData.alt_mobile_no);
                $row.find('.role').text(updatedData.role);

                $row.find('.save-row, .cancel-row').addClass('d-none');
                $row.find('.edit-row').removeClass('d-none');

                delete originalValues[id];
                alert('Admin details updated successfully!');
            },
            error: function() {
                alert('Failed to update admin details. Please try again.');
            }
        });
    });

    // Cancel button click event
    $(document).on('click', '.cancel-row', function() {
        const $row = $(this).closest('tr');
        const id = $row.data('id');
        const orig = originalValues[id] || {};

        $row.find('.name').text(orig.name !== undefined ? orig.name : $row.find('.name input').val());
        $row.find('.gender').text(orig.gender !== undefined ? orig.gender : $row.find('.gender input').val());
        $row.find('.email').text(orig.email !== undefined ? orig.email : $row.find('.email input').val());
        $row.find('.whatsapp').text(orig.whatsapp !== undefined ? orig.whatsapp : $row.find('.whatsapp input').val());
        $row.find('.mobile_no').text(orig.mobile_no !== undefined ? orig.mobile_no : $row.find('.mobile_no input').val());
        $row.find('.alt_mobile_no').text(orig.alt_mobile_no !== undefined ? orig.alt_mobile_no : $row.find('.alt_mobile_no input').val());
        $row.find('.role').text(orig.role !== undefined ? orig.role : $row.find('.role input').val());

        $row.find('.save-row, .cancel-row').addClass('d-none');
        $row.find('.edit-row').removeClass('d-none');

        delete originalValues[id];
    });
});
