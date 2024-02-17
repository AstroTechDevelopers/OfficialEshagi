<?php
/**
 * Created by PhpStorm for eshagi
 * User: vinceg
 * Date: 21/2/2021
 * Time: 12:55
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
                    <h5 class="text-white">*Credit data provided by CRB </h5>
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
                            <h3>{{$zambian->credit_score ?? 'Nil'}}</h3>
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
                            <h3>ZMK {{$user->cred_limit ?? 'Nil '}} K</h3>
                            <h5 class="header-title mb-4">Credit Limit</h5>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="header-title">My Loans</h4>

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
                                @if ($cashLoans->isEmpty())
                                    No Credit loans picked up
                                @else
                                    @foreach($cashLoans as $loan)
                                        @php $totalRepayments += $loan->cf_11353_installment; @endphp
                                        <tr>
                                            <td>{{getZambianLoanstatus($loan->loan_status)}}</td>
                                            <td>{{$loan->loan_principal_amount}}</td>
                                            <td>{{$loan->loan_duration}}</td>
                                            <td>{{$loan->loan_interest}}</td>
                                            <td>{{$loan->cf_11353_installment}}</td>
                                        </tr>
                                    @endforeach
                                @endif
                                <tr>
                                    <td colspan="4"><strong>Total Repayments</strong></td>
                                    <td><strong>ZMK {{$totalRepayments}} K</strong></td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

@endsection

@section('footer_scripts')
@endsection
