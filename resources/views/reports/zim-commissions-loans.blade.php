<?php
/**
 * Created by PhpStorm for eshagitwo
 * User: VinceGee
 * Date: 8/18/2022
 * Time: 9:40 AM
 */ ?>
@extends('layouts.app')

@section('template_title')
    Zim Revenue Report
@endsection

@section('content')
    <div class="page-title-box">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h4 class="page-title mb-1">Zim Revenue Report</h4>
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Reports</a></li>
                        <li class="breadcrumb-item active">Zim Revenue Summary</li>
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
                                <h4 class="header-title">Loans For {{date('F Y')}}</h4>
                                    Total: {{$loans->count()}} Loans <br>
                                    Revenue: ${{number_format($sum,2,'.',',')}} <br>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-6 float-right">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="header-title">USD Loans For {{date('F Y')}}</h4>
                                Total: {{$usdLoans->count()}} Loans <br>
                                Revenue: ${{number_format($usdSum,2,'.',',')}} <br>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
