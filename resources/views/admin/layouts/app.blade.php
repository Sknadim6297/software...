<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="csrf-token" content="{{ csrf_token() }}">
	
	<title>@yield('title', 'Admin Panel') - Konnectix</title>
    
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('template/images/favicon.png') }}">
    <link href="{{ asset('template/vendor/jqvmap/css/jqvmap.min.css') }}" rel="stylesheet">
	<link rel="stylesheet" href="{{ asset('template/vendor/chartist/css/chartist.min.css') }}">
    <link href="{{ asset('template/vendor/bootstrap-select/dist/css/bootstrap-select.min.css') }}" rel="stylesheet">
	<link href="{{ asset('template/css/style.css') }}" rel="stylesheet">
	
	<style>
		.dropdown-menu {
			position: absolute !important;
			z-index: 1050 !important;
		}
		.content-body {
			min-height: calc(100vh - 150px);
		}
		#main-wrapper {
			display: flex;
			flex-direction: column;
			min-height: 100vh;
		}
		.badge-xs {
			padding: 2px 6px;
			font-size: 10px;
		}
		/* Notification Bell Styles */
		.notification_dropdown .nav-link {
			position: relative;
			padding: 0.5rem 1rem !important;
		}
		.notification_dropdown .nav-link i {
			font-size: 24px;
			color: #333;
		}
		.notification_dropdown .badge-circle {
			position: absolute;
			top: 5px;
			right: 5px;
			min-width: 18px;
			height: 18px;
			padding: 2px 5px;
			font-size: 10px;
			line-height: 14px;
			border-radius: 50%;
		}
		.notification_dropdown .dropdown-menu {
			min-width: 350px;
			max-width: 400px;
		}
		.notification_dropdown .timeline {
			list-style: none;
			padding: 0;
			margin: 0;
		}
		.notification_dropdown .timeline li {
			padding: 10px 0;
			border-bottom: 1px solid #f0f0f0;
		}
		.notification_dropdown .timeline li:last-child {
			border-bottom: none;
		}
		.notification_dropdown .timeline-panel {
			display: flex;
			align-items: start;
			gap: 10px;
			padding: 5px;
			border-radius: 4px;
		}
		.notification_dropdown .timeline-panel.bg-light {
			background-color: #f8f9fa;
		}
		.notification_dropdown .media i {
			font-size: 20px;
		}
		.notification_dropdown .media-body h6 {
			margin-bottom: 5px;
			font-size: 13px;
			font-weight: 600;
		}
		.notification_dropdown .media-body small {
			font-size: 11px;
		}
	</style>
	
	@stack('styles')
