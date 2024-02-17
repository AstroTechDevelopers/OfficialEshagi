<?php
/**
 *Created by PhpStorm for eshagi
 *User: Vincent Guyo
 *Date: 11/5/2020
 *Time: 11:27 AM
 */

$pid = \App\Models\Partner::where('regNumber',auth()->user()->natid)->first();
?>
@extends('layouts.app')

@section('template_title')
    Edit Branch
@endsection

@section('template_linked_css')
    <link href="{{ asset('css/select2.min.css')}}" rel="stylesheet" />
@endsection

@section('content')
    <div class="page-title-box">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h4 class="page-title mb-1">Branches</h4>
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{url('/my-branches')}}">Branches</a></li>
                        <li class="breadcrumb-item active">Edit a Branch</li>
                    </ol>
                </div>

                <div class="col-md-4">
                    <div class="float-right d-none d-md-block">
                        <div>
                            <a class="btn btn-light btn-rounded" href="{{url('/my-branches')}}" type="button">
                                <i class="mdi mdi-keyboard-backspace mr-1"></i>Back to Branches
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
                            {!! Form::open(array('route' => 'save.branch', 'method' => 'POST', 'role' => 'form', 'class' => 'needs-validation')) !!}

                            {!! csrf_field() !!}
							<input type="hidden" name="partner_id" id="partner_id" value="{{$pid->id}}">

                            <div class="form-group has-feedback row {{ $errors->has('name') ? ' has-error ' : '' }}">
                                {!! Form::label('name', 'Name', array('class' => 'col-md-3 control-label')); !!}
                                <div class="col-md-9">
                                    <div class="input-group">
                                        {!! Form::text('name', $branch->name, array('id' => 'name', 'class' => 'form-control', 'placeholder' => 'e.g. AstroCred', 'required')) !!}
                                    </div>
                                    @if ($errors->has('name'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('name') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group has-feedback row {{ $errors->has('location') ? ' has-error ' : '' }}">
                                {!! Form::label('location', 'Location', array('class' => 'col-md-3 control-label')); !!}
                                <div class="col-md-9">
                                    <div class="input-group">
                                        {!! Form::text('location', $branch->location, array('id' => 'location', 'class' => 'form-control', 'placeholder' => 'e.g. Lusaka', 'required')) !!}
                                    </div>
                                    @if ($errors->has('location'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('location') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group has-feedback row {{ $errors->has('contact_no') ? ' has-error ' : '' }}">
                                {!! Form::label('contact_no', 'Contact Number' , array('class' => 'col-md-3 control-label')); !!}
                                <div class="col-md-9">
                                    <div class="input-group">
                                        {!! Form::text('contact_no', $branch->contact_no, array('id' => 'contact_no', 'class' => 'form-control', 'placeholder' => 'e.g. 771234567 (NB: there is no leading zero)', 'required', 'onkeyup'=>'validateNumber()', 'maxlength'=>'10')) !!}
                                    </div>
                                    @if ($errors->has('contact_no'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('contact_no') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group has-feedback row {{ $errors->has('address') ? ' has-error ' : '' }}">
                                {!! Form::label('address', 'Address' , array('class' => 'col-md-3 control-label')); !!}
                                <div class="col-md-9">
                                    <div class="input-group">
                                        {!! Form::textarea('address', $branch->address, array('id' => 'address', 'class' => 'form-control', 'placeholder' => 'e.g. AstroCred, 72 East Street Lusaka', 'required')) !!}
                                    </div>
                                    @if ($errors->has('address'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('address') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
							<input type="hidden" name="bid" value="{{ $branch->id }}">

                            {!! Form::button('Update Branch', array('class' => 'btn btn-success margin-bottom-1 mb-1 float-right','type' => 'submit' )) !!}
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
    <script>
        function validateNumber(){
            var myLength=document.getElementById("contact_no").value.length;
            var myNumber=document.getElementById("contact_no").value;
            if(myLength >=10){
                document.getElementById("contact_no").value=myNumber.substring(0, myNumber.length - 1);
            }
        }
        </script>
@endsection