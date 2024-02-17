<?php
/**
 * Created by PhpStorm for eshagi
 * User: vinceg
 * Date: 21/3/2021
 * Time: 20:13
 */
?>
@extends('layouts.app')

@section('template_title')
    Deleted Astrogents
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
                    <h4 class="page-title mb-1">Deleted Astrogents</h4>
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{url('/astrogents/deleted')}}">Deleted Astrogents</a></li>
                        <li class="breadcrumb-item active">Deleted Astrogents</li>
                    </ol>
                </div>

                <div class="col-md-4">
                    <div class="float-right d-none d-md-block">
                        <div>
                            <a class="btn btn-light btn-rounded" href="{{url('/astrogents')}}" type="button">
                                <i class="mdi mdi-keyboard-backspace mr-1"></i>Back to astrogents
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
                            @if(count($astrogents) === 0)

                                <tr>
                                    <p class="text-center margin-half">
                                        No deleted astrogents found.
                                    </p>
                                </tr>

                            @else

                                <table id="datatable-buttons" class="table table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                    <thead>
                                    <tr>
                                        <th>First Name</th>
                                        <th>Surname</th>
                                        <th>National ID</th>
                                        <th>Mobile</th>
                                        <th>Email</th>
                                        <th>Deleted On</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>

                                    <tbody>
                                    @foreach($astrogents as $astrogent)
                                        <tr>
                                            <td>{{$astrogent->first_name}}</td>
                                            <td>{{$astrogent->last_name}}</td>
                                            <td>{{$astrogent->natid}}</td>
                                            <td>{{$astrogent->mobile}}</td>
                                            <td>{{$astrogent->email}}</td>
                                            <td>{{$astrogent->deleted_at}}</td>
                                            <td style="white-space: nowrap;">
                                                {!! Form::model($astrogent, array('action' => array('App\Http\Controllers\SoftDeleteAstrogent@update', $astrogent->id), 'method' => 'PUT', 'class' => 'd-inline')) !!}
                                                {!! Form::button('<i class="mdi mdi-restore" aria-hidden="true"></i>', array('class' => 'btn btn-success btn-sm d-inline', 'type' => 'submit')) !!}
                                                {!! Form::close() !!}

                                                <a class="btn btn-sm btn-info d-inline" href="{{ URL::to('astrogents/deleted/' . $astrogent->id) }}">
                                                    <i class="mdi mdi-eye-outline" aria-hidden="true"></i>
                                                </a>

                                                {!! Form::model($astrogent, array('action' => array('App\Http\Controllers\SoftDeleteAstrogent@destroy', $astrogent->id), 'method' => 'DELETE', 'class' => 'd-inline')) !!}
                                                {!! Form::hidden('_method', 'DELETE') !!}
                                                {!! Form::button('<i class="mdi mdi-trash-can-outline" aria-hidden="true"></i>', array('class' => 'btn btn-danger btn-sm d-inline','type' => 'button','data-toggle' => 'modal', 'data-target' => '#confirmDelete', 'data-title' => 'Delete Astrogent', 'data-message' => 'Are you sure you want to delete this astrogent ?')) !!}
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
