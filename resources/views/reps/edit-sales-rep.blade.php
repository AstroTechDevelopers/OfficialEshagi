<?php
/**
 *Created by PhpStorm for eshagi
 *User: Vincent Guyo
 *Date: 11/8/2020
 *Time: 11:46 AM
 */

?>
@extends('layouts.app')

@section('template_title')
    Edit Sales Rep
@endsection

@section('template_linked_css')
    <link href="{{ asset('css/select2.min.css')}}" rel="stylesheet" />
@endsection

@section('content')
    <div class="page-title-box">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h4 class="page-title mb-1">Representatives</h4>
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{url('/representatives')}}">Representatives</a></li>
                        <li class="breadcrumb-item active">Edit Sales Rep</li>
                    </ol>
                </div>

                <div class="col-md-4">
                    <div class="float-right d-none d-md-block">
                        <div>
                            <a class="btn btn-light btn-rounded" href="{{url('/representatives')}}" type="button">
                                <i class="mdi mdi-keyboard-backspace mr-1"></i>Back to Representatives
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
                            {!! Form::open(array('route' => ['representatives.update', $representative->id], 'method' => 'PUT', 'role' => 'form', 'class' => 'needs-validation')) !!}

                            {!! csrf_field() !!}

                            <div class="form-group has-feedback row {{ $errors->has('partner_id') ? ' has-error ' : '' }}">
                                {!! Form::label('partner_id', 'Partner', array('class' => 'col-md-3 control-label')); !!}
                                <div class="col-md-9">
                                    <select class="custom-select form-control dynamic" name="partner_id" id="partner_id" required>
                                        @if ($partners)
                                            @foreach($partners as $partner)
                                                <option value="{{ $partner->id }}" {{ $currentPartner->id == $partner->id ? 'selected="selected"' : '' }}>{{ $partner->partner_name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                    @if ($errors->has('partner_id'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('partner_id') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group has-feedback row {{ $errors->has('first_name') ? ' has-error ' : '' }}">
                                {!! Form::label('first_name', 'First name', array('class' => 'col-md-3 control-label')); !!}
                                <div class="col-md-9">
                                    <div class="input-group">
                                        {!! Form::text('first_name', $representative->first_name, array('id' => 'first_name', 'class' => 'form-control', 'placeholder' => 'e.g. Arthur', 'required')) !!}
                                    </div>
                                    @if ($errors->has('first_name'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('first_name') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group has-feedback row {{ $errors->has('last_name') ? ' has-error ' : '' }}">
                                {!! Form::label('last_name', 'Last name', array('class' => 'col-md-3 control-label')); !!}
                                <div class="col-md-9">
                                    <div class="input-group">
                                        {!! Form::text('last_name', $representative->last_name, array('id' => 'last_name', 'class' => 'form-control', 'placeholder' => 'e.g. Doe', 'required')) !!}
                                    </div>
                                    @if ($errors->has('last_name'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('last_name') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group has-feedback row {{ $errors->has('email') ? ' has-error ' : '' }}">
                                {!! Form::label('email', 'Email Address' , array('class' => 'col-md-3 control-label')); !!}
                                <div class="col-md-9">
                                    <div class="input-group">
                                        {!! Form::email('email', $representative->email, array('id' => 'email', 'class' => 'form-control', 'placeholder' => 'e.g. adoe@gmail.com', 'required')) !!}
                                    </div>
                                    @if ($errors->has('email'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('email') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group has-feedback row {{ $errors->has('natid') ? ' has-error ' : '' }}">
                                {!! Form::label('natid', 'National ID ' , array('class' => 'col-md-3 control-label')); !!}
                                <div class="col-md-9">
                                    <div class="input-group">
                                        {!! Form::text('natid', $representative->natid, array('id' => 'natid', 'class' => 'form-control', 'placeholder' => 'e.g. 12-2345678-H-90','autocapitalize'=>'characters','maxlength'=>'15','onkeyup'=>'validateId()','pattern'=>'^[0-9]{2}-[0-9]{6,7}-[A-Z]-[0-9]{2}$','title'=>'ID Format should be in the form of xx-xxxxxxx-X-xx', 'required')) !!}
                                    </div>
                                    @if ($errors->has('natid'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('natid') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group has-feedback row {{ $errors->has('mobile') ? ' has-error ' : '' }}">
                                {!! Form::label('mobile', 'Mobile' , array('class' => 'col-md-3 control-label')); !!}
                                <div class="col-md-9">
                                    <div class="input-group">
                                        {!! Form::text('mobile', $representative->mobile, array('id' => 'mobile', 'class' => 'form-control', 'placeholder' => 'e.g. 0771234567', 'required')) !!}
                                    </div>
                                    @if ($errors->has('mobile'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('mobile') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group has-feedback row {{ $errors->has('branch') ? ' has-error ' : '' }}">
                                {!! Form::label('branch', 'Branch/Location' , array('class' => 'col-md-3 control-label')); !!}
                                <div class="col-md-9">
                                    <div class="input-group">
                                        <select class="custom-select form-control dynamic" name="branch" id="branch" required>
                                        <option value="">Select Branch</option>
                                        @if ($branches)
                                            @foreach($branches as $branch)
                                                <option value="{{ $branch->id }}" {{ $representative->branch == $branch->id ? 'selected="selected"' : '' }}>{{ $branch->name }}</option>
                                            @endforeach
                                        @endif
										</select>
                                    </div>
                                    @if ($errors->has('branch'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('branch') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group has-feedback row {{ $errors->has('password') ? ' has-error ' : '' }}">
                                {!! Form::label('password', trans('forms.create_user_label_password'), array('class' => 'col-md-3 control-label')); !!}
                                <div class="col-md-9">
                                    <div class="input-group">
                                        {!! Form::password('password', array('id' => 'password','autocomplete'=>'off', 'class' => 'form-control ', 'placeholder' => trans('forms.create_user_ph_password'))) !!}
                                    </div>
                                    <span class="help-block text-muted text-info">
                                            Password has to be at least 6 characters to be accepted.
                                        </span>
                                    @if ($errors->has('password'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('password') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group has-feedback row {{ $errors->has('password_confirmation') ? ' has-error ' : '' }}">
                                {!! Form::label('password_confirmation', trans('forms.create_user_label_pw_confirmation'), array('class' => 'col-md-3 control-label')); !!}
                                <div class="col-md-9">
                                    <div class="input-group">
                                        {!! Form::password('password_confirmation', array('id' => 'password_confirmation', 'class' => 'form-control', 'placeholder' => trans('forms.create_user_ph_pw_confirmation'))) !!}
                                    </div>
                                    @if ($errors->has('password_confirmation'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('password_confirmation') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            {!! Form::button('Update Sales Rep', array('class' => 'btn btn-success margin-bottom-1 mb-1 float-right','type' => 'submit' )) !!}
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

    <script>
        function validateId(){
            var myId=document.getElementById("natid").value;
            myId=myId.replace(/ /gi, "");
            myId=myId.replace(/-/gi, "");

            myId=insert(myId, "-", 2);
            myId=insert(myId, "-", myId.length-3);
            myId=insert(myId, "-", myId.length-2);

            document.getElementById("natid").value=myId;
        }

        function insert(main_string, ins_string, pos) {
            if(typeof(pos) == "undefined") {
                pos = 0;
            }
            if(typeof(ins_string) == "undefined") {
                ins_string = '';
            }
            return main_string.slice(0, pos) + ins_string + main_string.slice(pos);
        }
    </script
@endsection
