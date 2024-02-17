<?php
/**
 * Created by PhpStorm for eshagitwo
 * User: VinceGee
 * Date: 12/5/2021
 * Time: 7:32 AM
 */ ?>

@extends('layouts.app')

@section('template_title')
    Review {{$kyc->natid}} KYC
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
                        <li class="breadcrumb-item"><a href="{{url('/kycs')}}">KYCs</a></li>
                        <li class="breadcrumb-item active">Reviewing Partner KYC for {{$kyc->natid}}</li>
                    </ol>
                </div>

                <div class="col-md-8">
                    <div class="float-right d-none d-md-block">
                        @if($kyc->loan_officer == false AND auth()->user()->hasRole('salesadmin') || auth()->user()->hasRole('loansofficer'))
                            <div class="float-right d-none d-md-block">
                                <div>
                                    <a class="btn btn-success btn-rounded" href="{{url('approve-partner-kyc/'.$user->id)}}" type="button">
                                        <i class="mdi mdi-folder-upload mr-1"></i>Approve KYC
                                    </a>
                                </div>
                            </div>
                        @endif

                        @if($kyc->loan_officer == true AND auth()->user()->hasRole('manager') AND $kyc->manager == false)
                            <div class="float-right d-none d-md-block">
                                <div>
                                    <a class="btn btn-success btn-rounded" href="{{url('approve-partner-kyc/'.$user->id)}}" type="button">
                                        <i class="mdi mdi-folder-upload mr-1"></i>Approve KYC
                                    </a>
                                </div>
                            </div>
                        @endif

                        <div class="float-right d-none d-md-block">
                            <div>

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
                            <p class="card-title-desc">KYC Last Update: {{$kyc->updated_at}}
                                <br>
                                @if($kyc->loan_officer == true)
                                    <span class="text-info">Approving Loan Officer: {{$kyc->approver}}</span>
                                    <br>
                                @endif
                                @if($kyc->manager == true)
                                    <span class="text-info">Approving Manager: {{$kyc->manager_approver}}</span>
                                    <br>
                                @endif
                            </p>

                            <ul class="nav nav-tabs nav-justified nav-tabs-custom" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" data-toggle="tab" href="#partner-details" role="tab">
                                        <i class="fas fa-user mr-1 align-middle"></i> <span class="d-none d-md-inline-block">Partner Details</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#company-details" role="tab">
                                        <i class="fas fa-address-book mr-1 align-middle"></i> <span class="d-none d-md-inline-block">Company Details</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#partner-kycdocs" role="tab">
                                        <i class="fas fa-user-tag mr-1 align-middle"></i> <span class="d-none d-md-inline-block">Partner KYC Documents</span>
                                    </a>
                                </li>
                            </ul>

                            <div class="tab-content p-3">
                                <div class="tab-pane active" id="partner-details" role="tabpanel">
                                    <div class="row">
                                        <div class="col-md-4 mb-3">
                                            <label for="first_name">Partner </label>
                                            <input type="text" class="form-control" id="partner_name" placeholder="e.g. John LLC" value="{{$partner->partner_name}}" required>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label for="last_name">Partner Type</label>
                                            <input type="text" class="form-control" id="partner_type" placeholder="e.g. Merchant" value="{{$partner->partner_type}}" required>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label for="dob">Merchant Name (Trading Name)</label>
                                            <input type="text" class="form-control" id="merchantname" placeholder="e.g. Eagle Kings" value="{{$partner->merchantname}}" required>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4 mb-3">
                                            <label for="gender">Business Type</label>
                                            <input type="text" class="form-control" id="business_type" placeholder="e.g. Transportation" value="{{$partner->business_type}}" required>
                                        </div>
                                        <div class="col-md-8 mb-3">
                                            <label for="partnerDesc">Business Nature</label>
                                            <input type="text" class="form-control" id="partnerDesc" placeholder="e.g. Local and international Transportation" value="{{$partner->partnerDesc}}" required>
                                        </div>

                                    </div>
                                    <div class="row">
                                        <div class="col-md-4 mb-3">
                                            <label for="yearsTrading">Years Trading</label>
                                            <input type="text" class="form-control" id="yearsTrading" placeholder="e.g. 7" value="{{$partner->yearsTrading}}" required>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label for="regNumber">Company Reg Number</label>
                                            <input type="text" class="form-control" id="regNumber" placeholder="e.g. 48977YHG" value="{{$partner->regNumber}}">
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label for="bpNumber">Business Partner Number</label>
                                            <input type="text" class="form-control" id="bpNumber" placeholder="e.g. 66000" value="{{$partner->bpNumber}}" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane" id="company-details" role="tabpanel">
                                    <div class="row">
                                        <div class="col-md-4 mb-3">
                                            <label for="full_address">Current Address</label>
                                            <input type="text" class="form-control" id="full_address" placeholder="e.g. 2533 Crocodile Road, Tshovani" value="{{$partner->propNumber.', '.$partner->street.', '.$partner->surburb.', '.$partner->city}}" required>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label for="province">Province</label>
                                            <input type="text" class="form-control" id="province" placeholder="e.g. Chiredzi" value="{{$partner->province}}" >
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label for="country">Country</label>
                                            <input type="text" class="form-control" id="country" placeholder="e.g. Zimbabwe" value="{{$partner->country}}">
                                        </div>

                                    </div>
                                    <div class="row">
                                        <div class="col-md-4 mb-3">
                                            <label for="cfullname">Contact Person</label>
                                            <input type="text" class="form-control" id="cfullname" placeholder="e.g. Jane Smith Doe" value="{{$partner->cfullname}}" >
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label for="cdesignation">Designation</label>
                                            <input type="text" class="form-control" id="cdesignation" placeholder="e.g. CEO" value="{{$partner->cdesignation}}" >
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label for="telephoneNo">Contact Person Mobile</label>
                                            <input type="text" class="form-control" id="telephoneNo" placeholder="e.g. 0771418009" value="{{$partner->telephoneNo}}">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4 mb-3">
                                            <label for="cemail">Contact Email</label>
                                            <input type="email" class="form-control" id="cemail" placeholder="e.g. jpsmith@gmail.com" value="{{$partner->cemail}}" required>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label for="bank">Bank</label>
                                            <input type="text" class="form-control" id="bank" placeholder="e.g. Stanbic" value="{{$bank->bank}}" required>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label for="branch">Bank Branch</label>
                                            <input type="text" class="form-control" id="branch" placeholder="e.g. Julius Nyerere" value="{{$partner->branch}}" required>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4 mb-3">
                                            <label for="branch_code">Branch Code</label>
                                            <input type="text" class="form-control" id="branch_code" placeholder="e.g. 68768" value="{{$partner->branch_code}}" required>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label for="acc_number">Account Number</label>
                                            <input type="text" class="form-control" id="acc_number" placeholder="e.g. 234566756543456" value="{{$partner->acc_number}}" required>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane" id="partner-kycdocs" role="tabpanel">
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="cert_incorp">Certificate of Incorporation</label>
                                            <div class="zoom-gallery">
                                                <a class="float-left" href="{{asset($kyc->cert_incorp)}}" title="{{$kyc->natid}}">
                                                    <img src="{{asset($kyc->cert_incorp)}}" alt="" width="275">
                                                </a>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="bus_licence">Business Licence</label>
                                            <div class="zoom-gallery">
                                                <a class="float-left" href="{{asset($kyc->bus_licence)}}" title="{{$kyc->natid}}">
                                                    <img src="{{asset($kyc->bus_licence)}}" alt="" width="275">
                                                </a>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="national_id1">National ID #1</label>
                                            <div class="zoom-gallery">
                                                <a class="float-left" href="{{asset($kyc->national_id1)}}" title="{{$kyc->natid}}">
                                                    <img src="{{asset($kyc->national_id1)}}" alt="" width="275">
                                                </a>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="national_id2">National ID #2</label>
                                            <div class="zoom-gallery">
                                                <a class="float-left" href="{{asset($kyc->national_id2)}}" title="{{$kyc->natid}}">
                                                    <img src="{{asset($kyc->national_id2)}}" alt="" width="275">
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="pphoto1">Passport Photo #1</label>
                                            <div class="zoom-gallery">
                                                <a class="float-left" href="{{asset($kyc->pphoto1)}}" title="{{$kyc->natid}}">
                                                    <img src="{{asset($kyc->pphoto1)}}" alt="" width="275">
                                                </a>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="pphoto2">Passport Photo #2</label>
                                            <div class="zoom-gallery">
                                                <a class="float-left" href="{{asset($kyc->pphoto2)}}" title="{{$kyc->natid}}">
                                                    <img src="{{asset($kyc->pphoto2)}}" alt="" width="275">
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="cr6">CR6</label>
                                            <div class="zoom-gallery">
                                                <a class="float-left" href="{{asset($kyc->cr6)}}" title="{{$kyc->natid}}">
                                                    <img src="{{asset($kyc->cr6)}}" alt="" width="275">
                                                </a>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="cr14">CR14</label>
                                            <div class="zoom-gallery">
                                                <a class="float-left" href="{{asset($kyc->cr14)}}" title="{{$kyc->natid}}">
                                                    <img src="{{asset($kyc->cr14)}}" alt="" width="275">
                                                </a>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="proof_of_res">Proof of Residence</label>
                                            <div class="zoom-gallery">
                                                <a class="float-left" href="{{asset($kyc->proof_of_res)}}" title="{{$kyc->natid}}">
                                                    <img src="{{asset($kyc->proof_of_res)}}" alt="" width="275">
                                                </a>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="proof_of_res">Other KYC Document</label>
                                            <div class="zoom-gallery">
                                                <a class="float-left" href="{{asset($kyc->proof_of_res)}}" title="{{$kyc->natid}}">
                                                    <img src="{{asset($kyc->proof_of_res)}}" alt="" width="275">
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

@section('footer_scripts')
    <script src="{{asset('assets/libs/magnific-popup/jquery.magnific-popup.min.js')}}"></script>
    <script src="{{asset('assets/js/pages/lightbox.init.js')}}"></script>
@endsection
