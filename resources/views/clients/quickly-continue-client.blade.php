<?php
/**
 * Created by PhpStorm for eshagi
 * User: vinceg
 * Date: 20/2/2021
 * Time: 07:42
 */
?>

@extends('layouts.app')

@section('template_title')
    Register New Client
@endsection

@section('template_linked_css')

    <link href="{{ asset('css/select2.min.css')}}" rel="stylesheet" />
    <!-- datepicker -->
    <link href="{{asset('assets/libs/air-datepicker/css/datepicker.min.css')}}" rel="stylesheet" type="text/css" />

    <link href="{{asset('assets/libs/bootstrap-touchspin/jquery.bootstrap-touchspin.min.css')}}" rel="stylesheet" />
@endsection

@section('content')
    <div class="page-title-box">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h4 class="page-title mb-1">Clients</h4>
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Clients</a></li>
                        <li class="breadcrumb-item active">Register Client</li>
                    </ol>
                </div>

                <div class="col-md-6">
                    <div class="float-right d-none d-md-block">
                        <div>
                            <h1 class="text-white">Step 1 of 5</h1>
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
                            <form method="POST" action="{{ route('post.register.one') }}" >
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
                                            <input class="form-control{{ $errors->has('first_name') ? ' is-invalid' : '' }}" type="text" name="first_name" id="first_name" value="{{ $user->first_name ?? old('first_name') }}" required="required" placeholder="Enter client name...">
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
                                            <input class="form-control{{ $errors->has('last_name') ? ' is-invalid' : '' }}" type="text" name="last_name" id="last_name" value="{{ $user->last_name }}" required="required" placeholder="Enter client surname...">
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
                                            <input class="form-control  {{ $errors->has('natid') ? ' is-invalid' : '' }}" type="text" autocapitalize="characters" @if(auth()->user()->locale == 2) maxlength="11" @else maxlength="15" @endif name="natid" id="natid" value="{{ $user->natid ?? old('natid') }}" onkeyup="validateId()" required="required" pattern="^[0-9]{2}-[0-9]{6,7}-[A-Z]-[0-9]{2}$|^[0-9]{6}\/[0-9]{2}\/[0-9]{1}$" title="ID Format should be in the form of xx-xxxxxxx X xx" placeholder="Enter client National ID as it appears on client ID Card....">
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
                                            <input class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" type="email" name="email" id="email" value="{{ $user->email ?? old('email') }}" placeholder="Enter client email..." required>
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
                                                <input class="input-group-addon form-control col-lg-2" value="+{{$locale->country_code}}" id="countryCode" readonly>
                                                <input class="form-control {{ $errors->has('mobile') ? ' is-invalid' : '' }}  col-lg-10" type="number" name="mobile" value="{{ $user->mobile ?? old('mobile') }}" onkeyup="validateNumber()" maxlength="10" id="mobile" required="required" placeholder="EG. 775731858">
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
                                            <input class="form-control datepicker-here{{ $errors->has('dob') ? ' is-invalid' : '' }}" data-language="en" data-date-format="dd-mm-yyyy" type="text" name="dob" id="dob" value="{{ old('dob') }}" required="required" placeholder="Enter client date of birth..." autocomplete="off">
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
                                            <input class="form-control {{ $errors->has('dependants') ? ' is-invalid' : '' }}" type="number" max=10 name="dependants" id="dependants" value="{{ old('dependants') }}" required="required" placeholder="Enter client dependants...">
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
                                            <input class="form-control" type="text" maxlength="20" name="nationality1" id="nationality1" placeholder="Specify client Nationality...">

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
                                            <input name="employer" class="form-control{{ $errors->has('employer') ? ' is-invalid' : '' }}" id="employer" value="{{ old('employer') }}" placeholder="Enter client employer's name..." required>
                                            @if ($errors->has('employer'))
                                                <span class="invalid-feedback">
                                                    <strong>{{ $errors->first('employer') }}</strong>
                                                </span>
                                        @endif
                                        <!--<input class="form-control" type="text" name="employer_name" id="employer_name" required="required" placeholder="Enter client employer's name...">-->
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>EC Number/Payroll ID</label>
                                            <input class="form-control{{ $errors->has('ecnumber') ? ' is-invalid' : '' }}" type="text" name="ecnumber" id="ecnumber" value="{{ $lead->ecnumber ?? old('ecnumber') }}" required="required" placeholder="Enter client payroll ID...">
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
                                                <input class="form-control {{ $errors->has('gross') ? ' is-invalid' : '' }} col-lg-12" name="gross" type="text" pattern="^\d{1,3}*(\.\d+)?$" value="{{ old('gross') }}" data-type="currency" id="gross" required placeholder="Enter client Gross Salary">
                                                @if ($errors->has('gross'))
                                                    <span class="invalid-feedback">
                                                        <strong>{{ $errors->first('gross') }}</strong>
                                                    </span>
                                                @endif
                                            </div>

                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>RTGS Net Monthly Salary</label>
                                            <div class="input-group">
                                                <input class="form-control {{ $errors->has('salary') ? ' is-invalid' : '' }} col-lg-12" name="salary" type="text" pattern="^\d{1,3}*(\.\d+)?$" value="{{ old('salary') }}" data-type="currency" id="salary" required placeholder="Enter client Net Salary">

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
                                                <input class="form-control {{ $errors->has('usd_gross') ? ' is-invalid' : '' }} col-lg-12" name="usd_gross" type="text" pattern="^\d{1,3}*(\.\d+)?$" value="{{ old('usd_gross') }}" data-type="currency" id="usd_gross" placeholder="Enter client USD Gross Salary(if any)">
                                                @if ($errors->has('usd_gross'))
                                                    <span class="invalid-feedback">
                                                        <strong>{{ $errors->first('usd_gross') }}</strong>
                                                    </span>
                                                @endif
                                            </div>

                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>USD Net Monthly Salary</label>
                                            <div class="input-group">
                                                <input class="form-control {{ $errors->has('usd_salary') ? ' is-invalid' : '' }} col-lg-12" name="usd_salary" type="text" pattern="^\d{1,3}*(\.\d+)?$" value="{{ old('usd_salary') }}" data-type="currency" id="usd_salary" placeholder="Enter client USD Net Salary(if any)">

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
                                            <input class="form-control{{ $errors->has('house_num') ? ' is-invalid' : '' }} " type="text" name="house_num" id="house_num" value="{{ $lead->address ?? old('house_num') }}" required="required" placeholder="Enter client house number...">
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
                                            <input class="form-control{{ $errors->has('street') ? ' is-invalid' : '' }}" type="text" maxlength="40" name="street" id="street" value="{{ old('street') }}" required="required" placeholder="Enter client street name...">
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
                                            <input class="form-control{{ $errors->has('surburb') ? ' is-invalid' : '' }}" type="text" maxlength="40" name="surburb" id="surburb" value="{{ old('surburb') }}" required="required" placeholder="Enter client suburb...">
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
                                            <input class="form-control" type="password" name="password" id="password" required="required" placeholder="Enter client password...">
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
                                        <input class="btn btn-success btn-send" type="submit" value="Next : Remaining Client Details ">
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
        if({!! auth()->user()->locale == 2 !!}) {
            function validateId() {
                var myId = document.getElementById("natid").value;
                myId = myId.replace(/ /gi, "");
                myId = myId.replace(/\//gi, "");

                myId = insert(myId, "/", 6);
                myId = insert(myId, "/", myId.length - 1);

                document.getElementById("natid").value = myId;
            }
        } else {

            function validateId() {
                var myId = document.getElementById("natid").value;
                myId = myId.replace(/ /gi, "");
                myId = myId.replace(/-/gi, "");

                myId = insert(myId, "-", 2);
                myId = insert(myId, "-", myId.length - 3);
                myId = insert(myId, "-", myId.length - 2);

                document.getElementById("natid").value = myId;
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
            placeholder: 'Please select client title',
            allowClear:true,
        });

        $("#gender").select2({
            placeholder: 'Please select client gender',
            allowClear:true,
        });

        $("#marital_state").select2({
            placeholder: 'Please select client marital status',
            allowClear:true,
        });

        $("#nationality").select2({
            placeholder: 'Please select client nationality',
            allowClear:true,
        });

        $("#emp_sector").select2({
            placeholder: 'Please select client employment sector',
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
            placeholder: 'Please select client House ownership status',
            allowClear:true,
        });

        $("#province").select2({
            placeholder: 'Please select country then client province',
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
@endsection
