<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <div class="container-fluid">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button">
                <i class="fas fa-bars"></i>
            </a>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
            <a href="{{ url('/dashboard') }}" class="nav-link d-flex align-items-center">
                <img src="{{ asset('images/homslivinglogo.jpg') }}" alt="Logo" width="60" height="60" class="mr-2">
                <span class="ml-2 font-weight-bold h5">Dashboard</span>
            </a>
        </li>
    </ul>

    <!-- Right navbar icons -->
    <ul class="navbar-nav ml-auto align-items-center">
        
        <!-- Notifications -->
        @php
            $notifications = auth()->user()->unreadNotifications->take(5);
        @endphp
        <li class="nav-item dropdown">
        <a class="nav-link" href="#" data-toggle="dropdown" aria-expanded="false">
            <i class="far fa-bell"></i>
            @if($notifications->count())
                <span class="badge badge-warning navbar-badge">{{ $notifications->count() }}</span>
            @endif
        </a>

        <!-- 移除 data-bs-popper / data-toggle="dropdown" / aria-expanded -->
        <ul class="dropdown-menu notification-dropdown">
            <span class="dropdown-header">{{ $notifications->count() }} Notifications</span>
           @foreach(auth()->user()->unreadNotifications as $notification)
                <a href="{{ route('notifications.read', $notification->id) }}" class="dropdown-item notification-item">
                    <div class="notification-text">
                        <i class="fas fa-envelope mr-2"></i>
                        <span class="message">{{ $notification->data['message'] ?? 'New Notification' }}</span>
                    </div>
                    <div class="notification-time">
                        {{ $notification->created_at->diffForHumans() }}
                    </div>
                </a>
            @endforeach
            <div class="dropdown-divider"></div>
            <a href="{{ route('notifications.markAllRead') }}" class="dropdown-footer text-danger">Mark All as Read</a>

            <a href="{{ route('notifications.index') }}" class="dropdown-footer">See All Notifications</a>
        </ul>
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
                    <img 
                        src="{{ Auth::user()->avatar ? asset('storage/' . Auth::user()->avatar) : 'https://via.placeholder.com/40?text=Avatar' }}" 
                        alt="User Avatar" 
                        class="rounded-circle" 
                        width="40">
                    <span class="ml-2">{{ Auth::user()->name }}</span>
                </a>

                <div class="dropdown-menu dropdown-menu-right">
                    <a class="dropdown-item" href="{{ route('account.settings') }}">
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
</div>
</nav>
