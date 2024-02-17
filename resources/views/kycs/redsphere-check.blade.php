<?php
/**
 * Created by PhpStorm for eshagi
 * User: vinceg
 * Date: 7/1/2021
 * Time: 10:09
 */
?>
@extends('layouts.app')

@section('template_title')
    KYC Verification
@endsection

@section('content')
    <div class="page-title-box">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h4 class="page-title mb-1">KYCs</h4>
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">KYCs</a></li>
                        <li class="breadcrumb-item active">KYC Verification</li>
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
                            <h4 class="header-title">Check KYC Info From RedSphere</h4>

                            <div class="card-body">
                                {!! Form::open(array('route' => 'checkkycreds', 'method' => 'POST', 'role' => 'form', 'class' => 'needs-validation')) !!}

                                {!! csrf_field() !!}

                                <div class="form-group has-feedback row {{ $errors->has('natid') ? ' has-error ' : '' }}">
                                    {!! Form::label('natid', 'National ID', array('class' => 'col-md-3 control-label')); !!}
                                    <div class="col-md-9">
                                        <div class="input-group">
                                            <input class="form-control  {{ $errors->has('natid') ? ' is-invalid' : '' }}" type="text" autocapitalize="characters" maxlength="15" name="natid" id="natid" value="{{ old('natid') }}" onkeyup="validateId()" required="required" pattern="^[0-9]{2}-[0-9]{6,7}-[A-Z]-[0-9]{2}$" title="ID Format should be in the form of xx-xxxxxxx X xx" placeholder="Enter client National ID...">
                                        </div>
                                        @if ($errors->has('natid'))
                                            <span class="help-block">
                                            <strong>{{ $errors->first('natid') }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group has-feedback row {{ $errors->has('loanid') ? ' has-error ' : '' }}">
                                    {!! Form::label('loanid', 'Loan ID', array('class' => 'col-md-3 control-label')); !!}
                                    <div class="col-md-9">
                                        <div class="input-group">
                                            <input class="form-control  {{ $errors->has('loanid') ? ' is-invalid' : '' }}" type="text" maxlength="15" name="loanid" id="loanid" value="{{ old('loanid') }}" required="required" placeholder="Enter client Loan ID, you're processing...">
                                        </div>
                                        @if ($errors->has('loanid'))
                                            <span class="help-block">
                                            <strong>{{ $errors->first('loanid') }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                </div>

                                {!! Form::button('Check Client Record', array('class' => 'btn btn-success margin-bottom-1 mb-1 float-right','type' => 'submit' )) !!}
                                {!! Form::close() !!}
                            </div>

                        </div>
                    </div>
                </div>
                @if(isset($resp))
                    <div class="col-xl-7">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="header-title text-info">{{$message}}</h4>
                                <dl class="user-info">
                                    <dt>
                                        <strong>Customer Type</strong>
                                    </dt>
                                    <dd>
                                        {{ $resp['CUSTOMER_TYPE'] }}
                                    </dd>
                                    <hr>
                                    <dt>
                                        <strong>Sub Individual</strong>
                                    </dt>
                                    <dd>
                                        {{ $resp['SUB_INDIVIDUAL'] }}
                                    </dd>
                                    <hr>

                                    <dt>
                                        <strong>RedSphere Number</strong>
                                    </dt>
                                    <dd>
                                        {{ $resp['CUSTOMER_NUMBER'] }}
                                    </dd>
                                    <hr>
                                    <dt>
                                        <strong>Surname</strong>
                                    </dt>
                                    <dd>
                                        {{ $resp['SURNAME'] }}
                                    </dd>
                                    <hr>
                                    <dt>
                                        <strong>Forename(s)</strong>
                                    </dt>
                                    <dd>
                                        {{ $resp['FORENAMES'] }}
                                    </dd>
                                    <hr>
                                    <dt>
                                        <strong>DOB</strong>
                                    </dt>
                                    <dd>
                                        {{ $resp['DOB'] }}
                                    </dd>
                                    <hr>
                                    <dt>
                                        <strong>National ID</strong>
                                    </dt>
                                    <dd>
                                        {{ $resp['IDNO'] }}
                                    </dd>
                                    <hr>
                                    <dt>
                                        <strong>Address</strong>
                                    </dt>
                                    <dd>
                                        {{ $resp['ADDRESS'] }}
                                    </dd>
                                    <hr>
                                    <dt>
                                        <strong>Mobile Number</strong>
                                    </dt>
                                    <dd>
                                        {{ $resp['PHONE_NO'] }}
                                    </dd>
                                    <hr>

                                </dl>
                            </div>
                        </div>
                        @endif

                    </div>

            </div>
        </div>
    </div>
@endsection

@section('footer_scripts')

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

    </script>

@endsection
