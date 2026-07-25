@extends('admin.layouts.main')

@section('admin-content')
    <div class="container-xxl flex-grow-1 mt-3">
        <div class="row">
            <div class="col-sm-6 col-lg-4">
                <div class="card card-sm">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <div class="avatar flex-shrink-0 text-success">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round"
                                        class="icon icon-tabler icons-tabler-outline icon-tabler-license">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path
                                            d="M15 21h-9a3 3 0 0 1 -3 -3v-1h10v2a2 2 0 0 0 4 0v-14a2 2 0 1 1 2 2h-2m2 -4h-11a3 3 0 0 0 -3 3v11" />
                                        <path d="M9 7l4 0" />
                                        <path d="M9 11l4 0" />
                                    </svg>
                                </div>
                            </div>
                            <div class="col">
                                <div class="font-weight-medium">
                                    Indian Approved Delegates <br> <span
                                        class="badge bg-primary text-primary-fg ms-auto">{{ $IndApprovedCount }}</span>
                                </div>
                                <div class="text-secondary">
                                    <a href="{{ route('indian-approved-delegates') }}"
                                        class="btn btn-outline-primary mt-4">Click
                                        Here</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-sm-6 col-lg-4">
                <div class="card card-sm">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <div class="avatar flex-shrink-0 text-primary">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round"
                                        class="icon icon-tabler icons-tabler-outline icon-tabler-license">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path
                                            d="M15 21h-9a3 3 0 0 1 -3 -3v-1h10v2a2 2 0 0 0 4 0v-14a2 2 0 1 1 2 2h-2m2 -4h-11a3 3 0 0 0 -3 3v11" />
                                        <path d="M9 7l4 0" />
                                        <path d="M9 11l4 0" />
                                    </svg>
                                </div>
                            </div>
                            <div class="col">
                                <div class="font-weight-medium">
                                    International Payment Submitted <br>
                                    <span class="badge bg-primary text-primary-fg ms-auto">{{ $appliedCount }}</span>
                                </div>
                                <div class="text-secondary">
                                    <a href="{{ route('international-payment-submitted-delegates') }}"
                                        class="btn btn-outline-primary mt-4">Click
                                        Here</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-6 col-lg-4">
                <div class="card card-sm">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <div class="avatar flex-shrink-0 text-primary">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round"
                                        class="icon icon-tabler icons-tabler-outline icon-tabler-license">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path
                                            d="M15 21h-9a3 3 0 0 1 -3 -3v-1h10v2a2 2 0 0 0 4 0v-14a2 2 0 1 1 2 2h-2m2 -4h-11a3 3 0 0 0 -3 3v11" />
                                        <path d="M9 7l4 0" />
                                        <path d="M9 11l4 0" />
                                    </svg>
                                </div>
                            </div>
                            <div class="col">
                                <div class="font-weight-medium">
                                    Approved International Delegate <br>
                                    <span class="badge bg-primary text-primary-fg ms-auto">{{ $IntApprovedCount }}</span>
                                </div>
                                <div class="text-secondary">
                                    <a href="{{ route('international-approved-delegates') }}"
                                        class="btn btn-outline-primary mt-4">Click
                                        Here</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        window.history.pushState(null, '', window.location.href);
        window.onpopstate = function() {
            window.history.go(1);
        };
    </script>
@endpush
