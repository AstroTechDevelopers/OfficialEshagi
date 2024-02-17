<?php
/**
 * Created by PhpStorm for eshagi
 * User: vinceg
 * Date: 22/3/2021
 * Time: 22:35
 */
?>
@extends('layouts.app')

@section('template_title')
    Executive Summary Report
@endsection

@section('content')
    <div class="page-title-box">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h4 class="page-title mb-1">Executive Summary Report</h4>
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Reports</a></li>
                        <li class="breadcrumb-item active">Executive Summary</li>
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
                <div class="col-xl-12">
                    <div class="col-xl-6 float-left">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="header-title">Daily Sales Executive Summary For {{date('d F Y')}}</h4>
                                @foreach($dailySales as $sales)
                                    Total: {{$sales->Count}} Loans <br>
                                    Worth: ${{number_format($sales->Total,2,'.',',')}} <br>
                                    Average Loan Amount: ${{number_format($sales->Average,2,'.',',')}} <br>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-6 float-right">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="header-title">Month to Date Executive Summary For {{date('F Y')}}</h4>
                                @foreach($monthToDateLoans as $sales)
                                    Total: {{$sales->Count}} Loans <br>
                                    Worth: ${{number_format($sales->Total,2,'.',',')}} <br>
                                    Average Loan Amount: ${{number_format($sales->Average,2,'.',',')}} <br>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xl-12">
                    <div class="col-xl-6 float-left">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="header-title">Year to Date Executive Summary For {{date('Y')}}</h4>
                                @foreach($yearToDateLoans as $sales)
                                    Total: {{$sales->Count}} Loans <br>
                                    Worth: ${{number_format($sales->Total,2,'.',',')}} <br>
                                    Average Loan Amount: ${{number_format($sales->Average,2,'.',',')}} <br>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-6 float-right">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="header-title">Disbursed Month to Date Executive Summary For {{date('F Y')}}</h4>
                                @foreach($disbursedMonthToDateLoans as $sales)
                                    Total: {{$sales->Count}} Loans <br>
                                    Worth: ${{number_format($sales->Total,2,'.',',')}} <br>
                                    Average Loan Amount: ${{number_format($sales->Average,2,'.',',')}} <br>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xl-12">
                    <div class="col-xl-6 float-left">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="header-title">USD Daily Sales Executive Summary For {{date('d F Y')}}</h4>
                                @foreach($dailyUsdSales as $sales)
                                    Total: {{$sales->Count}} Loans <br>
                                    Worth: ${{number_format($sales->Total,2,'.',',')}} <br>
                                    Average Loan Amount: ${{number_format($sales->Average,2,'.',',')}} <br>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-6 float-right">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="header-title">USD Month to Date Executive Summary For {{date('F Y')}}</h4>
                                @foreach($monthToDateUsdLoans as $sales)
                                    Total: {{$sales->Count}} Loans <br>
                                    Worth: ${{number_format($sales->Total,2,'.',',')}} <br>
                                    Average Loan Amount: ${{number_format($sales->Average,2,'.',',')}} <br>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xl-12">
                    <div class="col-xl-6 float-left">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="header-title">USD Year to Date Executive Summary For {{date('Y')}}</h4>
                                @foreach($yearToDateUsdLoans as $sales)
                                    Total: {{$sales->Count}} Loans <br>
                                    Worth: ${{number_format($sales->Total,2,'.',',')}} <br>
                                    Average Loan Amount: ${{number_format($sales->Average,2,'.',',')}} <br>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-6 float-right">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="header-title">USD Disbursed Month to Date Executive Summary For {{date('F Y')}}</h4>
                                @foreach($disbursedMonthToDateUsdLoans as $sales)
                                    Total: {{$sales->Count}} Loans <br>
                                    Worth: ${{number_format($sales->Total,2,'.',',')}} <br>
                                    Average Loan Amount: ${{number_format($sales->Average,2,'.',',')}} <br>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xl-12">
                    <div class="col-xl-6 float-left">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="header-title">Zambia Daily Sales Executive Summary For {{date('d F Y')}}</h4>
                                @foreach($dailyZambiaSales as $sales)
                                    Total: {{$sales->Count}} Loans <br>
                                    Worth: K{{number_format($sales->Total,2,'.',',')}} <br>
                                    Average Loan Amount: K{{number_format($sales->Average,2,'.',',')}} <br>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-6 float-right">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="header-title">Zambia Month to Date Executive Summary For {{date('F Y')}}</h4>
                                @foreach($monthToDateZambiaLoans as $sales)
                                    Total: {{$sales->Count}} Loans <br>
                                    Worth: K{{number_format($sales->Total,2,'.',',')}} <br>
                                    Average Loan Amount: K{{number_format($sales->Average,2,'.',',')}} <br>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xl-12">
                    <div class="col-xl-6 float-left">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="header-title">Zambia Year to Date Executive Summary For {{date('Y')}}</h4>
                                @foreach($yearToDateZambiaLoans as $sales)
                                    Total: {{$sales->Count}} Loans <br>
                                    Worth: K{{number_format($sales->Total,2,'.',',')}} <br>
                                    Average Loan Amount: K{{number_format($sales->Average,2,'.',',')}} <br>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-6 float-right">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="header-title">Zambia Disbursed Month to Date Executive Summary For {{date('F Y')}}</h4>
                                @foreach($disbursedMonthToDateZambiaLoans as $sales)
                                    Total: {{$sales->Count}} Loans <br>
                                    Worth: K{{number_format($sales->Total,2,'.',',')}} <br>
                                    Average Loan Amount: K{{number_format($sales->Average,2,'.',',')}} <br>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
