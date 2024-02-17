<?php
/**
 *Created by PhpStorm for eshagi
 *User: Vincent Guyo
 *Date: 11/13/2020
 *Time: 3:49 AM
 */

?>
@extends('layouts.app')

@section('template_title')
    Edit Client Info
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
                    <h4 class="page-title mb-1">Clients</h4>
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Clients</a></li>
                        <li class="breadcrumb-item active">Editing Client: {{$client->natid}}</li>
                    </ol>
                </div>

                <div class="col-md-6">
                    <div class="float-right d-none d-md-block">
                        <div>

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
                            <h4 class="header-title">Modifying details for {{$client->natid}}</h4>

                            <!-- Nav tabs -->
                            <ul class="nav nav-tabs nav-justified" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" data-toggle="tab" href="#personal" role="tab">
                                        <i class="fas fa-user-cog mr-1"></i> <span class="d-none d-md-inline-block">Personal Info</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#kycinfo" role="tab">
                                        <i class="fas fa-user mr-1"></i> <span class="d-none d-md-inline-block">KYC Info</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#custom-kycdocs" role="tab">
                                        <i class="fas fa-user-tag mr-1 align-middle"></i> <span class="d-none d-md-inline-block">KYC Documents</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#otherinfo" role="tab">
                                        <i class="fas fa-user-tag mr-1"></i> <span class="d-none d-md-inline-block">Other Info</span>
                                    </a>
                                </li>
                            </ul>

                            <!-- Tab panes -->
                            <div class="tab-content p-3">
                                <div class="tab-pane active" id="personal" role="tabpanel">
                                    {!! Form::open(array('route' => ['clients.update', $client->id], 'method' => 'PUT', 'role' => 'form', 'class' => 'needs-validation')) !!}

                                    {!! csrf_field() !!}
                                        <div class="messag"></div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Select Title</label>
                                                    <select class="form-control" id="title" name="title" required>
                                                        <option value="{{$client->title}}">{{ $client->title }}</option>
                                                        <option value="Mr">Mr</option>
                                                        <option value="Mrs">Mrs</option>
                                                        <option value="Ms">Ms</option>
                                                        <option value="Miss">Miss</option>
                                                        <option value="Dr">Dr</option>
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
                                                    <input class="form-control{{ $errors->has('first_name') ? ' is-invalid' : '' }}" type="text" name="first_name" id="first_name" value="{{$client->first_name}}" required="required" placeholder="Enter client name...">
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
                                                    <label>Surname</label>
                                                    <input class="form-control{{ $errors->has('last_name') ? ' is-invalid' : '' }}" type="text" name="last_name" id="last_name" value="{{ $client->last_name}}" required="required" placeholder="Enter client surname...">
                                                    @if ($errors->has('last_name'))
                                                        <span class="invalid-feedback">
                                                    <strong>{{ $errors->first('last_name') }}</strong>
                                                </span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label> National ID</label>
                                                    <input class="form-control  {{ $errors->has('natid') ? ' is-invalid' : '' }}" type="text" autocapitalize="characters" maxlength="15" name="natid" id="natid" value="{{ $client->natid}}" onkeyup="validateId()" required="required" pattern="^[0-9]{6}\/[0-9]{2}\/[0-9]{1}$" title="ID Format should be in the form of xxxxxx/xx/x" placeholder="Enter client National ID...">
                                                    @if ($errors->has('natid'))
                                                        <span class="invalid-feedback">
                                                    <strong>{{ $errors->first('natid') }}</strong>
                                                </span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Email</label>
                                                    <input class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" type="email" name="email" id="email" value="{{ $client->email }}" placeholder="Enter client email..." required>
                                                    @if ($errors->has('email'))
                                                        <span class="invalid-feedback">
                                                    <strong>{{ $errors->first('email') }}</strong>
                                                </span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Mobile Number</label>
                                                    <div class="input-group">
                                                        <input class="input-group-addon form-control col-lg-2" value="+263" id="countryCode" readonly>
                                                        <input class="form-control {{ $errors->has('mobile') ? ' is-invalid' : '' }}  col-lg-10" type="number" name="mobile" value="{{ $client->mobile }}" onkeyup="validateNumber()" maxlength="10" id="mobile" required="required" placeholder="EG. 775731858">
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
                                                    <label>Date of Birth</label>
                                                    <input class="form-control datepicker-here{{ $errors->has('dob') ? ' is-invalid' : '' }}" data-language="en" data-date-format="dd-mm-yyyy" type="text" name="dob" id="dob" value="{{ $client->dob }}" required="required" placeholder="Enter client date of birth..." autocomplete="off">
                                                    @if ($errors->has('dob'))
                                                        <span class="invalid-feedback">
                                                    <strong>{{ $errors->first('dob') }}</strong>
                                                </span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Select Gender</label>
                                                    <select class="form-control" id="gender" name="gender" required>
                                                        <option value="{{ $client->gender }}">{{ $client->gender }}</option>
                                                        <option value="Male">Male</option>
                                                        <option value="Female">Female</option>
                                                    </select>
                                                    @if ($errors->has('gender'))
                                                        <span class="invalid-feedback">
                                                        <strong>{{ $errors->first('gender') }}</strong>
                                                    </span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Select Marital Status</label>
                                                    <select class="form-control" id="marital_state" name="marital_state" required>
                                                        <option value="{{ $client->marital_state }}">{{ $client->marital_state }}</option>
                                                        <option value="Single">Single</option>
                                                        <option value="Married">Married</option>
                                                        <option value="Widowed">Widowed</option>
                                                        <option value="Divorced">Divorced</option>
                                                    </select>
                                                    @if ($errors->has('marital_state'))
                                                        <span class="invalid-feedback">
                                                        <strong>{{ $errors->first('marital_state') }}</strong>
                                                    </span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label> Number of Dependants</label>
                                                    <input class="form-control {{ $errors->has('dependants') ? ' is-invalid' : '' }}" type="number" max=10 name="dependants" id="dependants" value="{{ $client->dependants }}" required="required" placeholder="Enter client dependants...">
                                                    @if ($errors->has('dependants'))
                                                        <span class="invalid-feedback">
                                                    <strong>{{ $errors->first('dependants') }}</strong>
                                                </span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Nationality</label>
                                                    <select class="form-control" id="nationality" name="nationality" onchange="validateId1()">
                                                        <option value="{{ $client->nationality }}">{{ $client->nationality }}</option>
                                                        <option value="Zimbabwe">Zimbabwe</option>
                                                        <option value="other">Other</option>
                                                    </select>
                                                    @if ($errors->has('nationality'))
                                                        <span class="invalid-feedback">
                                                        <strong>{{ $errors->first('nationality') }}</strong>
                                                    </span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-md-6" id="otherNationality">
                                                <div class="form-group">
                                                    <label>Other Nationality</label>
                                                    <input class="form-control" type="text" maxlength="20" name="nationality1" id="nationality1" placeholder="Specify client Nationality...">

                                                </div>
                                            </div>
                                        </div>
                                        <br>
                                        <h2 class="title-h2"> Employment Details</h2>
                                        <hr>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Employment Sector</label>
                                                    <select class="form-control" id="emp_sector" name="emp_sector" required>
                                                        <option value="{{ $client->emp_sector }}">{{ $client->emp_sector }}</option>
                                                        <option value="Government">Government</option>
                                                        <option value="Private">Private Sector</option>
                                                        <option value="Informal">Informal Sector</option>
                                                    </select>
                                                    @if ($errors->has('emp_sector'))
                                                        <span class="invalid-feedback">
                                                        <strong>{{ $errors->first('emp_sector') }}</strong>
                                                    </span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label id="label">Employer Name</label>
                                                    <input name="employer" class="form-control{{ $errors->has('employer') ? ' is-invalid' : '' }}" id="employer" value="{{ $client->employer }}" placeholder="Enter client employer's name..." required>
                                                    @if ($errors->has('employer'))
                                                        <span class="invalid-feedback">
                                                    <strong>{{ $errors->first('employer') }}</strong>
                                                </span>
                                                @endif
                                                <!--<input class="form-control" type="text" name="employer_name" id="employer_name" required="required" placeholder="Enter client employer's name...">-->
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>EC Number/Payroll ID</label>
                                                    <input class="form-control{{ $errors->has('ecnumber') ? ' is-invalid' : '' }}" type="text" name="ecnumber" id="ecnumber" value="{{ $client->ecnumber }}" required="required" placeholder="Enter client payroll ID...">
                                                    @if ($errors->has('ecnumber'))
                                                        <span class="invalid-feedback">
                                                    <strong>{{ $errors->first('ecnumber') }}</strong>
                                                </span>
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Gross Monthly Salary</label>
                                                    <div class="input-group">
                                                        <input class="input-group-addon form-control col-lg-2" value="ZWL$" readonly>
                                                        <input class="form-control {{ $errors->has('gross') ? ' is-invalid' : '' }} col-lg-10" max="500000" name="gross" type="text" pattern="^\d{1,3}*(\.\d+)?$" value="{{ $client->gross }}" data-type="currency" id="gross" required placeholder="Enter client Gross Salary">
                                                        @if ($errors->has('gross'))
                                                            <span class="invalid-feedback">
                                                        <strong>{{ $errors->first('gross') }}</strong>
                                                    </span>
                                                        @endif
                                                    </div>


                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Net Monthly Salary</label>
                                                    <div class="input-group">
                                                        <input class="input-group-addon form-control col-lg-2" value="ZWL$" readonly>
                                                        <input class="form-control {{ $errors->has('salary') ? ' is-invalid' : '' }} col-lg-10" max="500000" name="salary" type="text" pattern="^\d{1,3}*(\.\d+)?$" value="{{ $client->salary }}" data-type="currency" id="salary" required placeholder="Enter client Net Salary">
                                                        @if ($errors->has('salary'))
                                                            <span class="invalid-feedback">
                                                        <strong>{{ $errors->first('salary') }}</strong>
                                                    </span>
                                                        @endif
                                                    </div>


                                                </div>
                                            </div>
                                        </div>

                                        <div class="spacing_1">
                                            <h2 class="title-h2"> Address Details</h2>
                                            <hr>
                                        </div>
                                        <div class="row">

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>House Number</label>
                                                    <input class="form-control{{ $errors->has('house_num') ? ' is-invalid' : '' }} " type="text" name="house_num" id="house_num" value="{{ $client->house_num }}" required="required" placeholder="Enter client house number...">
                                                    @if ($errors->has('house_num'))
                                                        <span class="invalid-feedback">
                                                        <strong>{{ $errors->first('house_num') }}</strong>
                                                    </span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Street Name</label>
                                                    <input class="form-control{{ $errors->has('street') ? ' is-invalid' : '' }}" type="text" maxlength="40" name="street" id="street" value="{{ $client->street }}" required="required" placeholder="Enter client street name...">
                                                    @if ($errors->has('street'))
                                                        <span class="invalid-feedback">
                                                        <strong>{{ $errors->first('street') }}</strong>
                                                    </span>
                                                    @endif
                                                </div>
                                            </div>

                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Surburb</label>
                                                    <input class="form-control{{ $errors->has('surburb') ? ' is-invalid' : '' }}" type="text" maxlength="40" name="surburb" id="surburb" value="{{ $client->surburb }}" required="required" placeholder="Enter client surburb...">
                                                    @if ($errors->has('surburb'))
                                                        <span class="invalid-feedback">
                                                        <strong>{{ $errors->first('surburb') }}</strong>
                                                    </span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>City</label>
                                                    <input class="form-control{{ $errors->has('city') ? ' is-invalid' : '' }}" type="text" maxlength="40" name="city" id="city" value="{{ $client->city }}" required="required" placeholder="Enter client city...">
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
                                                    <select class="form-control{{ $errors->has('province') ? ' is-invalid' : '' }}" type="text" name="province" id="province" required="required" >
                                                        <option value="{{ $client->province }}">{{ $client->province }}</option>
                                                        <option value="Harare">Harare</option>
                                                        <option value="Bulawayo">Bulawayo</option>
                                                        <option value="Manicaland">Manicaland</option>
                                                        <option value="Mashonaland Central">Mashonaland Central</option>
                                                        <option value="Mashonaland East">Mashonaland East</option>
                                                        <option value="Mashonaland West">Mashonaland West</option>
                                                        <option value="Masvingo">Masvingo</option>
                                                        <option value="Matabeleland North">Matabeleland North</option>
                                                        <option value="Matabeleland South">Matabeleland South</option>
                                                        <option value="Midlands">Midlands</option>
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
                                                    <label>Country</label>
                                                    <select class="form-control" id="country" name="country">
                                                        <option value="{{$client->country}}">{{$client->country}}</option>
                                                        <option value="Zimbabwe">Zimbabwe</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Occupation Type</label>
                                                    <select class="form-control" id="home_type" name="home_type" required>
                                                        <option value="{{ $client->home_type }}">{{ $client->home_type }}</option>
                                                        <option value="Owned">Owned</option>
                                                        <option value="Rented">Rented</option>
                                                        <option value="Mortgaged">Mortgaged</option>
                                                        <option value="Parents">Parents</option>
                                                        <option value="Employer Owned">Employer Owned</option>
                                                    </select>
                                                    @if ($errors->has('home_type'))
                                                        <span class="invalid-feedback">
                                                        <strong>{{ $errors->first('home_type') }}</strong>
                                                    </span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <hr>

                                    {!! Form::button('Update Personal Info', array('class' => 'btn btn-success margin-bottom-1 mb-1 float-right','type' => 'submit' )) !!}
                                    {!! Form::close() !!}
                                </div>
                                <div class="tab-pane" id="kycinfo" role="tabpanel">
                                    {!! Form::open(array('route' => ['kycs.update', $client->id], 'method' => 'PUT', 'role' => 'form', 'class' => 'needs-validation')) !!}

                                    {!! csrf_field() !!}
                                        <div class="spacing_1">
                                            <h2 class="title-h2"> Next of Kin Details</h2>
                                            <hr>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Select Title</label>
                                                    <select class="form-control" id="kin_title" name="kin_title" style="width: 100%;" required>
                                                        <option value="{{ $kyc->kin_title }}">{{ $kyc->kin_title }}</option>
                                                        <option value="Mr">Mr</option>
                                                        <option value="Mrs">Mrs</option>
                                                        <option value="Ms">Ms</option>
                                                        <option value="Miss">Miss</option>
                                                        <option value="Dr">Dr</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>First Name</label>
                                                    <input class="form-control{{ $errors->has('kin_fname') ? ' is-invalid' : '' }}" value="{{ $kyc->kin_fname }}" type="text" name="kin_fname" id="kin_fname" required="required" placeholder="Enter your next of kin's first name...">
                                                    @if ($errors->has('kin_fname'))
                                                        <span class="invalid-feedback">
                                                    <strong>{{ $errors->first('kin_fname') }}</strong>
                                                </span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <!-- email input -->
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Surname</label>
                                                    <input class="form-control{{ $errors->has('kin_lname') ? ' is-invalid' : '' }}" value="{{ $kyc->kin_lname }}" type="text" name="kin_lname" id="kin_lname" required="required" placeholder="Enter your next of kin's surname...">
                                                    @if ($errors->has('kin_lname'))
                                                        <span class="invalid-feedback">
                                                    <strong>{{ $errors->first('kin_lname') }}</strong>
                                                </span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Email</label>
                                                    <input class="form-control{{ $errors->has('kin_email') ? ' is-invalid' : '' }}" type="email" value="{{ $kyc->kin_email }}" name="kin_email" id="kin_email" placeholder="Enter your next of kin's email (optional)...">
                                                    @if ($errors->has('kin_email'))
                                                        <span class="invalid-feedback">
                                                    <strong>{{ $errors->first('kin_email') }}</strong>
                                                </span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Next of Kin Place of Work</label>
                                                    <input class="form-control{{ $errors->has('kin_work') ? ' is-invalid' : '' }}" type="text" name="kin_work" id="kin_work" value="{{ $kyc->kin_work }}" required="required" placeholder="Enter place of work for your next of kin...">
                                                    @if ($errors->has('kin_work'))
                                                        <span class="invalid-feedback">
                                                    <strong>{{ $errors->first('kin_work') }}</strong>
                                                </span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Next of Kin Mobile Number</label>
                                                    <div class="input-group">
                                                        <input class="input-group-addon form-control col-lg-2" value="+263" readonly>
                                                        <input class="form-control {{ $errors->has('kin_number') ? ' is-invalid' : '' }} col-lg-10" type="number" name="kin_number" value="{{ $kyc->kin_number }}" onkeyup="validateNumber()"  maxlength="10" id="kin_number" required="required" placeholder="EG. 775731858">
                                                        @if ($errors->has('kin_number'))
                                                            <span class="invalid-feedback">
                                                    <strong>{{ $errors->first('kin_number') }}</strong>
                                                </span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
										<div class="row">
											<div class="col-md-6">
												<div class="form-group">
													<label>Relationship With The Customer</label>
													<select class="form-control" id="relationship" name="relationship" onchange="verifyRelationship()" required>
														<option value="{{ $kyc->relationship }}">@if($kyc->relationship==='Wife') Spouse @else {{ $kyc->relationship }} @endif</option>
														<option value="{{ old('relationship') }}">{{ old('relationship') }}</option>
														<option value="Wife">Spouse</option>
														<option value="Mother">Mother</option>
														<option value="Father">Father</option>
														<option value="Daughter">Daughter</option>
														<option value="Son">Son</option>
														<option value="Uncle">Uncle</option>                                              
														<option value="Aunt">Aunt</option>
														<option value="Cousin">Cousin</option>
														<option value="Other">Other</option>
													</select>
													@if ($errors->has('relationship'))
														<span class="invalid-feedback">
																<strong>{{ $errors->first('relationship') }}</strong>
															</span>
													@endif
												</div>
											</div>
											<div class="col-md-6" id="otherRelationship">
												<div class="form-group">
													<label>Other Relationship</label>
													<input class="form-control" type="text" maxlength="20" name="relationship1" id="relationship1" placeholder="Your relationship with the customer...">
												</div>
											</div>
										</div>
										
										<div class="spacing_1">
											<h2 class="title-h2"> Address Details</h2>
											<hr>
										</div>
										<div class="row">

											<div class="col-md-6">
												<div class="form-group">
													<label>House Number</label>
													<input class="form-control{{ $errors->has('house_num') ? ' is-invalid' : '' }} " type="text" name="house_num" id="house_num" value="{{ $kyc->house_num }}" required="required" placeholder="Enter your house number...">
													@if ($errors->has('house_num'))
														<span class="invalid-feedback">
																<strong>{{ $errors->first('house_num') }}</strong>
															</span>
													@endif
												</div>
											</div>
											<div class="col-md-6">
												<div class="form-group">
													<label>Street Name</label>
													<input class="form-control{{ $errors->has('street') ? ' is-invalid' : '' }}" type="text" maxlength="40" name="street" id="street" value="{{ $kyc->street }}" required="required" placeholder="Enter your street name...">
													@if ($errors->has('street'))
														<span class="invalid-feedback">
																<strong>{{ $errors->first('street') }}</strong>
															</span>
													@endif
												</div>
											</div>

										</div>

										<div class="row">
											<div class="col-md-6">
												<div class="form-group">
													<label>Surburb</label>
													<input class="form-control{{ $errors->has('surburb') ? ' is-invalid' : '' }}" type="text" maxlength="40" name="surburb" id="surburb" value="{{ $kyc->surburb }}" required="required" placeholder="Enter your surburb...">
													@if ($errors->has('surburb'))
														<span class="invalid-feedback">
																<strong>{{ $errors->first('surburb') }}</strong>
															</span>
													@endif
												</div>
											</div>
											<div class="col-md-6">
												<div class="form-group">
													<label>City</label>
													<input class="form-control{{ $errors->has('city') ? ' is-invalid' : '' }}" type="text" maxlength="40" name="city" id="city" value="{{ $kyc->city }}" required="required" placeholder="Enter your city...">
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
													<select class="form-control{{ $errors->has('province') ? ' is-invalid' : '' }}" type="text" name="province" id="province" required="required" >
														<option value="{{ $kyc->province }}">{{ $kyc->province }}</option>
														<option value="Lusaka">Lusaka</option>
														<option value="Copperbelt">Copperbelt</option>
														<option value="Central">Central</option>
														<option value="Western">Western</option>
														<option value="Nothwestern">Nothwestern</option>
														<option value="Eastern">Eastern</option>
														<option value="Luapula">Luapula</option>
														<option value="Nothern">Nothern</option>
														<option value="Muchinaga">Muchinaga</option>
														<option value="Southern">Southern</option>
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
													<label>Country</label>
													<select class="form-control" id="country" name="country">
														<option value="Zambia">Zambia</option>
													</select>
												</div>
											</div>

											<div class="col-md-6">
												<div class="form-group">
													<label>Occupation Type</label>
													<select class="form-control" id="home_type" name="home_type" required>
														<option value="{{ $kyc->home_type }}">{{ $kyc->home_type }}</option>
														<option value="Owned">Owned</option>
														<option value="Rented">Rented</option>
														<option value="Mortgaged">Mortgaged</option>
														<option value="Parents">Parents</option>
														<option value="Employer Owned">Employer Owned</option>
													</select>
													@if ($errors->has('home_type'))
														<span class="invalid-feedback">
																<strong>{{ $errors->first('home_type') }}</strong>
															</span>
													@endif
												</div>
											</div>
											<div class="col-md-6">
												<div class="form-group">
													<label> Number of months stayed at current residence</label>
													<input class="form-control {{ $errors->has('resduration') ? ' is-invalid' : '' }}" type="number" name="resduration" id="resduration" value="{{ old('resduration') }}" required="required" placeholder="Enter number of months stayed at current residence...">
													@if ($errors->has('resduration'))
														<span class="invalid-feedback">
															<strong>{{ $errors->first('resduration') }}</strong>
														</span>
													@endif
												</div>
											</div>
										</div>

                                        <div class="spacing_1">
                                            <h2 class="title-h2"> Banking Details</h2>
                                            <hr>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Bank Name</label>
                                                    <select class="form-control" type="text" name="bank" id="bank" style="width: 100%;" required>
                                                        @if ($banks)
                                                            @foreach($banks as $bank)
                                                                <option value="{{ $bank->id }}" {{ $kyc->bank == $bank->id ? 'selected="selected"' : '' }}>{{ $bank->bank }}</option>
                                                            @endforeach
                                                        @endif
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Branch Name</label>
                                                    <select name="branch" id="branch" class="form-control" style="width: 100%;" required>
                                                        <option value="{{$kyc->branch}}">{{$kyc->branch}}</option>
                                                    </select>
                                                </div>
                                            </div>

                                        </div>
                                        <!-- message input -->
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Branch Code</label>
                                                    <input class="form-control{{ $errors->has('branch_code') ? ' is-invalid' : '' }}" type="text" name="branch_code" id="branch_code" required value="{{$kyc->branch_code}}" placeholder="Please select your branch" readonly>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Account Number</label>
                                                    <input class="form-control{{ $errors->has('acc_number') ? ' is-invalid' : '' }}" type="text" name="acc_number" id="acc_number" value="{{ $kyc->acc_number}}" pattern='^\d{1,3}*(\.\d+)?$' data-type="currency2" required="required" placeholder="Enter your account number...">
                                                    @if ($errors->has('acc_number'))
                                                        <span class="invalid-feedback">
                                                    <strong>{{ $errors->first('acc_number') }}</strong>
                                                </span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Bank Account Name</label>
                                                    <input class="form-control{{ $errors->has('bank_acc_name') ? ' is-invalid' : '' }}" type="text" name="bank_acc_name" id="bank_acc_name" required="required" value="{{$kyc->bank_acc_name}}" placeholder="Please confirm the name registered to the account...">
                                                    @if ($errors->has('bank_acc_name'))
                                                        <span class="invalid-feedback">
                                                    <strong>{{ $errors->first('bank_acc_name') }}</strong>
                                                </span>
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="col-md-6">

                                            </div>
                                        </div>

                                        <hr>
										<div class="row">
											<div class="col-lg-6">
												<label>Loan Purpose</label>
												<input type="text" class="form-control col-lg-10" name="loan_purpose" id="loan_purpose" placeholder="Enter loan purpose" >
											</div>
										</div>
										<hr>

                                    {!! Form::button('Update KYC Info', array('class' => 'btn btn-success margin-bottom-1 mb-1 float-right','type' => 'submit' )) !!}
                                    {!! Form::close() !!}
                                </div>
                                <div class="tab-pane" id="custom-kycdocs" role="tabpanel">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label for="national_pic">National ID Front</label>
                                            <div class="zoom-gallery">
                                                <a href="{{asset('nationalids/'.$kyc->national_pic)}}" title="{{$kyc->natid}}">
                                                    <img src="{{asset('nationalids/'.$kyc->national_pic)}}" alt="" width="275">
                                                </a>
                                            </div>

                                            <form method="post" action="{{ route('uploadClientNatID') }}" enctype="multipart/form-data">
                                                @csrf
                                                <input type="hidden" name="clientnationalid" value="{{$kyc->natid}}">
                                                    <input type="file" name="natid" id="natid" accept="image/*" />
                                                    <button id="upId" class="btn btn-primary">Upload National ID Front</button>
                                            </form>
                                            <br>
                                            <hr>
                                        </div>
										<div class="col-md-6">
                                            <label for="national_pic_back">National ID Back</label>
                                            <div class="zoom-gallery">
                                                <a href="{{asset('nationalids/'.$kyc->national_pic_back)}}" title="{{$kyc->natid}}">
                                                    <img src="{{asset('nationalids/'.$kyc->national_pic_back)}}" alt="" width="275">
                                                </a>
                                            </div>

                                            <form method="post" action="{{ route('uploadClientNatIDBack') }}" enctype="multipart/form-data">
                                                @csrf
                                                <input type="hidden" name="clientnationalid" value="{{$kyc->natid}}">
                                                    <input type="file" name="natidback" id="natidback" accept="image/*" />
                                                    <button id="upId" class="btn btn-primary">Upload National ID Back</button>
                                            </form>
                                            <br>
                                            <hr>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="passport_pic">Passport Photo</label>
                                            <div class="zoom-gallery">
                                                <a href="{{asset('pphotos/'.$kyc->passport_pic)}}" title="{{$kyc->natid}}">
                                                    <img src="{{asset('pphotos/'.$kyc->passport_pic)}}" alt="" width="275">
                                                </a>
                                            </div>

                                            <form method="post" action="{{ route('uploadClientPPhoto') }}" enctype="multipart/form-data">
                                                @csrf
                                                <input type="hidden" name="clientnationalid" value="{{$kyc->natid}}">
                                                    <input type="file" name="passport" id="passport" accept="image/*" />
                                                    <button id="upPhoto" class="btn btn-primary">Upload Passport Photo</button>
                                            </form>
                                            <br>
                                            <hr>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label for="payslip_pic">Payslip 1</label>
                                            <div class="zoom-gallery">
                                                <a href="{{asset('payslips/'.$kyc->payslip_pic)}}" title="{{$kyc->natid}}">
                                                    <img src="{{asset('payslips/'.$kyc->payslip_pic)}}" alt="" width="275">
                                                </a>
                                            </div><br />
											<label for="payslip_pic_2">Payslip 2</label><br />
											@if(empty($kyc->payslip_pic_2)) NA @else
											<a href="{{asset('payslips/'.$kyc->payslip_pic_2)}}" title="{{$kyc->natid}}">
												<img src="{{asset('payslips/'.$kyc->payslip_pic_2)}}" alt="" width="275">
											</a>@endif<br />
											<label for="payslip_pic_3">Payslip 3</label><br />
											@if(empty($kyc->payslip_pic_3)) NA @else
											<a href="{{asset('payslips/'.$kyc->payslip_pic_3)}}" title="{{$kyc->natid}}">
												<img src="{{asset('payslips/'.$kyc->payslip_pic_3)}}" alt="" width="275">
											</a>@endif<br />
											<label for="payslip_pic_4">Payslip 4</label><br />
											@if(empty($kyc->payslip_pic_4)) NA @else
											<a href="{{asset('payslips/'.$kyc->payslip_pic_4)}}" title="{{$kyc->natid}}">
												<img src="{{asset('payslips/'.$kyc->payslip_pic_4)}}" alt="" width="275">
											</a>@endif<br /><br />
											<label for="payslip_pic_2">Upload Payslips</label><br />
											<form method="post" action="{{ route('uploadClientpayslip') }}" enctype="multipart/form-data">
                                                @csrf
													<input type="hidden" name="clientnationalid" value="{{$kyc->natid}}">
                                                    <label for="payslip_pic_2">Pay Slip Page 1</label>&nbsp;&nbsp;<input type="file" name="payslip" id="payslip" accept="image/*" /><br /><br />
													<label for="payslip_pic_2">Pay Slip Page 2</label>&nbsp;&nbsp;<input type="file" name="payslip2" id="payslip2" accept="image/*" /><br /><br />
													<label for="payslip_pic_2">Pay Slip Page 3</label>&nbsp;&nbsp;<input type="file" name="payslip3" id="payslip3" accept="image/*" /><br /><br />
													<label for="payslip_pic_2">Pay Slip Page 4</label>&nbsp;&nbsp;<input type="file" name="payslip4" id="payslip4" accept="image/*" /><br /><br />                                            
                                                    <button id="upPay" class="btn btn-primary">Upload Payslip</button>
                                            </form>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="proof_res">Proof of Residence</label>
                                            <div class="zoom-gallery">
                                                <a href="{{asset('proofres/'.$kyc->proofres_pic)}}" title="{{$kyc->natid}}">
                                                    <img src="{{asset('proofres/'.$kyc->proofres_pic)}}" alt="" width="275">
                                                </a>
                                            </div>
                                            <form method="post" action="{{ route('uploadClientPResidence') }}" enctype="multipart/form-data">
                                                @csrf
                                                <input type="hidden" name="clientnationalid" value="{{$kyc->natid}}">
                                                <input type="file" name="proofres" id="proofres" accept="image/*" />
                                                <button id="upRes" class="btn btn-primary">Upload Proof of Residence</button>
                                            </form>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label for="payslip_pic">Employee Approval Letter</label>
                                            <div class="zoom-gallery">
                                                <a href="{{asset('empletters/'.$kyc->emp_approval_letter)}}" title="{{$kyc->natid}}">
                                                    <img src="{{asset('empletters/'.$kyc->emp_approval_letter)}}" alt="" width="275">
                                                </a>
                                            </div>
											<form method="post" action="{{ route('uploadClientEmpApproval') }}" enctype="multipart/form-data">
                                                @csrf
													<input type="hidden" name="clientnationalid" value="{{$kyc->natid}}">
                                                    <input type="file" name="emp_letter" id="emp_letter" accept="image/*" /><br /><br />                                            
                                                    <button id="upEmp" class="btn btn-primary">Upload Employee Approval Letter</button>
                                            </form>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="proof_res">Signature</label>
                                            <div class="zoom-gallery">
                                                <a href="{{asset('signatures/'.$kyc->sign_pic)}}" title="{{$kyc->natid}}">
                                                    <img src="{{asset('signatures/'.$kyc->sign_pic)}}" alt="" width="275">
                                                </a>
                                            </div>
                                            <form method="post" action="{{ route('uploadClientSignature') }}" enctype="multipart/form-data">
                                                @csrf
                                                <input type="hidden" name="clientnationalid" value="{{$kyc->natid}}">
                                                <input type="file" name="signature" id="signature" accept="image/*" />
                                                <button id="upRes" class="btn btn-primary">Upload Signature</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane" id="otherinfo" role="tabpanel">
                                    {!! Form::open(array('route' => ['ssbdetails.update', $client->natid], 'method' => 'PUT', 'role' => 'form', 'class' => 'needs-validation')) !!}

                                    {!! csrf_field() !!}

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Profession</label>
                                                <input class="form-control{{ $errors->has('profession') ? ' is-invalid' : '' }}" value="{{ $ssbDetail->profession }}" type="text" name="profession" id="profession" placeholder="Enter client profession...">
                                                @if ($errors->has('profession'))
                                                    <span class="invalid-feedback">
                                                    <strong>{{ $errors->first('profession') }}</strong>
                                                </span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Other Sources of Income</label>
                                                <input class="form-control{{ $errors->has('sourcesOfIncome') ? ' is-invalid' : '' }}" value="{{ $ssbDetail->sourcesOfIncome }}" type="text" name="sourcesOfIncome" id="sourcesOfIncome" placeholder="Enter other sources of income...">
                                                @if ($errors->has('sourcesOfIncome'))
                                                    <span class="invalid-feedback">
                                                    <strong>{{ $errors->first('sourcesOfIncome') }}</strong>
                                                </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <!-- email input -->
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>HR Contact Name</label>
                                                <input class="form-control{{ $errors->has('hr_contact_name') ? ' is-invalid' : '' }}" value="{{ $ssbDetail->hr_contact_name }}" type="text" name="hr_contact_name" id="hr_contact_name" placeholder="Enter HR Contact Person Name...">
                                                @if ($errors->has('hr_contact_name'))
                                                    <span class="invalid-feedback">
                                                    <strong>{{ $errors->first('hr_contact_name') }}</strong>
                                                </span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>HR Contact Job Title</label>
                                                <input class="form-control{{ $errors->has('hr_position') ? ' is-invalid' : '' }}" type="text" value="{{ $ssbDetail->hr_position }}" name="hr_position" id="hr_position" placeholder="Enter HR Contact person Job Title...">
                                                @if ($errors->has('hr_position'))
                                                    <span class="invalid-feedback">
                                                    <strong>{{ $errors->first('hr_position') }}</strong>
                                                </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>HR Email</label>
                                                <input class="form-control{{ $errors->has('hr_email') ? ' is-invalid' : '' }}" type="email" name="hr_email" id="hr_email" value="{{ $ssbDetail->hr_email }}"  placeholder="Enter HR email...">
                                                @if ($errors->has('hr_email'))
                                                    <span class="invalid-feedback">
                                                    <strong>{{ $errors->first('hr_email') }}</strong>
                                                </span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>HR Phone Number</label>
                                                <div class="input-group">
                                                    <input class="input-group-addon form-control col-lg-2" value="+263" readonly>
                                                    <input class="form-control {{ $errors->has('hr_telephone') ? ' is-invalid' : '' }} col-lg-10" type="number" name="hr_telephone" value="{{ $ssbDetail->hr_telephone }}" onkeyup="validateNumber()"  maxlength="10" id="hr_telephone"  placeholder="EG. 775731858">
                                                    @if ($errors->has('hr_telephone'))
                                                        <span class="invalid-feedback">
                                                    <strong>{{ $errors->first('hr_telephone') }}</strong>
                                                </span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="spacing_1">
                                        <h2 class="title-h2"> Other Details</h2>
                                        <hr>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>EC Number</label>
                                                <input class="form-control{{ $errors->has('ecnumber') ? ' is-invalid' : '' }}" type="text" name="ecnumber" id="ecnumber" value="{{ $ssbDetail->ecnumber}}" placeholder="Enter your EC number...">
                                                @if ($errors->has('ecnumber'))
                                                    <span class="invalid-feedback">
                                                    <strong>{{ $errors->first('ecnumber') }}</strong>
                                                </span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Payroll Area Code</label>
                                                <input class="form-control{{ $errors->has('payrollAreaCode') ? ' is-invalid' : '' }}" type="text" name="payrollAreaCode" id="payrollAreaCode" value="{{ $ssbDetail->payrollAreaCode}}" placeholder="Enter your Payroll Area Code...">
                                                @if ($errors->has('payrollAreaCode'))
                                                    <span class="invalid-feedback">
                                                    <strong>{{ $errors->first('payrollAreaCode') }}</strong>
                                                </span>
                                                @endif
                                            </div>
                                        </div>

                                    </div>
                                    <!-- message input -->
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Date Joined</label>
                                                <input class="form-control datepicker-here{{ $errors->has('dateJoined') ? ' is-invalid' : '' }}" data-language="en" data-date-format="dd-mm-yyyy" type="text" name="dateJoined" id="dateJoined" value="{{ $ssbDetail->dateJoined }}" placeholder="Enter date joined company..." autocomplete="off">
                                                @if ($errors->has('dateJoined'))
                                                    <span class="invalid-feedback">
                                                    <strong>{{ $errors->first('dateJoined') }}</strong>
                                                </span>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Account Type</label>
                                                <input class="form-control{{ $errors->has('accountType') ? ' is-invalid' : '' }}" type="text" name="accountType" id="accountType" value="{{ $ssbDetail->accountType}}"  placeholder="Enter your account type...">
                                                @if ($errors->has('accountType'))
                                                    <span class="invalid-feedback">
                                                    <strong>{{ $errors->first('accountType') }}</strong>
                                                </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Years With Current Bank</label>
                                                <input class="form-control{{ $errors->has('yearsWithCurrentBank') ? ' is-invalid' : '' }}" type="number" name="yearsWithCurrentBank" id="yearsWithCurrentBank" value="{{$ssbDetail->yearsWithCurrentBank}}" placeholder="Enter the years with current bank...">
                                                @if ($errors->has('yearsWithCurrentBank'))
                                                    <span class="invalid-feedback">
                                                    <strong>{{ $errors->first('yearsWithCurrentBank') }}</strong>
                                                </span>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Spouse Employer</label>
                                                <input class="form-control{{ $errors->has('spouseEmployer') ? ' is-invalid' : '' }}" type="text" name="spouseEmployer" id="spouseEmployer" value="{{$ssbDetail->spouseEmployer}}" placeholder="Enter the spouse employer...">
                                                @if ($errors->has('spouseEmployer'))
                                                    <span class="invalid-feedback">
                                                    <strong>{{ $errors->first('spouseEmployer') }}</strong>
                                                </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Spouse Name</label>
                                                <input class="form-control{{ $errors->has('spouseName') ? ' is-invalid' : '' }}" type="text" name="spouseName" id="spouseName" value="{{$ssbDetail->spouseName}}" placeholder="Enter the name of spouse...">
                                                @if ($errors->has('spouseName'))
                                                    <span class="invalid-feedback">
                                                    <strong>{{ $errors->first('spouseName') }}</strong>
                                                </span>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Spouse Phone Number</label>
                                                <div class="input-group">
                                                <input class="input-group-addon form-control col-lg-2" value="+263" id="countryCode" readonly>
                                                <input class="form-control{{ $errors->has('spousePhoneNumber') ? ' is-invalid' : '' }} col-lg-10" type="number" name="spousePhoneNumber" id="spousePhoneNumber" onkeyup="validateNumber()" maxlength="10" value="{{$ssbDetail->spousePhoneNumber}}" placeholder="EG. 775731858">
                                                @if ($errors->has('spousePhoneNumber'))
                                                    <span class="invalid-feedback">
                                                    <strong>{{ $errors->first('spousePhoneNumber') }}</strong>
                                                </span>
                                                @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <hr>

                                    {!! Form::button('Update Other Info', array('class' => 'btn btn-success margin-bottom-1 mb-1 float-right','type' => 'submit' )) !!}
                                    {!! Form::close() !!}
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
        document.getElementById("otherNationality").style.visibility = "hidden";

        function validateId1(){
            if(document.getElementById("nationality").value=="other") {
                document.getElementById("otherNationality").style.visibility = "visible";
            } else {
                document.getElementById("otherNationality").style.visibility = "hidden";
                document.getElementById("nationality1").value = document.getElementById("nationality").value;
            }
        }
        function validateId(){
            var myId=document.getElementById("natid").value;
            myId = myId.replace(/ /gi, "");
            myId = myId.replace(/\//gi, "");

            myId = insert(myId, "/", 6);
            myId = insert(myId, "/", myId.length - 1);

            document.getElementById("natid").value=myId;
        }

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
            placeholder: 'Please select client country',
            allowClear:true,
        });

        $("#home_type").select2({
            placeholder: 'Please select client House ownership status',
            allowClear:true,
        });

        $("#province").select2({
            placeholder: 'Please select client province',
            allowClear:true,
        });

    </script>

    <script src="{{ asset('js/select2.min.js')}}"></script>
    <script>
        function validateNumber(){
            var myLength=document.getElementById("kin_number").value.length;
            var myNumber=document.getElementById("kin_number").value;
            if(myLength >=10){
                document.getElementById("kin_number").value=myNumber.substring(0, myNumber.length - 1);
            }
        }
    </script>

    <script type="text/javascript">
	    document.getElementById("otherRelationship").style.visibility = "hidden";

		function verifyRelationship(){
			if(document.getElementById("relationship").value=="other") {
				document.getElementById("otherRelationship").style.visibility = "visible";
			} else {
				document.getElementById("otherRelationship").style.visibility = "hidden";
				document.getElementById("relationship").value = document.getElementById("relationship").value;
			}
		}
	    
        $("#kin_title").select2({
            placeholder: 'Please select client next of kin\'s title',
            allowClear:true,
        });

        $("#branch").select2({
            placeholder: 'Please select client bank branch name',
            allowClear:true,
        }).change(function(){
            var price = $(this).children('option:selected').data('price');
            $('#branch_code').val(price);
        });

        $('#bank').select2({
            placeholder: 'Please select client bank',
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

        $("#dateJoined").datepicker({
            language: 'en',
        }).data('datepicker').selectDate(new Date($("#dateJoined").val()));

    </script>

    <script>

        $("input[data-type='currency2']").on({
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
            // format number 1000000 to 1,234,567
            return n.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, "")
        }


        function formatCurrency(input, blur) {
            // appends $ to value, validates decimal side
            // and puts cursor back in right position.

            // get input value
            var input_val = input.val();

            // don't validate empty input
            if (input_val === "") { return; }

            // original length
            var original_len = input_val.length;

            // initial caret position
            var caret_pos = input.prop("selectionStart");

            // check for decimal
            if (input_val.indexOf(".") >= 0) {

                // get position of first decimal
                // this prevents multiple decimals from
                // being entered
                var decimal_pos = input_val.indexOf(".");

                // split number by decimal point
                var left_side = input_val.substring(0, decimal_pos);
                var right_side = input_val.substring(decimal_pos);

                // add commas to left side of number
                left_side = formatNumber(left_side);

                // validate right side
                right_side = formatNumber(right_side);

                // On blur make sure 2 numbers after decimal
                if (blur === "blur") {
                    right_side += "00";
                }

                // Limit decimal to only 2 digits
                right_side = right_side.substring(0, 2);

                // join number by .
                input_val = left_side + "." + right_side;

            } else {
                // no decimal entered
                // add commas to number
                // remove all non-digits
                input_val = formatNumber(input_val);
                //input_val = input_val;

                // final formatting
                if (blur === "blur") {
                    input_val += ".00";
                }
            }

            // send updated string to input
            input.val(input_val);

            // put caret back in the right position
            var updated_len = input_val.length;
            caret_pos = updated_len - original_len + caret_pos;
            input[0].setSelectionRange(caret_pos, caret_pos);
        }
    </script>
@endsection
