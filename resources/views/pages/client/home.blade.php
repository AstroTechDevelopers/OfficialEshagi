<?php
/**
 *Created by PhpStorm for eshagi
 *User: Vincent Guyo
 *Date: 9/25/2020
 *Time: 11:18 AM
 */

?>
@extends('layouts.app')

@section('template_title')
    {{ Auth::user()->name }}'s' Homepage
@endsection

@section('template_fastload_css')
@endsection

@section('content')
    <div class="page-title-box">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h4 class="page-title mb-1">Hello, {{auth()->user()->first_name}}</h4>
                    <h5 class="text-white">*Credit data provided by Credit Reference Bureau (CRB) </h5>
                </div>

                <div class="col-md-4">
                    <div class="float-right d-none d-md-block">
                    </div>
                </div>
            </div>

        </div>
    </div>

    <div class="page-content-wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-xl-4">
                    <div class="card">
                        <div class="card-body">
                            <h3>{{$user->fsb_score ?? 'Nil'}}</h3>
                            <h5 class="header-title mb-4">Credit Score</h5>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4">
                    <div class="card">
                        <div class="card-body">
                            <h3>{{$user->fsb_status ?? 'Nil'}}</h3>
                            <h5 class="header-title mb-4">Credit Status</h5>
                            <h6 class="text-muted">{{$user->fsb_rating ?? 'Nil'}}</h6>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-6">
                                    <h3>ZMW {{$user->cred_limit ?? 'Nil'}}</h3>
                                    <h5 class="header-title mb-4">ZMW Credit Limit</h5>
                                </div>
                            
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="header-title">My Loans</h4>

                        <!-- Nav tabs -->
                        <ul class="nav nav-pills nav-justified" role="tablist">
                            <li class="nav-item waves-effect waves-light">
                                <a class="nav-link active" data-toggle="tab" href="#salary-loan" role="tab">
                                    <span class="d-none d-md-inline-block">Salary Loan</span>
                                </a>
                            </li>
                            <li class="nav-item waves-effect waves-light">
                                <a class="nav-link" data-toggle="tab" href="#store-credit" role="tab">
                                     <span class="d-none d-md-inline-block">Store Credit</span>
                                </a>
                            </li>
                            {{-- <li class="nav-item waves-effect waves-light">
                                <a class="nav-link" data-toggle="tab" href="#usd-loan" role="tab">
                                     <span class="d-none d-md-inline-block">USD Loans</span>
                                </a>
                            </li> --}}
                            <li class="nav-item waves-effect waves-light">
                                <a class="nav-link" data-toggle="tab" href="#device-finance" role="tab">
                                     <span class="d-none d-md-inline-block">Device Financing</span>
                                </a>
                            </li>
                        </ul>

                        <!-- Tab panes -->
                        <div class="tab-content p-6">
                            <div class="tab-pane active" id="salary-loan" role="tabpanel">
                                <br>
                                <div class="float-right ml-2 mb-3">
                                    <a href="{{url('loans/create')}}" class="btn btn-primary waves-effect waves-light">Request Salary Loan</a>
                                </div>
                                <br>
                                <div class="table-responsive">
                                    <table class="table table-centered table-hover mb-0">
                                        <thead>
                                        <tr>
                                            <th>Loan Status</th>
                                            <th>Amount</th>
                                            <th>Payback Period</th>
                                            <th>Interest %</th>
                                            <th>Monthly Payments</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @php $totalRepayments=0; @endphp
                                        @if ($salloans->isEmpty())
                                            No Salary loans picked up
                                        @else
                                            @foreach($salloans as $loan)
                                                @php $totalRepayments += $loan->monthly; @endphp
                                                <tr>
                                                    <td>{{getLoanstatus($loan->loan_status)}}</td>
                                                    <td>{{$loan->amount}}</td>
                                                    <td>{{$loan->paybackPeriod}}</td>
                                                    <td>{{$loan->interestRate}}</td>
                                                    <td>{{$loan->monthly}}</td>
                                                </tr>
                                            @endforeach
                                        @endif

                                        <tr>
                                            <td colspan="4"><strong>Total Repayments</strong></td>
                                            <td><strong>K{{$totalRepayments}}</strong></td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>

                            </div>
                            <div class="tab-pane" id="store-credit" role="tabpanel">
                                <br>
                                <div class="float-left mr-2 mb-3">
                                    <a href="{{url('/create-credit-loan')}}" class="btn btn-primary waves-effect waves-light">Request Store Credit</a>
                                </div>
                                <br>
                                <div class="table-responsive">
                                    <table class="table table-centered table-hover mb-0">
                                        <thead>
                                        <tr>
                                            <th>Loan Status</th>
                                            <th>Merchant</th>
                                            <th>Amount</th>
                                            <th>Payback Period</th>
                                            <th>Interest %</th>
                                            <th>Monthly Payments</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @php $totalRepayments=0; @endphp
                                        @if ($creloans->isEmpty())
                                            No Credit loans picked up
                                        @else
                                            @foreach($creloans as $loan)
                                            @php $totalRepayments += $loan->monthly; @endphp
                                            <tr>
                                                <td>{{getLoanstatus($loan->loan_status)}}</td>
                                                <td>{{$loan->partner_name}}</td>
                                                <td>{{$loan->amount}}</td>
                                                <td>{{$loan->paybackPeriod}}</td>
                                                <td>{{$loan->interestRate}}</td>
                                                <td>{{$loan->monthly}}</td>
                                            </tr>
                                        @endforeach
                                        @endif
                                        <tr>
                                            <td colspan="5"><strong>Total Repayments</strong></td>
                                            <td><strong>K{{$totalRepayments}}</strong></td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>

                            </div>
                            <div class="tab-pane" id="usd-loan" role="tabpanel">
                                <br>
                                <div class="float-right ml-2 mb-3">
                                    <a href="{{url('/usd-loans/create')}}" class="btn btn-primary waves-effect waves-light">Request USD Loan</a>
                                </div>
                                <br>
                                <div class="table-responsive">
                                    <table class="table table-centered table-hover mb-0">
                                        <thead>
                                        <tr>
                                            <th>Loan Status</th>
                                            <th>Merchant</th>
                                            <th>Amount</th>
                                            <th>Payback Period</th>
                                            <th>Interest %</th>
                                            <th>Monthly Payments</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @php $totalRepayments=0; @endphp
                                        @if ($usdloans->isEmpty())
                                            No USD loans picked up
                                        @else
                                            @foreach($usdloans as $loan)
                                            @php $totalRepayments += $loan->monthly; @endphp
                                            <tr>
                                                <td>{{getUsdLoanstatus($loan->loan_status)}}</td>
                                                <td>{{$loan->partner_name}}</td>
                                                <td>{{$loan->amount}}</td>
                                                <td>{{$loan->paybackPeriod}}</td>
                                                <td>{{$loan->interestRate}}</td>
                                                <td>{{$loan->monthly}}</td>
                                            </tr>
                                        @endforeach
                                        @endif
                                        <tr>
                                            <td colspan="5"><strong>Total Repayments</strong></td>
                                            <td><strong>K{{$totalRepayments}}</strong></td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>

                            </div>
                            <div class="tab-pane" id="device-finance" role="tabpanel">
                                <br>
                                <div class="float-left mr-2 mb-3">
                                    <a href="{{url('/device-loans/create')}}" class="btn btn-primary waves-effect waves-light">Request Device Loan</a>
                                </div>
                                <br>
                                <div class="table-responsive">
                                    <table class="table table-centered table-hover mb-0">
                                        <thead>
                                        <tr>
                                            <th>Loan Status</th>
                                            <th>Merchant</th>
                                            <th>Amount</th>
                                            <th>Payback Period</th>
                                            <th>Interest %</th>
                                            <th>Monthly Payments</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @php $totalRepayments=0; @endphp
                                        @if ($devfinloans->isEmpty())
                                            No device loans picked up
                                        @else
                                            @foreach($devfinloans as $loan)
                                            @php $totalRepayments += $loan->monthly; @endphp
                                            <tr>
                                                <td>{{getDeviceLoanstatus($loan->loan_status)}}</td>
                                                <td>{{$loan->partner_name}}</td>
                                                <td>{{$loan->amount}}</td>
                                                <td>{{$loan->paybackPeriod}}</td>
                                                <td>{{$loan->interestRate}}</td>
                                                <td>{{$loan->monthly}}</td>
                                            </tr>
                                        @endforeach
                                        @endif
                                        <tr>
                                            <td colspan="5"><strong>Total Repayments</strong></td>
                                            <td><strong>K{{$totalRepayments}}</strong></td>
                                        </tr>
                                        </tbody>
                                    </table>
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
@endsection
