<?php
/**
 *Created by PhpStorm for eshagi
 *User: Vincent Guyo
 *Date: 9/24/2020
 *Time: 8:01 AM
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
    <header class="navbar-header clearfix">
        <nav class="navbar navbar-expand-lg fixed-top">
            <div class="container">
                <a class="navbar-brand" href="{{url('/')}}"><img src="{{asset('images/logo.png')}}" alt=""></a>
                <div style="right:0">
                    <ul>
                        <li>
                            <a href="{{ route('logout')}}" onclick="event.preventDefault();
                                             document.getElementById('logout-form').submit();" type="button" class="btn btn-blue">{{ trans('auth.logout') }}</a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        </li>
                    </ul>
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
                            <h1>You're nearly there!</h1>
                            <p>Just a bit more information to complete your application !</p>

                            <h2 class="title-h2"> Next of kin Details</h2>
                            @include('partials.fe-status')
                            <hr>
                            <form method="POST" action="{{route('clients.register.two.post')}}" >
                                @csrf
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Select Title</label>
                                            <select class="form-control" id="kin_title" name="kin_title" required>
                                                <option value="{{ old('kin_title') }}">{{ old('kin_title') }}</option>
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
                                            <input class="form-control{{ $errors->has('kin_fname') ? ' is-invalid' : '' }}" value="{{ old('kin_fname') }}" type="text" name="kin_fname" id="kin_fname" required="required" placeholder="Enter your next of kin's first name...">
                                            @if ($errors->has('kin_fname'))
                                                <span class="invalid-feedback">
                                                    <strong>{{ $errors->first('kin_fname') }}</strong>
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
                                            <input class="form-control{{ $errors->has('kin_lname') ? ' is-invalid' : '' }}" value="{{ old('kin_lname') }}" type="text" name="kin_lname" id="kin_lname" required="required" placeholder="Enter your next of kin's surname...">
                                            @if ($errors->has('kin_lname'))
                                                <span class="invalid-feedback">
                                                    <strong>{{ $errors->first('kin_lname') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Email</label>
                                            <input class="form-control{{ $errors->has('kin_email') ? ' is-invalid' : '' }}" type="email" value="{{ old('kin_email') }}" name="kin_email" id="kin_email" placeholder="Enter your next of kin's email (optional)...">
                                            @if ($errors->has('kin_email'))
                                                <span class="invalid-feedback">
                                                    <strong>{{ $errors->first('kin_email') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Next of Kin Place of Work</label>
                                            <input class="form-control{{ $errors->has('kin_work') ? ' is-invalid' : '' }}" type="text" name="kin_work" id="kin_work" value="{{ old('kin_work') }}" required="required" placeholder="Enter place of work for your next of kin...">
                                            @if ($errors->has('kin_work'))
                                                <span class="invalid-feedback">
                                                    <strong>{{ $errors->first('kin_work') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Next of Kin Mobile Number</label>
                                            <div class="input-group">
                                                <select class="input-group-addon custom-select form-control col-lg-2 dynamic" name="countryCode" id="countryCode" required>
                                                    <option value="{{ old('countryCode') }}">{{ old('countryCode') }}</option>
                                                    @if ($countries)
                                                        @foreach($countries as $country)
                                                            <option value="+{{ $country->country_code }}">+{{ $country->country_code }} </option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                                <input class="form-control {{ $errors->has('kin_number') ? ' is-invalid' : '' }} col-lg-10" type="number" name="kin_number" value="{{ old('kin_number') }}" onkeyup="validateNumber()"  maxlength="10" id="kin_number" required="required" placeholder="EG. 775731858">
                                                @if ($errors->has('kin_number'))
                                                    <span class="invalid-feedback">
                                                    <strong>{{ $errors->first('kin_number') }}</strong>
                                                </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Relationship With The Customer</label>
                                            <select class="form-control" id="relationship" name="relationship" onchange="verifyRelationship()" required>
                                                <option value="{{ old('relationship') }}">{{ old('relationship') }}</option>
                                                <option value="Wife">Spouse</option>
												<option value="Mother">Mother</option>
												<option value="Father">Father</option>
												<option value="Daughter">Daughter</option>
                                                <option value="Son">Son</option>
												<option value="Uncle">Uncle</option>                                              
                                                <option value="Aunt">Aunt</option>
                                                <option value="Cousin">Cousin</option>
												<option value="Other">Other</option>
                                            </select>
                                            @if ($errors->has('relationship'))
                                                <span class="invalid-feedback">
                                                        <strong>{{ $errors->first('relationship') }}</strong>
                                                    </span>
                                            @endif
                                        </div>
                                    </div>
									<div class="col-md-6" id="otherRelationship">
                                        <div class="form-group">
                                            <label>Other Relationship</label>
                                            <input class="form-control" type="text" maxlength="20" name="relationship1" id="relationship1" placeholder="Your relationship with the customer...">
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
                                            <select class="form-control{{ $errors->has('province') ? ' is-invalid' : '' }}" type="text" name="province" id="province" required="required" >
                                                <option value="{{ old('province') }}">{{ old('province') }}</option>
                                                <option value="Lusaka">Lusaka</option>
                                                <option value="Bulawayo">Copperbelt</option>
                                                <option value="Manicaland">Central</option>
                                                <option value="Mashonaland Central">Western</option>
                                                <option value="Mashonaland East">Nothwestern</option>
                                                <option value="Mashonaland West">Eastern</option>
                                                <option value="Masvingo">Luapula</option>
                                                <option value="Matabeleland North">Nothern</option>
                                                <option value="Matabeleland South">Muchinaga</option>
                                                <option value="Midlands">Southern</option>
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
                                            <select class="form-control" id="country" name="country">
                                                <option value="Zambia">Zambia</option>
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
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label> Number of months stayed at current residence</label>
                                            <input class="form-control {{ $errors->has('resduration') ? ' is-invalid' : '' }}" type="number" name="resduration" id="resduration" value="{{ old('resduration') }}" required="required" placeholder="Enter number of months stayed at current residence...">
                                            @if ($errors->has('resduration'))
                                                <span class="invalid-feedback">
                                                    <strong>{{ $errors->first('resduration') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="spacing_1">
                                    <h2 class="title-h2"> Banking Details</h2>
                                    <hr>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Bank Name</label>
                                            <select class="form-control" type="text" name="bank" id="bank" required="required">
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
                                <!-- message input -->
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
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Bank Account Name</label>
                                            <input class="form-control{{ $errors->has('bank_acc_name') ? ' is-invalid' : '' }}" type="text" name="bank_acc_name" id="bank_acc_name" required="required" value="{{auth()->user()->first_name.' '. auth()->user()->last_name}}" placeholder="Please confirm the name registered to the account...">
                                            @if ($errors->has('bank_acc_name'))
                                                <span class="invalid-feedback">
                                                    <strong>{{ $errors->first('bank_acc_name') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-md-6">

                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-lg-6">
                                        <label>Loan Purpose</label>
                                        <input type="text" class="form-control col-lg-10" name="loan_purpose" id="loan_purpose" placeholder="Enter loan purpose" >
                                    </div>
                                </div>
                                <hr>
                                <br>
                                <br>
                                <input class="btn btn-success btn-send" type="submit" value="Next, Your KYC">

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
        var myLength=document.getElementById("kin_number").value.length;
        var myNumber=document.getElementById("kin_number").value;
        if(myLength >=10){
            document.getElementById("kin_number").value=myNumber.substring(0, myNumber.length - 1);
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
{{--<script src="{{asset('js/mo.min.js')}} "></script>
<script src="{{asset('js/main.js')}} "></script>--}}

<script src="{{ asset('js/select2.min.js')}}"></script>

<script type="text/javascript">

	document.getElementById("otherRelationship").style.visibility = "hidden";

    function verifyRelationship(){
        if(document.getElementById("relationship").value=="other") {
            document.getElementById("otherRelationship").style.visibility = "visible";
        } else {
            document.getElementById("otherRelationship").style.visibility = "hidden";
            document.getElementById("relationship").value = document.getElementById("relationship").value;
        }
    }
	
    $("#kin_title").select2({
        placeholder: 'Please select your next of kin\'s title',
        allowClear:true,
    });

    $("#countryCode").select2({
        placeholder: 'Code.',
        allowClear:true,
    })

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
