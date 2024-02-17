<?php
/**
 * Created by PhpStorm for eshagitwo
 * User: VinceGee
 * Date: 11/10/2022
 * Time: 4:33 AM
 */ ?>

@extends('layouts.app')

@section('template_title')
    Zambia Savings
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
                    <h4 class="page-title mb-1">Clients</h4>
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{url('/zambians')}}">Clients</a></li>
                        <li class="breadcrumb-item active">eShagi Zambia Saving Accounts</li>
                    </ol>
                </div>

                <div class="col-md-4">

                </div>

            </div>
        </div>
    </div>
    <!-- end page title end breadcrumb -->

    <div class="page-content-wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="header-title">Client Saving Accounts Table</h4>
                            <table id="datatable-buttons" class="table table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                <thead>
                                <tr>
                                    <th>First Name</th>
                                    <th>Surname</th>
                                    <th>Loan Disk ID</th>
                                    <th>NRC</th>
                                    <th>Savings Account #</th>
                                    <th>Savings ID</th>
                                    <th>Action</th>
                                </tr>
                                </thead>

                                <tbody>
                                @foreach($kycs as $client)
                                    <tr>
                                        <td>{{$client->first_name}}</td>
                                        <td>{{$client->last_name}}</td>
                                        <td>{{$client->ld_borrower_id}}</td>
                                        <td>{{$client->nrc}}</td>
                                        <td>{{$client->savings_acc}}</td>
                                        <td>{{$client->savings_id}}</td>
                                        <td style="white-space: nowrap;">
                                            <a class="btn btn-sm btn-success" href="{{ URL::to('zambians/' . $client->id) }}" >
                                                <i class="mdi mdi-eye-outline" aria-hidden="true"></i>
                                            </a>

                                            <a class="btn btn-sm btn-primary" href="{{ URL::to('savings-draw-down/' . $client->id) }}" data-toggle="tooltip" data-placement="top" title="Draw Down Account">
                                                <i class="mdi mdi-bank-transfer-out" aria-hidden="true"></i>
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

    @include('modals.modal-delete')

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
