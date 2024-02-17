<?php
/**
 *Created by PhpStorm for eshagi
 *User: Vincent Guyo
 *Date: 10/10/2020
 *Time: 6:50 PM
 */

?>
@extends('layouts.app')

@section('template_title')
    Deleted Funders
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
                    <h4 class="page-title mb-1">Deleted Funders</h4>
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{url('/funders/deleted')}}">Deleted Funders</a></li>
                        <li class="breadcrumb-item active">Deleted System Funders</li>
                    </ol>
                </div>

                <div class="col-md-4">
                    <div class="float-right d-none d-md-block">
                        <div>
                            <a class="btn btn-light btn-rounded" href="{{url('/funders')}}" type="button">
                                <i class="mdi mdi-keyboard-backspace mr-1"></i>Back to funders
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
                            @if(count($funders) === 0)

                                <tr>
                                    <p class="text-center margin-half">
                                        No deleted funders found
                                    </p>
                                </tr>

                            @else

                                <table id="datatable-buttons" class="table table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                    <thead>
                                    <tr>
                                        <th>Funder</th>
                                        <th>Contact Name</th>
                                        <th>Email</th>
                                        <th>Telephone</th>
                                        <th>Support Email</th>
                                        <th>Deleted On</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>

                                    <tbody>
                                    @foreach($funders as $funder)
                                        <tr>
                                            <td>{{$funder->funder}}</td>
                                            <td>{{$funder->contact_fname.' '.$funder->contact_lname}}</td>
                                            <td>{{$funder->email}}</td>
                                            <td>{{$funder->tel_no}}</td>
                                            <td>{{$funder->support_email}}</td>
                                            <td>{{$funder->deleted_at}}</td>
                                            <td style="white-space: nowrap;">

                                            {!! Form::model($funder, array('action' => array('App\Http\Controllers\SoftDeleteFunder@update', $funder->id), 'method' => 'PUT', 'data-toggle' => 'tooltip')) !!}
                                                {!! Form::button('<i class="mdi mdi-restore" aria-hidden="true"></i>', array('class' => 'btn btn-success btn-block btn-sm', 'type' => 'submit', 'data-toggle' => 'tooltip', 'title' => 'Restore Funder')) !!}
                                                {!! Form::close() !!}

                                                {!! Form::model($funder, array('action' => array('App\Http\Controllers\SoftDeleteFunder@destroy', $funder->id), 'method' => 'DELETE', 'class' => 'inline', 'data-toggle' => 'tooltip', 'title' => 'Destroy Funder Record')) !!}
                                                {!! Form::hidden('_method', 'DELETE') !!}
                                                {!! Form::button('<i class="mdi mdi-trash-can-outline" aria-hidden="true"></i>', array('class' => 'btn btn-danger btn-sm inline','type' => 'button', 'style' =>'width: 100%;' ,'data-toggle' => 'modal', 'data-target' => '#confirmDelete', 'data-title' => 'Delete Funder', 'data-message' => 'Are you sure you want to delete this funder ?')) !!}
                                                {!! Form::close() !!}
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>

                            @endif

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
    @include('scripts.tooltips')

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
