@extends('layouts.app')

@section('template_title')
    {!! trans('usersmanagement.showing-user', ['name' => $user->name]) !!}
@endsection

@php
    $levelAmount = trans('usersmanagement.labelUserLevel');
    if ($user->level() >= 2) {
      $levelAmount = trans('usersmanagement.labelUserLevels');
    }
@endphp

@section('content')
    <div class="page-title-box">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h4 class="page-title mb-1">Showing User {{$user->name}}</h4>
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{url('/users')}}">Users</a></li>
                        <li class="breadcrumb-item active">{{$user->name}}</li>
                    </ol>
                </div>

                <div class="col-md-4">
                    <div class="float-right d-none d-md-block">
                        <div>
                            <a class="btn btn-light btn-rounded" href="{{url('/users')}}" type="button">
                                <i class="mdi mdi-keyboard-backspace mr-1"></i>Back to users
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
                            <div class="row">
                                <div class="float-left col-sm-4 offset-sm-2 col-md-2 offset-md-3">
                                    <img src="@if ($user->profile && $user->profile->avatar_status == 1) {{ $user->profile->avatar }}
                                    @else {{ Gravatar::get($user->email) }}
                                    @endif" alt="{{ $user->name }}" class="img-thumbnail float-left" style="width: 200px; height: 200px;" data-holder-rendered="true">

                                </div>
                                <br>
                                <div class="col-sm-4 col-md-6">
                                    <h4 class="text-muted margin-top-sm-1 text-center text-left-tablet">
                                        {{ $user->name }}
                                    </h4>
                                    <p class="text-center text-left-tablet">
                                        <strong>
                                            {{ $user->first_name }} {{ $user->last_name }}
                                        </strong>
                                        @if($user->email)
                                            <br />
                                            <span class="text-center" data-toggle="tooltip" data-placement="top" title="{{ trans('usersmanagement.tooltips.email-user', ['user' => $user->email]) }}">
                                              {{ Html::mailto($user->email, $user->email) }}
                                            </span>
                                        @endif
                                    </p>
                                    @if ($user->profile)
                                        <div class="text-center text-left-tablet mb-4 flex-nowrap">
                                            {!! Form::open(array('url' => 'users/' . $user->id, 'class' => 'form-inline')) !!}
                                            {!! Form::hidden('_method', 'DELETE') !!}
                                            {!! Form::button('<i class="fa fa-trash-o fa-fw" aria-hidden="true"></i> <span class="hidden-xs hidden-sm hidden-md">' . trans('usersmanagement.deleteUser') . '</span>' , array('class' => 'btn btn-danger btn-sm form-inline','type' => 'button', 'data-toggle' => 'modal', 'data-target' => '#confirmDelete', 'data-title' => 'Delete User', 'data-message' => 'Are you sure you want to delete this user?')) !!}
                                            {!! Form::close() !!}

                                            <a href="{{ url('/profile/'.$user->name) }}" class="btn btn-sm btn-info" >
                                                <i class="fa fa-eye fa-fw" aria-hidden="true"></i> <span class="hidden-xs hidden-sm hidden-md"> {{ trans('usersmanagement.viewProfile') }}</span>
                                            </a>
                                            <a href="{{url('/users/'.$user->id.'/edit')}}" class="btn btn-sm btn-warning" >
                                                <i class="fas fa-user-edit" aria-hidden="true"></i> <span class="hidden-xs hidden-sm hidden-md"> {{ trans('usersmanagement.editUser') }} </span>
                                            </a>

                                        </div>
                                    @endif
                                </div>
                            </div>
                            <br>
                            <div class="clearfix"></div>
                            <div class="border-bottom"></div>

                            @if ($user->name)

                                <div class="col-sm-5 col-6 text-larger">
                                    <strong>
                                        Username
                                    </strong>
                                </div>

                                <div class="col-sm-7">
                                    {{ $user->name }}
                                </div>

                                <div class="clearfix"></div>
                                <div class="border-bottom"></div>

                            @endif

                            @if ($user->first_name)

                                <div class="col-sm-5 col-6 text-larger">
                                    <strong>
                                        {{ trans('usersmanagement.labelFirstName') }}
                                    </strong>
                                </div>

                                <div class="col-sm-7">
                                    {{ $user->first_name }}
                                </div>

                                <div class="clearfix"></div>
                                <div class="border-bottom"></div>

                            @endif

                            @if ($user->last_name)

                                <div class="col-sm-5 col-6 text-larger">
                                    <strong>
                                        {{ trans('usersmanagement.labelLastName') }}
                                    </strong>
                                </div>

                                <div class="col-sm-7">
                                    {{ $user->last_name }}
                                </div>

                                <div class="clearfix"></div>
                                <div class="border-bottom"></div>

                            @endif

                            @if ($user->natid)

                                <div class="col-sm-5 col-6 text-larger">
                                    <strong>
                                        National ID
                                    </strong>
                                </div>

                                <div class="col-sm-7">
                                    {{ $user->natid }}
                                </div>

                                <div class="clearfix"></div>
                                <div class="border-bottom"></div>

                            @endif

                            @if ($user->mobile)

                                <div class="col-sm-5 col-6 text-larger">
                                    <strong>
                                        Mobile
                                    </strong>
                                </div>

                                <div class="col-sm-7">
                                    {{ $user->mobile }}
                                </div>

                                <div class="clearfix"></div>
                                <div class="border-bottom"></div>

                            @endif

                            @if ($user->email)

                                <div class="col-sm-5 col-6 text-larger">
                                    <strong>
                                        {{ trans('usersmanagement.labelEmail') }}
                                    </strong>
                                </div>

                                <div class="col-sm-7">
                                  <span data-toggle="tooltip" data-placement="top" title="{{ trans('usersmanagement.tooltips.email-user', ['user' => $user->email]) }}">
                                    {{ HTML::mailto($user->email, $user->email) }}
                                  </span>
                                </div>

                                <div class="clearfix"></div>
                                <div class="border-bottom"></div>

                            @endif

                            <div class="col-sm-5 col-6 text-larger">
                                <strong>
                                    {{ trans('usersmanagement.labelRole') }}
                                </strong>
                            </div>

                            <div class="col-sm-7">
                                @foreach ($user->roles as $user_role)

                                    @if ($user_role->name == 'User')
                                        @php $badgeClass = 'primary' @endphp

                                    @elseif ($user_role->name == 'Supervisor')
                                        @php $badgeClass = 'info' @endphp

                                    @elseif ($user_role->name == 'Manager')
                                        @php $badgeClass = 'success' @endphp

                                    @elseif ($user_role->name == 'Admin')
                                        @php $badgeClass = 'danger' @endphp

                                    @elseif ($user_role->name == 'Unverified')
                                        @php $badgeClass = 'warning' @endphp

                                    @else
                                        @php $badgeClass = 'default' @endphp

                                    @endif

                                    <span class="badge badge-{{$badgeClass}}">{{ $user_role->name }}</span>

                                @endforeach
                            </div>

                            <div class="clearfix"></div>
                            <div class="border-bottom"></div>

                            <div class="col-sm-5 col-6 text-larger">
                                <strong>
                                    {{ trans('usersmanagement.labelStatus') }}
                                </strong>
                            </div>

                            <div class="col-sm-7">
                                @if ($user->activated == 1)
                                    <span class="badge badge-success">
                  Activated
                </span>
                                @else
                                    <span class="badge badge-danger">
                  Not-Activated
                </span>
                                @endif
                            </div>

                            <div class="clearfix"></div>
                            <div class="border-bottom"></div>

                            @if ($user->created_at)

                                <div class="col-sm-5 col-6 text-larger">
                                    <strong>
                                        {{ trans('usersmanagement.labelCreatedAt') }}
                                    </strong>
                                </div>

                                <div class="col-sm-7">
                                    {{ $user->created_at }}
                                </div>

                                <div class="clearfix"></div>
                                <div class="border-bottom"></div>

                            @endif

                            @if ($user->updated_at)

                                <div class="col-sm-5 col-6 text-larger">
                                    <strong>
                                        {{ trans('usersmanagement.labelUpdatedAt') }}
                                    </strong>
                                </div>

                                <div class="col-sm-7">
                                    {{ $user->updated_at }}
                                </div>

                                <div class="clearfix"></div>
                                <div class="border-bottom"></div>

                            @endif

                            @if ($user->signup_ip_address)

                                <div class="col-sm-5 col-6 text-larger">
                                    <strong>
                                        {{ trans('usersmanagement.labelIpEmail') }}
                                    </strong>
                                </div>

                                <div class="col-sm-7">
                                    <code>
                                        {{ $user->signup_ip_address }}
                                    </code>
                                </div>

                                <div class="clearfix"></div>
                                <div class="border-bottom"></div>

                            @endif

                            @if ($user->signup_confirmation_ip_address)

                                <div class="col-sm-5 col-6 text-larger">
                                    <strong>
                                        {{ trans('usersmanagement.labelIpConfirm') }}
                                    </strong>
                                </div>

                                <div class="col-sm-7">
                                    <code>
                                        {{ $user->signup_confirmation_ip_address }}
                                    </code>
                                </div>

                                <div class="clearfix"></div>
                                <div class="border-bottom"></div>

                            @endif

                            @if ($user->signup_sm_ip_address)

                                <div class="col-sm-5 col-6 text-larger">
                                    <strong>
                                        {{ trans('usersmanagement.labelIpSocial') }}
                                    </strong>
                                </div>

                                <div class="col-sm-7">
                                    <code>
                                        {{ $user->signup_sm_ip_address }}
                                    </code>
                                </div>

                                <div class="clearfix"></div>
                                <div class="border-bottom"></div>

                            @endif

                            @if ($user->admin_ip_address)

                                <div class="col-sm-5 col-6 text-larger">
                                    <strong>
                                        {{ trans('usersmanagement.labelIpAdmin') }}
                                    </strong>
                                </div>

                                <div class="col-sm-7">
                                    <code>
                                        {{ $user->admin_ip_address }}
                                    </code>
                                </div>

                                <div class="clearfix"></div>
                                <div class="border-bottom"></div>

                            @endif

                            @if ($user->updated_ip_address)

                                <div class="col-sm-5 col-6 text-larger">
                                    <strong>
                                        {{ trans('usersmanagement.labelIpUpdate') }}
                                    </strong>
                                </div>

                                <div class="col-sm-7">
                                    <code>
                                        {{ $user->updated_ip_address }}
                                    </code>
                                </div>

                                <div class="clearfix"></div>
                                <div class="border-bottom"></div>

                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('modals.modal-delete')
@endsection

@section('footer_scripts')
    @include('scripts.delete-modal-script')
    @if(config('usersmanagement.tooltipsEnabled'))
        @include('scripts.tooltips')
    @endif
@endsection
