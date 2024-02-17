<?php
/**
 * Created by PhpStorm for eshagitwo
 * User: vinceg
 * Date: 16/8/2021
 * Time: 19:33
 */
?>
@extends('layouts.app')

@section('template_title')
    eLoan Calculator
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
                        <li class="breadcrumb-item"><a href="{{url('/eloans')}}">eLoans</a></li>
                        <li class="breadcrumb-item active">eLoan Calculator</li>
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

                            <div class="row">
                                <div class="col-lg-6">
                                    <label>Loan Amount</label>
                                    <div class="input-group">
                                        <input class="input-group-addon form-control col-lg-2" value="{{getLocaleInfo()->currency_code}}" readonly>
                                        <input type="number" onkeyup="calculate();" step=0.01 class="form-control col-lg-10" name="amount" id="amount" placeholder="Enter loan amount">
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <label>Repayment Period</label>
                                    <select onchange="calculate();" class="form-control" id="paybackPeriod" name="paybackPeriod">
                                        <option value="">Select repayment period</option>
                                        <option value="2">2 months</option>
                                        <option value="3">3 months</option>
                                        <option value="4">4 months</option>
                                        <option value="5">5 months</option>
                                        <option value="6">6 months</option>
                                        <option value="7">7 months</option>
                                        <option value="8">8 months</option>
                                        <option value="9">9 months</option>
                                        <option value="10">10 months</option>
                                        <option value="11">11 months</option>
                                        <option value="12">12 months</option>
                                        <option value="13">13 months</option>
                                        <option value="14">14 months</option>
                                        <option value="15">15 months</option>
                                        <option value="16">16 months</option>
                                        <option value="17">17 months</option>
                                        <option value="18">18 months</option>
                                        <option value="19">19 months</option>
                                        <option value="20">20 months</option>
                                        <option value="21">21 months</option>
                                        <option value="22">22 months</option>
                                        <option value="23">23 months</option>
                                        <option value="24">24 months</option>
                                    </select>
                                </div>
                            </div>
                            <br>

                            <h2 class="title-h2">Loan Summary</h2>
                            <hr>
                            <div class="row">
                                <div class="col-lg-6">
                                    <p><strong>Monthly Repayment :</strong> {{getLocaleInfo()->currency_code}}<input class="{{ $errors->has('monthly') ? ' is-invalid' : '' }} col-lg-6" style="border: none;width: fit-content;" type="text" name="monthly" id="monthly" required value="{{ old('monthly') }}" placeholder="0" readonly></p>
                                    <p>Interest Rate :          <input class="{{ $errors->has('interestRate') ? ' is-invalid' : '' }} col-lg-2" style="border: none;width: 100%;" type="text" name="interestRate" id="interestRate" required value="{{ old('interestRate') }}" placeholder="0" readonly>%</p>
                                    <p>Payment Period Rate :   <input class="{{ $errors->has('tenure') ? ' is-invalid' : '' }} col-lg-2" style="border: none;width: 100%;" type="text" name="tenure" id="tenure" required value="{{ old('tenure') }}" placeholder="0" readonly>Months</p>
                                </div>

                                <div class="col-lg-6">
                                    <p><strong>Amount Disbursed :</strong> {{getLocaleInfo()->currency_code}}<input class="{{ $errors->has('disbursed') ? ' is-invalid' : '' }} col-lg-6" style="border: none;width: fit-content;" type="text" name="disbursed" id="disbursed" required value="{{ old('disbursed') }}" placeholder="0" readonly></p>
                                    <p>Application Fee :  {{getLocaleInfo()->currency_code}}<input class="{{ $errors->has('appFee') ? ' is-invalid' : '' }} col-lg-6" style="border: none;width: fit-content;" type="text" name="appFee" id="appFee" required value="{{ old('appFee') }}" placeholder="0" readonly></p>
                                    <p>Tax : {{getLocaleInfo()->currency_code}}<input class="{{ $errors->has('tax') ? ' is-invalid' : '' }} col-lg-6" style="border: none;width: fit-content;" type="text" name="tax" id="tax" required value="{{ old('tax') }}" placeholder="0" readonly></p>
                                </div>

                            </div>
                            <div class="row">
                                <div class="col-lg-6">
                                    <p>Admin/Arrangement : {{getLocaleInfo()->currency_code}}<input class="{{ $errors->has('arrangement') ? ' is-invalid' : '' }} col-lg-6" style="border: none;width: fit-content;" type="text" name="arrangement" id="arrangement" required value="{{ old('arrangement') }}" placeholder="0" readonly></p>
                                    <p>Insurance :         {{getLocaleInfo()->currency_code}}<input class="{{ $errors->has('insurance') ? ' is-invalid' : '' }} col-lg-2" style="border: none;width: 100%;" type="text" name="insurance" id="insurance" required value="{{ old('insurance') }}" placeholder="0" readonly></p>
                                </div>

                                <div class="col-lg-6">
                                    <p>Disbursement Fee: {{getLocaleInfo()->currency_code}}<input class="{{ $errors->has('disbursefee') ? ' is-invalid' : '' }} col-lg-6" style="border: none;width: fit-content;" type="text" name="disbursefee" id="disbursefee" required value="{{ old('disbursefee') }}" placeholder="0" readonly></p>
                                    <p><strong> Total Charges: </strong>   {{getLocaleInfo()->currency_code}}<input class="{{ $errors->has('charges') ? ' is-invalid' : '' }} col-lg-6" style="border: none;width: fit-content;" type="text" name="charges" id="charges" required value="{{ old('charges') }}" placeholder="0" readonly></p>

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

    <script src="{{ asset('js/select2.min.js')}}"></script>

    <script type="text/javascript">
        $("#paybackPeriod").select2({
            placeholder: 'Please select a Payback period.',
            allowClear:true,
        });
    </script>

    @include('eloans.calculate-eloan')

@endsection
