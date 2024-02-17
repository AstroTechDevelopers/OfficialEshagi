<?php
/**
 * Created by PhpStorm for eshagi
 * User: vinceg
 * Date: 28/12/2020
 * Time: 23:55
 */
?>
@extends('layouts.app')

@section('template_title')
    Awaiting Disbursement
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
                    <h4 class="page-title mb-1">Loans</h4>
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{url('/loans')}}">Loans</a></li>
                        <li class="breadcrumb-item active">Awaiting RedSphere Disbursement</li>
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
                            <h4 class="header-title">Awaiting Disbursement</h4>
                            <p class="text-info"> Loans Posted to CBZ, still waiting to be disbursed into relevant client account.</p>
                            <table id="datatable-buttons" class="table table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                <thead>
                                <tr>
                                    <th>Loan Type</th>
                                    <th>Loan Number</th>
                                    <th>Name</th>
                                    <th>Surname</th>
                                    <th>National ID</th>
                                    <th>EC Number</th>
                                    <th>Bank</th>
                                    <th>Branch</th>
                                    <th>Account Number</th>
                                    <th>Amount</th>
                                    <th>Tenure</th>
                                    <th>Monthly Payments</th>
                                    <th>Is Disbursed ?</th>
                                    <th>Actions</th>
                                </tr>
                                </thead>

                                <tbody>
                                @foreach($loans as $loan)
                                    <tr>
                                        <td>{{getLoantype($loan->loan_type)}}</td>
                                        <td>{{$loan->loan_number}}</td>
                                        <td>{{$loan->first_name}}</td>
                                        <td>{{$loan->last_name}}</td>
                                        <td>{{$loan->natid}}</td>
                                        <td>{{$loan->ecnumber}}</td>
                                        <td>{{$loan->bank_short}}</td>
                                        <td>{{$loan->branch}}</td>
                                        <td>{{$loan->acc_number}}</td>
                                        <td>{{$loan->amount}}</td>
                                        <td>{{$loan->paybackPeriod}}</td>
                                        <td>{{$loan->monthly}}</td>
                                        <td>@if ($loan->isDisbursed == 1)
                                                Yes
                                            @else
                                                No
                                            @endif</td>
                                        <td style="white-space: nowrap;">
                                            {{--<a class="btn btn-sm btn-success" href="{{url('/check-loan/'.$loan->loan_number)}}" data-toggle="tooltip" data-placement="top" title="Check Loan Status">
                                                <i class="mdi mdi-selection-search" aria-hidden="true"></i>
                                            </a>--}}
                                            <a class="btn btn-sm btn-success" href="{{url('/loan-disburse-check/'.$loan->loan_number)}}" data-toggle="tooltip" data-placement="top" title="Check Disbursement State">
                                                <i class="mdi mdi-briefcase-search" aria-hidden="true"></i>
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
