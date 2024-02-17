<?php
/**
 * Created by PhpStorm for eshagitwo
 * User: VinceGee
 * Date: 2/21/2022
 * Time: 4:53 PM
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
    <title>eShagi | Zambia's Leading Loans & Store Credit Provider</title>

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
                            <form method="POST" action="{{route('save.zambian')}}" enctype="multipart/form-data">
                                @csrf
                                <div class="messag"></div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Select Title</label>
                                            <select class="form-control" id="title" name="title" required>
                                                <option value="{{ old('title') }}">{{ old('title') }}</option>
                                                <option value="1">Mr. </option>
                                                <option value="2">Mrs. </option>
                                                <option value="3">Miss </option>
                                                <option value="4">Ms. </option>
                                                <option value="5">Dr. </option>
                                                <option value="6">Prof. </option>
                                                <option value="7">Rev. </option>
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
                                            <label>Middle Name</label>
                                            <input class="form-control{{ $errors->has('middle_name') ? ' is-invalid' : '' }}" type="text" name="middle_name" id="middle_name" value="{{ old('middle_name') }}" placeholder="Enter your middle name...">
                                            @if ($errors->has('middle_name'))
                                                <span class="invalid-feedback">
                                                    <strong>{{ $errors->first('middle_name') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
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
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label> NRC</label>
                                            <input class="form-control  {{ $errors->has('nrc') ? ' is-invalid' : '' }}" type="text" autocapitalize="characters" maxlength="11" name="nrc" id="nrc" value="{{ old('nrc') }}" onkeyup="validateId()" required="required" pattern="^[0-9]{6}\/[0-9]{2}\/[0-9]{1}$" title="ID Format should be in the form of xxxxxx/xx/x" placeholder="Enter your National ID as it appears on your ID Card...">
                                            <span class="text-info">e.g. 123456/78/9</span>
                                            @if ($errors->has('nrc'))
                                                <span class="invalid-feedback">
                                                    <strong>{{ $errors->first('nrc') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
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
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Mobile Number</label>
                                            <div class="input-group">
                                                <input class="input-group-addon form-control col-lg-2" value="+260" id="countryCode" readonly>
                                                <input class="form-control {{ $errors->has('mobile') ? ' is-invalid' : '' }}  col-lg-10" type="number" name="mobile" value="{{ old('mobile') }}" onkeyup="validateNumber()" maxlength="10" id="mobile" required="required" placeholder="EG. 775731858">
                                                @if ($errors->has('mobile'))
                                                    <span class="invalid-feedback">
                                                    <strong>{{ $errors->first('mobile') }}</strong>
                                                </span>
                                                @endif
                                            </div>

                                        </div>
                                    </div>
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
                                </div>
                                <div class="row">

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

                                <div class="spacing_1">
                                    <h2 class="title-h2"> Address Details</h2>
                                    <hr>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Address</label>
                                            <input class="form-control{{ $errors->has('address') ? ' is-invalid' : '' }} " type="text" name="address" id="address" value="{{ old('address') }}" required="required" placeholder="Enter client address...">
                                            @if ($errors->has('address'))
                                                <span class="invalid-feedback">
                                                        <strong>{{ $errors->first('address') }}</strong>
                                                    </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>City</label>
                                            <input class="form-control{{ $errors->has('city') ? ' is-invalid' : '' }}" type="text" maxlength="40" name="city" id="city" value="{{ old('city') }}" required="required" placeholder="Enter client city...">
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
                                            <select class="form-control{{ $errors->has('province') ? ' is-invalid' : '' }}" name="province" id="province" required>
                                                <option value="{{ old('province') }}">{{ old('province') }}</option>
                                                @if ($provinces)
                                                    @foreach($provinces as $province)
                                                        <option value="{{ $province->province }}">{{ $province->province }} </option>
                                                    @endforeach
                                                @endif
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
                                            <label>ZIP Code</label>
                                            <input class="form-control{{ $errors->has('zip_code') ? ' is-invalid' : '' }}" type="text" maxlength="40" name="zip_code" id="zip_code" value="{{ old('zipcode') }}" placeholder="Enter client zipcode...">
                                            @if ($errors->has('zip_code'))
                                                <span class="invalid-feedback">
                                                        <strong>{{ $errors->first('zip_code') }}</strong>
                                                    </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Landline</label>
                                            <input class="form-control{{ $errors->has('landline') ? ' is-invalid' : '' }}" type="text" name="landline" id="landline" value="{{ old('landline') }}" placeholder="Enter client landline...">
                                            @if ($errors->has('landline'))
                                                <span class="invalid-feedback">
                                                    <strong>{{ $errors->first('landline') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-md-6">

                                    </div>
                                </div>
                                <hr>

                                <h2 class="title-h2"> Employment Details</h2>
                                <hr>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label id="label">Employer/Business</label>
                                            <input name="business_employer" class="form-control{{ $errors->has('business_employer') ? ' is-invalid' : '' }}" id="business_employer" value="{{ old('business_employer') }}" placeholder="Enter client employer's name...">
                                            @if ($errors->has('business_employer'))
                                                <span class="invalid-feedback">
                                                    <strong>{{ $errors->first('business_employer') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>EC Number</label>
                                            <input class="form-control{{ $errors->has('ec_number') ? ' is-invalid' : '' }}" type="text" name="ec_number" id="ec_number" value="{{ old('ec_number') }}" placeholder="Enter client payroll ID...">
                                            @if ($errors->has('ec_number'))
                                                <span class="invalid-feedback">
                                                    <strong>{{ $errors->first('ec_number') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label id="label">Institution</label>
                                            <input name="institution" class="form-control{{ $errors->has('institution') ? ' is-invalid' : '' }}" id="institution" value="{{ old('institution') }}" placeholder="Enter client institution...">
                                            @if ($errors->has('institution'))
                                                <span class="invalid-feedback">
                                                    <strong>{{ $errors->first('institution') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Employment Status</label>
                                            <select class="form-control" id="work_status" name="work_status" required>
                                                <option value="{{ old('work_status') }}">{{ old('work_status') }}</option>
                                                <option value="Employee">Employee</option>
                                                <option value="Government Employee">Government Employee</option>
                                                <option value="Private Sector Employee">Private Sector Employee</option>
                                                <option value="Owner">Owner</option>
                                                <option value="Student">Student</option>
                                                <option value="Overseas Worker">Overseas Worker</option>
                                                <option value="Pensioner">Pensioner</option>
                                                <option value="Unemployed">Unemployed</option>
                                            </select>
                                            @if ($errors->has('work_status'))
                                                <span class="invalid-feedback">
                                                    <strong>{{ $errors->first('work_status') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label id="label">Credit Score</label>
                                            <input name="credit_score" class="form-control{{ $errors->has('credit_score') ? ' is-invalid' : '' }}" id="credit_score" value="{{ old('credit_score') }}" pattern='^\d{1,3}*(\.\d+)?$' data-type="currency" placeholder="Enter client credit score...">
                                            @if ($errors->has('credit_score'))
                                                <span class="invalid-feedback">
                                                    <strong>{{ $errors->first('credit_score') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label id="label">Department</label>
                                            <input name="department" class="form-control{{ $errors->has('department') ? ' is-invalid' : '' }}" id="department" value="{{ old('department') }}" placeholder="Enter client department...">
                                            @if ($errors->has('department'))
                                                <span class="invalid-feedback">
                                                    <strong>{{ $errors->first('department') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <h2 class="title-h2"> Next of Kin Details</h2>
                                <hr>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Next of Kin</label>
                                            <input class="form-control{{ $errors->has('kin_name') ? ' is-invalid' : '' }}" value="{{ old('kin_name') }}" type="text" name="kin_name" id="kin_name" required="required" placeholder="Enter your next of kin's full name...">
                                            @if ($errors->has('kin_name'))
                                                <span class="invalid-feedback">
                                                    <strong>{{ $errors->first('kin_name') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Next of Kin Relationship</label>
                                            <input class="form-control{{ $errors->has('kin_relationship') ? ' is-invalid' : '' }}" value="{{ old('kin_relationship') }}" type="text" name="kin_relationship" id="kin_relationship" required="required" placeholder="Enter relationship to next of kin...">
                                            @if ($errors->has('kin_relationship'))
                                                <span class="invalid-feedback">
                                                    <strong>{{ $errors->first('kin_relationship') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Kin Address</label>
                                            <input class="form-control{{ $errors->has('kin_address') ? ' is-invalid' : '' }}" value="{{ old('kin_address') }}" type="text" name="kin_address" id="kin_address" required="required" placeholder="Enter your next of kin's address...">
                                            @if ($errors->has('kin_address'))
                                                <span class="invalid-feedback">
                                                    <strong>{{ $errors->first('kin_address') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Kin Number</label>
                                            <input class="form-control{{ $errors->has('kin_number') ? ' is-invalid' : '' }}" value="{{ old('kin_number') }}" type="text" name="kin_number" id="kin_number" required="required" placeholder="Enter your next of kin's number...">
                                            @if ($errors->has('kin_number'))
                                                <span class="invalid-feedback">
                                                    <strong>{{ $errors->first('kin_number') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <hr>
                                <h2 class="title-h2"> Banking Details</h2>
                                <hr>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Bank Name</label>
                                            <select class="form-control" type="text" name="bank_name" id="bank_name" required="required">
                                                <option value="">Please select your bank</option>
                                                @if ($banks)
                                                    @foreach($banks as $bank)
                                                        <option value='{{ $bank->id }}'>{{ $bank->bank }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Branch Name</label>
                                            <select name="branch" id="branch" class="form-control" required>
                                                <option value="">Select Branch name</option>
                                            </select>
                                        </div>
                                    </div>

                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Bank Account Number</label>
                                            <input class="form-control{{ $errors->has('bank_account') ? ' is-invalid' : '' }}" type="text" name="bank_account" id="bank_account" value="{{ old('bank_account') }}" pattern='^\d{1,3}*(\.\d+)?$' data-type="currency" required="required" placeholder="Enter your account number...">

                                            @if ($errors->has('bank_account'))
                                                <span class="invalid-feedback">
                                                    <strong>{{ $errors->first('bank_account') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-md-6">

                                    </div>
                                </div>
                                <hr>
                                <h2 class="title-h2"> Documents Upload</h2>
                                <hr>
                                <div class="row">
                                    <div class="col-lg-6">
                                        <label>NRC photo</label> <br>
                                        <input type="file" name="nrc_pic" id="nrc_pic" accept="image/*" required />
                                    </div>
                                    <div class="col-lg-6">
                                        <p>1. Please upload a cropped image of your <strong>NRC photo</strong>. <br>
                                            2. NRC photo should not be greater than 4MB. <br>
                                            3. NRC photo should of the format: jpeg,png,jpg,gif,svg. <br></p>
                                    </div>
                                </div>
                                <hr>
                                <hr>
                                <div class="row">
                                    <div class="col-lg-6">
                                        <label>Passport sized photo</label> <br>
                                        <input type="file" name="pass_photo" id="pass_photo" accept="image/*" required />
                                    </div>
                                    <div class="col-lg-6">
                                        <p>1. Please upload a cropped image of your <strong>passport sized photo</strong>. <br>
                                            2. Passport sized photo should not be greater than 4MB. <br>
                                            3. Passport sized photo should of the format: jpeg,png,jpg,gif,svg. <br></p>
                                    </div>
                                </div>
                                <hr>
                                <hr>
                                <div class="row">
                                    <div class="col-lg-6">
                                        <label>Proof of Residence photo</label> <br>
                                        <input type="file" name="por_pic" id="por_pic" accept="image/*" required />
                                    </div>
                                    <div class="col-lg-6">
                                        <p>1. Please upload a cropped image of your <strong>Proof of Residence photo</strong>. <br>
                                            2. Proof of Residence photo should not be greater than 4MB. <br>
                                            3. Proof of Residence photo should of the format: jpeg,png,jpg,gif,svg. <br></p>
                                    </div>
                                </div>
                                <hr>
                                <hr>
                                <div class="row">
                                    <div class="col-lg-6">
                                        <label>Payslip photo</label> <br>
                                        <input type="file" name="pslip_pic" id="pslip_pic" accept="image/*" required />
                                    </div>
                                    <div class="col-lg-6">
                                        <p>1. Please upload a cropped image of your <strong>payslip photo</strong>. <br>
                                            2. Payslip photo should not be greater than 4MB. <br>
                                            3. Payslip photo should of the format: jpeg,png,jpg,gif,svg. <br></p>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-lg-6">
                                        <input type="file" name="files" id="files" accept="application/pdf" />
                                    </div>
                                    <div class="col-lg-6">
                                        <p> 1. Please upload any PDF documents relating yourself. e.g. CRB record, CV, Confirmation of employment <br>
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

    function validateId() {
        var myId = document.getElementById("nrc").value;
        myId = myId.replace(/ /gi, "");
        myId = myId.replace(/\//gi, "");

        myId = insert(myId, "/", 6);
        myId = insert(myId, "/", myId.length - 1);

        document.getElementById("nrc").value = myId;
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
            maxDate: "01-01-2005",
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

    $("#work_status").select2({
        placeholder: 'Please select a valid work status',
        allowClear:true,
    });

    $("#province").select2({
        placeholder: 'Please select your province',
        allowClear:true,
    });

    $("#branch").select2({
        placeholder: 'Please select your bank branch name',
        allowClear:true,
    });

    $('#bank_name').select2({
        placeholder: 'Please select your bank',
        allowClear:true,
    }).change(function(){
        var id = $(this).val();
        var _token = $("input[name='_token']").val();
        if(id){
            $.ajax({
                type:"get",
                url:"{{url('/getBranches')}}/"+id,
                _token: _token ,
                success:function(res) {
                    if(res) {
                        $("#branch").empty();
                        $.each(res,function(key, value){
                            $("#branch").append('<option value="">Please select your bank branch name</option>').append('<option value="'+value.branch+'" data-price="'+value.branch_code+'">'+value.branch+'</option>');
                        });
                    }
                }

            });
        }
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

        input_val = formatNumber(input_val);

        if (blur === "blur") {
            input_val;
        }

        input.val(input_val);

        var updated_len = input_val.length;
        caret_pos = updated_len - original_len + caret_pos;
        input[0].setSelectionRange(caret_pos, caret_pos);
    }
</script>

</body>
</html>
