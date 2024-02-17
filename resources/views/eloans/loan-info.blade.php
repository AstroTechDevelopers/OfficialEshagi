<?php
/**
 * Created by PhpStorm for eshagitwo
 * User: vincegee
 * Date: 6/9/2021
 * Time: 06:23
 */
?>
@extends('layouts.app')

@section('template_title')
    eLoan Details
@endsection

@section('content')
    <div class="page-title-box">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h4 class="page-title mb-1">eLoan Details</h4>
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{url('/myeloans')}}">eLoan</a></li>
                        <li class="breadcrumb-item active">eLoan ID: {{$eloan->id}}</li>
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
                            @if ($eloan->client_id)

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


                            @if ($eloan->partner_id)

                                <div class="col-sm-5 col-6 text-larger">
                                    <strong>
                                        Agent:
                                    </strong>

                                    {{'('.$client->creator.')'.' '.$agent->first_name.' '.$agent->last_name.' - '. $agent->mobile}} @if ($merchant->partner_name) {{$merchant->partner_name}} @endif
                                </div>
                                <div class="clearfix"></div>
                                <div class="border-bottom"></div>
                                <br>
                            @endif


                            @if ($eloan->channel_id)

                                <div class="col-sm-5 col-6 text-larger">
                                    <strong>
                                        Applied via:
                                    </strong>

                                    {{$eloan->channel_id}}
                                </div>
                                <div class="clearfix"></div>
                                <div class="border-bottom"></div>
                                <br>
                            @endif

                            @if ($eloan->loan_type)

                                <div class="col-sm-5 col-6 text-larger">
                                    <strong>
                                        Type of Loan:
                                    </strong>
                                    {{getELoanType($eloan->loan_type)}}
                                </div>
                                <div class="clearfix"></div>
                                <div class="border-bottom"></div>
                                <br>
                            @endif

                            @if ($eloan->loan_status)

                                <div class="col-sm-5 col-6 text-larger">
                                    <strong>
                                        Loan Status:
                                    </strong>
                                    {{getELoanStatus($eloan->loan_status)}}
                                </div>
                                <div class="clearfix"></div>
                                <div class="border-bottom"></div>
                                <br>
                            @endif

                            @if ($eloan->amount)

                                <div class="col-sm-5 col-6 text-larger">
                                    <strong>
                                        Amount:
                                    </strong>

                                    ${{$eloan->amount}}
                                </div>
                                <div class="clearfix"></div>
                                <div class="border-bottom"></div>
                                <br>
                            @endif

                            @if ($eloan->disbursed)

                                <div class="col-sm-5 col-6 text-larger">
                                    <strong>
                                        Amount Disbursed:
                                    </strong>

                                    ${{$eloan->disbursed}}
                                </div>
                                <div class="clearfix"></div>
                                <div class="border-bottom"></div>
                                <br>
                            @endif

                            @if ($eloan->tenure)

                                <div class="col-sm-5 col-6 text-larger">
                                    <strong>
                                        Tenure:
                                    </strong>

                                    {{$eloan->tenure}} Months
                                </div>
                                <div class="clearfix"></div>
                                <div class="border-bottom"></div>
                                <br>
                            @endif

                            @if ($eloan->interestRate)

                                <div class="col-sm-5 col-6 text-larger">
                                    <strong>
                                        Interest Rate:
                                    </strong>

                                    {{$eloan->interestRate}}%
                                </div>
                                <div class="clearfix"></div>
                                <div class="border-bottom"></div>
                                <br>
                            @endif

                            @if ($eloan->monthly)

                                <div class="col-sm-5 col-6 text-larger">
                                    <strong>
                                        Monthly Repayments:
                                    </strong>

                                    ${{$eloan->monthly}}
                                </div>
                                <div class="clearfix"></div>
                                <div class="border-bottom"></div>
                                <br>
                            @endif

                            @if ($eloan->appFee)

                                <div class="col-sm-5 col-6 text-larger">
                                    <strong>
                                        Application Fee:
                                    </strong>

                                    ${{$eloan->appFee}}
                                </div>
                                <div class="clearfix"></div>
                                <div class="border-bottom"></div>
                                <br>
                            @endif

                            @if ($eloan->disbursefee)

                                <div class="col-sm-5 col-6 text-larger">
                                    <strong>
                                        Loan Disbursement Fee:
                                    </strong>

                                    ${{$eloan->disbursefee}}
                                </div>
                                <div class="clearfix"></div>
                                <div class="border-bottom"></div>
                                <br>
                            @endif

                            @if ($eloan->charges)

                                <div class="col-sm-5 col-6 text-larger">
                                    <strong>
                                        Charges:
                                    </strong>

                                    ${{$eloan->charges}}
                                </div>
                                <div class="clearfix"></div>
                                <div class="border-bottom"></div>
                                <br>
                            @endif

                            @if ($eloan->product)

                                <div class="col-sm-5 col-6 text-larger">
                                    <strong>
                                        Product:
                                    </strong>

                                    {{$eloan->product}}
                                </div>
                                <div class="clearfix"></div>
                                <div class="border-bottom"></div>
                                <br>
                            @endif

                            @if ($eloan->pprice)

                                <div class="col-sm-5 col-6 text-larger">
                                    <strong>
                                        Product Price:
                                    </strong>

                                    ${{$eloan->pprice}}
                                </div>
                                <div class="clearfix"></div>
                                <div class="border-bottom"></div>
                                <br>
                            @endif

                            @if ($eloan->notes)

                                <div class="col-sm-5 col-6 text-larger">
                                    <strong>
                                        Loan Notes:
                                    </strong>

                                    {{$eloan->notes}}
                                </div>
                                <div class="clearfix"></div>
                                <div class="border-bottom"></div>
                                <br>
                            @endif

                            @if (auth()->user()->hasRole('root') || auth()->user()->hasRole('admin') || auth()->user()->hasRole('manager') || auth()->user()->hasRole('loansofficer') || auth()->user()->hasRole('salesadmin')|| auth()->user()->hasRole('agent'))


                                @if ($eloan->funder_id)

                                    <div class="col-sm-5 col-6 text-larger">
                                        <strong>
                                            Funder ID:
                                        </strong>

                                        {{$eloan->funder_id}}
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="border-bottom"></div>
                                    <br>
                                @endif


                                @if ($eloan->funder_acc_number)

                                    <div class="col-sm-5 col-6 text-larger">
                                        <strong>
                                            Funder Account Number:
                                        </strong>

                                        {{$eloan->funder_acc_number}}
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="border-bottom"></div>
                                    <br>
                                @endif


                                @if ($eloan->id)

                                    <div class="col-sm-5 col-6 text-larger">
                                        <strong>
                                            Loan Number:
                                        </strong>

                                        {{$eloan->id}}
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="border-bottom"></div>
                                    <br>
                                @endif

                            @endif

                            @if ($eloan->created_at)

                                <div class="col-sm-5 col-6 text-larger">
                                    <strong>
                                        Applied On:
                                    </strong>

                                    {{$eloan->created_at}}
                                </div>
                                <div class="clearfix"></div>
                                <div class="border-bottom"></div>
                                <br>
                            @endif


                            @if ($eloan->updated_at)

                                <div class="col-sm-5 col-6 text-larger">
                                    <strong>
                                        Last Modified On:
                                    </strong>

                                    {{$eloan->updated_at}}
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
