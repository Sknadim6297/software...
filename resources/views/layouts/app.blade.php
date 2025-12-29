<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="csrf-token" content="{{ csrf_token() }}">
	
	<title>@yield('title', 'Konnectix BDM Panel')</title>
    
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('template/images/favicon.png') }}">
    <link href="{{ asset('template/vendor/jqvmap/css/jqvmap.min.css') }}" rel="stylesheet">
	<link rel="stylesheet" href="{{ asset('template/vendor/chartist/css/chartist.min.css') }}">
    <link href="{{ asset('template/vendor/bootstrap-select/dist/css/bootstrap-select.min.css') }}" rel="stylesheet">
	<link href="{{ asset('template/css/style.css') }}" rel="stylesheet">
	
	<!-- Icon Fix Styles -->
	<style>
		/* Ensure icons don't get affected by any font changes */
		i, .flaticon, .fa, .fas, .far, .fal, .fab,
		[class*="flaticon-"], [class*="fa-"], [class^="icon-"],
		.material-icons, .ti, .la, .simple-icon {
			font-family: inherit !important;
		}
		
		/* Fix FontAwesome specifically */
		.fa, .fas, .far, .fal, .fab {
			font-family: "FontAwesome" !important;
			font-style: normal !important;
			font-weight: normal !important;
			text-decoration: inherit !important;
		}
		
		/* Fix Flaticon specifically */
		[class*="flaticon-"] {
			font-family: "Flaticon" !important;
			font-style: normal !important;
			font-weight: normal !important;
		}
		
		/* Fix dropdown positioning and width */
		.dropdown-menu {
			position: absolute !important;
			z-index: 1050 !important;
			min-width: 160px !important;
			max-width: 200px !important;
			width: auto !important;
		}
		
		.dropdown-menu.show {
			display: block !important;
		}
		
		.table .dropdown {
			position: relative;
		}
		
		.table .dropdown-toggle {
			white-space: nowrap;
		}
		
		.dropdown-item {
			white-space: nowrap;
			padding: 0.5rem 1rem;
		}
		
		/* Responsive logo sizing */
		@media (max-width: 767px) {
			.logo-konnectix {
				max-height: 30px !important;
				margin: 10px 15px !important;
			}
			
			.nav-header {
				width: 100% !important;
				display: flex !important;
				justify-content: space-between !important;
				align-items: center !important;
				padding: 0 !important;
			}
			
			.nav-control {
				right: auto !important;
				left: auto !important;
				order: -1 !important;
				margin-left: 15px !important;
			}
			
			.brand-logo {
				padding: 0 !important;
				order: 1 !important;
				margin-right: 15px !important;
			}
		}
		
		@media (max-width: 575px) {
			.logo-konnectix {
				max-height: 26px !important;
				margin: 8px 10px !important;
			}
			
			.nav-control {
				margin-left: 10px !important;
			}
			
			.brand-logo {
				margin-right: 10px !important;
			}
		}
		
		/* Fix content body scrolling and footer positioning */
		.content-body {
			min-height: calc(100vh - 150px);
			padding-bottom: 80px;
		}
		
		.footer {
			position: relative;
			margin-top: auto;
			clear: both;
		}
		
		/* Ensure main wrapper has proper flex layout */
		#main-wrapper {
			display: flex;
			flex-direction: column;
			min-height: 100vh;
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
	
	<!-- intl-tel-input CSS -->
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/intl-tel-input@19.5.6/build/css/intlTelInput.css">
	
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
            <a href="{{ route('dashboard') }}" class="brand-logo">
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
                                @yield('page-title', 'Dashboard')
                            </div>
                        </div>
                        <ul class="navbar-nav header-right">
                            {{-- Notifications --}}
                            <li class="nav-item dropdown notification_dropdown">
                                <a class="nav-link bell dz-theme-mode p-0" href="javascript:void(0);" role="button" data-bs-toggle="dropdown">
                                    <i class="flaticon-381-alarm-clock"></i>
                                    @auth
                                        @php
                                            $unreadCount = Auth::user()->bdm ? Auth::user()->bdm->notifications()->where('is_read', false)->count() : 0;
                                        @endphp
                                        @if($unreadCount > 0)
                                            <span class="badge badge-circle badge-danger">{{ $unreadCount }}</span>
                                        @endif
                                    @endauth
                                </a>
                                <div class="dropdown-menu dropdown-menu-end">
                                    <div id="DZ_W_Notification1" class="widget-media dz-scroll p-3" style="height:380px;">
                                        <div class="timeline-panel">
                                            <div class="media-heading mb-2">
                                                <h5 class="mb-0">Notifications</h5>
                                            </div>
                                        </div>
                                        <ul class="timeline">
                                            @auth
                                                @if(Auth::user()->bdm)
                                                    @php
                                                        $notifications = Auth::user()->bdm->notifications()
                                                            ->latest()
                                                            ->take(10)
                                                            ->get();
                                                    @endphp
                                                    @forelse($notifications as $notification)
                                                        <li>
                                                            <div class="timeline-panel {{ $notification->is_read ? '' : 'bg-light' }}">
                                                                <div class="media me-2">
                                                                    @if($notification->type == 'leave')
                                                                        <i class="flaticon-381-calendar-1 text-warning"></i>
                                                                    @elseif($notification->type == 'salary')
                                                                        <i class="flaticon-381-price-tag text-success"></i>
                                                                    @elseif($notification->type == 'target')
                                                                        <i class="flaticon-381-diploma text-primary"></i>
                                                                    @else
                                                                        <i class="flaticon-381-alarm-clock text-info"></i>
                                                                    @endif
                                                                </div>
                                                                <div class="media-body">
                                                                    <h6 class="mb-1">{{ $notification->title }}</h6>
                                                                    <small class="d-block">{{ $notification->message }}</small>
                                                                    <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                                                                </div>
                                                            </div>
                                                        </li>
                                                    @empty
                                                        <li class="text-center">
                                                            <p class="text-muted mt-3">No notifications</p>
                                                        </li>
                                                    @endforelse
                                                @endif
                                            @endauth
                                        </ul>
                                        @auth
                                            @if(Auth::user()->bdm && $unreadCount > 0)
                                                <div class="text-center mt-3">
                                                    <a href="{{ route('bdm.notifications') }}" class="btn btn-primary btn-sm">View All</a>
                                                </div>
                                            @endif
                                        @endauth
                                    </div>
                                </div>
                            </li>
                            
                            <li class="nav-item dropdown header-profile">
                                <a class="nav-link" href="javascript:void(0)" role="button" data-bs-toggle="dropdown">
                                    @if(Auth::check() && Auth::user()->bdm && Auth::user()->bdm->profile_image)
                                        <img src="{{ asset('storage/' . Auth::user()->bdm->profile_image) }}" width="20" alt=""/>
                                    @else
                                        <img src="{{ asset('template/images/profile/17.jpg') }}" width="20" alt=""/>
                                    @endif
									<div class="header-info" style="display: block !important;">
										<span class="text-black">{{ Auth::check() && Auth::user()->bdm ? Auth::user()->bdm->name : Auth::user()->name }}</span>
										<p class="fs-12 mb-0">{{ Auth::check() && Auth::user()->bdm ? Auth::user()->bdm->employee_code : Auth::user()->email }}</p>
									</div>
                                </a>
                                <div class="dropdown-menu dropdown-menu-right">
                                    <a href="{{ route('bdm.profile') }}" class="dropdown-item ai-icon">
                                        <svg id="icon-user1" xmlns="http://www.w3.org/2000/svg" class="text-primary" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                                        <span class="ms-2">My Profile </span>
                                    </a>
                                    <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="dropdown-item ai-icon">
                                        <svg id="icon-logout" xmlns="http://www.w3.org/2000/svg" class="text-danger" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg>
                                        <span class="ms-2">Logout </span>
                                    </a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
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
                    <li>
                        <a href="{{ route('dashboard') }}" class="ai-icon" aria-expanded="false">
                            <i class="flaticon-381-networking"></i>
                            <span class="nav-text">Dashboard</span>
                        </a>
                    </li>
                    
                    <li>
                        <a class="has-arrow ai-icon" href="javascript:void(0);" aria-expanded="false">
                            <i class="flaticon-381-user-7"></i>
                            <span class="nav-text">Customer Management</span>
                        </a>
                        <ul aria-expanded="false">
                            <li><a href="{{ route('customers.index') }}">
                                <i class="flaticon-381-user text-primary me-2"></i>All Customers
                            </a></li>
                            <li><a href="{{ route('customers.history') }}">
                                <i class="flaticon-381-back-1 text-info me-2"></i>Customer History
                            </a></li>
                        </ul>
                    </li>
                    
                    <li>
                        <a class="has-arrow ai-icon" href="javascript:void(0);" aria-expanded="false">
                            <i class="flaticon-381-controls-3"></i>
                            <span class="nav-text">Lead Management</span>
                        </a>
                        <ul aria-expanded="false">
                            <li><a href="{{ route('leads.all') }}">
                                <i class="flaticon-381-list text-info me-2"></i>All Leads
                            </a></li>
                            <li><a href="{{ route('leads.incoming') }}">
                                <i class="flaticon-381-download text-success me-2"></i>Incoming Leads
                            </a></li>
                            <li><a href="{{ route('leads.outgoing') }}">
                                <i class="flaticon-381-upload text-primary me-2"></i>Outgoing Leads
                            </a></li>
                           
                        </ul>
                    </li>
                
                 
                 <li>
                        <a class="has-arrow ai-icon" href="javascript:void(0);" aria-expanded="false">
                            <i class="flaticon-381-file-1"></i>
                            <span class="nav-text">Proposals & Contracts</span>
                        </a>
                        <ul aria-expanded="false">
                            <li><a href="{{ route('proposals.create') }}">
                                <i class="flaticon-381-add text-success me-2"></i>Create Proposal
                            </a></li>
                            <li><a href="{{ route('proposals.index') }}">
                                <i class="flaticon-381-list text-primary me-2"></i>All Proposals
                            </a></li>
                            <li><a href="{{ route('contracts.index') }}">
                                <i class="flaticon-381-notebook-1 text-warning me-2"></i>All Contracts
                            </a></li>
                            <li><a href="{{ route('invoices.index') }}">
                                <i class="flaticon-381-notepad text-info me-2"></i>All Invoices
                            </a></li>
                        </ul>
                    </li>

                    {{-- BDM Personal Management --}}
                    <li>
                        <a class="has-arrow ai-icon" href="javascript:void(0);" aria-expanded="false">
                            <i class="flaticon-381-user-9"></i>
                            <span class="nav-text">Profile, Salary & Leave</span>
                        </a>
                        <ul aria-expanded="false">
                            <li><a href="{{ route('bdm.profile') }}">
                                <i class="flaticon-381-user text-primary me-2"></i>My Profile
                            </a></li>
                            <li><a href="{{ route('bdm.documents') }}">
                                <i class="flaticon-381-file-1 text-info me-2"></i>My Documents
                            </a></li>
                            <li><a href="{{ route('bdm.salary') }}">
                                <i class="flaticon-381-price-tag text-success me-2"></i>Salary & Remuneration
                            </a></li>
                            <li><a href="{{ route('bdm.leaves') }}">
                                <i class="flaticon-381-calendar-1 text-warning me-2"></i>Leave Management
                            </a></li>
                        </ul>
                    </li>
                    
                    <li>
                        <a class="ai-icon" href="{{ route('bdm.targets') }}" aria-expanded="false">
                            <i class="flaticon-381-diploma"></i>
                            <span class="nav-text">Target Management</span>
                        </a>
                    </li>

                    {{-- Website, Software & Application Management --}}
                    <li class="{{ request()->routeIs('projects.*') ? 'mm-active' : '' }}">
                        <a class="has-arrow ai-icon" href="javascript:void(0);" aria-expanded="false">
                            <i class="flaticon-381-settings-1"></i>
                            <span class="nav-text">Website, Software & Application Management</span>
                        </a>
                        <ul aria-expanded="false">
                            <li><a href="{{ route('projects.index') }}">
                                <i class="flaticon-381-list text-primary me-2"></i>All Projects
                            </a></li>
                            <li><a href="{{ route('projects.create') }}">
                                <i class="flaticon-381-add text-success me-2"></i>Create Project
                            </a></li>
                            <li><a href="{{ route('projects.index', ['status' => 'in-progress']) }}">
                                <i class="flaticon-381-hourglass text-warning me-2"></i>In Progress
                            </a></li>
                            <li><a href="{{ route('projects.index', ['status' => 'completed']) }}">
                                <i class="flaticon-381-success text-success me-2"></i>Completed
                            </a></li>
                        </ul>
                    </li>

                    {{-- Renewal & Service Management --}}
                    <li class="{{ request()->routeIs('service-renewals.*') ? 'mm-active' : '' }}">
                        <a class="has-arrow ai-icon" href="javascript:void(0);" aria-expanded="false">
                            <i class="flaticon-381-refresh"></i>
                            <span class="nav-text">Renewal & Service Management</span>
                        </a>
                        <ul aria-expanded="false">
                            <li><a href="{{ route('service-renewals.index') }}">
                                <i class="flaticon-381-list text-primary me-2"></i>All Renewals
                            </a></li>
                            <li><a href="{{ route('service-renewals.create') }}">
                                <i class="flaticon-381-add text-success me-2"></i>Create Renewal
                            </a></li>
                            <li><a href="{{ route('contracts.index') }}">
                                <i class="flaticon-381-notebook-1 text-warning me-2"></i>Contracts
                            </a></li>
                            <li><a href="{{ route('invoices.index') }}">
                                <i class="flaticon-381-notepad text-info me-2"></i>Invoices
                            </a></li>
                            <li><a href="{{ route('projects.index') }}">
                                <i class="flaticon-381-settings-1 text-secondary me-2"></i>Projects
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
    <!-- Required vendors -->
    <script src="{{ asset('template/vendor/global/global.min.js') }}"></script>
	<script src="{{ asset('template/vendor/bootstrap-select/dist/js/bootstrap-select.min.js') }}"></script>
    <script src="{{ asset('template/js/custom.min.js') }}"></script>
	<script src="{{ asset('template/js/dlabnav-init.js') }}"></script>
	
	<!-- intl-tel-input JS -->
	<script src="https://cdn.jsdelivr.net/npm/intl-tel-input@19.5.6/build/js/intlTelInput.min.js"></script>

    @stack('scripts')
</body>
</html>