<?php
/**
 * Created by PhpStorm for eshagitwo
 * User: VinceGee
 * Date: 5/2/2022
 * Time: 11:27 PM
 */ ?>
@extends('layouts.app')

@section('template_title')
    Device Loan Details
@endsection

@section('content')
    <div class="page-title-box">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h4 class="page-title mb-1">Device Loan Details</h4>
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{url('/my-device-loans')}}">Device Loan</a></li>
                        <li class="breadcrumb-item active">Device Loan ID: {{$loan->id}}</li>
                    </ol>
                </div>

                <div class="col-md-4">
                    <div class="float-right d-none d-md-block">
                        <div>
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
                            @if ($loan->client_id)

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


                            @if ($agent)

                                <div class="col-sm-5 col-6 text-larger">
                                    <strong>
                                        Agent:
                                    </strong>

                                    {{'('.$client->creator.')'.' '.$agent->first_name.' '.$agent->last_name.' - '. $agent->mobile}}
                                    @if ($merchant)
                                        {{$merchant->partner_name}}
                                    @endif
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

                            @if ($loan->loan_type)

                                <div class="col-sm-5 col-6 text-larger">
                                    <strong>
                                        Type of Loan:
                                    </strong>

                                    {{getDeviceLoantype($loan->loan_type)}}
                                </div>
                                <div class="clearfix"></div>
                                <div class="border-bottom"></div>
                                <br>
                            @endif

                            @if ($loan->loan_status)

                                <div class="col-sm-5 col-6 text-larger">
                                    <strong>
                                        Loan Status:
                                    </strong>

                                    {{getDeviceLoanstatus($loan->loan_status)}}
                                </div>
                                <div class="clearfix"></div>
                                <div class="border-bottom"></div>
                                <br>
                            @endif

                            @if ($loan->deposit_prct)

                                <div class="col-sm-5 col-6 text-larger">
                                    <strong>
                                        Deposit Percent:
                                    </strong>

                                    {{$loan->deposit_prct}} %
                                </div>
                                <div class="clearfix"></div>
                                <div class="border-bottom"></div>
                                <br>
                            @endif

                            @if ($loan->deposit)

                                <div class="col-sm-5 col-6 text-larger">
                                    <strong>
                                        Deposit :
                                    </strong>

                                    ${{$loan->deposit_prct}}
                                </div>
                                <div class="clearfix"></div>
                                <div class="border-bottom"></div>
                                <br>
                            @endif

                            @if ($loan->amount)

                                <div class="col-sm-5 col-6 text-larger">
                                    <strong>
                                        Amount:
                                    </strong>

                                    ${{$loan->amount}}
                                </div>
                                <div class="clearfix"></div>
                                <div class="border-bottom"></div>
                                <br>
                            @endif

                            @if ($loan->disbursed)

                                <div class="col-sm-5 col-6 text-larger">
                                    <strong>
                                        Amount Disbursed:
                                    </strong>

                                    ${{$loan->disbursed}}
                                </div>
                                <div class="clearfix"></div>
                                <div class="border-bottom"></div>
                                <br>
                            @endif

                            @if ($loan->paybackPeriod)

                                <div class="col-sm-5 col-6 text-larger">
                                    <strong>
                                        Payback Period:
                                    </strong>

                                    {{$loan->paybackPeriod}} Months
                                </div>
                                <div class="clearfix"></div>
                                <div class="border-bottom"></div>
                                <br>
                            @endif

                            @if ($loan->interestRate)

                                <div class="col-sm-5 col-6 text-larger">
                                    <strong>
                                        Interest Rate:
                                    </strong>

                                    {{$loan->interestRate}}%
                                </div>
                                <div class="clearfix"></div>
                                <div class="border-bottom"></div>
                                <br>
                            @endif

                            @if ($loan->monthly)

                                <div class="col-sm-5 col-6 text-larger">
                                    <strong>
                                        Monthly Repayments:
                                    </strong>

                                    ${{$loan->monthly}}
                                </div>
                                <div class="clearfix"></div>
                                <div class="border-bottom"></div>
                                <br>
                            @endif

                            @if ($loan->appFee)

                                <div class="col-sm-5 col-6 text-larger">
                                    <strong>
                                        Application Fee:
                                    </strong>

                                    ${{$loan->appFee}}
                                </div>
                                <div class="clearfix"></div>
                                <div class="border-bottom"></div>
                                <br>
                            @endif

                            @if ($loan->disbursefee)

                                <div class="col-sm-5 col-6 text-larger">
                                    <strong>
                                        Loan Disbursement Fee:
                                    </strong>

                                    ${{$loan->disbursefee}}
                                </div>
                                <div class="clearfix"></div>
                                <div class="border-bottom"></div>
                                <br>
                            @endif

                            @if ($loan->charges)

                                <div class="col-sm-5 col-6 text-larger">
                                    <strong>
                                        Charges:
                                    </strong>

                                    ${{$loan->charges}}
                                </div>
                                <div class="clearfix"></div>
                                <div class="border-bottom"></div>
                                <br>
                            @endif

                            @if ($loan->serial_num)

                                <div class="col-sm-5 col-6 text-larger">
                                    <strong>
                                        Device Serial Number:
                                    </strong>

                                    {{$loan->serial_num}}
                                </div>
                                <div class="clearfix"></div>
                                <div class="border-bottom"></div>
                                <br>
                            @endif

                            @if ($loan->device)

                                <div class="col-sm-5 col-6 text-larger">
                                    <strong>
                                        Device:
                                    </strong>

                                    {{$loan->device}}
                                </div>
                                <div class="clearfix"></div>
                                <div class="border-bottom"></div>
                                <br>
                            @endif

                            @if ($loan->device_model)

                                <div class="col-sm-5 col-6 text-larger">
                                    <strong>
                                        Device Model:
                                    </strong>

                                    {{$loan->device_model}}
                                </div>
                                <div class="clearfix"></div>
                                <div class="border-bottom"></div>
                                <br>
                            @endif

                            @if ($loan->imei)

                                <div class="col-sm-5 col-6 text-larger">
                                    <strong>
                                        IMEI:
                                    </strong>

                                    {{$loan->imei}}
                                </div>
                                <div class="clearfix"></div>
                                <div class="border-bottom"></div>
                                <br>
                            @endif

                            @if ($loan->notes)

                                <div class="col-sm-5 col-6 text-larger">
                                    <strong>
                                        Loan Notes:
                                    </strong>

                                    {{$loan->notes}}
                                </div>
                                <div class="clearfix"></div>
                                <div class="border-bottom"></div>
                                <br>
                            @endif

                            @if (auth()->user()->hasRole('root') || auth()->user()->hasRole('admin') || auth()->user()->hasRole('manager') || auth()->user()->hasRole('loansofficer') || auth()->user()->hasRole('salesadmin')|| auth()->user()->hasRole('agent'))


                                @if ($loan->funder_id)

                                    <div class="col-sm-5 col-6 text-larger">
                                        <strong>
                                            Funder ID:
                                        </strong>

                                        {{$loan->funder_id}}
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="border-bottom"></div>
                                    <br>
                                @endif


                                @if ($loan->funder_acc_number)

                                    <div class="col-sm-5 col-6 text-larger">
                                        <strong>
                                            Funder Account Number:
                                        </strong>

                                        {{$loan->funder_acc_number}}
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="border-bottom"></div>
                                    <br>
                                @endif


                                @if ($loan->loan_number)

                                    <div class="col-sm-5 col-6 text-larger">
                                        <strong>
                                            Loan Number:
                                        </strong>

                                        {{$loan->loan_number}}
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="border-bottom"></div>
                                    <br>
                                @endif


                                @if ($loan->enrollment_date)

                                    <div class="col-sm-5 col-6 text-larger">
                                        <strong>
                                            PayTrigger Enrollment Date:
                                        </strong>

                                        {{$loan->enrollment_date->toDateString()}}
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="border-bottom"></div>
                                    <br>
                                @endif

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
