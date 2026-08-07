<style>
    /* Executive Sidebar Theme */
    #layout-menu.bg-menu-theme {
        background: linear-gradient(180deg, #f4f7fc 0%, #eef3f9 100%) !important;
        box-shadow: 0 4px 20px rgba(1, 48, 105, 0.06);
        border-right: 1px solid rgba(1, 48, 105, 0.1);
        display: flex;
        flex-direction: column;
    }
    .menu-inner .menu-header {
        position: relative;
        padding: 8px 16px 3px 16px !important;
        margin-top: 4px;
    }
    .menu-inner .menu-header-text {
        font-size: 0.65rem !important;
        font-weight: 800 !important;
        letter-spacing: 0.8px !important;
        text-transform: uppercase;
        color: #013069 !important;
        background: #eef4fc;
        padding: 2px 8px;
        border-radius: 20px;
        display: inline-block;
    }
    .menu-inner .menu-item .menu-link {
        margin: 1.5px 8px !important;
        padding: 6.5px 12px !important;
        border-radius: 8px !important;
        color: #475569 !important;
        font-weight: 600 !important;
        font-size: 0.81rem !important;
        transition: all 0.2s ease-in-out;
    }
    .menu-inner .menu-item .menu-link:hover {
        background-color: rgba(1, 48, 105, 0.06) !important;
        color: #013069 !important;
        transform: translateX(2px);
    }
    .menu-inner .menu-item.active .menu-link {
        background: linear-gradient(135deg, #013069 0%, #0d47a1 100%) !important;
        color: #ffffff !important;
        box-shadow: 0 3px 10px rgba(1, 48, 105, 0.22) !important;
    }
    .menu-inner .menu-item.active .menu-link .menu-icon {
        color: #ffffff !important;
    }
    .menu-inner .menu-icon {
        font-size: 1.1rem !important;
        margin-right: 8px !important;
        color: #64748b;
        transition: all 0.2s ease;
    }
    .menu-inner .menu-item .menu-link:hover .menu-icon {
        color: #013069;
    }
    .sidebar-user-footer {
        background: #ffffff;
        border: 1px solid rgba(1, 48, 105, 0.12);
        box-shadow: 0 2px 8px rgba(1, 48, 105, 0.04);
        border-radius: 12px;
        transition: all 0.2s ease-in-out;
    }
    .sidebar-user-footer:hover {
        border-color: #013069;
        background: #ffffff;
        box-shadow: 0 4px 12px rgba(1, 48, 105, 0.08);
    }
</style>

<ul class="menu-inner py-2 flex-grow-1">
    @if (optional(auth('admin')->user())->role == 'superadmin')
    <li class="menu-item {{ request()->routeIs('superadmin.dashboard') ? 'active' : '' }}">
        <a href="{{ Route::has('superadmin.dashboard') ? route('superadmin.dashboard') : '#' }}" class="menu-link">
            <i class="menu-icon tf-icons bx bx-grid-alt"></i>
            <div>Super Dashboard</div>
        </a>
    </li>

    <li class="menu-header">
        <span class="menu-header-text">Admin &amp; Roles</span>
    </li>

    <li class="menu-item {{ request()->routeIs('admin.admins.create') || request()->routeIs('admins.create') ? 'active' : '' }}">
        <a href="{{ Route::has('admin.admins.create') ? route('admin.admins.create') : (Route::has('admins.create') ? route('admins.create') : '#') }}" class="menu-link">
            <i class="menu-icon tf-icons bx bx-user-plus"></i>
            <div>Create Admin</div>
            <span class="badge bg-label-primary rounded-pill ms-auto" style="font-size: 0.65rem;">New</span>
        </a>
    </li>

    <li class="menu-item {{ request()->routeIs('admin.admins.index') || request()->routeIs('admins.view-admins') ? 'active' : '' }}">
        <a href="{{ Route::has('admin.admins.index') ? route('admin.admins.index') : (Route::has('admins.view-admins') ? route('admins.view-admins') : '#') }}" class="menu-link">
            <i class="menu-icon tf-icons bx bx-group"></i>
            <div>View Admins</div>
        </a>
    </li>
    @endif

    <li class="menu-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
        <a href="{{ Route::has('admin.dashboard') ? route('admin.dashboard') : '#' }}" class="menu-link">
            <i class="menu-icon tf-icons bx bx-home-circle"></i>
            <div>Dashboard</div>
        </a>
    </li>

    <li class="menu-header">
        <span class="menu-header-text">Registration</span>
    </li>

    <li class="menu-item {{ request()->routeIs('submitted-delegates') ? 'active' : '' }}">
        <a href="{{ Route::has('submitted-delegates') ? route('submitted-delegates') : '#' }}" class="menu-link">
            <i class="menu-icon tf-icons bx bx-paper-plane text-warning"></i>
            <div>Submitted Delegates</div>
        </a>
    </li>

    <li class="menu-item {{ request()->routeIs('international-payment-submitted-delegates') ? 'active' : '' }}">
        <a href="{{ Route::has('international-payment-submitted-delegates') ? route('international-payment-submitted-delegates') : '#' }}" class="menu-link">
            <i class="menu-icon tf-icons bx bx-credit-card-front text-info"></i>
            <div>Foreign Payment Submitted</div>
        </a>
    </li>

    <li class="menu-item {{ request()->routeIs('indian-approved-delegates') ? 'active' : '' }}">
        <a href="{{ Route::has('indian-approved-delegates') ? route('indian-approved-delegates') : '#' }}" class="menu-link">
            <i class="menu-icon tf-icons bx bx-check-shield text-success"></i>
            <div>Registered Delegates(India)</div>
        </a>
    </li>

    <li class="menu-item {{ request()->routeIs('international-approved-delegates') ? 'active' : '' }}">
        <a href="{{ Route::has('international-approved-delegates') ? route('international-approved-delegates') : '#' }}" class="menu-link">
            <i class="menu-icon tf-icons bx bx-award text-primary"></i>
            <div>Registered Delegates(Foreign)</div>
        </a>
    </li>

    <li class="menu-item {{ request()->routeIs('indian-incomplete-delegates') ? 'active' : '' }}">
        <a href="{{ Route::has('indian-incomplete-delegates') ? route('indian-incomplete-delegates') : '#' }}" class="menu-link">
            <i class="menu-icon tf-icons bx bx-time-five text-warning"></i>
            <div>Incomplete Registration</div>
        </a>
    </li>

    <li class="menu-item {{ request()->routeIs('admin.cme-delegates') ? 'active' : '' }}">
        <a href="{{ Route::has('admin.cme-delegates') ? route('admin.cme-delegates') : '#' }}" class="menu-link">
            <i class="menu-icon tf-icons bx bx-book-reader text-info"></i>
            <div>CME Workshop Participants</div>
        </a>
    </li>

    <li class="menu-item {{ request()->routeIs('international-rejected-delegates') ? 'active' : '' }}">
        <a href="{{ Route::has('international-rejected-delegates') ? route('international-rejected-delegates') : '#' }}" class="menu-link">
            <i class="menu-icon tf-icons bx bx-x-circle text-danger"></i>
            <div>Rejected Registrations</div>
        </a>
    </li>

    <li class="menu-item {{ request()->routeIs('international-reverted-delegates') ? 'active' : '' }}">
        <a href="{{ Route::has('international-reverted-delegates') ? route('international-reverted-delegates') : '#' }}" class="menu-link">
            <i class="menu-icon tf-icons bx bx-undo text-secondary"></i>
            <div>Reverted Registrations</div>
        </a>
    </li>

    <li class="menu-item {{ request()->routeIs('deleted-delegates') ? 'active' : '' }}">
        <a href="{{ Route::has('deleted-delegates') ? route('deleted-delegates') : '#' }}" class="menu-link">
            <i class="menu-icon tf-icons bx bx-trash text-muted"></i>
            <div>Deleted Registrations</div>
        </a>
    </li>

    <li class="menu-header">
        <span class="menu-header-text">Scientific &amp; Abstracts</span>
    </li>

    <li class="menu-item {{ request()->routeIs('admin.abstracts.*') ? 'active' : '' }}">
        <a href="{{ Route::has('admin.abstracts.index') ? route('admin.abstracts.index') : '#' }}" class="menu-link">
            <i class="menu-icon tf-icons bx bx-file-find text-info"></i>
            <div>Abstract Submissions</div>
        </a>
    </li>

    <li class="menu-header">
        <span class="menu-header-text">Transactions</span>
    </li>

    <li class="menu-item {{ request()->routeIs('paid-payments') ? 'active' : '' }}">
        <a href="{{ Route::has('paid-payments') ? route('paid-payments') : '#' }}" class="menu-link">
            <i class="menu-icon tf-icons bx bx-credit-card text-success"></i>
            <div>Successful Payments</div>
        </a>
    </li>

    <li class="menu-item {{ request()->routeIs('failed-payments') ? 'active' : '' }}">
        <a href="{{ Route::has('failed-payments') ? route('failed-payments') : '#' }}" class="menu-link">
            <i class="menu-icon tf-icons bx bx-x-circle text-danger"></i>
            <div>Failed Payments</div>
        </a>
    </li>
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
                <span class="fw-bold text-dark d-block text-truncate" style="font-size: 0.8rem; line-height: 1.2;">
                    {{ auth('admin')->user()->full_name ?? auth('admin')->user()->username ?? 'IPHACON Admin' }}
                </span>
                <small class="text-primary fw-bold" style="font-size: 0.65rem; letter-spacing: 0.3px;">
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