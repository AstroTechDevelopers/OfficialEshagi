<?php
/**
 * Created by PhpStorm for eshagitwo
 * User: VinceGee
 * Date: 12/6/2021
 * Time: 1:33 AM
 */ ?>
@extends('layouts.app')

@section('template_title')
    View {{$client->natid}} KYC
@endsection

@section('template_linked_css')
    <link href="{{asset('assets/libs/magnific-popup/magnific-popup.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('css/select2.min.css')}}" rel="stylesheet" />

@endsection

@section('content')
    <div class="page-title-box">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-md-4">
                    <h4 class="page-title mb-1">Know Your Customer</h4>
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{url('/kycs')}}">KYCs</a></li>
                        <li class="breadcrumb-item active">Reviewing Bot KYC Application for {{$client->natid}}</li>
                    </ol>
                </div>

                <div class="col-md-8">
                    <div class="float-right d-none d-md-block">
                        <div class="float-right d-none d-md-block">

                        </div>

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
                            <p class="card-title-desc">KYC Created At: {{$client->created_at}}
                                <br>
                            </p>
                            <ul class="nav nav-tabs nav-justified nav-tabs-custom" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" data-toggle="tab" href="#custom-details" role="tab">
                                        <i class="fas fa-user mr-1 align-middle"></i> <span class="d-none d-md-inline-block">Client Details</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#custom-kin" role="tab">
                                        <i class="fas fa-address-card mr-1 align-middle"></i> <span class="d-none d-md-inline-block">KYC Documents</span>
                                    </a>
                                </li>
                            </ul>
                            <div class="tab-content p-3">
                                <div class="tab-pane active" id="custom-details" role="tabpanel">
                                    <div class="row">
                                        <div class="col-md-4 mb-3">
                                            <label for="title">Title</label>
                                            <input type="text" class="form-control" id="title" name="title" placeholder="e.g. Mr" value="{{$client->title}}" required>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4 mb-3">
                                            <label for="first_name">First name</label>
                                            <input type="text" class="form-control" id="first_name" name="first_name" placeholder="e.g. John" value="{{$client->first_name}}" required>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label for="last_name">Last name</label>
                                            <input type="text" class="form-control" id="last_name" name="last_name" placeholder="e.g. Doe" value="{{$client->last_name}}" required>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label for="dob">Date of Birth</label>
                                            <input type="text" class="form-control" id="dob" name="dob" placeholder="e.g. 1989-01-31" value="{{date_format($client->dob, 'Y-m-d')}}" required>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4 mb-3">
                                            <label for="gender">Gender</label>
                                            <input type="text" class="form-control" id="gender" name="gender" placeholder="e.g. Male" value="{{$client->gender}}" required>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label for="marital_state">Marital Status</label>
                                            <input type="text" class="form-control" id="marital_state" name="marital_state" placeholder="e.g. Single" value="{{$client->marital_state}}" required>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label for="natid">National ID Number</label>
                                            <input type="text" class="form-control" id="natid" name="natid" placeholder="e.g. 63-2321066-F-71" value="{{$client->natid}}" required>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4 mb-3">
                                            <label for="mobile">Phone Number</label>
                                            <input type="text" class="form-control" id="mobile" name="mobile" placeholder="e.g. 773418009" value="{{$client->mobile}}" required>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label for="email">Email</label>
                                            <input type="text" class="form-control" id="email" name="email" placeholder="e.g. johndoe@gmail.com" value="{{$client->email}}">
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label for="salary">Salary</label>
                                            <input type="text" class="form-control" id="salary" name="salary" placeholder="e.g. 66000.00" value="{{$client->salary}}" required>
                                        </div>
                                    </div>

                                </div>
                                <div class="tab-pane" id="custom-kin" role="tabpanel">
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="national_pic">National ID</label>
                                            <div class="zoom-gallery">
                                                <a class="float-left" href="{{asset('eshagitwo/public/nationalids/'.$botApplication->national_pic) ?? ''}}" title="{{$client->natid}}">
                                                    <img src="{{asset('eshagitwo/public/nationalids/'.$botApplication->national_pic) ?? ''}}" alt="" width="275">
                                                </a>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="passport_pic">Passport Photo</label>
                                            <div class="zoom-gallery">
                                                <a class="float-left" href="{{asset('eshagitwo/public/pphotos/'.$botApplication->passport_pic) ?? ''}}" title="{{$client->natid}}">
                                                    <img src="{{asset('eshagitwo/public/pphotos/'.$botApplication->passport_pic) ?? ''}}" alt="" width="275">
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
    <script src="{{ asset('js/select2.min.js')}}"></script>

    <script type="text/javascript">
        $("#title").select2({
            placeholder: 'Please select client title',
            allowClear:true,
        });
    </script>

    <script src="{{asset('assets/libs/magnific-popup/jquery.magnific-popup.min.js')}}"></script>
    <script src="{{asset('assets/js/pages/lightbox.init.js')}}"></script>

@endsection
