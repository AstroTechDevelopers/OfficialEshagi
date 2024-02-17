<?php
/**
 * Created by PhpStorm for eshagitwo
 * User: vincegee
 * Date: 7/9/2021
 * Time: 21:08
 */
?>
@extends('layouts.app')

@section('template_title')
    Modify eLoan
@endsection

@section('template_linked_css')
    <link href="{{ asset('css/select2.min.css')}}" rel="stylesheet" />
@endsection

@section('content')
    <div class="page-title-box">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h4 class="page-title mb-1">eLoans</h4>
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{url('/eloans')}}">eLoans</a></li>
                        <li class="breadcrumb-item active">Edit eLoan:  {{$eloan->id}}</li>
                    </ol>
                </div>

                <div class="col-md-4">
                    <div class="float-right d-none d-md-block">
                        <div>
                            <a class="btn btn-light btn-rounded" href="{{url('/eloans')}}" type="button">
                                <i class="mdi mdi-keyboard-backspace mr-1"></i>Back to eLoans
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
                            {!! Form::open(array('route' => ['eloans.update',$eloan->id], 'method' => 'PUT', 'role' => 'form', 'class' => 'needs-validation')) !!}

                            {!! csrf_field() !!}

                            <div class="form-group has-feedback row {{ $errors->has('user_id') ? ' has-error ' : '' }}">
                                {!! Form::label('user_id', 'User ID', array('class' => 'col-md-3 control-label')); !!}
                                <div class="col-md-9">
                                    <div class="input-group">
                                        {!! Form::text('user_id', $sysuser->name, array('id' => 'user_id', 'class' => 'form-control', 'placeholder' => 'e.g. 1', 'required', 'readonly')) !!}
                                    </div>
                                    @if ($errors->has('user_id'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('user_id') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group has-feedback row {{ $errors->has('client_id') ? ' has-error ' : '' }}">
                                {!! Form::label('client_id', 'Client ID', array('class' => 'col-md-3 control-label')); !!}
                                <div class="col-md-9">
                                    <div class="input-group">
                                        {!! Form::text('client_id', $user->first_name.' '.$user->last_name.' - '. ($user->natid), array('id' => 'client_id', 'class' => 'form-control', 'placeholder' => 'e.g. 12', 'required', 'readonly')) !!}
                                    </div>
                                    @if ($errors->has('client_id'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('client_id') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group has-feedback row {{ $errors->has('partner_id') ? ' has-error ' : '' }}">
                                {!! Form::label('partner_id', 'Partner', array('class' => 'col-md-3 control-label')); !!}
                                <div class="col-md-9">
                                    <div class="input-group">
                                        {!! Form::text('partner_id', $user->creator, array('id' => 'partner_id', 'class' => 'form-control', 'placeholder' => 'e.g. 17', 'readonly')) !!}
                                    </div>
                                    @if ($errors->has('partner_id'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('partner_id') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group has-feedback row {{ $errors->has('funder_id') ? ' has-error ' : '' }}">
                                {!! Form::label('funder_id', 'Funder', array('class' => 'col-md-3 control-label')); !!}
                                <div class="col-md-9">
                                    <div class="input-group">
                                        {!! Form::text('funder_id', $eloan->funder_id, array('id' => 'funder_id', 'class' => 'form-control', 'placeholder' => 'e.g. RedSphere')) !!}
                                    </div>
                                    @if ($errors->has('funder_id'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('funder_id') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group has-feedback row {{ $errors->has('loan_number') ? ' has-error ' : '' }}">
                                {!! Form::label('loan_number', 'Loan Number', array('class' => 'col-md-3 control-label')); !!}
                                <div class="col-md-9">
                                    <div class="input-group">
                                        {!! Form::text('loan_number', $eloan->loan_number, array('id' => 'loan_number', 'class' => 'form-control', 'placeholder' => 'e.g. 12345', 'readonly')) !!}
                                    </div>
                                    @if ($errors->has('loan_number'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('loan_number') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group has-feedback row {{ $errors->has('loan_type') ? ' has-error ' : '' }}">
                                {!! Form::label('loan_type', 'Loan Type', array('class' => 'col-md-3 control-label')); !!}
                                <div class="col-md-9">
                                    <select class="custom-select form-control dynamic" name="loan_type" id="loan_type" required>
                                        <option value="1" {{ 1 == $eloan->loan_type ? 'selected="selected"' : '' }}>Cash Loan</option>
                                        <option value="2" {{ 2 == $eloan->loan_type ? 'selected="selected"' : '' }}>Store Credit Loan</option>
                                        <option value="3" {{ 3 == $eloan->loan_type ? 'selected="selected"' : '' }}>Hybrid Loan</option>
                                        <option value="4" {{ 4 == $eloan->loan_type ? 'selected="selected"' : '' }}>Business Loan</option>
                                        <option value="5" {{ 5 == $eloan->loan_type ? 'selected="selected"' : '' }}>Recharge Loan</option>
                                    </select>
                                    @if ($errors->has('loan_type'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('loan_type') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group has-feedback row {{ $errors->has('loan_status') ? ' has-error ' : '' }}">
                                {!! Form::label('loan_status', 'Loan Status', array('class' => 'col-md-3 control-label')); !!}
                                <div class="col-md-9">
                                    <div class="input-group">
                                        <p class="form-control">
                                            {{getELoanStatus($eloan->loan_status)}}
                                        </p>

                                    </div>
                                    @if ($errors->has('loan_status'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('loan_status') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group has-feedback row {{ $errors->has('amount') ? ' has-error ' : '' }}">
                                {!! Form::label('amount', 'Loan Amount', array('class' => 'col-md-3 control-label')); !!}
                                <div class="col-md-9">
                                    <div class="input-group">
                                        {!! Form::text('amount', $eloan->amount, array('id' => 'amount', 'class' => 'form-control', 'placeholder' => 'e.g. 123.45', 'onKeyUp'=>'calculate();', 'required')) !!}
                                    </div>
                                    @if ($errors->has('amount'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('amount') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group has-feedback row {{ $errors->has('tenure') ? ' has-error ' : '' }}">
                                {!! Form::label('tenure', 'Payback Period', array('class' => 'col-md-3 control-label')); !!}
                                <div class="col-md-9">
                                    <select onchange="calculate();" class="form-control" name="tenure" id="tenure" required>
                                        <option value="2" {{ 2 == $eloan->tenure ? 'selected="selected"' : '' }}>2 Months</option>
                                        <option value="3" {{ 3 == $eloan->tenure ? 'selected="selected"' : '' }}>3 Months</option>
                                        <option value="4" {{ 4 == $eloan->tenure ? 'selected="selected"' : '' }}>4 Months</option>
                                        <option value="5" {{ 5 == $eloan->tenure ? 'selected="selected"' : '' }}>5 Months</option>
                                        <option value="6" {{ 6 == $eloan->tenure ? 'selected="selected"' : '' }}>6 Months</option>
                                        <option value="7" {{ 7 == $eloan->tenure ? 'selected="selected"' : '' }}>7 Months</option>
                                        <option value="8" {{ 8 == $eloan->tenure ? 'selected="selected"' : '' }}>8 Months</option>
                                        <option value="9" {{ 9 == $eloan->tenure ? 'selected="selected"' : '' }}>9 Months</option>
                                        <option value="10" {{ 10 == $eloan->tenure ? 'selected="selected"' : '' }}>10 Months</option>
                                        <option value="11" {{ 11 == $eloan->tenure ? 'selected="selected"' : '' }}>11 Months</option>
                                        <option value="12" {{ 12 == $eloan->tenure ? 'selected="selected"' : '' }}>12 Months</option>
                                        <option value="13" {{ 13 == $eloan->tenure ? 'selected="selected"' : '' }}>13 Months</option>
                                        <option value="14" {{ 14 == $eloan->tenure ? 'selected="selected"' : '' }}>14 Months</option>
                                        <option value="15" {{ 15 == $eloan->tenure ? 'selected="selected"' : '' }}>15 Months</option>
                                        <option value="16" {{ 16 == $eloan->tenure ? 'selected="selected"' : '' }}>16 Months</option>
                                        <option value="17" {{ 17 == $eloan->tenure ? 'selected="selected"' : '' }}>17 Months</option>
                                        <option value="18" {{ 18 == $eloan->tenure ? 'selected="selected"' : '' }}>18 Months</option>
                                        <option value="19" {{ 19 == $eloan->tenure ? 'selected="selected"' : '' }}>19 Months</option>
                                        <option value="20" {{ 20 == $eloan->tenure ? 'selected="selected"' : '' }}>20 Months</option>
                                        <option value="21" {{ 21 == $eloan->tenure ? 'selected="selected"' : '' }}>21 Months</option>
                                        <option value="22" {{ 22 == $eloan->tenure ? 'selected="selected"' : '' }}>22 Months</option>
                                        <option value="23" {{ 23 == $eloan->tenure ? 'selected="selected"' : '' }}>23 Months</option>
                                        <option value="24" {{ 24 == $eloan->tenure ? 'selected="selected"' : '' }}>24 Months</option>
                                    </select>
                                    @if ($errors->has('tenure'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('tenure') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group has-feedback row {{ $errors->has('interestRate') ? ' has-error ' : '' }}">
                                {!! Form::label('interestRate', 'Loan Interest', array('class' => 'col-md-3 control-label')); !!}
                                <div class="col-md-9">
                                    <div class="input-group">
                                        {!! Form::text('interestRate', $eloan->interestRate, array('id' => 'interestRate', 'class' => 'form-control', 'placeholder' => 'e.g. 15', 'required')) !!}
                                    </div>
                                    @if ($errors->has('interestRate'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('interestRate') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group has-feedback row {{ $errors->has('monthly') ? ' has-error ' : '' }}">
                                {!! Form::label('monthly', 'Scheduled Monthly Repayment', array('class' => 'col-md-3 control-label')); !!}
                                <div class="col-md-9">
                                    <div class="input-group">
                                        {!! Form::text('monthly', $eloan->monthly, array('id' => 'monthly', 'class' => 'form-control', 'placeholder' => 'e.g. 1500.78', 'required')) !!}
                                    </div>
                                    @if ($errors->has('monthly'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('monthly') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group has-feedback row {{ $errors->has('disbursed') ? ' has-error ' : '' }}">
                                {!! Form::label('disbursed', 'Scheduled Disbursed Amount', array('class' => 'col-md-3 control-label')); !!}
                                <div class="col-md-9">
                                    <div class="input-group">
                                        {!! Form::text('disbursed', $eloan->disbursed, array('id' => 'disbursed', 'class' => 'form-control', 'placeholder' => 'e.g. 100.78', 'required')) !!}
                                    </div>
                                    @if ($errors->has('disbursed'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('disbursed') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group has-feedback row {{ $errors->has('appFee') ? ' has-error ' : '' }}">
                                {!! Form::label('appFee', 'Application Fee', array('class' => 'col-md-3 control-label')); !!}
                                <div class="col-md-9">
                                    <div class="input-group">
                                        {!! Form::text('appFee', $eloan->appFee, array('id' => 'appFee', 'class' => 'form-control', 'placeholder' => 'e.g. 30.78', 'required')) !!}
                                    </div>
                                    @if ($errors->has('appFee'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('appFee') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group has-feedback row {{ $errors->has('charges') ? ' has-error ' : '' }}">
                                {!! Form::label('charges', 'Application Charges', array('class' => 'col-md-3 control-label')); !!}
                                <div class="col-md-9">
                                    <div class="input-group">
                                        {!! Form::text('charges', $eloan->charges, array('id' => 'charges', 'class' => 'form-control', 'placeholder' => 'e.g. 30.78', 'required')) !!}
                                    </div>
                                    @if ($errors->has('charges'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('charges') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group has-feedback row {{ $errors->has('product') ? ' has-error ' : '' }}">
                                {!! Form::label('product', 'Product', array('class' => 'col-md-3 control-label')); !!}
                                <div class="col-md-9">
                                    <div class="input-group">
                                        <select class="form-control" id="product" name="product">
                                            <option value="">Select Product </option>
                                            @if ($products)
                                                @foreach($products as $product)
                                                    <option value="{{$product->pcode}}" {{ $eloan->product == $product->pcode ? 'selected="selected"' : '' }} data-price="{{$product->price}}" >{{ $product->pcode }} - {{ $product->pname }} (${{$product->price}}) from {{$product->creator}}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                    @if ($errors->has('product'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('product') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group has-feedback row {{ $errors->has('pprice') ? ' has-error ' : '' }}">
                                {!! Form::label('pprice', 'Product Price', array('class' => 'col-md-3 control-label')); !!}
                                <div class="col-md-9">
                                    <div class="input-group">
                                        {!! Form::text('pprice', $eloan->pprice, array('id' => 'pprice', 'class' => 'form-control', 'placeholder' => 'e.g. 14.78','readonly')) !!}
                                    </div>
                                    @if ($errors->has('pprice'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('pprice') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group has-feedback row {{ $errors->has('install_date') ? ' has-error ' : '' }}">
                                {!! Form::label('install_date', 'Install Date', array('class' => 'col-md-3 control-label')); !!}
                                <div class="col-md-9">
                                    <div class="input-group">
                                        {!! Form::text('install_date', $eloan->install_date, array('id' => 'install_date', 'class' => 'form-control', 'placeholder' => 'e.g. 25')) !!}
                                    </div>
                                    @if ($errors->has('install_date'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('install_date') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group has-feedback row {{ $errors->has('maturity_date') ? ' has-error ' : '' }}">
                                {!! Form::label('maturity_date', 'Maturity Date', array('class' => 'col-md-3 control-label')); !!}
                                <div class="col-md-9">
                                    <div class="input-group">
                                        {!! Form::text('maturity_date', $eloan->maturity_date, array('id' => 'maturity_date', 'class' => 'form-control', 'placeholder' => 'e.g. 31-01-2025')) !!}
                                    </div>
                                    @if ($errors->has('maturity_date'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('maturity_date') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group has-feedback row {{ $errors->has('isDisbursed') ? ' has-error ' : '' }}">
                                {!! Form::label('isDisbursed', 'Is this eLoan Disbursed?', array('class' => 'col-md-3 control-label')); !!}
                                <div class="col-md-9">
                                    <div class="input-group">
                                        <select class="custom-select form-control dynamic" name="isDisbursed" id="isDisbursed" required>
                                            <option value="0" {{ 0 == $eloan->isDisbursed ? 'selected="selected"' : '' }}>No</option>
                                            <option value="1" {{ 1 == $eloan->isDisbursed ? 'selected="selected"' : '' }}>Yes</option>
                                        </select>
                                    </div>
                                    @if ($errors->has('isDisbursed'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('isDisbursed') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group has-feedback row {{ $errors->has('notes') ? ' has-error ' : '' }}">
                                {!! Form::label('notes', 'Loan Notes', array('class' => 'col-md-3 control-label')); !!}
                                <div class="col-md-9">
                                    <div class="input-group">
                                        {!! Form::textarea('notes', $eloan->notes, array('id' => 'notes', 'class' => 'form-control', 'placeholder' => 'e.g. Loan applied for device')) !!}
                                    </div>
                                    @if ($errors->has('notes'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('notes') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            {!! Form::button('Update eLoan', array('class' => 'btn btn-success margin-bottom-1 mb-1 float-right','type' => 'submit' )) !!}
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

        $("#tenure").select2({
            placeholder: 'Please select a Payback period.',
            allowClear:true,
        }).on('change',function(){
            calculate();
        });

        $("#product").select2({
            placeholder: 'Please select a product.',
            allowClear:true,
        }).on('change',function(){
            var amount = $(this).children('option:selected').data('price');
            $('#pprice').val(amount);
        });
    </script>

    <script type="text/javascript">
        function validateCreditLimit() {
            var amount=document.getElementById("amount").value;
            var creditLimit= document.getElementById("cred_limit").value;

            if(amount>creditLimit) {
                alert("Please do not exceed client maximum credit limit of ZWL$"+creditLimit);
                document.getElementById("amount").value=0;
            }
        }

    </script>

    @include('eloans.calculate-eloan')
@endsection
