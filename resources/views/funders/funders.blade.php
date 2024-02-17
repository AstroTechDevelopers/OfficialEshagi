<?php
/**
 *Created by PhpStorm for eshagi
 *User: Vincent Guyo
 *Date: 10/10/2020
 *Time: 5:42 PM
 */

?>
@extends('layouts.app')

@section('template_title')
    Funders
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
                    <h4 class="page-title mb-1">Funders</h4>
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{url('/funders')}}">Funders</a></li>
                        <li class="breadcrumb-item active">All Funders</li>
                    </ol>
                </div>

                <div class="col-md-4">
                    <div class="float-right d-none d-md-block">
                        <div>
                            <a class="btn btn-light btn-rounded" href="{{url('/funders/create')}}" type="button">
                                <i class="mdi mdi-plus mr-1"></i>Add Funder
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
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="header-title">Funders Table</h4>
                            <table id="datatable-buttons" class="table table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                <thead>
                                <tr>
                                    <th>Locale</th>
                                    <th>Funder</th>
                                    <th>Contact Name</th>
                                    <th>Email</th>
                                    <th>Telephone</th>
                                    <th>Support Email</th>
                                    <th>Added On</th>
                                    <th>Action</th>
                                </tr>
                                </thead>

                                <tbody>
                                @foreach($funders as $funder)
                                    <tr>
                                        <td>{{$funder->country}}</td>
                                        <td>{{$funder->funder}}</td>
                                        <td>{{$funder->contact_fname.' '.$funder->contact_lname}}</td>
                                        <td>{{$funder->email}}</td>
                                        <td>{{$funder->tel_no}}</td>
                                        <td>{{$funder->support_email}}</td>
                                        <td>{{$funder->created_at}}</td>
                                        <td style="white-space: nowrap;">
                                            {!! Form::open(array('url' => 'funders/' . $funder->fid, 'class' => 'btn btn-sm btn-danger ')) !!}
                                            {!! Form::hidden('_method', 'DELETE') !!}
                                            {!! Form::button('<i class="mdi mdi-trash-can-outline" aria-hidden="true"></i>' , array('class' => 'btn btn-sm btn-danger ','type' => 'button', 'data-toggle' => 'modal', 'data-target' => '#confirmDelete', 'data-title' => 'Delete Funder', 'data-message' => 'Are you sure you want to delete this funder ? This will probably cause problems for linked items.')) !!}
                                            {!! Form::close() !!}

                                            <a class="btn btn-sm btn-info" href="{{ URL::to('funders/' . $funder->fid . '/edit') }}" >
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
