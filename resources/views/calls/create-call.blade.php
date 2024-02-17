<?php
/**
 * Created by PhpStorm for eshagitwo
 * User: vinceg
 * Date: 28/7/2021
 * Time: 00:57
 */
?>
@extends('layouts.app')

@section('template_title')
    Outgoing Call
@endsection

@section('template_linked_css')
    <link href="{{asset('assets/libs/air-datepicker/css/datepicker.min.css')}}" rel="stylesheet" type="text/css" />
@endsection

@section('content')
    <div class="page-title-box">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h4 class="page-title mb-1">Outgoing Call</h4>
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{url('/my-calls')}}">Calls</a></li>
                        <li class="breadcrumb-item active">Outgoing Sales Call</li>
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
            <livewire:find-call />
        </div>
    </div>
@endsection

@section('footer_scripts')
    @include('scripts.delete-modal-script')
    @if(config('usersmanagement.tooltipsEnabled'))
        @include('scripts.tooltips')
    @endif

    <script src="{{asset('assets/libs/air-datepicker/js/datepicker.min.js')}}"></script>
    <script src="{{asset('assets/libs/air-datepicker/js/i18n/datepicker.en.js')}}"></script>

    <script>
        $("#appointment").datepicker({
            language: 'en',
        });

    </script>
@endsection
