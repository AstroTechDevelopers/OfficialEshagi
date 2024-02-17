<?php
/**
 *Created by PhpStorm for eshagi
 *User: Vincent Guyo
 *Date: 9/27/2020
 *Time: 9:20 AM
 */

?>
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
                <div class="col-lg-4">
                    <h4 class="page-title mb-1">Know Your Customer</h4>
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{url('/kycs')}}">KYCs</a></li>
                        <li class="breadcrumb-item active">Reviewing KYC for {{$kyc->natid}}</li>
                    </ol>
                </div>

                <div class="col-lg-8">
                    <div class="float-right d-none d-md-block">
                            <div class="float-right d-none d-md-block">
							    @if(!empty($loan))
                                <div>
                                @if($kyc->kyc_status==0)    
                                    <a class="btn btn-info btn-rounded" style="pointer-events: none;cursor: default;" href="{{url(''.$client->id.'/'.$loan->id)}}" type="button">
                                        <i class="mdi mdi-folder-upload mr-1"></i>Post to AstroCred
                                    </a>
                                @else    
                                    <a class="btn btn-info btn-rounded" href="{{url(''.$client->id.'/'.$loan->id)}}" type="button">
                                        <i class="mdi mdi-folder-upload mr-1"></i>Post to AstroCred
                                    </a>
                                @endif
                                </div>
								@endif
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
                            @if($client->locale_id == 1)
                                <h4 class="header-title">Customer Number: {{$client->reds_number ?? 'Not yet generated'}}</h4>
                            @elseif($client->locale_id == 2)
                                <h4 class="header-title">eShagi Customer Number: {{$client->reds_number ?? 'Not yet generated'}}</h4>
                            @endif

                            <p class="card-title-desc">KYC Last Update: {{$kyc->updated_at}} Credit Limit: ${{$client->cred_limit}}
                                <br>
                                <span class="text-info">KYC Added By: {{$client->creator}}</span></p>

                            <ul class="nav nav-tabs nav-justified nav-tabs-custom" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" data-toggle="tab" href="#custom-details" role="tab">
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
								@if($loan->loan_type!=1)
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#custom-banking" role="tab">
                                        <i class="fas fa-money-bill-wave mr-1 align-middle"></i> <span class="d-none d-md-inline-block">Banking Details</span>
                                    </a>
                                </li>
								@endif
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#custom-kycdocs" role="tab">
                                        <i class="fas fa-user-tag mr-1 align-middle"></i> <span class="d-none d-md-inline-block">KYC Documents</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#custom-loandetails" role="tab">
                                        <i class="fas fa-money-bill-wave mr-1 align-middle"></i> <span class="d-none d-md-inline-block">Loan Details</span>
                                    </a>
                                </li>
                            </ul>

                            <div class="tab-content p-3">
                                <div class="tab-pane active" id="custom-details" role="tabpanel">
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
                                            <input type="text" class="form-control" id="mobile" placeholder="e.g. 773418009" value="+260{{$client->mobile}}" required>
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
                                            <input type="text" class="form-control" id="kin_number" placeholder="e.g. 777418009" value="+260{{$kyc->kin_number}}" required>
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
								@if($loan->loan_type!=1)
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
								@endif
                                <div class="tab-pane" id="custom-kycdocs" role="tabpanel">
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="national_pic">National ID Front</label>
                                            <div class="zoom-gallery">
                                            <a class="float-left" href="{{asset('nationalids/'.$kyc->national_pic)}}" title="{{$kyc->natid}}">
                                                <img src="{{asset('nationalids/'.$kyc->national_pic)}}" alt="" width="275">
                                            </a>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="passport_pic">National ID Back</label>
                                            <div class="zoom-gallery">
                                            <a class="float-left" href="{{asset('nationalids/'.$kyc->national_pic_back)}}" title="{{$kyc->natid}}">
                                                <img src="{{asset('nationalids/'.$kyc->national_pic_back)}}" alt="" width="275">
                                            </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="passport_pic">Passport Photo</label>
                                            <div class="zoom-gallery">
                                            <a class="float-left" href="{{asset('pphotos/'.$kyc->passport_pic)}}" title="{{$kyc->natid}}">
                                                <img src="{{asset('pphotos/'.$kyc->passport_pic)}}" alt="" width="275">
                                            </a>
                                            </div>
                                        </div>
										<div class="col-md-6 mb-3">
                                            <label for="proof_res">Proof of Residence</label>
                                            <div class="zoom-gallery">
                                                <a class="float-left" href="{{asset('proofres/'.$kyc->proofres_pic)}}" title="{{$kyc->natid}}">
                                                    <img src="{{asset('proofres/'.$kyc->proofres_pic)}}" alt="" width="275">
                                                </a>
                                            </div>
                                        </div>										                                        
                                    </div>
									<div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="proof_res">Client Signature</label>
                                            <div class="zoom-gallery">
                                                <a class="float-left" href="{{asset('signatures/'.$kyc->sign_pic )}}" title="{{$kyc->natid}}">
                                                    <img src="{{asset('signatures/'.$kyc->sign_pic )}}" alt="" width="275">
                                                </a>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="proof_res">Employer Letter</label>
                                            <div class="zoom-gallery">
                                                <a class="float-left" href="{{asset('empletters/'.$kyc->emp_approval_letter)}}" title="{{$kyc->natid}}">
                                                    <img src="{{asset('empletters/'.$kyc->emp_approval_letter)}}" alt="" width="275">
                                                </a>
                                            </div>
                                        </div>
                                    </div>
									<div class="row">
                                       <div class="col-md-6 mb-3">
                                            <label for="payslip_pic">Payslip 1</label>
                                            <div class="zoom-gallery">
                                                <a class="float-left" href="{{asset('payslips/'.$kyc->payslip_pic)}}" title="{{$kyc->natid}}">
                                                    <img src="{{asset('payslips/'.$kyc->payslip_pic)}}" alt="" width="275">
                                                </a>
                                            </div>
                                        </div>
										<div class="col-md-6 mb-3">
                                            <label for="payslip_pic">Payslip 2 (Optional)</label>
                                            <div class="zoom-gallery">
                                                <a class="float-left" href="{{asset('payslips/'.$kyc->payslip_pic_2)}}" title="{{$kyc->natid}}">
                                                    <img src="{{asset('payslips/'.$kyc->payslip_pic_2)}}" alt="" width="275">
                                                </a>
                                            </div>
                                        </div>
                                    </div>
									<div class="row">
                                       <div class="col-md-6 mb-3">
                                            <label for="payslip_pic">Payslip 3 (Optional)</label>
                                            <div class="zoom-gallery">
                                                <a class="float-left" href="{{asset('payslips/'.$kyc->payslip_pic_3)}}" title="{{$kyc->natid}}">
                                                    <img src="{{asset('payslips/'.$kyc->payslip_pic_3)}}" alt="" width="275">
                                                </a>
                                            </div>
                                        </div>
										<div class="col-md-6 mb-3">
                                            <label for="payslip_pic">Payslip 4 (Optional)</label>
                                            <div class="zoom-gallery">
                                                <a class="float-left" href="{{asset('payslips/'.$kyc->payslip_pic_4)}}" title="{{$kyc->natid}}">
                                                    <img src="{{asset('payslips/'.$kyc->payslip_pic_4)}}" alt="" width="275">
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane" id="custom-loandetails" role="tabpanel">
                                    @if(empty($loan))
									<div class="row">
                                        <div class="col-md-4 mb-3">
                                            <p>No loan request found.</p>
                                        </div>
                                    </div>	
									@else
									<div class="row">
                                        <div class="col-md-4 mb-3">
                                            @if($loan->loan_type!=1)
											<label for="loanamount">Loan Amount</label>
										    @else
											<label for="loanamount">Product Price</label>	
											@endif	
                                            <p>{{ $loan->amount }}</p>
                                        </div>
										<div class="col-md-4 mb-3">
                                            @if($loan->loan_type!=1)
                                            <label for="disbursedamount">Loan Amount To Be Disbursed</label>
										    @else
											<label for="disbursedamount">Loan Applied Amount</label>
											@endif	
                                            <p>{{ $loan->disbursed }}</p>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label for="paybackperiod">Pay Back Period</label>
                                            <p>{{ $loan->paybackPeriod }}</p>
                                        </div>
                                    </div>
									@if($loan->loan_type==1)
									<div class="row">                                        
										<div class="col-md-4 mb-3">
                                            <label for="paybackperiod">Merchant</label>
                                            <p>{{ $partnerName }}</p>
                                        </div>
										<div class="col-md-4 mb-3">
                                            <label for="paybackperiod">Product</label>
                                            <p>{{ $loan->product }}</p>
                                        </div>
                                    </div>
									@endif
                                    @endif
                                </div>
                            </div>
                            <div class="float-right d-none d-md-block">
                                <div>
								@if(!empty($loan))
                                {!! Form::open(array('url' => 'approve-kyc/' . $kyc->id, 'class' => 'd-inline')) !!}                                
                                {!! Form::button('<a><i class="mdi mdi-printer mr-1" aria-hidden="true"></i>Approve KYC</a>' , array('class' => 'btn btn-info btn-rounded','type' => 'button', 'data-toggle' => 'modal', 'data-target' => '#approveKYC', 'data-title' => 'Approve KYC', 'data-message' => 'Are you sure you want to approve this KYC ?')) !!}
                                {!! Form::close() !!}
								@endif

                                {!! Form::open(array('url' => 'reject-kyc/' . $kyc->id, 'class' => 'd-inline', 'method' => 'post')) !!}
                                {!! Form::button('<a><i class="mdi mdi-printer mr-1" aria-hidden="true"></i>Reject KYC</a>' , array('class' => 'btn btn-info btn-rounded','type' => 'button', 'data-toggle' => 'modal', 'data-target' => '#rejectKYC', 'data-title' => 'Reject KYC', 'data-message' => 'Are you sure you want to reject this KYC ?')) !!}
                                {!! Form::close() !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('modals.modal-loanofficer-kyc-approve')
    @include('modals.modal-loanofficer-kyc-reject')
@endsection

@section('footer_scripts')
    @include('scripts.loanofficer-kyc-approve-modal-script')
    @include('scripts.loanofficer-kyc-reject-modal-script')
    <script src="{{asset('assets/libs/magnific-popup/jquery.magnific-popup.min.js')}}"></script>
    <script src="{{asset('assets/js/pages/lightbox.init.js')}}"></script>
@endsection
