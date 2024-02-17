<?php
/**
 * Created by PhpStorm for eshagitwo
 * User: VinceGee
 * Date: 6/5/2022
 * Time: 10:53 AM
 */ ?>

@extends('layouts.app')

@section('template_title')
    Loan Disk KYC Verification
@endsection

@section('content')
    <div class="page-title-box">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h4 class="page-title mb-1">KYCs</h4>
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">KYCs</a></li>
                        <li class="breadcrumb-item active">Loan Disk KYC Verification</li>
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
                            <h4 class="header-title">Check KYC Info From Loan Disk</h4>

                            <div class="card-body">
                                {!! Form::open(array('route' => 'ldKycChecker', 'method' => 'POST', 'role' => 'form', 'class' => 'needs-validation')) !!}

                                {!! csrf_field() !!}

                                <div class="form-group has-feedback row {{ $errors->has('uniqueid') ? ' has-error ' : '' }}">
                                    {!! Form::label('uniqueid', 'NRC', array('class' => 'col-md-3 control-label')); !!}
                                    <div class="col-md-9">
                                        <div class="input-group">
                                            <input class="form-control {{ $errors->has('uniqueid') ? ' is-invalid' : '' }}" type="text" autocapitalize="characters" maxlength="11" name="uniqueid" id="uniqueid" value="{{ old('uniqueid') }}" onkeyup="validateId()" required="required" pattern="^[0-9]{6}\/[0-9]{2}\/[0-9]{1}$" title="ID Format should be in the form of xxxxxx/xx/x" placeholder="Enter client NRC as it appears on client ID Card....">

                                        </div>
                                        @if ($errors->has('uniqueid'))
                                            <span class="help-block">
                                            <strong>{{ $errors->first('uniqueid') }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                </div>

                                {!! Form::button('Check Client Record', array('class' => 'btn btn-success margin-bottom-1 mb-1 float-right','type' => 'submit' )) !!}
                                {!! Form::close() !!}
                            </div>

                        </div>
                    </div>
                </div>
                @if(isset($response))
                    <div class="col-xl-7">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="header-title text-info">Client Record found on LoanDisk</h4>
                                {!! Form::open(array('route' => ['updateFromLoanDisk'], 'method' => 'POST', 'role' => 'form', 'enctype' => 'multipart/form-data', 'class' => 'needs-validation')) !!}

                                {!! csrf_field() !!}
                                    <dl class="user-info">
                                    <dt>
                                        <strong>Borrower ID</strong>
                                    </dt>
                                    <dd>
                                        {{ $response['borrower_id'] }}
                                        <input type="hidden" name="borrower_id" value="{{ $response['borrower_id'] }}">
                                    </dd>
                                    <hr>
                                    <dt>
                                        <strong>Borrower Country</strong>
                                    </dt>
                                    <dd>
                                        {{ $response['borrower_country'] }}
                                        <input type="hidden" name="borrower_country" value="{{ $response['borrower_country'] }}">
                                    </dd>
                                    <hr>

                                    <dt>
                                        <strong>First Name</strong>
                                    </dt>
                                    <dd>
                                        {{ $response['borrower_firstname'] }}
                                        <input type="hidden" name="borrower_firstname" value="{{ $response['borrower_firstname'] }}">
                                    </dd>
                                    <hr>
                                    <dt>
                                        <strong>Surname</strong>
                                    </dt>
                                    <dd>
                                        {{ $response['borrower_lastname'] }}
                                        <input type="hidden" name="borrower_lastname" value="{{ $response['borrower_lastname'] }}">
                                    </dd>
                                    <hr>
                                    <dt>
                                        <strong>Gender</strong>
                                    </dt>
                                    <dd>
                                        {{ $response['borrower_gender'] }}
                                        <input type="hidden" name="borrower_gender" value="{{ $response['borrower_gender'] }}">
                                    </dd>
                                    <hr>
                                    <dt>
                                        <strong>DOB</strong>
                                    </dt>
                                    <dd>
                                        {{ $response['borrower_dob'] }}
                                        <input type="hidden" name="borrower_dob" value="{{ $response['borrower_dob'] }}">
                                    </dd>
                                    <hr>
                                    <dt>
                                        <strong>NRC</strong>
                                    </dt>
                                    <dd>
                                        {{ $response['borrower_unique_number'] }}
                                        <input type="hidden" name="borrower_unique_number" value="{{ $response['borrower_unique_number'] }}">
                                    </dd>
                                    <hr>
                                    <dt>
                                        <strong>Address</strong>
                                    </dt>
                                    <dd>
                                        {{ $response['borrower_address'] }}
                                        <input type="hidden" name="borrower_address" value="{{ $response['borrower_address'] }}">
                                    </dd>
                                    <hr>
                                    <dt>
                                        <strong>Mobile Number</strong>
                                    </dt>
                                    <dd>
                                        {{ $response['borrower_mobile'] }}
                                        <input type="hidden" name="borrower_mobile" value="{{ $response['borrower_mobile'] }}">
                                    </dd>
                                    <hr>
                                    <dt>
                                        <strong>Business Name</strong>
                                    </dt>
                                    <dd>
                                        {{ $response['borrower_business_name'] }}
                                        <input type="hidden" name="borrower_business_name" value="{{ $response['borrower_business_name'] }}">
                                    </dd>
                                    <hr>
                                    <dt>
                                        <strong>Institution</strong>
                                    </dt>
                                    <dd>
                                        {{ $response['custom_field_11543'] ?? ''}}
                                        <input type="hidden" name="custom_field_11543" value="{{ $response['custom_field_11543'] ?? '' }}">
                                    </dd>
                                    <hr>
                                    <dt>
                                        <strong>EC Number</strong>
                                    </dt>
                                    <dd>
                                        {{ $response['custom_field_11302'] ?? ''}}
                                        <input type="hidden" name="custom_field_11302" value="{{ $response['custom_field_11302'] ?? ''}}">
                                    </dd>
                                    <hr>
                                    <dt>
                                        <strong>Department</strong>
                                    </dt>
                                    <dd>
                                        {{ $response['custom_field_11303'] ?? ''}}
                                        <input type="hidden" name="custom_field_11303" value="{{ $response['custom_field_11303'] ?? ''}}">
                                    </dd>
                                    <hr>
                                    <dt>
                                        <strong>Next of Kin Name</strong>
                                    </dt>
                                    <dd>
                                        {{ $response['custom_field_11085'] ?? ''}}
                                        <input type="hidden" name="custom_field_11085" value="{{ $response['custom_field_11085'] ?? ''}}">
                                    </dd>
                                    <hr>
                                    <dt>
                                        <strong>Relationship to Kin</strong>
                                    </dt>
                                    <dd>
                                        {{ $response['custom_field_11083'] ?? ''}}
                                        <input type="hidden" name="custom_field_11083" value="{{ $response['custom_field_11083'] ?? ''}}">
                                    </dd>
                                    <hr>
                                    <dt>
                                        <strong>Next of Kin Address</strong>
                                    </dt>
                                    <dd>
                                        {{ $response['custom_field_11082'] ?? ''}}
                                        <input type="hidden" name="custom_field_11082" value="{{ $response['custom_field_11082'] ?? ''}}">
                                    </dd>
                                    <hr>
                                    <dt>
                                        <strong>Next of Kin Number</strong>
                                    </dt>
                                    <dd>
                                        {{ $response['custom_field_11084'] ?? ''}}
                                        <input type="hidden" name="custom_field_11084" value="{{ $response['custom_field_11084'] ?? ''}}">
                                    </dd>
                                    <hr>
                                    <dt>
                                        <strong>Client Bank </strong>
                                    </dt>
                                    <dd>
                                        {{ $response['custom_field_11789'] ?? ''}}
                                        <input type="hidden" name="custom_field_11789" value="{{ $response['custom_field_11789'] ?? ''}}">
                                    </dd>
                                    <hr>
                                    <dt>
                                        <strong>Account Number</strong>
                                    </dt>
                                    <dd>
                                        {{ $response['custom_field_11788'] ?? ''}}
                                        <input type="hidden" name="custom_field_11788" value="{{ $response['custom_field_11788'] ?? ''}}">
                                    </dd>
                                    <hr>
                                    <dt>
                                        <strong>Bank Branch</strong>
                                    </dt>
                                    <dd>
                                        {{ $response['custom_field_11790'] ?? ''}}
                                        <input type="hidden" name="custom_field_11790" value="{{ $response['custom_field_11790'] ?? ''}}">
                                    </dd>
                                    <hr>

                                </dl>

                                {!! Form::button('Update eShagi Record', array('class' => 'btn btn-success margin-bottom-1 mb-1 float-right','type' => 'submit' )) !!}
                                {!! Form::close() !!}
                            </div>
                        </div>
                    </div>
                @endif

            </div>
        </div>
    </div>
@endsection

@section('footer_scripts')

    <script>
        function validateId() {
            var myId = document.getElementById("uniqueid").value;
            myId = myId.replace(/ /gi, "");
            myId = myId.replace(/\//gi, "");

            myId = insert(myId, "/", 6);
            myId = insert(myId, "/", myId.length - 1);

            document.getElementById("uniqueid").value = myId;
        }

        function insert(main_string, ins_string, pos) {
            if(typeof(pos) == "undefined") {
                pos = 0;
            }
            if(typeof(ins_string) == "undefined") {
                ins_string = '';
            }
            return main_string.slice(0, pos) + ins_string + main_string.slice(pos);
        }
    </script>

@endsection
