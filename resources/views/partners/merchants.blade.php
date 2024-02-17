<?php
/**
 *Created by PhpStorm for eshagi
 *User: Vincent Guyo
 *Date: 10/11/2020
 *Time: 7:23 AM
 */

?>
@extends('layouts.app')

@section('template_title')
    Merchants
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
                <div class="col-md-8">
                    <h4 class="page-title mb-1">Merchants</h4>
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{url('/merchants')}}">Merchants</a></li>
                        <li class="breadcrumb-item active">All Merchants</li>
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
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="header-title">Merchants Table</h4>
                            <table id="datatable-buttons" class="table table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                <thead>
                                <tr>
                                    <th>Merchant</th>
                                    <th>Business Type</th>
                                    <th>Reg Number</th>
                                    <th>TPIN</th>
                                    <th>Registered On</th>
                                    <th>Contact</th>
                                    <th>Telephone</th>
                                    <th>Email</th>
                                    <th>City</th>
                                    <th>Action</th>
                                </tr>
                                </thead>

                                <tbody>
                                @foreach($merchants as $merchant)
                                    <tr>
                                        <td>{{$merchant->partner_name}}</td>
                                        <td>{{$merchant->business_type}}</td>
                                        <td>{{$merchant->regNumber}}</td>
                                        <td>{{$merchant->bpNumber}}</td>
                                        <td>{{$merchant->created_at}}</td>
                                        <td>{{$merchant->cfullname}}</td>
                                        <td>{{$merchant->telephoneNo}}</td>
                                        <td>{{$merchant->cemail}}</td>
                                        <td>{{$merchant->city}}</td>
                                        <td style="white-space: nowrap;">
                                            <a class="btn btn-sm btn-success" href="{{ URL::to('partners/' . $merchant->id) }}" >
                                                <i class="mdi mdi-eye-outline" aria-hidden="true"></i>
                                            </a>

                                            <a class="btn btn-sm btn-info" href="{{ URL::to('partners/' . $merchant->id . '/edit') }}" >
                                                <i class="mdi mdi-account-edit-outline" aria-hidden="true"></i>
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
