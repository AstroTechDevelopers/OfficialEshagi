<?php
/**
 * Created by PhpStorm for eshagitwo
 * User: VinceGee
 * Date: 5/17/2022
 * Time: 9:50 PM
 */ ?>
    <!doctype html>
<html lang="en">

<head>
    <!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-Y996J4MKXZ"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-Y996J4MKXZ');
</script>

    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="AstroCred is Zambia's leading online provider of affordable loans & store credit.">
    <meta name="author" content="Kauma Mbewe">
    <title>AstroCred| Zambia's Leading Loans & Store Credit Provider</title>
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{asset('images/favicon.png')}}" type="image/x-icon">
    <link rel="icon" href="{{asset('images/favicon.png')}}" type="image/x-icon">

    <!-- Bootstrap Css -->
    <link href="{{asset('chief_assets/css/bootstrap.min.css')}}" id="bootstrap-style" rel="stylesheet" type="text/css" />
    <!-- Icons Css -->
    <link href="{{asset('chief_assets/css/icons.min.css')}}" rel="stylesheet" type="text/css" />
    <!-- App Css-->
    <link href="{{asset('chief_assets/css/app.min.css')}}" id="app-style" rel="stylesheet" type="text/css" />

</head>

<body class="auth-body-bg">
<div class="bg-overlay"></div>
<div class="wrapper-page">
    <div class="container-fluid p-0">
        <div class="card">
            <div class="card-body">

                <div class="text-center mt-4">
                    <div class="mb-3">
                        <a href="{{url('/chief/')}}" class="auth-logo">
                            <img src="{{asset('images/new_logo.png')}}" height="80" class="logo-dark mx-auto" alt="">
                            <img src="{{asset('images/new_logo.png')}}" height="80" class="logo-light mx-auto" alt="">
                        </a>
                    </div>
                </div>

                <h4 class="text-muted text-center font-size-18"><b>Hello Chief</b></h4>

                <div class="p-3">
                        <form class="form-horizontal mt-3" method="POST" action="{{ route('login') }}">
                            @csrf

                        <div class="form-group mb-3 row">
                            <div class="col-12">
                                <input class="form-control{{ $errors->has('natid') ? ' is-invalid' : '' }}" type="text" name="natid" id="natid"  title="Format should be in the form of as on your National ID"  value="{{ old('email') }}" required autofocus placeholder="Enter your national ID...">
                                @if ($errors->has('natid'))
                                    <span class="invalid-feedback">
                                                        <strong>{{ $errors->first('natid') }}</strong>
                                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group mb-3 row">
                            <div class="col-12">
                                <input class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" type="password" name="password" id="password" required="required" placeholder="Enter your password...">
                                @if ($errors->has('password'))
                                    <span class="invalid-feedback">
                                                        <strong>{{ $errors->first('password') }}</strong>
                                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group mb-3 row">
                            <div class="col-12">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="customCheck1">
                                    <label class="form-label ms-1" for="customCheck1">Remember me</label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group mb-3 text-center row mt-3 pt-1">
                            <div class="col-12">
                                <button class="btn btn-primary w-100 waves-effect waves-light" type="submit">Log In</button>
                            </div>
                        </div>

                        <div class="form-group mb-0 row mt-2">
                            <div class="col-sm-7 mt-3">
                                <a href="#" class="text-muted"><i class="mdi mdi-lock"></i> Forgot your password?</a>
                            </div>
                            <div class="col-sm-5 mt-3">
                            </div>
                        </div>
                    </form>
                </div>
                <!-- end -->
            </div>
            <!-- end cardbody -->
        </div>
        <!-- end card -->
    </div>
    <!-- end container -->
</div>
<!-- end -->

<!-- JAVASCRIPT -->
<script src="{{asset('chief_assets/libs/jquery/jquery.min.js')}}"></script>
<script src="{{asset('chief_assets/libs/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<script src="{{asset('chief_assets/libs/metismenu/metisMenu.min.js')}}"></script>
<script src="{{asset('chief_assets/libs/simplebar/simplebar.min.js')}}"></script>
<script src="{{asset('chief_assets/libs/node-waves/waves.min.js')}}"></script>

<script src="{{asset('chief_assets/js/app.js')}}"></script>

</body>
</html>
