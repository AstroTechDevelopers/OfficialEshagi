<?php
/**
 *Created by PhpStorm for eshagi
 *User: Vincent Guyo
 *Date: 9/23/2020
 *Time: 11:05 AM
 */

?>
    <!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
        <!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-Y996J4MKXZ"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-Y996J4MKXZ');
</script>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="AstroCred is Zambia's leading online provider of affordable loans & store credit.">
    <meta name="author" content="Kauma Mbewe">
    <title>AstroCred| Zambia's Leading Loans & Store Credit Provider</title>

    <link rel="stylesheet" href="{{asset('css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{asset('css/animate.css')}}">
    <link rel="stylesheet" href="{{asset('css/slick.css')}}">
    <link rel="stylesheet" href="{{asset('css/magnific-popup.css')}}">
    <link rel="stylesheet" href="{{asset('css/style.css')}}">
    <link rel="stylesheet" href="{{asset('css/responsive.css')}}">

    <link rel="shortcut icon" href="{{asset('images/favicon.png')}}" type="image/x-icon">
    <link rel="icon" href="{{asset('images/favicon.png')}}" type="image/x-icon">

    <script src="{{asset('js/modernizr.js')}}"></script>

</head>
<body data-spy="scroll" data-target=".navbar-default" data-offset="100">

<div class="warpper clearfix">
    <a href="{{url('/')}}" class="menu-nav-trigger-login" style="color:#ffffff">
        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="44px" height="44px" viewBox="0 0 24 24" version="1.1" style="color:#ffffff">
            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                <polygon points="0 0 24 0 24 24 0 24" />
                <rect fill="#ffffff" transform="translate(12.000000, 12.000000) scale(-1, 1) rotate(-90.000000) translate(-12.000000, -12.000000) " x="11" y="5" width="2" height="14" rx="1" style="color:#ffffff"/>
                <path d="M3.7071045,15.7071045 C3.3165802,16.0976288 2.68341522,16.0976288 2.29289093,15.7071045 C1.90236664,15.3165802 1.90236664,14.6834152 2.29289093,14.2928909 L8.29289093,8.29289093 C8.67146987,7.914312 9.28105631,7.90106637 9.67572234,8.26284357 L15.6757223,13.7628436 C16.0828413,14.136036 16.1103443,14.7686034 15.7371519,15.1757223 C15.3639594,15.5828413 14.7313921,15.6103443 14.3242731,15.2371519 L9.03007346,10.3841355 L3.7071045,15.7071045 Z"
                      fill="#ffffff" fill-rule="evenodd" transform="translate(9.000001, 11.999997) scale(-1, -1) rotate(90.000000) translate(-9.000001, -11.999997) "/>
            </g>
        </svg>
    </a>

    <header class="navbar-header clearfix">
        <nav class="navbar navbar-expand-lg fixed-top ">
            <div class="container">
                <img class="navbar-brand" src="{{asset('images/logo.png')}}" alt="eShagi">

                <div style="right:0">
                    <a href="{{route('regista-patina')}}" class="btn btn-blue">Register</a>
                </div>
            </div>
        </nav>
    </header>

    <section id="home">
        <div class="container-page  hero">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="hero-text">
                            <h1 id="loginToStart">Hello Partner!</h1>
                            <p id="loginToStart2"></p>
                            <div class="col-lg-8 col-md-6">
                                <form class="login-form" method="POST" action="{{ route('login') }}">
                                    @csrf
                                    <div class="row" id="whole">
                                        <div class="col-lg-6">
                                            <div class="form-group" id="myid">
                                                <label>Email Address or Company Reg Number</label>
                                                <input class="form-control{{ $errors->has('natid') || $errors->has('email') ? ' is-invalid' : '' }}" type="text" name="natid" id="natid" value="{{ old('natid') ?: old('email') }}" required autofocus placeholder="Enter your email address or Company Reg Number...">
                                                @if ($errors->has('natid') || $errors->has('email'))
                                                    <span class="invalid-feedback">
                                                        <strong>{{ $errors->first('natid') ?: $errors->first('email')}}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                            <div class="form-group" id="mypassword">
                                                <label>Password</label>
                                                <input class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" type="password" name="password" id="password" required="required" placeholder="Enter your password...">
                                                @if ($errors->has('password'))
                                                    <span class="invalid-feedback">
                                                        <strong>{{ $errors->first('password') }}</strong>
                                                    </span>
                                                @endif
                                            </div>

                                            <input class="btn btn-success btn-send" id="loginbtn" type="submit" value="Login">
                                            <br> <br><span id="notyet1">Not yet Registered? </span><a id="notyet2" href="{{route('regista-patina')}}">Click Here</a>

                                            <br> <br><span id="notyet1">{{ __('auth.forgot') }} </span><a id="notyet2" href="{{ route('password.request') }}">Reset It Here</a>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

</div>

@include('partials.fe-footer')

<script src="{{asset('js/jquery-3.3.1.min.js')}} "></script>
<script src="{{asset('js/bootstrap.min.js')}} "></script>
<script src="{{asset('js/jquery.easing.js')}} "></script>
<script src="{{asset('js/wow.min.js')}}"></script>
<script src="{{asset('js/magnific-popup.min.js')}} "></script>
<script src="{{asset('js/jquery.scrollUp.min.js')}} "></script>
<script src="{{asset('js/jquery.ajaxchimp.min.js')}} "></script>
<script src="{{asset('js/slick.min.js')}} "></script>
<script src="{{asset('js/mo.min.js')}} "></script>
<script src="{{asset('js/main.js')}} "></script>
<script src="{{asset('js/login.js')}} "></script>

</body>
</html>
