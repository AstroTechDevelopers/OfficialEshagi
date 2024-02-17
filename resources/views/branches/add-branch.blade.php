<?php
/**
 *Created by PhpStorm for eshagi
 *User: Vincent Guyo
 *Date: 10/10/2020
 *Time: 2:44 PM
 */

?>
@extends('layouts.app')

@section('template_title')
    Add Branch
@endsection

@section('template_linked_css')
    <link href="{{ asset('css/select2.min.css')}}" rel="stylesheet" />
@endsection

@section('content')
    <div class="page-title-box">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h4 class="page-title mb-1">Branches</h4>
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{url('/branches')}}">Branches</a></li>
                        <li class="breadcrumb-item active">Bank Branches</li>
                    </ol>
                </div>

                <div class="col-md-4">
                    <div class="float-right d-none d-md-block">
                        <div>
                            <a class="btn btn-light btn-rounded" href="{{url('/branches')}}" type="button">
                                <i class="mdi mdi-keyboard-backspace mr-1"></i>Back to Branches
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
                            {!! Form::open(array('route' => 'branches.store', 'method' => 'POST', 'role' => 'form', 'class' => 'needs-validation')) !!}

                            {!! csrf_field() !!}

                            <div class="form-group has-feedback row {{ $errors->has('bank_id') ? ' has-error ' : '' }}">
                                {!! Form::label('bank_id', 'Bank', array('class' => 'col-md-3 control-label')); !!}
                                <div class="col-md-9">
                                    <select class="custom-select form-control dynamic" name="bank_id" id="bank_id" required>
                                        <option value="">Select Bank</option>
                                        @if ($banks)
                                            @foreach($banks as $bank)
                                                <option value="{{ $bank->id }}" >{{ $bank->bank }} </option>
                                            @endforeach
                                        @endif
                                    </select>
                                    @if ($errors->has('bank_id'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('bank_id') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group has-feedback row {{ $errors->has('branch') ? ' has-error ' : '' }}">
                                {!! Form::label('branch', 'Branch', array('class' => 'col-md-3 control-label')); !!}
                                <div class="col-md-9">
                                    <div class="input-group">
                                        {!! Form::text('branch', NULL, array('id' => 'branch', 'class' => 'form-control', 'placeholder' => 'e.g. Jason Moyo', 'required')) !!}
                                    </div>
                                    @if ($errors->has('branch'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('branch') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group has-feedback row {{ $errors->has('branch_code') ? ' has-error ' : '' }}">
                                {!! Form::label('branch_code', 'Bank Branch Code', array('class' => 'col-md-3 control-label')); !!}
                                <div class="col-md-9">
                                    <div class="input-group">
                                        {!! Form::text('branch_code', NULL, array('id' => 'branch_code', 'class' => 'form-control', 'placeholder' => 'e.g. 01551', 'required')) !!}
                                    </div>
                                    @if ($errors->has('branch_code'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('branch_code') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            {!! Form::button('Add Branch', array('class' => 'btn btn-success margin-bottom-1 mb-1 float-right','type' => 'submit' )) !!}
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
        $("#bank_id").select2({
            placeholder: 'Please select a bank.',
            allowClear:true,
        });
    </script>
@endsection
