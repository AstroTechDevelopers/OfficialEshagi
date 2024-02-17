@extends('layouts.app')

@section('template_title')
    {{ $user->name }}'s Profile
@endsection

@section('template_fastload_css')
    #map-canvas{
    min-height: 300px;
    height: 100%;
    width: 100%;
    }
@endsection

@if (auth()->user()->hasRole('root') || auth()->user()->hasRole('admin')|| auth()->user()->hasRole('representative') || auth()->user()->hasRole('agent')|| auth()->user()->hasRole('astrogent') || auth()->user()->hasRole('manager')|| auth()->user()->hasRole('loansofficer')|| auth()->user()->hasRole('salesadmin') || auth()->user()->hasRole('partner'))
@section('content')
    <div class="page-title-box">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h4 class="page-title mb-1">My Profile Info</h4>
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Profiles</a></li>
                        <li class="breadcrumb-item active">{{ $user->name}}</li>
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
                <div class="col-xl-3">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="header-title">{{ trans('profile.showProfileTitle',['username' => $user->name]) }}</h4>

                            <div class="card-body">
                                <h4 class="card-title font-size-16 mt-0">About Me</h4>
                                <p class="card-text">{{auth()->user()->first_name}}, {{auth()->user()->last_name}}</p>
                                <p class="card-text">{{auth()->user()->natid}}</p>
                                <p class="card-text">{{auth()->user()->email}}</p>
                                <p class="card-text">+263{{auth()->user()->mobile}}</p>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="col-xl-9">
                    <div class="card">
                        <div class="card-body">
                            <dl class="user-info">
                                <dt>
                                    <strong>{{ trans('profile.showProfileUsername') }}</strong>
                                </dt>
                                <dd>
                                    {{ $user->name }}
                                </dd>
                                <hr>
                                <dt>
                                    <strong>System Unique Identiifier</strong>
                                </dt>
                                <dd>
                                    {{ $user->natid }}
                                </dd>
                                <hr>

                                    <dt>
                                        <strong>{{ trans('profile.showProfileEmail') }}</strong>
                                    </dt>
                                    <dd>
                                        {{ $user->email }}
                                    </dd>
                                <hr>
                                <dt>
                                    <strong>User Type</strong>
                                </dt>
                                <dd>
                                    {{ $user->utype }}
                                </dd>
                                <hr>
                                <dt>
                                    <strong>Added On</strong>
                                </dt>
                                <dd>
                                    {{ $user->created_at }}
                                </dd>
                                <hr>

                            </dl>
                        </div>
                    </div>

                    {!! HTML::icon_link(URL::to('/profile/'.auth()->user()->name.'/edit'), 'fa fa-fw fa-cog', trans('titles.editProfile'), array('class' => 'btn btn-small btn-info ')) !!}

                </div>

            </div>
        </div>
    </div>



@endsection

@else
@section('content')
    <div class="page-title-box">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h4 class="page-title mb-1">My Profile Info</h4>
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Profiles</a></li>
                        <li class="breadcrumb-item active">{{ $user->name}}</li>
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
                <div class="col-xl-3">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="header-title">{{ trans('profile.showProfileTitle',['username' => $user->name]) }}</h4>

                            <div class="card-body">
                                <h4 class="card-title font-size-16 mt-0">About Me</h4>
                                <p class="card-text">Name: {{auth()->user()->first_name}}, {{auth()->user()->last_name}}</p>
                                <p class="card-text">DOB: {{date_format($client->dob,'d-m-Y')}}</p>
                                <p class="card-text">National ID: {{auth()->user()->natid}}</p>
                                <p class="card-text">Mobile: +263{{auth()->user()->mobile}}</p>
                                <p class="card-text">Sector: {{$client->emp_sector}}</p>
                                <p class="card-text">Employer: {{$client->employer}}</p>
                                <p class="card-text">EC Number: {{$client->ecnumber}}</p>
                                <p class="card-text">FCB Score: {{$client->fsb_score}}</p>
                                <p class="card-text">FCB Status: {{$client->fsb_status}}</p>
                                <p class="card-text">FCB Rating: {{$client->fsb_rating}}</p>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="col-xl-9">
                    <div class="card">
                        <div class="card-body">
                            <dl class="user-info">
                                <dt>
                                    <strong>System Username</strong>
                                </dt>
                                <dd>
                                    {{ $user->name }}
                                </dd>
                                <hr>
                                <dt>
                                    <strong>System Unique Identiifier</strong>
                                </dt>
                                <dd>
                                    {{ $user->natid }}
                                </dd>
                                <hr>

                                <dt>
                                    <strong>Address</strong>
                                </dt>
                                <dd>
                                    {{ $client->house_num.' '. $client->street.', '.$client->surburb.', '.$client->city.', '.$client->country}}
                                </dd>
                                <hr>
                                <dt>
                                    <strong>Bank</strong>
                                </dt>
                                <dd>
                                    {{ $bank->bank ?? ''}}
                                </dd>
                                <hr>
                                <dt>
                                    <strong>Branch</strong>
                                </dt>
                                <dd>
                                    {{ $kyc->branch }}
                                </dd>
                                <hr><dt>
                                    <strong>Branch Code</strong>
                                </dt>
                                <dd>
                                    {{ $kyc->branch_code }}
                                </dd>
                                <hr><dt>
                                    <strong>Bank</strong>
                                </dt>
                                <dd>
                                    {{ $kyc->acc_number }}
                                </dd>
                                <hr>
                                <dt>
                                    <strong>Joined AstroCred On</strong>
                                </dt>
                                <dd>
                                    {{ $user->created_at }}
                                </dd>
                                <hr>

                            </dl>
                        </div>
                    </div>

                    {!! HTML::icon_link(URL::to('/profile/'.auth()->user()->name.'/edit'), 'fa fa-fw fa-cog', 'Edit Profile', array('class' => 'btn btn-small btn-info ')) !!}

                </div>

            </div>
        </div>
    </div>



