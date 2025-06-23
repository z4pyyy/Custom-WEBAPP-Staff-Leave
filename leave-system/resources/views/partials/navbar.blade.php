<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button">
                <i class="fas fa-bars"></i>
            </a>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
            <a href="{{ url('/dashboard') }}" class="nav-link">Dashboard</a>
        </li>
    </ul>

    <!-- Right navbar icons -->
    <ul class="navbar-nav ml-auto align-items-center">
        
        <!-- Notifications -->
        @php
            $notifications = auth()->user()->unreadNotifications->take(5);
        @endphp
        <li class="nav-item dropdown">
            <a class="nav-link" data-toggle="dropdown" href="#">
                <i class="far fa-bell"></i>
                @if($notifications->count() > 0)
                    <span class="badge badge-warning navbar-badge">{{ $notifications->count() }}</span>
                @endif
            </a>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                <span class="dropdown-header">{{ $notifications->count() }} Notifications</span>
                <div class="dropdown-divider"></div>

                @forelse($notifications as $notification)
                    <a href="{{ url('/notifications/read/' . $notification->id) }}" class="dropdown-item">
                        <i class="fas fa-envelope mr-2"></i>
                        {{ $notification->data['message'] ?? 'New notification' }}
                        <span class="float-right text-muted text-sm">{{ $notification->created_at->diffForHumans() }}</span>
                    </a>
                    <div class="dropdown-divider"></div>
                @empty
                    <span class="dropdown-item text-muted">No new notifications</span>
                @endforelse

                <a href="{{ url('/notifications') }}" class="dropdown-item dropdown-footer">See All Notifications</a>
            </div>
        </li>

        <!-- Dark mode toggle -->
        <li class="nav-item">
            <a href="#" id="dark-mode-toggle" class="nav-link" role="button">
                <i class="fas fa-moon"></i>
            </a>
        </li>

        <!-- Profile dropdown -->
        @if(Auth::check())
            <li class="nav-item dropdown">  
                <a id="navbarDropdown" class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button"
                   data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <img src="{{ asset('images/Totoro.jpg') }}" class="rounded-circle" width="30" height="30" alt="User Image">
                    <span class="ml-2">{{ Auth::user()->name }}</span>
                </a>

                <div class="dropdown-menu dropdown-menu-right">
                    <a class="dropdown-item" href="#">
                        <i class="fas fa-user mr-2"></i> Profile
                    </a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="{{ route('logout') }}"
                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="fas fa-sign-out-alt mr-2"></i> Logout
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
                </div>
            </li>
        @endif
    </ul>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const toggle = document.getElementById('dark-mode-toggle');
        toggle.addEventListener('click', function (e) {
            e.preventDefault();

            // 切换 body 的 dark-mode class
            document.body.classList.toggle('dark-mode');

            // 如果你想保存状态到 localStorage（页面刷新后仍记得）
            const isDark = document.body.classList.contains('dark-mode');
            localStorage.setItem('darkModeEnabled', isDark);
        });

        // 初始读取状态
        if (localStorage.getItem('darkModeEnabled') === 'true') {
            document.body.classList.add('dark-mode');
        }
    });
</script>


</nav>
