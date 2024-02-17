<?php
/**
 * Created by PhpStorm for eshagi
 * User: vinceg
 * Date: 24/3/2021
 * Time: 06:53
 */
?>
@extends('layouts.app')

@section('template_title')
    Resend Client OTP
@endsection

@section('template_linked_css')
    <link href="{{ asset('css/select2.min.css')}}" rel="stylesheet" />
@endsection

@section('content')
    <div class="page-title-box">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h4 class="page-title mb-1">Clients</h4>
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Clients</a></li>
                        <li class="breadcrumb-item active">Resend Client Loan OTP</li>
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
                <div class="col-xl-8">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="header-title">Client Info</h4>

                            <div class="card-body">
                                {!! Form::open(array('route' => 'sending.otp', 'method' => 'POST', 'role' => 'form', 'class' => 'needs-validation')) !!}

                                {!! csrf_field() !!}

                                <div class="form-group has-feedback row {{ $errors->has('client_id') ? ' has-error ' : '' }}">
                                    {!! Form::label('client_id', 'Select client', array('class' => 'col-md-3 control-label')); !!}
                                    <div class="col-md-9">
                                        <select class="form-control" id="client_id" name="client_id" required>
                                            <option value="">Select a Client </option>
                                            @if ($clients)
                                                @foreach($clients as $client)
                                                    <option value="{{$client->id}}" >{{ $client->first_name .' '. $client->last_name .' '. $client->natid }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                        @if ($errors->has('client_id'))
                                            <span class="help-block">
                                            <strong>{{ $errors->first('client_id') }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                </div>

                                {!! Form::button('Resend OTP', array('class' => 'btn btn-success margin-bottom-1 mb-1 float-right','type' => 'submit' )) !!}
                                {!! Form::close() !!}
                            </div>

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
        $("#client_id").select2({
            placeholder: 'Please select a client',
            allowClear:true,
        });
    </script>
@endsection
