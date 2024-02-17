<?php
/**
 * Created by PhpStorm for eshagitwo
 * User: vincegee
 * Date: 6/9/2021
 * Time: 05:32
 */
?>
@extends('layouts.app')

@section('template_title')
    Authenticate Disbursement
@endsection

@section('content')
    <div class="page-title-box">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h4 class="page-title mb-1">eLoans</h4>
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">eLoans</a></li>
                        <li class="breadcrumb-item active">eLoan Disbursement Confirmation of eloan ID: {{$eloan->id}}</li>
                    </ol>
                </div>

                <div class="col-md-4">
                    <div class="float-right d-none d-md-block">

                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- end page title end breadcrumb -->
    <div class="page-content-wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-xl-5">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="header-title">OTP Confirmation</h4>

                            <div class="card-body">
                                {!! Form::open(array('route' => ['money.out',$eloan->id], 'method' => 'POST', 'role' => 'form', 'class' => 'd-inline')) !!}

                                {!! csrf_field() !!}
                                    <div class="form-group has-feedback row {{ $errors->has('otp') ? ' has-error ' : '' }}">
                                        {!! Form::label('otp', 'Enter the OTP sent to your phone', array('class' => 'col-md-12 control-label')); !!}
                                        <div class="col-md-12">
                                            <div class="input-group">
                                                {!! Form::password('otp', array('id' => 'otp', 'class' => 'form-control', 'placeholder' => 'e.g. 12345678', 'required')) !!}
                                            </div>
                                            @if ($errors->has('otp'))
                                                <span class="help-block">
                                            <strong>{{ $errors->first('otp') }}</strong>
                                        </span>
                                            @endif
                                        </div>
                                    </div>
                                <input type="hidden" name="loan_id" id="loan_id" value="{{$eloan->id}}"/>
                                    <br>
                                {!! Form::button('Disburse Loan', array('class' => 'btn btn-success margin-bottom-1 mb-1 float-right','type' => 'submit' )) !!}
                                {!! Form::close() !!}
                            </div>

                        </div>
                    </div>
                </div>
                    <div class="col-xl-7">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="header-title text-info">eLoan Information</h4>
                                <dl class="user-info">
                                    <dt>
                                        <strong>Client</strong>
                                    </dt>
                                    <dd>
                                        {{ $client->first_name.' '. $client->first_name}}
                                    </dd>
                                    <hr>
                                    <dt>
                                        <strong>Client National ID</strong>
                                    </dt>
                                    <dd>
                                        {{ $client->natid }}
                                    </dd>
                                    <hr>

                                    <dt>
                                        <strong>Client ID</strong>
                                    </dt>
                                    <dd>
                                        {{ $client->reds_number }}
                                    </dd>
                                    <hr>
                                    <dt>
                                        <strong>Loan Type</strong>
                                    </dt>
                                    <dd>
                                        {{ getELoanType($eloan->loan_type) }}
                                    </dd>
                                    <hr>
                                    <dt>
                                        <strong>Loan Amount</strong>
                                    </dt>
                                    <dd>
                                        {{ $eloan->amount }}
                                    </dd>
                                    <hr>
                                    <dt>
                                        <strong>Amount to Disburse</strong>
                                    </dt>
                                    <dd>
                                        {{ $eloan->disbursed }}
                                    </dd>
                                    <hr>
                                    <dt>
                                        <strong>Tenure</strong>
                                    </dt>
                                    <dd>
                                        {{ $eloan->tenure }}
                                    </dd>
                                    <hr>
                                    <dt>
                                        <strong>Charges</strong>
                                    </dt>
                                    <dd>
                                        {{ $eloan->charges }}
                                    </dd>
                                    <hr>

                                </dl>
                            </div>
                        </div>
                    </div>

            </div>
        </div>
    </div>
@endsection
