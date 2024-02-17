<?php
/**
 *Created by PhpStorm for eshagi
 *User: Vincent Guyo
 *Date: 10/13/2020
 *Time: 11:38 AM
 */

?>
@extends('layouts.app')

@section('template_title')
    {{ $client->natid }} Info
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
                        <li class="breadcrumb-item active">{{ $client->natid}} info</li>
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
                            <img class="card-img-top img-fluid" src="{{asset('pphotos/'.$kyc->passport_pic)}}" alt="{{ $client->name }}">
                            <div class="card-body">
                                <h4 class="card-title font-size-16 mt-0">{{$client->first_name.' '.$client->last_name}}</h4>
                                <p class="card-text">{{$client->natid}}</p>
                                <p class="card-text">{{$client->email}}</p>
                                <p class="card-text">+263{{$client->mobile}}</p>
                                <p class="card-text">Sector: {{$client->emp_sector}}</p>
                                <p class="card-text">EC Number: {{$client->ecnumber}}</p>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="col-xl-9">
                    <div class="card">
                        <div class="card-body">

                            <h4 class="header-title">{{$client->first_name.' '.$client->last_name}} Details</h4>

                            <div class="row">

                                <table class="table table-striped mb-0">
                                    <tr>
                                        <td>Full Name</td>
                                        <td><strong>{{$client->first_name.' '.$client->last_name}}</strong></td>
                                        <td>National ID Number</td>
                                        <td><strong>{{$client->natid}}</strong></td>
                                    </tr>
                                    <tr>
                                        <td>Date of Birth</td>
                                        <td><strong>{{date_format($client->dob, 'd F Y')}}</strong></td>
                                        <td>Gender</td>
                                        <td><strong>{{$client->gender}}</strong></td>
                                    </tr>
                                    <tr>
                                        <td>Marital State</td>
                                        <td><strong>{{$client->marital_state}}</strong></td>
                                        <td>Title</td>
                                        <td><strong>{{$client->title}}</strong></td>
                                    </tr>
                                    <tr>
                                        <td>Address</td>
                                        <td><strong>{{$client->house_num.' '.$client->street.', '.$client->surburb.', '.$client->city}}</strong></td>
                                        <td>Home Type</td>
                                        <td><strong>{{$client->home_type}}</strong></td>
                                    </tr>
                                    <tr>
                                        <td>Client Bank</td>
                                        <td><strong>{{$bank->bank}}</strong></td>
                                        <td>Branch</td>
                                        <td><strong>{{$kyc->branch}}</strong></td>
                                    </tr>
                                    <tr>
                                        <td>Bank Branch Code</td>
                                        <td><strong>{{$kyc->branch_code}}</strong></td>
                                        <td>Account Number</td>
                                        <td><strong>{{$kyc->acc_number}}</strong></td>
                                    </tr>
                                    <tr>
                                        <td>Employee</td>
                                        <td><strong>{{$client->employer}}</strong></td>
                                        <td>Employment Sector</td>
                                        <td><strong>{{$client->emp_sector}}</strong></td>
                                    </tr>
                                    <tr>
                                        <td>EC Number</td>
                                        <td><strong>{{$client->ecnumber}}</strong></td>
                                        <td>Salary</td>
                                        <td><strong>K{{$client->salary}}</strong></td>
                                    </tr>
                                    <tr>
                                        <td>Profession</td>
                                        <td><strong>{{$ssbDetails->profession}}</strong></td>
                                        <td>Other Sources of Income</td>
                                        <td><strong>{{$ssbDetails->sourcesOfIncome}}</strong></td>
                                    </tr>
                                    <tr>
                                        <td>HR Contact Name</td>
                                        <td><strong>{{$ssbDetails->hr_contact_name}}</strong></td>
                                        <td>HR Position</td>
                                        <td><strong>{{$ssbDetails->hr_position}}</strong></td>
                                    </tr>
                                    <tr>
                                        <td>HR Email</td>
                                        <td><strong>{{$ssbDetails->hr_email}}</strong></td>
                                        <td>HR Phone</td>
                                        <td><strong>{{$ssbDetails->hr_telephone}}</strong></td>
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
