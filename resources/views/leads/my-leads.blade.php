<?php
/**
 * Created by PhpStorm for eshagi
 * User: vinceg
 * Date: 17/2/2021
 * Time: 15:48
 */
?>
@extends('layouts.app')

@section('template_title')
    My Leads
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
                    <h4 class="page-title mb-1">My Leads</h4>
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{url('/my-leads')}}">Leads</a></li>
                        <li class="breadcrumb-item active">My Sales Leads</li>
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
                                    <th>Username</th>
                                    <th>National ID</th>
                                    <th>Mobile</th>
                                    <th>Is SMSed</th>
                                    <th>Is Contacted?</th>
                                    <th>Action</th>
                                </tr>
                                </thead>

                                <tbody>
                                @foreach($leads->chunk(100) as $row)
                                    @foreach($row as $lead)
                                        <tr>
                                            <td>{{$lead->name}}</td>
                                            <td>{{$lead->natid}}</td>
                                            <td>{{$lead->mobile}}</td>
                                            <td>@if($lead->isSMSed) Yes @else No @endif</td>
                                            <td>@if($lead->isContacted) Yes @else No @endif</td>
                                            <td style="white-space: nowrap;">
                                                {!! Form::open(array('route' => ['start.call', $lead->id], 'method' => 'GET', 'class' => 'd-inline')) !!}

                                                {!! Form::button('<i class="mdi mdi-phone-forward" aria-hidden="true"></i>', array('class' => 'btn btn-primary btn-sm d-inline','data-toggle'=>'tooltip', 'data-placement'=>'top', 'title'=>'Call Lead', 'type' => 'submit')) !!}
                                                {!! Form::close() !!}

                                                <a class="btn btn-sm btn-success" href="{{ URL::to('leads/' . $lead->id) }}" >
                                                    <i class="mdi mdi-eye-outline" aria-hidden="true"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
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
