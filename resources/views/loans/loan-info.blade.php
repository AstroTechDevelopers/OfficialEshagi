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
                        <li class="breadcrumb-item"><a href="{{url('/myloans')}}">Loan</a></li>
                        <li class="breadcrumb-item active">Loan ID: {{$loan->id}}</li>
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


                            {{-- @if ($loan->partner_id)

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
                            @endif --}}


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

                                    {{getLoantype($loan->loan_type)}}
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

                                    {{getLoanstatus($loan->loan_status)}}
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

                                    Kwacha{{$loan->amount}}
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

                                    Kwacha{{$loan->disbursed}}
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

                                    Kwacha{{$loan->monthly}}
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

                                    Kwacha{{$loan->appFee}}
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

                                    Kwacha{{$loan->disbursefee}}
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

                                    Kwacha{{$loan->charges}}
                                </div>
                                <div class="clearfix"></div>
                                <div class="border-bottom"></div>
                                <br>
                            @endif

                            @if ($loan->product)

                                <div class="col-sm-5 col-6 text-larger">
                                    <strong>
                                        Product:
                                    </strong>

                                    {{$loan->product}}
                                </div>
                                <div class="clearfix"></div>
                                <div class="border-bottom"></div>
                                <br>
                            @endif

                            @if ($loan->pprice)

                                <div class="col-sm-5 col-6 text-larger">
                                    <strong>
                                        Product Price:
                                    </strong>

                                    Kwacha{{$loan->pprice}}
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


                                @if ($loan->dd_approval)

                                    <div class="col-sm-5 col-6 text-larger">
                                        <strong>
                                            Deduction Approval:
                                        </strong>

                                        @if($loan->dd_approval == 0) Not Approved for Deduction @else Approved @endif
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="border-bottom"></div>
                                    <br>
                                @endif


                                @if ($loan->dd_approval_ref)

                                    <div class="col-sm-5 col-6 text-larger">
                                        <strong>
                                            Deduction Approval Reference:
                                        </strong>

                                        {{$loan->dd_approval_ref}}
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="border-bottom"></div>
                                    <br>
                                @endif

                                @if ($loan->disbursement_ref)

                                    <div class="col-sm-5 col-6 text-larger">
                                        <strong>
                                            Disbursement Reference:
                                        </strong>

                                        {{$loan->disbursement_ref}}
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="border-bottom"></div>
                                    <br>
                                @endif

                                @if ($loan->ndasendaBatch)

                                    <div class="col-sm-5 col-6 text-larger">
                                        <strong>
                                            Ndasenda Batch:
                                        </strong>

                                        {{$loan->ndasendaBatch}}
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="border-bottom"></div>
                                    <br>
                                @endif

                                @if ($loan->ndasendaRef1)

                                    <div class="col-sm-5 col-6 text-larger">
                                        <strong>
                                            Ndasenda Reference One:
                                        </strong>

                                        {{$loan->ndasendaRef1}}
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="border-bottom"></div>
                                    <br>
                                @endif

                                @if ($loan->ndasendaRef2)

                                    <div class="col-sm-5 col-6 text-larger">
                                        <strong>
                                            Ndasenda Reference Two:
                                        </strong>

                                        {{$loan->ndasendaRef2}}
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="border-bottom"></div>
                                    <br>
                                @endif

                                @if ($loan->ndasendaState)

                                    <div class="col-sm-5 col-6 text-larger">
                                        <strong>
                                            Ndasenda State:
                                        </strong>

                                        {{$loan->ndasendaState}}
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="border-bottom"></div>
                                    <br>
                                @endif

                                @if ($loan->ndasendaMessage)

                                    <div class="col-sm-5 col-6 text-larger">
                                        <strong>
                                            Ndasenda Message:
                                        </strong>

                                        {{$loan->ndasendaMessage}}
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
