<?php
/**
 * Created by PhpStorm for eshagi
 * User: vinceg
 * Date: 20/2/2021
 * Time: 10:19
 */
?>
@extends('layouts.app')

@section('template_title')
    Modify Partner Details
@endsection

@section('template_linked_css')
    <link href="{{ asset('css/select2.min.css')}}" rel="stylesheet" />

@endsection

@section('content')
    <div class="page-title-box">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h4 class="page-title mb-1">Partners</h4>
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{url('/partners')}}">Partners</a></li>
                        <li class="breadcrumb-item active">Edit Partner</li>
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
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="header-title">Edit {{$partner->partner_name}} details</h4>

                            <ul class="nav nav-pills nav-justified" role="tablist">
                                <li class="nav-item waves-effect waves-light">
                                    <a class="nav-link active" data-toggle="tab" href="#info" role="tab">
                                        <i class="fas fa-home mr-1"></i> <span class="d-none d-md-inline-block">Business Details</span>
                                    </a>
                                </li>
								<li class="nav-item waves-effect waves-light">
                                    <a class="nav-link" data-toggle="tab" href="#bizdocs" role="tab">
                                        <i class="fas fa-home mr-1"></i> <span class="d-none d-md-inline-block">Business Documents</span>
                                    </a>
                                </li>
								{{--<li class="nav-item waves-effect waves-light">
                                    <a class="nav-link" data-toggle="tab" href="#dir1docs" role="tab">
                                        <i class="fas fa-home mr-1"></i> <span class="d-none d-md-inline-block">Director 1 Documents</span>
                                    </a>
                                </li>
								<li class="nav-item waves-effect waves-light">
                                    <a class="nav-link" data-toggle="tab" href="#dir2docs" role="tab">
                                        <i class="fas fa-home mr-1"></i> <span class="d-none d-md-inline-block">Director 2 Documents</span>
                                    </a>
                                </li>
                                <li class="nav-item waves-effect waves-light">
                                    <a class="nav-link" data-toggle="tab" href="#other" role="tab">
                                        <i class="fas fa-user mr-1"></i> <span class="d-none d-md-inline-block">Other</span>
                                    </a>
                                </li>--}}
                            </ul>

                            <!-- Tab panes -->
                            <div class="tab-content p-3">
                                <div class="tab-pane active" id="info" role="tabpanel">
                                    {!! Form::open(array('route' => ['partners.update', $partner->id], 'method' => 'PUT', 'role' => 'form', 'class' => 'needs-validation')) !!}
                                    {!! csrf_field() !!}

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Partner Name</label>
                                                <input class="form-control{{ $errors->has('partner_name') ? ' is-invalid' : '' }}" type="text" name="partner_name" id="partner_name" required="required" value="{{ $partner->partner_name ?? old('partner_name')}}" placeholder="Enter your company name...">
                                                @if ($errors->has('partner_name'))
                                                    <span class="invalid-feedback">
                                                    <strong>{{ $errors->first('partner_name') }}</strong>
                                                </span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Partner Type</label>
                                                <select class="form-control{{ $errors->has('partner_type') ? ' is-invalid' : '' }}" id="partner_type" name="partner_type" required>
                                                    <option value="{{ $partner->partner_type }}">{{ $partner->partner_type }}</option>
                                                    <option value="Agent">Agent</option>
                                                    <option value="Merchant">Merchant</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Trading name (if different)</label>
                                                <input class="form-control{{ $errors->has('merchantname') ? ' is-invalid' : '' }}" type="text" name="merchantname" id="merchantname" value="{{ $partner->merchantname ?? old('merchantname')}}" placeholder="Enter trading name...">
                                                @if ($errors->has('merchantname'))
                                                    <span class="invalid-feedback">
                                                    <strong>{{ $errors->first('merchantname') }}</strong>
                                                </span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Type of Business</label>
                                                <select class="form-control{{ $errors->has('business_type') ? ' is-invalid' : '' }}" id="business_type" name="business_type" onchange='businessTypeChanged()' required>
                                                    <option value="{{ $partner->business_type }}">{{ $partner->business_type }}</option>
                                                    <option value="Private Limited Company">Private Limited Company</option>
                                                    <option value="Sole Trader">Sole Trader</option>
                                                    <option value="Private Business Corporation">Private Business Corporation</option>
                                                    <option value="Co-Operative Society">Co-Operative Society</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Partner Trading Details</label>
                                                <input class="form-control{{ $errors->has('partnerDesc') ? ' is-invalid' : '' }}" type="text" name="partnerDesc" id="partnerDesc" value="{{$partner->partnerDesc ?? old('partnerDesc')}}" required="required" placeholder="Enter description of business activities...">
                                                @if ($errors->has('partnerDesc'))
                                                    <span class="invalid-feedback">
                                                    <strong>{{ $errors->first('partnerDesc') }}</strong>
                                                </span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Years Trading</label>
                                                <select class="form-control{{ $errors->has('yearsTrading') ? ' is-invalid' : '' }}" id="yearsTrading" name="yearsTrading" required>
                                                    <option value="{{ $partner->yearsTrading }}">{{ $partner->yearsTrading }}</option>
                                                    <option value=1>1</option>
                                                    <option value=2>2</option>
                                                    <option value=3>3</option>
                                                    <option value=4>4</option>
                                                    <option value=5>5</option>
                                                    <option value="5+">5+</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row" id="other_details">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>PACRA No.</label>
                                                <input class="form-control{{ $errors->has('regNumber') ? ' is-invalid' : '' }}" type="text" name="regNumber" id="regNumber" value="{{$partner->regNumber ?? old('regNumber')}}" placeholder="Enter your company PACRA No....">
                                                @if ($errors->has('regNumber'))
                                                    <span class="invalid-feedback">
                                                    <strong>{{ $errors->first('regNumber') }}</strong>
                                                </span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>TPIN</label>
                                                <input class="form-control{{ $errors->has('bpNumber') ? ' is-invalid' : '' }}" type="text" name="bpNumber" id="bpNumber" value="{{$partner->bpNumber ?? old('bpNumber')}}" placeholder="Enter your TPIN ...">
                                                @if ($errors->has('bpNumber'))
                                                    <span class="invalid-feedback">
                                                    <strong>{{ $errors->first('bpNumber') }}</strong>
                                                </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
									
									<div class="row">
										<div class="col-md-6">
											<div class="form-group">
												<label>VAT Registered?</label>
												<select class="form-control" type="text" name="vatRegNumber" id="vatRegNumber" required="required">                                               
													<option value="">Is your company / business VAT registered?</option>
												    <option value="{{ $partner->vatRegNumber }}">{{ $partner->vatRegNumber }}</option>
													<option value="YES">YES</option>
													<option value="NO">NO</option>
												</select>
											</div>
										</div>
									</div>
                                    <hr>
                                    <h2 class="title-h2"> Address Details</h2>
                                    <hr>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Plot Number</label>
                                                <input class="form-control{{ $errors->has('propNumber') ? ' is-invalid' : '' }}" type="text" name="propNumber" id="propNumber" value="{{$partner->propNumber ?? old('propNumber')}}" required="required" placeholder="Enter your Plot Number...">
                                                @if ($errors->has('propNumber'))
                                                    <span class="invalid-feedback">
                                                    <strong>{{ $errors->first('propNumber') }}</strong>
                                                </span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Street Name</label>
                                                <input class="form-control{{ $errors->has('street') ? ' is-invalid' : '' }}" type="text" name="street" id="street" value="{{$partner->street ?? old('street')}}" required="required" placeholder="Enter your street name...">
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
                                                <label>Suburb</label>
                                                <input class="form-control{{ $errors->has('suburb') ? ' is-invalid' : '' }}" type="text" name="suburb" id="suburb" value="{{$partner->suburb ?? old('suburb')}}" required="required" placeholder="Enter your suburb...">
                                                @if ($errors->has('suburb'))
                                                    <span class="invalid-feedback">
                                                    <strong>{{ $errors->first('suburb') }}</strong>
                                                </span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>City</label>
                                                <input class="form-control{{ $errors->has('city') ? ' is-invalid' : '' }}" type="text" name="city" id="city" value="{{$partner->city ?? old('city')}}" required="required" placeholder="Enter your city...">
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
                                                    <option value="{{ $partner->province }}">{{ $partner->province }}</option>
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
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Country</label>
                                                <select class="form-control{{ $errors->has('country') ? ' is-invalid' : '' }}" id="country" name="country" required>
                                                    <option value="{{ $partner->country }}">{{ $partner->country }}</option>
                                                    <option value="Zimbabwe">Zimbabwe</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <hr>
                                    <h2 class="title-h2"> Contact Details</h2>
                                    <hr>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Contact Person Full Name</label>
                                                <input class="form-control{{ $errors->has('cfullname') ? ' is-invalid' : '' }}" type="text" name="cfullname" id="cfullname" value="{{$partner->cfullname ?? old('cfullname')}}" required="required" placeholder="Enter contact person's full name...">
                                                @if ($errors->has('cfullname'))
                                                    <span class="invalid-feedback">
                                                    <strong>{{ $errors->first('cfullname') }}</strong>
                                                </span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Contact Person Designation</label>
                                                <input class="form-control{{ $errors->has('cdesignation') ? ' is-invalid' : '' }}" type="text" name="cdesignation" id="cdesignation" value="{{$partner->cdesignation ?? old('cdesignation')}}" required="required" placeholder="Enter contact person's designation e.g. CEO...">
                                                @if ($errors->has('cdesignation'))
                                                    <span class="invalid-feedback">
                                                    <strong>{{ $errors->first('cdesignation') }}</strong>
                                                </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Telephone No</label>
                                                <input class="form-control{{ $errors->has('telephoneNo') ? ' is-invalid' : '' }}" type="text" name="telephoneNo" id="telephoneNo" value="{{$partner->telephoneNo ?? old('telephoneNo')}}" required="required" placeholder="Enter contact person's telephone number...">
                                                @if ($errors->has('telephoneNo'))
                                                    <span class="invalid-feedback">
                                                    <strong>{{ $errors->first('telephoneNo') }}</strong>
                                                </span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>E-mail</label>
                                                <input class="form-control{{ $errors->has('cemail') ? ' is-invalid' : '' }}" type="email" name="cemail" id="cemail" value="{{$partner->cemail ?? old('cemail')}}" required="required" placeholder="Enter contact person's email...">
                                                @if ($errors->has('cemail'))
                                                    <span class="invalid-feedback">
                                                    <strong>{{ $errors->first('cemail') }}</strong>
                                                </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    <h2 class="title-h2">Banking Details</h2>
                                    <hr>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Bank Name</label>
                                                <select class="form-control" type="text" name="bank" id="bank" style="width: 100%;" required>
                                                    @if ($banks)
                                                        @foreach($banks as $bank)
                                                            <option value="{{ $bank->id }}" {{ $partner->bank == $bank->id ? 'selected="selected"' : '' }}>{{ $bank->bank }}</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Branch Name</label>
                                                <select name="branch" id="branch" class="form-control" required>
                                                    <option value="{{$partner->branch}}">{{$partner->branch}}</option>
                                                </select>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Branch Code</label>
                                                <input class="form-control{{ $errors->has('branch_code') ? ' is-invalid' : '' }}" type="text" name="branch_code" id="branch_code" required value="{{ $partner->branch_code ?? old('branch_code') }}" placeholder="Please select your branch" readonly>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Account Number</label>
                                                <input class="form-control{{ $errors->has('acc_number') ? ' is-invalid' : '' }}" type="text" name="acc_number" id="acc_number" value="{{ $partner->acc_number ?? old('acc_number') }}" pattern='^\d{1,3}*(\.\d+)?$' data-type="currency" required="required" placeholder="Enter your account number...">
                                                @if ($errors->has('acc_number'))
                                                    <span class="invalid-feedback">
                                                    <strong>{{ $errors->first('acc_number') }}</strong>
                                                </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <hr>

                                    {!! Form::button('Update Business Details', array('class' => 'btn btn-success margin-bottom-1 mb-1 float-right','type' => 'submit' )) !!}
                                    {!! Form::close() !!}
                                </div>
                                <div class="tab-pane" id="bizdocs" role="tabpanel">
									<div class="container" >
										<form method="post" action="{{ route('update.business.kyc') }}" enctype="multipart/form-data">
											@csrf
											<div class="row">
												<div class="col-lg-6">
													<h2 class="title-h2">Certificate of Incorporation (PACRA)</h2>
												</div>
											</div>
											<hr>                            
											<div class="row">
												@if(!empty($mkyc->cert_incorp))
												<div class="col-md-3 mb-3">
													<div class="zoom-gallery">
													    <?php
                                                           $certtype=explode(".",$mkyc->cert_incorp);
                                                           if($certtype[1]=='pdf'){
                                                        ?>
                                                        <a class="float-left" href="{{asset('merchants/inccerts/'.$mkyc->cert_incorp)}}" title="{{$mkyc->natid}}" target="_blank">Click to View PACRA</a>
                                                        <?php } else{ ?>
														<a class="float-left" href="{{asset('merchants/inccerts/'.$mkyc->cert_incorp)}}" title="{{$mkyc->natid}}">
															<img src="{{asset('merchants/inccerts/'.$mkyc->cert_incorp)}}" alt="" width="275">
														</a>
														<?php } ?>
													</div>
												</div>
												@endif
												<div class="col-md-3">
													<input type="file" name="inc_cert" id="inc_cert" accept="image/*" />
												</div>
												<div class="col-md-6">
													<p>1. Please upload a cropped file of your Certificate of Incorporation <br>
													2. Certificate of Incorporation file should not be greater than 4MB. <br>
													3. Certificate of Incorporation file should of the format: jpeg,png,jpg,gif,svg,pdf. <br></p>
												</div>
											</div>
											{{--<div class="row">
												<div class="col-lg-6">
													<h2 class="title-h2">Proof of Residence</h2>
												</div>
											</div>
											<hr>
											<div class="row">
											    @if(!empty($mkyc->proof_of_res))
											    <div class="col-md-3 mb-3">
													<div class="zoom-gallery">
													    <?php
                                                           $resproof=explode(".",$mkyc->proof_of_res);
                                                           if($resproof[1]=='pdf'){
                                                        ?>
                                                        <a class="float-left" href="{{asset('merchants/resproofs/'.$mkyc->proof_of_res)}}" title="{{$mkyc->natid}}" target="_blank">Click to View Proof of Residence</a>
                                                        <?php } else{ ?>
														<a class="float-left" href="{{asset('merchants/resproofs/'.$mkyc->proof_of_res )}}" title="{{$mkyc->natid}}">
															<img src="{{asset('merchants/resproofs/'.$mkyc->proof_of_res )}}" alt="" width="275">
														</a>
														<?php } ?>
													</div>
												</div>
												@endif
												<div class="col-md-3">
													<input type="file" name="resproof" id="resproof" accept="image/*" />
												</div>
												<div class="col-md-6">
													<p>1. Please upload proof of residence. <br>
														2. Proof of residence file should not be greater than 4MB. <br>
														3. Proof of residence file should of the format: jpeg,png,jpg,gif,svg,pdf. <br></p>
												</div>
											</div>--}}
											<div class="row">
												<div class="col-lg-6">
													<h2 class="title-h2">ZRA Tax Registration Certificate</h2>
												</div>
											</div>
											<hr>
											<div class="row">
											    @if(!empty($mkyc->cr14))
												<div class="col-md-3 mb-3">
													<div class="zoom-gallery">
													    <?php
                                                           $buslicence=explode(".",$mkyc->cr14);
                                                           if($buslicence[1]=='pdf'){
                                                        ?>
                                                        <a class="float-left" href="{{asset('merchants/cr14/'.$mkyc->cr14)}}" title="{{$mkyc->natid}}" target="_blank">Click to View ZRA Tax Registration Certificate</a>
                                                        <?php } else{ ?>
														<a class="float-left" href="{{asset('merchants/cr14/'.$mkyc->cr14)}}" title="{{$mkyc->natid}}">
															<img src="{{asset('merchants/cr14/'.$mkyc->cr14)}}" alt="" width="275">
														</a>
														<?php } ?>
													</div>
												</div>
												@endif
												<div class="col-md-3">
													<input type="file" name="cr14" id="cr14" accept="image/*" />
												</div>
												<div class="col-md-6">
													<p>1. Please upload ZRA Tax Registration Certificate. <br>
														2. ZRA Tax Registration Certificate file should not be greater than 4MB. <br>
														3. ZRA Tax Registration Certificate file should of the format: jpeg,png,jpg,gif,svg,pdf. <br></p>
												</div>
											</div>
											<div class="row">
												<div class="col-lg-6">
													<h2 class="title-h2">Business License</h2>
												</div>
											</div>
											<hr>
											
											<div class="row">
											    @if(!empty($mkyc->bus_licence))
												<div class="col-md-3 mb-3">
													<div class="zoom-gallery">
													    <?php
                                                           $buslicence=explode(".",$mkyc->bus_licence);
                                                           if($buslicence[1]=='pdf'){
                                                        ?>
                                                        <a class="float-left" href="{{asset('merchants/bizlicense/'.$mkyc->bus_licence)}}" title="{{$mkyc->natid}}" target="_blank">Click to View Business License</a>
                                                        <?php } else{ ?>
														<a class="float-left" href="{{asset('merchants/bizlicense/'.$mkyc->bus_licence)}}" title="{{$mkyc->natid}}">
															<img src="{{asset('merchants/bizlicense/'.$mkyc->bus_licence)}}" alt="" width="275">
														</a>
														<?php } ?>
													</div>
												</div>
												@endif
												<div class="col-md-3">
													<input type="file" name="bizlicense" id="bizlicense" accept="image/*" />
												</div>
												<div class="col-md-6">
													<p>1. Please upload business license. <br>
														2. Business license file should not be greater than 4MB. <br>
														3. Business license file should of the format: jpeg,png,jpg,gif,svg,pdf. <br></p>
												</div>
											</div>
											<hr>
											<div class="row">
												<div class="col-lg-6">
												    @if(!empty($mkyc->id))
													<input type="hidden" name="mid" value="{{ $mkyc->id }}">
													<input type="hidden" name="pid" value="NA12092023">
													@else
													<input type="hidden" name="mid" value="NA12092023">
													<input type="hidden" name="pid" value="{{ $partner->id }}">
													@endif
													<input class="btn btn-success btn-send" type="submit" value="Update Business Documents ">
												</div>
											</div>
										</form>	
									</div>
                                </div>
								{{--<div class="tab-pane" id="dir1docs" role="tabpanel">
									<div class="container" >
										<form method="post" action="{{ route('update.kyc.director.one') }}" enctype="multipart/form-data">
											@csrf
											<div class="row">
												<div class="col-md-6">
													<div class="form-group">
														<label>First Name</label>
														@if(!empty($mkyc->dir_one_name))
														<input class="form-control{{ $errors->has('dir_one_name') ? ' is-invalid' : '' }}" type="text" name="dir_one_name" id="dir_one_name" value="{{ $mkyc->dir_one_name }}" placeholder="Director name...">
														@else
														<input class="form-control{{ $errors->has('dir_one_name') ? ' is-invalid' : '' }}" type="text" name="dir_one_name" id="dir_one_name" required="required" value="" placeholder="Director name...">
														@endif
														@if ($errors->has('dir_one_name'))
															<span class="invalid-feedback">
																<strong>{{ $errors->first('dir_one_name') }}</strong>
															</span>
														@endif
													</div>
												</div>
												<div class="col-md-6">
													<div class="form-group">
														<label>Last Name</label>
														@if(!empty($mkyc->dir_one_lname))
														<input class="form-control{{ $errors->has('dir_one_lname') ? ' is-invalid' : '' }}" type="text" name="dir_one_lname" id="dir_one_lname" value="{{ $mkyc->dir_one_lname }}" placeholder="Director last name...">
														@else
														<input class="form-control{{ $errors->has('dir_one_lname') ? ' is-invalid' : '' }}" type="text" name="dir_one_lname" id="dir_one_lname" required="required" value="" placeholder="Director last name...">
														@endif
														@if ($errors->has('dir_one_lname'))
															<span class="invalid-feedback">
																<strong>{{ $errors->first('dir_one_lname') }}</strong>
															</span>
														@endif
													</div>
												</div>
											</div>
											<div class="row">
												<div class="col-md-6">
													<div class="form-group">
														<label>National ID Number</label>
														@if(!empty($mkyc->dir_one_nid))
														<input class="form-control{{ $errors->has('dir_one_nid') ? ' is-invalid' : '' }}" type="text" name="dir_one_nid" id="dir_one_nid" value="{{ $mkyc->dir_one_nid }}" placeholder="e.g 112233/44/5">
														@else
														<input class="form-control{{ $errors->has('dir_one_nid') ? ' is-invalid' : '' }}" type="text" name="dir_one_nid" id="dir_one_nid" required="required" value="" placeholder="e.g 112233/44/5">
														@endif
														@if ($errors->has('dir_one_nid'))
															<span class="invalid-feedback">
																<strong>{{ $errors->first('dir_one_nid') }}</strong>
															</span>
														@endif
													</div>
												</div>					
											</div>
											<div class="row">
												<div class="col-lg-6">
													<h2 class="title-h2">National ID: Frontside</h2>
												</div>
											</div>
											<hr>
											<div class="row">
												@if(!empty($mkyc->national_id1))
												<div class="col-md-3 mb-3">
													<div class="zoom-gallery">
													<a class="float-left" href="{{asset('merchants/nationalids/'.$mkyc->national_id1)}}" title="{{$mkyc->dir_one_nid}}">
														<img src="{{asset('merchants/nationalids/'.$mkyc->national_id1)}}" alt="" width="275">
													</a>
													</div>
												</div>
												@endif
												<div class="col-md-3">
													<input type="file" name="natid_front_dir_one" id="natid_front_dir_one" accept="image/*" />
												</div>
												<div class="col-md-6">
													<p>1. Please upload a cropped image of your Frontside National ID <br>
													2. Backside National ID image should not be greater than 4MB. <br>
													3. Backside National ID image should of the format: jpeg,png,jpg,gif,svg. <br></p>
												</div>
											</div>
											<div class="row">
												<div class="col-lg-6">
													<h2 class="title-h2">National ID: Backside</h2>
												</div>
											</div>
											<hr>
											<div class="row">
												@if(!empty($mkyc->national_id1_back_pic))
												<div class="col-md-3 mb-3">
													<div class="zoom-gallery">
														<a class="float-left" href="{{asset('merchants/nationalids/'.$mkyc->national_id1_back_pic)}}" title="{{$mkyc->dir_one_nid}}">
															<img src="{{asset('merchants/nationalids/'.$mkyc->national_id1_back_pic)}}" alt="" width="275">
														</a>
													</div>
												</div>
												@endif
												<div class="col-md-3">
													<input type="file" name="natid_back_dir_one" id="natid_back_dir_one" accept="image/*" />
												</div>
												<div class="col-md-6">
													<p>1. Please upload a cropped image of your Backside National ID <br>
													2. Backside National ID image should not be greater than 4MB. <br>
													3. Backside National ID image should of the format: jpeg,png,jpg,gif,svg. <br></p>
												</div>
											</div>
											<p></p>
											<div class="row">
												<div class="col-lg-6">
													<h2 class="title-h2">Passport Size Photo</h2>
												</div>
											</div>
											<hr>								
											<div class="row">
											    @if(!empty($mkyc->pphoto1))
												<div class="col-md-3 mb-3">
													<div class="zoom-gallery">
														<a class="float-left" href="{{asset('merchants/photos/'.$mkyc->pphoto1)}}" title="{{$mkyc->dir_one_nid}}">
															<img src="{{asset('merchants/photos/'.$mkyc->pphoto1)}}" alt="" width="275">
														</a>
													</div>
												</div>
												@endif
												<div class="col-md-3">
													<input type="file" name="passport_one" id="passport_one" accept="image/*" />
												</div>
												<div class="col-md-6">
													<p>1. Please upload a cropped image of your passport photo. <br>
														2. Passport Photo image should not be greater than 4MB. <br>
														3. Passport Photo image should of the format: jpeg,png,jpg,gif,svg. <br></p>
												</div>
											</div>
											<hr>
											<div class="row">
												<div class="col-lg-6">
												    @if(!empty($mkyc->id))
													<input type="hidden" name="mid" value="{{ $mkyc->id }}">
													<input type="hidden" name="pid" value="NA12092023">
													@else
													<input type="hidden" name="mid" value="NA12092023">
													<input type="hidden" name="pid" value="{{ $partner->id }}">
													@endif
													<input class="btn btn-success btn-send" type="submit" value=" Update KYC Documents for Director One ">
												</div>
											</div>
										</form>
									</div>
								</div>
								<div class="tab-pane" id="dir2docs" role="tabpanel">
									<div class="container" >
										<form method="post" action="{{ route('update.kyc.director.two') }}" enctype="multipart/form-data">
											@csrf
											<div class="row">
												<div class="col-md-6">
													<div class="form-group">
														<label>First Name</label>
														@if(!empty($mkyc->dir_two_name))
														<input class="form-control{{ $errors->has('dir_two_name') ? ' is-invalid' : '' }}" type="text" name="dir_two_name" id="dir_two_name" required="required" value="{{ $mkyc->dir_two_name }}" placeholder="Director name...">
														@else
														<input class="form-control{{ $errors->has('dir_two_name') ? ' is-invalid' : '' }}" type="text" name="dir_two_name" id="dir_two_name" value="" placeholder="Director name...">
														@endif
														@if ($errors->has('dir_two_name'))
															<span class="invalid-feedback">
																<strong>{{ $errors->first('dir_two_name') }}</strong>
															</span>
														@endif
													</div>
												</div>	
												<div class="col-md-6">
													<div class="form-group">
														<label>Last Name</label>
														@if(!empty($mkyc->dir_two_lname))
														<input class="form-control{{ $errors->has('dir_two_lname') ? ' is-invalid' : '' }}" type="text" name="dir_two_lname" id="dir_two_lname" required="required" value="{{ $mkyc->dir_two_lname }}" placeholder="Director last name...">
														@else
														<input class="form-control{{ $errors->has('dir_two_lname') ? ' is-invalid' : '' }}" type="text" name="dir_two_lname" id="dir_two_lname" value="" placeholder="Director last name...">
														@endif
														@if ($errors->has('dir_two_lname'))
															<span class="invalid-feedback">
																<strong>{{ $errors->first('dir_two_lname') }}</strong>
															</span>
														@endif
													</div>
												</div>
											</div>
											<div class="row">
												<div class="col-md-6">
													<div class="form-group">
														<label>National ID Number</label>
														@if(!empty($mkyc->dir_two_nid))
														<input class="form-control{{ $errors->has('dir_two_nid') ? ' is-invalid' : '' }}" type="text" name="dir_two_nid" id="dir_two_nid" required="required" value="{{ $mkyc->dir_two_nid }}" placeholder="e.g 112233/44/5">
														@else
														<input class="form-control{{ $errors->has('dir_two_nid') ? ' is-invalid' : '' }}" type="text" name="dir_two_nid" id="dir_two_nid" value="" placeholder="e.g 112233/44/5">
														@endif
														@if ($errors->has('dir_two_nid'))
															<span class="invalid-feedback">
																<strong>{{ $errors->first('dir_two_nid') }}</strong>
															</span>
														@endif
													</div>
												</div>
											</div>
											<div class="row">
												<div class="col-lg-6">
													<h2 class="title-h2">National ID: Frontside</h2>
												</div>
											</div>
											<hr>                            
											<div class="row">
											    @if(!empty($mkyc->national_pic_front_dir_two))
												<div class="col-md-3 mb-3">
													<div class="zoom-gallery">
														<a class="float-left" href="{{asset('merchants/nationalids/'.$mkyc->national_pic_front_dir_two)}}" title="{{$mkyc->dir_two_nid}}">
														  <img src="{{asset('merchants/nationalids/'.$mkyc->national_pic_front_dir_two)}}" alt="" width="275">
													   </a>
													</div>
												</div>
												@endif
												<div class="col-md-3">
													<input type="file" name="natid_front_dir_two" id="natid_front_dir_two" accept="image/*" />
												</div>
												<div class="col-md-6">
													<p>1. Please upload a cropped image of your Frontside National ID <br>
													2. Backside National ID image should not be greater than 4MB. <br>
													3. Backside National ID image should of the format: jpeg,png,jpg,gif,svg. <br></p>
												</div>
											</div>
											<div class="row">
												<div class="col-lg-6">
													<h2 class="title-h2">National ID: Backside</h2>
												</div>
											</div>
											<hr>                            
											<div class="row">
											    @if(!empty($mkyc->national_pic_back_dir_two))
											    <div class="col-md-3 mb-3">
													<div class="zoom-gallery">
														<a class="float-left" href="{{asset('merchants/nationalids/'.$mkyc->national_pic_back_dir_two)}}" title="{{$mkyc->dir_two_nid}}">
														  <img src="{{asset('merchants/nationalids/'.$mkyc->national_pic_back_dir_two)}}" alt="" width="275">
													   </a>
													</div>
												</div>
												@endif
												<div class="col-md-3">
													<input type="file" name="natid_back_dir_two" id="natid_back_dir_two" accept="image/*" />
												</div>
												<div class="col-md-6">
													<p>1. Please upload a cropped image of your Backside National ID <br>
													2. Backside National ID image should not be greater than 4MB. <br>
													3. Backside National ID image should of the format: jpeg,png,jpg,gif,svg. <br></p>
												</div>
											</div>
											<div class="row">
												<div class="col-lg-6">
													<h2 class="title-h2">Passport Size Photo</h2>
												</div>
											</div>
											<hr>                            
											<div class="row">
											    @if(!empty($mkyc->pphoto2))
											    <div class="col-md-3 mb-3">
													<div class="zoom-gallery">
														<a class="float-left" href="{{asset('merchants/photos/'.$mkyc->pphoto2)}}" title="{{$mkyc->dir_two_nid}}">
														  <img src="{{asset('merchants/photos/'.$mkyc->pphoto2)}}" alt="" width="275">
														</a>
													</div>
												</div>
												@endif
												<div class="col-md-3">
													<input type="file" name="passport_two" id="passport_two" accept="image/*" />
												</div>
												<div class="col-md-6">
													<p>1. Please upload a cropped image of your passport photo. <br>
														2. Passport Photo image should not be greater than 4MB. <br>
														3. Passport Photo image should of the format: jpeg,png,jpg,gif,svg. <br></p>
												</div>
											</div>
											<hr>
											<div class="row">
												<div class="col-lg-6">
													@if(!empty($mkyc->id))
													<input type="hidden" name="mid" value="{{ $mkyc->id }}">
													<input type="hidden" name="pid" value="NA12092023">
													@else
													<input type="hidden" name="mid" value="NA12092023">
													<input type="hidden" name="pid" value="{{ $partner->id }}">
													@endif
													<input class="btn btn-success btn-send" type="submit" value="Update KYC Documents for Director 2">
												</div>
											</div>
										</form>
									</div>
								</div>--}}
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
        document.getElementById('other_details').style.visibility = "visible";
        function businessTypeChanged(){

            if(document.getElementById('business_type').value != "Sole Trader"){
                document.getElementById('other_details').style.visibility = "visible";
            }
            else
            {
                document.getElementById('other_details').style.visibility = "hidden";
            }
        }
    </script>

    <script src="{{ asset('js/select2.min.js')}}"></script>

    <script type="text/javascript">
        $("#partner_type").select2({
            placeholder: 'Please select your partner type',
            allowClear:true,
        });

        $("#business_type").select2({
            placeholder: 'Please select your business type',
            allowClear:true,
        });

        $("#yearsTrading").select2({
            placeholder: 'Please select your years in Business',
            allowClear:true,
        });

        $("#province").select2({
            placeholder: 'Please select your province',
            allowClear:true,
        });

        $("#country").select2({
            placeholder: 'Please select your country',
            allowClear:true,
        });

        $("#branch").select2({
            placeholder: 'Please select your bank branch name',
            allowClear:true,
        }).change(function(){
            var price = $(this).children('option:selected').data('price');
            $('#branch_code').val(price);
        });

        $('#bank').select2({
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
