<?php
/**
 *Created by PhpStorm for eshagi
 *User: Vincent Guyo
 *Date: 11/8/2020
 *Time: 6:24 PM
 */

?>
@extends('layouts.app')

@section('template_title')
    Add Product
@endsection

@section('template_linked_css')
    <link href="{{ asset('css/select2.min.css')}}" rel="stylesheet" />
@endsection

@section('content')
    <div class="page-title-box">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h4 class="page-title mb-1">Products</h4>
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{url('/products')}}">Products</a></li>
                        <li class="breadcrumb-item active">Add Products</li>
                    </ol>
                </div>

                <div class="col-md-4">
                    <div class="float-right d-none d-md-block">
                        <div>
                            <a class="btn btn-light btn-rounded" href="{{url('/our-products')}}" type="button">
                                <i class="mdi mdi-keyboard-backspace mr-1"></i>Back to Products
                            </a>
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
                            {!! Form::open(array('route' => 'products.store', 'method' => 'POST', 'enctype'=>'multipart/form-data', 'role' => 'form', 'class' => 'needs-validation')) !!}

                            {!! csrf_field() !!}

                            <input type="hidden" name="creator" value="{{auth()->user()->name}}">

                            <div class="row">
                                <div class="col-lg-6 ml-5">
                                    <div class="form-group has-feedback row {{ $errors->has('loandevice') ? ' has-error ' : '' }}">
                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <input class="form-check-input" type="checkbox" name="loandevice" id="loandevice" >
                                                <label class="form-check-label" for="loandevice">
                                                    Make Product available for loans?
                                                </label>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                                <div class="col-lg-6">
                                </div>
                            </div>

                            <div class="form-group has-feedback row {{ $errors->has('category_id') ? ' has-error ' : '' }}">
                                {!! Form::label('category_id', 'Product Category', array('class' => 'col-md-3 control-label')); !!}
								<div class="col-md-9">
                                    <div class="input-group">
                                       {!! Form::select('category_id', \App\Models\Category::all()->pluck('category_name', 'id')->toArray(), null, ['class' => 'form-control']) !!}
                                    </div>
                                    @if ($errors->has('category_id'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('category_id') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

							<div class="form-group has-feedback row {{ $errors->has('pcode') ? ' has-error ' : '' }}">
                                {!! Form::label('pcode', 'Product Code', array('class' => 'col-md-3 control-label')); !!}
                                <div class="col-md-9">
                                    <div class="input-group">
                                        {!! Form::text('pcode', NULL, array('id' => 'pcode', 'class' => 'form-control', 'placeholder' => 'e.g. EC225', 'required')) !!}
                                    </div>
                                    @if ($errors->has('pcode'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('pcode') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group has-feedback row {{ $errors->has('serial') ? ' has-error ' : '' }}">
                                {!! Form::label('serial', 'Serial Number', array('class' => 'col-md-3 control-label')); !!}
                                <div class="col-md-9">
                                    <div class="input-group">
                                        {!! Form::text('serial', NULL, array('id' => 'serial', 'class' => 'form-control', 'placeholder' => 'e.g. 1122334455', 'required')) !!}
                                    </div>
                                    @if ($errors->has('serial'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('serial') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group has-feedback row {{ $errors->has('pname') ? ' has-error ' : '' }}">
                                {!! Form::label('pname', 'Product Name', array('class' => 'col-md-3 control-label')); !!}
                                <div class="col-md-9">
                                    <div class="input-group">
                                        {!! Form::text('pname', NULL, array('id' => 'pname', 'class' => 'form-control', 'placeholder' => 'e.g. Collar Dot', 'required')) !!}
                                    </div>
                                    @if ($errors->has('pname'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('pname') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group has-feedback row {{ $errors->has('model') ? ' has-error ' : '' }}">
                                {!! Form::label('model', 'Model', array('class' => 'col-md-3 control-label')); !!}
                                <div class="col-md-9">
                                    <div class="input-group">
                                        {!! Form::text('model', NULL, array('id' => 'model', 'class' => 'form-control', 'placeholder' => 'e.g. CD3345', 'required')) !!}
                                    </div>
                                    @if ($errors->has('model'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('model') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group has-feedback row {{ $errors->has('descrip') ? ' has-error ' : '' }}">
                                {!! Form::label('descrip', 'Description', array('class' => 'col-md-3 control-label')); !!}
                                <div class="col-md-9">
                                    <div class="input-group">
                                        {!! Form::text('descrip', NULL, array('id' => 'descrip', 'class' => 'form-control', 'placeholder' => 'e.g. A brand new collar', 'required')) !!}
                                    </div>
                                    @if ($errors->has('descrip'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('descrip') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group has-feedback row {{ $errors->has('price') ? ' has-error ' : '' }}">
                                {!! Form::label('price', 'Price', array('class' => 'col-md-3 control-label')); !!}
                                <div class="col-md-9">
                                    <div class="input-group">
                                        {!! Form::text('price', NULL, array('id' => 'price', 'class' => 'form-control','pattern'=>'^\d{1,3}*(\.\d+)?$', 'value'=>"", 'data-type'=>"currency",'placeholder' => 'e.g. 2512.23', 'required')) !!}
                                    </div>
                                    @if ($errors->has('price'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('price') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

							<div class="form-group has-feedback row {{ $errors->has('product_image') ? ' has-error ' : '' }}">
                                {!! Form::label('product_image', 'Product Image', array('class' => 'col-md-3 control-label')); !!}
								<div class="col-md-9">
                                    <div class="input-group">
                                       {!! Form::file('product_image', NULL, array('id' => 'product_image', 'class' => 'form-control', 'required')) !!}
                                    </div>
                                    @if ($errors->has('product_image'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('product_image') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <hr><br>
                            <div class="form-group has-feedback row {{ $errors->has('img_1') ? ' has-error ' : '' }}">
                                {!! Form::label('img_1', 'Additional Images', array('class' => 'col-md-3 control-label')); !!}
                                <div class="col-md-9">
                                    <div class="input-group">
                                        {!! Form::file('img_1', NULL, array('id' => 'ing_1', 'class' => 'form-control', 'required')) !!}
                                    </div>
                                    @if ($errors->has('img_1'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('img_1') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div id="targetElement">

                            </div>

                            <div class="form-group has-feedback row" id="addBtn">
                                {!! Form::label('img_1', 'Image', array('class' => 'col-md-3 control-label')); !!}
                                <div class="col-md-9">
                                    <div class="input-group">
                                        <button id="addImage" type="button" class="btn btn-primary">+</button>
                                    </div>
                                </div>
                            </div>


                            {{-- <div class="form-group has-feedback row {{ $errors->has('usd_price') ? ' has-error ' : '' }}">
                                {!! Form::label('usd_price', 'USD Price', array('class' => 'col-md-3 control-label')); !!}
                                <div class="col-md-9">
                                    <div class="input-group">
                                        {!! Form::text('usd_price', NULL, array('id' => 'usd_price', 'class' => 'form-control','pattern'=>'^\d{1,3}*(\.\d+)?$', 'value'=>"", 'data-type'=>"currency",'placeholder' => 'e.g. 152.23')) !!}
                                    </div>
                                    <span class="help-block text-info">
                                            <strong>Only give the USD price if your prices are in USD and you would want to periodically change the exchange rate.</strong>
                                        </span>
                                    @if ($errors->has('usd_price'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('usd_price') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div> --}}
                            <input type="hidden" name="imageNumber" class="imageNumber">
                            {!! Form::button('Add Product', array('class' => 'btn btn-success margin-bottom-1 mb-1 float-right','type' => 'submit' )) !!}
                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('footer_scripts')

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

        $(document).ready(function() {
            var imgCounter = 1;
            if (imgCounter < 5){
                $('#addImage').click(function () {
                    console.log(imgCounter)
                    var imgID = 'img_' + imgCounter; // Generate the dynamic ID
                    var htmlCode = `
                                <div class="form-group has-feedback row {{ $errors->has('${imgID}') ? ' has-error ' : '' }}">
                                   {!! Form::label('${imgID}', 'Image', array('class' => 'col-md-3 control-label')); !!}
                    <div class="col-md-9">
                    <div class="input-group">
{!! Form::file('${imgID}', NULL, array('id' => '${imgID}', 'class' => 'form-control', 'required')) !!}
                    </div>
@if ($errors->has('${imgID}'))
                    <span class="help-block">
                    <strong>{{ $errors->first('${imgID}') }}</strong>
                                      </span>
                                @endif
                    </div>
                    </div>
`;

                    $('#targetElement').append(htmlCode);
                    imgCounter++;
                });
                }else {
                $('#addBtn').hide()
            }
        });
    </script>
@endsection
