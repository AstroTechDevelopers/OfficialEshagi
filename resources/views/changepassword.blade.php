<?php
/**
 *Created by PhpStorm for eshagi
 *User: Vincent Guyo
 *Date: 2020-12-10
 *Time: 12:04 AM
 */

$user = \Illuminate\Support\Facades\Auth::user();
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="AstroCred is Zambia's leading online provider of affordable loans & store credit.">
    <meta name="author" content="Kauma Mbewe">
    <title>AstroCred| Zambia's Leading Loans & Store Credit Provider</title>
    <link rel="shortcut icon" href="{{asset('images/favicon.png')}}" type="image/x-icon">
    <link rel="icon" href="{{asset('images/favicon.png')}}" type="image/x-icon">

    <link href="{{asset('assets/css/bootstrap.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('assets/css/icons.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('assets/css/app.min.css')}}" rel="stylesheet" type="text/css" />

</head>

<body class="bg-primary bg-pattern">
<div class="home-btn d-none d-sm-block">
    <a href="{{url('/home')}}"><i class="mdi mdi-home-variant h2 text-white"></i></a>
</div>

<div class="account-pages my-5 pt-sm-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="text-center mb-5">
                    <a href="{{url('/home')}}" class="logo"><img src="{{asset('images/logo.png')}}" height="24" alt="logo"></a>
                    <h5 class="font-size-16 text-white-50 mb-4">Welcome to AstroCred, {{auth()->user()->first_name}}.</h5>
                </div>
            </div>
        </div>
        <!-- end row -->

        <div class="row justify-content-center">
            <div class="col-xl-5 col-sm-8">
                <div class="card">
                    <div class="card-body p-4">
                        <div class="p-2">
                            <p class="text-center">For security reasons we would appreciate it, if you would change your password.</p>

                                {!! Form::open(array('route' => ['forcePasswordChange',$user->id], 'method' => 'PUT', 'role' => 'form', 'class' => 'form-horizontal')) !!}

                                {!! csrf_field() !!}
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group form-group-custom mt-5">
                                            <input type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" id="password" name="password" required>
                                            <label for="userpassword">Password</label>
                                            @if ($errors->has('password'))
                                                <span class="invalid-feedback">
                                                        <strong>{{ $errors->first('password') }}</strong>
                                                    </span>
                                            @endif
                                        </div>

                                        <div class="form-group form-group-custom mt-5">
                                            <input type="password" class="form-control{{ $errors->has('password_confirmation') ? ' is-invalid' : '' }}" id="password_confirmation" name="password_confirmation" required>
                                            <label for="userpassword">Confirm Password</label>
                                            @if ($errors->has('password_confirmation'))
                                                <span class="invalid-feedback">
                                                        <strong>{{ $errors->first('password_confirmation') }}</strong>
                                                    </span>
                                            @endif
                                        </div>
                                        <div class="mt-4">
                                            <button class="btn btn-success btn-block waves-effect waves-light" type="submit">Change Password</button>
                                        </div>

                                    </div>
                                </div>
                                {!! Form::close() !!}

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="{{asset('assets/libs/jquery/jquery.min.js')}}"></script>
<script src="{{asset('assets/libs/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<script src="{{asset('assets/libs/metismenu/metisMenu.min.js')}}"></script>
<script src="{{asset('assets/libs/simplebar/simplebar.min.js')}}"></script>
<script src="{{asset('assets/libs/node-waves/waves.min.js')}}"></script>

<script src="{{asset('assets/js/app.js')}}"></script>

</body>
</html>
