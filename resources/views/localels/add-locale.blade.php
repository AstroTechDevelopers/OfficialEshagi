<?php
/**
 *Created by PhpStorm for eshagi
 *User: Vincent Guyo
 *Date: 10/10/2020
 *Time: 11:57 AM
 */

?>
@extends('layouts.app')

@section('template_title')
    Add Locale
@endsection

@section('template_linked_css')

@endsection

@section('content')
    <div class="page-title-box">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h4 class="page-title mb-1">Locales</h4>
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{url('/localels')}}">Locales</a></li>
                        <li class="breadcrumb-item active">eShagi Locales</li>
                    </ol>
                </div>

                <div class="col-md-4">
                    <div class="float-right d-none d-md-block">
                        <div>
                            <a class="btn btn-light btn-rounded" href="{{url('/localels')}}" type="button">
                                <i class="mdi mdi-keyboard-backspace mr-1"></i>Back to Locales
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
                            {!! Form::open(array('route' => 'localels.store', 'method' => 'POST', 'role' => 'form', 'class' => 'needs-validation')) !!}

                            {!! csrf_field() !!}

                            <div class="form-group has-feedback row {{ $errors->has('country') ? ' has-error ' : '' }}">
                                {!! Form::label('country', 'Country', array('class' => 'col-md-3 control-label')); !!}
                                <div class="col-md-9">
                                    <div class="input-group">
                                        {!! Form::text('country', NULL, array('id' => 'country', 'class' => 'form-control', 'placeholder' => 'e.g. Zimbabwe', 'required')) !!}
                                    </div>
                                    @if ($errors->has('country'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('country') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group has-feedback row {{ $errors->has('country_short') ? ' has-error ' : '' }}">
                                {!! Form::label('country_short', 'Country Abbreviation', array('class' => 'col-md-3 control-label')); !!}
                                <div class="col-md-9">
                                    <div class="input-group">
                                        {!! Form::text('country_short', NULL, array('id' => 'country_short', 'class' => 'form-control', 'placeholder' => 'e.g. ZW', 'required')) !!}
                                    </div>
                                    @if ($errors->has('country_short'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('country_short') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group has-feedback row {{ $errors->has('currency_code') ? ' has-error ' : '' }}">
                                {!! Form::label('currency_code', 'Currency Code', array('class' => 'col-md-3 control-label')); !!}
                                <div class="col-md-9">
                                    <div class="input-group">
                                        {!! Form::text('currency_code', NULL, array('id' => 'currency_code', 'class' => 'form-control', 'placeholder' => 'e.g. ZWL', 'required')) !!}
                                    </div>
                                    @if ($errors->has('currency_code'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('currency_code') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group has-feedback row {{ $errors->has('currency_name') ? ' has-error ' : '' }}">
                                {!! Form::label('currency_name', 'Currency Name', array('class' => 'col-md-3 control-label')); !!}
                                <div class="col-md-9">
                                    <div class="input-group">
                                        {!! Form::text('currency_name', NULL, array('id' => 'currency_name', 'class' => 'form-control', 'placeholder' => 'e.g. Zimbabwean Dollar', 'required')) !!}
                                    </div>
                                    @if ($errors->has('currency_name'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('currency_name') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group has-feedback row {{ $errors->has('symbol') ? ' has-error ' : '' }}">
                                {!! Form::label('symbol', 'Currency Symbol', array('class' => 'col-md-3 control-label')); !!}
                                <div class="col-md-9">
                                    <div class="input-group">
                                        {!! Form::text('symbol', NULL, array('id' => 'symbol', 'class' => 'form-control', 'placeholder' => 'e.g. $', 'required')) !!}
                                    </div>
                                    @if ($errors->has('symbol'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('symbol') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group has-feedback row {{ $errors->has('country_code') ? ' has-error ' : '' }}">
                                {!! Form::label('country_code', 'Country Code', array('class' => 'col-md-3 control-label')); !!}
                                <div class="col-md-9">
                                    <div class="input-group">
                                        {!! Form::text('country_code', NULL, array('id' => 'country_code', 'class' => 'form-control', 'placeholder' => 'e.g. 263', 'required')) !!}
                                    </div>
                                    @if ($errors->has('country_code'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('country_code') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            {!! Form::button('Add Locale', array('class' => 'btn btn-success margin-bottom-1 mb-1 float-right','type' => 'submit' )) !!}
                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('footer_scripts')

@endsection
