<?php
/**
 * Created by PhpStorm for eshagitwo
 * User: VinceGee
 * Date: 11/9/2022
 * Time: 9:48 AM
 */ ?>
@extends('layouts.app')

@section('template_title')
    Affordability Checker
@endsection

@section('template_linked_css')

    <link href="{{ asset('css/select2.min.css')}}" rel="stylesheet" />
@endsection

@section('content')
    <div class="page-title-box">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-md-4">
                    <h4 class="page-title mb-1">Zambia Clients</h4>
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Zambia Clients</a></li>
                        <li class="breadcrumb-item active">Check Loan Affordability</li>
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
                            {!! Form::open(array('route' => 'check.for.affordability', 'method' => 'POST', 'id' => 'loanForm','role' => 'form', 'class' => 'needs-validation')) !!}
                            {!! csrf_field() !!}

                            <div class="row">
                                <div class="col-lg-3">
                                    <label>Loan Amount</label>
                                    <div class="input-group">
                                        <input class="input-group-addon form-control col-lg-2" value="K" readonly>
                                        <input type="number" onkeyup="calculate();" step=0.01 class="form-control col-lg-10" name="loan_principal_amount" id="loan_principal_amount" placeholder="Enter loan amount">
                                    </div>
                                </div>

                                <div class="col-lg-3">
                                    <label>Gross Salary</label>
                                    <div class="input-group">
                                        <input class="input-group-addon form-control col-lg-2" value="K" readonly>
                                        <input type="number" onkeyup="calculate();" step=0.01 class="form-control col-lg-10" name="gross_salary" id="gross_salary" placeholder="Enter gross salary">
                                    </div>
                                </div>

                                <div class="col-lg-3">
                                    <label>Net Salary</label>
                                    <div class="input-group">
                                        <input class="input-group-addon form-control col-lg-2" value="K" readonly>
                                        <input type="number" onkeyup="calculate();" step=0.01 class="form-control col-lg-10" name="net_salary" id="net_salary" placeholder="Enter net salary">
                                    </div>
                                </div>

                                <div class="col-lg-3">
                                    <label>Tenure (In Months)</label>
                                    <div class="input-group">
                                        <input type="number" onkeyup="calculate();" step=0.01 class="form-control col-lg-12" name="loan_duration" id="loan_duration" placeholder="Enter loan tenure">
                                    </div>
                                </div>
                            </div>
                            <br>

                            <h2 class="title-h2">Loan Summary</h2>
                            <hr>
                            <div class="row">
                                <div class="col-lg-4">
                                    <p><strong>Loan Eligibility :</strong> <input class="{{ $errors->has('eligibility') ? ' is-invalid' : '' }} col-lg-6" style="border: none;width: fit-content;" type="text" name="eligibility" id="eligibility" required value="{{ old('eligibility') }}" autocomplete="off" ></p>
                                </div>

                                <div class="col-lg-4">
                                    <p><strong>Loan Limit :</strong> K<input class="{{ $errors->has('loan_limit') ? ' is-invalid' : '' }} col-lg-6" style="border: none;width: fit-content;" type="text" name="loan_limit" id="loan_limit" required value="{{ old('loan_limit') }}" autocomplete="off"></p>
                                </div>

                                <div class="col-lg-4">
                                    <p><strong>Loan Limit Test :</strong> <input class="{{ $errors->has('limit_test') ? ' is-invalid' : '' }} col-lg-6" style="border: none;width: fit-content;" type="text" name="limit_test" id="limit_test" required value="{{ old('limit_test') }}" autocomplete="off"></p>
                                </div>

                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-lg-6">
                                    <p><strong>Interest Rate:</strong> <input class="{{ $errors->has('interest') ? ' is-invalid' : '' }} col-lg-6" style="border: none;width: fit-content;" type="text" name="interest" id="interest" required value="{{ getSelfInterestRate().'%' }}" placeholder="0" readonly></p>
                                    <p><strong>Monthly Repayment :</strong> K<input class="{{ $errors->has('monthly') ? ' is-invalid' : '' }} col-lg-6" style="border: none;width: fit-content;" type="text" name="monthly" id="monthly" required value="{{ old('monthly') }}" placeholder="0" readonly></p>
                                </div>

                                <div class="col-lg-6">
                                    <p><strong> Total Charges: </strong>   K<input class="{{ $errors->has('total_charges') ? ' is-invalid' : '' }} col-lg-6" style="border: none;width: fit-content;" type="text" name="total_charges" id="total_charges" required value="{{ old('total_charges') }}" placeholder="0" readonly></p>
                                    <p><strong>Net After Charges :</strong> K<input class="{{ $errors->has('net_after_charge') ? ' is-invalid' : '' }} col-lg-6" style="border: none;width: fit-content;" type="text" name="net_after_charge" id="net_after_charge" required value="{{ old('net_after_charge') }}" placeholder="0" readonly></p>

                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-6">
                                    <p><strong>Admin Fees :</strong> K<input class="{{ $errors->has('admin_fee') ? ' is-invalid' : '' }} col-lg-6" style="border: none;width: fit-content;" type="text" name="admin_fee" id="admin_fee" required value="{{ old('admin_fee') }}" placeholder="0" readonly></p>
                                    <p><strong>Insurance Fee :</strong> K<input class="{{ $errors->has('insurance_fee') ? ' is-invalid' : '' }} col-lg-6" style="border: none;width: fit-content;" type="text" name="insurance_fee" id="insurance_fee" required value="{{ old('insurance_fee') }}" placeholder="0" readonly></p>
                                </div>

                                <div class="col-lg-6">
                                    <p><strong>Application Fee :</strong>  K<input class="{{ $errors->has('app_fee') ? ' is-invalid' : '' }} col-lg-6" style="border: none;width: fit-content;" type="text" name="app_fee" id="app_fee" required value="{{ old('app_fee') }}" placeholder="0" readonly></p>
                                </div>

                            </div>


                            {!! Form::button('Confirm Affordability', array('id'=>'btSubmit','disabled','class' => 'btn btn-primary btn-send margin-bottom-1 mb-1 float-right','type' => 'submit' )) !!}
                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('footer_scripts')

    <script src="{{ asset('js/select2.min.js')}}"></script>

    <script type="text/javascript">
        $("#paybackPeriod").select2({
            placeholder: 'Please select a Payback period.',
            allowClear:true,
        });
    </script>

    @include('zamloans.immediate-zam-calc')

@endsection
