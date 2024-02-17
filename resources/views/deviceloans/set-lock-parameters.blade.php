<?php
/**
 * Created by PhpStorm for eshagitwo
 * User: VinceGee
 * Date: 5/25/2022
 * Time: 12:44 PM
 */ ?>

@extends('layouts.app')

@section('template_title')
    Set Lock Parameters
@endsection

@section('content')
    <div class="page-title-box">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h4 class="page-title mb-1">Device Loan Lock Parameters</h4>
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{url('/device-loans')}}">Device Loan</a></li>
                        <li class="breadcrumb-item active">Device Loan ID: {{$loan->id}}</li>
                    </ol>
                </div>

                <div class="col-md-4">
                    <div class="float-right d-none d-md-block">
                        <div>
                            <a class="btn btn-light btn-rounded" href="{{url()->previous()}}" type="button">
                                <i class="mdi mdi-keyboard-backspace mr-1"></i>Back
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
                <div class="col-xl-7">
                    <div class="card">
                        <div class="card-body">
                            <div class="card-header">
                                Lock Parameter Details
                            </div>
                            <br>
                            {!! Form::open(array('route' => 'set.lock.para', 'method' => 'POST', 'id' => 'loanForm','role' => 'form', 'class' => 'needs-validation')) !!}
                            {!! csrf_field() !!}
                            <input type="hidden" name="loan_id" id="loan_id" value="{{$loan->id}}">
                            <div class="form-group has-feedback row {{ $errors->has('currentTerm') ? ' has-error ' : '' }}">
                                {!! Form::label('currentTerm', 'Current Repayment Term', array('class' => 'col-md-3 control-label')); !!}
                                <div class="col-md-9">
                                    <div class="input-group">
                                        {!! Form::number('currentTerm', $loan->currentTerm, array('id' => 'currentTerm', 'class' => 'form-control', 'placeholder' => 'e.g. 2', 'required')) !!}
                                    </div>
                                    @if ($errors->has('currentTerm'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('currentTerm') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group has-feedback row {{ $errors->has('totalTerm') ? ' has-error ' : '' }}">
                                {!! Form::label('totalTerm', 'Total Repayment Terms', array('class' => 'col-md-3 control-label')); !!}
                                <div class="col-md-9">
                                    <div class="input-group">
                                        {!! Form::number('totalTerm', $loan->totalTerm, array('id' => 'totalTerm', 'class' => 'form-control', 'placeholder' => 'e.g. 3', 'required')) !!}
                                    </div>
                                    @if ($errors->has('totalTerm'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('totalTerm') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group has-feedback row {{ $errors->has('description') ? ' has-error ' : '' }}">
                                {!! Form::label('description', 'description', array('class' => 'col-md-3 control-label')); !!}
                                <div class="col-md-9">
                                    <div class="input-group">
                                        {!! Form::text('description', $loan->description, array('id' => 'description', 'class' => 'form-control', 'placeholder' => 'e.g. iTel repay record update', 'required')) !!}
                                    </div>
                                    @if ($errors->has('description'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('description') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group has-feedback row {{ $errors->has('nextRepayAmt') ? ' has-error ' : '' }}">
                                {!! Form::label('nextRepayAmt', 'Next Repayment Amount', array('class' => 'col-md-3 control-label')); !!}
                                <div class="col-md-9">
                                    <div class="input-group">
                                        {!! Form::text('nextRepayAmt', $loan->nextRepayAmt, array('id' => 'nextRepayAmt', 'class' => 'form-control', 'pattern'=>"^\d{1,3}*(\.\d+)?$", 'data-type'=>"currency", 'placeholder' => 'e.g. 25.00', 'required')) !!}
                                    </div>
                                    @if ($errors->has('nextRepayAmt'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('nextRepayAmt') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group has-feedback row {{ $errors->has('nextRepayTime') ? ' has-error ' : '' }}">
                                {!! Form::label('nextRepayTime', 'Next Repayment Date', array('class' => 'col-md-3 control-label')); !!}
                                <div class="col-md-9">
                                    <div class="input-group">
                                        {!! Form::text('nextRepayTime', $loan->nextRepayTime, array('id' => 'enrollment_date', 'class' => 'form-control datepicker-here', 'data-language'=>"en", 'data-date-format'=>"dd-mm-yyyy", 'placeholder' => 'e.g. 2022-04-21', 'required')) !!}
                                    </div>
                                    @if ($errors->has('nextRepayTime'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('nextRepayTime') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group has-feedback row {{ $errors->has('repayedAmt') ? ' has-error ' : '' }}">
                                {!! Form::label('repayedAmt', 'Paid Amount', array('class' => 'col-md-3 control-label')); !!}
                                <div class="col-md-9">
                                    <div class="input-group">
                                        {!! Form::text('repayedAmt', $loan->repayedAmt, array('id' => 'repayedAmt', 'class' => 'form-control','pattern'=>"^\d{1,3}*(\.\d+)?$", 'data-type'=>"currency", 'placeholder' => 'e.g. 44.25', 'required')) !!}
                                    </div>
                                    @if ($errors->has('repayedAmt'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('repayedAmt') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group has-feedback row {{ $errors->has('totalAmt') ? ' has-error ' : '' }}">
                                {!! Form::label('totalAmt', 'Total Amount', array('class' => 'col-md-3 control-label')); !!}
                                <div class="col-md-9">
                                    <div class="input-group">
                                        {!! Form::text('totalAmt', $loan->totalAmt, array('id' => 'totalAmt', 'class' => 'form-control','pattern'=>"^\d{1,3}*(\.\d+)?$", 'data-type'=>"currency", 'placeholder' => 'e.g. 125.00', 'required')) !!}
                                    </div>
                                    @if ($errors->has('totalAmt'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('totalAmt') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>


                            {!! Form::button('Set Lock Parameters', array('class' => 'btn btn-primary btn-send margin-bottom-1 mb-1 float-right','type' => 'submit' )) !!}
                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>

                <div class="col-xl-5">
                    <div class="card">
                        <div class="card-body">
                            <div class="card-header">
                                Confirm Loan Details
                            </div>
                            <br>
                            @if ($loan->device)

                                <div class="col-lg-6 text-larger">
                                    <strong>
                                        Device:
                                    </strong>

                                    {{$loan->device}}
                                </div>
                                <div class="clearfix"></div>
                                <div class="border-bottom"></div>
                                <br>
                            @endif

                            @if ($loan->device_model)

                                <div class="col-lg-6 text-larger">
                                    <strong>
                                        Device Model:
                                    </strong>

                                    {{$loan->device_model}}
                                </div>
                                <div class="clearfix"></div>
                                <div class="border-bottom"></div>
                                <br>
                            @endif

                            @if ($loan->client_id)

                                <div class="col-lg-6 text-larger">
                                    <strong>
                                        Client:
                                    </strong>

                                    {{$client->first_name .' '.$client->last_name}}
                                </div>
                                <div class="clearfix"></div>
                                <div class="border-bottom"></div>
                                <br>
                            @endif


                            @if ($loan->partner_id)

                                <div class="col-lg-6 text-larger">
                                    <strong>
                                        Agent:
                                    </strong>

                                    {{'('.$client->creator.')'.' '.$agent->first_name.' '.$agent->last_name.' - '. $agent->mobile}}
                                    @if ($merchant)
                                        {{$merchant->partner_name}}
                                    @endif
                                </div>
                                <div class="clearfix"></div>
                                <div class="border-bottom"></div>
                                <br>
                            @endif


                            @if ($loan->channel_id)

                                <div class="col-lg-6 text-larger">
                                    <strong>
                                        Applied via:
                                    </strong>

                                    {{$loan->channel_id}}
                                </div>
                                <div class="clearfix"></div>
                                <div class="border-bottom"></div>
                                <br>
                            @endif

                            @if ($loan->loan_type)

                                <div class="col-lg-6 text-larger">
                                    <strong>
                                        Type of Loan:
                                    </strong>

                                    {{getDeviceLoantype($loan->loan_type)}}
                                </div>
                                <div class="clearfix"></div>
                                <div class="border-bottom"></div>
                                <br>
                            @endif

                            @if ($loan->loan_status)

                                <div class="col-lg-6 text-larger">
                                    <strong>
                                        Loan Status:
                                    </strong>

                                    {{getDeviceLoanstatus($loan->loan_status)}}
                                </div>
                                <div class="clearfix"></div>
                                <div class="border-bottom"></div>
                                <br>
                            @endif

                            @if ($loan->deposit_prct)

                                <div class="col-lg-6 text-larger">
                                    <strong>
                                        Deposit Percent:
                                    </strong>

                                    {{$loan->deposit_prct}} %
                                </div>
                                <div class="clearfix"></div>
                                <div class="border-bottom"></div>
                                <br>
                            @endif

                            @if ($loan->deposit)

                                <div class="col-lg-6 text-larger">
                                    <strong>
                                        Deposit :
                                    </strong>

                                    ${{$loan->deposit_prct}}
                                </div>
                                <div class="clearfix"></div>
                                <div class="border-bottom"></div>
                                <br>
                            @endif

                            @if ($loan->amount)

                                <div class="col-lg-6 text-larger">
                                    <strong>
                                        Amount:
                                    </strong>

                                    ${{$loan->amount}}
                                </div>
                                <div class="clearfix"></div>
                                <div class="border-bottom"></div>
                                <br>
                            @endif

                            @if ($loan->disbursed)

                                <div class="col-lg-6 text-larger">
                                    <strong>
                                        Amount Disbursed:
                                    </strong>

                                    ${{$loan->disbursed}}
                                </div>
                                <div class="clearfix"></div>
                                <div class="border-bottom"></div>
                                <br>
                            @endif

                            @if ($loan->paybackPeriod)

                                <div class="col-lg-6 text-larger">
                                    <strong>
                                        Payback Period:
                                    </strong>

                                    {{$loan->paybackPeriod}} Months
                                </div>
                                <div class="clearfix"></div>
                                <div class="border-bottom"></div>
                                <br>
                            @endif

                            @if ($loan->interestRate)

                                <div class="col-lg-6 text-larger">
                                    <strong>
                                        Interest Rate:
                                    </strong>

                                    {{$loan->interestRate}}%
                                </div>
                                <div class="clearfix"></div>
                                <div class="border-bottom"></div>
                                <br>
                            @endif

                            @if ($loan->monthly)

                                <div class="col-lg-6 text-larger">
                                    <strong>
                                        Monthly Repayments:
                                    </strong>

                                    ${{$loan->monthly}}
                                </div>
                                <div class="clearfix"></div>
                                <div class="border-bottom"></div>
                                <br>
                            @endif

                            @if ($loan->appFee)

                                <div class="col-lg-6 text-larger">
                                    <strong>
                                        Application Fee:
                                    </strong>

                                    ${{$loan->appFee}}
                                </div>
                                <div class="clearfix"></div>
                                <div class="border-bottom"></div>
                                <br>
                            @endif

                            @if ($loan->disbursefee)

                                <div class="col-lg-6 text-larger">
                                    <strong>
                                        Loan Disbursement Fee:
                                    </strong>

                                    ${{$loan->disbursefee}}
                                </div>
                                <div class="clearfix"></div>
                                <div class="border-bottom"></div>
                                <br>
                            @endif

                            @if ($loan->charges)

                                <div class="col-lg-6 text-larger">
                                    <strong>
                                        Charges:
                                    </strong>

                                    ${{$loan->charges}}
                                </div>
                                <div class="clearfix"></div>
                                <div class="border-bottom"></div>
                                <br>
                            @endif

                            @if ($loan->serial_num)

                                <div class="col-lg-6 text-larger">
                                    <strong>
                                        Device Serial Number:
                                    </strong>

                                    {{$loan->serial_num}}
                                </div>
                                <div class="clearfix"></div>
                                <div class="border-bottom"></div>
                                <br>
                            @endif

                            @if ($loan->imei)

                                <div class="col-lg-6 text-larger">
                                    <strong>
                                        IMEI:
                                    </strong>

                                    {{$loan->imei}}
                                </div>
                                <div class="clearfix"></div>
                                <div class="border-bottom"></div>
                                <br>
                            @endif

                            @if ($loan->notes)

                                <div class="col-lg-6 text-larger">
                                    <strong>
                                        Loan Notes:
                                    </strong>

                                    {{$loan->notes}}
                                </div>
                                <div class="clearfix"></div>
                                <div class="border-bottom"></div>
                                <br>
                            @endif

                            @if (auth()->user()->hasRole('root') || auth()->user()->hasRole('admin') || auth()->user()->hasRole('manager') || auth()->user()->hasRole('loansofficer') || auth()->user()->hasRole('salesadmin')|| auth()->user()->hasRole('agent'))


                                @if ($loan->funder_id)

                                    <div class="col-lg-6 text-larger">
                                        <strong>
                                            Funder ID:
                                        </strong>

                                        {{$loan->funder_id}}
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="border-bottom"></div>
                                    <br>
                                @endif


                                @if ($loan->funder_acc_number)

                                    <div class="col-lg-6 text-larger">
                                        <strong>
                                            Funder Account Number:
                                        </strong>

                                        {{$loan->funder_acc_number}}
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="border-bottom"></div>
                                    <br>
                                @endif


                                @if ($loan->loan_number)

                                    <div class="col-lg-6 text-larger">
                                        <strong>
                                            Loan Number:
                                        </strong>

                                        {{$loan->loan_number}}
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="border-bottom"></div>
                                    <br>
                                @endif


                                @if ($loan->enrollment_date)

                                    <div class="col-lg-6 text-larger">
                                        <strong>
                                            PayTrigger Enrollment Date:
                                        </strong>

                                        {{$loan->enrollment_date->toDateString() ?? ''}}
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="border-bottom"></div>
                                    <br>
                                @endif

                            @endif

                            @if ($loan->created_at)

                                <div class="col-lg-6 text-larger">
                                    <strong>
                                        Applied On:
                                    </strong>

                                    {{$loan->created_at}}
                                </div>
                                <div class="clearfix"></div>
                                <div class="border-bottom"></div>
                                <br>
                            @endif


                            @if ($loan->updated_at)

                                <div class="col-lg-6 text-larger">
                                    <strong>
                                        Last Modified On:
                                    </strong>

                                    {{$loan->updated_at}}
                                </div>
                                <div class="clearfix"></div>
                                <div class="border-bottom"></div>
                            @endif

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
