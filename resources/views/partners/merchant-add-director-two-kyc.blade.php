<?php
/**
 *Created by PhpStorm for eshagi
 *User: Vincent Guyo
 *Date: 9/24/2020
 *Time: 10:41 PM
 */

?>
    <!DOCTYPE html>
<html class="no-js" lang="zxx">
<head>
        <!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-Y996J4MKXZ"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-Y996J4MKXZ');
</script>
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
            <!--<div style="right:0">
                <ul>
                    <li>
                        <a href="{{ route('logout')}}" onclick="event.preventDefault();
                                             document.getElementById('logout-form').submit();" type="button" class="btn btn-blue">Logout</a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </li>
                </ul>
            </div>-->
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
                        <p class="wow fadeInUp" data-wow-duration="1s" data-wow-delay=".2s">Please upload Documents of Director 2</p>
                        <div class="container" >
						    <form method="post" action="{{ route('upload.kyc.director.two') }}" enctype="multipart/form-data">
							    @csrf
								<div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>First Name</label>
                                            <input class="form-control{{ $errors->has('dir_two_name') ? ' is-invalid' : '' }}" type="text" name="dir_two_name" id="dir_two_name" required="required" value="{{old('dir_two_name')}}" placeholder="Director name...">
                                            @if ($errors->has('dir_two_name'))
                                                <span class="invalid-feedback">
                                                    <strong>{{ $errors->first('dir_two_name') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6">
										<div class="form-group">
											<label>Last Name</label>
											<input class="form-control{{ $errors->has('dir_two_lname') ? ' is-invalid' : '' }}" type="text" name="dir_two_lname" id="dir_two_lname" required="required" value="{{old('dir_two_lname')}}" placeholder="Director last name...">
											@if ($errors->has('dir_two_lname'))
												<span class="invalid-feedback">
													<strong>{{ $errors->first('dir_two_lname') }}</strong>
												</span>
											@endif
										</div>
									</div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>National ID Number</label>
                                            <input class="form-control{{ $errors->has('dir_two_nid') ? ' is-invalid' : '' }}" type="text" name="dir_two_nid" id="dir_two_nid" required="required" value="{{old('dir_two_nid')}}" placeholder="Director name...">
                                            @if ($errors->has('dir_two_nid'))
                                                <span class="invalid-feedback">
                                                    <strong>{{ $errors->first('dir_two_nid') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
								<div class="row">
									<div class="col-lg-6">
										<h2 class="title-h2">National ID: Frontside</h2>
									</div>
								</div>
								<hr>                            
								<div class="row">
									<div class="col-lg-6">
										<input type="file" name="natid_front_dir_two" id="natid_front_dir_two" accept="image/*" required/>
									</div>
									<div class="col-lg-6">
										<p>1. Please upload a cropped image of your Frontside National ID <br>
										2. Backside National ID image should not be greater than 4MB. <br>
										3. Backside National ID image should of the format: jpeg,png,jpg,gif,svg. <br></p>
									</div>
								</div>
								<div class="row">
									<div class="col-lg-6">
										<h2 class="title-h2">National ID: Backside</h2>
									</div>
								</div>
								<hr>                            
								<div class="row">
									<div class="col-lg-6">
										<input type="file" name="natid_back_dir_two" id="natid_back_dir_two" accept="image/*" required/>
									</div>
									<div class="col-lg-6">
										<p>1. Please upload a cropped image of your Backside National ID <br>
										2. Backside National ID image should not be greater than 4MB. <br>
										3. Backside National ID image should of the format: jpeg,png,jpg,gif,svg. <br></p>
									</div>
								</div>
								<div class="row">
									<div class="col-lg-6">
										<h2 class="title-h2">Passport Size Photo</h2>
									</div>
								</div>
								<hr>                            
								<div class="row">
									<div class="col-lg-6">
										<input type="file" name="passport_two" id="passport_two" accept="image/*" required/>
									</div>
									<div class="col-lg-6">
										<p>1. Please upload a cropped image of your passport photo. <br>
											2. Passport Photo image should not be greater than 4MB. <br>
											3. Passport Photo image should of the format: jpeg,png,jpg,gif,svg. <br></p>
									</div>
								</div>
								<hr>
								<div class="row">
									<div class="col-lg-6">
									    <input type="hidden" name="mid" value="{{ $partnerid }}">
										<input class="btn btn-success btn-send" type="submit" value="Finished, Complete The Registration ">
									</div>
								</div>
							</form>
                        </div>
                    </div>
                </div>
            </div>

            <!--<div class="status">
                <div class="col-lg-12">
                    <h3>
                        <div class="d-none d-lg-block">
                        </div>
                    </h3>
                </div>
            </div>-->
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
</body>
</html>