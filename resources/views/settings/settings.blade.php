<?php
/**
 * Created by PhpStorm for eshagi
 * User: vinceg
 * Date: 9/2/2021
 * Time: 21:15
 */
?>
@extends('layouts.app')

@section('template_title')
    System Settings
@endsection


@section('template_linked_css')

    <link href="{{ asset('css/select2.min.css')}}" rel="stylesheet" />
@endsection

@section('content')
    <div class="page-title-box">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h4 class="page-title mb-1">Global System Settings</h4>
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">System Settings</a></li>
                        <li class="breadcrumb-item active">Settings</li>
                    </ol>
                </div>

                <div class="col-md-4">
                    <div class="float-right d-none d-md-block">

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
                            <div class="card-body">
                                <h4 class="header-title">Configure the eShagi Global System Settings </h4>
                                <p class="card-title-desc">By clicking update, eShagi will update all the settings</p>

                                {!! Form::open(array('route' => ['settings.update',$masettings->id], 'method' => 'PUT', 'role' => 'form', 'class' => 'needs-validation', 'novalidate')) !!}

                                {!! csrf_field() !!}

                                <div class="row">
                                    <div class="col-xl-12">
                                        <div class="card">
                                            <div class="card-body">
                                                <!-- Nav tabs -->
                                                <ul class="nav nav-tabs nav-justified nav-tabs-custom" role="tablist">
                                                    <li class="nav-item waves-effect waves-light">
                                                        <a class="nav-link active" data-toggle="tab" href="#rates-justify" role="tab">
                                                            <i class="fas fa-percentage mr-1"></i> <span class="d-none d-md-inline-block">Rates</span>
                                                        </a>
                                                    </li>
                                                    <li class="nav-item waves-effect waves-light">
                                                        <a class="nav-link" data-toggle="tab" href="#creds-justify" role="tab">
                                                            <i class="fas fa-lock mr-1"></i> <span class="d-none d-md-inline-block">Credentials</span>
                                                        </a>
                                                    </li>
                                                    <li class="nav-item waves-effect waves-light">
                                                        <a class="nav-link" data-toggle="tab" href="#urls-justify" role="tab">
                                                            <i class="fas fa-globe-africa mr-1"></i> <span class="d-none d-md-inline-block">Base URLs & Authorization</span>
                                                        </a>
                                                    </li>
                                                    <li class="nav-item waves-effect waves-light">
                                                        <a class="nav-link" data-toggle="tab" href="#fees-justify" role="tab">
                                                            <i class="fas fa-money-bill-alt mr-1"></i> <span class="d-none d-md-inline-block">Fees</span>
                                                        </a>
                                                    </li>
                                                </ul>

                                                <!-- Tab panes -->
                                                <div class="tab-content p-3">
                                                    <div class="tab-pane active" id="rates-justify" role="tabpanel">
                                                        <div class="row">
                                                            <div class="col-sm-6">
                                                                <label for="interest">Loan Interest Rate*</label>
                                                                <input type="text" class="form-control" id="interest" name="interest" placeholder="e.g. 15.00" value="{{$masettings->interest}}" pattern="^\d{1,3}*(\.\d+)?$" data-type="currency" required>
                                                                @if ($errors->has('interest'))
                                                                    <span class="help-block text-danger">
                                                                        <strong>{{ $errors->first('interest') }}</strong>
                                                                    </span>
                                                                @endif
                                                            </div>
                                                            <div class="col-sm-6">
                                                                <label for="self_interest">Self-Financed Loans Interest Rate* (e.g. Zambia Loans)</label>
                                                                <input type="text" class="form-control" id="self_interest" name="self_interest" placeholder="e.g. 15.00" value="{{$masettings->self_interest}}" pattern="^\d{1,3}*(\.\d+)?$" data-type="currency" required>
                                                                @if ($errors->has('self_interest'))
                                                                    <span class="help-block text-danger">
                                                                        <strong>{{ $errors->first('self_interest') }}</strong>
                                                                    </span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                        <br>

                                                        <div class="row">
                                                            <div class="col-sm-6">
                                                                <label for="creditRate">Credit Limit Rate*</label>
                                                                <input type="text" class="form-control" id="creditRate" name="creditRate" placeholder="e.g. 3.1" value="{{$masettings->creditRate}}" pattern="^\d{1,3}*(\.\d+)?$" data-type="currency" required>
                                                                @if ($errors->has('creditRate'))
                                                                    <span class="help-block text-danger">
                                                                        <strong>{{ $errors->first('creditRate') }}</strong>
                                                                    </span>
                                                                @endif
                                                            </div>
                                                            <div class="col-sm-6">
                                                                    <label>Default Loan Interest Method (Interest will be percentage (%) based)</label>
                                                                    <select class="form-control" name="loan_interest_method" id="loan_interest_method" required="">
                                                                        <option value="flat_rate" {{ $masettings->loan_interest_method == 'flat_rate' ? 'selected="selected"' : '' }}> Flat Rate</option>
                                                                        <option value="reducing_rate_equal_installments" {{ $masettings->loan_interest_method == 'reducing_rate_equal_installments' ? 'selected="selected"' : '' }}> Reducing Balance - Equal Installments</option>
                                                                        <option value="reducing_rate_equal_principal" {{ $masettings->loan_interest_method == 'reducing_rate_equal_principal' ? 'selected="selected"' : '' }}> Reducing Balance - Equal Principal</option>
                                                                        <option value="interest_only" {{ $masettings->loan_interest_method == 'interest_only' ? 'selected="selected"' : '' }}> Interest-Only</option>
                                                                        <option value="compound_interest" {{ $masettings->loan_interest_method == 'compound_interest' ? 'selected="selected"' : '' }}> Compound Interest</option>
                                                                    </select>
                                                            </div>
                                                        </div>
                                                        <br>

                                                        <div class="row">
                                                            <div class="col-sm-6">
                                                                <label for="om_interest">Old Mutual (Musoni) Interest Rate*</label>
                                                                <input type="text" class="form-control" id="om_interest" name="om_interest" placeholder="e.g. 12" value="{{$masettings->om_interest}}" pattern="^\d{1,3}*(\.\d+)?$" data-type="currency" required>
                                                                @if ($errors->has('om_interest'))
                                                                    <span class="help-block text-danger">
                                                                        <strong>{{ $errors->first('om_interest') }}</strong>
                                                                    </span>
                                                                @endif
                                                            </div>
                                                            <div class="col-sm-6">
                                                                <label for="device_interest">Device Financing Interest Rate*</label>
                                                                <input type="text" class="form-control" id="device_interest" name="device_interest" placeholder="e.g. 12" value="{{$masettings->device_interest}}" pattern="^\d{1,3}*(\.\d+)?$" data-type="currency" required>
                                                                @if ($errors->has('device_interest'))
                                                                    <span class="help-block text-danger">
                                                                        <strong>{{ $errors->first('device_interest') }}</strong>
                                                                    </span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                        <br>

                                                        <div class="row">
                                                            <div class="col-sm-6">
                                                                <label for="usd_interest">USD Interest Rate*</label>
                                                                <input type="text" class="form-control" id="usd_interest" name="usd_interest" placeholder="e.g. 12" value="{{$masettings->usd_interest}}" pattern="^\d{1,3}*(\.\d+)?$" data-type="currency" required>
                                                                @if ($errors->has('usd_interest'))
                                                                    <span class="help-block text-danger">
                                                                        <strong>{{ $errors->first('usd_interest') }}</strong>
                                                                    </span>
                                                                @endif
                                                            </div>
                                                            <div class="col-sm-6">

                                                            </div>
                                                        </div>
                                                        <br>

                                                        <br>
                                                        <div class="row">
                                                            <div class="col-sm-6">
                                                                <label for="interest">Call Centre Weekly Target</label>
                                                                <input type="text" class="form-control" id="weekly_target" name="weekly_target" placeholder="e.g. 33" value="{{$masettings->weekly_target}}" pattern="^\d{1,3}*(\.\d+)?$" data-type="currency" required>
                                                                @if ($errors->has('weekly_target'))
                                                                    <span class="help-block text-danger">
                                                                        <strong>{{ $errors->first('weekly_target') }}</strong>
                                                                    </span>
                                                                @endif
                                                            </div>

                                                            <div class="col-sm-6">
                                                                <label for="interest">Number of Leads to Auto Allocate to agents</label>
                                                                <input type="text" class="form-control" id="leads_allocation" name="leads_allocation" placeholder="e.g. 20" value="{{$masettings->leads_allocation}}" pattern="^\d{1,3}*(\.\d+)?$" data-type="currency" required>
                                                                @if ($errors->has('leads_allocation'))
                                                                    <span class="help-block text-danger">
                                                                        <strong>{{ $errors->first('leads_allocation') }}</strong>
                                                                    </span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                        <br>
                                                    </div>

                                                    <div class="tab-pane" id="creds-justify" role="tabpanel">
                                                        <div class="row">
                                                            <div class="col-md-6 mb-3">
                                                                <label for="fcb_username">FCB Username*</label>
                                                                <input type="text" class="form-control" id="fcb_username" name="fcb_username" placeholder="e.g. fcbcreds@cbz.co.zw" value="{{$masettings->fcb_username}}" required>
                                                                @if ($errors->has('fcb_username'))
                                                                    <span class="help-block text-danger">
                                                                        <strong>{{ $errors->first('fcb_username') }}</strong>
                                                                    </span>
                                                                @endif
                                                            </div>

                                                            <div class="col-md-6 mb-3">
                                                                <label for="fcb_password">FCB Password*</label>
                                                                <input type="text" class="form-control" id="fcb_password" name="fcb_password" placeholder="e.g. SecurePassw0rd!" value="{{$masettings->fcb_password}}" required>
                                                                @if ($errors->has('fcb_password'))
                                                                    <span class="help-block text-danger">
                                                                        <strong>{{ $errors->first('fcb_password') }}</strong>
                                                                    </span>
                                                                @endif
                                                            </div>

                                                        </div>

                                                        <div class="row">
                                                            <div class="col-md-6 mb-3">
                                                                <label for="reds_username">RedSphere API Username*</label>
                                                                <input type="text" class="form-control" id="reds_username" name="reds_username" placeholder="e.g. redspherecreds@cbz.co.zw" value="{{$masettings->reds_username}}" required>
                                                                @if ($errors->has('reds_username'))
                                                                    <span class="help-block text-danger">
                                                                        <strong>{{ $errors->first('reds_username') }}</strong>
                                                                    </span>
                                                                @endif
                                                            </div>

                                                            <div class="col-md-6 mb-3">
                                                                <label for="reds_password">RedSphere API Password*</label>
                                                                <input type="text" class="form-control" id="reds_password" name="reds_password" placeholder="e.g. SecurePassw0rd!" value="{{$masettings->reds_password}}" required>
                                                                @if ($errors->has('reds_password'))
                                                                    <span class="help-block text-danger">
                                                                        <strong>{{ $errors->first('reds_password') }}</strong>
                                                                    </span>
                                                                @endif
                                                            </div>

                                                        </div>

                                                        <div class="row">
                                                            <div class="col-md-6 mb-3">
                                                                <label for="ndas_username">Ndasenda API Username*</label>
                                                                <input type="text" class="form-control" id="ndas_username" name="ndas_username" placeholder="e.g. ndasendacreds@ndasenda.co.zw" value="{{$masettings->ndas_username}}" required>
                                                                @if ($errors->has('ndas_username'))
                                                                    <span class="help-block text-danger">
                                                                        <strong>{{ $errors->first('ndas_username') }}</strong>
                                                                    </span>
                                                                @endif
                                                            </div>

                                                            <div class="col-md-6 mb-3">
                                                                <label for="ndas_password">Ndasenda API Password*</label>
                                                                <input type="text" class="form-control" id="ndas_password" name="ndas_password" placeholder="e.g. SecurePassw0rd!" value="{{$masettings->ndas_password}}" required>
                                                                @if ($errors->has('ndas_password'))
                                                                    <span class="help-block text-danger">
                                                                        <strong>{{ $errors->first('ndas_password') }}</strong>
                                                                    </span>
                                                                @endif
                                                            </div>

                                                        </div>

                                                        <div class="row">
                                                            <div class="col-md-4 mb-3">
                                                                <label for="crb_infinity_code">CRB Infinity Code*</label>
                                                                <input type="text" class="form-control" id="crb_infinity_code" name="crb_infinity_code" placeholder="e.g. 123456" value="{{$masettings->crb_infinity_code}}" required>
                                                                @if ($errors->has('crb_infinity_code'))
                                                                    <span class="help-block text-danger">
                                                                        <strong>{{ $errors->first('crb_infinity_code') }}</strong>
                                                                    </span>
                                                                @endif
                                                            </div>

                                                            <div class="col-md-4 mb-3">
                                                                <label for="crb_username">CRB API Username*</label>
                                                                <input type="text" class="form-control" id="crb_username" name="crb_username" placeholder="e.g. Administrator" value="{{$masettings->crb_username}}" required>
                                                                @if ($errors->has('crb_username'))
                                                                    <span class="help-block text-danger">
                                                                        <strong>{{ $errors->first('crb_username') }}</strong>
                                                                    </span>
                                                                @endif
                                                            </div>

                                                            <div class="col-md-4 mb-3">
                                                                <label for="crb_password">CRB API Password*</label>
                                                                <input type="text" class="form-control" id="crb_password" name="crb_password" placeholder="e.g. SecurePassw0rd!" value="{{$masettings->crb_password}}" required>
                                                                @if ($errors->has('crb_password'))
                                                                    <span class="help-block text-danger">
                                                                        <strong>{{ $errors->first('crb_password') }}</strong>
                                                                    </span>
                                                                @endif
                                                            </div>

                                                        </div>
                                                    </div>

                                                    <div class="tab-pane" id="urls-justify" role="tabpanel">
                                                        <div class="row">
                                                            <div class="col-md-12 mb-3">
                                                                <label for="bulksmsweb_baseurl">BulkSMS Base URL & Parameters*</label>
                                                                <input type="text" class="form-control" id="bulksmsweb_baseurl" name="bulksmsweb_baseurl" placeholder="e.g. http://portal.bulksms.com" value="{{$masettings->bulksmsweb_baseurl}}" required>
                                                                @if ($errors->has('bulksmsweb_baseurl'))
                                                                    <span class="help-block text-danger">
                                                                        <strong>{{ $errors->first('bulksmsweb_baseurl') }}</strong>
                                                                    </span>
                                                                @endif
                                                            </div>
                                                        </div>

                                                        <div class="row">
                                                            <div class="col-md-6 mb-3">
                                                                <label for="signing_ceo">Signing CEO*</label>
                                                                <input type="text" class="form-control" id="signing_ceo" name="signing_ceo" placeholder="e.g. John Doe" value="{{$masettings->signing_ceo}}" required>
                                                                @if ($errors->has('signing_ceo'))
                                                                    <span class="help-block text-danger">
                                                                        <strong>{{ $errors->first('signing_ceo') }}</strong>
                                                                    </span>
                                                                @endif
                                                            </div>
                                                            <div class="col-md-6 mb-3">
                                                                <label for="ceo_encoded_signature">CEO's Signature*</label>
                                                                <input type="text" class="form-control" id="ceo_encoded_signature" name="ceo_encoded_signature" placeholder="e.g. my_signature" value="{{$masettings->ceo_encoded_signature}}" required>
                                                                @if ($errors->has('ceo_encoded_signature'))
                                                                    <span class="help-block text-danger">
                                                                        <strong>{{ $errors->first('ceo_encoded_signature') }}</strong>
                                                                    </span>
                                                                @endif
                                                            </div>
                                                        </div>

                                                        <div class="row">
                                                            <div class="col-md-12 mb-3">
                                                                <label for="cbz_authorizer">CBZ Authorized Personnel to authorize KYCs*</label>
                                                                <input type="text" class="form-control" id="cbz_authorizer" name="cbz_authorizer" placeholder="e.g. John Doe" value="{{$masettings->cbz_authorizer}}" required>
                                                                @if ($errors->has('cbz_authorizer'))
                                                                    <span class="help-block text-danger">
                                                                        <strong>{{ $errors->first('cbz_authorizer') }}</strong>
                                                                    </span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="tab-pane" id="fees-justify" role="tabpanel">
                                                        <div class="row">
                                                            <div class="col-sm-6">
                                                                <label for="device_penalty">Device Loan Penalty*</label>
                                                                <input type="text" class="form-control" id="device_penalty" name="device_penalty" placeholder="e.g. 1500.00" value="{{$masettings->device_penalty}}" pattern="^\d{1,3}*(\.\d+)?$" data-type="currency" required>
                                                                @if ($errors->has('device_penalty'))
                                                                    <span class="help-block text-danger">
                                                                        <strong>{{ $errors->first('device_penalty') }}</strong>
                                                                    </span>
                                                                @endif
                                                            </div>

                                                            <div class="col-sm-6">
                                                                <label for="loan_penalty">Normal Loan Penalty*</label>
                                                                <input type="text" class="form-control" id="loan_penalty" name="loan_penalty" placeholder="e.g. 1200.00" value="{{$masettings->loan_penalty}}" pattern="^\d{1,3}*(\.\d+)?$" data-type="currency" required>
                                                                @if ($errors->has('loan_penalty'))
                                                                    <span class="help-block text-danger">
                                                                        <strong>{{ $errors->first('loan_penalty') }}</strong>
                                                                    </span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                        <br>
                                                        <div class="row">
                                                            <div class="col-sm-6">
                                                                <label for="zam_dev_upfront_fee">Zambia Device Upfront Fee(K)*</label>
                                                                <input type="text" class="form-control" id="zam_dev_upfront_fee" name="zam_dev_upfront_fee" placeholder="e.g. 1500.00" value="{{$masettings->zam_dev_upfront_fee}}" pattern="^\d{1,3}*(\.\d+)?$" data-type="currency" required>
                                                                @if ($errors->has('zam_dev_upfront_fee'))
                                                                    <span class="help-block text-danger">
                                                                        <strong>{{ $errors->first('zam_dev_upfront_fee') }}</strong>
                                                                    </span>
                                                                @endif
                                                            </div>

                                                            <div class="col-sm-6">

                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label for="last_changed_by">Last Modified By</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control" id="last_changed_by" placeholder="e.g. root" name="last_changed_by" value="{{$masettings->last_changed_by}}" aria-describedby="inputGroupPrepend" readonly>

                                        </div>
                                    </div>
                                </div>


                                {!! Form::button('Update Settings', array('class' => 'btn btn-primary float-right','type' => 'submit' )) !!}
                                {!! Form::close() !!}
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
    <script>
        $("#loan_interest_method").select2({
            placeholder: 'Please select Interest Method',
            allowClear:true,
        });

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
