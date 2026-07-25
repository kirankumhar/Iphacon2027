@extends('admin.layouts.main')

@section('admin-content')
    <style>
        .user-profile-header-banner img {
            width: 100%;
            object-fit: cover;
            height: 120px
        }

        .user-profile-header {
            margin-top: -2rem
        }

        .user-profile-header .user-profile-img {
            border: 5px solid;
            width: 120px
        }

        .light-style .user-profile-header .user-profile-img {
            border-color: #fff
        }

        .dark-style .user-profile-header .user-profile-img {
            border-color: #2b2c40
        }

        .dataTables_wrapper .card-header .dataTables_filter label {
            margin-top: 0 !important;
            margin-bottom: 0 !important
        }

        @media(max-width: 767.98px) {
            .user-profile-header-banner img {
                height: 150px
            }

            .user-profile-header .user-profile-img {
                width: 100px
            }
        }
    </style>
    <link rel="stylesheet" href="{{ asset('assets/admin/core1.css') }}" />

    <div class="container-xxl flex-grow-1 container-p-y">
        <!-- Header -->
        <div class="row">
            <div class="col-12">
                <div class="card mb-6">
                    <div class="user-profile-header-banner">
                        <img src="{{ asset('assets/admin/assets/img/profile-banner.png') }}" alt="Banner image"
                            class="rounded-top">
                    </div>
                    <div class="user-profile-header d-flex flex-column flex-lg-row text-sm-start text-center mb-8">
                        <div class="flex-shrink-0 mt-1 mx-sm-0 mx-auto">
                            <img src="{{ !empty(auth()->user()->adminDetails->profile_pic) ? asset(auth()->user()->adminDetails->profile_pic) : asset('assets/admin/assets/img/logo.jpg') }}"
                                alt="user image" class="d-block h-auto ms-0 ms-sm-6 rounded-3 user-profile-img">
                        </div>
                        <div class="flex-grow-1 mt-3 mt-lg-5">
                            <div
                                class="d-flex align-items-md-end align-items-sm-start align-items-center justify-content-md-between justify-content-start mx-5 flex-md-row flex-column gap-4">
                                <div class="user-profile-info">
                                    <h4 class="mb-2 mt-lg-7">{{ auth()->user()->adminDetails->name }}</h4>
                                    <ul
                                        class="list-inline mb-0 d-flex align-items-center flex-wrap justify-content-sm-start justify-content-center gap-4 mt-4">
                                        <li class="list-inline-item">
                                            <i class="bx bx-palette me-2 align-top"></i><span
                                                class="fw-medium">{{ strtoupper(auth()->user()->role) }}</span>
                                        </li>
                                        <li class="list-inline-item">
                                            <i class="bx bx-map me-2 align-top"></i><span class="fw-medium">JSPC</span>
                                        </li>

                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--/ Header -->

        <!-- User Profile Content -->
        <div class="row">
            <div class="col-xl-12 col-lg-12 col-md-12">
                <!-- About User -->
                <div class="card mb-6">
                    <div class="card-body">
                        <div class="row">
                            <!-- About Section (Left Column) -->
                            <div class="col-md-6">
                                <small class="card-text text-uppercase text-muted small">About</small>
                                <ul class="list-unstyled my-3 py-1">
                                    <li class="d-flex align-items-center mb-4">
                                        <i class="bx bx-user"></i><span class="fw-medium mx-2">Full Name:</span>
                                        <span>{{ auth()->user()->adminDetails->name }}</span>
                                    </li>
                                    <li class="d-flex align-items-center mb-4">
                                        <i class="bx bx-check"></i><span class="fw-medium mx-2">Status:</span>
                                        <span>Active</span>
                                    </li>
                                    <li class="d-flex align-items-center mb-4">
                                        <i class="bx bx-crown"></i><span class="fw-medium mx-2">Role:</span>
                                        <span>{{ strtoupper(auth()->user()->role) }}</span>
                                    </li>
                                    <li class="d-flex align-items-center mb-4">
                                        <i class="bx bx-flag"></i><span class="fw-medium mx-2">Country:</span>
                                        <span>India</span>
                                    </li>
                                </ul>
                            </div>

                            <!-- Contacts Section (Right Column) -->
                            <div class="col-md-6">
                                <small class="card-text text-uppercase text-muted small">Contacts</small>
                                <ul class="list-unstyled my-3 py-1">
                                    <li class="d-flex align-items-center mb-4">
                                        <i class="bx bx-phone"></i><span class="fw-medium mx-2">Mobile No.:</span>
                                        <span>{{ auth()->user()->adminDetails->mobile_no }}</span>
                                    </li>
                                    <li class="d-flex align-items-center mb-4">
                                        <i class="bx bx-phone"></i><span class="fw-medium mx-2">Alt. Mobile No.:</span>
                                        <span>{{ auth()->user()->adminDetails->alt_mobile_no }}</span>
                                    </li>
                                    <li class="d-flex align-items-center mb-4">
                                        <i class="bx bx-chat"></i><span class="fw-medium mx-2">WhatsApp:</span>
                                        <span>{{ auth()->user()->adminDetails->whatsapp }}</span>
                                    </li>
                                    <li class="d-flex align-items-center mb-4">
                                        <i class="bx bx-envelope"></i><span class="fw-medium mx-2">Email:</span>
                                        <span>{{ auth()->user()->adminDetails->email }}</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                </div>
                <!--/ About User -->
            </div>
            {{-- <div class="col-xl-8 col-lg-7 col-md-7">
                <!-- Activity Timeline -->
                <div class="card card-action mb-6">
                    <div class="card-header align-items-center">
                        <h5 class="card-action-title mb-0"><i
                                class="bx bx-bar-chart-alt-2 bx-lg text-body me-4"></i>Activity Timeline</h5>
                    </div>
                    <div class="card-body pt-3">
                        <ul class="timeline mb-0">
                            <li class="timeline-item timeline-item-transparent">
                                <span class="timeline-point timeline-point-primary"></span>
                                <div class="timeline-event">
                                    <div class="timeline-header mb-3">
                                        <h6 class="mb-0">12 Invoices have been paid</h6>
                                        <small class="text-muted">12 min ago</small>
                                    </div>
                                    <p class="mb-2">
                                        Invoices have been paid to the company
                                    </p>
                                    <div class="d-flex align-items-center mb-2">
                                        <div class="badge bg-lighter rounded d-flex align-items-center">
                                            <img src="../../assets//img/icons/misc/pdf.png" alt="img" width="15"
                                                class="me-2">
                                            <span class="h6 mb-0 text-body">invoices.pdf</span>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li class="timeline-item timeline-item-transparent">
                                <span class="timeline-point timeline-point-success"></span>
                                <div class="timeline-event">
                                    <div class="timeline-header mb-3">
                                        <h6 class="mb-0">Client Meeting</h6>
                                        <small class="text-muted">45 min ago</small>
                                    </div>
                                    <p class="mb-2">
                                        Project meeting with john @10:15am
                                    </p>
                                    <div class="d-flex justify-content-between flex-wrap gap-2 mb-2">
                                        <div class="d-flex flex-wrap align-items-center mb-50">
                                            <div class="avatar avatar-sm me-3">
                                                <img src="../../assets/img/avatars/1.png" alt="Avatar"
                                                    class="rounded-circle">
                                            </div>
                                            <div>
                                                <p class="mb-0 small fw-medium">Lester McCarthy (Client)</p>
                                                <small>CEO of ThemeSelection</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li class="timeline-item timeline-item-transparent">
                                <span class="timeline-point timeline-point-info"></span>
                                <div class="timeline-event">
                                    <div class="timeline-header mb-3">
                                        <h6 class="mb-0">Create a new project for client</h6>
                                        <small class="text-muted">2 Day Ago</small>
                                    </div>
                                    <p class="mb-2">
                                        6 team members in a project
                                    </p>
                                    <ul class="list-group list-group-flush">
                                        <li
                                            class="list-group-item d-flex justify-content-between align-items-center flex-wrap border-top-0 p-0">
                                            <div class="d-flex flex-wrap align-items-center">
                                                <ul
                                                    class="list-unstyled users-list d-flex align-items-center avatar-group m-0 me-2">
                                                    <li data-bs-toggle="tooltip" data-popup="tooltip-custom"
                                                        data-bs-placement="top" class="avatar pull-up"
                                                        aria-label="Vinnie Mostowy"
                                                        data-bs-original-title="Vinnie Mostowy">
                                                        <img class="rounded-circle" src="../../assets/img/avatars/1.png"
                                                            alt="Avatar">
                                                    </li>
                                                    <li data-bs-toggle="tooltip" data-popup="tooltip-custom"
                                                        data-bs-placement="top" class="avatar pull-up"
                                                        aria-label="Allen Rieske" data-bs-original-title="Allen Rieske">
                                                        <img class="rounded-circle" src="../../assets/img/avatars/4.png"
                                                            alt="Avatar">
                                                    </li>
                                                    <li data-bs-toggle="tooltip" data-popup="tooltip-custom"
                                                        data-bs-placement="top" class="avatar pull-up"
                                                        aria-label="Julee Rossignol"
                                                        data-bs-original-title="Julee Rossignol">
                                                        <img class="rounded-circle" src="../../assets/img/avatars/2.png"
                                                            alt="Avatar">
                                                    </li>
                                                    <li class="avatar">
                                                        <span class="avatar-initial rounded-circle pull-up text-heading"
                                                            data-bs-toggle="tooltip" data-bs-placement="bottom"
                                                            data-bs-original-title="3 more">+3</span>
                                                    </li>
                                                </ul>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
                <!--/ Activity Timeline -->
            </div> --}}
        </div>
        <!--/ User Profile Content -->

    </div>
@endsection
