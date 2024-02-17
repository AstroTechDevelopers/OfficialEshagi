<?php
/**
 * Created by PhpStorm for eshagitwo
 * User: vincegee
 * Date: 7/9/2021
 * Time: 04:19
 */
?>
@extends('layouts.app')

@section('template_title')
    Apply For A Credit eLoan
@endsection

@section('template_linked_css')

    <link href="{{ asset('css/select2.min.css')}}" rel="stylesheet" />
@endsection

@section('content')
    <div class="page-title-box">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-md-4">
                    <h4 class="page-title mb-1">eLoan</h4>
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{url('/myeloans')}}">eLoan</a></li>
                        <li class="breadcrumb-item active">Apply For Store Credit eLoan</li>
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
            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-body">
                            {!! Form::open(array('route' => 'eloans.store', 'method' => 'POST', 'id' => 'loanForm','role' => 'form', 'class' => 'needs-validation')) !!}

                            {!! csrf_field() !!}
                            <input type="hidden" name="cred_limit" value="{{$user->cred_limit}}">
                            <input type="hidden" name="user_id" value="{{auth()->user()->id}}">
                            <input type="hidden" name="channel_id" value="www.eshagi.com">
                            <input type="hidden" name="loan_type" value="2">
                            <h2 class="title-h2">Loan Details</h2>
                            <hr>
                            <div class="row">
                                <div class="col-lg-6">
                                    <label>Product Description</label>
                                    <select class="form-control" id="prod_descrip" name="prod_descrip" required>
                                        <option value="">Select Product </option>
                                        @if ($products)
                                            @foreach($products as $product)
                                                <option value="{{$product->pcode}}" data-price="{{$product->price}}" >{{ $product->pcode }} - {{ $product->pname }} (${{$product->price}}) </option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>

                                <div class="col-lg-6">
                                    <label>Product Price</label>
                                    <div class="input-group">
                                        <input class="input-group-addon form-control col-lg-2" value="{{getLocaleInfo()->currency_code}}" readonly>
                                        <input type="number" step=0.01 class="form-control col-lg-10" name="amount" id="amount" placeholder="Credit item cost" value="{{old('amount')}}">
                                    </div>
                                </div>
                            </div>
                            <br>
                            <div class="row">
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

                                <div class="col-lg-6">
                                    <label>eShagi Registered Merchant</label>
                                    <select class="form-control" type="text" name="partner_id" id="partner_id" required="required">
                                        @if ($partners)
                                            @foreach($partners as $partner)
                                                <option value="">Please select your Merchant</option>
                                                <option value='{{ $partner->id }}'>{{ $partner->partner_name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>

                            <br>
                            <h2 class="title-h2">Credit Overview</h2>
                            <hr>
                            <div class="row">
                                <div class="col-lg-6">
                                    <p>Name: {{$user->first_name.' '.$user->last_name}}</p>
                                    <p>ID No: {{$user->natid}}</p>
                                </div>

                                <div class="col-lg-6">
                                    <p>Credit Status: {{$user->fsb_status}}</p>
                                    <p>Credit Score: {{$user->fsb_score}}</p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-6">
                                    <p>Credit Limit: ${{$user->cred_limit}}</p>
                                </div>
                            </div>
                            <br>

                            <h2 class="title-h2">Loan Overview</h2>
                            <hr>
                            <div class="row">
                                <div class="col-lg-6">
                                    <p>Monthly Repayment : {{getLocaleInfo()->currency_code}}<input class="{{ $errors->has('monthly') ? ' is-invalid' : '' }} col-lg-6" style="border: none;width: fit-content;" type="text" name="monthly" id="monthly" required value="{{ old('monthly') }}" placeholder="0" readonly></p>
                                    <p>Interest Rate :          <input class="{{ $errors->has('interestRate') ? ' is-invalid' : '' }} col-lg-1" style="border: none;width: 100%;" type="text" name="interestRate" id="interestRate" required value="{{ old('interestRate') }}" placeholder="{!! getInterestRate() !!}" readonly>%</p>
                                    <p>Payment Period Rate :   <input class="{{ $errors->has('tenure') ? ' is-invalid' : '' }} col-lg-1" style="border: none;width: 100%;" type="text" name="tenure" id="tenure" required value="{{ old('tenure') }}" placeholder="0" readonly>Months</p>
                                </div>

                                <div class="col-lg-6">
                                    <p>Amount Disbursed : {{getLocaleInfo()->currency_code}}<input class="{{ $errors->has('disbursed') ? ' is-invalid' : '' }} col-lg-6" style="border: none;width: fit-content;" type="text" name="disbursed" id="disbursed" required value="{{ old('disbursed') }}" placeholder="0" readonly></p>
                                    <p>Application Fee :  {{getLocaleInfo()->currency_code}}<input class="{{ $errors->has('appFee') ? ' is-invalid' : '' }} col-lg-6" style="border: none;width: fit-content;" type="text" name="appFee" id="appFee" required value="{{ old('appFee') }}" placeholder="0" readonly></p>
                                    <p>Disbursement Fee: {{getLocaleInfo()->currency_code}}<input class="{{ $errors->has('disbursefee') ? ' is-invalid' : '' }} col-lg-6" style="border: none;width: fit-content;" type="text" name="disbursefee" id="disbursefee" required value="{{ old('disbursefee') }}" placeholder="0" readonly></p>
                                </div>

                            </div>

                            <div class="row">
                                <div class="col-lg-6">
                                </div>

                                <div class="col-lg-6">
                                    <p><strong>Total Charges : </strong>    {{getLocaleInfo()->currency_code}}<input class="{{ $errors->has('charges') ? ' is-invalid' : '' }} col-lg-6" style="border: none;width: fit-content;" type="text" name="charges" id="charges" required value="{{ old('charges') }}" placeholder="0" readonly></p>
                                </div>

                            </div>

                            {!! Form::button('Next : Loan Agreement', array('class' => 'btn btn-primary btn-send margin-bottom-1 mb-1 float-right','type' => 'submit' )) !!}
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

        $("#partner_id").select2({
            placeholder: 'Please select the merchant you are applying with.',
            allowClear:true,
        });

        $("#prod_descrip").select2({
            placeholder: 'Please select a product.',
            allowClear:true,
        }).on('change',function(){
            var amount = $(this).children('option:selected').data('price');
            $('#amount').val(amount);
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

    @include('eloans.calculate-eloan')
@endsection
