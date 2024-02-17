<?php
/**
 * Created by PhpStorm for eshagi
 * User: vinceg
 * Date: 22/2/2021
 * Time: 20:05
 */
?>
@extends('layouts.app')

@section('template_title')
    Add New Mailing List
@endsection

@section('template_linked_css')

@endsection

@section('content')
    <div class="page-title-box">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h4 class="page-title mb-1">Mailing Lists</h4>
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{url('/mailings')}}">Mailing Lists</a></li>
                        <li class="breadcrumb-item active">System Mailing Lists</li>
                    </ol>
                </div>

                <div class="col-md-4">
                    <div class="float-right d-none d-md-block">
                        <div>
                            <a class="btn btn-light btn-rounded" href="{{url('/mailings')}}" type="button">
                                <i class="mdi mdi-keyboard-backspace mr-1"></i>Back to Mailing Lists
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
                            {!! Form::open(array('route' => 'mailings.store', 'method' => 'POST', 'role' => 'form', 'class' => 'needs-validation')) !!}

                            {!! csrf_field() !!}

                            <div class="form-group has-feedback row {{ $errors->has('report') ? ' has-error ' : '' }}">
                                {!! Form::label('report', 'Report Name', array('class' => 'col-md-3 control-label')); !!}
                                <div class="col-md-9">
                                    <div class="input-group">
                                        {!! Form::text('report', NULL, array('id' => 'report', 'class' => 'form-control', 'placeholder' => 'e.g. Daily report', 'required')) !!}
                                    </div>
                                    @if ($errors->has('report'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('report') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group has-feedback row {{ $errors->has('list') ? ' has-error ' : '' }}">
                                {!! Form::label('list', 'Mailing List', array('class' => 'col-md-3 control-label')); !!}
                                <div class="col-md-9">
                                    <div class="input-group">
                                        {!! Form::textarea('list', NULL, array('id' => 'list', 'class' => 'form-control', 'placeholder' => 'e.g. manager@astroafrica.tech,sales@eshagi.com', 'required')) !!}
                                    </div>
                                        <span class="text-success">Enter the mailing list for this report separated by commas and no spaces.</span>
                                    @if ($errors->has('list'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('list') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group has-feedback row {{ $errors->has('notes') ? ' has-error ' : '' }}">
                                {!! Form::label('notes', 'Notes', array('class' => 'col-md-3 control-label')); !!}
                                <div class="col-md-9">
                                    <div class="input-group">
                                        {!! Form::text('notes', NULL, array('id' => 'notes', 'class' => 'form-control', 'placeholder' => 'e.g. Any additional notes')) !!}
                                    </div>
                                    @if ($errors->has('notes'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('notes') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            {!! Form::button('Add Mailing List', array('class' => 'btn btn-success margin-bottom-1 mb-1 float-right','type' => 'submit' )) !!}
                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('footer_scripts')

@endsection
