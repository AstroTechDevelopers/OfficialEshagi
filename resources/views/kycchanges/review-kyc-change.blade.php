<?php
/**
 *Created by PhpStorm for eshagi
 *User: Vincent Guyo
 *Date: 10/19/2020
 *Time: 3:36 AM
 */

?>
@extends('layouts.app')

@section('template_title')
    Review {{$client->natid}} KYC
@endsection

@section('template_linked_css')
    <link href="{{asset('assets/libs/magnific-popup/magnific-popup.css')}}" rel="stylesheet" type="text/css" />
@endsection

@section('content')
    <div class="page-title-box">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-md-4">
                    <h4 class="page-title mb-1">KYC Change Request</h4>
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{url('/kycs')}}">KYCs</a></li>
                        <li class="breadcrumb-item active">Reviewing KYC for {{$client->natid}}</li>
                    </ol>
                </div>

                <div class="col-md-8">
                    <div class="float-right d-none d-md-block">


                        @if ($kycchange->status == false)
                        <div class="float-right d-none d-md-block">
                            <div>
                                <a class="btn btn-info btn-rounded" href="{{url('kyc-change/'.$kycchange->id)}}" type="button">
                                    <i class="mdi mdi-account-check-outline mr-1"></i>Approve KYC Change Request
                                </a>
                            </div>
                        </div>

                            <div class="float-right d-none d-md-block">
                            <div>
                                {!! Form::open(array('url' => 'kycchanges/'.$kycchange->id, 'class' => 'btn btn-sm btn-danger ')) !!}
                                {!! Form::hidden('_method', 'DELETE') !!}
                                {!! Form::button('<i class="mdi mdi-account-remove-outline mr-1" aria-hidden="true"></i> Decline Request' , array('class' => 'btn btn-sm btn-danger ','type' => 'button', 'data-toggle' => 'modal', 'data-target' => '#confirmDelete', 'data-title' => 'Decline KYC Change Request', 'data-message' => 'Are you sure you want to decline this KYC change request ? This will also automatically delete the request.')) !!}
                                {!! Form::close() !!}
                                @include('modals.modal-delete')
                            </div>
                        </div>
                        @endif
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
                                    <a class="nav-link active" data-toggle="tab" href="#current-details" role="tab">
                                        <i class="fas fa-user mr-1 align-middle"></i> <span class="d-none d-md-inline-block">Current Details</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#change-request" role="tab">
                                        <i class="fas fa-user-friends mr-1 align-middle"></i> <span class="d-none d-md-inline-block">Change Request</span>
                                    </a>
                                </li>
                            </ul>

                            <div class="tab-content p-3">
                                <div class="tab-pane active" id="current-details" role="tabpanel">
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
                                            <label for="salary">Gross Salary</label>
                                            <input type="text" class="form-control" id="gross" placeholder="e.g. 66000.00" value="{{$client->gross}}" required>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4 mb-3">
                                            <label for="salary">Net Salary</label>
                                            <input type="text" class="form-control" id="salary" placeholder="e.g. 60000.00" value="{{$client->salary}}" required>
                                        </div>
                                        <div class="col-md-8 mb-3">
                                            <label for="payslip_pic">Current Payslip</label>
                                            <div class="zoom-gallery">
                                                <a class="float-left" href="{{asset('project/public/payslips/'.$kyc->payslip_pic)}}" title="{{$client->natid}}">
                                                    <img src="{{asset('project/public/payslips/'.$kyc->payslip_pic)}}" alt="" width="275">
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane" id="change-request" role="tabpanel">
                                    <div class="row">
                                            <div class="col-lg-4 mb-3">
                                                <label for="mobile">Phone Number</label>
                                                <input type="text" class="form-control" id="mobile" placeholder="e.g. 773418009" value="+263{{$kycchange->mobile_no}}" required>
                                            </div>
                                            <div class="col-lg-4 mb-3">
                                                <label for="salary">Gross Salary</label>
                                                <input type="text" class="form-control" id="gross" placeholder="e.g. 66000.00" value="{{$kycchange->gross}}" required>
                                            </div>
                                            <div class="col-lg-4 mb-3">
                                                <label for="net">Net Salary</label>
                                                <input type="text" class="form-control" id="net" placeholder="e.g. 60000.00" value="{{$kycchange->net}}" required>
                                            </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="payslip_pic">Payslip</label>
                                            <div class="zoom-gallery">
                                                <a class="float-left" href="{{asset('project/public/payslips/'.$kyc->payslip_pic)}}" title="{{$client->natid}}">
                                                    <img src="{{asset('project/public/payslips/'.$kyc->payslip_pic)}}" alt="" width="275">
                                                </a>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">

                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-lg-4 mb-3">
                                            <label for="status">Status</label>
                                            <input type="text" class="form-control" id="status" value="@if ($kycchange->status == true) Approved @else Not Yet Actioned @endif" >
                                        </div>
                                        <div class="col-lg-4 mb-3">
                                            <label for="reviewer">Reviewed By</label>
                                            <input type="text" class="form-control" id="reviewer" placeholder="e.g. jdoe" value="{{$kycchange->reviewer}}">
                                        </div>
                                        <div class="col-lg-4 mb-3">
                                            <label for="updated_at">Last Modified</label>
                                            <input type="text" class="form-control" id="updated_at" placeholder="e.g. 2020-01-31 14:00:32" value="{{$kycchange->updated_at}}" >
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
    @include('scripts.delete-modal-script')
    <script src="{{asset('assets/libs/magnific-popup/jquery.magnific-popup.min.js')}}"></script>
    <script src="{{asset('assets/js/pages/lightbox.init.js')}}"></script>
@endsection
