<?php
/**
 * Created by PhpStorm for eshagitwo
 * User: VinceGee
 * Date: 5/16/2022
 * Time: 8:32 AM
 */ ?>
@extends('layouts.app')

@section('template_title')
    Apply For A Device Loan
@endsection

@section('template_linked_css')

    <link href="{{ asset('css/select2.min.css')}}" rel="stylesheet" />
@endsection

@section('content')
    <div class="page-title-box">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-md-4">
                    <h4 class="page-title mb-1">Device Loans</h4>
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{url('/my-agent-device-loans')}}">Device Loan</a></li>
                        <li class="breadcrumb-item active">Apply For eShagi Device Loan</li>
                    </ol>
                </div>

                <div class="col-md-8">
                    <div class="float-right d-none d-md-block">
                        <div>
                            <h1 class="text-white">Device Loan </h1>
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
                            {!! Form::open(array('route' => 'newdevloan', 'method' => 'POST', 'id' => 'loanForm','role' => 'form', 'class' => 'needs-validation')) !!}

                            {!! csrf_field() !!}
                            <h2 class="title-h2">Applicant Details</h2>
                            <hr>
                            <div class="row">
                                <div class="col-lg-6">
                                    <label>Client</label>
                                    <select class="form-control" id="client_id" name="client_id" required>
                                        <option value="">Select Client </option>
                                        @if ($clients)
                                            @foreach($clients as $client)
                                                <option value="{{$client->id}}" data-user_id="{{$client->user_id}}" data-credit="{{$client->cred_limit}}" data-fsb_score="{{$client->fsb_score}}" data-fsb_rating="{{$client->fsb_rating}}" data-fsb_status="{{$client->fsb_status}}" >{{ $client->first_name }} {{ $client->last_name }}  {{ $client->natid }}</option>
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

                            <h2 class="title-h2">Loan Details</h2>
                            <hr>
                            <div class="row">
                                <div class="col-lg-6">
                                    <label>Device</label>
                                    <select class="form-control" name="device" id="device">
                                        <option value="118224" selected="">Business Loan</option>
                                        <option value="136645">Infinix Hot 11 Play ZIM</option>
                                        <option value="136644">Infinix Hot 11 ZIM</option>
                                        <option value="136633">Itel A37 ZIM</option>
                                        <option value="136639">Itel A56 ZIM</option>
                                        <option value="136641">Itel P37 Pro ZIM</option>
                                        <option value="136640">Itel P37 ZIM</option>
                                        <option value="118226">Overseas Worker Loan</option>
                                        <option value="118227">Pensioner Loan</option>
                                        <option value="118223">Personal Loan</option>
                                        <option value="118225">Student Loan</option>
                                        <option value="136642">Tecno Spark 8C ZIM</option>
                                        <option value="136643">Tecno Spark ZIM</option>
                                    </select>

