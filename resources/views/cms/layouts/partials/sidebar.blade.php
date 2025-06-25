<div class="sidebar" data-background-color="dark">
<div class="sidebar-logo">
    <!-- Logo Header -->
    <div class="logo-header" data-background-color="dark">
    <a href="{{ route('dashboard.index') }}" class="logo">
        <span class="navbar-brand" style="color: aliceblue !important">CykleCMS</span>
        {{-- <img
        src="{{ asset('assets/cms/img/kaiadmin/logo_light.svg') }}"
        alt="navbar brand"
        class="navbar-brand"
        height="20"
        /> --}}
    </a>
    <div class="nav-toggle">
        <button class="btn btn-toggle toggle-sidebar">
        <i class="gg-menu-right"></i>
        </button>
        <button class="btn btn-toggle sidenav-toggler">
        <i class="gg-menu-left"></i>
        </button>
    </div>
    <button class="topbar-toggler more">
        <i class="gg-more-vertical-alt"></i>
    </button>
    </div>
    <!-- End Logo Header -->
</div>
<div class="sidebar-wrapper scrollbar scrollbar-inner">
    <div class="sidebar-content">
    <ul class="nav nav-secondary">
        <li class="nav-item {{ request()->routeIs('dashboard.index') ? 'active' : '' }}">
        <a
            {{-- data-bs-toggle="collapse" --}}
            href="{{ route('dashboard.index') }}"
            class="collapsed"
            aria-expanded="false"
        >
            <i class="fas fa-home"></i>
            <p>Dashboard</p>
        </a>
        </li>
        <li class="nav-section">
            <span class="sidebar-mini-icon">
                <i class="fa fa-ellipsis-h"></i>
            </span>
            <h4 class="text-section">Modules</h4>
        </li>
        {{-- Users --}}
        @canany(['view users', 'add user', 'view roles', 'add role', 'view permissions', 'add permission'])
        <li class="nav-item {{ (request()->is('users*') || request()->is('roles*') || request()->is('permissions*')) ? 'active' : '' }}">
            <a data-bs-toggle="collapse" href="#users" aria-expanded="{{ (request()->is('users*') || request()->is('roles*') || request()->is('permissions*')) ? 'true' : 'false' }}">
                <i class="fas fa-user"></i>
                <p>Users</p>
                <span class="caret"></span>
            </a>
            <div class="collapse {{ (request()->is('users*') || request()->is('roles*') || request()->is('permissions*')) ? 'show' : '' }}" id="users">
                <ul class="nav nav-collapse">
                    @can('view users')
                    <li class="{{ request()->routeIs('users.index') ? 'active' : '' }}">
                        <a href="{{ route('users.index') }}">
                        <span class="sub-item">View Users</span>
                        </a>
                    </li>
                    @endcan
                    @can('add user')
                    <li class="{{ request()->routeIs('users.create') ? 'active' : '' }}">
                        <a href="{{ route('users.create') }}">
                        <span class="sub-item">Add User</span>
                        </a>
                    </li>
                    @endcan
                    @can('view roles')
                    <li class="{{ request()->routeIs('roles.index') ? 'active' : '' }}">
                        <a href="{{ route('roles.index') }}">
                        <span class="sub-item">View Roles</span>
                        </a>
                    </li>
                    @endcan
                    @can('add role')
                    <li class="{{ request()->routeIs('roles.create') ? 'active' : '' }}">
                        <a href="{{ route('roles.create') }}">
                        <span class="sub-item">Add Role</span>
                        </a>
                    </li>
                    @endcan
                    @can('view permissions')
                    <li class="{{ request()->routeIs('permissions.index') ? 'active' : '' }}">
                        <a href="{{ route('permissions.index') }}">
                        <span class="sub-item">View Permissions</span>
                        </a>
                    </li>
                    @endcan
                    @can('add permission')
                    <li class="{{ request()->routeIs('permissions.create') ? 'active' : '' }}">
                        <a href="{{ route('permissions.create') }}">
                        <span class="sub-item">Add Permissions</span>
                        </a>
                    </li>
                    @endcan
                </ul>
            </div>
        </li>
        @endcanany
        {{-- Settings --}}
        {{-- <li class="nav-item">
            <a data-bs-toggle="collapse" href="#settings">
                <i class="fas fa-cog"></i>
                <p>Settings</p>
                <span class="caret"></span>
            </a>
            <div class="collapse" id="settings">
                <ul class="nav nav-collapse">
                    <li>
                        <a href="#">
                        <span class="sub-item">Homepage Settings</span>
                        </a>
                    </li>
                    <li>
                        <a href="#">
                        <span class="sub-item">SEO Settings</span>
                        </a>
                    </li>
                </ul>
            </div>
        </li> --}}
    </ul>
    </div>
</div>
</div>