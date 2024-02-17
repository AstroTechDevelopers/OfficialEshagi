<?php
/**
 *Created by PhpStorm for eshagi
 *User: Vincent Guyo
 *Date: 11/8/2020
 *Time: 6:05 PM
 */

?>
@extends('layouts.app')

@section('template_title')
    All Products
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
                    <h4 class="page-title mb-1">Products</h4>
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{url('/products')}}">Products</a></li>
                        <li class="breadcrumb-item active">All Products</li>
                    </ol>
                </div>

                <div class="col-md-4">
                    <div class="float-right d-none d-md-block">
                        <div>
                            <a class="btn btn-light btn-rounded" href="{{url('/products/create')}}" type="button">
                                <i class="mdi mdi-plus mr-1"></i>Add Product
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
                            <h4 class="header-title">All Products</h4>
                            <table id="datatable-buttons" class="table table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                <thead>
                                <tr>
                                    <th>Owner</th>
                                    <th>Product Code</th>
                                    <th>Serial #</th>
                                    <th>Name</th>
                                    <th>Model</th>
                                    <th>Price</th>
                                    {{-- <th>USD Price</th> --}}
                                    <th>Actions</th>
                                </tr>
                                </thead>

                                <tbody>
                                @foreach($products as $product)
                                    <tr>
                                        <td>{{$product->creator}}</td>
                                        <td>{{$product->pcode}}</td>
                                        <td>{{$product->serial}}</td>
                                        <td>{{$product->pname}}</td>
                                        <td>{{$product->model}}</td>
                                        <td>{{$product->price}}</td>
                                        {{-- <td>{{$product->usd_price}}</td> --}}
                                        <td style="white-space: nowrap;">
                                            {!! Form::open(array('url' => 'products/' . $product->id, 'class' => 'd-inline')) !!}
                                            {!! Form::hidden('_method', 'DELETE') !!}
                                            {!! Form::button('<i class="mdi mdi-trash-can-outline" aria-hidden="true"></i>' , array('class' => 'd-inline btn btn-sm btn-danger ','type' => 'button', 'data-toggle' => 'modal', 'data-target' => '#confirmDelete', 'data-title' => 'Delete Product', 'data-message' => 'Are you sure you want to delete this product ?')) !!}
                                            {!! Form::close() !!}

                                            <a class="btn btn-sm btn-success" href="{{ URL::to('products/' . $product->id) }}" >
                                                <i class="mdi mdi-eye-outline" aria-hidden="true"></i>
                                            </a>

                                            <a class="btn btn-sm btn-info" href="{{ URL::to('products/' . $product->id . '/edit') }}" >
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
