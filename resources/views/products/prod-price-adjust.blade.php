<?php
/**
 * Created by PhpStorm for eshagi
 * User: vinceg
 * Date: 9/5/2021
 * Time: 13:19
 */
?>
@extends('layouts.app')

@section('template_title')
    Product Price Settings
@endsection

@section('content')
    <div class="page-title-box">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h4 class="page-title mb-1">Product Price Settings</h4>
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Product Price Settings</a></li>
                        <li class="breadcrumb-item active">Product Exchange Rate</li>
                    </ol>
                </div>

                <div class="col-md-4">
                    <div class="float-right d-none d-md-block">

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
                            <div class="card-body">
                                <h4 class="header-title">Configure my eShagi System Product Prices </h4>
                                <p class="card-title-desc">By clicking update, eShagi will update all your product prices, based on the USD Exchange Rate set here.</p>
                                <p class="card-title-desc text-danger"><strong>Please note that your products have to be priced in USD to use this feature.</strong></p>

                                {!! Form::open(array('route' => ['adjustin.prices'], 'method' => 'POST', 'role' => 'form', 'class' => 'needs-validation', 'novalidate')) !!}

                                {!! csrf_field() !!}

                                <div class="row">
                                    <div class="col-xl-12">
                                        <div class="card">
                                            <div class="card-body">

                                                <div class="p-3">
                                                    <div id="rates-justify" role="tabpanel">
                                                        <div class="row">
                                                            <div class="col-sm-6">
                                                                <label for="interest">Current USD Rate*</label>
                                                                <input type="text" class="form-control" id="usd_rate" name="usd_rate" placeholder="e.g. 125.00" value="{{$rate->usd_rate ?? ''}}" onKeyUp="calculateRate()" pattern="^\d{1,3}*(\.\d+)?$" data-type="currency" required>
                                                                @if ($errors->has('usd_rate'))
                                                                    <span class="help-block text-danger">
                                                                        <strong>{{ $errors->first('usd_rate') }}</strong>
                                                                    </span>
                                                                @endif
                                                            </div>
                                                            <div class="col-sm-6">
                                                                <label for="interest">Current Rand Rate</label>
                                                                <input type="text" class="form-control" id="rand_rate" name="rand_rate" placeholder="e.g. 16.00" value="{{$rate->rand_rate ?? ''}}" pattern="^\d{1,3}*(\.\d+)?$" data-type="currency">
                                                                @if ($errors->has('rand_rate'))
                                                                    <span class="help-block text-danger">
                                                                        <strong>{{ $errors->first('rand_rate') }}</strong>
                                                                    </span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                        <br>

                                                        <div class="row">
                                                            <div class="col-sm-6">
                                                                <label for="interest">RTGS Rate</label>
                                                                <input type="text" class="form-control" id="rtgs_rate" name="rtgs_rate" placeholder="e.g. 3.1" value="{{$rate->rtgs_rate ?? ''}}" pattern="^\d{1,3}*(\.\d+)?$" data-type="currency" >
                                                                @if ($errors->has('rtgs_rate'))
                                                                    <span class="help-block text-danger">
                                                                        <strong>{{ $errors->first('rtgs_rate') }}</strong>
                                                                    </span>
                                                                @endif
                                                            </div>
                                                            <div class="col-sm-6">

                                                            </div>
                                                        </div>
                                                        <br>
                                                        <br>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-sm-6">
                                        <label for="interest">Rate Calculator (100USD is equivalent to the following RTGS:)</label>
                                        <input type="text" class="form-control" id="amount" name="amount" placeholder="e.g. 3.1" >
                                    </div>
                                    <div class="col-sm-6">

                                    </div>
                                </div>

                                {!! Form::button('Update Prices', array('class' => 'btn btn-primary float-right','type' => 'submit' )) !!}
                                {!! Form::close() !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('footer_scripts')
    <script>

        function calculateRate() {
            var amount = document.getElementById('usd_rate').value;

            var amt = (amount * 100);

            localStorage.setItem("amount", parseFloat(amt).toFixed(2));

            display(amt);
        }

        function display(amount){
            document.getElementById("amount").value=parseFloat(amount).toFixed(2);
        }

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
