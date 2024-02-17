<?php
/**
 * Created by PhpStorm for eshagi
 * User: vinceg
 * Date: 17/2/2021
 * Time: 07:01
 */
?>
@extends('layouts.app')

@section('template_title')
    Edit Lead
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
                        <li class="breadcrumb-item active">Modify Sales Lead: {{$lead->id}}</li>
                    </ol>
                </div>

                <div class="col-md-4">
                    <div class="float-right d-none d-md-block">
                        <div>
                            <a class="btn btn-light btn-rounded" href="{{url('/leads')}}" type="button">
                                <i class="mdi mdi-keyboard-backspace mr-1"></i>Back to Leads
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
                            {!! Form::open(array('route' => ['leads.update',$lead->id], 'method' => 'PUT', 'role' => 'form', 'class' => 'needs-validation')) !!}

                            {!! csrf_field() !!}

                            <div class="form-group has-feedback row {{ $errors->has('locale') ? ' has-error ' : '' }}">
                                {!! Form::label('locale', 'Locale*', array('class' => 'col-md-3 control-label')); !!}
                                <div class="col-md-9">
                                    <select class="custom-select form-control dynamic" name="locale" id="locale" required>
                                        <option value="">Select Locale</option>
                                        @if ($localels)
                                            @foreach($localels as $locale)
                                                <option value="{{ $locale->id }}" {{ $lead->locale == $locale->id ? 'selected="selected"' : '' }}>{{ $locale->country }}</option>
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
                                        {!! Form::text('name', $lead->name, array('id' => 'name', 'class' => 'form-control', 'placeholder' => 'e.g. initial + surname like John Doe i.e. jdoe', 'required')) !!}
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
                                        {!! Form::text('first_name', $lead->first_name, array('id' => 'first_name', 'class' => 'form-control', 'placeholder' => 'e.g. John', 'required')) !!}
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
                                        {!! Form::text('last_name', $lead->last_name, array('id' => 'last_name', 'class' => 'form-control', 'placeholder' => 'e.g. Doe','required')) !!}
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
                                        {!! Form::email('email', $lead->email, array('id' => 'email', 'class' => 'form-control', 'placeholder' => 'e.g. jdoe@gmail.com','required')) !!}
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
                                        {!! Form::text('natid', $lead->natid, array('id' => 'natid', 'class' => 'form-control text-uppercase','onkeyup'=>"validateId()", 'pattern'=>"^[0-9]{2}-[0-9]{6,7}-[A-Z]-[0-9]{2}$|^[0-9]{6}\/[0-9]{2}\/[0-9]{1}$", 'title'=>"ID Format should be in the form of XX-XXXXXXX-X-XX", 'placeholder' => 'e.g. Enter user National ID as it appears on user ID Card like: 63-235148177-L-22', 'required')) !!}
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
                                        {!! Form::text('mobile', $lead->mobile, array('id' => 'mobile', 'class' => 'form-control', 'placeholder' => 'e.g. 0773 418 009', 'required')) !!}
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
                                        {!! Form::text('ecnumber', $lead->ecnumber, array('id' => 'ecnumber', 'class' => 'form-control', 'placeholder' => 'e.g. 123456A', 'required')) !!}
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
                                        {!! Form::text('address', $lead->address, array('id' => 'address', 'class' => 'form-control', 'placeholder' => 'e.g. 12 Home address Street, Home, Suburb' , 'required')) !!}
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
                                        {!! Form::textarea('notes', $lead->notes, array('id' => 'notes', 'class' => 'form-control', 'placeholder' => 'e.g. Any notes about this lead, if there is any')) !!}
                                    </div>
                                    @if ($errors->has('notes'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('notes') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            {!! Form::button('Update Sales Lead', array('class' => 'btn btn-success margin-bottom-1 mb-1 float-right','type' => 'submit' )) !!}
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
