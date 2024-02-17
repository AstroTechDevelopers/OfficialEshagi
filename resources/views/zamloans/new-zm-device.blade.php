<?php
/**
 * Created by PhpStorm for eshagitwo
 * User: VinceGee
 * Date: 8/11/2022
 * Time: 6:06 AM
 */ ?>
@extends('layouts.app')

@section('template_title')
    New Device Loan
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
                        <li class="breadcrumb-item"><a href="{{url('/zambia-me-loans')}}">Loans</a></li>
                        <li class="breadcrumb-item active">Apply For A Existing Customer Cash Loan</li>
                    </ol>
                </div>

                <div class="col-md-8">
                    <div class="float-right d-none d-md-block">
                        <div>
                            <h1 class="text-white">Zambia Device Loan</h1>
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
                            {!! Form::open(array('route' => 'save.zamdev.loan', 'method' => 'POST', 'id' => 'loanForm','role' => 'form', 'class' => 'needs-validation')) !!}
                            {!! csrf_field() !!}

                            <input type="hidden" name="channel_id" value="www.eshagi.com">
                            <input type="hidden" name="borrower_id" value="{{$client->id}}">
                            <h2 class="title-h2">Loan Details</h2>
                            <hr>
                            <div class="row">
                                <div class="col-lg-6">
                                    <label>Loan Product</label>
                                    <select class="form-control" name="loan_product_id" id="loan_product_id" required>
                                        <option value="">Select Product </option>
                                        @if($products)
                                            @foreach($products as $product)
                                                <option value="{{$product->pcode}}" data-price="{{$product->price}}">{{ $product->pname }} {{ $product->model }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                                <div class="col-lg-6">

                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-lg-6">
                                    <label>Disbursed By</label>
                                    <select class="form-control" id="loan_disbursed_by_id" name="loan_disbursed_by_id" required>
                                        <option value="90801">Cash</option>
                                        <option value="90802">Cheque</option>
                                        <option value="90803">Wire Transfer</option>
                                        <option value="90804">Online Transfer</option>
                                    </select>
                                </div>
                                <div class="col-lg-6">
                                    <label>Loan Release Date</label>
                                    <input class="form-control datepicker-here{{ $errors->has('loan_released_date') ? ' is-invalid' : '' }}" data-language="en" data-date-format="dd-mm-yyyy" type="text" name="loan_released_date" id="loan_released_date" value="{{ old('loan_released_date') }}" required="required" placeholder="Enter loan released date..." autocomplete="off">

                                </div>
                            </div>
                            <br>

                            <div class="row">
                                <div class="col-lg-6">
                                    <label>Principal Amount</label>
                                    <div class="input-group">
                                        <input class="input-group-addon form-control col-lg-2" value="{{getLocaleInfo()->currency_code.'('.getLocaleInfo()->symbol.')'}}" readonly>
                                        <input type="text" onkeyup="calculate();" pattern="^\d{1,3}*(\.\d+)?$" data-type="currency" class="form-control col-lg-10" name="loan_principal_amount" id="loan_principal_amount" placeholder="Enter loan amount" value="{{old('loan_principal_amount')}}">
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <label>Loan Interest Period</label>
                                    <select class="form-control" name="loan_interest_period" id="loan_interest_period" required="">
                                        <option value="Month">Month</option>
                                    </select>
                                </div>
                            </div>
                            <br>

                            <div class="row">
                                <div class="col-lg-6">
                                    <label>Loan Duration (Per Month)</label>
                                    <input class="form-control {{ $errors->has('loan_duration') ? ' is-invalid' : '' }}" onkeyup="calculate();" type="number" name="loan_duration" id="loan_duration" value="{{ old('loan_duration') }}" required="required" placeholder="e.g. 3" autocomplete="off">

                                </div>
                                <div class="col-lg-6">
                                    <label>Loan Interest Period</label>
                                    <select class="form-control" name="loan_duration_period" id="loan_duration_period" required="">
                                        <option value="Months">Months</option>
                                    </select>
                                </div>
                            </div>
                            <br>

                            <div class="row">
                                <div class="col-lg-6">
                                    <label>Repayment Cycle</label>
                                    <select class="form-control" id="loan_payment_scheme_id" name="loan_payment_scheme_id" required>
                                        <option value="3">Monthly</option>

                                    </select>
                                </div>
                                <div class="col-lg-6">
                                    <label>Number of Repayments</label>
                                    <input class="form-control {{ $errors->has('loan_num_of_repayments') ? ' is-invalid' : '' }}" type="number" name="loan_num_of_repayments" id="loan_num_of_repayments" value="{{ old('loan_num_of_repayments') }}" required="required" placeholder="e.g. 6" autocomplete="off">

                                </div>
                            </div>
                            <br>

                            <h2 class="title-h2">Other Details</h2>
                            <hr>
                            <div class="row">
                                <div class="col-lg-6">
                                    <label>Approval Date</label>
                                    <input class="form-control datepicker-here{{ $errors->has('cf_11133_approval_date') ? ' is-invalid' : '' }}" data-language="en" data-date-format="dd-mm-yyyy" type="text" name="cf_11133_approval_date" id="cf_11133_approval_date" value="{{ old('cf_11133_approval_date') }}" required="required" placeholder="Choose Approval date" autocomplete="off">
                                </div>
                                <div class="col-lg-6">
                                    <label>Installment</label>
                                    <input class="form-control {{ $errors->has('cf_11353_installment') ? ' is-invalid' : '' }}" type="text" readonly pattern="^\d{1,3}*(\.\d+)?$" data-type="currency" name="cf_11353_installment" id="cf_11353_installment" value="{{ old('cf_11353_installment') }}" required="required" placeholder="e.g. 567.80" autocomplete="off">

                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-lg-6">
                                    <label>Quantity</label>
                                    <input class="form-control {{ $errors->has('cf_11132_qty') ? ' is-invalid' : '' }}" type="text" name="cf_11132_qty" id="cf_11132_qty" value="{{ old('cf_11132_qty') }}" required="required" placeholder="e.g. 2" autocomplete="off">

                                </div>
                                <div class="col-lg-6">
                                    <label>Loan Status</label>
                                    <select class="form-control" id="loan_status_id" name="loan_status_id" required>
                                        <option value="8">Processing</option>
                                        <option value="1" selected="">Open</option>
                                        <option value="3">Defaulted</option>
                                        <option value="182376">----Credit Counseling</option>
                                        <option value="182377">----Collection Agency</option>
                                        <option value="182378">----Sequestrate</option>
                                        <option value="182379">----Debt Review</option>
                                        <option value="182380">----Fraud</option>
                                        <option value="182381">----Investigation</option>
                                        <option value="182382">----Legal</option>
                                        <option value="182383">----Write-Off</option>
                                        <option value="9">Denied</option>
                                        <option value="17">Not Taken Up</option>
                                    </select>
                                </div>
                            </div>
                            <br>

                            <h2 class="title-h2">Banking Details</h2>
                            <hr>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Bank Name</label>
                                        <input class="form-control{{ $errors->has('cf_11134_bank') ? ' is-invalid' : '' }}" type="text" name="cf_11134_bank" id="cf_11134_bank" value="{{ $client->bank_name }}" required="required" placeholder="Enter your Bank...">

                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Branch Name</label>
                                        <input class="form-control{{ $errors->has('cf_11135_branch') ? ' is-invalid' : '' }}" type="text" name="cf_11135_branch" id="cf_11135_branch" value="{{ $client->branch }}" required="required" placeholder="Enter your branch...">

                                    </div>
                                </div>

                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Bank Account Number</label>
                                        <input class="form-control{{ $errors->has('cf_11136_account_num') ? ' is-invalid' : '' }}" type="text" name="cf_11136_account_num" id="cf_11136_account_num" value="{{ $client->bank_account }}" pattern='^\d{1,3}*(\.\d+)?$' data-type="account_num" required="required" placeholder="Enter your account number...">

                                        @if ($errors->has('cf_11136_account_num'))
                                            <span class="invalid-feedback">
                                                    <strong>{{ $errors->first('cf_11136_account_num') }}</strong>
                                                </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-md-6">

                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-4">
                                    <label>What is your Gross Salary</label>
                                    <div class="input-group">
                                        <input class="input-group-addon form-control col-lg-2" value="ZMK" readonly>
                                        <input type="number" onkeyup="calculate();" step=0.01 class="form-control col-lg-10" name="gross_salary" id="gross_salary" placeholder="Enter gross salary">
                                    </div>
                                </div>

                                <div class="col-lg-4">
                                    <label>What is your Net Salary?</label>
                                    <div class="input-group">
                                        <input class="input-group-addon form-control col-lg-2" value="ZMK" readonly>
                                        <input type="number" onkeyup="calculate();" step=0.01 class="form-control col-lg-10" name="net_salary" id="net_salary" placeholder="Enter net salary">
                                    </div>
                                </div>

                                <div class="col-lg-4">

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
                                    <p>Interest Rate: <input class="{{ $errors->has('interest') ? ' is-invalid' : '' }} col-lg-6" style="border: none;width: fit-content;" type="text" name="interest" id="interest" required value="{{ getDeviceInterestRate().'%' }}" placeholder="0" readonly></p>
                                    <p>Installment :  K<input class="{{ $errors->has('installment') ? ' is-invalid' : '' }} col-lg-2" style="border: none;width: 100%;" type="text" name="installment" id="installment" required value="{{ old('installment') }}" placeholder="0" readonly></p>
                                </div>

                                <div class="col-lg-6">
                                    <p><strong> 60% of Gross Salary: </strong>   K<input class="{{ $errors->has('sixty_gross') ? ' is-invalid' : '' }} col-lg-6" style="border: none;width: fit-content;" type="text" name="sixty_gross" id="sixty_gross" required value="{{ old('sixty_gross') }}" placeholder="0" readonly></p>
                                    <p><strong> Net Plus Installment: </strong>   K<input class="{{ $errors->has('tot_deductions') ? ' is-invalid' : '' }} col-lg-6" style="border: none;width: fit-content;" type="text" name="tot_deductions" id="tot_deductions" required value="{{ old('tot_deductions') }}" placeholder="0" readonly></p>

                                </div>
                            </div>

                            {!! Form::button('Apply Loan', array('id'=>'btSubmit','disabled','class' => 'btn btn-primary btn-send margin-bottom-1 mb-1 float-right','type' => 'submit' )) !!}
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
    <script src="{{asset('assets/libs/air-datepicker/js/datepicker.min.js')}}"></script>
    <script src="{{asset('assets/libs/air-datepicker/js/i18n/datepicker.en.js')}}"></script>
    <script src="{{asset('assets/libs/bootstrap-touchspin/jquery.bootstrap-touchspin.min.js')}}"></script>
    <script src="{{asset('assets/libs/bootstrap-maxlength/bootstrap-maxlength.min.js')}}"></script>

    <script type="text/javascript">
        $("#loan_released_date").datepicker({
            language: 'en',
            /*                minDate: new Date("01-01-1940"),
                            maxDate: new Date("01-01-2003"),*/
        });

        $("#cf_11133_approval_date").datepicker({
            language: 'en',
            /*                minDate: new Date("01-01-1940"),
                            maxDate: new Date("01-01-2003"),*/
        });

        $("#borrower_id").select2({
            placeholder: 'Please select a client',
            allowClear:true,
        });

        $("#loan_product_id").select2({
            placeholder: 'Please select a product',
            allowClear:true,
        }).on('change',function(){
            var amount = $(this).children('option:selected').data('price');
            $('#loan_principal_amount').val(amount);
        });

        $("#loan_disbursed_by_id").select2({
            placeholder: 'Please select disbursement method',
            allowClear:true,
        });

        $("#loan_interest_method").select2({
            placeholder: 'Please select Interest Method',
            allowClear:true,
        });

        $("#loan_interest_period").select2({
            placeholder: 'Please select Interest Period',
            allowClear:true,
        });


        $("#loan_duration_period").select2({
            placeholder: 'Please select Loan Period',
            allowClear:true,
        });

        $("#loan_payment_scheme_id").select2({
            placeholder: 'Please select Loan Period',
            allowClear:true,
        });

        $("#loan_status_id").select2({
            placeholder: 'Please select Loan status',
            allowClear:true,
        });

        $("#cf_11135_branch").select2({
            placeholder: 'Please select your bank branch name',
            allowClear:true,
        });

        $('#cf_11134_bank').select2({
            placeholder: 'Please select your bank',
            allowClear:true,
        }).change(function(){
            var id = $(this).val();
            var _token = $("input[name='_token']").val();
            if(id){
                $.ajax({
                    type:"get",
                    url:"{{url('/getBranches')}}/"+id,
                    _token: _token ,
                    success:function(res) {
                        if(res) {
                            $("#cf_11135_branch").empty();
                            $.each(res,function(key, value){
                                $("#cf_11135_branch").append('<option value="">Please select your bank branch name</option>').append('<option value="'+value.branch+'" data-price="'+value.branch_code+'">'+value.branch+'</option>');
                            });
                        }
                    }

                });
            }
        });
    </script>

    @include('zamloans.immediate-zam-devi-calc')

    <script>

        $("input[data-type='account_num']").on({
            keyup: function() {
                formatAccNum($(this));
            },
            blur: function() {
                formatAccNum($(this), "blur");
            }
        });


        function formatAccNumber(n) {
            return n.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, "")
        }


        function formatAccNum(input, blur) {

            var input_val = input.val();

            if (input_val === "") { return; }

            var original_len = input_val.length;

            var caret_pos = input.prop("selectionStart");

            input_val = formatAccNumber(input_val);

            if (blur === "blur") {
                input_val;
            }

            input.val(input_val);

            var updated_len = input_val.length;
            caret_pos = updated_len - original_len + caret_pos;
            input[0].setSelectionRange(caret_pos, caret_pos);
        }
    </script>

    <script>

        $("input[data-type='currency']").on({
            keyup: function() {
                formatCurrency($(this));
            },
            blur: function() {
                formatCurrency($(this), "blur");
            }
        });


        function formatNumber(n) {
            return n.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, "")
        }


        function formatCurrency(input, blur) {

            var input_val = input.val();

            if (input_val === "") { return; }

            var original_len = input_val.length;

            var caret_pos = input.prop("selectionStart");

            if (input_val.indexOf(".") >= 0) {

                var decimal_pos = input_val.indexOf(".");

                var left_side = input_val.substring(0, decimal_pos);
                var right_side = input_val.substring(decimal_pos);

                left_side = formatNumber(left_side);

                right_side = formatNumber(right_side);

                if (blur === "blur") {
                    right_side += "00";
                }

                right_side = right_side.substring(0, 2);

                input_val = left_side + "." + right_side;

            } else {
                input_val = formatNumber(input_val);

                if (blur === "blur") {
                    input_val += ".00";
                }
            }

            input.val(input_val);

            var updated_len = input_val.length;
            caret_pos = updated_len - original_len + caret_pos;
            input[0].setSelectionRange(caret_pos, caret_pos);
        }
    </script>
@endsection
