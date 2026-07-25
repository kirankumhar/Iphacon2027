@extends('admin.layouts.main')

@section('admin-content')
    <div class="container-xxl flex-grow-1 ">
        <h6 class="py-3 mb-4"><span class="invert-text-white">International Delegates/Reverted Delegates </span>
        </h6>

        <div class="card mb-4">
            <h5 class="card-header text-white bg-info">View Reverted Delegates List</h5>
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

                <div class="row d-none">

                    <div class="col-md-3 mb-3">
                        <div class="text-center">
                            <input type="button" id="searchButton" value="Search" data-route="revert" class="btn btn-info mt-4">

                            <button id="resetButton" class="btn btn-danger mt-4">Reset</button>
                        </div>
                    </div>
                </div>

                <div class="table-responsive">
                    <table id="studentListTable" class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Photo/Regn. No</th>
                                <th>Delegate Type</th>
                                <th>Name</th>
                                <th>DOB</th>
                                <th>Transaction ID/ Receipt</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
