@extends('layouts.app')

@section('template_title')
    {!!trans('usersmanagement.show-deleted-users')!!}

@endsection

@section('template_linked_css')
    <!-- DataTables -->
    <link href="{{url('assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{url('assets/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css')}}" rel="stylesheet" type="text/css" />

    <!-- Responsive datatable examples -->
    <link href="{{url('assets/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css')}}" rel="stylesheet" type="text/css" />
@endsection

@section('content')
    <div class="page-title-box">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h4 class="page-title mb-1">Deleted Users</h4>
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{url('/users/deleted')}}">Deleted Users</a></li>
                        <li class="breadcrumb-item active">Deleted System Users</li>
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
                            @if(count($users) === 0)

                                <tr>
                                    <p class="text-center margin-half">
                                        {!! trans('usersmanagement.no-records') !!}
                                    </p>
                                </tr>

                            @else

                                <table id="datatable-buttons" class="table table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                    <thead>
                                    <tr>
                                        <th class="hidden-xxs">National ID</th>
                                        <th>{!!trans('usersmanagement.users-table.name')!!}</th>
                                        <th class="hidden-xs hidden-sm">Email</th>
                                        <th class="hidden-xs hidden-sm hidden-md">{!!trans('usersmanagement.users-table.fname')!!}</th>
                                        <th class="hidden-xs hidden-sm hidden-md">{!!trans('usersmanagement.users-table.lname')!!}</th>
                                        <th class="hidden-xs hidden-sm">{!!trans('usersmanagement.users-table.role')!!}</th>
                                        <th class="hidden-xs">{!!trans('usersmanagement.labelDeletedAt')!!}</th>
                                        <th class="hidden-xs">{!!trans('usersmanagement.labelIpDeleted')!!}</th>
                                        <th>{!!trans('usersmanagement.users-table.actions')!!}</th>
                                    </tr>
                                    </thead>

                                    <tbody>
                                    @foreach($users as $user)
                                        <tr>
                                            <td class="hidden-xxs">{{$user->natid}}</td>
                                            <td>{{$user->name}}</td>
                                            <td class="hidden-xs hidden-sm"><a href="mailto:{{ $user->email }}" title="email {{ $user->email }}">{{ $user->email }}</a></td>
                                            <td class="hidden-xs hidden-sm hidden-md">{{$user->first_name}}</td>
                                            <td class="hidden-xs hidden-sm hidden-md">{{$user->last_name}}</td>
                                            <td class="hidden-xs hidden-sm">
                                                @foreach ($user->roles as $user_role)

                                                    @if ($user_role->name == 'User')
                                                        @php $labelClass = 'primary' @endphp

                                                    @elseif ($user_role->name == 'Supervisor')
                                                        @php $labelClass = 'info' @endphp

                                                    @elseif ($user_role->name == 'Manager')
                                                        @php $labelClass = 'success' @endphp

                                                    @elseif ($user_role->name == 'Admin')
                                                        @php $labelClass = 'danger' @endphp

                                                    @elseif ($user_role->name == 'Unverified')
                                                        @php $labelClass = 'warning' @endphp

                                                    @else
                                                        @php $labelClass = 'default' @endphp

                                                    @endif

                                                    <span class="label label-{{$labelClass}}">{{ $user_role->name }}</span>

                                                @endforeach
                                            </td>
                                            <td class="hidden-xs">{{$user->deleted_at}}</td>
                                            <td class="hidden-xs">{{$user->deleted_ip_address}}</td>
                                            <td>
                                                {!! Form::model($user, array('action' => array('App\Http\Controllers\SoftDeletesController@update', $user->id), 'method' => 'PUT', 'data-toggle' => 'tooltip','class'=>'d-inline')) !!}
                                                {!! Form::button('<i class="mdi mdi-restore" aria-hidden="true"></i>', array('class' => 'd-inline btn btn-success btn-sm', 'type' => 'submit', 'data-toggle' => 'tooltip', 'title' => 'Restore User')) !!}
                                                {!! Form::close() !!}

                                                <a class="btn btn-sm btn-info" href="{{ URL::to('users/deleted/' . $user->id) }}" data-toggle="tooltip" title="Show User">
                                                    <i class="mdi mdi-eye-outline" aria-hidden="true"></i>
                                                </a>

                                                {!! Form::model($user, array('action' => array('App\Http\Controllers\SoftDeletesController@destroy', $user->id), 'method' => 'DELETE', 'class' => 'd-inline', 'data-toggle' => 'tooltip', 'title' => 'Destroy User Record')) !!}
                                                {!! Form::hidden('_method', 'DELETE') !!}
                                                {!! Form::button('<i class="mdi mdi-trash-can-outline" aria-hidden="true"></i>', array('class' => 'd-inline btn btn-danger btn-sm','type' => 'button', 'data-toggle' => 'modal', 'data-target' => '#confirmDelete', 'data-title' => 'Delete User', 'data-message' => 'Are you sure you want to delete this user ?')) !!}
                                                {!! Form::close() !!}
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>

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
    @include('scripts.save-modal-script')
    @include('scripts.tooltips')

    <!-- Required datatable js -->
    <script src="{{url('assets/libs/datatables.net/js/jquery.dataTables.min.js')}}"></script>
    <script src="{{url('assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js')}}"></script>
    <!-- Buttons examples -->
    <script src="{{url('assets/libs/datatables.net-buttons/js/dataTables.buttons.min.js')}}"></script>
    <script src="{{url('assets/libs/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js')}}"></script>
    <script src="{{url('assets/libs/jszip/jszip.min.js')}}"></script>
    <script src="{{url('assets/libs/pdfmake/build/pdfmake.min.js')}}"></script>
    <script src="{{url('assets/libs/pdfmake/build/vfs_fonts.js')}}"></script>
    <script src="{{url('assets/libs/datatables.net-buttons/js/buttons.html5.min.js')}}"></script>
    <script src="{{url('assets/libs/datatables.net-buttons/js/buttons.print.min.js')}}"></script>
    <script src="{{url('assets/libs/datatables.net-buttons/js/buttons.colVis.min.js')}}"></script>
    <!-- Responsive examples -->
    <script src="{{url('assets/libs/datatables.net-responsive/js/dataTables.responsive.min.js')}}"></script>
    <script src="{{url('assets/libs/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js')}}"></script>

    <!-- Datatable init js -->
    <script src="{{url('assets/js/pages/datatables.init.js')}}"></script>
@endsection
