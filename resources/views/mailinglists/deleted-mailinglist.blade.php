<?php
/**
 * Created by PhpStorm for eshagi
 * User: vinceg
 * Date: 22/2/2021
 * Time: 22:00
 */
?>
@extends('layouts.app')

@section('template_title')
    View Mailing List
@endsection

@section('content')
    <div class="page-title-box">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h4 class="page-title mb-1">Mailing Lists</h4>
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Mailing Lists</a></li>
                        <li class="breadcrumb-item active">{{$repmailinglist->report}} Mailing List</li>
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
                            <h4 class="header-title">Mailing List For System Reports</h4>
                            <p class="card-title-desc text-danger"> Deleted On: {{$repmailinglist->deleted_at}}</p>
                            <div class="card-body">
                                <div class="form-group has-feedback row {{ $errors->has('report') ? ' has-error ' : '' }}">
                                    {!! Form::label('report', 'Report', array('class' => 'col-md-3 control-label')); !!}
                                    <div class="col-lg-12">
                                        <div class="input-group">
                                            <input class="form-control {{ $errors->has('report') ? ' is-invalid' : '' }}" type="text" name="report" id="report" value="{{$repmailinglist->report}}" readonly>
                                        </div>
                                        @if ($errors->has('report'))
                                            <span class="help-block">
                                            <strong>{{ $errors->first('report') }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="col-xl-7">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="header-title text-info">Report: {{$repmailinglist->report}}</h4>
                            <dl class="user-info">
                                <dt>
                                    <strong>Mailing List Recipients</strong>
                                </dt>
                                <dd>
                                    {{$repmailinglist->list}}
                                </dd>
                                <hr>
                                <dt>
                                    <strong>Is Mailing List Active?</strong>
                                </dt>
                                <dd>
                                    @if($repmailinglist->active) Yes @else No @endif
                                </dd>
                                <hr>

                                <dt>
                                    <strong>List Notes</strong>
                                </dt>
                                <dd>
                                    {{$repmailinglist->notes}}
                                </dd>
                                <hr>
                                <dt>
                                    <strong>Deleted On</strong>
                                </dt>
                                <dd>
                                   <span class="text-danger"> {{$repmailinglist->deleted_at}} </span>
                                </dd>
                                <hr>

                            </dl>
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </div>
@endsection

@section('footer_scripts')

@endsection
