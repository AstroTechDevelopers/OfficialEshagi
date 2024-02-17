<?php
/**
 * Created by PhpStorm for eshagi
 * User: vinceg
 * Date: 18/3/2021
 * Time: 09:13
 */
?>
@extends('layouts.app')

@section('template_title')
    Loan Disbursed Status
@endsection

@section('content')
    <div class="page-title-box">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h4 class="page-title mb-1">Loans</h4>
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Loans</a></li>
                        <li class="breadcrumb-item active">Loan Disbursed Status</li>
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
                            <h4 class="header-title">eShagi Loan Information</h4>

                            <div class="card-body">
                                <div class="row">
                                @if($loanInfo->loan_status == 11)
                                    <div class="col-lg-6 float-left">
                                        {!! Form::open(array('route' => 'updateloanstatus', 'method' => 'POST', 'role' => 'form', 'class' => 'needs-validation float-left')) !!}
                                        {!! csrf_field() !!}

                                            <input type="hidden" name="loan_number" value="{{$loanInfo->loan_number}}">

                                        {!! Form::button('Confirm Disbursement', array('class' => 'btn btn-success margin-bottom-1 mb-1 float-left','type' => 'submit' )) !!}
                                        {!! Form::close() !!}
                                    </div>

                                        <div class="col-lg-6 float-right">
                                {!! Form::open(array('route' => 'declinedloanstatus', 'method' => 'POST', 'role' => 'form', 'class' => 'needs-validation float-right')) !!}
                                {!! csrf_field() !!}

                                    <input type="hidden" name="loan_number" value="{{$loanInfo->loan_number}}">

                                {!! Form::button('Confirm Declined', array('class' => 'btn btn-danger margin-bottom-1 mb-1 float-right','type' => 'submit' )) !!}
                                {!! Form::close() !!}
                                        </div>
                                @endif
                                </div>

                                <div class="col-xl-12">
                                    <div class="card">
                                        <div class="card-body">
                                            <dl class="user-info">
                                                <dt>
                                                    <strong>Loan Number</strong>
                                                </dt>
                                                <dd>
                                                    {{$loanInfo->loan_number}}
                                                </dd>
                                                <hr>
                                                <dt>
                                                    <strong>Customer</strong>
                                                </dt>
                                                <dd>
                                                    {{$client->first_name.' '.$client->last_name}}
                                                </dd>
                                                <hr>
                                                <dt>
                                                    <strong>National ID</strong>
                                                </dt>
                                                <dd>
                                                    {{$client->natid}}
                                                </dd>
                                                <hr>

                                                <dt>
                                                    <strong>Amount</strong>
                                                </dt>
                                                <dd>
                                                    ${{$loanInfo->amount}}
                                                </dd>
                                                <hr>
                                                <dt>
                                                    <strong>Agent</strong>
                                                </dt>
                                                <dd>
                                                    {{$client->creator}}
                                                </dd>
                                                <hr>

                                            </dl>
                                        </div>
                                    </div>

                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                @if(isset($resp))
                    <div class="col-xl-7">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="header-title text-info">{{$message}}</h4>
                                <dl class="user-info">
                                    <dt>
                                        <strong>Loan Number</strong>
                                    </dt>
                                    <dd>
                                        {{ $resp['LoanId'] }}
                                    </dd>
                                    <hr>
                                    <dt>
                                        <strong>RedSphere Number</strong>
                                    </dt>
                                    <dd>
                                        {{ $resp['CustomerNumber'] }}
                                    </dd>
                                    <hr>

                                    <dt>
                                        <strong>Loan Status</strong>
                                    </dt>
                                    <dd>
                                        {{ $resp['LoanStatus'] }}
                                    </dd>
                                    <hr>
                                    <dt>
                                        <strong>Loan Amount</strong>
                                    </dt>
                                    <dd>
                                        {{ $resp['LoanAmount'] }}
                                    </dd>
                                    <hr>
                                    <dt>
                                        <strong>Interest Rate</strong>
                                    </dt>
                                    <dd>
                                        {{ $resp['InterestRate'] }}
                                    </dd>
                                    <hr>
                                    <dt>
                                        <strong>Is Disbursed?</strong>
                                    </dt>
                                    <dd>
                                        @if($resp['IsDisbursed'] == false)
                                            No
                                        @else
                                            Yes
                                        @endif
                                    </dd>
                                    <hr>

                                </dl>
                            </div>
                        </div>
                        @endif

                    </div>

            </div>
        </div>
    </div>
@endsection
