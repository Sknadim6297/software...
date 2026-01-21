<!DOCTYPE html>
<html lang="en" class="h-100">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Login - Konnectix Software</title>
    
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
        html, body {
            overflow: hidden;
            height: 100%;
        }
        
        * {
            font-family: "Momo Trust Display", sans-serif;
        }
        
        h1, h2, h3, h4, h5, h6 {
            font-family: "DM Serif Display", serif;
        }
        
        /* BDM Design - Modern Teal/Blue Gradient */
        .sign {
            background: linear-gradient(135deg, #00b4db 0%, #0083b0 100%);
            position: relative;
            overflow: hidden;
        }
        
        .sign::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"><path fill="rgba(255,255,255,0.1)" fill-opacity="1" d="M0,96L48,112C96,128,192,160,288,160C384,160,480,128,576,122.7C672,117,768,139,864,144C960,149,1056,139,1152,133.3C1248,128,1344,128,1392,128L1440,128L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path></svg>') no-repeat bottom;
            background-size: cover;
            opacity: 0.2;
        }
        
        .sign .text-center a img { max-height: 80px; width: auto; position: relative; z-index: 1; }
        
        .img-fix {
            max-width: 100%;
            height: auto;
        }
        
        .mobile-logo {
            display: none;
        }
        
        /* BDM Form Styling */
        .sign-in-your {
            background: #f8f9fa;
        }
        
        .sign-in-your h4 {
            color: #0083b0;
            font-weight: 700;
            margin-bottom: 10px;
            font-size: 22px;
        }
        
        .sign-in-your > span {
            color: #6c757d;
            font-size: 14px;
        }
        
        .sign-in-your .form-control {
            border: 2px solid #dee2e6;
            border-radius: 10px;
            padding: 13px 16px;
            font-size: 14px;
            background: white;
            transition: all 0.3s ease;
        }
        
        .sign-in-your .form-control:focus {
            border-color: #00b4db;
            box-shadow: 0 0 0 0.2rem rgba(0, 180, 219, 0.15);
            background: white;
        }
        
        .sign-in-your .form-control::placeholder {
            color: #adb5bd;
        }
        
        .sign-in-your .btn-primary {
            background: linear-gradient(135deg, #00b4db 0%, #0083b0 100%);
            border: none;
            border-radius: 10px;
            padding: 13px 30px;
            font-weight: 600;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .sign-in-your .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 25px rgba(0, 180, 219, 0.35);
        }
        
        .sign-in-your a {
            color: #0083b0;
            font-weight: 500;
            transition: color 0.3s ease;
        }
        
        .sign-in-your a:hover {
            color: #00b4db;
            text-decoration: underline;
        }
        
        .form-check-label {
            color: #495057;
            font-size: 14px;
        }
        
        @media (max-width: 767px) {
            .sign {
                display: none !important;
            }
            
            .mobile-logo {
                display: block !important;
                text-align: center;
                margin-bottom: 30px;
            }
            
            .mobile-logo img {
                max-height: 60px;
                width: auto;
            }
        }
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
                                        <a href="{{ route('dashboard') }}">
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
                                        <a href="{{ route('dashboard') }}">
                                            <img src="{{ asset('template/images/logo/logo_konnectix.webp') }}" alt="Konnectix Logo">
                                        </a>
                                    </div>
                                    
                                    <h4 class="fs-20">Sign in your account</h4>
                                    <span>Welcome back! Login with your data that you entered<br> during registration</span>
                                    
                                    <!-- Error Messages -->
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
                                    
                                    <form method="POST" action="{{ route('login') }}">
                                        @csrf
                                        
                                        <div class="mb-3">
                                            <label class="mb-1"><strong>Email</strong></label>
                                            <input type="email" 
                                                   class="form-control" 
                                                   name="email" 
                                                   value="{{ old('email') }}" 
                                                   placeholder="hello@example.com"
                                                   required 
                                                   autofocus>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label class="mb-1"><strong>Password</strong></label>
                                            <input type="password" 
                                                   class="form-control" 
                                                   name="password" 
                                                   placeholder="Password"
                                                   required>
                                        </div>
                                        
                                        <div class="row d-flex justify-content-between mt-4 mb-2">
                                            <div class="mb-3">
                                                <div class="form-check custom-checkbox ms-1">
                                                    <input type="checkbox" 
                                                           class="form-check-input" 
                                                           id="basic_checkbox_1" 
                                                           name="remember">
                                                    <label class="form-check-label" for="basic_checkbox_1">Remember my preference</label>
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <a href="{{ route('password.request') }}">Forgot Password?</a>
                                            </div>
                                        </div>
                                        
                                        <div class="text-center">
                                            <button type="submit" class="btn btn-primary btn-block">Sign Me In</button>
                                        </div>
                                    </form>
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
</body>

</html>
