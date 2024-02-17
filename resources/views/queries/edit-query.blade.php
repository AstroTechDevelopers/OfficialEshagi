<?php
/**
 * Created by PhpStorm for eshagitwo
 * User: vinceg
 * Date: 3/8/2021
 * Time: 05:40
 */
?>
@extends('layouts.app')

@section('template_title')
    Edit Query: {{$query->id}}
@endsection

@section('template_linked_css')
    <link href="{{ asset('css/select2.min.css')}}" rel="stylesheet" />
@endsection

@section('content')
    <div class="page-title-box">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h4 class="page-title mb-1">Edit Query</h4>
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{url('/my-queries')}}">Query</a></li>
                        <li class="breadcrumb-item active">Edit Query: {{$query->id}}</li>
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
                            <h4 class="header-title">Query Details</h4>

                            {!! Form::open(array('route' => ['queries.update',$query->id], 'method' => 'PUT', 'role' => 'form', 'class' => 'needs-validation')) !!}

                            {!! csrf_field() !!}
                            <div class="form-group has-feedback row {{ $errors->has('medium') ? ' has-error ' : '' }}">
                                {!! Form::label('medium', 'Medium(How did the query come in)*', array('class' => 'col-md-12 control-label')); !!}
                                <div class="col-md-12">
                                    <select class="custom-select form-control dynamic" name="medium" id="medium" required>
                                        <option value="">Select Medium</option>
                                        <option value="WhatsApp" {{ $query->medium == 'WhatsApp'  ? 'selected="selected"' : '' }}>WhatsApp</option>
                                        <option value="Email" {{ $query->medium == 'Email'  ? 'selected="selected"' : '' }}>Email</option>
                                        <option value="Phone" {{ $query->medium == 'Phone'  ? 'selected="selected"' : '' }}>Phone</option>
                                        <option value="SMS" {{ $query->medium == 'SMS'  ? 'selected="selected"' : '' }}>SMS</option>
                                        <option value="Walk-in" {{ $query->medium == 'Walk-in'  ? 'selected="selected"' : '' }}>Walk-in</option>
                                        <option value="Other" {{ $query->medium == 'Other'  ? 'selected="selected"' : '' }}>Other</option>
                                    </select>
                                    @if ($errors->has('medium'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('medium') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group has-feedback row {{ $errors->has('natid') ? ' has-error ' : '' }}">
                                {!! Form::label('natid', 'National ID*', array('class' => 'col-md-3 control-label')); !!}
                                <div class="col-md-9">
                                    <div class="input-group">
                                        <select class="form-control" id="natid" name="natid">
                                            <option value="">Please select a client</option>
                                            @foreach($clients as $client)
                                                <option value="{{ $client->natid }}" {{ $query->natid == $client->natid  ? 'selected="selected"' : '' }} data-fname="{{$client->first_name}}" data-lname="{{$client->last_name}}" data-mobile="{{$client->mobile}}">{{  $client['natid'].' - ' . $client['first_name'].' '.$client['last_name'] }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @if ($errors->has('natid'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('natid') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group has-feedback row {{ $errors->has('first_name') ? ' has-error ' : '' }}">
                                {!! Form::label('first_name',' First Name*', array('class' => 'col-md-3 control-label')); !!}
                                <div class="col-md-9">
                                    <div class="input-group">
                                        {!! Form::text('first_name', $query->first_name, array('id' => 'first_name', 'class' => 'form-control', 'placeholder' => 'e.g. John', 'required')) !!}
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
                                        {!! Form::text('last_name', $query->last_name, array('id' => 'last_name', 'class' => 'form-control', 'placeholder' => 'e.g. Doe','required')) !!}
                                    </div>
                                    @if ($errors->has('last_name'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('last_name') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group has-feedback row {{ $errors->has('mobile') ? ' has-error ' : '' }}">
                                {!! Form::label('mobile', 'Mobile Number*', array('class' => 'col-md-3 control-label')); !!}
                                <div class="col-md-9">
                                    <div class="input-group">
                                        {!! Form::text('mobile', $query->mobile, array('id' => 'mobile', 'class' => 'form-control', 'placeholder' => 'e.g. 0773 418 009', 'required')) !!}
                                    </div>
                                    @if ($errors->has('mobile'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('mobile') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group has-feedback row {{ $errors->has('query') ? ' has-error ' : '' }}">
                                {!! Form::label('query', 'Query Details', array('class' => 'col-md-3 control-label')); !!}
                                <div class="col-md-9">
                                    <div class="input-group">
                                        {!! Form::textarea('query', $query->query, array('id' => 'query', 'class' => 'form-control', 'placeholder' => 'e.g. Query details go here.' , 'required')) !!}
                                    </div>
                                    @if ($errors->has('query'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('query') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group has-feedback row {{ $errors->has('status') ? ' has-error ' : '' }}">
                                {!! Form::label('status', 'Ticket Status', array('class' => 'col-md-3 control-label')); !!}
                                <div class="col-md-9">
                                    <div class="input-group">
                                        <select class="form-control select2" name="status" id="status" required>
                                            <option value="">Select Status</option>
                                            <option value="New" {{ $query->status == 'New'  ? 'selected="selected"' : '' }}>New</option>
                                            <option value="Pending" {{ $query->status == 'Pending' ? 'selected="selected"' : '' }}>Pending</option>
                                            <option value="Work In Progress" {{ $query->status == 'Work In Progress' ? 'selected="selected"' : '' }}>Work In Progress</option>
                                            <option value="Resolved" {{ $query->status == 'Resolved'  ? 'selected="selected"' : '' }}>Resolved</option>
                                        </select>
                                    </div>
                                    @if ($errors->has('status'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('status') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <br><br>
                            @if($query->status != 'Resolved')
                            {!! Form::button('Update Query', array('class' => 'btn btn-success margin-bottom-1 mb-1 float-right','type' => 'submit' )) !!}
                            @endif
                            {!! Form::close() !!}

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

    <script src="{{ asset('js/select2.min.js')}}"></script>

    <script type="text/javascript">
        $("#medium").select2({
            placeholder: 'Please select a medium.',
            allowClear:true,
        });

        $("#status").select2({
            placeholder: 'Please select a relevant ticket state.',
            allowClear:true,
        });

        $("#natid").select2({
            placeholder: 'Please select a client',
            allowClear:true,
        }).on('change',function(){
            var fname = $(this).children('option:selected').data('fname');
            var lname = $(this).children('option:selected').data('lname');
            var mobile = $(this).children('option:selected').data('mobile');
            $('#first_name').val(fname);
            $('#last_name').val(lname);
            $('#mobile').val(mobile);
        });
    </script>

@endsection
