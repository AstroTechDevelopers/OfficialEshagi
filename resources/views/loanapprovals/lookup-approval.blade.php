<?php
/**
 * Created by PhpStorm for eshagitwo
 * User: VinceGee
 * Date: 8/14/2022
 * Time: 7:33 PM
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
    <title> Loan Lookup | eShagi</title>
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
                    <h5 class="font-size-16 text-white-50 mb-4">Hie There, {{$user->first_name}}.</h5>
                </div>
            </div>
        </div>
        <!-- end row -->

        <div class="row justify-content-center">
            <div class="col-xl-5 col-sm-8">
                <div class="card">
                    <div class="card-body p-4">
                        <div class="p-2">
                            @if($loanApproval->verified)
                                <p class="text-center">eShagi was given permission to process this loan!</p>
                            @else
                                <p class="text-center">To give eShagi permission to process your loan, please enter your NRC and OTP sent to you</p>
                            @endif

                            <div class="container">
                                <div class="row">
                                    <div class="col-12">
                                        @include('partials.form-status')
                                    </div>
                                </div>
                            </div>

                            {!! Form::open(array('route' => ['process.loan.lookup'], 'method' => 'POST', 'role' => 'form', 'class' => 'form-horizontal')) !!}

                            {!! csrf_field() !!}
                            <input type="hidden" name="lookup_id" id="lookup_id" value="{{$loanApproval->id}}">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group form-group-custom mt-5">
                                        <input type="password" class="form-control{{ $errors->has('nrc') ? ' is-invalid' : '' }}" id="nrc" name="nrc" required>
                                        <label for="nrc">NRC</label>
                                        @if ($errors->has('nrc'))
                                            <span class="invalid-feedback">
                                                        <strong>{{ $errors->first('nrc') }}</strong>
                                                    </span>
                                        @endif
                                    </div>

                                    <div class="form-group form-group-custom mt-5">
                                        <input type="password" class="form-control{{ $errors->has('otp') ? ' is-invalid' : '' }}" id="otp" name="otp" required>
                                        <label for="otp">OTP</label>
                                        @if ($errors->has('otp'))
                                            <span class="invalid-feedback">
                                                        <strong>{{ $errors->first('otp') }}</strong>
                                                    </span>
                                        @endif
                                    </div>
                                    @if($loanApproval->verified != true)
                                        <div class="mt-4">
                                            <button class="btn btn-success btn-block waves-effect waves-light" type="submit">Approve Loan</button>
                                        </div>
                                    @endif

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
