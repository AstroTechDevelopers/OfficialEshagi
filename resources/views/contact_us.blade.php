<!doctype html>
<html lang="zxx" class="theme-light">
    <head>
        <!-- Required meta tags -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <!-- Bootstrap CSS --> 
       <link rel="stylesheet" href="{{ asset('front_css/bootstrap.min.css') }}">
        <!-- Animate CSS --> 
        <link rel="stylesheet" href="{{ asset('front_css/animate.min.css') }}">
        <!-- Meanmenu CSS -->
        <link rel="stylesheet" href="{{ asset('front_css/meanmenu.css') }}">
        <!-- Boxicons CSS -->
        <link rel="stylesheet" href="{{ asset('front_css/boxicons.min.css') }}">
        <!-- Flaticon CSS -->
        <link rel="stylesheet" href="{{ asset('front_css/flaticon.css') }}">
        <!-- Carousel CSS -->
        <link rel="stylesheet" href="{{ asset('front_css/owl.carousel.min.css') }}">
        <!-- Carousel Default CSS -->
        <link rel="stylesheet" href="{{ asset('front_css/owl.theme.default.min.css') }}">
        <!-- Magnific Popup CSS -->
        <link rel="stylesheet" href="{{ asset('front_css/magnific-popup.min.css') }}">
        <!-- Nice Select CSS -->
        <link rel="stylesheet" href="{{ asset('front_css/nice-select.min.css') }}">
        <!-- Odometer CSS -->
        <link rel="stylesheet" href="{{ asset('front_css/odometer.min.css') }}">
        <!-- Style CSS -->
        <link rel="stylesheet" href="{{ asset('front_css/style.css') }}">
        <!-- Dark CSS -->
        <link rel="stylesheet" href="{{ asset('front_css/dark.css') }}">
        <!-- Responsive CSS -->
		<link rel="stylesheet" href="{{ asset('front_css/responsive.css') }}">
		
		<title>AstroCred | Africa's Leading Loans & Store Credit Provider</title>

        <link rel="icon" type="image/png" href="{{ asset('front_img/favicon.png') }}">
   
    </head>

    <body>
        
         
        <!-- Start Navbar Area -->
        <div class="navbar-area">
            <div class="main-responsive-nav">
                <div class="container">
                    <div class="main-responsive-menu">
                        <div class="logo">
                            <a href="#home">
                                <img src="{{ asset('front_img/logo.png') }}" class="black-logo" alt="image">
                                <img src="{{ asset('front_img/logo2.png') }}" class="white-logo" alt="image">
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="main-navbar">
                <div class="container-fluid">
                    <nav class="navbar navbar-expand-md navbar-light">
                        <a class="navbar-brand" href="#home">
                            <img src="{{ asset('front_img/logo.png') }}" class="black-logo" alt="image">
                            <img src="{{ asset('front_img/logo2.png') }}" class="white-logo" alt="image">
                        </a>

                        <div class="collapse navbar-collapse mean-menu" id="navbarSupportedContent">
                            <ul class="navbar-nav">
                                <li class="nav-item">
                                    <a href="/" class="nav-link active">
                                        Home 
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a href="/" class="nav-link">
                                        About Us
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a href="/" class="nav-link">
                                        Benefits
                                    </a>
                                </li>
                               
                                <li class="nav-item">
                                    <a href="/" class="nav-link">
                                        How it works
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a href="/" class="nav-link">
                                        Products
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a href="#" class="nav-link">
                                        Merchants 
                                        <i class='bx bx-chevron-down'></i>
                                    </a>
                                    <ul class="dropdown-menu">
                                        <li class="nav-item">
                                            <a href="{{route('login')}}" class="nav-link">
                                                Login
                                            </a>
                                        </li>

                                        <li class="nav-item">
                                            <a href="{{route('regista-patina')}}" class="nav-link">
                                                Register
                                            </a>
                                        </li>
                                    </ul>
                                </li>

                                <li class="nav-item">
                                    <a href="/contact_us" class="nav-link">
                                        Contact Us
                                    </a>
                                </li>
                            </ul>

                            <div class="others-options d-flex align-items-center">
                                <div class="option-item">
                                    <a href="{{route('login')}}" class="default-btn">
                                        Login
                                        <span></span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </nav>
                </div>
            </div>
        </div>
        <!-- End Navbar Area -->

        <!-- Start Page Title Area -->
        <div class="page-title-area item-bg-1">
            <div class="d-table">
                <div class="d-table-cell">
                    <div class="container">
                        <div class="page-title-content">
                            <h2>Contact Us</h2>
                            <ul>
                                <li><a href="index.html">Home</a></li>
                                <li>Contact Us</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Page Title Area -->

        <!-- Start Contact Area -->
        <section class="contact-area ptb-100">
            <div class="container">
                <div class="section-title">
                    <span>Let's discuss</span>
                    <h2>Get in touch</h2>
                    <p>Need help with our credit products or application process. Please get in touch with our customer services team 24/7.</p>
                </div>

                <div class="row">
                    <div class="col-lg-8">
                        <div class="contact-form">
                            <div class="title">
                                <h3>Write to Us</h3>
                            </div>
        
                            <form id="contactForm">
                                <div class="row">
                                    <div class="col-lg-6 col-md-12">
                                        <div class="form-group">
                                            <label>Name</label>
                                            <input type="text" name="name" id="name" class="form-control" required data-error="Please enter your name">
                                            <div class="help-block with-errors"></div>
                                        </div>
                                    </div>
        
                                    <div class="col-lg-6 col-md-12">
                                        <div class="form-group">
                                            <label>Email Address</label>
                                            <input type="email" name="email" id="email" class="form-control" required data-error="Please enter your email">
                                            <div class="help-block with-errors"></div>
                                        </div>
                                    </div>
        
                                    <div class="col-lg-12 col-md-12">
                                        <div class="form-group">
                                            <label>Subject</label>
                                            <input type="text" name="msg_subject" id="msg_subject" class="form-control" required data-error="Please enter your subject">
                                            <div class="help-block with-errors"></div>
                                        </div>
                                    </div>
        
                                    <div class="col-lg-12 col-md-12">
                                        <div class="form-group">
                                            <label>Message</label>
                                            <textarea name="message" class="form-control" id="message" cols="30" rows="5" required data-error="Write your message"></textarea>
                                            <div class="help-block with-errors"></div>
                                        </div>
                                    </div>
        
                                    <div class="col-lg-12 col-md-12">
                                        <button type="submit" class="default-btn">
                                            Send message
                                            <span></span>
                                        </button>
                                        <div id="msgSubmit" class="h3 text-center hidden"></div>
                                        <div class="clearfix"></div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <div class="contact-side-box">
                            <div class="title">
                                <h3>Contact Finix</h3>
                            </div>
        
                            <div class="info-box">
                                <div class="icon">
                                    <i class="flaticon-clock"></i>
                                </div>
                                <h4>Working Hours</h4>
                                <ul class="list">
                                    <li>
                                        Mon – Fri
                                        <span>8:00 AM - 5:00 PM</span>
                                    </li>
                                    <li>
                                        Sat
                                        <span>8:00 AM - 1:00 PM</span>
                                    </li>
                                    <li>
                                        Sun
                                        <span>CLOSED</span>
                                    </li>
                                </ul>
                            </div>

                            <div class="info-box">
                                <div class="icon">
                                    <i class="flaticon-pin"></i>
                                </div>
                                <h4>Address</h4>
                                <span>Chigwilizana Road Lusaka ZM 10101, 79 Great East Road Cnr</span>
                            </div>

                            <div class="info-box">
                                <div class="icon">
                                    <i class="flaticon-phone-call"></i>
                                </div>
                                <h4>Phone</h4>
                                <span>
                                    <a href="tel:+260976222956">(+260) 976 222 956 </a>
                                </span>
                                <span>
                                    <a href="tel:+260956998040">(+260) 956 998 040</a>
                                </span>
                            </div>

                            <div class="info-box">
                                <div class="icon">
                                    <i class="flaticon-email"></i>
                                </div>
                                <h4>Email</h4>
                                <span>
                                    <a href="mailto:info@astrocred.com">info@astrocred.com</a>
                                </span>
                                <span>
                                    <a href="mailto:hello@astrocred.com">hello@astrocred.com</a>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- End Contact Area -->

        <!-- Start Map Area -->
        <div class="map">
            <div class="container-fluid">
                <iframe src="https://www.google.com/maps/embed?pb=!1m14!1m12!1m3!1d1359.950681572792!2d28.30248905038236!3d-15.402385175504266!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!5e0!3m2!1sen!2szm!4v1683892173199!5m2!1sen!2szm" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
            </div>
        </div>
        <!-- End Map Area -->
        
       
        <!-- Start Footer Area -->
        <section class="footer-area pt-100 pb-70">
            <div class="container">
                <div class="row">

                    <div class="col-lg-3 col-md-6">
                        <div class="single-footer-widget">
                            <h3>AstroCred Zambia</h3>
                         <div class="logo">
                                <a href="#">
                                    <img src="{{ asset('front_img/logo2.png') }}" alt="image">
                                </a>
                            </div>
                            <p style="padding-bottom: 20px;">eShagi Financial Services (Pvt) Ltd is registered in Zambia, Company No: 9002/2019</p>
                        </div>
                    </div>
