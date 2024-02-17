<?php
/**
 * Created by PhpStorm for eshagi
 * User: vinceg
 * Date: 21/3/2021
 * Time: 20:40
 */
?>
@extends('layouts.app')

@section('template_title')
    Astrogent Info
@endsection

@section('content')
    <div class="page-title-box">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h4 class="page-title mb-1">Showing {{$astrogent->first_name.' '.$astrogent->last_name}}</h4>
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{url('/astrogents')}}">Astrogents</a></li>
                        <li class="breadcrumb-item active">{{$astrogent->first_name.' '.$astrogent->last_name}}</li>
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
                            <div class="row">
                                <div class="col-sm-12 col-md-12">
                                    <h4 class="text-muted margin-top-sm-1 text-center text-left-tablet">
                                        {{ $astrogent->first_name.' '.$astrogent->last_name }}
                                    </h4>
                                    <p class="text-center text-left-tablet">
                                        <strong>
                                            Astrogent
                                        </strong>
                                        @if($astrogent->email)
                                            <br />
                                            <span class="text-center" data-toggle="tooltip" data-placement="top" title="Mail to {{$astrogent->email}}">
                                              {{ Html::mailto($astrogent->email, $astrogent->email) }}
                                            </span>
                                        @endif
                                        <br>
                                        Deleted On: <span class="text-danger">{{$astrogent->deleted_at}}</span>
                                    </p>

                                </div>
                            </div>
                            <br>
                            <div class="clearfix"></div>
                            <div class="border-bottom"></div>


                            <div class="row">
                                <div class="col-lg-6">

                                    <div class="col-lg-12 text-larger">
                                        <strong>
                                            First Name
                                        </strong>
                                    </div>

                                    <div class="col-lg-12">
                                        {{ $astrogent->first_name }}
                                    </div>

                                    <div class="clearfix"></div>
                                    <div class="border-bottom"></div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="col-lg-12 text-larger">
                                        <strong>
                                            Last Name
                                        </strong>
                                    </div>

                                    <div class="col-lg-12">
                                        {{ $astrogent->last_name }}
                                    </div>

                                    <div class="clearfix"></div>
                                    <div class="border-bottom"></div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-6">

                                    <div class="col-lg-12 text-larger">
                                        <strong>
                                            Title
                                        </strong>
                                    </div>

                                    <div class="col-lg-12">
                                        {{ $astrogent->title }}
                                    </div>

                                    <div class="clearfix"></div>
                                    <div class="border-bottom"></div>

                                </div>
                                <div class="col-lg-6">
                                    <div class="col-lg-12 text-larger">
                                        <strong>
                                            Gender
                                        </strong>
                                    </div>

                                    <div class="col-lg-12">
                                        {{ $astrogent->gender }}
                                    </div>

                                    <div class="clearfix"></div>
                                    <div class="border-bottom"></div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="col-lg-12 text-larger">
                                        <strong>
                                            National ID
                                        </strong>
                                    </div>

                                    <div class="col-lg-12">
                                        {{ $astrogent->natid }}
                                    </div>

                                    <div class="clearfix"></div>
                                    <div class="border-bottom"></div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="col-lg-12 text-larger">
                                        <strong>
                                            Mobile Number
                                        </strong>
                                    </div>

                                    <div class="col-lg-12">
                                        {{ $astrogent->mobile }}
                                    </div>

                                    <div class="clearfix"></div>
                                    <div class="border-bottom"></div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="col-lg-12 text-larger">
                                        <strong>
                                            Physical Address
                                        </strong>
                                    </div>

                                    <div class="col-lg-12">
                                        {{ $astrogent->address }}
                                    </div>

                                    <div class="clearfix"></div>
                                    <div class="border-bottom"></div>
                                </div>
                            </div>

                            <div class="row">
                                @if($astrogent->bank_acc_name)
                                    <div class="col-lg-6">
                                        <div class="col-lg-12 text-larger">
                                            <strong>
                                                Bank Account Name
                                            </strong>
                                        </div>

                                        <div class="col-lg-12">
                                            {{ $astrogent->bank_acc_name }}
                                        </div>

                                        <div class="clearfix"></div>
                                        <div class="border-bottom"></div>
                                    </div>
                                @endif
                                @if($astrogent->bank)
                                    <div class="col-lg-6">
                                        <div class="col-lg-12 text-larger">
                                            <strong>
                                                Bank ID
                                            </strong>
                                        </div>

                                        <div class="col-lg-12">
                                            {{ $astrogent->bank }}
                                        </div>

                                        <div class="clearfix"></div>
                                        <div class="border-bottom"></div>
                                    </div>
                                @endif
                            </div>

                            <div class="row">
                                @if($astrogent->branch)
                                    <div class="col-lg-6">
                                        <div class="col-lg-12 text-larger">
                                            <strong>
                                                Branch
                                            </strong>
                                        </div>

                                        <div class="col-lg-12">
                                            {{ $astrogent->branch }}
                                        </div>

                                        <div class="clearfix"></div>
                                        <div class="border-bottom"></div>
                                    </div>
                                @endif
                                @if($astrogent->branch_code)
                                    <div class="col-lg-6">
                                        <div class="col-lg-12 text-larger">
                                            <strong>
                                                Branch Code
                                            </strong>
                                        </div>

                                        <div class="col-lg-12">
                                            {{ $astrogent->branch_code }}
                                        </div>

                                        <div class="clearfix"></div>
                                        <div class="border-bottom"></div>
                                    </div>
                                @endif
                            </div>

                            <div class="row">
                                @if($astrogent->accountNumber)
                                    <div class="col-lg-6">
                                        <div class="col-lg-12 text-larger">
                                            <strong>
                                                Account Number
                                            </strong>
                                        </div>

                                        <div class="col-lg-12">
                                            {{ $astrogent->accountNumber }}
                                        </div>

                                        <div class="clearfix"></div>
                                        <div class="border-bottom"></div>
                                    </div>
                                @endif

                                <div class="col-lg-6">
                                    <div class="col-lg-12 text-larger">
                                        <strong>
                                            Activated
                                        </strong>
                                    </div>

                                    <div class="col-lg-12">
                                        @if($astrogent->activated == true)
                                            Yes
                                        @else
                                            No
                                        @endif
                                    </div>

                                    <div class="clearfix"></div>
                                    <div class="border-bottom"></div>
                                </div>

                            </div>

                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="col-lg-12 text-larger">
                                        <strong>
                                            {{ trans('usersmanagement.labelCreatedAt') }}
                                        </strong>
                                    </div>

                                    <div class="col-lg-12">
                                        {{ $astrogent->created_at }}
                                    </div>

                                    <div class="clearfix"></div>
                                    <div class="border-bottom"></div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="col-lg-12 text-larger">
                                        <strong>
                                            {{ trans('usersmanagement.labelUpdatedAt') }}
                                        </strong>
                                    </div>

                                    <div class="col-lg-12">
                                        {{ $astrogent->updated_at }}
                                    </div>

                                    <div class="clearfix"></div>
                                    <div class="border-bottom"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


            </div>
        </div>
    </div>
@endsection

@section('footer_scripts')
    @if(config('usersmanagement.tooltipsEnabled'))
        @include('scripts.tooltips')
    @endif
@endsection
