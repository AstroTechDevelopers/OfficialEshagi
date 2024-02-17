<?php
/**
 * Created by PhpStorm for eshagi
 * User: vinceg
 * Date: 20/3/2021
 * Time: 18:31
 */
?>

    <!DOCTYPE html>
<html class="no-js" lang="zxx">
<head>
    <meta charset="windows-1252">

    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="AstroCred is Zambia's leading online provider of affordable loans & store credit.">
    <meta name="author" content="Kauma Mbewe">
    <meta name="csrf-token" content="{{ csrf_token() }}">
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

<!--[if lt IE 9]>
    <script src="{{asset('js/html5shiv.min.js')}}"></script>
    <script src="{{asset('js/respond.min.js')}}"></script>
    <![endif]-->

    <script src="{{asset('js/jquery.form.js')}}"></script>
</head>
<body data-spy="scroll" data-target=".navbar-default" data-offset="100">

<header class="navbar-header clearfix">
    <nav class="navbar navbar-expand-lg fixed-top">
        <div class="container">
            <a class="navbar-brand" href="{{url('/')}}"><img src="{{asset('images/logo.png')}}" alt=""></a>
            <div style="right:0">
                <ul>
                    <li>
                        <a href="{{ route('logout')}}" onclick="event.preventDefault();
                                             document.getElementById('logout-form').submit();" type="button" class="btn btn-blue">Logout</a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</header>

<section id="home">

    <div class="container-page">

        <div class="container">
            <div class="hero-text">
                <div class="row">
                    <div class="col-lg-12">
                        <h1>You're nearly there!</h1>
                        @include('partials.fe-status')
                        <p class="wow fadeInUp" data-wow-duration="1s" data-wow-delay=".2s">Great, {{auth()->user()->first_name}} please upload Your KYC Documents</p>

                        <div class="container" >
                            <form>
                                <div class="row">
                                    <div class="col-lg-6">
                                        <h2 class="title-h2">National ID</h2>
                                    </div>
                                </div>
                                <hr>
                            </form>
                            @if ($agent->natidUpload == true)
                                <div class="row">
                                    <div class="col-lg-6">
                                        <img src="{{asset('project/public/agentids/'.$agent->natidPic)}}" id="uploadedID" width="80" height="80" />
                                    </div>
                                </div>

                                <form method="post" action="{{ route('uploadAstrogNatID') }}" enctype="multipart/form-data">
                                    @csrf
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <input type="file" name="natid" id="natid" accept="image/*" />
                                            <button id="upId" class="btn btn-blue">Upload</button>
                                        </div>
                                        <div class="col-lg-6">
                                            <img src="{{asset('images/driver-license.svg')}}" id="uploadedID" width="80" height="80" />
                                        </div>
                                    </div>
                                </form>
                            @else
                                <form method="post" action="{{ route('uploadAstrogNatID') }}" enctype="multipart/form-data">
                                    @csrf
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <input type="file" name="natid" id="natid" accept="image/*" required/>
                                            <button id="upId" class="btn btn-blue">Upload</button>
                                        </div>
                                        <div class="col-lg-6">
                                            <img src="{{asset('images/driver-license.svg')}}" id="uploadedID" width="80" height="80" />
                                        </div>
                                    </div>
                                </form>
                            @endif

                            <p></p>

                            <form>
                                <div class="row">
                                    <div class="col-lg-6">
                                        <h2 class="title-h2">Signature</h2>
                                    </div>
                                </div>
                                <hr>
                            </form>
                            @if ($agent->signUpload == true)
                                <div class="row">
                                    <div class="col-lg-6">
                                        <img src="{{asset('project/public/agentssign/'.$agent->signaturePic)}}" id="uploadedID" width="80" height="80" />
                                    </div>
                                </div>
                                <form method="post" action="{{ route('uploadAstrogSign') }}" enctype="multipart/form-data">
                                    @csrf
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <input type="file" name="signature" id="signature" accept="image/*" />
                                            <button id="upPhoto" class="btn btn-blue">Upload</button>
                                        </div>
                                        <div class="col-lg-6">
                                            <img src="{{asset('images/pen.svg')}}" id="uploadedPhoto" width="80" height="80" />
                                        </div>
                                    </div>
                                </form>
                            @else
                                <form method="post" action="{{ route('uploadAstrogSign') }}" enctype="multipart/form-data">
                                    @csrf
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <input type="file" name="signature" id="signature" accept="image/*" required/>
                                            <button id="upPhoto" class="btn btn-blue">Upload</button>
                                        </div>
                                        <div class="col-lg-6">
                                            <img src="{{asset('images/pen.svg')}}" id="uploadedPhoto" width="80" height="80" />
                                        </div>
                                    </div>
                                </form>
                            @endif
                            <br>
                            <hr>
                            @if($agent->natidUpload == true AND $agent->signUpload == true)
                                <a href="{{url('home')}}" class="btn btn-blue">Finished, Let me make Money</a>
                            @endif

                        </div>
                    </div>
                </div>
            </div>

            <div class="status">
                <div class="col-lg-12">
                    <h3>
                        <div class="d-none d-lg-block">
                        </div>
                    </h3>
                </div>
            </div>
        </div>
    </div>