<!-- 
                    <div class="col-lg-3 col-md-6">
                        <div class="single-footer-widget">
                            <h3>Quick Links</h3>

                            <ul class="quick-links">
                                <li>
                                    <a href="about.html">About</a>
                                </li>
                                <li>
                                    <a href="#">Our Performance</a>
                                </li>
                             
                                <li>
                                    <a href="news.html">Blog</a>
                                </li>
                                <li>
                                    <a href="contact.html">Contact</a>
                                </li>
                            </ul>
                        </div>    
                    </div> -->

             
                    <div class="col-lg-3 col-md-6">
                        <div class="single-footer-widget">
                            <h3>Other Resources</h3>

                            <ul class="quick-links">
                                <li>
                                    <a href="/faq">Help (FAQ)</a>
                                </li>
                                <li>
                                    <a href="/privacy_policy">Privacy Policy</a>
                                </li>
                                <li>
                                    <a href="/terms_and_conditions">Terms of Service</a>
                                </li>
                            </ul>
                        </div>    
                    </div>

                    <div class="col-lg-3 col-md-6">
                        <div class="single-footer-widget">
                            <h3>Contact Us</h3>

                            <div class="info-contact">
                                <i class="flaticon-pin"></i>
                                <span>79 Great East Road Cnr, Chigwilizana Road Lusaka ZM 10101, ZAMBIA</span>
                            </div>

                            <div class="info-contact">
                                <i class="flaticon-mail"></i>
                                <span>
                                    <a href="mailto:info@AstroCred.com">info@astrocred.com</a>
                                </span>
                            </div>

                            <div class="info-contact">
                                <i class="flaticon-telephone"></i>
                                <span>
                                    <a href="tel:+260976222956">+260 976 222 956</a>
                                </span>
                                <span>
                                    <a href="tel:+260956998040">+260 956 998 040</a>
                                </span>
                            </div>
                          <ul class="social">
                                <li>
                                    <b>Follow us:</b>
                                </li>
