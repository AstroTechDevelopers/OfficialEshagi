<?php
/**
 * Created by PhpStorm for eshagitwo
 * User: VinceGee
 * Date: 8/2/2022
 * Time: 3:05 AM
 */ ?>
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
                            <h1 class="text-white">Credit Limit Applies</h1>
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
                            {!! Form::open(array('route' => 'usd-loans.store', 'method' => 'POST', 'id' => 'loanForm','role' => 'form', 'class' => 'needs-validation')) !!}

                            {!! csrf_field() !!}
                            <input type="hidden" name="cred_limit" value="{{$user->cred_limit}}">
                            <input type="hidden" name="user_id" value="{{auth()->user()->id}}">
                            <input type="hidden" name="channel_id" value="www.eshagi.com">
                            <input type="hidden" name="loan_type" value="2">
                            <h2 class="title-h2">Loan Details</h2>
                            <hr>
                            <div class="row">

                                <div class="col-lg-6">
                                    <label>Loan Amount</label>
                                    <div class="input-group">
                                        <input class="input-group-addon form-control col-lg-2" value="USD" readonly>
                                        <input type="number" step=0.01 onkeyup="calculate()" class="form-control col-lg-10" name="amount" id="amount" placeholder="Enter loan amount">
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <label>Repayment Period</label>
                                    <select onchange="calculate();" class="form-control" id="tenure" name="tenure">
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
                            <h2 class="title-h2">Payout Account</h2>
                            <hr>
                            <div class="row">
                                <div class="col-lg-6">
                                    <p>Bank Name: {{$bank->bank}}</p>
                                    <p>Branch Code: {{$yuser->branch_code}}</p>
                                </div>

                                <div class="col-lg-6">
                                    <p>Branch Name: {{$yuser->branch}}</p>
                                    <p>Account Number: {{$yuser->acc_number}}</p>
                                </div>
                            </div>
                            <br>

                            <h2 class="title-h2">Loan Overview</h2>
                            <hr>
                            <div class="row">
                                <div class="col-lg-6">
                                    <p><strong>Monthly Repayment :</strong> USD<input class="{{ $errors->has('monthly') ? ' is-invalid' : '' }} col-lg-6" style="border: none;width: fit-content;" type="text" name="monthly" id="monthly" required value="{{ old('monthly') }}" placeholder="0" readonly></p>
                                    <p>Interest Rate :          <input class="{{ $errors->has('interestRate') ? ' is-invalid' : '' }} col-lg-2" style="border: none;width: 100%;" type="text" name="interestRate" id="interestRate" required  value="{{ getUsdInterestRate() }}" placeholder="0" readonly>%</p>
                                    <p>AGS Commission : USD<input class="{{ $errors->has('ags_commission') ? ' is-invalid' : '' }} col-lg-6" style="border: none;width: fit-content;" type="text" name="ags_commission" id="ags_commission" required value="{{ old('ags_commission') }}" placeholder="0" readonly></p>
                                </div>

                                <div class="col-lg-6">
                                    <p><strong>Net After Charges :</strong> USD<input class="{{ $errors->has('net_after_charge') ? ' is-invalid' : '' }} col-lg-6" style="border: none;width: fit-content;" type="text" name="net_after_charge" id="net_after_charge" required value="{{ old('net_after_charge') }}" placeholder="0" readonly></p>
                                    <p>Application Fee :  USD<input class="{{ $errors->has('app_fee') ? ' is-invalid' : '' }} col-lg-6" style="border: none;width: fit-content;" type="text" name="app_fee" id="app_fee" required value="{{ old('app_fee') }}" placeholder="0" readonly></p>
                                    <p>Establishment Fee : USD<input class="{{ $errors->has('est_fee') ? ' is-invalid' : '' }} col-lg-6" style="border: none;width: fit-content;" type="text" name="est_fee" id="est_fee" required value="{{ old('est_fee') }}" placeholder="0" readonly></p>
                                </div>

                            </div>
                            <div class="row">
                                <div class="col-lg-6">
                                    <p>Insurance :         USD<input class="{{ $errors->has('insurance') ? ' is-invalid' : '' }} col-lg-2" style="border: none;width: 100%;" type="text" name="insurance" id="insurance" required value="{{ old('insurance') }}" placeholder="0" readonly></p>
                                </div>

                                <div class="col-lg-6">

                                </div>
                            </div>
                            {!! Form::button('Apply For Loan', array('class' => 'btn btn-primary btn-send margin-bottom-1 mb-1 float-right','type' => 'submit' )) !!}
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
        $("#tenure").select2({
            placeholder: 'Please select a Payback period.',
            allowClear:true,
        });
    </script>

    <script type="text/javascript">
        function validateCreditLimit() {
            var amount=document.getElementById("amount").value;
            var creditLimit= {!! $user->cred_limit !!};
            if(amount>creditLimit) {
                alert("Please do not exceed your maximum credit limit of ZWL$"+creditLimit);
                document.getElementById("amount").value=0;
            }
        }

    </script>

    @include('usdloans.calculate-usd-loan')

@endsection
