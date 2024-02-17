<?php
/**
 * Created by PhpStorm for eshagi
 * User: vinceg
 * Date: 22/2/2021
 * Time: 21:09
 */
?>
@extends('layouts.app')

@section('template_title')
    Update Mailing List
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
                        <li class="breadcrumb-item active">Update {{$repmailinglist->report}} Mailing List</li>
                    </ol>
                </div>

                <div class="col-md-4">
                    <div class="float-right d-none d-md-block">

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
                            {!! Form::open(array('route' => ['mailings.update', $repmailinglist->id], 'method' => 'PUT', 'role' => 'form', 'class' => 'needs-validation')) !!}

                            {!! csrf_field() !!}

                            <div class="form-group has-feedback row {{ $errors->has('report') ? ' has-error ' : '' }}">
                                {!! Form::label('report', 'Report Name', array('class' => 'col-md-3 control-label')); !!}
                                <div class="col-md-9">
                                    <div class="input-group">
                                        {!! Form::text('report', $repmailinglist->report, array('id' => 'report', 'class' => 'form-control', 'placeholder' => 'e.g. Daily report', 'required')) !!}
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
                                        {!! Form::textarea('list', $repmailinglist->list, array('id' => 'list', 'class' => 'form-control', 'placeholder' => 'e.g. manager@astroafrica.tech, sales@eshagi.com', 'required')) !!}
                                    </div>
                                    @if ($errors->has('list'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('list') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group has-feedback row {{ $errors->has('active') ? ' has-error ' : '' }}">
                                {!! Form::label('active', 'Is List Active', array('class' => 'col-md-3 control-label')); !!}
                                <div class="col-md-9">
                                    <div class="input-group">
                                        <div class="col-sm-6">
                                            <div class="btn-group btn-group-toggle mt-2 mt-sm-0" data-toggle="buttons">
                                                <label class="btn btn-primary active">
                                                    <input type="radio" name="active" id="active" value="0" {{ ($repmailinglist->active=="0")? "checked" : "" }}> <i class="mdi mdi-toggle-switch-off"></i> Inactive
                                                </label>
                                                <label class="btn btn-primary">
                                                    <input type="radio" name="active" id="inactive" value="1" {{ ($repmailinglist->active=="1")? "checked" : "" }}> <i class="mdi mdi-toggle-switch"></i> Active
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    @if ($errors->has('active'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('active') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group has-feedback row {{ $errors->has('notes') ? ' has-error ' : '' }}">
                                {!! Form::label('notes', 'Notes', array('class' => 'col-md-3 control-label')); !!}
                                <div class="col-md-9">
                                    <div class="input-group">
                                        {!! Form::text('notes', $repmailinglist->notes, array('id' => 'notes', 'class' => 'form-control', 'placeholder' => 'e.g. Any additional notes')) !!}
                                    </div>
                                    @if ($errors->has('notes'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('notes') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            {!! Form::button('Update Mailing List', array('class' => 'btn btn-success margin-bottom-1 mb-1 float-right','type' => 'submit' )) !!}
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
