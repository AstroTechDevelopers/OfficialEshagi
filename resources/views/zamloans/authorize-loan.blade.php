<?php
/**
 * Created by PhpStorm for eshagitwo
 * User: VinceGee
 * Date: 5/18/2022
 * Time: 12:55 PM
 */ ?>

@extends('layouts.app')

@section('template_title')
    Authorize Zambia Loan
@endsection


@section('content')
    <div class="page-title-box">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h4 class="page-title mb-1">Zambia Loan Details</h4>
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{url('/my-zambia-loans')}}">Zambia Loans</a></li>
                        <li class="breadcrumb-item active">Zambia Loan ID: {{$zambiaLoan->id}}</li>
                    </ol>
                </div>

                <div class="col-md-4">
                    <div class="float-right d-none d-md-block">
                        <div>
                            <a class="btn btn-light btn-rounded" href="{{url('/open-loans')}}" type="button">
                                <i class="mdi mdi-keyboard-backspace mr-1"></i>Back to Open Loans
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
                                    <input type="text" class="form-control" id="" placeholder="e.g. Client name" value="{{$zambian->first_name.' '.$zambian->last_name}}" required>
                                </div>
                                <div class="col-md-2 mb-3">
                                    <label for="validationCustomUsername">NRC</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="" placeholder="e.g. 63-2321066-F-71" value="{{$zambian->nrc}}" aria-describedby="inputGroupPrepend" required>
                                    </div>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label for="validationCustom02">Loan Interest</label>
                                    <input type="text" class="form-control" id="" placeholder="e.g. 10%" value="{{$zambiaLoan->loan_interest}}" required>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label for="validationCustomUsername">Amount Requested (ZMK$)</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="" placeholder="e.g. 100000.00" value="{{$zambiaLoan->loan_principal_amount}}" aria-describedby="inputGroupPrepend" required>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="validationCustom01">EC Number</label>
                                    <input type="text" class="form-control" id="" placeholder="e.g. 278123F" value="{{$zambian->ec_number}}" required>
                                </div>
                                <div class="col-md-4 mb-3">

                                </div>
                                <div class="col-md-4 mb-3">
                                </div>
                            </div>

                            @if ($zambiaLoan->lo_approved == true AND $zambiaLoan->manager_approved == false)
                                <a class="btn btn-success" href="{{url('authorize-this-loan/'.$zambiaLoan->id)}}" >
                                    Authorize Loan
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
                            <p class="card-title-desc">Last 10 Loan Items for {{$zambiaLoan->first_name.' '.$zambiaLoan->last_name}} .</p>

                            <div class="table-responsive">
                                <table class="table table-striped mb-0">
                                    <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Amount</th>
                                        <th>Balance</th>
                                        <th>Approval Date</th>
                                        <th>Installment</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach ($zambiaLoans as $transaction)
                                        <tr>
                                            <td>{{$transaction->created_at}}</td>
                                            <td>${{$transaction->loan_principal_amount}}</td>
                                            <td>${{$transaction->balance_amount}}</td>
                                            <td>{{$zambiaLoan->cf_11133_approval_date->toDateString()}}</td>
                                            <td>{{$zambiaLoan->cf_11353_installment}}</td>
                                        </tr>
                                    @endforeach

                                    </tbody>
                                </table>
                            </div>

                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>
@endsection
