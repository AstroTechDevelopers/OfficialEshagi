<?php
/**
 *Created by PhpStorm for eshagi
 *User: Vincent Guyo
 *Date: 10/19/2020
 *Time: 9:41 AM
 */

?>
@extends('layouts.app')

@section('template_title')
    Update Repayment
@endsection

@section('template_linked_css')
    <link href="{{ asset('css/select2.min.css')}}" rel="stylesheet" />
@endsection

@section('content')
    <div class="page-title-box">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h4 class="page-title mb-1">Repayments</h4>
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{url('/repayments')}}">Repayments</a></li>
                        <li class="breadcrumb-item active">Modify Repayment</li>
                    </ol>
                </div>

                <div class="col-md-4">
                    <div class="float-right d-none d-md-block">
                        <div>
                            <a class="btn btn-light btn-rounded" href="{{url('/repayments')}}" type="button">
                                <i class="mdi mdi-keyboard-backspace mr-1"></i>Back to Repayments
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
                            {!! Form::open(array('route' => ['repayments.update', $repayment->id], 'method' => 'PUT', 'role' => 'form', 'class' => 'needs-validation')) !!}

                            {!! csrf_field() !!}

                            <div class="form-group has-feedback row {{ $errors->has('paymt_number') ? ' has-error ' : '' }}">
                                {!! Form::label('paymt_number', 'Payment Number', array('class' => 'col-md-3 control-label')); !!}
                                <div class="col-md-9">
                                    <div class="input-group">
                                        {!! Form::text('paymt_number', $repayment->paymt_number, array('id' => 'paymt_number', 'class' => 'form-control', 'placeholder' => 'e.g. 1', 'required')) !!}
                                    </div>
                                    @if ($errors->has('paymt_number'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('paymt_number') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group has-feedback row {{ $errors->has('loanid') ? ' has-error ' : '' }}">
                                {!! Form::label('loanid', 'Loan ID', array('class' => 'col-md-3 control-label')); !!}
                                <div class="col-md-9">
                                    <div class="input-group">
                                        {!! Form::text('loanid', $repayment->loanid, array('id' => 'loanid', 'class' => 'form-control', 'placeholder' => 'e.g. 64', 'required', 'readonly')) !!}
                                    </div>
                                    @if ($errors->has('loanid'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('loanid') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group has-feedback row {{ $errors->has('client_id') ? ' has-error ' : '' }}">
                                {!! Form::label('client_id', 'Client ID', array('class' => 'col-md-3 control-label')); !!}
                                <div class="col-md-9">
                                    <div class="input-group">
                                        {!! Form::text('client_id', $repayment->client_id, array('id' => 'client_id', 'class' => 'form-control', 'placeholder' => 'e.g. 64', 'required', 'readonly')) !!}
                                    </div>
                                    @if ($errors->has('client_id'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('client_id') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group has-feedback row {{ $errors->has('reds_number') ? ' has-error ' : '' }}">
                                {!! Form::label('reds_number', 'RedSphere Number', array('class' => 'col-md-3 control-label')); !!}
                                <div class="col-md-9">
                                    <div class="input-group">
                                        {!! Form::text('reds_number', $repayment->reds_number, array('id' => 'reds_number', 'class' => 'form-control', 'placeholder' => 'e.g. 212/00011454/RSF', 'readonly')) !!}
                                    </div>
                                    @if ($errors->has('reds_number'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('reds_number') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group has-feedback row {{ $errors->has('payment') ? ' has-error ' : '' }}">
                                {!! Form::label('payment', 'Payment Of', array('class' => 'col-md-3 control-label')); !!}
                                <div class="col-md-9">
                                    <div class="input-group">
                                        {!! Form::text('payment', $repayment->payment, array('id' => 'payment', 'class' => 'form-control','pattern'=>'^\d{1,3}*(\.\d+)?$', 'value'=>"", 'data-type'=>"currency",'placeholder' => 'e.g. 2512.23', 'readonly')) !!}
                                    </div>
                                    @if ($errors->has('payment'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('payment') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div><div class="form-group has-feedback row {{ $errors->has('principal') ? ' has-error ' : '' }}">
                                {!! Form::label('principal', 'Principal', array('class' => 'col-md-3 control-label')); !!}
                                <div class="col-md-9">
                                    <div class="input-group">
                                        {!! Form::text('principal', $repayment->principal, array('id' => 'principal', 'class' => 'form-control','pattern'=>'^\d{1,3}*(\.\d+)?$', 'value'=>"", 'data-type'=>"currency", 'placeholder' => 'e.g. 212.45', 'readonly')) !!}
                                    </div>
                                    @if ($errors->has('principal'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('principal') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div><div class="form-group has-feedback row {{ $errors->has('interest') ? ' has-error ' : '' }}">
                                {!! Form::label('interest', 'Interest', array('class' => 'col-md-3 control-label')); !!}
                                <div class="col-md-9">
                                    <div class="input-group">
                                        {!! Form::text('interest', $repayment->interest, array('id' => 'interest', 'class' => 'form-control','pattern'=>'^\d{1,3}*(\.\d+)?$', 'value'=>"", 'data-type'=>"currency", 'placeholder' => 'e.g. 73.12', 'readonly')) !!}
                                    </div>
                                    @if ($errors->has('interest'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('interest') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div><div class="form-group has-feedback row {{ $errors->has('balance') ? ' has-error ' : '' }}">
                                {!! Form::label('balance', 'Balance', array('class' => 'col-md-3 control-label')); !!}
                                <div class="col-md-9">
                                    <div class="input-group">
                                        {!! Form::text('balance', $repayment->balance, array('id' => 'balance', 'class' => 'form-control','pattern'=>'^\d{1,3}*(\.\d+)?$', 'value'=>"", 'data-type'=>"currency", 'placeholder' => 'e.g. 954.16', 'readonly')) !!}
                                    </div>
                                    @if ($errors->has('balance'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('balance') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            {!! Form::button('Update Repayment', array('class' => 'btn btn-success margin-bottom-1 mb-1 float-right','type' => 'submit' )) !!}
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
        $("#loanid").select2({
            placeholder: 'Please select a loan to make a payment',
            allowClear:true,
        }).on('change',function(){
            var price = $(this).children('option:selected').data('price');
            var price1 = $(this).children('option:selected').data('tager');
            $('#client_id').val(price);
            $('#reds_number').val(price1);
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
