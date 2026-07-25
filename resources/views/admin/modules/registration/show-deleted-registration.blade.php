@extends('admin.layouts.main')

@section('admin-content')
    <div class="container-xxl flex-grow-1 ">
        <h6 class="py-3 mb-4"><span class="invert-text-white">Deleted Registration </span>
        </h6>

        <div class="card mb-4">
            <h5 class="card-header text-white bg-info">View Deleted Registered List</h5>
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
            <div class="card-body mt-2 d-none">

                <div class="col-md-3 mb-3">
                    <div class="text-center">
                        <input type="button" id="searchDeletedButton" value="Search" data-route="failed"
                            class="btn btn-info mt-4">

                        <button id="resetDeletedButton" class="btn btn-danger mt-4">Reset</button>
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table id="deletedListTable" class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Photo/Regn. No</th>
                            <th>Name</th>
                            <th>Delegate Type</th>
                            <th>DOB</th>
                            <th>Deleted On</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($deletedStuList as $key => $stuList)
                            <tr>
                                <td>{{ ++$key }}</td>
                                <td class="text-center">
                                    <img src="{{ asset('storage/' . $stuList->photo_path) }}" alt="Photo"
                                        class="img-fluid mx-auto d-block" width="50px"><br>
                                    <a href="{{ asset('storage/' . $stuList->registration_pdf_path) }}" target="_blank"
                                        title="Download Registration Form">{{ $stuList->registration_number }}</a>
                                </td>
                                <td>{{ $stuList->user->prefix }} {{ $stuList->user->full_name }}</td>
                                <td>{{ $stuList->user->delegate_type }}</td>
                                <td>{{ date('d-m-Y', strtotime($stuList->user->date_of_birth)) }}</td>
                                <td>{{ date('d-m-Y H:i:s', strtotime($stuList->deleted_datetime)) }}</td>
                                {{-- <td>
                                        <form action="{{ route('student-regis-accept') }}" method="post">
                                            @csrf
                                            <input type="hidden" name="appl_no" value="{{ $stuList->appl_no }}">
                                            <button type="submit" class="btn btn-success">Accept</button>
                                        </form>
                                    </td> --}}
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    </div>
@endsection
