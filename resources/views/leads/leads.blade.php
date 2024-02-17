<?php
/**
 * Created by PhpStorm for eshagi
 * User: vinceg
 * Date: 16/2/2021
 * Time: 20:20
 */
?>
@extends('layouts.app')

@section('template_title')
    Leads
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
                    <h4 class="page-title mb-1">Leads</h4>
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{url('/leads')}}">Leads</a></li>
                        <li class="breadcrumb-item active">All Sales Leads</li>
                    </ol>
                </div>

                <div class="col-md-4">
                    <div class="float-right d-none d-md-block">
                        <div>
                            <a class="btn btn-light btn-rounded" href="{{url('/leads/create')}}" type="button">
                                <i class="mdi mdi-plus mr-1"></i>Add Lead
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
                            <h4 class="header-title">Leads Table</h4>
                            <table id="datatable-buttons" class="table table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                <thead>
                                    <tr>
                                        <th class="hidden">ID</th>
                                        <th>Username</th>
                                        <th>National ID</th>
                                        <th>Mobile</th>
                                        <th>Agent</th>
                                        <th>Is Contacted?</th>
                                        <th style="white-space: nowrap;">Action</th>
                                    </tr>
                                </thead>

                               {{-- <tbody>
                                @foreach($leads->chunk(100) as $row)
                                    @foreach($row as $lead)
                                        <tr>
                                            <td>{{$lead->name}}</td>
                                            <td>{{$lead->natid}}</td>
                                            <td>{{$lead->mobile}}</td>
                                            <td>{{$lead->agent}}</td>
                                            <td>@if($lead->isContacted) Yes @else No @endif</td>
                                            <td style="white-space: nowrap;">
                                                {!! Form::open(array('url' => 'leads/' . $lead->id, 'class' => 'btn btn-sm btn-danger ')) !!}
                                                {!! Form::hidden('_method', 'DELETE') !!}
                                                {!! Form::button('<i class="mdi mdi-trash-can-outline" aria-hidden="true"></i>' , array('class' => 'btn btn-sm btn-danger ','type' => 'button', 'data-toggle' => 'modal', 'data-target' => '#confirmDelete', 'data-title' => 'Delete Lead', 'data-message' => 'Are you sure you want to delete this lead ?')) !!}
                                                {!! Form::close() !!}

                                                <a class="btn btn-sm btn-success" href="{{ URL::to('leads/' . $lead->id) }}" >
                                                    <i class="mdi mdi-eye-outline" aria-hidden="true"></i>
                                                </a>

                                                <a class="btn btn-sm btn-info" href="{{ URL::to('leads/' . $lead->id . '/edit') }}" >
                                                    <i class="mdi mdi-account-edit-outline" aria-hidden="true"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endforeach
                                </tbody>--}}
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
    <script>
        /*$(function () {
            $("#datatable").DataTable(), $('#datatable-buttons').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{ route('leads') }}",
                columns: [
                    {data: 'id', name: 'id'},
                    {data: 'name', name: 'name'},
                    {data: 'natid', name: 'natid'},
                    {data: 'mobile', name: 'mobile'},
                    {data: 'agent', name: 'agent'},
                    {data: 'isContacted', name: 'isContacted'},
                    {data: 'action', name: 'action', orderable: false, searchable: false},
                ],
                buttons: ["copy", "excel","csv", "pdf", "colvis"]
            }).buttons().container().appendTo("#datatable-buttons_wrapper .col-md-6:eq(0)");
        });*/

        $(document).ready(function () {
            $("#datatable").DataTable(), $("#datatable-buttons").DataTable({
                processing: true,
                serverSide: true,
                buttons: ["copy", "excel","csv", "pdf", "colvis"],
                ajax: "{{ route('leads') }}",
                columns: [
                    {data: 'id', name: 'id'},
                    {data: 'name', name: 'name'},
                    {data: 'natid', name: 'natid'},
                    {data: 'mobile', name: 'mobile'},
                    {data: 'agent', name: 'agent'},
                    {data: 'isContacted', name: 'isContacted'},
                    {data: 'action', name: 'action', orderable: false, searchable: false},
                ],
                lengthChange: 1,
            }).buttons().container().appendTo("#datatable-buttons_wrapper .col-md-6:eq(0)")
        });
    </script>

@endsection