</head>
<body>

    <!--*******************
        Preloader start
    ********************-->
    <div id="preloader">
        <div class="sk-three-bounce">
            <div class="sk-child sk-bounce1"></div>
            <div class="sk-child sk-bounce2"></div>
            <div class="sk-child sk-bounce3"></div>
        </div>
    </div>
    <!--*******************
        Preloader end
    ********************-->

    <!--**********************************
        Main wrapper start
    ***********************************-->
    <div id="main-wrapper">

        <!--**********************************
            Nav header start
        ***********************************-->
        <div class="nav-header">
            <a href="{{ route('admin.dashboard') }}" class="brand-logo">
				<img src="{{ asset('template/images/logo/logo_konnectix.webp') }}" alt="Konnectix Technologies" class="logo-konnectix" style="max-height: 40px; width: auto; object-fit: contain; margin: 10px;">
            </a>

            <div class="nav-control">
                <div class="hamburger">
                    <span class="line"></span><span class="line"></span><span class="line"></span>
                </div>
            </div>
        </div>
        <!--**********************************
            Nav header end
        ***********************************-->

        <!--**********************************
            Header start
        ***********************************-->
        <div class="header">
            <div class="header-content">
                <nav class="navbar navbar-expand">
                    <div class="collapse navbar-collapse justify-content-between">
                        <div class="header-left">
                            <div class="dashboard_bar">
                                @yield('page-title', 'Admin Dashboard')
                            </div>
                        </div>
                        <ul class="navbar-nav header-right">
                            {{-- Notifications --}}
                            <li class="nav-item dropdown notification_dropdown">
                                <a class="nav-link bell dz-theme-mode p-0" href="javascript:void(0);" role="button" data-bs-toggle="dropdown">
                                    <i class="flaticon-381-alarm-clock"></i>
                                    @php
                                        $pendingLeaves = \App\Models\BDMLeaveApplication::where('status', 'pending')->count();
                                        $totalNotifications = $pendingLeaves;
                                    @endphp
                                    @if($totalNotifications > 0)
                                        <span class="badge badge-circle badge-danger">{{ $totalNotifications }}</span>
                                    @endif
                                </a>
                                <div class="dropdown-menu dropdown-menu-end">
                                    <div id="DZ_W_Notification1" class="widget-media dz-scroll p-3" style="height:380px;">
                                        <div class="timeline-panel">
                                            <div class="media-heading mb-2">
                                                <h5 class="mb-0">Notifications</h5>
                                            </div>
                                        </div>
                                        <ul class="timeline">
                                            @php
                                                $recentLeaves = \App\Models\BDMLeaveApplication::where('status', 'pending')
                                                    ->with('bdm')
                                                    ->latest()
                                                    ->take(10)
                                                    ->get();
                                            @endphp
                                            @forelse($recentLeaves as $leave)
                                                <li>
                                                    <div class="timeline-panel">
                                                        <div class="media me-2">
                                                            <i class="flaticon-381-calendar-1 text-warning"></i>
                                                        </div>
                                                        <div class="media-body">
                                                            <h6 class="mb-1">Leave Request</h6>
                                                            <small class="d-block">{{ $leave->bdm->name ?? 'BDM' }} - {{ $leave->leave_type }}</small>
                                                            <small class="text-muted">{{ $leave->created_at->diffForHumans() }}</small>
                                                        </div>
                                                        <a href="{{ route('admin.leaves.show', $leave->id) }}" class="btn btn-primary btn-xxs shadow">View</a>
                                                    </div>
                                                </li>
                                            @empty
                                                <li class="text-center">
                                                    <p class="text-muted mt-3">No pending notifications</p>
                                                </li>
                                            @endforelse
                                        </ul>
                                        @if($totalNotifications > 0)
                                            <div class="text-center mt-3">
                                                <a href="{{ route('admin.leaves.index') }}" class="btn btn-primary btn-sm">View All</a>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </li>
                            
                            <li class="nav-item dropdown header-profile">
                                <a class="nav-link" href="javascript:void(0)" role="button" data-bs-toggle="dropdown">
                                    <img src="{{ asset('template/images/profile/17.jpg') }}" width="20" alt=""/>
									<div class="header-info" style="display: block !important;">
										<span class="text-black">{{ auth('admin')->user()->name }}</span>
										<p class="fs-12 mb-0">Administrator</p>
									</div>
                                </a>
                                <div class="dropdown-menu dropdown-menu-right">
                                    <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="dropdown-item ai-icon">
                                        <svg id="icon-logout" xmlns="http://www.w3.org/2000/svg" class="text-danger" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg>
                                        <span class="ms-2">Logout </span>
                                    </a>
                                    <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        </ul>
                    </div>
                </nav>
            </div>
        </div>
        <!--**********************************
            Header end
        ***********************************-->

        <!--**********************************
            Sidebar start
        ***********************************-->
        <div class="dlabnav">
            <div class="dlabnav-scroll">
				<ul class="metismenu" id="menu">
                    <li class="{{ request()->routeIs('admin.dashboard') ? 'mm-active' : '' }}">
                        <a href="{{ route('admin.dashboard') }}" class="ai-icon" aria-expanded="false">
                            <i class="flaticon-381-networking"></i>
                            <span class="nav-text">Dashboard</span>
                        </a>
                    </li>
                    
                    <li class="{{ request()->routeIs('admin.employees.*') ? 'mm-active' : '' }}">
                        <a href="{{ route('admin.employees.index') }}" class="ai-icon" aria-expanded="false">
                            <i class="flaticon-381-user-7"></i>
                            <span class="nav-text">Employees / BDMs</span>
                        </a>
                    </li>
                    
                    <li class="{{ request()->routeIs('admin.salaries.*', 'admin.leaves.*') ? 'mm-active' : '' }}">
                        <a class="has-arrow ai-icon" href="javascript:void(0);" aria-expanded="false">
                            <i class="flaticon-381-user-9"></i>
                            <span class="nav-text">Profile, Salary & Leave</span>
                        </a>
                        <ul aria-expanded="false">
                            <li><a href="{{ route('admin.salaries.index') }}">
                                <i class="flaticon-381-price-tag text-success me-2"></i>Salary Management
                            </a></li>
                            <li><a href="{{ route('admin.leaves.index') }}">
                                <i class="flaticon-381-list text-primary me-2"></i>Leave Requests
                            </a></li>
                            <li><a href="{{ route('admin.leaves.balances') }}">
                                <i class="flaticon-381-calendar-1 text-info me-2"></i>Leave Balances
                            </a></li>
                        </ul>
                    </li>
                    
                    <li class="{{ request()->routeIs('admin.targets.*') ? 'mm-active' : '' }}">
                        <a class="has-arrow ai-icon" href="javascript:void(0);" aria-expanded="false">
                            <i class="flaticon-381-diploma"></i>
                            <span class="nav-text">Target Management</span>
                        </a>
                        <ul aria-expanded="false">
                            <li><a href="{{ route('admin.targets.index') }}">
                                <i class="flaticon-381-list text-primary me-2"></i>All Targets
                            </a></li>
                            <li><a href="{{ route('admin.targets.create') }}">
                                <i class="flaticon-381-add text-success me-2"></i>Add Target
                            </a></li>
                            <li><a href="{{ route('admin.targets.bulk-create') }}">
                                <i class="flaticon-381-file-1 text-info me-2"></i>Bulk Add Targets
                            </a></li>
                        </ul>
                    </li>
                    
                    <li class="{{ request()->routeIs('admin.projects.*') ? 'mm-active' : '' }}">
                        <a class="has-arrow ai-icon" href="javascript:void(0);" aria-expanded="false">
                            <i class="flaticon-381-settings-1"></i>
                            <span class="nav-text">Project Management</span>
                        </a>
                        <ul aria-expanded="false">
                            <li><a href="{{ route('admin.projects.index') }}">
                                <i class="flaticon-381-list text-primary me-2"></i>All Projects
                            </a></li>
                            <li><a href="{{ route('admin.projects.index', ['status' => 'in-progress']) }}">
                                <i class="flaticon-381-loading text-warning me-2"></i>In Progress
                            </a></li>
                            <li><a href="{{ route('admin.projects.index', ['status' => 'completed']) }}">
                                <i class="flaticon-381-success text-success me-2"></i>Completed
                            </a></li>
                            <li><a href="{{ route('admin.projects.payments') }}">
                                <i class="flaticon-381-coin text-info me-2"></i>Payment Tracking
                            </a></li>
                            <li><a href="{{ route('admin.projects.maintenance') }}">
                                <i class="flaticon-381-heart text-danger me-2"></i>Maintenance Contracts
                            </a></li>
                        </ul>
                    </li>
                    
                    <li class="{{ request()->routeIs('admin.reports.*') ? 'mm-active' : '' }}">
                        <a class="has-arrow ai-icon" href="javascript:void(0);" aria-expanded="false">
                            <i class="flaticon-381-notepad"></i>
                            <span class="nav-text">Reports</span>
                        </a>
                        <ul aria-expanded="false">
                            <li><a href="{{ route('admin.reports.target') }}">
                                <i class="flaticon-381-diploma text-primary me-2"></i>Target Report
                            </a></li>
                            <li><a href="{{ route('admin.reports.salary') }}">
                                <i class="flaticon-381-price-tag text-success me-2"></i>Salary Report
                            </a></li>
                            <li><a href="{{ route('admin.reports.leave') }}">
                                <i class="flaticon-381-calendar-1 text-warning me-2"></i>Leave Report
                            </a></li>
                            <li><a href="{{ route('admin.reports.performance') }}">
                                <i class="flaticon-381-cup text-info me-2"></i>Performance Report
                            </a></li>
                            <li><a href="{{ route('admin.reports.attendance') }}">
                                <i class="flaticon-381-clock text-danger me-2"></i>Attendance Report
                            </a></li>
                        </ul>
                    </li>
                </ul>
			</div>
        </div>
        <!--**********************************
            Sidebar end
        ***********************************-->

		<!--**********************************
            Content body start
        ***********************************-->
        <div class="content-body">
            <div class="container-fluid">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="me-2"><polyline points="9 11 12 14 22 4"></polyline><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path></svg>
                        <strong>Success!</strong> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="me-2"><polygon points="7.86 2 16.14 2 22 7.86 22 16.14 16.14 22 7.86 22 2 16.14 2 7.86 7.86 2"></polygon><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg>
                        <strong>Error!</strong> {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Error!</strong>
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @yield('content')
            </div>
        </div>
        <!--**********************************
            Content body end
        ***********************************-->

        <!--**********************************
            Footer start
        ***********************************-->
        <div class="footer">
            <div class="copyright">
                <p>Copyright © Designed &amp; Developed by <a href="#" target="_blank">Konnectix</a> {{ date('Y') }}</p>
            </div>
        </div>
        <!--**********************************
            Footer end
        ***********************************-->

	</div>
    <!--**********************************
        Main wrapper end
    ***********************************-->

    <!--**********************************
        Scripts
    ***********************************-->
    <script src="{{ asset('template/vendor/global/global.min.js') }}"></script>
	<script src="{{ asset('template/vendor/bootstrap-select/dist/js/bootstrap-select.min.js') }}"></script>
    <script src="{{ asset('template/js/custom.min.js') }}"></script>
	<script src="{{ asset('template/js/dlabnav-init.js') }}"></script>

    @stack('scripts')
</body>
</html>
