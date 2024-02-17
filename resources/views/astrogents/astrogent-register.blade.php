<?php
/**
 * Created by PhpStorm for eshagi
 * User: vinceg
 * Date: 20/3/2021
 * Time: 17:54
 */
?>
    <!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <head>    <!-- Google tag (gtag.js) -->
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
                <img class="navbar-brand" src="{{asset('images/logo.png')}}" alt="eShagi">

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
                            <h1>Hello, Astrogent!</h1>
                            @include('partials.fe-status')
                            <p>As an Astrogent, you will work from the comfort of your home, all you need is a smartphone & eShagi.</p>
                            <br>
                            <h2 class="title-h2"> Personal Details</h2>
                            <form method="POST" action="{{ route('post.astrogent.register') }}" >
                                @csrf
                                <div class="messag"></div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Select Your Title*</label>
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
                                            <label>First Name*</label>
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
                                            <label>Surname*</label>
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
                                            <label> National ID*</label>
                                            <input class="form-control  {{ $errors->has('natid') ? ' is-invalid' : '' }}" type="text" onkeyup="formatIDNumber()" autocapitalize="characters" maxlength="15" name="natid" id="natid" value="{{ old('natid') }}" required="required" pattern="^[0-9]{2}-[0-9]{6,7}-[A-Z]-[0-9]{2}$" title="ID Format should be in the form of xx-xxxxxxx X xx" placeholder="Enter your National ID as it appears on your ID Card...">
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
                                            <label>Email*</label>
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
                                            <label>Mobile Number*</label>
                                            <div class="input-group">
                                                <input class="form-control {{ $errors->has('mobile') ? ' is-invalid' : '' }}  col-lg-12" type="number" name="mobile" value="{{ old('mobile') }}" onkeyup="validateNumber()" maxlength="10" id="mobile" required="required" placeholder="e.g. 775731858">
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
                                            <label>Select Gender*</label>
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

                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Physical Address*</label>
                                            <input class="form-control{{ $errors->has('address') ? ' is-invalid' : '' }} " type="text" name="address" id="address" value="{{ old('address') }}" required="required" placeholder="Enter your physical address...">
                                            @if ($errors->has('address'))
                                                <span class="invalid-feedback">
                                                        <strong>{{ $errors->first('address') }}</strong>
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
                                            <select class="form-control" type="text" name="bank" id="bank" >
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
                                            <select name="branch" id="branch" class="form-control" >
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
                                            <input class="form-control{{ $errors->has('branch_code') ? ' is-invalid' : '' }}" type="text" name="branch_code" id="branch_code" value="{{ old('branch_code') }}" placeholder="Please select your branch" readonly>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Account Number</label>
                                            <input class="form-control{{ $errors->has('acc_number') ? ' is-invalid' : '' }}" type="text" name="acc_number" id="acc_number" value="{{ old('acc_number') }}" pattern='^\d{1,3}*(\.\d+)?$' data-type="currency" placeholder="Enter your account number...">
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
                                            <input class="form-control{{ $errors->has('bank_acc_name') ? ' is-invalid' : '' }}" type="text" name="bank_acc_name" id="bank_acc_name" value="{{ old('bank_acc_name') }}" placeholder="Please confirm the name registered to the account...">
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
                                        <div class="form-group">
                                            <label>Password*</label>
                                            <input class="form-control" type="password" name="password" id="password" required="required" placeholder="Enter your password...">
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label>Confirm Password*</label>
                                            <input class="form-control" type="password" name="password_confirmation" id="password_confirmation" required="required" placeholder="Repeat password">
                                        </div>
                                    </div>

                                </div>
                                <div class="row">
                                    <div class="col-lg-6">
                                        <input class="btn btn-success btn-send" type="submit" value="Next: KYC Documents">
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

    function formatIDNumber(){
        var myId=document.getElementById("natid").value;
        myId=myId.replace(/ /gi, "");
        myId=myId.replace(/-/gi, "");

        myId=insert(myId, "-", 2);
        myId=insert(myId, "-", myId.length-3);
        myId=insert(myId, "-", myId.length-2);

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

<script src="{{ asset('js/select2.min.js')}}"></script>

<link rel="stylesheet" href="{{('css/jquery-ui.css')}}">
<script src="{{asset('js/jquery-ui.js')}}"></script>

<script type="text/javascript">
    $("#title").select2({
        placeholder: 'Please select your title',
        allowClear:true,
    });

    $("#gender").select2({
        placeholder: 'Please select your gender',
        allowClear:true,
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

        input.val(input_val);

        var updated_len = input_val.length;
        caret_pos = updated_len - original_len + caret_pos;
        input[0].setSelectionRange(caret_pos, caret_pos);
    }
</script>

</body>
</html>
