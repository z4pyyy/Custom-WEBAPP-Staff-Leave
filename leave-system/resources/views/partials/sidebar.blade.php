<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{ url('/dashboard') }}" class="brand-link">
        <span class="brand-text font-weight-light">Leave System</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

                @auth
                    @php
                        $role = Auth::user()->role_id;
                    @endphp

                    {{-- Superadmin --}}
                    @if($role === 1)
                        <li class="nav-header">USER MANAGEMENT</li>
                        <li class="nav-item">
                            <a href="{{ route('admin.users') }}" class="nav-link">
                                <i class="nav-icon fas fa-users-cog"></i>
                                <p>Manage Users</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ url('/account/settings') }}" class="nav-link">
                                <i class="nav-icon fas fa-user-cog"></i>
                                <p>Account Settings</p>
                            </a>
                        </li>
                    @endif

                    @if(in_array($role, [1, 2, 3]))
                        <li class="nav-header">LEAVE MANAGEMENT</li>
                        <li class="nav-item">
                            <a href="{{ url('/leave/apply') }}" class="nav-link">
                                <i class="nav-icon fas fa-calendar-plus"></i>
                                <p>Apply for Leave</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ url('/leave/history') }}" class="nav-link">
                                <i class="nav-icon fas fa-history"></i>
                                <p>Leave History</p>
                            </a>
                        </li>
                    @endif

                    @if(in_array($role, [1, 2]))
                        <li class="nav-item">
                            <a href="{{ url('/leave/approve') }}" class="nav-link">
                                <i class="nav-icon fas fa-check-circle"></i>
                                <p>Approve Leave</p>
                            </a>
                        </li>
                    @endif

                    @if(in_array($role, [1, 2, 3]))
                        <li class="nav-item">
                            <a href="{{ url('/leave/calendar') }}" class="nav-link">
                                <i class="nav-icon fas fa-calendar-alt"></i>
                                <p>Leave Calendar</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ url('/notifications') }}" class="nav-link">
                                <i class="nav-icon fas fa-bell"></i>
                                <p>Notifications</p>
                            </a>
                        </li>
                    @endif

                    @if($role === 1)
                        <li class="nav-header">REPORTS & SYSTEM</li>
                        <li class="nav-item">
                            <a href="{{ url('/reports') }}" class="nav-link">
                                <i class="nav-icon fas fa-file-alt"></i>
                                <p>Generate Reports</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ url('/holidays') }}" class="nav-link">
                                <i class="nav-icon fas fa-umbrella-beach"></i>
                                <p>Public Holidays</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ url('/system/settings') }}" class="nav-link">
                                <i class="nav-icon fas fa-cogs"></i>
                                <p>System Settings</p>
                            </a>
                        </li>
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
