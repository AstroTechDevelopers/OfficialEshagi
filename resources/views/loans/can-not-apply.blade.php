<?php
/**
 *Created by PhpStorm for eshagi
 *User: Vincent Guyo
 *Date: 9/25/2020
 *Time: 3:53 PM
 */

?>
@extends('layouts.app')

@section('template_title')
    Apply For A Loan
@endsection

@section('template_linked_css')

    <link href="{{ asset('css/select2.min.css')}}" rel="stylesheet" />
@endsection

@section('content')
    <div class="page-title-box">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-md-4">
                    <h4 class="page-title mb-1">Loan</h4>
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{url('/myloans')}}">Loan</a></li>
                        <li class="breadcrumb-item active">Apply For Loan</li>
                    </ol>
                </div>

                <div class="col-md-8">
                    <div class="float-right d-none d-md-block">
                        <div>
                            <h1 class="text-white">Credit limit : {{getLocaleInfo()->currency_code.' '.$user->cred_limit}}</h1>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- end page title end breadcrumb -->
    <div class="page-content-wrapper">
        <div class="container-fluid">
		    <br /><br /><br />
            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-body">
							<div class="row">
							   <p>Please note that you are not allowed to apply for a new loan at this time as you have a ongoing loan or have a previous loan application in progress.</p>
							</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('footer_scripts')
@endsection
