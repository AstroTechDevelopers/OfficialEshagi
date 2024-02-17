<?php
/**
 *Created by PhpStorm for eshagi
 *User: Vincent Guyo
 *Date: 10/11/2020
 *Time: 7:32 AM
 */

?>
@extends('layouts.app')

@section('template_title')
    Partner Info
@endsection

@section('content')
    <div class="page-title-box">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h4 class="page-title mb-1">Showing {{$partner->partner_name}}</h4>
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{url('/partners')}}">Partners</a></li>
                        <li class="breadcrumb-item active">{{$partner->partner_name}}</li>
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
                <div class="col-xl-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="col-md-12 offset-md-2">
                                <img src="@if ($partner->profile && $partner->profile->avatar_status == 1) {{ $partner->profile->avatar }}
                                @else {{ Gravatar::get($partner->cemail) }}
                                @endif" alt="{{ $partner->name }}" class="img-thumbnail float-left" style=" height: 200px;" data-holder-rendered="true">

                            </div>
                            <br><br><br><br><br><br><br><br><br><br><br>
                            @if (auth()->user()->hasRole('root') OR auth()->user()->hasRole('admin') OR auth()->user()->hasRole('manager'))
                                <div class="text-center mb-4 flex-nowrap">
                                    {!! Form::open(array('url' => 'partners/' . $partner->id)) !!}
                                    {!! Form::hidden('_method', 'DELETE') !!}
                                    {!! Form::button('<i class="fas fa-trash" aria-hidden="true"></i> <span class="hidden-xs hidden-sm hidden-md">Delete Partner</span>' , array('class' => 'btn btn-danger btn-sm','type' => 'button', 'data-toggle' => 'modal', 'data-target' => '#confirmDelete', 'data-title' => 'Delete Partner', 'data-message' => 'Are you sure you want to delete this partner?')) !!}
                                    {!! Form::close() !!}
                                    <br>
                                    <a href="{{url('/partners/'.$partner->id.'/edit')}}" class="btn btn-sm btn-warning" >
                                        <i class="fas fa-user-edit" aria-hidden="true"></i> <span class="hidden-xs hidden-sm hidden-md"> Edit Partner </span>
                                    </a>

                                </div>
                            @endif
							<div class="text-center mb-4 flex-nowrap">
                                <div>
                                {!! Form::open(array('url' => 'approve-merchant/' . $partner->id, 'class' => 'd-inline')) !!}                                
                                {!! Form::button('<a><i class="fas fa-user" aria-hidden="true"></i> Approve Merchant</a>' , array('class' => 'btn btn-sm btn-warning','type' => 'button', 'data-toggle' => 'modal', 'data-target' => '#approveMerchant', 'data-title' => 'Approve Merchant', 'data-message' => 'Are you sure you want to approve this Merchant ?')) !!}
                                {!! Form::close() !!}

                                {!! Form::open(array('url' => 'reject-merchant/' . $partner->id, 'class' => 'd-inline', 'method' => 'post')) !!}
                                {!! Form::button('<a><i class="fas fa-user" aria-hidden="true"></i> Reject Merchant</a>' , array('class' => 'btn btn-danger btn-sm','type' => 'button', 'data-toggle' => 'modal', 'data-target' => '#rejectMerchant', 'data-title' => 'Reject Merchant', 'data-message' => 'Are you sure you want to reject this Merchant ?')) !!}
                                {!! Form::close() !!}
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="col-xl-8">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm-12 col-md-12">
                                    <h4 class="text-muted margin-top-sm-1 text-center text-left-tablet">
                                        {{ $partner->partner_name }}
                                    </h4>
                                    <p class="text-center text-left-tablet">
                                        <strong>
                                            {{ $partner->partner_type }}
                                        </strong>
                                        @if($partner->cemail)
                                            <br />
                                            <span class="text-center" data-toggle="tooltip" data-placement="top" title="Mail to {{$partner->cemail}}">
                                              {{ Html::mailto($partner->cemail, $partner->cemail) }}
                                            </span>
                                        @endif
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
                                            Partner Name
                                        </strong>
                                    </div>

                                    <div class="col-lg-12">
                                        {{ $partner->partner_name }}
                                    </div>

                                    <div class="clearfix"></div>
                                    <div class="border-bottom"></div>
                                </div>
                                <div class="col-lg-6">
                                <div class="col-lg-12 text-larger">
                                    <strong>
                                        Partner Type
                                    </strong>
                                </div>

                                <div class="col-lg-12">
                                    {{ $partner->partner_type }}
                                </div>

                                <div class="clearfix"></div>
                                <div class="border-bottom"></div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-6">

                                @if ($partner->merchantname)

                                <div class="col-lg-12 text-larger">
                                    <strong>
                                        Merchant/Trading Name
                                    </strong>
                                </div>

                                <div class="col-lg-12">
                                    {{ $partner->merchantname }}
                                </div>

                                <div class="clearfix"></div>
                                <div class="border-bottom"></div>

                            @endif
                                </div>
                                <div class="col-lg-6">
                                <div class="col-lg-12 text-larger">
                                    <strong>
                                        Type of Business
                                    </strong>
                                </div>

                                <div class="col-lg-12">
                                    {{ $partner->business_type }}
                                </div>

                                <div class="clearfix"></div>
                                <div class="border-bottom"></div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-6">
                                <div class="col-lg-12 text-larger">
                                    <strong>
                                        Nature of Business
                                    </strong>
                                </div>

                                <div class="col-lg-12">
                                    {{ $partner->partnerDesc }}
                                </div>

                                <div class="clearfix"></div>
                                <div class="border-bottom"></div>
                                </div>
                                <div class="col-lg-6">
                                <div class="col-lg-12 text-larger">
                                    <strong>
                                        Years Trading
                                    </strong>
                                </div>

                                <div class="col-lg-12">
                                    {{ $partner->yearsTrading }}
                                </div>

                                <div class="clearfix"></div>
                                <div class="border-bottom"></div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="col-lg-12 text-larger">
                                        <strong>
                                            Reg Number
                                        </strong>
                                    </div>

                                    <div class="col-lg-12">
                                        {{ $partner->regNumber ?? 'Agent with no Company Reg Number'}}
                                    </div>

                                    <div class="clearfix"></div>
                                    <div class="border-bottom"></div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="col-lg-12 text-larger">
                                        <strong>
                                            TPIN
                                        </strong>
                                    </div>

                                    <div class="col-lg-12">
                                        {{ $partner->bpNumber ?? 'Agent with no TPIN'}}
                                    </div>

                                    <div class="clearfix"></div>
                                    <div class="border-bottom"></div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="col-lg-12 text-larger">
                                        <strong>
                                            Address
                                        </strong>
                                    </div>

                                    <div class="col-lg-12">
                                        {{ $partner->propNumber.' '.$partner->street.', '.$partner->suburb.', '.$partner->city.', '.$partner->country}}
                                    </div>

                                    <div class="clearfix"></div>
                                    <div class="border-bottom"></div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="col-lg-12 text-larger">
                                        <strong>
                                            Province
                                        </strong>
                                    </div>

                                    <div class="col-lg-12">
                                        {{ $partner->province}}
                                    </div>

                                    <div class="clearfix"></div>
                                    <div class="border-bottom"></div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="col-lg-12 text-larger">
                                        <strong>
                                            Contact
                                        </strong>
                                    </div>

                                    <div class="col-lg-12">
                                        {{ $partner->cfullname}}
                                    </div>

                                    <div class="clearfix"></div>
                                    <div class="border-bottom"></div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="col-lg-12 text-larger">
                                        <strong>
                                            Contact Designation
                                        </strong>
                                    </div>

                                    <div class="col-lg-12">
                                        {{ $partner->cdesignation}}
                                    </div>

                                    <div class="clearfix"></div>
                                    <div class="border-bottom"></div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="col-lg-12 text-larger">
                                        <strong>
                                            Telephone Number
                                        </strong>
                                    </div>

                                    <div class="col-lg-12">
                                        {{ $partner->telephoneNo}}
                                    </div>

                                    <div class="clearfix"></div>
                                    <div class="border-bottom"></div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="col-lg-12 text-larger">
                                        <strong>
                                            Contact Email
                                        </strong>
                                    </div>

                                    <div class="col-lg-12">
                                        {{ $partner->cemail}}
                                    </div>

                                    <div class="clearfix"></div>
                                    <div class="border-bottom"></div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="col-lg-12 text-larger">
                                        <strong>
                                            Bank
                                        </strong>
                                    </div>

                                    <div class="col-lg-12">
                                        {{ $partner->bank}}
                                    </div>

                                    <div class="clearfix"></div>
                                    <div class="border-bottom"></div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="col-lg-12 text-larger">
                                        <strong>
                                            Bank Branch
                                        </strong>
                                    </div>

                                    <div class="col-lg-12">
                                        {{ $partner->branch}}
                                    </div>

                                    <div class="clearfix"></div>
                                    <div class="border-bottom"></div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="col-lg-12 text-larger">
                                        <strong>
                                            Branch Code
                                        </strong>
                                    </div>

                                    <div class="col-lg-12">
                                        {{ $partner->branch_code}}
                                    </div>

                                    <div class="clearfix"></div>
                                    <div class="border-bottom"></div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="col-lg-12 text-larger">
                                        <strong>
                                            Account Number
                                        </strong>
                                    </div>

                                    <div class="col-lg-12">
                                        {{ $partner->acc_number}}
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
                                    {{ $partner->created_at }}
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
                                    {{ $partner->updated_at }}
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
    @include('modals.modal-delete')
	@include('modals.modal-loanofficer-merchant-approve')
    @include('modals.modal-loanofficer-merchant-reject')
@endsection

@section('footer_scripts')
    @include('scripts.delete-modal-script')
	@include('scripts.loanofficer-merchant-approve-modal-script')
    @include('scripts.loanofficer-merchant-reject-modal-script')
    @if(config('usersmanagement.tooltipsEnabled'))
        @include('scripts.tooltips')
    @endif
@endsection
