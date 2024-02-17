<?php
/**
 * Created by PhpStorm for eshagitwo
 * User: VinceGee
 * Date: 5/27/2022
 * Time: 9:45 AM
 */ ?>
@extends('layouts.app')

@section('template_title')
    ZWMB KYC
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
                    <h4 class="page-title mb-1">ZWMB Clients</h4>
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">ZWMB Clients</a></li>
                        <li class="breadcrumb-item active">ZWMB Client KYC for {{$client->natid}}</li>
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
                            <form method="POST" action="{{ route('zwmbs.store') }}" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="natid" value="{{$client->natid}}">
                                <input type="hidden" name="user_id" value="{{$user->id}}">
                                <div class="messag"></div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Select Account Type *</label>
                                            <select class="form-control" id="account_type" name="account_type" required>
                                                <option value="{{ old('account_type') }}">{{ old('account_type') }}</option>
                                                <option value="Mirco">Micro </option>
                                                <option value="Personal">Personal </option>
                                                <option value="Fixed">Fixed </option>
                                                <option value="Other">Other </option>
                                            </select>
                                            @if ($errors->has('account_type'))
                                                <span class="invalid-feedback">
                                                        <strong>{{ $errors->first('account_type') }}</strong>
                                                    </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Maiden Name</label>
                                            <input class="form-control{{ $errors->has('maiden_name') ? ' is-invalid' : '' }}" type="text" name="maiden_name" id="maiden_name" value="{{ old('maiden_name') }}" placeholder="Enter client maiden name...">
                                            @if ($errors->has('maiden_name'))
                                                <span class="invalid-feedback">
                                                    <strong>{{ $errors->first('maiden_name') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <!-- email input -->
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Passport Number</label>
                                            <input class="form-control{{ $errors->has('passport_number') ? ' is-invalid' : '' }}" type="text" name="passport_number" id="passport_number" value="{{ old('passport_number') }}" placeholder="Enter client passport number...">
                                            @if ($errors->has('passport_number'))
                                                <span class="invalid-feedback">
                                                    <strong>{{ $errors->first('passport_number') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Driver's Licence</label>
                                            <input class="form-control{{ $errors->has('driver_licence') ? ' is-invalid' : '' }}" type="text" name="driver_licence" id="driver_licence" value="{{ old('driver_licence') }}" placeholder="Enter client driver's licence...">
                                            @if ($errors->has('driver_licence'))
                                                <span class="invalid-feedback">
                                                    <strong>{{ $errors->first('driver_licence') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Mobile Banking Number</label>
                                            <input class="form-control{{ $errors->has('mobile_banking_num') ? ' is-invalid' : '' }}" type="text" name="mobile_banking_num" id="mobile_banking_num" value="{{ $client->mobile }}" placeholder="Enter client mobile banking registered number...">
                                            @if ($errors->has('mobile_banking_num'))
                                                <span class="invalid-feedback">
                                                    <strong>{{ $errors->first('mobile_banking_num') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6">

                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label> Sector/Race (For RBZ statistics) *</label>
                                            <select class="form-control" id="race" name="race" required>
                                                <option value="{{ old('race') }}">{{ old('race') }}</option>
                                                <option value="Staff">Staff </option>
                                                <option value="African">African </option>
                                                <option value="European">European </option>
                                                <option value="AsianColoured">Asian or Coloured </option>
                                                <option value="Minor">Minor</option>
                                                <option value="Senior Citizen">Senior Citizen</option>
                                            </select>
                                            @if ($errors->has('race'))
                                                <span class="invalid-feedback">
                                                    <strong>{{ $errors->first('race') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Occupation  *</label>
                                            <div class="input-group">
                                                <input class="form-control{{ $errors->has('occupation') ? ' is-invalid' : '' }}" type="text" name="occupation" id="occupation" value="{{ old('occupation') }}" placeholder="Enter client occupation..." required>
                                                @if ($errors->has('occupation'))
                                                    <span class="invalid-feedback">
                                                    <strong>{{ $errors->first('occupation') }}</strong>
                                                </span>
                                                @endif
                                            </div>

                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Employer Name *</label>
                                            <input class="form-control{{ $errors->has('employer_name') ? ' is-invalid' : '' }}" type="text" name="employer_name" id="employer_name" value="{{ $kyc->employer }}" placeholder="Enter client employer..." required>
                                            @if ($errors->has('employer_name'))
                                                <span class="invalid-feedback">
                                                    <strong>{{ $errors->first('employer_name') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Employer Contact Person *</label>
                                            <input class="form-control {{ $errors->has('employer_contact_person') ? ' is-invalid' : '' }}" type="text" name="employer_contact_person" id="employer_contact_person" value="{{ old('employer_contact_person') }}" required="required" placeholder="Enter employer contact person..." >
                                            @if ($errors->has('employer_contact_person'))
                                                <span class="invalid-feedback">
                                                    <strong>{{ $errors->first('employer_contact_person') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="row">

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Contact Person Designation *</label>
                                            <input class="form-control {{ $errors->has('designation') ? ' is-invalid' : '' }}" type="text" name="designation" id="designation" value="{{ old('designation') }}" required="required" placeholder="Enter employer contact person designation..." >

                                        @if ($errors->has('designation'))
                                                <span class="invalid-feedback">
                                                        <strong>{{ $errors->first('designation') }}</strong>
                                                    </span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Nature of Employer Business *</label>
                                            <select class="form-control" id="nature_employer" name="nature_employer" required>
                                                <option value="{{ old('nature_employer') }}">{{ old('nature_employer') }}</option>
                                                <option value="Manufacturing">Manufacturing </option>
                                                <option value="Mining">Mining </option>
                                                <option value="Distribution">Distribution </option>
                                                <option value="Agriculture">Agriculture </option>
                                                <option value="Transport">Transport</option>
                                                <option value="Communications">Communications</option>
                                                <option value="Financial Services">Financial Services</option>
                                                <option value="Construction">Construction</option>
                                                <option value="Services">Services</option>
                                                <option value="Other">Other</option>
                                            </select>
                                        @if ($errors->has('nature_employer'))
                                                <span class="invalid-feedback">
                                                        <strong>{{ $errors->first('nature_employer') }}</strong>
                                                    </span>
                                            @endif
                                        </div>
                                    </div>


                                </div>
                                <br>

                                <h2 class="title-h2"> Next of Kin Details</h2>
                                <hr>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Next of Kin *</label>
                                            <input class="form-control{{ $errors->has('kin_name') ? ' is-invalid' : '' }}" value="{{ $kyc->kin_fname.' '.$kyc->kin_lname }}" type="text" name="kin_name" id="kin_name" required="required" placeholder="Enter your next of kin's full name...">
                                            @if ($errors->has('kin_name'))
                                                <span class="invalid-feedback">
                                                    <strong>{{ $errors->first('kin_name') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Next of Kin Relationship *</label>
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
                                            <label>Kin Address *</label>
                                            <input class="form-control{{ $errors->has('kin_address') ? ' is-invalid' : '' }}" value="{{ old('kin_address') }}" type="text" name="kin_address" id="kin_address" required="required" placeholder="Enter your next of kin's address...">
                                            @if ($errors->has('kin_address'))
                                                <span class="invalid-feedback">
                                                    <strong>{{ $errors->first('kin_address') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-md-6">

                                    </div>
                                </div>

                                <h2 class="title-h2"> Banking Services</h2>
                                <hr>

                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <div class="custom-control custom-checkbox mt-2 mb-2">
                                                <input type="checkbox" class="custom-control-input" id="mobile_banking" name="mobile_banking">
                                                <label class="custom-control-label" for="mobile_banking">Mobile Banking</label>
                                            </div>
                                            @if ($errors->has('mobile_banking'))
                                                <span class="invalid-feedback">
                                                        <strong>{{ $errors->first('mobile_banking') }}</strong>
                                                    </span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <div class="custom-control custom-checkbox mt-2 mb-2">
                                                <input type="checkbox" class="custom-control-input" id="internet_banking" name="internet_banking">
                                                <label class="custom-control-label" for="internet_banking">Internet Banking</label>
                                            </div>
                                            @if ($errors->has('internet_banking'))
                                                <span class="invalid-feedback">
                                                        <strong>{{ $errors->first('internet_banking') }}</strong>
                                                    </span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <div class="custom-control custom-checkbox mt-2 mb-2">
                                                <input type="checkbox" class="custom-control-input" id="sms_alerts" name="sms_alerts">
                                                <label class="custom-control-label" for="sms_alerts">SMS Alerts</label>
                                            </div>
                                            @if ($errors->has('sms_alerts'))
                                                <span class="invalid-feedback">
                                                        <strong>{{ $errors->first('sms_alerts') }}</strong>
                                                    </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <div class="custom-control custom-checkbox mt-2 mb-2">
                                                <input type="checkbox" class="custom-control-input" id="bank_card_local" name="bank_card_local">
                                                <label class="custom-control-label" for="bank_card_local">Bank Card (Local)</label>
                                            </div>
                                            @if ($errors->has('bank_card_local'))
                                                <span class="invalid-feedback">
                                                        <strong>{{ $errors->first('bank_card_local') }}</strong>
                                                    </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <h2 class="title-h2"> Documents Upload</h2>
                                <hr>
                                <div class="row">
                                    <div class="col-lg-6">
                                        <input type="file" name="proof_res" id="proof_res" accept="image/*" required /> *
                                    </div>
                                    <div class="col-lg-6">
                                        <p>1. Please upload a cropped image of the client's proof of residence. <br>
                                            2. Passport sized photo should not be greater than 4MB. <br>
                                            3. Passport sized photo should of the format: jpeg,png,jpg,gif,svg. <br></p>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-lg-6">
                                        <input type="file" name="proof_of_income" id="proof_of_income" accept="image/*" required /> *
                                    </div>
                                    <div class="col-lg-6">
                                        <p> 1. Please upload the proof of income for the client. <br>
                                            2. Passport sized photo should not be greater than 4MB. <br>
                                            3. Passport sized photo should of the format: jpeg,png,jpg,gif,svg. <br></p>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-6">
                                        <input class="btn btn-success btn-send" type="submit" value="Update Client Record">
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
        $("#account_type").select2({
            placeholder: 'Please select an account type',
            allowClear:true,
        });

        $("#race").select2({
            placeholder: 'Please select client race',
            allowClear:true,
        });

        $("#nature_employer").select2({
            placeholder: 'Please select nature of business',
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
