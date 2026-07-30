@extends('shared.auth-delegate')
@section('title', 'My Profile')

@php
    $inner_title = 'Delegate Profile';
@endphp

@section('delegate-content')
<div class="container py-3">
    <div class="row justify-content-center">
        <div class="col-lg-9 col-xl-8">

            {{-- Compact Card Container --}}
            <div class="card border-0 shadow-sm" style="border-radius: 14px; overflow: hidden; background: #ffffff;">

                {{-- Compact Header --}}
                <div class="card-header d-flex justify-content-between align-items-center py-3 px-4"
                     style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%); border-bottom: 3px solid #2D69FF;">
                    <div class="d-flex align-items-center gap-2">
                        <i class="fas fa-user-circle text-primary fs-5"></i>
                        <h5 class="text-white mb-0 fw-bold" style="letter-spacing: 0.3px;">My Profile</h5>
                    </div>
                    <a href="{{ route('profile.change-password') }}" class="btn btn-outline-light btn-sm px-3 py-1 fw-semibold" style="border-radius: 8px; font-size: 0.85rem;">
                        <i class="fas fa-key me-1.5"></i>Change Password
                    </a>
                </div>

                <div class="card-body p-3 p-md-4">
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show py-2 px-3 mb-3 small" role="alert">
                            <i class="fas fa-check-circle me-1.5"></i>{{ session('success') }}
                            <button type="button" class="btn-close py-2" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    {{-- User Top Summary Strip --}}
                    <div class="d-flex align-items-center gap-3 p-3 mb-4 rounded-3" style="background: #f8fafc; border: 1px solid #e2e8f0;">
                        <div class="rounded-circle bg-primary bg-gradient d-flex align-items-center justify-content-center text-white fw-bold shadow-sm flex-shrink-0" style="width: 52px; height: 52px; font-size: 1.4rem;">
                            {{ strtoupper(substr($user->full_name ?? 'U', 0, 1)) }}
                        </div>
                        <div class="flex-grow-1 min-w-0">
                            <div class="d-flex flex-wrap align-items-center gap-2">
                                <h6 class="fw-bold mb-0 text-dark fs-6">{{ $user->prefix }} {{ $user->full_name }}</h6>
                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2.5 py-1 rounded-pill" style="font-size: 0.75rem;">
                                    {{ $user->delegate_type ?? 'Delegate' }}
                                </span>
                            </div>
                            <div class="text-muted small mt-1 d-flex flex-wrap gap-3" style="font-size: 0.825rem;">
                                <span><i class="fas fa-envelope me-1 text-secondary"></i>{{ $user->email }}</span>
                                @if ($user->hasVerifiedEmail())
                                    <span class="text-success"><i class="fas fa-check-circle me-1"></i>Verified</span>
                                @else
                                    <span class="text-warning"><i class="fas fa-exclamation-triangle me-1"></i>Unverified</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Compact Details Grid --}}
                    <div class="row g-3">

                        {{-- Personal Details --}}
                        <div class="col-md-6">
                            <div class="p-3 rounded-3 h-100" style="background: #ffffff; border: 1px solid #f1f5f9;">
                                <h6 class="fw-bold text-primary mb-3 pb-2 border-bottom d-flex align-items-center gap-2" style="font-size: 0.9rem;">
                                    <i class="fas fa-user text-primary"></i> Personal Details
                                </h6>
                                <div class="row g-2" style="font-size: 0.875rem;">
                                    <div class="col-5 text-muted">Full Name</div>
                                    <div class="col-7 fw-semibold text-dark">{{ $user->prefix }} {{ $user->full_name }}</div>

                                    <div class="col-5 text-muted">Gender</div>
                                    <div class="col-7 fw-semibold text-dark">{{ $user->gender ?: 'N/A' }}</div>

                                    <div class="col-5 text-muted">Date of Birth</div>
                                    <div class="col-7 fw-semibold text-dark">
                                        {{ $user->date_of_birth ? $user->date_of_birth->format('d M, Y') : 'N/A' }}
                                        @if($user->date_of_birth)
                                            <span class="text-muted fw-normal">({{ $user->date_of_birth->age }} yrs)</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Contact Details --}}
                        <div class="col-md-6">
                            <div class="p-3 rounded-3 h-100" style="background: #ffffff; border: 1px solid #f1f5f9;">
                                <h6 class="fw-bold text-primary mb-3 pb-2 border-bottom d-flex align-items-center gap-2" style="font-size: 0.9rem;">
                                    <i class="fas fa-address-book text-primary"></i> Contact Details
                                </h6>
                                <div class="row g-2" style="font-size: 0.875rem;">
                                    <div class="col-5 text-muted">Email</div>
                                    <div class="col-7 fw-semibold text-dark text-break">{{ $user->email }}</div>

                                    <div class="col-5 text-muted">Mobile</div>
                                    <div class="col-7 fw-semibold text-dark">{{ $user->mobile_country_code }} {{ $user->mobile_number }}</div>

                                    <div class="col-5 text-muted">Country</div>
                                    <div class="col-7 fw-semibold text-dark">{{ $user->country->country_name ?? 'Not set' }}</div>

                                    <div class="col-5 text-muted">Delegate Type</div>
                                    <div class="col-7 fw-semibold text-dark">{{ $user->delegate_type ?: 'N/A' }}</div>
                                </div>
                            </div>
                        </div>

                        {{-- Account Details Strip --}}
                        <div class="col-12 mt-3">
                            <div class="p-3 rounded-3" style="background: #f8fafc; border: 1px solid #e2e8f0;">
                                <h6 class="fw-bold text-secondary mb-2.5 pb-2 border-bottom d-flex align-items-center gap-2" style="font-size: 0.875rem;">
                                    <i class="fas fa-shield-alt text-secondary"></i> Account Activity
                                </h6>
                                <div class="row g-2" style="font-size: 0.85rem;">
                                    <div class="col-sm-6 d-flex justify-content-between pe-sm-4">
                                        <span class="text-muted">Member Since:</span>
                                        <span class="fw-semibold text-dark">{{ $user->created_at ? $user->created_at->format('d M, Y') : 'N/A' }}</span>
                                    </div>
                                    <div class="col-sm-6 d-flex justify-content-between ps-sm-4 border-sm-start">
                                        <span class="text-muted">Last Login:</span>
                                        <span class="fw-semibold text-dark">
                                            {{ $user->last_login ? $user->last_login->format('d M, Y h:i A') : 'Never' }}
                                        </span>
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

