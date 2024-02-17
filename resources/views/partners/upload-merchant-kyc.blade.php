<?php
/**
 * Created by PhpStorm for eshagitwo
 * User: VinceGee
 * Date: 11/23/2021
 * Time: 1:37 PM
 */ ?>
    <!DOCTYPE html>
<html class="no-js" lang="en">
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

<!--[if lt IE 9]>
    <script src="{{asset('js/html5shiv.min.js')}}"></script>
    <script src="{{asset('js/respond.min.js')}}"></script>
    <![endif]-->
    @livewireStyles
    <x-notyf::styles/>
</head>
<body data-spy="scroll" data-target=".navbar-default" data-offset="100">

<header class="navbar-header clearfix">
    <nav class="navbar navbar-expand-lg fixed-top">
        <div class="container">
            <a class="navbar-brand" href="{{url('/home')}}"><img src="{{asset('images/logo.png')}}" alt=""></a>

            <div style="right:0">
                <a href="{{ route('logout')}}" onclick="event.preventDefault();
                                             document.getElementById('logout-form').submit();" type="button" class="btn btn-blue">Logout</a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
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

                        <h2 class="title-h2">Merchant Required Documents</h2>
                        <p>To avoid fraud and other security reasons we now need these documents for you to transact on eShagi.</p>

                        <hr>
                        <h2 class="title-h2">1. Certificate of Incorporation</h2> <hr>
                        <p>Please upload a clear copy of your Certificate of Incorporation in either image or PDF of not larger than 4MB in size.</p>

                        <livewire:upload-certificate-incorporation />

                        <hr>
                        <h2 class="title-h2">2. National ID’s of at least 2 Directors</h2> <hr>
                        <p>Please upload at least 2 National ID photos in image format of not larger than 4MB in size, each.</p>

                        <livewire:upload-national-ids />


                        <hr>
                        <h2 class="title-h2">3. Proof of Residence</h2> <hr>
                        <p>Please upload a clear copy of your Proof of Residence in either image or PDF of not larger than 4MB in size.</p>

                        <livewire:upload-proof-residence />

                        <hr>
                        <h2 class="title-h2">4. CR6</h2> <hr>
                        <p>Please upload a clear copy of your CR6 in either image or PDF of not larger than 4MB in size.</p>

                        <livewire:upload-cr6 />

                        <hr>
                        <h2 class="title-h2">5. CR14</h2> <hr>
                        <p>Please upload a clear copy of your CR14 in either image or PDF of not larger than 4MB in size.</p>

                        <livewire:upload-cr14 />

                        <hr>
                        <h2 class="title-h2">6. Business License</h2> <hr>
                        <p>Please upload a clear copy of your Business License in either image or PDF of not larger than 4MB in size.</p>

                        <livewire:upload-business-license />

                        <hr>
                        <h2 class="title-h2">7. Passport Photos of the 2 directors</h2> <hr>
                        <p>Please upload at least 2 passport sized photos in image format of not larger than 4MB in size, each.</p>

                        <livewire:upload-passport-photos />

                        <hr>
                        <h2 class="title-h2">8. Other</h2> <hr>
                        <p>Upload any other documents, that identify you as an organization</p>

                        <livewire:upload-merchantkyc-other />

                        <hr> <br><br><br>
                        <livewire:merchant-kyc-upload-checker :yuser="$yuser" :kyc="$kyc" />
                    </div>
                </div>
            </div>
        </div>
</section>
@include('partials.fe-footer')

<script>
    document.getElementById('other_details').style.visibility = "hidden";
    function businessTypeChanged(){

        if(document.getElementById('business_type').value != "Sole Trader"){
            document.getElementById('other_details').style.visibility = "visible";
        }



    }
</script>

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

@livewireScripts
<x-notyf::scripts/>
</body>
</html>
