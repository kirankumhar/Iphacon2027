<!-- resources/views/admins/create.blade.php -->
@extends('admin.layouts.main')

@section('admin-content')
<div class="container">
    <div class="row justify-content-center mt-5">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Create New Admin</div>
                <div class="card-body">
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

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif


                    <form action="{{ route('admins.create') }}" method="POST">
                        @csrf
                        <div class="row mb-2">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">Name</label>
                                    <input type="text" class="form-control" id="name" name="name"
                                        value="{{ old('name') }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="gender">Gender</label>
                                    <select class="form-control" id="gender" name="gender" required>
                                        <option value="">Select Gender</option>
                                        <option value="Male" {{ old('gender') == 'Male' ? 'selected' : '' }}>Male
                                        </option>
                                        <option value="Female" {{ old('gender') == 'Female' ? 'selected' : '' }}>Female
                                        </option>
                                        <option value="Other" {{ old('gender') == 'Other' ? 'selected' : '' }}>Other
                                        </option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-2">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input class="form-control" id="email" name="email" required>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="whatsapp">Whatsapp</label>
                                    <input type="tel" class="form-control" id="whatsapp" name="whatsapp"
                                        value="{{ old('whatsapp') }}" required>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-2">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="mobile_no">Mobile Number</label>
                                    <input type="tel" class="form-control" id="mobile_no" name="mobile_no"
                                        value="{{ old('mobile_no') }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="alt_mobile_no">Alternative Mobile Number</label>
                                    <input type="tel" class="form-control" id="alt_mobile_no" name="alt_mobile_no"
                                        value="{{ old('alt_mobile_no') }}">
                                </div>
                            </div>
                        </div>

                        {{-- <div class="row mb-2">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="profile_pic">Profile Picture</label>
                                    <input type="file" class="form-control" id="profile_pic" name="profile_pic"
                                        value="{{ old('profile_pic') }}">
                                </div>
                            </div>
                            <div class="col-md-6"></div>
                        </div> --}}

                        <div class="row mb-2">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="role">Role</label>
                                    <select class="form-control" id="role" name="role" required>
                                        <option value="">Select Role</option>
                                        <option value="admn_office" {{ old('role') == 'admn_office' ? 'selected' : '' }}>
                                            Admission Office</option>
                                        {{-- <option value="registar" {{ old('role') == 'registar' ? 'selected' : '' }}>
                                            Registrar
                                        </option> --}}
                                        <option value="superadmin" {{ old('role') == 'superadmin' ? 'selected' : '' }}>
                                            Superadmin</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Create Admin</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
