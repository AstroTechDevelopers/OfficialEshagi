<?php
/**
 * Created by PhpStorm for eshagi
 * User: vinceg
 * Date: 17/2/2021
 * Time: 07:50
 */
?>
@extends('layouts.app')

@section('template_title')
    Upload Sales Leads
@endsection

@section('content')
    <div class="page-title-box">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h4 class="page-title mb-1">Sales Leads</h4>
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{url('/leads')}}">Sales Leads</a></li>
                        <li class="breadcrumb-item active">Upload Bulk Leads</li>
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
                            {!! Form::open(array('route' => 'process.bulkleads', 'method' => 'POST', 'role' => 'form', 'class' => 'needs-validation', 'enctype'=>'multipart/form-data')) !!}
                            {!! csrf_field() !!}

                            <div class="col-md-6 mb-3">
                                <label class="control-label">Sales Lead Excel File</label>
                                <div class="custom-file">
                                    <input type="file" class="form-control" id="leads_excel" name="leads_excel" required>
                                    @if ($errors->has('leads_excel'))
                                        <div class="invalid-feedback">
                                            <strong>{{ $errors->first('leads_excel') }}</strong>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <br>
                            <div class="text-danger">
                                <strong>1. Please remove file headings. i.e. USERNAME, FIRSTNAME, SURNAME, EMAIL, NATIONAL ID, MOBILE, PASSWORD, TOKEN, EC NUMBER, ADDRESS, LOCALE
                                    <br> as illustrated: <br>
                                    <img src="{{asset('upload_leads.png')}}" alt="" style="width: 900px;">
                                </strong> <br>
                                <strong>2. Please make sure your file is in that particular order. You can leave the password & token columns empty.</strong><br>
                                <strong>3. Please make sure the EC Number is present for each record.</strong><br>
                            </div>

                            {!! Form::button('Upload Leads', array('class' => 'btn btn-success margin-bottom-1 mb-1 float-right','type' => 'submit' )) !!}
                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
