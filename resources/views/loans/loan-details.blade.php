<?php
/**
 *Created by PhpStorm for eshagi
 *User: Vincent Guyo
 *Date: 10/10/2020
 *Time: 2:13 AM
 */

?>
@extends('layouts.app')

@section('template_title')
    Loan Request
@endsection


@section('content')
    <div class="page-title-box">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h4 class="page-title mb-1">Loan Details</h4>
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{url('/myloans')}}">Loan</a></li>
                        <li class="breadcrumb-item active">Loan ID: {{$loan->id}}</li>
                    </ol>
                </div>

                <div class="col-md-4">
                    <div class="float-right d-none d-md-block">
                        <div>
                            <a class="btn btn-light btn-rounded" href="{{url('/approved-loans')}}" type="button">
                                <i class="mdi mdi-keyboard-backspace mr-1"></i>Back to Approved Loans
                            </a>
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
                            <h4 class="header-title">Reviewing Loan Request</h4>

                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="validationCustom01">Client</label>
                                    <input type="text" class="form-control" id="" placeholder="e.g. Client name" value="{{$client->first_name.' '.$client->last_name}}" required>
                                </div>
                                <div class="col-md-2 mb-3">
                                    <label for="validationCustomUsername">National ID</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="" placeholder="e.g. 63-2321066-F-71" value="{{$client->natid}}" aria-describedby="inputGroupPrepend" required>
                                    </div>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label for="validationCustom02">Loan Type</label>
                                    <input type="text" class="form-control" id="" placeholder="e.g. Cash Loan or Credit" value="{{getLoantype($loan->loan_type)}}" required>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label for="validationCustomUsername">Amount Requested (ZWL$)</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="" placeholder="e.g. $100000.00" value="{{$loan->amount}}" aria-describedby="inputGroupPrepend" required>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="validationCustom01">FCB Score</label>
                                    <input type="text" class="form-control" id="" placeholder="e.g. 278" value="{{$client->fsb_score}}" required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="validationCustomUsername">FCB Status</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="" placeholder="e.g. Good" value="{{$client->fsb_status}}" aria-describedby="inputGroupPrepend" required>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="validationCustom02">FCB Rating</label>
                                    <input type="text" class="form-control" id="" placeholder="e.g. No Risk" value="{{$client->fsb_rating}}" required>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-3 mb-3">
                                    <label for="validationCustom01">RedSphere Number</label>
                                    <input type="text" class="form-control" id="" placeholder="e.g. RSF278000004" value="{{$client->reds_number}}" required>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label for="validationCustom01">RedSphere ID</label>
                                    <input type="text" class="form-control" id="" placeholder="e.g. 278000004" value="{{$client->reds_id}}" required>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label for="validationCustomUsername">RedSphere Type</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="" placeholder="e.g. SSB" value="{{$client->reds_type}}" aria-describedby="inputGroupPrepend" required>
                                    </div>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label for="validationCustom02">RedSphere Sub</label>
                                    <input type="text" class="form-control" id="" placeholder="e.g. No Risk" value="{{$client->reds_type}}" required>
                                </div>
                            </div>
                            @if ($loan->loan_number == null AND $client->locale_id == 1)
                                <a class="btn btn-success" href="{{url('postloanredsphere/'.$loan->id)}}" >
                                    Post Loan to CBZ
                                </a>
                            @endif

                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="header-title">Recent Loans</h4>
                            <p class="card-title-desc">Last 10 Loan Items for {{$client->first_name.' '.$client->last_name}} .</p>

                            <div class="table-responsive">
                                <table class="table table-striped mb-0">
                                    <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Type</th>
                                        <th>Amount</th>
                                        <th>Monthly</th>
                                        <th>Status</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach ($loans as $transaction)
                                        <tr>
                                            <td>{{$transaction->created_at}}</td>
                                            <td>{{getLoantype($loan->loan_type)}}</td>
                                            <td>${{$transaction->amount}}</td>
                                            <td>${{$transaction->monthly}}</td>
                                            <td>{{getLoanstatus($loan->loan_status)}}</td>
                                        </tr>
                                    @endforeach

                                    </tbody>
                                </table>
                            </div>

                        </div>
                    </div>
                </div>

            </div>

            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-body">

                            <h4 class="header-title">Other Details</h4>
                            <p class="card-title-desc">Loan & KYC info</p>

                            <div class="row">

                                <div class="col-sm-3">
                                    <div class="nav flex-column nav-pills  mt-4 mt-sm-0" role="tablist" aria-orientation="vertical">
                                        <a class="nav-link active mb-2" id="v-pills-right-home" data-toggle="pill" href="#loan-info" role="tab" aria-controls="v-pills-right-home" aria-selected="true">
                                            <i class="fas fa-money-bill mr-1"></i> Loan
                                        </a>
                                        <a class="nav-link mb-2" id="v-pills-right-profile" data-toggle="pill" href="#client-info" role="tab" aria-controls="v-pills-right-profile" aria-selected="false">
                                            <i class="fas fa-user mr-1"></i> Client
                                        </a>
                                        <a class="nav-link mb-2" id="v-pills-right-messages" data-toggle="pill" href="#kyc-info" role="tab" aria-controls="v-pills-right-messages" aria-selected="false">
                                            <i class="fas fa-user-cog mr-1"></i> KYC
                                        </a>
                                        <a class="nav-link" id="v-pills-right-setting" data-toggle="pill" href="#ssb-info" role="tab" aria-controls="v-pills-right-setting" aria-selected="false">
                                            <i class="fas fa-cog mr-1"></i> SSB Details
                                        </a>
                                    </div>
                                </div>

                                <div class="col-sm-9">
                                    <div class="tab-content">
                                        <div class="tab-pane fade show active" id="loan-info" role="tabpanel" aria-labelledby="v-pills-right-home">
                                            <table class="table table-striped mb-0">
                                                <tr>
                                                    <td>eShagi Ref Number</td>
                                                    <td><strong>ES{{$loan->id}}</strong></td>
                                                    <td>Loan Number</td>
                                                    <td><strong>{{$loan->loan_number}}</strong></td>
                                                </tr>
                                                <tr>
                                                    <td>Total Amount</td>
                                                    <td><strong>${{$loan->amount}}</strong></td>
                                                    <td>Amount Disbursed</td>
                                                    <td><strong>${{$loan->disbursed}}</strong></td>
                                                </tr>
                                                <tr>
                                                    <td>Monthly</td>
                                                    <td><strong>${{$loan->monthly}}</strong></td>
                                                    <td>Application Fee</td>
                                                    <td><strong>${{$loan->appFee}}</strong></td>
                                                </tr>
                                                <tr>
                                                    <td>Interest Rate</td>
                                                    <td><strong>{{$loan->interestRate}}%</strong></td>
                                                    <td>Charges</td>
                                                    <td><strong>${{$loan->charges}}</strong></td>
                                                </tr>
                                                <tr>
                                                    <td>Loan Disbursement Fee</td>
                                                    <td><strong>${{$loan->disbursefee}}</strong></td>

                                                </tr>
                                            </table>
                                        </div>
                                        <div class="tab-pane fade" id="client-info" role="tabpanel" aria-labelledby="v-pills-right-profile">
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
                                            </table>
                                        </div>
                                        <div class="tab-pane fade" id="kyc-info" role="tabpanel" aria-labelledby="v-pills-right-messages">
                                            <table class="table table-striped mb-0">
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
                                                    <td><strong>${{$client->salary}}</strong></td>
                                                </tr>
                                            </table>
                                        </div>
                                        <div class="tab-pane fade" id="ssb-info" role="tabpanel" aria-labelledby="v-pills-right-setting">
                                            <table class="table table-striped mb-0">
                                                <tr>
                                                    <td>Profession</td>
                                                    <td><strong>{{$ssbInfo->profession}}</strong></td>
                                                    <td>Other Sources of Income</td>
                                                    <td><strong>{{$ssbInfo->sourcesOfIncome}}</strong></td>
                                                </tr>
                                                <tr>
                                                    <td>HR Contact Name</td>
                                                    <td><strong>{{$ssbInfo->hr_contact_name}}</strong></td>
                                                    <td>HR Position</td>
                                                    <td><strong>{{$ssbInfo->hr_position}}</strong></td>
                                                </tr>
                                                <tr>
                                                    <td>HR Email</td>
                                                    <td><strong>{{$ssbInfo->hr_email}}</strong></td>
                                                    <td>HR Phone</td>
                                                    <td><strong>{{$ssbInfo->hr_telephone}}</strong></td>
                                                </tr>
                                            </table>
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
