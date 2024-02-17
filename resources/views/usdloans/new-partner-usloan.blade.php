<?php
/**
 * Created by PhpStorm for eshagitwo
 * User: VinceGee
 * Date: 8/1/2022
 * Time: 5:38 AM
 */ ?>
@extends('layouts.app')

@section('template_title')
    Apply For A USD Loan
@endsection

@section('template_linked_css')

    <link href="{{ asset('css/select2.min.css')}}" rel="stylesheet" />
@endsection

@section('content')
    <div class="page-title-box">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-md-4">
                    <h4 class="page-title mb-1">USD Loan</h4>
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{url('/partner-loans')}}">Loan</a></li>
                        <li class="breadcrumb-item active">Apply For A Existing Customer USD Loan</li>
                    </ol>
                </div>

                <div class="col-md-8">
                    <div class="float-right d-none d-md-block">
                        <div>
                            <h1 class="text-white">USD Loan</h1>
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
                            {!! Form::open(array('route' => 'uploadPartnerUsdLoan', 'method' => 'POST', 'id' => 'loanForm','role' => 'form', 'class' => 'needs-validation')) !!}
                            {!! csrf_field() !!}

                            <input type="hidden" name="channel_id" value="www.eshagi.com">
                            <input type="hidden" name="partner_id" value="{{$partner->id}}">
                            <input type="hidden" name="user_id" value="">
                            <input type="hidden" name="loan_type" value="2">
                            <h2 class="title-h2">Loan Details</h2>
                            <hr>
                            <div class="row">
                                <div class="col-lg-6">
                                    <label>Client</label>
                                    <select class="form-control" id="client_id" name="client_id" required>
                                        <option value="">Select Client </option>
                                        @if($clients)
                                            @foreach($clients as $client)
                                                <option value="{{$client->id}}" data-uid="{{$client->user_id}}" data-credit="{{$client->cred_limit}}" data-bank="{{$client->bank}}" data-branch_code="{{$client->branch_code}}" data-branch="{{$client->branch}}" data-acc_number="{{$client->acc_number}}" >{{ $client->first_name }} {{ $client->last_name }}  {{ $client->natid }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                                <div class="col-lg-6">
                                    <label>Credit Limit</label>
                                    <p>{{getLocaleInfo()->symbol}}<input class="{{ $errors->has('cred_limit') ? ' is-invalid' : '' }} col-lg-6" style="border: none;width: fit-content;" type="text" name="cred_limit" id="cred_limit" required value="{{ old('cred_limit') }}" placeholder="" readonly></p>

                                </div>
                            </div>
                            <br>

                            <div class="row">
                                <div class="col-lg-4">
                                    <label>Loan Amount</label>
                                    <div class="input-group">
                                        <input class="input-group-addon form-control col-lg-2" value="USD" readonly>
                                        <input type="number" onkeyup="calculate();" step=0.01 class="form-control col-lg-10" name="amount" id="amount" placeholder="Enter loan amount" value="{{old('amount')}}">
                                    </div>
                                </div>

                                <div class="col-lg-4">
                                    <label>Gross Loan Amount</label>
                                    <div class="input-group">
                                        <input class="input-group-addon form-control col-lg-2" value="USD" readonly>
                                        <input type="number" onkeyup="calculate();" step=0.01 class="form-control col-lg-10" name="gross_amount" id="gross_amount" placeholder="Enter loan amount" readonly>
                                    </div>
                                </div>

                                <div class="col-lg-4">
                                    <label>Repayment Period</label>
                                    <select onchange="calculate();" class="form-control" id="tenure" name="tenure">
                                        <option value="{{old('tenure')}}">{{old('tenure')}}</option>
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
                                    <p>Bank Name: <input class="{{ $errors->has('bank') ? ' is-invalid' : '' }} col-lg-6" style="border: none;width: fit-content;" type="text" name="bank" id="bank" required value="{{ old('bank') }}" placeholder="" readonly></p>
                                    <p>Branch Code: <input class="{{ $errors->has('branch_code') ? ' is-invalid' : '' }} col-lg-6" style="border: none;width: fit-content;" type="text" name="branch_code" id="branch_code" required value="{{ old('branch_code') }}" placeholder="" readonly></p>
                                </div>

                                <div class="col-lg-6">
                                    <p>Branch Name: <input class="{{ $errors->has('branch') ? ' is-invalid' : '' }} col-lg-6" style="border: none;width: fit-content;" type="text" name="branch" id="branch" required value="{{ old('branch') }}" placeholder="" readonly></p>
                                    <p>Account Number: <input class="{{ $errors->has('acc_number') ? ' is-invalid' : '' }} col-lg-6" style="border: none;width: fit-content;" type="text" name="acc_number" id="acc_number" required value="{{ old('acc_number') }}" placeholder="" readonly></p>
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

                            {!! Form::button('Submit Loan Request', array('class' => 'btn btn-primary btn-send margin-bottom-1 mb-1 float-right','type' => 'submit' )) !!}
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
        $("#client_id").select2({
            placeholder: 'Please select a client',
            allowClear:true,
        }).on('change',function(){
            var credit = $(this).children('option:selected').data('credit');
            var bank = $(this).children('option:selected').data('bank');
            var branch_code = $(this).children('option:selected').data('branch_code');
            var branch = $(this).children('option:selected').data('branch');
            var acc_number = $(this).children('option:selected').data('acc_number');
            var user_id = $(this).children('option:selected').data('user_id');
            $('#cred_limit').val(credit);
            $('#bank').val(bank);
            $('#branch_code').val(branch_code);
            $('#branch').val(branch);
            $('#acc_number').val(acc_number);
            $('#user_id').val(user_id);
        });

        $("#tenure").select2({
            placeholder: 'Please select a Payback period.',
            allowClear:true,
        });
    </script>

    <script type="text/javascript">
        function validateCreditLimit() {
            var amount=document.getElementById("amount").value;
            var creditLimit= document.getElementById("cred_limit").value;

            if(amount>creditLimit) {
                alert("Please do not exceed client maximum credit limit of USD$"+creditLimit);
                document.getElementById("amount").value=0;
            }
        }

    </script>

    @include('usdloans.calculate-usd-loan')
@endsection
