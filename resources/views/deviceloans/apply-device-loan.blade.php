<?php
/**
 * Created by PhpStorm for eshagitwo
 * User: VinceGee
 * Date: 4/30/2022
 * Time: 9:38 AM
 */ ?>
@extends('layouts.app')

@section('template_title')
    Apply For A Device Loan
@endsection

@section('template_linked_css')

    <link href="{{ asset('css/select2.min.css')}}" rel="stylesheet" />
@endsection

@section('content')
    <div class="page-title-box">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-md-4">
                    <h4 class="page-title mb-1">Device Loans</h4>
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{url('/my-device-loans')}}">Device Loan</a></li>
                        <li class="breadcrumb-item active">Apply For eShagi Device Loan</li>
                    </ol>
                </div>

                <div class="col-md-8">
                    <div class="float-right d-none d-md-block">
                        <div>
                            <h1 class="text-white">Credit limit : {{getLocaleInfo()->currency_code.' '.$user->cred_limit}}</h1>
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
                            {!! Form::open(array('route' => 'device-loans.store', 'method' => 'POST', 'id' => 'loanForm','role' => 'form', 'class' => 'needs-validation')) !!}

                            {!! csrf_field() !!}
                            <input type="hidden" name="cred_limit" value="{{$user->cred_limit}}">
                            <h2 class="title-h2">Loan Details</h2>
                            <hr>
                            <div class="row">
                                <div class="col-lg-6">
                                    <label>Device</label>
                                    <select class="form-control" id="device" name="device" required>
                                        <option value="">Select Device </option>
                                        @if ($products)
                                            @foreach($products as $product)
                                                <option value="{{$product->id}}" data-price="{{$product->price}}" >{{ $product->pcode }} - {{ $product->pname }} (${{$product->price}}) </option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>

                                <div class="col-lg-6">
                                    <label>Product Price</label>
                                    <div class="input-group">
                                        <input class="input-group-addon form-control col-lg-2" value="USD" readonly>
                                        <input type="number" step=0.01 class="form-control col-lg-10" name="amount" id="amount" placeholder="Credit item cost" value="{{old('amount')}}">
                                    </div>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-lg-6">
                                    <label>Deposit Paid (in %)</label>
                                    <input type="text" class="form-control col-lg-12" pattern="^\d{1,3}*(\.\d+)?$" data-type="currency" name="deposit_prct" id="deposit_prct" placeholder="e.g. 30" value="{{old('deposit_prct')}}">
                                </div>

                                <div class="col-lg-6">
                                    <label>Repayment Period</label>
                                    <select onchange="calculate();" class="form-control" id="paybackPeriod" name="paybackPeriod">
                                        <option value="">Select repayment period</option>
                                        <option value="3">3 months</option>
                                        <option value="6">6 months</option>
                                    </select>
                                </div>
                            </div>

                            <br>
                            <h2 class="title-h2">Credit Overview</h2>
                            <hr>
                            <div class="row">
                                <div class="col-lg-6">
                                    <p>Name: {{$user->first_name.' '.$user->last_name}}</p>
                                    <p>ID No: {{$user->natid}}</p>
                                </div>

                                <div class="col-lg-6">
                                    <p>Credit Status: {{$user->fsb_status}}</p>
                                    <p>Credit Score: {{$user->fsb_score}}</p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-6">
                                    <p>Credit Limit: ${{$user->cred_limit}}</p>
                                </div>
                            </div>
                            <br>

                            <h2 class="title-h2">Loan Overview</h2>
                            <hr>
                            <div class="row">
                                <div class="col-lg-6">
                                    <p>Monthly Repayment : USD<input class="{{ $errors->has('monthly') ? ' is-invalid' : '' }} col-lg-6" style="border: none;width: fit-content;" type="text" name="monthly" id="monthly" required value="{{ old('monthly') }}" placeholder="0" readonly></p>
                                    <p>Interest Rate :          <input class="{{ $errors->has('interestRate') ? ' is-invalid' : '' }} col-lg-1" style="border: none;width: 100%;" type="text" name="interestRate" id="interestRate" required value="{{ old('interestRate') }}" placeholder="{!! getDeviceInterestRate() !!}" readonly>%</p>
                                    <p>Payment Period Rate :   <input class="{{ $errors->has('tenure') ? ' is-invalid' : '' }} col-lg-1" style="border: none;width: 100%;" type="text" name="tenure" id="tenure" required value="{{ old('tenure') }}" placeholder="0" readonly>Months</p>
                                </div>

                                <div class="col-lg-6">
                                    <p>Amount Disbursed : USD<input class="{{ $errors->has('disbursed') ? ' is-invalid' : '' }} col-lg-6" style="border: none;width: fit-content;" type="text" name="disbursed" id="disbursed" required value="{{ old('disbursed') }}" placeholder="0" readonly></p>
                                    <p>Application Fee :  USD<input class="{{ $errors->has('appFee') ? ' is-invalid' : '' }} col-lg-6" style="border: none;width: fit-content;" type="text" name="appFee" id="appFee" required value="{{ old('appFee') }}" placeholder="0" readonly></p>
                                    <p>Disbursement Fee: USD<input class="{{ $errors->has('disbursefee') ? ' is-invalid' : '' }} col-lg-6" style="border: none;width: fit-content;" type="text" name="disbursefee" id="disbursefee" required value="{{ old('disbursefee') }}" placeholder="0" readonly></p>
                                </div>

                            </div>

                            <div class="row">
                                <div class="col-lg-6">
                                </div>

                                <div class="col-lg-6">
                                    <p><strong>Total Charges : </strong>    USD<input class="{{ $errors->has('charges') ? ' is-invalid' : '' }} col-lg-6" style="border: none;width: fit-content;" type="text" name="charges" id="charges" required value="{{ old('charges') }}" placeholder="0" readonly></p>
                                </div>

                            </div>

                            {!! Form::button('Next : Loan Agreement', array('class' => 'btn btn-primary btn-send margin-bottom-1 mb-1 float-right','type' => 'submit' )) !!}
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
        $("#paybackPeriod").select2({
            placeholder: 'Please select a Payback period.',
            allowClear:true,
        });

        $("#partner_id").select2({
            placeholder: 'Please select the merchant you are applying with.',
            allowClear:true,
        });

        $("#device").select2({
            placeholder: 'Please select a product.',
            allowClear:true,
        }).on('change',function(){
            var amount = $(this).children('option:selected').data('price');
            $('#amount').val(amount);
        });
    </script>

    <script type="text/javascript">
        function validateCreditLimit() {
            var amount=document.getElementById("amount").value;
            var creditLimit= {!! $user->cred_limit !!};
            if(amount>creditLimit) {
                alert("Please do not exceed your maximum credit limit of ZWL$"+creditLimit);
                document.getElementById("amount").value=0;
            }
        }

    </script>
    @include('deviceloans.calculate-device-loan')

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


@endsection
