<?php
/**
 * Created by PhpStorm for eshagitwo
 * User: VinceGee
 * Date: 12/13/2021
 * Time: 5:59 AM
 */ ?>
@extends('layouts.app')

@section('template_title')
    Client Verification
@endsection

@section('content')
    <div class="page-title-box">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h4 class="page-title mb-1">Old Mutual Clients</h4>
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Old Mutual Clients</a></li>
                        <li class="breadcrumb-item active">Old Mutual Client Verification</li>
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
                <div class="col-xl-5">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="header-title">Check Client Info From Old Mutual/Musoni</h4>

                            <div class="card-body">
                                {!! Form::open(array('route' => 'fetch.clientinfo', 'method' => 'POST', 'role' => 'form', 'class' => 'needs-validation')) !!}

                                {!! csrf_field() !!}

                                <div class="form-group has-feedback row {{ $errors->has('client_id') ? ' has-error ' : '' }}">
                                    {!! Form::label('client_id', 'Musoni Client ID', array('class' => 'col-md-12 control-label')); !!}
                                    <div class="col-md-12">
                                        <div class="input-group">
                                            <input class="form-control  {{ $errors->has('client_id') ? ' is-invalid' : '' }}" type="text" name="client_id" id="client_id" value="{{ old('client_id') }}" required="required" placeholder="e.g. 3924">
                                        </div>
                                        @if ($errors->has('client_id'))
                                            <span class="help-block">
                                            <strong>{{ $errors->first('client_id') }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                </div>

                                {!! Form::button('Get Client Record', array('class' => 'btn btn-success margin-bottom-1 mb-1 float-right','type' => 'submit' )) !!}
                                {!! Form::close() !!}
                            </div>

                        </div>
                    </div>
                </div>
                @if(isset($singleClientResp))
                    @if(isset($singleClientResp['accountNo']))
                    <div class="col-xl-7">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="header-title text-info">{{$message}}</h4>
                                <dl class="user-info">
                                    <dt>
                                        <strong>Account Number</strong>
                                    </dt>
                                    <dd>
                                        {{ $singleClientResp['accountNo'] }}
                                    </dd>
                                    <hr>
                                    <dt>
                                        <strong>External ID</strong>
                                    </dt>
                                    <dd>
                                        {{ $singleClientResp['externalId'] }}
                                    </dd>
                                    <hr>

                                    <dt>
                                        <strong>Status</strong>
                                    </dt>
                                    <dd>
                                        {{ $singleClientResp['status']['value'] }}
                                    </dd>
                                    <hr>
                                    <dt>
                                        <strong>Activation Date</strong>
                                    </dt>
                                    <dd>
                                        {{ $singleClientResp['activationDate'][0].'-'.$singleClientResp['activationDate'][1].'-'.$singleClientResp['activationDate'][2]}}
                                    </dd>
                                    <hr>
                                    <dt>
                                        <strong>Surname</strong>
                                    </dt>
                                    <dd>
                                        {{ $singleClientResp['lastname'] }}
                                    </dd>
                                    <hr>
                                    <dt>
                                        <strong>Forename(s)</strong>
                                    </dt>
                                    <dd>
                                        {{ $singleClientResp['firstname'] }}
                                    </dd>
                                    <hr>
                                    <dt>
                                        <strong>Display Name</strong>
                                    </dt>
                                    <dd>
                                        {{ $singleClientResp['displayName'] }}
                                    </dd>
                                    <hr>
                                    {{--<dt>
                                        <strong>DOB</strong>
                                    </dt>
                                    <dd>
                                        {{ $singleClientResp['dateOfBirth'][0].'-'.$singleClientResp['dateOfBirth'][1].'-'.$singleClientResp['dateOfBirth'][2] }}
                                    </dd>
                                    <hr>
                                    <dt>
                                        <strong>Mobile Number</strong>
                                    </dt>
                                    <dd>
                                        {{ $singleClientResp['mobileNo'] }}
                                    </dd>
                                    <hr>--}}
                                    <dt>
                                        <strong>Status</strong>
                                    </dt>
                                    <dd>
                                        {{ $singleClientResp['status']['value'] }}
                                    </dd>
                                    <hr>
                                    <dt>
                                        <strong>Office</strong>
                                    </dt>
                                    <dd>
                                        {{ $singleClientResp['officeName'] }}
                                    </dd>
                                    <hr>
                                    <dt>
                                        <strong>Locale</strong>
                                    </dt>
                                    <dd>
                                        {{ $singleClientResp['countryIsoCode'] }}
                                    </dd>
                                    <hr>

                                </dl>
                            </div>
                        </div>


                    </div>
                    @else
                        <div class="col-xl-7">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="header-title text-danger">Error</h4>
                                    <dl class="user-info text-center">
                                        <dt>
                                            <strong>Error!</strong>
                                        </dt>
                                        <dd>
                                            {{ $singleClientResp['errors'][0]['defaultUserMessage'] }}
                                        </dd>
                                    </dl>
                                </div>
                            </div>


                        </div>
                    @endif
                @endif

            </div>
        </div>
    </div>
@endsection

@section('footer_scripts')

@endsection
