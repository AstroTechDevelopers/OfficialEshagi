<?php
/**
 * Created by PhpStorm for eshagi
 * User: vinceg
 * Date: 18/2/2021
 * Time: 12:34
 */
?>
@extends('layouts.app')

@section('template_title')
    View Call
@endsection

@section('template_linked_css')
    <link href="{{asset('assets/libs/air-datepicker/css/datepicker.min.css')}}" rel="stylesheet" type="text/css" />
@endsection

@section('content')
    <div class="page-title-box">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h4 class="page-title mb-1">Call {{$call->id}}</h4>
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{url('/calls')}}">Calls</a></li>
                        <li class="breadcrumb-item active">{{$lead->natid}}</li>
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
                <div class="col-xl-8">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="header-title">Lead Details</h4>
                            <p class="card-title-desc">Sales lead details. <span class="text-danger">Please make sure you verify the client details, during or after the call.</span></p>

                            <!-- Nav tabs -->
                            <ul class="nav nav-pills" role="tablist">
                                <li class="nav-item waves-effect waves-light">
                                    <a class="nav-link " data-toggle="tab" href="#personal" role="tab">
                                        <i class="fas fa-home mr-1"></i> <span class="d-none d-md-inline-block">Personal</span>
                                    </a>
                                </li>
                                <li class="nav-item waves-effect waves-light">
                                    <a class="nav-link" data-toggle="tab" href="#lead" role="tab">
                                        <i class="fas fa-user mr-1"></i> <span class="d-none d-md-inline-block">Lead Details</span>
                                    </a>
                                </li>
                                <li class="nav-item waves-effect waves-light">
                                    <a class="nav-link active" data-toggle="tab" href="#call" role="tab">
                                        <i class="fas fa-phone-volume mr-1"></i> <span class="d-none d-md-inline-block">Call Details</span>
                                    </a>
                                </li>
                            </ul>

                            <!-- Tab panes -->
                            <div class="tab-content p-3">
                                <div class="tab-pane" id="personal" role="tabpanel">
                                    <div class="form-group has-feedback row {{ $errors->has('name') ? ' has-error ' : '' }}">
                                        {!! Form::label('name', 'Username [initial + surname]', array('class' => 'col-md-3 control-label')); !!}
                                        <div class="col-md-9">
                                            <div class="input-group">
                                                {!! Form::text('name', $lead->name, array('id' => 'name', 'class' => 'form-control', 'placeholder' => 'e.g. jdoe' , 'required')) !!}
                                            </div>
                                            @if ($errors->has('name'))
                                                <span class="help-block">
                                            <strong>{{ $errors->first('name') }}</strong>
                                        </span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="form-group has-feedback row {{ $errors->has('email') ? ' has-error ' : '' }}">
                                        {!! Form::label('email', 'Email', array('class' => 'col-md-3 control-label')); !!}
                                        <div class="col-md-9">
                                            <div class="input-group">
                                                {!! Form::email('email', $lead->email, array('id' => 'email', 'class' => 'form-control', 'placeholder' => 'e.g. jdoe@gmail.com' , 'required')) !!}
                                            </div>
                                            @if ($errors->has('email'))
                                                <span class="help-block">
                                            <strong>{{ $errors->first('email') }}</strong>
                                        </span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="form-group has-feedback row {{ $errors->has('first_name') ? ' has-error ' : '' }}">
                                        {!! Form::label('first_name', 'First Name', array('class' => 'col-md-3 control-label')); !!}
                                        <div class="col-md-9">
                                            <div class="input-group">
                                                {!! Form::text('first_name', $lead->first_name, array('id' => 'first_name', 'class' => 'form-control', 'placeholder' => 'e.g. John' , 'required')) !!}
                                            </div>
                                            @if ($errors->has('first_name'))
                                                <span class="help-block">
                                            <strong>{{ $errors->first('first_name') }}</strong>
                                        </span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="form-group has-feedback row {{ $errors->has('last_name') ? ' has-error ' : '' }}">
                                        {!! Form::label('last_name', 'Last Name', array('class' => 'col-md-3 control-label')); !!}
                                        <div class="col-md-9">
                                            <div class="input-group">
                                                {!! Form::text('last_name', $lead->last_name, array('id' => 'last_name', 'class' => 'form-control', 'placeholder' => 'e.g. Doe' , 'required')) !!}
                                            </div>
                                            @if ($errors->has('last_name'))
                                                <span class="help-block">
                                            <strong>{{ $errors->first('last_name') }}</strong>
                                        </span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="form-group has-feedback row {{ $errors->has('natid') ? ' has-error ' : '' }}">
                                        {!! Form::label('natid', 'National ID', array('class' => 'col-md-3 control-label')); !!}
                                        <div class="col-md-9">
                                            <div class="input-group">
                                                {!! Form::text('natid', $lead->natid, array('id' => 'natid', 'class' => 'form-control', 'placeholder' => 'e.g. 12-345678-A-90' , 'required')) !!}
                                            </div>
                                            @if ($errors->has('natid'))
                                                <span class="help-block">
                                            <strong>{{ $errors->first('natid') }}</strong>
                                        </span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="form-group has-feedback row {{ $errors->has('mobile') ? ' has-error ' : '' }}">
                                        {!! Form::label('mobile', 'Mobile', array('class' => 'col-md-3 control-label')); !!}
                                        <div class="col-md-9">
                                            <div class="input-group">
                                                {!! Form::text('mobile', $lead->mobile, array('id' => 'mobile', 'class' => 'form-control', 'placeholder' => 'e.g. 0773 418 009' , 'required')) !!}
                                            </div>
                                            @if ($errors->has('mobile'))
                                                <span class="help-block">
                                            <strong>{{ $errors->first('mobile') }}</strong>
                                        </span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="form-group has-feedback row {{ $errors->has('ecnumber') ? ' has-error ' : '' }}">
                                        {!! Form::label('ecnumber', 'EC Number', array('class' => 'col-md-3 control-label')); !!}
                                        <div class="col-md-9">
                                            <div class="input-group">
                                                {!! Form::text('ecnumber', $lead->ecnumber, array('id' => 'ecnumber', 'class' => 'form-control', 'placeholder' => 'e.g. 123456A' , 'required')) !!}
                                            </div>
                                            @if ($errors->has('ecnumber'))
                                                <span class="help-block">
                                            <strong>{{ $errors->first('ecnumber') }}</strong>
                                        </span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="form-group has-feedback row {{ $errors->has('address') ? ' has-error ' : '' }}">
                                        {!! Form::label('address', 'Physical Address', array('class' => 'col-md-3 control-label')); !!}
                                        <div class="col-md-9">
                                            <div class="input-group">
                                                {!! Form::text('address', $lead->address, array('id' => 'address', 'class' => 'form-control', 'placeholder' => 'e.g. 123 Home Street, Suburb, Area' , 'required')) !!}
                                            </div>
                                            @if ($errors->has('address'))
                                                <span class="help-block">
                                            <strong>{{ $errors->first('address') }}</strong>
                                        </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane" id="lead" role="tabpanel">
                                    <table class="table table-striped mb-0">
                                        <tr>
                                            <td>Was this Lead Contacted?</td>
                                            <td><strong>@if($lead->isContacted) Yes @else No @endif</strong></td>
                                            <td>Was this Lead Converted to a Loan?</td>
                                            <td><strong>@if($lead->isSale) Yes @else No @endif</strong></td>
                                        </tr>
                                        <tr>
                                            <td>Was this Lead Contacted via SMS?</td>
                                            <td><strong>@if($lead->isSMSed) Yes @else No @endif</strong></td>

                                        </tr>
                                        <tr>
                                            <td>Agent</td>
                                            <td><strong>{{$lead->agent}}</strong></td>
                                            <td>Assigned to Agent on</td>
                                            <td><strong>{{$lead->assignedOn}}</strong></td>
                                        </tr>
                                        <tr>
                                            <td>When was the Lead Converted?</td>
                                            <td><strong>{{$lead->completedOn ?? 'Not yet converted to a loan'}}</strong></td>
                                            <td>Lead Created On</td>
                                            <td><strong>{{$lead->created_at}}</strong></td>
                                        </tr>
                                    </table>
                                    <hr>
                                    <div class="form-group has-feedback row {{ $errors->has('notes') ? ' has-error ' : '' }}">
                                        {!! Form::label('notes', 'Notes', array('class' => 'col-md-3 control-label')); !!}
                                        <div class="col-md-9">
                                            <div class="input-group">
                                                {!! Form::textarea('notes', $lead->notes, array('id' => 'notes', 'class' => 'form-control', 'placeholder' => 'e.g. Any details or notes to be placed here.' , 'required')) !!}
                                            </div>
                                            @if ($errors->has('notes'))
                                                <span class="help-block">
                                            <strong>{{ $errors->first('notes') }}</strong>
                                        </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane active" id="call" role="tabpanel">
                                    <div class="form-group has-feedback row {{ $errors->has('notes') ? ' has-error ' : '' }}">
                                        {!! Form::label('notes', 'Notes', array('class' => 'col-md-3 control-label')); !!}
                                        <div class="col-md-9">
                                            <div class="input-group">
                                                {!! Form::textarea('notes', NULL, array('id' => 'notes', 'class' => 'form-control', 'placeholder' => 'e.g. Any details or notes to be placed here.' , 'required')) !!}
                                            </div>
                                            @if ($errors->has('notes'))
                                                <span class="help-block">
                                            <strong>{{ $errors->first('notes') }}</strong>
                                        </span>
                                            @endif
                                        </div>
                                    </div>
                                    <br>

                                    <input class="form-check-input" type="checkbox" name="setAppointment" id="setAppointment" @if ($call->setAppointment) checked @endif>
                                    <label class="form-check-label" for="setAppointment">
                                        Did the client want a follow up?
                                    </label>
                                    <input type="text" name="appointment" id="appointment" value="{{$call->appointment}}" class="form-control datepicker-here{{ $errors->has('appointment') ? ' is-invalid' : '' }}" placeholder="Select a appointment" data-timepicker="true" data-date-format='yyyy-mm-dd' data-time-format='hh:ii' data-language="en" autocomplete="off">

                                    <br>
                                    <input class="form-check-input" type="checkbox" name="isSale" id="isSale" @if ($call->isSale) checked @endif>
                                    <label class="form-check-label" for="isSale">
                                        Did Client Accept Loan?
                                    </label>
                                    <br><br>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="col-xl-4">
                    <div class="card">
                        <div class="card-body">

                            <h4 class="header-title">Call History</h4>
                            <p class="card-title-desc">Summary of calls made regarding this lead</p>

                            @if($calls->count()>0)
                                <div id="accordion">
                                    @foreach($calls as $call)
                                        <div class="card mb-0">
                                            <div class="card-header" id="heading{{$loop->index}}">
                                                <h5 class="m-0 font-size-14">
                                                    <a data-toggle="collapse" data-parent="#accordion"
                                                       href="#collapse{{$call->id}}" aria-expanded="true"
                                                       aria-controls="collapse{{$loop->index}}" class="text-dark">
                                                        Call #{{$loop->index+1}} to {{$call->mobile}}
                                                    </a>
                                                </h5>
                                            </div>

                                            <div id="collapse{{$call->id}}" class="collapse"
                                                 aria-labelledby="heading{{$call->id}}" data-parent="#accordion">
                                                <div class="card-body">
                                                    {!! $call->notes !!} <br>
                                                    @if($call->setAppointment)
                                                        Appointment set for {{$call->appointment}}
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p>Lead has not yet been called.</p>
                            @endif

                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection

@section('footer_scripts')
    @include('scripts.delete-modal-script')
    @if(config('usersmanagement.tooltipsEnabled'))
        @include('scripts.tooltips')
    @endif

    <script src="{{asset('assets/libs/air-datepicker/js/datepicker.min.js')}}"></script>
    <script src="{{asset('assets/libs/air-datepicker/js/i18n/datepicker.en.js')}}"></script>

    <script>
        $("#appointment").datepicker({
            language: 'en',
        }).data('datepicker').selectDate(new Date($("#appointment").val()));

    </script>
@endsection
