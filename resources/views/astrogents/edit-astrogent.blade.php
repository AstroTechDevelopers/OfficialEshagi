<?php
/**
 * Created by PhpStorm for eshagi
 * User: vinceg
 * Date: 21/3/2021
 * Time: 17:56
 */
?>
@extends('layouts.app')

@section('template_title')
    Editing Astrogent: {{$astrogent->id}}
@endsection

@section('template_linked_css')
    <style type="text/css">
        .pw-change-container {
            display: none;
        }
    </style>

    <link href="{{ asset('css/select2.min.css')}}" rel="stylesheet" />
@endsection

@section('content')
    <div class="page-title-box">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h4 class="page-title mb-1">Modifying Astrogent {{$astrogent->name}}</h4>
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{url('/astrogents')}}">Astrogents</a></li>
                        <li class="breadcrumb-item active">Edit Astrogent</li>
                    </ol>
                </div>

                <div class="col-md-4">
                    <div class="float-right d-none d-md-block">
                        <div>
                            <a class="btn btn-light btn-rounded" href="{{url('/astrogents')}}" type="button">
                                <i class="mdi mdi-keyboard-backspace mr-1"></i>Back to astrogents
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
                            {!! Form::open(array('route' => ['astrogents.update', $astrogent->id], 'method' => 'PUT', 'role' => 'form', 'class' => 'needs-validation')) !!}

                            {!! csrf_field() !!}

                            <div class="form-group has-feedback row {{ $errors->has('title') ? ' has-error ' : '' }}">
                                {!! Form::label('title', 'Title', array('class' => 'col-md-3 control-label')); !!}
                                <div class="col-md-9">
                                    <select class="custom-select form-control dynamic" id="title" name="title" required>
                                        <option value="{{$astrogent->title}}">{{ $astrogent->title }}</option>
                                        <option value="Mr">Mr</option>
                                        <option value="Mrs">Mrs</option>
                                        <option value="Ms">Ms</option>
                                        <option value="Miss">Miss</option>
                                        <option value="Dr">Dr</option>
                                    </select>
                                    @if ($errors->has('title'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('title') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group has-feedback row {{ $errors->has('name') ? ' has-error ' : '' }}">
                                {!! Form::label('name', 'Username', array('class' => 'col-md-3 control-label')); !!}
                                <div class="col-md-9">
                                    <div class="input-group">
                                        {!! Form::text('name', $astrogent->name, array('id' => 'name', 'class' => 'form-control', 'placeholder' => 'Initial + Surname in lowercase','readonly')) !!}
                                    </div>
                                    @if ($errors->has('name'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('name') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group has-feedback row {{ $errors->has('first_name') ? ' has-error ' : '' }}">
                                {!! Form::label('first_name', trans('forms.create_user_label_firstname'), array('class' => 'col-md-3 control-label')); !!}
                                <div class="col-md-9">
                                    <div class="input-group">
                                        {!! Form::text('first_name', $astrogent->first_name, array('id' => 'first_name', 'class' => 'form-control', 'placeholder' => 'e.g. John','required')) !!}
                                    </div>
                                    @if ($errors->has('first_name'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('first_name') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group has-feedback row {{ $errors->has('last_name') ? ' has-error ' : '' }}">
                                {!! Form::label('last_name', trans('forms.create_user_label_lastname'), array('class' => 'col-md-3 control-label')); !!}
                                <div class="col-md-9">
                                    <div class="input-group">
                                        {!! Form::text('last_name', $astrogent->last_name, array('id' => 'last_name', 'class' => 'form-control', 'placeholder' => 'e.g. Doe','required')) !!}
                                    </div>
                                    @if ($errors->has('last_name'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('last_name') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group has-feedback row {{ $errors->has('gender') ? ' has-error ' : '' }}">
                                {!! Form::label('gender', 'Gender', array('class' => 'col-md-3 control-label')); !!}
                                <div class="col-md-9">
                                    <select class="custom-select form-control dynamic" id="gender" name="gender" required>
                                        <option value="{{ $astrogent->gender }}">{{ $astrogent->gender }}</option>
                                        <option value="Male">Male</option>
                                        <option value="Female">Female</option>
                                    </select>
                                    @if ($errors->has('gender'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('gender') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group has-feedback row {{ $errors->has('natid') ? ' has-error ' : '' }}">
                                {!! Form::label('natid', 'National ID', array('class' => 'col-md-3 control-label')); !!}
                                <div class="col-md-9">
                                    <div class="input-group">
                                        {!! Form::text('natid', $astrogent->natid, array('id' => 'natid', 'class' => 'form-control text-uppercase', 'pattern'=>"^[0-9]{2}-[0-9]{6,7}-[A-Z]-[0-9]{2}$", 'title'=>"ID Format should be in the form of XX-XXXXXXX-X-XX", 'placeholder' => 'e.g. Enter user National ID as it appears on user ID Card like: 63-235148177-L-22','required')) !!}
                                    </div>
                                    @if ($errors->has('natid'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('natid') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group has-feedback row {{ $errors->has('email') ? ' has-error ' : '' }}">
                                {!! Form::label('email', trans('forms.create_user_label_email'), array('class' => 'col-md-3 control-label')); !!}
                                <div class="col-md-9">
                                    <div class="input-group">
                                        {!! Form::email('email', $astrogent->email, array('id' => 'email', 'class' => 'form-control', 'placeholder' => 'e.g. jdoe@eshagi.com')) !!}
                                    </div>
                                    @if ($errors->has('email'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('email') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group has-feedback row {{ $errors->has('mobile') ? ' has-error ' : '' }}">
                                {!! Form::label('mobile', 'Mobile Number', array('class' => 'col-md-3 control-label')); !!}
                                <div class="col-md-9">
                                    <div class="input-group">
                                        {!! Form::text('mobile', $astrogent->mobile, array('id' => 'mobile', 'class' => 'form-control', 'placeholder' => 'e.g. 773418009','required')) !!}
                                    </div>
                                    @if ($errors->has('mobile'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('mobile') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group has-feedback row {{ $errors->has('address') ? ' has-error ' : '' }}">
                                {!! Form::label('address', 'Physical Address', array('class' => 'col-md-3 control-label')); !!}
                                <div class="col-md-9">
                                    <div class="input-group">
                                        {!! Form::text('address', $astrogent->address, array('id' => 'address', 'class' => 'form-control', 'placeholder' => 'e.g. 1212 Soft Rd, Belvedere, Harare','required')) !!}
                                    </div>
                                    @if ($errors->has('address'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('address') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group has-feedback row {{ $errors->has('locale_id') ? ' has-error ' : '' }}">
                                {!! Form::label('locale_id', 'Locale', array('class' => 'col-md-3 control-label')); !!}
                                <div class="col-md-9">
                                    <select class="custom-select form-control dynamic" name="locale_id" id="locale_id" required>
                                        <option value="">Select Locale</option>
                                        @if ($localels)
                                            @foreach($localels as $locale)
                                                <option value="{{ $locale->id }}" {{ $astrogent->locale == $locale->id ? 'selected="selected"' : '' }}>{{ $locale->country }}</option>

                                            @endforeach
                                        @endif
                                    </select>
                                    @if ($errors->has('locale_id'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('locale_id') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group has-feedback row {{ $errors->has('bank') ? ' has-error ' : '' }}">
                                {!! Form::label('bank', 'Bank', array('class' => 'col-md-3 control-label')); !!}
                                <div class="col-md-9">
                                    <div class="input-group">
                                        <select class="form-control" type="text" name="bank" id="bank" style="width: 100%;" >
                                            @if ($banks)
                                                @foreach($banks as $bank)
                                                    <option value="{{ $bank->id }}" {{ $astrogent->bank == $bank->id ? 'selected="selected"' : '' }}>{{ $bank->bank }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                    @if ($errors->has('bank'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('bank') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group has-feedback row {{ $errors->has('branch') ? ' has-error ' : '' }}">
                                {!! Form::label('branch', 'Branch', array('class' => 'col-md-3 control-label')); !!}
                                <div class="col-md-9">
                                    <div class="input-group">
                                        <select name="branch" id="branch" class="form-control" style="width: 100%;" >
                                            <option value="{{$astrogent->branch}}">{{$astrogent->branch}}</option>
                                        </select>
                                    </div>
                                    @if ($errors->has('branch'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('branch') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group has-feedback row {{ $errors->has('branch_code') ? ' has-error ' : '' }}">
                                {!! Form::label('branch_code', 'Branch code', array('class' => 'col-md-3 control-label')); !!}
                                <div class="col-md-9">
                                    <div class="input-group">
                                        {!! Form::text('branch_code', $astrogent->branch_code, array('id' => 'branch_code', 'class' => 'form-control', 'placeholder' => 'e.g. 00987')) !!}
                                    </div>
                                    @if ($errors->has('branch_code'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('branch_code') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group has-feedback row {{ $errors->has('accountNumber') ? ' has-error ' : '' }}">
                                {!! Form::label('accountNumber', 'Account Number', array('class' => 'col-md-3 control-label')); !!}
                                <div class="col-md-9">
                                    <div class="input-group">
                                        {!! Form::text('accountNumber', $astrogent->accountNumber, array('id' => 'accountNumber', 'class' => 'form-control', 'placeholder' => 'e.g. 1293487235')) !!}
                                    </div>
                                    @if ($errors->has('accountNumber'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('accountNumber') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="row">
                                <div class="float-right col-3">
                                    {!! Form::button(trans('forms.save-changes'), array('class' => 'btn btn-success btn-block margin-bottom-1 mt-3 mb-2 btn-save float-right','type' => 'button', 'data-toggle' => 'modal', 'data-target' => '#confirmSave', 'data-title' => trans('modals.edit_user__modal_text_confirm_title'), 'data-message' => trans('modals.edit_user__modal_text_confirm_message'))) !!}
                                </div>
                            </div>
                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('modals.modal-save')
    @include('modals.modal-delete')

@endsection

@section('footer_scripts')
    @include('scripts.delete-modal-script')
    @include('scripts.save-modal-script')

    <script src="{{ asset('js/select2.min.js')}}"></script>

    <script type="text/javascript">
        $("#title").select2({
            placeholder: 'Please select a title.',
            allowClear:true,
        });

        $("#gender").select2({
            placeholder: 'Please select a gender.',
            allowClear:true,
        });

        $("#locale_id").select2({
            placeholder: 'Please select locale.',
            allowClear:true,
        });

        $("#branch").select2({
            placeholder: 'Please select your bank branch name',
            allowClear:true,
        }).change(function(){
            var price = $(this).children('option:selected').data('price');
            $('#branch_code').val(price);
        });

        $('#bank').select2({
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
                            $("#branch").empty();
                            $.each(res,function(key, value){
                                $("#branch").append('<option value="">Please select your bank branch name</option>').append('<option value="'+value.branch+'" data-price="'+value.branch_code+'">'+value.branch+'</option>');
                            });
                        }
                    }

                });
            }
        });

    </script>

    <script>
        function formatIDNumber(){
            var myId=document.getElementById("natid").value;
            myId=myId.replace(/ /gi, "");
            myId=myId.replace(/-/gi, "");

            myId=insert(myId, "-", 2);
            myId=insert(myId, "-", myId.length-3);
            myId=insert(myId, "-", myId.length-2);

            document.getElementById("natid").value=myId;
        }

        function validateNumber(){
            var myLength=document.getElementById("mobile").value.length;
            var myNumber=document.getElementById("mobile").value;
            if(myLength >=10){
                document.getElementById("mobile").value=myNumber.substring(0, myNumber.length - 1);
            }
        }

        function insert(main_string, ins_string, pos) {
            if(typeof(pos) == "undefined") {
                pos = 0;
            }
            if(typeof(ins_string) == "undefined") {
                ins_string = '';
            }
            return main_string.slice(0, pos) + ins_string + main_string.slice(pos);
        }
    </script>

@endsection
