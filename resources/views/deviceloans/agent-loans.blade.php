<?php
/**
 * Created by PhpStorm for eshagitwo
 * User: VinceGee
 * Date: 5/16/2022
 * Time: 8:30 AM
 */ ?>
@extends('layouts.app')

@section('template_title')
    My Device Loans
@endsection

@section('template_linked_css')
    <!-- DataTables -->
    <link href="{{asset('assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('assets/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css')}}" rel="stylesheet" type="text/css" />

    <!-- Responsive datatable examples -->
    <link href="{{asset('assets/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css')}}" rel="stylesheet" type="text/css" />
@endsection

@section('content')
    <div class="page-title-box">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h4 class="page-title mb-1">Device Loans</h4>
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{url('/my-agent-device-loans')}}">Device Loan</a></li>
                        <li class="breadcrumb-item active">My Device Loans</li>
                    </ol>
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
                            <h4 class="header-title">My Device Loans</h4>
                            <p class="text-info"> </p>
                            <table id="datatable-buttons" class="table table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                <thead>
                                <tr>
                                    <th>Loan Type</th>
                                    <th>Loan Status</th>
                                    <th>Amount</th>
                                    <th>Payback Period</th>
                                    <th>Interest %</th>
                                    <th>Monthly Payments</th>
                                    <th>Actions</th>
                                </tr>
                                </thead>

                                <tbody>
                                @foreach($loans as $loan)
                                    <tr>
                                        <td>{{getDeviceLoantype($loan->loan_type)}}</td>
                                        <td>{{getDeviceLoanstatus($loan->loan_status)}}</td>
                                        <td>{{$loan->amount}}</td>
                                        <td>{{$loan->paybackPeriod}}</td>
                                        <td>{{$loan->interestRate}}</td>
                                        <td>{{$loan->monthly}}</td>
                                        <td style="white-space: nowrap;">
                                            <a class="btn btn-sm btn-success" href="{{ URL::to('device-loans/' . $loan->id) }}" >
                                                <i class="mdi mdi-eye-outline" aria-hidden="true"></i>
                                            </a>

                                        </td>

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
@endsection

@section('footer_scripts')

    @include('scripts.delete-modal-script')
    @include('scripts.save-modal-script')

    <!-- Required datatable js -->
    <script src="{{asset('assets/libs/datatables.net/js/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js')}}"></script>
    <!-- Buttons examples -->
    <script src="{{asset('assets/libs/datatables.net-buttons/js/dataTables.buttons.min.js')}}"></script>
    <script src="{{asset('assets/libs/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js')}}"></script>
    <script src="{{asset('assets/libs/jszip/jszip.min.js')}}"></script>
    <script src="{{asset('assets/libs/pdfmake/build/pdfmake.min.js')}}"></script>
    <script src="{{asset('assets/libs/pdfmake/build/vfs_fonts.js')}}"></script>
    <script src="{{asset('assets/libs/datatables.net-buttons/js/buttons.html5.min.js')}}"></script>
    <script src="{{asset('assets/libs/datatables.net-buttons/js/buttons.print.min.js')}}"></script>
    <script src="{{asset('assets/libs/datatables.net-buttons/js/buttons.colVis.min.js')}}"></script>
    <!-- Responsive examples -->
    <script src="{{asset('assets/libs/datatables.net-responsive/js/dataTables.responsive.min.js')}}"></script>
    <script src="{{asset('assets/libs/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js')}}"></script>

    <!-- Datatable init js -->
    <script src="{{asset('assets/js/pages/datatables.init.js')}}"></script>

@endsection
