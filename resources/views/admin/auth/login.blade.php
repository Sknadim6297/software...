<!DOCTYPE html>
<html lang="en" class="h-100">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin Login - Konnectix Software</title>
    
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('template/images/favicon.png') }}">
    <link href="{{ asset('template/vendor/jquery-nice-select/css/nice-select.css') }}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Icons" rel="stylesheet">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&family=Momo+Trust+Display&display=swap" rel="stylesheet">
    
    <link href="{{ asset('template/css/style.css') }}" rel="stylesheet">
    
    <style>
        html, body { overflow: hidden; height: 100%; }
        * { font-family: "Momo Trust Display", sans-serif; }
        h1, h2, h3, h4, h5, h6 { font-family: "DM Serif Display", serif; }
        
        /* Admin Design - Purple Premium Style */
        .sign { 
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            position: relative;
            overflow: hidden;
        }
        
        .sign::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 1px, transparent 1px);
            background-size: 50px 50px;
            animation: float 20s linear infinite;
        }
        
        @keyframes float {
            0% { transform: translate(0, 0); }
            100% { transform: translate(50px, 50px); }
        }
        
        .sign .text-center a img { max-height: 80px; width: auto; position: relative; z-index: 1; }
        .img-fix { max-width: 100%; height: auto; }
        .mobile-logo { display: none; }
        
        /* Admin Form Styling */
        .sign-in-your h4 {
            color: #333;
            font-weight: 700;
            margin-bottom: 10px;
        }
        
        .sign-in-your .form-control {
            border: 2px solid #e9ecef;
            border-radius: 8px;
            padding: 12px 15px;
            font-size: 14px;
            transition: all 0.3s ease;
        }
        
        .sign-in-your .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.15);
        }
        
        .sign-in-your .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 8px;
            padding: 12px 30px;
            font-weight: 600;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        .sign-in-your .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
        }
        
        @media (max-width: 767px) {
            .sign { display: none !important; }
            .mobile-logo { display: block !important; text-align: center; margin-bottom: 30px; }
            .mobile-logo img { max-height: 60px; width: auto; }
        }
        
        /* Password toggle */
        .input-group .btn-toggle-pass { border: 1px solid #ced4da; border-left: none; }
        .input-group .form-control { border-right: none; }
    </style>
</head>

<body class="body h-100">
    <div class="container h-100">
        <div class="row h-100 align-items-center justify-contain-center">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-body p-0">
                        <div class="row m-0">
                            <!-- Left Side - Logo & Illustration -->
                            <div class="col-xl-6 col-md-6 sign text-center">
                                <div>
                                    <div class="text-center my-5">
                                        <a href="{{ route('admin.dashboard') }}">
                                            <img src="{{ asset('template/images/logo/logo_konnectix.webp') }}" alt="Konnectix Logo">
                                        </a>
                                    </div>
                                    <img src="{{ asset('template/images/log.png') }}" class="img-fix bitcoin-img sd-shape7" alt="Login Illustration">
                                </div>
                            </div>
                            
                            <!-- Right Side - Login Form -->
                            <div class="col-xl-6 col-md-6">
                                <div class="sign-in-your py-4 px-2">
                                    <!-- Mobile Logo -->
                                    <div class="mobile-logo">
                                        <a href="{{ route('admin.dashboard') }}">
                                            <img src="{{ asset('template/images/logo/logo_konnectix.webp') }}" alt="Konnectix Logo">
                                        </a>
                                    </div>
                                    
                                    <h4 class="fs-20">Admin Login</h4>
                                    <span>Use your administrator credentials to sign in</span>
                                    
                                    <!-- Error Messages -->
                                    @if(session('error'))
                                        <div class="alert alert-danger alert-dismissible fade show mt-3">
                                            <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="me-2"><polygon points="7.86 2 16.14 2 22 7.86 22 16.14 16.14 22 7.86 22 2 16.14 2 7.86 7.86 2"></polygon><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg>
                                            <strong>Error!</strong> {{ session('error') }}
                                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="btn-close"></button>
                                        </div>
                                    @endif
                                    @if ($errors->any())
                                        <div class="alert alert-danger alert-dismissible fade show mt-3">
                                            <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="me-2"><polygon points="7.86 2 16.14 2 22 7.86 22 16.14 16.14 22 7.86 22 2 16.14 2 7.86 7.86 2"></polygon><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg>
                                            <strong>Error!</strong>
                                            @foreach ($errors->all() as $error)
                                                {{ $error }}
                                            @endforeach
                                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="btn-close"></button>
                                        </div>
                                    @endif
                                    
                                    <form method="POST" action="{{ route('admin.login.post') }}">
                                        @csrf
                                        
                                        <div class="mb-3">
                                            <label class="mb-1"><strong>Email</strong></label>
                                            <input type="email" 
                                                   class="form-control" 
                                                   name="email" 
                                                   value="{{ old('email') }}" 
                                                   placeholder="admin@example.com"
                                                   required 
                                                   autofocus>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label class="mb-1"><strong>Password</strong></label>
                                            <div class="input-group">
                                                <input type="password" 
                                                       class="form-control" 
                                                       id="adminPassword" 
                                                       name="password" 
                                                       placeholder="Password"
                                                       required>
                                                <button type="button" class="btn btn-outline-secondary btn-toggle-pass" id="togglePassword" aria-label="Show/Hide password">
                                                    <span class="material-icons" id="toggleIcon">visibility</span>
                                                </button>
                                            </div>
                                        </div>
                                        
                                        <div class="row d-flex justify-content-between mt-4 mb-2">
                                            <div class="mb-3">
                                                <div class="form-check custom-checkbox ms-1">
                                                    <input type="checkbox" 
                                                           class="form-check-input" 
                                                           id="admin_remember" 
                                                           name="remember">
                                                    <label class="form-check-label" for="admin_remember">Remember me</label>
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <a href="{{ route('admin.login') }}">Need help?</a>
                                            </div>
                                        </div>
                                        
                                        <div class="text-center">
                                            <button type="submit" class="btn btn-primary btn-block">Sign In</button>
                                        </div>
                                    </form>
                                    
                                    <div class="text-center mt-3">
                                        <a href="{{ route('login') }}" class="">
                                            <i class="la la-arrow-left me-1"></i> Back to BDM Login
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Required vendors -->
    <script src="{{ asset('template/vendor/global/global.min.js') }}"></script>
    <script src="{{ asset('template/js/custom.min.js') }}"></script>
    <script src="{{ asset('template/js/dlabnav-init.js') }}"></script>
    <script>
        (function(){
            const passInput = document.getElementById('adminPassword');
            const toggleBtn = document.getElementById('togglePassword');
            const toggleIcon = document.getElementById('toggleIcon');
            if (toggleBtn && passInput) {
                toggleBtn.addEventListener('click', function(){
                    const isHidden = passInput.getAttribute('type') === 'password';
                    passInput.setAttribute('type', isHidden ? 'text' : 'password');
                    toggleIcon.textContent = isHidden ? 'visibility_off' : 'visibility';
                });
            }
        })();
    </script>
</body>

</html>
