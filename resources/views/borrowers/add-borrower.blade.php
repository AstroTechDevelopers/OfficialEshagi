<?php
/**
 * Created by PhpStorm for eshagitwo
 * User: VinceGee
 * Date: 10/6/2021
 * Time: 4:51 AM
 */ ?>
@extends('layouts.app')

@section('template_title')
    Add Borrower
@endsection

@section('template_linked_css')
    <link href="{{ asset('css/select2.min.css')}}" rel="stylesheet" />
@endsection

@section('content')
    <div class="page-title-box">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h4 class="page-title mb-1">Borrowers</h4>
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{url('/borrowers')}}">Borrowers</a></li>
                        <li class="breadcrumb-item active">Add a Borrower</li>
                    </ol>
                </div>

                <div class="col-md-4">
                    <div class="float-right d-none d-md-block">
                        <div>
                            <a class="btn btn-light btn-rounded" href="{{url('/borrowers')}}" type="button">
                                <i class="mdi mdi-keyboard-backspace mr-1"></i>Back to Borrowers
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
                            {!! Form::open(array('route' => 'borrowers.store', 'method' => 'POST', 'role' => 'form', 'class' => 'needs-validation')) !!}

                            {!! csrf_field() !!}

                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group has-feedback row {{ $errors->has('natid') ? ' has-error ' : '' }}">
                                        {!! Form::label('natid', 'Borrower', array('class' => 'col-md-12 control-label')); !!}
                                        <div class="col-md-12">
                                            <div class="input-group">
                                                <select class="custom-select form-control dynamic" name="natid" id="natid" required>
                                                    <option value="">Select Client</option>
                                                    @if ($clients)
                                                        @foreach($clients as $client)
                                                            <option value="{{ $client->natid }}" >{{ $client->first_name.' '. $client->last_name }} </option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                            </div>
                                            @if ($errors->has('natid'))
                                                <span class="help-block">
                                            <strong>{{ $errors->first('natid') }}</strong>
                                        </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group has-feedback row {{ $errors->has('currency') ? ' has-error ' : '' }}">
                                        {!! Form::label('currency', 'Currency', array('class' => 'col-md-12 control-label')); !!}
                                        <div class="col-md-12">
                                            <div class="input-group">
                                                {!! Form::text('currency', NULL, array('id' => 'currency', 'class' => 'form-control', 'placeholder' => 'e.g. ZWL', 'required')) !!}
                                            </div>
                                            @if ($errors->has('currency'))
                                                <span class="help-block">
                                            <strong>{{ $errors->first('currency') }}</strong>
                                        </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group has-feedback row {{ $errors->has('amount') ? ' has-error ' : '' }}">
                                        {!! Form::label('amount', 'Amount', array('class' => 'col-md-12 control-label')); !!}
                                        <div class="col-md-12">
                                            <div class="input-group">
                                                {!! Form::text('amount', NULL, array('id' => 'amount', 'class' => 'form-control', 'pattern'=>"^\d{1,3}*(\.\d+)?$", 'data-type'=>"currency", 'placeholder' => 'e.g. 90000.00', 'required')) !!}
                                            </div>
                                            @if ($errors->has('amount'))
                                                <span class="help-block">
                                            <strong>{{ $errors->first('amount') }}</strong>
                                        </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group has-feedback row {{ $errors->has('reason') ? ' has-error ' : '' }}">
                                        {!! Form::label('reason', 'Reason', array('class' => 'col-md-12 control-label')); !!}
                                        <div class="col-md-12">
                                            <div class="input-group">
                                                <select class="custom-select form-control dynamic" name="reason" id="reason" required>
                                                    <option value="">Select a Loan</option>
                                                    @if ($loans)
                                                        @foreach($loans as $loan)
                                                            <option value="{{ $loan->id }}" >{{ $loan->first_name.' '. $loan->last_name }} </option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                            </div>
                                            @if ($errors->has('reason'))
                                                <span class="help-block">
                                            <strong>{{ $errors->first('reason') }}</strong>
                                        </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {!! Form::button('Add Borrower', array('class' => 'btn btn-success margin-bottom-1 mb-1 float-right','type' => 'submit' )) !!}
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
        $("#natid").select2({
            placeholder: 'Please select client.',
            allowClear:true,
        });

        $("#reason").select2({
            placeholder: 'Please select a loan.',
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
            return n.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, "")
        }


        function formatCurrency(input, blur) {

            var input_val = input.val();

            if (input_val === "") { return; }

            var original_len = input_val.length;

            var caret_pos = input.prop("selectionStart");

            if (input_val.indexOf(".") >= 0) {

                var decimal_pos = input_val.indexOf(".");

                var left_side = input_val.substring(0, decimal_pos);
                var right_side = input_val.substring(decimal_pos);

                left_side = formatNumber(left_side);

                right_side = formatNumber(right_side);

                if (blur === "blur") {
                    right_side += "00";
                }

                right_side = right_side.substring(0, 2);

                input_val = left_side + "." + right_side;

            } else {
                input_val = formatNumber(input_val);

                if (blur === "blur") {
                    input_val += ".00";
                }
            }

            input.val(input_val);

            var updated_len = input_val.length;
            caret_pos = updated_len - original_len + caret_pos;
            input[0].setSelectionRange(caret_pos, caret_pos);
        }
    </script>

@endsection
