<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="csrf-token" content="{{ csrf_token() }}">
	
	<title>@yield('title', 'Konnectix Admin Panel')</title>
    
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
                            <li class="nav-item dropdown header-profile">
                                <a class="nav-link" href="javascript:void(0)" role="button" data-bs-toggle="dropdown">
                                    <img src="{{ asset('template/images/profile/17.jpg') }}" width="20" alt=""/>
									<div class="header-info">
										<span class="text-black">{{ Auth::user()->name ?? 'Admin' }}</span>
										<p class="fs-12 mb-0">{{ Auth::user()->email ?? 'admin@konnectix.com' }}</p>
									</div>
                                </a>
                                <div class="dropdown-menu dropdown-menu-right">
                                    <a href="#" class="dropdown-item ai-icon">
                                        <svg id="icon-user1" xmlns="http://www.w3.org/2000/svg" class="text-primary" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                                        <span class="ms-2">Profile </span>
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
                        <a class="ai-icon" href="{{ route('customers.index') }}" aria-expanded="false">
                            <i class="flaticon-381-user-7"></i>
                            <span class="nav-text">Customer Management</span>
                        </a>
                    </li>
                    
                    <li>
                        <a class="has-arrow ai-icon" href="javascript:void(0);" aria-expanded="false">
                            <i class="flaticon-381-controls-3"></i>
                            <span class="nav-text">Lead Management</span>
                        </a>
                        <ul aria-expanded="false">
                            <li><a href="{{ route('leads.incoming') }}">
                                <i class="flaticon-381-download text-success me-2"></i>Incoming Leads
                            </a></li>
                            <li><a href="{{ route('leads.outgoing') }}">
                                <i class="flaticon-381-upload text-primary me-2"></i>Outgoing Leads
                            </a></li>
                        </ul>
                    </li>
                    
                    {{-- Commented out for now as requested --}}
                    {{--
                    <li>
                        <a class="ai-icon" href="{{ route('invoices.index') }}" aria-expanded="false">
                            <i class="flaticon-381-notepad"></i>
                            <span class="nav-text">Invoice Management</span>
                        </a>
                    </li>
                    --}}

                    {{-- Future modules - commented for now --}}
                    {{--
                    <li><a class="ai-icon" href="#" aria-expanded="false">
							<i class="flaticon-381-calendar-1"></i>
							<span class="nav-text">Monthly Amount</span>
						</a>
                    </li>

                    <li><a class="ai-icon" href="#" aria-expanded="false">
							<i class="flaticon-381-notebook-2"></i>
							<span class="nav-text">Monthly Invoices</span>
						</a>
                    </li>

                    <li><a class="ai-icon" href="#" aria-expanded="false">
							<i class="flaticon-381-file"></i>
							<span class="nav-text">Monthly GST</span>
						</a>
                    </li>

                    <li><a class="has-arrow ai-icon" href="javascript:void(0);" aria-expanded="false">
							<i class="flaticon-381-bookmark"></i>
							<span class="nav-text">Leads Management</span>
						</a>
                        <ul aria-expanded="false">
                            <li><a href="#">All Leads</a></li>
                            <li><a href="#">Add Lead</a></li>
                        </ul>
                    </li>

                    <li><a class="has-arrow ai-icon" href="javascript:void(0);" aria-expanded="false">
							<i class="flaticon-381-settings-1"></i>
							<span class="nav-text">User Management</span>
						</a>
                        <ul aria-expanded="false">
                            <li><a href="#">All Users</a></li>
                            <li><a href="#">Add User</a></li>
                        </ul>
                    </li>
                    --}}
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

    @stack('scripts')
</body>
</html>