@endsection
@endif
{{--
@section('content')
    <div class="page-title-box">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h4 class="page-title mb-1">My Profile Info</h4>
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Profiles</a></li>
                        <li class="breadcrumb-item active">{{ $user->name}}</li>
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
                <div class="col-xl-3">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="header-title">{{ trans('profile.showProfileTitle',['username' => $user->name]) }}</h4>

                            <img class="card-img-top img-fluid" src="{{asset('pphotos/'.$kyc->passport_pic)}}" alt="{{ $user->name }}">
                            <div class="card-body">
                                <h4 class="card-title font-size-16 mt-0">About Me</h4>
                                <p class="card-text">{{auth()->user()->first_name}}, {{auth()->user()->last_name}}</p>
                                <p class="card-text">{{auth()->user()->natid}}</p>
                                <p class="card-text">{{auth()->user()->email}}</p>
                                <p class="card-text">+263{{auth()->user()->mobile}}</p>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="col-xl-9">
                    <div class="card">
                        <div class="card-body">
                            <dl class="user-info">
                                <dt>
                                    <strong>{{ trans('profile.showProfileUsername') }}</strong>
                                </dt>
                                <dd>
                                    {{ $user->name }}
                                </dd>
                                <hr>
                                <dt>
                                    <strong>System Unique Identiifier</strong>
                                </dt>
                                <dd>
                                    {{ $user->natid }}
                                </dd>
                                <hr>

                                @if ($user->email && ($currentUser->id == $user->id || $currentUser->hasRole('admin')))
                                    <dt>
                                        <strong>{{ trans('profile.showProfileEmail') }}</strong>
                                    </dt>
                                    <dd>
                                        {{ $user->email }}
                                    </dd>
                                @endif
                                <hr>
                                <dt>
                                    <strong>User Type</strong>
                                </dt>
                                <dd>
                                    {{ $user->utype }}
                                </dd>
                                <hr>
                                <dt>
                                    <strong>Added On</strong>
                                </dt>
                                <dd>
                                    {{ $user->created_at }}
                                </dd>
                                <hr>

                            </dl>
                        </div>
                    </div>

                    @if ($user->profile)
                        @if ($currentUser->id == $user->id)
                            {!! HTML::icon_link(URL::to('/profile/'.$currentUser->name.'/edit'), 'fa fa-fw fa-cog', trans('titles.editProfile'), array('class' => 'btn btn-small btn-info ')) !!}
                        @endif
                    @else
                        <p>
                            {{ trans('profile.noProfileYet') }}
                        </p>
                        {!! HTML::icon_link(URL::to('/profile/'.$currentUser->name.'/edit'), 'fa fa-fw fa-plus ', trans('titles.createProfile'), array('class' => 'btn btn-small btn-info')) !!}
                    @endif
                </div>

            </div>
        </div>
    </div>


@endsection
--}}

@section('footer_scripts')

    @if(config('settings.googleMapsAPIStatus'))
        @include('scripts.google-maps-geocode-and-map')
    @endif

@endsection
