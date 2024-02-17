<?php
/**
 * Created by PhpStorm for eshagitwo
 * User: vinceg
 * Date: 23/8/2021
 * Time: 02:54
 */
?>
@extends('layouts.app')

@section('template_title')
     {{$ledger->shortname}} Ledger Info
@endsection

@section('template_linked_css')
    <link href="{{asset('assets/libs/magnific-popup/magnific-popup.css')}}" rel="stylesheet" type="text/css" />
    <!-- DataTables -->
    <link href="{{asset('assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('assets/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css')}}" rel="stylesheet" type="text/css" />

    <!-- Responsive datatable examples -->
    <link href="{{asset('assets/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css')}}" rel="stylesheet" type="text/css" />

@endsection

@section('content')
    <div class="page-title-box">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-md-4">
                    <h4 class="page-title mb-1">Ledger</h4>
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{url('/ledgers')}}">Ledger</a></li>
                        <li class="breadcrumb-item active">Ledger Info: {{$ledger->shortname}}</li>
                    </ol>
                </div>

                <div class="col-md-8">
                    <div class="float-right d-none d-md-block">
                        <div class="float-right d-none d-md-block">
                            <div>

                            </div>
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
                            <h4 class="header-title">Ledger ID: {{$ledger->id}}</h4>
                            <p class="card-title-desc">Ledger Last Update: {{$ledger->updated_at}}<br>
                            <span class="text-success">Ledger Added By: {{$ledger->creator}}</span></p>

                            <ul class="nav nav-tabs nav-justified nav-tabs-custom" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" data-toggle="tab" href="#general-details" role="tab">
                                        <i class="fas fa-comments-dollar mr-1 align-middle"></i> <span class="d-none d-md-inline-block">General</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#ledger-accounts" role="tab">
                                        <i class="fas fa-clone mr-1 align-middle"></i> <span class="d-none d-md-inline-block">Accounts</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#ledger-activity" role="tab">
                                        <i class="fas fa-clipboard-list mr-1 align-middle"></i> <span class="d-none d-md-inline-block">Recent Activity</span>
                                    </a>
                                </li>
                            </ul>

                            <div class="tab-content p-3">
                                <div class="tab-pane active" id="general-details" role="tabpanel">
                                    <div class="row">
                                        <div class="col-md-4 mb-3">
                                            <label for="locale_id">Locale</label>
                                            <input type="text" class="form-control" id="locale_id" placeholder="e.g. Zimbabwe" value="{{$locale->country}}" required>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label for="short_name">Short name</label>
                                            <input type="text" class="form-control" id="short_name" placeholder="e.g. ecocash" value="{{$ledger->shortname}}" required>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label for="currency">Currency</label>
                                            <input type="text" class="form-control" id="currency" placeholder="e.g. ZWL" value="{{$ledger->currency}}" required>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4 mb-3">
                                            <label for="ledger">Ledger Name</label>
                                            <input type="text" class="form-control" id="ledger" placeholder="e.g. EcoCash" value="{{$ledger->ledger}}" required>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label for="balance">Balance</label>
                                            <input type="text" class="form-control" id="balance" placeholder="e.g. 12457.17" value="{{$ledger->balance}}" required>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label for="active">Active</label>
                                            <input type="text" class="form-control" id="active" placeholder="e.g. Yes" value="@if($ledger->active) Yes @else No @endif" required>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12 mb-3">
                                            <label for="notes">Notes</label>
                                            <textarea style="width:100%;" name="notes" id="notes" cols="30" rows="10">{{$ledger->notes}}</textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane" id="ledger-accounts" role="tabpanel">
                                    <div class="row">
                                        <div class="col-md-12 mb-3">
                                            <table id="datatable-buttons" class="table table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                                <thead>
                                                <tr>
                                                    <th>Shortname</th>
                                                    <th>Currency</th>
                                                    <th>Ledger</th>
                                                    <th>Balance</th>
                                                    <th>Is Active</th>
                                                </tr>
                                                </thead>

                                                <tbody>
                                                @foreach($ledger->account as $account)
                                                    <tr>
                                                        <td>{{$account->shortname}}</td>
                                                        <td>{{$account->currency}}</td>
                                                        <td>{{$account->ledger}}</td>
                                                        <td>{{$account->balance}}</td>
                                                        <td>@if($account->active) Yes @else No @endif</td>
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                            </table>

                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane" id="ledger-activity" role="tabpanel">
                                    <div class="row">
                                        <div class="col-md-12 mb-3">
                                            <table id="datatable-buttons2" class="table table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                                <thead>
                                                <tr>
                                                    <th>Shortname</th>
                                                    <th>Currency</th>
                                                    <th>Ledger</th>
                                                    <th>Balance</th>
                                                    <th>Is Active</th>
                                                </tr>
                                                </thead>

                                                <tbody>
                                                @foreach($recently as $activity)
                                                    <tr>
                                                        <td>{{$activity->shortname}}</td>
                                                        <td>{{$activity->currency}}</td>
                                                        <td>{{$activity->ledger}}</td>
                                                        <td>{{$activity->balance}}</td>
                                                        <td>@if($activity->active) Yes @else No @endif</td>
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                            </table>

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
@endsection

@section('footer_scripts')
    <script src="{{asset('assets/libs/magnific-popup/jquery.magnific-popup.min.js')}}"></script>
    <script src="{{asset('assets/js/pages/lightbox.init.js')}}"></script>

    <script src="{{asset('assets/libs/datatables.net/js/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js')}}"></script>
    <!-- Buttons examples -->
    <script src="{{asset('assets/libs/datatables.net-buttons/js/dataTables.buttons.min.js')}}"></script>
    <script src="{{asset('assets/libs/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js')}}"></script>
    <script src="{{asset('assets/libs/jszip/jszip.min.js')}}"></script>
    <script src="{{asset('assets/libs/pdfmake/build/pdfmake.min.js')}}"></script>
    <script src="{{asset('assets/libs/pdfmake/build/vfs_fonts.js')}}"></script>
    <script src="{{asset('assets/libs/datatables.net-buttons/js/buttons.html5.min.js')}}"></script>
    <script src="{{asset('assets/libs/datatables.net-buttons/js/buttons.print.min.js')}}"></script>
    <script src="{{asset('assets/libs/datatables.net-buttons/js/buttons.colVis.min.js')}}"></script>
    <!-- Responsive examples -->
    <script src="{{asset('assets/libs/datatables.net-responsive/js/dataTables.responsive.min.js')}}"></script>
    <script src="{{asset('assets/libs/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js')}}"></script>

    <!-- Datatable init js -->
    <script src="{{asset('assets/js/pages/datatables.init.js')}}"></script>
@endsection
