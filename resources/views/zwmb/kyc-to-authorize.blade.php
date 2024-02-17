<?php
/**
 * Created by PhpStorm for eshagitwo
 * User: VinceGee
 * Date: 6/29/2022
 * Time: 12:55 PM
 */ ?>
@extends('layouts.app')

@section('template_title')
    ZWMB Account to Authorize
@endsection

@section('content')
    <div class="page-title-box">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h4 class="page-title mb-1">ZWMB Account Details</h4>
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{url('/zwmbs')}}">ZWMB Clients</a></li>
                        <li class="breadcrumb-item active">ZWMB Client: {{$client->natid}}</li>
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
                <div class="col-xl-5">
                    <div class="card">
                        <div class="card-body">
                            <div class="card-header">
                                Authorize Account
                            </div>
                            <br>
                            {!! Form::open(array('route' => ['authorize.zwmb',$zwmb->id], 'method' => 'POST', 'id' => 'loanForm','role' => 'form', 'class' => 'needs-validation')) !!}
                            {!! csrf_field() !!}
                            <input type="hidden" name="loan_id" id="loan_id" value="{{$client->id}}">
                            <div class="form-group has-feedback row {{ $errors->has('customer_number') ? ' has-error ' : '' }}">
                                {!! Form::label('customer_number', 'Customer Number', array('class' => 'col-md-3 control-label')); !!}
                                <div class="col-md-9">
                                    <div class="input-group">
                                        {!! Form::text('customer_number', $zwmb->customer_number, array('id' => 'customer_number', 'class' => 'form-control', 'placeholder' => 'e.g. 9098882019922', 'required')) !!}
                                    </div>
                                    @if ($errors->has('customer_number'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('customer_number') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group has-feedback row {{ $errors->has('account_number') ? ' has-error ' : '' }}">
                                {!! Form::label('account_number', 'Account Number', array('class' => 'col-md-3 control-label')); !!}
                                <div class="col-md-9">
                                    <div class="input-group">
                                        {!! Form::text('account_number', $zwmb->account_number, array('id' => 'account_number', 'class' => 'form-control', 'placeholder' => 'e.g. 1121212213132', 'required')) !!}
                                    </div>
                                    @if ($errors->has('account_number'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('account_number') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            {!! Form::button('Authorize Client', array('class' => 'btn btn-primary btn-send margin-bottom-1 mb-1 float-right','type' => 'submit' )) !!}
                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>

                <div class="col-xl-7">
                    <div class="card">
                        <div class="card-body">
                            <ul class="nav nav-tabs nav-justified nav-tabs-custom" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" data-toggle="tab" href="#custom-details" role="tab">
                                        <i class="fas fa-user mr-1 align-middle"></i> <span class="d-none d-md-inline-block">Client Details</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#custom-kycdocs" role="tab">
                                        <i class="fas fa-user-tag mr-1 align-middle"></i> <span class="d-none d-md-inline-block">KYC Documents</span>
                                    </a>
                                </li>
                            </ul>
                            <div class="tab-content p-3">
                                <div class="tab-pane active" id="custom-details" role="tabpanel">
                                    <h4 class="header-title">{{$client->first_name.' '.$client->last_name}} Details</h4>

                                    <div class="row">

                                        <table class="table table-striped mb-0">
                                            <tr>
                                                <td>Full Name</td>
                                                <td><strong>{{$client->first_name.' '.$client->middle_name.' '.$client->last_name}}</strong></td>
                                                <td>National ID Number</td>
                                                <td><strong>{{$zwmb->natid}}</strong></td>
                                            </tr>
                                            <tr>
                                                <td>Date of Birth</td>
                                                <td><strong>{{date_format($client->dob, 'd F Y')}}</strong></td>
                                                <td>Gender</td>
                                                <td><strong>{{$client->gender}}</strong></td>
                                            </tr>
                                            <tr>
                                                <td>Passport Number</td>
                                                <td><strong>{{$zwmb->passport_number}}</strong></td>
                                                <td>Driver's Licence</td>
                                                <td><strong>{{$zwmb->driver_licence}}</strong></td>
                                            </tr>
                                            <tr>
                                                <td>Phone Number</td>
                                                <td><strong>{{$client->mobile}}</strong></td>
                                                <td>Email</td>
                                                <td><strong>{{$client->email}}</strong></td>
                                            </tr>

                                            <tr>
                                                <td>Title</td>
                                                <td><strong>{{$client->title}}</strong></td>
                                                <td>Maiden Name</td>
                                                <td><strong>{{$zwmb->maiden_name}}</strong></td>

                                            </tr>
                                            <tr>
                                                <td>Race</td>
                                                <td><strong>{{$zwmb->race}}</strong></td>
                                                <td>Mobile Banking Number</td>
                                                <td><strong>{{$zwmb->mobile_banking_num}}</strong></td>

                                            </tr>
                                            <tr>
                                                <td>Address</td>
                                                <td><strong>{{$client->house_num.', '.$client->street.', '.$client->surburb}}</strong></td>
                                                <td>City</td>
                                                <td><strong>{{$client->city}}</strong></td>
                                            </tr>
                                            <tr>
                                                <td>Account Number</td>
                                                <td><strong>{{$zwmb->account_number}}</strong></td>
                                                <td>EC Number</td>
                                                <td><strong>{{$client->ecnumber}}</strong></td>
                                            </tr>
                                            <tr>
                                                <td>Employer/Business</td>
                                                <td><strong>{{$zwmb->employer_name}}</strong></td>
                                                <td>Occupation</td>
                                                <td><strong>{{$zwmb->occupation}}</strong></td>
                                            </tr>
                                            <tr>
                                                <td>Credit Score</td>
                                                <td><strong>{{$client->fsb_score}}</strong></td>
                                                <td>FCb Rating</td>
                                                <td><strong>{{$client->fsb_rating}}</strong></td>
                                            </tr>
                                            <tr>
                                                <td>Next of Kin</td>
                                                <td><strong>{{$kyc->kin_fname.' '.$kyc->kin_lname}}</strong></td>
                                                <td>Relationship to Kin</td>
                                                <td><strong>{{$zwmb->kin_relationship}}</strong></td>
                                            </tr>
                                            <tr>
                                                <td>Next of Kin Address</td>
                                                <td><strong>{{$zwmb->kin_address}}</strong></td>
                                                <td>Kin Phone Number</td>
                                                <td><strong>{{$kyc->kin_number}}</strong></td>
                                            </tr>
                                            <tr>
                                                <td>Reviewed By</td>
                                                <td><strong>{{$zwmb->reviewer}}</strong></td>
                                                <td>Authorized By</td>
                                                <td><strong>{{$zwmb->authorized}}</strong></td>
                                            </tr>
                                        </table>

                                    </div>
                                </div>
                                <div class="tab-pane" id="custom-kycdocs" role="tabpanel">
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="national_pic">National ID</label>
                                            <div class="zoom-gallery">
                                                <a class="float-left" href="{{asset('nationalids/'.$kyc->national_pic)}}" title="{{$kyc->natid}}">
                                                    <img src="{{asset('nationalids/'.$kyc->national_pic)}}" alt="" width="275">
                                                </a>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="passport_pic">Passport Photo</label>
                                            <div class="zoom-gallery">
                                                <a class="float-left" href="{{asset('pphotos/'.$kyc->passport_pic)}}" title="{{$kyc->natid}}">
                                                    <img src="{{asset('pphotos/'.$kyc->passport_pic)}}" alt="" width="275">
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="payslip_pic">Proof of Income</label>
                                            <div class="zoom-gallery">
                                                <a class="float-left" href="{{asset('client_poincome/'.$zwmb->proof_of_income)}}" title="{{$kyc->natid}}">
                                                    <img src="{{asset('client_poincome/'.$zwmb->proof_of_income)}}" alt="" width="275">
                                                </a>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="proof_res">Proof of Residence</label>
                                            <div class="zoom-gallery">
                                                <a class="float-left" href="{{asset('client_por/'.$zwmb->proof_of_res)}}" title="{{$kyc->natid}}">
                                                    <img src="{{asset('client_por/'.$zwmb->proof_of_res)}}" alt="" width="275">
                                                </a>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
