<?php
/**
 *Created by PhpStorm for eshagi
 *User: Vincent Guyo
 *Date: 10/10/2020
 *Time: 1:33 PM
 */

?>
@extends('layouts.app')

@section('template_title')
    Add Bank
@endsection

@section('template_linked_css')
    <link href="{{ asset('css/select2.min.css')}}" rel="stylesheet" />
@endsection

@section('content')
    <div class="page-title-box">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h4 class="page-title mb-1">Banks</h4>
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{url('/banks')}}">Banks</a></li>
                        <li class="breadcrumb-item active">System Banks</li>
                    </ol>
                </div>

                <div class="col-md-4">
                    <div class="float-right d-none d-md-block">
                        <div>
                            <a class="btn btn-light btn-rounded" href="{{url('/banks')}}" type="button">
                                <i class="mdi mdi-keyboard-backspace mr-1"></i>Back to Banks
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
                            {!! Form::open(array('route' => 'banks.store', 'method' => 'POST', 'role' => 'form', 'class' => 'needs-validation')) !!}

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

                            <div class="form-group has-feedback row {{ $errors->has('bank') ? ' has-error ' : '' }}">
                                {!! Form::label('bank', 'Bank', array('class' => 'col-md-3 control-label')); !!}
                                <div class="col-md-9">
                                    <div class="input-group">
                                        {!! Form::text('bank', NULL, array('id' => 'bank', 'class' => 'form-control', 'placeholder' => 'e.g. Zanaco', 'required')) !!}
                                    </div>
                                    @if ($errors->has('bank'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('bank') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group has-feedback row {{ $errors->has('bank_short') ? ' has-error ' : '' }}">
                                {!! Form::label('bank_short', 'Bank Short Name', array('class' => 'col-md-3 control-label')); !!}
                                <div class="col-md-9">
                                    <div class="input-group">
                                        {!! Form::text('bank_short', NULL, array('id' => 'bank_short', 'class' => 'form-control', 'placeholder' => 'e.g. FNB', 'required')) !!}
                                    </div>
                                    @if ($errors->has('bank_short'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('bank_short') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group has-feedback row {{ $errors->has('bank_post_address') ? ' has-error ' : '' }}">
                                {!! Form::label('bank_post_address', 'Bank Postal Address', array('class' => 'col-md-3 control-label')); !!}
                                <div class="col-md-9">
                                    <div class="input-group">
                                        {!! Form::text('bank_post_address', NULL, array('id' => 'bank_post_address', 'class' => 'form-control', 'placeholder' => 'e.g. P.O Box 10101', 'required')) !!}
                                    </div>
                                    @if ($errors->has('bank_post_address'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('bank_post_address') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group has-feedback row {{ $errors->has('bank_city') ? ' has-error ' : '' }}">
                                {!! Form::label('bank_city', 'Bank City', array('class' => 'col-md-3 control-label')); !!}
                                <div class="col-md-9">
                                    <div class="input-group">
                                        {!! Form::text('bank_city', NULL, array('id' => 'bank_city', 'class' => 'form-control', 'placeholder' => 'e.g. kabwe', 'bank_city')) !!}
                                    </div>
                                    @if ($errors->has('bank_city'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('bank_city') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group has-feedback row {{ $errors->has('bank_telephone') ? ' has-error ' : '' }}">
                                {!! Form::label('bank_telephone', 'Bank Telephone', array('class' => 'col-md-3 control-label')); !!}
                                <div class="col-md-9">
                                    <div class="input-group">
                                        {!! Form::text('bank_telephone', NULL, array('id' => 'bank_telephone', 'class' => 'form-control', 'placeholder' => 'e.g. 0979334455', 'bank_telephone')) !!}
                                    </div>
                                    @if ($errors->has('bank_telephone'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('bank_telephone') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            {!! Form::button('Add Bank', array('class' => 'btn btn-success margin-bottom-1 mb-1 float-right','type' => 'submit' )) !!}
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
@endsection
