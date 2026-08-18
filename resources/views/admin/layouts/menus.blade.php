<style>
    /* Ultra-Modern Executive Dark Sidebar Theme */
    #layout-menu.bg-menu-theme {
        background: linear-gradient(180deg, #0F172A 0%, #1E293B 100%) !important;
        box-shadow: 4px 0 25px rgba(15, 23, 42, 0.15);
        border-right: 1px solid rgba(255, 255, 255, 0.06) !important;
        display: flex;
        flex-direction: column;
    }

    /* Menu Group Headers */
    .menu-inner .menu-header {
        position: relative;
        padding: 14px 18px 6px 18px !important;
        margin-top: 4px;
    }
    .menu-inner .menu-header-text {
        font-size: 0.64rem !important;
        font-weight: 700 !important;
        letter-spacing: 1.2px !important;
        text-transform: uppercase;
        color: #64748B !important;
    }

    /* Menu Link Items */
    .menu-inner .menu-item .menu-link {
        margin: 2px 10px !important;
        padding: 8.5px 12px !important;
        border-radius: 10px !important;
        color: #94A3B8 !important;
        font-weight: 500 !important;
        font-size: 0.83rem !important;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .menu-inner .menu-item .menu-link:hover {
        background-color: rgba(255, 255, 255, 0.06) !important;
        color: #F8FAFC !important;
        transform: translateX(3px);
    }

    /* Active Parent Item */
    .menu-inner .menu-item.active > .menu-link,
    .menu-inner .menu-item.open > .menu-link {
        background: rgba(255, 255, 255, 0.08) !important;
        color: #FFFFFF !important;
        font-weight: 600 !important;
    }
    .menu-inner .menu-item.active > .menu-link {
        background: linear-gradient(135deg, #2563EB 0%, #1D4ED8 100%) !important;
        color: #FFFFFF !important;
        box-shadow: 0 4px 14px rgba(37, 99, 235, 0.35) !important;
    }

    /* Icons */
    .menu-inner .menu-icon {
        font-size: 1.15rem !important;
        margin-right: 10px !important;
        color: #94A3B8;
        transition: all 0.2s ease;
    }
    .menu-inner .menu-item .menu-link:hover .menu-icon {
        color: #38BDF8 !important;
    }
    .menu-inner .menu-item.active > .menu-link .menu-icon {
        color: #FFFFFF !important;
    }

    /* Submenu Tree Line styling */
    .menu-inner .menu-sub {
        background: transparent !important;
        margin: 4px 0 6px 22px !important;
        padding: 0 0 0 12px !important;
        border-left: 1px solid rgba(255, 255, 255, 0.1) !important;
    }
    .menu-inner .menu-sub .menu-item .menu-link {
        margin: 1.5px 0 !important;
        padding: 6.5px 12px !important;
        font-size: 0.8rem !important;
        font-weight: 450 !important;
        color: #94A3B8 !important;
        border-radius: 8px !important;
    }
    .menu-inner .menu-sub .menu-item .menu-link::before {
        display: none !important;
        content: none !important;
    }
    .menu-inner .menu-sub .menu-item .menu-link:hover {
        background-color: rgba(255, 255, 255, 0.05) !important;
        color: #F1F5F9 !important;
    }
    .menu-inner .menu-sub .menu-item.active > .menu-link {
        background: rgba(59, 130, 246, 0.15) !important;
        color: #60A5FA !important;
        font-weight: 600 !important;
        box-shadow: none !important;
        border-left: 2px solid #3B82F6;
    }

    /* Dropdown Toggle Arrow */
    .menu-item.menu-toggle > .menu-link::after {
        content: "\ea50";
        font-family: "boxicons" !important;
        font-size: 1.1rem;
        margin-left: auto;
        transition: transform 0.2s ease;
        color: #64748B;
    }
    .menu-item.open > .menu-link::after {
        transform: rotate(90deg);
        color: #38BDF8;
    }

    /* User Footer Card */
    .sidebar-user-footer {
        background: rgba(255, 255, 255, 0.05) !important;
        backdrop-filter: blur(12px);
        border: 1px solid rgba(255, 255, 255, 0.08) !important;
        border-radius: 12px;
        transition: all 0.25s ease-in-out;
    }
    .sidebar-user-footer:hover {
        background: rgba(255, 255, 255, 0.09) !important;
        border-color: rgba(255, 255, 255, 0.18) !important;
    }
</style>

<ul class="menu-inner py-2 flex-grow-1">
    @if (optional(auth('admin')->user())->isModerator())
        <!-- Moderator Dashboard -->
        <li class="menu-item {{ request()->routeIs('admin.moderator.dashboard') ? 'active' : '' }}">
            <a href="{{ route('admin.moderator.dashboard') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-shield-quarter" style="color: #38BDF8 !important;"></i>
                <div>Moderator Dashboard</div>
            </a>
        </li>

        <!-- Scientific & Abstracts -->
        <li class="menu-item {{ request()->routeIs('admin.abstracts.*') ? 'active open' : '' }}">
            <a href="{{ route('admin.abstracts.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-file-find" style="color: #FBBF24 !important;"></i>
                <div>Abstract Submissions</div>
            </a>
        </li>
    @else
        <!-- Dashboard -->
        @if (optional(auth('admin')->user())->role == 'superadmin' || optional(auth('admin')->user())->isSuperAdmin())
        <li class="menu-item {{ request()->routeIs('superadmin.dashboard') ? 'active' : '' }}">
            <a href="{{ Route::has('superadmin.dashboard') ? route('superadmin.dashboard') : '#' }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-grid-alt" style="color: #38BDF8 !important;"></i>
                <div>Super Dashboard</div>
            </a>
        </li>
        @else
        <li class="menu-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <a href="{{ Route::has('admin.dashboard') ? route('admin.dashboard') : '#' }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-home-circle" style="color: #38BDF8 !important;"></i>
                <div>Dashboard</div>
            </a>
        </li>
        @endif

        <!-- Admin & Roles Dropdown -->
        @if (optional(auth('admin')->user())->role == 'superadmin' || optional(auth('admin')->user())->isSuperAdmin())
        @php
            $isAdminActive = request()->routeIs('admin.admins.*') || request()->routeIs('admins.*');
        @endphp
        <li class="menu-item {{ $isAdminActive ? 'active open' : '' }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-user-check" style="color: #34D399 !important;"></i>
                <div>Admin &amp; Roles</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item {{ request()->routeIs('admin.admins.create') || request()->routeIs('admins.create') ? 'active' : '' }}">
                    <a href="{{ Route::has('admin.admins.create') ? route('admin.admins.create') : (Route::has('admins.create') ? route('admins.create') : '#') }}" class="menu-link">
                        <div>Create Admin</div>
                    </a>
                </li>
                <li class="menu-item {{ request()->routeIs('admin.admins.index') || request()->routeIs('admins.view-admins') ? 'active' : '' }}">
                    <a href="{{ Route::has('admin.admins.index') ? route('admin.admins.index') : (Route::has('admins.view-admins') ? route('admins.view-admins') : '#') }}" class="menu-link">
                        <div>View Admins</div>
                    </a>
                </li>
            </ul>
        </li>
        @endif

        <!-- Registration Dropdown -->
        @php
            $isRegActive = request()->routeIs('submitted-delegates') || 
                            request()->routeIs('indian-approved-delegates') || 
                            request()->routeIs('international-approved-delegates') || 
                            request()->routeIs('indian-incomplete-delegates') || 
                            request()->routeIs('admin.cme-delegates') || 
                            request()->routeIs('international-rejected-delegates') || 
                            request()->routeIs('international-reverted-delegates') || 
                            request()->routeIs('deleted-delegates');
        @endphp
        <li class="menu-item {{ $isRegActive ? 'active open' : '' }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-group" style="color: #60A5FA !important;"></i>
                <div>Registration</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item {{ request()->routeIs('submitted-delegates') ? 'active' : '' }}">
                    <a href="{{ Route::has('submitted-delegates') ? route('submitted-delegates') : '#' }}" class="menu-link">
                        <div>Submitted Delegates</div>
                    </a>
                </li>
                <li class="menu-item {{ request()->routeIs('international-payment-submitted-delegates') ? 'active' : '' }}">
                    <a href="{{ Route::has('international-payment-submitted-delegates') ? route('international-payment-submitted-delegates') : '#' }}" class="menu-link">
                        <div>Submitted Delegates (Foreign)</div>
                    </a>
                </li>
                <li class="menu-item {{ request()->routeIs('indian-approved-delegates') ? 'active' : '' }}">
                    <a href="{{ Route::has('indian-approved-delegates') ? route('indian-approved-delegates') : '#' }}" class="menu-link">
                        <div>Registered Delegates (India)</div>
                    </a>
                </li>
                <li class="menu-item {{ request()->routeIs('international-approved-delegates') ? 'active' : '' }}">
                    <a href="{{ Route::has('international-approved-delegates') ? route('international-approved-delegates') : '#' }}" class="menu-link">
                        <div>Registered Delegates (Foreign)</div>
                    </a>
                </li>
                <li class="menu-item {{ request()->routeIs('indian-incomplete-delegates') ? 'active' : '' }}">
                    <a href="{{ Route::has('indian-incomplete-delegates') ? route('indian-incomplete-delegates') : '#' }}" class="menu-link">
                        <div>Incomplete Registration</div>
                    </a>
                </li>
                <li class="menu-item {{ request()->routeIs('admin.cme-delegates') ? 'active' : '' }}">
                    <a href="{{ Route::has('admin.cme-delegates') ? route('admin.cme-delegates') : '#' }}" class="menu-link">
                        <div>Pre-Conference Workshop</div>
                    </a>
                </li>
                <li class="menu-item {{ request()->routeIs('international-rejected-delegates') ? 'active' : '' }}">
                    <a href="{{ Route::has('international-rejected-delegates') ? route('international-rejected-delegates') : '#' }}" class="menu-link">
                        <div>Rejected Registrations</div>
                    </a>
                </li>
                <li class="menu-item {{ request()->routeIs('international-reverted-delegates') ? 'active' : '' }}">
                    <a href="{{ Route::has('international-reverted-delegates') ? route('international-reverted-delegates') : '#' }}" class="menu-link">
                        <div>Reverted Registrations</div>
                    </a>
                </li>
                <li class="menu-item {{ request()->routeIs('deleted-delegates') ? 'active' : '' }}">
                    <a href="{{ Route::has('deleted-delegates') ? route('deleted-delegates') : '#' }}" class="menu-link">
                        <div>Deleted Registrations</div>
                    </a>
                </li>
            </ul>
        </li>

        <!-- Scientific & Abstracts Dropdown -->
        @php
            $isAbstractActive = request()->routeIs('admin.abstracts.*');
        @endphp
        <li class="menu-item {{ $isAbstractActive ? 'active open' : '' }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-file-find" style="color: #FBBF24 !important;"></i>
                <div>Scientific &amp; Abstracts</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item {{ request()->routeIs('admin.abstracts.*') ? 'active' : '' }}">
                    <a href="{{ Route::has('admin.abstracts.index') ? route('admin.abstracts.index') : '#' }}" class="menu-link">
                        <div>Abstract Submissions</div>
                    </a>
                </li>
            </ul>
        </li>
    @endif

    @if (!optional(auth('admin')->user())->isModerator())
        <!-- Transactions Dropdown -->
        @php
            $isTxActive = request()->routeIs('pending-payments') || request()->routeIs('paid-payments') || request()->routeIs('failed-payments');
        @endphp
        <li class="menu-item {{ $isTxActive ? 'active open' : '' }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-credit-card" style="color: #F472B6 !important;"></i>
                <div>Transactions</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item {{ request()->routeIs('pending-payments') ? 'active' : '' }}">
                    <a href="{{ Route::has('pending-payments') ? route('pending-payments') : '#' }}" class="menu-link">
                        <div>Pending Payments</div>
                    </a>
                </li>
                <li class="menu-item {{ request()->routeIs('paid-payments') ? 'active' : '' }}">
                    <a href="{{ Route::has('paid-payments') ? route('paid-payments') : '#' }}" class="menu-link">
                        <div>Successful Payments</div>
                    </a>
                </li>
                <li class="menu-item {{ request()->routeIs('failed-payments') ? 'active' : '' }}">
                    <a href="{{ Route::has('failed-payments') ? route('failed-payments') : '#' }}" class="menu-link">
                        <div>Failed Payments</div>
                    </a>
                </li>
            </ul>
        </li>
    @endif
</ul>

<!-- Sidebar Bottom User Profile & Logout Section -->
<div class="px-3 pb-3 mt-auto">
    <div class="sidebar-user-footer p-2.5 d-flex align-items-center justify-content-between gap-2 shadow-xs">
        <div class="d-flex align-items-center gap-2 overflow-hidden">
            <div class="avatar avatar-online flex-shrink-0" style="width: 36px; height: 36px;">
                <img src="{{ !empty(auth()->user()->adminDetails->profile_pic) ? asset(auth()->user()->adminDetails->profile_pic) : asset('assets/admin/assets/img/logo.png') }}"
                    alt="Admin" class="w-100 h-100 rounded-circle border border-2 border-primary" style="object-fit: cover;" />
            </div>
            <div class="overflow-hidden">
                <span class="fw-bold text-white d-block text-truncate" style="font-size: 0.8rem; line-height: 1.2;">
                    {{ auth('admin')->user()->full_name ?? auth('admin')->user()->username ?? 'IPHACON Admin' }}
                </span>
                <small class="text-info fw-bold" style="font-size: 0.65rem; letter-spacing: 0.3px;">
                    {{ strtoupper(auth('admin')->user()->role ?? 'SUPERADMIN') }}
                </small>
            </div>
        </div>
        <form method="POST" action="{{ route('admin.logout') }}" class="m-0 p-0">
            @csrf
            <button type="submit" class="btn btn-sm btn-outline-danger px-2.5 py-1 fw-bold rounded-pill shadow-xs" title="Log Out" style="font-size: 0.75rem; white-space: nowrap;">
                Logout
            </button>
        </form>
    </div>
</div>