<!--                                 
                                <li>
                                    <a href="#" target="_blank">
                                        <i class="flaticon-twitter"></i>
                                    </a>
                                </li>
            
                                <li>
                                    <a href="#" target="_blank">
                                        <i class="flaticon-instagram"></i>
                                    </a>
                                </li> -->
            
                                <li>
                                    <a href="https://web.facebook.com/astrotechZM" target="_blank">
                                        <i class="flaticon-facebook"></i>
                                    </a>
                                </li>
                                
                                <!-- <li>
                                    <a href="#" target="_blank">
                                        <i class="flaticon-linkedin"></i>
                                    </a>
                                </li> -->
                            </ul>
                        </div>
                    </div>
                    
                        <div class="col-lg-3 col-md-6">
                        <div class="single-footer-widget">
                            <h3>Download Our App</h3>
                            <p style="padding-bottom: 20px;">Get quick and hassle-free loans with our mobile app right from the comfort of your home, no paper work needed.</p>
                            <div class="logo">
                                <a href="https://play.google.com/store/apps?hl=en&gl=US">
                                    <img src="{{ asset('front_img/play_icon.png') }}" alt="image">
                                </a>
                                <br>
                                <a href="https://apps.apple.com/us/app/apple-store/id375380948">
                                    <img src="{{ asset('front_img/apple_icon.png') }}" alt="image">
                                </a>
                            </div>
        
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- End Footer Area -->

        <!-- Start Copy Right Area -->
        <div class="copy-right-area">
            <div class="container">
                <div class="copy-right-content">
                    <p>
                        Copyright @<script>document.write(new Date().getFullYear())</script>
                        <a href="#" target="_blank">
                            AstroTech.
                        </a>
                        All rights reserved.
                    </p>
                </div>
            </div>
        </div>
        <!-- End Copy Right Area -->

        <!-- Start Go Top Area -->
        <div class="go-top">
            <i class='bx bx-chevron-up'></i>
        </div>
        <!-- End Go Top Area -->

        <!-- Dark version -->
        <div class="dark-version">
            <label id="switch" class="switch">
                <input type="checkbox" onchange="toggleTheme()" id="slider">
                <span class="slider round"></span>
            </label>
        </div>
        <!-- Dark version -->

               <script src="{{ asset('front_js/jquery.min.js') }}"></script>
        <!-- Popper JS -->
        <script src="{{ asset('front_js/popper.min.js') }}"></script>
        <!-- Bootstrap JS -->
        <script src="{{ asset('front_js/bootstrap.min.js') }}"></script>
        <!-- Meanmenu JS -->
        <script src="{{ asset('front_js/jquery.meanmenu.js') }}"></script>
        <!-- Carousel JS -->
        <script src="{{ asset('front_js/owl.carousel.min.js') }}"></script>
        <!-- Nice Select JS -->
        <script src="{{ asset('front_js/jquery.nice-select.min.js') }}"></script>
        <!-- Magnific Popup JS -->
        <script src="{{ asset('front_js/jquery.magnific-popup.min.js') }}"></script>
        <!-- Odometer JS -->
        <script src="{{ asset('front_js/odometer.min.js') }}"></script>
        <!-- Appear JS -->
        <script src="{{ asset('front_js/jquery.appear.min.js') }}"></script>
        <!-- Form Ajaxchimp JS -->
		<script src="{{ asset('front_js/jquery.ajaxchimp.min.js') }}"></script>
		<!-- Form Validator JS -->
		<script src="{{ asset('front_js/form-validator.min.js') }}"></script>
		<!-- Contact JS -->
        <script src="{{ asset('front_js/contact-form-script.js') }}"></script>
        <!-- Wow JS -->
		<script src="{{ asset('front_js/wow.min.js') }}"></script>
        <!-- Custom JS -->
        <script src="{{ asset('front_js/main.js') }}"></script>
    </body>
</html>