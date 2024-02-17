<?php
/**
 * Created by PhpStorm for eshagitwo
 * User: VinceGee
 * Date: 5/4/2022
 * Time: 12:39 PM
 */ ?>
@extends('layouts.app')

@section('template_title')
    {{ $zambian->nrc }} Info
@endsection

@section('template_fastload_css')
    #map-canvas{
    min-height: 300px;
    height: 100%;
    width: 100%;
    }
@endsection

@section('content')
    <div class="page-title-box">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h4 class="page-title mb-1">Client Info</h4>
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Clients</a></li>
                        <li class="breadcrumb-item active">{{ $zambian->nrc}} info</li>
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
                <div class="col-xl-3">
                    <div class="card">
                        <div class="card-body">
                            <img class="card-img-top img-fluid" src="{{asset('zam_pphotos/'.$zambian->pass_photo)}}" alt="{{ $zambian->first_name }}">
                            <div class="card-body">
                                <h4 class="card-title font-size-16 mt-0">{{$zambian->first_name.' '.$zambian->last_name}}</h4>
                                <p class="card-text">{{$zambian->nrc}}</p>
                                <p class="card-text">{{$zambian->email}}</p>
                                <p class="card-text">+260{{$zambian->mobile}}</p>
                                <p class="card-text">Credit Score: {{$zambian->credit_score}}</p>
                                <p class="card-text">EC Number: {{$zambian->ec_number}}</p>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="col-xl-9">
                    <div class="card">
                        <div class="card-body">

                            <h4 class="header-title">{{$zambian->first_name.' '.$zambian->last_name}} Details</h4>

                            <div class="row">

                                <table class="table table-striped mb-0">
                                    <tr>
                                        <td>Full Name</td>
                                        <td><strong>{{$zambian->first_name.' '.$zambian->middle_name.' '.$zambian->last_name}}</strong></td>
                                        <td>National ID Number</td>
                                        <td><strong>{{$zambian->nrc}}</strong></td>
                                    </tr>
                                    <tr>
                                        <td>Date of Birth</td>
                                        <td><strong>{{date_format($zambian->dob, 'd F Y')}}</strong></td>
                                        <td>Gender</td>
                                        <td><strong>{{$zambian->gender}}</strong></td>
                                    </tr>
                                    <tr>
                                        <td>Title</td>
                                        <td><strong>{{getPersonTitle($zambian->title)}}</strong></td>
                                        <td>ZIP Code</td>
                                        <td><strong>{{$zambian->zip_code}}</strong></td>

                                    </tr>
                                    <tr>
                                        <td>Address</td>
                                        <td><strong>{{$zambian->address.', '.$zambian->city}}</strong></td>
                                        <td>Province</td>
                                        <td><strong>{{$zambian->province}}</strong></td>
                                    </tr>
                                    <tr>
                                        <td>Client Bank</td>
                                        <td><strong>{{$zambian->bank_name}}</strong></td>
                                        <td>Branch</td>
                                        <td><strong>{{$zambian->branch}}</strong></td>
                                    </tr>
                                    <tr>
                                        <td>Account Number</td>
                                        <td><strong>{{$zambian->bank_account}}</strong></td>
                                        <td>EC Number</td>
                                        <td><strong>{{$zambian->ec_number}}</strong></td>
                                    </tr>
                                    <tr>
                                        <td>Employer/Business</td>
                                        <td><strong>{{$zambian->business_employer}}</strong></td>
                                        <td>Work Status</td>
                                        <td><strong>{{$zambian->work_status}}</strong></td>
                                    </tr>
                                    <tr>
                                        <td>Credit Score</td>
                                        <td><strong>{{$zambian->credit_score}}</strong></td>
                                        <td>Landline</td>
                                        <td><strong>{{$zambian->landline}}</strong></td>
                                    </tr>
                                    <tr>
                                        <td>Department</td>
                                        <td><strong>{{$zambian->department}}</strong></td>
                                        <td>Institution</td>
                                        <td><strong>{{$zambian->institution}}</strong></td>
                                    </tr>
                                    <tr>
                                        <td>Next of Kin</td>
                                        <td><strong>{{$zambian->kin_name}}</strong></td>
                                        <td>Relationship to Kin</td>
                                        <td><strong>{{$zambian->kin_relationship}}</strong></td>
                                    </tr>
                                    <tr>
                                        <td>Next of Kin Address</td>
                                        <td><strong>{{$zambian->kin_address}}</strong></td>
                                        <td>Kin Phone Number</td>
                                        <td><strong>{{$zambian->kin_relationship}}</strong></td>
                                    </tr>
                                    <tr>
                                        <td>Savings Account #</td>
                                        <td><strong>{{$zambian->savings_acc}}</strong></td>
                                        <td>Savings ID</td>
                                        <td><strong>{{$zambian->savings_id}}</strong></td>
                                    </tr>
                                </table>

                            </div>
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </div>

@endsection

@section('footer_scripts')

@endsection
