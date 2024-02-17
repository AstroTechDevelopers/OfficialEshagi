<?php
/**
 * Created by PhpStorm for eshagi
 * User: vinceg
 * Date: 16/12/2020
 * Time: 17:43
 */
?>
@extends('layouts.app')

@section('template_title')
    Sales Admin Report
@endsection

@section('template_linked_css')

    <link href="{{ asset('css/select2.min.css')}}" rel="stylesheet" />
    <!-- DataTables -->
    <link href="{{url('assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{url('assets/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css')}}" rel="stylesheet" type="text/css" />

    <!-- Responsive datatable examples -->
    <link href="{{url('assets/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css')}}" rel="stylesheet" type="text/css" />
@endsection

@section('content')
    <div class="page-title-box">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h4 class="page-title mb-1">All Loans Report For All Clients by Agent</h4>
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Reports</a></li>
                        <li class="breadcrumb-item active">Sales Admin Report</li>
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
                    <div class="card">
                        <div class="card-body">
                            @if($loans->count()>0)
                                <strong>Showing All Loans by Agent Recorded on {{date('j F Y')}}</strong>
                                <br>
                                <table id="datatable-buttons" class="table table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                    <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Agent</th>
                                        <th>Client</th>
                                        <th>Amount</th>
                                        <th>Loan Type</th>
                                        <th>Loan State</th>
                                    </tr>
                                    </thead>

                                    <tbody>
                                    @foreach($loans as $loan)
                                        <tr>
                                            <td>{{$loan->created_at}}</td>
                                            <td>{{$loan->creator}}</td>
                                            <td>{{$loan->first_name}} {{$loan->last_name}}</td>
                                            <td>{{$loan->amount}}</td>
                                            <td>@if ($loan->loan_type == 1)
                                                    Store Credit
                                                @elseif($loan->loan_type == 2)
                                                    Cash Loan
                                                @elseif($loan->loan_type == 3)
                                                    Recharge Credit
                                                @endif</td>
                                            <td>@if ($loan->loan_status == 0)
                                                    Not Signed
                                                @elseif($loan->loan_status == 1)
                                                    New
                                                @elseif($loan->loan_status == 2)
                                                    KYC CBZ (PRIVATE)
                                                @elseif($loan->loan_status == 3)
                                                    Stop Order (PRIVATE)
                                                @elseif($loan->loan_status == 4)
                                                    MOU (PRIVATE)
                                                @elseif($loan->loan_status == 5)
                                                    Client Bank (PRIVATE)
                                                @elseif($loan->loan_status == 6)
                                                    HR(PRIVATE)
                                                @elseif($loan->loan_status == 7)
                                                    CBZ Banking(PRIVATE)
                                                @elseif($loan->loan_status == 8)
                                                    RedSphere Processing(PRIVATE)
                                                @elseif($loan->loan_status == 9)
                                                    CBZ KYC(GOVT)
                                                @elseif($loan->loan_status == 10)
                                                    Ndasenda(GOVT)
                                                @elseif($loan->loan_status == 11)
                                                    CBZ Banking(GOVT)
                                                @elseif($loan->loan_status == 12)
                                                    Disbursed
                                                @elseif($loan->loan_status == 13)
                                                    Declined
                                                @elseif($loan->loan_status == 14)
                                                    Paid Back
                                                @endif</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            @else
                                <p class="text-center"><strong>No loans have been recorded as yet for {{date('j F Y')}}</strong></p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('footer_scripts')

    <!-- Required datatable js -->
    <script src="{{url('assets/libs/datatables.net/js/jquery.dataTables.min.js')}}"></script>
    <script src="{{url('assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js')}}"></script>
    <!-- Buttons examples -->
    <script src="{{url('assets/libs/datatables.net-buttons/js/dataTables.buttons.min.js')}}"></script>
    <script src="{{url('assets/libs/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js')}}"></script>
    <script src="{{url('assets/libs/jszip/jszip.min.js')}}"></script>
    <script src="{{url('assets/libs/pdfmake/build/pdfmake.min.js')}}"></script>
    <script src="{{url('assets/libs/pdfmake/build/vfs_fonts.js')}}"></script>
    <script src="{{url('assets/libs/datatables.net-buttons/js/buttons.html5.min.js')}}"></script>
    <script src="{{url('assets/libs/datatables.net-buttons/js/buttons.print.min.js')}}"></script>
    <script src="{{url('assets/libs/datatables.net-buttons/js/buttons.colVis.min.js')}}"></script>
    <!-- Responsive examples -->
    <script src="{{url('assets/libs/datatables.net-responsive/js/dataTables.responsive.min.js')}}"></script>
    <script src="{{url('assets/libs/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js')}}"></script>

    <!-- Datatable init js -->
    <script src="{{url('assets/js/pages/datatables.init.js')}}"></script>

@endsection
