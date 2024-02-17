<?php
/**
 * Created by PhpStorm for eshagitwo
 * User: VinceGee
 * Date: 11/15/2021
 * Time: 12:10 AM
 */ ?>
@extends('layouts.app')

@section('template_title')
    My Loan Requests
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
                    <h4 class="page-title mb-1">Loan Requests</h4>

                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="#">Loan Requests</a></li>
                        <li class="breadcrumb-item active">My Loan Requests</li>
                    </ol>
                </div>

                <div class="col-md-4">
                    <div class="float-right d-none d-md-block">
                        <div>
                            <a class="btn btn-success btn-rounded" href="{{url('loan-requests/create')}}" type="button">
                                <i class="mdi mdi-file-plus mr-1"></i>Place Loan Request
                            </a>
                        </div>
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
                            <h4 class="header-title">My Loan Requests</h4>
                            <p>All loan requests that I placed in the system</p>
                            <table id="datatable-buttons" class="table table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                <thead>
                                <tr>
                                    <th>Loan ID</th>
                                    <th>National ID</th>
                                    <th>Request</th>
                                    <th>Approved By</th>
                                    <th>Approved At</th>
                                    <th>Actions</th>
                                </tr>
                                </thead>

                                <tbody>
                                @foreach($requests as $request)
                                    <tr>
                                        <td><a href="{{url('/loans/'.$request->lid)}}" target="_blank">{{$request->lid}}</a></td>
                                        <td>{{$request->natid}}</td>
                                        <td>{{$request->request}}</td>
                                        <td>{{$request->approver}}</td>
                                        <td>{{$request->approved_at}}</td>
                                        <td style="white-space: nowrap;">
                                            {!! Form::open(array('url' => 'loan-requests/' . $request->id, 'class' => 'd-inline')) !!}
                                            {!! Form::hidden('_method', 'DELETE') !!}
                                            {!! Form::button('<i class="mdi mdi-trash-can-outline" aria-hidden="true"></i>' , array('class' => 'btn btn-sm btn-danger ','type' => 'button', 'data-toggle' => 'modal', 'data-target' => '#confirmDelete', 'data-title' => 'Delete Loan Request', 'data-message' => 'Are you sure you want to delete this loan request ?')) !!}
                                            {!! Form::close() !!}

                                            <a class="btn btn-sm btn-success" href="{{ URL::to('loan-requests/' . $request->id) }}" >
                                                <i class="mdi mdi-eye-outline" aria-hidden="true"></i>
                                            </a>

                                            <a class="btn btn-sm btn-info" href="{{ URL::to('loan-requests/' . $request->id . '/edit') }}" >
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
