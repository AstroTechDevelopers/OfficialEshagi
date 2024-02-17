<?php
/**
 *Created by PhpStorm for eshagi
 *User: Vincent Guyo
 *Date: 11/10/2020
 *Time: 12:26 PM
 */

?>
@extends('layouts.app')

@section('template_title')
    Apply For A Loan
@endsection

@section('template_linked_css')

    <link href="{{ asset('css/select2.min.css')}}" rel="stylesheet" />
@endsection

@section('content')
    <div class="page-title-box">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-md-4">
                    <h4 class="page-title mb-1">Loan</h4>
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{url('/partner-loans')}}">Loan</a></li>
                        <li class="breadcrumb-item active">Apply For A Existing Customer Credit Loan</li>
                    </ol>
                </div>

                <div class="col-md-8">
                    <div class="float-right d-none d-md-block">
                        <div>
                            <h1 class="text-white">Hybrid (Cash + Product) Loan</h1>
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
                            {!! Form::open(array('route' => 'uploadPartnerHybrid', 'method' => 'POST', 'id' => 'loanForm','role' => 'form', 'class' => 'needs-validation')) !!}
                            {!! csrf_field() !!}

                            <input type="hidden" name="channel_id" value="www.eshagi.com">
                            <input type="hidden" name="user_id" value="">
                            <input type="hidden" name="loan_type" value="4">
                            <h2 class="title-h2">Client Details</h2>
                            <hr>
                            <div class="row">
                                <div class="col-lg-6">
                                    <label>Client</label>
                                    <select class="form-control" id="client_id" name="client_id">
                                        <option value="">Select Client </option>
                                        @if ($clients)
                                            @foreach($clients as $client)
                                                <option value="{{$client->id}}" data-user_id="{{$client->user_id}}" data-credit="{{$client->cred_limit}}" data-fsb_score="{{$client->fsb_score}}" data-fsb_rating="{{$client->fsb_rating}}" data-fsb_status="{{$client->fsb_status}}" >{{ $client->first_name }} {{ $client->last_name }}  {{ $client->natid }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                                <div class="col-lg-6">
                                    <label>Credit Limit</label>
                                    <p>{{$locale->symbol}}<input class="{{ $errors->has('cred_limit') ? ' is-invalid' : '' }} col-lg-6" style="border: none;width: fit-content;" type="text" name="cred_limit" id="cred_limit" required value="{{ old('cred_limit') }}" placeholder="" readonly></p>

                                </div>
                            </div>
                            <br>

                            <h2 class="title-h2">Purchase Details</h2>
                            <hr>

                            <div class="row">
                                <div class="col-lg-6">
                                    <label>Product Description</label>
                                    <select class="form-control" id="prod_descrip" name="prod_descrip" required>
                                        <option value="">Select Product </option>
                                        @if ($products)
                                            @foreach($products as $product)
                                                <option value="{{$product->pcode}}" data-price="{{$product->price}}" >{{ $product->pcode }} - {{ $product->pname }} (${{$product->price}}) </option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>

                                <div class="col-lg-6">
                                    <label>Cash Back Amount</label>
                                    <div class="input-group">
                                        <input class="input-group-addon form-control col-lg-2" value="{{$locale->currency_code.'('.$locale->symbol.')'}}" readonly>
                                        <input type="number" step=0.01 class="form-control col-lg-10" name="cashamount" id="cashamount" placeholder="Enter cash loan amount" value="{{old('cashamount')}}">
                                    </div>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-lg-6">
                                    <label>Product Price</label>
                                    <div class="input-group">
                                        <input class="input-group-addon form-control col-lg-2" value="{{$locale->currency_code.'('.$locale->symbol.')'}}" readonly>
                                        <input type="number" step=0.01 class="form-control col-lg-10" name="amount" id="amount" placeholder="Credit item cost" value="{{old('amount')}}">
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <label>Repayment Period</label>
                                    <select onchange="calculate();" class="form-control" id="paybackPeriod" name="paybackPeriod">
                                        <option value="{{old('paybackPeriod')}}">{{old('paybackPeriod')}}</option>
                                        <option value="2">2 months</option>
                                        <option value="3">3 months</option>
                                        <option value="4">4 months</option>
                                        <option value="5">5 months</option>
                                        <option value="6">6 months</option>
                                        <option value="7">7 months</option>
                                        <option value="8">8 months</option>
                                        <option value="9">9 months</option>
                                        <option value="10">10 months</option>
                                        <option value="11">11 months</option>
                                        <option value="12">12 months</option>
                                        <option value="13">13 months</option>
                                        <option value="14">14 months</option>
                                        <option value="15">15 months</option>
                                        <option value="16">16 months</option>
                                        <option value="17">17 months</option>
                                        <option value="18">18 months</option>
                                        <option value="19">19 months</option>
                                        <option value="20">20 months</option>
                                        <option value="21">21 months</option>
                                        <option value="22">22 months</option>
                                        <option value="23">23 months</option>
                                        <option value="24">24 months</option>
                                    </select>
                                </div>
                            </div>
                            <br>

                            <h2 class="title-h2">Credit Overview</h2>
                            <hr>
                            <div class="row">
                                <div class="col-lg-6">
                                    <p>Credit Score: <input class="{{ $errors->has('fsb_score') ? ' is-invalid' : '' }} col-lg-6" style="border: none;width: fit-content;" type="text" name="fsb_score" id="fsb_score" required value="{{ old('fsb_score') }}" placeholder="" readonly></p>
                                    <p>Credit Rating: <input class="{{ $errors->has('fsb_rating') ? ' is-invalid' : '' }} col-lg-6" style="border: none;width: fit-content;" type="text" name="fsb_rating" id="fsb_rating" required value="{{ old('fsb_rating') }}" placeholder="" readonly></p>
                                </div>

                                <div class="col-lg-6">
                                    <p>Credit Status: <input class="{{ $errors->has('fsb_status') ? ' is-invalid' : '' }} col-lg-6" style="border: none;width: fit-content;" type="text" name="fsb_status" id="fsb_status" required value="{{ old('fsb_status') }}" placeholder="" readonly></p>
                                </div>
                            </div>
                            <br>

                            <h2 class="title-h2">Loan Overview</h2>
                            <hr>
                            <div class="row">
                                <div class="col-lg-6">
                                    <p>Monthly Repayment : {{$locale->currency_code.'('.$locale->symbol.')'}}<input class="{{ $errors->has('monthly') ? ' is-invalid' : '' }} col-lg-6" style="border: none;width: fit-content;" type="text" name="monthly" id="monthly" required value="{{ old('monthly') }}" placeholder="0" readonly></p>
                                    <p>Interest Rate :          <input class="{{ $errors->has('interestRate') ? ' is-invalid' : '' }} col-lg-1" style="border: none;width: 100%;" type="text" name="interestRate" id="interestRate" required value="{{ old('interestRate') }}" placeholder="0" readonly>%</p>
                                    <p>Payment Period Rate :   <input class="{{ $errors->has('tenure') ? ' is-invalid' : '' }} col-lg-1" style="border: none;width: 100%;" type="text" name="tenure" id="tenure" required value="{{ old('tenure') }}" placeholder="0" readonly>Months</p>
                                </div>

                                <div class="col-lg-6">
                                    <p>Amount Disbursed : {{$locale->currency_code.'('.$locale->symbol.')'}}<input class="{{ $errors->has('disbursed') ? ' is-invalid' : '' }} col-lg-6" style="border: none;width: fit-content;" type="text" name="disbursed" id="disbursed" required value="{{ old('disbursed') }}" placeholder="0" readonly></p>
                                    <p>Application Fee :  {{$locale->currency_code.'('.$locale->symbol.')'}}<input class="{{ $errors->has('appFee') ? ' is-invalid' : '' }} col-lg-6" style="border: none;width: fit-content;" type="text" name="appFee" id="appFee" required value="{{ old('appFee') }}" placeholder="0" readonly></p>
                                    <p>Total Charges :    {{$locale->currency_code.'('.$locale->symbol.')'}}<input class="{{ $errors->has('charges') ? ' is-invalid' : '' }} col-lg-6" style="border: none;width: fit-content;" type="text" name="charges" id="charges" required value="{{ old('charges') }}" placeholder="0" readonly></p>
                                </div>

                            </div>

                            {!! Form::button('Submit Loan Request', array('class' => 'btn btn-primary btn-send margin-bottom-1 mb-1 float-right','type' => 'submit' )) !!}
                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('footer_scripts')

    <script src="{{ asset('js/select2.min.js')}}"></script>

    <script type="text/javascript">
        $("#client_id").select2({
            placeholder: 'Please select a client',
            allowClear:true,
        }).on('change',function(){
            var credit = $(this).children('option:selected').data('credit');
            var fsb_score = $(this).children('option:selected').data('fsb_score');
            var fsb_rating = $(this).children('option:selected').data('fsb_rating');
            var fsb_status = $(this).children('option:selected').data('fsb_status');
            var user_id = $(this).children('option:selected').data('user_id');
            $('#cred_limit').val(credit);
            $('#fsb_score').val(fsb_score);
            $('#fsb_rating').val(fsb_rating);
            $('#fsb_status').val(fsb_status);
            $('#user_id').val(user_id);
        });

        $("#paybackPeriod").select2({
            placeholder: 'Please select a Payback period.',
            allowClear:true,
        });

        $("#prod_descrip").select2({
            placeholder: 'Please select a product.',
            allowClear:true,
        }).on('change',function(){
            var amount = $(this).children('option:selected').data('price');
            $('#amount').val(amount);
        });
    </script>

    <script type="text/javascript">
        function validateCreditLimit() {
            var a1 = document.getElementById('amount').value;
            var a2 = document.getElementById('cashamount').value;
            var amount = (+a1) + (+a2);
            var creditLimit= document.getElementById("cred_limit").value;

            if(amount>creditLimit) {
                alert("Please do not exceed client maximum credit limit of ZWL$"+creditLimit);
                document.getElementById("amount").value=0;
            }
        }

        function calculate() {
            var bankCharge = 1;
            var a1 = document.getElementById('amount').value;
            var a2 = document.getElementById('cashamount').value;
            var amount = (+a1) + (+a2);

            var interest_rate = {!! getSelfInterestRate() !!};

            if (bankCharge=="") {
                alert("Please select Microfinance");
                return;
            }
            var paybackPeriod = document.getElementById('paybackPeriod').value;
            var interest = (amount * (interest_rate * .01))/paybackPeriod;
            var payment =  -1*pmt(interest_rate/100,paybackPeriod,amount,0,0);
            //payment = payment.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
            var total_charges = 0.16*amount;
            var amountDibursed = 0.84*amount;
            localStorage.setItem("MonthlyRepayment", parseFloat(payment).toFixed(2));
            localStorage.setItem("Intrest", interest_rate);
            localStorage.setItem("Months", paybackPeriod);
            localStorage.setItem("amountDibursed", parseFloat(amountDibursed).toFixed(2));
            localStorage.setItem("total_charges", parseFloat(total_charges).toFixed(2));
            localStorage.setItem("amount", parseFloat(amount).toFixed(2));

            display(amountDibursed,interest_rate,paybackPeriod,payment,total_charges);

        }
        function pmt(rate_per_period, number_of_payments, present_value, future_value, type){
            future_value = typeof future_value !== 'undefined' ? future_value : 0;
            type = typeof type !== 'undefined' ? type : 0;

            if(rate_per_period != 0.0){
                // Interest rate exists
                var q = Math.pow(1 + rate_per_period, number_of_payments);
                return -(rate_per_period * (future_value + (q * present_value))) / ((-1 + q) * (1 + rate_per_period * (type)));

            } else if(number_of_payments != 0.0){
                // No interest rate, but number of payments exists
                return -(future_value + present_value) / number_of_payments;
            }

            return 0;
        }
        function display(amountDibursed,interest_rate,paybackPeriod,payment,total_charges){

            document.getElementById("monthly").value=parseFloat(payment).toFixed(2);
            document.getElementById("interestRate").value=interest_rate;
            document.getElementById("paybackPeriod").value=paybackPeriod;
            document.getElementById("disbursed").value=parseFloat(amountDibursed).toFixed(2);
            document.getElementById("charges").value=parseFloat(total_charges).toFixed(2);
            document.getElementById("appFee").value=parseFloat(localStorage.getItem("amount")*0.065).toFixed(2);
        }

        function checkCreditLimit(){
            var a1 = document.getElementById('amount').value;
            var a2 = document.getElementById('cashamount').value;
            var amount = (+a1) + (+a2);
            if (parseFloat(amount)> parseFloat(localStorage.getItem('creditLimit'))) {
                document.getElementById("btn").disabled = true;
                document.getElementById('amount').style.borderColor = "red";
            }
            else{
                document.getElementById("btn").disabled = false;
                document.getElementById('amount').style.borderColor = "green";
            }
        }
    </script>

@endsection
