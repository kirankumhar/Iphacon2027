<ul class="menu-inner py-2">
    @if (auth('admin')->user()->role == 'superadmin')
        <li class="menu-item {{ request()->routeIs('superadmin.dashboard') ? 'active' : '' }}">
            <a href="{{ route('superadmin.dashboard') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-shield-quarter"></i>
                <div data-i18n="Superadmin Dashboard">Superadmin Dashboard</div>
            </a>
        </li>

        <li class="menu-header small text-uppercase">
            <span class="menu-header-text">Admin &amp; Roles</span>
        </li>

        <li class="menu-item {{ request()->routeIs('admins.create') ? 'active' : '' }}">
            <a href="{{ route('admins.create') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-user-plus"></i>
                <div data-i18n="Create Admin">Create Admin</div>
            </a>
        </li>

        <li class="menu-item {{ request()->routeIs('admins.view-admins') ? 'active' : '' }}">
            <a href="{{ route('admins.view-admins') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-group"></i>
                <div data-i18n="View Admins">View Admins</div>
            </a>
        </li>
    @endif

    <!-- Main Navigation Header -->
    <li class="menu-header small text-uppercase">
        <span class="menu-header-text">Overview</span>
    </li>

    <li class="menu-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
        <a href="{{ route('admin.dashboard') }}" class="menu-link">
            <i class="menu-icon tf-icons bx bx-grid-alt"></i>
            <div data-i18n="Dashboard">Dashboard</div>
        </a>
    </li>

    <!-- International Delegates Header -->
    <li class="menu-header small text-uppercase">
        <span class="menu-header-text">International Delegates</span>
    </li>

    <li class="menu-item {{ request()->routeIs('international-payment-submitted-delegates') ? 'active' : '' }}">
        <a href="{{ route('international-payment-submitted-delegates') }}" class="menu-link">
            <i class="menu-icon tf-icons bx bx-credit-card-front"></i>
            <div data-i18n="Payment Submitted">Payment Submitted</div>
        </a>
    </li>

    <li class="menu-item {{ request()->routeIs('international-approved-delegates') ? 'active' : '' }}">
        <a href="{{ route('international-approved-delegates') }}" class="menu-link">
            <i class="menu-icon tf-icons bx bx-user-check"></i>
            <div data-i18n="Approved Delegates">Approved Delegates</div>
        </a>
    </li>

    <li class="menu-item {{ request()->routeIs('international-rejected-delegates') ? 'active' : '' }}">
        <a href="{{ route('international-rejected-delegates') }}" class="menu-link">
            <i class="menu-icon tf-icons bx bx-user-x"></i>
            <div data-i18n="Rejected Delegates">Rejected Delegates</div>
        </a>
    </li>

    <li class="menu-item {{ request()->routeIs('international-reverted-delegates') ? 'active' : '' }}">
        <a href="{{ route('international-reverted-delegates') }}" class="menu-link">
            <i class="menu-icon tf-icons bx bx-undo"></i>
            <div data-i18n="Reverted Delegates">Reverted Delegates</div>
        </a>
    </li>

    <!-- Indian Delegates Header -->
    <li class="menu-header small text-uppercase">
        <span class="menu-header-text">Indian Delegates</span>
    </li>

    <li class="menu-item {{ request()->routeIs('indian-approved-delegates') ? 'active' : '' }}">
        <a href="{{ route('indian-approved-delegates') }}" class="menu-link">
            <i class="menu-icon tf-icons bx bx-badge-check"></i>
            <div data-i18n="Paid Registrations">Paid Registrations</div>
        </a>
    </li>

    <!-- Trash Header -->
    <li class="menu-header small text-uppercase">
        <span class="menu-header-text">Trash &amp; Archives</span>
    </li>

    <li class="menu-item {{ request()->routeIs('deleted-delegates') || request()->routeIs('deleted-registration') ? 'active' : '' }}">
        <a href="{{ route('deleted-delegates') }}" class="menu-link">
            <i class="menu-icon tf-icons bx bx-trash-alt"></i>
            <div data-i18n="Deleted Applications">Deleted Applications</div>
        </a>
    </li>
</ul>