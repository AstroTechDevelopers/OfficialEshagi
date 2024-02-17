<?php
/**
 * Created by PhpStorm for eshagitwo
 * User: VinceGee
 * Date: 10/3/2021
 * Time: 9:23 PM
 */ ?>
@extends('layouts.app')

@section('template_title')
    Add Account
@endsection

@section('template_linked_css')
    <link href="{{ asset('css/select2.min.css')}}" rel="stylesheet" />
@endsection

@section('content')
    <div class="page-title-box">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h4 class="page-title mb-1">Accounts</h4>
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{url('/accounts')}}">Accounts</a></li>
                        <li class="breadcrumb-item active">Add an Account</li>
                    </ol>
                </div>

                <div class="col-md-4">
                    <div class="float-right d-none d-md-block">
                        <div>
                            <a class="btn btn-light btn-rounded" href="{{url('/accounts')}}" type="button">
                                <i class="mdi mdi-keyboard-backspace mr-1"></i>Back to Accounts
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
                            {!! Form::open(array('route' => 'accounts.store', 'method' => 'POST', 'role' => 'form', 'class' => 'needs-validation')) !!}

                            {!! csrf_field() !!}

                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group has-feedback row {{ $errors->has('ledger') ? ' has-error ' : '' }}">
                                        {!! Form::label('ledger', 'Ledger', array('class' => 'col-md-12 control-label')); !!}
                                        <div class="col-md-12">
                                            <select class="custom-select form-control dynamic" name="ledger" id="ledger" required>
                                                <option value="">Select Ledger</option>
                                                @if ($ledgers)
                                                    @foreach($ledgers as $ledger)
                                                        <option value="{{ $ledger->id }}" >{{ $ledger->ledger }} </option>
                                                    @endforeach
                                                @endif
                                            </select>
                                            @if ($errors->has('ledger'))
                                                <span class="help-block">
                                            <strong>{{ $errors->first('ledger') }}</strong>
                                        </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group has-feedback row {{ $errors->has('natid') ? ' has-error ' : '' }}">
                                        {!! Form::label('natid', 'Account Holder', array('class' => 'col-md-12 control-label')); !!}
                                        <div class="col-md-12">
                                            <div class="input-group">
                                                <select class="custom-select form-control dynamic" name="natid" id="natid" required>
                                                    <option value="">Select Client</option>
                                                    @if ($clients)
                                                        @foreach($clients as $client)
                                                            <option value="{{ $client->natid }}" >{{ $client->first_name.' '. $client->last_name }} </option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                            </div>
                                            @if ($errors->has('natid'))
                                                <span class="help-block">
                                            <strong>{{ $errors->first('natid') }}</strong>
                                        </span>
                                            @endif
                                        </div>
                                    </div>

                                </div>
                            </div>

                            {!! Form::button('Add Account', array('class' => 'btn btn-success margin-bottom-1 mb-1 float-right','type' => 'submit' )) !!}
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
        $("#ledger").select2({
            placeholder: 'Please select ledger.',
            allowClear:true,
        });

        $("#natid").select2({
            placeholder: 'Please select client.',
            allowClear:true,
        });
    </script>
@endsection
