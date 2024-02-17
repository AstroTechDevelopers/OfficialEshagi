<?php
/**
 * Created by PhpStorm for eshagitwo
 * User: VinceGee
 * Date: 7/25/2022
 * Time: 1:50 AM
 */ ?>
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
    <script type="text/javascript">window.$crisp=[];window.CRISP_WEBSITE_ID="ea06615c-57f5-4ff8-802c-8f530a09b22b";(function(){d=document;s=d.createElement("script");s.src="https://client.crisp.chat/l.js";s.async=1;d.getElementsByTagName("head")[0].appendChild(s);})();</script>

    <style>
        *, *:before, *:after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            background: #ededed;
        }

        input, button {
            border: none;
            outline: none;
            background: none;
        }

        .tip {
            font-size: 20px;
            margin: 40px auto 50px;
            text-align: center;
        }

        .cont {
            overflow: hidden;
            position: relative;
            width: 900px;
            height: 550px;
            margin: 0 auto 100px;
            background: #fff;
        }

        .form {
            position: relative;
            width: 500px;
            height: 100%;
            transition: transform 1.2s ease-in-out;
            padding: 50px 30px 0;
        }

        .sub-cont {
            overflow: hidden;
            position: absolute;
            left: 640px;
            top: 0;
            width: 900px;
            height: 100%;
            padding-left: 260px;
            background: #fff;
            transition: transform 1.2s ease-in-out;
        }
        .cont.s--signup .sub-cont {
            transform: translate3d(-640px, 0, 0);
        }

        .img {
            overflow: hidden;
            z-index: 2;
            position: absolute;
            left: 0;
            top: 0;
            width: 260px;
            height: 100%;
            padding-top: 360px;
        }
        .img:before {
            content: "";
            position: absolute;
            right: 0;
            top: 0;
            width: 900px;
            height: 100%;
            background-color:#1dbc1d;
            transition: transform 1.2s ease-in-out;
        }
        .img:after {
            content: "";
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background:#1dbc1d;
        }
        .cont.s--signup .img:before {
            transform: translate3d(640px, 0, 0);
        }
        .img__text {
            z-index: 2;
            position: absolute;
            left: 0;
            top: 50px;
            width: 100%;
            padding: 0 20px;
            text-align: center;
            color: #fff;
            transition: transform 1.2s ease-in-out;
        }
        .img__text h2 {
            margin-bottom: 10px;
            font-weight: normal;
        }
        .img__text p {
            font-size: 14px;
            line-height: 1.5;
        }
        .cont.s--signup .img__text.m--up {
            transform: translateX(520px);
        }
        .img__text.m--in {
            transform: translateX(-520px);
        }
        .cont.s--signup .img__text.m--in {
            transform: translateX(0);
        }
        .img__btn {
            overflow: hidden;
            z-index: 2;
            position: relative;
            width: 100px;
            height: 36px;
            margin: 0 auto;
            background: transparent;
            color: #fff;
            text-transform: uppercase;
            font-size: 15px;
            cursor: pointer;
        }
        .img__btn:after {
            content: "";
            z-index: 2;
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            border: 2px solid #fff;
            border-radius: 30px;
        }
        .img__btn span {
            position: absolute;
            left: 0;
            top: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            width: 100%;
            height: 100%;
            transition: transform 1.2s;
        }
        .img__btn span.m--in {
            transform: translateY(-72px);
        }
        .cont.s--signup .img__btn span.m--in {
            transform: translateY(0);
        }
        .cont.s--signup .img__btn span.m--up {
            transform: translateY(72px);
        }

        h2 {
            width: 100%;
            font-size: 26px;
            text-align: center;
        }

        label {
            display: block;
            width: 260px;
            margin: 25px auto 0;
            text-align: center;
        }
        label span {
            font-size: 12px;
            color: #cfcfcf;
            text-transform: uppercase;
        }

        .sign-in {
            transition-timing-function: ease-out;
        }
        .cont.s--signup .sign-in {
            transition-timing-function: ease-in-out;
            transition-duration: 1.2s;
            transform: translate3d(640px, 0, 0);
        }

        .sign-up {
            transform: translate3d(-900px, 0, 0);
        }
        .cont.s--signup .sign-up {
            transform: translate3d(0, 0, 0);
        }

        .header_spacing{
            margin-top:10%;
        }

    </style>
    @include('scripts.ga-analytics')
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
                <a href="{{url('/')}}"><img class="navbar-brand" src="{{asset('images/logo_official.png')}}" alt="" width="200px" height="200px"></a>

            </div>
            <a href="https://eshagi.com" class="menu-nav-trigger-login" style="color:#ffffff">
                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="44px" height="44px" viewBox="0 0 24 24" version="1.1" style="color:#ffffff">
                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                        <polygon points="0 0 24 0 24 24 0 24"></polygon>
                        <rect fill="#ffffff" transform="translate(12.000000, 12.000000) scale(-1, 1) rotate(-90.000000) translate(-12.000000, -12.000000) " x="11" y="5" width="2" height="14" rx="1" style="color:#ffffff"></rect>
                        <path d="M3.7071045,15.7071045 C3.3165802,16.0976288 2.68341522,16.0976288 2.29289093,15.7071045 C1.90236664,15.3165802 1.90236664,14.6834152 2.29289093,14.2928909 L8.29289093,8.29289093 C8.67146987,7.914312 9.28105631,7.90106637 9.67572234,8.26284357 L15.6757223,13.7628436 C16.0828413,14.136036 16.1103443,14.7686034 15.7371519,15.1757223 C15.3639594,15.5828413 14.7313921,15.6103443 14.3242731,15.2371519 L9.03007346,10.3841355 L3.7071045,15.7071045 Z" fill="#ffffff" fill-rule="evenodd" transform="translate(9.000001, 11.999997) scale(-1, -1) rotate(90.000000) translate(-9.000001, -11.999997) "></path>
                    </g>
                </svg>
            </a>
        </nav>
    </header>

    <section class=" padd-80">
        <div class="container">
            <div class="contact-inner">
                <div class="row-centered">
                    <div class="container">
                        <div class="header_spacing"></div>
                        <div class="cont">
                            <div class="form sign-in">
                                <h1 class="text-center">Merchant</h1> <br>
                                <h3 class="text-center">We will facilitate the selling of your own products on credit and we will pay you full amount upfront of the product sold.</h3>
                                <br><br><br>
                                <div class="" style="align-content: center;">
                                    <a href="{{route('quick.register')}} }" class="btn btn-blue">Merchant Registration</a>
                                </div>
                            </div>
                            <div class="sub-cont">
                                <div class="img">
                                    <div class="img__text m--up">
                                        <h2>Astrogent?</h2>
                                        <p>Astro + Agent = You! <br>
                                            As an astrogent you will be paid via commission for each successful loan application on behalf of a client.
                                        </p>
                                    </div>
                                    <div class="img__text m--in">
                                        <h2>Merchant?</h2>
                                        <p>Boost your sales by 200%</p>
                                    </div>
                                    <div class="img__btn">
                                        <span class="m--up">Astrogent</span>
                                        <span class="m--in">Merchant</span>
                                    </div>
                                </div>
                                <div class="form sign-up">
                                    <h1 class="text-center">Astrogent</h1> <br>
                                    <p class="text-center">As an Astrogent, you will work from the comfort of your home, all you need is a smartphone. <br><br>
                                        <span class="font-weight-bold">How it works</span><br><br>
                                        You will receive daily leads from eShagi and for every product sale or loan application you will be a paid a commission. Easy!</p>
                                    <br><br><br>
                                    <div class="" style="align-content: center;">
                                        <a href="{{route('astrogent.register')}}" class="btn btn-blue">Astrogent Registration</a>
                                    </div>
                                </div>
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

<script>
    document.querySelector('.img__btn').addEventListener('click', function() {
        document.querySelector('.cont').classList.toggle('s--signup');
    });
</script>

@if(config('settings.googleMapsAPIStatus'))
    {!! HTML::script('//maps.googleapis.com/maps/api/js?key='.config("settings.googleMapsAPIKey").'&libraries=places&dummy=.js', array('type' => 'text/javascript')) !!}
@endif

</body>
</html>
