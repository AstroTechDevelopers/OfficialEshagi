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
    <a href="#menu-nav" class="menu-nav-trigger">Menu
        <span class="menu-nav-icon"></span>
        <svg x="0px" y="0px" width="54px" height="54px" viewBox="0 0 54 54">
            <circle fill="transparent" stroke="#656e79" stroke-width="1" cx="27" cy="27" r="25" stroke-dasharray="157 157" stroke-dashoffset="157"></circle>
        </svg>
    </a>

    <div id="menu-nav" class="menu-nav">
        <div class="menu-navigation-wrapper">
            <div class="menu-half-block">
                <h2>Menu</h2>
                <nav>
                    <ul class="menu-primary-nav">
                        <li><a href="{{url('/#about')}}" class="selected section-scroll">About Eshagi</a></li>
                        <li><a href="{{url('/#features')}}" class="section-scroll">Why choose Eshagi</a></li>
                        <li><a href="{{url('/#video-features')}}" class="section-scroll">Watch Video</a></li>
                        <li><a href="{{url('/#pricing')}}" class="section-scroll">What we offer</a></li>
                        <li><a href="{{url('/#testimonials')}}" class="section-scroll">Testimonials</a></li>
                        <li><a href="{{url('/#contact')}}" class="section-scroll">Contact</a></li>
                        <li><a href="{{route('partner-login')}}" >Partner Login</a></li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>

    <header class="navbar-header clearfix">
        <nav class="navbar navbar-expand-lg fixed-top ">
            <div class="container">
                <img class="navbar-brand" src="{{asset('images/logo_official.png')}}" alt="eShagi" width="200px" height="200px">

                <div style="right:0">
                    <a href="{{route('quick.register')}}" class="btn btn-blue">Register</a>
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
                            <h1 id="loginToStart">{{ __('Reset Password') }}</h1>
                            <p id="loginToStart2"></p>
                            @include('partials.fe-status')
                            <div class="col-lg-8 col-md-6">
                                <form method="POST" action="{{ route('password.email') }}">
                                    @csrf
                                    <div class="row" id="whole">
                                        <dixv class="col-lg-6">
                                            <div class="form-group" id="myid">
                                                <label>Email</label>
                                                <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required>
                                            </div>

                                            <input class="btn btn-success btn-send" id="loginbtn" type="submit" value="{{ __('Send Password Reset Link') }}">
                                        </dixv>
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

</body>
</html>
