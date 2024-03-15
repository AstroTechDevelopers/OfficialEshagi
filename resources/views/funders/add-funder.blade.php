<?php
/**
 *Created by PhpStorm for eshagi
 *User: Vincent Guyo
 *Date: 10/10/2020
 *Time: 5:48 PM
 */

?>
@extends('layouts.app')

@section('template_title')
    Add Funder
@endsection

@section('template_linked_css')
    <link href="{{ asset('css/select2.min.css')}}" rel="stylesheet" />
@endsection

@section('content')
    <div class="page-title-box">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h4 class="page-title mb-1">Funders</h4>
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{url('/funders')}}">Funders</a></li>
                        <li class="breadcrumb-item active">Add a Funder</li>
                    </ol>
                </div>

                <div class="col-md-4">
                    <div class="float-right d-none d-md-block">
                        <div>
                            <a class="btn btn-light btn-rounded" href="{{url('/funders')}}" type="button">
                                <i class="mdi mdi-keyboard-backspace mr-1"></i>Back to Funders
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
                            {!! Form::open(array('route' => 'funders.store', 'method' => 'POST', 'role' => 'form', 'class' => 'needs-validation')) !!}

                            {!! csrf_field() !!}

                            <div class="form-group has-feedback row {{ $errors->has('locale_id') ? ' has-error ' : '' }}">
                                {!! Form::label('locale_id', 'Locale', array('class' => 'col-md-3 control-label')); !!}
                                <div class="col-md-9">
                                    <select class="custom-select form-control dynamic" name="locale_id" id="locale_id" required>
                                        <option value="">Select Locale</option>
                                        @if ($localels)
                                            @foreach($localels as $locale)
                                                <option value="{{ $locale->id }}" >{{ $locale->country }} </option>
                                            @endforeach
                                        @endif
                                    </select>
                                    @if ($errors->has('locale_id'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('locale_id') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group has-feedback row {{ $errors->has('funder') ? ' has-error ' : '' }}">
                                {!! Form::label('funder', 'Funder', array('class' => 'col-md-3 control-label')); !!}
                                <div class="col-md-9">
                                    <div class="input-group">
                                        {!! Form::text('funder', NULL, array('id' => 'funder', 'class' => 'form-control', 'placeholder' => 'e.g. Redshpere Financial Services', 'required')) !!}
                                    </div>
                                    @if ($errors->has('funder'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('funder') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group has-feedback row {{ $errors->has('funder_acc_num') ? ' has-error ' : '' }}">
                                {!! Form::label('funder_acc_num', 'Funder Account Number', array('class' => 'col-md-3 control-label')); !!}
                                <div class="col-md-9">
                                    <div class="input-group">
                                        {!! Form::text('funder_acc_num', NULL, array('id' => 'funder_acc_num', 'class' => 'form-control', 'placeholder' => 'e.g. 1233354446777', 'required')) !!}
                                    </div>
                                    @if ($errors->has('funder_acc_num'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('funder_acc_num') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group has-feedback row {{ $errors->has('contact_fname') ? ' has-error ' : '' }}">
                                {!! Form::label('contact_fname', 'Funder Contact\'s Firstname' , array('class' => 'col-md-3 control-label')); !!}
                                <div class="col-md-9">
                                    <div class="input-group">
                                        {!! Form::text('contact_fname', NULL, array('id' => 'contact_fname', 'class' => 'form-control', 'placeholder' => 'e.g. John', 'required')) !!}
                                    </div>
                                    @if ($errors->has('contact_fname'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('contact_fname') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group has-feedback row {{ $errors->has('contact_lname') ? ' has-error ' : '' }}">
                                {!! Form::label('contact_lname', 'Funder Contact\'s Surname' , array('class' => 'col-md-3 control-label')); !!}
                                <div class="col-md-9">
                                    <div class="input-group">
                                        {!! Form::text('contact_lname', NULL, array('id' => 'contact_lname', 'class' => 'form-control', 'placeholder' => 'e.g. Doe', 'required')) !!}
                                    </div>
                                    @if ($errors->has('contact_lname'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('contact_lname') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group has-feedback row {{ $errors->has('email') ? ' has-error ' : '' }}">
                                {!! Form::label('email', 'Funder\'s Email' , array('class' => 'col-md-3 control-label')); !!}
                                <div class="col-md-9">
                                    <div class="input-group">
                                        {!! Form::email('email', NULL, array('id' => 'email', 'class' => 'form-control', 'placeholder' => 'e.g. johndoe@gmail.com', 'required')) !!}
                                    </div>
                                    @if ($errors->has('email'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('email') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group has-feedback row {{ $errors->has('tel_no') ? ' has-error ' : '' }}">
                                {!! Form::label('tel_no', 'Funder Telephone' , array('class' => 'col-md-3 control-label')); !!}
                                <div class="col-md-9">
                                    <div class="input-group">
                                        {!! Form::text('tel_no', NULL, array('id' => 'tel_no', 'class' => 'form-control', 'placeholder' => 'e.g. 0242699425', 'required')) !!}
                                    </div>
                                    @if ($errors->has('tel_no'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('tel_no') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group has-feedback row {{ $errors->has('support_email') ? ' has-error ' : '' }}">
                                {!! Form::label('support_email', 'Support Email' , array('class' => 'col-md-3 control-label')); !!}
                                <div class="col-md-9">
                                    <div class="input-group">
                                        {!! Form::email('support_email', NULL, array('id' => 'support_email', 'class' => 'form-control', 'placeholder' => 'e.g. eshagi@cbz.co.zw', 'required')) !!}
                                    </div>
                                    @if ($errors->has('support_email'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('support_email') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <hr><br>

                            <div class="form-group has-feedback row {{ $errors->has('require_deposit') ? ' has-error ' : '' }}">
                                {!! Form::label('require_deposit', 'Does Funder Require deposit ?', array('class' => 'col-md-3 control-label')); !!}
                                <div class="col-md-9">
                                    <select class="custom-select form-control dynamic" name="require_deposit" id="require_deposit" required>
                                        <option value="">Select Option</option>
                                        <option value="1">YES</option>
                                        <option value="0">NO</option>
                                    </select>
                                    @if ($errors->has('require_deposit'))
                                        <span class="help-block">
                                                <strong>{{ $errors->first('require_deposit') }}</strong>
                                            </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group has-feedback row {{ $errors->has('initial_deposit_percentage') ? ' has-error ' : '' }}" id="initialDeposit">
                                {!! Form::label('initial_deposit_percentage', 'Initial Deposit %' , array('class' => 'col-md-3 control-label')); !!}
                                <div class="col-md-9">
                                    <div class="input-group">
                                        {!! Form::number('initial_deposit_percentage', NULL, array('id' => 'initial_deposit_percentage', 'class' => 'form-control', 'placeholder' => 'e.g. 10')) !!}
                                    </div>
                                    @if ($errors->has('initial_deposit_percentage'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('initial_deposit_percentage') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group has-feedback row {{ $errors->has('interest_rate_percentage') ? ' has-error ' : '' }}">
                                {!! Form::label('interest_rate_percentage', 'Interest Rate %' , array('class' => 'col-md-3 control-label')); !!}
                                <div class="col-md-9">
                                    <div class="input-group">
                                        {!! Form::number('interest_rate_percentage', NULL, array('id' => 'interest_rate_percentage', 'class' => 'form-control', 'placeholder' => 'e.g. 10', 'required')) !!}
                                    </div>
                                    @if ($errors->has('interest_rate_percentage'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('interest_rate_percentage') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group has-feedback row {{ $errors->has('max_repayment_month') ? ' has-error ' : '' }}">
                                {!! Form::label('max_repayment_month', 'Max Repayment Months' , array('class' => 'col-md-3 control-label')); !!}
                                <div class="col-md-9">
                                    <div class="input-group">
                                        {!! Form::number('max_repayment_month', NULL, array('id' => 'max_repayment_month', 'class' => 'form-control', 'placeholder' => 'e.g. 4', 'required')) !!}
                                    </div>
                                    @if ($errors->has('max_repayment_month'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('max_repayment_month') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <hr><br>
                            {!! Form::button('Add Funder', array('class' => 'btn btn-success margin-bottom-1 mb-1 float-right','type' => 'submit' )) !!}
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
        $("#locale_id").select2({
            placeholder: 'Please select locale.',
            allowClear:true,
        });

    </script>
    <script>
        $(document).ready(function (){
            $('#initialDeposit').hide();

            $('#require_deposit').change(function() {
                var selectedValue = $(this).val();
                if(selectedValue == 1){
                    $('#initialDeposit').show();
                }
                else{
                    $('#initialDeposit').hide();
                }
            });
        });

    </script>
@endsection
