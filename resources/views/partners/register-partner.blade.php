<?php
/**
 *Created by PhpStorm for eshagi
 *User: Vincent Guyo
 *Date: 9/23/2020
 *Time: 12:41 PM
 */
?>
<!DOCTYPE html>
<html lang="en">

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
                <img class="navbar-brand" src="{{asset('images/logo.png')}}" alt="eShagi">
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

                            <h2 class="title-h2"> Business Details</h2>
                            <hr>
                            <form method="POST" action="{{ route('kupinda-kwepatina') }}">
                                @csrf
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Partner Name</label>
                                            <input class="form-control{{ $errors->has('partner_name') ? ' is-invalid' : '' }}" type="text" name="partner_name" id="partner_name" required="required" value="{{old('partner_name')}}" placeholder="Enter your company name...">
                                            @if ($errors->has('partner_name'))
                                                <span class="invalid-feedback">
                                                    <strong>{{ $errors->first('partner_name') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Partner Type</label>
                                            <select class="form-control{{ $errors->has('partner_type') ? ' is-invalid' : '' }}" id="partner_type" name="partner_type" required>
                                                <option value="{{ old('partner_type') }}">{{ old('partner_type') }}</option>
                                                <option value="Agent">Agent</option>
                                                <option value="Merchant">Merchant</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <!-- email input -->
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Trading name (if different)</label>
                                            <input class="form-control{{ $errors->has('merchantname') ? ' is-invalid' : '' }}" type="text" name="merchantname" id="merchantname" value="{{old('merchantname')}}" placeholder="Enter trading name...">
                                            @if ($errors->has('merchantname'))
                                                <span class="invalid-feedback">
                                                    <strong>{{ $errors->first('merchantname') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Type of Business</label>
                                            <select class="form-control{{ $errors->has('business_type') ? ' is-invalid' : '' }}" id="business_type" name="business_type" onchange='businessTypeChanged()' required>
                                                <option value="{{ old('business_type') }}">{{ old('business_type') }}</option>
                                                <option value="Private Limited Company">Private Limited Company</option>
                                                <option value="Sole Trader">Sole Trader</option>
                                                <option value="Private Business Corporation">Private Business Corporation</option>
                                                <option value="Co-Operative Society">Co-Operative Society</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Partner Trading Details</label>
                                            <input class="form-control{{ $errors->has('partnerDesc') ? ' is-invalid' : '' }}" type="text" name="partnerDesc" id="partnerDesc" value="{{old('partnerDesc')}}" required="required" placeholder="Enter description of business activities...">
                                            @if ($errors->has('partnerDesc'))
                                                <span class="invalid-feedback">
                                                    <strong>{{ $errors->first('partnerDesc') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Years Trading</label>
                                            <select class="form-control{{ $errors->has('yearsTrading') ? ' is-invalid' : '' }}" id="yearsTrading" name="yearsTrading" required>
                                                <option value="{{ old('yearsTrading') }}">{{ old('yearsTrading') }}</option>
                                                <option value=1>1</option>
                                                <option value=2>2</option>
                                                <option value=3>3</option>
                                                <option value=4>4</option>
                                                <option value=5>5</option>
                                                <option value="5+">5+</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row" id="other_details">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>PACRA No.</label>
                                            <input class="form-control{{ $errors->has('regNumber') ? ' is-invalid' : '' }}" type="text" name="regNumber" id="regNumber" value="{{old('regNumber')}}" placeholder="Enter your company PACRA No....">
                                            @if ($errors->has('regNumber'))
                                                <span class="invalid-feedback">
                                                    <strong>{{ $errors->first('regNumber') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>TPIN</label>
                                            <input class="form-control{{ $errors->has('bpNumber') ? ' is-invalid' : '' }}" type="text" name="bpNumber" id="bpNumber" value="{{old('bpNumber')}}" placeholder="Enter your TPIN ...">
                                            @if ($errors->has('bpNumber'))
                                                <span class="invalid-feedback">
                                                    <strong>{{ $errors->first('bpNumber') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>VAT Registered?</label>
                                            <select class="form-control" type="text" name="vatRegNumber" id="vatRegNumber" required="required">                                               
                                                <option value="">Is your company / business VAT registered?</option>
                                            @if(!empty(old('vatRegNumber')))    
                                                <option value="{{ old('vatRegNumber') }}">{{ old('vatRegNumber') }}</option>
                                            @endif    
                                                <option value="YES">YES</option>
                                                <option value="NO">NO</option>
                                            </select>
                                        </div>
                                    </div>                                    
                                </div>
                                <hr>
                                <h2 class="title-h2"> Address Details</h2>
                                <hr>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Plot Number</label>
                                            <input class="form-control{{ $errors->has('propNumber') ? ' is-invalid' : '' }}" type="text" name="propNumber" id="propNumber" value="{{old('propNumber')}}" required="required" placeholder="Enter your Plot Number...">
                                            @if ($errors->has('propNumber'))
                                                <span class="invalid-feedback">
                                                    <strong>{{ $errors->first('propNumber') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Street Name</label>
                                            <input class="form-control{{ $errors->has('street') ? ' is-invalid' : '' }}" type="text" name="street" id="street" value="{{old('street')}}" required="required" placeholder="Enter your street name...">
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
                                            <input class="form-control{{ $errors->has('suburb') ? ' is-invalid' : '' }}" type="text" name="suburb" id="suburb" value="{{old('suburb')}}" required="required" placeholder="Enter your suburb...">
                                            @if ($errors->has('suburb'))
                                                <span class="invalid-feedback">
                                                    <strong>{{ $errors->first('suburb') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>City</label>
                                            <input class="form-control{{ $errors->has('city') ? ' is-invalid' : '' }}" type="text" name="city" id="city" value="{{old('city')}}" required="required" placeholder="Enter your city...">
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
                                            <label>Country</label>
                                            <select class="form-control{{ $errors->has('country') ? ' is-invalid' : '' }}" name="country" id="country" required>
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
                                            <label>Province</label>
                                            <select class="form-control{{ $errors->has('province') ? ' is-invalid' : '' }}" name="province" id="province" required="required" >
                                                <option value="{{ old('province') }}">{{ old('province') }}</option>
                                            </select>
                                        </div>
                                    </div>                                    
                                </div>
                                <hr>
                                <h2 class="title-h2"> Contact Details</h2>
                                <hr>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Contact Person Full Name</label>
                                            <input class="form-control{{ $errors->has('cfullname') ? ' is-invalid' : '' }}" type="text" name="cfullname" id="cfullname" value="{{old('cfullname')}}" required="required" placeholder="Enter contact person's full name...">
                                            @if ($errors->has('cfullname'))
                                                <span class="invalid-feedback">
                                                    <strong>{{ $errors->first('cfullname') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Contact Person Designation</label>
                                            <input class="form-control{{ $errors->has('cdesignation') ? ' is-invalid' : '' }}" type="text" name="cdesignation" id="cdesignation" value="{{old('cfullname')}}" required="required" placeholder="Enter contact person's designation e.g. CEO...">
                                            @if ($errors->has('cdesignation'))
                                                <span class="invalid-feedback">
                                                    <strong>{{ $errors->first('cdesignation') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Telephone No</label>
                                            <input class="form-control{{ $errors->has('telephoneNo') ? ' is-invalid' : '' }}" type="text" name="telephoneNo" id="telephoneNo" value="{{old('cfullname')}}" onkeyup="validateNumber()" maxlength="10" required="required" pattern='^\d{1,3}*(\.\d+)?$' data-type="currency" placeholder="Enter contact person's telephone number, e.g. 773321123">
                                            @if ($errors->has('telephoneNo'))
                                                <span class="invalid-feedback">
                                                    <strong>{{ $errors->first('telephoneNo') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>E-mail</label>
                                            <input class="form-control{{ $errors->has('cemail') ? ' is-invalid' : '' }}" type="email" name="cemail" id="cemail" value="{{old('cemail')}}" required="required" placeholder="Enter contact person's email...">
                                            @if ($errors->has('cemail'))
                                                <span class="invalid-feedback">
                                                    <strong>{{ $errors->first('cemail') }}</strong>
                                                </span>
                                            @endif											
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <h2 class="title-h2">Banking Details</h2>
                                <hr>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Bank Name</label>
                                            <select class="form-control" type="text" name="bank" id="bank" required="required">
                                                @if ($banks)
                                                    @foreach($banks as $bank)
                                                        <option value="">Please select your bank</option>
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
                                            <label>Branch Code</label>
                                            <input class="form-control{{ $errors->has('branch_code') ? ' is-invalid' : '' }}" type="text" name="branch_code" id="branch_code" required value="{{ old('branch_code') }}" placeholder="Please select your branch" readonly>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Account Number</label>
                                            <input class="form-control{{ $errors->has('acc_number') ? ' is-invalid' : '' }}" type="text" name="acc_number" id="acc_number" value="{{ old('acc_number') }}" pattern='^\d{1,3}*(\.\d+)?$' data-type="currency" required="required" placeholder="Enter your account number...">
                                            @if ($errors->has('acc_number'))
                                                <span class="invalid-feedback">
                                                    <strong>{{ $errors->first('acc_number') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <hr>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Password</label>
                                            <input class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" type="password" name="password" id="password" required="required" placeholder="Enter your password...">
                                            @if ($errors->has('password'))
                                                <span class="invalid-feedback">
                                                    <strong>{{ $errors->first('password') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Confirm Password</label>
                                            <div class="input-group">
                                                <input class="form-control{{ $errors->has('password_confirmation') ? ' is-invalid' : '' }}" type="password" id="password_confirmation" name="password_confirmation" placeholder="Confirm Password">
                                                @if ($errors->has('password_confirmation'))
                                                    <span class="invalid-feedback">
                                                    <strong>{{ $errors->first('password_confirmation') }}</strong>
                                                </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-lg-6">
                                        <input class="btn btn-success btn-send" type="submit" value="Next : Business Documents ">
                                    </div>
                                </div>
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
    function validateNumber(){
        var myLength=document.getElementById("telephoneNo").value.length;
        var myNumber=document.getElementById("telephoneNo").value;
        if(myLength >=10){
            document.getElementById("telephoneNo").value=myNumber.substring(0, myNumber.length - 1);
        }
    }
</script>

    <script>
        document.getElementById('other_details').style.visibility = "visible";
        function businessTypeChanged(){

            if(document.getElementById('business_type').value != "Sole Trader"){
                document.getElementById('other_details').style.visibility = "visible";
            }
            else
            {
                document.getElementById('other_details').style.visibility = "hidden";
            }
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
    <script src="js/merchantReg.js "></script>

<script src="{{ asset('js/select2.min.js')}}"></script>

<script type="text/javascript">
    $("#partner_type").select2({
        placeholder: 'Please select your partner type',
        allowClear:true,
    });

    $("#business_type").select2({
        placeholder: 'Please select your business type',
        allowClear:true,
    });

    $("#yearsTrading").select2({
        placeholder: 'Please select your years in Business',
        allowClear:true,
    });

    $("#province").select2({
        placeholder: 'Please select your province',
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

            /*$.ajax({
                type:"get",
                url:"{{url('/getBanksByCountry')}}/"+id,
                _token: _token ,
                success:function(res) {
                    if(res) {
                        $("#bank").empty();
                        $.each(res,function(key, value){
                            $("#bank").append('<option value="">Please select a bank</option>').append('<option value="'+value.id+'">'+value.bank+'</option>');
                        });
                    }
                }

            });*/
        }
    });

    $("#branch").select2({
        placeholder: 'Please select your bank branch name',
        allowClear:true,
    }).change(function(){
        var price = $(this).children('option:selected').data('price');
        $('#branch_code').val(price);
    });

    $('#bank').select2({
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
