<?php
/**
 * Created by PhpStorm for eshagitwo
 * User: VinceGee
 * Date: 7/5/2022
 * Time: 4:31 AM
 */ ?>
@extends('layouts.app')

@section('template_title')
    Continue Registration
@endsection

@section('template_linked_css')

    <link href="{{ asset('css/select2.min.css')}}" rel="stylesheet" />
    <!-- datepicker -->
    <link href="{{asset('assets/libs/air-datepicker/css/datepicker.min.css')}}" rel="stylesheet" type="text/css" />

    <link href="{{asset('assets/libs/bootstrap-touchspin/jquery.bootstrap-touchspin.min.css')}}" rel="stylesheet" />
@endsection

@php
$user = auth()->user();
@endphp

@section('content')
    <div class="page-title-box">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h4 class="page-title mb-1">Zambian Clients</h4>
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Zambian Clients</a></li>
                        <li class="breadcrumb-item active">Continue Registration for {{$user->first_name.' '.$user->last_name}}</li>
                    </ol>
                </div>

                <div class="col-md-6">
                    <div class="float-right d-none d-md-block">
                        <div>
                            <h1 class="text-white"></h1>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- end page title end breadcrumb -->
    <div class="page-content-wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-body">
                            <form method="POST" action="{{ route('updatingZamReg') }}" enctype="multipart/form-data">
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
                                            <input class="form-control{{ $errors->has('first_name') ? ' is-invalid' : '' }}" type="text" name="first_name" id="first_name" value="{{ $user->first_name }}" required="required" placeholder="Enter your name...">
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
                                            <input class="form-control{{ $errors->has('middle_name') ? ' is-invalid' : '' }}" type="text" name="middle_name" id="middle_name" value="{{ old('middle_name') }}" placeholder="Enter your middlename...">
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
                                            <input class="form-control{{ $errors->has('last_name') ? ' is-invalid' : '' }}" type="text" name="last_name" id="last_name" value="{{ $user->last_name }}" required="required" placeholder="Enter your surname...">
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
                                            <label> NRC </label>
                                            <input class="form-control {{ $errors->has('nrc') ? ' is-invalid' : '' }}" type="text" autocapitalize="characters" maxlength="11" name="nrc" id="nrc" value="{{ $user->natid }}" onkeyup="validateId()" required="required" pattern="^[0-9]{6}\/[0-9]{2}\/[0-9]{1}$" title="ID Format should be in the form of xxxxxx/xx/x" placeholder="Enter your National ID as it appears on your ID Card....">
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
                                            <label>Mobile Number</label>
                                            <div class="input-group">
                                                <input class="input-group-addon form-control col-lg-2" value="+260" id="countryCode" readonly>
                                                <input class="form-control {{ $errors->has('mobile') ? ' is-invalid' : '' }}  col-lg-10" type="number" name="mobile" value="{{ $user->mobile }}" onkeyup="validateNumber()" maxlength="10" id="mobile" required="required" placeholder="EG. 775731858">
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
                                            <label>Email</label>
                                            <input class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" type="email" name="email" id="email" value="{{ $user->email }}" placeholder="Enter your email..." required>
                                            @if ($errors->has('email'))
                                                <span class="invalid-feedback">
                                                    <strong>{{ $errors->first('email') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Date of Birth</label>
                                            <input class="form-control datepicker-here{{ $errors->has('dob') ? ' is-invalid' : '' }}" data-language="en" data-date-format="dd-mm-yyyy" type="text" name="dob" id="dob" value="{{ old('dob') }}" required="required" placeholder="Enter your date of birth..." autocomplete="off">
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
                                <br>
                                <div class="spacing_1">
                                    <h2 class="title-h2"> Address Details</h2>
                                    <hr>
                                </div>
                                <div class="row">

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Address</label>
                                            <input class="form-control{{ $errors->has('address') ? ' is-invalid' : '' }} " type="text" name="address" id="address" value="{{ old('address') }}" required="required" placeholder="Enter your address...">
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
                                            <input class="form-control{{ $errors->has('zip_code') ? ' is-invalid' : '' }}" type="text" maxlength="40" name="zip_code" id="zip_code" value="{{ old('zipcode') }}" placeholder="Enter your zipcode...">
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
                                            <input class="form-control{{ $errors->has('landline') ? ' is-invalid' : '' }}" type="text" name="landline" id="landline" value="{{ old('landline') }}" placeholder="Enter your landline...">
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

                                <h2 class="title-h2"> Employment Details</h2>
                                <hr>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label id="label">Employer/Business</label>
                                            <input name="business_employer" class="form-control{{ $errors->has('business_employer') ? ' is-invalid' : '' }}" id="business_employer" value="{{ old('business_employer') }}" placeholder="Enter your employer's name...">
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
                                            <input class="form-control{{ $errors->has('ec_number') ? ' is-invalid' : '' }}" type="text" name="ec_number" id="ec_number" value="{{ old('ec_number') }}" placeholder="Enter your payroll ID...">
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
                                            <input name="institution" class="form-control{{ $errors->has('institution') ? ' is-invalid' : '' }}" id="institution" value="{{ old('institution') }}" placeholder="Enter your institution...">
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
                                            <input name="credit_score" class="form-control{{ $errors->has('credit_score') ? ' is-invalid' : '' }}" id="credit_score" value="{{ old('credit_score') }}" pattern='^\d{1,3}*(\.\d+)?$' data-type="currency" placeholder="Enter your credit score...">
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
                                            <input name="department" class="form-control{{ $errors->has('department') ? ' is-invalid' : '' }}" id="department" value="{{ old('department') }}" placeholder="Enter your department...">
                                            @if ($errors->has('department'))
                                                <span class="invalid-feedback">
                                                    <strong>{{ $errors->first('department') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>



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

                                <h2 class="title-h2"> Documents Upload</h2>
                                <hr>
                                <div class="row">
                                    <div class="col-lg-6">
                                        <input type="file" name="pass_photo" id="pass_photo" accept="image/*" required />
                                    </div>
                                    <div class="col-lg-6">
                                        <p>1. Please upload a cropped image of the your passport sized photo. <br>
                                            2. Passport sized photo should not be greater than 4MB. <br>
                                            3. Passport sized photo should of the format: jpeg,png,jpg,gif,svg. <br></p>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label>Password</label>
                                            <input class="form-control" type="password" name="password" id="password" required="required" placeholder="Enter at least a 6 character password">
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
                                        <input class="btn btn-success btn-send" type="submit" value="Complete Registration ">
                                    </div>

                                </div>
                                <hr>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('footer_scripts')
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
    </script>

    <script>
        function validateId() {
            var myId = document.getElementById("nrc").value;
            myId = myId.replace(/ /gi, "");
            myId = myId.replace(/\//gi, "");

            myId = insert(myId, "/", 6);
            myId = insert(myId, "/", myId.length - 1);

            document.getElementById("nrc").value = myId;
        }
    </script>

    <script src="{{ asset('js/select2.min.js')}}"></script>
    <script src="{{asset('assets/libs/air-datepicker/js/datepicker.min.js')}}"></script>
    <script src="{{asset('assets/libs/air-datepicker/js/i18n/datepicker.en.js')}}"></script>
    <script src="{{asset('assets/libs/bootstrap-touchspin/jquery.bootstrap-touchspin.min.js')}}"></script>
    <script src="{{asset('assets/libs/bootstrap-maxlength/bootstrap-maxlength.min.js')}}"></script>

    <script>
        $("#dob").datepicker({
            language: 'en',
            /*                minDate: new Date("01-01-1940"),
                            maxDate: new Date("01-01-2003"),*/
        });
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

        $("#work_status").select2({
            placeholder: 'Please select a valid work status',
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
@endsection
