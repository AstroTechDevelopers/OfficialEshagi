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
                                    <a href="#home" class="nav-link active">
                                        Home 
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a href="#about-us" class="nav-link">
                                        About Us
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a href="#benefits" class="nav-link">
                                        Benefits
                                    </a>
                                </li>
                               
                                <li class="nav-item">
                                    <a href="#how-it-works" class="nav-link">
                                        How it works
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a href="#products" class="nav-link">
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

        <!-- Start Main Banner Area -->
        <div id="home" class="main-banner-area bg-two">
            <div class="d-table">
                <div class="d-table-cell">
                    <div class="container-fluid">
                        <div class="main-banner-content">
                            <span>Here for you</span>
                            <h1>We make credit purchases simple & affordable!</h1>
                            <p>With AstroCred, you can get an instant loan or shop at any of your favourite stores across Zambia and pay in affordable monthly installments.</p>
                            
                            <div class="banner-btn">
                                <a href="{{route('quick.register')}}" class="default-btn">
                                    Apply now
                                    <span></span>
                                </a>
                            </div>
                        </div>

                        <div class="banner-social-buttons">
                            <ul>
                                <li>
                                    <span>Follow us</span>
                                </li>
            
                                <li>
                                    <a href="https://web.facebook.com/astrotechZM" target="_blank">
                                        <i class="flaticon-facebook"></i>
                                    </a>
                                </li>

                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Main Banner Area -->

        <!-- Start About Area Area -->
        <section id="about-us" class="about-area ptb-100">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-lg-6">
                        <div class="about-content">
                            <span>IT'S 2023 AFTER ALL</span>
                            <h3>A Fresh Approach</h3>
                            <p>We create magic for traditional banks and microfinance companies. AstroCred provides you with a hassle free way to access affordable credit and the freedom to spend whenever and wherever you choose.</p>
                            <p>Through our cutting-edge technology, we're transforming the way credit and financial services are delivered to millions of underserved people in Zambia and Africa. AstroCred is serving customers in Zimbabwe, Mozambique, Malawi, Kenya and South Africa; and we're still growing.
                            </p>
                            <div class="about-btn" style="padding-bottom: 15px">
                                <a href="{{route('quick.register')}}" class="default-btn">
                                    Lets Get Started
                                    <span></span>
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="about-wrap">
                            <img src="{{ asset('front_img/vehicle-loan.jpg') }}" alt="image">
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- End About Area Area -->

  

        
        <!-- Start Services Area -->
        <section id="benefits" class="services-area pt-100 pb-70">
            <div class="container">
                <div class="section-title">
                    <span>Why We Are Cool</span>
                    <h2>Why choose AstroCred</h2>
                    <p>Here is why more than 1000 of our customers across Zambia love and trust AstroCred.
                    </p>
                </div>

                <div class="row">
                    <div class="col-lg-4 col-md-6">
                        <div class="services-item">
                            <div class="icon">
                                <i class="flaticon-globe"></i>
                            </div>
                            <h3>100% Online Process</h3>
                            <p>No need to vist our office, complete paper forms or photocopy ID's.</p>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-6">
                        <div class="services-item">
                            <div class="icon">
                                <i class="flaticon-loan-1"></i>
                            </div>
                            <h3>Affordable Installments</h3>
                            <p>Choose to pay back our loans or credit in 1 to 60 months.</p>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-6">
                        <div class="services-item">
                            <div class="icon">
                                <i class="flaticon-responsibility"></i>
                            </div>
                            <h3>Everyone's Welcome</h3>
                            <p>We provide credit to both civil servants and the private sector.</p>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-6">
                        <div class="services-item">
                            <div class="icon">
                                <i class="flaticon-user"></i>
                            </div>
                            <h3>Web & Mobile</h3>
                            <p>Easily apply and mange your account via web or mobile app.</p>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-6">
                        <div class="services-item">
                            <div class="icon">
                                <i class="flaticon-satisfaction"></i>
                            </div>
                            <h3>Quick Approval</h3>
                            <p>We'll approve you loan or credit in under 30 minutes.</p>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-6">
                        <div class="services-item">
                            <div class="icon">
                                <i class="flaticon-loan-3"></i>
                            </div>
                            <h3>Spending Freedom</h3>
                            <p>You can shop on credit from over 1000 retail stores across Zambia.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- End Services Area -->


        <!-- Start Deserve Area -->
        <section id="how-it-works" class="deserve-area ptb-100">
            <div class="container">
                <div class="section-title">
                    <span>Application Process</span>
                    <h2>How it Works</h2>
                    <p>Below is our loan application process, the process is completed with 3 steps to get your loan approaved and dibursed.</p>
                </div>

                <div class="row align-items-center">
                    <div class="col-lg-6">
                        <div class="deserve-item">
                            <h3>We follow 3 steps to get your Salary/Store loan to you.</h3>

                            <div class="deserve-content">
                                <span>1</span>
                                <h4>Apply here or via WhatsApp</h4>
                                <p>Simply apply here or contact our team on WhatsApp to start your application. Please ensure you have your ID, payslip and employment details in hand. Once complete, you will have a pre-approval in under 30 minutes</p>
                            </div>

                            <div class="deserve-content">
                                <span>2</span>
                                <h4>Submit ID & KYC details</h4>
                                <p>Submit a selfie, picture of your ID, latest payslips to complete our online Know Your Customer (KYC) and Electronic credit agreement. Once done, grab a coffee. We will now complete our checks and final approvals.</p>
                            </div>

                            <div class="deserve-content">
                                <span>3</span>
                                <h4>Disbursement</h4>
                                <p>Voila! Your funds are ready. You can now request a fund payout to your designated bank account, get a shopping card and grab that new TV from your favourite store.</p>
                            </div>
                            
                            <div class="deserve-content">
                                <span>4</span>
                                <h4>Repayments</h4>
                            </div>
                            <div class="deserve-btn">
                                <a href="{{route('quick.register')}}" class="default-btn">
                                    Apply now
                                    <span></span>
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="rate-form bg-fdebeb">
                            <div class="rate-content">
                                <span>How much do you need?</span>
                                <h3>Loan Affordability Calculator</h3>
                            </div>
                                
    <form id="loanForm">

        <div class="form-group">
        <label>Gross Salary:</label>
        <input  class="form-control" type="number" id="grossSalary" placeholder="Enter gross salary" required>
        </div>

        <div class="form-group" >
        <label>Net Salary:</label>
        <input  class="form-control" type="number" id="netSalary" placeholder="Enter net salary" required>
        </div>

        <div class="form-group" >
        <label>Number of Months:</label>
        <input  class="form-control" type="number" id="months" placeholder="Enter number of months" required>
       </div>
        
       <div class="form-group" >
        <label>Amount to Borrow:</label>
        <input  class="form-control" type="number" id="amountToBorrow" placeholder="Enter amount to borrow" required>
       </div>
        <br>
        <div class="deserve-btn">
            <button type="button" onclick="calculateLoan()" class="default-btn">
                Calculate
                <span></span>
            </button>
            <button type="button" onclick="resetForm()" class="default-btn-clear">
                Clear Form
                <span></span>
            </button>
        </div>
    </form>
                              

                            

                               
                                <br>
                                <div class="form-group">
                                     <h6>Results:</h6>
                                       
    <div id="qualificationStatus"></div>
    <div id="loanDetails" style="display: none;">
        <div id="affordabilityAmount"></div>
        <div id="borrowAmount"></div>
        <div id="monthlyRepayment"></div>
        <p><small>This is just an estimation of your repayments, actual calculations will be produced after a real loan application.</small> </p>
    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- End Deserve Area -->

        <!-- Start Best Services Area -->
        <section id="products" class="best-services-area ptb-100">
            <div class="container">
                <div class="section-title">
                    <span>Our Loan Products</span>
                    <h2>What we offer</h2>
                    <p>Whatever your borrowing needs, we have an affordable credit product for you.</p>
                </div>

                <div class="best-services-slider"> 
                  
                    <div id="thumbs" class="tab-home owl-carousel owl-theme">
                    
                        <div class="tabs-item">
                            <a href="#">
                                <i class="flaticon-loan-3"></i>
                                <span>Salary Loan</span>
                            </a>
                        </div>

                        <div class="tabs-item">
                            <a href="#">
                                <i class="flaticon-idea"></i>
                                <span>Product Loan</span>
                            </a>
                        </div>

                        <div class="tabs-item">
                            <a href="#">
                                <i class="flaticon-globe"></i>
                                <span>Shopping Card</span>
                            </a>
                        </div>
                        
                        <div class="tabs-item">
                            <a href="#">
                                <i class="flaticon-car"></i>
                                <span>Vehicle Loan</span>
                            </a>
                        </div>
                        <div class="tabs-item">
                            <a href="#">
                                <i class="flaticon-rocket"></i>
                                <span>Device Loan</span>
                            </a>
                        </div>
                             <div class="tabs-item">
                            <a href="#">
                                <i class="flaticon-scholarship"></i>
                                <span>Business Loan</span>
                            </a>
                        </div>
                    
                    </div>

                    <div id="best-services" class="owl-carousel owl-theme">
                        <div class="services-tabs-item">
                            <div class="row align-items-center">
                                <div class="col-lg-6">
                                    <div class="services-tab-image">
                                        <img src="{{ asset('front_img/cash-loan.jpg') }}" alt="image">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="services-tab-content">
                                        <h3>AstroCred Salary Loan</h3>
                                        <p>We've all been there - an unexpected bill , school fees round the corner or the car making some odd noises that shout ‘time for a service'.If you're in need of extra money, an AstroCred is your best option. Whether you are a civil servant or employed in the private sector, it’s quick and easy to apply with AstroCred – all you need to do is complete a short online application form or download our mobile application and we’ll give you a preliminary decision within 30 minutes.</p>
                                        <br>
                                        <h5>Term: 1 - 60 Months | Maximum Loan Amount: K500,000</h5>
                                    </div>
                                   
                                    <ul class="list">
                                        <h3>Eligibility</h3>
                                        <li>Over 18 years old and a resident of the residing country.</li>
                                        <li>Full-time employment in the government or private sector.</li>
                                        <li>Receive salary or other income through a bank account.</li>
                                        <li>Not on an active blacklist, bankruptcy or equivalent.</li>
                                    </ul>
                                    <div class="deserve-btn">
                                        <a href="{{route('quick.register')}}" class="default-btn">
                                            Apply now
                                            <span></span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="services-tabs-item">
                            <div class="row align-items-center">
                                <div class="col-lg-6">
                                    <div class="services-tab-image">
                                        <img src="{{ asset('front_img/product-loan.jpg') }}" alt="image">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="services-tab-content">
                                        <h3>AstroCred Product Loan</h3>
                                        <p>Why hold off on that TV you have always wanted. It’s quick and easy to apply for a product loan – all you need to do is register for an AstroCred Account then choose the product that you want from over 1000 retail stores across Zambia. If approved we instantly pay the retailer and you pick your product at zero deposit, then payback in small monthly installments upto 36 months.</p>
                                        <br>
                                        <h5>Term: 1 - 36 Months | Minimum Loan Amount: K3,000 | Maximum Loan Amount: K100,000</h5>
                                    </div>
                                   
                                    <ul class="list">
                                        <h3>Eligibility</h3>
                                        <li>Over 18 years old and a resident of the residing country.</li>
                                        <li>Full-time employment in the government or private sector.</li>
                                        <li>Receive salary or other income through a bank account.</li>
                                        <li>Not on an active blacklist, bankruptcy or equivalent.</li>
                                    </ul>
                                    <div class="deserve-btn">
                                        <a href="{{route('quick.register')}}" class="default-btn">
                                            Apply now
                                            <span></span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="services-tabs-item">
                            <div class="row align-items-center">
                                <div class="col-lg-6">
                                    <div class="services-tab-image">
                                        <img src="{{ asset('front_img/shopping-card.jpg') }}" alt="image">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="services-tab-content">
                                        <h3>AstroCred Shopping Card</h3>
                                        <p>We know how important it is to stay afloat financially to combat all important needs, hence we have innovated a shopping credit card. You can apply for a shopping credit card for all the major retail stores across Zambia. All you need to do is register an AstroCred account and choose the type of shopping credit from available retailers on our platform. The AstroCred shopping card is a convenient tool for shopping anytime from your favourite retail stores.</p>
                                        <br>
                                        <h5>Upto: 6 Months | Minimum Loan Amount: K5000 | Maximum Loan Amount: K50,000</h5>
                                    </div>
                                   
                                    <ul class="list">
                                        <h3>Eligibility</h3>
                                        <li>Over 18 years old and a resident of the residing country.</li>
                                        <li>Full-time employment in the government or private sector.</li>
                                        <li>Receive salary or other income through a bank account.</li>
                                        <li>Not on an active blacklist, bankruptcy or equivalent.</li>
                                    </ul>
                                    <div class="deserve-btn">
                                        <a href="{{route('quick.register')}}" class="default-btn">
                                            Apply now
                                            <span></span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="services-tabs-item">
                            <div class="row align-items-center">
                                <div class="col-lg-6">
                                    <div class="services-tab-image">
                                        <img src="{{ asset('front_img/vehicle-loan-2.jpg') }}" alt="image">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="services-tab-content">
                                        <h3>Vehicle Loan</h3>
                                        <p>We've all been there - an unexpected bill , school fees round the corner or the car making some odd noises that shout ‘time for a service'.If you're in need of extra money, an AstroCred is your best option. Whether you are a civil servant or employed in the private sector, it’s quick and easy to apply with AstroCred – all you need to do is complete a short online application form or download our mobile application and we’ll give you a preliminary decision within 30 minutes.</p>
                                        <br>
                                        <h5>Upto: 24 months | Minimum Loan Amount: K2,500 | Maximum Loan Amount: K20,000</h5>
                                    </div>
                                   
                                    <ul class="list">
                                        <h3>Eligibility</h3>
                                        <li>Over 18 years old and a resident of the residing country.</li>
                                        <li>Full-time employment in the government or private sector.</li>
                                        <li>Receive salary or other income through a bank account.</li>
                                        <li>Not on an active blacklist, bankruptcy or equivalent.</li>
                                    </ul>
                                    <div class="deserve-btn">
                                        <a href="{{route('quick.register')}}" class="default-btn">
                                            Apply now
                                            <span></span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="services-tabs-item">
                            <div class="row align-items-center">
                                <div class="col-lg-6">
                                    <div class="services-tab-image">
                                        <img src="{{ asset('front_img/device-loan.jpg') }}" alt="image">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="services-tab-content">
                                        <h3>AstroCred Device Loan</h3>
                                        <p>Thinking of getting yourself a nice mobile device but dont have money?, worry no more because with AstroCred you can get a mobile brand from our listed merchants.</p>
                                        <br>
                                        <h5>Term: 1 - 60 Months | Maximum Loan Amount: K500,000</h5>
                                    </div>
                                   
                                    <ul class="list">
                                        <h3>Eligibility</h3>
                                        <li>Over 18 years old and a resident of the residing country.</li>
                                        <li>Full-time employment in the government or private sector.</li>
                                        <li>Receive salary or other income through a bank account.</li>
                                        <li>Not on an active blacklist, bankruptcy or equivalent.</li>
                                    </ul>
                                    <div class="deserve-btn">
                                        <a href="{{route('quick.register')}}" class="default-btn">
                                            Apply now
                                            <span></span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="services-tabs-item">
                            <div class="row align-items-center">
                                <div class="col-lg-6">
                                    <div class="services-tab-image">
                                        <img src="{{ asset('front_img/business-loan.jpg') }}" alt="image">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="services-tab-content">
                                        <h3>Business Loan</h3>
                                        <p>We've all been there - an unexpected bill , school fees round the corner or the car making some odd noises that shout ‘time for a service'.If you're in need of extra money, an AstroCred is your best option. Whether you are a civil servant or employed in the private sector, it’s quick and easy to apply with AstroCred – all you need to do is complete a short online application form or download our mobile application and we’ll give you a preliminary decision within 30 minutes.</p>
                                        <br>
                                        <h5>Term: 1 - 60 Months | Maximum Loan Amount: K500,000</h5>
                                    </div>
                                   
                                    <ul class="list">
                                        <h3>Eligibility</h3>
                                        <li>Over 18 years old and a resident of the residing country.</li>
                                        <li>Full-time employment in the government or private sector.</li>
                                        <li>Receive salary or other income through a bank account.</li>
                                        <li>Not on an active blacklist, bankruptcy or equivalent.</li>
                                    </ul>
                                    <div class="deserve-btn">
                                        <a href="{{route('quick.register')}}" class="default-btn">
                                            Apply now
                                            <span></span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        

                    </div>
                </div>
            </div>
        </section>
        <!-- End Best Services Area -->

        <!-- Start Clients Area -->
        <section class="clients-area pt-100 pb-70">
            <div class="container">
                <div class="section-title">
                    <span>Testimonials</span>
                    <h2>What Our Customers Say</h2>
                    <p> </p>
                </div>

                <div class="clients-slider owl-carousel owl-theme">
                    <div class="clients-item">
                        <div class="row align-items-center">
                            <div class="col-lg-6">
                                <div class="clients-info-text">
                                    <p>“ AstroCred was so simple to use when I applied for a topup loan to add to my new car. The process was so simple and I was approved the same day I applied. Great service.”</p>
                                    <h3>Lumbwe</h3>
                                    <span>Kitwe, Zambia</span>
                                </div>
                            </div>
                            
                            <div class="col-lg-6">
                                <div class="clients-image">
                                    <img src="{{ asset('front_img/clients/lumbwe.jpg') }}" alt="image">
                                    <div class="icon-1">
                                        <i class="flaticon-right-quote"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="clients-item">
                        <div class="row align-items-center">
                            <div class="col-lg-6">
                                <div class="clients-info-text">
                                    <p>“Finally got that sofa set I wanted. It was so easy using AstroCred's Store Credit who provided me with all the money and I get to pay it over 12 months. Yey!”</p>
                                    <h3>Sibongile</h3>
                                    <span>Ndola, Zambia</span>
                                </div>
                            </div>
                            
                            <div class="col-lg-6">
                                <div class="clients-image">
                                    <img src="{{ asset('front_img/clients/sibongile.jpg') }}" alt="image">
                                    <div class="icon-1">
                                        <i class="flaticon-right-quote"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="clients-item">
                        <div class="row align-items-center">
                            <div class="col-lg-6">
                                <div class="clients-info-text">
                                    <p>“I couldn't believe such a service existed in Zambia, everything was online and the team was so helpful. Keep up the good work AstroCred.”</p>
                                    <h3>Bruce</h3>
                                    <span>Lusaka, Zambia</span>
                                </div>
                            </div>
                            
                            <div class="col-lg-6">
                                <div class="clients-image">
                                    <img src="{{ asset('front_img/clients/bruce.jpg') }}" alt="image">
                                    <div class="icon-1">
                                        <i class="flaticon-right-quote"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- End Clients Area -->

       

              <!-- Start Projects Area -->
              <section class="projects-area ptb-100">
                <div class="container">
                    <div class="section-title">
                        <span>ASTROCRED STORE</span>
                        <h2>Our Credit Store Sections</h2>
                        <p>Browse through a wide variaty of items in our AstroCred store featuring assorted merchants and stores.</p>
                    </div>
                    <div class="projects-slider owl-carousel owl-theme">
                        <div class="projects-item">
                            <a>
                                <img src="{{ asset('front_img/projects/electronics.jpg') }}" alt="image">
                            </a>
                            <div class="content">
                                <h3>
                                    <a>Electronics</a>
                                </h3>
                            </div>
                        </div>
    
                        <div class="projects-item">
                            <a >
                                <img src="{{ asset('front_img/projects/home-appliances.jpg') }}" alt="image">
                            </a>
                            <div class="content">
                                <h3>
                                    <a>Home Appliances</a>
                                </h3>
                            </div>
                        </div>
    
                        <div class="projects-item">
                            <a >
                                <img src="{{ asset('front_img/projects/furniture.jpg') }}" alt="image">
                            </a>
                            <div class="content">
                                <h3>
                                    <a>Furniture</a>
                                </h3>
                            </div>
                        </div>
    
                        <div class="projects-item">
                            <a >
                                <img src="{{ asset('front_img/projects/hardware-tools.jpg') }}" alt="image">
                            </a>
                            <div class="content">
                                <h3>
                                    <a>Hardware & Tools</a>
                                </h3>
                            </div>
                        </div>
    
                        <div class="projects-item">
                            <a >
                                <img src="{{ asset('front_img/projects/fashion.jpg') }}" alt="image">
                            </a>
                            <div class="content">
                                <h3>
                                    <a>Fashion</a>
                                </h3>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <!-- End Projects Area -->

             <!-- Start Partner Area -->
             <div class="partner-area ptb-100">
                <div class="container">
                    <div class="partner-slider owl-carousel owl-theme">
                        <div class="partner-item">
                            <img src="{{ asset('front_img/partner/1.png') }}" alt="image">
                        </div>
    
                        <div class="partner-item">
                            <img src="{{ asset('front_img/partner/2.png') }}" alt="image">
                        </div>
    
                        <div class="partner-item">
                            <img src="{{ asset('front_img/partner/3.png') }}" alt="image">
                        </div>
    
                        <div class="partner-item">
                            <img src="{{ asset('front_img/partner/4.png') }}" alt="image">
                        </div>
    
                        <div class="partner-item">
                            <img src="{{ asset('front_img/partner/5.png') }}" alt="image">
                        </div>
                        <div class="partner-item">
                            <img src="{{ asset('front_img/partner/6.png') }}" alt="image">
                        </div>
                        <div class="partner-item">
                            <img src="{{ asset('front_img/partner/7.png') }}" alt="image">
                        </div>
                        <div class="partner-item">
                            <img src="{{ asset('front_img/partner/8.png') }}" alt="image">
                        </div>
                        <div class="partner-item">
                            <img src="{{ asset('front_img/partner/9.png') }}" alt="image">
                        </div>
                        <div class="partner-item">
                            <img src="{{ asset('front_img/partner/10.png') }}" alt="image">
                        </div>
                        <div class="partner-item">
                            <img src="{{ asset('front_img/partner/11.png') }}" alt="image">
                        </div>
                    </div>
                </div>
            </div>
            <!-- End Partner Area -->

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
            
                                <li>
                                    <a href="https://web.facebook.com/astrotechZM" target="_blank">
                                        <i class="flaticon-facebook"></i>
                                    </a>
                                </li>
                                
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

        <!-- Jquery Slim JS -->
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

        <script type="text/javascript">
            function calculateLoan() {
                var grossSalary = parseFloat(document.getElementById('grossSalary').value);
                var netSalary = parseFloat(document.getElementById('netSalary').value);
                var months = parseInt(document.getElementById('months').value);
                var amountToBorrow = parseFloat(document.getElementById('amountToBorrow').value);
                
                // Calculate monthly repayment
                var interestRate = 0.15;
                var monthlyInterest = amountToBorrow * interestRate / months;
                var monthlyRepayment = amountToBorrow / months + monthlyInterest;
                
                // Check if monthly repayment exceeds 40% of net salary
                var maxRepayment = netSalary * 0.4;
                
                if (monthlyRepayment > maxRepayment) {
                    // Display message if person doesn't qualify
                    document.getElementById('qualificationStatus').innerHTML = "You do not qualify for the loan. <br> Your Affordability is: K" + maxRepayment.toFixed(2);
                    // document.getElementById('affordabilityAmount').innerHTML = "Your Affordability is: K" + maxRepayment.toFixed(2);
                    document.getElementById('loanDetails').style.display = "none";
                } else {
                    // Calculate maximum amount that can be borrowed
                    var maxBorrowAmount = maxRepayment * months;
                    
                    // Display results
                    document.getElementById('qualificationStatus').innerHTML = "Congratulations! You qualify for the loan.";
                    document.getElementById('affordabilityAmount').innerHTML = "Your Affordability is: K" + maxRepayment.toFixed(2);
                    document.getElementById('borrowAmount').innerHTML = "Total amount you can borrow: K" + maxBorrowAmount.toFixed(2);
                    document.getElementById('monthlyRepayment').innerHTML = "Monthly repayment amount: K" + monthlyRepayment.toFixed(2);
                    document.getElementById('loanDetails').style.display = "block";
                }
            }
            
            function resetForm() {
                document.getElementById('loanForm').reset();
                document.getElementById('qualificationStatus').innerHTML = "";
                document.getElementById('loanDetails').style.display = "none";
            }
        </script>


    </body>
</html>