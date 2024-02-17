<?php
/**
 * Created by PhpStorm for eshagitwo
 * User: vinceg
 * Date: 22/8/2021
 * Time: 10:51
 */
?>
@extends('layouts.app')

@section('template_title')
    Add Employer
@endsection

@section('template_linked_css')
    <link href="{{ asset('css/select2.min.css')}}" rel="stylesheet" />
@endsection

@section('content')
    <div class="page-title-box">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h4 class="page-title mb-1">Employers</h4>
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{url('/employers')}}">Employers</a></li>
                        <li class="breadcrumb-item active">Add a Employer</li>
                    </ol>
                </div>

                <div class="col-md-4">
                    <div class="float-right d-none d-md-block">
                        <div>
                            <a class="btn btn-light btn-rounded" href="{{url('/employers')}}" type="button">
                                <i class="mdi mdi-keyboard-backspace mr-1"></i>Back to Employers
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
                            {!! Form::open(array('route' => 'employers.store', 'method' => 'POST', 'role' => 'form', 'class' => 'needs-validation')) !!}

                            {!! csrf_field() !!}

                            <div class="form-group has-feedback row {{ $errors->has('locale_id') ? ' has-error ' : '' }}">
                                {!! Form::label('locale_id', 'Locale', array('class' => 'col-md-3 control-label')); !!}
                                <div class="col-md-9">
                                    <select class="custom-select form-control dynamic" name="locale_id" id="locale_id" required>
                                        <option value="">Select Locale</option>
                                        @if ($localels)
                                            @foreach($localels as $locale)
                                                <option value="{{ $locale->id }}" >{{ $locale->country }} </option>
                                            @endforeach
                                        @endif
                                    </select>
                                    @if ($errors->has('locale_id'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('locale_id') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group has-feedback row {{ $errors->has('employer') ? ' has-error ' : '' }}">
                                {!! Form::label('employer', 'Employer', array('class' => 'col-md-3 control-label')); !!}
                                <div class="col-md-9">
                                    <div class="input-group">
                                        {!! Form::text('employer', NULL, array('id' => 'employer', 'class' => 'form-control', 'placeholder' => 'e.g. Government SSB', 'required')) !!}
                                    </div>
                                    @if ($errors->has('employer'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('employer') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group has-feedback row {{ $errors->has('location') ? ' has-error ' : '' }}">
                                {!! Form::label('location', 'Location', array('class' => 'col-md-3 control-label')); !!}
                                <div class="col-md-9">
                                    <div class="input-group">
                                        {!! Form::text('location', NULL, array('id' => 'location', 'class' => 'form-control', 'placeholder' => 'e.g. Harare', 'required')) !!}
                                    </div>
                                    @if ($errors->has('location'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('location') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group has-feedback row {{ $errors->has('subgroup') ? ' has-error ' : '' }}">
                                {!! Form::label('subgroup', 'Sub Group' , array('class' => 'col-md-3 control-label')); !!}
                                <div class="col-md-9">
                                    <div class="input-group">
                                        {!! Form::text('subgroup', NULL, array('id' => 'subgroup', 'class' => 'form-control', 'placeholder' => 'e.g. ZRP', 'required')) !!}
                                    </div>
                                    @if ($errors->has('subgroup'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('subgroup') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group has-feedback row {{ $errors->has('cutoffdate') ? ' has-error ' : '' }}">
                                {!! Form::label('cutoffdate', 'Cut Off Date' , array('class' => 'col-md-3 control-label')); !!}
                                <div class="col-md-9">
                                    <div class="input-group">
                                        {!! Form::number('cutoffdate', NULL, array('id' => 'cutoffdate','min' => '1','max' => '31', 'class' => 'form-control', 'placeholder' => 'e.g. 25', 'required')) !!}
                                    </div>
                                    @if ($errors->has('cutoffdate'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('cutoffdate') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            {!! Form::button('Add Employer', array('class' => 'btn btn-success margin-bottom-1 mb-1 float-right','type' => 'submit' )) !!}
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
        $("#locale_id").select2({
            placeholder: 'Please select locale.',
            allowClear:true,
        });
    </script>
@endsection
