<?php
/**
 * Created by PhpStorm for eshagitwo
 * User: vincegee
 * Date: 5/9/2021
 * Time: 18:02
 */
?>
@extends('layouts.app')

@section('template_title')
    My eLoans
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
                    <h4 class="page-title mb-1">eLoans</h4>
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{url('/myeloans')}}">eLoan</a></li>
                        <li class="breadcrumb-item active">My eLoans</li>
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
                            <h4 class="header-title">My Personal eLoans</h4>
                            <p class="text-info"> Total Monthly repayments of ${{$monthlies}}</p>
                            <table id="datatable-buttons" class="table table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                <thead>
                                <tr>
                                    <th>Loan Type</th>
                                    <th>Loan Status</th>
                                    <th>Amount</th>
                                    <th>Tenure</th>
                                    <th>Interest %</th>
                                    <th>Monthly Payments</th>
                                    <th>Actions</th>
                                </tr>
                                </thead>

                                <tbody>
                                @foreach($loans as $loan)
                                    <tr>
                                        <td>@if ($loan->loan_type == 1)
                                                Cash Loan
                                            @elseif($loan->loan_type == 2)
                                                Store Credit Loan
                                            @elseif($loan->loan_type == 3)
                                                Hybrid Loan
                                            @elseif($loan->loan_type == 4)
                                                Business Loan
                                            @elseif($loan->loan_type == 5)
                                                Recharge Loan
                                            @endif</td>
                                        <td>@if ($loan->loan_status == 0)
                                                Not Signed
                                            @elseif($loan->loan_status == 1)
                                                AWAIT FCB Approval
                                            @elseif($loan->loan_status == 2)
                                                AWAIT KYC Approval
                                            @elseif($loan->loan_status == 3)
                                                KYC Approved
                                            @elseif($loan->loan_status == 4)
                                                KYC Rejected
                                            @elseif($loan->loan_status == 5)
                                                Loan Authorized
                                            @elseif($loan->loan_status == 6)
                                                Loan Rejected
                                            @elseif($loan->loan_status == 7)
                                                Await Disbursement
                                            @elseif($loan->loan_status == 8)
                                                Disbursed
                                            @elseif($loan->loan_status == 9)
                                                Repaying
                                            @elseif($loan->loan_status == 10)
                                                Paid Back
                                            @endif</td>
                                        <td>{{$loan->amount}}</td>
                                        <td>{{$loan->tenure}}</td>
                                        <td>{{$loan->interestRate}}</td>
                                        <td>{{$loan->monthly}}</td>
                                        <td style="white-space: nowrap;">
                                            <a class="btn btn-sm btn-success" href="{{ URL::to('loans/' . $loan->id) }}" >
                                                <i class="mdi mdi-eye-outline" aria-hidden="true"></i>
                                            </a>
                                            @if ($loan->loan_status == 0)
                                                <a class="btn btn-sm btn-success" href="{{ URL::to('geteloaninfo/' . $loan->id) }}" >
                                                    <i class="mdi mdi-signature-image" aria-hidden="true"></i>
                                                </a>
                                            @endif
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
