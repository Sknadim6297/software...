<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'BDM Panel') - Konnectix</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Custom Styles -->
    <style>
        :root {
            --bdm-primary: #7c3aed;
            --bdm-secondary: #a855f7;
            --bdm-dark: #1e1b4b;
            --bdm-light: #f5f3ff;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
        }
        
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: 260px;
            background: linear-gradient(180deg, var(--bdm-dark) 0%, #312e81 100%);
            color: white;
            overflow-y: auto;
            z-index: 1000;
        }
        
        .sidebar-header {
            padding: 1.5rem 1rem;
            background: rgba(0, 0, 0, 0.2);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .sidebar-header h4 {
            margin: 0;
            font-size: 1.25rem;
            font-weight: 700;
            color: white;
        }
        
        .sidebar-header .bdm-info {
            font-size: 0.875rem;
            color: rgba(255, 255, 255, 0.8);
            margin-top: 0.25rem;
        }
        
        .nav-menu {
            padding: 1rem 0;
        }
        
        .nav-item {
            margin-bottom: 0.25rem;
        }
        
        .nav-link {
            display: flex;
            align-items: center;
            padding: 0.75rem 1.5rem;
            color: rgba(255, 255, 255, 0.85);
            text-decoration: none;
            transition: all 0.3s ease;
        }
        
        .nav-link:hover {
            background: rgba(255, 255, 255, 0.1);
            color: white;
        }
        
        .nav-link.active {
            background: var(--bdm-primary);
            color: white;
            border-left: 3px solid var(--bdm-secondary);
        }
        
        .nav-link i {
            width: 24px;
            margin-right: 0.75rem;
            font-size: 1.1rem;
        }
        
        .main-content {
            margin-left: 260px;
            padding: 2rem;
            min-height: 100vh;
        }
        
        .top-bar {
            background: white;
            padding: 1rem 1.5rem;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            margin-bottom: 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .page-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: #333;
            margin: 0;
        }
        
        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            margin-bottom: 1.5rem;
        }
        
        .card-header {
            background: linear-gradient(135deg, var(--bdm-primary) 0%, var(--bdm-secondary) 100%);
            color: white;
            border-radius: 10px 10px 0 0 !important;
            padding: 1rem 1.5rem;
            font-weight: 600;
        }
        
        .btn-primary {
            background: var(--bdm-primary);
            border-color: var(--bdm-primary);
        }
        
        .btn-primary:hover {
            background: var(--bdm-secondary);
            border-color: var(--bdm-secondary);
        }
        
        .badge-warning {
            background: #fbbf24;
            color: #78350f;
        }
        
        .badge-success {
            background: #34d399;
            color: #064e3b;
        }
        
        .badge-danger {
            background: #f87171;
            color: #7f1d1d;
        }
        
        .notification-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            background: #ef4444;
            color: white;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            font-size: 0.75rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }
    </style>
    
    @stack('styles')
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-header">
            <h4><i class="fas fa-user-tie"></i> BDM Panel</h4>
            @auth
                @if(Auth::user()->bdm)
                    <div class="bdm-info">
                        {{ Auth::user()->bdm->name }}<br>
                        <small>{{ Auth::user()->bdm->employee_code }}</small>
                    </div>
                @endif
            @endauth
        </div>
        
        <ul class="nav-menu">
            <li class="nav-item">
                <a href="{{ route('bdm.dashboard') }}" class="nav-link {{ request()->routeIs('bdm.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-home"></i> Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('bdm.profile') }}" class="nav-link {{ request()->routeIs('bdm.profile*') ? 'active' : '' }}">
                    <i class="fas fa-user"></i> My Profile
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('bdm.documents') }}" class="nav-link {{ request()->routeIs('bdm.documents*') ? 'active' : '' }}">
                    <i class="fas fa-file-alt"></i> Documents
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('bdm.salary') }}" class="nav-link {{ request()->routeIs('bdm.salary*') ? 'active' : '' }}">
                    <i class="fas fa-money-bill-wave"></i> Salary & Remuneration
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('bdm.leaves') }}" class="nav-link {{ request()->routeIs('bdm.leaves*') ? 'active' : '' }}">
                    <i class="fas fa-calendar-check"></i> Leave Management
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('bdm.targets') }}" class="nav-link {{ request()->routeIs('bdm.targets*') ? 'active' : '' }}">
                    <i class="fas fa-bullseye"></i> Target Management
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('bdm.notifications') }}" class="nav-link {{ request()->routeIs('bdm.notifications*') ? 'active' : '' }}" style="position: relative;">
                    <i class="fas fa-bell"></i> Notifications
                    @auth
                        @if(Auth::user()->bdm && Auth::user()->bdm->notifications()->where('is_read', false)->count() > 0)
                            <span class="notification-badge">{{ Auth::user()->bdm->notifications()->where('is_read', false)->count() }}</span>
                        @endif
                    @endauth
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('logout') }}" class="nav-link" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </li>
        </ul>
    </div>
    
    <!-- Main Content -->
    <div class="main-content">
        <div class="top-bar">
            <h1 class="page-title">@yield('page-title', 'Dashboard')</h1>
            <div>
                @auth
                    @if(Auth::user()->bdm)
                        @if(Auth::user()->bdm->status === 'warned')
                            <span class="badge badge-warning">
                                <i class="fas fa-exclamation-triangle"></i> Warning {{ Auth::user()->bdm->warning_count }}/3
                            </span>
                        @elseif(Auth::user()->bdm->status === 'active')
                            <span class="badge badge-success">
                                <i class="fas fa-check-circle"></i> Active
                            </span>
                        @endif
                    @endif
                @endauth
            </div>
        </div>
        
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        
        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        
        @yield('content')
    </div>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    @stack('scripts')
</body>
</html>
