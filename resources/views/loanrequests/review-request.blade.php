<?php
/**
 * Created by PhpStorm for eshagitwo
 * User: VinceGee
 * Date: 11/15/2021
 * Time: 12:38 AM
 */ ?>
@extends('layouts.app')

@section('template_title')
    Review Loan Request ID: {{$loanRequest->id}}
@endsection

@section('content')
    <div class="page-title-box">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h4 class="page-title mb-1">Loan Requests</h4>
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Loan Requests</a></li>
                        <li class="breadcrumb-item active">Review Loan Request ID: {{$loanRequest->id}}</li>
                    </ol>
                </div>

                <div class="col-md-6">
                    <div class="float-right d-none d-md-block">
                        @if ($loanRequest->approver == null)
                        {!! Form::open(array('route' => ['approve.loanrequest',$loanRequest->id], 'method' => 'GET', 'role' => 'form', 'class' => 'd-inline')) !!}

                        {!! csrf_field() !!}
                        <div class="float-right d-none d-md-block">
                            <div>
                                <button class="btn btn-success btn-rounded" href="" type="button" data-toggle="modal" data-target="#confirmForm" data-title="Approve Request" data-message="Are you sure you want to approve this Loan Request?">
                                    <i class="mdi mdi-check-underline mr-1"></i>Approve Request
                                </button>
                            </div>
                        </div>
                        {!! Form::close() !!}
                        @endif
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
                            <ul class="nav nav-pills nav-justified" role="tablist">
                                <li class="nav-item waves-effect waves-light">
                                    <a class="nav-link active" data-toggle="tab" href="#loan-request-info" role="tab">
                                        <i class="fas fa-search-dollar mr-1"></i> <span class="d-none d-md-inline-block">Loan Request</span>
                                    </a>
                                </li>
                                <li class="nav-item waves-effect waves-light">
                                    <a class="nav-link" data-toggle="tab" href="#loan-info" role="tab">
                                        <i class="fas fa-dollar-sign mr-1"></i> <span class="d-none d-md-inline-block">Loan Details</span>
                                    </a>
                                </li>
                                <li class="nav-item waves-effect waves-light">
                                    <a class="nav-link" data-toggle="tab" href="#approve-state" role="tab">
                                        <i class="fas fa-stamp mr-1"></i> <span class="d-none d-md-inline-block">Approval Status</span>
                                    </a>
                                </li>
                            </ul>

                            <!-- Tab panes -->
                            <div class="tab-content p-3">
                                <div class="tab-pane active" id="loan-request-info" role="tabpanel">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <label for="loan_id">Loan ID</label>
                                            <input type="text" class="form-control" id="loan_id" name="loan_id" placeholder="e.g. 15" value="{{$loanRequest->loan_id}}" required>
                                        </div>
                                        <div class="col-sm-6">
                                            <label for="level">Request Level</label>
                                            <input type="text" class="form-control" id="level" name="level" placeholder="e.g. 1" value="{{$loanRequest->level}}" required>
                                        </div>
                                    </div>
                                    <br>
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <label for="requestor">Requested By</label>
                                            <input type="text" class="form-control" id="requestor" name="requestor" placeholder="e.g. vguyo" value="{{$loanRequest->requestor}}" required>
                                        </div>
                                        <div class="col-sm-6">
                                            <label for="request">Request Type</label>
                                            <input type="text" class="form-control" id="request" name="request" placeholder="e.g. 1" value="{{$loanRequest->request}}" required>
                                        </div>
                                    </div>
                                    <br>
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <label for="explanation">Reason For Request</label>
                                            <textarea name="explanation" id="explanation" cols="30" rows="10" style="width: 100%;">{!! $loanRequest->explanation !!}</textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane" id="loan-info" role="tabpanel">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <label for="loan_number">Loan Number</label>
                                            <input type="text" class="form-control" id="loan_number" name="loan_number" placeholder="e.g. 15" value="{{$loan->loan_number}}" required>
                                        </div>
                                        <div class="col-sm-6">
                                            <label for="loan_type">Loan Type</label>
                                            <input type="text" class="form-control" id="loan_type" name="loan_type" placeholder="e.g. Cash" value="{{getLoantype($loan->loan_type)}}" required>
                                        </div>
                                    </div>
                                    <br>
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <label for="client">Client</label>
                                            <input type="text" class="form-control" id="client" name="client" placeholder="e.g. John Doe" value="{{$loan->user->first_name.' '.$loan->user->last_name}}" required>
                                        </div>
                                        <div class="col-sm-6">
                                            <label for="natid">Client National ID</label>
                                            <input type="text" class="form-control" id="natid" name="natid" placeholder="e.g. 63-1234567-F-71" value="{{$loan->user->natid}}" required>
                                        </div>
                                    </div>
                                    <br>
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <label for="loan_status">Loan Status</label>
                                            <input type="text" class="form-control" id="loan_status" name="loan_status" placeholder="e.g. New" value="{{getLoanstatus($loan->loan_status)}}" required>
                                        </div>
                                        <div class="col-sm-6">
                                            <label for="amount">Loan Amount</label>
                                            <input type="text" class="form-control" id="amount" name="amount" placeholder="e.g. 6371.00" value="{{$loan->amount}}" required>
                                        </div>
                                    </div>
                                    <br>
                                </div>
                                <div class="tab-pane" id="approve-state" role="tabpanel">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <label for="approver">Approver</label>
                                            <input type="text" class="form-control" id="approver" name="approver" placeholder="e.g. vguyo" value="{{$loanRequest->approver}}" required>
                                        </div>
                                        <div class="col-sm-6">
                                            <label for="approved_at">Approved At</label>
                                            <input type="text" class="form-control" id="approved_at" name="approved_at" placeholder="e.g. 31-01-2021 14:00:00" value="{{$loanRequest->approved_at}}" required>
                                        </div>
                                    </div>
                                    <br>
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <label for="comment">Comment</label>
                                            <textarea name="comment" id="comment" cols="30" rows="10" style="width: 100%;">{!! $loanRequest->comment !!}</textarea>
                                        </div>
                                    </div>
                                    <br>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('modals.modal-form')
