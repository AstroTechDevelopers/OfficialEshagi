<?php
/**
 * Created by PhpStorm for eshagitwo
 * User: vinceg
 * Date: 21/8/2021
 * Time: 16:52
 */
?>
@extends('layouts.app')

@section('template_title')
    eLoan Amortization Schedule
@endsection

@section('template_linked_css')

    <link href="{{ asset('css/apexcharts.css')}}" rel="stylesheet" />
    <!-- datepicker -->
    <link href="{{asset('assets/libs/air-datepicker/css/datepicker.min.css')}}" rel="stylesheet" type="text/css" />

    <link href="{{asset('assets/libs/bootstrap-touchspin/jquery.bootstrap-touchspin.min.css')}}" rel="stylesheet" />
    <style>
        .numeric {
            text-align: end;
        }

        body {
        }

        .container {
            background-color: #fff;
        }

        input[type="text"],
        input[type="date"],
        input[type="number"],
        .input-group-text {
            font-weight: bold;
        }

        .hide {
            display: none;
        }

        .main-flex,
        .loan-calculation-header {
            display: flex;
            width: 100%;
        }

        .main-data-picker,
        .main-chart {
            width: 50%;
        }

        .main-chart,
        .loan-calculation-text-section,
        .loan-calculation-chart-section {
            display: flex;
            flex-direction: column;
            justify-content: space-evenly;
        }
    </style>
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
                    <h4 class="page-title mb-1">Loan</h4>
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{url('/eloans')}}">eLoans</a></li>
                        <li class="breadcrumb-item active">eLoan Amortization Planner</li>
                    </ol>
                </div>

                <div class="col-md-8">
                    <div class="float-right d-none d-md-block">
                        <div>
                            <h1 class="text-white"></h1>
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
                            <div class="container">
                                <div class="main-flex">
                                    <div class="main-data-picker">
                                        <table class="table table-sm table-borderless" style="margin: auto;">
                                            <tbody>
                                            <tr>
                                                <td>
                                                    <label class="active" for="loan_amount">Loan Amount</label>
                                                </td>
                                                <td>
                                                    <div class="input-group">
                                                        <input id="loan_amount" type="text" class="form-control form-control-sm numeric" />
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <label class="active" for="interest_rate">Annual Interest Rate</label>
                                                </td>
                                                <td>
                                                    <div class="input-group">
                                                        <input id="interest_rate" type="text" value="{{getSelfInterestRate()}}" class="form-control form-control-sm numeric" />
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <label class="active" for="loan_period">Loan period in years</label>
                                                </td>
                                                <td>
                                                    <input id="loan_period" type="number"  class="form-control form-control-sm numeric"/>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <label class="active" for="loan_start_date">EMI Starts from</label>
                                                </td>
                                                <td>
                                                    <div class="input-group date">
                                                        <input id="loan_start_date" type="text" class="form-control form-control-sm datepicker-here" data-language='en'
                                                               data-min-view="months"
                                                               data-view="months"
                                                               data-date-format="MM-yyyy" readonly/>
                                                        <div class="input-group-addon">
                                                            <span class="glyphicon glyphicon-th"></span>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <label class="active" for="emi_amount">Scheduled monthly payment</label>
                                                </td>
                                                <td>
                                                    <div class="input-group">
                                                        <input id="emi_amount" type="text" class="form-control form-control-sm numeric" readonly />
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <label class="active" for="number_of_payments">Scheduled number of payments</label>
                                                </td>
                                                <td>
                                                    <input id="number_of_payments" type="text" class="form-control form-control-sm numeric" readonly />
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <label class="active" for="actual_number_of_payments">Actual number of payments</label>
                                                </td>
                                                <td>
                                                    <input id="actual_number_of_payments" type="text" class="form-control form-control-sm numeric" readonly/>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <label class="active" for="total_early_payments">Total of early payments</label>
                                                </td>
                                                <td>
                                                    <div class="input-group">
                                                        <input id="total_early_payments" type="text" class="form-control form-control-sm numeric" readonly/>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <label class="active" for="total_interest">Total interest</label>
                                                </td>
                                                <td>
                                                    <div class="input-group">
                                                        <input id="total_interest" type="text" class="form-control form-control-sm numeric" readonly/>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <label class="active" for="total_interest">Part Payments ? </label>
                                                </td>
                                                <td>
                                                    <div id="part_payment_selector" class="btn-group btn-group-toggle" data-toggle="buttons">
                                                        <label class="btn btn-secondary active" for="off"><input type="radio" value="off" id="off" checked name="part_payments" onchange="calculateEmiAmount()"/>No</label>
                                                        <label class="btn btn-secondary"><input type="radio"  value="scheduled_plan" id="scheduled_plan" name="part_payments" onchange="calculateEmiAmount()"/>Scheduled Plan</label>
                                                        <label class="btn btn-secondary"><input type="radio" value="custom_plan" id="custom_plan" name="part_payments" onchange="calculateEmiAmount()"/>Custom Plan</label>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr class="scheduled_payment_section" style="display: none;">
                                                <td>
                                                    <label class="active" for="total_interest">Schedule Frequency</label>
                                                </td>
                                                <td>
                                                    <div id="frequency_selector" class="btn-group btn-group-toggle" data-toggle="buttons">
                                                        <label class="btn btn-secondary"><input type="radio" value="monthly" id="monthly" name="schedule_frequecy" onchange="calculateEmiAmount()"/>Monthly</label>
                                                        <label class="btn btn-secondary active"><input type="radio"  value="quarterly" id="quarterly" checked name="schedule_frequecy" onchange="calculateEmiAmount()"/>Quarterly</label>
                                                        <label class="btn btn-secondary"><input type="radio" value="yearly" id="yearly" name="schedule_frequecy" onchange="calculateEmiAmount()"/>Yearly</label>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr class="scheduled_payment_section" style="display: none;">
                                                <td>
                                                    <label class="active" for="part_payment_installment">Part Payment Per Installment</label>
                                                </td>
                                                <td>
                                                    <div class="input-group">
                                                        <input id="part_payment_installment" type="text" class="form-control form-control-sm numeric"/>
                                                    </div>
                                                </td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="main-chart">
                                        <div id="chart-container">
                                            <div id="chart-area"></div>
                                        </div>
                                    </div>
                                </div>

                                <br/>
                                <div id="amort_table">
                                    <table id="datatable-buttons" class="table table-striped table-sm">
                                        <thead>
                                        <tr>
                                            <th class="text-center">No</th>
                                            <th class="text-center">EMI Date</th>
                                            <th class="text-right">Beginning Balance</th>
                                            <th class="text-right hide">Scheduled Payment</th>
                                            <th class="text-right" id="part_payment_hdr">Part Payment</th>
                                            <th class="text-right">Total Payment</th>
                                            <th class="text-right">Principle</th>
                                            <th class="text-right">Interest</th>
                                            <th class="text-right">Ending Balance</th>
                                        </tr>
                                        </thead>

                                        <tbody></tbody>
                                    </table>
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
    <script src="{{asset('js/jquery-3.2.1.min.js')}}"></script>
    <script src="{{asset('js/popper.min.js')}}"></script>
    <script src="{{asset('assets/libs/air-datepicker/js/datepicker.min.js')}}"></script>
    <script src="{{asset('assets/libs/air-datepicker/js/i18n/datepicker.en.js')}}"></script>
    <script src="{{asset('assets/libs/bootstrap-touchspin/jquery.bootstrap-touchspin.min.js')}}"></script>
    <script src="{{asset('assets/libs/bootstrap-maxlength/bootstrap-maxlength.min.js')}}"></script>
    <script src="{{asset('js/apexcharts.min.js')}}"></script>
    <script src="{{asset('js/loancalculator.js')}}"></script>

    <!-- Required datatable js -->
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


    <script>


    </script>
@endsection
