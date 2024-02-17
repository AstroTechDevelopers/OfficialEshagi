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

    Review {{$partner->regNumber}} KYC
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
                        <li class="breadcrumb-item active">Reviewing KYC for {{$partner->partner_name}}</li>
                    </ol>
                </div>

                <div class="col-lg-8">
                    <div class="float-right d-none d-md-block">
                            <div class="float-right d-none d-md-block">
							    @if (auth()->user()->hasRole('root') OR auth()->user()->hasRole('admin') OR auth()->user()->hasRole('manager'))
                                <div class="text-center mb-4 flex-nowrap">
                                    {!! Form::open(array('url' => 'partners/' . $partner->id)) !!}
                                    {!! Form::hidden('_method', 'DELETE') !!}
                                    {!! Form::button('<i class="fas fa-trash" aria-hidden="true"></i> <span class="hidden-xs hidden-sm hidden-md">Delete Partner</span>' , array('class' => 'btn btn-danger btn-sm','type' => 'button', 'data-toggle' => 'modal', 'data-target' => '#confirmDelete', 'data-title' => 'Delete Partner', 'data-message' => 'Are you sure you want to delete this partner?')) !!}
                                    {!! Form::close() !!}
                                    <br>
                                    <a href="{{url('/partners/'.$partner->id.'/edit')}}" class="btn btn-sm btn-warning" >
                                        <i class="fas fa-user-edit" aria-hidden="true"></i> <span class="hidden-xs hidden-sm hidden-md"> Edit Partner </span>
                                    </a>

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
                            <ul class="nav nav-tabs nav-justified nav-tabs-custom" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" data-toggle="tab" href="#custom-biz-details" role="tab">
                                        <i class="fas fa-user mr-1 align-middle"></i> <span class="d-none d-md-inline-block">Business Details</span>
                                    </a>
                                </li>
								<li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#custom-biz-contact" role="tab">
                                        <i class="fas fa-user mr-1 align-middle"></i> <span class="d-none d-md-inline-block">Contact Details</span>
                                    </a>
                                </li>								
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#custom-biz-address" role="tab">
                                        <i class="fas fa-address-book mr-1 align-middle"></i> <span class="d-none d-md-inline-block">Address Details</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#custom-biz-banking" role="tab">
                                        <i class="fas fa-money-bill-wave mr-1 align-middle"></i> <span class="d-none d-md-inline-block">Banking Details</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#custom-biz-kycdocs" role="tab">
                                        <i class="fas fa-user-tag mr-1 align-middle"></i> <span class="d-none d-md-inline-block">Business KYC Documents</span>
                                    </a>
                                </li>
                                {{--<li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#custom-dir1-kycdocs" role="tab">
                                        <i class="fas fa-user-tag mr-1 align-middle"></i> <span class="d-none d-md-inline-block">Director 1 KYC Documents</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#custom-dir2-kycdocs" role="tab">
                                        <i class="fas fa-user-tag mr-1 align-middle"></i> <span class="d-none d-md-inline-block">Director 2 KYC Documents</span>
                                    </a>
                                </li>--}}
                            </ul>

                            <div class="tab-content p-3">
                                <div class="tab-pane active" id="custom-biz-details" role="tabpanel">
                                    <div class="row">
                                        <div class="col-md-4 mb-3">
                                            <label for="first_name">Partner Name</label>
                                            <p>{{ $partner->partner_name }}</p>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label for="last_name">Merchant / Trading Name</label>
                                            <p>{{ $partner->merchantname }}</p>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label for="dob">Business Type</label>
                                            <p>{{ $partner->business_type }}</p>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4 mb-3">
                                            <label for="gender">Nature of Business</label>
                                            <p>{{ $partner->partnerDesc }}</p>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label for="marital_state">Years Trading</label>
                                            <p>{{ $partner->yearsTrading }}</p>
                                        </div>                                        
                                    </div>
                                    <div class="row">
										<div class="col-md-4 mb-3">
                                            <label for="natid">Registration Number</label>
                                            <p>{{ $partner->regNumber ?? 'Agent with no Company Reg Number' }}</p>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label for="mobile">TPIN</label>
                                            <p>{{ $partner->bpNumber ?? 'Agent with no TPIN'}}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane" id="custom-biz-contact" role="tabpanel">
                                    <div class="row">
                                        <div class="col-md-4 mb-3">
                                            <label for="salary">Full Name</label>
                                            <p>{{ $partner->cfullname }}</p>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label for="gross">Designation</label>
                                            <p>{{ $partner->cdesignation }}</p>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4 mb-3">
                                            <label for="work_contact">Contact Number</label>
                                            <p>{{ $partner->telephoneNo }}</p>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label for="work_email">Email</label>
                                            <p>{{ $partner->cemail }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane" id="custom-biz-address" role="tabpanel">
                                    <div class="row">
                                        <div class="col-md-4 mb-3">
                                            <label for="full_address">Buziness Address</label>
                                            <p>{{$partner->propNumber.', '.$partner->street.', '.$partner->suburb.', '.$partner->city}}</p>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label for="province">Province</label>
                                            <p>{{ $partner->province }}</p>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label for="country">Country</label>
                                            <p>{{$partner->country}}</p>
                                        </div>

                                    </div>
                                </div>
                                <div class="tab-pane" id="custom-biz-banking" role="tabpanel">
                                    <div class="row">
                                        <div class="col-md-4 mb-3">
                                            <label for="bank">Bank Name</label>
                                            <p>{{ $bank->bank }}</p>
                                        </div>

                                        <div class="col-md-4 mb-2">
                                            <label for="branch">Branch</label>
											<p>{{ $partner->branch }}</p>
                                        </div>
                                        <div class="col-md-4 mb-2">
                                            <label for="branch_code">Branch Code</label>
											<p>{{ $partner->branch_code }}</p>
                                        </div>
										<div class="col-md-4 mb-3">
                                            <label for="branch_code">Account Number</label>
											<p>{{ $partner->acc_number }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane" id="custom-biz-kycdocs" role="tabpanel">
                                    @if(empty($mkyc))
                                    <div class="row">
                                        Merchant did not upload business documents.
                                    </div>
                                    @else
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="national_pic">Certificatre of Incorporation (PACRA)</label>
                                            @if(empty($mkyc->cert_incorp))
                                            <div class="row">
                                                <strong style="color: red;">Merchant did not upload Certificatre of Incorporation (PACRA).</strong>
                                            </div>
                                            @else
                                            <div class="zoom-gallery">
                                            <?php
                                               $certtype=explode(".",$mkyc->cert_incorp);
                                               if($certtype[1]=='pdf'){
                                            ?>
                                            <a class="float-left" href="{{asset('merchants/inccerts/'.$mkyc->cert_incorp)}}" title="{{$mkyc->natid}}" target="_blank"><strong style="color: green;">Click to Check PACRA</strong></a>
                                            <?php } else{ ?>
                                            <a class="float-left" href="{{asset('merchants/inccerts/'.$mkyc->cert_incorp)}}" title="{{$mkyc->natid}}">
                                                <img src="{{asset('merchants/inccerts/'.$mkyc->cert_incorp)}}" alt="" width="275">
                                            </a>
                                            <?php } ?>
                                            </div>
                                            @endif
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="passport_pic">Business License</label>
                                            @if(empty($mkyc->bus_licence))
                                            <div class="row">
                                                <strong style="color: red;">Merchant did not upload Business License.</strong>
                                            </div>
                                            @else
                                            <div class="zoom-gallery">
                                            <?php
                                               $buslicence=explode(".",$mkyc->bus_licence);
                                               if($buslicence[1]=='pdf'){
                                            ?>
                                            <a class="float-left" href="{{asset('merchants/bizlicense/'.$mkyc->bus_licence)}}" title="{{$mkyc->natid}}" target="_blank"><strong style="color: green;">Click to Check Business License</strong></a>
                                            <?php } else{ ?>
                                            <a class="float-left" href="{{asset('merchants/bizlicense/'.$mkyc->bus_licence)}}" title="{{$mkyc->natid}}">
                                                <img src="{{asset('merchants/bizlicense/'.$mkyc->bus_licence)}}" alt="" width="275">
                                            </a>
                                            <?php } ?>
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="proof_res">ZRA Tax Registration Certificate</label>
                                            @if(empty($mkyc->cr14))
                                            <div class="row">
                                                <strong style="color: red;">Merchant did not upload ZRA Tax Registration Certificate.</strong>
                                            </div>
                                            @else
                                            <div class="zoom-gallery">
                                            <?php
                                               $cr14=explode(".",$mkyc->cr14);
                                               if($cr14[1]=='pdf'){
                                            ?>
                                            <a class="float-left" href="{{asset('merchants/cr14/'.$mkyc->cr14)}}" title="{{$mkyc->natid}}" target="_blank"><strong style="color: green;">Click to Check ZRA Tax Registration Certificate</strong></a>
                                            <?php } else{ ?>    
                                                <a class="float-left" href="{{asset('merchants/cr14/'.$mkyc->cr14)}}" title="{{$mkyc->natid}}">
                                                    <img src="{{asset('merchants/cr14/'.$mkyc->cr14)}}" alt="" width="275">
                                                </a>
                                            <?php } ?>    
                                            </div>
                                            @endif
                                        </div>										                                        
										{{--<div class="col-md-6 mb-3">
                                            <label for="proof_res">Proof of Residence</label>
                                            @if(empty($mkyc->proof_of_res))
                                            <div class="row">
                                                <strong style="color: red;">Merchant did not upload Proof of Residence.</strong>
                                            </div>
                                            @else
                                            <div class="zoom-gallery">
                                            <?php
                                               $resproof=explode(".",$mkyc->proof_of_res);
                                               if($resproof[1]=='pdf'){
                                            ?>
                                            <a class="float-left" href="{{asset('merchants/resproofs/'.$mkyc->proof_of_res)}}" title="{{$mkyc->natid}}" target="_blank"><strong style="color: red;">Click to Check Proof of Residence</strong></a>
                                            <?php } else{ ?>    
                                                <a class="float-left" href="{{asset('merchants/resproofs/'.$mkyc->proof_of_res )}}" title="{{$mkyc->natid}}">
                                                    <img src="{{asset('merchants/resproofs/'.$mkyc->proof_of_res )}}" alt="" width="275">
                                                </a>
                                            <?php } ?>
                                            </div>
                                            @endif
                                        </div>--}}
                                    </div>
                                    @endif
                                </div>
                                {{--<div class="tab-pane" id="custom-dir1-kycdocs" role="tabpanel">
                                    @if(empty($mkyc))
                                    <div class="row">
                                        Merchant did not upload director documents.
                                    </div>
                                    @else
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="dir1_fname">First Name</label>
											<p>{{ $mkyc->dir_one_name }}</p>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="dir2_lname">Last Name</label>
											<p>{{ $mkyc->dir_one_lname }}</p>
                                        </div>
										<div class="col-md-6 mb-3">
                                            <label for="dir2_lname">National ID</label>
											<p>{{ $mkyc->dir_one_nid }}</p>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="dir1_national_fpic">National ID Front</label>
                                            <div class="zoom-gallery">
                                            <a class="float-left" href="{{asset('merchants/nationalids/'.$mkyc->national_id1)}}" title="{{$mkyc->dir_one_nid}}">
                                                <img src="{{asset('merchants/nationalids/'.$mkyc->national_id1)}}" alt="" width="275">
                                            </a>
                                            </div>
                                        </div>
										<div class="col-md-6 mb-3">
                                            <label for="dir1_national_bpic">National ID Back</label>
                                            <div class="zoom-gallery">
                                                <a class="float-left" href="{{asset('merchants/nationalids/'.$mkyc->national_id1_back_pic)}}" title="{{$mkyc->dir_one_nid}}">
                                                <img src="{{asset('merchants/nationalids/'.$mkyc->national_id1_back_pic)}}" alt="" width="275">
                                            </a>
                                            </div>
                                        </div>										                                        
                                    </div>
									<div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="dir1_passport_pic">Photo</label>
                                            <div class="zoom-gallery">
                                                <a class="float-left" href="{{asset('merchants/photos/'.$mkyc->pphoto1)}}" title="{{$mkyc->dir_one_nid}}">
                                                <img src="{{asset('merchants/photos/'.$mkyc->pphoto1)}}" alt="" width="275">
                                            </a>
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                </div>
								<div class="tab-pane" id="custom-dir2-kycdocs" role="tabpanel">
								    @if(empty($mkyc))
                                    <div class="row">
                                        Merchant did not upload director documents.
                                    </div>
                                    @else
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="dir1_fname">First Name</label>
											<p>{{ $mkyc->dir_two_name }}</p>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="dir2_lname">Last Name</label>
											<p>{{ $mkyc->dir_two_lname }}</p>
                                        </div>
										<div class="col-md-6 mb-3">
                                            <label for="dir2_lname">National ID</label>
											<p>{{ $mkyc->dir_two_nid }}</p>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="dir1_national_fpic">National ID Front</label>
                                            <div class="zoom-gallery">
											   <a class="float-left" href="{{asset('merchants/nationalids/'.$mkyc->national_pic_front_dir_two)}}" title="{{$mkyc->dir_two_nid}}">
                                                  <img src="{{asset('merchants/nationalids/'.$mkyc->national_pic_front_dir_two)}}" alt="" width="275">
                                               </a>
                                            </div>
                                        </div>
										<div class="col-md-6 mb-3">
                                            <label for="dir1_national_bpic">National ID Back</label>
                                            <div class="zoom-gallery">
                                               <a class="float-left" href="{{asset('merchants/nationalids/'.$mkyc->national_pic_back_dir_two)}}" title="{{$mkyc->dir_two_nid}}">
                                                  <img src="{{asset('merchants/nationalids/'.$mkyc->national_pic_back_dir_two)}}" alt="" width="275">
											   </a>
                                            </div>
                                        </div>										                                        
                                    </div>
									<div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="dir1_passport_pic">Photo</label>
                                            <div class="zoom-gallery">
                                               <a class="float-left" href="{{asset('merchants/photos/'.$mkyc->pphoto2)}}" title="{{$mkyc->dir_two_nid}}">
                                                  <img src="{{asset('merchants/photos/'.$mkyc->pphoto2)}}" alt="" width="275">
                                               </a>
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                </div>--}}
                            </div>
                            <div class="float-right d-none d-md-block">
                                <div>
								@if($partner->status==0)
                                {!! Form::open(array('url' => 'approve-merchant/' . $partner->id, 'class' => 'd-inline')) !!}                                
                                {!! Form::button('<a><i class="fas fa-user" aria-hidden="true"></i> Approve Merchant</a>' , array('class' => 'btn btn-sm btn-warning','type' => 'button', 'data-toggle' => 'modal', 'data-target' => '#approveMerchant', 'data-title' => 'Approve Merchant', 'data-message' => 'Are you sure you want to approve this Merchant ?')) !!}
                                {!! Form::close() !!}
								@endif
								
								@if($partner->status==1)
                                {!! Form::open(array('url' => 'activate-merchant/' . $partner->id, 'class' => 'd-inline')) !!}                                
                                {!! Form::button('<a><i class="fas fa-user" aria-hidden="true"></i> Aactivate Merchant</a>' , array('class' => 'btn btn-sm btn-warning','type' => 'button', 'data-toggle' => 'modal', 'data-target' => '#activateMerchant', 'data-title' => 'Aactivate Merchant', 'data-message' => 'Are you sure you want to activate this Merchant ?')) !!}
                                {!! Form::close() !!}
								@endif

                                {!! Form::open(array('url' => 'reject-merchant/' . $partner->id, 'class' => 'd-inline', 'method' => 'post')) !!}
                                {!! Form::button('<a><i class="fas fa-user" aria-hidden="true"></i> Reject Merchant</a>' , array('class' => 'btn btn-danger btn-sm','type' => 'button', 'data-toggle' => 'modal', 'data-target' => '#rejectMerchant', 'data-title' => 'Reject Merchant', 'data-message' => 'Are you sure you want to reject this Merchant ?')) !!}
                                {!! Form::close() !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('modals.modal-delete')
	@include('modals.modal-loanofficer-merchant-approve')
	@include('modals.modal-loanofficer-merchant-activate')
    @include('modals.modal-loanofficer-merchant-reject')
@endsection

@section('footer_scripts')
    @include('scripts.delete-modal-script')
	@include('scripts.loanofficer-merchant-approve-modal-script')
	@include('scripts.loanofficer-merchant-activate-modal-script')
    @include('scripts.loanofficer-merchant-reject-modal-script')
    @if(config('usersmanagement.tooltipsEnabled'))
        @include('scripts.tooltips')
    @endif
@endsection