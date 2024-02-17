<?php
/**
 * Created by PhpStorm for eshagi
 * User: vinceg
 * Date: 19/2/2021
 * Time: 00:35
 */
?>
@extends('layouts.app')

@section('template_title')
    Call Centre Weekly Report
@endsection

@section('content')
    <div class="page-title-box">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h4 class="page-title mb-1">Call Centre Weekly Reports</h4>
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Reports</a></li>
                        <li class="breadcrumb-item active">Call Centre Weekly</li>
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
                            <h4 class="header-title">Weekly Report For {{date(' F Y')}}</h4>
                            <ul class="nav nav-pills" role="tablist">
                                @foreach($output->keys() as $index => $week)
                                    <li class="nav-item waves-effect waves-light">
                                        <a class="nav-link @if ($index == 0) active @elseif($index == $week) active @endif" data-toggle="tab" href="#week{{$week}}" role="tab">
                                             <span class="d-none d-md-inline-block">Week {{$week}}</span>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>

                            <!-- Tab panes -->
                            <div class="tab-content p-3">
                                @foreach($output as $index => $week)
                                <div class="tab-pane @if ($week->loop->index == 0) active @elseif($week->loop->index == $week) active @endif" id="week{{$index}}" role="tabpanel">
                                    @foreach($week as $item)
                                        <p class="mb-0">
                                            {{$item->Count.' loans for '.$item->Total.' - '.$item->creator .' on '.$item->Date. date(' F')}}
                                        </p>
                                    @endforeach
                                </div>
                                @endforeach
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
