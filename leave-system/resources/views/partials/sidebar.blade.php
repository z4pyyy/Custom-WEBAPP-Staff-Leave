<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{ url('/dashboard') }}" class="brand-link text-center">
        <span class="brand-text font-weight-light">Welcome, Admin</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column"
                data-widget="treeview"
                role="menu"
                data-accordion="false">
                @auth
                    @php
                        $role = Auth::user()->role_id;
                        $segment = request()->segment(1);
                    @endphp

                    @if($role === 1)
                        <li class="nav-item has-treeview {{ in_array($segment, ['account', 'admin']) ? 'menu-open' : '' }}">
                            <a href="#" class="nav-link {{ in_array($segment, ['account', 'admin']) ? 'active' : '' }}">
                                <i class="nav-icon fas fa-users-cog"></i>
                                <p>
                                    User Management
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('admin.users') }}" class="nav-link {{ request()->is('admin/users') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Manage Users</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ url('/account/settings') }}" class="nav-link {{ request()->is('account/settings') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Account Settings</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    @endif

                    @if(in_array($role, [1, 2, 3]))
                        <li class="nav-item has-treeview {{ in_array($segment, ['leave', 'notifications']) ? 'menu-open' : '' }}">
                            <a href="#" class="nav-link {{ in_array($segment, ['leave', 'notifications']) ? 'active' : '' }}">
                                <i class="nav-icon fas fa-calendar-alt"></i>
                                <p>
                                    Leave Management
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ url('/leave/apply') }}" class="nav-link {{ request()->is('leave/apply') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Apply for Leave</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ url('/leave/history') }}" class="nav-link {{ request()->is('leave/history') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Leave History</p>
                                    </a>
                                </li>
                                @if(in_array($role, [1, 2]))
                                    <li class="nav-item">
                                        <a href="{{ route('leave.manage') }}" class="nav-link {{ request()->is('leave/manage') ? 'active' : '' }}">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Manage Leave</p>
                                        </a>
                                    </li>
                                @endif
                                @if(in_array($role, [1, 2]))
                                    <li class="nav-item">
                                        <a href="{{ route('balance.index') }}" class="nav-link {{ request()->is('balance') ? 'active' : '' }}">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Annual Leave Balance</p>
                                        </a>
                                    </li>
                                @endif
                                @if(in_array($role, [1,2]))
<li class="nav-item">
    <a href="{{ route('balance.audit') }}" class="nav-link {{ request()->is('balance/audit') ? 'active' : '' }}">
        <i class="far fa-circle nav-icon"></i>
        <p>Audit Trail</p>
    </a>
</li>
@endif

                                <li class="nav-item">
                                    <a href="{{ url('/leave/calendar') }}" class="nav-link {{ request()->is('leave/calendar') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Leave Calendar</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    @endif

                    @if($role === 1)
                        <li class="nav-item has-treeview {{ in_array($segment, ['reports', 'holidays', 'system', 'page-permission']) ? 'menu-open' : '' }}">
                            <a href="#" class="nav-link {{ in_array($segment, ['reports', 'holidays', 'system', 'page-permission']) ? 'active' : '' }}">
                                <i class="nav-icon fas fa-cogs"></i>
                                <p>
                                    Reports & System
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ url('/leave/report') }}" class="nav-link {{ request()->is('reports') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Generate Reports</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ url('/holidays') }}" class="nav-link {{ request()->is('holidays') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Public Holidays</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ url('/system/settings') }}" class="nav-link {{ request()->is('system/settings') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>System Settings</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('admin.page-permission') }}" class="nav-link {{ request()->is('page-permission') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Page Permissions</p>
                                    </a>
                                </li>
                            </li>
                        </ul>
                    @endif

                    <li class="nav-item mt-3">
                        <a href="{{ route('logout') }}" class="nav-link"
                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="nav-icon fas fa-sign-out-alt"></i>
                            <p>Logout</p>
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </li>
                @endauth
            </ul>
        </nav>
    </div>
</aside>
