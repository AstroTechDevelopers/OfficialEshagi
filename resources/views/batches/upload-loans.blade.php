<?php
/**
 * Created by PhpStorm for eshagi
 * User: vinceg
 * Date: 17/12/2020
 * Time: 11:47
 */
?>
@extends('layouts.app')

@section('template_title')
    Update Loans From Ndasenda
@endsection

@section('content')
    <div class="page-title-box">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h4 class="page-title mb-1">Ndasenda Batches</h4>
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{url('/batches')}}">Ndasenda Batches</a></li>
                        <li class="breadcrumb-item active">Update Loans</li>
                    </ol>
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
                            {!! Form::open(array('route' => 'importndasenda.batch', 'method' => 'POST', 'role' => 'form', 'class' => 'needs-validation', 'enctype'=>'multipart/form-data')) !!}
                            {!! csrf_field() !!}
                            <div class="form-group has-feedback row {{ $errors->has('ndasBatch') ? ' has-error ' : '' }}">
                                {!! Form::label('ndasBatch', 'Ndasenda Batch Number', array('class' => 'col-md-3 control-label')); !!}
                                <div class="col-md-9">
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="ndasBatch" name="ndasBatch" placeholder="e.g. REQ1234567" required>
                                        @if ($errors->has('ndasBatch'))
                                            <div class="invalid-feedback">
                                                <strong>{{ $errors->first('ndasBatch') }}</strong>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="form-group has-feedback row {{ $errors->has('ndasenda_excel') ? ' has-error ' : '' }}">
                                    {!! Form::label('ndasenda_excel', 'Ndasenda Response File', array('class' => 'col-md-3 control-label')); !!}
                                    <div class="col-md-9">
                                        <div class="custom-file">
                                            <input type="file" class="form-control" id="ndasenda_excel" name="ndasenda_excel" required>
                                            @if ($errors->has('ndasenda_excel'))
                                                <div class="invalid-feedback">
                                                    <strong>{{ $errors->first('ndasenda_excel') }}</strong>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                            </div>

                            <br>
                            <div class="text-danger">
                                <strong>1. Please remove file headings. i.e. RecId, DeductionCode, Reference, IdNumber,	EcNumber, Type, Status, StartDate, EndDate, Amount, TotalAmount, Name, Bank, BankAccount, Message
                                    <br> as illustrated: <br>
                                    <img src="{{asset('project/public/upload_sample.png')}}" alt="" style="width: 900px;">
                                </strong> <br>
                                <strong>2. Please do not rename the response file, as the filename is used as the Ndasenda Reference. i.e. RESXXXXXXXX</strong><br>
                            </div>

                            {!! Form::button('Upload Loans', array('class' => 'btn btn-success margin-bottom-1 mb-1 float-right','type' => 'submit' )) !!}
                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