{{--                                    <select class="form-control" name="device" id="device">--}}
{{--                                        <option value="129273">Astro Slide</option>--}}
{{--                                        <option value="129272">Astro Virtual 2+</option>--}}
{{--                                        <option value="131212">Astro X1</option>--}}
{{--                                        <option value="126836">Bundle L55B+KXD</option>--}}
{{--                                        <option value="125398">Bundle L61+KXD</option>--}}
{{--                                        <option value="125172">Bundle L61+L63</option>--}}
{{--                                        <option value="125330">Bundle L61+X40</option>--}}
{{--                                        <option value="125399">Bundle L61+X60</option>--}}
{{--                                        <option value="125341">Bundle L63+KXD</option>--}}
{{--                                        <option value="125173">Bundle L63+X40</option>--}}
{{--                                        <option value="125410">Bundle L63+X60</option>--}}
{{--                                        <option value="129009">Bundle Tab 7+ KXD</option>--}}
{{--                                        <option value="126427">Bundle Tab 7+ L55B</option>--}}
{{--                                        <option value="127097">Bundle X60+ L55B</option>--}}
{{--                                        <option value="125377">Bundle X60+KXD</option>--}}
{{--                                        <option value="118224">Business Loan</option>--}}
{{--                                        <option value="131703">Genesis Phab</option>--}}
{{--                                        <option value="129269">GENESIS S8</option>--}}
{{--                                        <option value="133113">Infinix Hot 11</option>--}}
{{--                                        <option value="133114">Infinix Hot 11 Play</option>--}}
{{--                                        <option value="133115">Itel A37</option>--}}
{{--                                        <option value="133116">Itel A58</option>--}}
{{--                                        <option value="133127">Itel P37</option>--}}
{{--                                        <option value="133128">Itel P37 Pro</option>--}}
{{--                                        <option value="122821">KXD A1</option>--}}
{{--                                        <option value="122818">LOGIC L55B</option>--}}
{{--                                        <option value="122817">LOGIC L61</option>--}}
{{--                                        <option value="125171">LOGIC L63</option>--}}
{{--                                        <option value="122820">LOGIC X40</option>--}}
{{--                                        <option value="122819">LOGIC X60 PLUS</option>--}}
{{--                                        <option value="129270">Mercury</option>--}}
{{--                                        <option value="129271">Mercury Nova</option>--}}
{{--                                        <option value="127325">Mercury Tab 7</option>--}}
{{--                                        <option value="118226">Overseas Worker Loan</option>--}}
{{--                                        <option value="118227">Pensioner Loan</option>--}}
{{--                                        <option value="118223">Personal Loan</option>--}}
{{--                                        <option value="118225">Student Loan</option>--}}
{{--                                        <option value="133111">Tecno Camon 18</option>--}}
{{--                                        <option value="133110">Tecno Phantom X</option>--}}
{{--                                        <option value="132937">Tecno Spark 8C</option>--}}
{{--                                        <option value="133109">Tecno Spark 8P</option>--}}
{{--                                        <option value="133112">TecnoCamon 18(128+8)</option>--}}
{{--                                    </select>--}}
                                </div>

                                <div class="col-lg-6">
                                    <label>Product Price</label>
                                    <div class="input-group">
                                        <input class="input-group-addon form-control col-lg-2" value="USD" readonly>
                                        <input type="number" step=0.01 class="form-control col-lg-10" name="amount" id="amount" placeholder="Credit item cost" value="{{old('amount')}}">
                                    </div>
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
                                    <label>Interest Method (Interest will be percentage (%) based)</label>
                                    <select class="form-control" name="loan_interest_method" id="loan_interest_method" required="">
                                        <option value="flat_rate"> Flat Rate</option>
                                    </select>
                                </div>
                            </div>
                            <br>
                            <div class="row">

                                <div class="col-lg-6">
                                    <label>Loan Interest</label>
                                    <input class="form-control {{ $errors->has('loan_interest') ? ' is-invalid' : '' }}" type="number" name="loan_interest" id="loan_interest" required="required" autocomplete="off">

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
                                    <label>Repayment Cycle</label>
                                    <select class="form-control" id="loan_payment_scheme_id" name="loan_payment_scheme_id" required>
                                        <option value="3">Monthly</option>

                                    </select>
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
                                    <label>Number of Repayments</label>
                                    <input class="form-control {{ $errors->has('loan_num_of_repayments') ? ' is-invalid' : '' }}" type="number" name="loan_num_of_repayments" id="loan_num_of_repayments" value="{{ old('loan_num_of_repayments') }}" required="required" placeholder="e.g. 6" autocomplete="off">

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
                            <div class="row">
                                <div class="col-lg-6">
                                    <label>Deposit Paid (in %)</label>
                                    <input type="text" class="form-control col-lg-12" pattern="^\d{1,3}*(\.\d+)?$" data-type="currency" name="deposit_prct" id="deposit_prct" placeholder="e.g. 30" value="{{old('deposit_prct')}}">
                                </div>

                                <div class="col-lg-6">
                                    <label>Repayment Period</label>
                                    <select onchange="calculate();" class="form-control" id="paybackPeriod" name="paybackPeriod">
                                        <option value="">Select repayment period</option>
                                        <option value="3">3 months</option>
                                        <option value="6">6 months</option>
                                    </select>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-lg-6">
                                    <label>Release Date</label>
                                    <input class="form-control datepicker-here{{ $errors->has('loan_released_date') ? ' is-invalid' : '' }}" data-language="en" data-date-format="dd-mm-yyyy" type="text" name="loan_released_date" id="loan_released_date" value="{{ old('loan_released_date') }}" required="required" placeholder="Enter loan released date..." autocomplete="off">
                                </div>

                                <div class="col-lg-6">
                                    <label>Approval Date</label>
                                    <input class="form-control datepicker-here{{ $errors->has('cf_11133_approval_date') ? ' is-invalid' : '' }}" data-language="en" data-date-format="dd-mm-yyyy" type="text" name="cf_11133_approval_date" id="cf_11133_approval_date" value="{{ old('cf_11133_approval_date') }}" required="required" placeholder="Choose Approval date" autocomplete="off">

                                </div>
                            </div>
                            <br>
                            <h2 class="title-h2">Credit Overview</h2>
                            <hr>
                            <div class="row">
                                <div class="col-lg-6">
                                    <p>Credit Score: <input class="{{ $errors->has('fsb_score') ? ' is-invalid' : '' }} col-lg-6" style="border: none;width: fit-content;" type="text" name="fsb_score" id="fsb_score" required value="{{ old('fsb_score') }}" placeholder="" readonly></p>
                                    <p>Credit Rating: <input class="{{ $errors->has('fsb_rating') ? ' is-invalid' : '' }} col-lg-6" style="border: none;width: fit-content;" type="text" name="fsb_rating" id="fsb_rating" required value="{{ old('fsb_rating') }}" placeholder="" readonly></p>
                                </div>

                                <div class="col-lg-6">
                                    <p>Credit Status: <input class="{{ $errors->has('fsb_status') ? ' is-invalid' : '' }} col-lg-6" style="border: none;width: fit-content;" type="text" name="fsb_status" id="fsb_status" required value="{{ old('fsb_status') }}" placeholder="" readonly></p>
                                </div>
                            </div>
                            <br>

                            <h2 class="title-h2">Loan Overview</h2>
                            <hr>
                            <div class="row">
                                <div class="col-lg-6">
                                    <p>Monthly Repayment : USD<input class="{{ $errors->has('monthly') ? ' is-invalid' : '' }} col-lg-6" style="border: none;width: fit-content;" type="text" name="monthly" id="monthly" required value="{{ old('monthly') }}" placeholder="0"></p>
                                    <p>Payment Period Rate :   <input class="{{ $errors->has('tenure') ? ' is-invalid' : '' }} col-lg-1" style="border: none;width: 100%;" type="text" name="tenure" id="tenure" required value="{{ old('tenure') }}" placeholder="0" readonly>Months</p>
                                </div>

                                <div class="col-lg-6">
{{--                                    <p>Interest Rate :          <input class="{{ $errors->has('interestRate') ? ' is-invalid' : '' }} col-lg-1" style="border: none;width: 100%;" type="text" name="interestRate" id="interestRate" required value="{{ old('interestRate') }}" placeholder="{!! getDeviceInterestRate() !!}" readonly>%</p>--}}

                                    {{--                                    <p>Amount Disbursed : USD<input class="{{ $errors->has('disbursed') ? ' is-invalid' : '' }} col-lg-6" style="border: none;width: fit-content;" type="text" name="disbursed" id="disbursed" required value="{{ old('disbursed') }}" placeholder="0" readonly></p>--}}
                                    {{--                                    <p>Application Fee :  USD<input class="{{ $errors->has('appFee') ? ' is-invalid' : '' }} col-lg-6" style="border: none;width: fit-content;" type="text" name="appFee" id="appFee" required value="{{ old('appFee') }}" placeholder="0" readonly></p>--}}
                                    {{--                                    <p>Disbursement Fee: USD<input class="{{ $errors->has('disbursefee') ? ' is-invalid' : '' }} col-lg-6" style="border: none;width: fit-content;" type="text" name="disbursefee" id="disbursefee" required value="{{ old('disbursefee') }}" placeholder="0" readonly></p>--}}
                                    {{--                                --}}
                                </div>

                            </div>

                            {{--                            <div class="row">--}}
                            {{--                                <div class="col-lg-6">--}}
                            {{--                                </div>--}}

                            {{--                                <div class="col-lg-6">--}}
                            {{--                                    <p><strong>Total Charges : </strong>    USD<input class="{{ $errors->has('charges') ? ' is-invalid' : '' }} col-lg-6" style="border: none;width: fit-content;" type="text" name="charges" id="charges" required value="{{ old('charges') }}" placeholder="0" readonly></p>--}}
                            {{--                                </div>--}}

                            {{--                            </div>--}}

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
        $("#paybackPeriod").select2({
            placeholder: 'Please select a Payback period.',
            allowClear:true,
        });

        $("#partner_id").select2({
            placeholder: 'Please select the merchant you are applying with.',
            allowClear:true,
        });

        $("#loan_product_id").select2({
            placeholder: 'Please select a product',
            allowClear:true,
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

        $("#device").select2({
            placeholder: 'Please select a product.',
            allowClear:true,
        }).on('change',function(){
            var amount = $(this).children('option:selected').data('price');
            $('#amount').val(amount);
        });

        $("#client_id").select2({
            placeholder: 'Please select a client.',
            allowClear:true,
        }).on('change',function(){
            var credit = $(this).children('option:selected').data('credit');
            var fsb_score = $(this).children('option:selected').data('fsb_score');
            var fsb_rating = $(this).children('option:selected').data('fsb_rating');
            var fsb_status = $(this).children('option:selected').data('fsb_status');
            var user_id = $(this).children('option:selected').data('user_id');
            $('#cred_limit').val(credit);
            $('#fsb_score').val(fsb_score);
            $('#fsb_rating').val(fsb_rating);
            $('#fsb_status').val(fsb_status);
            $('#user_id').val(user_id);
        });
    </script>

    <script type="text/javascript">
        function validateCreditLimit() {
            var amount=document.getElementById("amount").value;
            var creditLimit= 0;
            if(amount>creditLimit) {
                alert("Please do not exceed your maximum credit limit of ZWL$"+creditLimit);
                document.getElementById("amount").value=0;
            }
        }

    </script>
    @include('deviceloans.calculate-device-loan')

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
            // format number 1000000 to 1,234,567
            return n.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, "")
        }


        function formatCurrency(input, blur) {
            // appends $ to value, validates decimal side
            // and puts cursor back in right position.

            // get input value
            var input_val = input.val();

            // don't validate empty input
            if (input_val === "") { return; }

            // original length
            var original_len = input_val.length;

            // initial caret position
            var caret_pos = input.prop("selectionStart");

            // check for decimal
            if (input_val.indexOf(".") >= 0) {

                // get position of first decimal
                // this prevents multiple decimals from
                // being entered
                var decimal_pos = input_val.indexOf(".");

                // split number by decimal point
                var left_side = input_val.substring(0, decimal_pos);
                var right_side = input_val.substring(decimal_pos);

                // add commas to left side of number
                left_side = formatNumber(left_side);

                // validate right side
                right_side = formatNumber(right_side);

                // On blur make sure 2 numbers after decimal
                if (blur === "blur") {
                    right_side += "00";
                }

                // Limit decimal to only 2 digits
                right_side = right_side.substring(0, 2);

                // join number by .
                input_val = left_side + "." + right_side;

            } else {
                // no decimal entered
                // add commas to number
                // remove all non-digits
                input_val = formatNumber(input_val);
                //input_val = input_val;

                // final formatting
                if (blur === "blur") {
                    input_val += ".00";
                }
            }

            // send updated string to input
            input.val(input_val);

            // put caret back in the right position
            var updated_len = input_val.length;
            caret_pos = updated_len - original_len + caret_pos;
            input[0].setSelectionRange(caret_pos, caret_pos);
        }
    </script>


@endsection
