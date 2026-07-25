@extends('admin.layouts.main')

@section('admin-content')
    <div class="container-xxl flex-grow-1 ">
        <h6 class="py-3 mb-4"><span class="invert-text-white">Quick Enquiries</span>
        </h6>

        <div class="card mb-4">
            <h5 class="card-header text-white bg-info">View Quick Enquiries</h5>
            @if (session('success'))
                <div class="alert alert-success alert-dismissible mx-3 mt-3" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible mx-3 mt-3" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            <div class="card-body mt-2">

                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label for="course_type" class="form-label">Class <small class="text-danger">*</small></label>
                        <select class="form-select" id="course_type" name="course_type" required>
                            <option value="">Select Class</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                            <option value="11">11</option>
                        </select>
                    </div>

                    <div class="col-md-3 mb-3">
                        <div class="text-center">
                            <input type="button" id="searchButton" value="Search" class="btn btn-info mt-4">

                            <button id="resetButton" class="btn btn-danger mt-4">Reset</button>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table id="studentListTable" class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Acad. Session</th>
                                <th>Full Name</th>
                                <th>Applying Class</th>
                                <th>Parent's Mobile</th>
                                <th>Parent's WhatsApp</th>
                                <th>Parent's Email</th>
                                <th>Query</th>
                                <th>Created At</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($enquiries as $key => $enquiry)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $enquiry->session }}</td>
                                    <td>{{ $enquiry->full_name }}</td>
                                    <td>{{ $enquiry->apply_class }}</td>
                                    <td>{{ $enquiry->parent_mobile }}</td>
                                    <td>{{ $enquiry->parent_whatsapp }}</td>
                                    <td>{{ $enquiry->parent_email }}</td>
                                    <td>{{ $enquiry->query }}</td>
                                    <td>{{ $enquiry->created_at->format('d-m-Y H:i') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
