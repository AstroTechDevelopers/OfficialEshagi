<?php
/**
 *Created by PhpStorm for eshagi
 *User: Vincent Guyo
 *Date: 11/8/2020
 *Time: 8:53 PM
 */

?>
@extends('layouts.app')

@section('template_title')
    Product Details
@endsection

@section('content')
    <div class="page-title-box">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h4 class="page-title mb-1">Product Details</h4>
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{url('/our-products')}}">Product</a></li>
                        <li class="breadcrumb-item active">Product ID: {{$product->id}}</li>
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
                            <div class="card-header">
                                Product Details
                            </div>
                            <br>
                            @if ($product->creator)

                                <div class="col-sm-5 col-6 text-larger">
                                    <strong>
                                        Owner:
                                    </strong>

                                    {{$product->creator}}
                                </div>
                                <div class="clearfix"></div>
                                <div class="border-bottom"></div>
                                <br>
                            @endif


                            @if ($product->pcode)

                                <div class="col-sm-5 col-6 text-larger">
                                    <strong>
                                        Product Code:
                                    </strong>

                                    {{$product->pcode}}
                                </div>
                                <div class="clearfix"></div>
                                <div class="border-bottom"></div>
                                <br>
                            @endif


                            @if ($product->serial)

                                <div class="col-sm-5 col-6 text-larger">
                                    <strong>
                                        Serial Number:
                                    </strong>

                                    {{$product->serial}}
                                </div>
                                <div class="clearfix"></div>
                                <div class="border-bottom"></div>
                                <br>
                            @endif

                            @if ($product->pname)

                                <div class="col-sm-5 col-6 text-larger">
                                    <strong>
                                        Product Name:
                                    </strong>

                                    {{$product->pname}}
                                </div>
                                <div class="clearfix"></div>
                                <div class="border-bottom"></div>
                                <br>
                            @endif

                            @if ($product->model)

                                <div class="col-sm-5 col-6 text-larger">
                                    <strong>
                                        Model:
                                    </strong>

                                    {{$product->model}}
                                </div>
                                <div class="clearfix"></div>
                                <div class="border-bottom"></div>
                                <br>
                            @endif

                            @if ($product->descrip)

                                <div class="col-sm-5 col-6 text-larger">
                                    <strong>
                                        Description:
                                    </strong>

                                    {{$product->descrip}}
                                </div>
                                <div class="clearfix"></div>
                                <div class="border-bottom"></div>
                                <br>
                            @endif

                            @if ($product->price)

                                <div class="col-sm-5 col-6 text-larger">
                                    <strong>
                                        Price:
                                    </strong>

                                    ${{$product->price}}
                                </div>
                                <div class="clearfix"></div>
                                <div class="border-bottom"></div>
                                <br>
                            @endif
{{-- 
                            @if ($product->usd_price)

                                <div class="col-sm-5 col-6 text-larger">
                                    <strong>
                                        USD Price:
                                    </strong>

                                    USD ${{$product->usd_price}}
                                </div>
                                <div class="clearfix"></div>
                                <div class="border-bottom"></div>
                                <br>
                            @endif --}}

                            @if ($product->created_at)

                                <div class="col-sm-5 col-6 text-larger">
                                    <strong>
                                        Created On:
                                    </strong>

                                    {{$product->created_at}}
                                </div>
                                <div class="clearfix"></div>
                                <div class="border-bottom"></div>
                                <br>
                            @endif


                            @if ($product->updated_at)

                                <div class="col-sm-5 col-6 text-larger">
                                    <strong>
                                        Last Modified On:
                                    </strong>

                                    {{$product->updated_at}}
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
@endsection
