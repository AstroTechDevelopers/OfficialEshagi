<?php
/**
 * Created by PhpStorm for eshagitwo
 * User: vinceg
 * Date: 3/8/2021
 * Time: 05:06
 */
?>
@extends('layouts.app')

@section('template_title')
    Query: {{$query->id}}
@endsection

@section('template_linked_css')
    <link href="{{ asset('css/select2.min.css')}}" rel="stylesheet" />
@endsection

@section('content')
    <div class="page-title-box">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h4 class="page-title mb-1">Action Query</h4>
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{url('/my-queries')}}">Queries</a></li>
                        <li class="breadcrumb-item active">Query: {{$query->id}}</li>
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
                            <h4 class="header-title">Query Details</h4>
                            <p class="card-title-desc">This ticket has been logged as opened by {{$query->agent}}</p>

                            {!! Form::open(array('route' => ['update.queri',$query->id], 'method' => 'POST', 'role' => 'form', 'class' => 'needs-validation')) !!}

                            {!! csrf_field() !!}

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

                            <div class="form-group has-feedback row {{ $errors->has('action_taken') ? ' has-error ' : '' }}">
                                {!! Form::label('action_taken', 'Action Taken', array('class' => 'col-md-3 control-label')); !!}
                                <div class="col-md-9">
                                    <div class="input-group">
                                        {!! Form::textarea('action_taken', $query->action_taken, array('id' => 'action_taken', 'class' => 'form-control', 'placeholder' => 'e.g. What have you done to solve this issue?' , 'required')) !!}
                                    </div>
                                    @if ($errors->has('action_taken'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('action_taken') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <br><br>
                            @if($query->status != 'Resolved')
                            {!! Form::button('Update Ticket', array('class' => 'btn btn-success margin-bottom-1 mb-1 float-right','type' => 'submit' )) !!}
                            @endif
                            {!! Form::close() !!}

                        </div>
                    </div>
                </div>

                <div class="col-xl-4">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="header-title">Query Info </h4>
                            <p class="card-title-desc">Query related Info  </p>

                            Ticket Status: {{$query->status}} <br>
                            Medium: {{$query->medium}} <br>
                            National ID: {{$query->natid}} <br>
                            Client: {{$query->first_name.' '.$query->last_name}} <br>
                            Phone Number: {{$query->mobile}} <br>
                            Opened On: {{$query->opened_on}} <br>
                            @if($query->resolved_on)
                            Resolved On: {{$query->resolved_on}} <br>
                            @endif
                            Created On: {{$query->created_at}} <br>


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
        $("#status").select2({
            placeholder: 'Please select a relevant state.',
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
