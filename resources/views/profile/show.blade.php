@extends('shared.auth-delegate')
@section('title', 'My Profile')

@php
    $inner_title = 'Delegate Profile';
    // dd($user);
@endphp


@section('delegate-content')
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow-lg border-0" style="border-radius: 15px;">
                <div class="card-header d-flex justify-content-between align-items-center py-4"
                    style="background: linear-gradient(135deg, #2e3192, #4a5bcc); border-radius: 15px 15px 0 0;">
                    <h3 class="text-white mb-0 fw-bold">
                        <i class="fas fa-user-circle me-2"></i>My Profile
                    </h3>
                    <div>
                        <a href="{{ route('profile.change-password') }}" class="btn btn-outline-light btn-sm">
                            <i class="fas fa-key me-1"></i>Change Password
                        </a>
                    </div>
                </div>

                <div class="card-body p-5">
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <div class="row">
                        <div class="col-md-6">
                            <div class="profile-section mb-4">
                                <h5 class="text-primary border-bottom pb-2 mb-3">
                                    <i class="fas fa-user me-2"></i>Personal Information
                                </h5>

                                <div class="row mb-3">
                                    <div class="col-sm-4"><strong>Full Name:</strong></div>
                                    <div class="col-sm-8">{{ $user->prefix }} {{ $user->full_name }}</div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-sm-4"><strong>Gender:</strong></div>
                                    <div class="col-sm-8">{{ $user?->gender }}</div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-sm-4"><strong>Date of Birth:</strong></div>
                                    <div class="col-sm-8">{{ $user?->date_of_birth?->format('d M, Y') }}</div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-sm-4"><strong>Age:</strong></div>
                                    <div class="col-sm-8">{{ $user?->date_of_birth?->age }} years</div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="profile-section mb-4">
                                <h5 class="text-primary border-bottom pb-2 mb-3">
                                    <i class="fas fa-address-book me-2"></i>Contact Information
                                </h5>

                                <div class="row mb-3">
                                    <div class="col-sm-4"><strong>Email:</strong></div>
                                    <div class="col-sm-8">
                                        {{ $user->email }}
                                        @if ($user->hasVerifiedEmail())
                                            <span class="badge bg-success ms-2">
                                                <i class="fas fa-check-circle me-1"></i>Verified
                                            </span>
                                        @else
                                            <span class="badge bg-warning ms-2">
                                                <i class="fas fa-exclamation-triangle me-1"></i>Not Verified
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-sm-4"><strong>Mobile:</strong></div>
                                    <div class="col-sm-8">{{ $user->mobile_country_code }} {{ $user?->mobile_number }}</div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-sm-4"><strong>Country:</strong></div>
                                    <div class="col-sm-8">{{ $user?->country?->country_name ?? 'Not set' }}</div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-sm-4"><strong>Delegate Tye:</strong></div>
                                    <div class="col-sm-8">{{ $user?->delegate_type }}</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="profile-section">
                                <h5 class="text-primary border-bottom pb-2 mb-3">
                                    <i class="fas fa-info-circle me-2"></i>Account Information
                                </h5>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="row mb-3">
                                            <div class="col-sm-6"><strong>Member Since:</strong></div>
                                            <div class="col-sm-6">{{ $user->created_at->format('d M, Y') }}</div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="row mb-3">
                                            <div class="col-sm-6"><strong>Last Login:</strong></div>
                                            <div class="col-sm-6">
                                                {{ $user->last_login ? $user->last_login->format('d M, Y H:i') : 'Never' }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
