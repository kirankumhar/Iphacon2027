<ul class="menu-inner py-1">
    @if (auth('admin')->user()->role == 'superadmin')
    <li class="menu-item {{ request()->routeIs('superadmin.dashboard') ? 'active' : '' }}">
        <a href="{{ route('superadmin.dashboard') }}" class="menu-link">
            <i class="menu-icon tf-icons bx bx-home-circle"></i>
            <div data-i18n="Dashboards">Dashboards</div>
        </a>
    </li>

    <li class="menu-header small text-uppercase">
        <span class="menu-header-text">Admin &amp; Roles</span>
    </li>

    <li class="menu-item {{ request()->routeIs('admins.create') ? 'active' : '' }}">
        <a href="{{ route('admins.create') }}" class="menu-link">
            <i class="menu-icon tf-icons bx bx-lock-open-alt"></i>
            <div data-i18n="CR">Create Admin</div>
            <div class="badge bg-label-danger fs-tiny rounded-pill ms-auto">New</div>
        </a>
    </li>

    <li class="menu-item {{ request()->routeIs('admins.view-admins') ? 'active' : '' }}">
        <a href="{{ route('admins.view-admins') }}" class="menu-link">
            <i class="menu-icon tf-icons bx bx-lock-open-alt"></i>
            <div data-i18n="CR">View Admin</div>
            <div class="badge bg-label-danger fs-tiny rounded-pill ms-auto">New</div>
        </a>
    </li>
    @endif

    <li class="menu-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
        <a href="{{ route('admin.dashboard') }}" class="menu-link">
            <i class="menu-icon tf-icons bx bx-home-circle"></i>
            <div data-i18n="Dashboards">Dashboards</div>
        </a>
    </li>

    <li class="menu-header small text-uppercase">
        <span style="color: #0ba3fc !important;">International Delegates</span>
    </li>

    <!-- <li class="menu-item {{ request()->routeIs('international-payment-submitted-delegates') ? 'active' : '' }}">
        <a href="{{ route('international-payment-submitted-delegates') }}" class="menu-link">
            <i class="menu-icon tf-icons bx bx-home-circle"></i>
            <div data-i18n="international-payment-submitted-delegates">Payment Submitted</div>
        </a>
    </li>

    <li class="menu-item {{ request()->routeIs('international-approved-delegates') ? 'active' : '' }}">
        <a href="{{ route('international-approved-delegates') }}" class="menu-link">
            <i class="menu-icon tf-icons bx bx-home-circle"></i>
            <div data-i18n="international-approved-delegates">Approved Delegates</div>
        </a>
    </li>

    <li class="menu-item {{ request()->routeIs('international-rejected-delegates') ? 'active' : '' }}">
        <a href="{{ route('international-rejected-delegates') }}" class="menu-link">
            <i class="menu-icon tf-icons bx bx-home-circle"></i>
            <div data-i18n="international-rejected-delegates">Rejected Delegates</div>
        </a>
    </li>

    <li class="menu-item {{ request()->routeIs('international-reverted-delegates') ? 'active' : '' }}">
        <a href="{{ route('international-reverted-delegates') }}" class="menu-link">
            <i class="menu-icon tf-icons bx bx-home-circle"></i>
            <div data-i18n="international-reverted-delegates">Reverted Delegates</div>
        </a>
    </li> -->

    <li class="menu-item {{ request()->routeIs('international-payment-submitted-delegates') ? 'active' : '' }}">
        <a href="{{ route('international-payment-submitted-delegates') }}" class="menu-link">
            <i class="menu-icon tf-icons bx bx-credit-card-front"></i>
            <div data-i18n="international-payment-submitted-delegates">Payment Submitted</div>
        </a>
    </li>

    <li class="menu-item {{ request()->routeIs('international-approved-delegates') ? 'active' : '' }}">
        <a href="{{ route('international-approved-delegates') }}" class="menu-link">
            <i class="menu-icon tf-icons bx bx-user-check"></i>
            <div data-i18n="international-approved-delegates">Approved Delegates</div>
        </a>
    </li>

    <li class="menu-item {{ request()->routeIs('international-rejected-delegates') ? 'active' : '' }}">
        <a href="{{ route('international-rejected-delegates') }}" class="menu-link">
            <i class="menu-icon tf-icons bx bx-user-x"></i>
            <div data-i18n="international-rejected-delegates">Rejected Delegates</div>
        </a>
    </li>

    <li class="menu-item {{ request()->routeIs('international-reverted-delegates') ? 'active' : '' }}">
        <a href="{{ route('international-reverted-delegates') }}" class="menu-link">
            <i class="menu-icon tf-icons bx bx-undo"></i>
            <div data-i18n="international-reverted-delegates">Reverted Delegates</div>
        </a>
    </li>

    <li class="menu-header small text-uppercase">
        <span style="color: #0ba3fc !important;">Indian Delegates</span>
    </li>

    <li class="menu-item {{ request()->routeIs('indian-approved-delegates') ? 'active' : '' }}">
    <a href="{{ route('indian-approved-delegates') }}" class="menu-link">
        <i class="menu-icon tf-icons bx bx-money"></i>
        <div data-i18n="indian-approved-delegates">Paid Registrations</div>
    </a>
</li>

    <li class="menu-header small text-uppercase">
        <span style="color: #0ba3fc !important;">Deleted Registration</span>
    </li>
  <li class="menu-item {{ request()->routeIs('deleted-registration') ? 'active' : '' }}">
    <a href="{{ route('deleted-delegates') }}" class="menu-link">
        <i class="menu-icon tf-icons bx bx-trash"></i>
        <div data-i18n="Deleted Application">Deleted Application</div>
    </a>
</li>

</ul>