<?php
/**
 * Created by PhpStorm for eshagi
 * User: vinceg
 * Date: 16/2/2021
 * Time: 21:32
 */
?>
@extends('layouts.app')

@section('template_title')
    Add Lead
@endsection

@section('template_linked_css')
    <link href="{{ asset('css/select2.min.css')}}" rel="stylesheet" />
@endsection

@section('content')
    <div class="page-title-box">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h4 class="page-title mb-1">Leads</h4>
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{url('/leads')}}">Leads</a></li>
                        <li class="breadcrumb-item active">Sales Leads</li>
                    </ol>
                </div>

                <div class="col-md-4">
                    <div class="float-right d-none d-md-block">
                        <div>
                            <a class="btn btn-light btn-rounded" href="{{url()->previous()}}" type="button">
                                <i class="mdi mdi-keyboard-backspace mr-1"></i>Back
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
                            {!! Form::open(array('route' => 'leads.store', 'method' => 'POST', 'role' => 'form', 'class' => 'needs-validation')) !!}

                            {!! csrf_field() !!}

                            <div class="form-group has-feedback row {{ $errors->has('locale') ? ' has-error ' : '' }}">
                                {!! Form::label('locale', 'Locale*', array('class' => 'col-md-3 control-label')); !!}
                                <div class="col-md-9">
                                    <select class="custom-select form-control dynamic" name="locale" id="locale" required>
                                        <option value="">Select Locale</option>
                                        @if ($localels)
                                            @foreach($localels as $locale)
                                                <option value="{{ $locale->id }}" >{{ $locale->country }} </option>
                                            @endforeach
                                        @endif
                                    </select>
                                    @if ($errors->has('locale'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('locale') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group has-feedback row {{ $errors->has('name') ? ' has-error ' : '' }}">
                                {!! Form::label('name', 'Username*', array('class' => 'col-md-3 control-label')); !!}
                                <div class="col-md-9">
                                    <div class="input-group">
                                        {!! Form::text('name', NULL, array('id' => 'name', 'class' => 'form-control', 'placeholder' => 'e.g. initial + surname like John Doe i.e. jdoe', 'required')) !!}
                                    </div>
                                    @if ($errors->has('name'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('name') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group has-feedback row {{ $errors->has('first_name') ? ' has-error ' : '' }}">
                                {!! Form::label('first_name',' First Name*', array('class' => 'col-md-3 control-label')); !!}
                                <div class="col-md-9">
                                    <div class="input-group">
                                        {!! Form::text('first_name', NULL, array('id' => 'first_name', 'class' => 'form-control', 'placeholder' => 'e.g. John', 'required')) !!}
                                    </div>
                                    @if ($errors->has('first_name'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('first_name') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group has-feedback row {{ $errors->has('last_name') ? ' has-error ' : '' }}">
                                {!! Form::label('last_name', 'Last Name*', array('class' => 'col-md-3 control-label')); !!}
                                <div class="col-md-9">
                                    <div class="input-group">
                                        {!! Form::text('last_name', NULL, array('id' => 'last_name', 'class' => 'form-control', 'placeholder' => 'e.g. Doe','required')) !!}
                                    </div>
                                    @if ($errors->has('last_name'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('last_name') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group has-feedback row {{ $errors->has('email') ? ' has-error ' : '' }}">
                                {!! Form::label('email', 'Email*', array('class' => 'col-md-3 control-label')); !!}
                                <div class="col-md-9">
                                    <div class="input-group">
                                        {!! Form::email('email', NULL, array('id' => 'email', 'class' => 'form-control', 'placeholder' => 'e.g. jdoe@gmail.com','required')) !!}
                                    </div>
                                    @if ($errors->has('email'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('email') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group has-feedback row {{ $errors->has('natid') ? ' has-error ' : '' }}">
                                {!! Form::label('natid', 'National ID*', array('class' => 'col-md-3 control-label')); !!}
                                <div class="col-md-9">
                                    <div class="input-group">
                                        {!! Form::text('natid', NULL, array('id' => 'natid', 'class' => 'form-control text-uppercase','onkeyup'=>"validateId()", 'pattern'=>"^[0-9]{2}-[0-9]{6,7}-[A-Z]-[0-9]{2}$|^[0-9]{6}\/[0-9]{2}\/[0-9]{1}$", 'title'=>"ID Format should be in the form of XX-XXXXXXX-X-XX", 'placeholder' => 'e.g. Enter user National ID as it appears on user ID Card like: 63-235148177-L-22', 'required')) !!}
                                    </div>
                                    @if ($errors->has('natid'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('natid') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group has-feedback row {{ $errors->has('mobile') ? ' has-error ' : '' }}">
                                {!! Form::label('mobile', 'Mobile Number*', array('class' => 'col-md-3 control-label')); !!}
                                <div class="col-md-9">
                                    <div class="input-group">
                                        {!! Form::text('mobile', NULL, array('id' => 'mobile', 'class' => 'form-control', 'placeholder' => 'e.g. 0773 418 009', 'required')) !!}
                                    </div>
                                    @if ($errors->has('mobile'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('mobile') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group has-feedback row {{ $errors->has('ecnumber') ? ' has-error ' : '' }}">
                                {!! Form::label('ecnumber', 'EC Number*', array('class' => 'col-md-3 control-label')); !!}
                                <div class="col-md-9">
                                    <div class="input-group">
                                        {!! Form::text('ecnumber', NULL, array('id' => 'ecnumber', 'class' => 'form-control', 'placeholder' => 'e.g. 123456A', 'required')) !!}
                                    </div>
                                    @if ($errors->has('ecnumber'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('ecnumber') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group has-feedback row {{ $errors->has('address') ? ' has-error ' : '' }}">
                                {!! Form::label('address', 'Physical address*', array('class' => 'col-md-3 control-label')); !!}
                                <div class="col-md-9">
                                    <div class="input-group">
                                        {!! Form::text('address', NULL, array('id' => 'address', 'class' => 'form-control', 'placeholder' => 'e.g. 12 Home address Street, Home, Suburb' , 'required')) !!}
                                    </div>
                                    @if ($errors->has('address'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('address') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group has-feedback row {{ $errors->has('notes') ? ' has-error ' : '' }}">
                                {!! Form::label('notes', 'Notes', array('class' => 'col-md-3 control-label')); !!}
                                <div class="col-md-9">
                                    <div class="input-group">
                                        {!! Form::textarea('notes', NULL, array('id' => 'notes', 'class' => 'form-control', 'placeholder' => 'e.g. Any notes about this lead, if there is any')) !!}
                                    </div>
                                    @if ($errors->has('notes'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('notes') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                                <span class="help-block">
                                    <strong class="text-info">Default password will be set to: pass12345</strong>
                                </span>


                            {!! Form::button('Add Sales Lead', array('class' => 'btn btn-success margin-bottom-1 mb-1 float-right','type' => 'submit' )) !!}
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
        $("#locale").select2({
            placeholder: 'Please select locale.',
            allowClear:true,
        });
    </script>
@endsection
