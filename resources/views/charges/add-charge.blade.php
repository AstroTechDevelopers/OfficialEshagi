<?php
/**
 *Created by PhpStorm for eshagi
 *User: Vincent Guyo
 *Date: 10/16/2020
 *Time: 4:19 AM
 */

?>
@extends('layouts.app')

@section('template_title')
    Add Charge
@endsection

@section('template_linked_css')
    <link href="{{ asset('css/select2.min.css')}}" rel="stylesheet" />
@endsection

@section('content')
    <div class="page-title-box">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h4 class="page-title mb-1">Charges</h4>
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{url('/charges')}}">Charges</a></li>
                        <li class="breadcrumb-item active">Loan Additional Charges (For Mobile)</li>
                    </ol>
                </div>

                <div class="col-md-4">
                    <div class="float-right d-none d-md-block">
                        <div>
                            <a class="btn btn-light btn-rounded" href="{{url('/charges')}}" type="button">
                                <i class="mdi mdi-keyboard-backspace mr-1"></i>Back to Charges
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
                            {!! Form::open(array('route' => 'charges.store', 'method' => 'POST', 'role' => 'form', 'class' => 'needs-validation')) !!}

                            {!! csrf_field() !!}

                            <div class="form-group has-feedback row {{ $errors->has('arrangement') ? ' has-error ' : '' }}">
                                {!! Form::label('arrangement', 'Admin/Arrangement Fee', array('class' => 'col-md-3 control-label')); !!}
                                <div class="col-md-9">
                                    <div class="input-group">
                                        {!! Form::text('arrangement', NULL, array('id' => 'arrangement','pattern'=>"^\d{1,3}*(\.\d+)?$",'data-type'=>"currency",'class' => 'form-control', 'placeholder' => 'e.g. 5', 'required')) !!}
                                    </div>
                                    @if ($errors->has('arrangement'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('arrangement') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group has-feedback row {{ $errors->has('application') ? ' has-error ' : '' }}">
                                {!! Form::label('application', 'Application Fee', array('class' => 'col-md-3 control-label')); !!}
                                <div class="col-md-9">
                                    <div class="input-group">
                                        {!! Form::text('application', NULL, array('id' => 'application','pattern'=>"^\d{1,3}*(\.\d+)?$",'data-type'=>"currency", 'class' => 'form-control', 'placeholder' => 'e.g. 15', 'required')) !!}
                                    </div>
                                    @if ($errors->has('application'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('application') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group has-feedback row {{ $errors->has('insurance') ? ' has-error ' : '' }}">
                                {!! Form::label('insurance', 'Loan Insurance', array('class' => 'col-md-3 control-label')); !!}
                                <div class="col-md-9">
                                    <div class="input-group">
                                        {!! Form::text('insurance', NULL, array('id' => 'insurance', 'pattern'=>"^\d{1,3}*(\.\d+)?$",'data-type'=>"currency",'class' => 'form-control', 'placeholder' => 'e.g. 2', 'required')) !!}
                                    </div>
                                    @if ($errors->has('insurance'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('insurance') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group has-feedback row {{ $errors->has('tax') ? ' has-error ' : '' }}">
                                {!! Form::label('tax', 'Tax', array('class' => 'col-md-3 control-label')); !!}
                                <div class="col-md-9">
                                    <div class="input-group">
                                        {!! Form::text('tax', NULL, array('id' => 'tax','pattern'=>"^\d{1,3}*(\.\d+)?$",'data-type'=>"currency", 'class' => 'form-control', 'placeholder' => 'e.g. 2', 'bank_city')) !!}
                                    </div>
                                    @if ($errors->has('tax'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('tax') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            {!! Form::button('Add Charge', array('class' => 'btn btn-success margin-bottom-1 mb-1 float-right','type' => 'submit' )) !!}
                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('footer_scripts')

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
