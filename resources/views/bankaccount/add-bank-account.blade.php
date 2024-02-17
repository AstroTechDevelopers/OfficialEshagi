<?php
/**
 * Created by PhpStorm for eshagitwo
 * User: VinceGee
 * Date: 11/1/2021
 * Time: 9:28 AM
 */ ?>
@extends('layouts.app')

@section('template_title')
    Add Bank Account
@endsection

@section('template_linked_css')
    <link href="{{ asset('css/select2.min.css')}}" rel="stylesheet" />
@endsection

@section('content')
    <div class="page-title-box">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h4 class="page-title mb-1">Bank Accounts</h4>
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{url('/bank-accounts')}}">Bank Accounts</a></li>
                        <li class="breadcrumb-item active">Add Bank Account</li>
                    </ol>
                </div>

                <div class="col-md-4">
                    <div class="float-right d-none d-md-block">
                        <div>
                            <a class="btn btn-light btn-rounded" href="{{url('/bank-accounts')}}" type="button">
                                <i class="mdi mdi-keyboard-backspace mr-1"></i>Back to Bank Accounts
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
                            {!! Form::open(array('route' => 'bank-accounts.store', 'method' => 'POST', 'role' => 'form', 'class' => 'needs-validation')) !!}

                            {!! csrf_field() !!}

                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group has-feedback row {{ $errors->has('bank') ? ' has-error ' : '' }}">
                                        {!! Form::label('bank', 'Bank', array('class' => 'col-md-12 control-label')); !!}
                                        <div class="col-md-12">
                                            <select class="custom-select form-control dynamic" name="bank" id="bank" required>
                                                <option value="">Select Bank Account</option>
                                                @if ($banks)
                                                    @foreach($banks as $bank)
                                                        <option value="{{ $bank->id }}" >{{ $bank->bank }} </option>
                                                    @endforeach
                                                @endif
                                            </select>
                                            @if ($errors->has('bank'))
                                                <span class="help-block">
                                            <strong>{{ $errors->first('bank') }}</strong>
                                        </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group has-feedback row {{ $errors->has('branch') ? ' has-error ' : '' }}">
                                        {!! Form::label('branch', 'Branch', array('class' => 'col-md-12 control-label')); !!}
                                        <div class="col-md-12">
                                            <select class="custom-select form-control dynamic" name="branch" id="branch" required>
                                                <option value="">Select Branch name</option>
                                            </select>
                                            @if ($errors->has('branch'))
                                                <span class="help-block">
                                            <strong>{{ $errors->first('branch') }}</strong>
                                        </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group has-feedback row {{ $errors->has('account_number') ? ' has-error ' : '' }}">
                                        {!! Form::label('account_number', 'Account Number', array('class' => 'col-md-12 control-label')); !!}
                                        <div class="col-md-12">
                                            <div class="input-group">
                                                {!! Form::text('account_number', NULL, array('id' => 'account_number', 'class' => 'form-control','pattern'=>'^\d{1,3}*(\.\d+)?$', 'data-type'=>"currency", 'placeholder' => 'e.g. 29873476487598', 'required')) !!}
                                            </div>
                                            @if ($errors->has('account_number'))
                                                <span class="help-block">
                                            <strong>{{ $errors->first('account_number') }}</strong>
                                        </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group has-feedback row {{ $errors->has('balance') ? ' has-error ' : '' }}">
                                        {!! Form::label('balance', 'Balance', array('class' => 'col-md-12 control-label')); !!}
                                        <div class="col-md-12">
                                            <div class="input-group">
                                                {!! Form::text('balance', NULL, array('id' => 'balance', 'class' => 'form-control','pattern'=>'^\d{1,3}*(\.\d+)?$', 'value'=>"", 'data-type'=>"money", 'placeholder' => 'e.g. 1233.50', 'required')) !!}
                                            </div>
                                            @if ($errors->has('balance'))
                                                <span class="help-block">
                                            <strong>{{ $errors->first('balance') }}</strong>
                                        </span>
                                            @endif
                                        </div>
                                    </div>

                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group has-feedback row {{ $errors->has('name') ? ' has-error ' : '' }}">
                                        {!! Form::label('name', 'Account Name', array('class' => 'col-md-12 control-label')); !!}
                                        <div class="col-md-12">
                                            <div class="input-group">
                                                {!! Form::text('name', NULL, array('id' => 'name', 'class' => 'form-control', 'placeholder' => 'e.g. Astro Corporate Account', 'required')) !!}
                                            </div>
                                            @if ($errors->has('name'))
                                                <span class="help-block">
                                            <strong>{{ $errors->first('name') }}</strong>
                                        </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-6">

                                </div>
                            </div>

                            {!! Form::button('Add Bank Account', array('class' => 'btn btn-success margin-bottom-1 mb-1 float-right','type' => 'submit' )) !!}
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
        $("#branch").select2({
            placeholder: 'Please select bank branch name',
            allowClear:true,
        });

        $('#bank').select2({
            placeholder: 'Please select a bank',
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
                                $("#branch").append('<option value="">Please select your bank branch name</option>').append('<option value="'+value.branch+'">'+value.branch+'</option>');
                            });
                        }
                    }

                });
            }
        });

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

    <script>

        $("input[data-type='money']").on({
            keyup: function() {
                formatMoney($(this));
            },
            blur: function() {
                formatMoney($(this), "blur");
            }
        });


        function formatMoneyNumber(n) {
            // format number 1000000 to 1,234,567
            return n.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, "")
        }


        function formatMoney(input, blur) {
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
                left_side = formatMoneyNumber(left_side);

                // validate right side
                right_side = formatMoneyNumber(right_side);

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
                input_val = formatMoneyNumber(input_val);
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
