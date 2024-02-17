<?php
/**
 * Created by PhpStorm for eshagi
 * User: vinceg
 * Date: 22/12/2020
 * Time: 06:26
 */
?>
@extends('layouts.app')

@section('template_title')
    Welcome {{ Auth::user()->name }}
@endsection

@section('content')
    <div class="page-title-box">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h4 class="page-title mb-1">Hello, {{auth()->user()->first_name}}</h4>
                </div>

                <div class="col-md-4">
                    <div class="float-right d-none d-md-block">
                    </div>
                </div>
            </div>

        </div>
    </div>

    <div class="page-content-wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-xl-4">
                    <div class="card">
                        <div class="card-body">
                            <h3>{{$kycsPending}}</h3>
                            <h5 class="header-title mb-4">Pending KYCs</h5>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4">
                    <div class="card">
                        <div class="card-body">
                            <h3>{{$kycsCompleted}}</h3>
                            <h5 class="header-title mb-4">Completed KYCs</h5>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4">
                    <div class="card">
                        <div class="card-body">
                            <h3>{{$kycsRejected}}</h3>
                            <h5 class="header-title mb-4">Rejected KYCs</h5>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
@section('footer_scripts')

@endsection
