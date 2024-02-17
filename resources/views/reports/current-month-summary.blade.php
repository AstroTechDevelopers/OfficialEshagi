<?php
/**
 * Created by PhpStorm for eshagi
 * User: vinceg
 * Date: 21/2/2021
 * Time: 08:20
 */
?>
@extends('layouts.app')

@section('template_title')
    Monthly Overview Report
@endsection

@section('template_linked_fonts')
    <link href="{{url('assets/libs/fullcalendar/fullcalendar.min.css')}}" rel="stylesheet" type="text/css" />
@endsection

@section('content')
    <div class="page-title-box">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h4 class="page-title mb-1">Monthly Overview Reports</h4>
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Reports</a></li>
                        <li class="breadcrumb-item active">Monthly Overview Report</li>
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
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="header-title">Monthly Overview Report For {{date(' F Y')}}</h4>

                            <div class="col-xl-12 col-lg-9 col-md-8 mt-4 mt-md-0">
                                {!! $calendar_details->calendar() !!}
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('footer_scripts')
    <script src="{{url('assets/libs/moment/min/moment.min.js')}}"></script>
    <script src="{{url('assets/libs/jquery-ui-dist/jquery-ui.min.js')}}"></script>
    <script src="{{url('assets/libs/fullcalendar/fullcalendar.min.js')}}"></script>

    {!! $calendar_details->script() !!}

@endsection
