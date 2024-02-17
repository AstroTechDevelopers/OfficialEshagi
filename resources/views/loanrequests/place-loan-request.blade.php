<?php
/**
 * Created by PhpStorm for eshagitwo
 * User: VinceGee
 * Date: 11/13/2021
 * Time: 7:18 AM
 */ ?>
@extends('layouts.app')

@section('template_title')
    Place Loan Request
@endsection

@section('template_linked_css')
    <link href="{{ asset('css/select2.min.css')}}" rel="stylesheet" />
    {{--<link href="{{ asset('assets/libs/selectize/css/selectize.css')}}" rel="stylesheet" type="text/css" />--}}

@endsection

@section('content')
    <div class="page-title-box">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h4 class="page-title mb-1">Loan Requests</h4>
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{url('/loan-requests')}}">Loan Requests</a></li>
                        <li class="breadcrumb-item active">Add Loan Request</li>
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
                            {!! Form::open(array('route' => 'loan-requests.store', 'method' => 'POST', 'role' => 'form', 'novalidate')) !!}

                            {!! csrf_field() !!}

                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group has-feedback row {{ $errors->has('loan_id') ? ' has-error ' : '' }}">
                                        {!! Form::label('loan_id', 'Loan', array('class' => 'col-md-12 control-label')); !!}
                                        <div class="col-md-12">
                                            <select class="livesearch form-control" id="loan_id" name="loan_id" required>

                                            </select>
                                            @if ($errors->has('loan_id'))
                                                <span class="help-block">
                                            <strong>{{ $errors->first('loan_id') }}</strong>
                                        </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group has-feedback row {{ $errors->has('request') ? ' has-error ' : '' }}">
                                        {!! Form::label('request', 'Request', array('class' => 'col-md-12 control-label')); !!}
                                        <div class="col-md-12">
                                            <div class="input-group">
                                                <select class="form-control select2" style="width: 100%;" name="request" id="request" required>
                                                    <option value="">Select Request Type</option>
                                                    <option value="Decline">Decline</option>
                                                    <option value="Escalate">Escalate</option>
                                                </select>
                                            </div>
                                            @if ($errors->has('request'))
                                                <span class="help-block">
                                            <strong>{{ $errors->first('request') }}</strong>
                                        </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group has-feedback row {{ $errors->has('explanation') ? ' has-error ' : '' }}">
                                        {!! Form::label('explanation', 'Explanation', array('class' => 'col-md-12 control-label')); !!}
                                        <div class="col-md-12">
                                            <div class="input-group">
                                                {!! Form::textarea('explanation', NULL, array('id' => 'explanation', 'class' => 'form-control', 'placeholder' => 'e.g. Incorrect information provided', 'required')) !!}
                                            </div>
                                            @if ($errors->has('explanation'))
                                                <span class="help-block">
                                            <strong>{{ $errors->first('explanation') }}</strong>
                                        </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {!! Form::button('Place Request', array('class' => 'btn btn-success margin-bottom-1 mb-1 float-right','type' => 'submit' )) !!}
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

    <script>
        $('.select2').select2({
            placeholder: 'Please select request type',
            allowClear: true,
        });
    </script>

    {{--<script src="{{ asset('assets/libs/selectize/js/standalone/selectize.min.js')}}"></script>

    <script>
        $('.selectize').selectize();
    </script>--}}

    <script type="text/javascript">
        $('.livesearch').select2({
            placeholder: 'Search by client name or National ID',
            allowClear:true,
            ajax: {
                url: '{{ route('searchselect.loan') }}',
                dataType: 'json',
                delay: 250,
                processResults: function (data) {
                    return {
                        results: $.map(data, function (item) {
                            return {
                                text: item.first_name+' '+item.last_name+' - '+item.natid+' ($'+ item.amount +')',
                                id: item.id
                            }
                        })
                    };
                },
                cache: true
            }
        });
    </script>

    <!--tinymce js-->
    <script src="{{asset('assets/libs/tinymce/tinymce.min.js')}}"></script>

    <script>
        $(document).ready(
            function(){
                $("#explanation").length&&tinymce.init({
                            selector:"textarea#explanation",
                            height:300,
                            width:'100%',
                            plugins:["advlist autolink link image lists charmap print preview hr anchor pagebreak spellchecker","searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking","save table directionality emoticons template paste"],
                            toolbar:"insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | l      ink image | print preview media fullpage | forecolor backcolor emoticons",
                            style_formats:[
                                {title:"Bold text",inline:"b"},
                                {title:"Red text",inline:"span",styles:{color:"#ff0000"}},
                                {title:"Red header",block:"h1",styles:{color:"#ff0000"}},
                                {title:"Example 1",inline:"span",classes:"example1"},
                                {title:"Example 2",inline:"span",classes:"example2"},
                                {title:"Table styles"},
                                {title:"Table row 1",selector:"tr",classes:"tablerow1"}
                            ]
                        })
                    });
    </script>
@endsection