@endsection

@section('footer_scripts')
    @include('scripts.form-modal-script')
    <!--tinymce js-->
    <script src="{{asset('assets/libs/tinymce/tinymce.min.js')}}"></script>

    <script>
        $(document).ready(
            function(){
                $("#explanation").length&&tinymce.init({
                    selector:"textarea#explanation",
                    height:300,
                    width:'100%',
                    plugins:["advlist autolink link image lists charmap print preview hr anchor pagebreak spellchecker","searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking","save table directionality emoticons template paste"],
                    toolbar:"insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | l      ink image | print preview media fullpage | forecolor backcolor emoticons",
                    style_formats:[
                        {title:"Bold text",inline:"b"},
                        {title:"Red text",inline:"span",styles:{color:"#ff0000"}},
                        {title:"Red header",block:"h1",styles:{color:"#ff0000"}},
                        {title:"Example 1",inline:"span",classes:"example1"},
                        {title:"Example 2",inline:"span",classes:"example2"},
                        {title:"Table styles"},
                        {title:"Table row 1",selector:"tr",classes:"tablerow1"}
                    ]
                });

                $("#comment").length&&tinymce.init({
                    selector:"textarea#comment",
                    height:300,
                    width:'100%',
                    plugins:["advlist autolink link image lists charmap print preview hr anchor pagebreak spellchecker","searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking","save table directionality emoticons template paste"],
                    toolbar:"insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | l      ink image | print preview media fullpage | forecolor backcolor emoticons",
                    style_formats:[
                        {title:"Bold text",inline:"b"},
                        {title:"Red text",inline:"span",styles:{color:"#ff0000"}},
                        {title:"Red header",block:"h1",styles:{color:"#ff0000"}},
                        {title:"Example 1",inline:"span",classes:"example1"},
                        {title:"Example 2",inline:"span",classes:"example2"},
                        {title:"Table styles"},
                        {title:"Table row 1",selector:"tr",classes:"tablerow1"}
                    ]
                });
            });
    </script>

@endsection
