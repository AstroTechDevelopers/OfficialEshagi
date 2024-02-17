<?php
/**
 * Created by PhpStorm for eshagi
 * User: vinceg
 * Date: 21/3/2021
 * Time: 20:55
 */
?>
@extends('layouts.app')

@section('template_title')
    SMS Sales Leads
@endsection

@section('content')
    <div class="page-title-box">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h4 class="page-title mb-1">Sales Leads</h4>
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{url('/leads')}}">Sales Leads</a></li>
                        <li class="breadcrumb-item active">SMS Leads</li>
                    </ol>
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
                            {!! Form::open(array('route' => 'texting.leads', 'method' => 'POST', 'role' => 'form', 'class' => 'needs-validation', 'enctype'=>'multipart/form-data')) !!}
                            {!! csrf_field() !!}

                            <div class="form-group has-feedback row {{ $errors->has('available') ? ' has-error ' : '' }}">
                                {!! Form::label('available', 'Available', array('class' => 'col-md-3 control-label')); !!}
                                <div class="col-md-9">
                                    <div class="input-group">
                                        {!! Form::text('available', $availableLeads, array('id' => 'available', 'class' => 'form-control', 'placeholder' => 'e.g. 12 000' , 'required', 'readonly')) !!}
                                    </div>
                                    @if ($errors->has('available'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('available') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group has-feedback row {{ $errors->has('count') ? ' has-error ' : '' }}">
                                {!! Form::label('count', 'Leads to SMS*', array('class' => 'col-md-3 control-label')); !!}
                                <div class="col-md-9">
                                    <div class="input-group">
                                        {!! Form::text('count', NULL, array('id' => 'count', 'class' => 'form-control', 'placeholder' => 'e.g. 200' ,'pattern'=>'^\d{1,3}*(\.\d+)?$', 'data-type'=>'currency', 'required')) !!}
                                    </div>
                                    @if ($errors->has('count'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('count') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <br>

                            {!! Form::button('SMS Leads', array('class' => 'btn btn-success margin-bottom-1 mb-1 float-right','type' => 'submit' )) !!}
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
            return n.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, "")
        }


        function formatCurrency(input, blur) {

            var input_val = input.val();

            if (input_val === "") { return; }

            var original_len = input_val.length;

            var caret_pos = input.prop("selectionStart");

            input_val = formatNumber(input_val);

            if (blur === "blur") {
                input_val;
            }

            input.val(input_val);

            var updated_len = input_val.length;
            caret_pos = updated_len - original_len + caret_pos;
            input[0].setSelectionRange(caret_pos, caret_pos);
        }
    </script>
@endsection