</section>

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

<script>
    $(document).ready(function(){
        $('form').ajaxForm({
            beforeSend:function(){
                $('#success').empty();
            },
            uploadProgress:function(event, position, total, percentComplete) {
                $('.progress-bar-nat').text(percentComplete + '%');
                $('.progress-bar-nat').css('width', percentComplete + '%');
            },
            success:function(data) {
                if(data.errors) {
                    $('.progress-bar-nat').text('0%');
                    $('.progress-bar-nat').css('width', '0%');
                    $('#success').html('<span class="text-danger"><b>'+data.errors+'</b></span>');
                }
                if(data.success) {
                    $('.progress-bar-nat').text('Uploaded');
                    $('.progress-bar-nat').css('width', '100%');
                    $('#success').html('<span class="text-success"><b>'+data.success+'</b></span><br /><br />');
                    $('#success').append(data.image);
                }
            }
        });
    });

    $(document).ready(function(){
        $('form').ajaxForm({
            beforeSend:function(){
                $('#success').empty();
            },
            uploadProgress:function(event, position, total, percentComplete) {
                $('.progress-bar-photo').text(percentComplete + '%');
                $('.progress-bar-photo').css('width', percentComplete + '%');
            },
            success:function(data) {
                if(data.errors) {
                    $('.progress-bar-photo').text('0%');
                    $('.progress-bar-photo').css('width', '0%');
                    $('#success').html('<span class="text-danger"><b>'+data.errors+'</b></span>');
                }
                if(data.success) {
                    $('.progress-bar-photo').text('Uploaded');
                    $('.progress-bar-photo').css('width', '100%');
                    $('#success').html('<span class="text-success"><b>'+data.success+'</b></span><br /><br />');
                    $('#success').append(data.image);
                }
            }
        });

    });

    $(document).ready(function(){
        $('form').ajaxForm({
            beforeSend:function(){
                $('#success').empty();
            },
            uploadProgress:function(event, position, total, percentComplete) {
                $('.progress-bar-payslip').text(percentComplete + '%');
                $('.progress-bar-payslip').css('width', percentComplete + '%');
            },
            success:function(data) {
                if(data.errors) {
                    $('.progress-bar-payslip').text('0%');
                    $('.progress-bar-payslip').css('width', '0%');
                    $('#success').html('<span class="text-danger"><b>'+data.errors+'</b></span>');
                }
                if(data.success) {
                    $('.progress-bar-payslip').text('Uploaded');
                    $('.progress-bar-payslip').css('width', '100%');
                    $('#success').html('<span class="text-success"><b>'+data.success+'</b></span><br /><br />');
                    $('#success').append(data.image);
                }
            }
        });

    });
</script>

</body>
</html>
