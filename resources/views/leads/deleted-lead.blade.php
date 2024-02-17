<?php
/**
 * Created by PhpStorm for eshagi
 * User: vinceg
 * Date: 17/2/2021
 * Time: 07:31
 */
?>
@extends('layouts.app')

@section('template_title')
    View Deleted Sales Lead
@endsection

@section('content')
    <div class="page-title-box">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h4 class="page-title mb-1">Deleted Sales Lead {{$lead->id}}</h4>
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{url('/leads')}}">Leads</a></li>
                        <li class="breadcrumb-item active">{{$lead->natid}}</li>
                    </ol>
                </div>

                <div class="col-md-4">
                    <div class="float-right d-none d-md-block">
                        <div>
                            <a class="btn btn-light btn-rounded" href="{{url('/leads/deleted')}}" type="button">
                                <i class="mdi mdi-keyboard-backspace mr-1"></i>Back to deleted leads
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
                <div class="col-xl-8">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="header-title">Lead Details</h4>
                            <p class="card-title-desc">Deleted sales lead details, deleted on: <span class="text-danger">{{$lead->deleted_at}}</span></p>

                            <!-- Nav tabs -->
                            <ul class="nav nav-pills" role="tablist">
                                <li class="nav-item waves-effect waves-light">
                                    <a class="nav-link active" data-toggle="tab" href="#personal" role="tab">
                                        <i class="fas fa-home mr-1"></i> <span class="d-none d-md-inline-block">Personal</span>
                                    </a>
                                </li>
                                <li class="nav-item waves-effect waves-light">
                                    <a class="nav-link" data-toggle="tab" href="#lead" role="tab">
                                        <i class="fas fa-user mr-1"></i> <span class="d-none d-md-inline-block">Lead Details</span>
                                    </a>
                                </li>
                            </ul>

                            <!-- Tab panes -->
                            <div class="tab-content p-3">
                                <div class="tab-pane active" id="personal" role="tabpanel">
                                    <table class="table table-striped mb-0">
                                        <tr>
                                            <td>Username</td>
                                            <td><strong>{{$lead->name}}</strong></td>
                                            <td>Email</td>
                                            <td><strong>{{$lead->email}}</strong></td>
                                        </tr>
                                        <tr>
                                            <td>First Name</td>
                                            <td><strong>{{$lead->first_name}}</strong></td>
                                            <td>Last Name</td>
                                            <td><strong>{{$lead->last_name}}</strong></td>
                                        </tr>
                                        <tr>
                                            <td>National ID</td>
                                            <td><strong>{{$lead->natid}}</strong></td>
                                            <td>Mobile Number</td>
                                            <td><strong>{{$lead->mobile}}</strong></td>
                                        </tr>
                                        <tr>
                                            <td>EC Number</td>
                                            <td><strong>{{$lead->ecnumber}}</strong></td>
                                            <td>Address</td>
                                            <td><strong>{{$lead->address}}</strong></td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="tab-pane" id="lead" role="tabpanel">
                                    <table class="table table-striped mb-0">
                                        <tr>
                                            <td>Was this Lead Contacted?</td>
                                            <td><strong>@if($lead->isContacted) Yes @else No @endif</strong></td>
                                            <td>Was this Lead Converted to a Loan?</td>
                                            <td><strong>@if($lead->isSale) Yes @else No @endif</strong></td>
                                        </tr>
                                        <tr>
                                            <td>Was this Lead Contacted via SMS?</td>
                                            <td><strong>@if($lead->isSMSed) Yes @else No @endif</strong></td>

                                        </tr>
                                        <tr>
                                            <td>Agent</td>
                                            <td><strong>{{$lead->agent}}</strong></td>
                                            <td>Assigned to Agent on</td>
                                            <td><strong>{{$lead->assignedOn}}</strong></td>
                                        </tr>
                                        <tr>
                                            <td>When was the Lead Converted?</td>
                                            <td><strong>{{$lead->completedOn ?? 'Not yet converted to a loan'}}</strong></td>
                                            <td>Lead Created On</td>
                                            <td><strong>{{$lead->created_at}}</strong></td>
                                        </tr>
                                        <tr>
                                            <td>Notes</td>
                                            <td><strong>{{$lead->notes}}</strong></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="col-xl-4">
                    <div class="card">
                        <div class="card-body">

                            <h4 class="header-title">Call History</h4>
                            <p class="card-title-desc">Summary of calls made regarding this lead</p>

                            <div id="accordion">
                                <div class="card mb-0">
                                    <div class="card-header" id="headingOne">
                                        <h5 class="m-0 font-size-14">
                                            <a data-toggle="collapse" data-parent="#accordion"
                                               href="#collapseOne" aria-expanded="true"
                                               aria-controls="collapseOne" class="text-dark">
                                                Collapsible Group Item #1
                                            </a>
                                        </h5>
                                    </div>

                                    <div id="collapseOne" class="collapse show"
                                         aria-labelledby="headingOne" data-parent="#accordion">
                                        <div class="card-body">
                                            Anim pariatur cliche reprehenderit, enim eiusmod high life
                                            accusamus terry richardson ad squid. 3 wolf moon officia
                                            aute, non cupidatat skateboard dolor brunch. Food truck
                                            quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor,
                                            sunt aliqua put a bird on it squid single-origin coffee
                                            nulla assumenda shoreditch et. Nihil anim keffiyeh
                                            helvetica, craft beer labore wes anderson cred nesciunt
                                            sapiente ea proident. Ad vegan excepteur butcher vice lomo.
                                            Leggings occaecat craft beer farm-to-table, raw denim
                                            aesthetic synth nesciunt you probably haven't heard of them
                                            accusamus labore sustainable VHS.
                                        </div>
                                    </div>
                                </div>
                                <div class="card mb-0">
                                    <div class="card-header" id="headingTwo">
                                        <h5 class="m-0 font-size-14">
                                            <a class="collapsed text-dark" data-toggle="collapse"
                                               data-parent="#accordion" href="#collapseTwo"
                                               aria-expanded="false" aria-controls="collapseTwo">
                                                Collapsible Group Item #2
                                            </a>
                                        </h5>
                                    </div>
                                    <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion">
                                        <div class="card-body">
                                            Anim pariatur cliche reprehenderit, enim eiusmod high life
                                            accusamus terry richardson ad squid. 3 wolf moon officia
                                            aute, non cupidatat skateboard dolor brunch. Food truck
                                            quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor,
                                            sunt aliqua put a bird on it squid single-origin coffee
                                            nulla assumenda shoreditch et. Nihil anim keffiyeh
                                            helvetica, craft beer labore wes anderson cred nesciunt
                                            sapiente ea proident. Ad vegan excepteur butcher vice lomo.
                                            Leggings occaecat craft beer farm-to-table, raw denim
                                            aesthetic synth nesciunt you probably haven't heard of them
                                            accusamus labore sustainable VHS.
                                        </div>
                                    </div>
                                </div>
                                <div class="card mb-0">
                                    <div class="card-header" id="headingThree">
                                        <h5 class="m-0 font-size-14">
                                            <a class="collapsed text-dark" data-toggle="collapse"
                                               data-parent="#accordion" href="#collapseThree"
                                               aria-expanded="false" aria-controls="collapseThree">
                                                Collapsible Group Item #3
                                            </a>
                                        </h5>
                                    </div>
                                    <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordion">
                                        <div class="card-body">
                                            Anim pariatur cliche reprehenderit, enim eiusmod high life
                                            accusamus terry richardson ad squid. 3 wolf moon officia
                                            aute, non cupidatat skateboard dolor brunch. Food truck
                                            quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor,
                                            sunt aliqua put a bird on it squid single-origin coffee
                                            nulla assumenda shoreditch et. Nihil anim keffiyeh
                                            helvetica, craft beer labore wes anderson cred nesciunt
                                            sapiente ea proident. Ad vegan excepteur butcher vice lomo.
                                            Leggings occaecat craft beer farm-to-table, raw denim
                                            aesthetic synth nesciunt you probably haven't heard of them
                                            accusamus labore sustainable VHS.
                                        </div>
                                    </div>
                                </div>
                            </div>

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
