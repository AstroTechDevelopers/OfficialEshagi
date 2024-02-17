<?php
/**
 * Created by PhpStorm for eshagi
 * User: vinceg
 * Date: 28/2/2021
 * Time: 12:42
 */

$pid = \App\Models\Partner::where('regNumber',auth()->user()->natid)->first();

?>
@extends('layouts.app')

@section('template_title')
    Import Sales Representatives
@endsection

@section('template_linked_css')
    <link href="{{ asset('css/select2.min.css')}}" rel="stylesheet" />
@endsection

@section('content')
    <div class="page-title-box">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h4 class="page-title mb-1">Sales Representatives</h4>
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{url('/representatives')}}">Sales Representatives</a></li>
                        <li class="breadcrumb-item active">Upload Bulk Sales Representatives</li>
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
                            {!! Form::open(array('route' => 'imp.bulksalesreps', 'method' => 'POST', 'role' => 'form', 'class' => 'needs-validation', 'enctype'=>'multipart/form-data')) !!}
                            {!! csrf_field() !!}

                            @if(\Illuminate\Support\Facades\Auth::user()->utype != 'Partner')
                                <div class="form-group has-feedback row {{ $errors->has('partner_id') ? ' has-error ' : '' }}">
                                    {!! Form::label('partner_id', 'Partner', array('class' => 'col-md-3 control-label')); !!}
                                    <div class="col-md-9">
                                        <select class="custom-select form-control dynamic" name="partner_id" id="partner_id" required>
                                            <option value="">Select Partner</option>
                                            @if ($partners)
                                                @foreach($partners as $partner)
                                                    <option value="{{ $partner->id }}" >{{ $partner->partner_name }} </option>
                                                @endforeach
                                            @endif
                                        </select>
                                        @if ($errors->has('partner_id'))
                                            <span class="help-block">
                                            <strong>{{ $errors->first('partner_id') }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                </div>
                            @else
                                <input type="hidden" name="partner_id" id="partner_id" value="{{$pid->id}}">
                            @endif

                            <div class="form-group has-feedback row {{ $errors->has('reps_excel') ? ' has-error ' : '' }}">
                                {!! Form::label('reps_excel', 'Sales Representatives Excel File', array('class' => 'col-md-3 control-label')); !!}
                            <div class="col-md-9">
                                <div class="custom-file">
                                    <input type="file" class="form-control" id="reps_excel" name="reps_excel" required>
                                    @if ($errors->has('reps_excel'))
                                        <div class="invalid-feedback">
                                            <strong>{{ $errors->first('reps_excel') }}</strong>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            </div>
                            <br>
                            <div class="text-danger">
                                <strong>1. Please remove file headings. i.e. CREATOR, FIRSTNAME, SURNAME, EMAIL, NATIONAL ID, MOBILE, PARTNER, BRANCH
                                    <br> as illustrated: <br>
                                    <img src="{{asset('project/public/upload_reps.png')}}" alt="" style="width: 900px;">
                                </strong> <br>
                                <strong>2. Please make sure your file is in that particular order. You can leave the national ID column empty, if it was not provided, the system will replace with mobile number.</strong><br>
                                <strong>3. Please make sure the Mobile Number is present for each record.</strong><br>
                            </div>

                            {!! Form::button('Upload Sales Representatives', array('class' => 'btn btn-success margin-bottom-1 mb-1 float-right','type' => 'submit' )) !!}
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
        $("#partner_id").select2({
            placeholder: 'Please select a Partner.',
            allowClear:true,
        });
    </script>

@endsection
