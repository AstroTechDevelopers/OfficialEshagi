<?php
/**
 * Created by PhpStorm for eshagitwo
 * User: VinceGee
 * Date: 5/4/2022
 * Time: 1:38 PM
 */ ?>

@extends('layouts.app')

@section('template_title')
    Update Client
@endsection

@section('template_linked_css')

    <link href="{{ asset('css/select2.min.css')}}" rel="stylesheet" />
    <!-- datepicker -->
    <link href="{{asset('assets/libs/air-datepicker/css/datepicker.min.css')}}" rel="stylesheet" type="text/css" />

    <link href="{{asset('assets/libs/bootstrap-touchspin/jquery.bootstrap-touchspin.min.css')}}" rel="stylesheet" />
@endsection

@section('content')
    <div class="page-title-box">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h4 class="page-title mb-1">Zambian Clients</h4>
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Zambian Clients</a></li>
                        <li class="breadcrumb-item active">Update Client</li>
                    </ol>
                </div>

                <div class="col-md-6">
                    <div class="float-right d-none d-md-block">
                        <div>
                            <h1 class="text-white"></h1>
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
                            <h4 class="header-title">Modifying details for {{$zambian->nrc}}</h4>
                            <!-- Nav tabs -->
                            <ul class="nav nav-tabs nav-justified" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" data-toggle="tab" href="#personal" role="tab">
                                        <i class="fas fa-user-cog mr-1"></i> <span class="d-none d-md-inline-block">Personal Info</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#custom-kycdocs" role="tab">
                                        <i class="fas fa-user-tag mr-1 align-middle"></i> <span class="d-none d-md-inline-block">KYC Documents</span>
                                    </a>
                                </li>
                            </ul>

                            <!-- Tab panes -->
                            <div class="tab-content p-3">
                                <div class="tab-pane active" id="personal" role="tabpanel">
                                    {!! Form::open(array('route' => ['zambians.update', $zambian->id], 'method' => 'PUT', 'role' => 'form', 'enctype' => 'multipart/form-data', 'class' => 'needs-validation')) !!}

                                    {!! csrf_field() !!}

                                    <div class="messag"></div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Select Title</label>
                                                <select class="form-control" id="title" name="title" required>
                                                    <option value="1" {{ $zambian->title == '1' ? 'selected="selected"' : '' }}>Mr. </option>
                                                    <option value="2" {{ $zambian->title == '2' ? 'selected="selected"' : '' }}>Mrs. </option>
                                                    <option value="3" {{ $zambian->title == '3' ? 'selected="selected"' : '' }}>Miss </option>
                                                    <option value="4" {{ $zambian->title == '4' ? 'selected="selected"' : '' }}>Ms. </option>
                                                    <option value="5" {{ $zambian->title == '5' ? 'selected="selected"' : '' }}>Dr. </option>
                                                    <option value="6" {{ $zambian->title == '6' ? 'selected="selected"' : '' }}>Prof. </option>
                                                    <option value="7" {{ $zambian->title == '7' ? 'selected="selected"' : '' }}>Rev. </option>
                                                </select>
                                                @if ($errors->has('title'))
                                                    <span class="invalid-feedback">
                                                        <strong>{{ $errors->first('title') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>First Name</label>
                                                <input class="form-control{{ $errors->has('first_name') ? ' is-invalid' : '' }}" type="text" name="first_name" id="first_name" value="{{ $zambian->first_name }}" required="required" placeholder="Enter client name...">
                                                @if ($errors->has('first_name'))
                                                    <span class="invalid-feedback">
                                                    <strong>{{ $errors->first('first_name') }}</strong>
                                                </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <!-- email input -->
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Middle Name</label>
                                                <input class="form-control{{ $errors->has('middle_name') ? ' is-invalid' : '' }}" type="text" name="middle_name" id="middle_name" value="{{ $zambian->middle_name }}" placeholder="Enter client middlename...">
                                                @if ($errors->has('middle_name'))
                                                    <span class="invalid-feedback">
                                                    <strong>{{ $errors->first('middle_name') }}</strong>
                                                </span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Surname</label>
                                                <input class="form-control{{ $errors->has('last_name') ? ' is-invalid' : '' }}" type="text" name="last_name" id="last_name" value="{{ $zambian->last_name }}" required="required" placeholder="Enter client surname...">
                                                @if ($errors->has('last_name'))
                                                    <span class="invalid-feedback">
                                                    <strong>{{ $errors->first('last_name') }}</strong>
                                                </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label> NRC </label>
                                                <input class="form-control {{ $errors->has('nrc') ? ' is-invalid' : '' }}" type="text" autocapitalize="characters" maxlength="11" name="nrc" id="nrc" value="{{ $zambian->nrc }}" onkeyup="validateId()" required="required" pattern="^[0-9]{6}\/[0-9]{2}\/[0-9]{1}$" title="ID Format should be in the form of xxxxxx/xx/x" placeholder="Enter client National ID as it appears on client ID Card....">
                                                <span class="text-info">e.g. 123456/78/9</span>
                                                @if ($errors->has('nrc'))
                                                    <span class="invalid-feedback">
                                                    <strong>{{ $errors->first('nrc') }}</strong>
                                                </span>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Mobile Number</label>
                                                <div class="input-group">
                                                    <input class="input-group-addon form-control col-lg-2" value="+260" id="countryCode" readonly>
                                                    <input class="form-control {{ $errors->has('mobile') ? ' is-invalid' : '' }}  col-lg-10" type="number" name="mobile" value="{{ $zambian->mobile }}" onkeyup="validateNumber()" maxlength="10" id="mobile" required="required" placeholder="EG. 775731858">
                                                    @if ($errors->has('mobile'))
                                                        <span class="invalid-feedback">
                                                    <strong>{{ $errors->first('mobile') }}</strong>
                                                </span>
                                                    @endif
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Email</label>
                                                <input class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" type="email" name="email" id="email" value="{{ $zambian->email }}" placeholder="Enter client email..." required>
                                                @if ($errors->has('email'))
                                                    <span class="invalid-feedback">
                                                    <strong>{{ $errors->first('email') }}</strong>
                                                </span>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Date of Birth</label>
                                                <input class="form-control datepicker-here{{ $errors->has('dob') ? ' is-invalid' : '' }}" data-language="en" data-date-format="dd-mm-yyyy" type="text" name="dob" id="dob" value="{{ $zambian->dob }}" required="required" autocomplete="off">
                                                @if ($errors->has('dob'))
                                                    <span class="invalid-feedback">
                                                    <strong>{{ $errors->first('dob') }}</strong>
                                                </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Select Gender</label>
                                                <select class="form-control" id="gender" name="gender" required>
                                                    <option value="Male" {{ $zambian->gender == 'Male' ? 'selected="selected"' : '' }}>Male</option>
                                                    <option value="Female" {{ $zambian->gender == 'Female' ? 'selected="selected"' : '' }}>Female</option>
                                                </select>
                                                @if ($errors->has('gender'))
                                                    <span class="invalid-feedback">
                                                        <strong>{{ $errors->first('gender') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <br>
                                    <div class="spacing_1">
                                        <h2 class="title-h2"> Address Details</h2>
                                        <hr>
                                    </div>
                                    <div class="row">

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Address</label>
                                                <input class="form-control{{ $errors->has('address') ? ' is-invalid' : '' }} " type="text" name="address" id="address" value="{{ $zambian->address }}" required="required" placeholder="Enter client address...">
                                                @if ($errors->has('address'))
                                                    <span class="invalid-feedback">
                                                        <strong>{{ $errors->first('address') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>City</label>
                                                <input class="form-control{{ $errors->has('city') ? ' is-invalid' : '' }}" type="text" maxlength="40" name="city" id="city" value="{{ $zambian->city }}" required="required" placeholder="Enter client city...">
                                                @if ($errors->has('city'))
                                                    <span class="invalid-feedback">
                                                        <strong>{{ $errors->first('city') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>

                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Province</label>
                                                <select class="form-control{{ $errors->has('province') ? ' is-invalid' : '' }}" name="province" id="province" required>
                                                    <option value="{{ old('province') }}">{{ old('province') }}</option>
                                                    @if ($provinces)
                                                        @foreach($provinces as $province)
                                                            <option value="{{ $province->province }}" {{ $zambian->province == $province->province ? 'selected="selected"' : '' }}>{{ $province->province }} </option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                                @if ($errors->has('province'))
                                                    <span class="invalid-feedback">
                                                        <strong>{{ $errors->first('province') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>ZIP Code</label>
                                                <input class="form-control{{ $errors->has('zip_code') ? ' is-invalid' : '' }}" type="text" maxlength="40" name="zip_code" id="zip_code" value="{{ $zambian->zip_code }}" placeholder="Enter client zipcode...">
                                                @if ($errors->has('zip_code'))
                                                    <span class="invalid-feedback">
                                                        <strong>{{ $errors->first('zip_code') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Landline</label>
                                                <input class="form-control{{ $errors->has('landline') ? ' is-invalid' : '' }}" type="text" name="landline" id="landline" value="{{ $zambian->landline }}" placeholder="Enter client landline...">
                                                @if ($errors->has('landline'))
                                                    <span class="invalid-feedback">
                                                    <strong>{{ $errors->first('landline') }}</strong>
                                                </span>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="col-md-6">

                                        </div>
                                    </div>

                                    <h2 class="title-h2"> Employment Details</h2>
                                    <hr>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label id="label">Employer/Business</label>
                                                <input name="business_employer" class="form-control{{ $errors->has('business_employer') ? ' is-invalid' : '' }}" id="business_employer" value="{{ $zambian->business_employer }}" placeholder="Enter client employer's name...">
                                                @if ($errors->has('business_employer'))
                                                    <span class="invalid-feedback">
                                                    <strong>{{ $errors->first('business_employer') }}</strong>
                                                </span>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>EC Number</label>
                                                <input class="form-control{{ $errors->has('ec_number') ? ' is-invalid' : '' }}" type="text" name="ec_number" id="ec_number" value="{{ $zambian->ec_number }}" placeholder="Enter client payroll ID...">
                                                @if ($errors->has('ec_number'))
                                                    <span class="invalid-feedback">
                                                    <strong>{{ $errors->first('ec_number') }}</strong>
                                                </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label id="label">Institution</label>
                                                <input name="institution" class="form-control{{ $errors->has('institution') ? ' is-invalid' : '' }}" id="institution" value="{{ $zambian->institution }}" placeholder="Enter client institution...">
                                                @if ($errors->has('institution'))
                                                    <span class="invalid-feedback">
                                                    <strong>{{ $errors->first('institution') }}</strong>
                                                </span>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Employment Status</label>
                                                <select class="form-control" id="work_status" name="work_status" required>
                                                    <option value="Employee" {{ $zambian->work_status == 'Employee' ? 'selected="selected"' : '' }}>Employee</option>
                                                    <option value="Government Employee" {{ $zambian->work_status == 'Government Employee' ? 'selected="selected"' : '' }}>Government Employee</option>
                                                    <option value="Private Sector Employee" {{ $zambian->work_status == 'Private Sector Employee' ? 'selected="selected"' : '' }}>Private Sector Employee</option>
                                                    <option value="Owner" {{ $zambian->work_status == 'Owner' ? 'selected="selected"' : '' }}>Owner</option>
                                                    <option value="Student" {{ $zambian->work_status == 'Student' ? 'selected="selected"' : '' }}>Student</option>
                                                    <option value="Overseas Worker" {{ $zambian->work_status == 'Overseas Worker' ? 'selected="selected"' : '' }}>Overseas Worker</option>
                                                    <option value="Pensioner" {{ $zambian->work_status == 'Pensioner' ? 'selected="selected"' : '' }}>Pensioner</option>
                                                    <option value="Unemployed" {{ $zambian->work_status == 'Unemployed' ? 'selected="selected"' : '' }}>Unemployed</option>
                                                </select>
                                                @if ($errors->has('work_status'))
                                                    <span class="invalid-feedback">
                                                    <strong>{{ $errors->first('work_status') }}</strong>
                                                </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label id="label">Credit Score</label>
                                                <input name="credit_score" class="form-control{{ $errors->has('credit_score') ? ' is-invalid' : '' }}" id="credit_score" value="{{ $zambian->credit_score }}" placeholder="Enter client credit score...">
                                                @if ($errors->has('credit_score'))
                                                    <span class="invalid-feedback">
                                                    <strong>{{ $errors->first('credit_score') }}</strong>
                                                </span>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label id="label">Department</label>
                                                <input name="department" class="form-control{{ $errors->has('department') ? ' is-invalid' : '' }}" id="department" value="{{ $zambian->department }}" placeholder="Enter client department...">
                                                @if ($errors->has('department'))
                                                    <span class="invalid-feedback">
                                                    <strong>{{ $errors->first('department') }}</strong>
                                                </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <h2 class="title-h2"> Next of Kin Details</h2>
                                    <hr>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Next of Kin</label>
                                                <input class="form-control{{ $errors->has('kin_name') ? ' is-invalid' : '' }}" value="{{ $zambian->kin_name }}" type="text" name="kin_name" id="kin_name" required="required" placeholder="Enter your next of kin's full name...">
                                                @if ($errors->has('kin_name'))
                                                    <span class="invalid-feedback">
                                                    <strong>{{ $errors->first('kin_name') }}</strong>
                                                </span>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Next of Kin Relationship</label>
                                                <input class="form-control{{ $errors->has('kin_relationship') ? ' is-invalid' : '' }}" value="{{ $zambian->kin_relationship }}" type="text" name="kin_relationship" id="kin_relationship" required="required" placeholder="Enter relationship to next of kin...">
                                                @if ($errors->has('kin_relationship'))
                                                    <span class="invalid-feedback">
                                                    <strong>{{ $errors->first('kin_relationship') }}</strong>
                                                </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Kin Address</label>
                                                <input class="form-control{{ $errors->has('kin_address') ? ' is-invalid' : '' }}" value="{{ $zambian->kin_address }}" type="text" name="kin_address" id="kin_address" required="required" placeholder="Enter your next of kin's address...">
                                                @if ($errors->has('kin_address'))
                                                    <span class="invalid-feedback">
                                                    <strong>{{ $errors->first('kin_address') }}</strong>
                                                </span>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Kin Number</label>
                                                <input class="form-control{{ $errors->has('kin_number') ? ' is-invalid' : '' }}" value="{{ $zambian->kin_number }}" type="text" name="kin_number" id="kin_number" required="required" placeholder="Enter your next of kin's number...">
                                                @if ($errors->has('kin_number'))
                                                    <span class="invalid-feedback">
                                                    <strong>{{ $errors->first('kin_number') }}</strong>
                                                </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <h2 class="title-h2"> Client Banking Details</h2>
                                    <hr>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Bank Name</label>
                                                <select class="form-control" type="text" name="bank_name" id="bank_name" style="width: 100%;" required>
                                                    @if ($banks)
                                                        @foreach($banks as $bank)
                                                            <option value="{{ $bank->bank }}" {{ $zambian->bank_name == $bank->bank ? 'selected="selected"' : '' }}>{{ $bank->bank }}</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Branch Name</label>
                                                <select name="branch" id="branch" class="form-control" required>
                                                    <option value="{{$zambian->branch}}">{{$zambian->branch}}</option>
                                                </select>
                                            </div>
                                        </div>

                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Bank Account Number</label>
                                                <input class="form-control{{ $errors->has('bank_account') ? ' is-invalid' : '' }}" type="text" name="bank_account" id="bank_account" value="{{ $zambian->bank_account }}" pattern='^\d{1,3}*(\.\d+)?$' data-type="currency" required="required" placeholder="Enter your account number...">

                                                @if ($errors->has('bank_account'))
                                                    <span class="invalid-feedback">
                                                    <strong>{{ $errors->first('bank_account') }}</strong>
                                                </span>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="col-md-6">

                                        </div>
                                    </div>

                                    {!! Form::button('Update Personal Info', array('class' => 'btn btn-success margin-bottom-1 mb-1 float-right','type' => 'submit' )) !!}
                                    {!! Form::close() !!}
                                </div>

                                <div class="tab-pane" id="custom-kycdocs" role="tabpanel">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label for="national_pic">NRC</label>
                                            <div class="zoom-gallery">
                                                <a href="{{asset('zam_nrcs/'.$zambian->nrc_pic)}}" title="{{$zambian->nrc}}">
                                                    <img src="{{asset('zam_nrcs/'.$zambian->nrc_pic)}}" alt="" width="275">
                                                </a>
                                            </div>
                                            <br>
                                            <form method="post" action="{{ route('uploadClientNrc') }}" enctype="multipart/form-data">
                                                @csrf
                                                <input type="hidden" name="clientnrc" value="{{$zambian->nrc}}">
                                                <input type="file" name="nrc" id="nrc" accept="image/*" />
                                                <button id="upId" class="btn btn-primary">Upload NRC</button>
                                            </form>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="passport_pic">Passport Photo</label>
                                            <div class="zoom-gallery">
                                                <a href="{{asset('zam_pphotos/'.$zambian->pass_photo)}}" title="{{$zambian->nrc}}">
                                                    <img src="{{asset('zam_pphotos/'.$zambian->pass_photo)}}" alt="" width="275">
                                                </a>
                                            </div>
                                            <br>
                                            <form method="post" action="{{ route('uploadZambiaPPhoto') }}" enctype="multipart/form-data">
                                                @csrf
                                                <input type="hidden" name="clientnrc" value="{{$zambian->nrc}}">
                                                <input type="file" name="passport" id="passport" accept="image/*" />
                                                <button id="upPhoto" class="btn btn-primary">Upload Passport Photo</button>
                                            </form>

                                        </div>
                                    </div>
                                    <br>
                                    <hr>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label for="payslip_pic">Payslip</label>
                                            <div class="zoom-gallery">
                                                <a href="{{asset('zam_payslips/'.$zambian->pslip_pic)}}" title="{{$zambian->nrc}}">
                                                    <img src="{{asset('zam_payslips/'.$zambian->pslip_pic)}}" alt="" width="275">
                                                </a>
                                            </div>
                                            <br>
                                            <form method="post" action="{{ route('uploadZambiapayslip') }}" enctype="multipart/form-data">
                                                @csrf
                                                <input type="hidden" name="clientnrc" value="{{$zambian->nrc}}">
                                                <input type="file" name="payslip" id="payslip" accept="image/*" />
                                                <button id="upPay" class="btn btn-primary">Upload Payslip</button>
                                            </form>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="proof_res">Proof of Residence</label>
                                            <div class="zoom-gallery">
                                                <a href="{{asset('zam_pors/'.$zambian->proofres_pic)}}" title="{{$zambian->nrc}}">
                                                    <img src="{{asset('zam_pors/'.$zambian->proofres_pic)}}" alt="" width="275">
                                                </a>
                                            </div>
                                            <br>
                                            <form method="post" action="{{ route('uploadZambiaPResidence') }}" enctype="multipart/form-data">
                                                @csrf
                                                <input type="hidden" name="clientnrc" value="{{$zambian->nrc}}">
                                                <input type="file" name="proofres" id="proofres" accept="image/*" />
                                                <button id="upRes" class="btn btn-primary">Upload Proof of Residence</button>
                                            </form>
                                        </div>
                                    </div>
                                    <br>
                                    <hr>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label for="crb_pic">CRB Document</label> <br>
                                            <a class="" href="{{asset('zam_crb_reports/'.$zambian->files)}}" target="_blank">{{$zambian->files}} </a>
                                            <br>
                                            <form method="post" action="{{ route('uploadZambiaCrbDoc') }}" enctype="multipart/form-data">
                                                @csrf
                                                <input type="hidden" name="clientnrc" value="{{$zambian->nrc}}">
                                                <input type="file" name="crb_pic" id="crb_pic" accept="application/pdf" />
                                                <button id="upPay" class="btn btn-primary">Upload Document</button>
                                            </form>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="signature">Signature</label>
                                            <div class="zoom-gallery">
                                                <a href="{{asset('zam_signs/'.$zambian->sign_pic)}}" title="{{$zambian->nrc}}">
                                                    <img src="{{asset('zam_signs/'.$zambian->sign_pic)}}" alt="" width="275">
                                                </a>
                                            </div>
                                            <br>
                                            <form method="post" action="{{ route('uploadZambiaSignature') }}" enctype="multipart/form-data">
                                                @csrf
                                                <input type="hidden" name="clientnrc" value="{{$zambian->nrc}}">
                                                <input type="file" name="signature" id="signature" accept="image/*" />
                                                <button id="upRes" class="btn btn-primary">Upload Signature</button>
                                            </form>
                                        </div>
                                        <br>
                                        <hr>
                                    </div>
                                    <br>
                                    <hr>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('footer_scripts')
    <script>

        function validateNumber(){
            var myLength=document.getElementById("mobile").value.length;
            var myNumber=document.getElementById("mobile").value;
            if(myLength >=10){
                document.getElementById("mobile").value=myNumber.substring(0, myNumber.length - 1);
            }
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

    <script>
        function validateId() {
            var myId = document.getElementById("nrc").value;
            myId = myId.replace(/ /gi, "");
            myId = myId.replace(/\//gi, "");

            myId = insert(myId, "/", 6);
            myId = insert(myId, "/", myId.length - 1);

            document.getElementById("nrc").value = myId;
        }
    </script>

    <script src="{{ asset('js/select2.min.js')}}"></script>
    <script src="{{asset('assets/libs/air-datepicker/js/datepicker.min.js')}}"></script>
    <script src="{{asset('assets/libs/air-datepicker/js/i18n/datepicker.en.js')}}"></script>
    <script src="{{asset('assets/libs/bootstrap-touchspin/jquery.bootstrap-touchspin.min.js')}}"></script>
    <script src="{{asset('assets/libs/bootstrap-maxlength/bootstrap-maxlength.min.js')}}"></script>

    <script>
        $("#dob").datepicker({
            language: 'en',
        }).data('datepicker').selectDate(new Date($("#dob").val()));
    </script>

    <script type="text/javascript">
        $("#title").select2({
            placeholder: 'Please select client title',
            allowClear:true,
        });

        $("#gender").select2({
            placeholder: 'Please select client gender',
            allowClear:true,
        });

        $("#marital_state").select2({
            placeholder: 'Please select client marital status',
            allowClear:true,
        });

        $("#nationality").select2({
            placeholder: 'Please select client nationality',
            allowClear:true,
        });

        $("#emp_sector").select2({
            placeholder: 'Please select client employment sector',
            allowClear:true,
        });

        $("#country").select2({
            placeholder: 'Please select your country',
            allowClear:true,
        }).change(function(){
            var id = $(this).val();
            var _token = $("input[name='_token']").val();
            if(id){
                $.ajax({
                    type:"get",
                    url:"{{url('/getProvinces')}}/"+id,
                    _token: _token ,
                    success:function(res) {
                        if(res) {
                            $("#province").empty();
                            $.each(res,function(key, value){
                                $("#province").append('<option value="">Please select a province</option>').append('<option value="'+value.province+'">'+value.province+'</option>');
                            });
                        }
                    }

                });
            }
        });

        $("#home_type").select2({
            placeholder: 'Please select client House ownership status',
            allowClear:true,
        });

        $("#province").select2({
            placeholder: 'Please select country then client province',
            allowClear:true,
        });

        $("#work_status").select2({
            placeholder: 'Please select a valid work status',
            allowClear:true,
        });

        $("#branch").select2({
            placeholder: 'Please select your bank branch name',
            allowClear:true,
        });

        $('#bank_name').select2({
            placeholder: 'Please select your bank',
            allowClear:true,
        }).change(function(){
            var id = $(this).val();
            var _token = $("input[name='_token']").val();
            if(id){
                $.ajax({
                    type:"get",
                    url:"{{url('/getBranches')}}/"+id,
                    _token: _token ,
                    success:function(res) {
                        if(res) {
                            $("#branch").empty();
                            $.each(res,function(key, value){
                                $("#branch").append('<option value="">Please select your bank branch name</option>').append('<option value="'+value.branch+'" data-price="'+value.branch_code+'">'+value.branch+'</option>');
                            });
                        }
                    }

                });
            }
        });

    </script>

    <script>

        $("input[data-type='currency']").on({
            keyup: function() {
                formatCurrency($(this));
            },
            blur: function() {
                formatCurrency($(this), "blur");
            }
        });


        function formatNumber(n) {
            return n.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, "")
        }


        function formatCurrency(input, blur) {

            var input_val = input.val();

            if (input_val === "") { return; }

            var original_len = input_val.length;

            var caret_pos = input.prop("selectionStart");

            input_val = formatNumber(input_val);

            if (blur === "blur") {
                input_val;
            }

            input.val(input_val);

            var updated_len = input_val.length;
            caret_pos = updated_len - original_len + caret_pos;
            input[0].setSelectionRange(caret_pos, caret_pos);
        }
    </script>
@endsection
