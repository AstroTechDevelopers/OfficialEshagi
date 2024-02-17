<?php
/**
 * Created by PhpStorm for eshagitwo
 * User: VinceGee
 * Date: 2/21/2022
 * Time: 5:03 PM
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
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>AstroCred| Zambia's Leading Loans & Store Credit Provider</title>

    <style>
        @import url('https://fonts.googleapis.com/css?family=Open+Sans:300,400,800');

        @media (min-width: 500px) {
            .col-sm-6 {
                width: 50%;
            }
        }
        html, body {
            height: 100%;
            min-height: 18em;
        }

        .frontend-side {
            background-image: url({{asset('/images/zimbabwe-flag-wrinkled.jpg')}});
        }

        .uiux-side {
            background-image: url({{asset('/images/zambia-flag-wrinkled.jpg')}});
        }

        .split-pane {
            padding-top: 1em;
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center center;
            height: 50%;
            min-height: 9em;
            font-size: 2em;
            color: white;
            font-family: 'Open Sans', sans-serif;
            font-weight:300;
        ;
        }
        @media(min-width: 500px) {
            .split-pane {
                padding-top: 2em;
                height: 100%;
            }
        }
        .split-pane > div {
            position: relative;
            top: 50%;
            -webkit-transform: translateY(-50%);
            -ms-transform: translateY(-50%);
            transform: translateY(-50%);
            text-align: center;
        }
        .split-pane > div .text-content {
            line-height: 1.6em;
            margin-bottom: 1em;
        }
        .split-pane > div .text-content .big {
            font-size: 2em;
        }
        .split-pane > div img {
            height: 1.3em;
        }
        @media (max-width: 500px) {
            .split-pane > div img {
                display:none;
            }
        }
        .split-pane button, .split-pane a.button {
            font-family: 'Open Sans', sans-serif;
            font-weight:800;
            background: none;
            border: 1px solid white;
            -moz-border-radius: 5px;
            -webkit-border-radius: 5px;
            border-radius: 5px;
            width: 15em;
            padding: 0.7em;
            font-size: 0.5em;
            -moz-transition: all 0.2s ease-out;
            -o-transition: all 0.2s ease-out;
            -webkit-transition: all 0.2s ease-out;
            transition: all 0.2s ease-out;
            text-decoration: none;
            color: white;
            display: inline-block;
            cursor: pointer;
        }
        .split-pane button:hover, .split-pane a.button:hover {
            text-decoration: none;
            background-color: white;
            border-color: white;
            cursor: pointer;
        }
        .uiux-side.split-pane button:hover, .split-pane a.button:hover {
            color: violet;
        }
        .frontend-side.split-pane button:hover, .split-pane a.button:hover {
            color: blue;
        }

        #split-pane-or {
            font-size: 2em;
            color: white;
            font-family: 'Open Sans', sans-serif;
            text-align: center;
            width: 100%;
            position: absolute;
            top: 50%;
            -webkit-transform: translateY(-50%);
            -ms-transform: translateY(-50%);
            transform: translateY(-50%);
        }
        @media (max-width: 925px) {
            #split-pane-or {
                top:15%;
            }
        }
        #split-pane-or > div img {
            height: 2.5em;
        }
        @media (max-width: 500px) {
            #split-pane-or {
                position: absolute;
                top: 50px;
            }
            #split-pane-or > div img {
                height:2em;
            }
        }
        @media(min-width: 500px) {
            #split-pane-or {
                font-size: 3em;
            }
        }
        .big {
            font-size: 2em;
        }

        #slogan {
            position: absolute;
            width: 100%;
            z-index: 100;
            text-align: center;
            vertical-align: baseline;
            top: 0.5em;
            color: white;
            font-family: 'Open Sans', sans-serif;
            font-size: 1.4em;
        }
        @media(min-width: 500px) {
            #slogan {
                top: 5%;
                font-size: 1.8em;
            }
        }
        #slogan img {
            height: 0.7em;
        }
        .bold {
            text-transform:uppercase;
        }
        .big {
            font-weight:800;
        }
    </style>

    <link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body data-spy="scroll" data-target=".navbar-default" data-offset="100">
<div class='split-pane col-xs-12 col-sm-6 uiux-side'>
    <div>
        <img src='{{asset('/images/Flag_of_Zambia.gif')}}'>
        <div class='text-content'>
            <div class="bold">Are you from</div>
            <div class='big'>ZAMBIA?</div>
        </div>
        <a class='button' href="{{route('register.zambian')}}">
            ZAMBIAN ALL THE WAY
        </a>
    </div>
</div>
<div class='split-pane col-xs-12 col-sm-6 frontend-side'>
    <div>
        <img src='{{asset('/images/Flag_of_Zimbabwe.gif')}}'>
        <div class='text-content'>
            <div class="bold">Are you a </div>
            <div class='big'>ZIMBO?</div>
        </div>
        <a class='button' href="{{route('quick.register')}}">
            ZIMBABWEAN HERE
        </a>
    </div>
</div>
<div id='split-pane-or'>
    <div>
        <img src='{{asset('images/logo_official.png')}}' height="400">
    </div>
</div>
</body>
</html>




