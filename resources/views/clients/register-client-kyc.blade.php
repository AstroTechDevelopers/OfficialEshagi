<?php
/**
 *Created by PhpStorm for eshagi
 *User: Vincent Guyo
 *Date: 10/2/2020
 *Time: 10:59 AM
 *
 * Modified By : Vusiaul Studi Code AstroCred Zambia
 * User: Tushar Patil
 * Date: 05/09/2023
 * Time: 11:30 AM
 */
?>
@extends('layouts.app')

@section('template_title')
    Upload Client KYC
@endsection

@section('content')
    <div class="page-title-box">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h4 class="page-title mb-1">Clients</h4>
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Clients</a></li>
                        <li class="breadcrumb-item active">Register Client</li>
                    </ol>
                </div>

                <div class="col-md-6">
                    <div class="float-right d-none d-md-block">
                        <div>
                            <h1 class="text-white">Step 3 of 5</h1>
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
                            <div class="container">
                                <div class="hero-text">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <h1>You're nearly there!</h1>
                                            @include('partials.fe-status')
                                            <p class="wow fadeInUp" data-wow-duration="1s" data-wow-delay=".2s">Great, {{auth()->user()->first_name}} please upload Your Documents</p>
                    
                                            <div class="container" >
                    							<div class="row">
                    								<div class="col-lg-6">
                    									<h2 class="title-h2">National ID : Frontside</h2>
                    								</div>
                    							</div>
                    							<hr>
                                                @if ($yuser->national_stat == true)
                                                    <div class="row">
                                                        <div class="col-lg-6">
                                                            <img src="{{asset('nationalids/'.$yuser->national_pic)}}" id="uploadedID" width="80" height="80" />
                                                        </div>
                                                    </div>
                    
                                                    <form method="post" action="{{ route('uploadClientNatID') }}" enctype="multipart/form-data">
                                                        @csrf
                                                        <div class="row">
                                                            <div class="col-lg-6">
                                                                <input type="file" name="natid" id="natid" accept="image/*" />
                                                                <button id="upId" class="btn btn-blue">Upload</button>
                                                            </div>
                                                            <div class="col-lg-6">
                                                                <p>1. Please make sure you uploaded a cropped image of your National ID </p>
                                                            </div>
                                                        </div>
                    									<input type="hidden" name="clientnationalid" value="{{$yuser->natid}}">
                                                    </form>
                                                @else
                                                    <form method="post" action="{{ route('uploadClientNatID') }}" enctype="multipart/form-data">
                                                        @csrf
                                                        <div class="row">
                                                            <div class="col-lg-6">
                                                                <input type="file" name="natid" id="natid" accept="image/*" required/>
                                                                <button id="upId" class="btn btn-blue">Upload</button>
                                                            </div>
                                                            <div class="col-lg-6">
                                                                <p>1. Please upload a cropped image of your National ID <br>
                                                                2. National ID image should not be greater than 4MB. <br>
                                                                3. National ID image should of the format: jpeg,png,jpg,gif,svg. <br></p>
                                                            </div>
                                                        </div>
                    									<input type="hidden" name="clientnationalid" value="{{$yuser->natid}}">
                                                    </form>
                                                @endif
                                                <p></p>
                    							<form>
                                                    <div class="row">
                                                        <div class="col-lg-6">
                                                            <h2 class="title-h2">National ID : Backside</h2>
                                                        </div>
                                                    </div>
                                                    <hr>
                                                </form>
                                                @if ($yuser->national_stat_back == true)
                                                    <div class="row">
                                                        <div class="col-lg-6">
                                                            <img src="{{asset('nationalids/'.$yuser->national_pic_back)}}" id="uploadedIDBack" width="80" height="80" />
                                                        </div>
                                                    </div>
                                                    <form method="post" action="{{ route('uploadClientNatIDBack') }}" enctype="multipart/form-data">
                                                        @csrf
                                                        <div class="row">
                                                            <div class="col-lg-6">
                                                                <input type="file" name="natidback" id="natidback" accept="image/*" required/>
                                                                <button id="upPhoto" class="btn btn-blue">Upload</button>
                                                            </div>
                                                            <div class="col-lg-6">
                                                                <p>1. Please make sure you uploaded a cropped image of your passport photo. </p>
                                                            </div>
                                                        </div>
                    									<input type="hidden" name="clientnationalid" value="{{$yuser->natid}}">
                                                    </form>
                                                @else
                                                    <form method="post" action="{{ route('uploadClientNatIDBack') }}" enctype="multipart/form-data">
                                                        @csrf
                                                        <div class="row">
                                                            <div class="col-lg-6">
                                                                <input type="file" name="natidback" id="natidback" accept="image/*" required/>
                                                                <button id="upId" class="btn btn-blue">Upload</button>
                                                            </div>
                                                            <div class="col-lg-6">
                                                                <p>1. Please upload a cropped image of your Backside National ID <br>
                                                                2. Backside National ID image should not be greater than 4MB. <br>
                                                                3. Backside National ID image should of the format: jpeg,png,jpg,gif,svg. <br></p>
                                                            </div>
                                                        </div>
                    									<input type="hidden" name="clientnationalid" value="{{$yuser->natid}}">
                                                    </form>
                                                @endif
                    							<p></p>
                                                <form>
                                                    <div class="row">
                                                        <div class="col-lg-6">
                                                            <h2 class="title-h2">Passport Size Photo</h2>
                                                        </div>
                                                    </div>
                                                    <hr>
                                                </form>
                                                @if ($yuser->passport_stat == true)
                                                    <div class="row">
                                                        <div class="col-lg-6">
                                                            <img src="{{asset('pphotos/'.$yuser->passport_pic)}}" id="uploadedID" width="80" height="80" />
                                                        </div>
                                                    </div>
                                                    <form method="post" action="{{ route('uploadClientPPhoto') }}" enctype="multipart/form-data">
                                                        @csrf
                                                        <div class="row">
                                                            <div class="col-lg-6">
                                                                <input type="file" name="passport" id="passport" accept="image/*" />
                                                                <button id="upPhoto" class="btn btn-blue">Upload</button>
                                                            </div>
                                                            <div class="col-lg-6">
                                                                <p>1. Please make sure you uploaded a cropped image of your passport photo. </p>
                                                            </div>
                                                        </div>
                    									<input type="hidden" name="clientnationalid" value="{{$yuser->natid}}">
                                                    </form>
                                                @else
                                                    <form method="post" action="{{ route('uploadClientPPhoto') }}" enctype="multipart/form-data">
                                                        @csrf
                                                        <div class="row">
                                                            <div class="col-lg-6">
                                                                <input type="file" name="passport" id="passport" accept="image/*" required/>
                                                                <button id="upPhoto" class="btn btn-blue">Upload</button>
                                                            </div>
                                                            <div class="col-lg-6">
                                                                <p>1. Please upload a cropped image of your passport photo. <br>
                                                                    2. Passport Photo image should not be greater than 4MB. <br>
                                                                    3. Passport Photo image should of the format: jpeg,png,jpg,gif,svg. <br></p>
                                                            </div>
                                                        </div>
                    									<input type="hidden" name="clientnationalid" value="{{$yuser->natid}}">
                                                    </form>
                                                @endif
                    
                    
                                                <form>
                                                    <div class="row">
                                                        <div class="col-lg-6">
                                                            <h2 class="title-h2">Current Payslip</h2>
                                                        </div>
                                                    </div>
                                                    <hr>
                                                </form>
                                                @if ($yuser->payslip_stat == true)
                                                    <div class="row">
                                                        <div class="col-lg-6">
                                                            <img src="{{asset('payslips/'.$yuser->payslip_pic)}}" id="uploadedID" width="80" height="80" />
                                                        </div>
                    									@if ($yuser->payslip_stat_2 == true)
                    									<div class="col-lg-6">
                                                            <img src="{{asset('payslips/'.$yuser->payslip_pic_2)}}" id="uploadedID" width="80" height="80" />
                                                        </div>
                    									@endif
                    									@if ($yuser->payslip_stat_3 == true)
                    									<div class="col-lg-6">
                                                            <img src="{{asset('payslips/'.$yuser->payslip_pic_3)}}" id="uploadedID" width="80" height="80" />
                                                        </div>
                    									@endif
                    									@if ($yuser->payslip_stat_4 == true)
                    									<div class="col-lg-6">
                                                            <img src="{{asset('payslips/'.$yuser->payslip_pic_4)}}" id="uploadedID" width="80" height="80" />
                                                        </div>
                    									@endif
                                                    </div>
                                                    <form method="post" action="{{ route('uploadClientpayslip') }}" enctype="multipart/form-data">
                                                        @csrf
                                                        <div class="row">
                                                            <div class="col-lg-6">
                                                                <input type="file" name="payslip" id="payslip" accept="image/*" />
                                                                <input type="file" name="payslip2" id="payslip2" accept="image/*" /><br /><br />
                                                                <input type="file" name="payslip3" id="payslip3" accept="image/*" /><br /><br />
                                                                <input type="file" name="payslip4" id="payslip4" accept="image/*" /><br /><br />
                                                                <button id="upPay" class="btn btn-blue">Upload Payslips</button>
                    											
                                                            </div>
                                                            <div class="col-lg-6">
                                                                <p>1. Please make sure you uploaded a cropped image of your most recent payslip. </p>
                                                            </div>										
                                                        </div>
                    									<input type="hidden" name="clientnationalid" value="{{$yuser->natid}}">
                                                    </form>
                                                @else
                                                    <form method="post" action="{{ route('uploadClientpayslip') }}" enctype="multipart/form-data">
                                                        @csrf
                                                        <div class="row">
                                                            <div class="col-lg-6">
                                                                <input type="file" name="payslip" id="payslip" accept="image/*" required /><br /><br />
                                                                <input type="file" name="payslip2" id="payslip2" accept="image/*" /><br /><br />
                                                                <input type="file" name="payslip3" id="payslip3" accept="image/*" /><br /><br />
                                                                <input type="file" name="payslip4" id="payslip4" accept="image/*" /><br /><br />
                                                                <button id="upPay" class="btn btn-blue">Upload Payslips</button>
                                                            </div>										
                                                            <div class="col-lg-6">
                                                                <p>1. Please upload a cropped image of your most recent payslip. <br>
                    											    2. You can upload payslip upto 4 page separatly by using all BROWSE (FILE UPLOAD) options. <br>
                                                                    3. Each Payslip image should not be greater than 4MB. <br>
                                                                    4. Each Payslip image should of the format: jpeg,png,jpg,gif,svg. <br></p>
                                                            </div>
                                                        </div>
                                                        <input type="hidden" name="clientnationalid" value="{{$yuser->natid}}"> 									
                                                    </form>
                                                @endif
                    							<p></p>
                                                <form>
                                                    <div class="row">
                                                        <div class="col-lg-6">
                                                            <h2 class="title-h2">Employee Approval Letter</h2>
                                                        </div>
                                                    </div>
                                                    <hr>
                                                </form>
                                                @if ($yuser->emp_approval_stat == true)
                                                    <div class="row">
                                                        <div class="col-lg-6">
                                                            <img src="{{asset('empletters/'.$yuser->emp_approval_letter)}}" id="uploadedID" width="80" height="80" />
                                                        </div>
                                                    </div>
                                                    <form method="post" action="{{ route('uploadClientEmpApproval') }}" enctype="multipart/form-data">
                                                        @csrf
                                                        <div class="row">
                                                            <div class="col-lg-6">
                                                                <input type="file" name="emp_letter" id="emp_letter" accept="image/*" />
                                                                <button id="upPay" class="btn btn-blue">Upload</button>
                                                            </div>
                                                            <div class="col-lg-6">
                                                                <p>1. Please make sure you uploaded a cropped image of approval letter from your employee. </p>
                                                            </div>
                                                        </div>
                    									<input type="hidden" name="clientnationalid" value="{{$yuser->natid}}">
                                                    </form>
                                                @else
                                                    <form method="post" action="{{ route('uploadClientEmpApproval') }}" enctype="multipart/form-data">
                                                        @csrf
                                                        <div class="row">
                                                            <div class="col-lg-6">
                                                                <input type="file" name="emp_letter" id="emp_letter" accept="image/*" required />
                                                                <button id="upPay" class="btn btn-blue">Upload</button>
                                                            </div>
                                                            <div class="col-lg-6">
                                                                <p>1. Please upload a cropped image of appvoal letter from your employee. <br>
                                                                    2. Approval letter image should not be greater than 4MB. <br>
                                                                    3. Approval letter image should of the format: jpeg,png,jpg,gif,svg. <br></p>
                                                            </div>
                                                        </div>
                    									<input type="hidden" name="clientnationalid" value="{{$yuser->natid}}">
                                                    </form>.
                                                @endif
                                                <hr>
                                                @if($yuser->national_stat == true AND $yuser->passport_stat == true AND $yuser->payslip_stat == true AND $yuser->emp_approval_stat == true)
                                                    <div class="row">
                                                        <div class="col-lg-6">
                                                            <a href="{{url('new-partner-loan')}}" class="btn btn-success btn-send">Finished, Apply for Cash loan</a>
                                                        </div>
                                                        <div class="col-lg-6">
                                                            <a href="{{url('new-partner-credit')}}" class="btn btn-success btn-send float-right">Finished, Now the Product Loan</a>
                                                        </div>
                                                        <br>
                                                    </div>
                                                    <br>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="status">
                                    <div class="col-lg-12">
                                        <h3>
                                            <div class="d-none d-lg-block">
                                            </div>
                                        </h3>
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
