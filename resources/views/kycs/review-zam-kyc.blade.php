<?php
/**
 * Created by PhpStorm for eshagitwo
 * User: VinceGee
 * Date: 5/5/2022
 * Time: 6:45 AM
 */ ?>
@extends('layouts.app')

@section('template_title')
    Review {{$client->nrc}} KYC
@endsection

@section('template_linked_css')
    <link href="{{asset('assets/libs/magnific-popup/magnific-popup.css')}}" rel="stylesheet" type="text/css" />
@endsection

@section('content')
    <div class="page-title-box">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-md-4">
                    <h4 class="page-title mb-1">Know Your Customer</h4>
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{url('/zam-pending-kycs')}}">KYCs</a></li>
                        <li class="breadcrumb-item active">Reviewing KYC for {{$client->nrc}}</li>
                    </ol>
                </div>

                <div class="col-md-8">
                    <div class="float-right d-none d-md-block">
                        <div class="float-right d-none d-md-block">
                            <div>
                                @if($client->officer_stat == true AND auth()->user()->hasRole('manager'))
                                    <a class="btn btn-success btn-rounded" href="{{url('post-loan-disk/'.$client->id)}}" type="button">
                                        <i class="mdi mdi-folder-upload mr-1"></i>Approve & Upload Borrower to Loan Disk
                                    </a>
                                @endif
                                {{--<a class="btn btn-success btn-rounded" href="{{url('post-kyc-crb/'.$client->id)}}" type="button">
                                    <i class="mdi mdi-folder-upload mr-1"></i>Send to CRB
                                </a>--}}

                            </div>
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
                            <p class="card-title-desc">KYC Last Update: {{$client->updated_at}}
                                <br>
                                <span class="text-info">KYC Added By: {{$client->creator}}</span>
                                @if($client->officer != null)
                                <br>
                                <span class="text-info">Loan Officer Approval: {{$client->officer}}</span>
                                @endif
                                @if($client->manager != null)
                                <br>
                                <span class="text-info">Manager Approval: {{$client->manager}}</span>
                                @endif
                            </p>


                            <ul class="nav nav-tabs nav-justified nav-tabs-custom" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" data-toggle="tab" href="#add-details" role="tab">
                                        <i class="fas fa-user-plus mr-1 align-middle"></i> <span class="d-none d-md-inline-block">Additional Info</span>
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
                                    <a class="nav-link" data-toggle="tab" href="#custom-banking" role="tab">
                                        <i class="fas fa-money-bill-wave mr-1 align-middle"></i> <span class="d-none d-md-inline-block">Banking Details</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#custom-kycdocs" role="tab">
                                        <i class="fas fa-user-tag mr-1 align-middle"></i> <span class="d-none d-md-inline-block">Documents</span>
                                    </a>
                                </li>
                            </ul>

                            <div class="tab-content p-3">
                                <div class="tab-pane active" id="add-details" role="tabpanel">
                                    <form method="POST" action="{{ route('approve.zamkyc') }}" enctype="multipart/form-data">
                                        @csrf
                                        <input type="hidden" name="clientid" value="{{$client->id}}">
                                        <input type="hidden" name="nrc" value="{{$client->nrc}}">

                                        <h2 class="title-h2"> Documents Upload</h2>
                                        <hr>
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <label>Signature photo</label> <br>
                                                <input type="file" name="sign_pic" id="sign_pic" accept="image/*" required />
                                            </div>
                                            <div class="col-lg-6">
                                                <p>1. Please upload a cropped image of the client's <strong>signature photo</strong>. <br>
                                                    2. Payslip photo should not be greater than 4MB. <br>
                                                    3. Payslip photo should of the format: jpeg,png,jpg,gif,svg. <br></p>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <label>CRB Record</label> <br>
                                                <input type="file" name="files" id="files" accept="application/pdf" />
                                            </div>
                                            <div class="col-lg-6">
                                                <p> 1. Please upload the CRB document relating to this borrower, in PDF. <br>
                                            </div>
                                        </div>
                                        <br>
                                        <br>
                                        <div class="row">
                                            <div class="col-lg-6">
                                                @if($client->officer_stat == false)
                                                    <input class="btn btn-success btn-send" type="submit" value="Upload & Approve KYC ">
                                                @endif

                                            </div>

                                        </div>
                                    </form>
                                </div>
                                <div class="tab-pane" id="custom-details" role="tabpanel">
                                    <div class="row">
                                        <div class="col-md-4 mb-3">
                                            <label for="first_name">First name</label>
                                            <input type="text" class="form-control" id="first_name" placeholder="e.g. John" value="{{$client->first_name}}" required>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label for="middle_name">Middle name</label>
                                            <input type="text" class="form-control" id="middle_name" placeholder="e.g. Joe" value="{{$client->middle_name}}" required>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label for="last_name">Last name</label>
                                            <input type="text" class="form-control" id="last_name" placeholder="e.g. Doe" value="{{$client->last_name}}" required>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4 mb-3">
                                            <label for="gender">Gender</label>
                                            <input type="text" class="form-control" id="gender" placeholder="e.g. Male" value="{{$client->gender}}" required>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label for="dob">Date of Birth</label>
                                            <input type="text" class="form-control" id="dob" placeholder="e.g. 1989-01-31" value="{{date_format($client->dob, 'Y-m-d')}}" required>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label for="nrc">National ID Number</label>
                                            <input type="text" class="form-control" id="nrc" placeholder="e.g. 632321/06/6" value="{{$client->nrc}}" required>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4 mb-3">
                                            <label for="mobile">Phone Number</label>
                                            <input type="text" class="form-control" id="mobile" placeholder="e.g. 773418009" value="+260{{$client->mobile}}" required>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label for="email">Email</label>
                                            <input type="text" class="form-control" id="email" placeholder="e.g. johndoe@gmail.com" value="{{$client->email}}">
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label for="landline">Landline</label>
                                            <input type="text" class="form-control" id="landline" placeholder="e.g. 8976776555" value="{{$client->landline}}" required>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4 mb-3">
                                            <label for="address">Current Address</label>
                                            <input type="text" class="form-control" id="address" placeholder="e.g. 2533 Crocodile Road, Tshovani" value="{{$client->address}}" required>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label for="city">City</label>
                                            <input type="text" class="form-control" id="city" placeholder="e.g. Lusaka" value="{{$client->city}}" >
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label for="province">Province</label>
                                            <input type="text" class="form-control" id="province" placeholder="e.g. Zimbabwe" value="{{$client->province}}">
                                        </div>

                                    </div>
                                </div>
                                <div class="tab-pane" id="custom-kin" role="tabpanel">
                                    <div class="row">
                                        <div class="col-md-4 mb-3">
                                            <label for="kin_name">Next of Kin</label>
                                            <input type="text" class="form-control" id="kin_name" placeholder="e.g. Jane Smith" value="{{$client->kin_name}}" required>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label for="kin_relationship">Kin Relationship</label>
                                            <input type="text" class="form-control" id="kin_relationship" placeholder="e.g. Spouse" value="{{$client->kin_relationship}}" required>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label for="kin_address">Kin Address</label>
                                            <input type="text" class="form-control" id="kin_address" placeholder="e.g. 12 Main St, Lusaka" value="{{$client->kin_address}}" >
                                        </div>

                                    </div>
                                    <div class="row">
                                        <div class="col-md-4 mb-3">
                                            <label for="kin_number">Phone Number</label>
                                            <input type="text" class="form-control" id="kin_number" placeholder="e.g. 777418009" value="+263{{$client->kin_number}}" required>
                                        </div>

                                        <div class="col-md-4 mb-3">
                                       </div>
                                        <div class="col-md-4 mb-3">

                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane" id="custom-employ" role="tabpanel">
                                    <div class="row">
                                        <div class="col-md-4 mb-3">
                                            <label for="business_employer">Employer</label>
                                            <input type="text" class="form-control" id="business_employer" placeholder="e.g. PMEC" value="{{$client->business_employer}}" required>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label for="institution">Institution</label>
                                            <input type="text" class="form-control" id="institution" placeholder="e.g. Leicester" value="{{$client->institution}}" required>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label for="ec_number">EC Number</label>
                                            <input type="text" class="form-control" id="ec_number" placeholder="e.g. 60000T0" value="{{$client->ec_number}}" >
                                        </div>


                                    </div>
                                    <div class="row">
                                        <div class="col-md-4 mb-3">
                                            <label for="department">Department</label>
                                            <input type="text" class="form-control" id="department" placeholder="e.g. John Banda" value="{{$client->department}}" >
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
                                            <label for="bank_name">Bank Name</label>
                                            <input type="text" class="form-control" id="bank_name" placeholder="e.g. ZANACO" value="{{$client->bank_name}}" required>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label for="bank_account">Bank Account Number</label>
                                            <input type="text" class="form-control" id="bank_account" placeholder="e.g. 112233445566" value="{{$client->bank_account}}" >
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label for="branch">Branch</label>
                                            <input type="text" class="form-control" id="branch" placeholder="e.g. Lusaka" value="{{$client->branch}}" >
                                        </div>

                                    </div>

                                </div>
                                <div class="tab-pane" id="custom-kycdocs" role="tabpanel">
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="national_pic">NRC Photo</label>
                                            <div class="zoom-gallery">
                                                <a class="float-left" href="{{asset('zam_nrcs/'.$client->nrc_pic)}}" title="{{$client->nrc}}">
                                                    <img src="{{asset('zam_nrcs/'.$client->nrc_pic)}}" alt="" width="275">
                                                </a>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="national_pic">Passport Photo</label>
                                            <div class="zoom-gallery">
                                                <a class="float-left" href="{{asset('zam_pphotos/'.$client->pass_photo)}}" title="{{$client->nrc}}">
                                                    <img src="{{asset('zam_pphotos/'.$client->pass_photo)}}" alt="" width="275">
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="national_pic">Payslip Photo</label>
                                            <div class="zoom-gallery">
                                                <a class="float-left" href="{{asset('zam_payslips/'.$client->pslip_pic)}}" title="{{$client->nrc}}">
                                                    <img src="{{asset('zam_payslips/'.$client->pslip_pic)}}" alt="" width="275">
                                                </a>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="national_pic">Proof of Residence Photo</label>
                                            <div class="zoom-gallery">
                                                <a class="float-left" href="{{asset('zam_pors/'.$client->por_pic)}}" title="{{$client->nrc}}">
                                                    <img src="{{asset('zam_pors/'.$client->por_pic)}}" alt="" width="275">
                                                </a>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="files">Other Document Files </label> <br>
                                            <a class="" href="{{asset('zam_crb_reports/'.$client->files)}}" target="_blank">{{$client->files}} </a>

                                        </div>
                                        <div class="col-md-6 mb-3">

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
@endsection
