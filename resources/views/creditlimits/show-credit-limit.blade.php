<?php
/**
 * Created by PhpStorm for eshagi
 * User: vinceg
 * Date: 20/2/2021
 * Time: 13:34
 */
?>
@extends('layouts.app')

@section('template_title')
    Credit Limit Record
@endsection

@section('content')
    <div class="page-title-box">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h4 class="page-title mb-1">Showing Credit Limit Record for {{$client->natid}}</h4>
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{url('/limits')}}">Limits</a></li>
                        <li class="breadcrumb-item active">{{$client->first_name.' '.$client->last_name}}</li>
                    </ol>
                </div>

                <div class="col-md-4">
                    <div class="float-right d-none d-md-block">
                        <div>
                            <a class="btn btn-light btn-rounded" href="{{url('/limits')}}" type="button">
                                <i class="mdi mdi-keyboard-backspace mr-1"></i>Back to Limits
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
                            <div class="row">
                                <div class="float-left col-sm-4 offset-sm-2 col-md-2 offset-md-3">
                                    <img src="@if ($client->profile && $client->profile->avatar_status == 1) {{ $client->profile->avatar }}
                                    @else {{ Gravatar::get($client->email) }}
                                    @endif" alt="{{ $client->name }}" class="img-thumbnail float-left" style="width: 200px; height: 200px;" data-holder-rendered="true">

                                </div>
                                <br>
                                <div class="col-sm-4 col-md-6">
                                    <h4 class="text-muted margin-top-sm-1 text-center text-left-tablet">
                                        {{ $client->name }}
                                    </h4>
                                    <p class="text-center text-left-tablet">
                                        <strong>
                                            {{ $client->first_name .' '. $client->last_name }}
                                        </strong>
                                    </p>
                                    <h1 class="text-center text-left-tablet">
                                       Limit: ${{ $client->cred_limit }}
                                    </h1>
                                </div>
                            </div>
                            <br>
                            <div class="clearfix"></div>
                            <div class="border-bottom"></div>

                            @if ($client->gross)

                                <div class="col-sm-5 col-6 text-larger">
                                    <strong>
                                        Gross Salary
                                    </strong>
                                </div>

                                <div class="col-sm-7">
                                    ${{ $client->gross }}
                                </div>

                                <div class="clearfix"></div>
                                <div class="border-bottom"></div>

                            @endif

                            @if ($client->salary)

                                <div class="col-sm-5 col-6 text-larger">
                                    <strong>
                                        Net Salary
                                    </strong>
                                </div>

                                <div class="col-sm-7">
                                    ${{ $client->salary }}
                                </div>

                                <div class="clearfix"></div>
                                <div class="border-bottom"></div>

                            @endif

                            @if ($client->first_name)

                                <div class="col-sm-5 col-6 text-larger">
                                    <strong>
                                        {{ trans('usersmanagement.labelFirstName') }}
                                    </strong>
                                </div>

                                <div class="col-sm-7">
                                    {{ $client->first_name }}
                                </div>

                                <div class="clearfix"></div>
                                <div class="border-bottom"></div>

                            @endif

                            @if ($client->last_name)

                                <div class="col-sm-5 col-6 text-larger">
                                    <strong>
                                        {{ trans('usersmanagement.labelLastName') }}
                                    </strong>
                                </div>

                                <div class="col-sm-7">
                                    {{ $client->last_name }}
                                </div>

                                <div class="clearfix"></div>
                                <div class="border-bottom"></div>

                            @endif

                            @if ($client->natid)

                                <div class="col-sm-5 col-6 text-larger">
                                    <strong>
                                        National ID
                                    </strong>
                                </div>

                                <div class="col-sm-7">
                                    {{ $client->natid }}
                                </div>

                                <div class="clearfix"></div>
                                <div class="border-bottom"></div>

                            @endif

                            @if ($client->mobile)

                                <div class="col-sm-5 col-6 text-larger">
                                    <strong>
                                        Mobile
                                    </strong>
                                </div>

                                <div class="col-sm-7">
                                    {{ $client->mobile }}
                                </div>

                                <div class="clearfix"></div>
                                <div class="border-bottom"></div>

                            @endif

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('modals.modal-delete')
@endsection

@section('footer_scripts')
    @include('scripts.delete-modal-script')
    @if(config('usersmanagement.tooltipsEnabled'))
        @include('scripts.tooltips')
    @endif
@endsection
