<?php
/**
 *Created by PhpStorm for eshagi
 *User: Vincent Guyo
 *Date: 10/22/2020
 *Time: 9:52 AM
 */

?>
@extends('layouts.app')

@section('template_title')
    Add Commission
@endsection

@section('template_linked_css')
    <link href="{{ asset('css/select2.min.css')}}" rel="stylesheet" />
@endsection

@section('content')
    <div class="page-title-box">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h4 class="page-title mb-1">Commissions</h4>
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{url('/commissions')}}">Commissions</a></li>
                        <li class="breadcrumb-item active">Add Commission</li>
                    </ol>
                </div>

                <div class="col-md-4">
                    <div class="float-right d-none d-md-block">
                        <div>
                            <a class="btn btn-light btn-rounded" href="{{url('/commissions')}}" type="button">
                                <i class="mdi mdi-keyboard-backspace mr-1"></i>Back to Commissions
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
                            {!! Form::open(array('route' => 'commissions.store', 'method' => 'POST', 'role' => 'form', 'class' => 'needs-validation')) !!}

                            {!! csrf_field() !!}

                            <div class="form-group has-feedback row {{ $errors->has('agent') ? ' has-error ' : '' }}">
                                {!! Form::label('agent', 'Agent', array('class' => 'col-md-3 control-label')); !!}
                                <div class="col-md-9">
                                    <select class="custom-select form-control dynamic" name="agent" id="agent" required>
                                        <option value="">Select Agent</option>
                                        @if ($agents)
                                            @foreach($agents as $agent)
                                                <option value="{{ $agent->name }}" >{{ $agent->name }} </option>
                                            @endforeach
                                        @endif
                                    </select>
                                    @if ($errors->has('agent'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('agent') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group has-feedback row {{ $errors->has('loanid') ? ' has-error ' : '' }}">
                                {!! Form::label('loanid', 'Loan ID', array('class' => 'col-md-3 control-label')); !!}
                                <div class="col-md-9">
                                    <select class="custom-select form-control dynamic" name="loanid" id="loanid" required>
                                        <option value="">Select Loan</option>
                                        @if ($loans)
                                            @foreach($loans as $loan)
                                                <option value="{{ $loan->id }}" data-price="{{$loan->client_id}}" data-tager="{{$loan->amount}}">{{ $loan->id .' worth $'.$loan->amount.' - '.$loan->created_at}} </option>
                                            @endforeach
                                        @endif
                                    </select>
                                    @if ($errors->has('loanid'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('loanid') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <input type="hidden" name="client" id="client">

                            <div class="form-group has-feedback row {{ $errors->has('loanamt') ? ' has-error ' : '' }}">
                                {!! Form::label('loanamt', 'Loan Amount', array('class' => 'col-md-3 control-label')); !!}
                                <div class="col-md-9">
                                    <div class="input-group">
                                        {!! Form::text('loanamt', NULL, array('id' => 'loanamt', 'class' => 'form-control','pattern'=>'^\d{1,3}*(\.\d+)?$', 'value'=>"", 'data-type'=>"currency", 'placeholder' => 'e.g. 35000.00', 'required')) !!}
                                    </div>
                                    @if ($errors->has('loanamt'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('loanamt') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group has-feedback row {{ $errors->has('commission') ? ' has-error ' : '' }}">
                                {!! Form::label('commission', 'Commission', array('class' => 'col-md-3 control-label')); !!}
                                <div class="col-md-9">
                                    <div class="input-group">
                                        {!! Form::text('commission', NULL, array('id' => 'commission', 'class' => 'form-control','pattern'=>'^\d{1,3}*(\.\d+)?$', 'value'=>"", 'data-type'=>"currency", 'placeholder' => 'e.g. 1050.00', 'required')) !!}
                                    </div>
                                    @if ($errors->has('commission'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('commission') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            {!! Form::button('Add Commission', array('class' => 'btn btn-success margin-bottom-1 mb-1 float-right','type' => 'submit' )) !!}
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
        $("#agent").select2({
            placeholder: 'Please select agent.',
            allowClear:true,
        });
    </script>

    <script type="text/javascript">
        $("#loanid").select2({
            placeholder: 'Please select a loan assign commission to',
            allowClear:true,
        }).on('change',function(){
            var price = $(this).children('option:selected').data('price');
            var price1 = $(this).children('option:selected').data('tager');
            $('#client').val(price);
            $('#loanamt').val(price1);
            $('#commission').val((price1*0.03).toFixed(2));
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
