<?php
/**
 * Created by PhpStorm for eshagitwo
 * User: VinceGee
 * Date: 8/11/2022
 * Time: 4:24 AM
 */ ?>
@extends('layouts.app')

@section('template_title')
    Record Zambia Payment
@endsection

@section('template_linked_css')
    <link href="{{ asset('css/select2.min.css')}}" rel="stylesheet" />
@endsection

@section('content')
    <div class="page-title-box">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h4 class="page-title mb-1">Zambia Payments</h4>
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{url('/zambia-payments')}}">Zambia Payments</a></li>
                        <li class="breadcrumb-item active">Record Zambia Payment</li>
                    </ol>
                </div>

                <div class="col-md-4">
                    <div class="float-right d-none d-md-block">
                        <div>
                            <a class="btn btn-light btn-rounded" href="{{url('/zambia-payments')}}" type="button">
                                <i class="mdi mdi-keyboard-backspace mr-1"></i>Back to Payments
                            </a>
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
                            {!! Form::open(array('route' => 'zambia-payments.store', 'method' => 'POST', 'role' => 'form', 'class' => 'needs-validation')) !!}

                            {!! csrf_field() !!}

                            <div class="form-group has-feedback row {{ $errors->has('loan_id') ? ' has-error ' : '' }}">
                                {!! Form::label('loan_id', 'Loan *', array('class' => 'col-md-3 control-label')); !!}
                                <div class="col-md-9">
                                    <div class="input-group">
                                        <select class="form-control" id="loan_id" name="loan_id" required>
                                            <option value="">Select Loan </option>
                                            @if ($loans)
                                                @foreach($loans as $loan)
                                                    <option value="{{$loan->id}}" >{{ $loan->id }} - {{ $loan->first_name.' '.$loan->last_name }} (${{$loan->loan_principal_amount}}) </option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                    @if ($errors->has('loan_id'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('loan_id') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group has-feedback row {{ $errors->has('amount') ? ' has-error ' : '' }}">
                                {!! Form::label('amount', 'Amount *', array('class' => 'col-md-3 control-label')); !!}
                                <div class="col-md-9">
                                    <div class="input-group">
                                        {!! Form::text('amount', NULL, array('id' => 'amount', 'class' => 'form-control', 'pattern'=>"^\d{1,3}*(\.\d+)?$", 'data-type'=>"currency", 'placeholder' => 'e.g. 24', 'required')) !!}
                                    </div>
                                    @if ($errors->has('amount'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('amount') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group has-feedback row {{ $errors->has('payment_method') ? ' has-error ' : '' }}">
                                {!! Form::label('payment_method', 'Repayment Method *', array('class' => 'col-md-3 control-label')); !!}
                                <div class="col-md-9">
                                    <div class="input-group">
                                        <select class="form-control" id="payment_method" name="payment_method" required>
                                            <option value="">Select Repayment Method </option>
                                            <option value="114841">Cash</option>
                                            <option value="114842">ATM</option>
                                            <option value="114843">Cheque</option>
                                            <option value="114844">Paypal</option>
                                            <option value="114845">Online Transfer</option>
                                            <option value="123095">E-Wallet</option>
                                            <option value="123096">Payroll Deduction-EFT</option>
                                        </select>
                                    </div>
                                    @if ($errors->has('payment_method'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('payment_method') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group has-feedback row {{ $errors->has('collection_date') ? ' has-error ' : '' }}">
                                {!! Form::label('collection_date', 'Collection Date *', array('class' => 'col-md-3 control-label')); !!}
                                <div class="col-md-9">
                                    <div class="input-group">
                                        {!! Form::text('collection_date', NULL, array('id' => 'collection_date', 'class' => 'form-control datepicker-here', 'data-language'=>"en" ,'data-date-format'=>"dd-mm-yyyy", 'placeholder' => 'e.g. 31-01-2022', 'required','autocomplete'=>"off")) !!}

                                    </div>
                                    @if ($errors->has('collection_date'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('collection_date') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group has-feedback row {{ $errors->has('collector_id') ? ' has-error ' : '' }}">
                                {!! Form::label('collector_id', 'Collected By *', array('class' => 'col-md-3 control-label')); !!}
                                <div class="col-md-9">
                                    <div class="input-group">
                                        <select class="form-control" id="collector_id" name="collector_id" required>
                                            <option value="40908">Astro Access A/C</option>
                                            <option value="40909">Astro Stanbic A/C</option>
                                            <option value="38054">Leonard Njobvu</option>
                                            <option value="40694">Leya Chifumbano</option>
                                            <option value="40907">Mobile Money</option>
                                            <option value="37883">tendai Shambare</option>
                                        </select>
                                    </div>
                                    @if ($errors->has('collector_id'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('collector_id') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group has-feedback row {{ $errors->has('rem_schedule') ? ' has-error ' : '' }}">
                                {!! Form::label('rem_schedule', 'Adjust Remaining Schedule *', array('class' => 'col-md-3 control-label')); !!}
                                <div class="col-md-9">
                                    <div class="input-group">
                                        {!! Form::text('rem_schedule', '1', array('id' => 'rem_schedule', 'class' => 'form-control', 'placeholder' => 'e.g. 1', 'required','autocomplete'=>"off")) !!}

                                    </div>
                                    @if ($errors->has('rem_schedule'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('rem_schedule') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group has-feedback row {{ $errors->has('description') ? ' has-error ' : '' }}">
                                {!! Form::label('description', 'Description', array('class' => 'col-md-3 control-label')); !!}
                                <div class="col-md-9">
                                    <div class="input-group">
                                        {!! Form::text('description', NULL , array('id' => 'description', 'class' => 'form-control', 'placeholder' => 'e.g. Payment made at exchange rate of 400','autocomplete'=>"off")) !!}

                                    </div>
                                    @if ($errors->has('description'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('description') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group has-feedback row {{ $errors->has('reference_num') ? ' has-error ' : '' }}">
                                {!! Form::label('reference_num', 'Payment Reference Number *', array('class' => 'col-md-3 control-label')); !!}
                                <div class="col-md-9">
                                    <div class="input-group">
                                        {!! Form::text('reference_num', NULL , array('id' => 'reference_num', 'class' => 'form-control', 'placeholder' => 'e.g. Receipt Number or Bank Transaction ID','required','autocomplete'=>"off")) !!}

                                    </div>
                                    @if ($errors->has('reference_num'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('reference_num') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>


                            {!! Form::button('Record Payment', array('class' => 'btn btn-success margin-bottom-1 mb-1 float-right','type' => 'submit' )) !!}
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
        $("#loan_id").select2({
            placeholder: 'Please select a Loan.',
            allowClear:true,
        });

        $("#collector_id").select2({
            placeholder: 'Please select a collection method.',
            allowClear:true,
        });

        $("#payment_method").select2({
            placeholder: 'Please select a repayment method.',
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
