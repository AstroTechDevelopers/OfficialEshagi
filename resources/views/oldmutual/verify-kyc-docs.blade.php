<?php
/**
 * Created by PhpStorm for eshagitwo
 * User: VinceGee
 * Date: 7/19/2022
 * Time: 7:27 AM
 */ ?>
@extends('layouts.app')

@section('template_title')
    Verify Musoni KYC for {{$kyc->natid}}
@endsection

@section('template_linked_css')
    <link href="{{asset('assets/libs/magnific-popup/magnific-popup.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('css/select2.min.css')}}" rel="stylesheet" />
    <!-- datepicker -->
    <link href="{{asset('assets/libs/air-datepicker/css/datepicker.min.css')}}" rel="stylesheet" type="text/css" />

    <link href="{{asset('assets/libs/bootstrap-touchspin/jquery.bootstrap-touchspin.min.css')}}" rel="stylesheet" />

@endsection

@section('content')
    <div class="page-title-box">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-lg-4">
                    <h4 class="page-title mb-1">Know Your Customer</h4>
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{url('/kycs')}}">KYCs</a></li>
                        <li class="breadcrumb-item active">Verify Musoni KYC: {{$kyc->natid}}</li>
                    </ol>
                </div>

                <div class="col-lg-8">
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
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="header-title">Musoni Customer Number: {{$client->reds_id ?? 'Not yet generated'}}</h4>

                            <p class="card-title-desc">KYC Last Update: {{$kyc->updated_at}} Credit Limit: ${{$client->cred_limit}}
                                <br>
                                <span class="text-info">KYC Added By: {{$client->creator}}</span></p>

                            <ul class="nav nav-tabs nav-justified nav-tabs-custom" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" data-toggle="tab" href="#custom-kycdocs" role="tab">
                                        <i class="fas fa-user-tag mr-1 align-middle"></i> <span class="d-none d-md-inline-block">KYC Documents</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#musoni-info" role="tab">
                                        <i class="fas fa-user mr-1 align-middle"></i> <span class="d-none d-md-inline-block">Musoni KYC</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#custom-details" role="tab">
                                        <i class="fas fa-user mr-1 align-middle"></i> <span class="d-none d-md-inline-block">Client Details</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#custom-kin" role="tab">
                                        <i class="fas fa-user-friends mr-1 align-middle"></i> <span class="d-none d-md-inline-block">Next Of Kin Details</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#custom-employ" role="tab">
                                        <i class="fas fa-user-tie mr-1 align-middle"></i> <span class="d-none d-md-inline-block">Employment Details</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#custom-address" role="tab">
                                        <i class="fas fa-address-book mr-1 align-middle"></i> <span class="d-none d-md-inline-block">Address Details</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#custom-banking" role="tab">
                                        <i class="fas fa-money-bill-wave mr-1 align-middle"></i> <span class="d-none d-md-inline-block">Banking Details</span>
                                    </a>
                                </li>
                            </ul>

                            <div class="tab-content p-3">
                                <div class="tab-pane active" id="custom-kycdocs" role="tabpanel">
                                    <form method="POST" action="{{ route('sendKycDocToMusoni') }}"  enctype="multipart/form-data">
                                        <input type="hidden" name="clientid" value="{{$client->reds_id}}">
                                        <input type="hidden" name="natid" value="{{$client->natid}}">
                                        <input type="hidden" name="loanid" value="{{$loanId}}">
                                        @csrf
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label for="national_pic">National ID</label>
                                                <div class="zoom-gallery">
                                                    <input type="file" name="natid" id="natid" accept="image/*" style="display: none" value="{{asset('nationalids/'.$kyc->national_pic)}}">
                                                    <a class="float-left" href="{{asset('nationalids/'.$kyc->national_pic)}}" title="{{$kyc->natid}}">
                                                        <img src="{{asset('nationalids/'.$kyc->national_pic)}}" alt="" width="275">
                                                    </a>
                                                </div>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="passport_pic">Passport Photo</label>
                                                <div class="zoom-gallery">
                                                    <input type="file" name="pphoto" id="pphoto" accept="image/*" style="display: none" value="{{asset('pphotos/'.$kyc->passport_pic)}}">

                                                    <a class="float-left" href="{{asset('pphotos/'.$kyc->passport_pic)}}" title="{{$kyc->natid}}">
                                                        <img src="{{asset('pphotos/'.$kyc->passport_pic)}}" alt="" width="275">
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label for="payslip_pic">Payslip</label>
                                                <div class="zoom-gallery">
                                                    <input type="file" name="payslip" id="payslip" accept="image/*" style="display: none" value="{{asset('payslips/'.$kyc->payslip_pic)}}">

                                                    <a class="float-left" href="{{asset('payslips/'.$kyc->payslip_pic)}}" title="{{$kyc->natid}}">
                                                        <img src="{{asset('payslips/'.$kyc->payslip_pic)}}" alt="" width="275">
                                                    </a>
                                                </div>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="proof_res">Proof of Residence</label>
                                                <div class="zoom-gallery">
                                                    <input type="file" name="proofres" id="proofres" accept="image/*" style="display: none" value="{{asset('proofres/'.$kyc->proofres_pic)}}">

                                                    <a class="float-left" href="{{asset('proofres/'.$kyc->proofres_pic)}}" title="{{$kyc->natid}}">
                                                        <img src="{{asset('proofres/'.$kyc->proofres_pic)}}" alt="" width="275">
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                        <br>
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <input class="btn btn-success btn-send" type="submit" value="Confirm & Upload to Musoni ">
                                            </div>

                                        </div>
                                    </form>
                                </div>
                                <div class="tab-pane" id="musoni-info" role="tabpanel">
                                        <div class="row">
                                            <div class="col-md-4 mb-3">
                                                <label for="business_type">Business Type</label>
                                                <select name="business_type" id="business_type" class="form-control" required style="width: 100%;">
                                                    <option value="{{$musoniKyc->business_type}}">{{$musoniKyc->business_type}}</option>
                                                    <option value="Manufacturing">Manufacturing</option>
                                                </select>
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <label for="business_start">Business Start</label>
                                                <input class="form-control datepicker-here{{ $errors->has('business_start') ? ' is-invalid' : '' }}" data-language="en" data-date-format="dd-mm-yyyy" type="text" name="business_start" id="business_start" value="{{ $musoniKyc->business_start }}" required="required" placeholder="Enter employer start date" autocomplete="off">

                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <label for="bus_address">Business Address</label>
                                                <input type="text" class="form-control" id="bus_address" name="bus_address" value="{{$musoniKyc->bus_address}}" placeholder="e.g. 1989 Main St" required>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-4 mb-3">
                                                <label for="bus_city">Business City</label>
                                                <input type="text" class="form-control" id="bus_city" name="bus_city" value="{{$musoniKyc->bus_city}}" placeholder="e.g. Harare" required>
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <label for="bus_country">Country of Operation</label>
                                                <input type="text" class="form-control" id="bus_country" name="bus_country" value="{{$musoniKyc->bus_country}}" placeholder="e.g. Zimbabwe" required>
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <label for="job_title">Job Title</label>
                                                <input type="text" class="form-control" id="job_title" name="job_title" value="{{$musoniKyc->job_title}}" placeholder="e.g. Teacher" required>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-4 mb-3">
                                                <label for="kin_address">Next of Kin Address</label>
                                                <input type="text" class="form-control" id="kin_address" name="kin_address" value="{{$musoniKyc->kin_address}}" placeholder="e.g. 332 My Home St, Hatfield" required>
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <label for="kin_city"> Next of Kin City</label>
                                                <input type="text" class="form-control" id="kin_city" name="kin_city" value="{{$musoniKyc->kin_city}}" placeholder="e.g. Harare" required>
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <label for="kin_relationship">Relationship to Kin</label>
                                                <select name="kin_relationship" id="kin_relationship" class="form-control" required style="width: 100%;">
                                                    <option value="5" {{ $musoniKyc->kin_relationship == '5' ? 'selected="selected"' : '' }}>Spouse</option>
                                                    <option value="6" {{ $musoniKyc->kin_relationship == '6' ? 'selected="selected"' : '' }}>Parent</option>
                                                    <option value="7" {{ $musoniKyc->kin_relationship == '7' ? 'selected="selected"' : '' }}>Sibling</option>
                                                    <option value="9" {{ $musoniKyc->kin_relationship == '9' ? 'selected="selected"' : '' }}>Other</option>
                                                    <option value="8" {{ $musoniKyc->kin_relationship == '8' ? 'selected="selected"' : '' }}>Business Associate</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-8 mb-3">
                                                <label for="notes">Any Additional Notes</label>
                                                <input type="text" class="form-control" id="notes" name="notes" {{$musoniKyc->notes}} placeholder="e.g. Client linked to 5 other members" >
                                            </div>
                                            <div class="col-md-4 mb-3">

                                            </div>

                                        </div>

                                </div>
                                <div class="tab-pane" id="custom-details" role="tabpanel">
                                    <div class="row">
                                        <div class="col-md-4 mb-3">
                                            <label for="first_name">First name</label>
                                            <input type="text" class="form-control" id="first_name" placeholder="e.g. John" value="{{$client->first_name}}" required>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label for="last_name">Last name</label>
                                            <input type="text" class="form-control" id="last_name" placeholder="e.g. Doe" value="{{$client->last_name}}" required>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label for="dob">Date of Birth</label>
                                            <input type="text" class="form-control" id="dob" placeholder="e.g. 1989-01-31" value="{{date_format($client->dob, 'Y-m-d')}}" required>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4 mb-3">
                                            <label for="gender">Gender</label>
                                            <input type="text" class="form-control" id="gender" placeholder="e.g. Male" value="{{$client->gender}}" required>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label for="marital_state">Marital Status</label>
                                            <input type="text" class="form-control" id="marital_state" placeholder="e.g. Single" value="{{$client->marital_state}}" required>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label for="natid">National ID Number</label>
                                            <input type="text" class="form-control" id="natid" placeholder="e.g. 63-2321066-F-71" value="{{$client->natid}}" required>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4 mb-3">
                                            <label for="mobile">Phone Number</label>
                                            <input type="text" class="form-control" id="mobile" placeholder="e.g. 773418009" value="+263{{$client->mobile}}" required>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label for="email">Email</label>
                                            <input type="text" class="form-control" id="email" placeholder="e.g. johndoe@gmail.com" value="{{$client->email}}">
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label for="salary">Salary</label>
                                            <input type="text" class="form-control" id="salary" placeholder="e.g. 66000.00" value="{{$client->salary}}" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane" id="custom-kin" role="tabpanel">
                                    <div class="row">
                                        <div class="col-md-4 mb-3">
                                            <label for="kin_fname">First name</label>
                                            <input type="text" class="form-control" id="kin_fname" placeholder="e.g. Jane" value="{{$kyc->kin_fname}}" required>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label for="kin_lname">Last name</label>
                                            <input type="text" class="form-control" id="kin_lname" placeholder="e.g. Smith" value="{{$kyc->kin_lname}}" required>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label for="kin_natid">National ID Number</label>
                                            <input type="text" class="form-control" id="kin_natid" placeholder="e.g. 33-2321066-F-71" value="{{$kyc->kin_natid}}" >
                                        </div>

                                    </div>
                                    <div class="row">
                                        <div class="col-md-4 mb-3">
                                            <label for="kin_number">Phone Number</label>
                                            <input type="text" class="form-control" id="kin_number" placeholder="e.g. 777418009" value="+263{{$kyc->kin_number}}" required>
                                        </div>

                                        <div class="col-md-4 mb-3">
                                            <label for="kin_work_number">Work Number</label>
                                            <input type="text" class="form-control" id="kin_work_number" placeholder="e.g. 242751953" value="{{$kyc->kin_work_number}}" >
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label for="kin_work">Employer</label>
                                            <input type="text" class="form-control" id="kin_work" placeholder="e.g. Google Inc" value="{{$kyc->kin_work}}" required>

                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane" id="custom-employ" role="tabpanel">
                                    <div class="row">
                                        <div class="col-md-4 mb-3">
                                            <label for="salary">Current Net Salary</label>
                                            <input type="text" class="form-control" id="salary" placeholder="e.g. 40000.00" value="{{$client->salary}}" required>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label for="gross">Gross Salary</label>
                                            <input type="text" class="form-control" id="gross" placeholder="e.g. 60000.00" value="{{$client->gross}}" >
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label for="employer">Employer</label>
                                            <input type="text" class="form-control" id="employer" placeholder="e.g. Ministry of Education" value="{{$client->employer}}" required>
                                        </div>

                                    </div>
                                    <div class="row">
                                        <div class="col-md-4 mb-3">
                                            <label for="work_contact">Contact Person</label>
                                            <input type="text" class="form-control" id="work_contact" placeholder="e.g. John Banda" value="{{$ssbInfo->hr_contact_name ?? ''}}" >
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label for="work_email">Employer Email</label>
                                            <input type="email" class="form-control" id="work_email" placeholder="e.g. info@gov.zw" value="{{$ssbInfo->hr_email ?? ''}}" >
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label for="ecnumber">EC Number</label>
                                            <input type="text" class="form-control" id="ecnumber" placeholder="e.g. 123456V" value="{{$client->ecnumber}}" >
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane" id="custom-address" role="tabpanel">
                                    <div class="row">
                                        <div class="col-md-4 mb-3">
                                            <label for="full_address">Current Address</label>
                                            <input type="text" class="form-control" id="full_address" placeholder="e.g. 2533 Crocodile Road, Tshovani" value="{{$client->house_num.', '.$client->street.', '.$client->surburb.', '.$client->city}}" required>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label for="province">Province</label>
                                            <input type="text" class="form-control" id="province" placeholder="e.g. Chiredzi" value="{{$client->province}}" >
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label for="country">Country</label>
                                            <input type="text" class="form-control" id="country" placeholder="e.g. Zimbabwe" value="{{$client->country}}">
                                        </div>

                                    </div>
                                    <div class="row">
                                        <div class="col-md-4 mb-3">
                                            <label for="home_type">Home Type</label>
                                            <input type="text" class="form-control" id="home_type" placeholder="e.g. Rental" value="{{$client->home_type}}" required>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                        </div>
                                        <div class="col-md-4 mb-3">
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane" id="custom-banking" role="tabpanel">
                                    <div class="row">
                                        <div class="col-md-4 mb-3">
                                            <label for="bank_acc_name">Bank Account Name</label>
                                            <input type="text" class="form-control" id="bank_acc_name" placeholder="e.g. John Doe" value="{{$kyc->bank_acc_name}}" required>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label for="acc_type">Account Type</label>
                                            <input type="text" class="form-control" id="acc_type" placeholder="e.g. Savings" value="{{$ssbInfo->accountType ?? ''}}" >
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label for="acc_number">Account Number</label>
                                            <input type="text" class="form-control" id="acc_number" placeholder="e.g. 112233445566" value="{{$kyc->acc_number}}" >
                                        </div>

                                    </div>
                                    <div class="row">
                                        <div class="col-md-4 mb-3">
                                            <label for="bank">Bank Name</label>
                                            <input type="text" class="form-control" id="bank" placeholder="e.g. NedBank" value="{{$bank->bank}}" required>
                                        </div>

                                        <div class="col-md-4 mb-3">
                                            <label for="branch">Branch</label>
                                            <input type="text" class="form-control" id="branch" placeholder="e.g. Jason Moyo" value="{{$kyc->branch}}" >
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label for="branch_code">Branch Code</label>
                                            <input type="text" class="form-control" id="branch_code" placeholder="e.g. JH777" value="{{$kyc->branch_code}}" >
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

@section('footer_scripts')
    <script src="{{asset('assets/libs/magnific-popup/jquery.magnific-popup.min.js')}}"></script>
    <script src="{{asset('assets/js/pages/lightbox.init.js')}}"></script>
    <script src="{{ asset('js/select2.min.js')}}"></script>
    <script src="{{asset('assets/libs/air-datepicker/js/datepicker.min.js')}}"></script>
    <script src="{{asset('assets/libs/air-datepicker/js/i18n/datepicker.en.js')}}"></script>
    <script src="{{asset('assets/libs/bootstrap-touchspin/jquery.bootstrap-touchspin.min.js')}}"></script>
    <script src="{{asset('assets/libs/bootstrap-maxlength/bootstrap-maxlength.min.js')}}"></script>


    <script type="text/javascript">
        $("#kin_relationship").select2({
            placeholder: 'Please select relationship to kin',
            allowClear:true,
        });

        $("#business_type").select2({
            placeholder: 'Please select business type',
            allowClear:true,
        });
    </script>

    <script>
        $("#business_start").datepicker({
            language: 'en',
        }).data('datepicker').selectDate(new Date($("#business_start").val()));
    </script>

@endsection
