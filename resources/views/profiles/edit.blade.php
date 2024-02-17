@extends('layouts.app')

@section('template_title')
    {{ trans('profile.templateTitle') }}
@endsection

@section('template_linked_css')

    <!-- Plugins css-->
    <link href="{{asset('assets/libs/dropzone/min/dropzone.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('css/select2.min.css')}}" rel="stylesheet" />
    <link href="{{asset('assets/libs/air-datepicker/css/datepicker.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('assets/libs/bootstrap-touchspin/jquery.bootstrap-touchspin.min.css')}}" rel="stylesheet" />
    <link href="{{asset('assets/libs/magnific-popup/magnific-popup.css')}}" rel="stylesheet" type="text/css" />
@endsection

@section('content')
    <div class="page-title-box">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h4 class="page-title mb-1">Edit My Profile</h4>
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Profile</a></li>
                        <li class="breadcrumb-item active">Editing {{auth()->user()->name}}</li>
                    </ol>
                </div>

                <div class="col-md-4">
                    <div class="float-right d-none d-md-block">

                    </div>
                </div>
            </div>

        </div>
    </div>

    @if (auth()->user()->hasRole('root') || auth()->user()->hasRole('admin')|| auth()->user()->hasRole('representative')||auth()->user()->hasRole('agent')||auth()->user()->hasRole('astrogent')|| auth()->user()->hasRole('manager')|| auth()->user()->hasRole('loansofficer')||auth()->user()->hasRole('salesadmin')|| auth()->user()->hasRole('partner'))
        <div class="page-content-wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-body">
                            @if (Auth::user()->id == $user->id)
                                <h4 class="header-title">{{auth()->user()->first_name.' '.auth()->user()->last_name}}'s profile</h4>
                                <p class="card-title-desc">Change my profile and account info </p>

                                <div class="row">
                                    <div class="col-sm-3">
                                        <div class="nav flex-column nav-pills" role="tablist" aria-orientation="vertical">
                                            <a class="nav-link active mb-2" id="v-pills-left-home-tab" data-toggle="pill" href="#v-pills-left-home" role="tab" aria-controls="v-pills-left-home" aria-selected="true">
                                                <i class="fas fa-home mr-1"></i> My Account
                                            </a>

                                            <a class="nav-link" id="v-pills-left-setting-tab" data-toggle="pill" href="#v-pills-left-setting" role="tab" aria-controls="v-pills-left-setting" aria-selected="false">
                                                <i class="fas fa-cog mr-1"></i> {{ trans('profile.editAccountAdminTitle') }}
                                            </a>
                                        @if(auth()->user()->hasRole('partner'))
                                            <a class="nav-link" id="v-pills-left-sign-tab" data-toggle="pill" href="#v-pills-left-signature" role="tab" aria-controls="v-pills-left-signature" aria-selected="false">
                                                <i class="mdi mdi-signature-freehand mr-1"></i> Signature
                                            </a>
                                        @endif
                                        </div>
                                    </div>
                                    <div class="col-sm-9">
                                        <div class="tab-content mt-4 mt-sm-0">

                                            <div class="tab-pane fade show active" id="v-pills-left-home" role="tabpanel" aria-labelledby="v-pills-left-home-tab">
                                                {!! Form::model($user, array('action' => array('ProfilesController@updateBackendAccount', $user->id), 'method' => 'PUT', 'id' => 'user_basics_form')) !!}

                                                {!! csrf_field() !!}

                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <div class="card-body" id="avatar_container" style="align-items: center;">

                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>First Name</label>
                                                            <input class="form-control{{ $errors->has('first_name') ? ' is-invalid' : '' }}" value="{{$user->first_name}}" type="text" name="first_name" id="first_name" required="required" placeholder="e.g. John" >
                                                            @if ($errors->has('first_name'))
                                                                <span class="invalid-feedback">
                                                                    <strong>{{ $errors->first('first_name') }}</strong>
                                                                </span>
                                                            @endif
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>Surname</label>
                                                            <input class="form-control{{ $errors->has('last_name') ? ' is-invalid' : '' }}" value="{{ $user->last_name }}" type="text" name="last_name" id="last_name" required="required" placeholder="e.g. Doe" >
                                                            @if ($errors->has('last_name'))
                                                                <span class="invalid-feedback">
                                                                <strong>{{ $errors->first('last_name') }}</strong>
                                                            </span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>System Username</label>
                                                            <input class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" value="{{$user->name}}" type="text" name="name" id="name" required="required" placeholder="e.g. jdoe" readonly>
                                                            @if ($errors->has('name'))
                                                                <span class="invalid-feedback">
                                                                    <strong>{{ $errors->first('name') }}</strong>
                                                                </span>
                                                            @endif
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>National ID</label>
                                                            <input class="form-control{{ $errors->has('natid') ? ' is-invalid' : '' }}" value="{{ $user->natid }}" type="text" name="natid" id="natid" required="required" onkeyup="validateId()" pattern="^[0-9]{2}-[0-9]{6,7}-[A-Z]-[0-9]{2}$" placeholder="e.g. 63-1234567-F-89" >
                                                            @if ($errors->has('natid'))
                                                                <span class="invalid-feedback">
                                                                <strong>{{ $errors->first('natid') }}</strong>
                                                            </span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>Email</label>
                                                            <input class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" value="{{$user->email}}" type="text" name="email" id="email" required="required" placeholder="e.g. jdoe@gmail.com" >
                                                            @if ($errors->has('email'))
                                                                <span class="invalid-feedback">
                                                                    <strong>{{ $errors->first('email') }}</strong>
                                                                </span>
                                                            @endif
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>Mobile Number</label>
                                                            <input class="form-control{{ $errors->has('mobile') ? ' is-invalid' : '' }}" value="{{ $user->mobile }}" type="number" onkeyup="validateNumber()" maxlength="10" name="mobile" id="mobile" required="required" placeholder="e.g. 778884555">
                                                            @if ($errors->has('mobile'))
                                                                <span class="invalid-feedback">
                                                                <strong>{{ $errors->first('mobile') }}</strong>
                                                            </span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-group row">
                                                    <div class="col-md-9 offset-md-3">
                                                        {!! Form::button(
                                                            '<i class="fa fa-fw fa-save" aria-hidden="true"></i> ' . trans('profile.submitProfileButton'),
                                                             array(
                                                                'class'             => 'btn btn-success disabled',
                                                                'id'                => 'account_save_trigger',
                                                                'disabled'          => true,
                                                                'type'              => 'submit',

                                                        )) !!}
                                                    </div>
                                                </div>
                                                {!! Form::close() !!}
                                            </div>

                                            <div class="tab-pane fade" id="v-pills-left-setting" role="tabpanel" aria-labelledby="v-pills-left-setting-tab">
                                                <ul class="account-admin-subnav nav nav-pills nav-justified margin-bottom-3 margin-top-1">
                                                    <li class="nav-item bg-info">
                                                        <a data-toggle="pill" href="#changepw" class="nav-link warning-pill-trigger text-white active" aria-selected="true">
                                                            {{ trans('profile.changePwPill') }}
                                                        </a>
                                                    </li>
                                                    <li class="nav-item bg-info">
                                                        <a data-toggle="pill" href="#deleteAccount" class="nav-link danger-pill-trigger text-white">
                                                            {{ trans('profile.deleteAccountPill') }}
                                                        </a>
                                                    </li>
                                                </ul>
                                                <div class="tab-content">

                                                    <div id="changepw" class="tab-pane fade show active">

                                                        <h3 class="margin-bottom-1 text-center text-warning">
                                                            {{ trans('profile.changePwTitle') }}
                                                        </h3>

                                                        {!! Form::model($user, array('action' => array('ProfilesController@updateUserPassword', $user->id), 'method' => 'PUT', 'autocomplete' => 'new-password')) !!}

                                                        <div class="pw-change-container margin-bottom-2">

                                                            <div class="form-group has-feedback row {{ $errors->has('password') ? ' has-error ' : '' }}">
                                                                {!! Form::label('password', trans('forms.create_user_label_password'), array('class' => 'col-md-3 control-label')); !!}
                                                                <div class="col-md-9">
                                                                    {!! Form::password('password', array('id' => 'password', 'class' => 'form-control ', 'placeholder' => trans('forms.create_user_ph_password'), 'autocomplete' => 'new-password')) !!}
                                                                    @if ($errors->has('password'))
                                                                        <span class="help-block">
                                                                            <strong>{{ $errors->first('password') }}</strong>
                                                                        </span>
                                                                    @endif
                                                                </div>
                                                            </div>

                                                            <div class="form-group has-feedback row {{ $errors->has('password_confirmation') ? ' has-error ' : '' }}">
                                                                {!! Form::label('password_confirmation', trans('forms.create_user_label_pw_confirmation'), array('class' => 'col-md-3 control-label')); !!}
                                                                <div class="col-md-9">
                                                                    {!! Form::password('password_confirmation', array('id' => 'password_confirmation', 'class' => 'form-control', 'placeholder' => trans('forms.create_user_ph_pw_confirmation'))) !!}
                                                                    <span id="pw_status"></span>
                                                                    @if ($errors->has('password_confirmation'))
                                                                        <span class="help-block">
                                                                            <strong>{{ $errors->first('password_confirmation') }}</strong>
                                                                        </span>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="form-group row">
                                                            <div class="col-md-9 offset-md-3">
                                                                {!! Form::button(
                                                                    '<i class="fa fa-fw fa-save" aria-hidden="true"></i> ' . trans('profile.submitPWButton'),
                                                                     array(
                                                                        'class'             => 'btn btn-warning',
                                                                        'id'                => 'pw_save_trigger',
                                                                        'disabled'          => true,
                                                                        'type'              => 'button',
                                                                        'data-submit'       => trans('profile.submitButton'),
                                                                        'data-target'       => '#confirmForm',
                                                                        'data-modalClass'   => 'modal-warning',
                                                                        'data-toggle'       => 'modal',
                                                                        'data-title'        => trans('modals.edit_user__modal_text_confirm_title'),
                                                                        'data-message'      => trans('modals.edit_user__modal_text_confirm_message')
                                                                )) !!}
                                                            </div>
                                                        </div>
                                                        {!! Form::close() !!}

                                                    </div>

                                                    <div id="deleteAccount" class="tab-pane fade">
                                                        <h3 class="margin-bottom-1 text-center text-danger">
                                                            {{ trans('profile.deleteAccountTitle') }}
                                                        </h3>
                                                        <p class="margin-bottom-2 text-center">
                                                            <i class="fa fa-exclamation-triangle fa-fw" aria-hidden="true"></i>
                                                            <strong>Deleting</strong> your account is <u><strong>permanent</strong></u> and <u><strong>cannot</strong></u> be undone.
                                                            <i class="fa fa-exclamation-triangle fa-fw" aria-hidden="true"></i>
                                                        </p>
                                                        <hr>
                                                        <div class="row">
                                                            <div class="col-sm-6 offset-sm-3 margin-bottom-3 text-center">

                                                                {!! Form::model($user, array('action' => array('ProfilesController@deleteUserAccount', $user->id), 'method' => 'DELETE')) !!}

                                                                <div class="btn-group btn-group-vertical margin-bottom-2 custom-checkbox-fa" data-toggle="buttons">
                                                                    <label class="btn no-shadow" for="checkConfirmDelete" >
                                                                        <input type="checkbox" name='checkConfirmDelete' id="checkConfirmDelete">
                                                                        <i class="fa fa-square-o fa-fw fa-2x"></i>
                                                                        <i class="fa fa-check-square-o fa-fw fa-2x"></i>
                                                                        <span class="margin-left-2"> Confirm Account Deletion</span>
                                                                    </label>
                                                                </div>

                                                                {!! Form::button(
                                                                    '<i class="fa fa-trash-o fa-fw" aria-hidden="true"></i> ' . trans('profile.deleteAccountBtn'),
                                                                    array(
                                                                        'class'             => 'btn btn-block btn-danger',
                                                                        'id'                => 'delete_account_trigger',
                                                                        'disabled'          => true,
                                                                        'type'              => 'button',
                                                                        'data-toggle'       => 'modal',
                                                                        'data-submit'       => trans('profile.deleteAccountBtnConfirm'),
                                                                        'data-target'       => '#confirmForm',
                                                                        'data-modalClass'   => 'modal-danger',
                                                                        'data-title'        => trans('profile.deleteAccountConfirmTitle'),
                                                                        'data-message'      => trans('profile.deleteAccountConfirmMsg')
                                                                    )
                                                                ) !!}

                                                                {!! Form::close() !!}

                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            @if(auth()->user()->hasRole('partner'))
                                                @php
                                                    $partner = \App\Models\Partner::where('regNumber','=',auth()->user()->natid)->first()
                                                @endphp
                                                <div class="tab-pane fade" id="v-pills-left-signature" role="tabpanel" aria-labelledby="v-pills-left-sign-tab">

                                                <div class="tab-content">
                                                    @if ($partner->partner_sign == true)
                                                        <div class="row">
                                                            <div class="col-lg-6">
                                                                <img src="{{asset('project/public/partnersign/'.$partner->signature)}}" id="uploadedID" width="80" height="80" />
                                                            </div>
                                                        </div>
                                                        <form method="post" action="{{ route('upPartnerSign') }}" enctype="multipart/form-data">
                                                            @csrf
                                                            <input type="hidden" name="regNumber" value="{{$partner->regNumber}}">
                                                            <div class="row">
                                                                <div class="col-lg-6">
                                                                    <input type="file" name="signature" id="signature" accept="image/*" />
                                                                    <button id="upId" class="btn btn-success btn-send">Upload</button>
                                                                </div>
                                                                <div class="col-lg-6">
                                                                    <img src="{{asset('images/pen.svg')}}" id="uploadedID" width="80" height="80" />
                                                                </div>
                                                            </div>
                                                        </form>
                                                    @else
                                                        <form method="post" action="{{ route('upPartnerSign') }}" enctype="multipart/form-data">
                                                            @csrf
                                                            <input type="hidden" name="regNumber" value="{{$partner->regNumber}}">
                                                            <div class="row">
                                                                <div class="col-lg-6">
                                                                    <input type="file" name="signature" id="signature" accept="image/*" required/>
                                                                    <button id="upId" class="btn btn-success btn-send">Upload</button>
                                                                </div>
                                                                <div class="col-lg-6">
                                                                    <img src="{{asset('images/pen.svg')}}" id="uploadedID" width="80" height="80" />
                                                                </div>
                                                            </div>
                                                        </form>
                                                    @endif
                                                </div>
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @else
                                <p>{{ trans('profile.notYourProfile') }}</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @else
        <div class="page-content-wrapper">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-xl-12">
                        <div class="card">
                            <div class="card-body">
                                @if (Auth::user()->id == $user->id)
                                    <h4 class="header-title">{{auth()->user()->first_name}}'s profile</h4>
                                    <p class="card-title-desc">Change my profile and account info </p>

                                    <div class="row">
                                        <div class="col-sm-3">
                                            <div class="nav flex-column nav-pills" role="tablist" aria-orientation="vertical">
                                                <a class="nav-link active mb-2" id="v-pills-left-home-tab" data-toggle="pill" href="#v-pills-left-home" role="tab" aria-controls="v-pills-left-home" aria-selected="true">
                                                    <i class="fas fa-home mr-1"></i> My Account
                                                </a>

                                                <a class="nav-link" id="v-pills-left-setting-tab" data-toggle="pill" href="#v-pills-left-setting" role="tab" aria-controls="v-pills-left-setting" aria-selected="false">
                                                    <i class="fas fa-cog mr-1"></i> {{ trans('profile.editAccountAdminTitle') }}
                                                </a>
                                            </div>
                                        </div>
                                        <div class="col-sm-9">
                                            <div class="tab-content mt-4 mt-sm-0">

                                                <div class="tab-pane fade show active" id="v-pills-left-home" role="tabpanel" aria-labelledby="v-pills-left-home-tab">
                                                    {!! Form::open(array('route' => ['updateuseraccount', $user->id], 'method' => 'PUT', 'id' => 'user_basics_form', 'enctype'=>'multipart/form-data')) !!}

                                                    {!! csrf_field() !!}

                                                    <div class="row">
                                                        <div class="col-sm-12">
                                                            <div class="card-body" id="avatar_container" style="align-items: center;">

                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label>First Name</label>
                                                                <input class="form-control{{ $errors->has('first_name') ? ' is-invalid' : '' }}" value="{{$user->first_name}}" type="text" name="first_name" id="first_name" required="required" placeholder="e.g. John" readonly>
                                                                @if ($errors->has('first_name'))
                                                                    <span class="invalid-feedback">
                                                                    <strong>{{ $errors->first('first_name') }}</strong>
                                                                </span>
                                                                @endif
                                                            </div>
                                                        </div>

                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label>Surname</label>
                                                                <input class="form-control{{ $errors->has('last_name') ? ' is-invalid' : '' }}" value="{{ $user->last_name }}" type="text" name="last_name" id="last_name" required="required" placeholder="e.g. Doe" readonly>
                                                                @if ($errors->has('last_name'))
                                                                    <span class="invalid-feedback">
                                                                <strong>{{ $errors->first('last_name') }}</strong>
                                                            </span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label>System Username</label>
                                                                <input class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" value="{{$user->name}}" type="text" name="name" id="name" required="required" placeholder="e.g. jdoe" readonly>
                                                                @if ($errors->has('name'))
                                                                    <span class="invalid-feedback">
                                                                    <strong>{{ $errors->first('name') }}</strong>
                                                                </span>
                                                                @endif
                                                            </div>
                                                        </div>

                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label>National ID</label>
                                                                <input class="form-control{{ $errors->has('natid') ? ' is-invalid' : '' }}" value="{{ $user->natid }}" type="text" name="natid" id="natid" required="required" onkeyup="validateId()" pattern="^[0-9]{2}-[0-9]{6,7}-[A-Z]-[0-9]{2}$" placeholder="e.g. 63-1234567-F-89" readonly>
                                                                @if ($errors->has('natid'))
                                                                    <span class="invalid-feedback">
                                                                <strong>{{ $errors->first('natid') }}</strong>
                                                            </span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label>Email</label>
                                                                <input class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" value="{{$user->email}}" type="text" name="email" id="email" required="required" placeholder="e.g. jdoe@gmail.com" readonly>
                                                                @if ($errors->has('email'))
                                                                    <span class="invalid-feedback">
                                                                    <strong>{{ $errors->first('email') }}</strong>
                                                                </span>
                                                                @endif
                                                            </div>
                                                        </div>

                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label>Mobile Number</label>
                                                                <input class="form-control{{ $errors->has('mobile') ? ' is-invalid' : '' }}" value="{{ $user->mobile }}" type="number" onkeyup="validateNumber()" maxlength="10" name="mobile" id="mobile" required="required" placeholder="e.g. 778884555">
                                                                @if ($errors->has('mobile'))
                                                                    <span class="invalid-feedback">
                                                                <strong>{{ $errors->first('mobile') }}</strong>
                                                            </span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label>Gross Salary</label>
                                                                <input class="form-control{{ $errors->has('gross') ? ' is-invalid' : '' }}" value="{{ $client->gross }}" pattern="^\d{1,3}*(\.\d+)?$" data-type="currency" type="text" name="gross" id="gross" required="required" placeholder="e.g. 8500.00">
                                                                @if ($errors->has('gross'))
                                                                    <span class="invalid-feedback">
                                                                <strong>{{ $errors->first('gross') }}</strong>
                                                            </span>
                                                                @endif
                                                            </div>
                                                        </div>

                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label>Net Salary</label>
                                                                <input class="form-control{{ $errors->has('net') ? ' is-invalid' : '' }}" value="{{ $client->salary }}" pattern="^\d{1,3}*(\.\d+)?$" data-type="currency" type="text" name="net" id="net" required="required" placeholder="e.g. 8500.00">
                                                                @if ($errors->has('net'))
                                                                    <span class="invalid-feedback">
                                                                <strong>{{ $errors->first('net') }}</strong>
                                                            </span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row">

                                                            <div class="col-md-6">
                                                                    <div class="row">
                                                                        <div class="col-lg-12">
                                                                            <input type="file" name="payslip" id="payslip" accept="image/*"  />
                                                                        </div>

                                                                    </div>
                                                            </div>


                                                        <div class="col-md-6">

                                                        </div>
                                                    </div>
                                                    <br>
                                                    <br>

                                                    <div class="form-group row">
                                                        <div class="col-md-9 offset-md-3">
                                                            {!! Form::button(
                                                                '<i class="fa fa-fw fa-save" aria-hidden="true"></i> ' . trans('profile.submitProfileButton'),
                                                                 array(
                                                                    'class'             => 'btn btn-success disabled',
                                                                    'id'                => 'account_save_trigger',
                                                                    'disabled'          => true,
                                                                    'type'              => 'submit',

                                                            )) !!}
                                                        </div>
                                                    </div>
                                                    {!! Form::close() !!}
                                                </div>

                                                <div class="tab-pane fade" id="v-pills-left-setting" role="tabpanel" aria-labelledby="v-pills-left-setting-tab">
                                                    <ul class="account-admin-subnav nav nav-pills nav-justified margin-bottom-3 margin-top-1">
                                                        <li class="nav-item bg-info">
                                                            <a data-toggle="pill" href="#changepw" class="nav-link warning-pill-trigger text-white active" aria-selected="true">
                                                                {{ trans('profile.changePwPill') }}
                                                            </a>
                                                        </li>
                                                        <li class="nav-item bg-info">
                                                            <a data-toggle="pill" href="#deleteAccount" class="nav-link danger-pill-trigger text-white">
                                                                {{ trans('profile.deleteAccountPill') }}
                                                            </a>
                                                        </li>
                                                    </ul>
                                                    <div class="tab-content">

                                                        <div id="changepw" class="tab-pane fade show active">

                                                            <h3 class="margin-bottom-1 text-center text-warning">
                                                                {{ trans('profile.changePwTitle') }}
                                                            </h3>

                                                            {!! Form::open(array('route' => ['updateuserpassword', $user->id], 'method' => 'PUT', 'autocomplete' => 'new-password')) !!}

                                                            <div class="pw-change-container margin-bottom-2">

                                                                <div class="form-group has-feedback row {{ $errors->has('password') ? ' has-error ' : '' }}">
                                                                    {!! Form::label('password', trans('forms.create_user_label_password'), array('class' => 'col-md-3 control-label')); !!}
                                                                    <div class="col-md-9">
                                                                        {!! Form::password('password', array('id' => 'password', 'class' => 'form-control ', 'placeholder' => trans('forms.create_user_ph_password'), 'autocomplete' => 'new-password')) !!}
                                                                        @if ($errors->has('password'))
                                                                            <span class="help-block">
                                                                            <strong>{{ $errors->first('password') }}</strong>
                                                                        </span>
                                                                        @endif
                                                                    </div>
                                                                </div>

                                                                <div class="form-group has-feedback row {{ $errors->has('password_confirmation') ? ' has-error ' : '' }}">
                                                                    {!! Form::label('password_confirmation', trans('forms.create_user_label_pw_confirmation'), array('class' => 'col-md-3 control-label')); !!}
                                                                    <div class="col-md-9">
                                                                        {!! Form::password('password_confirmation', array('id' => 'password_confirmation', 'class' => 'form-control', 'placeholder' => trans('forms.create_user_ph_pw_confirmation'))) !!}
                                                                        <span id="pw_status"></span>
                                                                        @if ($errors->has('password_confirmation'))
                                                                            <span class="help-block">
                                                                            <strong>{{ $errors->first('password_confirmation') }}</strong>
                                                                        </span>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="form-group row">
                                                                <div class="col-md-9 offset-md-3">
                                                                    {!! Form::button(
                                                                        '<i class="fa fa-fw fa-save" aria-hidden="true"></i> ' . trans('profile.submitPWButton'),
                                                                         array(
                                                                            'class'             => 'btn btn-warning',
                                                                            'id'                => 'pw_save_trigger',
                                                                            'disabled'          => true,
                                                                            'type'              => 'button',
                                                                            'data-submit'       => trans('profile.submitButton'),
                                                                            'data-target'       => '#confirmForm',
                                                                            'data-modalClass'   => 'modal-warning',
                                                                            'data-toggle'       => 'modal',
                                                                            'data-title'        => trans('modals.edit_user__modal_text_confirm_title'),
                                                                            'data-message'      => trans('modals.edit_user__modal_text_confirm_message')
                                                                    )) !!}
                                                                </div>
                                                            </div>
                                                            {!! Form::close() !!}

                                                        </div>

                                                        <div id="deleteAccount" class="tab-pane fade">
                                                            <h3 class="margin-bottom-1 text-center text-danger">
                                                                {{ trans('profile.deleteAccountTitle') }}
                                                            </h3>
                                                            <p class="margin-bottom-2 text-center">
                                                                <i class="fa fa-exclamation-triangle fa-fw" aria-hidden="true"></i>
                                                                <strong>Deleting</strong> your account is <u><strong>permanent</strong></u> and <u><strong>cannot</strong></u> be undone.
                                                                <i class="fa fa-exclamation-triangle fa-fw" aria-hidden="true"></i>
                                                            </p>
                                                            <hr>
                                                            <div class="row">
                                                                <div class="col-sm-6 offset-sm-3 margin-bottom-3 text-center">

                                                                    {!! Form::open(array('route' => ['deleteuseraccount', $user->id], 'method' => 'DELETE')) !!}

                                                                    <div class="btn-group btn-group-vertical margin-bottom-2 custom-checkbox-fa" data-toggle="buttons">
                                                                        <label class="btn no-shadow" for="checkConfirmDelete" >
                                                                            <input type="checkbox" name='checkConfirmDelete' id="checkConfirmDelete">
                                                                            <i class="fa fa-square-o fa-fw fa-2x"></i>
                                                                            <i class="fa fa-check-square-o fa-fw fa-2x"></i>
                                                                            <span class="margin-left-2"> Confirm Account Deletion</span>
                                                                        </label>
                                                                    </div>

                                                                    {!! Form::button(
                                                                        '<i class="fa fa-trash-o fa-fw" aria-hidden="true"></i> ' . trans('profile.deleteAccountBtn'),
                                                                        array(
                                                                            'class'             => 'btn btn-block btn-danger',
                                                                            'id'                => 'delete_account_trigger',
                                                                            'disabled'          => true,
                                                                            'type'              => 'button',
                                                                            'data-toggle'       => 'modal',
                                                                            'data-submit'       => trans('profile.deleteAccountBtnConfirm'),
                                                                            'data-target'       => '#confirmForm',
                                                                            'data-modalClass'   => 'modal-danger',
                                                                            'data-title'        => trans('profile.deleteAccountConfirmTitle'),
                                                                            'data-message'      => trans('profile.deleteAccountConfirmMsg')
                                                                        )
                                                                    ) !!}

                                                                    {!! Form::close() !!}

                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <p>{{ trans('profile.notYourProfile') }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{--<div class="page-content-wrapper">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-xl-12">
                                <div class="card">
                                    <div class="card-body">
                                        @if ($user->profile)
                                            @if (Auth::user()->id == $user->id)
                                                <h4 class="header-title">{{auth()->user()->first_name}}'s profile</h4>
                                                <p class="card-title-desc">Change my profile and account info </p>

                                                <div class="row">
                                                    <div class="col-sm-3">
                                                        <div class="nav flex-column nav-pills" role="tablist" aria-orientation="vertical">
                                                            <a class="nav-link active mb-2" id="v-pills-left-home-tab" data-toggle="pill" href="#v-pills-left-home" role="tab" aria-controls="v-pills-left-home" aria-selected="true">
                                                                <i class="fas fa-home mr-1"></i> My Account
                                                            </a>

                                                            <a class="nav-link" id="v-pills-left-setting-tab" data-toggle="pill" href="#v-pills-left-setting" role="tab" aria-controls="v-pills-left-setting" aria-selected="false">
                                                                <i class="fas fa-cog mr-1"></i> {{ trans('profile.editAccountAdminTitle') }}
                                                            </a>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-9">
                                                        <div class="tab-content mt-4 mt-sm-0">

                                                            <div class="tab-pane fade show active" id="v-pills-left-home" role="tabpanel" aria-labelledby="v-pills-left-home-tab">
                                                                {!! Form::model($user, array('action' => array('ProfilesController@updateUserAccount', $user->id), 'method' => 'PUT', 'id' => 'user_basics_form')) !!}

                                                                {!! csrf_field() !!}

                                                                <div class="row">
                                                                    <div class="col-sm-12">
                                                                        <div class="card-body" id="avatar_container" style="align-items: center;">
                                                                            <img src="{{asset('pphotos/'.$kyc->passport_pic)}}" alt="{{ $user->name }}" class="align-content-center card-img-top img-fluid"
                                                                                 style="width: 300px; ">
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="row">
                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <label>First Name</label>
                                                                            <input class="form-control{{ $errors->has('first_name') ? ' is-invalid' : '' }}" value="{{$client->first_name}}" type="text" name="first_name" id="first_name" required="required" placeholder="e.g. John">
                                                                            @if ($errors->has('first_name'))
                                                                                <span class="invalid-feedback">
                                                                                <strong>{{ $errors->first('first_name') }}</strong>
                                                                            </span>
                                                                            @endif
                                                                        </div>
                                                                    </div>

                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <label>Surname</label>
                                                                            <input class="form-control{{ $errors->has('last_name') ? ' is-invalid' : '' }}" value="{{ $client->last_name }}" type="text" name="last_name" id="last_name" required="required" placeholder="e.g. Doe">
                                                                            @if ($errors->has('last_name'))
                                                                                <span class="invalid-feedback">
                                                                            <strong>{{ $errors->first('last_name') }}</strong>
                                                                        </span>
                                                                            @endif
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="row">
                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <label>System Username</label>
                                                                            <input class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" value="{{$user->name}}" type="text" name="name" id="name" required="required" placeholder="e.g. jdoe">
                                                                            @if ($errors->has('name'))
                                                                                <span class="invalid-feedback">
                                                                                <strong>{{ $errors->first('name') }}</strong>
                                                                            </span>
                                                                            @endif
                                                                        </div>
                                                                    </div>

                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <label>National ID</label>
                                                                            <input class="form-control{{ $errors->has('natid') ? ' is-invalid' : '' }}" value="{{ $client->natid }}" type="text" name="natid" id="natid" required="required" onkeyup="validateId()" pattern="^[0-9]{2}-[0-9]{6,7}-[A-Z]-[0-9]{2}$" placeholder="e.g. 63-1234567-F-89">
                                                                            @if ($errors->has('natid'))
                                                                                <span class="invalid-feedback">
                                                                            <strong>{{ $errors->first('natid') }}</strong>
                                                                        </span>
                                                                            @endif
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="row">
                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <label>Email</label>
                                                                            <input class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" value="{{$user->email}}" type="text" name="email" id="email" required="required" placeholder="e.g. jdoe@gmail.com">
                                                                            @if ($errors->has('email'))
                                                                                <span class="invalid-feedback">
                                                                                <strong>{{ $errors->first('email') }}</strong>
                                                                            </span>
                                                                            @endif
                                                                        </div>
                                                                    </div>

                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <label>Mobile Number</label>
                                                                            <input class="form-control{{ $errors->has('mobile') ? ' is-invalid' : '' }}" value="{{ $client->mobile }}" type="number" onkeyup="validateNumber()" maxlength="10" name="mobile" id="mobile" required="required" placeholder="e.g. 778884555">
                                                                            @if ($errors->has('mobile'))
                                                                                <span class="invalid-feedback">
                                                                            <strong>{{ $errors->first('mobile') }}</strong>
                                                                        </span>
                                                                            @endif
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="row">
                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <label>Date of Birth</label>
                                                                            <input class="form-control{{ $errors->has('dob') ? ' is-invalid' : '' }}" data-date-format="dd-mm-yyyy" type="text" name="dob" id="dob" value="{{date_format($client->dob, 'd-m-Y')}}" placeholder="e.g Select here if you only want to change it" autocomplete="off">                                                                @if ($errors->has('dob'))
                                                                                <span class="invalid-feedback">
                                                                                <strong>{{ $errors->first('dob') }}</strong>
                                                                            </span>
                                                                            @endif
                                                                        </div>
                                                                    </div>

                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <label>Gender</label>
                                                                            <select class="form-control" id="gender" name="gender" required>
                                                                                <option value="{{ $client->gender }}">{{ $client->gender }}</option>
                                                                                <option value="Male">Male</option>
                                                                                <option value="Female">Female</option>
                                                                            </select>
                                                                            @if ($errors->has('gender'))
                                                                                <span class="invalid-feedback">
                                                                            <strong>{{ $errors->first('gender') }}</strong>
                                                                        </span>
                                                                            @endif
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="row">
                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <label>Marital State</label>
                                                                            <select class="form-control" id="marital_state" name="marital_state" required>
                                                                                <option value="{{ $client->marital_state }}">{{ $client->marital_state }}</option>
                                                                                <option value="Single">Single</option>
                                                                                <option value="Married">Married</option>
                                                                                <option value="Widowed">Widowed</option>
                                                                                <option value="Divorced">Divorced</option>
                                                                            </select>
                                                                            @if ($errors->has('marital_state'))
                                                                                <span class="invalid-feedback">
                                                                            <strong>{{ $errors->first('marital_state') }}</strong>
                                                                        </span>
                                                                            @endif
                                                                        </div>

                                                                    </div>

                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <label>Dependants</label>
                                                                            <input class="form-control {{ $errors->has('dependants') ? ' is-invalid' : '' }}" type="number" max=10 name="dependants" id="dependants" value="{{ $client->dependants}}" required="required" placeholder="Enter your dependants...">
                                                                            @if($errors->has('dependants'))
                                                                                <span class="invalid-feedback">
                                                                                <strong>{{ $errors->first('dependants') }}</strong>
                                                                            </span>
                                                                            @endif
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="row">
                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <label>Nationality</label>
                                                                            <select class="form-control" id="nationality" name="nationality" onchange="validateId1()">
                                                                                <option value="{{ $client->nationality }}">{{ $client->nationality }}</option>
                                                                                <option value="Zimbabwe">Zimbabwe</option>
                                                                                <option value="other">Other</option>
                                                                            </select>
                                                                            @if ($errors->has('nationality'))
                                                                                <span class="invalid-feedback">
                                                                <strong>{{ $errors->first('nationality') }}</strong>
                                                            </span>
                                                                            @endif
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-6" id="otherNationality">
                                                                        <div class="form-group">
                                                                            <label>Other Nationality</label>
                                                                            <input class="form-control" type="text" maxlength="20" name="nationality1" id="nationality1" placeholder="Specify your Nationality...">

                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="form-group row">
                                                                    <div class="col-md-9 offset-md-3">
                                                                        {!! Form::button(
                                                                            '<i class="fa fa-fw fa-save" aria-hidden="true"></i> ' . trans('profile.submitProfileButton'),
                                                                             array(
                                                                                'class'             => 'btn btn-success disabled',
                                                                                'id'                => 'account_save_trigger',
                                                                                'disabled'          => true,
                                                                                'type'              => 'submit',

                                                                        )) !!}
                                                                    </div>
                                                                </div>
                                                                {!! Form::close() !!}
                                                            </div>

                                                            <div class="tab-pane fade" id="v-pills-left-setting" role="tabpanel" aria-labelledby="v-pills-left-setting-tab">
                                                                <ul class="account-admin-subnav nav nav-pills nav-justified margin-bottom-3 margin-top-1">
                                                                    <li class="nav-item bg-info">
                                                                        <a data-toggle="pill" href="#changepw" class="nav-link warning-pill-trigger text-white active" aria-selected="true">
                                                                            {{ trans('profile.changePwPill') }}
                                                                        </a>
                                                                    </li>
                                                                    <li class="nav-item bg-info">
                                                                        <a data-toggle="pill" href="#deleteAccount" class="nav-link danger-pill-trigger text-white">
                                                                            {{ trans('profile.deleteAccountPill') }}
                                                                        </a>
                                                                    </li>
                                                                </ul>
                                                                <div class="tab-content">

                                                                    <div id="changepw" class="tab-pane fade show active">

                                                                        <h3 class="margin-bottom-1 text-center text-warning">
                                                                            {{ trans('profile.changePwTitle') }}
                                                                        </h3>

                                                                        {!! Form::model($user, array('action' => array('ProfilesController@updateUserPassword', $user->id), 'method' => 'PUT', 'autocomplete' => 'new-password')) !!}

                                                                        <div class="pw-change-container margin-bottom-2">

                                                                            <div class="form-group has-feedback row {{ $errors->has('password') ? ' has-error ' : '' }}">
                                                                                {!! Form::label('password', trans('forms.create_user_label_password'), array('class' => 'col-md-3 control-label')); !!}
                                                                                <div class="col-md-9">
                                                                                    {!! Form::password('password', array('id' => 'password', 'class' => 'form-control ', 'placeholder' => trans('forms.create_user_ph_password'), 'autocomplete' => 'new-password')) !!}
                                                                                    @if ($errors->has('password'))
                                                                                        <span class="help-block">
                                                                                        <strong>{{ $errors->first('password') }}</strong>
                                                                                    </span>
                                                                                    @endif
                                                                                </div>
                                                                            </div>

                                                                            <div class="form-group has-feedback row {{ $errors->has('password_confirmation') ? ' has-error ' : '' }}">
                                                                                {!! Form::label('password_confirmation', trans('forms.create_user_label_pw_confirmation'), array('class' => 'col-md-3 control-label')); !!}
                                                                                <div class="col-md-9">
                                                                                    {!! Form::password('password_confirmation', array('id' => 'password_confirmation', 'class' => 'form-control', 'placeholder' => trans('forms.create_user_ph_pw_confirmation'))) !!}
                                                                                    <span id="pw_status"></span>
                                                                                    @if ($errors->has('password_confirmation'))
                                                                                        <span class="help-block">
                                                                                        <strong>{{ $errors->first('password_confirmation') }}</strong>
                                                                                    </span>
                                                                                    @endif
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="form-group row">
                                                                            <div class="col-md-9 offset-md-3">
                                                                                {!! Form::button(
                                                                                    '<i class="fa fa-fw fa-save" aria-hidden="true"></i> ' . trans('profile.submitPWButton'),
                                                                                     array(
                                                                                        'class'             => 'btn btn-warning',
                                                                                        'id'                => 'pw_save_trigger',
                                                                                        'disabled'          => true,
                                                                                        'type'              => 'button',
                                                                                        'data-submit'       => trans('profile.submitButton'),
                                                                                        'data-target'       => '#confirmForm',
                                                                                        'data-modalClass'   => 'modal-warning',
                                                                                        'data-toggle'       => 'modal',
                                                                                        'data-title'        => trans('modals.edit_user__modal_text_confirm_title'),
                                                                                        'data-message'      => trans('modals.edit_user__modal_text_confirm_message')
                                                                                )) !!}
                                                                            </div>
                                                                        </div>
                                                                        {!! Form::close() !!}

                                                                    </div>

                                                                    <div id="deleteAccount" class="tab-pane fade">
                                                                        <h3 class="margin-bottom-1 text-center text-danger">
                                                                            {{ trans('profile.deleteAccountTitle') }}
                                                                        </h3>
                                                                        <p class="margin-bottom-2 text-center">
                                                                            <i class="fa fa-exclamation-triangle fa-fw" aria-hidden="true"></i>
                                                                            <strong>Deleting</strong> your account is <u><strong>permanent</strong></u> and <u><strong>cannot</strong></u> be undone.
                                                                            <i class="fa fa-exclamation-triangle fa-fw" aria-hidden="true"></i>
                                                                        </p>
                                                                        <hr>
                                                                        <div class="row">
                                                                            <div class="col-sm-6 offset-sm-3 margin-bottom-3 text-center">

                                                                                {!! Form::model($user, array('action' => array('ProfilesController@deleteUserAccount', $user->id), 'method' => 'DELETE')) !!}

                                                                                <div class="btn-group btn-group-vertical margin-bottom-2 custom-checkbox-fa" data-toggle="buttons">
                                                                                    <label class="btn no-shadow" for="checkConfirmDelete" >
                                                                                        <input type="checkbox" name='checkConfirmDelete' id="checkConfirmDelete">
                                                                                        <i class="fa fa-square-o fa-fw fa-2x"></i>
                                                                                        <i class="fa fa-check-square-o fa-fw fa-2x"></i>
                                                                                        <span class="margin-left-2"> Confirm Account Deletion</span>
                                                                                    </label>
                                                                                </div>

                                                                                {!! Form::button(
                                                                                    '<i class="fa fa-trash-o fa-fw" aria-hidden="true"></i> ' . trans('profile.deleteAccountBtn'),
                                                                                    array(
                                                                                        'class'             => 'btn btn-block btn-danger',
                                                                                        'id'                => 'delete_account_trigger',
                                                                                        'disabled'          => true,
                                                                                        'type'              => 'button',
                                                                                        'data-toggle'       => 'modal',
                                                                                        'data-submit'       => trans('profile.deleteAccountBtnConfirm'),
                                                                                        'data-target'       => '#confirmForm',
                                                                                        'data-modalClass'   => 'modal-danger',
                                                                                        'data-title'        => trans('profile.deleteAccountConfirmTitle'),
                                                                                        'data-message'      => trans('profile.deleteAccountConfirmMsg')
                                                                                    )
                                                                                ) !!}

                                                                                {!! Form::close() !!}

                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @else
                                                <p>{{ trans('profile.notYourProfile') }}</p>
                                            @endif
                                        @else
                                            <p>{{ trans('profile.noProfileYet') }}</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>--}}
    @endif

