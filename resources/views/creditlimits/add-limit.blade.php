<?php
/**
 * Created by PhpStorm for eshagi
 * User: vinceg
 * Date: 20/2/2021
 * Time: 13:39
 */
?>
@extends('layouts.app')

@section('template_title')
    Add Credit Limit Record
@endsection

@section('template_linked_css')
    <link href="{{ asset('css/select2.min.css')}}" rel="stylesheet" />
@endsection

@section('content')
    <div class="page-title-box">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h4 class="page-title mb-1">Limits</h4>
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{url('/limits')}}">Limits</a></li>
                        <li class="breadcrumb-item active">System Limits</li>
                    </ol>
                </div>

                <div class="col-md-4">
                    <div class="float-right d-none d-md-block">
                        <div>
                            <a class="btn btn-light btn-rounded" href="{{url('/limits')}}" type="button">
                                <i class="mdi mdi-keyboard-backspace mr-1"></i>Back to Limits
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
                            {!! Form::open(array('route' => 'limits.store', 'method' => 'POST', 'role' => 'form', 'class' => 'needs-validation')) !!}

                            {!! csrf_field() !!}

                            <div class="form-group has-feedback row {{ $errors->has('client_id') ? ' has-error ' : '' }}">
                                {!! Form::label('client_id', 'Client', array('class' => 'col-md-3 control-label')); !!}
                                <div class="col-md-9">
                                    <select class="custom-select form-control dynamic" name="client_id" id="client_id" required>
                                        <option value="">Select Client</option>
                                        @if ($clients)
                                            @foreach($clients as $client)
                                                <option value="{{ $client->id }}" data-price="{{$client->gross}}" data-tager="{{$client->salary}}" data-taglast="{{$client->cred_limit}}">{{ $client->first_name.' '. $client->last_name .' - '. $client->natid}} </option>
                                            @endforeach
                                        @endif
                                    </select>
                                    @if ($errors->has('client_id'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('client_id') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group has-feedback row {{ $errors->has('grossSalary') ? ' has-error ' : '' }}">
                                {!! Form::label('grossSalary', 'Gross Salary', array('class' => 'col-md-3 control-label')); !!}
                                <div class="col-md-9">
                                    <div class="input-group">
                                        {!! Form::text('grossSalary', NULL, array('id' => 'grossSalary', 'class' => 'form-control', 'placeholder' => 'e.g. 21255.40', 'readonly')) !!}
                                    </div>
                                    @if ($errors->has('grossSalary'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('grossSalary') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group has-feedback row {{ $errors->has('netSalary') ? ' has-error ' : '' }}">
                                {!! Form::label('netSalary', 'Net Salary', array('class' => 'col-md-3 control-label')); !!}
                                <div class="col-md-9">
                                    <div class="input-group">
                                        {!! Form::text('netSalary', NULL, array('id' => 'netSalary', 'class' => 'form-control', 'placeholder' => 'e.g. 18254.63', 'readonly')) !!}
                                    </div>
                                    @if ($errors->has('netSalary'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('netSalary') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group has-feedback row {{ $errors->has('creditlimit') ? ' has-error ' : '' }}">
                                {!! Form::label('creditlimit', 'Credit Limit', array('class' => 'col-md-3 control-label')); !!}
                                <div class="col-md-9">
                                    <div class="input-group">
                                        {!! Form::text('creditlimit', NULL, array('id' => 'creditlimit', 'class' => 'form-control', 'placeholder' => 'e.g. 60000.00', 'readonly')) !!}
                                    </div>
                                    @if ($errors->has('creditlimit'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('creditlimit') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            {!! Form::button('Add Credit Limit', array('class' => 'btn btn-success margin-bottom-1 mb-1 float-right','type' => 'submit' )) !!}
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
        $("#client_id").select2({
            placeholder: 'Please select a client',
            allowClear:true,
        }).on('change',function(){
            var price = $(this).children('option:selected').data('price');
            var price1 = $(this).children('option:selected').data('tager');
            var price2 = $(this).children('option:selected').data('taglast');
            $('#grossSalary').val(price);
            $('#netSalary').val(price1);
            $('#creditlimit').val(price2);
        });
    </script>
@endsection
