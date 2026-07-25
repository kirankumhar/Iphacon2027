<!-- resources/views/admins/create.blade.php -->
@extends('admin.layouts.main')

@section('admin-content')
    <div class="container-xxl flex-grow-1 ">
        <h6 class="py-3 mb-4"><span class="invert-text-white">Admin & Roles / View Admins List </span>
        </h6>
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        <div class="card mb-4">
            <h5 class="card-header text-white bg-info">View Admins List</h5>
            @if (session('success'))
                <div class="alert alert-success alert-dismissible mx-3" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible mx-3" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            <div class="card-body mt-2">
                <div class="table-responsive">
                    <table id="studentListTable" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Gender</th>
                                <th>Email</th>
                                <th>Whatsapp</th>
                                <th>Mobile No</th>
                                <th>Alt Mobile No</th>
                                <th>Role</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($admins as $admin)
                                <tr data-id="{{ $admin->id }}">
                                    <td>{{ $loop->iteration }}</td>
                                    <td class="name">{{ $admin->adminDetails->name }}</td>
                                    <td class="gender">{{ $admin->adminDetails->gender }}</td>
                                    <td class="email">{{ $admin->adminDetails->email }}</td>
                                    <td class="whatsapp">{{ $admin->adminDetails->whatsapp }}</td>
                                    <td class="mobile_no">{{ $admin->mobile_no }}</td>
                                    <td class="alt_mobile_no">{{ $admin->alt_mobile_no }}</td>
                                    <td class="role">{{ ucfirst($admin->role) }}</td>
                                    <td>
                                        <a href="javascript:void(0);" class="btn btn-primary btn-sm edit-row">Edit</a>
                                        <a href="javascript:void(0);"
                                            class="btn btn-success btn-sm save-row d-none">Save</a>
                                        <a href="javascript:void(0);"
                                            class="btn btn-danger btn-sm cancel-row d-none">Cancel</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center">No admins found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        $(document).ready(function() {
            // Edit button click event
            $('.edit-row').on('click', function() {
                var $row = $(this).closest('tr');

                // Make the columns editable
                $row.find('.name').html('<input type="text" class="form-control" value="' + $row.find(
                    '.name').text() + '">');
                $row.find('.gender').html('<input type="text" class="form-control" value="' + $row.find(
                    '.gender').text() + '">');
                $row.find('.email').html('<input type="text" class="form-control" value="' + $row.find(
                    '.email').text() + '">');
                $row.find('.whatsapp').html('<input type="text" class="form-control" value="' + $row.find(
                    '.whatsapp').text() + '">');
                $row.find('.mobile_no').html('<input type="text" class="form-control" value="' + $row.find(
                    '.mobile_no').text() + '">');
                $row.find('.alt_mobile_no').html('<input type="text" class="form-control" value="' + $row
                    .find('.alt_mobile_no').text() + '">');
                $row.find('.role').html('<input type="text" class="form-control" value="' + $row.find(
                    '.role').text() + '">');

                // Toggle button visibility
                $(this).addClass('d-none'); // Hide Edit button
                $row.find('.save-row, .cancel-row').removeClass('d-none'); // Show Save and Cancel buttons
            });

            // Save button click event
            $('.save-row').on('click', function() {
                var $row = $(this).closest('tr');
                var id = $row.data('id');

                // Collect updated values
                var updatedData = {
                    name: $row.find('.name input').val(),
                    gender: $row.find('.gender input').val(),
                    email: $row.find('.email input').val(),
                    whatsapp: $row.find('.whatsapp input').val(),
                    mobile_no: $row.find('.mobile_no input').val(),
                    alt_mobile_no: $row.find('.alt_mobile_no input').val(),
                    role: $row.find('.role input').val(),
                    _token: $('meta[name="csrf-token"]').attr('content')
                };

                // Send AJAX request to update the data on the server
                $.ajax({
                    url: '/admin/admins/' + id, // Assuming the update route follows RESTful convention
                    method: 'PUT',
                    data: updatedData,
                    success: function(response) {
                        // Replace input fields with updated values
                        $row.find('.name').html(updatedData.name);
                        $row.find('.gender').html(updatedData.gender);
                        $row.find('.email').html(updatedData.email);
                        $row.find('.whatsapp').html(updatedData.whatsapp);
                        $row.find('.mobile_no').html(updatedData.mobile_no);
                        $row.find('.alt_mobile_no').html(updatedData.alt_mobile_no);
                        $row.find('.role').html(updatedData.role);

                        // Toggle button visibility
                        $row.find('.save-row, .cancel-row').addClass(
                        'd-none'); // Hide Save and Cancel buttons
                        $row.find('.edit-row').removeClass('d-none'); // Show Edit button

                        // Show success message (optional)
                        alert('Admin details updated successfully!');
                    },
                    error: function(xhr) {
                        alert('Failed to update admin details. Please try again.');
                    }
                });
            });

            // Cancel button click event
            $('.cancel-row').on('click', function() {
                var $row = $(this).closest('tr');
                // Revert to original text (you can customize this to store original values before editing)
                $row.find('.name').html($row.find('.name input').val());
                $row.find('.gender').html($row.find('.gender input').val());
                $row.find('.email').html($row.find('.email input').val());
                $row.find('.whatsapp').html($row.find('.whatsapp input').val());
                $row.find('.mobile_no').html($row.find('.mobile_no input').val());
                $row.find('.alt_mobile_no').html($row.find('.alt_mobile_no input').val());
                $row.find('.role').html($row.find('.role input').val());

                // Toggle button visibility
                $row.find('.save-row, .cancel-row').addClass('d-none'); // Hide Save and Cancel buttons
                $row.find('.edit-row').removeClass('d-none'); // Show Edit button
            });
        });
    </script>
@endpush
