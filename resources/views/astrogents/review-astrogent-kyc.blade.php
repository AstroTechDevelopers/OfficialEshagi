<?php
/**
 * Created by PhpStorm for eshagi
 * User: vinceg
 * Date: 20/3/2021
 * Time: 17:33
 */
?>
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
    Review {{$agent->natid}} KYC
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
                        <li class="breadcrumb-item"><a href="{{url('/astrogents')}}">Astrogents KYC</a></li>
                        <li class="breadcrumb-item active">Reviewing KYC for {{$agent->natid}}</li>
                    </ol>
                </div>

                <div class="col-md-8">
                    <div class="float-right d-none d-md-block">

                        <div class="float-right d-none d-md-block">
                            <div>
                                <a class="btn btn-success btn-rounded" href="{{url('approve-astrogent/'.$agent->id)}}" type="button">
                                    <i class="mdi mdi-account-check-outline mr-1"></i>Activate Astrogent
                                </a>
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
                            <h4 class="header-title">Astrogent: {{$agent->first_name .' '. $agent->last_name}}</h4>

                            <ul class="nav nav-tabs nav-justified nav-tabs-custom" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" data-toggle="tab" href="#custom-details" role="tab">
                                        <i class="fas fa-user mr-1 align-middle"></i> <span class="d-none d-md-inline-block">Agent Details</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#custom-banking" role="tab">
                                        <i class="fas fa-money-bill-wave mr-1 align-middle"></i> <span class="d-none d-md-inline-block">Banking Details</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#custom-kycdocs" role="tab">
                                        <i class="fas fa-user-tag mr-1 align-middle"></i> <span class="d-none d-md-inline-block">KYC Documents</span>
                                    </a>
                                </li>
                            </ul>

                            <div class="tab-content p-3">
                                <div class="tab-pane active" id="custom-details" role="tabpanel">
                                    <div class="row">
                                        <div class="col-md-4 mb-3">
                                            <label for="first_name">First name</label>
                                            <input type="text" class="form-control" id="first_name" placeholder="e.g. John" value="{{$agent->first_name}}" required>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label for="last_name">Last name</label>
                                            <input type="text" class="form-control" id="last_name" placeholder="e.g. Doe" value="{{$agent->last_name}}" required>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label for="natid">National ID Number</label>
                                            <input type="text" class="form-control" id="natid" placeholder="e.g. 63-2321066-F-71" value="{{$agent->natid}}" required>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4 mb-3">
                                            <label for="mobile">Phone Number</label>
                                            <input type="text" class="form-control" id="mobile" placeholder="e.g. 773418009" value="+263{{$agent->mobile}}" required>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label for="email">Email</label>
                                            <input type="text" class="form-control" id="email" placeholder="e.g. johndoe@gmail.com" value="{{$agent->email}}">
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label for="salary">Physical Address</label>
                                            <input type="text" class="form-control" id="address" placeholder="e.g. 12 Home Street, Belvedere, Harare" value="{{$agent->address}}" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane" id="custom-banking" role="tabpanel">
                                    <div class="row">
                                        <div class="col-md-4 mb-3">
                                            <label for="bank">Bank Name</label>
                                            <input type="text" class="form-control" id="bank" placeholder="e.g. NedBank" value="{{$bank->bank}}" required>
                                        </div>

                                        <div class="col-md-4 mb-3">
                                            <label for="branch">Branch</label>
                                            <input type="text" class="form-control" id="branch" placeholder="e.g. Jason Moyo" value="{{$agent->branch}}" >
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label for="branch_code">Branch Code</label>
                                            <input type="text" class="form-control" id="branch_code" placeholder="e.g. JH777" value="{{$agent->branch_code}}" >
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4 mb-3">
                                            <label for="bank_acc_name">Bank Account Name</label>
                                            <input type="text" class="form-control" id="bank_acc_name" placeholder="e.g. John Doe" value="{{$agent->bank_acc_name}}" required>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label for="acc_number">Account Number</label>
                                            <input type="text" class="form-control" id="acc_number" placeholder="e.g. 112233445566" value="{{$agent->accountNumber}}" >
                                        </div>

                                    </div>

                                </div>
                                <div class="tab-pane" id="custom-kycdocs" role="tabpanel">
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="national_pic">National ID</label>
                                            <div class="zoom-gallery">
                                                <a class="float-left" href="{{asset('project/public/agentids/'.$agent->natidPic)}}" title="{{$agent->natid}}">
                                                    <img src="{{asset('project/public/agentids/'.$agent->natidPic)}}" alt="" width="275">
                                                </a>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="passport_pic">Signature</label>
                                            <div class="zoom-gallery">
                                                <a class="float-left" href="{{asset('project/public/agentssign/'.$agent->signaturePic)}}" title="{{$agent->natid}}">
                                                    <img src="{{asset('project/public/agentssign/'.$agent->signaturePic)}}" alt="" width="275">
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
