<?php
/**
 * Created by PhpStorm for eshagitwo
 * User: vinceg
 * Date: 28/7/2021
 * Time: 03:51
 */
?>
@extends('layouts.app')

@section('template_title')
    Log Query
@endsection

@section('template_linked_css')
    <link href="{{ asset('css/select2.min.css')}}" rel="stylesheet" />
@endsection

@section('content')
    <div class="page-title-box">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h4 class="page-title mb-1">Log Query</h4>
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{url('/my-queries')}}">Query</a></li>
                        <li class="breadcrumb-item active">New Query</li>
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
            <livewire:log-new-query />
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
        $("#medium").select2({
            placeholder: 'Please select a medium.',
            allowClear:true,
        });
    </script>

@endsection
