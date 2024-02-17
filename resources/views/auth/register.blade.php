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

<!--[if lt IE 9]>
    <script src="{{asset('js/html5shiv.min.js')}}"></script>
    <script src="{{asset('js/respond.min.js')}}"></script>
    <![endif]-->

    <link href="{{ asset('css/select2.min.css')}}" rel="stylesheet" />
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
                    <a href="{{route('login')}}" class="btn btn-blue">Login</a>
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
                            <h1>Let’s get started !</h1>
                            <p>Join over 1,000 people who have discovered affordable credit with Eshagi.</p>
                            <br>
                            <h2 class="title-h2"> Personal Details</h2>
                            <form method="POST" action="{{ route('clients.register.one.post') }}" >
                                @csrf
                                <div class="messag"></div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Select Title</label>
                                            <select class="form-control" id="title" name="title" required>
                                                <option value="{{ old('title') }}">{{ old('title') }}</option>
                                                <option value="Mr">Mr</option>
                                                <option value="Mrs">Mrs</option>
                                                <option value="Ms">Ms</option>
                                                <option value="Miss">Miss</option>
                                                <option value="Dr">Dr</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>First Name</label>
                                            <input class="form-control{{ $errors->has('first_name') ? ' is-invalid' : '' }}" type="text" name="first_name" id="first_name" value="{{ old('first_name') }}" required="required" placeholder="Enter your name...">
                                            @if ($errors->has('first_name'))
                                                <span class="invalid-feedback">
                                                    <strong>{{ $errors->first('first_name') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <!-- email input -->
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Surname</label>
                                            <input class="form-control{{ $errors->has('last_name') ? ' is-invalid' : '' }}" type="text" name="last_name" id="last_name" value="{{ old('last_name') }}" required="required" placeholder="Enter your surname...">
                                            @if ($errors->has('last_name'))
                                                <span class="invalid-feedback">
                                                    <strong>{{ $errors->first('last_name') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label> National ID</label>
                                            <input class="form-control{{ $errors->has('natid') ? ' is-invalid' : '' }}" type="text" maxlength="15" name="natid" id="natid" value="{{ old('natid') }}" onkeyup="validateId()" required="required" pattern="^[0-9]{2}-[0-9]{6,7}-[a-zA-Z]-[0-9]{2}$" title="ID Format should be in the form of xx-xxxxxxx X xx" placeholder="Enter your National ID...">
                                            @if ($errors->has('natid'))
                                                <span class="invalid-feedback">
                                                    <strong>{{ $errors->first('natid') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Email</label>
                                            <input class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" type="email" name="email" id="email" value="{{ old('email') }}" placeholder="Enter your  email ( optional )...">
                                            @if ($errors->has('email'))
                                                <span class="invalid-feedback">
                                                    <strong>{{ $errors->first('email') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Mobile Number</label>
                                            <div class="input-group">
                                                <input class="input-group-addon form-control col-lg-2" value="+263" id="countryCode" readonly>
                                                <input class="form-control {{ $errors->has('mobile') ? ' is-invalid' : '' }}  col-lg-10" type="number" name="mobile" value="{{ old('mobile') }}" onkeyup="validateNumber()" maxlength="10" id="mobile" required="required" placeholder="EG. 775731858">
                                                @if ($errors->has('mobile'))
                                                    <span class="invalid-feedback">
                                                    <strong>{{ $errors->first('mobile') }}</strong>
                                                </span>
                                                @endif
                                            </div>

                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Date of Birth</label>
                                            <input class="form-control {{ $errors->has('dob') ? ' is-invalid' : '' }}" type="date"  min="1940-01-01" max="2003-01-01" name="dob" id="dob" value="{{ old('dob') }}" required="required" placeholder="Enter your date of birth..." autocomplete="off">
                                            @if ($errors->has('dob'))
                                                <span class="invalid-feedback">
                                                    <strong>{{ $errors->first('dob') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Select Gender</label>
                                            <select class="form-control" id="gender" name="gender" required>
                                                <option value="{{ old('gender') }}">{{ old('gender') }}</option>
                                                <option value="Male">Male</option>
                                                <option value="Female">Female</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Select Marital Status</label>
                                            <select class="form-control" id="marital_state" name="marital_state" required>
                                                <option value="{{ old('marital_state') }}">{{ old('marital_state') }}</option>
                                                <option value="Single">Single</option>
                                                <option value="Married">Married</option>
                                                <option value="Widowed">Widowed</option>
                                                <option value="Divorced">Divorced</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label> Number of Dependants</label>
                                            <input class="form-control {{ $errors->has('dependants') ? ' is-invalid' : '' }}" type="number" max=10 name="dependants" id="dependants" value="{{ old('dependants') }}" required="required" placeholder="Enter your dependants...">
                                            @if ($errors->has('dependants'))
                                                <span class="invalid-feedback">
                                                    <strong>{{ $errors->first('dependants') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Nationality</label>
                                            <select class="form-control" id="nationality" name="nationality" onchange="validateId1()">
                                                <option value="{{ old('nationality') }}">{{ old('nationality') }}</option>
                                                <option value="Zimbabwe">Zimbabwe</option>
                                                <option value="other">Other</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6" id="otherNationality">
                                        <div class="form-group">
                                            <label>Other Nationality</label>
                                            <input class="form-control" type="text" maxlength="20" name="nationality1" id="nationality" placeholder="Specify your Nationality...">
                                        </div>
                                    </div>
                                </div>
                                <br>
                                <h2 class="title-h2"> Employment Details</h2>
                                <hr>

                                <div class="row">

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Employment Sector</label>
                                            <select class="form-control" id="emp_sector" name="emp_sector" required>
                                                <option value="{{ old('emp_sector') }}">{{ old('emp_sector') }}</option>
                                                <option value="Government">Government</option>
                                                <option value="Private">Private Sector</option>
                                                <option value="Informal">Informal Sector</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label id="label">Employer Name</label>
                                            <input name="employer" class="form-control{{ $errors->has('employer') ? ' is-invalid' : '' }}" id="employer" value="{{ old('employer') }}" placeholder="Enter your employer's name..." required>
                                            @if ($errors->has('employer'))
                                                <span class="invalid-feedback">
                                                    <strong>{{ $errors->first('employer') }}</strong>
                                                </span>
                                        @endif
                                            <!--<input class="form-control" type="text" name="employer_name" id="employer_name" required="required" placeholder="Enter your employer's name...">-->
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>EC Number/Payroll ID</label>
                                            <input class="form-control{{ $errors->has('ecnumber') ? ' is-invalid' : '' }}" type="text" name="ecnumber" id="ecnumber" value="{{ old('ecnumber') }}" required="required" placeholder="Enter your payroll ID...">
                                            @if ($errors->has('ecnumber'))
                                                <span class="invalid-feedback">
                                                    <strong>{{ $errors->first('ecnumber') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Net Monthly Salary</label>
                                            <div class="input-group">
                                                <input class="input-group-addon form-control col-lg-2" value="ZWL$" readonly>
                                                <input class="form-control {{ $errors->has('salary') ? ' is-invalid' : '' }} col-lg-10" max="500000" name="salary" type="text" pattern="^\d{1,3}*(\.\d+)?$" value="{{ old('salary') }}" data-type="currency" id="salary" required placeholder="Enter Net Salary">
                                                @if ($errors->has('salary'))
                                                    <span class="invalid-feedback">
                                                        <strong>{{ $errors->first('salary') }}</strong>
                                                    </span>
                                                @endif
                                            </div>


                                        </div>
                                    </div>
                                </div>

                                <div class="spacing_1">
                                    <h2 class="title-h2"> Address Details</h2>
                                    <hr>
                                </div>
                                <div class="row">

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>House Number</label>
                                            <input class="form-control{{ $errors->has('house_num') ? ' is-invalid' : '' }} " type="text" name="house_num" id="house_num" value="{{ old('house_num') }}" required="required" placeholder="Enter your house number...">
                                            @if ($errors->has('house_num'))
                                                <span class="invalid-feedback">
                                                        <strong>{{ $errors->first('house_num') }}</strong>
                                                    </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Street Name</label>
                                            <input class="form-control{{ $errors->has('street') ? ' is-invalid' : '' }}" type="text" maxlength="40" name="street" id="street" value="{{ old('street') }}" required="required" placeholder="Enter your street name...">
                                            @if ($errors->has('street'))
                                                <span class="invalid-feedback">
                                                        <strong>{{ $errors->first('street') }}</strong>
                                                    </span>
                                            @endif
                                        </div>
                                    </div>

                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Surburb</label>
                                            <input class="form-control{{ $errors->has('surburb') ? ' is-invalid' : '' }}" type="text" maxlength="40" name="surburb" id="surburb" value="{{ old('surburb') }}" required="required" placeholder="Enter your surburb...">
                                            @if ($errors->has('surburb'))
                                                <span class="invalid-feedback">
                                                        <strong>{{ $errors->first('surburb') }}</strong>
                                                    </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>City</label>
                                            <input class="form-control{{ $errors->has('city') ? ' is-invalid' : '' }}" type="text" maxlength="40" name="city" id="city" value="{{ old('city') }}" required="required" placeholder="Enter your city...">
                                            @if ($errors->has('city'))
                                                <span class="invalid-feedback">
                                                        <strong>{{ $errors->first('city') }}</strong>
                                                    </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Province</label>
                                            <input class="form-control{{ $errors->has('province') ? ' is-invalid' : '' }}" type="text" name="province" maxlength="40" id="province" value="{{ old('province') }}" required="required" placeholder="Enter your province...">
                                            @if ($errors->has('province'))
                                                <span class="invalid-feedback">
                                                        <strong>{{ $errors->first('province') }}</strong>
                                                    </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Country</label>
                                            <select class="form-control" id="country" name="country">
                                                <option value="Zimbabwe">Zimbabwe</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Occupation Type</label>
                                            <select class="form-control" id="home_type" name="home_type" required>
                                                <option value="{{ old('home_type') }}">{{ old('home_type') }}</option>
                                                <option value="Owned">Owned</option>
                                                <option value="Rental">Rental</option>
                                                <option value="Living With Parents">Living With Parents</option>
                                                <option value="Provided by Employer">Provided by Employer</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label>Password</label>
                                            <input class="form-control" type="password" name="password" id="password" required="required" placeholder="Enter your password...">
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label>Confirm Password</label>
                                            <input class="form-control" type="password" name="password_confirmation" id="password_confirmation" required="required" placeholder="Repeat password">
                                        </div>
                                    </div>

                                </div>

                                <div class="row">
                                    <div class="col-lg-6">
                                        <input class="btn btn-success btn-send" type="submit" value="Next : Your account ">
                                    </div>

                                </div>
                                <hr>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

</div>

@include('partials.fe-footer')

<script>
    document.getElementById("otherNationality").style.visibility = "hidden";

    function nationality(){

    }
    function validateId1(){
        if(document.getElementById("nationality").value=="other")
        {
            document.getElementById("otherNationality").style.visibility = "visible";
        }
        else
        {

            document.getElementById("otherNationality").style.visibility = "hidden";
            document.getElementById("nationality1").value = document.getElementById("nationality").value;
        }
    }
    function validateId(){
        console.log("ndripo");
        var myId=document.getElementById("natid").value;
        myId=myId.replace(/ /gi, "");
        myId=myId.replace(/-/gi, "");

        myId=insert(myId, "-", 2);
        myId=insert(myId, "-", myId.length-3);
        myId=insert(myId, "-", myId.length-2);
        console.log(myId);

        document.getElementById("natid").value=myId;
    }

    function validateNumber(){
        var myLength=document.getElementById("mobile").value.length;
        var myNumber=document.getElementById("mobile").value;
        if(myLength >=10){
            document.getElementById("mobile").value=myNumber.substring(0, myNumber.length - 1);
        }
    }

    function insert(main_string, ins_string, pos) {
        if(typeof(pos) == "undefined") {
            pos = 0;
        }
        if(typeof(ins_string) == "undefined") {
            ins_string = '';
        }
        return main_string.slice(0, pos) + ins_string + main_string.slice(pos);
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

<script src="{{ asset('js/select2.min.js')}}"></script>

<script type="text/javascript">
    $("#title").select2({
        placeholder: 'Please select your title',
        allowClear:true,
    });

    $("#gender").select2({
        placeholder: 'Please select your gender',
        allowClear:true,
    });

    $("#marital_state").select2({
        placeholder: 'Please select your marital status',
        allowClear:true,
    });

    $("#nationality").select2({
        placeholder: 'Please select your nationality',
        allowClear:true,
    });

    $("#emp_sector").select2({
        placeholder: 'Please select your employment sector',
        allowClear:true,
    });

    $("#country").select2({
        placeholder: 'Please select your country',
        allowClear:true,
    });

    $("#home_type").select2({
        placeholder: 'Please select your House status',
        allowClear:true,
    });

</script>

<script src='https://www.google.com/recaptcha/api.js'></script>

<script>

    $("input[data-type='currency']").on({
        keyup: function() {
            formatCurrency($(this));
        },
        blur: function() {
            formatCurrency($(this), "blur");
        }
    });


    function formatNumber(n) {
        // format number 1000000 to 1,234,567
        return n.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, "")
    }


    function formatCurrency(input, blur) {
        // appends $ to value, validates decimal side
        // and puts cursor back in right position.

        // get input value
        var input_val = input.val();

        // don't validate empty input
        if (input_val === "") { return; }

        // original length
        var original_len = input_val.length;

        // initial caret position
        var caret_pos = input.prop("selectionStart");

        // check for decimal
        if (input_val.indexOf(".") >= 0) {

            // get position of first decimal
            // this prevents multiple decimals from
            // being entered
            var decimal_pos = input_val.indexOf(".");

            // split number by decimal point
            var left_side = input_val.substring(0, decimal_pos);
            var right_side = input_val.substring(decimal_pos);

            // add commas to left side of number
            left_side = formatNumber(left_side);

            // validate right side
            right_side = formatNumber(right_side);

            // On blur make sure 2 numbers after decimal
            if (blur === "blur") {
                right_side += "00";
            }

            // Limit decimal to only 2 digits
            right_side = right_side.substring(0, 2);

            // join number by .
            input_val = left_side + "." + right_side;

        } else {
            // no decimal entered
            // add commas to number
            // remove all non-digits
            input_val = formatNumber(input_val);
            //input_val = input_val;

            // final formatting
            if (blur === "blur") {
                input_val += ".00";
            }
        }

        // send updated string to input
        input.val(input_val);

        // put caret back in the right position
        var updated_len = input_val.length;
        caret_pos = updated_len - original_len + caret_pos;
        input[0].setSelectionRange(caret_pos, caret_pos);
    }
</script>

</body>
</html>

