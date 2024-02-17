<?php
/**
 * Created by PhpStorm for eshagi
 * User: vinceg
 * Date: 9/5/2021
 * Time: 11:37
 */
?>
@extends('layouts.app')

@section('template_title')
    Import Products
@endsection

@section('template_linked_css')
    <link href="{{ asset('css/select2.min.css')}}" rel="stylesheet" />
@endsection

@section('content')
    <div class="page-title-box">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h4 class="page-title mb-1">Products</h4>
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{url('/our-products')}}">Products</a></li>
                        <li class="breadcrumb-item active">Upload Bulk Products</li>
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
                            {!! Form::open(array('route' => 'import.bulkproducts', 'method' => 'POST', 'role' => 'form', 'class' => 'needs-validation', 'enctype'=>'multipart/form-data')) !!}
                            {!! csrf_field() !!}

                            @if(\Illuminate\Support\Facades\Auth::user()->utype != 'Partner')
                                <div class="form-group has-feedback row {{ $errors->has('creator') ? ' has-error ' : '' }}">
                                    {!! Form::label('creator', 'Product Owner', array('class' => 'col-md-3 control-label')); !!}
                                    <div class="col-md-9">
                                        <select class="custom-select form-control dynamic" name="creator" id="creator" required>
                                            <option value="">Select Partner</option>
                                            @if ($partners)
                                                @foreach($partners as $partner)
                                                    <option value="{{ $partner->id }}" >{{ $partner->name }} </option>
                                                @endforeach
                                            @endif
                                        </select>
                                        @if ($errors->has('creator'))
                                            <span class="help-block">
                                            <strong>{{ $errors->first('creator') }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                </div>
                            @else
                                <input type="hidden" name="creator" id="creator" value="{{auth()->user()->name}}">
                            @endif

                            <div class="form-group has-feedback row {{ $errors->has('products_excel') ? ' has-error ' : '' }}">
                                {!! Form::label('products_excel', 'Products Excel File', array('class' => 'col-md-3 control-label')); !!}
                                <div class="col-md-9">
                                    <div class="custom-file">
                                        <input type="file" class="form-control" id="products_excel" name="products_excel" required>
                                        @if ($errors->has('products_excel'))
                                            <div class="invalid-feedback">
                                                <strong>{{ $errors->first('products_excel') }}</strong>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <br>
                            <div class="text-danger">
                                <strong>1. Please remove file headings. i.e. PRODUCT CODE, SERIAL, PRODUCT NAME, MODEL, DESCRIPTION, PRICE
                                    <br> as illustrated: <br>
                                    <img src="{{asset('project/public/upload_products.png')}}" alt="" style="width: 900px;">
                                </strong> <br>
                                <strong>2. Please make sure your file is in that particular order. </strong><br>
                            </div>

                            {!! Form::button('Upload Products', array('class' => 'btn btn-success margin-bottom-1 mb-1 float-right','type' => 'submit' )) !!}
                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('footer_scripts')

    <script src="{{ asset('js/select2.min.js')}}"></script>

    <script type="text/javascript">
        $("#partner_id").select2({
            placeholder: 'Please select a Partner.',
            allowClear:true,
        });
    </script>

@endsection
