<?php
/**
 *Created by PhpStorm for eshagi
 *User: Vincent Guyo
 *Date: 11/8/2020
 *Time: 10:52 AM
 */

?>
@extends('layouts.app')

@section('template_title')
    Showing Representative {{$user->name}}
@endsection

@section('content')
    <div class="page-title-box">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h4 class="page-title mb-1">Showing Representative {{$user->name}} details</h4>
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{url('/representatives')}}">Representatives</a></li>
                        <li class="breadcrumb-item active">{{$user->name}}</li>
                    </ol>
                </div>

                <div class="col-md-4">
                    <div class="float-right d-none d-md-block">
                        <div>
                            <a class="btn btn-light btn-rounded" href="{{url('/representatives')}}" type="button">
                                <i class="mdi mdi-keyboard-backspace mr-1"></i>Back to Representatives
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
                                    <img src="@if ($user->profile && $user->profile->avatar_status == 1) {{ $user->profile->avatar }}
                                    @else {{ Gravatar::get($user->email) }}
                                    @endif" alt="{{ $user->name }}" class="img-thumbnail float-left" style="width: 200px; height: 200px;" data-holder-rendered="true">

                                </div>
                                <br>
                                <div class="col-sm-4 col-md-6">
                                    <h4 class="text-muted margin-top-sm-1 text-center text-left-tablet">
                                        {{ $user->name }}
                                    </h4>
                                    <p class="text-center text-left-tablet">
                                        <strong>
                                            {{ $representative->first_name }} {{ $representative->last_name }}
                                        </strong>
                                        @if($representative->email)
                                            <br />
                                            <span class="text-center" data-toggle="tooltip" data-placement="top" title="{{ trans('usersmanagement.tooltips.email-user', ['user' => $representative->email]) }}">
                                              {{ Html::mailto($representative->email, $representative->email) }}
                                            </span>
                                        @endif
                                    </p>
                                </div>
                            </div>
                            <br>
                            <div class="clearfix"></div>
                            <div class="border-bottom"></div>

                            @if ($representative->partner_id)

                                <div class="col-sm-5 col-6 text-larger">
                                    <strong>
                                        Partner
                                    </strong>
                                </div>

                                <div class="col-sm-7">
                                    {{ $partner->partner_name }}
                                </div>

                                <div class="clearfix"></div>
                                <div class="border-bottom"></div>

                            @endif

                            @if ($user->name)

                                <div class="col-sm-5 col-6 text-larger">
                                    <strong>
                                        Username
                                    </strong>
                                </div>

                                <div class="col-sm-7">
                                    {{ $user->name }}
                                </div>

                                <div class="clearfix"></div>
                                <div class="border-bottom"></div>

                            @endif

                            @if ($representative->first_name)

                                <div class="col-sm-5 col-6 text-larger">
                                    <strong>
                                        {{ trans('usersmanagement.labelFirstName') }}
                                    </strong>
                                </div>

                                <div class="col-sm-7">
                                    {{ $representative->first_name }}
                                </div>

                                <div class="clearfix"></div>
                                <div class="border-bottom"></div>

                            @endif

                            @if ($representative->last_name)

                                <div class="col-sm-5 col-6 text-larger">
                                    <strong>
                                        {{ trans('usersmanagement.labelLastName') }}
                                    </strong>
                                </div>

                                <div class="col-sm-7">
                                    {{ $representative->last_name }}
                                </div>

                                <div class="clearfix"></div>
                                <div class="border-bottom"></div>

                            @endif

                            @if ($representative->natid)

                                <div class="col-sm-5 col-6 text-larger">
                                    <strong>
                                        National ID
                                    </strong>
                                </div>

                                <div class="col-sm-7">
                                    {{ $representative->natid }}
                                </div>

                                <div class="clearfix"></div>
                                <div class="border-bottom"></div>

                            @endif

                            @if ($representative->mobile)

                                <div class="col-sm-5 col-6 text-larger">
                                    <strong>
                                        Mobile
                                    </strong>
                                </div>

                                <div class="col-sm-7">
                                    {{ $representative->mobile }}
                                </div>

                                <div class="clearfix"></div>
                                <div class="border-bottom"></div>

                            @endif

                            @if ($representative->email)

                                <div class="col-sm-5 col-6 text-larger">
                                    <strong>
                                        {{ trans('usersmanagement.labelEmail') }}
                                    </strong>
                                </div>

                                <div class="col-sm-7">
                                  <span data-toggle="tooltip" data-placement="top" title="{{ trans('usersmanagement.tooltips.email-user', ['user' => $representative->email]) }}">
                                    {{ HTML::mailto($representative->email, $representative->email) }}
                                  </span>
                                </div>

                                <div class="clearfix"></div>
                                <div class="border-bottom"></div>

                            @endif

                            <div class="col-sm-5 col-6 text-larger">
                                <strong>
                                    {{ trans('usersmanagement.labelStatus') }}
                                </strong>
                            </div>

                            <div class="col-sm-7">
                                @if ($user->activated == 1)
                                    <span class="badge badge-success">
                  Activated
                </span>
                                @else
                                    <span class="badge badge-danger">
                  Not-Activated
                </span>
                                @endif
                            </div>

                            <div class="clearfix"></div>
                            <div class="border-bottom"></div>

                            @if ($representative->branch)

                                <div class="col-sm-5 col-6 text-larger">
                                    <strong>
                                        Branch
                                    </strong>
                                </div>

                                <div class="col-sm-7">
                                    {{ $branch->name }}
                                </div>

                                <div class="clearfix"></div>
                                <div class="border-bottom"></div>

                            @endif

                            @if ($representative->created_at)

                                <div class="col-sm-5 col-6 text-larger">
                                    <strong>
                                        {{ trans('usersmanagement.labelCreatedAt') }}
                                    </strong>
                                </div>

                                <div class="col-sm-7">
                                    {{ $representative->created_at }}
                                </div>

                                <div class="clearfix"></div>
                                <div class="border-bottom"></div>

                            @endif

                            @if ($representative->updated_at)

                                <div class="col-sm-5 col-6 text-larger">
                                    <strong>
                                        {{ trans('usersmanagement.labelUpdatedAt') }}
                                    </strong>
                                </div>

                                <div class="col-sm-7">
                                    {{ $representative->updated_at }}
                                </div>

                                <div class="clearfix"></div>
                                <div class="border-bottom"></div>

                            @endif

                            @if ($user->signup_ip_address)

                                <div class="col-sm-5 col-6 text-larger">
                                    <strong>
                                        {{ trans('usersmanagement.labelIpEmail') }}
                                    </strong>
                                </div>

                                <div class="col-sm-7">
                                    <code>
                                        {{ $user->signup_ip_address }}
                                    </code>
                                </div>

                                <div class="clearfix"></div>
                                <div class="border-bottom"></div>

                            @endif

                            @if ($user->signup_confirmation_ip_address)

                                <div class="col-sm-5 col-6 text-larger">
                                    <strong>
                                        {{ trans('usersmanagement.labelIpConfirm') }}
                                    </strong>
                                </div>

                                <div class="col-sm-7">
                                    <code>
                                        {{ $user->signup_confirmation_ip_address }}
                                    </code>
                                </div>

                                <div class="clearfix"></div>
                                <div class="border-bottom"></div>

                            @endif

                            @if ($user->signup_sm_ip_address)

                                <div class="col-sm-5 col-6 text-larger">
                                    <strong>
                                        {{ trans('usersmanagement.labelIpSocial') }}
                                    </strong>
                                </div>

                                <div class="col-sm-7">
                                    <code>
                                        {{ $user->signup_sm_ip_address }}
                                    </code>
                                </div>

                                <div class="clearfix"></div>
                                <div class="border-bottom"></div>

                            @endif

                            @if ($user->admin_ip_address)

                                <div class="col-sm-5 col-6 text-larger">
                                    <strong>
                                        {{ trans('usersmanagement.labelIpAdmin') }}
                                    </strong>
                                </div>

                                <div class="col-sm-7">
                                    <code>
                                        {{ $user->admin_ip_address }}
                                    </code>
                                </div>

                                <div class="clearfix"></div>
                                <div class="border-bottom"></div>

                            @endif

                            @if ($user->updated_ip_address)

                                <div class="col-sm-5 col-6 text-larger">
                                    <strong>
                                        {{ trans('usersmanagement.labelIpUpdate') }}
                                    </strong>
                                </div>

                                <div class="col-sm-7">
                                    <code>
                                        {{ $user->updated_ip_address }}
                                    </code>
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