@endsection

@section('footer_scripts')
    @include('scripts.form-modal-script')

    @if(config('settings.googleMapsAPIStatus'))
        @include('scripts.gmaps-address-lookup-api3')
    @endif

    <script src="{{ asset('js/select2.min.js')}}"></script>

    <script type="text/javascript">
        document.getElementById("otherNationality").style.visibility = "hidden";

        function validateId1(){
            if(document.getElementById("nationality").value=="other") {
                document.getElementById("otherNationality").style.visibility = "visible";
            } else {
                document.getElementById("otherNationality").style.visibility = "hidden";
                document.getElementById("nationality1").value = document.getElementById("nationality").value;
            }
        }

        $("#kin_title").select2({
            placeholder: 'Please select your next of kin\'s title',
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

    {{--@include('scripts.user-avatar-dz')--}}
    <!-- dropzone js -->
    <script src="{{asset('assets/libs/dropzone/min/dropzone.min.js')}}"></script>

    <script>

        function validateNumber(){
            var myLength=document.getElementById("kin_number").value.length;
            var myNumber=document.getElementById("kin_number").value;
            if(myLength >=10){
                document.getElementById("kin_number").value=myNumber.substring(0, myNumber.length - 1);
            }
        }

        function validateId(){
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

    <script src="{{asset('assets/libs/air-datepicker/js/datepicker.min.js')}}"></script>
    <script src="{{asset('assets/libs/air-datepicker/js/i18n/datepicker.en.js')}}"></script>
    <script src="{{asset('assets/libs/bootstrap-touchspin/jquery.bootstrap-touchspin.min.js')}}"></script>
    <script src="{{asset('assets/libs/bootstrap-maxlength/bootstrap-maxlength.min.js')}}"></script>
    <script>

        $("#gender").select2({
            placeholder: 'Please select your gender',
            allowClear:true,
        });

        $("#marital_state").select2({
            placeholder: 'Please select your marital status',
            allowClear:true,
        });

        $("#nationality").select2({
            placeholder: 'Please select your nationality',
            allowClear:true,
        });

        $("#emp_sector").select2({
            placeholder: 'Please select your employment sector',
            allowClear:true,
        });

        $("#country").select2({
            placeholder: 'Please select your country',
            allowClear:true,
        });

        $("#home_type").select2({
            placeholder: 'Please select your House ownership status',
            allowClear:true,
        });

        $("#province").select2({
            placeholder: 'Please select your province',
            allowClear:true,
        });
    </script>

    <script type="text/javascript">

        $('.dropdown-menu li a').click(function() {
            $('.dropdown-menu li').removeClass('active');
        });

        $('.profile-trigger').click(function() {
            $('.panel').alterClass('card-*', 'card-default');
        });

        $('.settings-trigger').click(function() {
            $('.panel').alterClass('card-*', 'card-info');
        });

        $('.admin-trigger').click(function() {
            $('.panel').alterClass('card-*', 'card-warning');
            $('.edit_account .nav-pills li, .edit_account .tab-pane').removeClass('active');
            $('#changepw')
                .addClass('active')
                .addClass('in');
            $('.change-pw').addClass('active');
        });

        $('.warning-pill-trigger').click(function() {
            $('.panel').alterClass('card-*', 'card-warning');
        });

        $('.danger-pill-trigger').click(function() {
            $('.panel').alterClass('card-*', 'card-danger');
        });

        $('#user_basics_form').on('keyup change', 'input, select, textarea', function(){
            $('#account_save_trigger').attr('disabled', false).removeClass('disabled').show();
        });

        $('#user_profile_form').on('keyup change', 'input, select, textarea', function(){
            $('#confirmFormSave').attr('disabled', false).removeClass('disabled').show();
        });

        $('#checkConfirmDelete').change(function() {
            var submitDelete = $('#delete_account_trigger');
            var self = $(this);

            if (self.is(':checked')) {
                submitDelete.attr('disabled', false);
            }
            else {
                submitDelete.attr('disabled', true);
            }
        });

        $("#password_confirmation").keyup(function() {
            checkPasswordMatch();
        });

        $("#password, #password_confirmation").keyup(function() {
            enableSubmitPWCheck();
        });


        $('#password').password({
            shortPass: 'The password is too short',
            badPass: 'Weak - Try combining letters & numbers',
            goodPass: 'Medium - Try using special charecters',
            strongPass: 'Strong password',
            containsUsername: 'The password contains the username',
            enterPass: false,
            showPercent: false,
            showText: true,
            animate: true,
            animateSpeed: 50,
            username: false, // select the username field (selector or jQuery instance) for better password checks
            usernamePartialMatch: true,
            minimumLength: 6
        });

        function checkPasswordMatch() {
            var password = $("#password").val();
            var confirmPassword = $("#password_confirmation").val();
            if (password != confirmPassword) {
                $("#pw_status").html("Passwords do not match!");
            }
            else {
                $("#pw_status").html("Passwords match.");
            }
        }

        function enableSubmitPWCheck() {
            var password = $("#password").val();
            var confirmPassword = $("#password_confirmation").val();
            var submitChange = $('#pw_save_trigger');
            if (password != confirmPassword) {
                submitChange.attr('disabled', true);
            }
            else {
                submitChange.attr('disabled', false);
            }
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
    <script src="{{asset('assets/libs/magnific-popup/jquery.magnific-popup.min.js')}}"></script>
    <script src="{{asset('assets/js/pages/lightbox.init.js')}}"></script>
@endsection
