<?php
/**
 *Created by PhpStorm for eshagi
 *User: Vincent Guyo
 *Date: 10/10/2020
 *Time: 6:25 PM
 */

?>
@extends('layouts.app')

@section('template_title')
    Edit Funder
@endsection

@section('template_linked_css')
    <link href="{{ asset('css/select2.min.css')}}" rel="stylesheet" />
@endsection

@section('content')
    <div class="page-title-box">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h4 class="page-title mb-1">Funders</h4>
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{url('/funders')}}">Funders</a></li>
                        <li class="breadcrumb-item active">Modify Funder: {{$funder->funder}}</li>
                    </ol>
                </div>

                <div class="col-md-4">
                    <div class="float-right d-none d-md-block">
                        <div>
                            <a class="btn btn-light btn-rounded" href="{{url('/funders')}}" type="button">
                                <i class="mdi mdi-keyboard-backspace mr-1"></i>Back to Funders
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
                            {!! Form::open(array('route' => ['funders.update',$funder->id], 'method' => 'PUT', 'role' => 'form', 'class' => 'needs-validation')) !!}

                            {!! csrf_field() !!}

                            <div class="form-group has-feedback row {{ $errors->has('locale_id') ? ' has-error ' : '' }}">
                                {!! Form::label('locale_id', 'Locale', array('class' => 'col-md-3 control-label')); !!}
                                <div class="col-md-9">
                                    <select class="custom-select form-control dynamic" name="locale_id" id="locale_id" required>
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

                            <div class="form-group has-feedback row {{ $errors->has('funder') ? ' has-error ' : '' }}">
                                {!! Form::label('funder', 'Funder', array('class' => 'col-md-3 control-label')); !!}
                                <div class="col-md-9">
                                    <div class="input-group">
                                        {!! Form::text('funder', $funder->funder, array('id' => 'funder', 'class' => 'form-control', 'placeholder' => 'e.g. Redshpere Financial Services', 'required')) !!}
                                    </div>
                                    @if ($errors->has('funder'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('funder') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group has-feedback row {{ $errors->has('funder_acc_num') ? ' has-error ' : '' }}">
                                {!! Form::label('funder_acc_num', 'Funder Account Number', array('class' => 'col-md-3 control-label')); !!}
                                <div class="col-md-9">
                                    <div class="input-group">
                                        {!! Form::text('funder_acc_num', $funder->funder_acc_num, array('id' => 'funder_acc_num', 'class' => 'form-control', 'placeholder' => 'e.g. 1233354446777', 'required')) !!}
                                    </div>
                                    @if ($errors->has('funder_acc_num'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('funder_acc_num') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group has-feedback row {{ $errors->has('contact_fname') ? ' has-error ' : '' }}">
                                {!! Form::label('contact_fname', 'Funder Contact\'s Firstname' , array('class' => 'col-md-3 control-label')); !!}
                                <div class="col-md-9">
                                    <div class="input-group">
                                        {!! Form::text('contact_fname', $funder->contact_fname, array('id' => 'contact_fname', 'class' => 'form-control', 'placeholder' => 'e.g. John', 'required')) !!}
                                    </div>
                                    @if ($errors->has('contact_fname'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('contact_fname') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group has-feedback row {{ $errors->has('contact_lname') ? ' has-error ' : '' }}">
                                {!! Form::label('contact_lname', 'Funder Contact\'s Surname' , array('class' => 'col-md-3 control-label')); !!}
                                <div class="col-md-9">
                                    <div class="input-group">
                                        {!! Form::text('contact_lname', $funder->contact_lname, array('id' => 'contact_lname', 'class' => 'form-control', 'placeholder' => 'e.g. Doe', 'required')) !!}
                                    </div>
                                    @if ($errors->has('contact_lname'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('contact_lname') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group has-feedback row {{ $errors->has('email') ? ' has-error ' : '' }}">
                                {!! Form::label('email', 'Funder\'s Email' , array('class' => 'col-md-3 control-label')); !!}
                                <div class="col-md-9">
                                    <div class="input-group">
                                        {!! Form::email('email', $funder->email, array('id' => 'email', 'class' => 'form-control', 'placeholder' => 'e.g. johndoe@gmail.com', 'required')) !!}
                                    </div>
                                    @if ($errors->has('email'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('email') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group has-feedback row {{ $errors->has('tel_no') ? ' has-error ' : '' }}">
                                {!! Form::label('tel_no', 'Funder Telephone' , array('class' => 'col-md-3 control-label')); !!}
                                <div class="col-md-9">
                                    <div class="input-group">
                                        {!! Form::text('tel_no', $funder->tel_no, array('id' => 'tel_no', 'class' => 'form-control', 'placeholder' => 'e.g. 0242699425', 'required')) !!}
                                    </div>
                                    @if ($errors->has('tel_no'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('tel_no') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group has-feedback row {{ $errors->has('support_email') ? ' has-error ' : '' }}">
                                {!! Form::label('support_email', 'Support Email' , array('class' => 'col-md-3 control-label')); !!}
                                <div class="col-md-9">
                                    <div class="input-group">
                                        {!! Form::email('support_email', $funder->support_email, array('id' => 'support_email', 'class' => 'form-control', 'placeholder' => 'e.g. eshagi@cbz.co.zw', 'required')) !!}
                                    </div>
                                    @if ($errors->has('support_email'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('support_email') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            {!! Form::button('Update Funder Info', array('class' => 'btn btn-success margin-bottom-1 mb-1 float-right','type' => 'submit' )) !!}
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
