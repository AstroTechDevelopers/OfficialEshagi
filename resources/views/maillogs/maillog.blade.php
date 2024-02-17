<?php
/**
 *Created by PhpStorm for eshagi
 *User: Vincent Guyo
 *Date: 10/19/2020
 *Time: 12:24 PM
 */

?>
@extends('layouts.app')

@section('template_title')
    Log Details
@endsection

@section('content')
    <div class="page-title-box">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h4 class="page-title mb-1">Log Details</h4>
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{url('/maillogs')}}">Log</a></li>
                        <li class="breadcrumb-item active">Log ID: {{$emailLog->id}}</li>
                    </ol>
                </div>

                <div class="col-md-4">
                    <div class="float-right d-none d-md-block">
                        <div>
                            <a class="btn btn-light btn-rounded" href="{{url('/maillogs')}}" type="button">
                                <i class="mdi mdi-keyboard-backspace mr-1"></i>Back to logs
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
                            <div class="card-header">
                                Email Details
                            </div>
                            <br>
                            @if ($emailLog->date)

                                <div class="col-sm-5 col-6 text-larger">
                                    <strong>
                                        Date:
                                    </strong>

                                    {{$client->date}}
                                </div>
                                <div class="clearfix"></div>
                                <div class="border-bottom"></div>
                                <br>
                            @endif


                            @if ($emailLog->from)

                                <div class="col-sm-5 col-6 text-larger">
                                    <strong>
                                        From:
                                    </strong>

                                    {{$partner->from}}
                                </div>
                                <div class="clearfix"></div>
                                <div class="border-bottom"></div>
                                <br>
                            @endif


                            @if ($emailLog->to)

                                <div class="col-sm-5 col-6 text-larger">
                                    <strong>
                                        To:
                                    </strong>

                                    {{$emailLog->to}}
                                </div>
                                <div class="clearfix"></div>
                                <div class="border-bottom"></div>
                                <br>
                            @endif

                            @if ($emailLog->cc)

                                <div class="col-sm-5 col-6 text-larger">
                                    <strong>
                                        Copied in the Email (CC):
                                    </strong>

                                    {{$emailLog->cc}}
                                </div>
                                <div class="clearfix"></div>
                                <div class="border-bottom"></div>
                                <br>
                            @endif

                            @if ($emailLog->bcc)

                                <div class="col-sm-5 col-6 text-larger">
                                    <strong>
                                        Blind Copied in the Email (BCC):
                                    </strong>

                                    {{$emailLog->bcc}}
                                </div>
                                <div class="clearfix"></div>
                                <div class="border-bottom"></div>
                                <br>
                            @endif

                            @if ($emailLog->subject)

                                <div class="col-sm-5 col-6 text-larger">
                                    <strong>
                                        Subject:
                                    </strong>

                                    {{$emailLog->subject}}
                                </div>
                                <div class="clearfix"></div>
                                <div class="border-bottom"></div>
                                <br>
                            @endif

                            @if ($emailLog->body)

                                <div class="col-sm-5 col-6 text-larger">
                                    <strong>
                                        Body:
                                    </strong>

                                    ${{$emailLog->body}}
                                </div>
                                <div class="clearfix"></div>
                                <div class="border-bottom"></div>
                                <br>
                            @endif

                            @if ($emailLog->headers)

                                <div class="col-sm-5 col-6 text-larger">
                                    <strong>
                                        Headers:
                                    </strong>

                                    {{$emailLog->headers}}
                                </div>
                                <div class="clearfix"></div>
                                <div class="border-bottom"></div>
                                <br>
                            @endif

                            @if ($emailLog->attachments)

                                <div class="col-sm-5 col-6 text-larger">
                                    <strong>
                                        Attachments:
                                    </strong>

                                    {{$emailLog->attachments}}
                                </div>
                                <div class="clearfix"></div>
                                <div class="border-bottom"></div>
                                <br>
                            @endif

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
