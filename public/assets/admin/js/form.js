$(document).ready(function () {

    $('#resetButton').remove();

    function initializeStudentTable(tableId, buttonId) {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        const studentListTable = $(tableId).DataTable({
            processing: true,
            serverSide: true,
            destroy: true,
            ajax: {
                url: '/admin/view_registrations/' + ($('#searchButton').data('route') || 'pending'),
                type: 'POST',
                data: function (d) {
                    // d.course_type = $('#course_type option:selected').val();
                    // d.session = $('#session option:selected').text();
                    d.route_type = $(buttonId).data('route');
                },
                error: function (xhr, error, thrown) {
                    console.error('DataTable Error:', error);
                }
            },
            columns: [
                {
                    data: null, name: 'id', render: function (data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    },
                    orderable: false, searchable: false
                },
                {
                    data: null, name: 'photo',
                    render: function (data, type, row) {
                        const photoHtml = row.photo_path
                            ? `<img src="/storage/${row.photo_path}" alt="Photo" class="img-fluid mx-auto d-block" width="50px">`
                            : `<i class="bx bx-user-circle text-secondary" style="font-size: 40px;"></i>`;

                        return `
                            <div class="text-center">
                               ${photoHtml}
                                <br>
                                <!-- <a href="/storage/${row.registration_pdf_path}" target="_blank" title="Download Registration Form">
                                    <i class="bx bx-file text-danger"></i>${row.registration_number}
                                </a> -->

                                <a href="/admin/download-receipt/${row.registration_number}" target="_blank" title="Download Registration Form">
                                    <i class="bx bxs-file text-danger"></i> ${row.registration_number}
                                </a>
                            </div>
                        `;
                    },
                    orderable: false
                },
                {
                    data: 'type',
                    name: 'type',
                },
                {
                    data: null, name: 'name', render: function (data, type, row) {
                        return `<div class="text-center">
                                    ${row.prefix} ${row.full_name}
                                    <br>

                                    <a href="/admin/show-registration-details/${row.registration_number}" target="_blank" title="Show Registration Details">
                                        <i class="bx bxs-file text-danger"></i> View more..
                                    </a>
                                    
                                </div>
                        `;
                    }
                },
                {
                    data: 'transaction_id',
                    name: 'transaction_id', render: function (data, type, row) {
                        if (row.payment_receipt_path !== null) {
                            return `
                                <div class="text-center">
                                    <a href="/storage/${row.payment_receipt_path}" target="_blank" title="Download Payment Receipt">
                                        <i class="menu-icon tf-icons bx bx-file"></i>${row.transaction_id}
                                    </a>
                                </div>`;
                        } else {
                            return `<div class="text-center">
                                        ₹ ${row.total_amount}
                                </div>`;
                        }
                    },
                    orderable: false
                },
                {
                    data: null,
                    defaultContent: '',
                    name: 'action',
                    render: function (data, type, row) {
                        const route = $('#searchButton').data('route');
                        const csrf = $('meta[name="csrf-token"]').attr('content');

                        if (route === 'pending') {
                            return `
                                <form action="/admin/approved-regis" method="post" class="d-inline accept-form">
                                    <input type="hidden" name="_token" value="${csrf}">
                                    <input type="hidden" name="registration_number" value="${row.registration_number}">
                                    <button type="submit" class="btn btn-success">Approved</button>
                                </form>

                                <form action="/admin/reject-regis" method="post" class="d-inline reject-form">
                                    <input type="hidden" name="_token" value="${csrf}">
                                    <input type="hidden" name="registration_number" value="${row.registration_number}">
                                    <input type="hidden" name="reason" class="reject-reason">
                                    <button type="button" class="btn btn-danger btn-reject">Reject</button>
                                </form>

                                <form action="/admin/revert-regis" method="post" class="d-inline revert-form">
                                    <input type="hidden" name="_token" value="${csrf}">
                                    <input type="hidden" name="registration_number" value="${row.registration_number}">
                                    <input type="hidden" name="reason" class="revert-reason">
                                    <button type="button" class="btn btn-warning btn-revert" title="Revert to Delegates Payments">Revert</button>
                                </form>

                                <br><br>
                                <form action="/admin/delete-regis" method="post" class="d-inline delete-form mt-1">
                                    <input type="hidden" name="_token" value="${csrf}">
                                    <input type="hidden" name="registration_number" value="${row.registration_number}">
                                    <button type="submit" class="btn btn-danger">Delete</button>
                                </form>
                            `;
                        } else if (route === 'revert') {
                            return `
                                <form action="/admin/approved-regis" method="post" class="d-inline accept-form">
                                    <input type="hidden" name="_token" value="${csrf}">
                                    <input type="hidden" name="registration_number" value="${row.registration_number}">
                                    <button type="submit" class="btn btn-success">Approved</button>
                                </form>

                                <form action="/admin/reject-regis" method="post" class="d-inline reject-form">
                                    <input type="hidden" name="_token" value="${csrf}">
                                    <input type="hidden" name="registration_number" value="${row.registration_number}">
                                    <input type="hidden" name="reason" class="reject-reason">
                                    <button type="button" class="btn btn-danger btn-reject">Reject</button>
                                </form>

                                <br><br>
                                <form action="/admin/delete-regis" method="post" class="d-inline delete-form mt-1">
                                    <input type="hidden" name="_token" value="${csrf}">
                                    <input type="hidden" name="registration_number" value="${row.registration_number}">
                                    <button type="submit" class="btn btn-danger">Delete</button>
                                </form>
                            `;
                        } else if (route === 'approved') {
                            return `<span class="text-success">Approved</span>`;
                        } else if (route === 'reject') {
                            return `<span class="text-danger">${row.rejection_reason}</span>`;
                        } else if (route === 'ind-paid') {
                            return `<span class="text-success">Paid</span>`;
                        }
                    },
                    orderable: false,
                    searchable: false
                }

            ],
            searching: true,
            searchDelay: 500,
            pageLength: 25,
            lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
            order: [[7, 'desc']],
        });

        $(buttonId).on('click', function () {
            studentListTable.ajax.reload();
        });
    }

    $(document).on('click', '.btn-reject', function (e) {
        e.preventDefault();
        const form = $(this).closest('form');
        const reason = prompt('Please enter a valid reason for rejection:');

        if (reason && reason.trim() !== '') {
            form.find('.reject-reason').val(reason.trim());
            form.submit();
        } else {
            alert('Rejection reason is mandatory.');
        }
    });

    $(document).on('click', '.btn-revert', function (e) {
        e.preventDefault();
        const form = $(this).closest('form');
        const reason = prompt('Please enter a valid reason for revert:');

        if (reason && reason.trim() !== '') {
            form.find('.revert-reason').val(reason.trim());
            form.submit();
        } else {
            alert('Revert reason is mandatory.');
        }
    });


    // Initialize for failed records table
    initializeStudentTable('#studentListTable', '#searchButton');

    $('#searchButton').on('click', function () {
        initializeStudentTable('#studentListTable', '#searchButton');
    });

    function formatDate(dateString) {
        const date = new Date(dateString);
        const day = String(date.getDate()).padStart(2, '0');
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const year = date.getFullYear();

        return `${day}-${month}-${year}`;
    }

    function formatDateTime(dateString) {
        const date = new Date(dateString);
        const day = String(date.getDate()).padStart(2, '0');
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const year = date.getFullYear();
        const hours = String(date.getHours()).padStart(2, '0');
        const minutes = String(date.getMinutes()).padStart(2, '0');
        const seconds = String(date.getSeconds()).padStart(2, '0');
        return `${day}-${month}-${year} ${hours}:${minutes}:${seconds}`;
    }

    // DataTable Initialize
    const deletedListTable = $('#deletedListTable').DataTable({});

    $('#searchDeletedButton').on('click', function () {
        const course_type = $('#course_type option:selected').val();
        const course_name = $('#course_name option:selected').text();
        const pass_state = $('#pass_state').val();

        deletedListTable.columns().search('');

        if (course_type) {
            deletedListTable.column(3).search(course_type);
        }

        if (course_name && course_name != 'Select Course Name') {
            deletedListTable.column(4).search(course_name);
        }

        if (pass_state && pass_state != 'Select Academic Session') {
            deletedListTable.column(5).search(pass_state);
        }

        deletedListTable.draw();
    });

    $('#resetDeletedButton').on('click', function () {
        location.reload();
    });
});
