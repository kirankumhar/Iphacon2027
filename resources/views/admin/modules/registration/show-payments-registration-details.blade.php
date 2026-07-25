@extends('admin.layouts.main')

@section('admin-content')
    <div class="container-xxl flex-grow-1 ">
        <h6 class="py-3 mb-4"><span class="invert-text-white">Student Registration / Pending Registration </span>
        </h6>

        <div class="card mb-4">
            <h5 class="card-header text-white bg-info">View Pending Registered Student List</h5>
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
                                {{-- <th>Acad. Session</th> --}}
                                <th>Acad. Sess./Photo/Appl. No</th>
                                <th>Class</th>
                                <th>Name</th>
                                <th>Transaction Id</th>
                                <th>Bank transaction Id</th>
                                <th>Transaction Date</th>
                                <th>Amount</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($payStuList as $key => $stuList)
                                <tr>
                                    <td>{{ ++$key }}</td>
                                    {{-- <td>{{ $stuList->session_year }}</td> --}}
                                    <td class="text-center">
                                        {{ $stuList->session_year }} <br>
                                        <img src="{{ asset('storage/' . $stuList->stu_photo_path) }}" alt="Photo"
                                            class="img-fluid mx-auto d-block" width="50px"><br>
                                        <a href="{{ asset('storage/' . $stuList->registration_pdf_path) }}"
                                            title="Download Application Form">{{ $stuList->appl_no }}</a>
                                    </td>
                                    <td>{{ $stuList->apply_class }}</td>
                                    <td>{{ "$stuList->first_name $stuList->middle_name $stuList->surname" }}</td>
                                    <td>{{ $stuList->transaction_id }}</td>
                                    <td>{{ $stuList->bank_transaction_id }}</td>
                                    <td>
                                        {{ $stuList->transaction_date }}
                                    </td>
                                    <td>
                                       Rs. {{ $stuList->transaction_amount }}
                                    </td>
                                    <td>
                                        <a href="{{ asset('storage/' . $stuList->registration_pdf_path) }}" target="_blank"
                                            class="btn btn-info" title="View Application Form">View</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
