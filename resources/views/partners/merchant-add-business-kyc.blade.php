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
                        <p class="wow fadeInUp" data-wow-duration="1s" data-wow-delay=".2s">Please upload Business Documents</p>

                        <div class="container" >
						    <form method="post" action="{{ route('upload.business.kyc') }}" enctype="multipart/form-data">
                                @csrf
								<div class="row">
									<div class="col-lg-6">
										<h2 class="title-h2">Certificate of Incorporation (PACRA)</h2>
									</div>
								</div>
								<hr>                            
								<div class="row">
									<div class="col-lg-6">
										<input type="file" name="inc_cert" id="inc_cert" accept="image/*" required/>
									</div>
									<div class="col-lg-6">
										<p>1. Please upload a cropped file of your Certificate of Incorporation <br>
										2. Certificate of Incorporation file should not be greater than 4MB. <br>
										3. Certificate of Incorporation file should of the format: jpeg,png,jpg,gif,svg,pdf. <br></p>
									</div>
								</div>
								{{--<div class="row">
									<div class="col-lg-6">
										<h2 class="title-h2">Proof of Residence</h2>
									</div>
								</div>
								<hr>
								<div class="row">
									<div class="col-lg-6">
										<input type="file" name="resproof" id="resproof" accept="image/*" required/>
									</div>
									<div class="col-lg-6">
										<p>1. Please upload proof of residence. <br>
											2. Proof of residence file should not be greater than 4MB. <br>
											3. Proof of residence file should of the format: jpeg,png,jpg,gif,svg,pdf. <br></p>
									</div>
								</div>--}}
								{{--<div class="row">
									<div class="col-lg-6">
										<h2 class="title-h2">ZRA Tax Registration Certificate</h2>
									</div>
								</div>
								<hr>
								<div class="row">
									<div class="col-lg-6">
										<input type="file" name="cr14" id="cr14" accept="image/*" required/>
									</div>
									<div class="col-lg-6">
										<p>1. Please upload ZRA Tax Registration Certificate. <br>
											2. ZRA Tax Registration Certificate file should not be greater than 4MB. <br>
											3. ZRA Tax Registration Certificate file should of the format: jpeg,png,jpg,gif,svg,pdf. <br></p>
									</div>
								</div>
								<div class="row">
									<div class="col-lg-6">
										<h2 class="title-h2">Business License</h2>
									</div>
								</div>
								<hr>
								
								<div class="row">
									<div class="col-lg-6">
										<input type="file" name="bizlicense" id="bizlicense" accept="image/*" required/>
									</div>
									<div class="col-lg-6">
										<p>1. Please upload business license. <br>
											2. Business license file should not be greater than 4MB. <br>
											3. Business license file should of the format: jpeg,png,jpg,gif,svg,pdf. <br></p>
									</div>
								</div>--}}
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