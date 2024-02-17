<?php
/**
 *Created by PhpStorm for eshagi
 *User: Vincent Guyo
 *Date: 9/25/2020
 *Time: 11:28 AM
 */

?>
@extends('layouts.app')

@section('template_title')
    {{ Auth::user()->name }}'s' Homepage
@endsection

@section('template_linked_css')
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
                <div class="col-md-6">
                    <h4 class="page-title mb-1">Hello, </h4>
                    <h5 class="text-white">{{auth()->user()->first_name}}</h5>
                </div>

                <div class="col-md-6">
                    <div class="float-right d-none d-md-block ">
                        <div>
                            <a class="btn btn-light btn-rounded" href="{{url('/new-partner-credit')}}" type="button">
                                <i class="mdi mdi-plus mr-1"></i>Existing Product Loan
                            </a>
                        </div>
                    </div>

                    <div class="float-right d-none d-md-block mr-3">
                        <div>
                            <a class="btn btn-light btn-rounded" href="{{url('/new-partner-loan')}}" type="button">
                                <i class="mdi mdi-plus mr-1"></i>Existing Cash Loan
                            </a>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <div class="page-content-wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-xl-3">
                    <div class="card">
                        <div class="card-body">
                            <h3>{{$pending}}</h3>
                            <h5 class="header-title mb-4">Pending</h5>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3">
                    <div class="card">
                        <div class="card-body">
                            <h3>{{$review}}</h3>
                            <h5 class="header-title mb-4">Review</h5>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3">
                    <div class="card">
                        <div class="card-body">
                            <h3>{{$successful}}</h3>
                            <h5 class="header-title mb-4">Successful</h5>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3">
                    <div class="card">
                        <div class="card-body">
                            <h3>{{$declined}}</h3>
                            <h5 class="header-title mb-4">Rejected</h5>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xl-6">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="header-title mb-4">Top 5 Best Selling Products</h4>

                            <div id="pie_chart" class="apex-charts" dir="ltr"></div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3">
                    <div class="card">
                        <div class="card-body">
                            <h3>{{$salesrep}}</h3>
                            <h5 class="header-title mb-4">Representatives</h5>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3">
                    <div class="card">
                        <div class="card-body">
                            <h3>{{$products}}</h3>
                            <h5 class="header-title mb-4">Products</h5>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="header-title">{{auth()->user()->first_name}} Loans</h4>
                            <table id="datatable-buttons" class="table table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                <thead>
                                <tr>
                                    <th>Full Name</th>
                                    <th>Loan Type</th>
                                    <th>Loan Applied</th>
                                    <th>Amount Disbursed</th>
                                    <th>Status</th>
                                    <th>Applied On</th>
                                    <th>Actions</th>
                                </tr>
                                </thead>

                                <tbody>
                                @foreach($loans as $loan)
                                    <tr>
                                        <td>{{$loan->first_name}} {{$loan->last_name}}</td>
                                        <td>@if ($loan->loan_type == 1)
                                                Store Credit
                                            @elseif($loan->loan_type == 2)
                                                Cash Loan
                                            @elseif($loan->loan_type == 3)
                                                Recharge Credit
                                            @elseif($loan->loan_type == 4)
                                                Hybrid Loan
                                            @endif</td>
                                        <td>{{$loan->amount}}</td>
                                        <td>{{$loan->disbursed}}</td>
                                        <td>@if ($loan->loan_status == 0)
                                                Not Signed
                                            @elseif($loan->loan_status == 1)
                                                New
                                            @elseif($loan->loan_status == 2)
                                                Stop Order (PRIVATE)
                                            @elseif($loan->loan_status == 3)
                                                MOU (PRIVATE)
                                            @elseif($loan->loan_status == 4)
                                                Client Bank (PRIVATE)
                                            @elseif($loan->loan_status == 5)
                                                HR(PRIVATE)
                                            @elseif($loan->loan_status == 6)
                                                CBZ Banking(PRIVATE)
                                            @elseif($loan->loan_status == 7)
                                                RedSphere Processing(PRIVATE)
                                            @elseif($loan->loan_status == 8)
                                                CBZ KYC(GOVT)
                                            @elseif($loan->loan_status == 9)
                                                Ndasenda Processing
                                            @elseif($loan->loan_status == 10)
                                                Ndasenda Approved
                                            @elseif($loan->loan_status == 11)
                                                RedSphere Processing(GOVT)
                                            @elseif($loan->loan_status == 12)
                                                Disbursed
                                            @elseif($loan->loan_status == 13)
                                                Declined
                                            @elseif($loan->loan_status == 14)
                                                Paid Back
                                            @endif</td>
                                        <td>{{$loan->created_at}}</td>
                                        <td style="white-space: nowrap;">

                                            <a class="btn btn-sm btn-success" href="{{ URL::to('loans/' . $loan->id) }}" data-toggle="tooltip" title="Show">
                                                <i class="mdi mdi-eye-outline" aria-hidden="true"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div> <!-- end col -->
            </div>
        </div>
    </div>

@endsection

@section('footer_scripts')
    @include('scripts.delete-modal-script')
    @include('scripts.save-modal-script')

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

    <script>
        var musvo = {!! json_encode($bestsellersData) !!};
        options = {
            chart: {height: 320, type: "pie"},
            series: Object.values(musvo),
            labels: Object.keys(musvo),
            colors: ["#3051d3", "#2fa97c", "#e4cc37", "#f06543", "#420dab"],
            legend: {
                show: 1,
                position: "bottom",
                horizontalAlign: "center",
                verticalAlign: "middle",
                floating: !1,
                fontSize: "12px",
                offsetX: 0,
                offsetY: 0
            },
            responsive: [{breakpoint: 600, options: {chart: {height: 240}, legend: {show: !1}}}]
        };
        (chart = new ApexCharts(document.querySelector("#pie_chart"), options)).render()
    </script>

    <script>
        $(document).ready(function () {
           $("#dtable-buttons").DataTable({
                lengthChange: 1,
                buttons: ["copy", "excel","csv", "pdf", "colvis"]
            }).buttons().container().appendTo("#datatable-buttons_wrapper .col-md-6:eq(0)")
        });
    </script>

@endsection
