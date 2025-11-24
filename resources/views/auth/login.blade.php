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
        
        .sign {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        .sign .text-center a img {
            max-height: 80px;
            width: auto;
        }
        
        .img-fix {
            max-width: 100%;
            height: auto;
        }
        
        .mobile-logo {
            display: none;
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
