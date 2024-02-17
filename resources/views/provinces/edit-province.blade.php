<?php
/**
 * Created by PhpStorm for eshagi
 * User: vinceg
 * Date: 29/1/2021
 * Time: 16:27
 */
?>
@extends('layouts.app')

@section('template_title')
    Edit Province
@endsection

@section('template_linked_css')
    <link href="{{ asset('css/select2.min.css')}}" rel="stylesheet" />
@endsection

@section('content')
    <div class="page-title-box">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h4 class="page-title mb-1">Provinces</h4>
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{url('/provinces')}}">Provinces</a></li>
                        <li class="breadcrumb-item active">Provinces</li>
                    </ol>
                </div>

                <div class="col-md-4">
                    <div class="float-right d-none d-md-block">
                        <div>
                            <a class="btn btn-light btn-rounded" href="{{url('/provinces')}}" type="button">
                                <i class="mdi mdi-keyboard-backspace mr-1"></i>Back to Provinces
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
                            {!! Form::open(array('route' => ['provinces.update', $province->id], 'method' => 'PUT', 'role' => 'form', 'class' => 'needs-validation')) !!}

                            {!! csrf_field() !!}

                            <div class="form-group has-feedback row {{ $errors->has('country') ? ' has-error ' : '' }}">
                                {!! Form::label('country', 'Country', array('class' => 'col-md-3 control-label')); !!}
                                <div class="col-md-9">
                                    <select class="custom-select form-control dynamic" name="country" id="country" required>
                                        <option value="">Select Country</option>
                                        @if ($countries)
                                            @foreach($countries as $country)
                                                <option value="{{ $country->id }}" data-price="{{$country->country}}" {{ $province->id == $country->id ? 'selected="selected"' : '' }}>{{ $country->country }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                    @if ($errors->has('country'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('country') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <input type="hidden" class="form-control" name="country_name" id="country_name" value="{{$province->country_name}}">

                            <div class="form-group has-feedback row {{ $errors->has('province') ? ' has-error ' : '' }}">
                                {!! Form::label('province', 'Province', array('class' => 'col-md-3 control-label')); !!}
                                <div class="col-md-9">
                                    <div class="input-group">
                                        {!! Form::text('province', $province->province, array('id' => 'province', 'class' => 'form-control', 'placeholder' => 'e.g. Harare', 'required')) !!}
                                    </div>
                                    @if ($errors->has('province'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('province') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            {!! Form::button('Update Province', array('class' => 'btn btn-success margin-bottom-1 mb-1 float-right','type' => 'submit' )) !!}
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

        $("#country").select2({
            placeholder: 'Please select a country.',
            allowClear:true,
        }).change(function(){
            var price = $(this).children('option:selected').data('price');
            $('#country_name').val(price);
        });
    </script>
@endsection
