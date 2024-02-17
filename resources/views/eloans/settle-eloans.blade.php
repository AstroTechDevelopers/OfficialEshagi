<?php
/**
 * Created by PhpStorm for eshagitwo
 * User: vinceg
 * Date: 22/8/2021
 * Time: 09:10
 */
?>
@extends('layouts.app')

@section('template_title')
    eLoans To Be settled
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
                        <li class="breadcrumb-item"><a href="{{url('/eloans')}}">eLoan</a></li>
                        <li class="breadcrumb-item active">eLoans to be paid off</li>
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
                            <h4 class="header-title">eLoans to be paid off</h4>
                            <p>Loans Disbursed by eShagi that can be settled off</p>
                            <table id="datatable-buttons" class="table table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                <thead>
                                <tr>
                                    <th>Full name</th>
                                    <th>National ID</th>
                                    <th>Total</th>
                                    <th>Monthly</th>
                                    <th>Loan Type</th>
                                    <th>Actions</th>
                                </tr>
                                </thead>

                                <tbody>
                                @foreach($loans as $loan)
                                    <tr>
                                        <td>{{$loan->first_name.' '.$loan->last_name}}</td>
                                        <td>{{$loan->natid}}</td>
                                        <td>{{$loan->amount}}</td>
                                        <td>{{$loan->monthly}}</td>
                                        <td>{{getELoanType($loan->loan_type)}}</td>
                                        <td style="white-space: nowrap;">

                                            <a class="btn btn-sm btn-success" href="{{ URL::to('settle-eloan/' . $loan->id) }}" data-id="{{$loan->id}}" data-toggle="modal" data-target="#confirmSettle{{$loan->id}}" data-whatever="@mdo" >
                                                <i class="mdi mdi-cash-refund" aria-hidden="true"></i>
                                            </a>

                                            @if(count($loans)>0)
                                                @include('modals.modal-esettle')
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
    @if(count($loans)>0)
        @include('modals.modal-esettle')
    @endif
@endsection

@section('footer_scripts')

    @include('scripts.form-modal-script')
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
