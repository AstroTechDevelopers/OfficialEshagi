<?php
/**
 * Created by PhpStorm for eshagitwo
 * User: VinceGee
 * Date: 6/29/2022
 * Time: 12:17 PM
 */ ?>

@extends('layouts.app')

@section('template_title')
    {{ $zwmb->natid }} Info
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
                    <h4 class="page-title mb-1">ZWMB Client Info</h4>
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">ZWMB Clients</a></li>
                        <li class="breadcrumb-item active">{{ $zwmb->natid}} info</li>
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
                            <img class="card-img-top img-fluid" src="{{asset('pphotos/'.$kyc->passport_pic)}}" alt="{{ $client->first_name }}">
                            <div class="card-body">
                                <h4 class="card-title font-size-16 mt-0">{{$client->first_name.' '.$client->last_name}}</h4>
                                <p class="card-text">{{$zwmb->natid}}</p>
                                <p class="card-text">{{$client->email}}</p>
                                <p class="card-text">+263{{$client->mobile}}</p>
                                <p class="card-text">Credit Score: {{$client->fsb_score}}</p>
                                <p class="card-text">EC Number: {{$client->ecnumber}}</p>
                                <p class="card-text">Customer Number: {{$zwmb->customer_number}}</p>
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
                                        <td><strong>{{$client->first_name.' '.$client->middle_name.' '.$client->last_name}}</strong></td>
                                        <td>National ID Number</td>
                                        <td><strong>{{$zwmb->natid}}</strong></td>
                                    </tr>
                                    <tr>
                                        <td>Date of Birth</td>
                                        <td><strong>{{date_format($client->dob, 'd F Y')}}</strong></td>
                                        <td>Gender</td>
                                        <td><strong>{{$client->gender}}</strong></td>
                                    </tr>
                                    <tr>
                                        <td>Passport Number</td>
                                        <td><strong>{{$zwmb->passport_number}}</strong></td>
                                        <td>Driver's Licence</td>
                                        <td><strong>{{$zwmb->driver_licence}}</strong></td>
                                    </tr>
                                    <tr>
                                        <td>Phone Number</td>
                                        <td><strong>{{$client->mobile}}</strong></td>
                                        <td>Email</td>
                                        <td><strong>{{$client->email}}</strong></td>
                                    </tr>

                                    <tr>
                                        <td>Title</td>
                                        <td><strong>{{$client->title}}</strong></td>
                                        <td>Maiden Name</td>
                                        <td><strong>{{$zwmb->maiden_name}}</strong></td>

                                    </tr>
                                    <tr>
                                        <td>Race</td>
                                        <td><strong>{{$zwmb->race}}</strong></td>
                                        <td>Mobile Banking Number</td>
                                        <td><strong>{{$zwmb->mobile_banking_num}}</strong></td>

                                    </tr>
                                    <tr>
                                        <td>Address</td>
                                        <td><strong>{{$client->house_num.', '.$client->street.', '.$client->surburb}}</strong></td>
                                        <td>City</td>
                                        <td><strong>{{$client->city}}</strong></td>
                                    </tr>
                                    <tr>
                                        <td>Account Number</td>
                                        <td><strong>{{$zwmb->account_number}}</strong></td>
                                        <td>EC Number</td>
                                        <td><strong>{{$client->ecnumber}}</strong></td>
                                    </tr>
                                    <tr>
                                        <td>Employer/Business</td>
                                        <td><strong>{{$zwmb->employer_name}}</strong></td>
                                        <td>Occupation</td>
                                        <td><strong>{{$zwmb->occupation}}</strong></td>
                                    </tr>
                                    <tr>
                                        <td>Credit Score</td>
                                        <td><strong>{{$client->fsb_score}}</strong></td>
                                        <td>FCb Rating</td>
                                        <td><strong>{{$client->fsb_rating}}</strong></td>
                                    </tr>
                                    <tr>
                                        <td>Next of Kin</td>
                                        <td><strong>{{$kyc->kin_fname.' '.$kyc->kin_lname}}</strong></td>
                                        <td>Relationship to Kin</td>
                                        <td><strong>{{$zwmb->kin_relationship}}</strong></td>
                                    </tr>
                                    <tr>
                                        <td>Next of Kin Address</td>
                                        <td><strong>{{$zwmb->kin_address}}</strong></td>
                                        <td>Kin Phone Number</td>
                                        <td><strong>{{$kyc->kin_number}}</strong></td>
                                    </tr>
                                    <tr>
                                        <td>Reviewed By</td>
                                        <td><strong>{{$zwmb->reviewer}}</strong></td>
                                        <td>Authorized By</td>
                                        <td><strong>{{$zwmb->authorized}}</strong></td>
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
