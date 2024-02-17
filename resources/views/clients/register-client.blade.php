<?php
/**
 *Created by PhpStorm for eshagi
 *User: Vincent Guyo
 *Date: 9/21/2020
 *Time: 8:29 PM
 */

?>
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
                <img class="navbar-brand" src="{{asset('images/logo_official.png')}}" alt="eShagi" width="200px" height="200px">

                <div style="right:0">
                    <a href="{{route('login')}}" class="btn btn-blue">{{ trans('auth.login') }}</a>
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
                            @include('partials.fe-status')
                            <p>Join over 1,000 people who have discovered affordable credit with eShagi.</p>
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
                                            @if ($errors->has('title'))
                                                <span class="invalid-feedback">
                                                        <strong>{{ $errors->first('title') }}</strong>
                                                    </span>
                                            @endif
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
                                            <input class="form-control  {{ $errors->has('natid') ? ' is-invalid' : '' }}" type="text" autocapitalize="characters" maxlength="15" name="natid" id="natid" value="{{ old('natid') }}" required="required" pattern="^[0-9]{2}-[0-9]{6,7}-[A-Z]-[0-9]{2}$|^[0-9]{6}\/[0-9]{2}\/[0-9]{1}$" title="ID Format should be in the form of xx-xxxxxxx X xx" placeholder="Enter your National ID as it appears on your ID Card...">
{{--                                            <input class="form-control  {{ $errors->has('natid') ? ' is-invalid' : '' }}" type="text" autocapitalize="characters" maxlength="15" name="natid" id="natid" value="{{ old('natid') }}" required="required" pattern="^[0-9]{2}-[0-9]{6,7}-[A-Z]-[0-9]{2}$|^[0-9]{6}\/[0-9]{2}\/[0-9]{1}$" title="ID Format should be in the form of xx-xxxxxxx X xx" placeholder="Enter your National ID as it appears on your ID Card...">--}}
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
                                            <input class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" type="email" name="email" id="email" value="{{ old('email') }}" placeholder="Enter your email..." required>
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
                                                <select class="input-group-addon custom-select form-control col-lg-2 dynamic" name="countryCode" id="countryCode" required>
                                                    <option value="{{ old('countryCode') }}">{{ old('countryCode') }}</option>
                                                    @if ($countries)
                                                            @foreach($countries as $country)
                                                                <option value="+{{ $country->country_code }}">+{{ $country->country_code }} </option>
                                                            @endforeach
                                                    @endif
                                                </select>
                                                <input class="form-control {{ $errors->has('mobile') ? ' is-invalid' : '' }}  col-lg-10" type="number" name="mobile" value="{{ old('mobile') }}" onkeyup="validateNumber()" maxlength="10" id="mobile" required="required" placeholder="e.g. 775731858">
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
                                            <input class="form-control {{ $errors->has('dob') ? ' is-invalid' : '' }}" type="text" name="dob" id="dob" value="{{ old('dob') }}" required="required" placeholder="Enter your date of birth..." autocomplete="off">
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
                                            @if ($errors->has('gender'))
                                                <span class="invalid-feedback">
                                                        <strong>{{ $errors->first('gender') }}</strong>
                                                    </span>
                                            @endif
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
                                            @if ($errors->has('marital_state'))
                                                <span class="invalid-feedback">
                                                        <strong>{{ $errors->first('marital_state') }}</strong>
                                                    </span>
                                            @endif
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
                                            <select class="form-control" name="nationality" id="nationality" onchange="getNationality()" required>
                                                <option value="{{ old('nationality') }}">{{ old('nationality') }}</option>
                                                @if ($countries)
                                                    @foreach($countries as $country)
                                                        <option value="{{ $country->country }}">{{ $country->country }} </option>
                                                    @endforeach
                                                        <option value="other">Other</option>
                                                @endif
                                            </select>
                                            @if ($errors->has('nationality'))
                                                <span class="invalid-feedback">
                                                        <strong>{{ $errors->first('nationality') }}</strong>
                                                    </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6" id="otherNationality">
                                        <div class="form-group">
                                            <label>Other Nationality</label>
                                            <input class="form-control" type="text" maxlength="20" name="nationality1" id="nationality1" placeholder="Specify your Nationality...">

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
                                                <option value="Zambian Military">Zambian Military</option>
                                            </select>
                                            @if ($errors->has('emp_sector'))
                                                <span class="invalid-feedback">
                                                        <strong>{{ $errors->first('emp_sector') }}</strong>
                                                    </span>
                                            @endif
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
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
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
                                            <label>RTGS Gross Monthly Salary</label>
                                            <div class="input-group">
                                                <input class="form-control {{ $errors->has('gross') ? ' is-invalid' : '' }} col-lg-12" name="gross" type="text" pattern="^\d{1,3}*(\.\d+)?$" value="{{ old('gross') }}" data-type="currency" id="gross" placeholder="Enter Gross Salary">
                                                @if ($errors->has('gross'))
                                                    <span class="invalid-feedback">
                                                        <strong>{{ $errors->first('gross') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>RTGS Net Monthly Salary</label>
                                            <div class="input-group">
                                                <input class="form-control {{ $errors->has('salary') ? ' is-invalid' : '' }} col-lg-12" name="salary" type="text" pattern="^\d{1,3}*(\.\d+)?$" value="{{ old('salary') }}" data-type="currency" id="salary" required placeholder="Enter Net Salary">
                                                <br>

                                                @if ($errors->has('salary'))
                                                    <span class="invalid-feedback">
                                                        <strong>{{ $errors->first('salary') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>USD Gross Monthly Salary</label>
                                            <div class="input-group">
                                                <input class="form-control {{ $errors->has('usd_gross') ? ' is-invalid' : '' }} col-lg-12" name="usd_gross" type="text" pattern="^\d{1,3}*(\.\d+)?$" value="{{ old('usd_gross') }}" data-type="currency" id="usd_gross" placeholder="Enter USD Gross Salary (if any)">
                                                @if ($errors->has('usd_gross'))
                                                    <span class="invalid-feedback">
                                                        <strong>{{ $errors->first('usd_gross') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>USD Net Monthly Salary</label>
                                            <div class="input-group">
                                                <input class="form-control {{ $errors->has('usd_salary') ? ' is-invalid' : '' }} col-lg-12" name="usd_salary" type="text" pattern="^\d{1,3}*(\.\d+)?$" value="{{ old('usd_salary') }}" data-type="currency" id="usd_salary" placeholder="Enter USD Net Salary (if any)">
                                                <br>

                                                @if ($errors->has('usd_salary'))
                                                    <span class="invalid-feedback">
                                                        <strong>{{ $errors->first('usd_salary') }}</strong>
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
                                            <label>Suburb</label>
                                            <input class="form-control{{ $errors->has('surburb') ? ' is-invalid' : '' }}" type="text" maxlength="40" name="surburb" id="surburb" value="{{ old('surburb') }}" required="required" placeholder="Enter your suburb...">
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
                                            <select class="form-control{{ $errors->has('province') ? ' is-invalid' : '' }}" name="province" id="province" required="required" >
                                                <option value="{{ old('province') }}">{{ old('province') }}</option>
                                            </select>
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
                                            <select class="form-control" name="country" id="country" required>
                                                <option value="{{ old('country') }}">{{ old('country') }}</option>
                                                @if ($countries)
                                                    @foreach($countries as $country)
                                                        <option value="{{ $country->id }}">{{ $country->country }} </option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Occupation Type</label>
                                            <select class="form-control" id="home_type" name="home_type" required>
                                                <option value="{{ old('home_type') }}">{{ old('home_type') }}</option>
                                                <option value="Owned">Owned</option>
                                                <option value="Rented">Rented</option>
                                                <option value="Mortgaged">Mortgaged</option>
                                                <option value="Parents">Parents</option>
                                                <option value="Employer Owned">Employer Owned</option>
                                            </select>
                                            @if ($errors->has('home_type'))
                                                <span class="invalid-feedback">
                                                        <strong>{{ $errors->first('home_type') }}</strong>
                                                    </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label>Password</label>
                                            <input class="form-control" type="password" name="password" id="password" required="required" placeholder="Enter at least a 6 characters password...">
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label>Confirm Password</label>
                                            <input class="form-control" type="password" name="password_confirmation" id="password_confirmation" required="required" placeholder="Repeat your password">
                                        </div>
                                    </div>

                                </div>
                                <div class="row">
                                    <div class="col-lg-6">
                                        <input class="btn btn-success btn-send" type="submit" value="{{ trans('auth.nextYourAccount') }}">
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

    function getNationality(){
        if(document.getElementById("nationality").value=="other") {
            document.getElementById("otherNationality").style.visibility = "visible";
        } else {
            document.getElementById("otherNationality").style.visibility = "hidden";
            document.getElementById("nationality1").value = document.getElementById("nationality").value;
        }
    }
    /*function formatIDNumber(){
        var myId=document.getElementById("natid").value;
        myId=myId.replace(/ /gi, "");
        myId=myId.replace(/-/gi, "");

        myId=insert(myId, "-", 2);
        myId=insert(myId, "-", myId.length-3);
        myId=insert(myId, "-", myId.length-2);

        document.getElementById("natid").value=myId;
    }*/

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

<script src="{{ asset('js/select2.min.js')}}"></script>

<link rel="stylesheet" href="{{('css/jquery-ui.css')}}">
<script src="{{asset('js/jquery-ui.js')}}"></script>
<script>
    $( function() {
        $( "#dob" ).datepicker({
            navigationAsDateFormat: true,
            dateFormat: "dd-mm-yy",
            minDate: "01-01-1940",
            maxDate: "01-01-2003",
            changeMonth: true,
            changeYear: true
        });
    } );
</script>

<script type="text/javascript">
    $("#title").select2({
        placeholder: 'Please select your title',
        allowClear:true,
    });

    $("#countryCode").select2({
        placeholder: 'Code.',
        allowClear:true,
    })

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
    }).change(function(){
        var id = $(this).val();
        var _token = $("input[name='_token']").val();
        if(id){
            $.ajax({
                type:"get",
                url:"{{url('/getProvinces')}}/"+id,
                _token: _token ,
                success:function(res) {
                    if(res) {
                        $("#province").empty();
                        $.each(res,function(key, value){
                            $("#province").append('<option value="">Please select a province</option>').append('<option value="'+value.province+'">'+value.province+'</option>');
                        });
                    }
                }

            });
        }
    });

    $("#home_type").select2({
        placeholder: 'Please select your House ownership status',
        allowClear:true,
    });

    $("#province").select2({
        placeholder: 'Please select country then your province',
        allowClear:true,
    });

</script>

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
        return n.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, "")
    }


    function formatCurrency(input, blur) {

        var input_val = input.val();

        if (input_val === "") { return; }

        var original_len = input_val.length;

        var caret_pos = input.prop("selectionStart");

        if (input_val.indexOf(".") >= 0) {

            var decimal_pos = input_val.indexOf(".");

            var left_side = input_val.substring(0, decimal_pos);
            var right_side = input_val.substring(decimal_pos);

            left_side = formatNumber(left_side);

            right_side = formatNumber(right_side);

            if (blur === "blur") {
                right_side += "00";
            }

            right_side = right_side.substring(0, 2);

            input_val = left_side + "." + right_side;

        } else {
            input_val = formatNumber(input_val);

            if (blur === "blur") {
                input_val += ".00";
            }
        }

        input.val(input_val);

        var updated_len = input_val.length;
        caret_pos = updated_len - original_len + caret_pos;
        input[0].setSelectionRange(caret_pos, caret_pos);
    }
</script>

</body>
</html>
