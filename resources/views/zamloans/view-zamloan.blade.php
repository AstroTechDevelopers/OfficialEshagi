<?php
/**
 *Created by PhpStorm for eshagi
 *User: Vincent Guyo
 *Date: 10/7/2020
 *Time: 2:12 AM
 */

?>
@extends('layouts.app')

@section('template_title')
    Loan Details
@endsection

@section('content')
    <div class="page-title-box">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h4 class="page-title mb-1">Loan Details</h4>
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{url('/my-zambia-loans')}}">Loan</a></li>
                        <li class="breadcrumb-item active">Loan ID: {{$loan->id}}</li>
                    </ol>
                </div>

                <div class="col-md-4">
                    <div class="float-right d-none d-md-block">
                        <div>
                            @if($loan->ld_loan_id)
                            <a class="btn btn-light btn-rounded" href="{{url('pull-from-loan-disk/'.$loan->ld_loan_id)}}" type="button">
                                <i class="mdi mdi-refresh mr-1"></i>Update Loan From Loan Disk
                            </a>
                            @endif

                            <a class="btn btn-light btn-rounded" href="{{url()->previous()}}" type="button">
                                <i class="mdi mdi-keyboard-backspace mr-1"></i>Back
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
                            <div class="card-header">
                                Loan Details
                            </div>
                            <br>
                            @if ($loan->zambian_id)

                                <div class="col-sm-5 col-6 text-larger">
                                    <strong>
                                        Client:
                                    </strong>

                                    {{$client->first_name .' '.$client->last_name}}
                                </div>
                                <div class="clearfix"></div>
                                <div class="border-bottom"></div>
                                <br>
                            @endif

                            @if ($loan->channel_id)

                                <div class="col-sm-5 col-6 text-larger">
                                    <strong>
                                        Applied via:
                                    </strong>

                                    {{$loan->channel_id}}
                                </div>
                                <div class="clearfix"></div>
                                <div class="border-bottom"></div>
                                <br>
                            @endif

                            @if ($loan->loan_principal_amount)

                                <div class="col-sm-5 col-6 text-larger">
                                    <strong>
                                        Amount:
                                    </strong>

                                    K{{$loan->loan_principal_amount}}
                                </div>
                                <div class="clearfix"></div>
                                <div class="border-bottom"></div>
                                <br>
                            @endif

                            @if ($loan->loan_released_date)

                                <div class="col-sm-5 col-6 text-larger">
                                    <strong>
                                        Loan Released date:
                                    </strong>

                                    {{$loan->loan_released_date->toDateString()}}
                                </div>
                                <div class="clearfix"></div>
                                <div class="border-bottom"></div>
                                <br>
                            @endif

                            @if ($loan->duration)

                                <div class="col-sm-5 col-6 text-larger">
                                    <strong>
                                        Duration:
                                    </strong>

                                    {{$loan->duration}} Months
                                </div>
                                <div class="clearfix"></div>
                                <div class="border-bottom"></div>
                                <br>
                            @endif

                            @if ($loan->interest_rate)

                                <div class="col-sm-5 col-6 text-larger">
                                    <strong>
                                        Interest Rate:
                                    </strong>

                                    {{$loan->interest_rate}}%
                                </div>
                                <div class="clearfix"></div>
                                <div class="border-bottom"></div>
                                <br>
                            @endif

                            @if ($loan->cf_11353_installment)

                                <div class="col-sm-5 col-6 text-larger">
                                    <strong>
                                        Monthly Installment:
                                    </strong>

                                    ${{$loan->cf_11353_installment}}
                                </div>
                                <div class="clearfix"></div>
                                <div class="border-bottom"></div>
                                <br>
                            @endif

                            @if ($loan->cf_11133_approval_date)

                                <div class="col-sm-5 col-6 text-larger">
                                    <strong>
                                        Approval Date:
                                    </strong>

                                    {{$loan->cf_11133_approval_date->toDateString()}}
                                </div>
                                <div class="clearfix"></div>
                                <div class="border-bottom"></div>
                                <br>
                            @endif

                            @if ($loan->loan_description)

                                <div class="col-sm-5 col-6 text-larger">
                                    <strong>
                                        Loan Description:
                                    </strong>

                                    ${{$loan->loan_description}}
                                </div>
                                <div class="clearfix"></div>
                                <div class="border-bottom"></div>
                                <br>
                            @endif

                            @if ($loan->due_date)

                                <div class="col-sm-5 col-6 text-larger">
                                    <strong>
                                        Due Date:
                                    </strong>

                                    {{$loan->due_date->toDateString()}}
                                </div>
                                <div class="clearfix"></div>
                                <div class="border-bottom"></div>
                                <br>
                            @endif

                                @if ($loan->ld_borrower_id)

                                    <div class="col-sm-5 col-6 text-larger">
                                        <strong>
                                            Loan Borrower ID:
                                        </strong>

                                        {{$loan->ld_borrower_id}}
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="border-bottom"></div>
                                    <br>
                                @endif


                                @if ($loan->loan_application_id)

                                    <div class="col-sm-5 col-6 text-larger">
                                        <strong>
                                            Loan Application ID:
                                        </strong>

                                        {{$loan->loan_application_id}}
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="border-bottom"></div>
                                    <br>
                                @endif

                            @if ($loan->created_at)

                                <div class="col-sm-5 col-6 text-larger">
                                    <strong>
                                        Applied On:
                                    </strong>

                                    {{$loan->created_at}}
                                </div>
                                <div class="clearfix"></div>
                                <div class="border-bottom"></div>
                                <br>
                            @endif


                            @if ($loan->updated_at)

                                <div class="col-sm-5 col-6 text-larger">
                                    <strong>
                                        Last Modified On:
                                    </strong>

                                    {{$loan->updated_at}}
                                </div>
                                <div class="clearfix"></div>
                                <div class="border-bottom"></div>
                            @endif

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
