@extends('admin.layouts.main')

@section('admin-content')
    <div class="container-xxl flex-grow-1 mt-3.5 mb-4">
        <!-- Moderator Hero Banner -->
        <div class="card mb-4 overflow-hidden border-0 shadow-sm position-relative moderator-hero-card" style="background: linear-gradient(135deg, #0F172A 0%, #1E293B 40%, #0F52BA 100%); color: #FFFFFF; border-radius: 16px; box-shadow: 0 12px 30px rgba(15, 82, 186, 0.22) !important;">
            <div class="card-body py-4 px-3 px-md-4 position-relative z-2">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                    <div>
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <span class="badge px-3 py-1 fw-bold shadow-xs d-inline-flex align-items-center gap-1.5" style="background-color: #38BDF8; color: #0F172A; border-radius: 20px; font-size: 0.75rem;">
                                <i class="bx bx-shield-quarter fs-6"></i> IPHACON 2027 Moderator Control Center
                            </span>
                            <span class="badge px-2.5 py-1 fw-semibold" style="background-color: rgba(255, 255, 255, 0.15); color: #E2E8F0; border-radius: 20px; font-size: 0.7rem;">
                                Moderation Active
                            </span>
                        </div>
                        <h3 class="text-white fw-bold mt-1 mb-1 fs-4" style="letter-spacing: -0.3px;">
                            Welcome, {{ auth('admin')->user()->full_name ?? auth('admin')->user()->username }}! 🛡️
                        </h3>
                        <p class="text-slate-300 mb-0 small" style="color: #94A3B8; font-size: 0.875rem; max-width: 680px;">
                            Verification desk for delegate registrations, payment validation, abstract evaluations, and participant inquiries for 71st Annual National Conference of IPHA.
                        </p>
                    </div>
                    <div class="d-flex gap-2 flex-wrap">
                        <a href="{{ route('submitted-delegates') }}" class="btn btn-sm btn-light fw-bold text-dark shadow-xs px-3.5 py-2.5 btn-capsule d-inline-flex align-items-center gap-1.5" style="border-radius: 25px; font-size: 0.825rem; background: #F8FAFC; color: #0F172A !important;">
                            <i class="bx bx-check-circle text-success fs-5"></i> Review Registrations
                        </a>
                        @if(Route::has('admin.abstracts.index'))
                        <a href="{{ route('admin.abstracts.index') }}" class="btn btn-sm btn-info fw-bold text-white shadow-xs px-3.5 py-2.5 btn-capsule d-inline-flex align-items-center gap-1.5" style="border-radius: 25px; font-size: 0.825rem; background: #0288D1; border: none;">
                            <i class="bx bx-file-find fs-5"></i> Evaluate Abstracts
                        </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Moderator Primary Metric Cards Grid -->
        <div class="row g-3.5 mb-4">
            <!-- 1. Pending Verification Card -->
            <div class="col-sm-6 col-xl-3">
                <div class="card h-100 border-0 shadow-sm moderator-stat-card overflow-hidden">
                    <div style="height: 4px; background: linear-gradient(90deg, #0288D1 0%, #00897B 100%);"></div>
                    <div class="card-body p-3.5 d-flex flex-column justify-content-between">
                        <div>
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <div class="rounded-3 d-flex align-items-center justify-content-center stat-icon-box" style="width: 48px; height: 48px; background-color: #E0F2FE; color: #0288D1;">
                                    <i class="bx bx-time-five fs-2"></i>
                                </div>
                                <span class="badge px-3 py-1.5 fs-6 fw-bold rounded-pill shadow-xs" style="background-color: #E0F2FE; color: #0288D1;">
                                    {{ number_format($submittedCount) }}
                                </span>
                            </div>
                            <h6 class="text-muted fw-bold mb-1 text-uppercase extra-small" style="letter-spacing: 0.5px; font-size: 0.72rem;">Pending Verification</h6>
                            <h5 class="fw-bold text-dark mb-2" style="font-size: 1.05rem;">Submitted Registrations</h5>
                            <p class="text-muted extra-small mb-3" style="font-size: 0.78rem;">Delegates awaiting payment & document verification.</p>
                        </div>
                        <a href="{{ route('submitted-delegates') }}" class="btn btn-sm w-100 fw-bold d-flex align-items-center justify-content-center gap-1 btn-capsule py-2" style="background-color: #E0F2FE; color: #0288D1; border: 1px solid #BAE6FD; font-size: 0.8rem;">
                            <span>Review Applications</span>
                            <i class="bx bx-right-arrow-alt fs-5"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- 2. Abstracts Under Evaluation Card -->
            <div class="col-sm-6 col-xl-3">
                <div class="card h-100 border-0 shadow-sm moderator-stat-card overflow-hidden">
                    <div style="height: 4px; background: linear-gradient(90deg, #FF6B00 0%, #E65100 100%);"></div>
                    <div class="card-body p-3.5 d-flex flex-column justify-content-between">
                        <div>
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <div class="rounded-3 d-flex align-items-center justify-content-center stat-icon-box" style="width: 48px; height: 48px; background-color: #FFF3E0; color: #FF6B00;">
                                    <i class="bx bx-file-find fs-2"></i>
                                </div>
                                <span class="badge px-3 py-1.5 fs-6 fw-bold rounded-pill shadow-xs" style="background-color: #FFF3E0; color: #FF6B00;">
                                    {{ number_format($abstractCount) }}
                                </span>
                            </div>
                            <h6 class="text-muted fw-bold mb-1 text-uppercase extra-small" style="letter-spacing: 0.5px; font-size: 0.72rem;">Scientific Papers</h6>
                            <h5 class="fw-bold text-dark mb-2" style="font-size: 1.05rem;">Abstract Submissions</h5>
                            <p class="text-muted extra-small mb-3" style="font-size: 0.78rem;">Oral & poster abstracts submitted by delegates.</p>
                        </div>
                        @if(Route::has('admin.abstracts.index'))
                        <a href="{{ route('admin.abstracts.index') }}" class="btn btn-sm w-100 fw-bold d-flex align-items-center justify-content-center gap-1 btn-capsule py-2" style="background-color: #FF6B00; color: #FFFFFF; border: none; font-size: 0.8rem;">
                            <span>Manage Abstracts</span>
                            <i class="bx bx-right-arrow-alt fs-5"></i>
                        </a>
                        @else
                        <span class="btn btn-sm w-100 fw-bold d-flex align-items-center justify-content-center gap-1 btn-capsule py-2 disabled" style="background-color: #FFF3E0; color: #FF6B00; border: none; font-size: 0.8rem;">
                            <span>Abstract Desk</span>
                        </span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- 3. Reverted Applications Card -->
            <div class="col-sm-6 col-xl-3">
                <div class="card h-100 border-0 shadow-sm moderator-stat-card overflow-hidden">
                    <div style="height: 4px; background: linear-gradient(90deg, #F59E0B 0%, #D97706 100%);"></div>
                    <div class="card-body p-3.5 d-flex flex-column justify-content-between">
                        <div>
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <div class="rounded-3 d-flex align-items-center justify-content-center stat-icon-box" style="width: 48px; height: 48px; background-color: #FEF3C7; color: #D97706;">
                                    <i class="bx bx-undo fs-2"></i>
                                </div>
                                <span class="badge px-3 py-1.5 fs-6 fw-bold rounded-pill shadow-xs" style="background-color: #FEF3C7; color: #B45309;">
                                    {{ number_format($revertedCount) }}
                                </span>
                            </div>
                            <h6 class="text-muted fw-bold mb-1 text-uppercase extra-small" style="letter-spacing: 0.5px; font-size: 0.72rem;">Needs Action</h6>
                            <h5 class="fw-bold text-dark mb-2" style="font-size: 1.05rem;">Reverted Registrations</h5>
                            <p class="text-muted extra-small mb-3" style="font-size: 0.78rem;">Applications returned to delegates for correction.</p>
                        </div>
                        <a href="{{ route('international-reverted-delegates') }}" class="btn btn-sm w-100 fw-bold d-flex align-items-center justify-content-center gap-1 btn-capsule py-2" style="background-color: #FEF3C7; color: #B45309; border: 1px solid #FDE68A; font-size: 0.8rem;">
                            <span>View Reverted</span>
                            <i class="bx bx-right-arrow-alt fs-5"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- 4. Verified & Approved Delegates Card -->
            <div class="col-sm-6 col-xl-3">
                <div class="card h-100 border-0 shadow-sm moderator-stat-card overflow-hidden">
                    <div style="height: 4px; background: linear-gradient(90deg, #10B981 0%, #059669 100%);"></div>
                    <div class="card-body p-3.5 d-flex flex-column justify-content-between">
                        <div>
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <div class="rounded-3 d-flex align-items-center justify-content-center stat-icon-box" style="width: 48px; height: 48px; background-color: #DCFFF0; color: #059669;">
                                    <i class="bx bx-badge-check fs-2"></i>
                                </div>
                                <span class="badge px-3 py-1.5 fs-6 fw-bold rounded-pill shadow-xs" style="background-color: #DCFFF0; color: #059669;">
                                    {{ number_format($approvedIndCount + $approvedIntCount) }}
                                </span>
                            </div>
                            <h6 class="text-muted fw-bold mb-1 text-uppercase extra-small" style="letter-spacing: 0.5px; font-size: 0.72rem;">Verified Attendees</h6>
                            <h5 class="fw-bold text-dark mb-2" style="font-size: 1.05rem;">Approved Delegates</h5>
                            <p class="text-muted extra-small mb-3" style="font-size: 0.78rem;">India: <strong>{{ $approvedIndCount }}</strong> | Foreign: <strong>{{ $approvedIntCount }}</strong></p>
                        </div>
                        <a href="{{ route('indian-approved-delegates') }}" class="btn btn-sm w-100 fw-bold d-flex align-items-center justify-content-center gap-1 btn-capsule py-2" style="background-color: #DCFFF0; color: #059669; border: 1px solid #A7F3D0; font-size: 0.8rem;">
                            <span>View Approved</span>
                            <i class="bx bx-right-arrow-alt fs-5"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Moderation Quick Action Hub -->
        <div class="row g-3.5 mb-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm overflow-hidden" style="border-radius: 16px;">
                    <div class="card-header bg-white py-3.5 px-4 border-bottom d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <div class="d-flex align-items-center gap-2.5">
                            <div class="rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 38px; height: 38px; background-color: #E8F2FF !important; color: #013069 !important;">
                                <i class="bx bx-rocket fs-5" style="color: #013069 !important;"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold text-dark mb-0" style="font-size: 1rem;">Moderation Action Shortcuts</h6>
                                <small class="text-muted extra-small">Direct links to active verification & evaluation tools</small>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-4 bg-light bg-opacity-50">
                        <div class="row g-3">
                            <div class="col-sm-6 col-lg-3">
                                <a href="{{ route('submitted-delegates') }}" class="card h-100 text-decoration-none border shadow-xs action-hub-card p-3 d-flex flex-row align-items-center gap-3">
                                    <div class="rounded-circle p-3 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 50px; height: 50px; background-color: #E0F2FE !important; color: #0288D1 !important;">
                                        <i class="bx bx-paper-plane fs-3" style="color: #0288D1 !important;"></i>
                                    </div>
                                    <div>
                                        <h6 class="fw-bold text-dark mb-0.5" style="font-size: 0.9rem;">Submitted Applications</h6>
                                        <small class="text-muted extra-small d-block">Verify payment slips & student IDs</small>
                                    </div>
                                </a>
                            </div>
                            <div class="col-sm-6 col-lg-3">
                                <a href="{{ route('admin.cme-delegates') }}" class="card h-100 text-decoration-none border shadow-xs action-hub-card p-3 d-flex flex-row align-items-center gap-3">
                                    <div class="rounded-circle p-3 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 50px; height: 50px; background-color: #E0F7FA !important; color: #00897B !important;">
                                        <i class="bx bx-book-reader fs-3" style="color: #00897B !important;"></i>
                                    </div>
                                    <div>
                                        <h6 class="fw-bold text-dark mb-0.5" style="font-size: 0.9rem;">CME Workshop</h6>
                                        <small class="text-muted extra-small d-block">{{ $cmeCount }} Registered Participants</small>
                                    </div>
                                </a>
                            </div>
                            <div class="col-sm-6 col-lg-3">
                                <a href="{{ Route::has('admin.abstracts.index') ? route('admin.abstracts.index') : '#' }}" class="card h-100 text-decoration-none border shadow-xs action-hub-card p-3 d-flex flex-row align-items-center gap-3">
                                    <div class="rounded-circle p-3 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 50px; height: 50px; background-color: #FFF3E0 !important; color: #FF6B00 !important;">
                                        <i class="bx bx-file-find fs-3" style="color: #FF6B00 !important;"></i>
                                    </div>
                                    <div>
                                        <h6 class="fw-bold text-dark mb-0.5" style="font-size: 0.9rem;">Scientific Abstracts</h6>
                                        <small class="text-muted extra-small d-block">Review abstract submissions</small>
                                    </div>
                                </a>
                            </div>
                            <div class="col-sm-6 col-lg-3">
                                <a href="{{ route('pending-payments') }}" class="card h-100 text-decoration-none border shadow-xs action-hub-card p-3 d-flex flex-row align-items-center gap-3">
                                    <div class="rounded-circle p-3 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 50px; height: 50px; background-color: #FEF3C7 !important; color: #D97706 !important;">
                                        <i class="bx bx-time-five fs-3" style="color: #D97706 !important;"></i>
                                    </div>
                                    <div>
                                        <h6 class="fw-bold text-dark mb-0.5" style="font-size: 0.9rem;">Pending Payments</h6>
                                        <small class="text-muted extra-small d-block">Verify pending transactions</small>
                                    </div>
                                </a>
                            </div>
                            <div class="col-sm-6 col-lg-3">
                                <a href="{{ route('paid-payments') }}" class="card h-100 text-decoration-none border shadow-xs action-hub-card p-3 d-flex flex-row align-items-center gap-3">
                                    <div class="rounded-circle p-3 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 50px; height: 50px; background-color: #DCFFF0 !important; color: #059669 !important;">
                                        <i class="bx bx-credit-card-front fs-3" style="color: #059669 !important;"></i>
                                    </div>
                                    <div>
                                        <h6 class="fw-bold text-dark mb-0.5" style="font-size: 0.9rem;">Payment Records</h6>
                                        <small class="text-muted extra-small d-block">Cross-check transaction status</small>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Activity Tables Row -->
        <div class="row g-4 mb-4">

            <!-- Recent Abstract Submissions -->
            <div class="col-lg-12">
                <div class="card border-0 shadow-sm h-100 overflow-hidden" style="border-radius: 16px;">
                    <div class="card-header bg-white py-3.5 px-4 border-bottom d-flex justify-content-between align-items-center flex-wrap gap-2">
                        <div class="d-flex align-items-center gap-2">
                            <i class="bx bx-file-find text-warning fs-4" style="color: #FF6B00 !important;"></i>
                            <h6 class="fw-bold text-dark mb-0" style="font-size: 0.95rem;">Recent Abstracts</h6>
                        </div>
                        @if(Route::has('admin.abstracts.index'))
                        <a href="{{ route('admin.abstracts.index') }}" class="btn btn-sm btn-outline-warning fw-bold px-3 py-1" style="border-radius: 20px; font-size: 0.75rem; color: #E65100; border-color: #FFB74D;">
                            Manage <i class="bx bx-right-arrow-alt"></i>
                        </a>
                        @endif
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="ps-4 text-muted extra-small fw-bold text-uppercase">Ack ID</th>
                                    <th class="text-muted extra-small fw-bold text-uppercase">Presenter / Title</th>
                                    <th class="text-end pe-4 text-muted extra-small fw-bold text-uppercase">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentAbstracts as $abs)
                                    <tr>
                                        <td class="ps-4 fw-bold font-monospace text-dark extra-small">
                                            {{ $abs->acknowledgement_id ?? ('ABS-'.$abs->id) }}
                                        </td>
                                        <td>
                                            <span class="fw-bold text-dark d-block text-truncate small" style="max-width: 190px;" title="{{ $abs->abstract_title }}">
                                                {{ $abs->abstract_title }}
                                            </span>
                                            <small class="text-muted extra-small d-block">By: {{ $abs->presenting_author_name ?? ($abs->user->full_name ?? 'N/A') }}</small>
                                            @if($abs->status === 'Accepted' && $abs->presentation_mode === 'Oral Presentation')
                                                <span class="badge rounded-pill extra-small mt-0.5" style="background-color: #059669 !important; color: #FFFFFF !important; font-size: 0.68rem;">
                                                    <i class="bx bx-microphone"></i> Oral (OP) • {{ $abs->reviewed_at ? $abs->reviewed_at->format('d M, h:i A') : $abs->updated_at->format('d M, h:i A') }}
                                                </span>
                                            @elseif($abs->status === 'Accepted')
                                                <span class="badge rounded-pill extra-small mt-0.5" style="background-color: #0288D1 !important; color: #FFFFFF !important; font-size: 0.68rem;">
                                                    <i class="bx bx-file"></i> Paper (PP) • {{ $abs->reviewed_at ? $abs->reviewed_at->format('d M, h:i A') : $abs->updated_at->format('d M, h:i A') }}
                                                </span>
                                            @elseif($abs->status === 'Rejected')
                                                <span class="badge rounded-pill extra-small mt-0.5" style="background-color: #DC2626 !important; color: #FFFFFF !important; font-size: 0.68rem;">
                                                    <i class="bx bx-x-circle"></i> Rejected • {{ $abs->reviewed_at ? $abs->reviewed_at->format('d M, h:i A') : $abs->updated_at->format('d M, h:i A') }}
                                                </span>
                                            @endif
                                        </td>
                                        <td class="text-end pe-4">
                                            @if(Route::has('admin.abstracts.show'))
                                            <div class="btn-group">
                                                <a href="{{ route('admin.abstracts.show', $abs->id) }}" class="btn btn-sm btn-light border text-dark fw-bold px-2 py-1" style="border-radius: 6px; font-size: 0.75rem;">
                                                    View
                                                </a>
                                                <button type="button" class="btn btn-sm btn-light border text-dark dropdown-toggle dropdown-toggle-split px-1" data-bs-toggle="dropdown" aria-expanded="false" style="border-radius: 6px;">
                                                    <span class="visually-hidden">Toggle</span>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 p-2" style="border-radius: 12px; font-size: 0.78rem;">
                                                    <li>
                                                        <form action="{{ route('admin.abstracts.update-status', $abs->id) }}" method="POST" class="m-0">
                                                            @csrf
                                                            <input type="hidden" name="decision" value="accept_oral">
                                                            <button type="submit" class="dropdown-item rounded-2 py-1 px-2 fw-bold d-flex align-items-center gap-1.5" style="color: #059669 !important;">
                                                                <i class="bx bx-microphone" style="color: #059669 !important;"></i>
                                                                <span style="color: #059669 !important;">Accept for Oral</span>
                                                            </button>
                                                        </form>
                                                    </li>
                                                    <li>
                                                        <form action="{{ route('admin.abstracts.update-status', $abs->id) }}" method="POST" class="m-0">
                                                            @csrf
                                                            <input type="hidden" name="decision" value="accept_paper">
                                                            <button type="submit" class="dropdown-item rounded-2 py-1 px-2 fw-bold d-flex align-items-center gap-1.5" style="color: #0288D1 !important;">
                                                                <i class="bx bx-file" style="color: #0288D1 !important;"></i>
                                                                <span style="color: #0288D1 !important;">Accept for Paper</span>
                                                            </button>
                                                        </form>
                                                    </li>
                                                    <li><hr class="dropdown-divider my-1"></li>
                                                    <li>
                                                        <form action="{{ route('admin.abstracts.update-status', $abs->id) }}" method="POST" class="m-0">
                                                            @csrf
                                                            <input type="hidden" name="decision" value="reject">
                                                            <button type="submit" class="dropdown-item rounded-2 py-1 px-2 fw-bold d-flex align-items-center gap-1.5" style="color: #DC2626 !important;">
                                                                <i class="bx bx-x-circle" style="color: #DC2626 !important;"></i>
                                                                <span style="color: #DC2626 !important;">Reject</span>
                                                            </button>
                                                        </form>
                                                    </li>
                                                </ul>
                                            </div>
                                            @else
                                            <span class="badge bg-light text-muted border extra-small">Submitted</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center py-4 text-muted extra-small">
                                            No recent abstract submissions found.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>


    </div>

    <style>
        .moderator-hero-card {
            transition: all 0.3s ease;
        }
        .moderator-stat-card {
            background: #ffffff;
            border-radius: 16px !important;
            transition: all 0.3s cubic-bezier(0.165, 0.84, 0.44, 1) !important;
            border: 1px solid rgba(226, 232, 240, 0.9) !important;
        }
        .moderator-stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 16px 32px rgba(15, 82, 186, 0.12) !important;
            border-color: rgba(15, 82, 186, 0.3) !important;
        }
        .moderator-stat-card .stat-icon-box {
            transition: transform 0.3s ease;
        }
        .moderator-stat-card:hover .stat-icon-box {
            transform: scale(1.1) rotate(-3deg);
        }
        .action-hub-card {
            border-radius: 12px !important;
            background: #ffffff;
            transition: all 0.25s ease !important;
        }
        .action-hub-card:hover {
            transform: translateY(-3px);
            border-color: #0288D1 !important;
            box-shadow: 0 8px 20px rgba(2, 136, 209, 0.12) !important;
        }
        .btn-capsule {
            border-radius: 25px !important;
            transition: all 0.25s ease !important;
        }
        .btn-capsule:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.15) !important;
        }
    </style>
@endsection

@push('scripts')
    <script>
        window.history.pushState(null, '', window.location.href);
        window.onpopstate = function() {
            window.history.go(1);
        };
    </script>
@endpush
